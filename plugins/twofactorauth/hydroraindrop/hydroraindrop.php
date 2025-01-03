<?php
declare (strict_types = 1);

/**
 * @package     Joomla.Plugin
 * @subpackage  Twofactorauth.hydroraindrop
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Require composer autoloader if installed on it's own
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
	require_once $composer;
}

use Adrenth\Raindrop\ApiSettings;
use Adrenth\Raindrop\Client;
use Adrenth\Raindrop\ApiAccessToken;
use Adrenth\Raindrop\Environment\ProductionEnvironment;
use Adrenth\Raindrop\Environment\SandboxEnvironment;
use Adrenth\Raindrop\Exception\UnableToAcquireAccessToken;
use Adrenth\Raindrop\TokenStorage\TokenStorage;
use Adrenth\Raindrop\Exception\RegisterUserFailed;
use Adrenth\Raindrop\Exception\UserAlreadyMappedToApplication;
use Adrenth\Raindrop\Exception\VerifySignatureFailed;
use Adrenth\Raindrop\Exception\UnregisterUserFailed;
use Joomla\CMS\Crypt\Cipher\SimpleCipher;
use Joomla\CMS\Crypt\Key;

require_once __DIR__ . '/hydro-raindrop-token.php';

/**
 * Joomla! Two Factor Authentication using Hydro Raindrop Plugin
 *
 * @since  3.2
 */
final class PlgTwofactorauthHydroraindrop extends JPlugin
{
	const COOKIE_NAME = 'HydroRaindropMfa';

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.2
	 */
	protected $autoloadLanguage = true;

	/**
	 * Method name
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $methodName = 'hydroraindrop';

	/**
	 * Client
	 *
	 * @var    object
	 */
	protected $client;

	/**
	 * validConfig
	 *
	 * @var    bool
	 */
	protected $validConfig;

	/**
	 * Application
	 *
	 * @var    JApplication
	 */
	protected $app;

	/**
	 * Session
	 *
	 * @var    JSession
	 */
	protected $session;

	/**
	 * User
	 *
	 * @var    JUser
	 */
	protected $user;

	/**
	 * Token Storage
	 *
	 * @var    Hydro_Raindrop_TokenStorage
	 */
	protected $token_storage;

	/**
	 * Lets create the class
	 */
	public function __construct($subject, $config)
	{
		parent::__construct($subject, $config);
		// Link the app first
		$this->app = JFactory::getApplication();
		// Store some stuffs we need
		$this->session = JFactory::getSession();
		$this->user = JFactory::getUser();

		// Get the config and parse it
		$config = json_decode($config['params'], true);
		// Validate the config
		if (!$this->validateHydroRaindropConfig($config))
			return;
		$this->validConfig = true;

		$this->token_storage = new Hydro_Raindrop_TokenStorage;
		// Setup the client
		$this->client = new Client(
			new ApiSettings(
				$config['client_id'],
				$config['client_secret'],
				$config['environment'] === 'sandbox'
					? new SandboxEnvironment()
					: new ProductionEnvironment()
			),
			$this->token_storage,
			$config['application_id']
		);

		// Load the helper and model used for two factor authentication
		JLoader::register('UsersModelUser', JPATH_ADMINISTRATOR . '/components/com_users/models/user.php');
		JLoader::import('joomla.filesystem.file');
	}

	/**
	 * Log an item to the error log
	 *
	 * @var		string The message
	 * @var		string The type of message
	 */
	private function log($message, $type = 'error')
	{
		switch ($type) {
			case 'error':
				$type = JLog::ERROR;
			case 'debug':
				$type = JLog::DEBUG;
			case 'warning':
				$type = JLog::WARNING;
			case 'info':
				$type = JLog::INFO;
			default:
				return;
		}
		JLog::add($message, $type, 'hydro-raindrop');
	}

	/**
	 * Display an error to the user
	 *
	 * @var		string The message
	 * @var		string The type of message
	 */
	private function enqueue($message, $type = 'error')
	{
		$this->app->enqueueMessage(JText::_($message), $type);
	}

