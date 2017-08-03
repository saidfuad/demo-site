<?php

class Mdl_users extends CI_Model{

    function __construct () {
        parent::__construct();
    }

      function get_users ($hawk_user_id=null) {
        $this->db->select('users.*,accounts.*');
        $this->db->where('user_type_id',4);
        $this->db->order_by('first_name', 'ASC');
        $this->db->join('accounts','accounts.account_id=users.account_id');
        $query = $this->db->get('users');

        return $query->result();

    }

    function get_users_clients ($hawk_user_id=null) {
    	$this->db->select('users.*,accounts.*');
        $this->db->where('user_type_id',2);
        $this->db->order_by('first_name', 'ASC');
        $this->db->join('accounts','accounts.account_id=users.account_id');
        $query = $this->db->get('users');

        return $query->result();

    }

    function save_user($data){
    	$query = $this->db->insert('users', $data);

        if ($query) {
            return $this->db->insert_id();
        }
        
        return 77;
    }

    function save_login($dataa){
    	$query = $this->db->insert('logins', $dataa);

        if ($query) {
            return true;
        }
        
        return false;
    }


    function update_user($data,$dataa) {
        $this->db->where('user_id', $data['user_id']);
        $query = $this->db->update('users', $data);

        if ($query) {
        // $this->db->where('user_id', $dataa['user_id']);
        // $query = $this->db->update('logins', $dataa);

        // 	if($query){
        // 		return true;
        // 	}

        	return true;
            
        }
        
        return false;
        
    }

    function get_user_by_id ($user_id) {
        $this->db->select('users.*');
        $this->db->from('users');
        
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
       
        return $query->row_array();
    }

    function check_email($email){
    	$this->db->where('email',$email);
    	return $this->db->get('users')->row();
    }

    function check_emaill($email,$userid){
    	$this->db->where('email',$email);
    	$this->db->where('user_id !=',$userid);
    	return $this->db->get('users')->row();
    }
}