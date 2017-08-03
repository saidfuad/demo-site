<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Telecom extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('telecom_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'telecom' );
	}
	function loadData(){
		
		$data = $this->telecom_model->getAllData(); 
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
	function deleteData(){		
		//echo $this->telecom_model->delete_telecom(); 
		$this->output->set_output($this->telecom_model->delete_telecom()); 
	}


	
	function index1()
	{
		
		$this->load->helper('flexigrid');
		/*
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		$colModel['id'] = array('ID',40,TRUE,'center',2);
		$colModel['telecom_name'] = array('telecom Name',150,TRUE,'center',1);
		$colModel['assets'] = array('Assets',700,TRUE,'center',1);
		
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 'auto',
		'rp' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'telecom List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actiontelecom');
		$buttons[] = array('Edit','edit','actiontelecom');
		$buttons[] = array('Delete','delete','actiontelecom');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actiontelecom');
		$buttons[] = array('DeSelect All','delete','actiontelecom');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('alltelecom_list',site_url("/telecom/ajax/alltelecom"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('telecom',$data);
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
			$this->form_model->save($formdata, uri_assoc('id'));
		}

	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
}
?>