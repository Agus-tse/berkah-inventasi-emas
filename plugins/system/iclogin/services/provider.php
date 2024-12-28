<?php
/**
 *----------------------------------------------------------------------------
 * iC Login     Plugin System - iC Login
 *----------------------------------------------------------------------------
 * @version     1.0.0 2024-07-17
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

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use W3biC\Plugin\System\IcLogin\Extension\IcLogin;

return new class () implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.4.0
	 */
	public function register(Container $container): void
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$dispatcher = $container->get(DispatcherInterface::class);
				$plugin = new IcLogin(
					$dispatcher,
					(array) PluginHelper::getPlugin('system', 'iclogin')
				);
				$plugin->setApplication(Factory::getApplication());
				$plugin->setDatabase($container->get(DatabaseInterface::class));

				return $plugin;
			}
		);
	}
};
