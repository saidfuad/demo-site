<?php
class Rfid extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('rfid_model','',TRUE);
	}
	
	function index()
	{
		$this->load->view('rfid');
	}
	function loadData($cmd='false'){
		$data = $this->rfid_model->getAllData($cmd); 
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
}
?>