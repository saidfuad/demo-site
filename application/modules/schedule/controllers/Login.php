<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {

        parent::__construct();
		
		$this->load->library('user_agent');
		$this->load->library('encrypt');
		$this->load->model('mdl_auth');
		$this->load->model('mdl_sessions');

		if ($this->session->userdata('itms_protocal') == 71) {
            redirect('admin');
        }

		if ($this->session->userdata('itms_user_id') != "") {
           redirect(home);
        }

       
    }

	public function index() {
		$this->load->view('login2');
	}


	public function authenticate () {

		$username = $this->input->post('username');
		$password =$this->input->post('password');
		$encrypted_password =$this->encrypt->encode($password);
		
		$browser=$this->agent->browser();
		$platform=$this->agent->platform();
        
        $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$this->mdl_sessions->getRealIpAddr());
        $country= $xml->geoplugin_countryName ;
		$city=$xml->geoplugin_city ;
		$lati=$xml->geoplugin_latitude;
		$longi=$xml->geoplugin_longitude;
		$browser=$this->agent->browser();
		$platform=$this->agent->platform();
        $ip = $_SERVER["REMOTE_ADDR"];
               
        $datanya = array('ip_address' => $ip, 
        					'country_name' => "$country", 
        					'city_name' => "$city", 
        					'os_name' => $platform, 
        					'device' => $browser, 
        					'latitude' =>$lati, 
        					'longitude' =>$longi, 
        					'last_login_time' => date("Y-m-d H:i:s"), 
        					'user_id' => '', 
        					'add_date' => gmdate("Y-m-d H:i:s"),
        					'comments' => '');

        $usernameExists = $this->mdl_auth->check_user_name($username);
        $emailExists = $this->mdl_auth->check_email($username);

        if($usernameExists) {
        	$user_id = $usernameExists['user_id'] ;
        	$company_id = $usernameExists['company_id'] ;
			$field_name = 'username';
		} else if ($emailExists) {
			$user_id = $emailExists['user_id'] ;
        	$company_id = $emailExists['company_id'] ;
			$field_name = 'email';
		} else {
			$datanya['comments'] = 'Username passed:'.$username;
			unset($datanya['last_login_time']);
			$this->mdl_auth->save_failed($datanya);
			echo json_encode(array('type'=>'error', 'title'=>'Access Denied','message'=>'Check username and or password and try again' ));
		    exit;
		}

		$isValid = false;;
		$type='error';
		
		
		$user = $this->mdl_auth->auth('itms_users', $field_name, 'password', $username, $encrypted_password);

		
		if ($user) {
			if ($this->encrypt->decode($user['password'])==$password) {
				$isValid = true;
			    $type = 'success';
			}    
		}
		


	    if ($isValid) {


	    	if ($user['protocal'] != 71) {

	            //check if account/subscription has expired
	            $validToDate = $this->mdl_auth->checkExpiryDate($user['user_id']);
	            
	            if ($validToDate == false) {
	            	echo json_encode(array('type'=>'info', 'title'=>'Account Expired', 'message'=>'Your account subscription has expired'));
	            	exit;
	            }
	            //check if user is allowed to access the system on the particular day	
	            $validToDay = $this->mdl_auth->checkExpiryDay($user['user_id']);

	            if($validToDay == false){
					echo json_encode(array('type'=>'info', 'title'=>'Access Denied', 'message'=>'Sorry You can not access this system on this day. Contact Administrator for more Info'));
	            	exit;
		        }


	        }

            $object_vars = array('user_id', 
            					 'last_name', 
            					 'email_address', 
            					 'mobile_number', 
            					 'first_name', 
            					 'protocal', 
            					 'global_admin', 
            					 'admin_id', 
            					 'company_name', 
            					 'usertype_id', 
            					 'language', 
            					 'timezone', 
            					 'date_format', 
            					 'menu_view', 
            					 'report_view', 
            					 'time_format', 
            					 'currency_format', 
            					 'language', 
            					 'photo', 
            					 'user_logo', 
            					 'def_dash_view', 
            					 'network_timeout', 
            					 'show_owners', 
            					 'show_divisions',
            					 'company_name',
            					 'company_logo',
            					 'company_address_1',
                                 'company_address_2',
                                 'company_tel_1',
                                 'company_tel_2',
                                 'company_phone_no_1',
                                 'company_phone_no_2',
                                 'company_country_id',
                                 'company_status',
                                 'company_latitude',
                                 'company_longitude'
                                 );

            //$object_vars = array('user_id', 'last_name', 'first_name', 'global_admin', 'admin_id', 'usertype_id');
            ini_set('session.gc_maxlifetime', 10 * 60 * 60);
            $subsc = $this->mdl_auth->get_company_subscriptions($user['company_id']);
            $_SESSION['itms_userid'] = $user['user_id'];
            $_SESSION['itms_protocal'] =$user['protocal'];
            $_SESSION['itms_company_id'] = $user['company_id'];
            $_SESSION['itms_company_subscriptions'] = $subsc['subscribed_services'];
            $_SESSION['itms_menu_permissions'] = $user['itms_menu_permissions'];
            $_SESSION['itms_current_city'] = (string)$city;
            $_SESSION['itms_current_country'] = (string)$country;

           
            
            $this->mdl_auth->set_session($user, $object_vars, array('openChatBoxes' => array(), 'is_admin' => TRUE, 'username' => $this->input->post('username')));
            $this->session->set_userdata('language', 'english');
    		
    		$this->mdl_auth->update_timestamp('itms_users', 'user_id',$user['user_id'], 'last_login', time());

    		$new_session_data = array();
            //get last login time and set session data
            $sys_user_id = $this->session->userdata('user_id');
			
			 $datanya['user_id'] = $sys_user_id;       
            		
            $new_session_data["sys_info_id"] = $this->mdl_auth->save($datanya);
			//save last insert id in session variable.
            $this->session->set_userdata('sys_info_id', $new_session_data['sys_info_id']);

            $SQL1 = "SELECT last_login_time FROM sys_information WHERE user_id = " . $this->session->userdata('user_id') . " order by add_date desc limit 1";
            $query1 = $this->db->query($SQL1);
            $i = 0;
            $row = $query1->row_array();
            
            $new_session_data['last_login_time'] = $row['last_login_time'];
            

            $this->session->set_userdata('last_login_time', $new_session_data['last_login_time']);


            if ($this->session->userdata('itms_protocal') == 71) {
	            echo json_encode(array('type'=>'success', 'title'=>'Success','message'=>'redirect_admin' ));
	        } else if($this->session->userdata('itms_protocal') <= 7){
	            echo json_encode(array('type'=>'success', 'title'=>'Success','message'=>'redirect_home' ));
	        }
		} else {
	    	$datanya['comments'] = 'Username passed:'.$username . 'and company_id: '. $company_id;
	    	unset($datanya['last_login_time']);
			$this->mdl_auth->save_failed($datanya);
			echo json_encode(array('type'=>'error', 'title'=>'Access Denied','message'=>'Check username and or password and try again' ));
		    exit;
	    }  

		

	}


	function logout() {
	
        $last_login_time = '';
        $sys_id = $this->session->userdata('sys_info_id');
        $date = date("Y-m-d");
        $startDate = date("Y-m-d H:i:s");
		
		$SQL1 = "SELECT last_login_time FROM sys_information WHERE id ='" . $this->session->userdata('sys_info_id') . "'";
		$query1 = $this->db->query($SQL1);
        
        if ($query1->num_rows() > 0) {
            $i = 0;
            $row = $query1->result();
            $endDate = $row[0]->last_login_time;
            $diff_time = strtotime($startDate) - strtotime($endDate);
			
            $datanya = array('last_login_time' =>$endDate,'last_logout_time' =>$startDate, 'duration_of_stay' => $diff_time);
			
            $this->mdl_auth->update_sys_info($sys_id, $datanya);
        }

        $this->session->sess_destroy();
        redirect('login');
    }


}
