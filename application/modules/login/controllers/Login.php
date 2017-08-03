<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library('user_agent');
        $this->load->library('encrypt');
        $this->load->model('users/mdl_users');
        $this->load->model('mdl_auth');
        $this->load->model('mdl_sessions');
        $this->load->model('api/Mdl_apimobile');
        $this->load->library('sendmail');
        $this->load->library('smssend');
        $this->load->helper('string');
    }

    public function index() {
        $this->load->view('login');
    }

    public function authenticate() {

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $encrypted_password = $this->encrypt->encode($password);

        
        // print_r($encrypted_password);
        // exit;

        $browser = $this->agent->browser();
        $platform = $this->agent->platform();

        /* $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$this->mdl_sessions->getRealIpAddr());
          $country= $xml->geoplugin_countryName ;
          $city=$xml->geoplugin_city ;
          $lati=$xml->geoplugin_latitude;
          $longi=$xml->geoplugin_longitude;
          $browser=$this->agent->browser();
          $platform=$this->agent->platform();
          $ip = $_SERVER["REMOTE_ADDR"];
         */
        $country = '';
        $city = '';
        $ip = '';
        $browser = '';
        $platform = '';
        $lati = '';
        $longi = '';
        $datanya = array('ip_address' => $ip,
            'country_name' => "$country",
            'city_name' => "$city",
            'os_name' => $platform,
            'device' => $browser,
            'latitude' => $lati,
            'longitude' => $longi,
            'last_login_time' => date("Y-m-d H:i:s"),
            'user_id' => '',
            'add_date' => gmdate("Y-m-d H:i:s"),
            'comments' => '');

        $emailExists = $this->mdl_auth->check_email($email);


        //print_r($emailExists);
        //exit;
        if ($emailExists) {
            $user_id = $emailExists['user_id'];
        } else {
            $datanya['comments'] = 'email passed:' . $email;
            unset($datanya['last_login_time']);
            //$this->mdl_auth->save_failed($datanya);
            echo json_encode(array('type' => 'error', 'title' => 'Access Denied', 'message' => 'Check email and or password and try again'));
            exit;
        }

        $isValid = false;
        $type = 'error';


        $user = $this->mdl_auth->auth('logins', 'email', 'password', $email, $encrypted_password);

      //print_r($user);

        if ($user) {
            if ($this->encrypt->decode($user['password']) == $password) {
                $isValid = true;
                $type = 'success';
            }
        }



        if ($isValid) {

           
            ini_set('session.gc_maxlifetime', 10 * 60 * 60);
            $_SESSION['hawk_user_id'] = $user['user_id'];
            $_SESSION['hawk_account_name'] = $user['account_name'];
            $_SESSION['hawk_user_type_id'] = $user['user_type_id'];
            $_SESSION['hawk_account_id'] = $user['account_id'];


            if ($this->session->userdata('hawk_user_type_id') == 1) {
                echo json_encode(array('type' => 'success', 'title' => 'Success', 'message' => 'redirect_admin'));
            } else if ($this->session->userdata('hawk_user_type_id') == 2) {
                echo json_encode(array('type' => 'success', 'title' => 'Success', 'message' => 'redirect_gps'));
            } else if ($this->session->userdata('hawk_user_type_id') == 3) {
                echo json_encode(array('type' => 'success', 'title' => 'Success', 'message' => 'redirect_normal'));
            }else if ($this->session->userdata('hawk_user_type_id') == $this->config->item("ntsa_user_type")) {
                echo json_encode(array('type' => 'success', 'title' => 'Success', 'message' => 'redirect_ntsa'));
            }else if ($this->session->userdata('hawk_user_type_id') == $this->config->item("sacco_user_type")) {
                echo json_encode(array('type' => 'success', 'title' => 'Success', 'message' => 'redirect_sacco'));
            }

        } else {
            echo json_encode(array('type' => 'error', 'title' => 'Access Denied', 'message' => 'Check email and or password and try again'));
            exit;
        }
    }

    function logout() {

        $last_login_time = '';
        $sys_id = $this->session->userdata('sys_info_id');
        $date = date("Y-m-d");
        $startDate = date("Y-m-d H:i:s");

        // $SQL1 = "SELECT last_login_time FROM sys_information WHERE id ='" . $this->session->userdata('sys_info_id') . "'";
        // $query1 = $this->db->query($SQL1);

        // if ($query1->num_rows() > 0) {
        //     $i = 0;
        //     $row = $query1->result();
        //     $endDate = $row[0]->last_login_time;
        //     $diff_time = strtotime($startDate) - strtotime($endDate);

        //     $datanya = array('last_login_time' => $endDate, 'last_logout_time' => $startDate, 'duration_of_stay' => $diff_time);

        //     $this->mdl_auth->update_sys_info($sys_id, $datanya);
        // }

        $this->session->sess_destroy();
        redirect('login');
    }

    function forgot(){
        $this->load->view('forgot');
    }

     function forgot_password(){
      $data = $this->input->post();
      $result = $this->mdl_auth->forgot($data['email']);

      if($result){
        echo json_encode(array('type' => 'success', 'title' => 'Success', 'message' => 'redirect_reset'));
      }else{
        echo json_encode(array('type' => 'failure', 'title' => 'Failure', 'message' => 'redirect_login'));
      }
    }
    
    function reset_password(){
        $this->load->view('reset');
    }
    
    function reset(){
        
        $email = $this->input->post('email');
        $emailExists = $this->mdl_auth->check_email($email);
        
        if ($emailExists) {
            
            $user_id = $emailExists['user_id'];
            $company_id = $emailExists['company_id'];
            
            $new_pass = uniqid();
            $encrypted_password = $this->encrypt->encode($new_pass);
            
            $data['user_id'] = $user_id;
            $data['company_id'] = $company_id;
            $data['password'] = $encrypted_password;
            
            $pass_reset = $this->mdl_auth->reset_password($data);
            
            if($pass_reset) {
                
                $new_password = $new_pass;
                $email_recipient = $email;
                $email_alert = $this->pass_reset_alert ($email_recipient, $new_password);
                
                echo 1;
            }   
            
        } else {
            echo -1;
        }  
    }
    
    function pass_reset_alert ($email_recipient, $new_password) {

        $to = array($email_recipient);
        $subj = "ITMS Company - User Password Reset";
        $url = "http://178.63.90.134/itmsafrica/assets/images/system/logo1.png";
        
        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                        <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                            <h1><img src="'.$url.'"></h1>
                        </div>
                        <div style="padding:20px;">
                            Dear User,<br><br>
                            Your ITMS user password has been reset.
                            You can access your account with your new login details below.
                            <br>
                            <br>
                            New Login Details<br>
                            Email : ' . $email_recipient . '<br>
                            Password: ' . $new_password . ' <br>
                            <br>
                            <br>
                            Verify carefully the Company information.
                            <br>
                            In case of any doubts, please feel free to contact ITMS Registrar.<br>
                            <a href="#">ITMSLive16@gmail.com</a> on or <a href="#">+254 (0)729 220 777</a>
                            <br>                        
                        </div>
                    </div>';

        return $this->send_email_message ($to, $subj, $message);
    }
    
    function send_email_message ($to, $subj, $message) {
        return $this->emailsend->send_email_message ($to, $subj, $message);
    }
    
    function new_account(){
        $this->load->view('new_account');
    }
    
    function create_account(){
        $email = "";
        $password = "";
        $phone_no = "";
        $account_type = "";

        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['phone_no'] = $this->input->post('phone_no');
        $data['email'] = $this->input->post('email');

        $data['company_name'] = $this->input->post('company_name');
        $data['company_email'] = $this->input->post('company_email');
        $data['company_phone_no'] = $this->input->post('company_phone_no');

        if($this->input->post('select_type') == 1){
            $account_type= 'personal';
            $password = $this->input->post('password');
            if(!empty($this->input->post('email'))){
                $email = $this->input->post('email');
            }
            $phone_no = $this->input->post('phone_no');
        }else{
            $account_type= 'business';
            $password= $this->input->post('company_password');
            if(!empty($this->input->post('company_email'))){
               $email= $this->input->post('company_email');
            }
            $phone_no = $this->input->post('company_phone_no');
        }
        


        $check_email= $this->mdl_users->check_email($email);
        $check_phone= $this->mdl_users->check_phone_no($phone_no);
        $message = "";

        if($check_email != null)
            $message ="email already exists.\n";

        if($check_phone != null)
            $message.="phone number already exists.\n";

        if($message!= "")
        {
            echo $message;
            return false;
        }


        $logins = $this->mdl_users->create_login_details($password, $phone_no, $email);
        $created_client = $this->mdl_users->save_user($data, $logins, $account_type);

        /* Send Notification */
        if($created_client["status"]== 1){
             /* SMS */
            $recipient = array($phone_no);
            $message = "HAWK Account Registration was Successful.\r\nPlease Login to your account using the following credentials.\r\nUrl  : ".base_url()." \r\nUserName : " 
            . $phone_no . " \r\nPassword : " . $password. "\r\nYour Account ID is: ".$created_client["account_name"]."";
             $res = $this->smssend->send_text_message ($recipient, $message);
            
            if(!empty($email)){
                    $this->mdl_users->send_registration_email($email, $phone_no, $password);
            }
        }
        echo $created_client['status'];
    }
}