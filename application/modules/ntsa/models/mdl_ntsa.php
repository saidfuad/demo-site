<?php

class Mdl_ntsa extends CI_Model {

	public function get_saccos(){
        $this->db->select('user_id, company_name');
        $this->db->from('users');
        $this->db->where('users.account_id!=', 71);
        $this->db->where('users.user_type_id', $this->config->item('sacco_user_type'));

        $query = $this->db->get();

        return $query->result();
    }

}

?>
