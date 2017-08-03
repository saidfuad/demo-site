<?php //(defined('BASEPATH')) OR exit('No direct script access allowed');

class Live extends Admin_Controller {
/*
	public function _remap($method, $params = array()) {
		if (method_exists($this, $method))
		{
			
			return call_user_func_array(array($this, $method), $params);
		}
		else {
			$method = 'index';
		}
	}

*/
	function __construct() {
		parent::__construct();
		//$this->load->helper('mcb_date');
	}
/*	public function _remap($method) {
	  $param_offset = 2;
	
	  // Default to index
	  if ( ! method_exists($this, $method))
	  {
		// We need one more param
		$param_offset = 1;
		$method = 'index';
	  }
	
	  // Since all we get is $method, load up everything else in the URI
	  $params = array_slice($this->uri->rsegment_array(), $param_offset);
	
	  // Call the determined method with all params
	  call_user_func_array(array($this, $method), $params);
	}*/
	function index($active_device='')
	{
		/*
		$this->load->model('device_model');
		
		$rows = $this->device_model->get_links();
		
		// $active_device = $this->input->get("active");
		
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
		/*	
		foreach($rows as $device) {
			
			$temp_cords = $this->device_model->get_device_location($device->device_id);
			
			if($temp_cords != false) {
				$coords[] = $temp_cords;
			}
			
		}
		// print_r($coords);
		
		if(count($coords) > 0) {
			foreach ($coords as $coord) {
				$text  = 'Lat : '.$coord[0]->lati."<br>";
				$text .= 'Lng : '.$coord[0]->longi."<br>";
				$text .= 'Date : '.$coord[0]->add_date."<br>";
				$text .= 'Speed : '.$coord[0]->speed."<br>";
				$text .= 'Device : '.$coord[0]->assets_name.' ('.$coord[0]->device_id.')<br>';
				$text .= 'Device : '.$coord[0]->sim_number.'<br>';
				//$this->gmap->addMarkerByCoords(floatval($coord[0]->lati), floatval($coord[0]->longi), $text);
			}
		}
		*-/
		
		$rows = $this->device_model->get_all_last_location($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$coords[] = $row;
			}
		}
		//$coords[] = $rows;
		
		$data['coords'] = $coords;
		if(trim($active_device) != '') {
			$data['active'] = $active_device;
		}else{
			$data['active'] = "";
		}
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['sidebar'] = $this->gmap->printSidebar();
		
		$links = "";
		if(count($rows)) {
			$m = 0;
			foreach ($rows as $row) {
				$links .= '<li><a id="dev_'.$row->device_id.'" name="'.base_url().'index.php/live/device/id/'.$row->device_id.'/window/current" title="'.$row->assets_name.'" href="#" onmouseover="toggleBounce(markersmap['.$m.'])" onmouseout="toggleBounce(markersmap['.$m.'])" class="link">'.$row->assets_name.' ('.$row->device_id.')</a></li>';
				$deviceOpt .= "<option value='".$row->device_id."'>".$row->assets_name." (".$row->device_id.")</option>";
				$m++;
			}
		}
		else {
			$links = '<li><a title="No Assets" href="#" class="link">No Assets</a></li>';
			$deviceOpt .= "<option value=''>No Assets</option>";
		}
		$grows = $this->device_model->get_group($this->session->userdata('user_id'));
		$group = "";
		if(count($grows)) {
			foreach ($grows as $grow) {
				$devices = $grow->assets;
				$group .= '<li><a href="#" onclick="loadDevices('.$grow->id.')">'.$grow->group_name.'</a><ul>';
				
				$gdrows = $this->device_model->get_group_device($devices);
				$m = 0;	
				foreach ($gdrows as $row) {
					
					$group .= '<li><a id="dev_'.$row->device_id.'" name="'.base_url().'index.php/live/device/id/'.$row->device_id.'/window/current" title="'.$row->assets_name.'" href="#" onmouseover="toggleBounce(markersmap['.$m.'])" onmouseout="toggleBounce(markersmap['.$m.'])" class="link">'.$row->assets_name.' ('.$row->device_id.')</a></li>';
					$m++;
				}
				
				$group .= '</ul></li>';
			}
		}
		$data['group'] = $group;
		$data['deviceOpt'] = $deviceOpt;
		$data['links'] = $links;
		
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
			$data['plyDev'][$row->polyid][] = $row->deviceid;
			$data['plyColor'][$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}
		//print_r($data['plyName']);
		//exit;
		if(count($data['plyId']) > 0){
			$data['plyId'] = array_unique($data['plyId']);
			foreach($data['plyId'] as $pid){
				if(count($data['plyDev'][$pid]) > 0){
					$data['plyDev'][$pid] = array_unique($data['plyDev'][$pid]);
				}
			}
		}
		//print_r($data);
		//echo count($data['plyId']);
		//exit;
		$this->load->view('live',$data);*/		
	}
	function loadRoute_live(){
		//$route_id = $_REQUEST['route_ids'];
		//$assets_id = $_REQUEST['assets_id'];
		//$row = $this->device_model->get_landmarks($route_id);
	}
	function deletepoly(){
		
		$this->load->model('device_model');
		$this->device_model->delete_poly();
		exit;
	}
	function history()
	{
		$this->load->model('device_model');
		//$this->load->model('home/home_model');
		$history = $this->device_model->get_history();
		$h_lat = array();
		$h_lng = array();
		$h_html = array();
		$i = 0;
		$txthtm="";
		foreach ($history as $row) {
			$h_lat[] = floatval($row->lati);
			$h_lng[] = floatval($row->longi);
			 $txthtm = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date)).", ".$row->speed." Km/H";
			 if($this->session->userdata('show_map_inspection_button')==1){
				//$txthtm .="<span style=\"display:block\"><a href=\"#\" onClick=\"saveInspection(".$row->id.");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a><a href=\"#\" onClick=\"saveAsWayPoint(".$row->id.");\" style=\"color:blue;float:left\">".$this->lang->line('Save Way Point')."</a></span>";
				$txthtm .= "<span><a href=\"#\" onClick=\"saveInspection(".$row->id.");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
			 }
			$txthtm .= "<br>".$row->address;
			$text1 = "<div style=\"text-align:left;\">".$txthtm."</div>";
			$txthtm = $text1;
			$h_html[] =$txthtm;
			$id = $row->id;
			$i++;
		}
		$data['html'] = $h_html;
		$data['lat'] = $h_lat;
		$data['lng'] = $h_lng;
		$data['id'] = $id;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function device()
	{		
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime; 
		$this->load->model('device_model');
			
		/*$history = $this->device_model->get_history();
		$h_lat = array();
		$h_lng = array();
		foreach ($history as $row) {
			$h_lat[] = floatval($row->lati);
			$h_lng[] = floatval($row->longi);
		}
		*/
		
		$rows = $this->device_model->get_locations();
		//$data = array();
		$text_address="";
		$driver_detail="";
		$dr_nm = array();
		$dr_mo = array();
		$device_id = uri_assoc('id');
				
		if(count($rows)) {
			foreach ($rows as $row) {
			
				$data['lat'] = floatval($row->lati);
				$data['lng'] = floatval($row->longi);
				$data['gsm_lat'] = floatval($row->gsm_leti);
				$data['gsm_lng'] = floatval($row->gsm_longi);
				$data['ignition'] = $row->ignition;
				$data['speed'] = $row->speed;
				$data['angle'] = floatval($row->angle_dir);
				$data["last_id"] = $row->id;
				$data["assets_id"] = $row->assets_id;
				$data["assets_category_id"] = $row->assets_category_id;
				$data["last_datetime"] = strtotime($row->add_date);

				$text = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
				$text_dt = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
				//$text .= '('.ago($row->add_date) . ' ago)<br>';
				$text .= $row->speed." KM";
				$minutes_before = ($row->beforeTime)/60;
				if($minutes_before > 20){
					$point = "RedDot.png";
				}else{
					$point = "green_dot.png";
				}
				$text_address .= ago($row->add_date)." ago";
				$text_address .= ", ".date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'),strtotime($row->add_date));
				$text_address .= ", Speed: ".$row->speed." Km/H";
				if($row->gsm_strength!=""){
						if($row->gsm_strength==99){
							$gsm_strength = "No Network";
						}else if($row->gsm_strength > 24 && $row->gsm_strength < 32){
							$gsm_strength = "FULL";
						}else if($row->gsm_strength > 10 && $row->gsm_strength < 25){
							$gsm_strength = "MEDIUM";
						}else if($row->gsm_strength < 11){
							$gsm_strength = "LOW";
						}
						$gsm_strength = $gsm_strength."(".$row->gsm_strength.")";
					$text_address .= ", GSM Strength: ".$gsm_strength;
				}
				if($row->temperature != ""){
					$tempr=$row->temperature;
					if($tempr<0){
						$tempr="<span style=\'font-size:14px;\'>-</span>".abs($tempr);
					}
					$text .= ", Temp.: " . $tempr."&deg; C<br />";
					$text_address .= ", Temp.: ".$tempr."&deg; C";
				}
				$text .= $row->assets_name.' ('.$row->device_id.')<br>';
				if($row->address != ""){
					$text .= addslashes($row->address)."<br><br>";
					$text_address .= ", <br/>".addslashes($row->address);
				}
				if($row->ext_batt_volt!=""){
					// $text_address .= ", Vehicle Battery : ".round($row->ext_batt_volt/100, 2)." Volt";;
					$text_address .= ", Vehicle Battery : ".number_format($row->ext_batt_volt, 2)." Volt";;
				}
				if($this->session->userdata('show_map_driver_detail_window')==1){
					if($row->driver_name!="" && $row->driver_name!=null){
						$dr_nm=explode(",",$row->driver_name);
						$dr_mo=explode(",",$row->driver_mobile);
						$driver_detail .=$this->lang->line('Drv_Nm').": ";
						for($i=0;$i<count($dr_nm);$i++){
							
							$driver_detail .=$dr_nm[$i];
							if(array_key_exists($i,$dr_mo)){
								$driver_detail .=" (".$dr_mo[$i].")";
							}
							if($i<count($dr_nm)-1){
								$driver_detail .=",<br/>";
							}
						}
						if($driver_detail!=""){
							$text_address.="<br/>".$driver_detail;
						}
					}
				}
				/* // it takes to much time-dharmik
				if($this->session->userdata('show_map_inspection_button')==1){
				$mtime = microtime();
				$mtime = explode(" ",$mtime);
				$mtime = $mtime[1] + $mtime[0];
				$starttime = $mtime; 
				$cntr=0;
					$trackId=$this->device_model->get_track_id($row->device_id,$row->add_date);
					if(count($trackId)==1){
						foreach($trackId as $track){
							$text.="<span style=\"display:block\"><a href=\"#\" onClick=\"saveInspection(".$track->id.");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
							$cntr++;
						}
					}
					$mtime = microtime();
					$mtime = explode(" ",$mtime);
					$mtime = $mtime[1] + $mtime[0];
					$endtime = $mtime;
					$totaltime = ($endtime - $starttime);
					$divLog.= "Loops: ".$totaltime." seconds.(counter):$cntr<br/>";
				}*/
				$data['html_dt'] = $text_dt;
				$data['html_address'] = $text_address;
				
				$text1 = "<div style=\"text-align:left;\">".$text."</div>";
				$text = $text1;
				$data['html'] = $text;
				$data['icon_path'] = $row->icon_path;
				$data['TabImage'] = $point;
				
				
			}
		}
		else{
			//die("No data Found");
			$this->output->set_output("No data Found");
		}
		/*
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords($data['lat'], $data['lng']);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		$data['headerjs'] = $this->gmap->getHeaderJS();
		
		$get = $this->uri->uri_to_assoc();
		*/
	
		$data["prefix"] = uri_assoc('id');
		$trip_distance ="";
	
		if($this->session->userdata('usertype_id')!=3){
			$in_trip=1;
			$row_dist= $this->device_model->getDistanceofTrip(uri_assoc('id'));
			if(count($row_dist)>0){
				foreach($row_dist as $rw){
					$trip_distance.="Distance Travelled: ".$rw->distance_travelled." KM";
				}
			}
		}
		if($trip_distance!=""){
			$data['html_address'] .= "<br/>".$trip_distance;
		}
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = ($endtime - $starttime);
		
		$divLog= "Time Taken to Process: ".$totaltime." seconds.<br/>";
		$data['time_taken']= $divLog;
		
		$this->load->view('device',$data);
		
	}
	function live_js(){
		$data['prefix']=$_REQUEST['prefix'];
		$data['time']=$_REQUEST['time'];
		$data['lat']=$_REQUEST['lat'];
		$data['lng']=$_REQUEST['lng'];
		$data['angle']=$_REQUEST['angle'];
		
		$this->load->view('device_js',$data);
	}
	function loadLandmarks(){
		$this->load->model('device_model');
		$this->load->model('home/home_model');
		$landmarks = array();
		$rows_trip = $this->device_model->getCurrentTrip();
		if(count($rows_trip)>0){
			foreach ($rows_trip as $row) {
				$landmarks[] = $row;
			}
	//		$landmarks = array();
			
		}else{
			
			$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
			if(count($rows) > 0) {
				foreach ($rows as $row) {
					if(is_null($row->icon_path)) $row->icon_path = '';
					$landmarks[] = $row;
				}
			}
		}
		/*Get Dealer's Landmark lat long if usertype == 3*/
		$dealerLandmark_latlng="";
		if($this->session->userdata('usertype_id')==3){
			$dealerLandmark_latlng= $this->device_model->getDealerLandmark(uri_assoc('id'));
		}
		
		$data['landmarks'] = $landmarks;
		//echo $landmarks;
		$data['dealerLandmark_latlng'] = $dealerLandmark_latlng;
		//die(json_encode($data));		
		$this->output->set_output(json_encode($data));		
	}
	function loadRoutes(){
		$this->load->model('device_model');
		$rows = $this->device_model->getCurrentTrip_ID();
		if(count($rows) > 0){
			foreach ($rows as $row){
				$route_id_arr[] = $row['current_trip'];
				$assets_id[] = $row['id'];
			}
			$route_id=implode(",",$route_id_arr);
		}else{
			$route_id=0;
		}
		$data['route_id'] = $route_id;
		$data['assets_id'] = $assets_id;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function loadArea(){
		$this->load->model('device_model');		
		$plyId = array();
		$plyDev = array();
		$plyLat = array();
		$plyLng = array();
		$plyName = array();
		$plyColor = array();
		
		$in_trip=0;
		$route_id_arr = array();
		$trip_distance="";
		//if($this->session->userdata('usertype_id') == 2){
		$plyRows = $this->device_model->get_poly_home();
		foreach ($plyRows as $row) {				
			$plyId[] = $row->polyid;
			$plyLat[$row->polyid][] = $row->lat;
			$plyLng[$row->polyid][] = $row->lng;
			$plyName[$row->polyid][] = $row->polyname;
			$plyDev[$row->polyid][] = $row->assets;
			$plyColor[$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}		
		if(count($plyId) > 0){
			$plyId = array_unique($plyId);
			sort($plyId);
			foreach($plyId as $pid){
				if(count($plyDev[$pid]) > 0){
					$plyDev[$pid] = array_unique($plyDev[$pid]);
					//sort($plyDev);
				}
				if(count($plyName[$pid]) > 0){
					$plyName[$pid] = array_unique($plyName[$pid]);
					//sort($plyName);
				}
			}
		}
		$data['pplyId'] = $plyId;
		$data['pplyDev'] = $plyDev;
		$data['pplyLat'] = $plyLat;
		$data['pplyLng'] = $plyLng;
		$data['pplyName'] = $plyName;
		$data['pplyColor'] = $plyColor;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	
	function loadZone(){
		$this->load->model('device_model');		
		$plyId = array();
		$plyDev = array();
		$plyLat = array();
		$plyLng = array();
		$plyName = array();
		$plyColor = array();
		
		$in_trip=0;
		$route_id_arr = array();
		$trip_distance="";
		//if($this->session->userdata('usertype_id') == 2){
		$plyRows = $this->device_model->get_poly_zone();
		foreach ($plyRows as $row) {				
			$plyId[] = $row->polyid;
			$plyLat[$row->polyid][] = $row->lat;
			$plyLng[$row->polyid][] = $row->lng;
			$plyName[$row->polyid][] = $row->polyname;
			$plyDev[$row->polyid][] = $row->assets;
			$plyColor[$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}		
		if(count($plyId) > 0){
			$plyId = array_unique($plyId);
			sort($plyId);
			foreach($plyId as $pid){
				if(count($plyDev[$pid]) > 0){
					$plyDev[$pid] = array_unique($plyDev[$pid]);
					//sort($plyDev);
				}
				if(count($plyName[$pid]) > 0){
					$plyName[$pid] = array_unique($plyName[$pid]);
					//sort($plyName);
				}
			}
		}
		$data['pplyId'] = $plyId;
		$data['pplyDev'] = $plyDev;
		$data['pplyLat'] = $plyLat;
		$data['pplyLng'] = $plyLng;
		$data['pplyName'] = $plyName;
		$data['pplyColor'] = $plyColor;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function newPoint()
	{
		$this->load->model('device_model', '',TRUE);
		
		$data['view'] = 'enable';
		
		$trip_distance = "";
		if($this->session->userdata('usertype_id')!=3){
			$row_dist= $this->device_model->getDistanceofTrip(uri_assoc('device'));
			if(count($row_dist)>0){
				foreach($row_dist as $rw){
					$trip_distance.="Distance Travelled: ".$rw->distance_travelled." KM";
				}
			}
		}
		
		$ignition = array();
		$row = $this->device_model->vehicle_stop_status();
		if($row->ignition_on == ""){
			$ignition[] = 0;
		}else{
			$ignition[] = 1;
		}

		$rows = $this->device_model->get_new_locations();
		$lat = array();
		$lng = array();
		$gsm_lat = array();
		$gsm_lng = array();
		//$ignition = array();
		$speedArr = array();
		$html = array();
		$html_addr = array();
		$driver_DET = array();
		$dr_nm = array();
		$dr_mo = array();
		$text_address="";
		$driver_detail="";
		
		foreach ($rows as $row) {
            $lat[] = $row->lati;
			$lng[] = $row->longi;
			$gsm_lat[] = $row->gsm_leti;
			$gsm_lng[] = $row->gsm_longi;
			//$ignition[] = $row->ignition;
			$speedArr[] = $row->speed;
			//$text  = 'Lat : '.$row->lati.'<br>';
			//$text .= 'Lng : '.$row->longi.'<br>';
			$text = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
			$data["assets_id"] = $row->assets_id;
			$data['angle'] = floatval($row->angle_dir);
			$minutes_before = ($row->beforeTime)/60;
				if($minutes_before > 20){
					$point = "RedDot.png";
				}else{
					$point = "green_dot.png";
				}
			//$text .= '('.ago($row->add_date) . ' ago)<br>';
			$text_address .= ago($row->add_date)." ago";
			$text_address .= ", ".date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'),strtotime($row->add_date));
			$text_address .= ", Speed: ".$row->speed." Km/H";
			if($row->gsm_strength!=""){
					if($row->gsm_strength==99){
						$gsm_strength = "No Network";
					}else if($row->gsm_strength > 24 && $row->gsm_strength < 32){
						$gsm_strength = "FULL";
					}else if($row->gsm_strength > 10 && $row->gsm_strength < 25){
						$gsm_strength = "MEDIUM";
					}else if($row->gsm_strength < 11){
						$gsm_strength = "LOW";
					}
					$gsm_strength = $gsm_strength."(".$row->gsm_strength.")";
				$text_address .= ", GSM Strength: ".$gsm_strength;
			}
			$text .= $row->speed." Km/H";
			/*if($row->temperature != ""){
				$text .= ", Temp.: " . $row->temperature."&deg; C<br />";
				$text_address .= ", Temp.: ".$row->temperature."&deg; C";
			}*/
			if($row->temperature != ""){
					$tempr=$row->temperature;
					if($tempr<0){
						$tempr="<span style=\'font-size:14px;\'>-</span>".abs($tempr);
					}
					$text .= ", Temp.: " . $tempr."&deg; C<br />";
					$text_address .= ", Temp.: ".$tempr."&deg; C";
				}
			$text .= $row->assets_name.' ('.$row->device_id.')<br>';
			if($row->address != ""){
				$text .= $row->address."<br><br>";
				$text_address .= ", <br/>".$row->address;
			}
			if($row->ext_batt_volt!=""){
				$text_address .= ", Vehicle Battery : ".number_format($row->ext_batt_volt, 2)." Volt";;
				// $text_address .= ", Vehicle Battery : ".round($row->ext_batt_volt/100, 2)." Volt";;
			}
			if($this->session->userdata('show_map_inspection_button')==1){
				$trackId=$this->device_model->get_track_id($row->device_id,$row->add_date);
				if(count($trackId)==1){
					foreach($trackId as $track){
						//$text.="<span style=\"display:block\"><a href=\"#\" onClick=\"saveInspection(".$track->id.");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a><a href=\"#\" onClick=\"saveAsWayPoint(".$row->id.");\" style=\"color:blue;float:left;\">".$this->lang->line('Save Way Point')."</a></span>";
						$text.="<span><a href=\"#\" onClick=\"saveInspection(".$track->id.");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
					}
				}
			}
			if($this->session->userdata('show_map_driver_detail_window')==1){
				if($row->driver_name!="" && $row->driver_name!=null){
					$dr_nm=explode(",",$row->driver_name);
					$dr_mo=explode(",",$row->driver_mobile);
					$driver_detail .=$this->lang->line('Drv_Nm').": ";
					for($i=0;$i<count($dr_nm);$i++){
				
						$driver_detail .=$dr_nm[$i];
						if(array_key_exists($i,$dr_mo)){
							$driver_detail .=" (".$dr_mo[$i].")";
						}
						if($i<count($dr_nm)-1){
							$driver_detail .=",<br/>";
						}
					}
					/*$driver_detail .=$this->lang->line('Drv_Nm').": ".$row->driver_name;
					if($driver_mobile!=""){
					$driver_detail .=."(".$row->driver_mobile.")"
					}*/
					//$driver_detail .=", ".$this->lang->line('Drv_Mob').": ".$row->driver_mobile;
					if($driver_detail!=""){
						$text_address.="<br/>".$driver_detail;
					}
				}
			}
			if($trip_distance!=""){
				$text_address .= "<br/>".$trip_distance;
			}
			$text1 = "<div style=\"text-align:left;\">".$text."</div>";
			$text = $text1;
			$html_addr[] = $text_address;
			//$driver_DET[] = $driver_detail;
			$html[] = $text;
			$data["last_id"] = $row->id;
			$data["last_datetime"] = strtotime($row->add_date);
			$data['TabImage'] = $point;
			
        }
		if(count($lat) == 0){
			$rows = $this->device_model->get_old_locations(uri_assoc('device'));
			$text_address="";
			if(count($rows)) {
				foreach ($rows as $row) {
					//$ignition[] = $row->ignition;
					$speedArr[] = $row->speed;
					$text_address .= ago($row->add_date)." ago";
					$text_address .= ", ".date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'),strtotime($row->add_date));
					$text_address .= ", Speed: ".$row->speed." Km/H";
					if($row->gsm_strength!=""){
							if($row->gsm_strength==99){
								$gsm_strength = "No Network";
							}else if($row->gsm_strength > 24 && $row->gsm_strength < 32){
								$gsm_strength = "FULL";
							}else if($row->gsm_strength > 10 && $row->gsm_strength < 25){
								$gsm_strength = "MEDIUM";
							}else if($row->gsm_strength < 11){
								$gsm_strength = "LOW";
							}
							$gsm_strength = $gsm_strength."(".$row->gsm_strength.")";
						$text_address .= ", GSM Strength: ".$gsm_strength;
					}
					$data["assets_id"] = $row->assets_id;
					$minutes_before = ($row->beforeTime)/60;
						if($minutes_before > 20){
							$point = "RedDot.png";
						}else{
							$point = "green_dot.png";
						}
					if($row->address != ""){
						$text_address .= ", <br/>".$row->address;
					}
					if($row->ext_batt_volt!=""){
						$text_address .= ", Vehicle Battery : ".number_format($row->ext_batt_volt, 2)." Volt";;
						// $text_address .= ", Vehicle Battery : ".round($row->ext_batt_volt/100, 2)." Volt";;
					}
					if($this->session->userdata('show_map_driver_detail_window')==1){
						if($row->driver_name!="" && $row->driver_name!=null){
							$dr_nm=explode(",",$row->driver_name);
							$dr_mo=explode(",",$row->driver_mobile);
							$driver_detail .=$this->lang->line('Drv_Nm').": ";
						for($i=0;$i<count($dr_nm);$i++){
							$driver_detail .=$dr_nm[$i];
							if(array_key_exists($i,$dr_mo)){
								$driver_detail .=" (".$dr_mo[$i].")";
							}
							if($i<count($dr_nm)-1){
								$driver_detail .=",<br/>";
							}
						}
						/*$driver_detail .=$this->lang->line('Drv_Nm').": ".$row->driver_name;
						if($driver_mobile!=""){
						$driver_detail .=."(".$row->driver_mobile.")"
						}*/
						//$driver_detail .=", ".$this->lang->line('Drv_Mob').": ".$row->driver_mobile;
						if($driver_detail!=""){
							$text_address.="<br/>".$driver_detail;
						}
						}
					}
					//$text .= 'Mobile : '.$row->sim_number.'<br>';
					if($trip_distance!=""){
						$text_address .= "<br/>".$trip_distance;
					}
					
					$html_addr = $text_address;
					$data['TabImage'] = $point;
				}
			}
		}
				
		/*==============*/
		$landmark_ids = '';
		if(isset($_POST['route_id']) && $_POST['route_id']!= ""){
			$row = $this->device_model->get_completed_trip_landmark();
			$landmark_ids = $row->landmark_ids;	
			$landmark_ids = explode(",", $landmark_ids);
			$landmark_ids = array_unique($landmark_ids);
			$landmark_ids = implode(",", $landmark_ids);
		}
		$data['completed_landmarks_ids'] = $landmark_ids;				
		/*==============*/
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['gsm_lat'] = $gsm_lat;
		$data['gsm_lng'] = $gsm_lng;
		$data['ignition'] = $ignition;
		$data['speed'] = $speedArr;
		$data['html'] = $html;
		$data['html_address'] = $html_addr;
		//$data['driver_detail'] = $driver_DET;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
		
	function allPoint()
	{
		$this->load->model('device_model');
		
		$coords = array();
		$lat = array();
		$lng = array();
		$html = array();
		$speed = array();
		$rows = $this->device_model->get_all_last_location($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$lat[] = $row->lati;
				$lng[] = $row->longi;
				$speed[] = $row->speed;
				$text = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
				$text .= '('.ago($row->add_date) . ' ago)<br>';
				$text .= $row->speed."<br>";
				$text .= $row->assets_name.' ('.$row->device_id.')<br>';
				if($row->address != ""){
					$text .= $row->address."<br><br>";
				}
				//$text .= 'Mobile : '.$row->sim_number.'<br>';
				$html[] = $text;
			}
		}
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		$data['speed'] = $speed;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	
	function addPoly()
	{
		$this->load->model('device_model', '',TRUE);
		$result = $this->device_model->add_poly();
		//die($result);
		$this->output->set_output($result);
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
	
	function map3()
	{
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI('map3_map');
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$data["prefix"] = "map3_";
		$data["c_lat"]	= 22.296024;
		$data["c_lng"]	= 70.785540;
		
		$this->load->view('map3',$data);
	}
		
	function map_form()
	{
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		/*$arr = array();
		$arr[0]['lat'] = 22.292090;
		$arr[0]['long'] = 70.792210;
		$arr[1]['lat'] = 22.3021545;
		$arr[1]['long'] = 70.782444;
		$arr[2]['lat'] = 22.3121545;
		$arr[2]['long'] = 70.802444;
		
		$this->gmap->addPolylineByCoordsArray($arr,true,'#cc0000',3,50);*/
		
		// you can also use addMarkerByCoords($long,$lat)
		// both marker methods also support $html, $tooltip, $icon_file and
		//$icon_shadow_filename
		$this->gmap->addMarkerByAddress("rajkot, gujarat", "Marker Description");
				
		$this->load->view('map_form');
	}	
	
	function getDevices()
	{
		$this->load->model('device_model');
		
		$lat = array();
		$lng = array();
		$html = array();
		$speed = array();
		$rows = $this->device_model->get_group_location($this->session->userdata('user_id'));
		if(count($rows) > 0) {
			foreach ($rows as $row){
				$lat[] = $row->lati;
				$lng[] = $row->longi;
				$speed[] = $row->speed;
				$text = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."<br>";
				$text .= '('.ago($row->add_date) . ' ago)<br>';
				$text .= $row->speed."<br>";
				$text .= $row->assets_name.' ('.$row->device_id.')<br>';
				if($row->address != ""){
					$text .= $row->address."<br><br>";
				}
				$html[] = $text;
			}
		}
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['text'] = $html;
		$data['speed'] = $speed;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function addToInspection(){
		$this->load->model('device_model');
		$rows = $this->device_model->insertIntoInspection();
		//echo $rows;
		$this->output->set_output($rows);
	}
	function getLandmarksList(){
		$id=uri_assoc("id");
		$this->load->model('device_model');
		$point="";
		$point=$this->device_model->getLatLngTrack($id);
		
		$rows = $this->device_model->getListofLandmarks();
		$opt="";
		
		if(count($rows) > 0) {
			foreach($rows as $row){
				$opt.="<option title='".base_url().$row->icon_path."' value='".$row->id."'>";
				$opt.=$row->name;
				$opt.="</option>";
			}
		}
		else {
			$opt .= '<option title="" value="">No Landmark</option>';
		}
		$data['opt'] = $opt;
		$data['point'] = $point;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
	function insertWaypoint(){
		$landmark1=$_REQUEST["landmark1"];
		$landmark2=$_REQUEST["landmark2"];
		$waypoint=$_REQUEST["waypoint"];
		$waypoint_name=$_REQUEST["waypoint_name"];
		$this->load->model('device_model');
		$rows = $this->device_model->insertIntoWaypoints($waypoint_name,$landmark1,$landmark2,$waypoint);
		//echo $rows;
		$this->output->set_output($rows);
	}
}