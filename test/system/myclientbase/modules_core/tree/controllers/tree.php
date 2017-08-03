<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tree extends Admin_Controller {
	function __construct() {
		parent::__construct();
		//$this->load->library('session');
		$this->load->helper('mcb_date');
		//userdata('time_zone');
		date_default_timezone_set($this->session->userdata('time_zone'));
		$this->load->model('tree_model');
	}
	
	function chat(){
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		if($action=='chatheartbeat'){
			$this->tree_model->chatheartbeat();
		}if($action=='closechat'){
			$this->tree_model->closechat();
		}
		if($action=='startchatsession'){
			$this->tree_model->startchatsession();
		}
		if($action=='sendChat'){
			$this->tree_model->sendChat();
		}
	}
	
	function index(){
		$query = "update alert_master set del_date='".gmdate('Y-m-d H:i:s')."' where user_id=".$this->session->userdata('user_id');
		$this->db->query($query);
		//$rs=$this->tree_model->delete_tbl_track_data();
		$coords = array();
		$data = array();
		$deviceOpt = array();

		$rows=$this->tree_model->getUserDisplay_Settings();
		$display_settings=$rows;
		$this->session->set_userdata($display_settings);
		
			//die(print_r($rows));
	
		$rows = $this->tree_model->get_devices($this->session->userdata('user_id'));
		$d_assets_cmb="";
		$countAssets=count($rows);
		//if(count($rows)>1)
		//	$d_assets_cmb.="<option value=''>Please Select</option>";
		if(count($rows)) {
			foreach ($rows as $row) {
				$d_assets_cmb.="<option value='".$row->id."'>";
				$d_assets_cmb.=$row->assets_name." (".$row->device_id.")";
				$d_assets_cmb.="</option>";
			}
		}
		
		$data['usr_assets_cmb']=$d_assets_cmb;
		$data['usr_assets_cmb_count']=$countAssets;
		
		$this->load->library('GMap');
		$data['main_menu'] = $this->tree_model->get_main_menu();
		$this->gmap->GoogleMapAPI();
		$data['headerjs'] = $this->gmap->getHeaderJS();
		
		$row = $this->tree_model->getServiceExpiryAlertBeforeDays();
		$days_before = $row->data_value;
		
		$row = $this->tree_model->getRemainingDays();
		$remaining_days = $row->days;
		$expiry_date = $row->to_date;
		$data['msg'] = '';
		
		if($remaining_days <= $days_before && $remaining_days != "" ){
			$data['msg'] = "Your account expires on ".date($this->session->userdata('date_format'), strtotime($expiry_date))." [$remaining_days days remain]";
		}
		
		$row = $this->tree_model->getMessage();
		$msg = $row->data_value;
		if($msg != "" ){
			$data['msg'] = $msg;
		}
		
		$row = $this->tree_model->getAutoRefreshSettings();
		$data['auto_refresh_setting'] = $row->auto_refresh_setting;
		
		$this->load->view('home_tree',$data);
	}
	
	function saveRoute(){
		$insert = $this->tree_model->save_route($this->session->userdata('user_id'));
		//echo $insert;
		$this->output->set_output($insert);
	}
	function alert_master(){
		//echo json_encode($this->tree_model->alert_master());
		$this->output->set_output(json_encode($this->tree_model->alert_master()));
	}
	function updateRoute(){
		$insert = $this->tree_model->update_route($this->session->userdata('user_id'));
		//echo $insert;
		$this->output->set_output($insert);
	}
	function view_photo(){
		$this->load->view("upload_image");
	}
	function get_photo(){
		//echo $query=$this->tree_model->get_user_photo();
		$this->output->set_output($query=$this->tree_model->get_user_photo());
	}
	function put_photo(){
		//echo $query=$this->tree_model->put_user_photo();
		$this->output->set_output($query=$this->tree_model->put_user_photo());
	}
	function route(){
		$this->load->model('live/device_model');
		$rows = $this->tree_model->get_landmark($this->session->userdata('user_id'));
		$landOpt = '';
		if(count($rows) > 0) {
			foreach ($rows as $row) {				
				$landOpt .= '<option title="'.base_url().$row->icon_path.'" value="'.$row->id.','.$row->lat.','.$row->lng.','.$row->icon_path.'">'.$row->name.'</option>';				
			}
		}
		else {
			$landOpt .= '<option title="" value="">No Landmark</option>';
		}
		$deviceOpt = "";
		$rows = $this->device_model->get_links();
		if(count($rows)) {
			
			foreach ($rows as $row) {				
				$deviceOpt .= "<option value='".$row->id."'>".$row->assets_name." (".$row->device_id.")</option>";				
			}
		}
		else {
			$deviceOpt .= "<option value=''>No Assets</option>";
		}
		$rows = $this->tree_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$coords[] = $row;
			}
		}
		$data['coords'] = $coords;
		$data['deviceOpt'] = $deviceOpt;
		$data['landOpt'] = $landOpt;
		$this->load->view('route', $data);
	}
	function loadRoute(){
		
		/*$rows = $this->tree_model->load_route($this->session->userdata('user_id'));
				
		$coords = array();
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		if(count($rows)) {
			foreach ($rows as $row) {				
				$landmarkArr[] = $row->landmark_ids;
				$coords[] = $row;
				$rowsL = $this->tree_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
			}
		}
		if(count($landmarkArr) > 0)
			$landmark_ids = implode(",", $landmarkArr);
			
		$landmarks = array();
		if($landmark_ids != ""){
			$rows = $this->tree_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}*/
		$rows = $this->tree_model->load_route($this->session->userdata('user_id'));
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		$finalCoords = array();
		if(count($rows)) {
			foreach ($rows as $row) {	
				$landmarkArr[] = $row->landmark_ids;
				
				$rowsSub = $this->tree_model->load_sub_route($row->id);
				foreach ($rowsSub as $rowS) {
					$coords[] = $rowS;
					/*$rowsL = $this->tree_model->get_route_landmark($rowS->landmark_ids);
					if(count($rowsL) > 0) {
						foreach ($rowsL as $rowL) {
							$landmarksRoute[$rowS->id][] = $rowL;
						}
					}*/
				}
				$finalCoords[] = $coords;
			
			}
		}
		if(count($landmarkArr) > 0)
			$landmark_ids = implode(",", $landmarkArr);
			
		$landmarks = array();
		if($landmark_ids != ""){
			$rows = $this->tree_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}
		
		$data['landmarks'] = $landmarks;
		$data['landmarksRoute'] = $landmarksRoute;
		$data['coords'] = $finalCoords;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function loadRouteLive(){
		
		if($this->session->userdata('usertype_id') > 2){
			$username = $this->session->userdata('username');
			$row = $this->tree_model->get_Landmark_from_comment($username);
			/*$my_landmark=0;
			$my_lat=0;
			$my_lng=0;*/
			$my_landmark=-1;
			$my_lat=-1;
			$my_lng=-1;
			if(count($row)==1){
				$my_landmark = $row[0]->id;
				$my_lat = $row[0]->lat;
				$my_lng = $row[0]->lng;
			}
		}
		
		if($this->session->userdata('usertype_id') > 2){
			$row = $this->tree_model->get_completed_trip_landmark();
			$trip_last_landmark = $row->landmark_ids;
			$trip_last_landmark = explode(",", $trip_last_landmark);
			$trip_last_landmark = $trip_last_landmark[0];
		}
		/*$rows = $this->tree_model->load_route_live($this->session->userdata('user_id'));
				
		$coords = array();
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		if(count($rows)) {
			foreach ($rows as $row) {				
				
				if($this->session->userdata('usertype_id') > 2){
					$landmark_ids = $row->landmark_ids;
					$landmark_ids = explode($my_landmark, $landmark_ids);
					$landmark_ids = $landmark_ids[0].$my_landmark;
					$row->landmark_ids = $landmark_ids;
					$row->end_point = $my_lat.",".$my_lng;
				}
				$landmarkArr[] = $row->landmark_ids;
				$coords[] = $row;
				$rowsL = $this->tree_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
			}
		}
		if(count($landmarkArr) > 0)
			$landmark_ids = implode(",", $landmarkArr);
			
		$landmarks = array();
		if($landmark_ids != ""){
			$rows = $this->tree_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}
		$data['landmarks'] = $landmarks;
		$data['landmarksRoute'] = $landmarksRoute;
		$data['coords'] = $coords;
		*/
		
		$rows = $this->tree_model->load_route_live($this->session->userdata('user_id'));
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		$coords = array();
		if(count($rows)) {
			foreach ($rows as $row) {				
				$route_id = $row->id;
				if($this->session->userdata('usertype_id') > 2){
					$landmark_ids = $row->landmark_ids;
					
					$first_ids = explode(",",$landmark_ids);
					$first_id = $first_ids[0];
					
					$remaining_ids = explode($trip_last_landmark,$landmark_ids, 2);
					//$remaining_ids = explode('462','462,463', 2);
					$landmark_ids = $first_id.$remaining_ids[1];
					
					//
					$landmark_ids = explode($my_landmark, $landmark_ids);
					
					$landmark_ids = $landmark_ids[0].$my_landmark;
					
					$row->landmark_ids = $landmark_ids;
					//$row->end_point = $my_lat.",".$my_lng;
				}else{
					$my_landmark = '';
				}
				$landmarkArr[] = $row->landmark_ids;
				
				$rowsL = $this->tree_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
				$rowsSub = $this->tree_model->load_sub_route_live($route_id, $my_landmark);
				
				if(count($rowsSub)) {
					foreach ($rowsSub as $rowS) {
						$coords[] = $rowS;
					}
				}
			}
		}
		if(count($landmarkArr) > 0)
			$landmark_ids = implode(",", $landmarkArr);
			
		$landmarks = array();
		if($landmark_ids != ""){
			$rows = $this->tree_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}
				
		$data['landmarks'] = $landmarks;
		$data['landmarksRoute'] = $landmarksRoute;
		$data['coords'] = $coords;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function loadRouteMap(){
		
		if($this->session->userdata('usertype_id') > 2){
			$username = $this->session->userdata('username');
			$row = $this->tree_model->get_Landmark_from_comment($username);
			$my_landmark = $row->id;
			$my_lat = $row->lat;
			$my_lng = $row->lng;
		}
		
		$rows = $this->tree_model->load_route_map();
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		$coords = array();
		$finalCoords = array();
		if(count($rows)) {
			foreach ($rows as $row) {				
				$route_id = $row->id;
				if($this->session->userdata('usertype_id') > 2){
					
					
					$rowT = $this->tree_model->get_completed_trip_landmark_map($row->id);
					$trip_last_landmark = $rowT->landmark_ids;
					$trip_last_landmark = explode(",", $trip_last_landmark);
					$trip_last_landmark = $trip_last_landmark[0];
				
					$landmark_ids = $row->landmark_ids;
					//
					$first_ids = explode(",",$landmark_ids);
					$first_id = $first_ids[0];
					
					$remaining_ids = explode($trip_last_landmark,$landmark_ids, 2);
					$landmark_ids = $first_id.$remaining_ids[1];
					
					//
					$landmark_ids = explode($my_landmark, $landmark_ids);
					
					$landmark_ids = $landmark_ids[0].$my_landmark;
					
					$row->landmark_ids = $landmark_ids;
					//$row->end_point = $my_lat.",".$my_lng;
				}else{
					$my_landmark = '';
				}
				$landmarkArr[] = $row->landmark_ids;
				
				$rowsL = $this->tree_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
				$rowsSub = $this->tree_model->load_sub_route_live($route_id, $my_landmark);
				
				if(count($rowsSub)) {
					foreach ($rowsSub as $rowS) {
						$coords[] = $rowS;
					}
					$finalCoords[] = $coords;
				}
			}
		}
		if(count($landmarkArr) > 0)
			$landmark_ids = implode(",", $landmarkArr);
			
		$landmarks = array();
		if($landmark_ids != ""){
			$rows = $this->tree_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}
				
		$data['landmarks'] = $landmarks;
		$data['landmarksRoute'] = $landmarksRoute;
		$data['coords'] = $finalCoords;
		
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function loadRouteList(){
		
		$rows = $this->tree_model->load_route_list($this->session->userdata('user_id'));
				
		$coords = array();
		$html = '<table id="route_det">';
		
		if(count($rows)) {
			foreach ($rows as $row) {	
		
				$html .= '<tr style="color:'.$row->route_color.'"><td><a style="cursor:pointer;" onclick="toggleDetails('.$row->id.')"><img id="img_'.$row->id.'" src="'.base_url().'assets/style/css/images/add.png"></a></td><td><input type="checkbox" value="'.$row->id.'"></td><td align="left">'.$row->routename.'</td><td><a style="cursor:pointer" onclick="editRoute('.$row->id.')">(Edit)</a></td></tr>';
				$html .= '<tr id="route_det_'.$row->id.'" style="display:none;">';
				$html .= '<td></td>';
				$html .= '<td colspan="3" style="line-height:20px;">';
				/*$html .= '<b> "'.$this->lang->line("Distance_Unit") .'" </b>'.$row->distance_unit.'<br><b> "'.$this->lang->line("Alert Distance Value").'" </b>'.$row->distance_value.' KM<br>';
				$seconds = $row->total_time_in_minutes * 60;
				$hours = floor($seconds / (60 * 60));
				$divisor_for_minutes = $seconds % (60 * 60);
				$minutes = floor($divisor_for_minutes / 60);
			
				$approxTime = $hours." Hour, ".$minutes." Min";
				$html .= '<b>Total Distance : </b>'.$row->total_distance.' '.$row->distance_unit.'<br><b>Time : </b>'.$approxTime.'<br>';
				*/
				$rowL = $this->tree_model->route_path($row->landmark);
				
				$lArr = explode(",",$rowL);
				
				$lString = str_replace(":", "-To-",$rowL);
				if($row->round_trip == 1 && count($lArr))
					$lString .= "-To-".$lArr[0];
				$html .= '<b>Way</b> : '.$lString."<br>";
				$html .= '<b>Assets</b> : '.$row->assets."<br>";
				$html .= '<b>Driver</b> : '.$row->driver_name."<br>";
				$html .= '<b>Created On</b> : '.date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date));
				$html .= '</td>';
				$html .= '</tr>';
		
			}
		}
		$html .= '</table>';
		//die($html);
		$this->output->set_output($html);
	}
	function geofence(){
		
		$this->load->model('live/device_model');
		$this->load->model('assets/asset_model');
		$area_id=uri_assoc('area_id');
		
		//echo $area_id;
		//die();
		// $active_device = $this->input->get("active");
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$deviceOpt = "";
		/*$rows = $this->tree_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$coords[] = $row;
			}
		}
		$data['coords'] = $coords;
		*/
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['sidebar'] = $this->gmap->printSidebar();
		
		$rows = $this->device_model->get_links();
		if(count($rows)) {
			
			foreach ($rows as $row) {				
				$deviceOpt .= "<option value='".$row->id."'>".$row->assets_name." (".$row->device_id.")</option>";				
			}
		}
		else {
			$deviceOpt .= "<option value=''>No Assets</option>";
		}
		
		$data['deviceOpt'] = $deviceOpt;
				
		$plyRows = $this->device_model->get_poly_home();
		
		$data['plyId'] = array();
		$data['plyDev'] = array();
		$data['plyLat'] = array();
		$data['plyLng'] = array();
		$data['plyName'] = array();
		$data['plyColor'] = array();
		foreach ($plyRows as $row) {
			
			$data['plyId'][] = $row->polyid;
			$data['plyLat'][$row->polyid][] = $row->lat;
			$data['plyLng'][$row->polyid][] = $row->lng;
			$data['plyName'][$row->polyid][] = $row->polyname;
			$data['plyDev'][$row->polyid][] = $row->assets;
			$data['plyColor'][$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}
		
		if(count($data['plyId']) > 0){
			$data['plyId'] = array_unique($data['plyId']);
			foreach($data['plyId'] as $pid){
				if(count($data['plyDev'][$pid]) > 0){
					$data['plyDev'][$pid] = array_unique($data['plyDev'][$pid]);
				}
			}
		}
		$rows = $this->asset_model->prepare_icon();
		$iconOpt = '';
		foreach ($rows as $row) {
			$iconOpt .= '<option title="'.base_url().'/assets/marker-images/'.$row->icon_path.'" value="'.$row->id.'">'.$row->icon_name.'</option>';
		}
		$rows = $this->tree_model->addressbook_opt();
		$addressbookOpt = '';
		foreach ($rows as $row) {
			$addressbookOpt .= '<option value="'.$row->id.'">'.$row->name.'</option>';
		}
		$rows = $this->tree_model->addressbook_group_opt();
		$addressbookGroupOpt = '';
		foreach ($rows as $row) {
			$addressbookGroupOpt .= '<option value="'.$row->id.'">'.$row->group_name.'</option>';
		}
		$rows = $this->tree_model->getAllCoord($this->session->userdata('user_id'));
		$opt_latlng="";
		if(count($rows) > 0) {
		$i=0;
			foreach ($rows as $row) {
				if($row['lati']!='' || $row['lati']!=null){
					$opt_latlng.="<option value='".$row['lati'].",".$row['longi']."'>".$row['assets_name']."</option>";
				}
				else{
					$opt_latlng.="<option value='0,0'>".$row['assets_name']."</option>";
				}
			}
		}
		$show_zone_name = $this->tree_model->getZoneNameSetting();
		
		$data['show_zone_name'] = $show_zone_name;
		
		$data['live_combo'] = $opt_latlng;
		$data['area_id'] = $area_id;
		$data['iconOpt'] = $iconOpt;
		$data['addressbookOpt'] = $addressbookOpt;
		$data['addressbookGroupOpt'] = $addressbookGroupOpt;
		$this->load->view('geofence',$data);
	}
	
	function landmark(){
		
		$this->load->model('live/device_model');
		$this->load->model('assets/asset_model');
		$this->load->model('landmarks/landmarks_model');
		$landmark_id=uri_assoc('landmark_id');
		// $active_device = $this->input->get("active");
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$deviceOpt = "";
		$rows = $this->tree_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$row->name=str_replace(array("\n",'\n\r'), " ", addslashes($row->name));
				$row->address=str_replace(array("\n",'\n\r'), " ", addslashes($row->address));
				$row->comments=str_replace(array("\n",'\n\r'), " ", addslashes($row->comments));
				$coords[] = $row;
			}
		}
		$data['coords'] = $coords;
		
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['sidebar'] = $this->gmap->printSidebar();
		
		$rows = $this->device_model->get_links();
		
		if(count($rows)) {
			
			foreach ($rows as $row) {				
				$deviceOpt .= "<option value='".$row->id."'>".addslashes($row->assets_name)." (".$row->device_id.")</option>";				
			}
		}
		else {
			$deviceOpt .= "<option value=''>No Assets</option>";
		}
		
		$data['deviceOpt'] = $deviceOpt;
				
		$rows = $this->asset_model->prepare_icon();
		$iconOpt = '';
		foreach ($rows as $row) {
			$iconOpt .= '<option title="'.base_url().'/assets/marker-images/'.$row->icon_path.'" value="'.$row->id.'">'.$row->icon_name.'</option>';
		}
		$data['iconOpt'] = $iconOpt;
		
		$rows = $this->landmarks_model->getIconPaths();
		$images = '';
		foreach ($rows as $row) {
			$images .= '<option title="'.base_url().'/'.$row->image_path.'" value="'.$row->image_path.'"></option>';
		}
		$data['images'] = $images;
		
		$rows = $this->tree_model->addressbook_opt();
		$addressbookOpt = '';
		foreach ($rows as $row) {
			$addressbookOpt .= '<option value="'.$row->id.'">'.$row->name.'</option>';
		}
		$rows = $this->tree_model->addressbook_group_opt();
		$addressbookGroupOpt = '';
		foreach ($rows as $row) {
			$addressbookGroupOpt .= '<option value="'.$row->id.'">'.$row->group_name.'</option>';
		}
		
		$rows = $this->tree_model->getLandmarkGroups();
		$LandmarkGroupOpt = '';
		$LandmarkGroupOpt = "<select class=\"select ui-widget-content ui-corner-all\" id=\"landmark_group_nm_".time()."\"><option value=\"\">Select Group Name</option>";
		foreach ($rows as $row) {
			$LandmarkGroupOpt .= '<option value="'.$row->id.'">'.addslashes($row->landmark_group_name).'</option>';
		}
		
		$LandmarkGroupOpt .= "</select>";

		$rows = $this->tree_model->getAllCoord($this->session->userdata('user_id'));
		
		$opt_latlng="";
		if(count($rows) > 0) {
		$i=0;
			foreach ($rows as $row) {
				if($row['lati']!='' || $row['lati']!=null){
					$opt_latlng.="<option value='".$row['lati'].",".$row['longi']."'>".addslashes($row['assets_name'])."</option>";
				}
				else{
					$opt_latlng.="<option value='0,0'>".addslashes($row['assets_name'])."</option>";
				}
			}
		}
		$data['live_combo'] = $opt_latlng;
		$data['addressbookOpt'] = $addressbookOpt;
		$data['landmark_id'] = $landmark_id;
		$data['addressbookGroupOpt'] = $addressbookGroupOpt;
		$data['LandmarkGroupOpt'] = $LandmarkGroupOpt;
		
		$this->load->view('landmark',$data);
	}
	function refreshLandmark(){
		
		
		$rows = $this->tree_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$row->name=str_replace(array("\n",'\n\r'), " ", addslashes($row->name));
				$row->address=str_replace(array("\n",'\n\r'), " ", addslashes($row->address));
				$row->comments=str_replace(array("\n",'\n\r'), " ", addslashes($row->comments));
				$coords[] = $row;
			}
		}
		$data['coords'] = $coords;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function refreshArea(){
		
		$this->load->model('live/device_model');
		
		$plyRows = $this->device_model->get_poly_home();
		
		$data = array();
		$plyId = array();
		$plyDev = array();
		$plyLat = array();
		$plyLng = array();
		$plyName = array();
		$plyColor = array();
		foreach ($plyRows as $row) {
			if(!in_array($row->polyid, $plyId))
				$plyId[] = $row->polyid;
			$plyLat[$row->polyid][] = sprintf("%.6f", $row->lat);
			$plyLng[$row->polyid][] = sprintf("%.6f", $row->lng);
			$plyName[$row->polyid][] = $row->polyname;
			$plyDev[$row->polyid][] = $row->assets;
			$plyColor[$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}
		
		$data['plyId'] = $plyId;
		$data['plyDev'] = $plyDev;
		$data['plyLat'] = $plyLat;
		$data['plyLng'] = $plyLng;
		$data['plyName'] = $plyName;
		$data['plyColor'] = $plyColor;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function edit_route(){
		
		$row = $this->tree_model->edit_route();
		$data['data'] = $row;	
		
		$rows = $this->tree_model->load_route_edit();
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		if(count($rows)) {
			foreach ($rows as $row) {	
				$landmarkArr[] = $row->landmark_ids;
			}
		}
		if(count($landmarkArr) > 0)
			$landmark_ids = implode(",", $landmarkArr);
			
		$landmarks = array();
		if($landmark_ids != ""){
			$rows = $this->tree_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}
		$rows = $this->tree_model->load_sub_route_edit();
				
		$coords = array();
		if(count($rows)) {
			foreach ($rows as $row) {
				$coords[] = $row;
				$rowsL = $this->tree_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
			}
		}
		
		$data['landmarks'] = $landmarks;
		$data['landmarksRoute'] = $landmarksRoute;
		$data['coords'] = $coords;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function edit_landmark(){
		
		$row = $this->tree_model->edit_landmark();
		$data['data'] = $row;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function edit_area(){
		
		$row = $this->tree_model->edit_area();
		$data['data'] = $row;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function updateArea(){
		$res = $this->tree_model->updateArea();
		$responce['result'] = $res['result'];
		$responce['id'] = $res['insert_id'];
		$responce['msg'] = $res['msg'];
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function removeLandmark(){
		
		$this->tree_model->removeLandmark();
		exit;
	}
	function assets()
	{
		$coords = array();
		
		$res = $this->tree_model->last_location($this->session->userdata('user_id'));
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				
				/*
				$distance = 0;
				
				$pts = $this->tree_model->get_todays_points($row->device_id);
				$lat1 = '';
				$lng1 = '';
				foreach ($pts as $pt) {
					$lat2 = $pt->lati;
					$lng2 = $pt->longi;
					if($lat1 && $lng1){
						$dist = 0;
						$theta = $lng1 - $lng2;  
						 $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));  
						 $dist = acos($dist);  
						 $dist = rad2deg($dist);  
						 $miles = $dist * 60 * 1.1515;  
						 $unit = "K";  
						 if ($unit == "K") {  
							 
							 $dstn = round(($miles * 1.609344), 2);
							 if(!is_nan($dstn)){
								 
								$distance += $dstn;  
								 
							 }
						 }  
						 else if ($unit == "N") {  
							  $distance += ($miles * 0.8684);  
						 }  
						 else {  
							 $distance += $miles;  
						 }
					}
					$lat1 	= $lat2;
					$lng1 	= $lng2;					
				}
				$row->distance = intval($distance);*/
				$row->received_time = ago($row->add_date) . ' ago';
				$coords[] = $row;
			}
			
		}
		$rows = $this->tree_model->getAssetsStatus($this->session->userdata('user_id'));
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
		//die(print_r($rows));
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}
		//$this->form_model->icon_id = $iconOpt;
		$data['running_1'] = $running;
		$data['parked_1'] = $parked;
		$data['out_of_network_1'] = $out_of_network;
		$data['device_fault_1'] = $device_fault;
		$data['total_1'] = $total;
		$data['coords'] = $coords;
		$data['totalPage'] = $totalPage;
		$data['page'] = $page;
		$data['totalRecords'] = $totalRecords;
		$data['limit'] = $limit;
		$this->load->view('assets',$data);
		
	}
	function assets_list()
	{
		$coords = array();
		$stopArr = array();
		
		$rows = $this->tree_model->stop_duration($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				if($row->stop_from != ''){
					$minutes = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($row->stop_from)) / 60;

					// $minutes = $row->stop_from;

					$d = floor ($minutes / 1440);
					$h = floor (($minutes - $d * 1440) / 60);
					$m = $minutes - ($d * 1440) - ($h * 60);
					$stop_time = '';
					if($d > 0)
						$stop_time .= $d." Day ";
					if($h > 0)
						$stop_time .= $h." Hour ";
					if($m > 0)
						$stop_time .= intval($m)." Min";

					$stopArr[$row->device_id] = $row->stop_from;
				}
			}
		}
		
		$res = $this->tree_model->last_location($this->session->userdata('user_id'));
		
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				if (array_key_exists($row->assets_id, $stopArr)) {
					$row->stop_from = $stopArr[$row->assets_id];
				}
				
				//$row->received_time = ago($row->add_date) . ' ago';
				$row->received_time = ago($row->add_date)." ".$this->lang->line('ago');
				$row->received_time = str_replace("weeks",$this->lang->line('weeks'),$row->received_time);
				$row->received_time = str_replace("week",$this->lang->line('week'),$row->received_time);
				$row->received_time = str_replace("months",$this->lang->line('months'),$row->received_time);
				$row->received_time = str_replace("month",$this->lang->line('month'),$row->received_time);
				$row->received_time = str_replace("years",$this->lang->line('years'),$row->received_time);
				$row->received_time = str_replace("year",$this->lang->line('year'),$row->received_time);
				$row->received_time = str_replace("days",$this->lang->line('days'),$row->received_time);
				$row->received_time = str_replace("day",$this->lang->line('day'),$row->received_time);
				$row->received_time = str_replace("hours",$this->lang->line('hours'),$row->received_time);
				$row->received_time = str_replace("hour",$this->lang->line('hour'),$row->received_time);
				$row->received_time = str_replace("minutes",$this->lang->line('minutes'),$row->received_time);
				$row->received_time = str_replace("minute",$this->lang->line('minute'),$row->received_time);
				$row->received_time = str_replace("seconds",$this->lang->line('seconds'),$row->received_time);
				$type=str_replace("weeks",'wk',$row->received_time);
				//echo $row->received_time."<br/>"."";
				$coords[] = $row;
			}
		}
	
		$reports = $_POST['report'];
		
		foreach($reports as $report) {
			$rptsub = substr($report, 0, 2);
			
			if($rptsub == "g-"){
				$group = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "u-"){
				$user = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "a-"){
				$us_ar = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "l-"){
				$us_ln = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "o-"){
				$us_ow = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "d-"){
				$us_dv = str_replace($rptsub, "", $report);
			}
		}
		
		if($user == '') {
			$user = $this->session->userdata('user_id');
		}
		
		if($us_ar != ""){
			$this->db->select("polyname", FALSE);
			$this->db->where('polyid', $us_ar);
			$this->db->limit(1);
			$query = $this->db->get('areas');			
			$rows = $query->result();
			$us_area = '';
			foreach ($rows as $key => $row) {
				$us_area = $row->polyname;
			}

			if($us_area!="")
				$gsub .= " AND lm.current_area = '".addslashes($us_area)."'";
		}
		
		if($us_ln != ""){
			$this->db->select("name", FALSE);
			$this->db->where('id', $us_ln);
			$this->db->limit(1);
			$query = $this->db->get('landmark');			
			$rows = $query->result();
			$us_land = '';
			foreach ($rows as $row) {
				$us_land = $row->name;
			}
			if($us_land!="")
				$gsub .= " AND lm.current_landmark = '".addslashes($us_land)."'";
		}
		
		if($group != ""){
			$gsub .= " AND am.assets_group_id = $group";
			/*
			$this->db->select("assets", FALSE);
			$this->db->where('id', $group);
			$this->db->limit(1);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			
			if($assets!="")
				$gsub .= " AND am.id in($assets)";
			else
				$gsub .= " AND am.id in(-1)";
			*/
		}

		if(trim($us_ow) != '') {
			$gsub .= " AND am.assets_owner = '".mysql_real_escape_string($us_ow)."'";
		}
		
		if(trim($us_dv) != '') {
			$gsub .= " AND am.assets_division = '".mysql_real_escape_string($us_dv)."'";
		}
		
		$rows = $this->tree_model->getAssetsStatus($user, $gsub);
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
		//die(print_r($rows));
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}
		//$this->form_model->icon_id = $iconOpt;
		$data['running_1'] = $running;
		$data['parked_1'] = $parked;
		$data['out_of_network_1'] = $out_of_network;
		$data['device_fault_1'] = $device_fault;
		$data['total_1'] = $total;
		$data['coords'] = $coords;
		$data['totalPage'] = $totalPage;
		$data['page'] = $page;
		$data['totalRecords'] = $totalRecords;
		$data['limit'] = $limit;
		
		$row = $this->tree_model->getAutoRefreshSettings();
		$data['auto_refresh_setting'] = $row->auto_refresh_setting;
		
		$this->load->view('assets_list',$data);
		
	}
	
	function tree_list()
	{
		$coords = array();
		$stopArr = array();
		$assets = $_POST['assets'];
		// $selected = $_POST['sel'];

		/*
		if($selected != '') {
			$selected = explode(',',$selected);
		}
		else {
			$selected = $assets;
		}
		*/
		
		$assets = implode(",", $assets);
		$rows = array();
		
		if($assets != '') {
			$res = $this->tree_model->treeLastLocation($assets);
		}
		
		$rows = $this->tree_model->stop_duration($assets);
		
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				if($row->stop_from != ''){
					$minutes = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($row->stop_from)) / 60;

					// $minutes = $row->stop_from;

					$d = floor ($minutes / 1440);
					$h = floor (($minutes - $d * 1440) / 60);
					$m = $minutes - ($d * 1440) - ($h * 60);
					$stop_time = '';
					if($d > 0)
						$stop_time .= $d." Day ";
					if($h > 0)
						$stop_time .= $h." Hour ";
					if($m > 0)
						$stop_time .= intval($m)." Min";

					$stopArr[$row->device_id] = $stop_time;
				}
			}
		}			
	
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];

		if(count($rows) > 0) {
			foreach ($rows as $row) {
				if (array_key_exists($row->assets_id, $stopArr)) {
					$row->stop_from = $stopArr[$row->assets_id];
				}
				
				//$row->received_time = ago($row->add_date) . ' ago';
				$row->received_time = ago($row->add_date)." ".$this->lang->line('ago');
				$row->received_time = str_replace("weeks",$this->lang->line('weeks'),$row->received_time);
				$row->received_time = str_replace("week",$this->lang->line('week'),$row->received_time);
				$row->received_time = str_replace("months",$this->lang->line('months'),$row->received_time);
				$row->received_time = str_replace("month",$this->lang->line('month'),$row->received_time);
				$row->received_time = str_replace("years",$this->lang->line('years'),$row->received_time);
				$row->received_time = str_replace("year",$this->lang->line('year'),$row->received_time);
				$row->received_time = str_replace("days",$this->lang->line('days'),$row->received_time);
				$row->received_time = str_replace("day",$this->lang->line('day'),$row->received_time);
				$row->received_time = str_replace("hours",$this->lang->line('hours'),$row->received_time);
				$row->received_time = str_replace("hour",$this->lang->line('hour'),$row->received_time);
				$row->received_time = str_replace("minutes",$this->lang->line('minutes'),$row->received_time);
				$row->received_time = str_replace("minute",$this->lang->line('minute'),$row->received_time);
				$row->received_time = str_replace("seconds",$this->lang->line('seconds'),$row->received_time);
				$type=str_replace("weeks",'wk',$row->received_time);
				//echo $row->received_time."<br/>"."";
				$coords[] = $row;
			}
		}
	
		if($assets != '') {
			$rows = $this->tree_model->treeAssetsStatus($assets);
		}
		
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
		//die(print_r($rows));
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}
		//$this->form_model->icon_id = $iconOpt;
		
		$data['running_1'] = $running;
		$data['parked_1'] = $parked;
		$data['out_of_network_1'] = $out_of_network;
		$data['device_fault_1'] = $device_fault;
		$data['total_1'] = $total;
		$data['coords'] = $coords;
		$data['totalPage'] = $totalPage;
		$data['page'] = $page;
		$data['totalRecords'] = $totalRecords;
		$data['limit'] = $limit;
		// $data['selected'] = $selected;

		$row = $this->tree_model->getAutoRefreshSettings();
		$data['auto_refresh_setting'] = $row->auto_refresh_setting;
		$this->load->view('tree_assets_list',$data);
	}

	
	function map()
	{
		$dist="";
		$dist=uri_assoc("cmd");
		$this->load->model('live/device_model');
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$coords = array();
		$deviceOpt = "";
		$rows = $this->tree_model->stop_duration($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				if($row->stop_from != ''){
					$minutes = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($row->stop_from)) / 60;

					// $minutes = $row->stop_from;

					$d = floor ($minutes / 1440);
					$h = floor (($minutes - $d * 1440) / 60);
					$m = $minutes - ($d * 1440) - ($h * 60);
					$stop_time = '';
					if($d > 0)
						$stop_time .= $d." Day ";
					if($h > 0)
						$stop_time .= $h." Hour ";
					if($m > 0)
						$stop_time .= intval($m)." Min";

					$stopArr[$row->device_id] = $stop_time;
				}
			}
		}			
		$rows = $this->tree_model->device_map($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				if (array_key_exists($row->assets_id, $stopArr)) {
					$row->stop_from = $stopArr[$row->assets_id];
				}
				$row->received_time = ago($row->add_date) . ' ago';
				$coords[] = $row;
			}
		}
		
		
		//die(print_r($coords));
		$data['coords'] = $coords;
		
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['ids'] = uri_assoc('id');
		$data['find_distance'] = 0;
		if(uri_assoc('d') == 1){
			$data['find_distance'] = 1;
		}
		
		
		$plyRows = $this->device_model->get_poly_home();
		
		$data['plyId'] = array();
		$data['plyDev'] = array();
		$data['plyLat'] = array();
		$data['plyLng'] = array();
		$data['plyName'] = array();
		$data['plyColor'] = array();
		foreach ($plyRows as $row) {
			
			$data['plyId'][] = $row->polyid;
			$data['plyLat'][$row->polyid][] = $row->lat;
			$data['plyLng'][$row->polyid][] = $row->lng;
			$data['plyName'][$row->polyid][] = $row->polyname;
			$data['plyDev'][$row->polyid][] = $row->assets;
			$data['plyColor'][$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}
		
		if(count($data['plyId']) > 0){
			$data['plyId'] = array_unique($data['plyId']);
			foreach($data['plyId'] as $pid){
				if(count($data['plyDev'][$pid]) > 0){
					$data['plyDev'][$pid] = array_unique($data['plyDev'][$pid]);
				}
			}
		}
		$rows = $this->tree_model->get_landmark($this->session->userdata('user_id'));
		$landmarks = array();
		if(count($rows) > 0){
			foreach ($rows as $row) {
				$landmarks[] = $row;
			}
		}
		$location_with_tag = $this->tree_model->tag_setting($this->session->userdata('user_id'));
		
		$data['location_with_tag']=$location_with_tag;

		$data['landmarks'] = $landmarks;
		$data['dist']=$dist;
		$this->load->view('map',$data);
	}
	function multi_map()
	{
		$dist="";
		$dist=uri_assoc("cmd");
		$this->load->model('live/device_model');
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$coords = array();
		$deviceOpt = "";
				
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['ids'] = uri_assoc('id');
		$data['find_distance'] = 0;
		$data['assets_ids'] = uri_assoc('id');
		$this->load->view('multi_map',$data);
	}
	function device_map_refresh()
	{
		
		$coords = array();
		$lat = array();
		$lng = array();
		$lat_n = array();
		$lng_n = array();
		$html = array();
		$tag = array();
		$direction = array();
		$statusArr = array();
		$speed = array();
		$title = array();
		$beforeTime = array();
		$icon_path="";
		$stopArr = array();
		$rows = $this->tree_model->stop_duration($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$minutes = $row->stop_from;
				
				$d = floor ($minutes / 1440);
				$h = floor (($minutes - $d * 1440) / 60);
				$m = $minutes - ($d * 1440) - ($h * 60);
				$stop_time = '';
				if($d > 0)
					$stop_time .= $d." Day ";
				if($h > 0)
					$stop_time .= $h." Hour ";
				if($m > 0)
					$stop_time .= intval($m)." Min";
				
				$stopArr[$row->device_id] = $stop_time;
			}
		}	
		$rows = $this->tree_model->device_map($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$stop_from = '';
				if (array_key_exists($row->assets_id, $stopArr)) {
					$stop_from = $stopArr[$row->assets_id];
				}

				$lat[] = $row->lati;
				$lng[] = $row->longi;
				$speed[] = $row->speed;
				
				$lat_n[] = $row->lat_n;
				$lng_n[] = $row->lng_n;
				
				
				if($row->assets_category_id == 1 || $row->assets_category_id == "" || $row->assets_category_id == 0){
					$image_type = "truck.png";
				}else if($row->assets_category_id == 2){
					$image_type = "car.png";
				}
				else if($row->assets_category_id == 3){
					$image_type = "bus.png";
				}
				else if($row->assets_category_id == 4){
					$image_type = "mobile.png";
				}
				else if($row->assets_category_id == 5){
					$image_type = "bike.png";
				}
				else if($row->assets_category_id == 6){
					$image_type = "altenator.png";
				}
				else if($row->assets_category_id == 7 || $row->assets_category_id == 8){
					$image_type = "man.png";
				}
				else if($row->assets_category_id == 9){
					$image_type = "stacker.png";
				}
				else if($row->assets_category_id == 10){
					$image_type = "loader.png";
				}
				else if($row->assets_category_id == 11){
					$image_type = "locomotive.png";
				}
				else if($row->assets_category_id == 12){
					$image_type = "generator.png";
				}
				else if($row->assets_category_id == 13){
					$image_type = "maintenance.png";
				}
				else if($row->assets_category_id == 14){
					$image_type = "motor.png";
				}
				else if($row->assets_category_id == 15){
					$image_type = "bobcat.png";
				}
				else if($row->assets_category_id == 16){
					$image_type = "tractor.png";
				}
				else if($row->assets_category_id == 17){
					$image_type = "car1.png";
				}
				else if($row->assets_category_id == 18){
					$image_type = "satellite.png";
				}
				else if($row->assets_category_id == 21){
					$image_type = "stacker.png";
				}
				else{
					$image_type = "truck.png";
				}

				$icon_path[] = $image_type;
				$minutes_before = ($row->beforeTime);
				$text = "<b>".$row->assets_name;
				
				if($row->assets_friendly_nm!="" || $row->assets_friendly_nm!=null){
					$text.=" (".$row->assets_friendly_nm.") ";
				}
				$text.=" (".$row->device_id.")";
				$text .= "</b><br>";
				
				$text .= ago($row->add_date)." ago, Dt-".date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'),strtotime($row->add_date))."<br>";
				
				if($row->assets_image_path!= NULL || $row->assets_image_path!="")
				{
					$text .= "<img src='".base_url()."assets/assets_photo/".$row->assets_image_path."' />";
				}
				
				if($row->driver_image!= NULL || $row->driver_image!="")
				{
					$text.="<img src='".base_url()."/assets/driver_photo/".$row->driver_image."' />";
				}
				
				if($row->ignition == 0)
					$ignition = "OFF";
				else 
					$ignition = "ON";
				$text .= "Ignition: ".$ignition." , Speed: ".$row->speed." KM <br>";
								
				if($row->address != "")
					$text .= " ".$row->address."<br>";
				
				$text .="Status: ";
				if($minutes_before < $this->session->userdata('network_timeout') && $row->speed > 0 && $minutes_before != ""){
						$status ="Running";
				}else if($minutes_before < $this->session->userdata('network_timeout') && $row->speed == 0 && $row->ignition == 0 && $minutes_before != ""){
						$status ="Parked";
				}else if($minutes_before < $this->session->userdata('network_timeout')  && $row->speed == 0 && $row->ignition == 1 && $minutes_before != ""){
						$status ="Idle";
				}else if($minutes_before >= $this->session->userdata('network_timeout') && $minutes_before <= ($this->session->userdata('network_timeout')+36000) && $minutes_before != ""){
						$status ="Out of network";
				}else if($minutes_before > ($this->session->userdata('network_timeout') + 36000) or $minutes_before ==""){
						$status ="Out of network";
				}
				$text .= $status."<br>";
				
				if($status == "Parked")
					$text .= "Parked From : ".$stop_from."<br>";
				
/*				if($row->routename != ""){
					$text .="Route : ".$row->routename."<br>";
					if($row->landmark_n != ""){						
						$text .="Next Landmark : ".$row->landmark_n."<br>";
					}
				}
*/
				$tg = '';
				if($row->driver_name != ""){
					//$tg .= $row->driver_name.", ";
				}
				$tg .= substr($row->assets_name, -4);
				//$tg .= $row->speed." KM";

				
				
				if($row->driver_name!="" || $row->driver_name!=null) 
					$text .="Driver Name: ".$row->driver_name."<br>"; 
				
				if($row->driver_mobile!="" || $row->driver_mobile!=null) 
					$text .="Driver Mob.:".$row->driver_mobile."<br>"; 
								
				//$text .="<a onClick='' style='color: blue; text-decoration: underline; cursor: pointer;'>View Dashboard</a><br>";

				$html[] = $text;
				$tag[] = $tg;
				$direction[] = $row->angle_dir;
				$statusArr[] = $status;
				$title[] = $row->assets_name;
				$beforeTime[] = intval(($row->beforeTime)/60);
			}
		}
		$data['lat_n'] = $lat_n;
		$data['lng_n'] = $lng_n;				
				
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		$data['tag'] = $tag;
		$data['direction'] = $direction;
		$data['status'] = $statusArr;
		$data['speed'] = $speed;
		$data['title'] = $title;
		$data['icon_path'] = $icon_path;
		$data['beforeTime'] = $beforeTime;

		$location_with_tag = $this->tree_model->tag_setting($this->session->userdata('user_id'));
		
		$data['location_with_tag']=$location_with_tag;

		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	
	function save_user()
	{
		//die(uri_assoc("cmd"));
		//die(PRINT_R($_REQUEST));
		if($_REQUEST['u_id']!="")
		{
			$res = $this->tree_model->editUser();	
		}
		else{
			if(uri_assoc("cmd") == "add")
				$res = $this->tree_model->addUser();	
			else
				$res = $this->tree_model->updateUser();	
		}
		$res['dash_cmb']="";
		if($res['result'] == 'true')
		{
		$combo_s="";
		$combo_s.="<option value=''>".$this->lang->line('all_assets')."</option>";
		$combo_s.="<option value='running'>".$this->lang->line('running')."</option>";
		$combo_s.="<option value='parked'>".$this->lang->line('parked')."</option>";
		$combo_s.="<option value='out_of_network'>".$this->lang->line('out_of_network')."</option>";
//		$combo_s.="<option value='device_fault'>".$this->lang->line('device_fault')."</option>";
		$rows = $this->tree_model->get_group($this->session->userdata('user_id'));
			if(count($rows)) {
				foreach ($rows as $row) {
					$combo_s .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
				}
			}
		
		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$combo_s .= "<option value='u-".$row->user_id."'>".$row->username." (".$row->first_name." ".$row->last_name.")</option>";
			}
		}
		$res['dash_cmb']=$combo_s;
		}
		$responce['dash_cmb'] = $res['dash_cmb'];
		$responce['result'] = $res['result'];
		$responce['id'] = $res['insert_id'];
		$responce['msg'] = $res['msg'];
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	
	function add_to_group()
	{
		
		$res = $this->tree_model->addToGroup();	
		$responce['result'] = $res['result'];
		$responce['id'] = $res['insert_id'];
		$responce['msg'] = $res['msg'];
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	
	//assets dashboard
	function assets_dash()
	{		
		$this->load->library('GMap');
		$this->gmap->GoogleMapAPI();
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['onload'] = $this->gmap->printOnLoad();
		
		$row = $this->tree_model->current_location();	
		if($row->num_rows())
		{
		$row=$row->row();
		$text = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
		$text .= '('.ago($row->add_date) . ' ago)<br>';
		$text .= $row->speed." KM<br>";
		$text .= $row->assets_name.' ('.$row->device_id.')<br>';
		if($row->address != ""){
			$text .= $row->address."<br><br>";
		}
		
		$data['lat'] = $row->lati;
		$data['lng'] = $row->longi;
		$data['html'] = $text;
		$data['speed'] = $row->speed;
		$data['address'] = $row->address;
		$data['date'] = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date));
		$data['id'] = uri_assoc('id');
		//$this->load->view('assets_dash', $data);
		$this->load->view('dash', $data);
		}
		else
		{
			//echo $this->lang->line('No Data Found.!!!');
			$this->output->set_output($this->lang->line('No Data Found.!!!'));
		}
	}
	function get_assets_nm()
	{		
		$row = $this->tree_model->get_name(uri_assoc("id"));	
		//echo $row['assets_name'];
		$this->output->set_output($row['assets_name']);
	}
	function templates()
	{
		$this->load->view('templates');
	}
	function mywidgets()
	{
		$data['id'] = uri_assoc('id');
		$this->load->view('mywidgets', $data);
	}
	function widget1()
	{
		$this->load->view('widget1');
	}
	function assets_det()
	{		
		$row = $this->tree_model->assets_det();	
		
		$result = "<table align='center' width='90%' class='assets_det_tbl'>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Asset Name')."</td><td align='left'>".$row->assets_name."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Device')."</td><td align='left'>".$row->device_id."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Sim Number')."</td><td align='left'>".$row->sim_number."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('assets_division')."</td><td align='left'>".$row->division."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('assets_owner')."</td><td align='left'>".$row->owner."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Battery Status')."</td><td align='left'>".$row->battery_status."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('alarm_type')."</td><td align='left'>".$row->alarm_type."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('running_time')."</td><td align='left'>".$row->runtime."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('message_cause')."</td><td align='left'>".$row->data_type."</td></tr>";
		//$result .= "<tr><td valign='top'>Icon</td><td align='left' style='float:left;'><img src='".base_url()."assets/marker-images/".$row->icon_path."' border='0'></td></tr>";
		$result .= "</table>";
		//echo $result;
		$this->output->set_output($result);
	}
	function get_distance()
	{		
		$row = $this->tree_model->distance_today();
		
		if(count($row) > 0)
			$dis = $row->distance;
		else
			$dis = 0;
		
		$result = "<div style='width:100%;height:80px;text-align:center;padding-top:40px;font-size:4em;'>".$dis." KM</div>";
		//echo $result;
		$this->output->set_output($result);
	}
	function get_speed()
	{		
		$row = $this->tree_model->current_speed();	
		//echo $row->speed;
		$this->output->set_output($row->speed);
	}
	function assets_location(){
		$rowss = $this->tree_model->current_location();
		$rows = $rowss->result();
		$row = $rows[0];
		$text = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
		$text .= '('.ago($row->add_date) . ' ago)<br>';
		$text .= $row->speed." KM<br>";
		$text .= $row->assets_name.' ('.$row->device_id.')<br>';
		if($row->address != ""){
			$text .= $row->address."<br><br>";
		}
		
		$data['lat'] = $row->lati;
		$data['lng'] = $row->longi;
		$data['html'] = $text;
		$data['speed'] = $row->speed;
		$data['address'] = $row->address;
		$data['date'] = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date));
		
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();
		$time = strtotime($row->add_date);
		$difference     = $now - $time;
		$tense         = "ago";

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		   $difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
		   $periods[$j].= "s";
		}

		$data['before'] = ago($row->add_date) . ' ago';	//"$difference $periods[$j]";
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function addLandmark(){
		$res = $this->tree_model->addLandmark();
		$responce['result'] = $res['result'];
		$responce['id'] = $res['insert_id'];
		$responce['msg'] = $res['msg'];
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function speedometer_widget(){
		$data['id'] = uri_assoc('id');
		$this->load->view('speedometer', $data);
	}
	function map_widget(){
		$data['id'] = uri_assoc('id');
		$this->load->view('map_widget', $data);
	}
	function speedgraph_widget(){
		$data['id'] = uri_assoc('id');
		$this->load->view('speedgraph', $data);
	}
	function distancegraph_widget(){
		$data['id'] = uri_assoc('id');
		$this->load->view('distancegraph', $data);
	}
	function stop_report(){
		$data['id'] = uri_assoc('id');
		$this->load->view('stop_report', $data);
	}
	function area_in_out_report(){
		$data['id'] = uri_assoc('id');
		$this->load->view('area_in_out_report', $data);
	}
	function landmark_report(){
		$data['id'] = uri_assoc('id');
		$this->load->view('landmark_report', $data);
	}
	function distance_wise_report(){
		$data['id'] = uri_assoc('id');
		$this->load->view('distance_wise_report', $data);
	}
	
	function get_stop_report(){
		$this->output->set_output($this->tree_model->get_stop_report());
	}
	
	function get_area_in_out(){
		$this->output->set_output($this->tree_model->get_area_in_out());
	}
	
	function get_landmark_report(){
		$this->output->set_output($this->tree_model->get_landmark_report());
	}
	function get_distance_wise(){
		$this->output->set_output($this->tree_model->get_distance_wise());
	}
	
	function get_city(){
		$data['id'] = uri_assoc('id');
		//echo $this->tree_model->get_city($data);
		$this->output->set_output($this->tree_model->get_city($data));
	}
	function get_state(){
		$data['id'] = uri_assoc('id');
		//echo $this->tree_model->get_state($data);
		$this->output->set_output($this->tree_model->get_state($data));
	}
	function get_all_country(){
		//echo $this->tree_model->get_all_country();
		$this->output->set_output($this->tree_model->get_all_country());
	}
	function popup_request(){
		$data['header'] =$_POST['header'];
		$data['string'] =$_POST['data'];
		$data['link'] =$_POST['link'];
		$data['type'] =$_POST['type'];
		//echo $this->tree_model->popup_request($data);
		$this->output->set_output($this->tree_model->popup_request($data));
	}
	function deleteRoute(){
		$res = $this->tree_model->delete_route();
		//die('Record Deleted Successfully');
		$this->output->set_output('Record Deleted Successfully');
	}
	function setLanguage(){
		$lang = $_POST['lang'];
		$array_items = array('language' => $lang);
		$res = $this->tree_model->set_language($array_items);
		$this->session->set_userdata($array_items);
		//die("done");
		$this->output->set_output("done");
	}
	function get_usrs()
	{	
		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		$subUserOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$subUserOpt .= "<option value='u-".$row->user_id."'>".$row->username." (".$row->first_name." ".$row->last_name.")</option>";
			}
		}
		
		$data['result']= "done";
		$data['user_combo']= $subUserOpt;
		//$data['assets_combo']= $deviceOpt;
		//echo json_encode($data);
		$this->output->set_output(json_encode($data));
	}
	function get_grps()
	{	
		$rows = $this->tree_model->get_group($this->session->userdata('user_id'));
		$groupOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$groupOpt .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
			}
		}
		
		$data['result']= "done";
		$data['user_combo']= $groupOpt;
		//$data['assets_combo']= $deviceOpt;
		//echo json_encode($data);
		$this->output->set_output(json_encode($data));
	}
	function get_assets_selecteds_grp()
	{
		$grp_id=uri_assoc("grp_id");
		$assets_ids=uri_assoc("assets_ids");
		$rows = $this->tree_model->get_group_assets_detail($grp_id);
		$assets_old_ids=explode(",",$rows[0]['assets']);
		$assets_id_arr=explode(",",$assets_ids);
		$tot = array_unique(array_merge($assets_id_arr,$assets_old_ids));
		$deviceOpt = "";
		$rows = $this->tree_model->get_devices($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$deviceOpt .= "<option value='".$row->id."' ";
				if(in_array($row->id,$tot))
							$deviceOpt .=" selected='selected'";
							
				$deviceOpt .= ">".$row->assets_name;
				if($row->assets_friendly_nm!="")
					$deviceOpt.= " (".$row->assets_friendly_nm.")";
				$deviceOpt.="</option>";
			}
		}
		//echo $deviceOpt;
		$this->output->set_output($deviceOpt);
	}
	function get_assets_selecteds()
	{
		
		$usr_id=uri_assoc("usr_id");
		$assets_ids=uri_assoc("assets_ids");
		$rows = $this->tree_model->get_subuser_assets_detail($usr_id);
		$assets_old_ids=explode(",",$rows[0]['assets_ids']);
		$assets_id_arr=explode(",",$assets_ids);
		$tot = array_unique(array_merge($assets_id_arr,$assets_old_ids));
		$deviceOpt = "";
		$rows = $this->tree_model->get_devices($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$deviceOpt .= "<option value='".$row->id."' ";
				if(in_array($row->id,$tot))
							$deviceOpt .=" selected='selected'";
							
				$deviceOpt .= ">".$row->assets_name;
				if($row->assets_friendly_nm!="")
					$deviceOpt.= " (".$row->assets_friendly_nm.")";
				$deviceOpt.="</option>";
			}
		}
		//echo $deviceOpt;
		$this->output->set_output($deviceOpt);
	}
	function get_usrs_details()
	{	
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');  
		$rows = $this->tree_model->get_subuser_detail(uri_assoc('uid'));
		$data=array();
		$data['row']=$rows[0];
		$data['row']['from_date']=date($date_format.' '.$time_format,strtotime($data['row']['from_date']));
		$data['row']['to_date']=date($date_format.' '.$time_format,strtotime($data['row']['to_date']));
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function get_group_detail()
	{	
		$rows = $this->tree_model->get_group_nm(uri_assoc('uid'));
		$data=array();
		$data['row']=$rows[0];
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function saveDist()
	{
		$insert = $this->tree_model->save_dist($this->session->userdata('user_id'));
		//echo "Data Saved";
		$this->output->set_output("Data Saved");
	}
	function getDist()
	{
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$coords = array();
		$deviceOpt = "";
				
		$rows = $this->tree_model->getCoord($this->session->userdata('user_id'));
		$arr = array();
		//die(print_r($rows));
		if(count($rows) > 0) {
		$i=0;
			foreach ($rows as $row) {
				$coords[$i]['assets_id']=$row['id'];
				$coords[$i]['lat']=$row['lati'];
				$coords[$i]['lng']=$row['longi'];
				$coords[$i]['truck']=$row['assets_name'];
				if($row['assets_image_path']==null)
				{
					$coords[$i]['image']="truck.png";
				}
				else
				{
				$coords[$i]['image']=$row['assets_image_path'];
				}
				$i++;
			}
		}
		$res['points']=$coords;
		//echo json_encode($res);
		$this->output->set_output(json_encode($res));
		
	}
	function newTab()
	{
		$path = uri_string('tab');
		$path = uri_string();
		$path_segments =array();
		$path_segments = explode("/", $path);
		$path = $path_segments[3];
		//print_r($path);
		$path_segments =array();
		$path_segments = explode(";", $path);
		//print_r($path_segments);
		//die();
		
		
		$coords = array();
		$data = array();
		$deviceOpt = array();
	//	echo $this->session->userdata('user_id')
		$rows = $this->tree_model->get_devices($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$deviceOpt[] = $row->assets_name;
			}
		}
		$rows = $this->tree_model->get_group($this->session->userdata('user_id'));
		$groupOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$groupOpt .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
			}
		}
		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		$subUserOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$subUserOpt .= "<option value='u-".$row->user_id."'>".$row->first_name." ".$row->last_name."</option>";
			}
		}
		//die($path_segments[0]);
		$path_segments[0]=  str_replace("||","//",$path_segments[0]);
		$path_segments[0] =  str_replace("|","/",$path_segments[0]);
		//die($path_segments[3]);
		$data['option'] = $deviceOpt;
		$data['groupOpt'] = $groupOpt;
		$data['subUserOpt'] = $subUserOpt;
		$this->load->library('GMap');
		$data['main_menu'] = $this->tree_model->get_main_menu();
		$this->gmap->GoogleMapAPI();
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['openTab']['url'] = $path_segments[0];
		$data['openTab']['title'] = $path_segments[1];
		$data['openTab']['language'] = $path_segments[2];
		$data['openTab']['cmd'] = $path_segments[3];
		$this->load->view('home',$data);
	}
	function tooltipURL()
	{
		$rows = $this->tree_model->getToolTips(uri_assoc('id'),$this->session->userdata('user_id'));
	}
	
	function filterAddressbook(){
		
		
		$rows = $this->tree_model->addressbook_opt();
		$addressbookOpt = '';
		foreach ($rows as $row) {
			$addressbookOpt .= '<option value="'.$row->id.'">'.$row->name.'</option>';
		}		
		$data['opt'] = $addressbookOpt;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function Help_html()
	{
		$this->load->view('speedHelp');
	}
	function extra_js()
	{
		$this->load->view('tree_js');
	}
	function image_open(){
		$coords = array();
		$res = $this->tree_model->imageViewer(1);
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				//$row->received_time = ago($row->add_date) . ' ago';
				$row->received_time = ago($row->add_date)." ".$this->lang->line('ago');
				$row->received_time = str_replace("weeks",$this->lang->line('weeks'),$row->received_time);
				$row->received_time = str_replace("week",$this->lang->line('week'),$row->received_time);
				$row->received_time = str_replace("months",$this->lang->line('months'),$row->received_time);
				$row->received_time = str_replace("month",$this->lang->line('month'),$row->received_time);
				$row->received_time = str_replace("years",$this->lang->line('years'),$row->received_time);
				$row->received_time = str_replace("year",$this->lang->line('year'),$row->received_time);
				$row->received_time = str_replace("days",$this->lang->line('days'),$row->received_time);
				$row->received_time = str_replace("day",$this->lang->line('day'),$row->received_time);
				$row->received_time = str_replace("hours",$this->lang->line('hours'),$row->received_time);
				$row->received_time = str_replace("hour",$this->lang->line('hour'),$row->received_time);
				$row->received_time = str_replace("minutes",$this->lang->line('minutes'),$row->received_time);
				$row->received_time = str_replace("minute",$this->lang->line('minute'),$row->received_time);
				$row->received_time = str_replace("seconds",$this->lang->line('seconds'),$row->received_time);
				$type=str_replace("weeks",'wk',$row->received_time);
				//echo $row->received_time."<br/>"."";
				$coords[] = $row;
			}
		}
	

		$rows = $this->tree_model->getAssetsStatus($this->session->userdata('user_id'));
		
		$data['img_assets_id'] = uri_assoc('id');
		
		$data['coords'] = $coords;
		$data['totalPage'] = $totalPage;
		$data['page'] = $page;
		$data['totalRecords'] = $totalRecords;
		$data['limit'] = $limit;
				
		$this->load->view('imageViewer',$data);		
	}
	function navigationImages(){
		$coords = array();
		$page=uri_assoc('page');
		$time=uri_assoc('time');
		$res = $this->tree_model->imageViewer($page);		
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];
		$currentR=count($rows);
		$viewingS="";
		$viewingE="";
		if($page>1){
			$viewingS.=(($page-1)*8)+1;
			$viewingE.=(($page-1)*8)+$currentR;
		}else{
			$viewingS.=1;
			$viewingE.=$currentR;
		}
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				//$row->received_time = ago($row->add_date) . ' ago';
				$row->received_time = ago($row->add_date)." ".$this->lang->line('ago');
				$row->received_time = str_replace("weeks",$this->lang->line('weeks'),$row->received_time);
				$row->received_time = str_replace("week",$this->lang->line('week'),$row->received_time);
				$row->received_time = str_replace("months",$this->lang->line('months'),$row->received_time);
				$row->received_time = str_replace("month",$this->lang->line('month'),$row->received_time);
				$row->received_time = str_replace("years",$this->lang->line('years'),$row->received_time);
				$row->received_time = str_replace("year",$this->lang->line('year'),$row->received_time);
				$row->received_time = str_replace("days",$this->lang->line('days'),$row->received_time);
				$row->received_time = str_replace("day",$this->lang->line('day'),$row->received_time);
				$row->received_time = str_replace("hours",$this->lang->line('hours'),$row->received_time);
				$row->received_time = str_replace("hour",$this->lang->line('hour'),$row->received_time);
				$row->received_time = str_replace("minutes",$this->lang->line('minutes'),$row->received_time);
				$row->received_time = str_replace("minute",$this->lang->line('minute'),$row->received_time);
				$row->received_time = str_replace("seconds",$this->lang->line('seconds'),$row->received_time);
				$type=str_replace("weeks",'wk',$row->received_time);
				//echo $row->received_time."<br/>"."";
				$coords[] = $row;
			}
		}
	

		$rows = $this->tree_model->getAssetsStatus($this->session->userdata('user_id'));
		
		$img_assets_id = uri_assoc('id');
		
		$html="<table><tr>";
		if(count($coords) > 0) {
		$cntr=0;
			foreach ($coords as $coord) {
		
			if($cntr!=0 && $cntr%4==0){
				$html.="</tr><tr><td class='td_padding' align='center'>";
				$html.='<a class="fancybox" rel="gallery1" href="'. base_url(). 'assets/captured/'. $coord->captured_image .'" title="'.date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($coord->add_date)) .'"><img src="'. base_url() .'assets/captured/'.$coord->captured_image.'" width="200"/></a><br />'.date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($coord->add_date));
				$html.="<td>";
			}else{
				$html.="<td class='td_padding' align='center'>";
				$html.='<a class="fancybox" rel="gallery1" href="'. base_url(). 'assets/captured/'. $coord->captured_image .'" title="'.date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($coord->add_date)) .'"><img src="'. base_url() .'assets/captured/'.$coord->captured_image.'" width="200"/></a><br />'.date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($coord->add_date));
				$html.="<td>";
			}
			$cntr++;
			}
		}
		else
		{	
			//echo "<td>No Data Found</td>";
			$this->output->set_output("<td>No Data Found</td>");
		}
	$html.="</tr></table>";
	
	//echo $html;
		$this->output->set_output($html);
		if($this->session->userdata('show_dash_paging')==1){
		//	echo paginate($reload, $page, $totalPage, 5, $totalRecords, $limit,$this->lang,$img_assets_id);
		//function paginate($reload, $page, $tpages, $adjacents, $totalRecords, $limit,$this->lang,$img_assets_id)
			$adjacents=5;
			$prevlabel = $this->lang->line("prev");
			$nextlabel = $this->lang->line("next");
			$firstlabel = $this->lang->line("First");
			$out = '<div class="sixteen columns centre" id="bottomPaging">';
			if($totalPage>1 && $page!=1)
			{
				$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage_img(1,".$img_assets_id.",".$time.")'>".$this->lang->line("First")."</a></span>\n";
			}
			else
			{
				$out.= "<span><a class='ui-state-default paginDisabled' style='cursor:pointer;'>".$this->lang->line("First")."</a></span>\n";
			}
			// previous
			if($page==1) {
				$out.= "<span><a class='ui-state-default paginDisabled'>" . $prevlabel . "</a></span>\n";
			}
			else {
				$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage_img(" . ($page-1) . ",".$img_assets_id.",".$time.")'>" . $prevlabel . "</a></span>\n";
			}
			// first
			if($page>($adjacents+1)) {
				$out.= "<a class='pagelink' onclick='changePage_img(1,".$img_assets_id.",".$time.")'>1</a>\n";
			}
			
			// interval
			if($page>($adjacents+2)) {
				$out.= "...\n";
			}
			
			// pages
			$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
			$pmax = ($page<($totalPage-$adjacents)) ? ($page+$adjacents) : $totalPage;
			for($i=$pmin; $i<=$pmax; $i++) {
				if($i==$page) {
					$out.= "<a class='activePage'>" . $i . "</a>\n";
				}
				else {
					$out.= "<a class='pagelink' onclick=changePage_img($i,".$img_assets_id.",".$time.")>" . $i . "</a>\n";
				}
			}
			
			// interval
			if($page<($totalPage-$adjacents-1)) {
				$out.= "...\n";
			}
			
			// last
			if($page<($totalPage-$adjacents)) {
				$out.= "<a class='pagelink' onclick=changePage_img(" . $totalPage . ",".$img_assets_id.",".$time.")>" . $totalPage . "</a>\n";
			}
			
			// next
			if($page<$totalPage) {
				$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage_img(" . ($page+1) . ",".$img_assets_id.",".$time.")'>" . $nextlabel . "</a></span>\n";
			}
			else {
				$out.= "<span><a class='ui-state-default paginDisabled'>" . $nextlabel . "</a></span>\n";
			}
			
			if($totalPage>1 && $page!=$totalPage)
			{
				$out.= "<span onclick=changePage_img(" . ($totalPage) . ",".$img_assets_id.",".$time.")><a style='cursor:pointer;' class='ui-state-default'>".$this->lang->line("Last")."</a></span>  | \n";
			}
			else
			{
				$out.= "<span><a style='cursor:pointer;' class='ui-state-default paginDisabled'>".$this->lang->line("Last")."</a></span>  | \n";
			}
		//	$out.= '<span style="display: inline-block; margin-top: 10px;">'.$this->lang->line("view").' : <strong> '.$viewingS.' - '.$viewingE.' of  '.$totalRecords.' </strong> ';
			//$out.= "</span>";
			
			$out.= '<span style="display: inline-block; margin-top: 10px;">'.$this->lang->line("Total Imaages").' : <strong> '.$totalRecords.' </strong> | '.$this->lang->line("Number of Imaages per page").' : ';
			$out.= "<select onchange='changePage_img(".$page.",".$img_assets_id.",".$time.")' style='margin:0' id='numImage".$time."' >";
			$out .= "<option";
			if($limit == 8)
				$out.= " selected='selected'";
			$out.= ">8</option><option";
			if($limit == 12)
				$out.= " selected='selected'";
			$out.= ">12</option><option";
			if($limit == 24)
				$out.= " selected='selected'";
			$out.= ">24</option><option";
			if($limit == 48)
				$out.= " selected='selected'";
			$out.= ">48</option><option";
			if($limit == 98)
				$out.= " selected='selected'";
			$out.= ">98</option><option value='all'";
			if($limit == 'all')
				$out.= " selected='selected'";
			$out.= ">All</option>";
			$out.= "</select> <a style='cursor:pointer;' onclick='changePage_img(" . $page . ",".$img_assets_id.",".$time.")'>Refresh</a></span></div>";
			
			//echo $out;
			$this->output->set_output($out);
		}
	}
	function changeAssetsCombo(){
		$rows = $this->tree_model->get_devices_admin(uri_assoc('user_id'));
		$d_assets_cmb="";
		if(count($rows)) {
			foreach ($rows as $row) {
				$d_assets_cmb.="<option value='".$row->id."'>";
				$d_assets_cmb.=$row->assets_name." (".$row->device_id.")";
				$d_assets_cmb.="</option>";
			}
		}	
		die($d_assets_cmb);
	}
	function filter_assets()
	{
		$rows = $this->tree_model->groupAssets();
		foreach ($rows as $row) {
			$opt .= "<option value='".$row->id."'>".$row->assets_name."</option>";
		}
		die($opt);
	}
	
	function userList(){
		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		$subUserList = '<ul>';
		if(count($rows)) {
			foreach ($rows as $row) {
				$subUserList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:triggerChange('opt_users', 'u-".$row->user_id."')\">".$row->username." (".$row->first_name." ".$row->last_name.")</a></li>";
			}
		}
		$subUserList .= '</ul>';
		$this->output->set_output($subUserList);
	}
	
	function groupList(){
		
		$rows = $this->tree_model->get_group($this->session->userdata('user_id'));
		$groupList = array();
		if(count($rows)) {
			foreach ($rows as $row) {
				$groupList[] = array(
				'label' => $row->group_name,
				'value' => 'tree/treenode/group/' . $row->id,
				'items' => array("label" => "Loading..."));
			}
		}
		
		print(json_encode($groupList));
/*		
		$rows = $this->tree_model->get_group($this->session->userdata('user_id'));
		$groupList = '<ul>';
		if(count($rows)) {
			foreach ($rows as $row) {
				$groupList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:triggerChange('opt_groups', 'u-".$row->id."')\">".$row->group_name."</a></li>";
			}
		}
	
		$groupList .= '</ul>';
		$this->output->set_output($groupList);
*/		
	}
	
	function areaList(){
		
		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		$landUsers = array($this->session->userdata('user_id'));
		
		foreach ($rows as $row) {
			$landUsers[] = $row->user_id;
		}
		
		$rows = $this->tree_model->get_areas($landUsers);
		
		$areasList = array();
		if(count($rows)) {
			foreach ($rows as $row) {
				$areasList[] = array(
				'label' => $row->polyname,
				'value' => 'tree/treenode/area/' . $row->polyid,
				'items' => array("label" => "Loading..."));
			}
		}

		print(json_encode($areasList));
		
/*
		$areasList = '<ul>';
		if(count($rows)) {
			foreach ($rows as $row) {
				$areasList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:triggerChange('opt_areas', 'a-".$row->polyid."')\">".addslashes($row->polyname)."</a></li>";
			}
		}
		
		$areasList .= '</ul>';
		$this->output->set_output($areasList);
*/		
	}
	
	function landmarkList(){	

		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		$landUsers = array($this->session->userdata('user_id'));
		
		foreach ($rows as $row) {
			$landUsers[] = $row->user_id;
		}
		
		$rows = $this->tree_model->get_landmarks($landUsers);
	
		$landList = array();
		if(count($rows)) {
			foreach ($rows as $row) {
				$landList[] = array(
				'label' => $row->name,
				'value' => 'tree/treenode/landmark/' . $row->id,
				'items' => array("label" => "Loading..."));
			}
		}
		
		print(json_encode($landList));
/*		
		$landList = '<ul>';
		if(count($rows)) {
			foreach ($rows as $row) {
				$landList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:triggerChange('opt_landmarks', 'l-".$row->id."')\">".addslashes($row->name)."</a></li>";
			}
		}
		
		$landList .= '</ul>';
		$this->output->set_output($landList);
*/		
	}
	
	function ownerList() {
		
		$ownerList = array();
		$rows = $this->tree_model->get_owners();
		foreach ($rows as $row)	{
			$ownerList[] = array(
				'label' => $row->owner,
				'value' => 'tree/treenode/owner/' . $row->id,
				'items' => array("label" => "Loading..."));
		}
		
		print(json_encode($ownerList));
/*		
		foreach ($rows as $row)	{
			$ownerList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:triggerChange('opt_owners', 'o-".$row->id."')\">".addslashes($row->owner)."</a></li>";
		}

		$ownerList .= '</ul>';
		$this->output->set_output($ownerList);
*/		
	}
	
	function divisionList() {
		
		$divisionList = array();
		$rows = $this->tree_model->get_divisions();
		foreach ($rows as $row) {
			$divisionList[] = array(
				'label' => $row->division,
				'value' => 'tree/treenode/division/' . $row->id,
				'items' => array("label" => "Loading..."));
		}
		
		print(json_encode($divisionList));
/*		
		foreach ($rows as $row) {
			$divisionList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:triggerChange('opt_divisions', 'd-".$row->id."')\">".addslashes($row->division)."</a></li>";
		}

		$divisionList .= '</ul>';
		$this->output->set_output($divisionList);
*/		
	}
	
	function assList() {
		//$this->input->post('limit', 'all');
		$rows = $this->tree_model->all_location($this->session->userdata('user_id'));
		
		$assList = '<ul>';
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				
				$assList .= "<li><a class='ui-button ui-widget ui-state-default ui-button-text-only linklink' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick=\"javascript:directTab(".$row->device_id.", ".$row->assets_id.")\">".$row->assets_name.", ";
				$assList .= date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date)). ', ';
				$assList .= ago($row->add_date) . ' ago';
				$assList .= '</a></li>';
			}
		}
		$assList .= '</ul>';
		
		$this->output->set_output($assList);

		
	}
	
	function fulltree() {
	
		$selected = $_REQUEST['sel'];
		$allAssetsList = array();
		
		$rows = $this->tree_model->get_group($this->session->userdata('user_id'));
		$groupList = array();
		if(count($rows)) {
			foreach ($rows as $row) {
				$indexs = explode(',', $row->assets);
				$ass = array();
				foreach($indexs as $index) {
					$ass[$index] = $row->id;
				}
				
				$groupList[$row->id] = array(
				'label' => $row->group_name,
				'value' => $row->id,
				'assets' => $ass);
			}
		}
		
		$rows = $this->tree_model->get_subuser($this->session->userdata('user_id'));
		$landUsers = array($this->session->userdata('user_id'));
		
		$max = 10;
		$u = 0;
		foreach ($rows as $row) {
//			if($u >= $max) break;
			$landUsers[] = $row->user_id;
			$u++;
		}
		
		$rows = $this->tree_model->get_areas($landUsers);
		
		$areasList = array();
		if(count($rows)) {
			$p = 0;
			foreach ($rows as $row) {
//				if($p >= $max) break;

				$areasList[$row->polyid] = array(
				'label' => $row->polyname,
				'value' => $row->polyid);
				$p++;
			}
		}		
		
		$rows = $this->tree_model->get_zones($landUsers);
		
		$zonesList = array();
		if(count($rows)) {
			$p = 0;
			foreach ($rows as $row) {
//				if($p >= $max) break;

				$zonesList[$row->polyid] = array(
				'label' => $row->polyname,
				'value' => $row->polyid);
				$p++;
			}
		}		
		
		$rows = $this->tree_model->get_landmarks($landUsers);
	
		$landList = array();
		if(count($rows)) {
			$l = 0;
			foreach ($rows as $row) {
//				if($l >= $max) break;
				
				$landList[$row->id] = array(
				'label' => $row->name,
				'value' => $row->id);
				$l++;
			}
		}
		
		$ownerList = array();
		$rows = $this->tree_model->get_owners();
		foreach ($rows as $row)	{
			$ownerList[$row->id] = array(
				'label' => $row->owner,
				'value' => $row->id);
		}
		
		$divisionList = array();
		$rows = $this->tree_model->get_divisions();
		foreach ($rows as $row) {
			$divisionList[$row->id] = array(
				'label' => $row->division,
				'value' => $row->id);
		}

		$rows = $this->tree_model->all_location($this->session->userdata('user_id'));
		
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				
				$other = true;
				
				if(is_null($row->beforeTime)) {
					$color = 'red';
				}
				else if($row->beforeTime < $this->session->userdata('network_timeout') && $row->speed > 10) {
					$color = 'green';
				}
				else if($row->beforeTime < $this->session->userdata('network_timeout') && $row->speed <= 10 && $row->ignition == 0) {
					$color = '#06F';
				}
				else if($row->beforeTime < $this->session->userdata('network_timeout') && $row->speed <= 10 && $row->ignition == 1) {
					$color = 'green';
				}
				else {
					$color = 'red';
				}

				if(in_array($row->assets_id, $selected)) {
					$sel = true;
				}
				else {
					$sel = false;
				}

				$element = array(
					'label' => "<span style='color: $color'>" . $row->assets_name . "</span>",
					'checked' => $sel,
					'value' => 'asset/' . $row->assets_id);
				
				foreach($groupList as $id => $group) {
					
					if(array_key_exists($row->assets_id, $group['assets'])) {
						$groupList[$id]['items'][] = $element;
						$other = false;
					}
				}
				
				if(array_key_exists($row->divison, $divisionList)) {
					$divisionList[$row->divison]['items'][] = $element;
					$other = false;
				}
				
				if(array_key_exists($row->own, $ownerList)) {
					$ownerList[$row->own]['items'][] = $element;
					$other = false;
				}

				if(array_key_exists($row->area_id, $areasList)) {
					$areasList[$row->area_id]['items'][] = $element;
					$other = false;
				}
				
				if(array_key_exists($row->zone_id, $zonesList)) {
					$zonesList[$row->zone_id]['items'][] = $element;
					$other = false;
				}
				
				if(array_key_exists($row->landmark_id, $landList)) {
					$landList[$row->landmark_id]['items'][] = $element;
					$other = false;
				}
				
				if($other) $allAssetsList[] = $element;

			}
		}
		
		foreach($groupList as $div){
			unset($div['assets']);
			$groupListNew[] = $div;
		}

		foreach($divisionList as $div){
			$divisionListNew[] = $div;
		}

		foreach($areasList as $div){
			$areasListNew[] = $div;
		}
		
		foreach($zonesList as $div){
			$zonesListNew[] = $div;
		}
		
		foreach($ownerList as $div){
			$ownerListNew[] = $div;
		}
		
		foreach($landList as $div){
			// echo "<br/>checking ". $div['label'];
			$flag = 0;
			foreach($landListNew as $key => $val) {
				if($val['label'] == $div['label']) {
					$flag = 1;
					foreach($div['items'] as $it)
						array_push($landListNew[$key]['items'], $it);
					// echo "Found " . $div['label'] . "<br />";
				}
			}
			if(!$flag) $landListNew[] = $div;
		}

		// print_r($landListNew); exit;
	
		$data[] = array("label" => "<B>Group</B>", 'value' => 'groupList', 'items' => $groupListNew);
		$data[] = array("label" => "<B>Landmark</B>", 'value' => 'landmarkList', 'items' => $landListNew);
		$data[] = array("label" => "<B>Area</B>", 'value' => 'areaList', 'items' => $areasListNew);
		$data[] = array("label" => "<B>Zone</B>", 'value' => 'zoneList', 'items' => $zonesListNew);
		
		if($this->session->userdata('show_owners') == 1) {
			$data[] = array("label" => "<B>Owner</B>", 'value' => 'ownerList', 'items' => $ownerListNew);
		}
		if($this->session->userdata('show_divisions') == 1) {
			$data[] = array("label" => "<B>Division</B>", 'value' => 'divisionList', 'items' => $divisionListNew);
		}
		if(count($allAssetsList)) {
			$data[] = array("label" => "<B>Other Assets</B>", 'value' => 'allAssetsList', 'items' => $allAssetsList);
		}
		
		$tree_data[] = array("label" => "<B>".ucwords($this->session->userdata('first_name'))."</B>", 'value' => $this->session->userdata('user_id'), 'expanded' => true);
		
		foreach($data as $div){
			$tree_data[0]['items'][] = $div;
		}
		
		foreach($tree_data as $div){
			$treeView[] = $div;
		}
		
		print(json_encode($treeView));
		