	/**
	 * Validate the hydro raindrop configuration
	 *
	 * @var    array
	 */
	private function validateHydroRaindropConfig($config)
	{
		$options = [
			'client_id',
			'client_secret',
			'environment',
			'application_id',
		];
		foreach ($options as $option) {
			$value = isset($config[$option]) ? $config[$option] : '';
			if (empty($value)) {
				return false;
			}
		}
		if (!$this->is_ssl()) {
			if ($this->user->authorise('core.admin'))
				$this->app->enqueueMessage(JText::_('PLG_TWOFACTORAUTH_HYDRORAINDROP_SSL_WARNING'), 'warning');
			$this->clean(true);
			return false;
		}
		return true;
	}

	/**
	 * This method returns the identification object for this two factor
	 * authentication plugin.
	 *
	 * @return  stdClass  An object with public properties method and title
	 *
	 * @since   3.2
	 */
	public function onUserTwofactorIdentify()
	{
		if (!$this->validConfig || !$this->isActiveSection() || $this->user->guest) {
			return false;
		}
		
		return (object)array(
			'method' => $this->methodName,
			'title' => JText::_('PLG_TWOFACTORAUTH_HYDRORAINDROP_METHOD_TITLE')
		);
	}

	/**
	 * This method returns if the saved section matches the current section.
	 *
	 * @return  bool
	 */
	public function isActiveSection()
	{
		$section = (int)$this->params->get('section', 3);

		$current_section = 0;

		try {
			if ($this->app->isClient('administrator')) {
				$current_section = 2;
			} elseif ($this->app->isClient('site')) {
				$current_section = 1;
			}
		} catch (Exception $exc) {
			$current_section = 0;
		}

		if (!($current_section & $section)) {
			return false;
		}

		return true;
	}

	/**
	 * Called after the user has logged in.
	 *
	 * @return  bool
	 */
	public function onUserAfterLogin(array $options)
	{
		if (!$this->validConfig) {
			return false;
		}
		$model = new UsersModelUser;
		$user = $options['user'];
		$otp = $model->getOtpConfig($user->id);
		if ($otp && isset($otp->config['hydro_id'])) {
			$this->clean();
			$this->session->set('id', $otp->config['hydro_id'], 'hydro_raindrop');
			$this->session->set('confirmed', $otp->config['hydro_raindrop_confirmed'], 'hydro_raindrop');
			$this->session->set('reauthenticate', true, 'hydro_raindrop');
			$this->session->set('key', $otp->config['hydro_key'], 'hydro_raindrop');
		}
		return true;
	}

	/**
	 * Called after the the site has finished routing
	 *
	 * @return void
	 */
	public function onAfterRoute() {
		$this->need_unregister();
		$this->showMfa();
	}

	/**
	 * Shows the configuration page for this two factor authentication method.
	 *
	 * @param   object   $otpConfig  The two factor auth configuration object
	 * @param   integer  $user_id    The numeric user ID of the user whose form we'll display
	 *
	 * @return  boolean|string  False if the method is not ours, the HTML of the configuration page otherwise
	 *
	 * @see     UsersModelUser::getOtpConfig
	 * @since   3.2
	 */
	public function onUserTwofactorShowConfiguration($otpConfig, $user_id = null)
	{
		if (!$this->validConfig) {
			return false;
		}

		JHtml::_('jquery.framework');
		$document = JFactory::getDocument();
		$document->addStyleSheet(Juri::root() . 'plugins/twofactorauth/hydroraindrop/hydro-raindrop-public.css');
		$document->addScript(Juri::root() . 'plugins/twofactorauth/hydroraindrop/hydro-raindrop-public.js');
		
		$hydro_id = isset ($otpConfig->config['hydro_id']) ? $otpConfig->config['hydro_id'] : '';
		if ($otpConfig->method === $this->methodName) {
			// This method is already activated. Reuse the same hydro id.
			$this->session->set('id', $hydro_id, 'hydro_raindrop');
			$this->session->set('key', isset ($otpConfig->config['hydro_key']) ? $otpConfig->config['hydro_key'] : '', 'hydro_raindrop');
		}
		
		$hydro_mfa_enabled = (bool)$otpConfig->method == $this->methodName && $hydro_id;
		$hydro_raindrop_confirmed = (bool)isset($otpConfig->config['hydro_raindrop_confirmed']) ? $otpConfig->config['hydro_raindrop_confirmed'] : false;
		$message = $this->get_message();
		
		// Include the form.php from a template override. If none is found use the default.
		$path = FOFPlatform::getInstance()->getTemplateOverridePath('plg_twofactorauth_hydroraindrop', true);

		$error = $this->session->get('mfa_error', null, 'hydro_raindrop');
		if ($error)
			$this->session->clear('mfa_error', 'hydro_raindrop');
		
		// Start output buffering
		@ob_start();

		extract($this->view_data($message, $error, $user_id));

		if (JFile::exists($path . '/form.php')) {
			include_once $path . '/form.php';
		} else {
			include_once __DIR__ . '/tmpl/form.php';
		}

		/*$cipher = new SimpleCipher();
		$key = random_bytes(32);
		$key = new Key('simple', $key);
		var_dump($cipher->encrypt('253452', $key));*/
		// Stop output buffering and get the form contents
		$html = @ob_get_clean();

		// Return the form contents
		return array(
			'method' => $this->methodName,
			'form' => $html
		);
	}

