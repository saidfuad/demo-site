<?php

class Mdl_fetch extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    function get_vehicles ($company_id=null) {
        $this->db->select('itms_assets.*, itms_assets_categories.assets_category_id, itms_assets_categories.assets_cat_name,
                                itms_assets_categories.assets_cat_image, itms_assets_types.assets_type_id, 
                                    itms_assets_types.assets_type_nm, itms_personnel_master.personnel_id AS driver_id, 
                                        CONCAT(itms_personnel_master.fname, " ", itms_personnel_master.lname) AS driver_name,
                                            itms_owner_master.owner_id, itms_owner_master.owner_name');
        $this->db->from('itms_assets')
            ->join('itms_assets_categories', 'itms_assets_categories.assets_category_id = itms_assets.assets_category_id', 'left')
            ->join('itms_assets_types', 'itms_assets_types.assets_type_id = itms_assets.assets_type_id', 'left')
            ->join('itms_personnel_master', 'itms_personnel_master.personnel_id = itms_assets.personnel_id', 'left')
            //->join('itms_alerts', 'itms_alerts.personnel_id = itms_assets.personnel_id', 'left')
            ->join('itms_owner_master', 'itms_owner_master.owner_id = itms_assets.owner_id', 'left');

        
        if ($this->session->userdata('protocal') <= 7 && $company_id!=null) {
            $this->db->where('itms_assets.company_id', $company_id);
            $this->db->where('itms_assets.del_date IS NULL');
        }
        
        if ($this->session->userdata('protocal') < 7) {
            //$this->db->where('itms_assets.company_id', $this->session->userdata('itms_company_id'));
        }

        
        $this->db->order_by('assets_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();
    }

    function get_zones ($company_id = null) {
        if($company_id != null) {
            $this->db->where('company_id', $company_id);
        }

        $this->db->where('status', 1);
        $this->db->where('del_date', NULL);

        $query = $this->db->get('itms_zones');

        return $query->result();
    }

    function get_vertices ($company_id = null) {
        if($company_id != null) {
            $this->db->where('company_id', $company_id);
        }

        $query = $this->db->get('itms_zones_vertices');

        return $query->result();
    }


    function get_vehicle_by_id ($asset_id) {
        $this->db->select('itms_assets.*, itms_assets_categories.assets_category_id, itms_assets_categories.assets_cat_name,
                                itms_assets_categories.assets_cat_image, itms_assets_types.assets_type_id, 
                                    itms_assets_types.assets_type_nm, itms_personnel_master.personnel_id AS driver_id, 
                                        CONCAT(itms_personnel_master.fname, " ", itms_personnel_master.lname) AS driver_name,
                                            itms_owner_master.owner_id, itms_owner_master.owner_name');
        $this->db->from('itms_assets')
            ->join('itms_assets_categories', 'itms_assets_categories.assets_category_id = itms_assets.assets_category_id')
            ->join('itms_assets_types', 'itms_assets_types.assets_type_id = itms_assets.assets_type_id')
            ->join('itms_personnel_master', 'itms_personnel_master.personnel_id = itms_assets.personnel_id', 'left')
            ->join('itms_owner_master', 'itms_owner_master.owner_id = itms_assets.owner_id', 'left');
        
        $this->db->where('asset_id', $asset_id);
       
        
        $this->db->order_by('assets_name', 'ASC');
        $query = $this->db->get();
       
        return $query->row_array();
    }

    function save_vehicle($data) {
        if ($this->check_vehicle_by_plate_number($data['assets_name'])) {
            return 77;
            exit;
        }

        $query = $this->db->insert('itms_assets', $data);

        if ($query) {
            $this->session->set_userdata('vehicle_image', '');
            return true;
        }
        
        return false;
        
    }

    function update_vehicle($data) {
        $this->db->where('asset_id', $data['asset_id']);
        $this->db->update('itms_assets', $data);

        if ($query) {
            $this->session->set_userdata('vehicle_image', '');
            return true;
        }
        
        return false;
        
    }

    function delete_vehicle($asset_id){
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_assets SET del_date = '".$t."' WHERE asset_id ='".$asset_id."'";
        $this->db->query($sql);
    }

// model function to obtain landmark
    function get_landmarks () {

        $query = $this->db->get('itms_landmarks', array('company_id'=>$this->session->userdata('itms_company_id')));

        return $query->result();        
    }

    function get_alerts () {

        $query = $this->db->get('itms_alert_master');

        return $query->result();        
    }
// end of model function to obtain landmarks
    function get_groups ($company_id=null) {
        if ($company_id != null) {
           $this->db->where('itms_vehicle_groups.company_id', $company_id);
        }
        $this->db->where('itms_vehicle_groups.del_date IS NULL');
        $query = $this->db->get('itms_vehicle_groups');

        return $query->result();        
    }

    function count_groups () {
        return sizeof($this->get_groups($this->session->userdata('itms_company_id')));
    }

    function count_untracked_assets ($company_id=null) {
        if ($company_id!=null) {
            $data['company_id'] = $company_id;
        }
        $d = $this->session->userdata('itms_company_id');
        $data['device_id'] = null;
        $query = $this->db->query("SELECT COUNT(assets_name) AS device_id 
                                    FROM itms_assets
                                    WHERE device_id IS NULL
                                    AND  company_id = $d
                                    ");
        
        $data = $query->row_array();
        
        return $data['device_id'];
    }

    function count_unassigned_groups ($company_id=null) {
         $whereCompany = "";
        if ($company_id!=null) {
            $whereCompany = " AND company_id= $company_id ";
        }
        
        $query = $this->db->query("SELECT COUNT(assets_group_id) AS count_unassigned 
                                    FROM itms_assets_groups
                                    WHERE 1
                                    {$whereCompany}
                                    AND del_date = null
                                    AND 
                                        assets_group_id NOT IN(select group_concat(assets_group_id) from itms_assigned_groups)
                                    ");
        $data = $query->row_array();
        
        return $data['count_unassigned'];
    }

    function count_unassigned_users ($company_id=null) {
         $whereCompany = "";
        if ($company_id!=null) {
            $whereCompany = " AND company_id= $company_id ";
        }
        
        $query = $this->db->query("SELECT COUNT(user_id) AS count_unassigned 
                                    FROM itms_users
                                    WHERE 1
                                    {$whereCompany}
                                    AND del_date = null
                                    AND 
                                        user_id NOT IN(select group_concat(user_id) from itms_assigned_groups)
                                    ");
        $data = $query->row_array();
        
        return $data['count_unassigned'];
    }

    function count_available_devices ($company_id=null) {
        if ($company_id!=null) {
            $data['company_id'] = $company_id;
        }

        $data['assigned'] = 0;
        $d = $this->session->userdata('itms_company_id');
        
        $query = $this->db->query("SELECT COUNT(device_id) AS device_id 
                                    FROM itms_devices
                                    WHERE assigned = 0
                                    AND  company_id = $d
                                    ");
        
        $data = $query->row_array();
        
        return $data['device_id'];
        
        return sizeof($query->result());
    }


    function count_users () {
        $this->db->where('company_id', $this->session->userdata('itms_company_id'));
        $query = $this->db->get('itms_users');

       return $query->num_rows();
    }
    
    function count_devices () {
        $this->db->where('company_id', $this->session->userdata('itms_company_id'));
        $query = $this->db->get('itms_devices');

       return $query->num_rows();
    }

    function edit_groups($company_id=null){

        if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_vehicle_groups.company_id', $company_id);
           }
        $this->db->where('group_id', $this->uri->segment(3));
        $query = $this->db->get('itms_vehicle_groups');
       
        return $query->result();
    }
    function save_group ($data) {
        if ($this->check_vehicle_group_name ($data['group_name'])) {
            return 77;
            exit;
        }

        $query = $this->db->insert('itms_vehicle_groups', $data);

        if ($query) {
            return true;
        }
        
        return false;
        
    }

    function delete_group($group_id){
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_vehicle_groups SET del_date = '".$t."' WHERE group_id ='".$group_id."'";
        $this->db->query($sql);
    }
    function update_group_vehicle($data) {
        $this->db->where('group_id', $data['group_id']);
        $this->db->update('itms_vehicle_groups', $data);
    }
    function check_vehicle_group_name($group_name) {
        $query = $this->db->get_where('itms_vehicle_groups', array('group_name'=>$group_name));
        
        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function check_vehicle_by_plate_number($plate_number) {
        $query = $this->db->get_where('itms_assets', array('assets_name'=>$plate_number));
        
        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }
    
    
    function get_drivers () {
        $this->db->select('itms_personnel_master.*, itms_roles.role_name');
        $this->db->from('itms_personnel_master')
                ->join('itms_roles', 'itms_roles.role_id=itms_personnel_master.role_id')
                ->where('itms_roles.role_name', 'driver');

                if ($this->session->userdata('protocal') <= 7) {
                    $this->db->where('itms_personnel_master.company_id', $this->session->userdata('itms_company_id'));
                }

        $this->db->order_by('fname', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }

    function get_owners () {
        $this->db->select('itms_owner_master.*');
        $this->db->from('itms_owner_master');
        $this->db->where('itms_owner_master.del_date IS NULL');
        
           if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_owner_master.company_id', $this->session->userdata('itms_company_id'));
           }

        $this->db->order_by('owner_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }
    function delete_owner ($owner_id) {
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_owner_master SET del_date = '".$t."' WHERE owner_id ='".$owner_id."'";
        $this->db->query($sql);

    }

    function get_categories () {
        $this->db->select('itms_assets_categories.*');
        $this->db->from('itms_assets_categories');
           if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_assets_categories.company_id', $this->session->userdata('itms_company_id'));
           }
        $this->db->order_by('assets_cat_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }

    function get_types () {
        $this->db->select('itms_assets_types.*');
        $this->db->from('itms_assets_types');
           if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_assets_types.company_id', $this->session->userdata('itms_company_id'));
           }

        $this->db->order_by('assets_type_nm', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

    }


    function veh ($company_id=null) {
        $whereCompany = "";
        if ($company_id!=null) {
            $whereCompany = " AND company_id= $company_id ";
        }
        $query =$this->db->query("SELECT COUNT(*) AS asset_count 
                                    FROM itms_assets 
                                    WHERE 1  
                                    {$whereCompany}                                    
                                    AND del_date IS NULL");


        $add = $query->row_array();
        $t=$add["asset_count"];
        

        return $t;

    }
    function dri ($company_id=null) {
        $whereCompany = "";
        if ($company_id!=null) {
            $whereCompany = " AND company_id= $company_id ";
        }
        $query =$this->db->query("SELECT COUNT(role_id) as count_driver 
                                    FROM itms_personnel_master 
                                    WHERE role_id=2  
                                    {$whereCompany}
                                    AND del_date is null");
        $add = $query->row_array();
        $t=$add["count_driver"];

        return $t;

    }

    function total_vehicles(){
        $this->db->select('itms_assets.*, itms_assets_categories.assets_category_id, itms_assets_categories.assets_cat_name,
                                itms_assets_categories.assets_cat_image, itms_assets_types.assets_type_id, 
                                    itms_assets_types.assets_type_nm, itms_personnel_master.personnel_id AS driver_id, 
                                        CONCAT(itms_personnel_master.fname, " ", itms_personnel_master.lname) AS driver_name,
                                            itms_owner_master.owner_id, itms_owner_master.owner_name');
        $this->db->from('itms_assets')
            ->join('itms_assets_categories', 'itms_assets_categories.assets_category_id = itms_assets.assets_category_id')
            ->join('itms_assets_types', 'itms_assets_types.assets_type_id = itms_assets.assets_type_id')
            ->join('itms_personnel_master', 'itms_personnel_master.personnel_id = itms_assets.personnel_id', 'left')
            ->join('itms_owner_master', 'itms_owner_master.owner_id = itms_assets.owner_id', 'left');


        $this->db->order_by('assets_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();
    }

    function total_drivers($company_id=null, $role_id=null, $user_id=null){
        $whereCompany ='';
        $whereUser = "";
        $whereRole = "";
        if ($company_id!=null) {
            $whereCompany = " AND itms_personnel_master.company_id = '".$company_id."' ";
        }

        if ($user_id!=null) {
            $whereUser = " AND itms_personnel_master.add_uid = '".$user_id."' ";
        }

        if ($role_id!=null) {
            $whereRole = " AND itms_personnel_master.role_id = '".$role_id."' ";
        }

        $SQL="SELECT itms_personnel_master.*, itms_roles.role_name from itms_personnel_master 
                INNER JOIN 
                    itms_roles ON(itms_personnel_master.role_id = itms_roles.role_id) 
                    WHERE itms_personnel_master.role_id = 2 AND 1 
                        {$whereCompany} 
                        {$whereUser} 
                        {$whereRole}";

        $rarr=$this->db->query($SQL);
        return $rarr->result();


        // $query =$this->db->query("SELECT * FROM itms_personnel_master WHERE role_id=2");

        // return $query->result();
    }
}
