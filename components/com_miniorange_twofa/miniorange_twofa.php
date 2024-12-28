<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_miniorange_twofa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/Mo_tfa_utility.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/Mo_tfa_customer_setup.php';


// Get an instance of the controller prefixed by HelloWorld
$controller = JControllerLegacy::getInstance('Miniorange_TwoFa');


// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
