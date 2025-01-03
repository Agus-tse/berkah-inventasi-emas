<?php
defined ('_JEXEC') or die();

/**
 * abstract controller class containing get,store,delete,publish and pagination
 *
 * This class provides the functions for the Views

 * This class provides the functions for the calculations
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers, Stan Scholz
 * @copyright Copyright (C) 2014-2022 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
#[\AllowDynamicProperties]
class VmViewAdmin extends JViewLegacy {
	/**
	 * Sets automatically the shortcut for the language and the redirect path
	 * @author Max Milbers
	 */

	var $lists = array();
	var $showVendors = null;
	protected $canDo;
	var $writeJs = true;

	function __construct($config = array()) {
		parent::__construct($config);
	}
	public function assignRef($key, &$val) {
		$this->{$key} =& $val; 
	}
	/*
	* Override the display function to include ACL
	* Redirect to the control panel when user does not have access
	*/
	public function display($tpl = null) {

		if ($this->_name == 'virtuemart' //Virtuemart view is always allowed since this is the page we redirect to in case the user does not have the rights
		or $this->_name == 'about' //About view always displayed

		or $this->manager($this->_name) ) {
			//or $this->canDo->get('core.admin')
			//or $this->canDo->get('vm.'.$view) ) { //Super administrators always have access

			if(VmConfig::get('backendTemplate')){
				$this->addTemplatePath (VMPATH_ROOT .'/administrator/templates/vmadmin/html/com_virtuemart/'. $this->_name);
				vmdebug('VmViewAdmin addTemplatePath vmadmin');
			}
			if(VmConfig::get('useLayoutOverrides',1)){
				$template = VmTemplate::getDefaultTemplate(1);

				if(VmConfig::isSiteByApp()){
					$unoverridable = array('category','manufacturer','user');	//This views have the same name and must not be overridable
					$this->addTemplatePath (VMPATH_ROOT .'/administrator/templates/'. $template['template'] .'/html/com_virtuemart/'. $this->_name);

					$template = VmTemplate::getDefaultTemplate(0);
					//So we need "the Admin" suffix only for unoverridable views, but it can be used for any view
					if(!in_array($this->_name,$unoverridable)){
						$this->addTemplatePath (VMPATH_ROOT .'/templates/'. $template['template'] .'/html/com_virtuemart/'. $this->_name);
					}
					$this->addTemplatePath (VMPATH_ROOT .'/templates/'. $template['template'] .'/html/com_virtuemart/'. $this->_name.'Admin');

				} else {
					$this->addTemplatePath (VMPATH_ROOT .'/administrator/templates/'. $template['template'] .'/html/com_virtuemart/'. $this->_name);
				}
			}

			$result = $this->loadTemplate($tpl);
			if ($result instanceof Exception) {
				return $result;
			}

			echo $result;

			if($this->writeJs){
				vmJsApi::keepAlive();
				echo vmJsApi::writeJS();
			}
			return true;
		} else {
			$viewType = vRequest::getCmd('format','html');
			vmTrace('View called without permission? '.$this->_name.$viewType);
			JFactory::getApplication()->redirect( 'index.php?option=com_virtuemart&component=0');

		}

	}

	/*
	 * set all commands and options for BE default.php views
	* return $list filter_order and
	*/
	function addStandardDefaultViewCommands($showNew=true, $showDelete=true, $showHelp=true) {


		$view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));

		JToolbarHelper::divider();
		if(vmAccess::manager($view.'.edit.state')){
			JToolbarHelper::publishList();
			JToolbarHelper::unpublishList();
		}
		if(vmAccess::manager($view.'.edit')){
			JToolbarHelper::editList();
		}
		if($showNew and vmAccess::manager($view.'.create')){
			JToolbarHelper::addNew();
		}
		if($showDelete and vmAccess::manager($view.'.delete')){
			JToolbarHelper::spacer('10');
			JToolbarHelper::deleteList();
		}
		JToolbarHelper::divider();
		JToolbarHelper::spacer('2');
		self::showACLPref($view);
		self::showHelp ( $showHelp);
		if(VmConfig::isSiteByApp()){
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', 'COM_VIRTUEMART_LEAVE', 'index.php?option=com_virtuemart&managing=0');
		}

		$this->addJsJoomlaSubmitButton();
		// javascript for cookies setting in case of press "APPLY"
	}

	function addJsJoomlaSubmitButton($validate=false){

		static $done=array(false,false);
		if(!$done[$validate]){
			if($validate){
				vmJsApi::vmValidator();
				$form = "if( (a=='apply' || a=='save') && myValidator(form,false)){
				form.submit();
			} else if(a!='apply' && a!='save'){
				form.submit();
			}";
			} else {
				$form = "form.submit();";
			}
		} else {
			return $done[$validate];
		}

		$j = "
	Joomla.submitbutton=function(a){

		var options = { path: '/', expires: 2}
		if (a == 'apply') {
			var idx = jQuery('#tabs li.current').index();
			jQuery.cookie('vmapply', idx, options);
		} else {
			jQuery.cookie('vmapply', '0', options);
		}
		jQuery( '#media-dialog' ).remove();
		form = document.getElementById('adminForm');
		form.task.value = a;
		".$form."
		return false;
	};

		links = jQuery('a[onclick].toolbar');

		links.each(function(){
			var onClick = new String(this.onclick);
			jQuery(this).click(function(e){
				//console.log('click ');
				e.stopImmediatePropagation();
				e.preventDefault();
			});
		});";
		vmJsApi::addJScript('submit', $j,false, true);
		$done[$validate]=true;
	}

	/**
	 * set pagination and filters
	 * return Array() $list( filter_order and dir )
	 */

	function addStandardDefaultViewLists($model, $default_order = 0, $default_dir = 'DESC',$name = 'search') {

		// set list filters
		$option = vRequest::getCmd('option');
		$view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));

		$app = JFactory::getApplication();
		$this->lists[$name] = $app->getUserStateFromRequest($option . '.' . $view . '.'.$name, $name, '', 'string');

		$this->lists['filter_order'] = $this->getValidFilterOrder($app,$model,$view,$default_order);

		$toTest = $app->getUserStateFromRequest( 'com_virtuemart.'.$view.'.filter_order_Dir', 'filter_order_Dir', $default_dir, 'cmd' );

		$this->lists['filter_order_Dir'] = $model->checkFilterDir($toTest);

	}

	function getValidFilterOrder($app,$model,$view,$default_order){

		if($default_order===0){
			$default_order = $model->getDefaultOrdering();
		}
		$toTest = $app->getUserStateFromRequest( 'com_virtuemart.'.$view.'.filter_order', 'filter_order', $default_order, 'cmd' );

		return $model->checkFilterOrder($toTest);
	}


	/**
	 * Add simple search to form
	 * @param $searchLabel text to display before searchbox
	 * @param $name 		 lists and id name
	 * ??vmText::_('COM_VIRTUEMART_NAME')
	 */

	function displayDefaultViewSearch($searchLabel='COM_VIRTUEMART_NAME',$name ='search') {
		return vmText::_('COM_VIRTUEMART_FILTER') . ' ' . vmText::_($searchLabel) . ':
		<input type="text" name="' . $name . '" id="' . $name . '" value="' .$this->lists[$name] . '" class="text_area" />
		<button class="btn btn-small" onclick="this.form.submit();">' . vmText::_('COM_VIRTUEMART_GO') . '</button>
		<button class="btn btn-small" onclick="document.getElementById(\'' . $name . '\').value=\'\';this.form.submit();">' . vmText::_('COM_VIRTUEMART_RESET') . '</button>';
	}

	function addStandardEditViewCommands($id = 0,$object = null) {

		$view = vRequest::getCmd('view', vRequest::getCmd('controller','virtuemart'));

		if ($view == 'product' and vmAccess::manager('product.create')) {
			if (vmAccess::manager('product.create')) {
				JToolbarHelper::custom( 'createchild', 'new', 'new', vmText::_( 'COM_VIRTUEMART_PRODUCT_CHILD' ), false );
				JToolbarHelper::custom( 'cloneproduct', 'copy', 'copy', vmText::_( 'COM_VIRTUEMART_PRODUCT_CLONE' ), false );
			}
			if (vmAccess::manager('ratings.edit')) {
				JToolbarHelper::custom('addrating', 'default', '', vmText::_('COM_VIRTUEMART_ADD_RATING'), false);
			}
		}

		JToolbarHelper::divider();
		if (vmAccess::manager($view.'.edit')) {
			JToolbarHelper::save();
			JToolbarHelper::apply();
		}
		JToolbarHelper::cancel();
		self::showHelp();
		self::showACLPref($view);

		if($view != 'shipmentmethod' and $view != 'paymentmethod' and $view != 'media') $validate = true; else $validate = false;
		$this->addJsJoomlaSubmitButton($validate);

		$editView = vRequest::getCmd('view',vRequest::getCmd('controller','' ) );
		$selectedLangue = VmConfig::$vmlangTag;

		// Get all the published languages defined in Language manager > Content
		$allLanguages	= JLanguageHelper::getLanguages();
		foreach ($allLanguages as $jlang) {
			$languagesByCode[$jlang->lang_code]=$jlang;
		}

		// only add if ID and view not null
		if ($editView and ($id or VmConfig::get('prodOnlyWLang',false)) and (count(VmConfig::get('active_languages', array(VmConfig::$jDefLangTag)))>1) ) {

			if ($editView =='user') $editView ='vendor';

			// list of languages installed in #__extensions (may be more than the ones in the Language manager > Content if the user did not added them)

			//$languages = JLanguageHelper::createLanguageList($selectedLangue, constant('VMPATH_ROOT'), true);
			$JLanguages = JHtml::_('contentlanguage.existing');
			$languages = [];
			foreach ($JLanguages as $i => $lang){

				$language = array();
				$language['value'] = $lang->value;
				$language['text'] = $lang->text;
				$language['selected'] = ($lang->value == $selectedLangue) ? 'selected="selected"' : '' ;
				$languages[] = $language;
			}

			$activeVmLangs = (VmConfig::get('active_languages', array(VmConfig::$jDefLangTag)) );
			$flagCss="";
			foreach ($languages as $k => &$joomlaLang) {
				if (!in_array($joomlaLang['value'], $activeVmLangs) ) {
					unset($languages[$k] );
				} else {

					$key=$joomlaLang['value'];
					if(!isset($languagesByCode[$key])){
						$img = substr($key,0,2);//We try a fallback
						vmdebug('COM_VIRTUEMART_MISSING_FLAG',$img,$joomlaLang['text']);
					} else {
						$img=$languagesByCode[$key]->image;
					}
					$image_flag= VMPATH_ROOT."/media/mod_languages/images/".$img.".gif";
					$image_flag_url= JURI::root()."media/mod_languages/images/".$img.".gif";

					if (!file_exists ($image_flag)) {
						vmerror(vmText::sprintf('COM_VIRTUEMART_MISSING_FLAG', $image_flag,$joomlaLang['text'] ) );
					} else {
						$flagCss .="td.flag-".$key.",.flag-".$key."{background: url( ".$image_flag_url.") no-repeat 0 0 !important; padding-left:20px !important;}\n";
					}
				}
			}
			JFactory::getDocument()->addStyleDeclaration($flagCss);
			
			$childdata = array(); 
			$token = vRequest::getFormToken();
			$childdata['id'][] = $id;

			if($editView =='product') {
				$productModel = VmModel::getModel('product');
				$childproducts = $productModel->getProductChildIds($id);

				if(!empty($childproducts)){
					foreach($childproducts as $ids) {
						$childdata['id'][] = (int) $ids;
					}
				}
			}
			
			$childdata[$token] = 1; 
			$childdata['editView'] = $editView; 

			//stAn: added json data as needed
			$this->langList = JHtml::_('select.genericlist',  $languages, 'vmlang', 'class="inputbox" style="width:176px;" data-json="'.htmlentities(json_encode($childdata)).'" onchange="javascript: updateLanguageVars(this, event);"', 'value', 'text', $selectedLangue , 'vmlang');
			//stAn: script can be loaded async and deferred
			vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/vmlang.js', false, true, true);
			
		} else {
			$jlang = vmLanguage::getLanguage();
			/*$langs = $jlang->getKnownLanguages();*/
			
			$langs = \Joomla\CMS\Language\LanguageHelper::getKnownLanguages(); 
			$defautName = $selectedLangue;
			$flagImg = $selectedLangue;
			if(isset($languagesByCode[$selectedLangue])){
				$defautName = $langs[$selectedLangue]['name'];
				$flagImg= JHtml::_('image', 'mod_languages/'. $languagesByCode[$selectedLangue]->image.'.gif',  $languagesByCode[$selectedLangue]->title_native, array('title'=> $languagesByCode[$selectedLangue]->title_native), true);
			} else {
				vmWarn(vmText::sprintf('COM_VIRTUEMART_MISSING_FLAG',$selectedLangue,$selectedLangue));
			}
			$this->langList = '<input name ="vmlang" type="hidden" value="'.$selectedLangue.'" >'.$flagImg.' <b> '.$defautName.'</b>';
		}

		if(VmConfig::isSiteByApp()){
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', 'COM_VIRTUEMART_LEAVE', 'index.php?option=com_virtuemart&managing=0');
		}
	}

	function SetViewTitle($name ='', $msg ='',$icon ='') {

		$view = vRequest::getCmd('view', vRequest::getCmd('controller'));
		if ($name == '')
			$name = strtoupper($view);
		if ($icon == '')
			$icon = strtolower($view);
		if (!$task = vRequest::getCmd('task'))
			$task = 'list';

		if (!empty($msg)) {
			$msg = ' <span style="color: #666666; font-size: large;">' . $msg . '</span>';
		}

		$viewText = vmText::_('COM_VIRTUEMART_' . strtoupper($name));

		$taskName = ' <small><small>[ ' . vmText::_('COM_VIRTUEMART_' . strtoupper($task)) . ' ]</small></small>';

		JToolbarHelper::title($viewText . ' ' . $taskName . $msg, 'head vm_' . $icon . '_48');
		$this->assignRef('viewName',$viewText); //TODO was $viewName?
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		//$doc->setTitle($app->getCfg('sitename'). ' - ' .vmText::_('JADMINISTRATION').' - '.strip_tags($msg));

		$app = JFactory::getApplication();
		$title = ShopFunctionsF::vmSubstr($app->getCfg('sitename'),0,5).' '.vmText::_('COM_VIRTUEMART_' . strtoupper($name)).' '.vmText::_('COM_VIRTUEMART_' . strtoupper($task));
		$doc->setTitle( $title );
	}

	function sort($orderby ,$name=null, $task=null ){
		if (!$name) $name= 'COM_VIRTUEMART_'.strtoupper ($orderby);
		return JHtml::_('grid.sort' , vmText::_($name) , $orderby , $this->lists['filter_order_Dir'] , $this->lists['filter_order'], $task);
	}

	public function addStandardHiddenToForm($controller=null, $task=''){
		if (!$controller) $controller = vRequest::getCmd('view');
		$option = vRequest::getCmd('option','com_virtuemart' );
		$hidden ='';
		if (array_key_exists('filter_order',$this->lists)) {
			$hidden ='
			<input type="hidden" name="filter_order" value="'.$this->lists['filter_order'].'" />
			<input type="hidden" name="filter_order_Dir" value="'.$this->lists['filter_order_Dir'].'" />';
		}

		if(VmConfig::isSiteByApp()){
			$hidden .='<input type="hidden" name="managing" value="1" />';
		}
		return  $hidden.'
		<input type="hidden" name="task" value="'.$task.'" />
		<input type="hidden" name="option" value="'.$option.'" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="'.$controller.'" />
		<input type="hidden" name="view" value="'.$controller.'" />
		'. JHtml::_( 'form.token' );
	}


	/**
	 * Additional grid function for custom toggles
	 *
	 * @return string HTML code to write the toggle button
	 */
	function toggle( $field, $i, $toggle, $imgY = 'tick.png', $imgX = 'publish_x.png', $untoggleable = false )
	{
		$field = intval($field);
		$img 	= $field ? $imgY : $imgX;
		if ($toggle == 'published') {
			// Stay compatible with grid.published
			$task 	= $field ? 'unpublish' : 'publish';
			$alt 	= $field ? vmText::_('COM_VIRTUEMART_PUBLISHED') : vmText::_('COM_VIRTUEMART_UNPUBLISHED');
			$action = $field ? vmText::_('COM_VIRTUEMART_UNPUBLISH_ITEM') : vmText::_('COM_VIRTUEMART_PUBLISH_ITEM');
		} else {
			$task 	= $field ? $toggle.'.0' : $toggle.'.1';
			$alt 	= $field ? vmText::_('COM_VIRTUEMART_PUBLISHED') : vmText::_('COM_VIRTUEMART_DISABLED');
			$action = $field ? vmText::_('COM_VIRTUEMART_DISABLE_ITEM') : vmText::_('COM_VIRTUEMART_ENABLE_ITEM');
		}

		$img = 'admin/' . $img;

		if ($untoggleable) {
			$attribs='style="opacity: 0.6;"';
		} else {
			$attribs='';
		}
		$image = JHtml::_('image', $img, $alt, $attribs, true);

		if($untoggleable) return $image;


			$icon 	= $field ? 'publish' : 'unpublish';
			return ('<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">'
			. '<span class="icon-'.$icon.'"><span>' .'</a>');



	}

	function gridPublished($name,$i) {
		if (JVM_VERSION < 3){
			$published = JHtml::_('grid.published', $name, $i );
		} else {
			$published = JHtml::_('jgrid.published', $name->published, $i );
		}
		return $published;
	}

	function showhelp(){
		/* http://docs.joomla.org/Help_system/Adding_a_help_button_to_the_toolbar */
		$task=vRequest::getCmd('task', '');
		$view=vRequest::getCmd('view', '');
		if ($task) {
			if ($task=="add") {
				$task="edit";
			}
			$task ="_".$task;
		}

		vmLanguage::loadJLang('com_virtuemart_help');
		$lang = vmLanguage::getLanguage();
		$key=  'COM_VIRTUEMART_HELP_'.$view.$task;

		if ($lang->hasKey($key)) {
			$help_url  = vmText::_($key)."?tmpl=component";
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton( 'Popup', 'help', 'JTOOLBAR_HELP', $help_url );
		}

	}

	function showACLPref(){

		if (vmAccess::manager('core')) {
			JToolbarHelper::divider();
			$bar = JToolBar::getInstance('toolbar');
			if(JVM_VERSION<3){
				$bar->appendButton('Popup', 'lock', 'JCONFIG_PERMISSIONS_LABEL', 'index.php?option=com_config&amp;view=component&amp;component=com_virtuemart&amp;tmpl=component', 875, 550, 0, 0, '');
			} else {
				$bar->appendButton('Link', 'lock', 'JCONFIG_PERMISSIONS_LABEL', 'index.php?option=com_config&amp;view=component&amp;component=com_virtuemart');
			}

		}

	}

	/**
	 * Checks if we show multivendor related stuff for admins
	 * @return bool|null
	 */
	public function showVendors(){

		if($this->showVendors===null){
			if(VmConfig::get('multix','none')!='none' and vmAccess::manager('managevendors')){
				$this->showVendors = true;
			} else {
				$this->showVendors = false;
			}
		}
		return $this->showVendors;
	}

	public function manager($view=0) {
		if(empty($view)) $view = $this->_name;
		return vmAccess::manager($view);
	}

	public function setOrigLang($model){
		$origLang = '';
		if(!empty($model->_loadedWithLangFallback)){
			$origLang = '(<span class="allflags flag-'.$model->_loadedWithLangFallback.'"></span>)';
		}
		$this->origLang = '<span class="langfallback">'.$origLang.'</span>';
	}

	public function saveOrder(){
		return '<a href="#" onclick="Joomla.submitbutton(\'saveOrder\')" rel="tooltip" class="saveOrder btn btn-small pull-right" title="'.vmText::_('JLIB_HTML_SAVE_ORDER').'"><span class="icon-menu-2"></span></a>';
	}
}