<?php

class Mdl_profile extends CI_Model{

    function __construct () {
        parent::__construct();
		$this->load->library('encrypt');
    }

	function edit_password($data){
		$new_pass = $data['password'];
		$current_pass = $data['current_password'];

       
		$encrypted_new_password = $this->encrypt->encode($new_pass);
		$encrypted_current_password = $this->encrypt->encode($current_pass);
        
       $query = $this->db->query("SELECT password FROM itms_users WHERE email_address = '".$data['username']."' OR username='".$data['username']."'");

       $user = $query->row_array();
        //print_r($user);
        //exit;

       $res = false;

       if ($query->num_rows() > 0)
		{
			//$user = $query->row_array();

            if ($this->encrypt->decode($user['password']) == $current_pass) {
				$this->db->where('username', $data['username']);
                $this->db->or_where('email_address', $data['username']);
        		$res = $this->db->update('itms_users', array('password'=>$encrypted_new_password, 'change_password'=>0));

			} else {
				$res = false;
			}
			
		} else {
			$res = false;
		}

        print_r($res);
        exit;

         return $res;
    }


    function get_user_menu_permissions ($user_id=null) {

    	$this->db->select('itms_menu_permissions.*, itms_menus.menu_name');
    	$this->db->join('itms_menus', 'itms_menus.menu_id=itms_menu_permissions.menu_id');
    	$this->db->where('itms_menu_permissions.user_id', $user_id);
    	$query = $this->db->get('itms_menu_permissions');

    	return $query->result();

    }

    function get_user_report_permissions ($user_id=null) {
    	$this->db->select('itms_report_permissions.*, itms_reports.report_name');
    	$this->db->join('itms_reports', 'itms_reports.report_id=itms_report_permissions.report_id');

    	if ($user_id!=null) {
    		$this->db->where('itms_report_permissions.user_id', $user_id);
    	}

    	$query = $this->db->get('itms_report_permissions');

    	return $query->result();


    }

    function get_user_alerts_permissions ($user_id=null) {
    	$this->db->select('itms_users.sms_alert, itms_users.email_alert');

    	if ($user_id!=null) {
    		$this->db->where('itms_users.user_id', $user_id);
    	}

    	$query = $this->db->get('itms_users');

    	return $query->row_array();
    }

    function get_assigned_groups ($user_id=null) {
    	$this->db->select('itms_assigned_groups.*, itms_assets_groups.assets_group_nm');
    	$this->db->join('itms_assets_groups', 'itms_assets_groups.assets_group_id=itms_assets_groups.assets_group_id');

    	if ($user_id!=null) {
    		$this->db->where('itms_assigned_groups.user_id', $user_id);
    	}

    	$query = $this->db->get('itms_assigned_groups');

    	return $query->result();

    }

    function get_assigned_vehicles ($groups_ids) {
    	$this->db->select('itms_assets.*');
    	
    	if ($groups_ids!=null) {
    		$this->db->where_in('itms_assets.assets_group_id', $groups_ids);
    	}
    	
    	$query = $this->db->get('itms_assets');

    	return $query->result();

    }
    
    function upload_profile_photo ($data){
        //$this->db->select('itms_users.user_logo');
        $this->db->where('user_id', $data['add_uid']);
        $res = $this->db->update('itms_users', $data);

        if ($res) {
                    $this->session->set_userdata('user_logo', $data['user_logo']);
                }
        
        return $res;
    }

    function get_user_pic(){
        $this->db->select('user_logo');
        $this->db->from('itms_users');
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $query = $this->db->get();
        
        return $query->result();
    }
}