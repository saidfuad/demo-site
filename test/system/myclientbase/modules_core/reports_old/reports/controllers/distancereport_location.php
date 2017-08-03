<?php
class Distancereport_location extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE); 

		$this->load->model('distancereport_location_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{	
		$data = $this->distancereport_location_model->get_distancereport_location($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			
			$responce->rows[$i] = $row;
			$i++;
		}
		$this->output->set_output(json_encode($responce));
	}	
	function index()
	{
		$this->load->view('distancereport_location');
	}
}