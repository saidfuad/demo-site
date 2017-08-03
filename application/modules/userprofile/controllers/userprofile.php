<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userprofile extends Base_Controller {

	function __construct() {

        parent::__construct();
		
        if ($this->session->userdata('itms_protocal') == "") {
            redirect('login');
        }
		
		if ($this->session->userdata('itms_protocal') == 71) {
            redirect('admin');
        }

		
        $this->load->library('encrypt');
        $this->load->model('mdl_profile');
       
    }

	public function index() {
        
        $user_id = $this->session->userdata('itms_userid');

        $data['menu_permissions'] = $this->mdl_profile->get_user_menu_permissions($user_id);
        $data['report_permissions'] = $this->mdl_profile->get_user_report_permissions($user_id);
        $data['alert'] = $this->mdl_profile->get_user_alerts_permissions($user_id);
        $data['assigned_groups'] = $this->mdl_profile->get_assigned_groups($user_id);
        $data['user_profile'] = $this->mdl_profile->get_user_pic();

        // die(var_dump($data));
        $array = array();
        foreach ($data['assigned_groups'] as $key => $group) {
            array_push($array, $group->assets_group_id);
        }

        $group_ids = implode(',', $array);
        $data['assigned_vehicles'] = $this->mdl_profile->get_assigned_vehicles($group_ids);

        $data['content_url'] = 'userprofile';
        $data['fa'] = 'fa fa-user';
        $data['title'] = 'ITMS Africa | Userprofile';
        $data['content_title'] = 'User Profile';
        $data['content_subtitle'] = 'Details, Roles and access Permissions';
        $data['content'] = 'userprofile/userprofile.php';
		$this->load->view('main/main.php', $data);
	}

    public function edit_password(){

        $data = array('username' => $this->session->userdata('username'),
                      'current_password' => $this->input->post('current_password'),
                      'password' => $this->input->post('password'),
                      'cfmPassword' => $this->input->post('cfmPassword'));
        
        $res = $this->mdl_profile->edit_password($data);

        echo $res;

    }
    
    public function upload_profile_photo(){
        
        $data = array();
        $data['add_uid'] = $this->session->userdata('user_id');
        $data['user_logo'] = ($this->session->userdata('user_image') != '') ? $this->session->userdata('user_image') :'user-default.png';
        
        $result = $this->mdl_profile->upload_profile_photo($data);
        
        echo $result;
    }


}
