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

namespace W3biC\Plugin\System\IcLogin\Field;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

class OnlyProField extends FormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'onlyPro';

	protected function getInput()
	{
		$label = $this->element['label'];
		$description = $this->element['infopro'];

		if (!$label && !$description) {
			return '';
		}

		return '<div class="text-danger pt-1">' . Text::_($description) . '</div>' . $this->getText();
	}

	protected function getLabel()
	{
		$label = $this->element['label'];
		$description = $this->element['infopro'];

		if (!$label && !$description) {
			return '</div><div>' . $this->getText();
		}

		return parent::getLabel();
	}


	protected function getExtensionName()
	{
		$element = $this->form->getValue('element');

		if ($element) {
			return $element;
		}

		$component = $this->app->input->get('component', '');

		if ($component) {
			return str_replace('com_', '', $component);
		}

		$folder = $this->app->input->get('folder', '');

		if ($folder) {
			$extension = explode('.', $folder);
			return array_pop($extension);
		}

		$option = $this->app->input->get('option', '');

		if ($option) {
			return str_replace('com_', '', $option);
		}

		return false;
	}

	protected function getText()
	{
		$text = Text::_('JOOMLIC_ONLY_AVAILABLE_IN_PRO');
		$text = '<em>' . $text . '</em>';
		$extension = $this->getExtensionName();

		if (!$extension) {
			return $text;
		}

		return '<a href="https://www.joomlic.com/extensions/' . $extension . '" rel="noopener" target="_blank"> ' . $text . '</a>';
	}
}
