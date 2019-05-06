<?php
/*
+ ----------------------------------------------------------------------------------------------------+
|        e107 website system 
|        Visual Builder Defines : e107_plugins/visualbuilder/visualbuilder_defines.php     
|        $Author: secretr $
|	       Copyright Jimako ( https://www.jimako.sk ) under GNU GPL License (http://gnu.org)
|        Free Support: https://www.e107.sk/
+----------------------------------------------------------------------------------------------------+
*/

 
if (!defined('e107_INIT')) { exit; }

//visualbuilder defs --------------------------------------------------->

define("VB_PATH", e_PLUGIN.'visualbuilder/');

define("VB_PATH_ABS", e_PLUGIN_ABS.'visualbuilder/');

define("VB_PATH_FULL", SITEURLBASE.e_PLUGIN_ABS.'visualbuilder/');

define("VB_INIT", TRUE);

define("VB_DATATABLE", 'qoobbuilder_data');
define("VB_PLUGINNAME", 'visualbuilder');

define("VB_IMAGE_DIR", e_PLUGIN.'visualbuilder/img/');
define("VB_IMAGE_DIR_ABS", e_PLUGIN_ABS.'visualbuilder/img/');
define("VB_IMAGE_DIR_FULL", SITEURLBASE.e_PLUGIN_ABS.'visualbuilder/img/'); 
?>