/*
		echo  '[{"label": "Root Folder 1", "value": "tree/ajax1", "items": [{ "value": "tree/ajax1", "label": "Loading..." }]},
    { "label": "Root Folder 2", "value": "tree/ajax2", "items": [{ "value": "tree/ajax2", "label": "Loading..." }]}]';
	
*/	
		
	}	

	function ajaxroot() {
		
		$data[] = array("label" => "Group", 'value' => base_url().'index.php/tree/groupList', 'items' => array("label" => "Loading..."));
		$data[] = array("label" => "Area", 'value' => base_url().'index.php/tree/areaList', 'items' => array("label" => "Loading..."));
		$data[] = array("label" => "Landmark", 'value' => base_url().'index.php/tree/landmarkList', 'items' => array("label" => "Loading..."));
		$data[] = array("label" => "Owner", 'value' => base_url().'index.php/tree/ownerList', 'items' => array("label" => "Loading..."));
		$data[] = array("label" => "Division", 'value' => base_url().'index.php/tree/divisionList', 'items' => array("label" => "Loading..."));
		
		print(json_encode($data));
/*
		echo  '[{"label": "Root Folder 1", "value": "tree/ajax1", "items": [{ "value": "tree/ajax1", "label": "Loading..." }]},
    { "label": "Root Folder 2", "value": "tree/ajax2", "items": [{ "value": "tree/ajax2", "label": "Loading..." }]}]';
	
*/	
		
	}
	
	function treenode() {
		
		$selected = $_REQUEST['sel'];
		
		$rows = $this->tree_model->get_subTree();
		
		$data = array();
		
		if(count($rows) > 1) {
			foreach ($rows as $row) {
				if(is_null($row->diffr)) {
					$color = 'red';
				}
				else if($row->diffr < $this->session->userdata('network_timeout')) {
					$color = 'green';
				}
				else {
					$color = 'red';
				}
				
				if(in_array($row->id, $selected)) {
					$sel = true;
				}
				else {
					$sel = false;
				}
				
				$data[] = array(
					'label' => "<span style='color: $color'>" . $row->assets_name . "</span>",
					'checked' => $sel,
					'value' => 'asset/' . $row->id);
			}
		}
		else {
			$data[] = array('label' => "No Assets");
		}
		
		print(json_encode($data));
		
	}
	
	function grid_view(){
		$this->load->view('lastpoint');
	}
	
	function loadGridData(){
		
		$assets = $_REQUEST['assets'];
		// $selected = $_REQUEST['sel'];
		/*
		if($selected != '') {
			$selected = explode(',',$selected);
		}
		else {
			$selected = $assets;
		}
		*/
		$assets = implode(",", $assets);
		
		if($assets != '') {

			$rows = $this->tree_model->stop_duration($assets);

			if(count($rows) > 0) {
				foreach ($rows as $row) {
					if($row->stop_from != ''){
						$minutes = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($row->stop_from)) / 60;

						// $minutes = $row->stop_from;

						$d = floor ($minutes / 1440);
						$h = floor (($minutes - $d * 1440) / 60);
						$m = $minutes - ($d * 1440) - ($h * 60);
						$stop_time = '';
						if($d > 0)
							$stop_time .= $d." Day ";
						if($h > 0)
							$stop_time .= $h." Hour ";
						if($m > 0)
							$stop_time .= intval($m)." Min";

						$stopArr[$row->device_id] = $stop_time;
					}
				}
			}
	
			$res = $this->tree_model->treeLastLocation($assets);
			$rows = $res[0];
		}

