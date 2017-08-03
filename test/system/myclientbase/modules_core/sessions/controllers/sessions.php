<?php

class Sessions extends Controller {

    function __construct() {

        parent::__construct();

        $this->load->library(array('session'));
		$this->load->library('user_agent');

        $this->load->database();
    }

    function index() {
        $this->load->helper(array('url', 'form'));
        if ($this->session->userdata('user_id') != "") {
            //redirect('home');
            if ($this->session->userdata('def_dash_view') == "tree_view") {
                redirect('tree');
            } else {
                redirect('home');
            }
        } else {
            redirect('sessions/login');
        }
    }

  /*  public function forgotpassword() {
        $this->load->helper(array('url', 'form'));
        $this->_load_language();
        $this->load->library('email');
        $this->load->library('user_agent');
        $this->load->library('messages');
        $this->load->model('mdl_sessions');
        //call to validate_email() for username field validations.
        if ($this->mdl_sessions->validate_user()) {
            $this->load->model('mdl_auth');
            //call to check_valid_email_() to check in database if the username exists or not.
            $user_email = $this->mdl_auth->check_valid_email_('tbl_users', 'username', $this->input->post('username'));
            if ($user_email) {
                
                $info = "Your password has been reset and emailed to <b> " . $user_email . "</b>";
                $this->messages->add($info, 'success');
            } else {
                $error = "The email id for this username is not found in our database.";
                $this->messages->add($error, 'error');
            }
        }

        $this->load->view('forgotpassword');
    }*/

