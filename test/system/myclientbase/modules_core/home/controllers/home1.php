<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Home extends Admin_Controller {
	function __construct() {
		parent::__construct();
		//$this->load->library('session');
		$this->load->helper('mcb_date');
		//userdata('time_zone');
		date_default_timezone_set($this->session->userdata('time_zone'));
	}
	function chat(){
		$this->load->helper('cookie');
		$this->load->model('home_model');
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		$rs="";
		if($action=='chatheartbeat'){
			$rs = $this->home_model->chatheartbeat();
		}else if($action=='closechat'){
			$rs = $this->home_model->closechat();
		}else if($action=='startchatsession'){
			$rs = $this->home_model->startchatsession();
		}else if($action=='sendChat'){
			$rs = $this->home_model->sendChat();
		}
		$this->output->set_output($rs);
	}
	function index(){
		$query = "update alert_master set del_date='".gmdate('Y-m-d H:i:s')."' where user_id=".$this->session->userdata('user_id');
		$this->db->query($query);
		$this->load->model('home_model');
		//$rs=$this->home_model->delete_tbl_track_data();
		$coords = array();
		$data = array();
		$deviceOpt = array();
		$rows = $this->home_model->get_devices($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$txt="";
				$txt = addslashes($row->assets_name);
				if($row->assets_friendly_nm!="")
					$txt.="(".addslashes($row->assets_friendly_nm).")";
				$deviceOpt[] =$txt;
			}
		}
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
		$groupOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$groupOpt .= "<option value='g-".$row->id."'>".addslashes($row->group_name)."</option>";
			}
		}	
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
		$subUserOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$subUserOpt .= "<option value='u-".$row->user_id."'>".$row->username." (".addslashes($row->first_name)." ".addslashes($row->last_name).")</option>";
			}
		}
		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
		
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
		$rows = $this->home_model->get_devices($this->session->userdata('user_id'));
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
		$display_settings = array();
		
		$rows=$this->home_model->getUserDisplay_Settings();
		$display_settings=$rows;
		$this->session->set_userdata($display_settings);
		
			//die(print_r($rows));
	
		$data['usr_assets_cmb']=$d_assets_cmb;
		$data['usr_assets_cmb_count']=$countAssets;
		
		$data['running_1'] = $running;
		$data['parked_1'] = $parked;
		$data['out_of_network_1'] = $out_of_network;
		$data['device_fault_1'] = $device_fault;
		$data['total_1'] = $total;
		$data['option'] = $deviceOpt;
		$data['groupOpt'] = $groupOpt;
		$data['subUserOpt'] = $subUserOpt;
		//$data['desplay_settings'] = $desplay_settings;
		$this->load->library('GMap');
		$data['main_menu'] = $this->home_model->get_main_menu();
		$this->gmap->GoogleMapAPI();
		$data['headerjs'] = $this->gmap->getHeaderJS();
		/*if($this->session->userdata('usertype_id')==3){
			$rows = $this->home_model->get_userDisplaySetting($this->session->userdata('user_id'));
			$d_assets_cmb="";
			if(count($rows)>1)
				$d_assets_cmb.="<option value=''>Please Select</option>";
			if(count($rows)) {
				foreach ($rows as $row) {
					$d_assets_cmb.="<option value='".$row->id."'>";
					$d_assets_cmb.=$row->assets_name." (".$row->device_id.")";
					$d_assets_cmb.="</option>";
				}
			}
		}*/
		$this->load->view('home',$data);
	}
	function saveRoute(){
		$this->load->model('home_model');
		$insert = $this->home_model->save_route($this->session->userdata('user_id'));
		//echo $insert;
		$this->output->set_output($insert);
	}
	function alert_master(){
		$this->load->model('home_model');
		//echo json_encode($this->home_model->alert_master());
		$this->output->set_output(json_encode($this->home_model->alert_master()));
	}
	function updateRoute(){
		$this->load->model('home_model');
		$insert = $this->home_model->update_route($this->session->userdata('user_id'));
		//echo $insert;
		$this->output->set_output($insert);
	}
	function view_photo(){
		$this->load->view("upload_image");
	}
	function get_photo(){
		$this->load->model('home_model');
		//echo $query=$this->home_model->get_user_photo();
		$this->output->set_output($query=$this->home_model->get_user_photo());
	}
	function put_photo(){
		$this->load->model('home_model');
		//echo $query=$this->home_model->put_user_photo();
		$this->output->set_output($query=$this->home_model->put_user_photo());
	}
	function route(){
		$this->load->model('live/device_model');
		$this->load->model('home_model');
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
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
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
		
		/*$rows = $this->home_model->load_route($this->session->userdata('user_id'));
				
		$coords = array();
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		if(count($rows)) {
			foreach ($rows as $row) {				
				$landmarkArr[] = $row->landmark_ids;
				$coords[] = $row;
				$rowsL = $this->home_model->get_route_landmark($row->landmark_ids);
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
			$rows = $this->home_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}*/
		$rows = $this->home_model->load_route($this->session->userdata('user_id'));
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		$finalCoords = array();
		if(count($rows)) {
			foreach ($rows as $row) {	
				$landmarkArr[] = $row->landmark_ids;
				
				$rowsSub = $this->home_model->load_sub_route($row->id);
				foreach ($rowsSub as $rowS) {
					$coords[] = $rowS;
					/*$rowsL = $this->home_model->get_route_landmark($rowS->landmark_ids);
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
			$rows = $this->home_model->get_route_landmark($landmark_ids);
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
		$this->load->model('home_model');
		
		if($this->session->userdata('usertype_id') > 2){
			$username = $this->session->userdata('username');
			$row = $this->home_model->get_Landmark_from_comment($username);
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
			$row = $this->home_model->get_completed_trip_landmark();
			$trip_last_landmark = $row->landmark_ids;
			$trip_last_landmark = explode(",", $trip_last_landmark);
			$trip_last_landmark = $trip_last_landmark[0];
		}
		/*$rows = $this->home_model->load_route_live($this->session->userdata('user_id'));
				
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
				$rowsL = $this->home_model->get_route_landmark($row->landmark_ids);
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
			$rows = $this->home_model->get_route_landmark($landmark_ids);
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
		
		$rows = $this->home_model->load_route_live($this->session->userdata('user_id'));
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
				
				$rowsL = $this->home_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
				$rowsSub = $this->home_model->load_sub_route_live($route_id, $my_landmark);
				
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
			$rows = $this->home_model->get_route_landmark($landmark_ids);
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
		$this->load->model('home_model');
		
		if($this->session->userdata('usertype_id') > 2){
			$username = $this->session->userdata('username');
			$row = $this->home_model->get_Landmark_from_comment($username);
			$my_landmark = $row->id;
			$my_lat = $row->lat;
			$my_lng = $row->lng;
		}
		
		$rows = $this->home_model->load_route_map();
		$landmarkArr = array();
		$landmark_ids = "";
		$landmarksRoute = array();
		$coords = array();
		$finalCoords = array();
		if(count($rows)) {
			foreach ($rows as $row) {				
				$route_id = $row->id;
				if($this->session->userdata('usertype_id') > 2){
					
					
					$rowT = $this->home_model->get_completed_trip_landmark_map($row->id);
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
				
				$rowsL = $this->home_model->get_route_landmark($row->landmark_ids);
				if(count($rowsL) > 0) {
					foreach ($rowsL as $rowL) {
						$landmarksRoute[$row->id][] = $rowL;
					}
				}
				$rowsSub = $this->home_model->load_sub_route_live($route_id, $my_landmark);
				
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
			$rows = $this->home_model->get_route_landmark($landmark_ids);
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
		$this->load->model('home_model');
		
		$rows = $this->home_model->load_route_list($this->session->userdata('user_id'));
				
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
				$rowL = $this->home_model->route_path($row->landmark);
				
				$lArr = explode(",",$rowL);
				
				$lString = str_replace(":", "-To-",$rowL);
				if($row->round_trip == 1 && count($lArr))
					$lString .= "-To-".$lArr[0];
				$html .= '<b>Way</b> : '.$lString."<br>";
				$html .= '<b>Assets</b> : '.$row->assets."<br>";
				$html .= '<b>Driver</b> : '.$row->driver_name."<br>";
				$html .= '<b>Created On</b> : '.date('d.m.Y h:i a', strtotime($row->add_date));
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
		$this->load->model('home_model');
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
		/*$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
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
		$rows = $this->home_model->addressbook_opt();
		$addressbookOpt = '';
		foreach ($rows as $row) {
			$addressbookOpt .= '<option value="'.$row->id.'">'.$row->name.'</option>';
		}
		$rows = $this->home_model->addressbook_group_opt();
		$addressbookGroupOpt = '';
		foreach ($rows as $row) {
			$addressbookGroupOpt .= '<option value="'.$row->id.'">'.$row->group_name.'</option>';
		}
		$rows = $this->home_model->getAllCoord($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
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
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$row->name=addslashes($row->name);
				$row->address=addslashes($row->address);
				$row->comments=addslashes($row->comments);
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
		
		$rows = $this->home_model->addressbook_opt();
		$addressbookOpt = '';
		foreach ($rows as $row) {
			$addressbookOpt .= '<option value="'.$row->id.'">'.$row->name.'</option>';
		}
		$rows = $this->home_model->addressbook_group_opt();
		$addressbookGroupOpt = '';
		foreach ($rows as $row) {
			$addressbookGroupOpt .= '<option value="'.$row->id.'">'.$row->group_name.'</option>';
		}
		
		$rows = $this->home_model->getLandmarkGroups();
		$LandmarkGroupOpt = '';
		$LandmarkGroupOpt = "<select class=\"select ui-widget-content ui-corner-all\" id=\"landmark_group_nm_".time()."\"><option value=\"\">Select Group Name</option>";
		foreach ($rows as $row) {
			$LandmarkGroupOpt .= '<option value="'.$row->id.'">'.addslashes($row->landmark_group_name).'</option>';
		}
		
		$LandmarkGroupOpt .= "</select>";

		$rows = $this->home_model->getAllCoord($this->session->userdata('user_id'));
		
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
		
		$this->load->model('home_model');
		
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
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
		
		$this->load->model('home_model');
		$row = $this->home_model->edit_route();
		$data['data'] = $row;	
		
		$rows = $this->home_model->load_route_edit();
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
			$rows = $this->home_model->get_route_landmark($landmark_ids);
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					$landmarks[] = $row;
				}
			}
		}
		$rows = $this->home_model->load_sub_route_edit();
				
		$coords = array();
		if(count($rows)) {
			foreach ($rows as $row) {
				$coords[] = $row;
				$rowsL = $this->home_model->get_route_landmark($row->landmark_ids);
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
		
		$this->load->model('home_model');
		$row = $this->home_model->edit_landmark();
		$data['data'] = $row;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function edit_area(){
		
		$this->load->model('home_model');
		$row = $this->home_model->edit_area();
		$data['data'] = $row;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function updateArea(){
		$this->load->model('home_model');
		$res = $this->home_model->updateArea();
		$responce['result'] = $res['result'];
		$responce['id'] = $res['insert_id'];
		$responce['msg'] = $res['msg'];
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function removeLandmark(){
		
		$this->load->model('home_model');
		$this->home_model->removeLandmark();
		exit;
	}
	function assets()
	{
		$this->load->model('home_model');
		$coords = array();
		
		$res = $this->home_model->last_location($this->session->userdata('user_id'));
		$rows = $res[0];
		$totalPage = $res[1];
		$page = $res[2];
		$totalRecords = $res[3];
		$limit = $res[4];
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				
				/*
				$distance = 0;
				
				$pts = $this->home_model->get_todays_points($row->device_id);
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
		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
		$coords = array();
		
		$res = $this->home_model->last_location($this->session->userdata('user_id'));
		
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
	

		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
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
				
		$this->load->view('assets_list',$data);
		
	}
	function map()
	{
		$dist="";
		$dist=uri_assoc("cmd");
		$this->load->model('home_model');
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
				
		$rows = $this->home_model->device_map($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
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
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
		$landmarks = array();
		if(count($rows) > 0){
			foreach ($rows as $row) {
				$landmarks[] = $row;
			}
		}
		$data['landmarks'] = $landmarks;
		$data['dist']=$dist;
		$this->load->view('map',$data);
	}
	function device_map_refresh()
	{
		$this->load->model('home_model');
		
		$coords = array();
		$lat = array();
		$lng = array();
		$html = array();
		$speed = array();
		$title = array();
		$beforeTime = array();
		$icon_path="";
		$rows = $this->home_model->device_map($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$lat[] = $row->lati;
				$lng[] = $row->longi;
				$speed[] = $row->speed;
				$icon_path[] = $row->icon_path;
				$minutes_before = ($row->beforeTime)/60;
				$text ="<div style='margin:3px;'>";
				$text .="<div style='background-color: lightgreen; text-align: center; border-radius: 7px 7px 7px 7px;'>Before ".ago($row->add_date)." ago, Dt-".date("d.m.Y h:i a",strtotime($row->add_date))."</div><span style='display: block ! important; width: 100%; height: 7px;'></span>";
				$text .="<div align='center' style='float:left;verticle-align:middle'><img src='".base_url()."assets/assets_photo/";
				if($row->assets_image_path!= NULL || $row->assets_image_path!="")
				{
					$text .= $row->assets_image_path."' />";
				}
				else
				{
					$text .= "truck.png' />";
				}
				$text.="<span style='display: block; height: 13px;'></span><img src='".base_url()."/assets/driver_photo/";
				if($row->driver_image!= NULL || $row->driver_image!="")
				{
					$text .= $row->driver_image."' />";
				}
				else
				{
					$text .= "not_available.jpg' />";
				}
				$text.="</div>";
				$text .="<div style='height:120px;margin:3px;width:200px;float:left'>";
				$text .="<div style='height: 63px ! important; margin-top: -2px;'><span style='display: block;'> ".$row->assets_name;
				if($row->assets_friendly_nm!="" || $row->assets_friendly_nm!=null)
					$text.=" (".$row->assets_friendly_nm.") ";
			
				$text.=" (".$row->device_id.") </span>";
				$text .="<span style='display: block;'> Ignition: ".$row->ignition." , Speed: ";
				$text .=" ".$row->speed." KM </span>";
				$text .="<span style='display: block;'>";
				
				if($row->address != "")
					$text .= " ".$row->address;
				$text .="</span>";
				$text .="<span style='display: block;'> Status: ";
				if($minutes_before <= 20 && $row->speed > 0)
					$text .="Running";
				else if($minutes_before <= 20 && $row->speed == 0 && $row->ignition == 0)
					$text .="Parked";
				else if($minutes_before <= 20 && $row->speed == 0 && $row->ignition == 1)
					$text .="Idle";
				else if($minutes_before < 1440)
					$text .="Running";
				else if($minutes_before <= 20)
					$text .="Running";
				$text .="</span>";
				$text .="<span style='display: block;'>Driver Name: ";
				if($row->driver_name!="" || $row->driver_name!=null) 
				$text .= $row->driver_name; 
				else 
				$text .="N/A";  
				$text .=" </span>";
				$text .="<span style='display: block;'>Driver Mob.:";
				if($row->driver_mobile!="" || $row->driver_mobile!=null) 
				$text .= $row->driver_mobile; 
				else 
				$text .="N/A";  
				$text .=" </span>";
				
				$text .="<a onClick='' style='left: 213px; top: 176px; position: absolute; color: blue; text-decoration: underline; cursor: pointer;'>View Dashboard</a>";
				$text .="</div></div></div>";
				/*$text = date('d.m.Y h:i a', strtotime($row->add_date))."<br>";
				$text .= '('.ago($row->add_date) . ' ago)<br>';
				$text .= $row->speed." KM<br>";
				$text .= $row->assets_name.' ('.$row->device_id.')<br>';
				if($row->address != ""){
					$text .= $row->address."<br><br>";
				}*/
				$html[] = $text;
				$title[] = $row->assets_name;
				$beforeTime[] = intval(($row->beforeTime)/60);
			}
		}
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		$data['speed'] = $speed;
		$data['title'] = $title;
		$data['icon_path'] = $icon_path;
		$data['beforeTime'] = $beforeTime;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	
	function save_user()
	{
		//die(uri_assoc("cmd"));
		$this->load->model('home_model');
		//die(PRINT_R($_REQUEST));
		if($_REQUEST['u_id']!="")
		{
			$res = $this->home_model->editUser();	
		}
		else{
			if(uri_assoc("cmd") == "add")
				$res = $this->home_model->addUser();	
			else
				$res = $this->home_model->updateUser();	
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
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
			if(count($rows)) {
				foreach ($rows as $row) {
					$combo_s .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
				}
			}
		
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
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
		
		$this->load->model('home_model');
		$res = $this->home_model->addToGroup();	
		$responce['result'] = $res['result'];
		$responce['id'] = $res['insert_id'];
		$responce['msg'] = $res['msg'];
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	
	//assets dashboard
	function assets_dash()
	{		
		$this->load->model('home_model');
		$this->load->library('GMap');
		$this->gmap->GoogleMapAPI();
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['onload'] = $this->gmap->printOnLoad();
		
		$row = $this->home_model->current_location();	
		if($row->num_rows())
		{
		$row=$row->row();
		$text = date('d.m.Y h:i a', strtotime($row->add_date))."<br>";
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
		$data['date'] = date('d.m.Y h:i a', strtotime($row->add_date));
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
		$this->load->model('home_model');
		$row = $this->home_model->get_name(uri_assoc("id"));	
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
		$this->load->model('home_model');
		$row = $this->home_model->assets_det();	
		$result = "<table align='center' width='90%' class='assets_det_tbl'>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Asset Name')."</td><td align='left'>".$row->assets_name."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Device')."</td><td align='left'>".$row->device_id."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Sim Number')."</td><td align='left'>".$row->sim_number."</td></tr>";
		$result .= "<tr><td valign='top'>".$this->lang->line('Driver Name')."</td><td align='left'>".$row->driver_name."</td></tr>";
		//$result .= "<tr><td valign='top'>Icon</td><td align='left' style='float:left;'><img src='".base_url()."assets/marker-images/".$row->icon_path."' border='0'></td></tr>";
		$result .= "</table>";
		//echo $result;
		$this->output->set_output($result);
	}
	function get_distance()
	{		
		$this->load->model('home_model');
		$row = $this->home_model->distance_today();
		
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
		$this->load->model('home_model');
		$row = $this->home_model->current_speed();	
		//echo $row->speed;
		$this->output->set_output($row->speed);
	}
	function assets_location(){
		$this->load->model('home_model');
		$rowss = $this->home_model->current_location();
		$rows = $rowss->result();
		$row = $rows[0];
		$text = date('d.m.Y h:i a', strtotime($row->add_date))."<br>";
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
		$data['date'] = date('d.m.Y h:i a', strtotime($row->add_date));
		
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

		$data['before'] = "$difference $periods[$j]";
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function addLandmark(){
		$this->load->model('home_model');
		$res = $this->home_model->addLandmark();
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
	
	function get_city(){
		$this->load->model('home_model');
		$data['id'] = uri_assoc('id');
		//echo $this->home_model->get_city($data);
		$this->output->set_output($this->home_model->get_city($data));
	}
	function get_state(){
		$this->load->model('home_model');
		$data['id'] = uri_assoc('id');
		//echo $this->home_model->get_state($data);
		$this->output->set_output($this->home_model->get_state($data));
	}
	function get_all_country(){
		$this->load->model('home_model');
		//echo $this->home_model->get_all_country();
		$this->output->set_output($this->home_model->get_all_country());
	}
	function popup_request(){
		$data['header'] =$_POST['header'];
		$data['string'] =$_POST['data'];
		$data['link'] =$_POST['link'];
		$data['type'] =$_POST['type'];
		$this->load->model('home_model');
		//echo $this->home_model->popup_request($data);
		$this->output->set_output($this->home_model->popup_request($data));
	}
	function deleteRoute(){
		$this->load->model('home_model');
		$res = $this->home_model->delete_route();
		//die('Record Deleted Successfully');
		$this->output->set_output('Record Deleted Successfully');
	}
	function setLanguage(){
		$lang = $_POST['lang'];
		$array_items = array('language' => $lang);
		$this->load->model('home_model');
		$res = $this->home_model->set_language($array_items);
		$this->session->set_userdata($array_items);
		//die("done");
		$this->output->set_output("done");
	}
	function get_usrs()
	{	
	$this->load->model('home_model');
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
		$rows = $this->home_model->get_group_assets_detail($grp_id);
		$assets_old_ids=explode(",",$rows[0]['assets']);
		$assets_id_arr=explode(",",$assets_ids);
		$tot = array_unique(array_merge($assets_id_arr,$assets_old_ids));
		$deviceOpt = "";
		$rows = $this->home_model->get_devices($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
		$rows = $this->home_model->get_subuser_assets_detail($usr_id);
		$assets_old_ids=explode(",",$rows[0]['assets_ids']);
		$assets_id_arr=explode(",",$assets_ids);
		$tot = array_unique(array_merge($assets_id_arr,$assets_old_ids));
		$deviceOpt = "";
		$rows = $this->home_model->get_devices($this->session->userdata('user_id'));
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
		$this->load->model('home_model');
		$rows = $this->home_model->get_subuser_detail(uri_assoc('uid'));
		$data=array();
		$data['row']=$rows[0];
		$data['row']['from_date']=date($date_format.' '.$time_format,strtotime($data['row']['from_date']));
		$data['row']['to_date']=date($date_format.' '.$time_format,strtotime($data['row']['to_date']));
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function get_group_detail()
	{	
		$this->load->model('home_model');
		$rows = $this->home_model->get_group_nm(uri_assoc('uid'));
		$data=array();
		$data['row']=$rows[0];
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function saveDist()
	{
		$this->load->model('home_model');
		$insert = $this->home_model->save_dist($this->session->userdata('user_id'));
		//echo "Data Saved";
		$this->output->set_output("Data Saved");
	}
	function getDist()
	{
		$this->load->model('home_model');
		
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
				
		$rows = $this->home_model->getCoord($this->session->userdata('user_id'));
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
		
		
		$this->load->model('home_model');
		$coords = array();
		$data = array();
		$deviceOpt = array();
	//	echo $this->session->userdata('user_id')
		$rows = $this->home_model->get_devices($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$deviceOpt[] = $row->assets_name;
			}
		}
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
		$groupOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$groupOpt .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
			}
		}
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
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
		$data['main_menu'] = $this->home_model->get_main_menu();
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
		$rows = $this->home_model->getToolTips(uri_assoc('id'),$this->session->userdata('user_id'));
	}
	
	function filterAddressbook(){
		
		$this->load->model('home_model');
		
		$rows = $this->home_model->addressbook_opt();
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
		$this->load->view('home_js');
	}
	function image_open(){
		$this->load->model('home_model');
		$coords = array();
		$res = $this->home_model->imageViewer(1);
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
	

		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
		
		$data['img_assets_id'] = uri_assoc('id');
		
		$data['coords'] = $coords;
		$data['totalPage'] = $totalPage;
		$data['page'] = $page;
		$data['totalRecords'] = $totalRecords;
		$data['limit'] = $limit;
				
		$this->load->view('imageViewer',$data);		
	}
	function navigationImages(){
		$this->load->model('home_model');
		$coords = array();
		$page=uri_assoc('page');
		$time=uri_assoc('time');
		$res = $this->home_model->imageViewer($page);		
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
	

		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
		
		$img_assets_id = uri_assoc('id');
		
		$html="<table><tr>";
		if(count($coords) > 0) {
		$cntr=0;
			foreach ($coords as $coord) {
		
			if($cntr!=0 && $cntr%4==0){
				$html.="</tr><tr><td class='td_padding' align='center'>";
				$html.='<a class="fancybox" rel="gallery1" href="'. base_url(). 'assets/captured/'. $coord->captured_image .'" title="'.date('d.m.Y h:i A', strtotime($coord->add_date)) .'"><img src="'. base_url() .'assets/captured/'.$coord->captured_image.'" width="200"/></a><br />'.date('d.m.Y h:i A', strtotime($coord->add_date));
				$html.="<td>";
			}else{
				$html.="<td class='td_padding' align='center'>";
				$html.='<a class="fancybox" rel="gallery1" href="'. base_url(). 'assets/captured/'. $coord->captured_image .'" title="'.date('d.m.Y h:i A', strtotime($coord->add_date)) .'"><img src="'. base_url() .'assets/captured/'.$coord->captured_image.'" width="200"/></a><br />'.date('d.m.Y h:i A', strtotime($coord->add_date));
				$html.="<td>";
			}
			$cntr++;
			}
		}
		else
		{	
			//echo "<td>No Data Found</td>";
			//$this->output->set_output("<td>No Data Found</td>");
			//$this->output->set_output("<td>No Data Found</td>");
			$html.="<td>No Data Found</td>";
			
		}
	$html.="</tr></table>";
	
	//echo $html;
		
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
			//$this->output->set_output($out);
			$html.=$out;
		}
		$this->output->set_output($html);
	}
}
?>