<?php
/**
 *----------------------------------------------------------------------------
 * iC Login     Extends Joomla's default User authentication.
 *----------------------------------------------------------------------------
 * @version     1.0.0 2024-07-25
 *
 * @package     iC Login
 * @subpackage  Package.IcLogin
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2024-now Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
 */

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Cache\Cache;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;

/**
 * iC Login Installer Script
 */
class Pkg_iCloginInstallerScript
{
	protected $ictype = 'core';

	/**
	 * The minimum PHP version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumPHPVersion = '7.2.0';

	/**
	 * The minimum Joomla! version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumJoomlaVersion = '4.4.0';

	/**
	 * The maximum Joomla! version this extension can be installed on
	 *
	 * @var   string
	 */
	protected $maximumJoomlaVersion = '5.3.99';

	/**
	 * A list of extensions (modules, plugins) to enable after installation. Each item has four values, in this order:
	 * type (plugin, module, ...), element (system name for the extension), client (0=site, 1=admin), group (for plugins).
	 *
	 * @var array
	 */
	protected $extensionsToEnable = [
		// Plugins
		['plugin', 'iclogin', 0, 'authentication'],
		['plugin', 'iclogin', 0, 'system'],
	];

	/**
	 * A list of extensions (library, modules, plugins) installed in this package. Each item has five values, in this order:
	 * type (plugin, module, ...), element (of the extension), client (0=site, 1=admin), group (for plugins), name (of the extension).
	 *
	 * @var array
	 */
	protected $packageExtensions = [
		// Plugins
		['plugin', 'iclogin', 0, 'authentication', 'PKG_ICLOGIN_PLUGIN_AUTHENTICATION_ICLOGIN'],
		['plugin', 'iclogin', 0, 'system', 'PKG_ICLOGIN_PLUGIN_SYSTEM_ICLOGIN'],
	];

	/**
	 * Like above, but enable these extensions on installation OR update. Use this sparingly. It overrides the
	 * preferences of the user. Ideally, this should only be used for installer plugins.
	 *
	 * @var array
	 */
	protected $extensionsToAlwaysEnable = [];

	protected $installedExtensions = [];

