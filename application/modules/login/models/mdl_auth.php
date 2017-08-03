<?php 

class Mdl_Auth extends CI_Model{

	public function auth($table, $user_field, $pass_field, $user_value, $pass_value){
		$this->db->select($table.'.*, users.*, account_name');
		$this->db->where($table.'.'.$user_field, $user_value);
		$this->db->or_where($table.'.phone_no', $user_value);
		$this->db->join('users', 'users.user_id='.$table.'.user_id', 'inner');
		$this->db->join('accounts', 'accounts.account_id= users.account_id');
		$query = $this->db->get($table);
		
		
		if ($query->num_rows() == 1) {
			return $query->row_array();
		}
		else {
			return false;
		}
	}

	public function checkExpiryDate($user_id){
		/*$res = $this->db->query("select user_id from itms_users where user_id = '$user_id' and '".gmdate('Y-m-d H:i:s')."' between date(from_date) and date(to_date)");
		if($res->cubrid_num_rows(req_identifier)()==1) {
			return true; 
		} else {
			return false;
		}*/
		return true;


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

    	
    	


    	//date_default_timezone_set("Etc/GMT+3");
    	//$now = gmdate("Y-m-d H:i:s");		
		//echo date_default_timezone_get(). '       ' . $now;

		$res = $this->db->query("SELECT group_concat(service_id) AS subscribed_services FROM itms_services_subscriptions 
									WHERE 
										company_id = '$company_id'
										AND '".gmdate('Y-m-d H:i:s')."'<= expiry_date");

		//AND '".gmdate('Y-m-d H:i:s')."'>=start_date AND '".gmdate('Y-m-d H:i:s')."'<= expiry_date");
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
 

	public function check_email($email) {
		$res = $this->db->query("select * from logins where email like '$email' or phone_no like '$email'");
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

    public function reset_password($data) {
        $this->db->select('itms_users.password');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('company_id', $data['company_id']);
		return $this->db->update('itms_users', $data);
        
	}

	public function forgot($input){
       $this->db->select('logins.*')
                 ->from('logins')
                 ->where('email',$input)
                 ->or_where('phone_no',$input);

        $query = $this->db->get();
        $data = $query->row_array();

        if(!empty($data)){
            $email_recipient=$data['email'];
            $phone_num=$data['phone_no'];
            $this->load->helper('string');
			$newpass= random_string('alnum', 6);
			$this->load->library('encrypt');
            $new_password=$this->encrypt->encode($newpass);
            $to = array($email_recipient);
            $subj = "Hawk - User Password Reset";
            $url = "http://40.68.162.157:9090/hawk/index.php";
            
            $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                            <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                                <h1><img src="'.$url.'"></h1>
                            </div>
                            <div style="padding:20px;">
                                Dear User,<br><br>
                                Your HAWK user password has been reset.
                                You can access your account with your new login details below.
                                <br>
                                <br>
                                New Login Details<br>
                                Email : ' . $email_recipient . '<br>
                                Password: ' . $newpass . ' <br>
                                <br>
                                <br>
                                Verify carefully the Company information.
                                <br>
                                In case of any doubts, please feel free to contact HAWK Registrar.<br>
                                <a href="#">info@svs.com</a> on or <a href="#">+254 (0)729 220 777</a>
                                <br>                        
                            </div>
                        </div>';

            if($this->emailsend->send_email_message ($to, $subj, $message)){
            	$recipient = array($phone_num);
                $message = "Hawk - User Password Reset.\r\nLogin to your account using the following credentials Then Change The Password.\r\nUrl  :  http://40.68.162.157:9090/hawk/. \r\nUserName : " . $phone_num . " \r\nPassword : " . $newpass . "";

                $this->smssend->send_text_message($recipient, $message);

                $this->db->where('user_id',$data['user_id']);
                $this->db->set('password',$new_password);
        		$this->db->update('logins');
                return true;
            }else{
                  return false; 
            }
             
                 
        }else{
            return false; 
        }
    }
}

?>