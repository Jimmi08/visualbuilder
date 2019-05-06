<?php

/**
 * @file
 * Visual Builder class for common use.
 */

if(!defined('e107_INIT'))
{
	exit;
}

if (!defined('VB_INIT')) { 
	require_once(e_PLUGIN.'visualbuilder/visualbuilder_defines.php');
} 
 
e107::lan(VB_PLUGINNAME, true, true);

/**
 * Class visualbuilder.
 */
 
if (class_exists('qoobbuilder')) {
	return null;
}

class qoobbuilder
{

	/**
	 * Plugin preferences.
	 *
	 * @var array|mixed
	 */
	private $plugPrefs = array();
	
	
	/**
	 * Plugin default library 
	 * 
	 * @var string
	 */
	private $defaultLibrary = array(
    "default" => VB_PATH_FULL."qoob/blocks/lib.json");

	/**
	 * Plugin default skin 
	 * 
	 * @var string
	 */
	private $defaultSkin = "simple";


	/**
	 * Plugin default skin url
	 * 
	 * @var string
	 */
	private $defaultSkinUrl = VB_PATH_FULL.'qoob/skins/simple/';
  
	/**
	 * Contains a list about plugins, which has e_builder.php addon file.
	 *
	 * @var array
	 */
	private $addonList = array();
	

	/**
	 * Constructor.
	 */
	function __construct()
	{
		$plugPrefs = e107::getPlugConfig(VB_PLUGINNAME)->getPref();
		$this->plugPrefs = $plugPrefs;
		$this->addonList = varset($plugPrefs['addon_list'], array());
 
	}
 
	/**
	 * Update addon list.  This is used from e_event.php when any plugin is installed/unistalled 
	 */
	public function updateAddonList()
	{
    // removed metatag version, used gold version
    $builder_pluglist = e107::getPref('plug_installed'); 
    ksort($builder_pluglist);
		
		foreach($builder_pluglist as $builder_plugin => $plugin_version)
		{
 
			$addonFile = e_PLUGIN . $builder_plugin . '/e_visualbuilder.php';
			if (file_exists($addonFile) && is_readable($addonFile))
      {
         e107_require_once($addonFile);
				  
				 $addonClass = $builder_plugin . '_visualbuilder';
				 
         if(!class_exists($addonClass))
    			{
    				continue;
    			}		
      
         $class = new $addonClass();	 
				 //$t = get_class_methods($class);
				 if(!method_exists($addonClass, 'priority'))
				 {
							$priority = 99;
				 }
				 else {
				     $priority = $class->priority();	
				 }
			 	$addonsList[] = array('name'=>$builder_plugin, 'priority'=>$priority);		 
      }

		} 
 
    
		        
    // https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
		// fatal erroe because site because  <=>
    /* resort array by priority, lower at first */  
    /*   echo version_compare(phpversion(), '7.0.0', '<');   
    if (version_compare(phpversion(), '7.0.0', '<')) { 
    }
    else {        
    		usort($addonsList, function ($item1, $item2) {
         	return $item1['priority'] <=> $item2['priority'];
    		});   
    }    
    */
    usort($addonsList, function ($item1, $item2) { return strnatcmp($item1['priority'], $item2['priority']); });  
		// create addons in correct order 
		foreach($addonsList as $addon) {   
 
		 $newAddonList[] = $addon['name'];
 
		}
    
	  e107::getPlugConfig(VB_PLUGINNAME)->set('addon_list', $newAddonList)->save(true);

	}
	
	/**
	 * Builds configuration array.
	 *
	 * @param bool $nocache
	 *   Set TRUE to disable cache.
	 *
	 * @return array
	 */
	public function getAddonConfig($method = 'config', $nocache = false  )
	{
		// Re-use the statically cached value to save memory.
	  $addonConfig = array();
  
		if(!empty($addonConfig))
		{
			return $addonConfig;
		}	
 
    /* with priority you don't need alter config method  */
		foreach($this->addonList as $plugin)
		{
 
			$file = e_PLUGIN . $plugin . '/e_visualbuilder.php';
			if(!is_readable($file))
			{
				continue;
			}
      
			e107_require_once($file);
			$addonClass = $plugin . '_visualbuilder';
			
      // check correct plugin class 
			if(!class_exists($addonClass))
			{
				continue;
			}				
			
			$class = new $addonClass();
 
      // plugin has to have method with name config
      if($class && method_exists($class, $method))
			{
				// this works too:  $addonConfig = $class->$method($addonConfig);
        $addonConfig = $class->$method($addonConfig);  
			}
 
			if(!is_array($addonConfig))
			{
				continue;
			}
 
 
		}	
 
		// override with actual theme
		$sitetheme = e107::getPref('sitetheme');
    $file = e_THEME . $sitetheme. '/e_visualbuilder.php';   
    if(is_readable($file))         
		{
			e107_require_once($file);
			$addonClass =  'theme_visualbuilder';
      
			if(class_exists($addonClass))
			{
				$class = new $addonClass();
	
				if(method_exists($class, $method))
				{ 
					$addonConfig = $class->$method($addonConfig);
				}
 
			}				
			
		}
 
		return $addonConfig;	
	}
	
	
	/**
	 * Set available library.
	 *
	 * @return array
	 */
	public function setLibrary()
	{
		$types = $this->getAddonConfig('library', false );
 
		$library = varset($types['library'], $this->defaultLibrary);
 
		e107::getPlugConfig(VB_PLUGINNAME)->set('qoob_library', $library)->save(true);
		return $library;
	}
  
  
  
	
	/**
	 * Set available skin
	 *
	 * @return array
	 */
	public function setSkin()
	{
		$types = $this->getAddonConfig('config', false );
 
		$skin = varset($types['skin'], $this->defaultSkin);
		
		e107::getPlugConfig(VB_PLUGINNAME)->set('qoob_skin', $skin)->save(true);
    
    $skinUrl = varset($types['skinUrl'], $this->defaultSkinUrl);
    
    e107::getPlugConfig(VB_PLUGINNAME)->set('qoob_skinUrl', $skinUrl)->save(true);
   
		return $skin;
	}	
	
	
	
}


?>