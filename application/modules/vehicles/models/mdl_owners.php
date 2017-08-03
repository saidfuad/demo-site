<?php

class Mdl_owners extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    function get_all_owners ($company_id = null) {
       
        $this->db->select('itms_owner_master.*');
        $this->db->from('itms_owner_master');
           if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_owner_master.company_id', $company_id);
           }

        $this->db->order_by('owner_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }

    function get_owner ($company_id = null) {
       
        if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_owner_master.company_id', $company_id);
           }
        $this->db->where('owner_id', $this->uri->segment(3));
        $query = $this->db->get('itms_owner_master');
       
        return $query->result();

    }

    function update_owner($data) {
        $this->db->where('owner_id', $data['owner_id']);
        $query = $this->db->update('itms_owner_master', $data);

        return false;
    }

    

}
