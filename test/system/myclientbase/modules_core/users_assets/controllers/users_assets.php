<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Users_assets extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('users_assets_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		$this->load->view( 'users_assets' );
	}
	function loadData(){
		$data = $this->users_assets_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			//$row->user = $row->first_name ." ".$row->last_name;
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		//echo $this->users_assets_model->delete_users(); 
		$this->output->set_output($this->users_assets_model->delete_users());
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
		$colModel['user'] = array('User',150,TRUE,'center',1);
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
		'title' => 'Users Assets List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Edit','edit','actionUsersAssets');
		
		$grid_js = build_grid_js('users_assets_list',site_url("/users_assets/ajax/all_users_assets"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('users_assets',$data);
	}
	
	
	function form() {

		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			
			$deviceGrp = '';
			$rows = $this->users_assets_model->get_group();
			$groups = $this->form_model->group_id;
			$grp = $this->form_model->group_id;
			$grp = explode(",", $grp);
			if(count($rows)) {
				foreach ($rows as $row) {				
					$deviceGrp .= '<option value="'.$row->id.'"';
					if(in_array($row->id, $grp))
						$deviceGrp .= ' selected="selected"';
					$deviceGrp .= '>'.$row->group_name.'</option>';
				}
			}
			
			$this->form_model->group_id = $deviceGrp;
			
			$user="";
			$rows = $this->users_assets_model->prepare_assets($groups);
			$opt = '';
			$ast = $this->form_model->assets_ids;
			$data['ast'] = $ast;
			$ast = explode(",", $ast);
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				if(in_array($row->id, $ast))
					$opt .= ' selected="selected"';
				$opt .= '>'.$row->assets_name.' ('.$row->device_id.')</option>';
			}
			$this->form_model->assets_ids = $opt;
			$rows = $this->users_assets_model->get_user_name($this->form_model->user_id);
			
			foreach ($rows as $row) {
				$user = $row->first_name." ".$row->last_name;
			}			
			$this->form_model->user = $user;
			$this->load->view('form', $data);

		}
		else{
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
			if(!isset($newData['assets_ids'])){
				$newData['assets_ids']="";
			}
			$newData['status']=1;
			$this->form_model->save($newData, uri_assoc('id'));
		}
	}
	function export(){		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	
	function fetchAssets(){
		$groups = $_POST['group'];
		$selected = $_POST['selected'];
		$groups = implode(',', $groups);
		
		$rows = $this->users_assets_model->prepare_assets($groups);
		$opt = '';
		$ast = explode(",", $selected);
		foreach ($rows as $row) {
			$opt .= '<option value="'.$row->id.'" selected="selected"';
			// if(in_array($row->id, $ast))
			//	$opt .= ' selected="selected"';
			$opt .= '>'.$row->assets_name.' ('.$row->device_id.')</option>';
		}
		
		print($opt);
		
	}
}
?>