<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Commercial extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('commercial_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'commercial' );
	} 
	function loadData(){
		$data = $this->commercial_model->getAllData();
		$responce->page = $data['page'];
		$responce->count_pay = $data['count_pay'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$responce->userdata['sms_text'] = "Total Unpaid Charges"; 
		$responce->userdata['payment_status'] = $data['count_pay'];
		
		$i=0;  
		foreach($data['result'] as $row) {  
			$row->mobile = $row->mobile."(".$row->first_name." ".$row->last_name.")";
			if($row->payment_status == 0)
				$row->payment_status = "Unpaid";
			else
				$row->payment_status = "Paid";
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