	/**
	 * The save handler of the two factor configuration method's configuration
	 * page.
	 *
	 * @param   string  $method  The two factor auth method for which we'll show the config page
	 *
	 * @return  boolean|stdClass  False if the method doesn't match or we have an error, OTP config object if it succeeds
	 *
	 * @see     UsersModelUser::setOtpConfig
	 * @since   3.2
	 */
	public function onUserTwofactorApplyConfiguration($method)
	{
		if (!$this->validConfig) {
			return false;
		}

		// Get a reference to the input data object
		$input = $this->app->input;
		
		// Load raw data
		$rawData = $input->get('jform', array(), 'array');

		if (!isset($rawData['twofactor']['hydroraindrop'])) {
			return false;
		}

		$data = $rawData['twofactor']['hydroraindrop'];

		// Warn if the hydro_id is empty
		if (array_key_exists('hydro_id', $data) && empty($data['hydro_id'])) {
			try {
				$this->enqueue('PLG_TWOFACTORAUTH_HYDRORAINDROP_ERR_VALIDATIONFAILED');
			} catch (Exception $exc) {
				// This only happens when we are in a CLI application. We cannot
				// enqueue a message, so just do nothing.
			}
			return false;
		}

		$user_id = isset($rawData['id']) ? $rawData['id'] : $this->user->id;
		$hydro_id = $data['hydro_id'];
		
		if (!empty($hydro_id)) {
			$length = strlen($hydro_id);

			if ($length < 5 || $length > 7) {
				$this->enqueue('PLG_TWOFACTORAUTH_HYDRORAINDROP_PROVIDE_VALID_HYDROID');
				return false;
			}
			
			$model = new UsersModelUser;
			$otp = $model->getOtpConfig($user_id);

			$cookie_key = bin2hex(random_bytes(32));

			try {
				// clean first
				$this->clean(false, false, $user_id);

				$this->client->registerUser($hydro_id);

				return (object)array(
					'method' => 'hydroraindrop',
					'config' => array(
						'hydro_id' => $hydro_id,
						'hydro_raindrop_confirmed' => isset($otp->config['hydro_id']) && $hydro_id == $otp->config['hydro_id'] ? $otp->config['hydro_raindrop_confirmed'] : 0,
						'hydro_key' => isset($otp->config['hydro_key']) ? $otp->config['hydro_key'] : $cookie_key
					),
					'otep' => array()
				);
			} catch (UserAlreadyMappedToApplication $e) {
				/*
				 * User is already mapped to this application.
				 *
				 * Edge case: A user tries to re-register with Hydro ID. If the user meta has been deleted, the
				 *            user can re-use his Hydro ID but needs to verify it again.
				 */
				$this->clean(false, false, $user_id);

				return (object)array(
					'method' => 'hydroraindrop',
					'config' => array(
						'hydro_id' => $hydro_id,
						'hydro_raindrop_confirmed' => isset($otp->config['hydro_id']) && $hydro_id == $otp->config['hydro_id'] ? $otp->config['hydro_raindrop_confirmed'] : 0,
						'hydro_key' => isset($otp->config['hydro_key']) ? $otp->config['hydro_key'] : $cookie_key
					),
					'otep' => array()
				);
			} catch (RegisterUserFailed $e) {
				var_dump($e);
				exit;
				$this->clean(true, false, $user_id);
				$this->enqueue($e->getMessage());
			}
		}
		return false;
	}

