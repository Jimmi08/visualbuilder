<?php


//v2.x Standard for extending admin areas.
// not load this in all admin area. Time loading issue. 

$notsupported = true;
if(e_CURRENT_PLUGIN == "jmelements") { $notsupported = false; }
if($notsupported) { return; }
 

class visualbuilder_admin implements e_admin_addon_interface
{
	private $active = false;
 
	function __construct()
	{
		// $pref = e107::pref('core','trackbackEnabled');
		$this->active = 1;

	}

	function load($event, $ids)
	{
        $sql = e107::getDb();
        $arr = array(); $ids_array = array();
        $data = $sql->retrieve("qoobbuilder_data", 
        "id, entity_type, entity_id", "entity_type='".$event."' AND entity_id IN(".$ids.")", true);
        $ids_array = explode(",",$ids);
        foreach($ids_array as $id) {
            $arr[$id]['inuse'] = array( 
                'id' => null,
                'entity_type' => $event,
                'entity_id' => $id ); 
        } 
 
		foreach($data as $row)
		{
			$id = $row['entity_id'];
			$arr[$id]['inuse'] = array( 
                'id' => $row['id'],
                'entity_type' => $event,
                'entity_id' => $id);   
        }
		return $arr;
	}


	/**
	 * Extend Admin-ui Parameters
	 * @param $ui admin-ui object
	 * @return array
	 */
	public function config(e_admin_ui $ui)
	{
		$action     = $ui->getAction(); // current mode: create, edit, list
		$type       = $ui->getEventName(); // 'wmessage', 'news' etc.
		$id         = $ui->getId();
		$sql        = e107::getDb();

        $config = array();
        
        // $id is available only in edit mode
        if(($action == 'edit') && !empty($id) && ( 
            $vb_id =  $sql->retrieve("qoobbuilder_data", "id", "entity_type='".$type."' AND entity_id=".$id)))
           
        {
            $default['id'] = $vb_id;
            $default['entity_type'] = $type;
            $default['entity_id'] = $id;
        }
        else
        {
            $default['id'] = null;
            $default['entity_type'] = $type;
            $default['entity_id'] = $id;
        }
 
		switch($type)
		{
			case "jmelements":
                if(isset($config['tabs'])) {
				$config['tabs'] = array('ref'=>'Visual Builder');
                }
                else {
                    $config['tabs'] = array('0'=>'Data', 'vb'=>'Visual Builder');   
                }
 

				if($this->active == true)
				{
					$config['fields']['inuse'] =   array ( 'title' =>"VB", 'type' => 'method',  'data'=> null, 'tab'=>'vb',  'writeParms'=> array('nolabel'=>true, 'size'=>'xxlarge', 'placeholder'=>'', 'default'=>$default), 'width' => 'auto', 'help' => '', 'readParms' => '', 'class' => 'left', 'thclass' => 'left',  );
				}
				break;
		}

		//Note: 'url' will be returned as $_POST['x_visualbuilder_inuse']. ie. x_{PLUGIN_FOLDER}_{YOURKEY}

		return $config;

	}


	/**
	 * Process Posted Data.
	 * @param $ui admin-ui object
	 */
	public function process(e_admin_ui $ui, $id=0)
	{
        return;  //TODO save  
 

    }
    



}

class visualbuilder_admin_form extends e_form
{

	/**
	 * @param $curval
	 * @param $mode
	 * @param $att
	 * @return null|string
	 */
    function x_visualbuilder_inuse($curval, $mode, $att=null)
	{
        switch($mode)
		{
            case "read":
            if(!empty($curval['id'])) {
                //TODO: styling to css
                $text = ''; 
                $query['action'] = 'edit';   //if($_REQUEST['action'] == 'edit') - only then is qoob fired. Maybe qoob is better idea.
                $query['id'] = $curval['id'];			

                // $query['use_ajax'] = 1;
                $query = http_build_query($query,null, '&amp;');
                $editurl = VB_PATH_ABS."qoobbuilder.php?".$query;

                $text .= '<div class="cube-button" style="margin: 0px; background: #0093e8;">
                <a href="'.$editurl.'" target="_blank" ><i class="cube" style="width: 30px; height: 30px; margin: 0px;"></i></a></div>';
            }
            else {
                $text .= '<div class="cube-button" style="margin: 0px; background: #212121;">
                <a><i class="cube" style="width: 34px; height: 34px; margin: 0px;"></i></a> </div>';

            }
                return $text;
			break;

			case "write":
            
             //TODO: styling to css
             $text = ''; 
             $query['action'] = 'edit';   //if($_REQUEST['action'] == 'edit') - only then is qoob fired. Maybe qoob is better idea.
             if(!empty($curval['id'])) { 
             $query['id'] = $curval['id'];			

             // $query['use_ajax'] = 1;
             $query = http_build_query($query,null, '&amp;');
             $editurl = VB_PATH_ABS."qoobbuilder.php?".$query;

             $text .= '<div class="cube-button" style="margin: 0px; background: #0093e8;">
             <a href="'.$editurl.'" target="_blank" ><i class="cube" style="width: 30px; height: 30px; margin: 0px;"></i>Edit with Visual Builder</a></div>';   

             $pagedata['entity_id'] = $curval['entity_id'];
             $pagedata['entity_type'] = $curval['entity_type']; //it will be different from e_admin 

             $frontendPageUrl = e107::url(VB_PLUGINNAME, 'page', $pagedata, 'full');
             
            
             $text .= '<hr>';
				$text .= '{VISUALBUILDER: id='.$curval['id'].'}';
				$text .= '<br><div class="cube-button">';
				$text .= '<a  href="'.$frontendPageUrl.'" target="_blank"><i class="fa fa-eye"></i> Show on frontend </a>';
				$text .= '</div>';
				return $text;
             
				return $text;
				break;
            }
			default:
				// code to be executed if n is different from all labels;
		}

		return null;


	}

}