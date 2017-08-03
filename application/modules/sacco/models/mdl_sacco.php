<?php

class Mdl_sacco extends CI_Model {

    public function get_saccos() {
        $this->db->select('user_id, company_name');
        $this->db->from('users');
        $this->db->where('users.account_id!=', 71);
        $this->db->where('users.user_type_id', $this->config->item('sacco_user_type'));

        $query = $this->db->get();

        return $query->result();
    }

    public function get_owners($sacco_id) {
        $this->db->select('users.user_id, first_name, last_name');
        $this->db->from('users');
        $this->db->join('sacco_vehicles', 'sacco_vehicles.owner_id = users.user_id');
        $this->db->where('sacco_vehicles.sacco_id', $sacco_id);
        $this->db->group_by('sacco_vehicles.owner_id');
        $query = $this->db->get();
        return $query->result();
    }
    
    
    public function add_sacco_vehicle($data){
        $this->db->insert("sacco_vehicles",$data);
    }

    public function check_vehicle_exist($vehicle_id)
    {
        $this->db->where('vehicle_id', $vehicle_id);
        return $this->db->get('sacco_vehicles')->row();
    }

}

?>