	/**
	 * Constructor
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function __construct(InstallerAdapter $adapter)
	{
	}
	
	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($route, InstallerAdapter $adapter)
	{
		// Do not run on uninstall.
		if ($route === 'uninstall') {
			return true;
		}

		// Check the minimum PHP version.
		if ( ! version_compare(PHP_VERSION, $this->minimumPHPVersion, 'ge')) {
			$msg = '<p><strong>' . Text::sprintf('PKG_ICLOGIN_WARNING_MINIMUM_PHP', $this->minimumPHPVersion) . '</strong></p>';
			Log::add($msg, Log::WARNING, 'error');

			return false;
		}

		// Check the minimum Joomla! version.
		if ( ! version_compare(JVERSION, $this->minimumJoomlaVersion, 'ge')) {
			$msg = '<p><strong>' . Text::sprintf('PKG_ICLOGIN_WARNING_MINIMUM_JOOMLA', $this->minimumJoomlaVersion) . '</strong></p>';
			Log::add($msg, Log::WARNING, 'error');

			return false;
		}

		// Check the maximum Joomla! version.
		if ( ! version_compare(JVERSION, $this->maximumJoomlaVersion, 'le')) {
			$msg = '<p><strong>' . Text::sprintf('PKG_ICLOGIN_WARNING_MAXIMUM_JOOMLA', $this->maximumJoomlaVersion) . '</strong></p>';
			Log::add($msg, Log::WARNING, 'error');

			return false;
		}

		$this->checkInstalled();

		return true;
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($route, InstallerAdapter $adapter)
	{
		// Do not run on uninstall.
		if ($route === 'uninstall') {
			return;
		}

		$this->cleanJoomlaCache();

		// Force-create the autoload_psr4.php file again.
		if (class_exists(JNamespacePsr4Map::class)) {
			try {
				$nsMap = new JNamespacePsr4Map();

				@clearstatcache(JPATH_CACHE . '/autoload_psr4.php');

				if (function_exists('opcache_invalidate')) {
					@opcache_invalidate(JPATH_CACHE . '/autoload_psr4.php');
				}

				@clearstatcache(JPATH_CACHE . '/autoload_psr4.php');
				$nsMap->create();

				if (function_exists('opcache_invalidate')) {
					@opcache_invalidate(JPATH_CACHE . '/autoload_psr4.php');
				}

				$nsMap->load();
			} catch (\Throwable $e) {
				// In case of failure, just try to delete the old autoload_psr4.php file
				if (function_exists('opcache_invalidate')) {
					@opcache_invalidate(JPATH_CACHE . '/autoload_psr4.php');
				}

				@unlink(JPATH_CACHE . '/autoload_psr4.php');
				@clearstatcache(JPATH_CACHE . '/autoload_psr4.php');

				Factory::getApplication()->createExtensionNamespaceMap();
			}
		}

		$this->displayMessages($route, $adapter);

		// If the namespace mapping file is not yet updated,
		// we need to slow down the installation process so that it can be regenerated.
		// This happens if the server takes longer to write the autoload_psr4 file
		// than the installation takes to complete.
		if (!class_exists('W3biC\Plugin\System\IcLogin\Extension\IcLogin')) {
			sleep(3);
//			if (!$this->namespacesMapCreated()) {
//				$this->disableExtension('plugin', 'iclogin', 0, 'system');
//			}
		}

		return true;
	}

	/**
	 * Called on installation
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(InstallerAdapter $adapter)
	{
		// Enable the extensions we need to enable on install.
		$this->enableExtensions();

		return true;
	}

	/**
	 * Called on update
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(InstallerAdapter $adapter)
	{
		// Always enable these extensions
		if (isset($this->extensionsToAlwaysEnable) && !empty($this->extensionsToAlwaysEnable)) {
			$this->enableExtensions($this->extensionsToAlwaysEnable);
		}

		return true;
	}

	/**
	 * Called on uninstallation
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function uninstall(InstallerAdapter $adapter)
	{
		return true;
	}

	/**
	 * Display Messages after successful installation
	 */
	private function displayMessages($route, InstallerAdapter $adapter) {
		// Get manifest file version
		$release = $adapter->getManifest()->version;

		$msg = '<div style="background: var(--body-bg); padding: 2rem; border-radius: .2rem;">';

		$msg.= '<p>';
		$msg.= '<div style="float: left; margin-right: 30px;">';
		$msg.= '<img src="../media/plg_system_iclogin/images/Logo-iCLogin-160.png" />';
		$msg.= '</div>';

		$msg.= '<span style="letter-spacing: .0625rem; font-size: 1rem; font-weight: 300;">'
			. Text::_('PKG_ICLOGIN_WELCOME')
			. '</span>'
			. '<br>';

		$isPro = $this->ictype == 'pro' ? strtoupper($this->ictype) : '';

		if ($route == 'install') {
			$installText = Text::sprintf('PKG_ICLOGIN_FIRST_INSTALL', '<strong>iC Login' . ($isPro ? ' ' . $isPro : '') . '</strong> ' . $release);
			$msg.= '<span style="font-size: 1.25rem; font-weight: 500;">' . $installText . '</span>'
				. '<br><br>';
		}

		// Extension Update
		if ($route == 'update') {
			$updateText = Text::sprintf('PKG_ICLOGIN_UPDATED_TO_VERSION', '<strong>iC Login' . ($isPro ? ' ' . $isPro : '') . '</strong>', $release);
			$msg.= '<span style="font-size: 1.25rem; font-weight: 500;">' . $updateText . '</span>'
				. '<br><br>';
		}

		$msg.= '<div>';
		$msg.= Text::_('PKG_ICLOGIN_FEATURES') . '<br>';
		$msg.= Text::_('PKG_ICLOGIN_FEATURES_PRO');
		$msg.= '</div>';

		$msg.= '</p><br>';

		$pluginSystemID = $this->extensionIsInstalled('plugin', 'iclogin', 'system');

		if ($pluginSystemID) {
			$msg.= '<a class="btn text-bg-success" href="index.php?option=com_plugins&task=plugin.edit&extension_id==' . (int) $pluginSystemID . '">'
				. '<span class="icon icon-options" aria-hidden="true"></span>&nbsp;' . Text::sprintf('PKG_ICLOGIN_BUTTON_SET_OPTIONS', Text::_('ICLOGIN'))
				. '</a>';
		}

		$msg.= '<div style="clear: both"></div>';

		$msg.= '<hr>';

		$msg.= '<strong>' . Text::_('PKG_ICLOGIN_EXTENSIONS_' . strtoupper($route) . '_LABEL') . '</strong>';
		$msg.= $this->postMessages();

		$msg.= '<hr>';

		$msg.= '<style>a[target="_blank"]::before { font-size: 1em; }</style>';
		$msg.= '<span class="small fw-bold">iC Login &#8226; <a href="https://www.joomlic.com/extensions/iclogin" rel="noopener noreferrer" target="_blank">www.joomlic.com</a></span>';

		$msg.= '</div>';

		echo $msg;
	}

