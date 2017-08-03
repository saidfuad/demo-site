<?php
class Fuel_report extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('fuel_report_model','',TRUE);
	}
	
	function loadData($cmd='false')
	{
		$data = $this->fuel_report_model->get_fueldata($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;  
		$km = 0;
		$percentage = 0;
		$liters = 0;
		foreach($data['result'] as $row) { 
			
			$km += $row->km_run;
			$percentage += $row->fuel_percent;
			$liters += $row->fuel_litters;
			
			if($row->fuel_percent > 0){
				$color="red";
			}else{
				$color="green";
			}
			
			if($row->fuel_litters < 0)
				$row->km_run = 0;
			$row->fuel_percent = "<font color='".$color."'>".abs($row->fuel_percent)." % </font>";
			$row->fuel_liters = "<font color='".$color."'>".abs($row->fuel_litters)." Liter</font>";
			$responce->rows[$i] = $row;
			$i++; 
			
		}
		$responce->userdata['km_run'] = ceil($km);
		$responce->userdata['fuel_liters'] = abs($liters); 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function index()
	{
		$this->load->view('fuel_report');
	}
	function view_map(){
		$device=uri_assoc('asset');
		$this->load->model('fuel_report_model');
		$rows = $this->fuel_report_model->get_map_data();
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