<?php
/*
* Copyright (c) e107 Inc e107.org, Licensed under GNU GPL (http://www.gnu.org/licenses/gpl.txt)
* $Id: e_shortcode.php 12438 2011-12-05 15:12:56Z secretr $
*
*/

if(!defined('e107_INIT'))
{
	exit;
}
if (!defined('VB_INIT')) { 
	require_once(e_PLUGIN.'visualbuilder/includes/visualbuilder_defines.php');
} 

class visualbuilder_shortcodes extends e_shortcode
{
	public $override = false;  
  
  public $settings  = false;
  
	function __construct()
	{
     
	}
 
	/* returns saved HTML output for ID 					*/
	/* @id record id 									  					*/
  /* example:  {VISUALBUILDER: id=1}            */
  function sc_visualbuilder($parm = '')
  {
		//in case if shortcode is directly in layout, load nothing if you are editing element
		$text = '';
		if(e_CURRENT_PLUGIN == "visualbuilder")  {
      return $text;
		}
		$id = $parm['id'];	
		 
		if($id) {   
			$where = ' WHERE id = '.$id;   
			if($record = e107::getDb()->retrieve(VB_DATATABLE,'*', $where )) 	
			{
				$filename = $record['entity_filename']; 
				$path = VB_PATH."pages/";
				$filename = $filename.".html";
				$text .= file_get_contents($path.$filename);
	
			}    
		} 
    return $text;
  }


  
}
