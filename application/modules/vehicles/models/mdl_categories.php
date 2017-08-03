<?php

class Mdl_categories extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    function get_all_categories ($company_id = null) {
             
        $this->db->select('itms_assets_categories.*');
        $this->db->from('itms_assets_categories');
           if ($company_id!=null) {
                //$this->db->where('itms_assets_categories.company_id', $company_id);
           }
        $this->db->order_by('assets_cat_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }
    
    
    

}
