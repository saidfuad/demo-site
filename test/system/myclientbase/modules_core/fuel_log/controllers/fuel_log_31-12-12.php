<?php
class Fuel_log extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('fuel_log_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->fuel_log_model->get_stopdata($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		$prev_fuel_reading = '';
		$fuel_status = $this->input->get('fuel_status');
		if($fuel_status == 1){
			foreach($data['result'] as $row) {
				$next_fuel_reading = $row->fuel_reading;
				if(isset($prev_fuel_reading) && ($next_fuel_reading - $prev_fuel_reading) > 300){
					$row->fuel_reading = '<font color="green">'.$row->fuel_reading.'</font>';
					$row->assets_name = '<font color="green">'.$row->assets_name.'</font>';
					$row->fuel_percent = '<font color="green">'.$row->fuel_percent.'</font>';
					$responce->rows[$i] = $row;
					$i++;
				}
				$prev_fuel_reading = $next_fuel_reading;
				
			}
		}else{
			foreach($data['result'] as $row) {
				$next_fuel_reading = $row->fuel_reading;
				if(isset($prev_fuel_reading) && ($next_fuel_reading - $prev_fuel_reading) > 300){
					$row->fuel_reading = '<font color="green">'.$row->fuel_reading.'</font>';
					$row->assets_name = '<font color="green">'.$row->assets_name.'</font>';
					$row->fuel_percent = '<font color="green">'.$row->fuel_percent.'</font>';
				}
				if(!isset($prev_fuel_reading)){
					$responce->rows[$i] = $row;
					$i++;
				}else{
					if($prev_fuel_reading != $next_fuel_reading){
						$responce->rows[$i] = $row;
						$i++;
					}
				}
				$prev_fuel_reading = $next_fuel_reading;				
			}
		}
		$responce->records = $i;
		echo json_encode($responce);
	}
	function index()
	{
		$this->load->view('fuel_log');
	}
	function view_map(){
		$device=uri_assoc('asset');
		$this->load->model('fuel_log_model');
		$rows = $this->fuel_log_model->get_map_data();
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