/*		
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];
*/		
		$responce->page = $res[2];
		$responce->total = $res[1];
		$responce->records = $res[3];
		
		$i=0;
		foreach($rows as $row) {
			if (array_key_exists($row->assets_id, $stopArr)) {
				$row->stop_from = $stopArr[$row->assets_id];
			}
			
			$image_type = ''; // base_url(). "assets/";
			if($row->assets_category_id == 1 || $row->assets_category_id == "" || $row->assets_category_id == 0){
				$image_type = "truck.png";
			}else if($row->assets_category_id == 2){
				$image_type = "car.png";
			}
			else if($row->assets_category_id == 3){
				$image_type = "bus.png";
			}
			else if($row->assets_category_id == 4){
				$image_type = "mobile.png";
			}
			else if($row->assets_category_id == 5){
				$image_type = "bike.png";
			}
			else if($row->assets_category_id == 6){
				$image_type = "altenator.png";
			}
			else if($row->assets_category_id == 7 || $row->assets_category_id == 8){
				$image_type = "man.png";
			}
			else if($row->assets_category_id == 9){
				$image_type = "stacker.png";
			}
			else if($row->assets_category_id == 10){
				$image_type = "loader.png";
			}
			else if($row->assets_category_id == 11){
				$image_type = "locomotive.png";
			}
			else if($row->assets_category_id == 12){
				$image_type = "generator.png";
			}
			else if($row->assets_category_id == 13){
				$image_type = "maintenance.png";
			}
			else if($row->assets_category_id == 14){
				$image_type = "motor.png";
			}
			else if($row->assets_category_id == 15){
				$image_type = "bobcat.png";
			}
			else if($row->assets_category_id == 16){
				$image_type = "tractor.png";
			}
			else if($row->assets_category_id == 17){
				$image_type = "car1.png";
			}
			else if($row->assets_category_id == 18){
				$image_type = "satellite.png";
			}
			else if($row->assets_category_id == 21){
				$image_type = "stacker.png";
			}
			else{
				$image_type = "truck.png";
			}
			
			$row->maker_image = $image_type;
			if($row->add_date!="")
				$row->received_time = ago($row->add_date) . ' ago';
			else
				$row->received_time = 'No Data';
			$minutes_before = ($row->beforeTime);
			
			$text  = "<b>$row->assets_name (".$row->assets_friendly_nm.") ";
			
			if($this->session->userdata('usertype_id')!=3){
				$text.=" (".$row->device_id.")";
			}
			
			$text .= "</b><br>";
			$text .= $row->received_time . ", ".date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'),strtotime($row->add_date))."<br>";
			if($row->ignition == 0)
				$ignition = "OFF";
			else 
				$ignition = "ON";
			$text .="Ignition: ".$ignition." , Speed: ".$row->speed." KM<br>";
			
			if($row->address != "") $text .= " ".$row->address."<br>";
			
			if($this->session->userdata('show_dash_legends')==1){
				$text .="Status: ";
				if($minutes_before < $this->session->userdata('network_timeout') && $row->speed > 0 && $minutes_before != ""){
						$status ="Running";
						$status_img = "green_dot.png";
						$color = "green";
				}else if($minutes_before < $this->session->userdata('network_timeout') && $row->speed == 0 && $row->ignition == 0 && $minutes_before != ""){
						$status ="Parked";
						$status_img = "blue_dot.png";
						$color = "#06F";
				}else if($minutes_before < $this->session->userdata('network_timeout') && $row->speed == 0 && $row->ignition == 1 && $minutes_before != ""){
						$status ="Idle";
						$status_img = "green_dot.png";
						$color = "green";
				}else if($minutes_before >= $this->session->userdata('network_timeout') && $minutes_before <= ($this->session->userdata('network_timeout')+36000) && $minutes_before != ""){
						$status ="Out of network";
						$status_img = "RedDot.png";
						$color = "orange";
				}else if($minutes_before > ($this->session->userdata('network_timeout')+36000) or $minutes_before ==""){
						$status ="Out of network";
						$status_img = "RedDot.png";
						$color = "orange";
				}
			}
			$text .= $status."<br>";

			//if($this->session->userdata('show_map_driver_detail_window')==1){
			
				if($row->driver_name!="" || $row->driver_name!=null) 
				$text .="Driver Name: ".$row->driver_name."<br>"; 
			
				if($row->driver_mobile!="" || $row->driver_mobile!=null) 
				$text .="Driver Mob.:".$row->driver_mobile."<br>"; 
			//}			
			
			$row->assets_friendly_name = $row->assets_name .", ". date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'),strtotime($row->add_date));
			$row->assets_name = '<a style="color:inherit;" href="javascript:loadAssetsDash_tt(\''.$row->assets_id.'\', \''.$row->assets_name.'\')">'.$row->assets_name.' ('.$row->assets_friendly_nm.')</a>';
			$row->maker_text  = "<div style='text-align:left;'>" . $text . "</div>";
			$row->ast_id 	  = $row->assets_id;
			$row->status_img  = $status_img;
			$row->dev_status  = $status;
			$row->direction   = $row->angle_dir;
			$row->id   		  = ($i+1);
			/*
			if (in_array($row->assets_id, $selected)) {
				$row->selected = 1;
			}
			else {
				$row->selected = 0;
			}
			*/
			$responce->rows[$i] = $row;
			$i++;
		}
		
		if($assets != '') {
			$rows = $this->tree_model->treeAssetsStatus($assets);
		}
		
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
	
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}
		
		$responce->userdata->running = $running;
		$responce->userdata->parked = $parked;
		$responce->userdata->out_of_network = $out_of_network;
		$responce->userdata->device_fault = $device_fault;
		$responce->userdata->total = $total;
		// $responce->userdata->selected = $selected;

		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	
	
/*	

	function ajax() {
		echo '[
{"label": "Folder 9" },
{"label": "Folder 10" },
{"label": "Folder 11" },
{"label": "Folder 12" }
]';
	}
	
	function ajax1() {
		echo  '[
{ "label": "Folder 1", "value": "tree/ajax", "items": [{ "value": "tree/ajax", "label" : "Loading..." } ] },
{ "label": "Folder 2", "value": "tree/ajax", "items": [{ "value": "tree/ajax", "label" : "Loading..." } ] },
{ "label": "Folder 3", "value": "tree/ajax", "items": [{ "value": "tree/ajax", "label" : "Loading..." } ] }
]';
		
	}
	
	function ajax2() {
		echo  '[
{"label": "Folder 5" },
{"label": "Folder 6" },
{"label": "Folder 7" },
{"label": "Folder 8" }
]';
		
	}
*/	

}
?>