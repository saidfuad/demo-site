<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Devices extends Base_Controller {

	function __construct() {

        parent::__construct();

        $this->load->library('encrypt');

        $this->load->model('mdl_devices');
        $this->load->model('vehicles/mdl_vehicles');
            $this->load->library('cart');

    }

    public function index(){
    	$data ['devices'] = $this->mdl_devices->get_all();


        $data['content_btn']= '<a href="'.site_url('devices/add_device').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Device</a>';  
        // var_dump($data);
        // die();
        // $data['content_btn']= '<a href="'.site_url('users').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Group</a>';    
        $data['content_url'] = 'devices';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'Hawk | View Devices';
        $data['content_title'] = 'View Devices';
        $data['content_subtitle'] = '';
        $data['content'] = 'devices/view_devices.php';
        $this->load->view('main/main.php', $data);
    }

     public function add_device () {

        $data['content_url'] = 'devices/add_device';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | New Device';
        $data['content_title'] = 'New Device';
        $data['content_subtitle'] = 'Add New Device';
        $data['content'] = 'devices/add_device.php';
        $this->load->view('main/main.php', $data);
    }

     public function save_device () {
        $data['serial_no'] = $this->input->post('serial_no');
        if(!empty($this->input->post('phone_no'))){
            $data['phone_no'] = $this->input->post('phone_no');
        }
       // $devnos=$this->mdl_devices->get_devices ();
        $data['terminal_id']=$this->input->post('serial_no');
        $data['status'] = "Not Assigned";
        echo $this->mdl_devices->save_device($data);
    }

    public function update_assign ($device_id=null,$vehicle_id=null) {
        $dataa['unassign_uid'] = $this->session->userdata('hawk_user_id');
        $dataa['unassign_date'] = date("Y-m-d");
        $vid = $vehicle_id;


        $data['status'] = "Faulty";
        $did=$device_id;
        echo $this->mdl_devices->update_device($data,$did,$vid,$dataa);

      //  redirect('devices');
    }


    public function fetch_device ($device_id) {
        
        $user_id = $this->session->userdata('hawk_user_id');
        $data['device'] = $this->mdl_devices->get_device($device_id);

        $data['content_btn']= '<a href="'.site_url('devices/add_device').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Device</a>';    
        
        $data['content_url'] = 'devices/fetch_device';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | Device';
        $data['content_title'] = 'Device';
        $data['content_subtitle'] = 'Device Details';
        $data['content'] = 'devices/fetch_device.php';
        $this->load->view('main/main.php', $data);
        
    }

    // public function add_user(){

    //     $data ['accountid'] = $this->session->userdata('hawk_account_id');
    //     $data ['uid'] = $this->session->userdata('hawk_user_id');
    //     $data['content_url'] = 'users/add_user';
    //     $data['fa'] = 'fa fa-plus';
    //     $data['title'] = 'HAWK | Add User';
    //     $data['content_title'] = 'Add User';
    //     $data['content_subtitle'] = '';
    //     $data['content'] = 'users/add_user.php';
    //     $this->load->view('main/main.php', $data);
    // }

    //   public function save_user () {
    //     $data = $this->input->post();

    //     $check= $this->mdl_devices->check_email($this->input->post('email'));

    //     if($check!=""){
    //         echo false;
    //     }
    //     else{
    //         $password=$this->input->post('first_name')."12345";
    //         $dataa['password'] = $this->encrypt->encode($password);
    //         $dataa['email']=$this->input->post('email');
            
    //         $insid= $this->mdl_devices->save_user($data);

    //         if($insid!=0){
    //             $dataa['user_id']=$insid;
    //             echo $this->mdl_devices->save_login($dataa);

    //         }

    //         else{
    //             echo false;
    //         }
    //     }
       
    // }

    // public function fetch_user($user_id){
    //     $data['user'] = $this->mdl_devices->get_user_by_id($user_id);
    //     $data ['accountid'] = $this->session->userdata('hawk_account_id');
    //     $data ['uid'] = $this->session->userdata('hawk_user_id');
        
    //     $data['content_url'] = 'users/fetch_user';
    //     $data['fa'] = 'fa fa-car';
    //     $data['title'] = 'HAWK | View User';
    //     $data['content_title'] = 'View User Details';
    //     $data['content_subtitle'] = 'View Details';

    //     $data['content'] = 'users/fetch_user.php';
    //     $this->load->view('main/main.php', $data);
    // }

    // public function edit_user ($user_id) {
        
    //     $data['user'] = $this->mdl_devices->get_user_by_id($user_id);
    //     $data ['accountid'] = $this->session->userdata('hawk_account_id');
    //     $data ['uid'] = $this->session->userdata('hawk_user_id');
        
    //     $data['content_url'] = 'users/edit_user';
    //     $data['fa'] = 'fa fa-car';
    //     $data['title'] = 'HAWK | Edit User';
    //     $data['content_title'] = 'Edit User Details';
    //     $data['content_subtitle'] = 'User Details';

    //     $data['content'] = 'users/edit_user.php';
    //     $this->load->view('main/main.php', $data);
    // }

    //  public function update_user () {
    //     $data = $this->input->post();

    //     $check= $this->mdl_devices->check_emaill($this->input->post('email'),$this->input->post('user_id'));

    //     if($check!=""){
    //         echo false;
    //     }
    //     else{
    //     $dataa['email']=$this->input->post('email');
    //     $dataa['user_id']=$this->input->post('user_id');

    //     echo $this->mdl_devices->update_user($data,$dataa);
    //     }
    // }


}