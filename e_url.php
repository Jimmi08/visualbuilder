<?php
/*
 * e107 Bootstrap CMS
 *
 * Copyright (C) 2008-2015 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
 * IMPORTANT: Make sure the redirect script uses the following code to load class2.php: 
 * 
 * 	if (!defined('e107_INIT'))
 * 	{
 * 		require_once("../../class2.php");
 * 	}
 * 
 */
 
if (!defined('e107_INIT')) { exit; }

// v2.x Standard  - Simple mod-rewrite module. 

class visualbuilder_url // plugin-folder + '_url'
{
	function config() 
	{
		$config = array();
 
    // created separate config because I am not able add next parameter to sef ulr, better not time for this TODO FIX THIS
		$config['qoobbuilder'] = array(
      'alias'         => 'custompage',
  //   'legacy'        => '{e_PLUGIN}qoobbuilder/page.php?id={entity_type}&type={entity_id}&qoobbuilder=true',
//			'regex'			=> '^{alias}/\/([^\/]*)_([\d]*)(?:\/|-)([\w-]*).html', 						// matched against url, and if true, redirected to 'redirect' below.
			'regex'			=> '^{alias}\/([^\/]*)_(\d*).html&qoobbuilder=true', 						// matched against url, and if true, redirected to 'redirect' below.
			'sef'			=> '{alias}/{entity_type}_{entity_id}.html&qoobbuilder=true', 							// used by e107::url(); to create a url from the db table.
			'redirect'		=> '{e_PLUGIN}visualbuilder/page.php?entity_id=$2&entity_type=$1&qoobbuilder=true', 		// file-path of what to load when the regex returns true.
		); 
 
		$config['page'] = array(
      'alias'         => 'custompage',
//			'regex'			=> '^{alias}/\/([^\/]*)_([\d]*)(?:\/|-)([\w-]*).html', 						// matched against url, and if true, redirected to 'redirect' below.
			'regex'			=> '^{alias}\/([^\/]*)_(\d*).html', 						// matched against url, and if true, redirected to 'redirect' below.
			'sef'			=> '{alias}/{entity_type}_{entity_id}.html', 							// used by e107::url(); to create a url from the db table.
			'redirect'		=> '{e_PLUGIN}visualbuilder/page.php?entity_id=$2&entity_type=$1', 		// file-path of what to load when the regex returns true.
		);
    
		$config['other'] = array(
			'alias'         => 'custompage',                            // default alias 'qoobbuilder'. {alias} is substituted with this value below. Allows for customization within the admin area.
			'regex'			=> '^{alias}/?$', 						// matched against url, and if true, redirected to 'redirect' below.
			'sef'			=> '{alias}', 							// used by e107::url(); to create a url from the db table.
			'redirect'		=> '{e_PLUGIN}visualbuilder/page.php', 		// file-path of what to load when the regex returns true.


		);

		return $config;
	}
	

	
}