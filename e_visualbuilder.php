<?php

/**
 * @file
 * Visualbuilder addon file.
 */


/**
 * Class visualbuilder_visualbuilder.
 *
 * Usage: PLUGIN_visualbuilder
 */
 
/* Note: this is just example, in fact it's not needed here, everytime default values are used */
 
class visualbuilder_visualbuilder
{

 	public function priority()
	{
		return 0;
	}
	
	public function config()
	{
		$config = array();	
	 	$config['skin'] 	 =  "simple";
		return $config;
	}
  
	public function library()
	{
		$config = array();
		
	 	$config['library']['default']  =  VB_PATH_FULL."qoob/blocks/lib.json";
 
		return $config;
	}
	
}