    function login() {

        $this->load->helper(array('url', 'form'));
        if ($this->session->userdata('user_id') != "") {
            //redirect('home');
            if ($this->session->userdata('def_dash_view') == "tree_view") {
                redirect('tree');
            } else {
                redirect('home');
            }
        }
        $this->_load_language();

        $this->load->library('user_agent');
        $this->load->library('messages');

        if ('test.trackeron.com' == $_SERVER['HTTP_HOST'] || 'vts.trackeron.com' == $_SERVER['HTTP_HOST'] || 'vehicle.worldwidetrackingservices.com' == $_SERVER['HTTP_HOST']) {
            redirect('sessions/login1');
            exit;
        }

        if ($this->agent->is_referral()) {
            if ('http://omexsol.com/' == $this->agent->referrer() || 'http://www.omexsol.com/' == $this->agent->referrer()) {
                redirect('sessions/login1'); // redirect('sessions/login1');
                exit;
            }
        }

        $this->load->model('mdl_sessions');
        if ($this->mdl_sessions->validate()) {

            $this->load->model('mdl_auth');
            $user = $this->mdl_auth->auth('tbl_users', 'username', 'password', $this->input->post('username'), $this->input->post('password'));

            if ($user) {
                $isValid = true;
                if ($user->usertype_id != 1) {
                    $validToDate = $this->mdl_auth->checkExpirtDate($user->user_id);
                    if ($validToDate == false) {

                        $this->messages->add('Your account has been expired.', 'error');
                        $isValid = false;
                    }
                        $validToDay = $this->mdl_auth->checkExpirtDay($user->user_id);
		        if($validToDay == false){
			$this->messages->add("Sorry you do not have access at this time.", 'error');
			$isValid = false;
		        }
                }
                $assets = $this->mdl_auth->auth_assets($user->user_id, $user->usertype_id);
                if ($assets == false) {
                    $this->messages->add('No Assets Assigned.', 'error');
                    $isValid = false;
                }
                
                if ($isValid) {

                    $data = array();
                    $datanya = array();

                    $object_vars = array('user_id', 'last_name', 'email_address', 'mobile_number', 'first_name', 'global_admin', 'admin_id', 'company_name', 'usertype_id', 'profile_id', 'language', 'timezone', 'date_format', 'menu_view', 'report_view', 'time_format', 'currency_format', 'language', 'photo', 'user_logo', 'def_dash_view', 'network_timeout', 'show_owners', 'show_divisions');
                    //$object_vars = array('user_id', 'last_name', 'first_name', 'global_admin', 'admin_id', 'usertype_id');
                    ini_set('session.gc_maxlifetime', 10 * 60 * 60);
                    $_SESSION['userid'] = $user->user_id;
                    gmdate();
                    $this->mdl_auth->set_session($user, $object_vars, array('openChatBoxes' => array(), 'is_admin' => TRUE, 'username' => $this->input->post('username')));
                    $this->session->set_userdata('language', 'english');
		    $query = "SELECT diff_from_gmt, time_zone FROM timezone WHERE diff_from_gmt = '" . $this->session->userdata('timezone') . "' ORDER BY id LIMIT 0,1";
                    $res = $this->db->query($query);

                    foreach ($res->result() as $row) {
                        date_default_timezone_set($row->time_zone);
                        $this->session->set_userdata('time_zone', $row->time_zone);
                        $this->session->set_userdata('diff_from_gmt', $row->diff_from_gmt);
                    }

                    // set the session variables
                    $query = "select js_date_format from date_formats where md5(format) = md5('" . $this->session->userdata('date_format') . "')";
                    $res = $this->db->query($query);

                    $data['js_date_format'] = "";
                    foreach ($res->result() as $row) {
                        $data['js_date_format'] = $row->js_date_format;
                    }
                    //	$this->session->set_userdata($data);
                    $query = "select js_time_format from time_formats where md5(format) = md5('" . $this->session->userdata('time_format') . "')";
                    $res = $this->db->query($query);
                    $data['js_time_format'] = "";
                    foreach ($res->result() as $row) {
                        $data['js_time_format'] = $row->js_time_format;
                    }

                    $this->session->set_userdata($data);
                    // update the last login field for this user
                    $this->mdl_auth->update_timestamp('tbl_users', 'user_id', $user->user_id, 'last_login', time());

                    /* 		Create By default  Combo options		 */
                    $query = "select  id , assets_name, device_id  from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id =" . $user->user_id . ")) and del_date is null";
                    $res = $this->db->query($query);

                    $options = "";
                    foreach ($res->result() as $row) {

                        /* 	echo $this->db->last_query();
                          die(); */
                        $options .="<option value='" . $row->id . "'>" . $row->assets_name . "(" . $row->device_id . ")</option>";
                    }
                    $new_session_data = array();
                    $new_session_data["assets_option"] = $options;
                    //get last login time and set session data
                    $sys_user_id = $this->session->userdata('user_id');
					$SQL1 = "SELECT last_login_time FROM sys_information WHERE user_id = " . $this->session->userdata('user_id') . " order by add_date desc limit 1";
                    $query1 = $this->db->query($SQL1);
                    $i = 0;
                    $row = $query1->result();
                    if (isset($row[0])) {
                        $new_session_data['last_login_time'] = $row[0]->last_login_time;
                    } else {
                        $new_session_data['last_login_time'] = "";
                    }

                    $this->session->set_userdata('last_login_time', $new_session_data['last_login_time']);
                    $date = date("Y-m-d");
                    //$user_os = $this->mdl_sessions->getOS();
                    // $user_browser = $this->mdl_sessions->browser_detect();
					//get 
					$browser=$this->agent->browser();
					$platform=$this->agent->platform();
                    /* redirect('dashboard'); */
					$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$this->mdl_sessions->getRealIpAddr());
                    $country= $xml->geoplugin_countryName ;
					$city=$xml->geoplugin_city ;
					$lati=$xml->geoplugin_latitude;
					$longi=$xml->geoplugin_longitude;
					$browser=$this->agent->browser();
					$platform=$this->agent->platform();
                    $ip = $_SERVER["REMOTE_ADDR"];
                           
                    $datanya = array('ip_address' => $ip, 'country_name' => "$country", 'state_name' => $data['RegionName'], 'city_name' => "$city", 'os_name' => $platform, 'device' => $browser, 'latitude' =>$lati, 'longitude' =>$longi, 'last_login_time' => date("Y-m-d G:i:s"), 'user_id' => $sys_user_id, 'add_date' => gmdate("Y-m-d H:i:s"), 'add_uid' => '1', 'status' => '1', 'comments' => '');
					
                    $new_session_data["sys_info_id"] = $this->mdl_auth->save($datanya);
					//save last insert id in session variable.
                    $this->session->set_userdata('sys_info_id', $new_session_data['sys_info_id']);
					
                    $this->db->where('country', Null);
                    $this->db->where('state', NUll);
                    $query = $this->db->get('language_master');
                    foreach ($query->result() as $row) {
                        $language[] = $row->language_name;
                    }

                    $this->db->where('country', $data['CountryName']);
                    $this->db->where('state', NUll);
                    $query = $this->db->get('language_master');
                    foreach ($query->result() as $row) {
                        $language[] = $row->language_name;
                    }

                    $this->db->where('country', $data['CountryName']);
                    $this->db->where('state', $data['RegionName']);
                    $query = $this->db->get('language_master');
                    foreach ($query->result() as $row) {
                        $language[] = $row->language_name;
                    }
                    $new_session_data = array();
                    $new_session_data["disp_language_list"] = implode(";", $language);
                    ;
                    // $new_session_data["sys_info_id"] =  $this->mdl_auth->save($datanya); 

                    $this->session->set_userdata($new_session_data);
                    if ($this->session->userdata('user_id') != 1) {
                        if ($this->session->userdata('admin_id') != "") {
                            $dates = date("d.m.Y h:i a");
                            $tz = " (GMT " . $this->session->userdata('timezone') . ")";
                            $datesa = gmdate("Y-m-d H:i:s");
                            $user_id = $this->session->userdata('admin_id');
                            $first_name = $this->session->userdata('first_name');
                            $last_name = $this->session->userdata('last_name');

                            $array = array(
                                'alert_header' => 'Login Info',
                                'alert_msg' => 'User Name : ' . $first_name . ' ' . $last_name . '<br> Login Time : ' . $dates . $tz,
                                'alert_link' => '',
                                'alert_type' => 'alert',
                                'user_id' => $user_id,
                                'add_date' => $datesa
                            );
                            $this->db->insert('alert_master', $array);
                        }
                    }
                    //redirect('home');
                    if ($this->session->userdata('def_dash_view') == "tree_view") {
                        redirect('tree');
                    } else {
                        redirect('home');
                    }
                }
                /* }else{
                  $id =$this->mdl_auth->check_user_name($this->input->post('username'));
                  if($id)
                  {
                  $user_os = $this->mdl_sessions->getOS();
                  $user_browser = $this->mdl_sessions->browser_detect();
                  $ip = $_SERVER["REMOTE_ADDR"];
                  //	$ip = '59.95.198.247';
                  $data =array();
                  $xml = simplexml_load_file("http://api.ipinfodb.com/v2/ip_query.php?key=00056324c5738a4acdc40d77dcce046e79907fef5a0684b2ae999c2a2fa61ecc&ip=".$ip."&timezone=true");
                  foreach($xml->children() as $child)
                  {
                  $data[$child->getName()] = $child."";
                  }
                  $data['username']=$this->input->post('username');
                  $data['password']=$this->input->post('password');
                  $data['password']=$this->input->post('password');
                  $datanya=array('ip_address'=>$ip,'country_name'=>$data['CountryName'],'state_name'=>$data['RegionName'],'city_name'=>$data['City'],'os_name'=>$user_os,'device'=>$user_browser,'latitude'=>$data['Latitude'],'longitude'=>$data['Longitude'],'user_id'=>$id,'add_date'=>gmdate("Y-m-d H:i:s"),'add_uid'=>$id,'status'=>'1','comments'=>'');
                  $this->mdl_auth->save_failed($datanya);
                  //	die();
                  }

                  /*
                  $datanya=array('ip_address'=>$ip,'country_name'=>$data['CountryName'],'state_name'=>$data['RegionName'],'city_name'=>$data['City'],'os_name'=>$user_os,'device'=>$user_browser,'latitude'=>$data['Latitude'],'longitude'=>$data['Longitude'],'last_login_time'=>date("Y-m-d H:i:s"),'user_id'=>$sys_user_id,'add_date'=>date("Y-m-d H:i:s"),'add_uid'=>'1','status'=>'1','comments'=>'');
                  $new_session_data["sys_info_id"] =  $this->mdl_auth->save($datanya);
                 */
                //$this->messages->add('No assets assigned!', 'error');
                //}				
            } else {
                $id = $this->mdl_auth->check_user_name($this->input->post('username'));
                
                if ($id) {
				    $datanya = array();
                    $user_os = $this->mdl_sessions->getOS();
                    $user_browser = $this->mdl_sessions->browser_detect();
			       //$data=$this->mdl_sessions->get_country_by_ip();
					$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$this->mdl_sessions->getRealIpAddr());
                    $country= $xml->geoplugin_countryName ;
					$city=$xml->geoplugin_city ;
					$lati=$xml->geoplugin_latitude;
					$longi=$xml->geoplugin_longitude;
                    $ip = $_SERVER["REMOTE_ADDR"];
					$browser=$this->agent->browser();
					$platform=$this->agent->platform();
					
					$datanya=array('ip_address'=>$ip,'country_name'=>"$country",'city_name'=>"$city",'os_name'=>$platform,'device'=>$browser,'latitude'=>$lati,'longitude'=>$longi,'user_id'=>$id,'add_date'=>gmdate("Y-m-d H:i:s"),'add_uid'=>$id,'status'=>'1','comments'=>'');
					 
                    $this->mdl_auth->save_failed($datanya);
                }
                $datanya = array('ip_address' => $ip, 'country_name' => $data['CountryName'], 'state_name' => $data['RegionName'], 'city_name' => $data['City'], 'os_name' => $user_os, 'device' => $browser, 'latitude' => $data['Latitude'], 'longitude' => $data['Longitude'], 'last_login_time' => date("Y-m-d G:i:s"), 'user_id' => $sys_user_id, 'add_date' => date("Y-m-d H:i:s"), 'add_uid' => '1', 'status' => '1', 'comments' => '');

                //$new_session_data["sys_info_id"] = $this->mdl_auth->save($datanya);

                $this->messages->add('Invalid Username or Password.', 'error');
            }
        }
	$query = $this->db->get('language_master');
		foreach ($query->result() as $row)
		{
		  $sel = '';
		  if($row->language_name == 'portuguese') $sel = ' selected="ture"';
		  $data['langopt'] .= "<option value='".$row->language_name."'$sel>".ucfirst($row->language_name)."</option>";
		 //$data['langopt'] .= "<option value='".$row->language_name."'>".ucfirst($row->language_name)."</option>";
		}
        $this->load->view('login',$data);
    }

    function login1() {
	
        $this->load->helper(array('url', 'form'));
        if ($this->session->userdata('user_id') != "") {
            //redirect('home');
            if ($this->session->userdata('def_dash_view') == "tree_view") {
                redirect('tree');
            } else {
                redirect('home');
            }
        }
        $this->_load_language();

        $this->load->library('messages');

        $this->load->model('mdl_sessions');
        if ($this->mdl_sessions->validate()) {

            $this->load->model('mdl_auth');
            $user = $this->mdl_auth->auth('tbl_users', 'username', 'password', $this->input->post('username'), $this->input->post('password'));

            if ($user) {
                $isValid = true;
                if ($user->usertype_id != 1) {
                    $validToDate = $this->mdl_auth->checkExpirtDate($user->user_id);
                    if ($validToDate == false) {

                        $this->messages->add('Your account has been expired.', 'error');

                        $isValid = false;
                    }
                     $validToDay = $this->mdl_auth->checkExpirtDay($user->user_id);
		        if($validToDay == false){
			$this->messages->add("Sorry you do not have access at this time.", 'error');
			$isValid = false;
		     }
                }

                $assets = $this->mdl_auth->auth_assets($user->user_id, $user->usertype_id);
                if ($assets == false) {
                    $this->messages->add('No Assets assigned.', 'error');
                    $isValid = false;
                }

                if ($isValid) {

                    $data = array();
                    $datanya = array();

                    $object_vars = array('user_id', 'last_name', 'email_address', 'mobile_number', 'first_name', 'global_admin', 'admin_id', 'company_name', 'usertype_id', 'profile_id', 'language', 'timezone', 'date_format', 'menu_view', 'report_view', 'time_format', 'country', 'currency_format', 'language', 'photo', 'user_logo', 'def_dash_view', 'network_timeout', 'show_owners', 'show_divisions', 'history');
                    //$object_vars = array('user_id', 'last_name', 'first_name', 'global_admin', 'admin_id', 'usertype_id');
                    ini_set('session.gc_maxlifetime', 10 * 60 * 60);
                    $_SESSION['userid'] = $user->user_id;

                    $this->mdl_auth->set_session($user, $object_vars, array('is_admin' => TRUE, 'username' => $this->input->post('username')));
 		    $this->session->set_userdata('language', 'english');

                    $query = "SELECT time_zone FROM timezone WHERE diff_from_gmt = '" . $this->session->userdata('timezone') . "' ORDER BY id LIMIT 0,1";
                    $res = $this->db->query($query);

                    foreach ($res->result() as $row) {
                        date_default_timezone_set($row->time_zone);
                        $this->session->set_userdata('time_zone', $row->time_zone);
                    }

                    // set the session variables
                    $query = "select js_date_format from date_formats where md5(format) = md5('" . $this->session->userdata('date_format') . "')";
                    $res = $this->db->query($query);

                    $data['js_date_format'] = "";
                    foreach ($res->result() as $row) {
                        $data['js_date_format'] = $row->js_date_format;
                    }
                    //$this->session->set_userdata($data);
                    $query = "select js_time_format from time_formats where md5(format) = md5('" . $this->session->userdata('time_format') . "')";
                    $res = $this->db->query($query);
                    $data['js_time_format'] = "";
                    foreach ($res->result() as $row) {
                        $data['js_time_format'] = $row->js_time_format;
                    }

                    $this->session->set_userdata($data);
                    // update the last login field for this user
                    $this->mdl_auth->update_timestamp('tbl_users', 'user_id', $user->user_id, 'last_login', time());

                    /* 		Create By default  Combo options		 */
                    $query = "select  id , assets_name, device_id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id =" . $user->user_id . ")) and del_date is null";
                    $res = $this->db->query($query);

                    $options = "";
                    foreach ($res->result() as $row) {

                        /* 	echo $this->db->last_query();
                          die(); */
                        $options .="<option value='" . $row->id . "'>" . $row->assets_name . "(" . $row->device_id . ")</option>";
                    }
                    $new_session_data = array();
                    $new_session_data["assets_option"] = $options;
                   
                    $sys_user_id = $this->session->userdata('user_id');
					//Get last login time of user and save in session
					$SQL1 = "SELECT last_login_time FROM sys_information WHERE user_id = " . $this->session->userdata('user_id') . " order by add_date desc limit 1";
                    $query1 = $this->db->query($SQL1);
                    $i = 0;
                    $row = $query1->result();
                    if (isset($row[0])) {
                        $new_session_data['last_login_time'] = $row[0]->last_login_time;
                    } else {
                        $new_session_data['last_login_time'] = "";
                    }
                    $this->session->set_userdata('last_login_time', $new_session_data['last_login_time']);
					//end
					
                    $date = date("Y-m-d");
                    //$user_os = $this->mdl_sessions->getOS();
                    //$user_browser = $this->mdl_sessions->browser_detect();
					//Get Country,City details from logged in user IP  address 
					$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$this->mdl_sessions->getRealIpAddr());
                    $country= $xml->geoplugin_countryName ;
					$city=$xml->geoplugin_city ;
					$lati=$xml->geoplugin_latitude;
					$longi=$xml->geoplugin_longitude;
					//Get browser and OS deatils using CI's User_agent library.
					$browser=$this->agent->browser();
					$platform=$this->agent->platform();
                    $ip = $_SERVER["REMOTE_ADDR"];
                     //Insert record in sys_information table   
                    $datanya = array('ip_address' => $ip, 'country_name' => "$country", 'state_name' => $data['RegionName'], 'city_name' => "$city", 'os_name' => $platform, 'device' => $browser, 'latitude' => $lati, 'longitude' => $longi, 'last_login_time' => date("Y-m-d G:i:s"), 'user_id' => $sys_user_id, 'add_date' => gmdate("Y-m-d H:i:s"), 'add_uid' => '1', 'status' => '1', 'comments' => '');
                   
                    $new_session_data["sys_info_id"] = $this->mdl_auth->save($datanya);
					
					//save last insert id in session variable.
					$this->session->set_userdata('sys_info_id', $new_session_data['sys_info_id']);
					
                    $this->db->where('country', Null);
                    $this->db->where('state', NUll);
                    $query = $this->db->get('language_master');
                    foreach ($query->result() as $row) {
                        $language[] = $row->language_name;
                    }

                    $this->db->where('country', $data['CountryName']);
                    $this->db->where('state', NUll);
                    $query = $this->db->get('language_master');
                    foreach ($query->result() as $row) {
                        $language[] = $row->language_name;
                    }

                    $this->db->where('country', $data['CountryName']);
                    $this->db->where('state', $data['RegionName']);
                    $query = $this->db->get('language_master');
                    foreach ($query->result() as $row) {
                        $language[] = $row->language_name;
                    }
                    $new_session_data = array();
                    $new_session_data["disp_language_list"] = implode(";", $language);
                    
                    //$new_session_data["sys_info_id"] = $this->mdl_auth->save($datanya);

                    $this->session->set_userdata($new_session_data);
                    if ($this->session->userdata('user_id') != 1) {
                        if ($this->session->userdata('admin_id') != "") {
                            $dates = date("d.m.Y h:i a");
                            $tz = " (GMT " . $this->session->userdata('timezone') . ")";
                            $datesa = gmdate("Y-m-d H:i:s");
                            $user_id = $this->session->userdata('admin_id');
                            $first_name = $this->session->userdata('first_name');
                            $last_name = $this->session->userdata('last_name');

                            $array = array(
                                'alert_header' => 'Login Info',
                                'alert_msg' => 'User Name : ' . $first_name . ' ' . $last_name . '<br> Login Time : ' . $dates . $tz,
                                'alert_link' => '',
                                'alert_type' => 'alert',
                                'user_id' => $user_id,
                                'add_date' => $datesa
                            );
                            $this->db->insert('alert_master', $array);
                        }
                    }
                    //redirect('home');
                    if ($this->session->userdata('def_dash_view') == "tree_view") {
                        redirect('tree');
                    } else {
                        redirect('home');
                    }
                } else {
                    $id = $this->mdl_auth->check_user_name($this->input->post('username'));
                    if ($id) {
                        $user_os = $this->mdl_sessions->getOS();
                        $user_browser = $this->mdl_sessions->browser_detect();
                        $ip = $_SERVER["REMOTE_ADDR"];
                        //	$ip = '59.95.198.247'; 
                        $data = array();
                        $datanya = array();
						$browser=$this->agent->browser();
                        /*
                          $xml = simplexml_load_file("http://api.ipinfodb.com/v2/ip_query.php?key=00056324c5738a4acdc40d77dcce046e79907fef5a0684b2ae999c2a2fa61ecc&ip=".$ip."&timezone=true");
                          foreach($xml->children() as $child)
                          {
                          $data[$child->getName()] = $child."";
                          }
                          $data['username']=$this->input->post('username');
                          $data['password']=$this->input->post('password');
                          $data['password']=$this->input->post('password');
                          $datanya=array('ip_address'=>$ip,'country_name'=>$data['CountryName'],'state_name'=>$data['RegionName'],'city_name'=>$data['City'],'os_name'=>$user_os,'device'=>$user_browser,'latitude'=>$data['Latitude'],'longitude'=>$data['Longitude'],'user_id'=>$id,'add_date'=>gmdate("Y-m-d H:i:s"),'add_uid'=>$id,'status'=>'1','comments'=>'');
                          $this->mdl_auth->save_failed($datanya);
                         */
                        //	die();
                    }

                    /*

                      $datanya=array('ip_address'=>$ip,'country_name'=>$data['CountryName'],'state_name'=>$data['RegionName'],'city_name'=>$data['City'],'os_name'=>$user_os,'device'=>$user_browser,'latitude'=>$data['Latitude'],'longitude'=>$data['Longitude'],'last_login_time'=>date("Y-m-d H:i:s"),'user_id'=>$sys_user_id,'add_date'=>date("Y-m-d H:i:s"),'add_uid'=>'1','status'=>'1','comments'=>'');
                      $new_session_data["sys_info_id"] =  $this->mdl_auth->save($datanya);
                     */
                    //$this->messages->add('No Assets assigned!', 'error');
                }
            } else {
                $id = $this->mdl_auth->check_user_name($this->input->post('username'));
                if ($id) {
				    $datanya = array();
                    $user_os = $this->mdl_sessions->getOS();
                    $user_browser = $this->mdl_sessions->browser_detect();
					//$data=$this->mdl_sessions->get_country_by_ip();
					
                    $ip = $_SERVER["REMOTE_ADDR"];
					$browser=$this->agent->browser();
					$platform=$this->agent->platform();
					
				    $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$this->mdl_sessions->getRealIpAddr());
                    $country= $xml->geoplugin_countryName ;
					$city=$xml->geoplugin_city ;
					$lati=$xml->geoplugin_latitude;
					$longi=$xml->geoplugin_longitude;
                   
					$browser=$this->agent->browser();
					$platform=$this->agent->platform();
					
					 $datanya=array('ip_address'=>$ip,'country_name'=>"$country",'city_name'=>"$city",'os_name'=>$platform,'device'=>$browser,'latitude'=>$lati,'longitude'=>$longi,'user_id'=>$id,'add_date'=>gmdate("Y-m-d H:i:s"),'add_uid'=>$id,'status'=>'1','comments'=>'');
                    $this->mdl_auth->save_failed($datanya);
                    
                     	
                }

                //$datanya = array('ip_address' => $ip, 'country_name' => $data['CountryName'], 'state_name' => $data['RegionName'], 'city_name' => $data['City'], 'os_name' => $user_os, 'device' => $user_browser, 'latitude' => $data['Latitude'], 'longitude' => $data['Longitude'], 'last_login_time' => date("Y-m-d H:i:s"), 'user_id' => $sys_user_id, 'add_date' => date("Y-m-d H:i:s"), 'add_uid' => '1', 'status' => '1', 'comments' => '');

                //$new_session_data["sys_info_id"] = $this->mdl_auth->save($datanya);

                $this->messages->add('Invalid Username or Password.', 'error');
            }
        }
	$query = $this->db->get('language_master');
		foreach ($query->result() as $row)
		{
		  $sel = '';
		  if($row->language_name == 'portuguese') $sel = ' selected="ture"';
		  $data['langopt'] .= "<option value='".$row->language_name."'$sel>".ucfirst($row->language_name)."</option>";
		 //$data['langopt'] .= "<option value='".$row->language_name."'>".ucfirst($row->language_name)."</option>";
		}

        $this->load->view('login1',$data);
    }

    function login_outside() {
        $get = $this->uri->uri_to_assoc();

        $this->_load_language();

        $this->load->helper(array('url', 'form'));

        $this->load->library('messages');

        $this->load->model('mdl_sessions');

        if ($get['username'] != "" && $get['password'] != "") {

            $this->load->model('mdl_auth');

            if ($user = $this->mdl_auth->auth('tbl_users', 'username', 'password', $get['username'], md5($get['password']))) {

                $object_vars = array('user_id', 'last_name', 'first_name', 'global_admin');

                // set the session variables
                $this->mdl_auth->set_session($user, $object_vars, array('is_admin' => TRUE));

                // update the last login field for this user
                $this->mdl_auth->update_timestamp('tbl_users', 'user_id', $user->user_id, 'last_login', time());

                redirect('home');
            }
        }
    }

    function logout() {
	
	     $query = "SELECT time_zone FROM timezone WHERE diff_from_gmt = '" . $this->session->userdata('timezone') . "' ORDER BY id LIMIT 0,1";
	   
         $res = $this->db->query($query);

         foreach ($res->result() as $row) {
			//echo $row->time_zone;
            date_default_timezone_set($row->time_zone);
		  
       }
        $last_login_time = '';
        $this->load->model('mdl_auth');
        $sys_id = $this->session->userdata('sys_info_id');
        $logo = $this->session->userdata('user_logo');
        $date = date("Y-m-d");
        $startDate = date("Y-m-d G:i:s");
		
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
        $this->load->helper('url');

        $this->session->sess_destroy();
         //die($logo);
        if ($logo != 'nKonnect') {
            redirect('sessions/login1');
        }

        redirect('sessions/login');
    }

    function _load_language() {

        $this->load->model('mcb_data/mdl_mcb_data');

        $default_language = $this->mdl_mcb_data->get('default_language');

        if ($default_language) {

            $this->load->language('english', $default_language);
        } else {

            $this->load->language('english');
        }
    }

    //function for get location by curl
    function location_outside() {
        $this->load->library(array('session'));

        $this->load->database();

        $get = $this->uri->uri_to_assoc();

        $this->_load_language();

        $this->load->helper(array('url', 'form'));

        $this->load->model('mdl_sessions');
        //echo $get['username']." ".$get['password'];
        //exit();
        if ($get['username'] != "" && $get['password'] != "") {

            $this->load->model('mdl_auth');

            if ($user = $this->mdl_auth->auth('tbl_users', 'username', 'password', $get['username'], md5($get['password']))) {

                $object_vars = array('user_id', 'last_name', 'first_name', 'global_admin');

                // set the session variables
                $this->mdl_auth->set_session($user, $object_vars, array('is_admin' => TRUE));



                // update the last login field for this user
                $this->mdl_auth->update_timestamp('tbl_users', 'user_id', $user->user_id, 'last_login', time());

                $rows = $this->mdl_auth->get_location($user->user_id);

                $data = array();

                if (count($rows) > 0) {
                    foreach ($rows as $coord) {

                        //$text  = 'Lat : '.$coord->lati."<br>";
                        //$text .= 'Lng : '.$coord->longi."<br>";
                        $text = 'Time : ' . date('d.m.Y h:i a', strtotime($coord->add_date));
                        $text .= '<br>Speed : ' . $coord->speed;
                        $text .= '<br>Device : ' . $coord->assets_name;
                        $text .= '<br>Address : ' . $coord->address;

                        $data[$coord->device_id]['Lat'] = $coord->lati;
                        $data[$coord->device_id]['Lng'] = $coord->longi;
                        $data[$coord->device_id]['Date'] = $coord->add_date;
                        $data[$coord->device_id]['Speed'] = $coord->speed;
                        $data[$coord->device_id]['Device'] = $coord->assets_name . ' (' . $coord->device_id . ')';
                        $data[$coord->device_id]['info'] = $text;
                        $data[$coord->device_id]['title'] = $coord->assets_name;
                    }
                } else {

                    $coords[] = array("lati" => 22.297744, "longi" => 70.792444);
                }

                //$data['coords'] = $coords;

                die(json_encode($data));
            }
        }
    }

    //function for get location by curl
    function alert_outside() {
        $this->load->library(array('session'));

        $this->load->database();

        $get = $this->uri->uri_to_assoc();

        $this->_load_language();

        $this->load->helper(array('url', 'form'));

        $this->load->model('mdl_sessions');
        //echo $get['username']." ".$get['password'];
        //exit();
        if ($get['username'] != "" && $get['password'] != "") {

            $this->load->model('mdl_auth');

            if ($user = $this->mdl_auth->auth('tbl_users', 'username', 'password', $get['username'], md5($get['password']))) {

                $object_vars = array('user_id', 'last_name', 'first_name', 'global_admin');

                // set the session variables
                $this->mdl_auth->set_session($user, $object_vars, array('is_admin' => TRUE));



                // update the last login field for this user
                $this->mdl_auth->update_timestamp('tbl_users', 'user_id', $user->user_id, 'last_login', time());

                $rows = $this->mdl_auth->get_sms_log($user->user_id);

                $data = array();
                $html = '<div style="height:200px;overflow:auto;"><table align=center style="border:1px solid green;border-collapse: collapse;color:#fff;font-family: segoe ui, arial, helvetica, sans-serif;font-family: Verdana, Arial; font-size: 12px; line-height: 18px;" width="100%" cellspacing="1" cellpadding="4"><tr><td style="border:1px solid green;border-collapse: collapse;">Mobile No</td><td style="border:1px solid green;border-collapse: collapse;">Sms Text</td><td style="border:1px solid green;border-collapse: collapse;">Time</td></tr>';
                if (count($rows) > 0) {
                    foreach ($rows as $coord) {

                        $html .= "<tr><td style='border:1px solid green;border-collapse: collapse;'>" . $coord->mobile . "</td><td style='border:1px solid green;border-collapse: collapse;'>" . $coord->sms_text . "</td><td style='border:1px solid green;border-collapse: collapse;'>" . date('d.m.Y H:i:s', strtotime($coord->add_date)) . "</td></tr>";
                    }
                    $html .= "</table></div>";

                    die($html);
                } else {
                    die();
                }
            }
        }
    }

}

?>