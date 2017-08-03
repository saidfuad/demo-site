<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Base_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library('encrypt');
        $this->load->model('devices/mdl_devices');
        $this->load->model('alerts/mdl_alerts');
        $this->load->model('mdl_users');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('cart');
        $this->load->library('emailsend');
        //$this->load->library('email');
        $this->load->library('smssend');
    }

    public function index() {
        $data ['users'] = $this->mdl_users->get_users($this->session->userdata('hawk_account_id'));

        $data['content_btn'] = '<a href="' . site_url('users/add_user') . '" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add User</a>';
        // var_dump($data);
        // die();
        // $data['content_btn']= '<a href="'.site_url('users').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Group</a>';    
        $data['content_url'] = 'users';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | View Users';
        $data['content_title'] = 'View Users';
        $data['content_subtitle'] = '';
        $data['content'] = 'users/view_users.php';
        $this->load->view('main/main.php', $data);
    }

    public function add_user() {

        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] = $this->session->userdata('hawk_user_id');
        $data ['assign'] = $this->mdl_users->pickvehicles($this->session->userdata('hawk_account_id'));
        $data['content_url'] = 'users/add_user';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add User';
        $data['content_title'] = 'Add User';
        $data['content_subtitle'] = '';
        $data['content'] = 'users/add_user.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_user() {
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
         $password = $data['first_name'] . $phone_no[$phone_array - 3] 
        . $phone_no[$phone_array - 2] . $phone_no[$phone_array - 1] 
        . $phone_no[$phone_array];
      
        $logins = $this->mdl_users->create_login_details($password, $data['phone_no'] , $data['email'] );
        $created_client = $this->mdl_users->save_user($data, $logins);

        $vehicle_ids = $_POST['assign'] ;
        if(!empty($vehicle_ids))
        {
                $this->assign_vehicles($created_client['user_id'], $vehicle_ids );
        }
  

        /* Send Notification */
        if($created_client["status"]== 1){
             /* SMS */
            $recipient = array($data['phone_no']);
            $message = "HAWK Account Registration was Successful.\r\nPlease Login to your account using the following credentials.\r\nUrl  : ".base_url()." \r\nUserName : " 
            . $data['phone_no'] . " \r\nPassword : " . $password. "\r\nYour Account ID is: ".$created_client["account_name"]."";
             $res = $this->smssend->send_text_message ($recipient, $message);
            
            if(!empty($email)){
                    $this->mdl_users->send_registration_email($email, $phone_no, $password);
            }
        }
        echo $created_client['status'];
   }

   private function assign_vehicles($user_id, $vehicle_ids )
   {
      $vehicles = array();

         foreach ($vehicle_ids as $vehicle_id){
               $vehicles['vehicle_id' ] = $vehicle_id;
               $vehicles['user_id' ] = $user_id;
              $vehicles['assign_uid' ] = $this->user_id;
            }

            $this->mdl_users->assign_vehicles($vehicles);
   }

    public function fetch_user($user_id) {
        $data['user'] = $this->mdl_users->get_user_by_id($user_id);
        $data['assigned'] = $this->mdl_users->get_assigned($user_id);
        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['viewall'] = $this->mdl_users->get_all_vehicles($this->session->userdata('hawk_account_id'));
        $data ['uid'] = $this->session->userdata('hawk_user_id');

        $data['content_url'] = 'users/fetch_user';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | View User';
        $data['content_title'] = 'View User Details';
        $data['content_subtitle'] = 'View Details';

        $data['content'] = 'users/fetch_user.php';
        $this->load->view('main/main.php', $data);
    }

    public function edit_user($user_id) {

        $data['user'] = $this->mdl_users->get_user_by_id($user_id);
        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] = $this->session->userdata('hawk_user_id');
        $data ['assign'] = $this->mdl_users->pickvehicles($this->session->userdata('hawk_account_id'));
        $data['assigned'] = $this->mdl_users->get_assigned($user_id);


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

        $data['content'] = 'users/edit_user.php';
        $this->load->view('main/main.php', $data);
    }

    public function update_user() {
       // $data = $this->input->post();
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['email'] = $this->input->post('email');
        $data['phone_no'] = $this->input->post('phone_no');
        $data['user_type_id'] = $this->input->post('user_type_id');
        $data['user_id'] = $this->input->post('user_id');
        $data['add_uid'] = $this->input->post('add_uid');
        $data['account_id'] = $this->input->post('account_id');
       $assigned = $this->mdl_users->get_assigned($this->input->post('user_id'));
        $check= $this->mdl_users->check_phone_nos($this->input->post('phone_no'),$this->input->post('user_id'));

        $check = $this->mdl_users->check_phone_nos($this->input->post('phone_no'), $this->input->post('user_id'));

        if ($check != "") {
            echo false;
        } else {
            $dataa['email'] = $this->input->post('email');
            $dataa['user_id'] = $this->input->post('user_id');
            $dataa['phone_no'] = $this->input->post('phone_no');


        foreach ($assigned as $val) {
            $keep=false;

        foreach ($_POST['assign'] as $new){

                if($val->vehicle_id==$new){
                    $keep=true;
                }
                # code...
            }
            if($keep==false){
            $take['vehicle_id']=$val->vehicle_id;
            $take['user_id']=$this->input->post('user_id');
            $take['unassign_uid']=$this->session->userdata('hawk_user_id');
            $take['unassign_date']=date("Y-m-d H:m:s");
            $this->mdl_users->unassign($take);

            }
        }

        foreach ($_POST['assign'] as $new){
            $give['vehicle_id']=$new;
            $give['user_id']=$this->input->post('user_id');
            $give['assign_uid']=$this->session->userdata('hawk_user_id');
            $give['assign_date']=date("Y-m-d H:m:s");

            $this->mdl_users->update_assign($give);
        }
        echo $this->mdl_users->update_user($data,$dataa);
        }

        }

        function unassign($user_id,$assign_id){
            $take['assign_id']=$assign_id;
            $take['unassign_uid']=$this->session->userdata('hawk_user_id');
            $take['unassign_date']=date("Y-m-d H:m:s");
            $this->mdl_users->unassignn($take);

            redirect('users/fetch_user/'.$user_id);

        }

    function sendMail($emeil,$pass,$phone_nos)
    {
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
         $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                            <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                            </div>
                            <div style="padding:20px;">
                                Dear User,<br><br>
                                Your HAWK Account Has Been Created.
                                You can access your account with your new login details below.
                                <br>
                                <br>
                                New Login Details<br>
                                Email : ' . $phone_nos . '<br>
                                Password: ' . $pass . ' <br>
                                <br>
                                <br>
                                Verify carefully the Company information.
                                <br>
                                In case of any doubts, please feel free to contact HAWK Registrar.<br>
                                <a href="#">info@svs.com</a> on or <a href="#">+254 (0)729 220 777</a>
                                <br>                        
                            </div>
                        </div>';
            $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('rheinadrops@gmail.com', 'HAWK System'); // change it to yours
          $this->email->to($emeil);// change it to yours
          $this->email->subject('Login Details');
          $this->email->message($message);
          $this->email->send();
        //   if()
        //  {
        //   echo true;
        //  }
        //  else
        // {
        //     echo false;
        //  show_error($this->email->print_debugger());
        // }
    }
}
