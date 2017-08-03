<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Route_out_log extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('route_out_log_model','',TRUE);
	}
	function index() 
	{
		$this->load->view( 'route_out_log' );
	}
	function view_map(){
		//	$data = $this->route_out_log_model->get_map_data();
		$this->load->model('route_out_log_model');
		$rows = $this->route_out_log_model->get_map_data();
		$data = array();
		
		if(count($rows)) {
			foreach ($rows as $row) {
				$data['lat'] = floatval($row->lat);
				$data['lng'] = floatval($row->lng);
				$data['date_time'] =  $row->date_time;
	  		    $data['device_name'] =  $row->device_name;
			    $data['name'] =  $row->name;
				$data['distance'] =  $row->distance;
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
		$data = $this->route_out_log_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;
		foreach($data['result'] as $row){
			$row->distance = $row->distance." ".$row->distance_unit;
			$row->on_route = ($row->on_route == 0) ? "Out" : "In";
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
?>