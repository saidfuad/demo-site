<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class broker extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('broker_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
		$this->load->helper('uri');
	}
	
	function index()
	{
		$this->load->view( 'broker' );
	}
	function loadData($cmd='false'){
		$data = $this->broker_model->getAllData($cmd); 
		$responce = new stdClass();
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
		//echo $this->broker_model->delete_users(); 
		$this->output->set_output($this->broker_model->delete_users());
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
		$colModel['user_id'] = array('ID',40,TRUE,'center',2);
		$colModel['first_name'] = array('First Name',100,TRUE,'center',1);
		$colModel['last_name'] = array('Last Name',100,TRUE,'center',1);
		$colModel['username'] = array('Username',100,TRUE,'center',1);
		$colModel['address'] = array('Address',100,TRUE,'center',1);
		$colModel['city'] = array('City',100,TRUE,'center',1);
		$colModel['state'] = array('State',100,TRUE,'center',1);
		$colModel['country'] = array('Country',100,TRUE,'center',1);
		$colModel['zip'] = array('Zip',100,TRUE,'center',1);
		$colModel['phone_number'] = array('Phone',100,TRUE,'center',1);
		$colModel['fax_number'] = array('Fax',100,TRUE,'center',1);
		$colModel['mobile_number'] = array('Mobile',100,TRUE,'center',1);
		$colModel['email_address'] = array('Email',100,TRUE,'center',1);
		$colModel['web_address'] = array('Website',100,TRUE,'center',1);
		$colModel['company_name'] = array('Company',100,TRUE,'center',1);
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 'auto',
		'rows' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'User List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionUsers');
		$buttons[] = array('Edit','edit','actionUsers');
		$buttons[] = array('Delete','delete','actionUsers');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionUsers');
		$buttons[] = array('DeSelect All','delete','actionUsers');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('users_list',site_url("/users/ajax/allusers"),$colModel,'user_id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('users',$data);
	}

	function AddCopyUsers() {
		$data = $this->broker_model->AddCopyUsers();
	}
	
	function form() {
	
		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
						
			$cntry=$this->broker_model->getCurrent(uri_assoc('id'));
			$stopt = '';

			if (!$_POST AND uri_assoc('id')){ 
				$cid=$cntry[0]['country'];
			}else{
				$cid=$this->session->userdata('country');
				$rows=$this->broker_model->getState($cid);
				foreach ($rows as $row) {
					$stopt .= '<option value="'.$row['id'].'"';
					$stopt .= '>'.$row['name'].'</option>';
				}
			}
			
			$rows=$this->broker_model->getCountries();
			$opt="";
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row['id'].'"';
				if($row['id']==$cid)
					$opt .= ' selected="selected"';
				$opt .= '>'.$row['name'].'</option>';
			}
			
			$data=array();
			$data['weekdays']=explode(",",$this->form_model->display_day);
			$this->form_model->cntry = $opt;
			$this->form_model->stt = $stopt;
			$this->load->view('form',$data);
			
		}
		else {
			$user_id=$this->session->userdata('user_id');
			$formdata = $this->form_model->db_array();
			$username=$formdata['username'];
			//$formdata['address']=addslashes($formdata['address']);
			
			$formdata['email_alert'] = isset($formdata['email_alert'])?$formdata['email_alert']:0;
			$formdata['sms_alert'] = isset($formdata['sms_alert'])?$formdata['sms_alert']:0;
			
			
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			
			$formdata['status'] = isset($formdata['status'])?$formdata['status']:0;
			if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{			  
			  $this->broker_model->save($formdata, uri_assoc('id'));
			}
		}
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	function state_data(){
		$this->load->model('broker_model','',TRUE);
		//echo $query=$this->broker_model->state();
		$this->output->set_output($query=$this->broker_model->state());
	}
	function city_data(){
		$this->load->model('broker_model','',TRUE);
		//echo $query=$this->broker_model->city();
		$this->output->set_output($query=$this->broker_model->city());
	}
	function get_json_data()
	{
		$this->load->model('broker_model','',TRUE);
		$rs=$this->broker_model->get_json();
		//echo json_encode($rs);
		$this->output->set_output(json_encode($rs));
	}
	function check_duplicates()
	{
		$username=uri_assoc("name");
		$id=uri_assoc("id");
		if(!$this->broker_model->checkUserDuplicate($username,$id))
		{
			//echo "This Username Already Taken, Please Choose Unique Username.";
			$this->output->set_output($this->lang->line('This Username Already Taken, Please Choose Unique Username.'));
		}
	}
	function getDashCombo()
	{
		$this->load->model('home/home_model','',TRUE);
		$combo_s="";
		$combo_s.="<option value=''>".$this->lang->line('all_assets')."</option>";
		$combo_s.="<option value='running'>".$this->lang->line('running')."</option>";
		$combo_s.="<option value='parked'>".$this->lang->line('parked')."</option>";
		$combo_s.="<option value='out_of_network'>".$this->lang->line('out_of_network')."</option>";
//		$combo_s.="<option value='device_fault'>".$this->lang->line('device_fault')."</option>";
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
			if(count($rows)) {
				foreach ($rows as $row) {
					$combo_s .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
				}
			}
		
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$combo_s .= "<option value='u-".$row->user_id."'>".$row->username." (".$row->first_name." ".$row->last_name.")</option>";
			}
		}
		//echo $combo_s;
		$this->output->set_output($combo_s);
	}
	
	function copyUsers()
	{
		$this->load->model('home/home_model','',TRUE);
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$combo_s .= "<option value='".$row->user_id."'>".$row->username." (".$row->first_name." ".$row->last_name.")</option>";
			}
		}
		
		$data['users'] = $combo_s;
		$this->load->view( 'copy_users', $data );
	}
	
	function usersExceptMe()
	{
		$ex_user = uri_assoc('id');
		
		$this->load->model('home/home_model','',TRUE);
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'), $ex_user);
		if(count($rows)) {
			foreach ($rows as $row) {
				$combo_s .= "<option value='".$row->user_id."'>".$row->username." (".$row->first_name." ".$row->last_name.")</option>";
			}
		}
		
		$this->output->set_output($combo_s);
	}
}
?>