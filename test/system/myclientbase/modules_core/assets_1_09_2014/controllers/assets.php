<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Assets extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('asset_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view('assets');
	}
	function loadData($cmd='false'){
		
		$data = $this->asset_model->getAllData($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		
		//echo $this->asset_model->delete_assets(); 
		$this->output->set_output($this->asset_model->delete_assets()); 
	}	
	function index1()
	{
		
		$this->load->helper('flexigrid');
		/*
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		$colModel['id'] = array('ID',40,TRUE,'center',2);
		$colModel['assets_name'] = array('Asset Name',150,TRUE,'center',1);
		$colModel['device_id'] = array('Device',100,TRUE,'center',1);
		$colModel['icon_id'] = array('Icon',100,TRUE,'center',1);
		$colModel['sim_number'] = array('Sim Number',150, TRUE,'center',1);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 'auto',
		'rp' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'My Assets List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionAssets');
		$buttons[] = array('Edit','edit','actionAssets');
		$buttons[] = array('Delete','delete','actionAssets');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionAssets');
		$buttons[] = array('DeSelect All','delete','actionAssets');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('allassets_list',site_url("/assets/ajax/allAssets"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('assets',$data);
	}
	function form() {

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);
		
		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			
	              
                        $user_asset = '';
			$rows = $this->asset_model->get_asset_users();
                       // echo "<pre>";
                       // print_r($rows);
			$groups = $this->form_model->asset_users_id;
			$grp = $this->form_model->asset_users_id;
			$grp = explode(",", $grp);
			if(count($rows)) {
				foreach ($rows as $row) {				
					$user_asset .= '<option value="'.$row->user_id.'"';
					if(in_array($row->user_id, $grp))
						$user_asset .= ' selected="selected"';
					$user_asset .= '>'.$row->username.'</option>';
				}
			}
                       	$this->form_model->asset_users_id = $user_asset;
				
			
			
			if($this->form_model->icon_id=="")
			{
				$this->form_model->icon_id=12;
			}
			$iconPath = '';
			$iconName = '';
			$rows = $this->asset_model->getIconPath($this->form_model->icon_id);
			
			foreach ($rows as $row) {
				$iconPath = $row->icon_path;
				 $iconName = $row->icon_name;
			}
			$this->form_model->iconPath = $iconPath;
			$this->form_model->iconName = $iconName;
			
			$rows_type = $this->asset_model->prepare_assets_type();
			
			$typeOpt = '';
			foreach ($rows_type as $row) {
				$typeOpt .= '<option value="'.$row->id.'"';
				if($row->id == $this->form_model->assets_type_id)
					$typeOpt .= ' selected="selected"';
				$typeOpt .= '>'.$row->assets_type_nm.'</option>';
			}
			$this->form_model->assets_type_id = $typeOpt;

			$this->form_model->ownersOpt 	= $this->asset_model->prepare_owner_combo();
			$this->form_model->divisionOpt 	= $this->asset_model->prepare_division_combo();

			$rows_battery = $this->asset_model->prepare_battery_combo();
			$batteryOpt = '';
			foreach ($rows_battery as $row) {
				$batteryOpt .= '<option value="'.$row->volt.'"';
				if($row->volt == $this->form_model->battery_size)
					$batteryOpt .= ' selected="selected"';
				$batteryOpt .= '>'.$row->volt.' Volt</option>';
			}
			$this->form_model->batteryOpt = $batteryOpt;
			
			$rows_group = $this->asset_model->assets_group_data();
			$assets_group_id = '';
			foreach ($rows_group as $row) {
				$assets_group_id .= '<option value="'.$row->id.'"';
				if($row->id == $this->form_model->assets_group_id) {
					$assets_group_id .= ' selected="selected"';
					$last_group = $this->form_model->assets_group_id;
				}
				$assets_group_id .= '>'.$row->group_name.'</option>';
			}
			$this->form_model->assets_group_id = $assets_group_id;
			$this->form_model->last_group = $last_group;
			
			$rows_telecom = $this->asset_model->prepare_telecom_provider();
			$tProvider = '';
			foreach ($rows_telecom as $row) {
				$tProvider .= '<option value="'.$row->id.'"';
				if($row->id == $this->form_model->telecom_provider)
					$tProvider .= ' selected="selected"';
				$tProvider .= '>'.$row->telecom_provider_name.'</option>';
			}			
			$this->form_model->tProvider = $tProvider;
			$arr=array();
			$arr=explode(",",$this->form_model->sensor_type);
			if(!isset($this->form_model->fuel_in_out_sensor)){
				$this->form_model->fuel_in_out_sensor=0;
			}
			if(!isset($this->form_model->xyz_sensor)){
				$this->form_model->xyz_sensor=0;
			}
			
			if(!isset($this->form_model->rollover_tilt)){
				$this->form_model->rollover_tilt=0;
			}
			if(!isset($this->form_model->panic)){
				$this->form_model->panic=0;
			}
			if(!isset($this->form_model->runtime)){
				$this->form_model->runtime=0;
			}
			
			if(!isset($this->form_model->sensor_fuel)){
				if(in_array("FUEL",$arr)){
					$this->form_model->sensor_fuel=1;
				}else{
					$this->form_model->sensor_fuel=0;
				}
			}
			if(!isset($this->form_model->sensor_tempr)){
				if(in_array("TEMPERATURE",$arr)){
					$this->form_model->sensor_tempr=1;
				}else{
					$this->form_model->sensor_tempr=0;
				}
			}
			if(!isset($this->form_model->fuel_in_out_sensor)){
				if(in_array("Fuel IN/Out Sensor",$arr)){
					$this->form_model->fuel_in_out_sensor=1;
				}else{
					$this->form_model->fuel_in_out_sensor=0;
				}
			}
			if(!isset($this->form_model->xyz_sensor)){
				if(in_array("XYZ Sensor",$arr)){
					$this->form_model->xyz_sensor=1;
				}else{
					$this->form_model->xyz_sensor=0;
				}
			}
			if(!isset($this->form_model->rollover_tilt)){
				if(in_array("Rollover/Tilt",$arr)){
					$this->form_model->rollover_tilt=1;
				}else{
					$this->form_model->rollover_tilt=0;
				}
			}
			if(!isset($this->form_model->panic)){
				if(in_array("Panic",$arr)){
					$this->form_model->panic=1;
				}else{
					$this->form_model->panic=0;
				}
			}
			if(!isset($this->form_model->runtime)){
				if(in_array("Runtime",$arr)){
					$this->form_model->runtime=1;
				}else{
					$this->form_model->runtime=0;
				}
			}
			$this->form_model->user_id = $this->session->userdata('user_id');
			
			$this->load->view('form');
		}

		else {
			$formdata = $this->form_model->db_array();			
			//$formdata['add_date'] = date('Y-m-d H:i:s');
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('user_id');
//			$formdata['sensor_type']="VTS";

			if(isset($formdata['sensor_fuel'])){
				$formdata['sensor_type'].=",FUEL";
				unset($formdata['sensor_fuel']);
				//********send command to store fuel data******//
				$command = '';
				$file_path = '../telnet/cmd.txt';
				
				if($formdata['tank_type'] == 'horizontal_cylinder' || $formdata['tank_type'] == 'vertical_cylinder'){
					$command .= "send ".$formdata['device_id']." hcyl,".$formdata['tank_diameter'].",".$formdata['tank_length'].",".$formdata['max_fuel_capacity']."\n";
				}else{
					$command .= "send ".$formdata['device_id']." hcyl,".$formdata['tank_diameter'].",".$formdata['tank_width'].",".$formdata['tank_length'].",".$formdata['max_fuel_capacity']."\n";
				}
				if($command != ""){
					$file_handle = fopen($file_path, "w");
					fwrite($file_handle, $command);
					fclose($file_handle);
				}
				//**************//
			}
			if(isset($formdata['sensor_tempr'])){
				$formdata['sensor_type'].=",TEMPERATURE";
				unset($formdata['sensor_tempr']);
			}
			if(isset($formdata['fuel_in_out_sensor'])){
				$formdata['sensor_type'].=",Fuel IN/Out Sensor";
			}
			if(isset($formdata['xyz_sensor'])){
				$formdata['sensor_type'].=",XYZ Sensor";
			}
			
			if(isset($formdata['rollover_tilt'])){
				$formdata['sensor_type'].=",Rollover/Tilt";
			}
			if(isset($formdata['panic'])){
				$formdata['sensor_type'].=",Panic";
			}
			if(isset($formdata['runtime'])){
				$formdata['sensor_type'].=",Runtime";
			}
			
			if(!isset($formdata['fuel_in_out_sensor'])){
				$formdata['fuel_in_out_sensor']=0;
			}
			if(!isset($formdata['xyz_sensor'])){
				$formdata['xyz_sensor']=0;
				//unset($formdata['xyz_sensor']);
			}
			
			if(!isset($formdata['rollover_tilt'])){
				$formdata['rollover_tilt']=0;
				//unset($formdata['rollover_tilt']);
			}
			if(!isset($formdata['panic'])){
				$formdata['panic']=0;
				//unset($formdata['panic']);
			}
			if(!isset($formdata['runtime'])){
				$formdata['runtime']=0;
				//unset($formdata['runtime']);
			}

			$formdata['sensor_type'] = trim($formdata['sensor_type'], ',');
			
			    // $formdata['asset_users_id']=trim($formdata['asset_users_id'], ',');
                       
			//if(!isset($formdata['asset_users_id'])){
				//$formdata['asset_users_id']="";
			//}
			
                       // echo"<pre>";
                      //  print_r($formdata);
                        $newData = array();
			foreach($formdata as $key=>$value){
				if(is_array($value)){
					if(count($value) > 0)
						$value = implode(",", $value);
					else
						$value = "";
				}
				$newData[$key] = $value;
                                
			}
			if(!isset($newData['asset_users_id'])){
				$newData['asset_users_id']="";
			}
                        $users_id= array();
                        $users_id= $newData['asset_users_id'];
                       
                       // echo"<pre>";
                        //print_r($users_id);
                        
			$newData['status']=1;
                        
			if(uri_assoc('id')){
				$this->form_model->save($newData, uri_assoc('id'));
			}else{
				$this->asset_model->save($newData, uri_assoc('id'));
			}
			//comment by harshal 
			/*if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->asset_model->save($formdata, uri_assoc('id'));
			}*/

			// $this->asset_model->map_assets();
		}
	}
	function checkDupli(){
		$device=uri_assoc('deviceId');
		$id=uri_assoc('id');
		if($device!="id"){
			$result=$this->asset_model->checkDupli($device,$id);
			$users="";
			foreach($result as $row){
				$users.="Asset : ".$row->assets_name.", User : ".$row->username.", ";
			}
			$users=trim($users,", ");
			if($users!=""){
				$data['result']=true;
			}else{
				$data['result']=false;
			}
			$data['users']=$users;
			//die(json_encode($data));
			$this->output->set_output(json_encode($data));
		}else{
			$data['result']=-1;
			$data['users']="";
			$this->output->set_output(json_encode($data));
		}
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}
	function getIco()
	{
		$rows = $this->asset_model->prepare_icon();
		$iconDiv = "<div style='height: 200px; margin-top: 10px; margin-bottom: 10px; margin-left: 10px; overflow: auto; width: 97%;'>";
		foreach ($rows as $row) {
			$iconDiv .= "<div style='border: 1px solid rgb(197, 219, 236); white-space: nowrap; margin: 2px; display: inline-block;cursor:pointer' id='getIcon_div' class='imageSection' ";
			$iconDiv .=" onClick='selectedMarker(\"".$row->icon_path."\",\"".$row->icon_name."\",".$row->id.")'>";
			$iconDiv .="<img src='".base_url()."/assets/marker-images/".$row->icon_path."' height='30' width='20' title='".$row->icon_name."' rel='".$row->id."'></div>";
			
			/*<option title="'.$this->config->item('base_url').'assets/marker-images/'.$row->icon_path.'" value="'.$row->id.'"';
			//if($row->id == $this->form_model->icon_id)
			//	$iconOpt .= ' selected="selected"';
			$iconDiv .= '>'.$row->icon_name.'</option>';*/
		}
		$iconDiv.="</div>";
		//echo $iconDiv;
		$this->output->set_output($iconDiv);
	}
	function asset()
	{
		$this->load->model('asset_model');
		$rows = $this->asset_model->get_locations();
		foreach ($rows as $row) {
            $data['lat'] = $row->lati;
			$data['lng'] = $row->longi;
			$data["last_id"] = $row->id;
			$text  = 'Lat : '.$row->lati."<br>";
			$text .= 'Lng : '.$row->longi."<br>";
			$text .= 'Date : '.$row->add_date."<br>";
			$text .= 'Speed : '.$row->speed."<br>";
			$data['html'] = $text;
        }
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords($data['lat'], $data['lng']);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		$data['headerjs'] = $this->gmap->getHeaderJS();
		
		$get = $this->uri->uri_to_assoc();
		$data["prefix"] = uri_assoc('id');
		if($get['window']=='new'){
			$this->load->view('asset_new_window',$data);
		}else{
			$this->load->view('asset',$data);
		}
	}
	function newPoint()
	{
		$this->load->model('asset_model');
		$rows = $this->asset_model->get_new_locations();
		$lat = array();
		$lng = array();
		$html = array();
		foreach ($rows as $row) {
            $lat[] = $row->lati;
			$lng[] = $row->longi;
			$text  = 'Lat : '.$row->lati.'<br>';
			$text .= 'Lng : '.$row->longi.'<br>';
			$text .= 'Date : '.$row->add_date."<br>";
			$text .= 'Speed : '.$row->speed."<br>";
			$html[] = $text;
			$data["last_id"] = $row->id;
        }
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	
	
	function trackList() {
		
		$page = (uri_assoc('page')) ? uri_assoc('page') : 1;
		$params = array(
			'limit'		=>	$this->mdl_mcb_data->results_per_page,
			'paginate'	=>	TRUE,
			'page'		=>	$page,
			'order_by'	=>	'id desc'
		);
		
		$data = array(
			'track' =>	$this->mdl_track->get($params)
		);
		$this->load->view('tracklist', $data);
	}

	function assets_category_data()
	{
		//echo $query=$this->asset_model->get_cat();	
		$this->output->set_output($query=$this->asset_model->get_cat());
	}
	function assets_group_data()
	{
		//echo $query=$this->asset_model->get_cat();	
		$this->output->set_output($query=$this->asset_model->assets_group_data());
	}
	function assets_category_data_post()
	{
		//echo $query=$this->asset_model->get_cat_post();	
		$this->output->set_output($query=$this->asset_model->get_cat_post());
	}
	function do_upload()
	{
//		die($_FILES);
		/*$uploaddir 	= 'picture/';
	
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
	
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
	
			$this->load->view('form', $error);
		}   
		else
		{
			$data = array('upload_data' => $this->upload->data());
	
			$this->load->view('upload_success', $data);
		}*/
	}   
	
	
}