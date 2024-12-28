<?php
/**
 *----------------------------------------------------------------------------
 * iC Login     Plugin System - iC Login
 *----------------------------------------------------------------------------
 * @version     1.0.0 2024-07-21
 *
 * @package     iC Login
 * @subpackage  System.IcLogin
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2024-now Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\System\IcLogin\Extension;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\Module\Login\Site\Helper\LoginHelper;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * iC Login System Plugin
 */
final class IcLogin extends CMSPlugin implements SubscriberInterface
{
	use DatabaseAwareTrait;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var     boolean
	 * @since   1.0.0
	 */
	protected $autoloadLanguage = true;

	public static function getSubscribedEvents(): array
	{ 
		return [
			'onBeforeRender' => 'onBeforeRender',
			'onAfterRoute'   => 'handlePasswordReset',
		];
	}

	/**
	 * Utilities before the page has fully rendered.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onBeforeRender(): void
	{
		$app = $this->getApplication();

		if ($app->isClient('administrator')) {
			$app->getDocument()->addStyleDeclaration('
				.icbeta::after {
					content: "BETA";
					margin-left: .4rem;
					padding: .2rem .3rem;
					font-size: .75rem;
					font-weight: 700;
					color: black;
					background: #ffb514;
					border-radius: .2rem;
				}
			');

			return;
		}
	}

	/**
	 * This method should handle any reset password and report back to the subject.
	 * Allows to use Email as well as Username to process Password reset.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function handlePasswordReset(): void
	{
		if ($this->getApplication()->isClient('administrator')) {
			return;
		}

		$component = $this->getApplication()->getInput()->get('option');

		if ($component !== 'com_users') {
			return;
		}

		$emailLogin    = $this->params->get('login_with_email', 1);
		$passwordReset = $this->params->get('password_reset', 0);
		$resetMethod   = ($emailLogin && $passwordReset) ?? false;

		if (!$resetMethod) {
			return;
		}

		$task = $this->getApplication()->getInput()->getCmd('task');

		if ($task === 'reset.request') {
			$jform = $this->getApplication()->input->get('jform', [], 'array');

			if (count($jform)) {
				$db = $this->getDatabase();

				$query = $db->getQuery(true);
				$query->select($db->quoteName('email'))
					->from($db->quoteName('#__users'))
					->where('username = ' . $db->quote($jform['email']));
				$db->setQuery($query);

				$email = $db->loadResult();

				if ($email !== null) {
					$jform['email'] = $email;
					$this->getApplication()->input->set('jform', $jform);
				}
			}
		}

		if ($task === 'reset.confirm') {
			$jform = $this->getApplication()->input->get('jform', [], 'array');

			if (count($jform) && preg_match('/@/', $jform['username'])) {
				$db = $this->getDatabase();

				$query = $db->getQuery(true);
				$query->select($db->quoteName('username'))
					->from($db->quoteName('#__users'))
					->where('LOWER(email) = LOWER(' . $db->quote($jform['username']) . ')')
					->where($db->quoteName('block') . ' = 0');
				$db->setQuery($query);

				$username = $db->loadResult();

				if ($username !== null) {
					$jform['username'] = $username;
					$this->getApplication()->input->set('jform', $jform);
				}
			}
		}
	}
}
