<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Provider extends Admin_Controller {
	
	function __construct() {

		parent::__construct(false);
		$this->load->model('provider_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}
	
	function index()
	{
		$this->load->view( 'provider_grid' );
	}
	function loadData(){
		
		$data = $this->provider_model->getAllData(); 
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
	function delete_Data(){
		if (uri_assoc('id')) {
			echo $this->provider_model->delete_provider(uri_assoc('id'));
		}
		else
		{
			echo "Error while Deleting";
		}
	}
	
	function form() {

		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			
			$this->load->view('provider_form');

		}

		else {
			$formdata = $this->form_model->db_array();
			
			$formdata['add_date'] = date('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('id');
			$formdata['status'] = 1;
			
			if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->provider_model->save($formdata, uri_assoc('id'));
			}

		}

	}
	function provider_duplicate()
	{	
		$nm=uri_assoc('provider');
		$id=uri_assoc('id');
		echo $this->provider_model->chk_provider($nm,$id);
		
	}

}
?>