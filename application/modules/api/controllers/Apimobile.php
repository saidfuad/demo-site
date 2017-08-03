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
class Apimobile extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("encrypt");
        $this->load->model('Mdl_apimobile');
        $this->load->library('smssend');
        $this->load->library('emailsend');
        $this->load->library('smtpapi');
        $this->load->model('accounting/mdl_accounting');
        $this->load->model('users/mdl_users');
        $this->load->model('reminders/mdl_reminders');
        
        //$this->load->library('../login/controllers/Login');
    }

   /**/

    function app_auth_post() {
        $data = $this->input->post();
        $result = $this->Mdl_apimobile->app_auth($data['username'], $data['password']);

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array("status" => 10, "data" => array("message" => "Wrong username and/password!")), 200);
        }
    }


    function app_auth_ios_post() {
        $data =json_decode($this->input->post(), TRUE); 
        $result = $this->Mdl_api->app_auth($data['email'], $data['password']);

        if ($result) {
            $this->response(array("status" => 1, "data" => $result), 200);
        } else {
            $this->response(array("status" => 10, "data" => "Wrong username and/password!"), 200);
        }
           $details = json_decode($this->input->post('details'), TRUE);
    }


    function vehicle_list_post(){
      $data = $this->input->post();
      $result = $this->Mdl_apimobile->vehicles($data['user_id']);

      if($result){
        $this->response($result,200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Vehicles Added")), 200);
      }
      
    }

    function vehicle_details_post(){
      $data = $this->input->post();
      $result = $this->Mdl_apimobile->vehicle($data['vehicle_id']);

      if($result){
        $this->response($result,200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Vehicle Details Found")), 200);
      }
    }

    function forgot_password_post(){
      $data = $this->input->post();
      $result = $this->Mdl_apimobile->forgot($data['input']);

      if(!empty($result)){
         
            $this->load->helper('string');
            $newpass= strtolower(random_string('alnum', 6));
            
            $new_password=$this->encrypt->encode($newpass);
            if(!empty($result['email'])){
            // $to = array($result['email']);
            $subj = "Hawk - User Password Reset";
            $url = "http://alwayswatching.co.ke/hawk/";
            
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
                                Email : ' . $result['email'] . '<br>
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

           

            //$this->emailsend->send_email_message ($to, $subj, $message);
                $this->sendMail($result['email'],$subj,$message);
        }
        $recipient = array($result['phone_no']);
        $message = "Hawk - User Password Reset.\r\nLogin to your account using the following credentials Then Change The Password.\r\nUrl  :  http://alwayswatching.co.ke/hawk/. \r\nUserName : " . $result['phone_no'] . " \r\nPassword : " . $newpass . "";

        $this->smssend->send_text_message($recipient, $message);

        $this->Mdl_apimobile->change_pass($result['user_id'],$new_password);
        $this->response(array("status" => 1, "data" => array("message"=>"An Email/SMS has been sent with your login details")),200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "Failed..Email Not Found")), 200);
      }
    }

    function create_account_post(){
        if(!empty($this->input->post('phone_no'))){
          $data['first_name'] = $this->input->post('first_name');
          $data['last_name'] = $this->input->post('last_name');
          $data['phone_no'] = $this->input->post('phone_no');
        }
        if(!empty($this->input->post('email'))){
          $data['email'] = $this->input->post('email');
        }
        if(!empty($this->input->post('company_email'))){
          $data['company_email'] = $this->input->post('company_email');
        }
        if(!empty($this->input->post('company_phone_no'))){
        $data['company_name'] = $this->input->post('company_name');
        $data['company_phone_no'] = $this->input->post('company_phone_no');
        }
        $select = $this->input->post('select_type');

        if($select==1){

            $logins['password'] = $this->input->post('password');
            if(!empty($this->input->post('email'))){
                $logins['email'] = $this->input->post('email');
            }
            $logins['phone_no']=$this->input->post('phone_no');

            $phone_no=$this->input->post('phone_no');
            $pass=$this->input->post('password');

            $logins['password'] = $this->encrypt->encode($logins['password']);

        }else{

            $logins['password'] = $this->input->post('company_password');
            if(!empty($this->input->post('company_email'))){
               $logins['email'] = $this->input->post('company_email');
            }
            
            $logins['phone_no']=$this->input->post('company_phone_no');
            $phone_no=$this->input->post('company_phone_no');
            $pass=$this->input->post('company_password');

            $logins['password'] = $this->encrypt->encode($logins['password']);
        }

        if($this->input->post('select_type') == 1){

            $account['account_type'] = 'personal';

        }else{

            $account['account_type'] = 'business';
        }

        $checkp= $this->Mdl_apimobile->account_name($logins);

        if($checkp == 2){
            
            $this->response(array("status" => 10, "data" => array("message" => "Account Registration Failed. Phone Number Already Exists")), 200);
        }

        $data['account_id'] = $this->Mdl_apimobile->open_account($account);

        $logins['user_id'] = $this->Mdl_apimobile->save_account($select, $data);

        if($logins['user_id']=="phone_exists"){
            
          $this->response(array("status" => 10, "data" => array("message" => "Account Registration Failed. Phone Number Already Exists")), 200);
        }else if($logins['user_id']=="email_exists"){
           
            $this->response(array("status" => 10, "data" => array("message" => "Account Registration Failed. Email Already Exists")), 200);
        }

        $created_client = $this->Mdl_apimobile->save_logins($logins);

        /* Send Notification */

        if($created_client == 1){

            if($this->input->post('phone_no') == null){

                $notif['phone_no'] = $this->input->post('company_phone_no');
                $notif['email'] = $this->input->post('company_email');

            }else{

                $notif['phone_no'] = $this->input->post('phone_no');
                $notif['email'] = $this->input->post('email');

            }

            /* SMS */
            $recipient = array($notif['phone_no']);
            if(!empty($this->input->post('phone_no'))){

                $message = "HAWK Account Registration was Successful.\r\nPlease Login to your account using the following credentials.\r\nUrl  :  www.alwayswatching.co.ke \r\nUserName : " . $this->input->post('phone_no') . " \r\nPassword : " . $this->input->post('password'). "";
            }else{
                $message = "HAWK Account Registration was Successful.\r\nPlease Login to your account using the following credentials.\r\nUrl  :  www.alwayswatching.co.ke \r\nUserName : " . $this->input->post('company_phone_no') . " \r\nPassword : " . $this->input->post('company_password'). "";

            }
            

            $res = $this->smssend->send_text_message ($recipient, $message);

            /* Email */
            $email = $notif['email'];
            if(!empty($email)){
            //$to = array($email);
            //$subj = 'HAWK Account Registration';

            $to = array($email);
            $subj = "HAWK Account Registration";
            $url = "http://alwayswatching.co.ke/hawk/index.php";

            $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                        <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                            <h1><img src="'.$url.'"></h1>
                        </div>
                        <div style="padding:20px;">
                            Dear User,<br><br>
                            Your have successfully been registered on HAWK.
                            Your HAWK account account is now active.
                            <br>
                            New Login Details<br>
                            Email : ' . $notif['phone_no'] . '<br>
                            Password: ' . $pass . ' <br>
                            <br>
                            <br>
                            Hawk. Always Watching | All Rights Reserved.
                            <br>
                        </div>
                    </div>';
            $this->sendMail($email,$subj,$message);

            //$emailstatus=$this->emailsend->send_email_message ($to, $subj, $message);
            }

            $userresult=$this->Mdl_apimobile->app_auth($phone_no,$pass);
            
            $this->response($userresult,200);
            // $this->response(array("status" => 1, "data" => array("message" => "Account Successfully Created.Login Details Have Been Sent to your phone number and your email")), 200);
        // if($this->emailsend->send_email_message ($to, $subj, $message)){
        //   $this->response(array("status" => 1, "data" => array("message" => "Account Successfully Created.Login Details Sent to your phone number and your email")), 200);
        // }
        // else{
        //   $this->response(array("status" => 1, "data" => array("message" => "Account Successfully Created But Email Containing Login In Details Has Failed To Be Sent")), 200);
        // }

        }
        else if($created_client==2){
            // $this->response(array("status" => 10, "data" => array("message" => "Account Registration Failed...An Account Registered With That Phone Number Already Exists")), 200);
        }

    }

    function get_trips_get(){
        $data=$this->input->get();
        $result=$this->Mdl_apimobile->get_trip_master($data['vehicle_id']);

        if($result){
            $this->response($result,200);
        }
        else{
            $this->response(array("status"=>10,"data"=>array("message"=>"No Trips Found")));
        }
    }


    function get_vehicle_dets_get(){
        $data = $this->input->get();
        $result = $this->Mdl_apimobile->get_vehicles_dets($data["plate_no"]);

        if($result){
            $this->response($result,200);
        }else{
            $this->response(array("status" => 10, "data" => array("message" => "Error Encountered!")));
        }
    }

    function get_route_points_get(){
        $data=$this->input->get();
        $result=$this->Mdl_apimobile->get_route_points($data['trip_id']);

          if($result){
            $this->response($result,200);
        }
        else{
            $this->response(array("status"=>10,"data"=>array("message"=>"No Points Found")));
        }
    }
    
     function get_vehicle_details_get(){
        $data=$this->input->get();
        $result=$this->Mdl_apimobile->get_route_points($data['trip_id']);

          if($result){
            $this->response($result,200);
        }
        else{
            $this->response(array("status"=>10,"data"=>array("message"=>"No Points Found")));
        }
    }

    function sendMail($emeil,$subj,$message){    
        $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'smtp-pulse.com',
      'smtp_port' => 2525,
      'smtp_user' => 'app@raindrops.co.ke', // change it to yours
      'smtp_pass' => '8tK4JQSEset', // change it to yours
      'mailtype' => 'html',
      'charset' => 'iso-8859-1',
      'wordwrap' => TRUE
    );
            $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('app@raindrops.co.ke', 'HAWK System'); // change it to yours
          $this->email->to($emeil);// change it to yours
          $this->email->subject($subj);
          $this->email->message($message);
          $check = $this->email->send();
          
