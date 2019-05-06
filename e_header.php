<?php

if (!defined('VB_INIT')) { 
	require_once(e_PLUGIN.'visualbuilder/includes/visualbuilder_defines.php');
}

if(e_ADMIN_AREA)  {
    // in all admin are to be able to use it in e_admin tab
    e107::css(VB_PLUGINNAME, 'assets/css/qoobbuilder-backend-custom.css'); 
 
}
else {       

  //why?? TODO: solve theme css
  e107::css(VB_PLUGINNAME, 'qoob/blocks/lib.css'); 

}