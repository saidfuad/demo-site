<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class landmarks_waypoints extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('landmarks_waypoints_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}
	
	function index()
	{
		$this->load->view( 'landmarks_waypoints' );
	}
	function loadData(){
		
		$data = $this->landmarks_waypoints_model->getAllData(); 
		$responce = new stdClass();
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
		
		echo $this->landmarks_waypoints_model->delete_landmarks_waypoints(); 
	}
	
	function index1()
	{
		$this->load->view('landmarks_waypoints',$data);
	}
	function form() {	
		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
			$opt1="";
			$opt2="";
			$this->load->model('live/device_model');
			
			$rows = $this->device_model->getListofLandmarks();
			if(count($rows) > 0) {
				foreach($rows as $row){
                   //Landmark 1				
 					$opt1.="<option title='".base_url();l.$row->icon_path."' value='".$row->id."'";
					if($this->form_model->landmark1==$row->id){
						$opt1.=" selected='selected' ";
					}
					
					$opt1.=" >";
					$opt1.=$row->name;
					$opt1.="</option>";
					
					//Landmark 2
					$opt2.="<option title='".base_url().$row->icon_path."' value='".$row->id."'";
					if($this->form_model->landmark2==$row->id){
						$opt2.=" selected='selected' ";
					}
					$opt2.=" >";
					$opt2.=$row->name;
					$opt2.="</option>";
				}
			}
			else {
				$opt1 .= '<option title="" value="">No Landmark</option>';
				$opt2 .= '<option title="" value="">No Landmark</option>';
			}
			$this->form_model->landmark1 = $opt1;
			$this->form_model->landmark2 = $opt2;
			$this->load->view('form');			
		}
		else {
			//$user_id=$this->session->userdata('user_id');
			$formdata = $this->form_model->db_array();
			
			//if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
		/*	}else{
				$formdata['password'] = md5($formdata['password']);
				$this->landmarks_waypoints_model->save($formdata, uri_assoc('id'));
				$this->landmarks_waypoints_model->menu_entery_user($user_id,$username);
			}*/
		}
	}
	function export(){		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}
	function checkDuplicate_way(){
		$row=$this->landmarks_waypoints_model->checkDuplicate_way();
		echo $row;
	}
}
?>