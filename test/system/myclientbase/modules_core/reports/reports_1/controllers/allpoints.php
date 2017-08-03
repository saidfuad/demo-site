<?php
class Allpoints extends Admin_Controller {

	function __construct() {
	
		parent::__construct(TRUE);

		$this->load->model('home/home_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	
	function index()
	{
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
		$group1 = "<option value=''>".$this->lang->line("Please Select")."</option>";
		if(count($rows)) {
			foreach ($rows as $row) {
				$group1 .= "<option value='".$row->id."'>".$row->group_name."</option>";
			}
		}

		$data['group1'] = $group1;
		$this->load->view('allpoints', $data);
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
			$html .= "<br>".$row->speed." ".$this->lang->line("Km/H");
			$html .= "<br>".$row->address;
			$row->actions = "<a href='#' onclick='viewLocationAllpoint(\"$lat\", \"$lng\", \"$html\")'> <img src='".base_url()."assets/marker-images/mini-RED-BLANK.png'></a>";
			if($row->speed > 0){
				$row->status = $this->lang->line("Running");
			}else if($row->ignition == 0){
				$row->status = $this->lang->line("Parked");
			}else if($row->ignition == 1){
				$row->status = $this->lang->line("Idle");
			}
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

		$sdate = $this->input->post('start_date');
		$edate = $this->input->post('end_date');
		$minutes = round(abs(strtotime($edate) - strtotime($sdate)) / 60,2);		
		$d = floor ($minutes / 1440) * 60;
		$hour_diff = $d + floor (($minutes - $d * 1440) / 60);
		
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
				$text = date($date_format.' '.$time_format, strtotime($rows[$i]['add_date'])).", ";
				$text .= $rows[$i]['speed']." ".$this->lang->line("Km/H");
				
				if($rows[$i]['speed'] > 0){
					$status = 'Running';
				}else if($rows[$i]['ignition'] == 0){
					$status = 'Parked';
				}else if($rows[$i]['ignition'] == 1){
					$status = 'Idle';
				}
				// $text .= $status."<br>,";
				
				if($this->session->userdata('show_map_inspection_button')==1){
					$text .="<span><a href=\"#\" onClick=\"saveInspection(".$rows[$i]['id'].");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
				}
				$text .= '<br>'.$rows[$i]['address'];
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
		$data['interval'] = $hour_diff;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));		
	}
}