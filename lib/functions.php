<?php

// Admin Functions ////////////////////////////////////////////////////

/*******************************************************************************************/
/***   LOAD PAGE DATA FROM DATABASE - called from ajax.php and loadPageData() in driver    ***/
/*******************************************************************************************/
/* @id - int()  page_id  */


function loadQoobPageData($post_id) {
  if($post_id)  {
   //data are saved as pretty json
      if($data = e107::getDb()->retrieve('qoobbuilder_data', 'entity_data' , ' WHERE id ='.$post_id)) {
        //to array 
        $data = e107::unserialize( $data); 
      }
      else  {
        //TODO:  put this as default value for new data or in settings
        $file = VB_PATH."pages/empty.json";  
        $data =  file_get_contents($file);
        $data = e107::unserialize($data); 
      }    
  }  
  
  return $data;
}

/*******************************************************************************************/
/***   SAVE PAGE DATA TO DATABASE - called from ajax.php and savePageData() in driver    ***/
/*******************************************************************************************/
/* @id - int()  page_id  */
/* @data - array         */

function saveQoobPageData($post_id, $data) {
  //record has to exists already
  $qoobbuilder_data =  e107::serialize($data['data'], 'json');  //result is pretty json
 
  $insert_data = array( 
   'data' => array('entity_data' => $qoobbuilder_data),   
   'WHERE' => 'id = '.$post_id 
  );
  
  $updated_data = e107::getDB()->update('qoobbuilder_data', $insert_data );
  if($updated_data >= 0 )  { $updated = true; }      
  return $updated;
 
}

/*******************************************************************************************/
/***  SAVE PAGE HTML VERSION TO HTML - called from ajax.php and savePageData() in driver ***/
/*******************************************************************************************/
/* @id - int()  page_id  */
/* @data - array         */

function saveQoobPageFile($post_id, $htmldata) {
  //record has to exists already
  $path = VB_PATH."pages/";

  $updated_file =  false;
  $where = ' WHERE id = '.$post_id ; 
  $filename = e107::getDB()->retrieve('qoobbuilder_data','entity_filename', $where); 
  
  if($filename) {
    $filename = $filename.".html";
    $updated_file = file_put_contents($path.$filename, $htmldata );    
    if($updated_file >  0 )  { $updated_file = true; }      
   }
  return $updated_file;
 
}


/*******************************************************************************************/
/***   LOAD BLOCKS LIBRARIES  - called from ajax.php and loadLibrariesData() in driver  ***/
/*******************************************************************************************/

/**
 * Get libs
 *
 * @return json
 */

function getLibs() {

  $result = array();
  $libs = array();
   
  /* try to load default libraries , there is path to demo library */
  // to get absolute path  
  // $pref_libs is array 
  $pref_libs =  e107::getPlugConfig(VB_PLUGINNAME)->getPref('qoob_library');
   
  foreach($pref_libs as $pref_lib) {
       $value =$pref_lib;
       //$value = e107::getParser()->replaceConstants($pref_lib, 'full', true);
       $libs =  json_decode( file_get_contents( $value ), true );
       
       //if url key is missing 
       if ( is_array( $libs ) && ! array_key_exists( 'url', $libs ) ) {
         $libs['url'] =  str_replace( '/lib.json', '', $value )  ;
       }
      if ( json_last_error() === 0 ) {
				// JSON is valid
				array_push( $result, $libs );
			}       
  }

 return $result;
} 

/*******************************************************************************************/
/***   SAVE PAGE TEMPLATE TO PREFS - called from ajax.php and savePageData() in driver   ***/
/*******************************************************************************************/
 
/* @data - jsoned string to array        */

function SaveQoobPageTemplate($data = array()) {
  //record has to exists already
  $qoobbuilder_data = json_encode($data );   
     
  $result = e107::getPlugConfig(VB_PLUGINNAME)->set('qoob_page_templates', $qoobbuilder_data )->save(false);
 
  if($result >= 0 )  { $updated = true; }      
  return $updated;
 
}
 
/*******************************************************************************************/
/***   LOAD PAGE TEMPLATE FROM PREFS - called from ajax.php and loadPageData() in driver ***/
/*******************************************************************************************/

function LoadQoobPageTemplates() {
  $id = 0;  
  if($result = e107::getPlugConfig(VB_PLUGINNAME)->getPref('qoob_page_templates')) {
    $page_templates = json_decode($result, true); 
    foreach ($page_templates as $template) {
      if ( $template['id'] > $id )
        $id = $template['id'];
    }
  }
  else $result = array();

  /*TODO: add support for default theme templates */ 
   
  return $result;

}
 
//addNewImage

//addNewVideo

//WP:
//mainMenu
//upload
//openUploadDialog
//fieldImageActions
//fieldVideoActions

// Public Functions ////////////////////////////////////////////////////


