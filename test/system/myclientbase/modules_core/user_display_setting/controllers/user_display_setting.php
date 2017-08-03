<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class User_display_setting extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('user_display_setting_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$users_row=$this->user_display_setting_model->getAllUsers();
		$data['Users_combo']=$users_row;
		$this->load->view('user_display_setting',$data);
	}
	function loadUser(){
		$uid=uri_assoc('id');
		if($uid=="all"){			
			$data = $this->user_display_setting_model->Users_Menu_all();
			//echo $data;
			$this->output->set_output($data);
			//echo "<tr><td style='background:black;height:8px' colspan='2'></td></tr></table>";
			$this->output->set_output("<tr><td style='background:black;height:8px' colspan='2'></td></tr></table>");
			$all_users=$this->user_display_setting_model->getAllUsers_ID();
			foreach($all_users as $users){
				//echo "<hr/>";
				$this->output->set_output("<hr/>");
				//echo "<strong>".$users->first_name." ".$users->last_name."</strong>";
				$this->output->set_output("<strong>".$users->first_name." ".$users->last_name."</strong>");
				$data = $this->user_display_setting_model->Users_Menu($users->user_id);
				//echo $data;
				$this->output->set_output($data);
			}
		}else{
			$data = $this->user_display_setting_model->Users_Menu($uid); 
			//echo $data;
			$this->output->set_output($data);
		}
	}
	function submitUser(){
		$data="";
		$data = $this->user_display_setting_model->update_user();
		//echo $data;
		$this->output->set_output($data);
	}
}
?>