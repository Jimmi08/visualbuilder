<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

if (!defined('VB_INIT')) { 
	require_once(e_PLUGIN.'visualbuilder/includes/visualbuilder_defines.php');
} 

 
/**
 * This file is part of the Qoob builder package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author     webark.com <qoob@webark.com>
 * @link       http://qoob-builder.com/
 * @copyright  2015-2018 WebArk.com
 * @license    http://qoob-builder.com/licenses/
 */

class Qoob {

  private $builderpath = VB_PLUGINNAME.'/qoob/';
  private $qoobpath = 'prod';  
  private $pagedata = array();
  
  
 
  public function __construct( $mode = 'prod' ) {

    if(isset($_GET['id']) && !empty($_GET['id']))
		{
      $this->id =  (int)$_GET['id'];
      if($qoobbuilder_data = e107::getDB()->retrieve(VB_DATATABLE, "*", " WHERE id =".$this->id )) {
        $this->pagedata = $qoobbuilder_data;
      }
		}  
		else {
		  //QUESTION:  get ID from  entity_type or  entity_id  or before calling this get ID of record? 
		}

		if(isset($_GET['entity_id']) && !empty($_GET['entity_id']))
		{
			$this->entity_id =  (int)$_GET['entity_id'];
		}
    if(isset($_GET['entity_type']) && !empty($_GET['entity_type']))
		{
			//$this->entity_type =  e107::getParser()->toDb($_GET['entity_type']) = 'custom';
      $this->entity_type =  'custom';
		}   
    
    // get skin
    $this->skin =  e107::getPlugConfig(VB_PLUGINNAME)->getPref('qoob_skin');
    $this->skinUrl =  e107::getPlugConfig(VB_PLUGINNAME)->getPref('qoob_skinUrl');
    
    $iframeUrl = VB_PATH_ABS.'/page.php?entity_id='.$this->entity_id.'&entity_type=custom&qoobbuilder=true';
 
    $frontendPageUrl = e107::url(VB_PLUGINNAME, 'page', $this->pagedata, 'full');

    $ajaxUrl = 'includes/ajax.php';
    
    $this->init();
    //pages are in root because hardcoded css links */
 
      e107::js(VB_PLUGINNAME, 'qoob/qoob-backend-starter.js', 'jquery');
      e107::js(VB_PLUGINNAME, 'qoob-e107-driver.js', 'jquery');

      //todo: move this to behaviours 
       $inlinecode = ' var starter = new QoobStarter({
          "mode": "'.$this->qoobprod.'",
          "qoobUrl": "' . $this->builderpath . '", 
          "skinUrl": "' . $this->skinUrl . '", 
          "skip":["jquery"],
          "driver": new Qoobe107Driver({
              "pageId": "' . $this->id . '" ,
              "iframeUrl": "' . $iframeUrl . '" ,
              "frontendPageUrl": "' . $frontendPageUrl . '" ,                      
              "ajaxUrl": "' . $ajaxUrl . '" ,
              "page": "' . $this->pagedata['entity_filename'] . '.html" ,
              // "translationsUrl": "../qoob/skins/simple/locale/ru.json"
          })
      });';
     e107::js('footer-inline', $inlinecode);
     
  
  }
  
  function init() {
       $this->builderpath  = VB_PATH_ABS.'/qoob/';
       $this->qoobprod  = 'prod';    //prod
  }
 
 private function getDriverHtml() {
 
    $builderpath = SITEURLBASE.e_PLUGIN_ABS.'qoobbuilder/qoob/';
    
    $iframeUrl = SITEURLBASE.e_PLUGIN_ABS.'qoobbuilder/qoobbuilder.php?file=layout.html';  
   
   
 }
 
   
 public function starterPage( $file ) {
    
    $htmlsource = file_get_contents($file.".html");
     
    $drivercode = $this-> getDriverHtml();
    //$pagecode = $this-> loadQoobPageData();
 
 
     $pages2 =   str_replace("<!-- qoob starter -->", $drivercode.$pagecode, $htmlsource );
 
  
    echo $pages2; 
 
 } 
  
  
 public function InnerPage( $file ) {
   
    $htmlsource = file_get_contents($file.".html");
  
    echo $htmlsource; 
 
 } 
 
  
	/**
	 * Load data page
	 *
	 * @return json
	 */
	public function loadQoobPageData() {
     $file = "mypage.json";
     $pages =  file_get_contents($file);
     $pages =   json_encode($pages);  
 
     return $pages;
  }
  
 
  
	
 
  
         
}
 
  
define('e_IFRAME',true);

include_once(HEADERF);    
 
if($_REQUEST['action'] == 'edit')  {
    $qoob = new Qoob( );
 
    $qoob->StarterPage($file); 
}   
else {

}
 
 
include_once(FOOTERF);
exit;        
 