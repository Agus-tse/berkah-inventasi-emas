#
#<?php die("Forbidden."); ?>

2024-12-14 16:56:39 ERROR vmError: Warning, the Safe Path is not configured yet. Use this link to the &lt;a href=&#039;https://localhost/Berkahemas/administrator/index.php?option=com_virtuemart&amp;view=updatesmigration&amp;show_spwizard=1&#039; &gt;setup wizard&lt;/a&gt;&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\shopfunctions.php:1277]
#1  ShopFunctions::checkSafePathBase() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\shopfunctions.php:1111]
#2  ShopFunctions::getSafePathFor() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\views\config\view.html.php:117]
#3  VirtuemartViewConfig-&gt;display() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmcontroller.php:126]
&lt;/pre&gt;
2024-12-14 16:57:04 ERROR vmError: Warning, the Safe Path is not configured yet. Use this link to the &lt;a href=&#039;https://localhost/Berkahemas/administrator/index.php?option=com_virtuemart&amp;view=updatesmigration&amp;show_spwizard=1&#039; &gt;setup wizard&lt;/a&gt;&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\shopfunctions.php:1277]
#1  ShopFunctions::checkSafePathBase() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\shopfunctions.php:1111]
#2  ShopFunctions::getSafePathFor() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\views\config\view.html.php:117]
#3  VirtuemartViewConfig-&gt;display() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmcontroller.php:126]
&lt;/pre&gt;
2024-12-15 15:24:41 ERROR vmError: vmTable store updateObject Incorrect integer value: &#039;&#039; for column `berkahku`.`q1g04_virtuemart_categories`.`category_parent_id` at row 1 UPDATE `q1g04_virtuemart_categories` SET `category_parent_id`=&#039;&#039;,`virtuemart_vendor_id`=&#039;1&#039;,`category_template`=&#039;&#039;,`category_layout`=&#039;&#039;,`category_product_layout`=&#039;&#039;,`products_per_row`=&#039;&#039;,`ordering`=&#039;0&#039;,`shared`=&#039;0&#039;,`cat_params`=&#039;show_store_desc=\&quot;\&quot;|showcategory_desc=\&quot;\&quot;|showcategory=\&quot;\&quot;|categories_per_row=\&quot;\&quot;|showproducts=\&quot;\&quot;|omitLoaded=\&quot;\&quot;|showsearch=\&quot;\&quot;|productsublayout=\&quot;\&quot;|featured=\&quot;\&quot;|featured_rows=\&quot;\&quot;|omitLoaded_featured=\&quot;\&quot;|discontinued=\&quot;\&quot;|discontinued_rows=\&quot;\&quot;|omitLoaded_discontinued=\&quot;\&quot;|latest=\&quot;\&quot;|latest_rows=\&quot;\&quot;|omitLoaded_latest=\&quot;\&quot;|topten=\&quot;\&quot;|topten_rows=\&quot;\&quot;|omitLoaded_topten=\&quot;\&quot;|recent=\&quot;\&quot;|recent_rows=\&quot;\&quot;|omitLoaded_recent=\&quot;\&quot;|&#039;,`limit_list_step`=&#039;0&#039;,`limit_list_initial`=&#039;0&#039;,`metarobot`=&#039;&#039;,`metaauthor`=&#039;&#039;,`published`=&#039;1&#039;,`has_children`=&#039;0&#039;,`modified_on`=&#039;2024-12-15 15:24:41&#039;,`modified_by`=&#039;479&#039;,`locked_by`=&#039;0&#039; WHERE `virtuemart_category_id` = &#039;2&#039;&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:1337]
#1  VmTable-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:2034]
#2  VmTable-&gt;bindChecknStoreNoLang() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:1926]
#3  VmTable-&gt;bindChecknStore() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\category.php:793]
#4  VirtueMartModelCategory-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmcontroller.php:178]
#5  VmController-&gt;save() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\controllers\category.php:43]
#6  VirtuemartControllerCategory-&gt;save() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
&lt;/pre&gt;
2024-12-16 01:57:24 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 01:57:24 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 01:57:24 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 01:57:48 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 01:57:48 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 01:57:48 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:04:32 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:04:32 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:04:32 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:04:42 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:04:42 ERROR vmError: Restricted access for view category&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:07:19 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:08:48 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:08:48 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:08:48 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:08:53 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:08:53 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:08:53 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:21:10 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:21:10 ERROR vmError: Restricted access for view virtuemart&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:21:30 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:21:30 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:21:30 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:21:35 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:21:35 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:21:35 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:21:53 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:21:53 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:21:53 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:22:22 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:22:22 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:22:22 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:24:27 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:24:27 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:24:27 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:25:07 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:25:07 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:25:07 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:25:22 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:391]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:25:22 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:25:22 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:25:27 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:25:27 ERROR vmError: Restricted access for view category&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:45:59 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:45:59 ERROR vmError: Restricted access for view category&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:46:44 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:46:44 ERROR vmError: Restricted access for view virtuemart&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:48:26 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:48:26 ERROR vmError: Restricted access for view state&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:50:27 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:50:27 ERROR vmError: Restricted access for view category&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:52:55 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:52:55 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:52:55 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:53:46 ERROR vmError: Restricted access for view 0&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:508]
#3  VmConfig::loadConfig() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:24]
&lt;/pre&gt;
2024-12-16 02:53:46 ERROR vmError: Restricted access for view product&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:53]
#2  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
&lt;/pre&gt;
2024-12-16 02:53:46 ERROR vmError: Tried to load controller &quot;product&quot; on base path &quot;C:\xampp\htdocs\Berkahemas/components/com_virtuemart&quot;. No File available VirtuemartControllerProduct&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:126]
#1  require_once(C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php) called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:71]
#2  Joomla\CMS\Dispatcher\LegacyComponentDispatcher::Joomla\CMS\Dispatcher\{closure}() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Dispatcher\LegacyComponentDispatcher.php:73]
#3  Joomla\CMS\Dispatcher\LegacyComponentDispatcher-&gt;dispatch() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Component\ComponentHelper.php:361]
#4  Joomla\CMS\Component\ComponentHelper::renderComponent() called at [C:\xampp\htdocs\Berkahemas\libraries\src\Application\SiteApplication.php:208]
&lt;/pre&gt;
2024-12-16 02:59:34 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-16 03:05:57 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-16 03:08:06 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-16 03:08:43 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-16 03:09:15 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-16 03:12:28 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-16 03:44:41 ERROR vmError: COM_VIRTUEMART_RESTRICTED_ACCESS_VIEW&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmaccess.php:262]
#1  vmAccess::isManagingFE() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\config.php:685]
#2  VmConfig::isSite() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:404]
#3  vmrouterHelper::buildRoute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\router.php:79]
&lt;/pre&gt;
2024-12-21 16:10:57 ERROR vmError: Warning, the Safe Path is not configured yet. Use this link to the &lt;a href=&#039;https://localhost/Berkahemas/administrator/index.php?option=com_virtuemart&amp;view=updatesmigration&amp;show_spwizard=1&#039; &gt;setup wizard&lt;/a&gt;&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\shopfunctions.php:1277]
#1  ShopFunctions::checkSafePathBase() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\shopfunctions.php:1111]
#2  ShopFunctions::getSafePathFor() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\views\config\view.html.php:117]
#3  VirtuemartViewConfig-&gt;display() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmcontroller.php:126]
&lt;/pre&gt;
2024-12-21 17:06:31 ERROR vmError: Empty slug product with id 1, entries exists for language? en-GB&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\product.php:1776]
#1  VirtueMartModelProduct-&gt;getProductSingle() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\product.php:1293]
#2  VirtueMartModelProduct-&gt;getProduct() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\helpers\cart.php:2385]
#3  VirtueMartCart::getProduct() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\helpers\cart.php:2414]
&lt;/pre&gt;
2024-12-21 17:06:31 ERROR vmError: The product is no longer available; cart getProduct is empty&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\helpers\cart.php:2387]
#1  VirtueMartCart::getProduct() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\helpers\cart.php:2414]
#2  VirtueMartCart-&gt;prepareCartData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\cart.php:94]
#3  VirtueMartControllerCart-&gt;display() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
&lt;/pre&gt;
2024-12-21 17:06:31 ERROR vmError: The product is no longer available; prepareCartData virtuemart_product_id is empty&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\helpers\cart.php:2417]
#1  VirtueMartCart-&gt;prepareCartData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\cart.php:94]
#2  VirtueMartControllerCart-&gt;display() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
#3  Joomla\CMS\MVC\Controller\BaseController-&gt;execute() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\virtuemart.php:133]
&lt;/pre&gt;
2024-12-21 17:17:42 ERROR vmError: Access Forbidden allowUserRegistration in joomla disabled&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:800]
#1  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#2  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
#3  VirtueMartControllerUser-&gt;saveUser() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
&lt;/pre&gt;
2024-12-21 17:20:13 ERROR vmError: Registration failed: The email address you entered is already in use. Please enter another email address.&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:294]
#1  VirtueMartModelUser-&gt;register() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:848]
#2  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#3  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
&lt;/pre&gt;
2024-12-21 17:20:13 ERROR vmError: Save failed with the following error: The email address you entered is already in use. Please enter another email address. Var0: &lt;pre&gt;object(Joomla\CMS\User\User)#1133 (28) {
  [&quot;isRoot&quot;:protected]=&gt;
  NULL
  [&quot;id&quot;]=&gt;
  int(0)
  [&quot;name&quot;]=&gt;
  string(10) &quot;susilo G R&quot;
  [&quot;username&quot;]=&gt;
  string(4) &quot;feri&quot;
  [&quot;email&quot;]=&gt;
  string(34) &quot;2311601823@student.budiluhur.ac.id&quot;
  [&quot;password&quot;]=&gt;
  string(60) &quot;$2y$10$.hvkPdG8iYn4L13xSE8XbOv.a0Qeq.JeaVAhsW86YA0by7/opTNDu&quot;
  [&quot;password_clear&quot;]=&gt;
  string(12) &quot;feri07021983&quot;
  [&quot;block&quot;]=&gt;
  string(1) &quot;1&quot;
  [&quot;sendEmail&quot;]=&gt;
  int(0)
  [&quot;registerDate&quot;]=&gt;
  string(19) &quot;2024-12-21 17:20:13&quot;
  [&quot;lastvisitDate&quot;]=&gt;
  NULL
  [&quot;activation&quot;]=&gt;
  string(32) &quot;53b287157b791fcd1abaded3fd498264&quot;
  [&quot;params&quot;]=&gt;
  string(20) &quot;{&quot;language&quot;:&quot;en-GB&quot;}&quot;
  [&quot;groups&quot;]=&gt;
  array(1) {
    [0]=&gt;
    string(1) &quot;2&quot;
  }
  [&quot;guest&quot;]=&gt;
  string(1) &quot;1&quot;
  [&quot;lastResetTime&quot;]=&gt;
  NULL
  [&quot;resetCount&quot;]=&gt;
  NULL
  [&quot;requireReset&quot;]=&gt;
  NULL
  [&quot;_params&quot;:protected]=&gt;
  object(Joomla\Registry\Registry)#1134 (3) {
    [&quot;data&quot;:protected]=&gt;
    object(stdClass)#1135 (1) {
      [&quot;language&quot;]=&gt;
      string(5) &quot;en-GB&quot;
    }
    [&quot;initialized&quot;:protected]=&gt;
    bool(false)
    [&quot;separator&quot;:protected]=&gt;
    string(1) &quot;.&quot;
  }
  [&quot;_authGroups&quot;:protected]=&gt;
  NULL
  [&quot;_authLevels&quot;:protected]=&gt;
  NULL
  [&quot;_authActions&quot;:protected]=&gt;
  NULL
  [&quot;_errorMsg&quot;:protected]=&gt;
  NULL
  [&quot;aid&quot;]=&gt;
  int(0)
  [&quot;_errors&quot;:protected]=&gt;
  array(1) {
    [0]=&gt;
    string(84) &quot;The email address you entered is already in use. Please enter another email address.&quot;
  }
  [&quot;language&quot;]=&gt;
  string(5) &quot;en-GB&quot;
  [&quot;password2&quot;]=&gt;
  string(12) &quot;feri07021983&quot;
  [&quot;usertype&quot;]=&gt;
  string(1) &quot;2&quot;
}
&lt;/pre&gt;
&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:850]
#1  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#2  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
#3  VirtueMartControllerUser-&gt;saveUser() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
&lt;/pre&gt;
2024-12-21 17:21:10 ERROR vmError: Registration failed: Username in use.&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:294]
#1  VirtueMartModelUser-&gt;register() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:848]
#2  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#3  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
&lt;/pre&gt;
2024-12-21 17:21:10 ERROR vmError: Save failed with the following error: Username in use. Var0: &lt;pre&gt;object(Joomla\CMS\User\User)#1133 (28) {
  [&quot;isRoot&quot;:protected]=&gt;
  NULL
  [&quot;id&quot;]=&gt;
  int(0)
  [&quot;name&quot;]=&gt;
  string(10) &quot;susilo G R&quot;
  [&quot;username&quot;]=&gt;
  string(4) &quot;feri&quot;
  [&quot;email&quot;]=&gt;
  string(26) &quot;feri.susilo@kemenkeu.go.id&quot;
  [&quot;password&quot;]=&gt;
  string(60) &quot;$2y$10$DrNfR3Ly8C2V3EcUU3wPmujzVc.I4qHXCj6gvpLA76ppuZ7kLam6e&quot;
  [&quot;password_clear&quot;]=&gt;
  string(8) &quot;Djpb#123&quot;
  [&quot;block&quot;]=&gt;
  string(1) &quot;1&quot;
  [&quot;sendEmail&quot;]=&gt;
  int(0)
  [&quot;registerDate&quot;]=&gt;
  string(19) &quot;2024-12-21 17:21:10&quot;
  [&quot;lastvisitDate&quot;]=&gt;
  NULL
  [&quot;activation&quot;]=&gt;
  string(32) &quot;0979215135b49e8fff135b2a8eb41f1b&quot;
  [&quot;params&quot;]=&gt;
  string(20) &quot;{&quot;language&quot;:&quot;en-GB&quot;}&quot;
  [&quot;groups&quot;]=&gt;
  array(1) {
    [0]=&gt;
    string(1) &quot;2&quot;
  }
  [&quot;guest&quot;]=&gt;
  string(1) &quot;1&quot;
  [&quot;lastResetTime&quot;]=&gt;
  NULL
  [&quot;resetCount&quot;]=&gt;
  NULL
  [&quot;requireReset&quot;]=&gt;
  NULL
  [&quot;_params&quot;:protected]=&gt;
  object(Joomla\Registry\Registry)#1134 (3) {
    [&quot;data&quot;:protected]=&gt;
    object(stdClass)#1135 (1) {
      [&quot;language&quot;]=&gt;
      string(5) &quot;en-GB&quot;
    }
    [&quot;initialized&quot;:protected]=&gt;
    bool(false)
    [&quot;separator&quot;:protected]=&gt;
    string(1) &quot;.&quot;
  }
  [&quot;_authGroups&quot;:protected]=&gt;
  NULL
  [&quot;_authLevels&quot;:protected]=&gt;
  NULL
  [&quot;_authActions&quot;:protected]=&gt;
  NULL
  [&quot;_errorMsg&quot;:protected]=&gt;
  NULL
  [&quot;aid&quot;]=&gt;
  int(0)
  [&quot;_errors&quot;:protected]=&gt;
  array(1) {
    [0]=&gt;
    string(16) &quot;Username in use.&quot;
  }
  [&quot;language&quot;]=&gt;
  string(5) &quot;en-GB&quot;
  [&quot;password2&quot;]=&gt;
  string(8) &quot;Djpb#123&quot;
  [&quot;usertype&quot;]=&gt;
  string(1) &quot;2&quot;
}
&lt;/pre&gt;
&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:850]
#1  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#2  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
#3  VirtueMartControllerUser-&gt;saveUser() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
&lt;/pre&gt;
2024-12-21 17:21:45 ERROR vmError: Registration failed: Username in use.&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:294]
#1  VirtueMartModelUser-&gt;register() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:848]
#2  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#3  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
&lt;/pre&gt;
2024-12-21 17:21:45 ERROR vmError: Save failed with the following error: Username in use. Var0: &lt;pre&gt;object(Joomla\CMS\User\User)#1132 (28) {
  [&quot;isRoot&quot;:protected]=&gt;
  NULL
  [&quot;id&quot;]=&gt;
  int(0)
  [&quot;name&quot;]=&gt;
  string(10) &quot;susilo G R&quot;
  [&quot;username&quot;]=&gt;
  string(4) &quot;feri&quot;
  [&quot;email&quot;]=&gt;
  string(26) &quot;feri.susilo@kemenkeu.go.id&quot;
  [&quot;password&quot;]=&gt;
  string(60) &quot;$2y$10$NvVtnF8wrk7offEAwdbAueYffxt1jQ5NsmR17mdBUwn0aW8rhhZ.m&quot;
  [&quot;password_clear&quot;]=&gt;
  string(8) &quot;Djpb#123&quot;
  [&quot;block&quot;]=&gt;
  string(1) &quot;1&quot;
  [&quot;sendEmail&quot;]=&gt;
  int(0)
  [&quot;registerDate&quot;]=&gt;
  string(19) &quot;2024-12-21 17:21:45&quot;
  [&quot;lastvisitDate&quot;]=&gt;
  NULL
  [&quot;activation&quot;]=&gt;
  string(32) &quot;6749cb69ee36d142b859745ea405f8b9&quot;
  [&quot;params&quot;]=&gt;
  string(20) &quot;{&quot;language&quot;:&quot;en-GB&quot;}&quot;
  [&quot;groups&quot;]=&gt;
  array(1) {
    [0]=&gt;
    string(1) &quot;2&quot;
  }
  [&quot;guest&quot;]=&gt;
  string(1) &quot;1&quot;
  [&quot;lastResetTime&quot;]=&gt;
  NULL
  [&quot;resetCount&quot;]=&gt;
  NULL
  [&quot;requireReset&quot;]=&gt;
  NULL
  [&quot;_params&quot;:protected]=&gt;
  object(Joomla\Registry\Registry)#1133 (3) {
    [&quot;data&quot;:protected]=&gt;
    object(stdClass)#1134 (1) {
      [&quot;language&quot;]=&gt;
      string(5) &quot;en-GB&quot;
    }
    [&quot;initialized&quot;:protected]=&gt;
    bool(false)
    [&quot;separator&quot;:protected]=&gt;
    string(1) &quot;.&quot;
  }
  [&quot;_authGroups&quot;:protected]=&gt;
  NULL
  [&quot;_authLevels&quot;:protected]=&gt;
  NULL
  [&quot;_authActions&quot;:protected]=&gt;
  NULL
  [&quot;_errorMsg&quot;:protected]=&gt;
  NULL
  [&quot;aid&quot;]=&gt;
  int(0)
  [&quot;_errors&quot;:protected]=&gt;
  array(1) {
    [0]=&gt;
    string(16) &quot;Username in use.&quot;
  }
  [&quot;language&quot;]=&gt;
  string(5) &quot;en-GB&quot;
  [&quot;password2&quot;]=&gt;
  string(8) &quot;Djpb#123&quot;
  [&quot;usertype&quot;]=&gt;
  string(1) &quot;2&quot;
}
&lt;/pre&gt;
&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\user.php:850]
#1  VirtueMartModelUser-&gt;store() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:260]
#2  VirtueMartControllerUser-&gt;saveData() called at [C:\xampp\htdocs\Berkahemas\components\com_virtuemart\controllers\user.php:121]
#3  VirtueMartControllerUser-&gt;saveUser() called at [C:\xampp\htdocs\Berkahemas\libraries\src\MVC\Controller\BaseController.php:693]
&lt;/pre&gt;
2024-12-24 09:07:51 ERROR vmError: vmTable store updateObject Data truncated for column &#039;currency_exchange_rate&#039; at row 1 UPDATE `q1g04_virtuemart_currencies` SET `virtuemart_vendor_id`=&#039;1&#039;,`currency_name`=&#039;Indonesian rupiah&#039;,`currency_code_2`=&#039;&#039;,`currency_code_3`=&#039;IDR&#039;,`currency_numeric_code`=&#039;360&#039;,`currency_exchange_rate`=&#039;0,15000&#039;,`currency_symbol`=&#039;Rp&#039;,`currency_decimal_place`=&#039;0&#039;,`currency_decimal_symbol`=&#039;&#039;,`currency_thousands`=&#039;&#039;,`currency_positive_style`=&#039;{symbol}{number}&#039;,`currency_negative_style`=&#039;{symbol}{sign}{number}&#039;,`shared`=&#039;0&#039;,`published`=&#039;1&#039;,`modified_on`=&#039;2024-12-24 09:07:51&#039;,`modified_by`=&#039;479&#039;,`locked_by`=&#039;0&#039;,`ordering`=&#039;0&#039; WHERE `virtuemart_currency_id` = &#039;65&#039;&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:1337]
#1  VmTable-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:2034]
#2  VmTable-&gt;bindChecknStoreNoLang() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:1969]
#3  VmTable-&gt;bindChecknStore() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmmodel.php:1177]
#4  VmModel-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\currency.php:146]
#5  VirtueMartModelCurrency-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmcontroller.php:178]
#6  VmController-&gt;save() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\controllers\currency.php:58]
&lt;/pre&gt;
2024-12-24 09:08:21 ERROR vmError: vmTable store updateObject Data truncated for column &#039;currency_exchange_rate&#039; at row 1 UPDATE `q1g04_virtuemart_currencies` SET `virtuemart_vendor_id`=&#039;1&#039;,`currency_name`=&#039;Indonesian rupiah&#039;,`currency_code_2`=&#039;&#039;,`currency_code_3`=&#039;IDR&#039;,`currency_numeric_code`=&#039;360&#039;,`currency_exchange_rate`=&#039;15,000&#039;,`currency_symbol`=&#039;Rp&#039;,`currency_decimal_place`=&#039;0&#039;,`currency_decimal_symbol`=&#039;&#039;,`currency_thousands`=&#039;&#039;,`currency_positive_style`=&#039;{symbol}{number}&#039;,`currency_negative_style`=&#039;{symbol}{sign}{number}&#039;,`shared`=&#039;0&#039;,`published`=&#039;1&#039;,`modified_on`=&#039;2024-12-24 09:08:21&#039;,`modified_by`=&#039;479&#039;,`locked_by`=&#039;0&#039;,`ordering`=&#039;0&#039; WHERE `virtuemart_currency_id` = &#039;65&#039;&lt;pre&gt;#0  vmError() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:1337]
#1  VmTable-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:2034]
#2  VmTable-&gt;bindChecknStoreNoLang() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmtable.php:1969]
#3  VmTable-&gt;bindChecknStore() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmmodel.php:1177]
#4  VmModel-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\models\currency.php:146]
#5  VirtueMartModelCurrency-&gt;store() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\helpers\vmcontroller.php:178]
#6  VmController-&gt;save() called at [C:\xampp\htdocs\Berkahemas\administrator\components\com_virtuemart\controllers\currency.php:58]
&lt;/pre&gt;