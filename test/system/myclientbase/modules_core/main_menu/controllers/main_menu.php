<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Main_menu extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('main_menu_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index() 
	{
		$this->load->view( 'main_menu' );
	}
	function loadData(){
		
		$data = $this->main_menu_model->getAllData(); 
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
		
		echo $this->main_menu_model->delete_menus(); 
	}
	function form() {

		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			//$res['typecombo']=$this->main_menu_model->filltypecombo(); 
			$this->load->view('form');

		}

		else {
			
			$formdata = $this->form_model->db_array();
			
			if($formdata['parent_menu_id'] =="")
				$formdata['parent_menu_id']=NULL;
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('user_id');
			$formdata['status'] = 1;
			
			if(uri_assoc('id')){
				
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{ 
				
				//$formdata['priority'] = ($formdata['priority'] + 1); 
				$formdata['menu_level'] = ($formdata['menu_level'] + 1);
				$this->main_menu_model->save($formdata, uri_assoc('id'));
				
				$m_id = $this->db->insert_id(); 
				$formdata1['menu_id'] = $m_id;
				$checkdata['is_admin'] = $formdata['is_admin']; 
				$formdata1['priority'] = $formdata['priority'];
				$formdata1['where_to_show'] = $formdata['where_to_show'];
				//$formdata1['user_id'] = $this->session->userdata('user_id');
				$formdata1['add_date'] = date('Y-m-d H:i:s');
				$formdata1['add_uid'] = $this->session->userdata('user_id');
				$formdata1['status'] = 1;
				$this->main_menu_model->save1($formdata1, $checkdata, uri_assoc('id'));
			} 
			
			
		}

	} 
	function sel_menu(){
		if(uri_assoc('id')){
			$this->form_model->getlevel(uri_assoc('id'));
		}
	}
	// function export(){
		
		// $this->load->plugin('to_excel'); 
		// $this->form_model->export();
	// }	 
}
?>