<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Failed_login extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('failed_login_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'failed_login' );
	}
	function loadData(){
		
		$data = $this->failed_login_model->getAllData(); 
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
