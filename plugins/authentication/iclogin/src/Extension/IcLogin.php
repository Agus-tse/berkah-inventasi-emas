<?php
/**
 *----------------------------------------------------------------------------
 * iC Login     Plugin Authentication - iC Login
 *----------------------------------------------------------------------------
 * @version     1.0.0 2024-07-17
 *
 * @package     iC Login
 * @subpackage  Authentication.IcLogin
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2024-now Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\Authentication\IcLogin\Extension;

use Joomla\CMS\Authentication\Authentication;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\UserFactoryAwareTrait;
use Joomla\CMS\User\UserHelper;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * iC Login Authentication Plugin
 */
final class IcLogin extends CMSPlugin
{
	use DatabaseAwareTrait;
	use UserFactoryAwareTrait;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var     boolean
	 * @since   1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @param   array   &$credentials  Array holding the user credentials
	 * @param   array   $options       Array of extra options
	 * @param   object  &$response     Authentication response object
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function onUserAuthenticate(&$credentials, $options, &$response)
	{
		$response->type = 'Joomla';

		// Joomla does not like blank passwords
		if (empty($credentials['password'])) {
			$response->status        = Authentication::STATUS_FAILURE;
			$response->error_message = $this->getApplication()->getLanguage()->_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');

			return;
		}

		$emailLogin = 1;

		$plugin = PluginHelper::getPlugin('system', 'iclogin');

		if ($plugin) {
			$params     = new Registry($plugin->params);
			$emailLogin = $params->get('login_with_email', 1);
		}

		$db    = $this->getDatabase();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(['id', 'username', 'email', 'password']))
			->from($db->quoteName('#__users'));

		if ($emailLogin == 1) {
			$query->where($db->quoteName('username') . ' = :username', 'OR')
				->where($db->quoteName('email') . ' = :email')
				->bind(':username', $credentials['username'])
				->bind(':email', $credentials['username']);
		} else {
			$query->where($db->quoteName('username') . ' = :username')
				->bind(':username', $credentials['username']);
		}

		$db->setQuery($query);
		$result = $db->loadObject();

		if ($result) {
			$credentials['username'] = $result->username;

			$match = UserHelper::verifyPassword($credentials['password'], $result->password, $result->id);

			if ($match === true) {
				// Bring this in line with the rest of the system
				$user               = $this->getUserFactory()->loadUserById($result->id);
				$response->email    = $user->email;
				$response->fullname = $user->name;

				// Set default status response to success
				$_status       = Authentication::STATUS_SUCCESS;
				$_errorMessage = '';

				if ($this->getApplication()->isClient('administrator')) {
					$response->language = $user->getParam('admin_language');
				} else {
					$response->language = $user->getParam('language');

					if ($this->getApplication()->get('offline') && !$user->authorise('core.login.offline')) {
						// User do not have access in offline mode
						$_status       = Authentication::STATUS_FAILURE;
						$_errorMessage = $this->getApplication()->getLanguage()->_('JLIB_LOGIN_DENIED');
					}
				}

				$response->status        = $_status;
				$response->error_message = $_errorMessage;

				// Stop event propagation when status is STATUS_SUCCESS
				if ($response->status === Authentication::STATUS_SUCCESS) {
					$response->username = $credentials['username'];
				}
			} else {
				// Invalid password
				$response->status        = Authentication::STATUS_FAILURE;
				$response->error_message = $this->getApplication()->getLanguage()->_('JGLOBAL_AUTH_INVALID_PASS');
			}
		} else {
			// Let's hash the entered password even if we don't have a matching user for some extra response time
			// By doing so, we mitigate side channel user enumeration attacks
			UserHelper::hashPassword($credentials['password']);

			// Invalid user
			$response->status        = Authentication::STATUS_FAILURE;
			$response->error_message = $this->getApplication()->getLanguage()->_('JGLOBAL_AUTH_NO_USER');
		}
	}
}
