<?php
/**
 *
 * Manufacturer View
 *
 * @package	VirtueMart
 * @subpackage Manufacturer
 * @author Patrick Kohl
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 11088 2024-11-06 18:44:46Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for maintaining the list of manufacturers
 *
 * @package	VirtueMart
 * @subpackage Manufacturer
 * @author Patrick Kohl
 */
class VirtuemartViewManufacturer extends VmViewAdmin {

	function display($tpl = null) {

		// get necessary models
		$model = VmModel::getModel('manufacturer');

		$categoryModel = VmModel::getModel('manufacturercategories');

		$this->SetViewTitle();

		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {

			$ids = vRequest::getVar('virtuemart_manufacturer_id',  vRequest::getVar('cid',  0));
			if($ids){
				if(is_array($ids) and isset($ids[0])){
					$ids = $ids[0];
				}
				$model->setId($ids);
			}

			$this->manufacturer = $model->getManufacturer();

			if(!empty($this->manufacturer->_loadedWithLangFallback)){
				vmInfo('COM_VM_LOADED_WITH_LANGFALLBACK',$this->manufacturer->_loadedWithLangFallback);
			}
			$this->setOrigLang($this->manufacturer);

			$isNew = ($this->manufacturer->virtuemart_manufacturer_id < 1);

			$model->addImages($this->manufacturer);

			/* Process the images */
			$mediaModel = VmModel::getModel('media');
			$image = $mediaModel->getFile($this->manufacturer->virtuemart_media_id,'manufacturer','image');

			$this->manufacturerCategories = $categoryModel->getManufacturerCategories(false,true);
			$emptOpt = new stdClass();
			$emptOpt->virtuemart_manufacturercategories_id = 0;
			$emptOpt->mf_category_name = vmText::sprintf( 'COM_VIRTUEMART_SELECT' ,  vmText::_('COM_VIRTUEMART_MANUFACTURER_CATEGORY')) ;;
			array_unshift($this->manufacturerCategories, $emptOpt);
vmdebug('mei',$this->manufacturerCategories);
			$this->addStandardEditViewCommands($this->manufacturer->virtuemart_manufacturer_id);

		}
		else {

			$mainframe = JFactory::getApplication();

			$categoryFilter = $categoryModel->getCategoryFilter();

			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model,'mf_name');

			$this->manufacturers = $model->getManufacturers();
			$this->pagination = $model->getPagination();

			$virtuemart_manufacturercategories_id	= $mainframe->getUserStateFromRequest( 'com_virtuemart.virtuemart_manufacturercategories_id', 'virtuemart_manufacturercategories_id', 0, 'int' );
			$this->lists['virtuemart_manufacturercategories_id'] =  JHtml::_('select.genericlist',   $categoryFilter, 'virtuemart_manufacturercategories_id', 'class="inputbox" onchange="this.form.submit()"', 'value', 'text', $virtuemart_manufacturercategories_id );

		}


		parent::display($tpl);
	}

}
// pure php no closing tag
