<?php

class Mdl_main extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->user_id = $this->session->userdata('hawk_user_id');
        $this->current_time = date("Y-m-d H:i:s");
    }

    function fetch_alerts($account_id) {

        $this->db->select('alerts.*');
        $this->db->from('alerts');
        $this->db->where('account_id', $account_id);
        $this->db->where('viewed', 0);

        $query = $this->db->get();

        return $query->num_rows();
    }

    function reset_password($new_password){
        
        $query = $this->db->query("UPDATE logins SET password = '".$new_password."' WHERE user_id = '".$this->user_id."'");
        return $query;

    }
    
    function check_current_password(){

        $this->db->select('password');
        $this->db->from('logins');
        $this->db->where('user_id', $this->user_id);
        $query = $this->db->get();

        return $query->row_array();
        /*if($query->num_rows() == 0){
            return 77;
        }else{
            return 1;
        }*/
    }
}

?>
