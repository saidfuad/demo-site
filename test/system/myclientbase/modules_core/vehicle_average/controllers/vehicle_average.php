<?php
class Vehicle_average extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('vehicle_average_model','',TRUE);
	}
	
	function loadData($cmd='false')
	{
		$data = $this->vehicle_average_model->get_data($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;  
		$km = 0;
		$percentage = 0;
		$liters = 0;
		foreach($data['result'] as $row1) { 
			
			$row1['id'] = $i+1;
			$row1['fuel_liters'] = intval($row1['fuel_litters']);
			$row1['km_run'] = ceil($row1['km_run']);
			$row1['average'] = number_format($row1['km_run']/$row1['fuel_liters'], 2);
			
			$responce->rows[$i] = $row1;
			$i++; 
		}
		$this->output->set_output(json_encode($responce));
	}
	function index()
	{
		$this->load->view('vehicle_average');
	}
	function view_map(){
		$device=uri_assoc('asset');
		$this->load->model('vehicle_average_model');
		$rows = $this->vehicle_average_model->get_map_data();
		$data = array();
		$stp_html="";
		if(count($rows)) {
			foreach ($rows as $row) {
				$data['lat'] = floatval($row->lat);
				$data['lng'] = floatval($row->lng);
				
				$ignition_off_date = date($this->session->userdata('date_format'), strtotime($row->ignition_off));
				$ignition_off_time = date($this->session->userdata('time_format'), strtotime($row->ignition_off));
				
				$ignition_on_date = date($this->session->userdata('date_format'), strtotime($row->ignition_on));
				$ignition_on_time = date($this->session->userdata('time_format'), strtotime($row->ignition_on));
				
				$stp_html .= 'Device : '.$device. "<br>";
				$stp_html .= 'Address : '.$row->address. "<br>";
				$stp_html .= 'Stop From : '.$ignition_off_date.' '.$ignition_off_time. "<br>";
				$stp_html .= 'Stop To : '.$ignition_on_date.' '.$ignition_on_time. "<br>";
				$stp_html .= 'Stop Duration : '.$row->duration. "<br>";
				$data['html'] = $stp_html;
			} 
		}
		else{
			die("No data Found");
		}
		$this->load->view('fuel_log_view_file',$data);
	}
	
}
?>