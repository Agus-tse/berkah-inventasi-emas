<?php

/**
 * virtuemart table class, with some additional behaviours.
 * derived from JTable Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 *
 * @version $Id$
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @copyright Copyright (c) 2011 - 2022 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
defined('_JEXEC') or die();

/**
 * Replaces JTable with some more advanced functions and fitting to the nooku conventions
 *
 * checked_out = locked_by,checked_time = locked_on
 *
 * @author Milbo
 *
 */

Use \Joomla\CMS\Table\TableInterface as JTableInterface;

class VmTable extends vObject implements \JTableInterface {

	protected static $_cache = array();
	private $_lhash = 0;

	protected $_tbl = '';
	protected $_tbl_lang = null;
	protected $_tbl_key ='';
	protected $_tbl_keys = '';

	/** @var string the extra primaryKey loads all rows by another column than the real primary,
	 * for example product_customfields.
	 */
	protected $_pkey = '';
	protected $_pkeyForm = '';
	public $_obkeys = array();
	protected $_unique = false;
	public $_unique_name = array();
	protected $_orderingKey = 'ordering';
	public $_slugAutoName = '';
	public $_slugName = '';
	protected $_db = false;
	protected $_rules;
	protected $_trackAssets = false;
	protected $_locked = false;
	protected $_loggable = false;
	public $_xParams = 0;
	public $_varsToPushParam = array();
	var $_translatable = false;
	protected $_translatableFields = array();
	protected $_dateFields = array();
	protected $_hashName = '';
	protected $_omittedHashFields = array();
	public $_cryptedFields = false;
	public $_langTag = null;
	public $_ltmp = false;
	public $_loadedWithLangFallback = 0;
	public $_loaded = false;
	protected $_updateNulls = false;
	protected $_toConvertDec = array();
	protected $_toConvertInt = array();
	protected $_genericVendorId = true;
	public $_update = null;

	/**
	 * @param string $table
	 * @param string $key
	 * @param JDatabase $db
	 */
	function __construct($table, $key, &$db) {

		$this->_tbl = $table;
		$this->_db = &$db;
		$this->_pkey = $key;
		$this->_pkeyForm = 'cid';

		if(JVM_VERSION<3){
			$this->_tbl_key = $key;
			$this->_tbl_keys = array($key);
		} else {
			// Set the key to be an array.
			if (is_string($key)){
				$key = array($key);
			} elseif (is_object($key)){
				$key = (array) $key;
			}

			$this->_tbl_keys = $key;
			$this->_tbl_key = $key[0];

			if (count($key) == 1) {
				$this->_autoincrement = true;
			} else {
				$this->_autoincrement = false;
			}
		}

		// If we are tracking assets, make sure an access field exists and initially set the default.
		if (property_exists($this, 'asset_id')){
			$this->_trackAssets = true;
		}

		// If the access property exists, set the default.
		if (property_exists($this, 'access')){
			$this->access = (int) JFactory::getConfig()->get('access');
		}

	}

	/**
	 * Returns an associative array of object properties.
	 *
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   boolean  $public  If true, returns only the public properties.
	 *
	 * @return  array
	 * @since   11.1
	 * @see     get()
	 */
	public function getProperties($public = true) {

		$vars = get_object_vars($this);
		if ($public) {

			foreach ($vars as $k => $v) {
				if (strpos ($k, '_') === 0 or !property_exists($this, $k)) {
					unset($vars[$k]);
				}
			}
		}

		return $vars;
	}

	/**
	 * @param bool $array
	 * @return array|stdClass true as array
	 */
	function loadFieldValues($array=true){


		if($array){
			$return = $this->getProperties();
		} else {
			$tmp = get_object_vars($this);
			$return = new stdClass();
			foreach ($tmp as $k => $v){

				if ( strpos ($k, '_') === 0){
					continue;
				}
				// Do not process internal variables
				if (property_exists($this, $k)){
					$return->{$k} = $v;
				}
			}
		}

		return $return;
	}

	/**
	 * Static method to get an instance of a JTable class if it can be found in
	 * the table include paths.  To add include paths for searching for JTable
	 * classes @see JTable::addIncludePath().
	 *
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   string  $type    The type (name) of the JTable class to get an instance of.
	 * @param   string  $prefix  An optional prefix for the table class name.
	 * @param   array   $config  An optional array of configuration values for the JTable object.
	 *
	 * @return  mixed    A JTable object if found or boolean false if one could not be found.
	 *
	 * @link	http://docs.joomla.org/JTable/getInstance
	 * @since   11.1
	 */
	public static function getInstance($type, $prefix = 'Table', $config = array())
	{
		// Sanitize and prepare the table class name.
		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$tableClass = $prefix . ucfirst($type);

		// Only try to load the class if it doesn't already exist.
		if (!class_exists($tableClass))
		{
			// Search for the class file in the JTable include paths.
			jimport('joomla.filesystem.path');

			$paths = VmTable::addIncludePath();
			$pathIndex = 0;
			while (!class_exists($tableClass) && $pathIndex < count($paths))
			{
				if ($tryThis = JPath::find($paths[$pathIndex++], strtolower($type) . '.php'))
				{
					// Import the class file.
					include_once $tryThis;
				}
			}
			if (!class_exists($tableClass))
			{
				vmEcho::$logDebug = true;
				vmdebug('Did not find class '.$tableClass.' in file '.$type.'.php in ',$paths,$tryThis);
				vmEcho::$logDebug = false;
				return false;
			}
		}

		// If a database object was passed in the configuration array use it, otherwise get the global one from JFactory.
		$db = isset($config['dbo']) ? $config['dbo'] : JFactory::getDbo();

		if(empty(VmConfig::$vmlang)){

			vmTrace('$vmlang not set',true,20);
			vmdebug('$vmlang not set',VmConfig::$jDefLangTag);
			vmError('$vmlang not set',VmConfig::$jDefLangTag);

			vmEcho::$logDebug = true;
			vmTrace('$vmlang not set',true,20);
			vmEcho::$logDebug = false;

			vmLanguage::initialise();
			//return false;
		}
		// Instantiate a new table class and return it.
		return new $tableClass($db);
	}

	/**
	 * Add a filesystem path where JTable should search for table class files.
	 * You may either pass a string or an array of paths.
	 *
	 * @param   mixed  $path  A filesystem path or array of filesystem paths to add.
	 *
	 * @return  array  An array of filesystem paths to find JTable classes in.
	 *
	 * @link    http://docs.joomla.org/JTable/addIncludePath
	 * @since   11.1
	 */
	public static function addIncludePath($path = null)
	{
		// Declare the internal paths as a static variable.
		static $_paths;

		// If the internal paths have not been initialised, do so with the base table path.
		if (!isset($_paths))
		{
			$_paths = array(VMPATH_ADMIN .'/tables');
		}

		// Convert the passed path(s) to add to an array.
		settype($path, 'array');

		// If we have new paths to add, do so.
		if (!empty($path) && !in_array($path, $_paths))
		{
			// Check and add each individual new path.
			foreach ($path as $dir)
			{
				// Sanitize path.
				$dir = trim($dir);

				if(!in_array($dir,$_paths)){
					// Add to the front of the list so that custom paths are searched first.
					array_unshift($_paths, $dir);
				}

			}
		}

		return $_paths;
	}


	public function getKeyName($multiple = false) {

		if (count($this->_tbl_keys)) {
			if ($multiple) {
				return $this->_tbl_keys;
			} else {
				return $this->_tbl_keys[0];
			}
		} else {
			return $this->_tbl_key;
		}

	}

	public function getDbo() {
		//static $db = false;
		if(!$this->_db){
			$this->_db = JFactory::getDbo();
		}
		return $this->_db;
	}

	/**
	 * @return string|void
	 */
	public function getError(){
		vmTrace( get_class($this).' asks for error');
		vmdebug( get_class($this).' asks for error');
		return ;
	}

	public function getErrors(){
		vmTrace( get_class($this).' asks for errors');
		vmdebug( get_class($this).' asks for errors');
		return ;
	}

	public function setPrimaryKey($key, $keyForm = 0) {

		//$error = vmText::sprintf('COM_VIRTUEMART_STRING_ERROR_PRIMARY_KEY', 'COM_VIRTUEMART_' . strtoupper($key));
		$this->setObligatoryKeys('_pkey'/*, $error*/);
		$this->_pkey = $key;
		$this->_pkeyForm = empty($keyForm) ? $key : $keyForm;
		$this->{$key} = 0;
	}

	public function getPKey(){
		return $this->_pkey;
	}

	public function setObligatoryKeys($key, $error = 1) {

		$this->_obkeys[$key] = $error;
	}

	public function setUniqueName($name) {
		$this->_unique = true;
		$this->_obkeys[$name] = 1;
		$this->_unique_name[$name] = 1;
	}

	public function setLoggable() {

		$this->_loggable = true;
		$this->created_on = false;
		$this->created_by = 0;
		$this->modified_on = '';
		$this->modified_by = 0;
		$this->setConvertInt(array('created_by','modified_by'));
		$this->setDateFields(array('created_on','modified_on'));
	}

	/**
	 *
	 * @author Patrick Kohl,
	 * @author Max Milbers
	 */
	public function setTranslatable($langFields) {

		$this->_translatableFields = $langFields;
		$this->_translatableFields['slug'] = 'slug';
		$this->_translatable = true;

		$this->_langTag = VmConfig::$vmlang;
		$this->_tbl_lang = $this->_tbl . '_' . $this->_langTag;
	}

	public function setLanguage($tag){
		$this->_langTag = strtolower(strtr($tag,'-','_'));
		$this->_tbl_lang = $this->_tbl . '_' . $this->_langTag;
	}

	public function getTranslatableFields() {

		return $this->_translatableFields;
	}

	public function setLockable() {

		$this->locked_on = '';
		$this->locked_by = 0;
		$this->setConvertInt(array('locked_by'));
		$this->setDateFields(array('locked_on'));
	}

	function setOrderable($key = 'ordering', $auto = true) {

		$this->_orderingKey = $key;
		$this->_orderable = 1;
		$this->_autoOrdering = $auto;
		$this->{$key} = 0;
		$this->setConvertInt(array($key));
	}

	function setHashable($key){
		$this->_hashName = $key;
	}

	function setOmittedHashFields(array $fields){
		$this->_omittedHashFields = $fields;
	}

	function setSlug($slugAutoName, $key = 'slug') {

		$this->_slugAutoName = $slugAutoName;
		$this->_slugName = $key;
		$this->{$key} = '';
		$this->setUniqueName($key);

	}

