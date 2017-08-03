<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Landmark_log extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('landmark_log_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'landmark_log' );
	}
	function view_map(){
		//$data =  $this->landmark_log_model->get_map_data(); 
		$this->load->model('landmark_log_model');
		$rows = $this->landmark_log_model->get_map_data();
		$data = array();
		
		if(count($rows)) {
			foreach ($rows as $row) {
				$data['lat'] = floatval($row->lat);
				$data['lng'] = floatval($row->lng);
				$data['date_time'] =  $row->date_time;
	  		    $data['landmark_name'] =  $row->landmark_name;
			    $data['device_name'] =  $row->device_name;
				$data['distance'] =  $row->distance;
			}
		}
		else{
			$this->output->set_output("No data Found");
			//die("No data Found");
			die();
		}
		
		$this->load->library('GMap');
		$this->gmap->GoogleMapAPI();
		$this->gmap->setMapType('map');
		$this->gmap->setCenterCoords($data['lat'], $data['lng']);
		$this->gmap->setWidth('100%');
		$this->gmap->setHeight('90%');
		$this->gmap->setZoomLevel('13');
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$get = $this->uri->uri_to_assoc();
		//$data["prefix"] = uri_assoc('id');
		
		$this->load->view('view_file', $data);
		 
	}
	function loadData(){
		
		$data = $this->landmark_log_model->getAllData(); 
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
	// function export(){
		
		// $this->load->plugin('to_excel'); 
		// $this->form_model->export();
	// }	 
}