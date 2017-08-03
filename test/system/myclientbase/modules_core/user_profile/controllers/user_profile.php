<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class User_profile extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('user_profile_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}
	
	function index()
	{
		$this->load->view( 'user_profile' );
	}
	function loadData(){
		
		$data = $this->user_profile_model->getAllData(); 
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
	function subdata(){
		
		$data = $this->user_profile_model->subdata(); 
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
		//echo $this->user_profile_model->delete_user_profile(); 
		$this->output->set_output($this->user_profile_model->delete_user_profile());
	}
	
	
	function form() {
	
		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
			if (!$_POST AND uri_assoc('copy_id')) {
				$this->form_model->prep_validation(uri_assoc('copy_id'));
			}
			$this->load->view('form');
			
		}
		else {
			$user_id=$this->session->userdata('user_id');
			$formdata = $this->form_model->db_array();
			$formdata['add_uid'] = $this->session->userdata('user_id');
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			$formdata['status'] = isset($formdata['status'])?$formdata['status']:0;
			if(uri_assoc('id')){
				$menu_setting = isset($formdata ['menu_setting'])?$formdata ['menu_setting']:array();
				unset($formdata ['menu_setting']);
				$id= uri_assoc('id');
				$this->db->delete('mst_user_profile_setting', array('profile_id' => $id)); 
				for($i=0;$i<count($menu_setting);$i++){
					$parts = explode("_",$menu_setting[$i]);
					if(count($parts)==2){
						$setting_name = $parts[1];
						$menu_id = $parts[0];
					}else{
						$menu_id = $parts[0];
						$setting_name = 'main';
					}
					$data = array(
						"profile_id"=>$id,
						"menu_id"=>$menu_id,
						"setting_name"=>$setting_name,
						"add_uid"=>$this->session->userdata('user_id'),
						"add_date"=>date('Y-m-d H:i:s'),
						"status"=>1,
					);
					$this->db->insert("mst_user_profile_setting", $data);
				}
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->user_profile_model->save($formdata, uri_assoc('id'));
			}
		}
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	function get_json_data()
	{
		$this->load->model('user_profile_model','',TRUE);
		$rs=$this->user_profile_model->get_json();
		//echo json_encode($rs);
		$this->output->set_output(json_encode($rs));
	}
	function check_duplicates(){
		$username=uri_assoc("name");
		$id=uri_assoc("id");
		if(!$this->user_profile_model->checkUserDuplicate($username,$id))
		{
			//echo "This Username Already Taken, Please Choose Unique Username.";
			$this->output->set_output("This Username Already Taken, Please Choose Unique Username.");
		}
	}
}
?>