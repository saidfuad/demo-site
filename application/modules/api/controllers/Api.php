<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Api
 *
 * @author Benson
 */
class Api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Mdl_api');
        $this->load->model('sacco/mdl_sacco');
        $this->load->model('users/mdl_users');
        $this->load->library('smssend');
        //$this->load->library('../login/controllers/Login');
    }

    function mpesa_post() {
        echo "ok";
    }

    function app_auth_get() {
        $data = $this->input->get();
        $result = $this->Mdl_api->app_auth($data['email'], $data['password']);

        if ($result) {
            $this->response(array("status" => 1, "data" => $result), 200);
        } else {
            $this->response(array("status" => 10, "data" => "Wrong username and/password!"), 200);
        }
    }

    function get_account_id_post() {
        $data = $this->input->post();
        $result = $this->Mdl_api->get_account($data['account_name']);

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array("status" => 10, "data" => "That Account Name Does Not Exist!"), 200);
        }
    }

    function get_phone_no_post() {
        $data = $this->input->post();
        $result = $this->Mdl_api->vehicle($data['plate_no']);

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array("status" => 10, "data" => "That Vehicle With That Plate Number Does Not Exist!"), 200);
        }
    }

    function add_account_post() {
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['phone_no'] = $this->input->post('phone_no');

        $phone_no = str_split($this->input->post('phone_no'));
        $phone_array = sizeof($phone_no);
        $phone_array = $phone_array - 1;

        $password = strtolower($this->input->post('first_name')) . $phone_no[$phone_array - 3] . $phone_no[$phone_array - 2] . $phone_no[$phone_array - 1] . $phone_no[$phone_array];

        $logins['phone_no'] = $this->input->post('phone_no');

        $logins['password'] = $this->encrypt->encode($password);


        $account['account_type'] = 'personal';


        $account['account_name'] = $this->Mdl_api->account_name($logins);

        if ($account['account_name'] == 3) {
            $this->response(array("status" => 10, "data" => array("message" => "Phone Number Already In Use!")), 200);
        } else {
            $data['account_id'] = $this->Mdl_api->open_account($account);

            $logins['user_id'] = $this->Mdl_api->save_account($data);

            $created_client = $this->Mdl_api->save_logins($logins);

            /* Send Notification */

            if ($created_client == 1) {

                $notif['phone_no'] = $this->input->post('phone_no');
                $notif['email'] = $this->input->post('email');

                /* SMS */
                $recipient = array($notif['phone_no']);
                $message = "HAWK Account Registration was Successful. Your Account Name is: " . $account['account_name'] . " Your Account UserName is: " . $this->input->post('phone_no') . " and Password is " . $password . "";

                $res = $this->smssend->send_text_message($recipient, $message);

                $this->response(array("status" => 1, "data" => array("account_id" => $data['account_id'])), 200);
            }
        }
    }

    function add_vehicle_post() {
        $data['plate_no'] = $this->input->post('plate_no');
        $data['model'] = $this->input->post('model');
        $data['max_speed_limit'] = $this->input->post('max_speed_limit');
        $data['account_id'] = $this->input->post('account_id');
        $data['add_uid'] = $this->input->post('user_id');

        $check = $this->Mdl_api->save_vehicle($data);

        if ($check == 3) {
            $this->response(array("status" => 10, "data" => array("message" => "Vehicle Plate Number Exists!")), 200);
        } else {
            $this->response(array("status" => 1, "data" => array("vehicle_id" => $check)), 200);
        }
    }

    function get_vehicles_details_get() {
        $plate_no = $this->input->get("plate_no");
        $result = $this->Mdl_api->get_vehicles_details($plate_no);

        if ($result) {
            if ($result[0]["device_id"] == null)
                $this->response(array("status" => 1, "data" => $result), 200);
            else
                $this->response(array("status" => 2, "data" => array("message" => "Vehicle Linked already!")), 200);
        } else {
            $this->response(array("status" => 10, "data" => array("message" => "Vehicle Not found!")), 200);
        }
    }
    
     function get_vehicle_dets_get(){
        $data = $this->input->get();
        $result = $this->Mdl_api->get_vehicles_dets($data["plate_no"]);

        if($result){
            $this->response($result,200);
        }else{
            $this->response(array("status" => 10, "data" => array("message" => "Error Encountered!")));
        }
    }


    function assign_device_post() {
        $data = $this->input->post();
        $check = $this->check_assign_device_post($data);


        if ($check == 1) {
            $result = $this->Mdl_api->assign_device($data['vehicle_id'], $data['serial_no'], $data['user_id'], $data['account_id'], $data['latitude'], $data['longitude']);

            if ($result) {
                $this->response(array("status" => 1, "data" => $result), 200);
            } else {
                $this->response(array("status" => 10, "data" => array("message" => "Device Not Assigned!")), 200);
            }
        } else {
            $this->response(array("status" => 10, "data" => $check), 200);
        }
    }

    function check_assign_device_post($post) {
        $message = "";
        $check = true;

        if ($post['vehicle_id'] == NULL || $post['vehicle_id'] == '') {
            $message = "vehicle_id cannot be null or empty ";
            $check = false;
        }

        if ($post['serial_no'] == NULL || $post['serial_no'] == '') {
            $message = "$mesage serial_no cannot be null or empty ";
            $check = false;
        }


        if ($post['account_id'] == NULL || $post['account_id'] == '') {
            $message = "$message account_id cannot be null or empty ";
            $check = false;
        }

        if ($post['user_id'] == NULL || $post['user_id'] == '') {
            $message = "$message user_id cannot be null or empty ";
            $check = false;
        }

        if (array_key_exists('latitude', $post)) {

            if ($post['latitude'] == '' || $post['latitude'] == NULL) {
                $message = "$message latitude cannot be null or empty ";
                $check = false;
            }
        } else {

            $message = "$message latitude not defined ";
            $check = false;
        }

        if (array_key_exists('longitude', $post)) {
            if ($post['longitude'] = '' || $post['longitude'] == NULL) {
                $message = "$message longitude cannot be null or empty ";
                $check = false;
            }
        } else {
            $message = "$message longitude not defined ";
            $check = false;
        }


        //echo "chec is $check";
        if ($check == 1) {
            return true;
        } else {
            return $message;
        }
    }

    /**
     * Get all SACCOs
     */
    function get_saccos_get() {
    
        $saccos = $this->mdl_sacco->get_saccos();
    
        if ($saccos) {
            $this->response(array("status" => 1, "data" => $saccos), 200);
        } else {
            $this->response(array("status" => 1, "data" => array("message" => "SACCOs Not found!")), 200);
        }
    }
    
    function create_sacco_member_post(){
        $sacco_id = $this->input->post("sacco_id");
        $first_name = $this->input->post("first_name");
        $last_name = $this->input->post("last_name");
        $phone_no = $this->input->post("phone_no");
        $plate_no = $this->input->post("plate_no");
        $user_id = $this->input->post("user_id");
        
        $saccos = $this->mdl_users->create_sacco_member($sacco_id,$first_name,$last_name,$phone_no,$plate_no,$user_id);
         $this->response($saccos, 200);
    
    }

}
