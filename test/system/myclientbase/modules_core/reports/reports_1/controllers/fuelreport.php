<?php
class Fuelreport extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('fuelreport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	function index()
	{
		//$data['device'] = $this->allpoints_model->prepareCombo();
		$this->load->view('fuelreport');
	}
	function loaddata($cmd='false')
	{
		$data = $this->fuelreport_model->get_data($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		
		foreach($data['result'] as $row) {
			$row->start_km = round($row->first_reading/1000, 2);
			$row->end_km = round($row->current_reading/1000, 2);
			$row->km = $row->distance;
			$row->fuel_used = $row->fuel_used;
			$row->mileage = round($row->distance/$row->fuel_used, 2);
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}	
}
?>