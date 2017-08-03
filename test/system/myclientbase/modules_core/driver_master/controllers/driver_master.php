<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Driver_master extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('driver_master_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view('driver_master');
	}
	function loadData(){
		
		$data = $this->driver_master_model->getAllData(); 
		foreach($data['result'] as $rawdata){
			if ($rawdata->sms_alert ==1){
		$rawdata->sms_alert="Yes";
		}else{
		$rawdata->sms_alert="No";
		}
		if ($rawdata->email_alert==1){
		$rawdata->email_alert="Yes";
		}else{
		$rawdata->email_alert="No";
		}
		}
		
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
	
	 function check_duplicates()
	{
		$driver_code=uri_assoc("driver_code");
		$id=uri_assoc("id");
		if(!$this->driver_master_model->checkUserDuplicate($driver_code,$id))
		{
			//echo "This Username Already Taken, Please Choose Unique Username.";
			$this->output->set_output("This Driver-RFID Already Taken, Please Choose Unique Driver-RFID.");
		}
	}
	
	function deleteData(){
		//echo $this->driver_master_model->delete_driver_master(); 
		$this->output->set_output($this->driver_master_model->delete_driver_master()); 
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
		$colModel['assets_name'] = array('Asset Name',150,TRUE,'center',1);
		$colModel['device_id'] = array('Device',100,TRUE,'center',1);
		$colModel['icon_id'] = array('Icon',100,TRUE,'center',1);
		$colModel['sim_number'] = array('Sim Number',150, TRUE,'center',1);
		
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
		'title' => 'My Assets List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionAssets');
		$buttons[] = array('Edit','edit','actionAssets');
		$buttons[] = array('Delete','delete','actionAssets');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionAssets');
		$buttons[] = array('DeSelect All','delete','actionAssets');
		$buttons[] = array('separator');
		
		
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('assets',$data);
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
			$formdata['sms_alert'] = (!isset($formdata['sms_alert'])) ? 0 : 1;
			$formdata['email_alert'] = (!isset($formdata['email_alert'])) ? 0 : 1;
			
			if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$formdata['add_date'] = gmdate('Y-m-d H:i:s');
				$formdata['add_uid'] = $this->session->userdata('user_id');
				$this->driver_master_model->save($formdata, uri_assoc('id'));
			}
		}

	}
	
}
?>