//          if($check)
//          {
//              echo $check;
//           //echo $check." Success: ". show_error($this->email->print_debugger());
//          }
//                    else
//     {
//         show_error($this->email->print_debugger());
//     }
    }


    //Accounting

    function get_expense_types_get(){
      $data = $this->input->get();
      $result = $this->mdl_accounting->pickextype();

      if($result){
        $this->response(array("status" => 1, "data" => array("expense_types" => $result)), 200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Expense Types available")), 200);
      }
      
    }

    function get_expenses_get(){
      $data = $this->input->get();
      $result = $this->mdl_accounting->fetch_expenses($data['account_id'], $data['vehicle_id']);

      if($result){
        $this->response(array("status" => 1, "data" => array("expense_types" => $result)), 200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Expense Types available")), 200);
      }
      
    }


function create_expense_post(){
    $data['vehicle_id'] = $this->input->post('vehicle_id');
    $data['expense_type_id'] = $this->input->post('expense_type_id');
    $data['amount'] = $this->input->post('amount');
    $data['description'] = $this->input->post('description');
    $data['account_id'] = $this->input->post('account_id');
    $data['add_uid'] = $this->input->post('add_uid');

    $result = $this->mdl_accounting->save_expense($data);

    if($result){
        $this->response(array("status" => 1, "data" => array("message" => "Expense Added")), 200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Expense Types available")), 200);
      }
}


function update_expense_post(){
    $data['vehicle_id'] = $this->input->post('vehicle_id');
    $data['expense_type_id'] = $this->input->post('expense_type_id');
    $data['amount'] = $this->input->post('amount');
    $data['description'] = $this->input->post('description');
    $data['account_id'] = $this->input->post('account_id');
    $data['add_uid'] = $this->input->post('add_uid');
    $data['accounting_id'] = $this->input->post('accounting_id');

    $result = $this->mdl_accounting->update_expense($data);

    if($result){
        $this->response(array("status" => 1, "data" => array("message" => "Expense Updated")), 200);
    }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Expense Types available")), 200);
    }
}


//Insuarance:
 function get_cover_type_get(){
      $result = array("Comprehensive", "Third Party");
     $this->response(array("status" => 1, "data" => array("covers" => $result)), 200);
     
    }

    function get_reminder_schedule_get(){
      $result = array("1 Week", "2 Weeks");
      $this->response(array("status" => 1, "data" => array("schedules" => $result)), 200);
    
    }


    function get_permits_get(){
      $result = array("Parking", "SACCO");
      $this->response(array("status" => 1, "data" => array("schedules" => $result)), 200);
    
    }

    function get_users_get(){
      $data = $this->input->get();
   
      $result = $this->mdl_users->get_users_api($data['account_id']);
     
      if($result){
        $this->response(array("status" => 1, "data" => array("users" => $result)), 200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No User Found")), 200);
      }
      
    }


    function get_reminder_types_get(){
      $data = $this->input->get();
      $result = $this->mdl_reminders->get_reminder_types();

      if($result){
        $this->response(array("status" => 1, "data" => array("reminder_types" => $result)), 200);
      }else{
        $this->response(array("status" => 10, "data" => array("message" => "No Reminder Types available")), 200);
      }
      
    }


}