	var $_tablePreFix = '';

	function setTableShortCut($prefix) {

		$this->_tablePreFix = $prefix . '.';
	}

	/**
	 * Method to set rules for the record.
	 *
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   mixed  $input  A JAccessRules object, JSON string, or array.
	 * @return  void
	 * @since   11.1
	 */
	public function setRules($input)
	{
		if ($input instanceof JAccessRules)
		{
			$this->_rules = $input;
		}
		else
		{
			$this->_rules = new JAccessRules($input);
		}
	}

	/**
	 * Method to get the rules for the record.
	 *
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @return  JAccessRules object
	 * @since   11.1
	 */
	public function getRules()
	{
		return $this->_rules;
	}


	public function emptyCache(){
		$this->_loaded = false;
		self::$_cache = array();
	}

	public function setConvertInt(array $toConvert){
		$this->_toConvertInt = array_merge($this->_toConvertInt, $toConvert);
	}

	public function convertInt(){
		if(isset($this->_toConvertInt)){
			foreach($this->_toConvertInt as $f){
				if(!empty($this->{$f})){
					$this->{$f} = intval(str_replace(array(',',' '),array('.',''),$this->{$f}));
				} else if(isset($this->{$f})){
					$this->{$f} = 0;
				}
			}
		}
	}

	/**
	 * @param $toConvert array
	 */
	public function setConvertDecimal(array $toConvert) {
		$this->_toConvertDec = array_merge($this->_toConvertDec, $toConvert);
	}

	public function convertDec(){

		if(isset($this->_toConvertDec)){
			foreach($this->_toConvertDec as $f){
				if(!empty($this->{$f})){
					$this->{$f} = floatval(str_replace(array(',',' '),array('.',''),$this->{$f}));
				} else if(isset($this->{$f})){
					$this->{$f} = 0.0;
				}
			}
		}
	}

	/**
	 * Fields to ensure to be dates, this function uses array_merge
	 * @param array $dateFields
	 */
	public function setDateFields(array $dateFields){
		$this->_dateFields = array_merge($dateFields, $this->_dateFields);
	}

	protected static $nullDate = null;
	public function convertDateFields(){
		$this->_dateFields = array_unique($this->_dateFields);
		if(!isset(self::$nullDate)) self::$nullDate = $this->_db->getNullDate();
		if($this->_dateFields){
			foreach($this->_dateFields as $f){
				if(!empty($this->{$f}) and $this->{$f}!= self::$nullDate){
					$date = new JDate($this->{$f});
					$this->{$f} = $date->toSQL();
				} else {
					/*if(JVM_VERSION<4){
						$this->{$f} = self::$nullDate;	//Works maybe also in j4
					} else {*/
						$this->{$f} = null;
					//}
				}
			}
		}
	}

	static function checkFieldNullDateSQL($fieldname){

		return ' ( ISNULL(`'.$fieldname.'`) or `'.$fieldname.'`="0000-00-00 00:00:00") ';
	}

