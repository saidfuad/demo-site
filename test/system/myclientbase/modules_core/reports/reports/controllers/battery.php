<?php
class Battery extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('battery_model','',TRUE);
	}
	
	function index()
	{
		$this->load->view('battery');
	}
	function loadData($cmd='false'){
		
		$data = $this->battery_model->getAllData($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$row->in_batt = round($row->in_batt/1000, 2)." Volt";
			$row->ext_batt_volt = round($row->ext_batt_volt/100, 2)." Volt";
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
		
	}
}
?>