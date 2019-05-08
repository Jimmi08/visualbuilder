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
 
e107::lan('visualbuilder',true);

 /* for time being e_event doesn't work*/
e107_require_once(e_PLUGIN . 'visualbuilder/includes/visualbuilder.class.php');

$vb = new qoobbuilder();
$vb->updateAddonList();
$types = $vb->setLibrary();
$skin = $vb->setSkin();
    
class qoobbuilder_adminArea extends e_admin_dispatcher
{

	protected $modes = array(	
	
		'main'	=> array(
			'controller' 	=> 'qoobbuilder_data_ui',
			'path' 			=> null,
			'ui' 			=> 'qoobbuilder_data_form_ui',
			'uipath' 		=> null
		),
		'main/prefs' => array(
			'caption' => 'Libraries',
			'perm'  => 'P',
		),
		

	);	
	
	
	protected $adminMenu = array(

		'main/list'			=> array('caption'=> LAN_MANAGE, 'perm' => 'P'),
		'main/create'		=> array('caption'=> LAN_CREATE, 'perm' => 'P'),

		'main/prefs' => array(
			'caption' => 'Preferencies',
			'perm'  => 'P',
		),
		// 'main/custom'		=> array('caption'=> 'Custom Page', 'perm' => 'P'),
		
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = 'Qoob Builder';
}




				
class qoobbuilder_data_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Qoob Builder';
		protected $pluginName		= VB_PLUGINNAME;
	 	protected $eventName		= 'qoobbuilder-qoobbuilder_data'; // remove comment to enable event triggers in admin. 		
		protected $table			= VB_DATATABLE;
		protected $pid				= 'id';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
		protected $batchExport   = true;
		protected $batchCopy		= true;

		//	protected $sortField		= 'somefield_order';
		//	protected $sortParent   = 'somefield_parent';
		//	protected $treePrefix   = 'somefield_title';

		//	protected $tabs				= array('Tabl 1','Tab 2'); // Use 'tab'=>0 OR 'tab'=>1 in the $fields below to enable. 
			
		//	protected $listQry   	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= 'id DESC';
	
		protected $fields 		= array(
			'checkboxes' => array(
												'title' => '',
												'type' => null,
												'data' => null,
												'width' => '5%',
												'thclass' => 'center',
												'forced' => true,
												'class' => 'center',
												'toggle' => 'e-multiselect',
												'readParms' => array(),
												'writeParms' => array()
											),
  	'id' => array(
									'title' => LAN_ID,
									'data' => 'int',
									'width' => '5%',
									'help' => '',
									'readParms' => array(),
									'writeParms' => array(),
									'class' => 'left',
									'thclass' => 'left'
								),
  	'entity_id' => array(
													'title' => LAN_ID,
													'type' => 'number',
													'data' => 'int',
													'width' => '5%',
													'inline' => true,
													'help' => '',
													'readParms' => array(),
													'writeParms' => array(),
													'class' => 'left',
													'thclass' => 'left'
												),
  	'entity_type' => array(
														'title' => LAN_TYPE,
														'type' => 'text',
														'data' => 'str',
														'width' => 'auto',
														'batch' => true,
														'filter' => true,
														'inline' => true,
														'help' => '',
														'readParms' => array(),
														'writeParms' => array(),
														'class' => 'left',
														'thclass' => 'left'
													),
  	'entity_filename' => array(
																'title' => 'File Name',
																'type' => 'text',
																'data' => 'str',
																'width' => 'auto',
																'batch' => true,
																'filter' => true,
																'inline' => true,
																'help' => '',
																'readParms' => array(),
																'writeParms' => array('readonly'=>true),
																'class' => 'left',
																'thclass' => 'left'
															),

  	'entity_data' => array(
														'title' => 'Data',
														'type' => 'method',
														'data' => 'str',
														'width' => 'auto',
														'help' => '',
														'readParms' => array(),
														'writeParms' => array('readonly'=>true),
														'class' => 'left',
														'thclass' => 'left',
														'filter' => false,
														'batch' => false
													),
  	'options' => array(
												'title' => LAN_OPTIONS,
												'type' => 'method', 
												'data' => null,
												'width' => '10%',
												'thclass' => 'center last',
												'class' => 'center last',
												'forced' => true,
												'readParms' => array(),
												'writeParms' => array()
											),
	);

		
		protected $fieldpref = array('id','entity_id', 'entity_type', 'entity_filename', 'entity_data');
			
	
		//	protected $preftabs    = array('General', 'Other' );
		protected $prefs = array(
			'qoob_library'  => array(
				'title'   => "Goob Libraries",
				'type'    => 'method',
				'data'    => false,
				'writeParms' => array(
				'size'=>'block-level',
				'post' => "<div class='bg-info' style='color: white;padding: 5px;'> You can change this only via e_visualbuilder addon </div>"),
				'tab'    => 0,
			),
			'qoob_skin'  => array(
				'title'   => "Skin",
				'type'    => 'text',
				'data'    => false,
				'writeParms' => array(
				  'size'=>'block-level',
					'post' => "<div class='bg-info' style='color: white;padding: 5px;'> You can change this only via e_visualbuilder addon </div>",
				),
				'tab'    => 0,
			),
			'qoob_skinUrl'  => array(
				'title'   => "Skin Url",
				'type'    => 'text',
				'data'    => false,
				'writeParms' => array('readonly'=>true, 'size'=>'block-level'),
				'tab'    => 0,
			),
			'qoob_mode'  => array(
				'title'   => "Production Mode",
				'type'    => 'boolean',
				'data'    => int,
				'writeParms' => array('enabled'=>LAN_PLUGIN_VB_MODE_PROD, 'disabled'=>LAN_PLUGIN_VB_MODE_DEV), 
				'tab'    => 0,
			),

		);

	
		public function init()
		{
 
	
		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data)
		{
			$new_data['entity_filename'] = $new_data['entity_type'] .'_'.  $new_data['entity_id'];
      return $new_data;
		}
	
