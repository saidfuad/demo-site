<?php 

class Mdl_admin_dashboard extends CI_Model {

	public function get_registered_companies ($status=null) {
		$this->db->select("itms_companies.*,
							(SELECT COUNT(*) FROM itms_services_subscriptions 
								WHERE company_id = itms_companies.company_id AND status = 'active') AS count_subscribed_services,
							(SELECT COUNT(*) FROM itms_users 
								WHERE company_id = itms_companies.company_id) AS no_of_users,
							(SELECT COUNT(*) FROM itms_personnel_master 
								WHERE company_id = itms_companies.company_id) AS no_of_personnel,
							(SELECT COUNT(*) FROM itms_assets 
								WHERE company_id = itms_companies.company_id) AS no_of_vehicles,
							(SELECT group_concat(itms_services.services_name) FROM itms_services 
								WHERE FIND_IN_SET(itms_services.service_id, (SELECT group_concat(service_id) 
											FROM itms_services_subscriptions WHERE company_id = itms_companies.company_id))) AS service_list");
        $this->db->from('itms_companies');
        $this->db->where('itms_companies.protocal!=', 71);
        $this->db->where('itms_companies.company_date_deleted IS NULL');
          
       if ($status != null) {
            $this->db->where('itms_companies.company_status', $status);
       }

        $this->db->order_by('company_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

	}
    
    public function get_services_subscriptions ($company_id=null) {
        
		$this->db->select("itms_services.services_name, itms_services.service_id, itms_services_subscriptions.company_id, itms_services_subscriptions.start_date, itms_services_subscriptions.expiry_date");
        $this->db->from('itms_services');
        $this->db->join("itms_services_subscriptions", "itms_services_subscriptions.service_id = itms_services.service_id");
        
        $this->db->where('itms_services.date_deleted IS NULL');

        if($company_id !=null){
            $this->db->where('itms_services_subscriptions.company_id', $company_id);
            //$this->db->group_by('itms_services_subscriptions.company_id');
        }

        $this->db->order_by('services_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

	} 

	public function get_services () {
        
		$this->db->select("itms_services.*");
        $this->db->from('itms_services');
        $this->db->where('itms_services.date_deleted IS NULL');
        $this->db->order_by('services_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();

	}
    
    public function get_active_services(){
        
        $this->db->select("itms_services.*");
        $this->db->from('itms_services');
        $this->db->where('itms_services.status=', "1");
        $query = $this->db->get();
       
        return $query->result();
    }
    
    public function edit_company($company_id){
        
        $this->db->where('company_id', $this->uri->segment(3));
        $query = $this->db->get('itms_companies');
       
        return $query->result();
   
    }
    
    public function enable_disable($company_id){
        
        $this->db->select("itms_companies.company_status");
        $this->db->from('itms_companies');
        $this->db->where('itms_companies.protocal!=', 71);
        $this->db->where('itms_companies.company_id=', $company_id);
        
        $sql = $this->db->get();
        //$sq = $sql->result();
        $add = $sql->result_array();
        $t=$add[0]["company_status"];

        if ($t == "active") {
            
            $s = "inactive";
            $sql="UPDATE itms_companies SET company_status = '".$s."' WHERE company_id ='".$company_id."'";
            $this->db->query($sql);
            
        }else{

            $s = "active";
            $sql="UPDATE itms_companies SET company_status = '".$s."' WHERE company_id ='".$company_id."'";
            $this->db->query($sql);
        }
        
    }
    
    public function delete_company($company_id){
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_companies SET company_date_deleted = '".$t."' WHERE company_id ='".$company_id."'";
        $this->db->query($sql);
    }
    
    public function enable_disable_service($service_id){
        
        $this->db->select("itms_services.status");
        $this->db->from('itms_services');
        $this->db->where('itms_services.service_id=', $service_id);
        
        $sql = $this->db->get();
        //$sq = $sql->result();
        $add = $sql->result_array();
        $t=$add[0]["status"];

        if ($t == "1") {
            
            $s = "0";
            $sql="UPDATE itms_services SET status = '".$s."' WHERE service_id ='".$service_id."'";
            $this->db->query($sql);
            
        }else{

            $s = "1";
            $sql="UPDATE itms_services SET status = '".$s."' WHERE service_id ='".$service_id."'";
            $this->db->query($sql);
        }
        
    }
    
    public function edit_service($service_id){
        
        $this->db->where('service_id', $this->uri->segment(3));
        $query = $this->db->get('itms_services');
       
        return $query->result();
    }
    
    public function delete_service($service_id){
        
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_services SET date_deleted = '".$t."' WHERE service_id ='".$service_id."'";
        $this->db->query($sql);
    }
    
    function update_company($data){
        $this->db->where('company_id', $data['company_id']);
        $this->db->update('itms_companies', $data);
    }
    
    function update_service($data){
        $this->db->where('service_id', $data['service_id']);
        $this->db->update('itms_services', $data);
    }

	public function get_subscriptions_count () {

		$this->db->select("itms_services.*, 
							(SELECT COUNT(id) FROM itms_services_subscriptions 
								WHERE 1
								AND service_id = service_id) AS count_subs");
        $this->db->from('itms_services');
        $this->db->order_by('services_name', 'ASC');
        $this->db->group_by('services_name');
        $query = $this->db->get();
       
        return $query->result();

	} 
    
    public function get_roles () {
        
		$this->db->select("itms_roles.*");
        $this->db->from('itms_roles');
        $this->db->where('role_name !=', 'user');
        $this->db->order_by('role_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();
	} 
    
    function save_role ($data) {

        if ($this->check_role_exists($data['role_name'])) {
            return 80;
            exit;
        }
        $query = $this->db->insert('itms_roles', $data);

        if ($query) {
            return true;
        }
        
        return false;
        
    }
    
    public function edit_role($role_id){
        
        $this->db->where('role_id', $this->uri->segment(3));
        $query = $this->db->get('itms_roles');
        
        return $query->result();
    }
    
    function update_role($data){
        
        $this->db->where('role_id', $data['role_id']);
        $query = $this->db->update('itms_roles', $data);
        
        echo $query;
    }
    
    public function delete_role($role_id){
        
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_roles SET date_deleted = '".$t."' WHERE role_id ='".$service_id."'";
        $this->db->query($sql);
    }
    
    function check_role_exists ($role_name) {
        $query = $this->db->get_where('itms_roles', array('role_name'=>$role_name));
        
        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    public function get_asset_categories () {
        
        $this->db->select("itms_assets_categories.*");
        $this->db->from('itms_assets_categories');
        $this->db->where('del_date IS NULL');
        $this->db->order_by('assets_cat_name', 'ASC');
        $query = $this->db->get();
       
        return $query->result();
    }

    function save_asset_category ($data) {

        if ($this->check_asset_category_exists($data['assets_cat_name'])) {
            return 80;
            exit;
        }
        $query = $this->db->insert('itms_assets_categories', $data);

        if ($query) {
            return true;
        }
        
        return false;
        
    }

    function check_asset_category_exists ($assets_cat_name) {
        $query = $this->db->get_where('itms_assets_categories', array('assets_cat_name'=>$assets_cat_name));
        
        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    public function edit_asset_category($asset_category_id){
        
        $this->db->where('assets_category_id', $this->uri->segment(3));
        $query = $this->db->get('itms_assets_categories');
        
        return $query->result();
    }

    function update_asset_category($data){
        
        $this->db->where('assets_category_id', $data['assets_category_id']);
        $query = $this->db->update('itms_assets_categories', $data);
        
        echo $query;
    }

    public function delete_asset_category($assets_category_id){
        
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');
        $uid = $this->session->userdata('user_id');

        $sql = "UPDATE itms_assets_categories SET del_date = '".$t."', del_uid = '".$uid."' WHERE assets_category_id ='".$assets_category_id."'";
        $this->db->query($sql);
    }   

    public function get_asset_types () {
        
        $this->db->select("itms_assets_types.*");
        $this->db->from('itms_assets_types');
        $this->db->where('del_date IS NULL');
        $this->db->order_by('assets_type_nm', 'ASC');
        $query = $this->db->get();
       
        return $query->result();
    }

    function save_asset_type ($data) {

        if ($this->check_asset_type_exists($data['assets_type_nm'])) {
            return 80;
            exit;
        }
        $query = $this->db->insert('itms_assets_types', $data);

        if ($query) {
            return true;
        }
        
        return false;
        
    }

    function check_asset_type_exists ($assets_type_nm) {
        $query = $this->db->get_where('itms_assets_types', array('assets_type_nm'=>$assets_type_nm));
        
        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    public function edit_asset_type($assets_type_id){
        
        $this->db->where('assets_type_id', $this->uri->segment(3));
        $query = $this->db->get('itms_assets_types');
        
        return $query->result();
    }

    function update_asset_type($data){
        
        $this->db->where('assets_type_id', $data['assets_type_id']);
        $query = $this->db->update('itms_assets_types', $data);
        
        echo $query;
    }

    public function delete_asset_type($assets_type_id){
        
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');
        $uid = $this->session->userdata('user_id');

        $sql = "UPDATE itms_assets_types SET del_date = '".$t."', del_uid = '".$uid."' WHERE assets_type_id ='".$assets_type_id."'";
        $this->db->query($sql);
    }
}

?>