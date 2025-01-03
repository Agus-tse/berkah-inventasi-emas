<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 11084 2024-11-05 07:31:12Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewInventory extends VmViewAdmin {

	function display($tpl = null) {

		// Get the data
		$model = VmModel::getModel('product');

		// Create filter
		$this->addStandardDefaultViewLists($model);

		$this->inventorylist = $model->getProductListing(false,false,false,false);

		$this->pagination = $model->getPagination();

		// Apply currency
		$currencydisplay = CurrencyDisplay::getInstance();

		foreach ($this->inventorylist as $virtuemart_product_id => $product) {

			//TODO oculd be interesting to show the price for each product, and all stored ones $product->product_in_stock
			$price = isset($product->allPrices[$product->selectedPrice]['product_price'])? $product->allPrices[$product->selectedPrice]['product_price']:0;

			$product->product_instock_value = $currencydisplay->priceDisplay($price,'',$product->product_in_stock,false);
			$product->product_price_display = $currencydisplay->priceDisplay($price,'',1,false);

			$product->weigth_unit_display= ShopFunctions::renderWeightUnit($product->product_weight_uom);

			if(!empty($product->product_weight)){
				$tmpStr = rtrim( (string) $product->product_weight,'0');
				$tmpStr = rtrim( $tmpStr,'.');
				$product->product_weight_display = $tmpStr;
				$total = $product->product_weight * $product->product_in_stock;
				$totalR = number_format($total,4,'.',' ');

				$tmpStr = rtrim( $totalR,'0');
				$tmpStr = rtrim( (string) $tmpStr,'.');
				$product->product_weight_displayTT = $tmpStr;
			} else {
				$product->product_weight_display = '';
				$product->product_weight_displayTT = '';
			}
		}

		$options = array();
		$options[] = JHtml::_('select.option', '', vmText::_('COM_VIRTUEMART_DISPLAY_STOCK').':');
		$options[] = JHtml::_('select.option', 'stocklow', vmText::_('COM_VIRTUEMART_STOCK_LEVEL_LOW'));
		$options[] = JHtml::_('select.option', 'stockout', vmText::_('COM_VIRTUEMART_STOCK_LEVEL_OUT'));
		$this->lists['stockfilter'] = JHtml::_('select.genericlist', $options, 'search_type', 'onChange="document.adminForm.submit(); return false;"', 'value', 'text', vRequest::getVar('search_type'));
		$this->lists['filter_product'] = vRequest::getVar('filter_product');

		/* Toolbar */
		$this->SetViewTitle('PRODUCT_INVENTORY');
		JToolbarHelper::publish();
		JToolbarHelper::unpublish();

		parent::display($tpl);
	}

}
// pure php no closing tag
