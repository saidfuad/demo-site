<?php

class Mdl_feedback extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    function save($data){
        $query = $this->db->insert('feedback', $data);

        if ($query) {
             return true;
        }
        else{
            return false;
        }
    }

    function get_user_by_id ($user_id) {
        $this->db->select('users.first_name,users.last_name,accounts.account_name');
        $this->db->from('users');
        $this->db->join('accounts','accounts.account_id=users.account_id');
        
        $this->db->where('users.user_id', $user_id);
        $query = $this->db->get();
       
        return $query->row_array();
    }

   
}

?>