	/**
	 * This method should handle any two factor authentication and report back
	 * to the subject.
	 *
	 * @param   array  $credentials  Array holding the user credentials
	 * @param   array  $options      Array of extra options
	 *
	 * @return  boolean  True if the user is authorised with this two-factor authentication method
	 *
	 * @since   3.2
	 */
	public function onUserTwofactorAuthenticate($credentials, $options)
	{
		// Just return true because HYDRO requires entering a code after the user's hydro id has been fetched
		return true;
	}

	private function verifyMessage(string $hydro_id, int $message) : bool
	{
		if (!$this->validConfig) {
			return false;
		}
		try {
			$data = $this->client->verifySignature($hydro_id, $message);
			if ($data)
				return true;
		} catch (VerifySignatureFailed $e) {
			$this->enqueue($e->getMessage());
		}
		return false;
	}

	public function onAjaxVerifySignatureLogin()
	{
		if (!$this->validConfig) {
			return false;
		}
		$hydro_id = $this->session->get('id', null, 'hydro_raindrop');
		$message = $this->get_message();
		$cookie_key = bin2hex(random_bytes(32));

		if ($this->verifyMessage($hydro_id, $message)) {
			$this->set_cookie($this->user->id, $hydro_id, $cookie_key);
			$updates = (object) array(
				'id'     => $this->user->id,
				'otpKey' => $this->methodName . ':' . json_encode(array(
					'hydro_id' => $hydro_id,
					'hydro_raindrop_confirmed' => 1,
					'hydro_key' => $cookie_key
				))
			);
			JFactory::getDbo()->updateObject('#__users', $updates, 'id');
			return true;
		}
		$this->session->set('mfa_error', 'PLG_TWOFACTORAUTH_HYDRORAINDROP_ERR_VALIDATIONFAILED', 'hydro_raindrop');
		return false;
	}

	/**
	 * Show the MFA page to the client so the can authorize will raindrop
	 *
	 * @return void
	 */
	private function showMfa() {
		if (!$this->validConfig || $this->user->guest) {
			// I would rather do this than all the checks below
			return;
		}

		if (!$this->isActiveSection())
			return;

		$just_logged_in = $this->session->get('reauthenticate', false, 'hydro_raindrop');
		$hydro_id = $this->session->get('id', null, 'hydro_raindrop');
		$hydro_raindrop_confirmed = $this->session->get('confirmed', false, 'hydro_raindrop');
		$cookie_key = $this->session->get('key', null, 'hydro_raindrop');
		//var_dump($just_logged_in, $hydro_id, $hydro_raindrop_confirmed, $cookie_key);

		$show_fma = false;

		if (!$this->user->guest && $just_logged_in && $hydro_id && $hydro_raindrop_confirmed) {
			$show_fma = true;
			$this->clean();
		}

		if ($hydro_id && $hydro_raindrop_confirmed && $just_logged_in && !$this->verify_cookie($this->user->id, $hydro_id, $cookie_key)) {
			$show_fma = true;
		}

		if ($show_fma) {
			$document = JFactory::getDocument();
			$input = $this->app->input;

			if ($input->get('cancel_hydro_raindrop')) {
				$this->clean(true, true);
			}

			$error = null;
			$message = $this->get_message();

			if ($input->get('hydro_raindrop')) {
				if (!$this->verifyMessage($hydro_id, $message)) {
					$message = $this->get_message(true);
					$this->enqueue('PLG_TWOFACTORAUTH_HYDRORAINDROP_ERR_VALIDATIONFAILED');
				} else {
					$this->enqueue('PLG_TWOFACTORAUTH_HYDRORAINDROP_LOGIN_COMPLETE', 'success');
					$this->session->clear('reauthenticate', 'hydro_raindrop');
					$this->set_cookie($this->user->id, $hydro_id, $cookie_key);
					return;
				}
			}
			
			// Include the form.php from a template override. If none is found use the default.
			$path = FOFPlatform::getInstance()->getTemplateOverridePath('plg_twofactorauth_hydroraindrop', true);
			// Start output buffering
			@ob_start();

			$layout = new JLayoutFile('mfa', __DIR__ . '/layouts', array('debug' => false, 'client' => 1, 'component' => 'com_login'));
			echo $layout->render($this->view_data($message, null, $this->user->id));

			// Stop output buffering and get the form contents
			$html = @ob_get_clean();

			//lets get the site title
			$config = JFactory::getConfig();
			$sitename = $config->get('sitename');

			$document->setBuffer($html, 'component');
			$document->addStyleSheet(Juri::root() . 'plugins/twofactorauth/hydroraindrop/hydro-raindrop-public.css');
			$document->addStyleDeclaration($this->params->get('custom_css', ''));
			$document->setHeadData(array('title' => $sitename . ' - Hydro Raindrop MFA'));
			echo $document->render(false, array(
				'template' => $this->app->getTemplate(),
				'file' => 'component.php'
			));

			$this->app->close();
		}
	}

