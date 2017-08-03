<?php
class Distance_vehicle_report extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE); 

		$this->load->model('distance_vehicle_report_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->distance_vehicle_report_model->get_distancevehicle_report($cmd); 
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
	
	function index()
	{
		$this->load->view('distance_vehicle_report');
	}
	function viewMap(){
		
		$data1 = $this->distance_vehicle_report_model->get_map_data();
		foreach($data1 as $row){
			$data['coords'] = $row;
		}
		$html =array();
		$html_assets_id =array();
		$iconPath =array();
		$data2 = $this->distance_vehicle_report_model->get_map_data_html($data['coords']['asset_id1'],$data['coords']['asset_id2']);
		$txtTop="";
		$txtTop .="<div style='background-color: lightgreen; text-align: center; border-radius: 7px 7px 7px 7px;'>".$this->lang->line("Before")." ".ago($data['coords']['add_date'])." ".$this->lang->line("ago").", ".$this->lang->line("Dt")."-".date("d.m.Y h:i a",strtotime($data['coords']['add_date']))."</div><span style='display: block ! important; width: 100%; height: 7px;'></span>";
		if(count($data2) > 0) {
			foreach ($data2 as $row) {
	
				$text ="<div style='clear:both;width:270px'>";
				$text .="<div style='float:left'><img src='http://nkcdn.nkonnect.com/track/assets/assets_photo/";
				if($row->assets_image_path!= NULL || $row->assets_image_path!="")
				{
					$text .= $row->assets_image_path."' />";
				}
				else
				{
					$text .= "truck.png' />";
				}
				$text .="</div><div style='float:left;margin-left:10px'><span style='display: inline-block;'> ".$row->assets_name;
				if($row->assets_friendly_nm!="" || $row->assets_friendly_nm!=null)
					$text.=" (".$row->assets_friendly_nm.") ";
			
				$text.=" (".$row->device_id.") </span>";
				$text .="</div>";
				$text .="</div>";
				$text .="<div style='clear:both;width:270px;margin-top:10px;'>";
				$text .="<div style='float:left'><img src='http://nkcdn.nkonnect.com/track/assets/driver_photo/";
				if($row->driver_image!= NULL || $row->driver_image!="")
				{
					$text .= $row->driver_image."' />";
				}
				else
				{
					$text .= "not_available.jpg' />";
				}
				$text .="</div><div style='float:left;margin-left:10px'>";
				$text .="<span style='display: block;'>".$this->lang->line("Driver Name").": ";
				if($row->driver_name!="" || $row->driver_name!=null) 
				$text .= $row->driver_name; 
				else 
				$text .=$this->lang->line("N/A");  
				$text .=" </span>";
				$text .="<span style='display: block;'>".$this->lang->line("Driver Mob.").":";
				if($row->driver_mobile!="" || $row->driver_mobile!=null) 
				$text .= $row->driver_mobile; 
				else 
				$text .=$this->lang->line("N/A");    
				$text .=" </span>";
				$text .="</div>";
				$text .="</div>";
				$html_assets_id[]=$row->id;
				$html[] = $text;
				$iconPath[] = $row->icon_path;
			}
		}
		$this->load->model('live/device_model');
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
		$this->load->model('home/home_model');
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
		$landmarks = array();
		if(count($rows) > 0){
			foreach ($rows as $row) {
				$landmarks[] = $row;
			}
		}
		//die(print_r(html_assets_id));
		$data['landmarks'] = $landmarks;
		$data['coords']['html1'] = $txtTop.$html[0];
		$data['coords']['html_assets_id1'] = $html_assets_id[0];
		$data['coords']['iconPath1'] = $iconPath[0];
		$data['coords']['html2'] = $txtTop.$html[1];
		$data['coords']['html_assets_id2'] =  $html_assets_id[1];
		$data['coords']['iconPath2'] = $iconPath[1];
		//die(print_r($data));
		$this->load->view('distance_vehicle_view_file',$data);		
	}
}
?>