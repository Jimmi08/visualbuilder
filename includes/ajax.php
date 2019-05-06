<?php
 
require_once('../../../class2.php');
if (!defined('e107_INIT')) { exit; }

if (!defined('VB_INIT')) { 
	require_once(e_PLUGIN.'visualbuilder/includes/visualbuilder_defines.php');
}


require_once(VB_PATH."lib/functions.php");


 
//with saving func is part of data = string from json
$js = e107::getJshelper();
$page_id =  intval($_REQUEST['page_id']) ;


$fl = e107::getFile();
$mes = e107::getMessage();
$tp = e107::getParser();
    

/*******************************************************************************************/
/***   SAVE PAGE DATA   - called from savePageData Warn: function is part of string!     ***/
/*******************************************************************************************/

$incoming = file_get_contents( 'php://input' );  //result is stringed json (stringify)
$decoded = json_decode( $incoming, true );       //result is array
$blocks_html = trim( $decoded['data']['html'] ); //result is array - preparing for caching
 
if ($decoded['func'] == 'qoob_save_page_data') { 
	$response = '';
	$updated = saveQoobPageData($decoded['pageId'], $decoded['data']);
                    
	if ( $updated  ) {
    $htmlupdated = saveQoobPageFile($decoded['pageId'], $blocks_html); 
    if ( $htmlupdated  ) {
  		  $response = array( 'success' => true );
    	} else {
    		$response = array( 'success' => false, 'error' => "Couldn't save data to html file" );
    	}
	} else {
		$response = array( 'success' => false, 'error' => "Couldn't save data to database" );
	}     
	$response = array( 'success' => true );       
	$response = json_encode($response);
	$js->_reset();
	ob_clean();
	$js->addTextResponse($response)->sendResponse();     
	exit;
}
/*******************************************************************************************/
/***   LOAD BLOCKS LIBRARIES  - called from loadLibrariesData                            ***/
/*******************************************************************************************/
elseif ($_POST['action'] == 'qoob_load_libraries_data') {
	$response = '';   
	$libs = getLibs();  //this should be array
 
	$response = array(
		'success' => true,
		'libs' => (isset( $libs ) ? $libs : array()),
	);
    
	$response = json_encode($response);      
	$js->_reset();
	ob_clean();
	$js->addTextResponse($response)->sendResponse();
	exit;  
}

/*******************************************************************************************/
/***   LOAD PAGE DATA  - called from loadPageData                    			         ***/
/*******************************************************************************************/
elseif ($_POST['action'] == 'qoob_load_page_data') {
	$response = '';
	//todo check access
	//if ( ! $this->checkAccess() ) {
	if(false) {
		$response = array(
			'success' => false,
			'error' => 'Access is denied',
		);
	} else {
		// check for new record - is this needed?
		//if ( is_string( get_post_status( $_REQUEST['page_id'] ) ) ) {
		if (  true ) {
			//to get data in  json  
			//$data = get_post_meta( $_REQUEST['page_id'], 'qoob_data', true );
			$data = loadQoobPageData($page_id);
			 
			// Send decoded page data to the Qoob editor page
			if ( '' !== $data ) {
				//decode data
				//$data = wp_unslash( json_decode( $data, true ) ); 
				//$data = e107::unserialize( $data, 'json'  ) - inside function;
 
				if ( ! empty( $data['blocks'] ) && is_null( $data['version'] ) ) {
					foreach ( $data['blocks'] as $index => $block ) {
						$data['blocks'][ $index ]['block'] = $data['blocks'][ $index ]['template'];
						unset( $data['blocks'][ $index ]['template'] );
					}

					$data['version'] = $this->version;
				}
       
				$response = array(
					'success' => true,
					'data' => $data,
				);
			} else {
				$response = array( 'success' => true, 'data' => array( 'version' => $this->version, 'blocks' => array() ) );
			}
		} else {
			$response = array( 'success' => false, 'error' => 'Page with id ' + $page_id + ' not found.' );
		}
	}              
	$response = json_encode($response);
	$js->_reset();
	ob_clean();
	$js->addTextResponse($response)->sendResponse();     
	exit;   
}
/*******************************************************************************************/
/***   SAVE TEMPLATE DATA  - called from savePageTemplate                    			       ***/
/*******************************************************************************************/
elseif ($_REQUEST['action'] == 'qoob_save_page_template') {

	$response = '';

	$updated = SaveQoobPageTemplate($decoded);  
                 
	if ( '' !== $data ) {
  		  $response = array( 'success' => true );
  } else {
    		$response = array( 'success' => false, 'error' => "Couldn't save data to template file" );
  }
         
	$response = json_encode($response);
	$js->_reset();
	ob_clean();
	$js->addTextResponse($response)->sendResponse();     
	exit;  
    
}
/*******************************************************************************************/
/***   LOAD TEMPLATE DATA  - called from loadPageTemplate                    			       ***/
/*******************************************************************************************/
elseif ($_REQUEST['action'] == 'qoob_load_page_templates') {

	$response = '';

	$result = LoadQoobPageTemplates();  
               
	// Send decoded page data to the Qoob editor page
	if ( count($result) > 0 ) {
		$response = array( 'success' => true, 'templates' =>  $result   );
	} else {
		$response = array( 'success' => false );
	}
        
	$response = json_encode($response);
	$js->_reset();
	ob_clean();
	$js->addTextResponse($response)->sendResponse();     
	exit;  
    
}


/*******************************************************************************************/
/***   UPLOAD IMAGE  - called from uploadImage                    			               v ***/
/*******************************************************************************************/
elseif ($_REQUEST['action'] == 'qoob_add_new_image') {

		$data = array();
   
		if ( empty( $_FILES ) ) {
			$data['error'] = false;
			$data['message'] =  'Please select an image to upload!' ;
		} elseif ( $file['size'] > 5242880 ) { // Maximum image size is 5M
			$data['size'] = $files[0]['size'];
			$data['error'] = false;
			$data['message'] =  'Image is too large. It must be less than 5M!' ;
		} else {
			$data['message'] = '';
             
			if ( isset( $_FILES['file_userfile'] ) ) {
				$file = $_FILES['file_userfile'];     
        
        $fileName = basename($file);
        $fileName = str_replace(array('%','+'),'',$fileName);
        
				$fl = e107::getFile();

				$uploaded = $fl->getUploaded(e_TEMP, "unique", array('max_file_count' => 2 ));
				
				$localfile = $uploaded[0]['name'];

				@copy(e_TEMP.$localfile, VB_IMAGE_DIR.$localfile);
				@unlink(e_TEMP.$localfile);
				
				$img_thumb = VB_IMAGE_DIR_FULL. $uploaded[0]['name'];
 
				$data['success'] = true;
				$data['url'] = $img_thumb;
			 
			}

			if ( ! $img_thumb  ) {
				$data['error'] = false;
				$data['message'] = 'An error has occured. Your image was not added.';
			}
		}
            
		$response = json_encode($data);
		$js->_reset();
		ob_clean();
		$js->addTextResponse($response)->sendResponse();     
		exit;  
 
    
}

 
 /* moved to each function, better for testing
$js->_reset();
ob_clean();
$js->addTextResponse($response)->sendResponse();
exit;    */    
 
 






