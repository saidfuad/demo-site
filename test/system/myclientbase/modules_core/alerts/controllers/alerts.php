<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class alerts extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('alerts_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'alerts' );
	}
	
	function loadData(){
		
		$data = $this->alerts_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];	
		$responce->sql = $data['sql'];
		
		$i=0;  
		foreach($data['result'] as $row) {  
			//$row->duration_of_stay = number_format($row->duration_of_stay/60,2);
			$responce->rows[$i] = $row;
			$i++; 
		} 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	} 	
}