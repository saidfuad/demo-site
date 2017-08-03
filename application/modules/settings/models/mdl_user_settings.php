<?php 

class Mdl_user_settings extends CI_Model{

    var $company_admin;

    function __construct() {

        parent::__construct();

        $this->company_admin = $this->get_company_admin();    
    }

    function get_company_admin () {
//        $company_id = $this->session->userdata('itms_company_id');
//        $q = $this->db->get_where('itms_users', array('company_id'=>$company_id, 'protocal'=>7, 'del_date'=>NULL));
//        $row = $q->row_array();
//
//
//        return $row['user_id'];
    }

	public function get_user_permissions ()
	{
        $company_id = $this->session->userdata('itms_company_id');
        $user_id = $this->session->userdata('itms_userid');

        $query = $this->db->query("SELECT itms_users.*, 
        				(select count(id) from itms_menu_permissions where user_id = itms_users.user_id) AS menu_permissions,
        				(select count(id) from itms_report_permissions where user_id = itms_users.user_id) AS report_permissions,  
        				(select count(id) from itms_assigned_groups where user_id = itms_users.user_id) AS assigned_groups  
        						    FROM 
        								itms_users
        							WHERE 1
        								AND 
        									itms_users.company_id = '".$company_id."' 
        								");
        return $query->result();
	}

	public function get_user_permissions_details ($user_id)
	{
        $company_id = $this->session->userdata('itms_company_id');
        
        $query = $this->db->query("SELECT itms_users.*, 
        				(select group_concat(menu_id) from itms_menu_permissions where user_id = '".$user_id."') AS menu_permissions,
        				(select group_concat(report_id) from itms_report_permissions where user_id = '".$user_id."') AS report_permissions,  
        				(select group_concat(assets_group_id) from itms_assigned_groups where user_id = '".$user_id."') AS assigned_groups  
        						    FROM 
        								itms_users
        							WHERE 1
        								AND 
        							itms_users.user_id = '".$user_id."'
                                                                AND company_id='".$company_id."'");
        

        return $query->row_array();
	}	

	public function get_company_services_subscriptions ()
	{
        $company_id = $this->session->userdata('itms_company_id');
        $user_id = $this->session->userdata('itms_userid');

        $query = $this->db->query("SELECT itms_services.* FROM itms_services WHERE 1
        							AND 
        							service_id IN (select group_concat(service_id) from itms_services_subscriptions 
        								where company_id = '$company_id')");
        

        return $query->result();
	}	

	public function get_menus ()
	{
        $company_id = $this->session->userdata('itms_company_id');

        //$user_id = $this->session->userdata('itms_userid');

        $query = $this->db->query("SELECT itms_menu_permissions.*, itms_menus.menu_name FROM itms_menu_permissions 
                                    JOIN itms_menus ON (itms_menus.menu_id=itms_menu_permissions.menu_id) 
                                    WHERE 1
                                    AND itms_menu_permissions.user_id = $this->company_admin 
                                    AND itms_menu_permissions.company_id = $company_id 
                                    ORDER BY menu_name ASC");
        
        return $query->result();
	}	
	
	public function get_reports ()
	{
        $company_id = $this->session->userdata('itms_company_id');
        //$user_id = $this->session->userdata('itms_userid');

        //$query = $this->db->query("SELECT itms_reports.* FROM itms_reports WHERE 1 ORDER BY report_name ASC");
        $query = $this->db->query("SELECT itms_report_permissions.*, itms_reports.report_name FROM itms_report_permissions 
                                    JOIN itms_reports ON (itms_reports.report_id=itms_report_permissions.report_id) 
                                    WHERE 1
                                    AND itms_report_permissions.user_id = $this->company_admin 
                                    AND itms_report_permissions.company_id = $company_id 
                                    ORDER BY report_name ASC");
        

        return $query->result();
	}

	public function get_vehicle_groups ()
	{
                $company_id = $this->session->userdata('itms_company_id');
                //$user_id = $this->session->userdata('itms_userid');

                $query = $this->db->query("SELECT itms_assets_groups.* FROM itms_assets_groups 
                								WHERE 1 
                								AND company_id = '$company_id' 
                								ORDER BY assets_group_nm ASC");
                

                return $query->result();
	}

        public function save_menu_permissions($user_id, $md_array) {
             $count = 0;


             if ($this->db->delete('itms_menu_permissions', array('user_id'=>$user_id))) {
                foreach ($md_array as $key => $arr) {
                    $q = $this->db->insert('itms_menu_permissions', $arr);

                    if ($q) {
                        $count++;
                    }
                }
                
             }

             return $q;


        }

        public function save_alert_permissions($data) {
            $this->db->where('user_id', $data['user_id']);

            return $this->db->update('itms_users', $data);
        }

        public function save_group_permissions($user_id, $md_array) {
             $count = 0;


             if ($this->db->delete('itms_assigned_groups', array('user_id'=>$user_id))) {
                foreach ($md_array as $key => $arr) {
                    $q = $this->db->insert('itms_assigned_groups', $arr);

                    if ($q) {
                        $count++;
                    }
                }
                
             }

             return $q;


        }

        public function save_report_permissions($user_id, $md_array) {
             $count = 0;


             if ($this->db->delete('itms_report_permissions', array('user_id'=>$user_id))) {
                foreach ($md_array as $key => $arr) {
                    $q = $this->db->insert('itms_report_permissions', $arr);

                    if ($q) {
                        $count++;
                    }
                }
                
             }

             return $q;


        }
}

?>
