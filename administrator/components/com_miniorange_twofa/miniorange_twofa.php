<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_twofa
 *
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/helpers/Mo_tfa_utility.php';
require_once JPATH_COMPONENT . '/helpers/Mo_tfa_customer_setup.php';
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_miniorange_twofa')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('com_miniorange_twofa', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('miniorange_twofa');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();