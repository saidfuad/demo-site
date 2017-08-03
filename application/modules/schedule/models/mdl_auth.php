<?php 

class Mdl_Auth extends CI_Model{

	public function auth($table, $user_field, $pass_field, $user_value, $pass_value){
		$this->db->select($table.'.*,itms_companies.*, 
								group_concat(itms_menu_permissions.menu_id) as itms_menu_permissions');
		$this->db->where($table.'.'.$user_field, $user_value);
		$this->db->where($table.'.status', 1);
		$this->db->where($table.'.del_date', NULL);
		$this->db->join('itms_companies', 'itms_companies.company_id='.$table.'.company_id', 'inner');
		$this->db->join('itms_menu_permissions', 'itms_menu_permissions.user_id='.$table.'.user_id', 'left');
		$query = $this->db->get($table);
		
		//return $this->db->last_query();
		//exit;
		if ($query->num_rows() == 1) {
			return $query->row_array();
		}
		else {
			return false;
		}
	}

	public function checkExpiryDate($user_id){
		$res = $this->db->query("select user_id from itms_users where user_id = '$user_id' and '".gmdate('Y-m-d H:i:s')."' between date(from_date) and date(to_date)");
		if($res->num_rows()==1) {
			return true; 
		} else {
			return false;
		}
	}
        //Added by Ashwini 12_02_2015
	 function checkExpiryDay($user_id){
            
       	$res = $this->db->query("select timezone,display_day from itms_users where user_id = '$user_id'");
	
		foreach ($res->result() as $row){
		        $display_day = $row->display_day;
	            $timezone= $row->timezone;
				
		}
       
        $day = strtolower(date('l'));
		$days = array();
	    $days = explode(",",$display_day);

	    	
		if((in_array($day, $days) || in_array("all", $days))){
			return true; 
		} else {
			return false;
		}
    }

    public function get_company_subscriptions($company_id){
		$res = $this->db->query("select group_concat(service_id) as subscribed_services from itms_services_subscriptions 
									where 
										company_id = '$company_id'
										and '".gmdate('Y-m-d H:i:s')."'>=start_date and '".gmdate('Y-m-d H:i:s')."'<= expiry_date");
		if($res->num_rows() > 0) {
			return $res->row_array(); 
		} else {
			return false;
		}
	}
        //
    
	public function auth_assets($id,$type){	
		
		if($type==3){			
			$this->db->where("user_id", $id);
			$this->db->where_not_in("assets_ids", NULL);
			$this->db->where_not_in("assets_ids","");
			$qry = $this->db->get("user_assets_map");
			if ($qry->num_rows() == 1) {
				return true;
			}else{					
				return false;
			}
		}else{
			return true;
		}
	}
	public function set_session($user_object, $object_vars, $custom_vars = NULL) {
		$session_data = array();
		foreach ($object_vars as $object_var) {
			$session_data[$object_var] = $user_object[$object_var];
		}

		if ($custom_vars) {
			foreach ($custom_vars as $key=>$var) {
				$session_data[$key] = $var;
			}
		}

		$this->session->set_userdata($session_data);
	
	}

	public function update_timestamp($table, $key_field, $key_id, $value_field, $value_value) {
		$this->db->where($key_field, $key_id);
		$this->db->update($table, array($value_field => $value_value));
	}
	
	
	public function get_sms_log($user) 
	{
		$query = $this->db->query("SELECT * from smslog where user_id = $user and date(add_date) = '".date('Y-m-d')."' order by id desc", FALSE);

		return $query->result();
	}
 
	public function check_user_name($user_name) {
		$res = $this->db->query("select * from itms_users where username='$user_name' and del_date is null and status=1");
		
		if($res->num_rows() > 0 ) {
			$row = $res -> row_array(); 
			return $row; 
		} else {
			return false;
		}
		
	}

	public function check_email($email) {
		$res = $this->db->query("select * from itms_users where email_address like '$email' and del_date is null and status=1");
		if($res->num_rows()>0) {
			$row = $res ->row_array(); 
			return $row; 
		} else {
			return $res->result();
		}
		
	}

	public function save($datanya) {
		
		$this->db->insert('sys_information',$datanya);
		if($this->db->insert_id()){
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function save_failed($datanya) {
		$this->db->insert('failed_login',$datanya);
		if($this->db->insert_id()){
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	
	public function update_sys_info($sys_id,$datanya) {
		$this->db->where('id',$sys_id);
		return $this->db->update('sys_information',$datanya);
	}
	
}

?>