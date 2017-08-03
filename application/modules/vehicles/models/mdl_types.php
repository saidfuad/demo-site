<?php

class Mdl_types extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    function get_all_types ($company_id = null) {
       
        $this->db->select('itms_assets_types.*');
        $this->db->from('itms_assets_types');
           if ($company_id!=null) {
                //$this->db->where('itms_assets_types.company_id', $company_id);
           }

        $this->db->order_by('assets_type_nm', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }
    
    
    

}