	/**
	 * Enable modules and plugins after installing them
	 */
	private function enableExtensions($extensions = [])
	{
		if (empty($extensions)) {
			$extensions = $this->extensionsToEnable;
		}

		foreach ($extensions as $ext) {
			$this->enableExtension($ext[0], $ext[1], $ext[2], $ext[3]);
		}
	}

	/**
	 * Enable an extension
	 *
	 * @param   string   $type    The extension type.
	 * @param   string   $name    The name of the extension (the element field).
	 * @param   integer  $client  The application id (0: Joomla CMS site; 1: Joomla CMS administrator).
	 * @param   string   $group   The extension group (for plugins).
	 */
	private function enableExtension($type, $name, $client = 1, $group = null)
	{
		try {
			$db = Factory::getDbo();

			$query = $db->getQuery(true)
				->update('#__extensions')
				->set($db->qn('enabled') . ' = ' . $db->q(1))
				->where('type = ' . $db->quote($type))
				->where('element = ' . $db->quote($name));
		} catch (\Exception $e) {
			return;
		}

		switch ($type) {
			case 'plugin':
				// Plugins have a folder but not a client
				$query->where('folder = ' . $db->quote($group));
				break;

			case 'language':
			case 'module':
			case 'template':
				// Languages, modules and templates have a client but not a folder
				$query->where('client_id = ' . (int) $client);
				break;

			default:
			case 'library':
			case 'package':
			case 'component':
				break;
		}

		try {
			$db->setQuery($query);
			$db->execute();
//			$this->installedExtensions[] = [$type, $name, $client, $group, 1];
		} catch (\Exception $e) {
//			$this->installedExtensions[] = [$type, $name, $client, $group, 0];
			// Nothing
		}
	}

	/**
	 * Disable an extension
	 *
	 * @param   string   $type    The extension type.
	 * @param   string   $name    The name of the extension (the element field).
	 * @param   integer  $client  The application id (0: Joomla CMS site; 1: Joomla CMS administrator).
	 * @param   string   $group   The extension group (for plugins).
	 */
	private function disableExtension($type, $name, $client = 1, $group = null)
	{
		try {
			$db = Factory::getDbo();

			$query = $db->getQuery(true)
				->update('#__extensions')
				->set($db->qn('enabled') . ' = ' . $db->q(0))
				->where('type = ' . $db->quote($type))
				->where('element = ' . $db->quote($name));
		} catch (\Exception $e) {
			return;
		}

		switch ($type) {
			case 'plugin':
				// Plugins have a folder but not a client
				$query->where('folder = ' . $db->quote($group));
				break;

			case 'language':
			case 'module':
			case 'template':
				// Languages, modules and templates have a client but not a folder
				$query->where('client_id = ' . (int) $client);
				break;

			default:
			case 'library':
			case 'package':
			case 'component':
				break;
		}

		try {
			$db->setQuery($query);
			$db->execute();
		} catch (\Exception $e) {
			// Nothing
		}
	}

	/**
	 * Check if extension installed and set extension_id to packageExtensions array.
	 */
	private function checkInstalled()
	{
		foreach ($this->packageExtensions as $k => $ext) {
			$extension_id = $this->extensionIsInstalled($ext[0], $ext[1], $ext[3]);

			$this->packageExtensions[$k][5] = $extension_id;

			if ( ! $extension_id) {
				$this->extensionsToAlwaysEnable[] = [$ext[0], $ext[1], $ext[2], $ext[3]];
			}
		}
	}