	/**
	 * Get the mfa view data
	 *
	 * @var string
	 * @return array
	 */
	private function view_data($message, $error = null, $user_id = null)
	{
		// Get the current users OTP config
		$model = new UsersModelUser;
		$otp = $model->getOtpConfig($this->user->id);

		return array(
			'error' => $error,
			'message' => $message,
			'logo' => JURI::root() . 'plugins/twofactorauth/hydroraindrop/images/logo.svg',
			'image' => JURI::root() . 'plugins/twofactorauth/hydroraindrop/images/security-code.png',
			'is_admin' => !$this->app->isClient('site'),
			'user_is_user' => $this->user->id == $user_id
		);
	}

	/**
	 * Cleanup based on params.
	 *
	 * @param bool $session Clear the session data.
	 * @param bool $logout Log the user out.
	 * @param string $user_id If passed remove the token for user id.
	 * @throws Exception When message could not be generated.
	 */
	private function clean(bool $session = false, bool $logout = false, $user_id = null)
	{
		// check if the user in on the frontend
		if (!$this->validConfig)
			return;
		
		// remove the token
		if ($user_id)
			$this->token_storage->unsetAccessTokenForUser($user_id);
		/*if ($user_id && $token)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('user_id') . ' = ' . $user_id,
				$db->quoteName('profile_key') . ' = ' . $db->quote('profile.HydroRaindropToken')
			);
			$query->delete($db->quoteName('#__user_profiles'));
			$query->where($conditions);
			$db->setQuery($query);
			$db->execute();
		}*/
		// remove the cookie only if on the frontend
		if (!$this->app->isClient('site') || $this->user->id == $user_id)
			$this->app->input->cookie->set(self::COOKIE_NAME, '', strtotime('-1 day'), $this->app->get('cookie_path', '/'), $this->app->get('cookie_domain'), $this->app->isSSLConnection());
		// clear the session data only if on frontend or unless you are the user your managing
		if ($session && (!$this->app->isClient('site') || $this->user->id == $user_id))
		{
			$this->session->clear('id', 'hydro_raindrop');
			$this->session->clear('key', 'hydro_raindrop');
			$this->session->clear('confirmed', 'hydro_raindrop');
			$this->session->clear('reauthenticate', 'hydro_raindrop');
			$this->session->clear('message', 'hydro_raindrop');
		}
		if ($logout)
		{
			$this->app->logout(null);
			$this->app->redirect('/');
		}
	}

	private function need_unregister() {
		// Get a reference to the input data object
		$input = $this->app->input;
		// Load raw data
		$rawData = $input->get('jform', array(), 'array');
		if (!isset($rawData['twofactor']['hydroraindrop'])) {
			return false;
		}
		$data = $rawData['twofactor']['hydroraindrop'];
		if (isset($rawData['twofactor']['method']) && $rawData['twofactor']['method'] == 'none') {
			$hydro_id = isset($data['hydro_id']) ? $data['hydro_id'] : '';
			if (!empty($hydro_id)) {
				try {
					$this->client->unregisterUser($hydro_id);
				} catch (UnregisterUserFailed $e) {
					$this->enqueue($e->getMessage());
				}
				$this->clean(true, false, $this->user->id);
			}
		}
	}

