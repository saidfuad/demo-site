<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {

        parent::__construct();


        if ($this->session->userdata('hawk_user_type_id') != 1) {
            redirect('home');
        }

        $this->load->model('mdl_admin_dashboard');
        $this->load->model('mdl_admin');
        $this->load->model('mdl_devices');
         $this->load->model('users/mdl_users');
        $this->load->model('mdl_gps_tracking');
        $this->load->library('encrypt');
        $this->load->library('smssend');
        $this->load->library('emailsend');
        $this->load->library('gps_utilities');

        $this->user_id = $this->session->userdata('hawk_user_id');
    }

    function getaddress($lat, $lng) {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=AIzaSyAzFof8b1BJz1t8K_rLafSS_Hah0Y4y1AA';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $contents = curl_exec($ch);

        if (curl_errno($ch)) {

            echo curl_error($ch);
            // echo "\n<br />";
            $contents = '';
        } else {
            curl_close($ch);
        }

        if (!is_string($contents) || !strlen($contents)) {

            echo "Failed to get contents.";
            return $contents = '';
        }

        $obj = json_decode($contents, true);
        return $obj["results"][0]["formatted_address"];
    }

    public function index() {

        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $coords = array();
        $data = array();
        $vehicleNames = array();
        $vehicleList = '';

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles(null, null);
        // print_r($vehicles);
        // exit;
        if (count($vehicles)) {
            foreach ($vehicles as $vehicle) {
                $txt = "";
                $txt = addslashes($vehicle->plate_no);
                if ($vehicle->model != "")
                    $txt.="(" . addslashes($vehicle->model) . ")";
                $vehicleNames[] = $txt;
                $vehicleList .= "<li vehicle-id='" . $vehicle->vehicle_id . "'><span class='fa fa-car'></span>&nbsp;" . $vehicle->plate_no . "</li>";
            }
        }else {
            $vehicleList .= "<li><span class='fa fa-car'></span>&nbsp;<a href='../index.php/vehicles/add_vehicle'>Add Vehicles</a></li>";
        }

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;
        $data['vehicleList'] = $vehicleList;
        $data['vehicleNames'] = $vehicleNames;

        $data['content_url'] = 'gps_tracking';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | GPS Tracking';
        $data['content_title'] = 'GPS Tracking';
        $data['content_subtitle'] = 'Vehicle Location Tracking';
        $data['content'] = 'admin/gps_tracking/gps_home2.php';

        $this->load->view('admin/main.php', $data);
    }

    public function refresh_grid() {

        $query = $this->input->post('query');

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles(null, $query);

        for ($i = 0; $i < sizeof($vehicles); $i++) {
            $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
        }

        $res = array('vehicles' => $vehicles);

        echo json_encode($vehicles);
    }

    public function filter_grid() {

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles(null, null);

        $res = array('vehicles' => $vehicles);

        echo json_encode($res);
    }
 
    public function sacco(){
    	$data ['saccos'] = $this->mdl_users->get_users( null, $this->config->item('sacco_user_type'));
         $data['content_url'] = 'admin/sacco';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | View SACCOs';
        $data['content_title'] = 'View SACCOs';
        $data['content_subtitle'] = '';
        $data['content'] = 'admin/sacco/sacco.php';
        $this->load->view('admin/main.php', $data);
    }

    public function add_sacco(){

        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] =  $this->user_id;
        $data['content_url'] = 'admin/add_sacco';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add SACCO';
        $data['content_title'] = 'Add SACCO';
        $data['content_subtitle'] = '';
        $data['content'] = 'admin/sacco/add_sacco.php';
        $this->load->view('admin/main.php', $data);
    }

     public function save_sacco () {
        $data['company_name'] = $this->input->post('company_name');
        $data['company_email'] = $this->input->post('company_email');
        $data['company_phone_no'] = $this->input->post('company_phone_no');
        $data['user_type_id'] = $this->config->item('sacco_user_type');

        $phone_no=  $data['company_phone_no'];
        $email =  $data['company_email'];
        $password  = $this->input->post('password');
       
        $message = "";
         if($email!= "")
        {
             $check_email= $this->mdl_users->check_email($email);
            if($check_email != null)
                $message ="email already exists.\n";
         }

        $check_phone= $this->mdl_users->check_phone_no($phone_no);
       if($check_phone != null)
            $message.="phone number already exists.\n";
        
        if($message!= "")
        {
            echo $message;
            return false;
        }
      
        $logins = $this->mdl_users->create_login_details($password, $phone_no ,  $email );
        $created_client = $this->mdl_users->save_user($data, $logins, "business");

        /* Send Notification */
        if($created_client["status"]== 1){
             /* SMS */
            $recipient = array($phone_no);
            $message = "HAWK SACCO Account Registration was Successful.\r\nPlease Login to your account using the following credentials.\r\nUrl  : ".base_url()." \r\nUserName : " 
            . $phone_no . " \r\nPassword : " . $password;
             $res = $this->smssend->send_text_message ($recipient, $message);
            
            if(!empty($email)){
                    $this->mdl_users->send_registration_email($email, $phone_no, $password);
            }
        }
        echo $created_client['status'];
    }
	
	public function save_installer() {
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['email'] = $this->input->post('email');
        $data['phone_no'] = $this->input->post('phone_no');
        $data['user_type_id'] = $this->input->post('user_type_id');
        $data['add_uid'] = $this->input->post('add_uid');
        $data['account_id'] = $this->input->post('account_id');

        $check_email= $this->mdl_users->check_email($data['email']);
        $check_phone= $this->mdl_users->check_phone_no($data['phone_no']);

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
        
         $phone_no = str_split($data['phone_no'] );
         $phone_array = sizeof($phone_no);
         $phone_array = $phone_array - 1;
         $password = strtolower($data['first_name'] . $phone_no[$phone_array - 3] 
        . $phone_no[$phone_array - 2] . $phone_no[$phone_array - 1] 
        . $phone_no[$phone_array]);
      
        $logins = $this->mdl_users->create_login_details($password, $data['phone_no'] , $data['email'] );
        $created_client = $this->mdl_users->save_user($data, $logins);

        /* Send Notification */
        if($created_client["status"]== 1){
             /* SMS */
            $recipient = array($data['phone_no']);
            $message = "HAWK Installer Account Created Successfully.\r\nPlease Login to the installer app using the following credentials. \r\nUserName : " 
            . $data['phone_no'] . " \r\nPassword : " . $password;
             $res = $this->smssend->send_text_message ($recipient, $message);
            
            if(!empty($email)){
                    //$this->mdl_users->send_registration_email($email, $phone_no, $password);
            }
        }
        echo $created_client['status'];
   }

    public function edit_sacco ($user_id) {
        
        $data['user'] = $this->mdl_users->get_user_by_id($user_id);
        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] =  $this->user_id;
        
        $data['content_url'] = 'users/edit_user';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit User';
        $data['content_title'] = 'Edit User Details';
        $data['content_subtitle'] = 'User Details';

        $data['content'] = 'admin/users/edit_user.php';
        $this->load->view('admin/main.php', $data);
    }

     public function update_sacco () {
        $data = $this->input->post();

        $check= $this->mdl_users->check_emaill($this->input->post('email'),$this->input->post('user_id'));

        if($check!=""){
            echo false;
        }
        else{
        $dataa['email']=$this->input->post('email');
        $dataa['user_id']=$this->input->post('user_id');

        echo $this->mdl_users->update_user($data,$dataa);
        }
    }
}
