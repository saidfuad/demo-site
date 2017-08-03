<?php 

class Mdl_Auth extends Model {

	public function auth($table, $user_field, $pass_field, $user_value, $pass_value){
		$this->db->where($user_field, $user_value);
		$this->db->where($pass_field, $pass_value);
		$this->db->where('status', 1);
		$this->db->where('del_date', NULL);
		//$this->db->where("if(usertype_id != 1, '".gmdate('Y-m-d H:i:s')."' between date(from_date) and date(to_date), 1)");
		$query = $this->db->get($table);
		if ($query->num_rows() == 1) {
			return $query->row();
		}
		else {
			return false;
			/*echo  $this->db->last_query();
			die();*/
		}
	}
	public function checkExpirtDate($user_id){
		$res = $this->db->query("select user_id from tbl_users where user_id = '$user_id' and '".gmdate('Y-m-d H:i:s')."' between date(from_date) and date(to_date)");
		if($res->num_rows ==1)
		{
			return true; 
		}
		else
		{
			return false;
		}
	}
        //Added by Ashwini 12_02_2015
	 function checkExpirtDay($user_id){
            
       	$res = $this->db->query("select timezone,display_day from tbl_users where user_id = '$user_id'");
	
	foreach ($res->result() as $row){
	        $display_day = $row->display_day;
            $timezone= $row->timezone;
			
	}
        
	$query = "SELECT time_zone FROM timezone WHERE diff_from_gmt = '" .$timezone. "' ORDER BY id LIMIT 0,1";
        $res = $this->db->query($query);
              
        foreach ($res->result() as $row) {
           date_default_timezone_set($row->time_zone);
         }
		 
		
        $day = strtolower(date('l'));
		$days = array();
	    $days = explode(",",$display_day);
		
	if((in_array($day, $days) || in_array("all", $days))){
	
	 return true; 
	}
	else{
	
	 return false;
	}
    }
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
			$session_data[$object_var] = $user_object->$object_var;
		}
		if ($custom_vars) {
			foreach ($custom_vars as $key=>$var) {
				$session_data[$key] = $var;
			}
		}
		$this->session->set_userdata($session_data);
	//	var_dump($this->session->userdata("is_admin"));
	//	die();
	}
	public function update_timestamp($table, $key_field, $key_id, $value_field, $value_value) {
		$this->db->where($key_field, $key_id);
		$this->db->update($table, array($value_field => $value_value));
	}
	public function get_location($user) 
	{
		$query = $this->db->query("SELECT lp.*, am.assets_name from tbl_last_point lp left join tbl_assests_master am on am.device_id = lp.device_id where find_in_set(am.id, (select assets_ids from user_assets_map where user_id = $user))", FALSE);

		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	public function get_sms_log($user) 
	{
		$query = $this->db->query("SELECT * from smslog where user_id = $user and date(add_date) = '".date('Y-m-d')."' order by id desc", FALSE);

		return $query->result();
	}
 
	public function check_user_name($user_name) {
		$res = $this->db->query("select user_id from tbl_users where username like '$user_name' and del_date is null and status=1");
		if($res->num_rows ==1)
		{
			$row = $res -> result(); 
			return $row[0]->user_id; 
		}
		else
		{
			return false;
		}
		//var_dump($res);
	}
	public function save($datanya) {
		//print_r($datanya);
			$this->db->insert('sys_information',$datanya);
			if($this->db->insert_id()){
				return $this->db->insert_id();
			}
			else
			{
				return false;
			}
			
			/*
			$this->table_name = 'sys_information';
			$db_array['ip_address'] = "192.168.0.143";
			$db_array['add_date'] = date("Y-m-d H:i:s");
			$db_array['comments'] = "subscribed";
			
			$sql = $this->db->insert_string($this->table_name, $db_array);
			$query = $this->db->query($sql);
			if($this->db->insert_id()){
				return true;
			}
			return false;
			*/
			
	}
	public function save_failed($datanya) {
		//print_r($datanya);
			$this->db->insert('failed_login',$datanya);
			if($this->db->insert_id()){
				return $this->db->insert_id();
			}
			else
			{
				return false;
			}
			
			/*
			$this->table_name = 'sys_information';
			$db_array['ip_address'] = "192.168.0.143";
			$db_array['add_date'] = date("Y-m-d H:i:s");
			$db_array['comments'] = "subscribed";
			
			$sql = $this->db->insert_string($this->table_name, $db_array);
			$query = $this->db->query($sql);
			if($this->db->insert_id()){
				return true;
			}
			return false;
			*/
			
	}
	
	public function update_sys_info($sys_id,$datanya) {
		$this->db->where('id',$sys_id);
		return $this->db->update('sys_information',$datanya);
	}
	
}

?>