		public function afterCreate($new_data, $old_data, $id)
		{                                      
      // do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something		
		}		
		
		
		// ------- Customize Update --------
		
		public function beforeUpdate($new_data, $old_data, $id)
		{
			$new_data['entity_filename'] = $new_data['entity_type'] .'_'.  $new_data['entity_id'];
      return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something	
		}
		
		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something		
		}		
		
		// left-panel help menu area. (replaces e_help.php used in old plugins)
		public function renderHelp()
		{
			$caption = LAN_HELP;
			$text = 'Some help text';

			return array('caption'=>$caption,'text'=> $text);

		}
			
}
				


class qoobbuilder_data_form_ui extends e_admin_form_ui
{


	// Custom Method/Function 
	function qoob_library($curVal,$mode)
	{
		$text = '';

		switch($mode)
		{
			case 'read': // List Page
				return $curVal;
			break;
			
			case 'write': // Edit Page
		 
				$text .= "<table>";
				foreach($curVal as $type => $library) {
				$text .= "<tr><td><b>".ucfirst($type).": </b> <td><td>".$library."<td><tr>";
				
				}
				$text .= "</table>";
				return $text;
			break;
		
		}
				
		return null;
	}
  	
	// Custom Method/Function 
	function entity_data($curVal,$mode)
	{
		$id = $this->getController()->getFieldVar('id');
		$entity_id = $this->getController()->getFieldVar('entity_id');
		$entity_type = $this->getController()->getFieldVar('entity_type');

		$data['id'] = $id;
		$data['entity_id'] = $entity_id;
		$data['entity_type'] = $entity_type;

		$frontendPageUrl = e107::url(VB_PLUGINNAME, 'page', $data, 'full'); 		
		switch($mode)
		{
			case 'read': // List Page
				$text = '';
				$text .= '{VISUALBUILDER: id='.$id.'}';
				$text .= '<br><div class="cube-button">';
				$text .= '<a  href="'.$frontendPageUrl.'" target="_blank"><i class="fa fa-eye"></i> Show on frontend </a>';
				$text .= '</div>';
				return $text;
			break;
			
			case 'write': // Edit Page
				return $curVal;
			break;
 
		}
		
		return null;
	}
 
	function options($parms, $value, $id, $attributes)
	{
		$text = ''; 
		if($attributes['mode'] == 'read')
		{
		 
			parse_str(str_replace('&amp;', '&', e_QUERY), $query);    // Why I put this line here?
	 
		  	// QUESTION: get data from controller or ID of record is enough? from e_admin use entities? 
		  	// from here 'id' = 'entity_id' for now
	  
		 	$entity_id = $this->getController()->getFieldVar('entity_id');
	  
	  		$query['action'] = 'edit';   //if($_REQUEST['action'] == 'edit') - only then is qoob fired. Maybe qoob is better idea.
			$query['entity_id'] = $entity_id;
			$query['entity_type'] = 'custom';  //it will be different from e_admin 
			$query['id'] = $id;			
 
   			// $query['use_ajax'] = 1;
			$query = http_build_query($query,null, '&amp;');
			$editurl = VB_PATH_ABS."qoobbuilder.php?".$query;

			$text .= '<div class="cube-button">
			  <a href="'.$editurl.'" target="_blank" ><i class="cube"></i><span>Edit with Visual Builder</span></a></div>';
      $attributes['type'] = null;
      $text .= $this->renderValue('options', $value, $attributes,  $id);
			return $text;
		}
	}  
}	

 	
 new qoobbuilder_adminArea();
 require_once(e_ADMIN."auth.php");
 e107::getAdminUI()->runPage();
 
 require_once(e_ADMIN."footer.php");
 exit;
 