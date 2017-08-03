<?php
class Allpoints extends Admin_Controller {

	function __construct() {
	
		parent::__construct(TRUE);

		$this->load->model('allpoints_model','',TRUE);
	}
	
	function index()
	{
		$this->load->view('allpoints');
	}
	function loadData($cmd='false'){
		
		$data = $this->allpoints_model->getAllData($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$lat = $row->lati;
			$lng = $row->longi;
			$html = date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date));
			$html .= "<br>".$row->speed." KM";
			$html .= "<br>".$row->address;
			$row->actions = "<a href='#' onclick='viewLocationAllpoint(\"$lat\", \"$lng\", \"$html\")'> <img src='".base_url()."assets/marker-images/mini-RED-BLANK.png'></a>";
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function trackOnMap()
	{
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');  
		$rows = $this->allpoints_model->get_all_locations();
		$lat = array();
		$lng = array();
		$html = array();
		$ignition_status = array();
		$count=0;
		$DistanceVal=0;
		if(sizeof($rows)>1)
		{
			$DistanceVal=floatval(($rows[sizeof($rows)-1]['odometer']-$rows[0]['odometer'])/1000);
		}
		for($i=0;$i<sizeof($rows)-1;$i++)
		{
		
				$lat[] = $rows[$i]['lati'];
				$lng[] = $rows[$i]['longi'];
				$text = 'Date : '.date($date_format.' '.$time_format, strtotime($rows[$i]['add_date']))."<br>";
				$text .= 'Speed : '.$rows[$i]['speed']."<br>";
				//$text .= 'Lat : '.$row->lati.'<br>';
				//$text .= 'Lng : '.$row->longi.'<br>';
				$text .= 'Address : '.$rows[$i]['address'].'<br>';
				if($this->session->userdata('show_map_inspection_button')==1){
					$text .="<span style=\"display:block\"><a href=\"#\" onClick=\"saveInspection(".$rows[$i]['id'].");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
				}
				$html[] = $text;
				$ignition_status[]=1;
		}
		$lat2 = '';
		$lng2 = '';
		$distance = 0;
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		$data['distance'] = $DistanceVal;
		$data['ignition_status'] = $ignition_status;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));		
	}
}