<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Blank Plugin
 *
*/
if (!defined('e107_INIT'))
{
	require_once("../../class2.php");
}

if (!defined('VB_INIT')) { 
	require_once(e_PLUGIN.'visualbuilder/includes/visualbuilder_defines.php');
}

e107::lan('visualbuilder',true);
 
/* it is builder */
if(isset($_GET['qoobbuilder']) && !empty($_GET['qoobbuilder']))  {
 
 // e107::css(VB_PLUGINNAME, 'assets/css/megafish.css'); 
  e107::css(VB_PLUGINNAME, 'assets/css/magnific-popup.css');   
  e107::css(VB_PLUGINNAME, 'assets/css/owl.carousel.min.css');   
  e107::css(VB_PLUGINNAME, 'assets/css/owl.theme.default.min.css'); 
  e107::css(VB_PLUGINNAME, 'assets/css/fonts/fonts.css');   
  e107::css(VB_PLUGINNAME, 'assets/css/jquery-ui.min.css'); 
 

  //  <!-- Theme js -->  
  e107::js(VB_PLUGINNAME, 'assets/js/skip-link-focus-fix.js', 'jquery');  
  e107::js(VB_PLUGINNAME, 'assets/js/hoverIntent.js', 'jquery');    
 
  e107::js(VB_PLUGINNAME, 'assets/js/owl.carousel.min.js', 'jquery');    
  
  e107::js(VB_PLUGINNAME, 'assets/js/jQuery.countTo.js', 'jquery');  
  e107::js(VB_PLUGINNAME, 'assets/js/progressbar.min.js', 'jquery');    
  e107::js(VB_PLUGINNAME, 'assets/js/masonry.js', 'jquery');  
  e107::js(VB_PLUGINNAME, 'assets/js/jquery.waypoints.js', 'jquery');    
  e107::js(VB_PLUGINNAME, 'assets/js/jquery.magnific-popup.min.js', 'jquery');  
  
  e107::js(VB_PLUGINNAME, 'assets/js/hammer.min.js', 'jquery');    
  e107::js(VB_PLUGINNAME, 'assets/js/froogaloop.min.js', 'jquery'); 
  e107::js(VB_PLUGINNAME, 'assets/js/common.js', 'jquery');  
 
 
 
}
 
class qoobbuilder_front
{

  public $id = null;
  public $entity_type = '';
  private $builderpath = 'qoob';
  private $ajaxUrl = 'includes/ajax.php';
  private $qoobprod = 'prod';

	function __construct()
	{
    $qoobprod =  e107::getPlugConfig(VB_PLUGINNAME)->getPref('qoob_mode');
    $this->qoobprod = ($qoobprod ? "prod" : "dev");

    $this->builderpath =  VB_PATH_ABS.'qoob/';

    $this->ajaxUrl =  VB_PATH_ABS.'includes/ajax.php';

    if(isset($_GET['entity_id']) && !empty($_GET['entity_id']))
		{
			$this->entity_id =  (int)$_GET['entity_id'] ;
		}
    if(isset($_GET['entity_type']) && !empty($_GET['entity_type']))
		{
			$this->entity_type =  e107::getParser()->toDb($_GET['entity_type']);
    }
    
    //Frontend should be managed by theme. 
    if(isset($_GET['qoobbuilder']) && !empty($_GET['qoobbuilder']))  { 
      e107::js(VB_PLUGINNAME, 'qoob/qoob-frontend-starter.js', 'jquery');
      e107::js(VB_PLUGINNAME, 'qoob-e107-driver.js', 'jquery'); 
    
      $inlinecode = 'var starter = new QoobStarter({
      "qoobUrl": "' . $this->builderpath . '",
      "mode": "' .$this->qoobprod. '", 
      "skip":["jquery"],
      "driver": new Qoobe107Driver( { 
        "ajaxUrl": "' . $this->ajaxUrl . '" ,
        "pageId": "' . $_GET['entity_id'] . '" ,
        }
      )});';
      
      e107::js('footer-inline', $inlinecode);
    }  
	}
  
  function run() {
    // TODO: replace $_GET
    if(isset($_GET['qoobbuilder']) && !empty($_GET['qoobbuilder']))  {
      $this->buildingPage();
   }
   else {
      $this->displayPage();
   }
  }
  
  public function buildingPage() {
 
   $ns = e107::getRender();
   $text =  '
   <div class="loader-wrap"></div>
    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- qoob blocks container -->
    <div id="qoob-blocks"></div>
   ';
   $ns->tablerender(LAN_PLUGIN_VB_TR_CAPTION, $text);
   
  } 
	public function displayPage()
	{

		$sql = e107::getDB(); 					// mysql class object
		$tp = e107::getParser(); 				// parser for converting to HTML and parsing templates etc.
		$frm = e107::getForm(); 				// Form element class.
		$ns = e107::getRender();				// render in theme box.
		$text = '';

    $where = ' WHERE entity_id = '.$this->entity_id. '  AND entity_type ="'.$this->entity_type.'"';  

		if($record = $sql->retrieve(VB_DATATABLE,'*', $where )) 	
		{
 
		  $filename = $record['entity_filename']; 
      $path = VB_PATH."pages/";
      $filename = $filename.".html";
		  $text .= file_get_contents($path.$filename);
			$ns->tablerender(LAN_PLUGIN_VB_TR_CAPTION, $text);

		}
	}
 

}
             
$qoobbuilderFront = new qoobbuilder_front;
require_once(HEADERF); 					// render the header (everything before the main content area)
$qoobbuilderFront->run();
require_once(FOOTERF);					// render the footer (everything after the main content area)
exit; 

 

?>