	/**
	 * This function defines a database field as parameter field, which means that some values get injected there
	 * As delimiters are used | for the pair and = for key, value
	 *
	 * @author Max Milbers
	 * @param string $paramsFieldName
	 * @param string $varsToPushParam
	 * @param boolean $overwrite
	 */
	function setParameterable($paramsFieldName, $varsToPushParam, $overwrite = false) {

		//if($this->_xParams===0)
		$this->_xParams = $paramsFieldName;

		if ($overwrite) {
			$this->_varsToPushParam = $varsToPushParam;
		} else {
			$this->_varsToPushParam = array_merge((array)$varsToPushParam, (array)$this->_varsToPushParam);
		}

		foreach ($this->_varsToPushParam as $k => $v) {
			if (!isset($this->{$k})) $this->{$k} = $v[0];
		}
		//vmdebug('setParameterable called '.$this->_xParams,$this->_varsToPushParam);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/bind
	 * @since   11.1
	 */
	public function bind($src, $ignore = array())
	{
		$this->_update = null;
		// If the source value is not an array or object return false.
		if (!is_object($src) && !is_array($src))
		{
			$e = new JException(vmText::sprintf('JLIB_DATABASE_ERROR_BIND_FAILED_INVALID_SOURCE_ARGUMENT', get_class($this)));
			vmError($e);
			return false;
		}

		// If the source value is an object, get its accessible properties.
		if (is_object($src))
		{
			$src = get_object_vars($src);
		}

		// If the ignore value is a string, explode it over spaces.
		if (!is_array($ignore))
		{
			$ignore = explode(' ', $ignore);
		}

		// Bind the source value, excluding the ignored fields.
		foreach ($this->getProperties() as $k => $v)
		{
			// Only process fields not in the ignore array.
			if (!in_array($k, $ignore))
			{
				if (isset($src[$k]))
				{
					$this->{$k} = $src[$k];
				}
			}
		}

		return true;
	}


	/**
	 * Maps the parameters to a subfield. usefull for the JForm
	 * @author Max Milbers
	 * @param $obj
	 * @param $varsToPush
	 * @param string $field
	 */
	static function bindParameterableToSubField(&$obj,$varsToPush,$field ='params'){
		foreach($varsToPush as $name=>$values){
			if(isset($obj->{$name})){
				$obj->{$field}->{$name} = $obj->{$name};
			} else {
				$obj->{$field}->{$name} = $values[0];
			}
		}
	}

	/**
	 * This function must be
	 * Takes the bounded values at obj of the field $xParams
	 * and adds them as attributs of obj
	 * @param $obj
	 * @param $xParams
	 * @param $varsToPushParam
	 */
	static function bindParameterable(&$obj, $xParams, $varsToPushParam) {

		if(empty($varsToPushParam)) return;

		if (empty($xParams)) {
			//vmError('There are bindParameterables, but $xParams is empty, this is a programmers error ',$varsToPushParam);
			vmdebug('There are bindParameterables, but $xParams is empty, this is a programmers error ', $obj);
			vmTrace('$xParams is empty');
		}

		//$paramFields = $obj->$xParams;
		//vmdebug('$obj->_xParams '.$xParams.' $varsToPushParam ',$obj->$xParams,$varsToPushParam);
		if(is_object($obj)){

			if (!empty($obj->{$xParams})) {
				if (!is_string($obj->{$xParams})){
					vmError('vmTable Error, $obj->{$xParams} is not a string '.vmEcho::varPrintR($obj));
					return;
				}
				$params = explode('|', $obj->{$xParams});
				foreach ($params as $param) {

					if(empty($param)) continue;

					$item = explode('=', $param);
					$key = $item[0];
					if(isset($item[1])){
						if(count($item)>2){
							unset($item[0]);
							$value = implode('=', $item);
						} else {
							$value = $item[1];
						}

					} else {
						vmdebug('bindParameterable key/value pair not valid ',$param);
						vmError('bindParameterable key/value pair not valid '.$param,'',7);
					}

					//unset($item[0]);

					if(isset($varsToPushParam[$key][1])) {
						//$item = implode('=', $item);
						$item = vmJsApi::safe_json_decode($value,false);
						if ($item !== null){
							$obj->{$key} = $item;
						} else {
							//vmdebug('bindParameterable $item ==null '.$key,$varsToPushParam[$key]);
						}
					}
					//else {
					//	Unsolicited Parameter
					//}
				}

			} else {

				if(!property_exists($obj,$xParams)){
					//vmError('There are bindParameterables, but $obj->{$xParams} is empty, this is a programmers error '.$xParams);
					vmdebug('There are bindParameterables, but $obj->{$xParams} is not isset, this is a programmers error ',$xParams , $obj);
					vmTrace('$obj->{$xParams} is not isset');
				}

			}

			foreach ($varsToPushParam as $key => $v) {
			    if(empty($key)){
                    vmdebug('Something went wrong in vmTable bindParameterable, empty key for $varsToPushParam',$varsToPushParam,$obj);
                    vmTrace('vmTable key empty');
                } else if (!isset($obj->{$key})) {
					$obj->{$key} = $v[0];
				}
			}
		} else {
			//vmdebug('bindParameterable array ',$obj[$xParams]);
			if (!empty($obj[$xParams])) {
				$params = explode('|', $obj[$xParams]);
				foreach ($params as $item) {

					$item = explode('=', $item);
					$key = $item[0];
					unset($item[0]);

					if (isset($item) && isset($varsToPushParam[$key][1])) {
						$item = implode('=', $item);
						$item = vmJsApi::safe_json_decode($item,false);
						if ($item != null){
								$obj[$key] = $item;

							//$obj[$key] = html_entity_decode($item);
						}
					}
				}
			} else {

				if($obj[$xParams]==null){
					//vmError('There are bindParameterables, but $obj->{$xParams} is empty, this is a programmers error '.$xParams);
					vmdebug('There are bindParameterables, but $obj[$xParams] is empty, this is a programmers error ',$xParams , $obj);
					vmTrace('$obj[$xParams] is empty');
				}
			}

			foreach ($varsToPushParam as $key => $v) {
				if (!isset($obj[$key])) {
					$obj[$key] = $v[0];
				}
			}
		}

	}

	/**
	 * Sets fields encrypted
	 * @author Max Milbers
	 * @param $fieldNames
	 */
	public function setCryptedFields($fieldNames){
		if(!$fieldNames){
			vmTrace('setEncrytped fields false not catched');
			return;
		}
		if(!is_array($fieldNames)) $fieldNames = array($fieldNames);
		if(isset($fieldNames[$this->_pkey])){
			unset($fieldNames[$this->_pkey]);
		}
		$this->_cryptedFields = $fieldNames;
	}

	/**
	 *
	 */
	public function getCryptedFields(){
		return $this->_cryptedFields;
	}


	/**
	 * Gives Back the columns of the current table, sets the properties on the table.
	 *
	 * @author Max Milbers
	 * @param int $typeKey use "Field" to get the effect of getTableColumns
	 * @param int $typeValue use "Type" to get the effect of getTableColumns
	 * @param bool $properties disable setting of columns as table properties
	 */
	public function showFullColumns($typeKey=0,$typeValue=0,$properties=true){

		$hash = 'SFL'.$this->_tbl.$typeKey.$typeValue;
		if (!isset(self::$_cache[$hash])) {//vmSetStartTime('showFullColumns');
			$this->_db->setQuery('SHOW FULL COLUMNS  FROM `'.$this->_tbl.'` ') ;
			self::$_cache[$hash] = $this->_db->loadAssocList();
			//vmTime('showFullColumns','showFullColumns');
		}

		if ($properties and count(self::$_cache[$hash]) > 0) {
			foreach (self::$_cache[$hash] as $key => $_f) {
				$_fieldlist[$_f['Field']] = $_f['Default'];
			}
			$this->setProperties($_fieldlist);
		}

		if ($typeKey or $typeValue){
			foreach (self::$_cache[$hash] as $field){
				if(empty($typeValue)){
					$value = $field;
				} else {
					$value = $field[$typeValue];
				}
				if($typeKey){
					$result[$field[$typeKey]] = $value;
				} else {
					$result[] = $value;
				}
			}
		} else {
			$result = self::$_cache[$hash];
		}

		return $result;
	}

	public function loadFields(){
		return $this->showFullColumns();
	}

	static public function checkTableExists($table){
		$db = JFactory::getDBO();
		$q = 'SHOW TABLES LIKE "'.$db->getPrefix().$table.'"';
		$db->setQuery($q);
		$t = $db->loadResult();

		if($t==false){
			return false;
		} else {
			return true;
		}
	}


	function checkDataContainsTableFields($from, $ignore = array()) {

		if (empty($from))
			return false;
		$fromArray = is_array($from);
		$fromObject = is_object($from);

		if (!$fromArray && !$fromObject) {
			vmError(get_class($this) . '::check if data contains table fields failed. Invalid from argument <pre>' . print_r($from, 1) . '</pre>');
			return false;
		}
		if (!is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}
		$properties = $this->getProperties();
		foreach ($properties as $k => $v) {
			// internal attributes of an object are ignored
			if (!in_array($k, $ignore)) {

				if ($fromArray && isset($from[$k])) {
					return true;
				} else if ($fromObject && isset($from->{$k})) {
					return true;
				}
			}
		}
		vmdebug('VmTable developer notice, table ' . get_class($this) . ' means that there is no data to store. When you experience that something does not get stored as expected, please write in the forum.virtuemart.net',$properties);
		return false;
	}

	/**
	 * Method to provide a shortcut to binding, checking and storing a JTable
	 * instance to the database table.  The method will check a row in once the
	 * data has been stored and if an ordering filter is present will attempt to
	 * reorder the table rows based on the filter.  The ordering filter is an instance
	 * property name.  The rows that will be reordered are those whose value matches
	 * the JTable instance for the property specified.
	 *
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   mixed   $src             An associative array or object to bind to the JTable instance.
	 * @param   string  $orderingFilter  Filter for the order updating
	 * @param   mixed   $ignore          An optional array or space separated list of properties
	 * to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link	http://docs.joomla.org/JTable/save
	 * @since   11.1
	 */
	public function save($src, $orderingFilter = '', $ignore = '')
	{
		// Attempt to bind the source to the instance.
		if (!$this->bind($src, $ignore))
		{
			return false;
		}

		// Run any sanity checks on the instance and verify that it is ready for storage.
		if (!$this->check())
		{
			return false;
		}

		// Attempt to store the properties to the database table.
		if (!$this->store())
		{
			return false;
		}

		// Attempt to check the row in, just in case it was checked out.
		if (!$this->checkin())
		{
			return false;
		}

		// If an ordering filter is set, attempt reorder the rows in the table based on the filter and value.
		if ($orderingFilter)
		{
			$filterValue = $this->{$orderingFilter};
			$this->reorder($orderingFilter ? $this->_db->quoteName($orderingFilter) . ' = ' . $this->_db->Quote($filterValue) : '');
		}


		return true;
	}


	/**
	 * Function setting the loggable data hack procted
	 * In case you want to override the value for administrators, just set the created_on to "0000-00-00 00:00:00"
	 *
	 * @author Max Milbers
	 */
	function setLoggableFieldsForStore() {

		if ($this->_loggable) {

			//We store in UTC time, dont touch it!
			$date = JFactory::getDate();
			$today = $date->toSQL();
			//vmdebug('my today ',$date);

			$userId = JFactory::getUser()->id;

			$admin = vmAccess::manager('core');
			if (!isset(self::$nullDate)) self::$nullDate = $this->_db->getNullDate();

			if (!$admin) {
				unset($this->created_on);
				unset($this->created_by);
				unset($this->modified_on);
				unset($this->modified_by);
			}
			if ($this->_update === true) {

				$this->modified_on = $today;
				$this->modified_by = $userId;

				if ($admin) {
					if (empty($this->created_on) or $this->created_on == self::$nullDate) {
						unset($this->created_on);
					}
					if (empty($this->created_by)) {
						unset($this->created_by);
					}
				}

			} else {

				$this->created_on = $today;
				$this->created_by = $userId;

				if ($admin) {
					if (empty($this->modified_on) or $this->modified_on == self::$nullDate) {
						unset($this->modified_on);
					}
					if (empty($this->modified_by)) {
						unset($this->modified_by);
					}
				}
			}

			if (isset($this->locked_on)) {
				//Check if user is allowed to store, then disable or prevent storing
				$this->locked_on = null;
			}
		}
	}

	/**
	 *
	 * @param $obj
	 * @param $src
	 * @param array $ignore
	 * @return bool
	 */
	static public function bindTo(&$obj, $src, $internals = false, $ignore = array()) {

		if(empty($src)) return false;

		if (is_object($src)) {
			$src = get_object_vars($src);
		}

		if(!is_array($src)) return false;

		$isIndexed = array_values($src) === $src;
		if($isIndexed) return false;

		// If the ignore value is a string, explode it over spaces.
		if (!empty($ignore) and !is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}

		foreach (get_object_vars($obj) as $k => $v) {
			if(!$internals and '_' == substr($k, 0, 1)) continue;
			// Only process fields not in the ignore array.
			if (!in_array($k, $ignore)) {
				if (isset($src[$k])) {
					$obj->{$k} = $src[$k];
				}
			}
		}

		return true;
	}

	/**
	 * Technic to inject params as table attributes
	 * @author Max Milbers
	 * $TableJoins array of table names to add and left join to find ID
	 */
	function load($oid = null, $overWriteLoadName = 0, $andWhere = 0, $tableJoins = array(), $joinKey = 0) {

		if($this->_translatable)vmSetStartTime('vmtableload');
		if( $overWriteLoadName!==0 ){
			$k = $overWriteLoadName;
		} else {
			$k = $this->_pkey;
		}

		if ($oid !== null) {
			$this->{$k} = $oid;
		} else {
			$oid = $this->{$k};
		}

		if (empty($oid)) {
			if (!empty($this->_xParams)) {
				if(!empty($this->_varsToPushParam)){
					foreach ($this->_varsToPushParam as $key => $v) {
						if (!isset($this->{$key})) {
							$this->{$key} = $v[0];
						}
					}
				} else {
					//vmdebug('_varsToPushParam empty ',$this);
				}
			}
			$this->convertDec();
			//vmdebug('vmtable load empty $oid return proto',$this);
			return $this;
		}

		//Version load the tables using JOIN
		if ($this->_translatable) {
			$mainTable =  $this->_tbl;
			$langTable = $this->_tbl . '_' . $this->_langTag;

			$select = 'SELECT `' . $mainTable . '`.* ,`' . $langTable . '`.* ';
			$from = ' FROM `' . $mainTable . '` INNER JOIN `' . $langTable . '` ON `' . $mainTable . '`.`' . $this->_tbl_key . '` = `' . $langTable . '`.`' . $this->_tbl_key . '` ';
		} else {
			$mainTable = $this->_tbl;
			$select = 'SELECT `' . $mainTable . '`.* ';
			$from = ' FROM `' . $mainTable . '` ';
		}

		if (count($tableJoins)) {
			if (!$joinKey) $joinKey = $this->_tbl_key;

			foreach ($tableJoins as $tableId => $table) {

				if(strpos($tableId,',')!==false){
					$tableIds = explode(',',$tableId);
					foreach($tableIds as $sel){
						if(strpos($sel,' as ')!==false){
							$temp = explode(' as ',$sel);
							$select .= ',`' . $table . '`.`' . trim($temp[0]) . '` as '.$temp[1].' ';
						} else {
							$select .= ',`' . $table . '`.`' . $sel . '` ';
						}
					}
				} else {
					$select .= ',`' . $table . '`.`' . $tableId . '` ';
				}

				$from .= ' LEFT JOIN `' . $table . '` on `' . $table . '`.`' . $joinKey . '`=`' . $mainTable . '`.`' . $joinKey . '`';
			}
		}

		//the cast to int here destroyed the query for keys like virtuemart_userinfo_id, so no cast on $oid
		// $query = $select.$from.' WHERE '. $mainTable .'.`'.$this->_tbl_key.'` = "'.$oid.'"';
		if ($andWhere === 0) $andWhere = '';


		if($this->_translatable and in_array($k,$this->_translatableFields)){
			$whereTable = $langTable;
		} else {
			$whereTable = $mainTable;
		}

		$query = $select . $from . ' WHERE `' . $whereTable . '`.`' . $k . '` = "' . $oid . '" ' . $andWhere;

		//We dont need the $hashVarsToPush in the has, because the parameteres are later bind to it.
		$this->_lhash = $this->getHash($oid. $select . $k . $mainTable . $andWhere /*. $hashVarsToPush*/);

		if (isset (self::$_cache['l'][$this->_lhash])) {
			$this->bind(self::$_cache['l'][$this->_lhash]);
			if (!empty($this->_xParams) and !empty($this->_varsToPushParam)) {
				self::bindParameterable($this, $this->_xParams, $this->_varsToPushParam);
			}
			if($this->_cryptedFields){
				$this->decryptFields();
			}
			$this->convertDec();
			$this->_update = true;
			//vmTime('loaded by cache '.$this->_pkey.' '.$this->_slugAutoName.' '.$oid,'vmtableload');
			return $this;
		} else {

		}

		$db = $this->getDBO();
		$db->setQuery($query);
//vmdebug('vmTable load ',str_replace('#__',$db->getPrefix(),$db->getQuery()));
		if($this->_translatable and empty($this->_langTag)) {
			vmTrace('vmTable the lang tag is empty!');
			return $this;
		}
		$result = $db->loadAssoc();

		//if(!empty($this->slug))vmdebug('vmtable load $query',$this->_langTag,$query,$result->slug);
		if ($result) {
			$this->_loaded = true;
			$this->_update = true;

			$this->bind($result);
			if (!empty($this->_xParams)) {
				//Maybe better to use for $this an &
				self::bindParameterable($this, $this->_xParams, $this->_varsToPushParam);
			}

			if (count($tableJoins)) {
				foreach ($tableJoins as $tableId => $table) {

					if(strpos($tableId,',')!==false){

						$tableIds = explode(',',$tableId);
						foreach($tableIds as $sel){

							if(strpos($sel,' as ')!==false){
								$temp = explode(' as ',$sel);
								$key = trim($temp[1]);
								//vmdebug('my $result ',$result[$key]);
								if (isset($result[$key])) $this->{$key} = $result[$key]; else $this->{$key} = false;

							} else {
								if (isset($result[$sel])) $this->{$sel} = $result[$sel];
							}
						}
					} else {

						if (isset($result[$tableId])) $this->{$tableId} = $result[$tableId];
					}
				}
			}
		} else {

			//$langCount -1, because the first language was already loaded as default
			if($this->_translatable and vmLanguage::$langCount>1 /*and $this->_langTag!=VmConfig::$defaultLang*/ and self::$loadedX<=2 ){
				//if(empty($this->_ltmp))

				//vmdebug('Load fallback '.self::$loadedX.' times '.$this->_pkey,$this->{$this->_pkey},$this->_langTag,$this->_ltmp);
				if(!$this->_ltmp){
					$first = true;
					$this->_ltmp = $this->_langTag;
				} else {
					$first = false;
					self::$loadedX++;
				}

				if(VmConfig::$defaultLang!=VmConfig::$jDefLang){
					if($this->_langTag != VmConfig::$defaultLang ){

						$this->_langTag = VmConfig::$defaultLang;
						$this->_loadedWithLangFallback = VmConfig::$defaultLangTag;
					} else {
						$this->_langTag = VmConfig::$jDefLang;
						$this->_loadedWithLangFallback = VmConfig::$jDefLangTag;
					}

				} else {
					//$this->_ltmp = $this->_langTag;
					$this->_langTag = VmConfig::$defaultLang;
					$this->_loadedWithLangFallback = VmConfig::$jDefLangTag;
				}

				//vmdebug('No result for table '.$this->_ltmp.' testing '.self::$loadedX.' times Fallback '.$this->_langTag.' '.$this->_pkey.' = '.$oid.'  '.$this->_slugAutoName);

				vmSetStartTime('lfallback');
				$lhash = $this->_lhash;
				$this->load($oid, $overWriteLoadName, $andWhere, $tableJoins, $joinKey) ;
				//vmTime('Time to load language fallback '.$this->_langTag, 'lfallback');
				self::$_cache['l'][$lhash] = $this->loadFieldValues(false);

				//if(!empty($this->slug))vmdebug('Set $this->_langTag '.$this->_langTag.' back to Ltmp '.$this->_ltmp,$this->slug);
				if($this->_ltmp and $first){
					$this->_langTag = $this->_ltmp;
					$this->_ltmp = false;
				}
				return $this;
			} else {
				$this->_loaded = false;
			}
		}

		self::$_cache['l'][$this->_lhash] = $this->loadFieldValues(false);

		if($this->_cryptedFields){
			$this->decryptFields();
		}

		$this->convertDec();

		self::$loadedX = 1;	//We just load the first fallback
		//if($this->_translatable) vmTime('loaded '.$this->_langTag.' '.$mainTable.' '.$oid ,'vmtableload');

		return $this;
	}

	static $loadedX = 0;

	function getHash($value) {
		$hashFunction = VmConfig::get('hashFunction', 'crc32');
		return call_user_func_array($hashFunction, array(&$value));
	}


	function getLoaded (){
		return $this->_loaded;
	}

	/**
	 * Typo, had wrong name
	 * @deprecated heavily
	 */
	function encryptFields(){
		$this->decryptFields();
	}

	function decryptFields(){

		if(isset($this->modified_on) and $this->modified_on!='0000-00-00 00:00:00'){
			$date = JFactory::getDate($this->modified_on);
			$date = $date->toUnix();
		} else if(isset($this->created_on) and $this->created_on!='0000-00-00 00:00:00'){
			$date = JFactory::getDate($this->created_on);
			$date = $date->toUnix();
		} else {
			$date = 0;
		}

		foreach($this->_cryptedFields as $field){
			if(isset($this->{$field})){
				$this->{$field} = vmCrypt::decrypt($this->{$field}, $date);
				//vmdebug($this->_tbl.' Field '.$field.' encrypted = '.$this->{$field});
			}
		}
	}

	/**
	 * Derived from JTable
	 * Records in this table do not need to exist, so we might need to create a record even
	 * if the primary key is set. Therefore we need to overload the store() function.
	 * Technic to inject params as table attributes and to encrypt data
	 * @author Max Milbers
	 * @copyright	for derived parts, (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @see libraries/joomla/database/JTable#store($updateNulls)
	 */
	function store($updateNulls = false) {

		if($this->_cryptedFields){
			foreach($this->_cryptedFields as $field){
				if(isset($this->{$field})){
					$this->{$field} = vmCrypt::encrypt($this->{$field});
				}
			}
		}

		$this->convertDateFields();

		$this->storeParams();

		if (!empty($this->asset_id)) {
			$currentAssetId = $this->asset_id;
		}

		// The asset id field is managed privately by this class.
		if ($this->_trackAssets) {
			unset($this->asset_id);
		}


		if(!empty($this->_hashName)){
			$this->hashEntry();
		}

		$tblKey = $this->_tbl_key;
		$ok = true;
		if($this->_update===null){
			if(!$this->check()){
				return false;
			}
			//$this->_update = !empty($this->{$tblKey});
			//$msg = 'vmTable function store was called without prior calling check';
			//vmTrace($msg.' this is deprecated, call function check now. This fallback will be removed in future', true, 6);
		}
		//vmdebug('vmTable Storing ',$this->loadFieldValues());

		$this->setLoggableFieldsForStore();

		if($this->_update === true){
			if(empty($this->{$tblKey})){
				vmdebug('Update has empty $tblKey ', $this->loadFieldValues());
				return false;
			}

			try {
				$ok = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
			} catch (Exception $e){
				$query = $this->_db->getQuery();
				vmError('vmTable store updateObject '.$e->getMessage().' '.$this->_db->replacePrefix($query), 'vmTable store updateObject ', 7);
				vmTrace('vmTable store updateObject ');
			}

		} else {
			$p = $this->{$tblKey};
			//vmdebug('vmTable '.get_class($this).' store insertObject Inserting '.$this->{$tblKey}.' ',$this->loadFieldValues(true));

			try {
				$ok = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
			} catch (Exception $e){
				$query = $this->_db->getQuery();
				vmError('vmTable store insertObject '.$this->_tbl.' '.$e->getMessage().' '.$this->_db->replacePrefix($query), 'vmTable store insertObject ', 7);
				vmTrace('vmTable store insertObject with error');
			}

			if($ok and !empty($this->_hashName)){
				$oldH= $this->{$this->_hashName};
				if($p!=$this->{$tblKey} and !in_array($tblKey,$this->_omittedHashFields)){
					$this->hashEntry();
					try {
						$ok = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
					} catch (Exception $e){
						vmError('vmTable store updateObject hash '.$e->getMessage());
					}

					vmdebug('VmTable Updated entry with correct hash ',$this->_tbl_key,$p,$this->{$tblKey},$oldH,$this->{$this->_hashName});
				}
			}
		}

		$this->_update = null;
		// If the store failed return false.
		if (!$ok) {
			vmError(vmText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED', get_class($this), $e->getMessage()));
			return false;
		}

		//reset Params
		if(isset($this->_tmpParams) and is_array($this->_tmpParams)){
			foreach($this->_tmpParams as $k => $v){
				$this->{$k} = $v;
			}
		}
		$this->_tmpParams = false;

		//decrypt the Fields
		if($this->_cryptedFields){
			$this->decryptFields();
		}


		// If the table is not set to track assets return true.
		if (!$this->_trackAssets) {
			return true;
		}

		if ($this->_locked) {
			$this->_unlock();
		}

		$parentId = $this->_getAssetParentId();
		$name = $this->_getAssetName();
		$title = $this->_getAssetTitle();

		$asset = JTable::getInstance('Asset', 'JTable', array('dbo' => $this->getDbo()));
		$asset->loadByName($name);

		// Re-inject the asset id.
		$this->asset_id = $asset->id;

		// Check for an error.
		if ($error = $asset->getError()){
			vmError($error);
			return false;
		}

		// Specify how a new or moved node asset is inserted into the tree.
		if (empty($this->asset_id) || $asset->parent_id != $parentId) {
			$asset->setLocation($parentId, 'last-child');
		}

		// Prepare the asset to be stored.
		$asset->parent_id = $parentId;
		$asset->name = $name;
		$asset->title = $title;

		if ($this->_rules instanceof JAccessRules) {
			$asset->rules = (string) $this->_rules;
		}

		if (!$asset->check() || !$asset->store($updateNulls)) {
			vmError($asset->getError());
			return false;
		}

		// Create an asset_id or heal one that is corrupted.
		if (empty($this->asset_id) || ($currentAssetId != $this->asset_id && !empty($this->asset_id))) {
			// Update the asset_id field in this table.
			$this->asset_id = (int) $asset->id;

			$query = $this->_db->getQuery(true);
			$query->update($this->_db->quoteName($this->_tbl));
			$query->set('asset_id = ' . (int) $this->asset_id);
			$query->where($this->_db->quoteName($tblKey) . ' = ' . (int) $this->{$tblKey});
			$this->_db->setQuery($query);

			try {
				$this->_db->execute();
			} catch (Exception $e) {
				$em = vmText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED_UPDATE_ASSET_ID', $e->getMessage);
				vmError($em);
				return false;
			}
		}

		return $ok;
	}


	function storeParams() {

		if (!empty($this->_xParams) and !empty($this->_varsToPushParam)) {
			$paramFieldName = $this->_xParams;
			$this->{$paramFieldName} = '';
			$this->_tmpParams = array();
			foreach ($this->_varsToPushParam as $key => $v) {

				if (isset($this->{$key})) {
					$this->{$paramFieldName} .= $key . '=' . vmJsApi::safe_json_encode($this->{$key}) . '|';
					$this->_tmpParams[$key] = $this->{$key};
				} else {
					$this->{$paramFieldName} .= $key . '=' . vmJsApi::safe_json_encode($v[0]) . '|';
					$this->_tmpParams[$key] = $v[0];
				}

				unset($this->{$key});
			}
		}
		return true;
	}


	function checkCreateUnique($tbl_name, $name) {

		$i = 0;

		while ($i < 40) {

			$tbl_key = $this->_tbl_key;
			$q = 'SELECT `' . $name . '` FROM `' . $tbl_name . '` WHERE `' . $name . '` =  "' . $this->_db->escape($this->{$name}) . '" ';
			if(!empty($this->{$tbl_key})){
				$q .= '  AND `' . $this->_tbl_key . '`!="' . $this->_db->escape($this->{$tbl_key}).'" ';
			}
			$this->_db->setQuery($q);
			$existingSlugName = $this->_db->loadResult();

			if (!empty($existingSlugName)) {

				if($posNbr = strrpos($this->{$name},'-')){
					$existingNbr = substr($this->{$name},$posNbr+1);

					if(is_numeric($existingNbr)){
						$existingNbr++;
						$this->{$name} = substr($this->{$name},0,$posNbr+1) . $existingNbr;
					} else{
						$this->{$name} = $this->{$name} . '-1';
					}
				} else {
					$this->{$name} = $this->{$name} . '-1';
				}
				vmdebug('checkCreateUnique '.$name.' = '.$existingSlugName.' changed to '.$this->{$name}.' '.$q);
			} else {
				return true;
			}
			$i++;
		}

		return false;

	}

	function setCheckVendorId(){
		if(empty($this->virtuemart_vendor_id) and $this->_pkey=='virtuemart_vendor_id'){
			$this->virtuemart_vendor_id = $this->{$this->_pkey};
		}

		$multix = Vmconfig::get('multix', 'none');
		//Lets check if the user is admin or the mainvendor
		$virtuemart_vendor_id = false;
		//Quickndirty is removed and set in derived classes (per override or $this->_genericVendorId = false;), but cant be avoided for the vendor language tables
		if ($multix == 'none' or strpos($this->_tbl,'virtuemart_vendors')!==FALSE) {
			if ($multix == 'none'){
				$this->virtuemart_vendor_id = 1;
			}
			return true;
		} else {
			//$user = JFactory::getUser();
			//$loggedVendorId = vmAccess::isSuperVendor($user->id);
			$loggedVendorId = vmAccess::isSuperVendor();
			//vmdebug('Table '.$this->_tbl.' check $loggedVendorId '.$loggedVendorId);
			$user_is_vendor = 0;
			$tbl_key = $this->_tbl_key;
			$className = get_class($this);

			$admin = vmAccess::manager('managevendors');
			//Todo removed Quickn Dirty, use check in derived class
			if (strpos($this->_tbl,'virtuemart_vendors')===FALSE) {
				if(!empty($this->{$tbl_key})){
					$q = 'SELECT `virtuemart_vendor_id` FROM `' . $this->_tbl . '` WHERE `' . $this->_tbl_key . '`="' . $this->{$tbl_key} . '" ';
					if (!isset(self::$_cache[md5($q)])) {
						$this->_db->setQuery($q);
						self::$_cache[md5($q)] = $virtuemart_vendor_id = $this->_db->loadResult();
					} else $virtuemart_vendor_id = self::$_cache[md5($q)];
				} else {

				}

			} else {

				Echo 'very wrong here'; return false;
			}

			if (!$admin and !empty($virtuemart_vendor_id) and !empty($loggedVendorId) and $loggedVendorId != $virtuemart_vendor_id) {
				//Todo removed Quickn Dirty, use check in derived class
				//This is the case when a vendor buys products of vendor1
				if (strpos($this->_tbl,'virtuemart_orders')===FALSE and strpos($this->_tbl,'virtuemart_order_items')===FALSE and strpos($this->_tbl,'virtuemart_carts')===FALSE) {
					vmdebug('Blocked storing, logged vendor ' . $loggedVendorId . ' but data belongs to ' . $virtuemart_vendor_id,$this->_tbl);
					VmError('Blocked storing of the object, you are not the owner');
					return false;
				} else {
					$this->virtuemart_vendor_id = $virtuemart_vendor_id;
				}

			} else if (!$admin) {
				if ($virtuemart_vendor_id) {
					$this->virtuemart_vendor_id = $virtuemart_vendor_id;
					vmdebug('Non admin is storing using loaded vendor_id');
				} else {
					if(empty($this->virtuemart_vendor_id) ){
						$this->virtuemart_vendor_id = $loggedVendorId;
					}
					//No id is stored, even users are allowed to use for the storage and vendorId, no change
				}

			} else {
				//Admins are allowed to do anything. We just trhow some messages
				if (!empty($virtuemart_vendor_id) and $loggedVendorId != $virtuemart_vendor_id) {
					vmdebug('Admin with vendor id ' . $loggedVendorId . ' is using for storing vendor id ' . $this->virtuemart_vendor_id);
				}
				else if (empty($virtuemart_vendor_id) and empty($this->virtuemart_vendor_id)) {
					if(strpos($this->_tbl,'virtuemart_vendors')===FALSE and strpos($this->_tbl,'virtuemart_vmusers')===FALSE){
						$this->virtuemart_vendor_id = $loggedVendorId;
						vmdebug('Fallback to '.$this->virtuemart_vendor_id.' for $loggedVendorId '.$loggedVendorId.': We run in multivendor mode and you did not set any vendor for '.$className.' and '.$this->_tbl);
					}
				}
			}
		}
		return true;
	}

	function hashEntry($set = true){

		$fields = $this->getProperties();

		unset($fields[(string)$this->_hashName]);
		if(!empty($this->_omittedHashFields)){
			foreach($this->_omittedHashFields as $prop){
				unset($fields[(string)$prop]);
			}
		}

		$toHash = serialize($fields);
		$h =  hash('md5',$toHash);
		if($set ) {
			$hashName = $this->_hashName;
			$this->{$hashName} = $h;
		}

		return $h;

	}

	function integrity(){

		$hashName = $this->_hashName;
		$oldHash = $this->{$hashName};
		$hash = $this->hashEntry(false);

		if($oldHash==$hash){
			return true;
		} else {
			return false;
		}
	}

	function loadTblKeyByPrimaryAnd ($skey) {
		$_qry = 'SELECT `'.$this->_tbl_key.'` '
			. 'FROM `'.$this->_tbl.'` '
			. 'WHERE `'. $this->_pkey.'` = "' . $this->{$this->_pkey}.'" AND '.$skey.' = "'.$this->{$skey}.'"';
		$this->_db->setQuery($_qry);
		$this->{$this->_tbl_key} = $this->_db->loadResult();
		//vmdebug('loadTblKeyByPrimaryAnd my query here ',$_qry,$this->{$this->_tbl_key});
	}

	public $_singleEntry = false;

	/**
	 * @author Max Milbers
	 * @param
	 */
	function check() {

		$tblKey = $this->_tbl_key;
		$pKey = $this->_pkey;
		//vmdebug('vmTable check() class '.$this->_tbl);
		$this->_update = null;

		if($tblKey != $pKey) {

			if(empty($this->{$tblKey}) and empty($this->{$pKey}) ){
				vmdebug('Cannot store entry, both keys are missing');
				return false;
			}
			vmdebug('vmTable check() ',$this->_tbl_key,$this->{$tblKey},$pKey,$this->{$pKey});
			$_qry = null;
			if($this->_singleEntry and empty($this->{$this->_tbl_key}) and !empty($this->{$pKey})){

				$_qry = 'SELECT `'.$this->_tbl_key.'` '
					. 'FROM `'.$this->_tbl.'` '
					. 'WHERE `'. $pKey.'` = "' . $this->{$pKey}.'" ';

			} else {
				$_qry = 'SELECT `' . $pKey . '` '
					. 'FROM `' . $this->_tbl . '` '
					. 'WHERE `' . $tblKey . '` = "' . $this->{$tblKey} . '" ';
			}

			$this->_db->setQuery($_qry);
			$res = $this->_db->loadResult();
			vmdebug('vmTable check() if(!empty($this->{'.$this->_tbl_key.'}))',$_qry,$res);
			if($res){
				$this->_update = true;
				if (empty($this->{$pKey})) {
					$this->{$pKey} = $res;
				} else if (empty($this->{$tblKey})){
					$this->{$tblKey} = $res;
				}
			} else {
				$this->_update = false;
				if (empty($this->{$pKey})) {
					return false;
				}
			}

		} else {
			if(empty($this->{$tblKey})){
				$this->_update = false;
			} else {
				$_qry = 'SELECT `'.$tblKey.'` '
					. 'FROM `'.$this->_tbl.'` '
					. 'WHERE `'. $tblKey.'` = "' . $this->{$tblKey}.'" ';
				$this->_db->setQuery($_qry);
				$res = $this->_db->loadResult();

				if($res){
					$this->_update = true;
					//vmdebug('vmTable check() loaded existing entry '.$res.' from '.$this->_tbl.' WHERE '.$tblKey.' = '.$this->{$tblKey});
				} else {
					$this->_update = false;
					vmdebug('vmTable check() existing entry not FOUND from '.$this->_tbl.' WHERE '.$tblKey.' = '.$this->{$tblKey});
				}
				//vmdebug('Query load existing entry from '.$this->_tbl.'. Got with WHERE `'.$this->_tbl_key.' = "' . $this->{$this->_tbl_key}.'" and $_qry '.$_qry, $res );
			}
		}


		if (!empty($this->_slugAutoName)) {

			$slugAutoName = $this->_slugAutoName;
			$slugName = $this->_slugName;

			if (in_array($slugAutoName, $this->_translatableFields)) {
				$checkTable = $this->_tbl_lang;
				vmTrace('Language table in normal check?');
			} else {
				$checkTable = $this->_tbl;
			}

			if (empty($this->{$slugName})) {
				// 				vmdebug('table check use _slugAutoName '.$slugAutoName.' '.$slugName);
				if (!empty($this->{$slugAutoName})) {
					$this->{$slugName} = $this->{$slugAutoName};
				} else {

					vmError('VmTable ' . $checkTable . ' Check not passed. Neither slug nor obligatory value at ' . $slugAutoName . ' for auto slug creation is given '.$this->{$this->_tbl_key});
					return false;
				}

			}

			//if (JVM_VERSION === 1) $this->{$slugName} = JFilterOutput::stringURLSafe($this->{$slugName});
			//else $this->{$slugName} = JApplication::stringURLSafe($this->{$slugName});
			//pro+#'!"§$%&/()=?duct-w-| ||cu|st|omfield-|str<ing>
			//vmdebug('my slugName '.$slugName,$this->{$slugName});

			$this->{$slugName} = str_replace('-', ' ', $this->{$slugName});
			$this->{$slugName} = html_entity_decode($this->{$slugName},ENT_QUOTES);
			//$config =& JFactory::getConfig();
			//$transliterate = $config->get('unicodeslugs');
			$unicodeslugs = VmConfig::get('transliterateSlugs',false);
			if($unicodeslugs){
				$lang = vmLanguage::getLanguage();
				$this->{$slugName} = $lang->transliterate($this->{$slugName});
			}

			// Trim white spaces at beginning and end of alias and make lowercase
			$this->{$slugName} = trim(utf8_strtolower($this->{$slugName}));
			$this->{$slugName} = str_replace(array('`','´',"'"),'',$this->{$slugName});

			$this->{$slugName} = vRequest::filterUword($this->{$slugName},'-,_,|','-');
			while(strpos($this->{$slugName},'--')){
				$this->{$slugName} = str_replace('--','-',$this->{$slugName});
			}
			// Trim dashes at beginning and end of alias
			$this->{$slugName} = trim($this->{$slugName}, '-');

			if($unicodeslugs)$this->{$slugName} = rawurlencode($this->{$slugName});

			$valid = $this->checkCreateUnique($checkTable, $slugName);
			//vmdebug('my Final slugName '.$slugName,$this->{$slugName});
			if (!$valid) {
				return false;
			}

		}

		foreach ($this->_obkeys as $obkeys => $error) {
			if (empty($this->{$obkeys})) {
				$error = get_class($this) . ' ' .vmText::sprintf('COM_VIRTUEMART_STRING_ERROR_OBLIGATORY_KEY', 'COM_VIRTUEMART_' . strtoupper($obkeys) );
				vmError($error);
				return false;
			}
		}

		if ($this->_unique) {
			if (empty($this->_db)) $this->_db = JFactory::getDBO();
			foreach ($this->_unique_name as $obkeys => $error) {

				if (empty($this->{$obkeys})) {
					$error = vmText::sprintf('COM_VIRTUEMART_STRING_ERROR_NOT_UNIQUE_NAME', 'COM_VIRTUEMART_' . strtoupper($obkeys));
					vmError('Non unique ' . $this->_unique_name . ' ' . $error);
					return false;
				} else {

					$valid = $this->checkCreateUnique($this->_tbl, $obkeys);
					if (!$valid) {
						return false;
					}
				}
			}
		}


		if ($this->_genericVendorId and property_exists($this,'virtuemart_vendor_id') ) {
			if(!$this->setCheckVendorId()){
				return false;
			}
		}

		$this->convertInt();
		$this->convertDec();

		if(!empty($this->_hashName)){
			$this->hashEntry();
		}
		return true;
	}

	/**
	 * As shortcat, Important the & MUST be there, even in php5.3
	 *
	 * @author Max Milbers
	 * @param array/obj $data input data as assoc array or obj
	 * @param boolean $preload You can preload the data here too preserve not updated data
	 * @return array/obj $data the updated data
	 */
	public function bindChecknStore(&$data, $preload = false, $langOnly = false) {

		$tblKey = $this->_tbl_key;
		$ok = true;
		if ($this->_translatable) {

			$db = JFactory::getDBO();
			$dataTable = clone($this);
			$langTable = new VmTable($this->_tbl_lang, $tblKey, $db);
			if( strpos($this->_tbl,'virtuemart_vendors')!==FALSE) {
				$langTable->_genericVendorId = false;
			}
			$langTable->{$tblKey} = $this->{$tblKey};
			$langTable->setLanguage($this->_langTag);
			//$langTable->setPrimaryKey($tblKey);
			$langData = array();
			$langObKeys = array();
			$langUniqueKeys = array();

			if (is_object($data)) {

				foreach ($this->_translatableFields as $name) {
					$langTable->{$name} = $this->{$name};
					if (isset($data->{$name})) {
						//We directly store language stuff "escaped" escape
						//$langData[$name] = htmlspecialchars(html_entity_decode($data->$name, ENT_QUOTES, "UTF-8"), ENT_QUOTES, "UTF-8");
						$langData[$name] = $db->escape(html_entity_decode($data->{$name}, ENT_QUOTES, "UTF-8") );
					}
					unset($dataTable->{$name});

					if (!empty($this->_unique_name[$name])) {
						$langUniqueKeys[$name] = 1;
						unset($dataTable->_unique_name[$name]);
						$langObKeys[$name] = 1;
						unset($dataTable->_obkeys[$name]);
					}

					if (!empty($this->_obkeys[$name])) {
						$langObKeys[$name] = 1;
						unset($dataTable->_obkeys[$name]);
					}

				}

			} else {
				foreach ($this->_translatableFields as $name) {
					$langTable->{$name} = $this->{$name};
					if (isset($data[$name])) {
						//$langData[$name] = htmlspecialchars(html_entity_decode($data[$name], ENT_QUOTES, "UTF-8"), ENT_QUOTES, "UTF-8");
						$langData[$name] = $db->escape(html_entity_decode($data[$name], ENT_QUOTES, "UTF-8") );
					}
					unset($dataTable->{$name});

					if (!empty($this->_unique_name[$name])) {
						$langUniqueKeys[$name] = 1;
						unset($dataTable->_unique_name[$name]);
						$langObKeys[$name] = 1;
						unset($dataTable->_obkeys[$name]);
					}

					if (!empty($this->_obkeys[$name])) {
						$langObKeys[$name] = 1;
						unset($dataTable->_obkeys[$name]);
					}

				}

			}

			$langTable->_unique_name = $langUniqueKeys;
			$langTable->_obkeys = $langObKeys;

			$langTable->_slugAutoName = $this->_slugAutoName;
			unset($dataTable->_slugAutoName);

			$langTable->_slugName = 'slug';
			unset($dataTable->_slugName);

			$langTable->setProperties($langData);
			$langTable->_translatable = false;
			//We must check the langtable BEFORE we store the normal table, cause the langtable is often defining if there are enough data to store it (for exmple the name)

			if ($ok) {
				//vmdebug('my langtable before bind',$langTable->id);
				if (!$langTable->bind($data)) {
					$ok = false;
					$msg = 'bind';
					// 			vmdebug('Problem in bind '.get_class($this).' '.$this->_db->getErrorMsg());
					vmdebug('Problem in bind ' . get_class($this) . ' ');
				}
			}

			if ($ok) {
				if (!$langTable->check()) {
					$ok = false;
					vmdebug('Check $langTable returned false ' . get_class($langTable) . ' ' . $this->_tbl );
				}
			}

			if ($ok) {

				if(!$langOnly){
					$ok = $dataTable->bindChecknStoreNoLang($data, $preload);
					$this->bind($dataTable);
					$langTable->{$tblKey} = !empty($this->{$tblKey}) ? $this->{$tblKey} : 0;
					//vmdebug('bindChecknStoreNoLang my $tblKey '.$tblKey.' '.$langTable->{$tblKey});
					if ($ok and $preload) {
						if (!empty($langTable->{$tblKey})) {
							$id = $langTable->{$tblKey};
							if (!$langTable->load($id)) {
								$ok = false;
								vmdebug('Preloading of language table failed, no id given, cannot store ' . $this->_tbl);
							}
						} else {
							if ($ok) {
								if (!$langTable->bind($data)) {
									$ok = false;
									vmdebug('Problem in bind ' . get_class($this) . ' ');
								}
							}

							if ($ok) {
								if (!$langTable->check()) {
									$ok = false;
									vmdebug('Check returned false ' . get_class($langTable) . ' ' . $this->_tbl );
								}
							}
						}
					}
				}

				if ($ok) {
					if (!$langTable->store()) {
						$ok = false;
						// $msg .= ' store';
						vmdebug('Problem in store with langtable ' . get_class($langTable) . ' with ' . $tblKey . ' = ' . $this->{$tblKey} );
					} else {
						$this->bind($langTable);

					}
				}
			}


		} else {
			if (!$this->bindChecknStoreNoLang($data, $preload)) {
				$ok = false;
			}
		}

		if($ok){
			if($this->_lhash){
				self::$_cache['l'][$this->_lhash] = $this->loadFieldValues(false);
			}
		}

		return $ok;
	}


	function bindChecknStoreNoLang(&$data, $preload = false) {

		$tblKey = $this->_tbl_key;

		if ($preload) {
			if (is_object($data)) {
				if (!empty($data->{$tblKey})) {
					$this->load($data->{$tblKey});
				}
			} else {
				if (!empty($data[$tblKey])) {
					$this->load($data[$tblKey]);
				}
			}
		}

		if ($this->_translatable) {
			foreach ($this->_translatableFields as $name) {
				unset($this->{$name});
			}
		}

		$ok = true;
		$msg = '';

		if (!$this->bind($data)) {
			$ok = false;
			$msg = 'bind';
			// 			vmdebug('Problem in bind '.get_class($this).' '.$this->_db->getErrorMsg());
			vmdebug('Problem in bind ' . get_class($this) . ' ');
		}

		if ($ok) {
			if (!$this->checkDataContainsTableFields($data)) {
				$ok = false;
			}
		}

		if ($ok) {

			if (!$this->check()) {
				$ok = false;
				$msg .= ' check';
				vmdebug('Check returned false ' . get_class($this) );
				return false;
			}
		}

		if ($ok) {
			try{
				$this->store($this->_updateNulls);
			} catch (Exception $e) {
				$ok = false;
				$msg .= ' store';
				vmdebug('Problem in store ' . get_class($this).' '.$msg);
				return false;
			}
		}


		if (is_object($data)) {
			$data->{$tblKey} = !empty($this->{$tblKey}) ? $this->{$tblKey} : 0;
		} else {
			$data[$tblKey] = !empty($this->{$tblKey}) ? $this->{$tblKey} : 0;
		}

		//This should return $ok and not the data, because it is already updated due use of reference
		return $ok;
	}

	/**
	 * Description
	 * will make sure that all items in the table are not using the same ordering values
	 * @author stAn
	 * @access public
	 * $where -> limits the categories if a child category of another one
	 */
	function fixOrdering($where = '') {

		$where = !empty($where) ? ' WHERE ' . $where : '';

		$q = ' SELECT * FROM `' . $this->_tbl . '` ' . $where . ' ORDER BY `' . $this->_orderingKey . '` ASC';
		$this->_db->setQuery($q, 0, 999999);
		try{
			$res = $this->_db->loadAssocList();
		} catch (Exception $e){
			vmError(get_class($this) . $e->getMessage());
		}


		// no data in the table
		if (empty($res)) return true;
		// we will set ordering to 5,10,15,20,25 so there is enough space in between for manual editing

		$start = 5;
		// it is not really optimized to load full table into array, a while loop would be better especially when having thousands of categories
		foreach ($res as $row) {
			$q = 'UPDATE  `' . $this->_tbl . '` SET `' . $this->_orderingKey . '` = ' . (int)$start . ' WHERE `' . $this->_tbl_key . '`= ' . $row[$this->_tbl_key] . ' LIMIT 1';
			vmdebug('Fix Ordering my Update query ',$q);
			$this->_db->setQuery($q);
			$r = $this->_db->execute($q);
			$start = $start + 5;
		}


	}

	/**
	 * Description
	 *
	 * @author Joomla Team, Max Milbers
	 * @access public
	 * @param $dirn
	 * @param $where
	 */
	function move($dirn, $whereIn = '', $orderingkey = 0, $cid = 0) {

		if($cid === 0){
			$cid = vRequest::getInt($this->_pkeyForm,vRequest::getInt($this->_pkey,false));
			if(empty($cid) and !empty($this->{$this->_pkey})){
				$cid = $this->{$this->_pkey};
			}
		}


		if (!empty($cid) && (is_array($cid))) {
			$cid = reset($cid);
		} else if(empty($cid)){
			vmError(get_class($this) . ' is missing cid information !');
			return false;
		}

		if (empty($orderingkey))
			$orderingkey = $this->_orderingKey;

		if (!in_array($orderingkey, array_keys($this->getProperties()))) {
			vmError(get_class($this) . ' does not support ordering');
			return false;
		}

		$this->loadOrderingCurrentItem($cid, $orderingkey);


		$excl = ' `' . $this->_tbl_key . '` != ' . (int)$cid . ' ';
		$where = ($whereIn ? $whereIn.' AND ' . $excl : $excl);



		//Lets load the ordering of the next row, so that we can directly use the value of the next row for the ordering value of the current row
		$sql = 'SELECT `' . $this->_tbl_key . '`, `' . $this->_orderingKey . '` FROM ' . $this->_tbl;

		if ($dirn < 0) {
			$sign = ' < ';		//<=
			$orderDir = 'DESC';
		} else if ($dirn > 0) {
			$sign = ' > ';		//>=
			$orderDir = '';
		} else {
			$sign = ' = ';
			$orderDir = '';
		}

		$sql .= ' WHERE `' . $orderingkey . '` '.$sign.' ' . (int)$this->{$orderingkey};
		$sql .= ($where ? ' AND ' . $where : '');
		$sql .= ' ORDER BY `' . $orderingkey . '` '.$orderDir;

		$this->_db->setQuery($sql, 0, 1);

		$nextRow = $this->_db->loadObject();
		vmdebug('vmTable Move loaded $nextRow ',$sql);

		if (isset($nextRow)) {
			//We use directly the direction input to calculate the new ordering value $nextRow->{$orderingkey} + $dirn
			$query = 'UPDATE '.$this->_tbl
			.' SET `'.$this->_orderingKey.'` = '.(int)($nextRow->{$orderingkey} + $dirn)
			.' WHERE '.$this->_pkey.' = "'.(int)$cid.'" LIMIT 1;';

			$this->_db->setQuery( $query );
			vmdebug('Update with query ',$query);

			try{
				$this->_db->execute();
			} catch (Exception $e){
				vmError( get_class($this) . ':: move isset row $row->{$k}' . $e->getMessage());
			}

		} else {
			//Should not happen, if there is no next row, then the command to order down/up was accidently given (for example the gui provided accidently an order up icon
		}



		$this->fixOrdering($whereIn);
		vmdebug('vmtable move return true');
		return true;
	}

	function loadOrderingCurrentItem( $cid, $orderingkey, $array = 0 ) {
		//if not loaded already, load the ordering of the current item
		if(!$this->_loaded){
			$q = 'SELECT `' . $orderingkey . '` FROM `' . $this->_tbl . '` WHERE `' . $this->_tbl_key . '` = "' . (int)$cid . '" limit 0,1 ';
			$this->_db->setQuery($q);

			try {
				$this->{$orderingkey} = $this->_db->loadResult();
				vmdebug('vmTable Move loaded ordering of current item ',$q, $orderingkey, $this->{$orderingkey});
			} catch (Exception $e) {
				vmError(get_class($this) . $e->getMessage());
			}
		} else {
			vmdebug('vmTable Move ordering of current item ', $this->{$orderingkey});
		}

	}

	/**
	 * Returns the ordering value to place a new item last in its group
	 *
	 * @access public
	 * @param string query WHERE clause for selecting MAX(ordering).
	 */
	function getNextOrder($where = '', $orderingkey = 0) {

		$where = $this->_db->escape($where);
		$orderingkey = $this->_db->escape($orderingkey);

		if (!empty($orderingkey))
			$this->_orderingKey = $orderingkey;
		if (!in_array($this->_orderingKey, array_keys($this->getProperties()))) {
			vmError(get_class($this) . ' does not support ordering');
			return false;
		}

		$query = 'SELECT MAX(`' . $this->_orderingKey . '`)' .
			' FROM ' . $this->_tbl .
			($where ? ' WHERE ' . $where : '');
		if (!isset(self::$_cache[md5($query)])) {
			$this->_db->setQuery($query);
			try{
				$maxord = $this->_db->loadResult();
			} catch (Exception $e) {
				vmError(get_class($this) . ' getNextOrder ' . $e->getMessage());
				return false;
			}


		} else $maxord = self::$_cache[md5($query)];

		return $maxord + 1;
	}

	/**
	 * Compacts the ordering sequence of the selected records
	 *
	 * @access public
	 * @param string Additional where query to limit ordering to a particular subset of records
	 */
	function reorder($where = '', $orderingkey = 0) {

		$where = $this->_db->escape($where);
		$orderingkey = $this->_db->escape($orderingkey);

		if (!empty($orderingkey))
			$this->_orderingKey = $orderingkey;
		$k = $this->_tbl_key;

		if (!in_array($this->_orderingKey, array_keys($this->getProperties()))) {
			vmError(get_class($this) . ' does not support ordering');
			return false;
		}

		if ($this->_tbl == '#__content_frontpage') {
			$order2 = ", content_id DESC";
		} else {
			$order2 = "";
		}

		$query = 'SELECT ' . $this->_tbl_key . ', ' . $this->_orderingKey
			. ' FROM ' . $this->_tbl
			. ' WHERE `' . $this->_orderingKey . '` >= 0' . ($where ? ' AND ' . $where : '')
			. ' ORDER BY `' . $this->_orderingKey . '` ' . $order2;
		$this->_db->setQuery($query);
		try{
			$orders = $this->_db->loadObjectList();
		} catch (Exception $e) {
			vmError(get_class($this) . ' reorder ' . $e->getMessage());
			return false;
		}

		$orderingKey = $this->_orderingKey;
		// compact the ordering numbers
		for ($i = 0, $n = count($orders); $i < $n; $i++) {
			if ($orders[$i]->{$orderingKey} >= 0) {
				if ($orders[$i]->{$orderingKey} != $i + 1) {
					$orders[$i]->{$orderingKey} = $i + 1;
					$query = 'UPDATE ' . $this->_tbl
						. ' SET `' . $this->_orderingKey . '` = "' . $this->_db->escape($orders[$i]->{$orderingKey}) . '"
					 WHERE ' . $k . ' = "' . $this->_db->escape($orders[$i]->{$k}) . '"';
					$this->_db->setQuery($query);
					$this->_db->execute();
				}
			}
		}

		return true;
	}

	/**
	 * Checks out a row
	 *
	 * @access public
	 * @param    integer    The id of the user
	 * @param    mixed    The primary key value for the row
	 * @return    boolean    True if successful, or if checkout is not supported
	 */
	function checkout($who, $oid = null) {

		if (!in_array('locked_by', array_keys($this->getProperties()))) {
			return true;
		}

		$k = $this->_tbl_key;
		if ($oid !== null) {
			$this->{$k} = $oid;
		}

		$config = JFactory::getConfig();
		$siteOffset = $config->get('offset');
		$date = JFactory::getDate('now', $siteOffset);

		$time = $date->toSql();

		$query = 'UPDATE ' . $this->_db->quoteName($this->_tbl) .
			' SET locked_by = ' . (int)$who . ', locked_on = "' . $this->_db->escape($time) . '"
			 WHERE ' . $this->_tbl_key . ' = "' . $this->_db->escape($this->{$k}) . '"';
		$this->_db->setQuery($query);

		$this->locked_by = $who;
		$this->locked_on = $time;

		return $this->_db->execute();
	}

	/**
	 * Checks in a row
	 *
	 * @access    public
	 * @param    mixed    The primary key value for the row
	 * @return    boolean    True if successful, or if checkout is not supported
	 */
	function checkin($oid = null) {

		if (!(
			in_array('locked_by', array_keys($this->getProperties())) ||
				in_array('locked_on', array_keys($this->getProperties()))
		)
		) {
			return true;
		}

		$k = $this->_tbl_key;

		if ($oid !== null) {
			$this->{$k} = $oid;
		}

		if ($this->{$k} == NULL) {
			return false;
		}

		$query = 'UPDATE ' . $this->_db->quoteName($this->_tbl) .
			' SET locked_by = 0, locked_on = "' . $this->_db->escape($this->_db->getNullDate()) . '"
				 WHERE ' . $this->_tbl_key . ' = "' . $this->_db->escape($this->{$k}) . '"';
		$this->_db->setQuery($query);

		$this->locked_by = 0;
		$this->locked_on = '';

		return $this->_db->execute();
	}

	/**
	 * Check if an item is checked out
	 *
	 * This function can be used as a static function too, when you do so you need to also provide the
	 * a value for the $against parameter.
	 *
	 * @static
	 * @access public
	 * @param integer $with    The userid to preform the match with, if an item is checked out
	 *                            by this user the function will return false
	 * @param integer $against    The userid to perform the match against when the function is used as
	 *                            a static function.
	 * @return boolean
	 */
	function isCheckedOut($with = 0, $against = null) {

		if (isset($this) && is_a($this, 'VmTable') && is_null($against)) {
			$against = $this->get('locked_by');
		}

		//item is not checked out, or being checked out by the same user
		if (!$against || $against == $with) {
			return false;
		}

		$session = VmTable::getInstance('session');
		return $session->exists($against);
	}

	/**
	 * toggle (0/1) a field
	 * or invert by $val
	 * @author impleri
	 * @author Max Milbers
	 * @param string $field the field to toggle
	 * @param boolean $val field value (0/1)
	 * @todo could make this multi-id as well...
	 */
	function toggle($field, $val = NULL) {

		if ($val === NULL) {
			$this->{$field} = !$this->{$field};
		} else {
			$this->{$field} = $val;
		}
		$k = $this->_tbl_key;
		if(empty($this->{$k})){
			vmdebug('Cannot toggle '.get_class($this).' '.$this->_tbl_key.' empty ',$this->{$this->_pkey});
			return false;
		}
		$q = 'UPDATE `' . $this->_tbl . '` SET `' . $field . '` = "' . $this->{$field} . '" WHERE `' . $k . '` = "' . $this->{$k} . '" ';
		$this->_db->setQuery($q);
		try{
			$res = $this->_db->execute();
		} catch (Exception $e) {
			vmError('There was an error toggling ' . $field, $e->getMessage());
		}

		return $res;
	}

	public function resetErrors() {

		$this->_errors = array();
	}


	function delete($oid = null, $where = 0) {

		$k = $this->_tbl_key;

		if ($oid) {
			$this->{$k} = intval($oid);
		}

		$mainTableError = $this->checkAndDelete($this->_tbl, $where);

		if ($this->_translatable) {

			$langs = VmConfig::get('active_languages', array(VmConfig::$jDefLangTag));
			if (!$langs) $langs[] = VmConfig::$vmlang;

			foreach ($langs as $lang) {
				$lang = strtolower(strtr($lang, '-', '_'));
				$langError = $this->checkAndDelete($this->_tbl . '_' . $lang);
				$mainTableError = min($mainTableError, $langError);
			}
		}

		return $mainTableError;
	}

	// author stAn
	// returns true when mysql version is larger than 5.0
	function isMysql51Plus() {

		$r = $this->getMysqlVersion();
		return version_compare($r, '5.1.0', '>=');
	}

	// author: stan, added in 2.0.16+
	// returns mysql version for query optimalization
	function getMysqlVersion() {

		$q = 'select version()';
		if (!isset(self::$_cache[md5($q)])) {
			$this->_db->setQuery($q);
			return $this->_db->loadResult();
		} else return self::$_cache[md5($q)];

	}

	function checkAndDelete($table, $whereField = 0, $andWhere = '') {

		$ok = 1;
		$k = $this->_tbl_key;

		if ($whereField !== 0) {
			$whereKey = $whereField;
		} else {
			$whereKey = $this->_pkey;
		}

		$query = 'SELECT `' . $this->_tbl_key . '` FROM `' . $table . '` WHERE `' . $whereKey . '` = "' . $this->{$k} . '" '.$andWhere;
		$this->_db->setQuery($query);
		// 		vmdebug('checkAndDelete',$query);
		$list = $this->_db->loadColumn();
		// 		vmdebug('checkAndDelete',$list);


		if ($list) {

			foreach ($list as $row) {
				$ok = $row;
				$query = 'DELETE FROM `' . $table . '` WHERE ' . $this->_tbl_key . ' = "' . $row . '"';
				$this->_db->setQuery($query);

				try {
					$this->_db->execute();
				} catch (Exception $e) {
					vmError('checkAndDelete ' . $this->_db->getMessage());
					$ok = 0;
				}

			}

		}
		return $ok;
	}

	/**
	 * Add, change or drop userfields
	 *
	 * @param string $_act Action: ADD, DROP or CHANGE (synonyms available, see the switch cases)
	 * @param string $_col Column name
	 * @param string $_type fieldtype
	 * @param string $_col2 Second Column name
	 * @return boolean True on success
	 * @author Oscar van Eijk
	 *
	 * stAn - note: i disabled deleting of user data when a column (shopper field) is deleted. If a deletion of specific user or order is needed, it can be done separatedly
	 * The column if not set with $_col2 will be renamed to ORIGINALNAME_DELETED_{timestamp()} and depending on mysql version it's definition will change
	 */
	function _modifyColumn($_act, $_col, $_type = '', $_col2 = '') {

		$user = JFactory::getUser();
		if(!vmAccess::manager('core')) return false;

		$_sql = 'ALTER TABLE `' . $this->_tbl . '` ';


		$_check_act = strtoupper(substr($_act, 0, 3));
		//Check if a column is there

		//$columns = $this->_db->getTableColumns($this->_tbl);
		$columns = $this->showFullColumns('Field','Type',false);

		$res = array_key_exists($_col, $columns);

		if ($_check_act != 'ADD' and $_check_act != 'CRE') {
			if (!$res) {
				vmdebug('_modifyColumn Command was ' . $_check_act . ' column does not exist, changed to ADD');
				$_check_act = 'ADD';
			}
		} else {
			if ($res) {
				vmdebug('_modifyColumn Command was ' . $_check_act . ' column already exists, changed to MOD');
				$_check_act = 'UPD';
			}
		}

		switch ($_check_act) {
			case 'ADD':
			case 'CRE': // Create
				$_sql .= 'ADD `'.$_col.'` '.$_type.' ';
				break;
			case 'DRO': // Drop
			case 'DEL': // Delete
				//stAn, i strongly do not recommend to delete customer information only because a field was deleted
				if (empty($_col2)) {

					$_col2 = $_col . '_DELETED_' . time();
					vmInfo('Be aware the column of table '.$this->_tbl.' is not deleted, only renamed to '.$_col2);
				}

				if (!$this->isMysql51Plus()) {
					if (empty($_type)) $_type = 'TEXT CHARACTER SET utf8';
				}

				// NOT NULL not allowed for deleted columns
				//$t_type = str_ireplace(' NOT ', '', $_type);
			$_sql .= 'CHANGE `'.$_col.'` `'.$_col2.'` '.$_type.' ';
				//was: $_sql .= "DROP $_col ";
				break;
			case 'MOD': // Modify
			case 'UPD': // Update
			case 'CHA': // Change
				if (empty($col2)) $_col2 = $_col; // change type only
				$_sql .= 'CHANGE `'.$_col.'` `'.$_col2.'` '.$_type.' ';
				break;
		}

		$this->_db->setQuery($_sql);

		try{
			$this->_db->execute();
		} catch (Exception $e){
			vmError(get_class($this) . '::modify table - ' . $e->getMessage() . '<br /> values: action ' . $_act . ', columname: ' . $_col . ', type: ' . $_type . ', columname2: ' . $_col2);
			return false;
		}

		vmdebug('_modifyColumn executed successfully ' . $_sql);
		return true;
	}

	/**
	 * Method to lock the database table for writing.
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 * @throws  JDatabaseException
	 */
	protected function _lock()
	{
		$this->_db->lockTable($this->_tbl);
		$this->_locked = true;

		return true;
	}

	/**
	 * Method to unlock the database table for writing.
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	protected function _unlock()
	{
		$this->_db->unlockTables();
		$this->_locked = false;

		return true;
	}

	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form table_name.id
	 * where id is the value of the primary key of the table.
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @return  string
	 *
	 * @since   11.1
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return $this->_tbl . '.' . (int) $this->{$k};
	}

	/**
	 * Method to return the title to use for the asset table.  In
	 * tracking the assets a title is kept for each asset so that there is some
	 * context available in a unified access manager.  Usually this would just
	 * return $this->title or $this->name or whatever is being used for the
	 * primary name of the row. If this method is not overridden, the asset name is used.
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @return  string  The string to use as the title in the asset table.
	 *
	 * @link    http://docs.joomla.org/JTable/getAssetTitle
	 * @since   11.1
	 */
	protected function _getAssetTitle()
	{
		return $this->_getAssetName();
	}

	/**
	 * Method to get the parent asset under which to register this one.
	 * By default, all assets are registered to the ROOT node with ID,
	 * which will default to 1 if none exists.
	 * The extended class can define a table and id to lookup.  If the
	 * asset does not exist it will be created.
	 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   JTable   $table  A JTable object for the asset parent.
	 * @param   integer  $id     Id to look up
	 *
	 * @return  integer
	 *
	 * @since   11.1
	 */
	protected function _getAssetParentId($table = null, $id = null)
	{
		// For simple cases, parent to the asset root.
		$assets = self::getInstance('Asset', 'JTable', array('dbo' => $this->getDbo()));
		$rootId = $assets->getRootId();
		if (!empty($rootId))
		{
			return $rootId;
		}

		return 1;
	}

	public function reset() {
		$this->showFullColumns();
	}

	/**
	 * Implement JObservableInterface:
	 * Adds an observer to this instance.
	 * This method will be called fron the constructor of classes implementing JObserverInterface
	 * which is instanciated by the constructor of $this with JObserverMapper::attachAllObservers($this)
	 * @copyright   Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
	 * @param   JObserverInterface|JTableObserver  $observer  The observer object
	 *
	 * @return  void
	 *
	 * @since   3.1.2
	 */
	public function attachObserver(JObserverInterface $observer)
	{
		$this->_observers->attachObserver($observer);
	}

	/**
	 * Returns the identity (primary key) value of this record
	 *
	 * @return  mixed
	 *
	 * @since   4.0.0
	 */
	public function getId()
	{
		$key = $this->getKeyName();

		return $this->$key;
	}

	/**
	 * Check if the record has a property (applying a column alias if it exists)
	 *
	 * @param   string  $key  key to be checked
	 *
	 * @return  boolean
	 *
	 * @since   3.9.11
	 */
	public function hasField($key)
	{
		$key = $this->getColumnAlias($key);

		return property_exists($this, $key);
	}

	/**
	 * Method to return the real name of a "special" column such as ordering, hits, published
	 * etc etc. In this way you are free to follow your db naming convention and use the
	 * built in \Joomla functions.
	 *
	 * @param   string  $column  Name of the "special" column (ie ordering, hits)
	 *
	 * @return  string  The string that identify the special
	 *
	 * @since   3.4
	 */
	public function getColumnAlias($column)
	{
		// Get the column data if set
		if (isset($this->_columnAlias[$column]))
		{
			$return = $this->_columnAlias[$column];
		}
		else
		{
			$return = $column;
		}

		// Sanitize the name
		$return = preg_replace('#[^A-Z0-9_]#i', '', $return);

		return $return;
	}

	public function unsetForDebug(){
		unset($this->_db);
	}

	public function resetForDebug(){
		$db = JFactory::getDbo();
		$this->_db = $db;
	}
}
