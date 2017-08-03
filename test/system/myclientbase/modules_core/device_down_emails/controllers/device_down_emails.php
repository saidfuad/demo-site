<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Device_down_emails extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('device_down_emails_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'device_down_emails' );
	}
	function loadData(){
		$data = $this->device_down_emails_model->getAllData(); 
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
		echo $this->device_down_emails_model->delete_emails(); 
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
		$colModel['emails_name'] = array('emails Name',150,TRUE,'center',1);
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
		'title' => 'emails List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionemails');
		$buttons[] = array('Edit','edit','actionemails');
		$buttons[] = array('Delete','delete','actionemails');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionemails');
		$buttons[] = array('DeSelect All','delete','actionemails');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('allemails_list',site_url("/emails/ajax/allemails"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('emails',$data);
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
			$newData = array();
			foreach($formdata as $key=>$value){
				if(is_array($value)){
					if(count($value) > 0)
						$value = implode(",", $value);
					else
						$value = "";
				}
				$newData[$key] = $value;
			}
			$newData['add_date'] = gmdate("Y-m-d H:i:s");
			$this->form_model->save($newData, uri_assoc('id'));
		}

	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	function check_duplicates()
	{
		$emailsName=uri_assoc("name");
		$id=uri_assoc("id");
		if(!$this->device_down_emails_model->checkemailsDuplicate($emailsName,$id))
		{
			echo "emails Name Already Exist.";
		}
	}
}
?>