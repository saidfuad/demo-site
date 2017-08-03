<?php
class Stopreportignitionoff extends Admin_Controller {
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('stopreport_ignitionOff_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->stopreport_ignitionOff_model->get_stopdata($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			if($row->duration == ""){
				$to_time = strtotime($row->ignition_off);
				$from_time = strtotime($row->now);
				
			
				
				//sanjaysinh jadeja
				$diff=strtotime($row->now)-strtotime($row->ignition_off); 
				
			
				$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day 
				$time_taken = '';
				// days 
				$days=floor($temp); $temp=24*($temp-$days);
				if($days > 0) $time_taken .= "$days Day ";
				// hours 
				$hours=floor($temp); $temp=60*($temp-$hours);
				if($hours > 0) $time_taken .= "$hours Hours ";
				// minutes 
				$minutes=floor($temp); $temp=60*($temp-$minutes); 
				if($minutes > 0) $time_taken .= "$minutes Min ";
				// seconds 
				$seconds=floor($temp);
				if($time_taken == "")	$time_taken = $seconds." Second";
				
				$row->duration = $time_taken;
			}else{
			
				//sanjaysinh jadeja
				$diff=strtotime($row->ignition_on)-strtotime($row->ignition_off); 
				
				// immediately convert to days 
				$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day 
				$time_taken = '';
				// days 
				$days=floor($temp); $temp=24*($temp-$days);
				if($days > 0) $time_taken .= "$days Day ";
				// hours 
				$hours=floor($temp); $temp=60*($temp-$hours);
				if($hours > 0) $time_taken .= "$hours Hours ";
				// minutes 
				$minutes=floor($temp); $temp=60*($temp-$minutes); 
				if($minutes > 0) $time_taken .= "$minutes Min ";
				// seconds 
				$seconds=floor($temp);
				if($time_taken == "")	$time_taken = $seconds." Second";
				
				$row->duration = $time_taken;
			}
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	
	function index()
	{
		
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('stopreport_ignitionOff', $responce);
	}
	
	
}
?>