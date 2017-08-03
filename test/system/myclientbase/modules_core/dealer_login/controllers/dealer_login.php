<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Dealer_login extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('dealer_login_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'dealer_login' );
	}
	function view_map(){
		//echo $this->dealer_login_model->get_map_data(); 
		$this->load->model('dealer_login_model');
		$rows = $this->dealer_login_model->get_map_data();
		$data = array();
		
		if(count($rows)) {
			foreach ($rows as $row) {
				$data['lat'] = floatval($row->latitude);
				$data['lng'] = floatval($row->longitude);
				$data['last_login_time'] =  $row->last_login_time;
	  		    $data['last_logout_time'] =  $row->last_logout_time;
			    $data['ip_address'] =  $row->ip_address;
				$data['duration_of_stay'] =  $row->duration_of_stay;
			}
		}
		else{
			//die("No data Found");
			$this->output->set_output("No data Found");
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
		
		$data = $this->dealer_login_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		
		$i=0;  
		foreach($data['result'] as $row) {  
			$row->duration_of_stay = number_format($row->duration_of_stay/60,2);
			$responce->rows[$i] = $row;
			$i++; 
		} 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
		$this->output->set_output(json_encode($responce));
	} 
	// function export(){
		
		// $this->load->plugin('to_excel'); 
		// $this->form_model->export();
	// }	 
}