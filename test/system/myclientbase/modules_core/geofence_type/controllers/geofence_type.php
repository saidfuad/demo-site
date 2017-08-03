<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class geofence_type extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('geofence_type_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}	
	function index()
	{
		$this->load->view( 'geofence_type' );
	}
	function loadData(){		
		$data = $this->geofence_type_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		echo json_encode($responce);
	}
	function deleteData(){
		
		echo $this->geofence_type_model->delete_geofence_type(); 
	}
	
	function form() {
	
		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			$this->load->view('form');
		}
		else {
			$formdata = $this->form_model->db_array();
			
			$formdata['add_date'] = date('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('user_id');
			
			if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->geofence_type_model->save($formdata, uri_assoc('id'));
			}
			
		}
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}
	function check_duplicates()
	{
		$type=uri_assoc("type");
		$id=uri_assoc("id");
		if(!$this->geofence_type_model->checkUserDuplicate($username,$id))
		{
			echo "This Geofence Type Taken, Please Choose Unique Geofence Type.";
		}
	}
}
?>