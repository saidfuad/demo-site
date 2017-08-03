<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class landmark_distance extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('landmark_distance_model','',TRUE);
	}
	function index()
	{
		$option = $this->landmark_distance_model->get_landmark();
		$data['landmark'] = $option;
		$this->load->view( 'landmark_distance', $data );
	}
	function view_map(){
		//$data =  $this->landmark_distance_model->get_map_data(); 
		$this->load->model('landmark_distance_model');
		$rows = $this->landmark_distance_model->get_map_data();
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
		
		$data = $this->landmark_distance_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;  
		foreach($data['result'] as $row) {  
			$row->distance = round($row->distance, 2);
			$responce->rows[$i] = $row;
			$i++; 
		} 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	} 
}