	/**
	 * Check if extension is installed
	 *
	 * @param   string   $type          The extension type.
	 * @param   string   $element       The element field.
	 * @param   string   $group         The extension group (for plugins).
	 */
	private function extensionIsInstalled($type, $element, $group = null)
	{
		try {
			$db = Factory::getDbo();

			$query = $db->getQuery(true)
				->select('e.extension_id')
				->from('`#__extensions` AS e')
				->where('type = ' . $db->quote($type))
				->where('element = ' . $db->quote($element));
		} catch (\Exception $e) {
			return;
		}

		switch ($type) {
			case 'component':
				break;

			case 'language':
			case 'library':
				break;

			case 'module':
				break;

			case 'plugin':
				// Plugins have a folder but not a client
				$query->where('folder = ' . $db->quote($group));
				break;

			default:
			case 'template':
			case 'package':
				break;
		}

		try {
			$db->setQuery($query);
			$extension_id = $db->loadResult();
		} catch (\Exception $e) {
			// Nothing
		}

		return $extension_id;
	}

	/**
	 * Check and set extension message
	 */
	private function postMessages($extensions = [])
	{
		if (empty($extensions)) {
			$extensions = $this->packageExtensions;
		}

		$messages = '';

		foreach ($extensions as $ext) {
			$extension_id = isset($ext[5]) ? $ext[5] : null;
			$messages.= $this->postMessage($ext[0], $ext[1], $ext[2], $ext[3], $ext[4], $extension_id);
		}

		return $messages;
	}

	/**
	 * Set extension message
	 *
	 * @param   string   $type          The extension type.
	 * @param   string   $element       The element field.
	 * @param   string   $client        0=site, 1=admin.
	 * @param   string   $group         The extension group (for plugins).
	 * @param   string   $name          The name of the extension (the title).
	 * @param   boolean  $extension_id  The extension id (if already installed).
	 */
	private function postMessage($type, $element, $client, $group, $name, $extension_id = null)
	{
		$labelClass = '';

		switch ($type) {
			case 'component':
				$labelClass = 'badge text-bg-success';
				break;

			case 'file':
				$labelClass = 'badge text-bg-secondary';
				break;

			case 'language':
			case 'library':
				$labelClass = 'badge text-bg-warning';
				break;

			case 'module':
				$labelClass = 'badge text-bg-danger';
				break;

			case 'plugin':
				$labelClass = 'badge text-bg-primary';
				break;

			case 'template':
				$labelClass = 'badge text-bg-info';
				break;

			default:
			case 'package':
				$labelClass = 'badge text-bg-light';
				break;
		}

		$extensionName  = '<strong>' . Text::_($name) . '</strong>';
		$extensionBadge = '<span class="' . $labelClass . '">&nbsp;' . ucfirst(Text::_('COM_INSTALLER_TYPE_TYPE_PLUGIN')) . '&nbsp;</span>';
		$proBadge       = ($this->ictype === 'pro' && $group === 'system') ? ' <span class="badge text-bg-danger">' . strtoupper($this->ictype) . '</span>' : '';

		if ($extension_id) {
			$extensionLabel = '&nbsp;&nbsp;<small>' . Text::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', Text::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($type)) . ' ' . $extensionName . $proBadge) . '</small>';
			$msg = '<div>' . $extensionBadge . $extensionLabel . '</div>';
		} else {
			$extensionLabel = '&nbsp;&nbsp;<small>' . Text::sprintf('COM_INSTALLER_INSTALL_SUCCESS', Text::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($type)) . ' ' . $extensionName . $proBadge) . '</small>';
			$msg = '<div>' . $extensionBadge . $extensionLabel
				. ' &#8680; <span class="text-success"><strong>' . Text::_('JENABLED') . '</strong></span></div>';
		}

		return $msg;
	}

	/**
	 * This method clean the Joomla Cache using the method `clean` from the com_cache model
	 *
	 * @return  void
	 */
	private function cleanJoomlaCache()
	{
		/** @var \Joomla\Component\Cache\Administrator\Model\CacheModel $model */
		$model = Factory::getApplication()->bootComponent('com_cache')->getMVCFactory()
			->createModel('Cache', 'Administrator', ['ignore_request' => true]);

		// Clean frontend cache
		$model->clean();

		// Clean admin cache
		$model->setState('client_id', 1);
		$model->clean();
	}
}
