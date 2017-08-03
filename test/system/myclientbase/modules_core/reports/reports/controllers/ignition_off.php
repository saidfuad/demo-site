<?php
class Ignition_off extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('ignition_off_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->ignition_off_model->get_stopdata($cmd);
		$responce = new stdClass();
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
		$this->load->view('ignition_off');
	}
	function view_map(){
		$device=uri_assoc('asset');
		$this->load->model('ignition_off_model');
		$rows = $this->ignition_off_model->get_map_data();
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
				
				$stp_html .= "<table><tr><td>".'Device : '."</td><td>".$device. "</tr></td>";
				$stp_html .= "<tr><td>".'Address : '."</td><td>".$row->address. "</tr></td>";
				$stp_html .= "<tr><td>".'Stop From : '."</td><td>".$ignition_off_date.' '.$ignition_off_time. "</tr></td>";
				$stp_html .= "<tr><td>".'Stop To : '."</td><td>".$ignition_on_date.' '.$ignition_on_time. "</tr></td>";
				$stp_html .= "<tr><td>".'Stop Duration : '."</td><td>".$row->duration. "</tr></td></table>";
				$data['html'] = $stp_html;
			} 
		}
		else{
			//die("No data Found");
			$this->output->set_output("No data Found");
		}
		$this->load->view('stopreport_view_file',$data);
	}
	
}
?>