	/**
	 * Get the Raindrop MFA message.
	 *
	 * @return int
	 * @throws Exception When message could not be generated.
	 */
	private function get_message($new = false) : int
	{
		$message = $this->session->get('message', null, 'hydro_raindrop');
		if (!$message || $new) {
			$message = $this->client->generateMessage();
			$this->session->set('message', $message, 'hydro_raindrop');
		}
		return (int)$message;
	}

	/**
	 * Check if the connection is ssl
	 *
	 * @return bool
	 */
	private function is_ssl() {
		if ($this->app->isSSLConnection())
			return true;
		return false;
	}

	/**
	 * Perform Hash Mac on data and return hash.
	 *
	 * @param string $data Data to hash.
	 *
	 * @return string
	 */
	private function hash_hmac(string $data, string $salt) : string
	{
		return hash_hmac('sha1', $data, $salt);
	}

	/**
	 * Get HTTP cookie value.
	 *
	 * @param int $user_id			Currently logged in user.
	 * @param string $cookie_name	Cookie name.
	 *
	 * @return string
	 */
	private function get_cookie_value($user_id, $hydro_id, string $cookie_name, string $cookie_key) : string
	{
		$cipher = new SimpleCipher();
		$key = new Key('simple', hex2bin($cookie_key));

		$user_hash = $cipher->encrypt($user_id, $key);
		$expire = strtotime('+24 hours');
		$value = $cipher->encrypt(sprintf('%s|%s|%s|%s', $cookie_name, $user_hash, $hydro_id, $expire), $key);
		$signature = $this->hash_hmac($value, $cookie_key);
		return $value . '|' . $signature;
	}

	/**
	 * Set cookie for current user.
	 *
	 * @param int $user_id Current logged in user.
	 * @param string $hydro_id The users hydro_id.
	 */
	private function set_cookie(string $user_id, string $hydro_id, string $cookie_key)
	{
		$cookie = $this->get_cookie_value($user_id, $hydro_id, self::COOKIE_NAME, $cookie_key);
		$this->app->input->cookie->set(self::COOKIE_NAME, $cookie, 0, $this->app->get('cookie_path', '/'), $this->app->get('cookie_domain'), $this->app->isSSLConnection());
	}

	/**
	 * Verify Hydro Raindrop MFA cookie.
	 *
	 * @param string $userId
	 * @param string $hydroId
	 *
	 * @return bool
	 */
	private function verify_cookie($userId, $hydroId, $cookie_key) : bool
	{
		if (!$cookie_key)
			return false;
		$cipher = new SimpleCipher();
		$key = new Key('simple', null, hex2bin($cookie_key));

		$cookie = $this->app->input->cookie->get(self::COOKIE_NAME, null, 'string');
		if (!$cookie) {
			//$this->log('Cookie is not set.');
			return false;
		}
		// @codingStandardsIgnoreLine
		$cookie_list = explode('|', $cookie);
		if (count($cookie_list) !== 2) {
			$this->enqueue('Cookie contents are not valid (2).');
			return false;
		}
		// @codingStandardsIgnoreLine
		list($cookie_value, $cookie_signature) = $cookie_list;
		$signature = $this->hash_hmac($cookie_value, $cookie_key);
		if ($this->hash_hmac($signature, $cookie_key) !== $this->hash_hmac($cookie_signature, $cookie_key)) {
			$this->enqueue('Cookie signature invalid.');
			return false;
		}
		// @codingStandardsIgnoreLine
		$cookie_content = explode('|', $cipher->decrypt($cookie_value, $key));
		if (count($cookie_content) !== 4) {
			$this->enqueue('Cookie contents are not valid (4).');
			return false;
		}
		list($name, $user_id, $hydro_id, $expire) = $cookie_content;
		$is_valid = self::COOKIE_NAME === $name
			|| $userId === $user_id
			|| $hydro_id === $hydroId;
		if (!$is_valid) {
			$this->enqueue('Cookie data invalid.');
			return false;
		}
		// Cookie expired.
		if ((int)$expire < time()) {
			$this->enqueue('Cookie is expired.');
			return false;
		}
		return true;
	}
}
