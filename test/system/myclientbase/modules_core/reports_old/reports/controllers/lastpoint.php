<?php
class Lastpoint extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);

		//$this->_post_handler();
		$this->load->model('lastpoint_model','',TRUE);
	}
	
	function index()
	{
		/*$this->load->model('home/home_model');
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
		}*/
		//$this->form_model->icon_id = $iconOpt;
		$data['device'] = $this->lastpoint_model->prepareCombo();
		/*$data['running_1'] = $running;
		$data['parked_1'] = $parked;
		$data['out_of_network_1'] = $out_of_network;
		$data['device_fault_1'] = $device_fault;
		$data['total_1'] = $total;*/
		$this->load->view('lastpoint',$data);
	}
	function loadData($cmd='false'){

		$this->load->model('home/home_model');
		$stopArr = array();
		$reports = $_REQUEST['report'];
		
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');  
	 
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
			if($rptsub == "z-"){
				$us_zr = str_replace($rptsub, "", $report);
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
		
		if($us_zr != ""){
			$this->db->select("polyname", FALSE);
			$this->db->where('polyid', $us_zr);
			$this->db->limit(1);
			$query = $this->db->get('landmark_areas');			
			$rows = $query->result();
			$this->db->last_query();
			$us_zone = '';
			foreach ($rows as $key => $row) {
				$us_zone = $row->polyname;
			}

			if($us_zone!="")
				$gsub .= " AND lm.current_zone = '".addslashes($us_zone)."'";
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
			$gsub .= " AND am.assets_owner = '".intval($us_ow)."'";
		}
		
		if(trim($us_dv) != '') {
			$gsub .= " AND am.assets_division = '".intval($us_dv)."'";
		}
		
		$rows = $this->home_model->stop_duration($user);

		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$minutes = $row->stop_from;
				
				$d = floor ($minutes / 1440);
				$h = floor (($minutes - $d * 1440) / 60);
				$m = $minutes - ($d * 1440) - ($h * 60);
				$stop_time = '';
				if($d > 0)
					$stop_time .= $d." ".$this->lang->line("Day")." ";
				if($h > 0)
					$stop_time .= $h." ".$this->lang->line("Hour")." ";
				if($m > 0)
					$stop_time .= intval($m)." ".$this->lang->line("Min");
				
				$stopArr[$row->device_id] = $stop_time;
			}
		}
		$data = $this->lastpoint_model->get_lastpoints($cmd);
		//$responce->sql = $data['sql'];
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;
		foreach($data['result'] as $row) {
			if (array_key_exists($row->assets_id, $stopArr)) {
				$row->stop_from = $stopArr[$row->assets_id];
			}
			
			$image_type = ''; // base_url(). "assets/";
			if($row->assets_category_id == 1 || $row->assets_category_id == "" || $row->assets_category_id == 0 || $row->assets_category_id == 13){
				$image_type .= "truck.png";
			}else if($row->assets_category_id == 2){
				$image_type .= "car.png";
			}
			else if($row->assets_category_id == 3){
				$image_type .= "bus.png";
			}
			else if($row->assets_category_id == 4){
				$image_type .= "mobile.png";
			}
			else if($row->assets_category_id == 5){
				$image_type .= "bike.png";
			}
			else if($row->assets_category_id == 6){
				$image_type .= "altenator.png";
			}
			else if($row->assets_category_id == 7 || $row->assets_category_id == 8){
				$image_type .= "man.png";
			}
			else if($row->assets_category_id == 9){
				$image_type .= "stacker.png";
			}
			else if($row->assets_category_id == 10){
				$image_type .= "loader.png";
			}
			else if($row->assets_category_id == 11){
				$image_type .= "locomotive.png";
			}
			else if($row->assets_category_id == 12){
				$image_type .= "generator.png";
			}
			else if($row->assets_category_id == 13){
				$image_type .= "maintenance.png";
			}
			else if($row->assets_category_id == 14){
				$image_type .= "motor.png";
			}
			else if($row->assets_category_id == 15){
				$image_type .= "bobcat.png";
			}
			else if($row->assets_category_id == 16){
				$image_type .= "tractor.png";
			}
			else if($row->assets_category_id == 17){
				$image_type .= "car1.png";
			}
			else if($row->assets_category_id == 18){
				$image_type = "satellite.png";
			}
			else if($row->assets_category_id == 21){
				$image_type = "stacker.png";
			}
			else{
				$image_type .= "truck.png";
			}
			
			$row->maker_image = $image_type;
			if($row->add_date!="")
				$row->received_time = ago($row->add_date) . ' '.$this->lang->line("ago");
			else
				$row->received_time = $this->lang->line("No Data");
			$minutes_before = ($row->beforeTime);
			
			$text  = "<b>$row->assets_name (".$row->assets_friendly_nm.") ";
			
			if($this->session->userdata('usertype_id')!=3){
				$text.=" (".$row->device_id.")";
			}
			
			$text .= "</b><br>";
			$text .= $row->received_time . ", ".date($date_format." ".$time_format,strtotime($row->add_date))."<br>";
			if($row->ignition == 0)
				$ignition = $this->lang->line("OFF");
			else 
				$ignition = $this->lang->line("ON");
			$text .=$this->lang->line("Ignition").": ".$ignition." , ".$this->lang->line("Speed").": ".$row->speed." ".$this->lang->line("KM")."<br>";
			
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
			$text .= $this->lang->line($status)."<br>";

			if($this->session->userdata('show_map_driver_detail_window')==1){
			
				if($row->driver_name!="" || $row->driver_name!=null) 
				$text .=$this->lang->line("Driver Name").": ".$row->driver_name."<br>"; 
			
				if($row->driver_mobile!="" || $row->driver_mobile!=null) 
				$text .=$this->lang->line("Driver Mob.").":".$row->driver_mobile."<br>"; 
			}			
			
			$row->assets_friendly_name = $row->assets_name .", ". date($date_format." ".$time_format,strtotime($row->add_date));
//			$row->assets_name = "$row->assets_name ($row->assets_friendly_nm)";
			$row->assets_name = '<a style="color:inherit;" href="javascript:loadAssetsDash_tt(\''.$row->assets_id.'\', \''.$row->assets_name.'\')">'.$row->assets_name.' ('.$row->assets_friendly_nm.')</a>';
			$row->maker_text  = "<div style='text-align:left;'>" . $text . "</div>";
			$row->ast_id 	  = $row->assets_id;
			$row->status_img  = $status_img;
			$row->dev_status  = $status;
			$row->direction   = $row->angle_dir;
			
			$responce->rows[$i] = $row;
			$i++;
		}

		$rows = $this->home_model->getAssetsStatus($user, $gsub);
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
	
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$idle .= $row[0]['Idle'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}
		
		$responce->userdata->running =$running;
		$responce->userdata->parked =$parked;
		$responce->userdata->out_of_network =$out_of_network;
		$responce->userdata->device_fault =$device_fault;
		$responce->userdata->total =$total;

		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){		
		$this->output->set_output($this->lastpoint_model->delete_lastpoint());
	}
}
?>