<?php

/**
 * @file
 *
 */

if(!defined('e107_INIT'))
{
	exit;
}


/**
 * Class metatag_event.
 */
class qoobbuilder_event
{

	/**
	 * Configure functions/methods to run when specific e107 events are triggered.
	 *
	 * @return array
	 */
	function config()
	{
		$event = array();
		
		// After updating plugins table.
		$event[] = array(
			'name'     => "system_plugins_table_updated",
			'function' => "visualbuilder_update_addon_list",
		);

		// After a plugin is installed.
		$event[] = array(
			'name'     => "admin_plugin_install",
			'function' => "visualbuilder_update_addon_list",
		);

		// After a plugin is uninstalled.
		$event[] = array(
			'name'     => "admin_plugin_uninstall",
			'function' => "visualbuilder_update_addon_list",
		);

		// After a plugin is upgraded.
		$event[] = array(
			'name'     => "admin_plugin_upgrade",
			'function' => "visualbuilder_update_addon_list",
		);

		// Plugin information is updated.
		$event[] = array(
			'name'     => "admin_plugin_refresh",
			'function' => "visualbuilder_update_addon_list",
		);
	}
	
	/**
	 * Callback function to update visualbuilder addon list.
	 */
	function visualbuilder_update_addon_list()
	{
		e107_require_once(e_PLUGIN . 'visualbuilder/includes/visualbuilder.class.php');
		$visualbuilder = new goobbuilder();
		$visualbuilder->updateAddonList();
    $visualbuilder->setLibrary();
    $visualbuilder->setSkin();		 
	}
	
}