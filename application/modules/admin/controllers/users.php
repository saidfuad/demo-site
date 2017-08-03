<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct() {

        parent::__construct();

        $this->load->library('encrypt');
    $this->load->model('mdl_devices');
        $this->load->model('mdl_users');
        $this->load->model('vehicles/mdl_vehicles');
                $this->load->library('cart');


    }

    public function index(){
    	$data ['users'] = $this->mdl_users->get_users($this->session->userdata('hawk_user_id'));

        $data['content_btn']= '<a href="'.site_url('admin/users/add_user').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add User</a>';  
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
        $data['content'] = 'admin/users/view_users.php';
        $this->load->view('admin/main.php', $data);
    }

     public function clients(){
        $data ['users'] = $this->mdl_users->get_users_clients($this->session->userdata('hawk_user_id'));

        $data['content_btn']= '<a href="'.site_url('admin/users/add_user').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add User</a>';  
        // var_dump($data);
        // die();
        // $data['content_btn']= '<a href="'.site_url('users').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Group</a>';    
        $data['content_url'] = 'users/clients';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | View Users';
        $data['content_title'] = 'View Users';
        $data['content_subtitle'] = '';
        $data['content'] = 'admin/users/view_clients.php';
        $this->load->view('admin/main.php', $data);
    }

    public function add_user(){

        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] = $this->session->userdata('hawk_user_id');
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
        $data['content'] = 'admin/users/add_user.php';
        $this->load->view('admin/main.php', $data);
    }



      public function save_user () {
        $data = $this->input->post();

        $check= $this->mdl_users->check_email($this->input->post('email'));

        if($check!=""){
            echo false;
        }
        else{
            $password=$this->input->post('first_name')."12345";
            $dataa['password'] = $this->encrypt->encode($password);
            $dataa['email']=$this->input->post('email');
            
            $insid= $this->mdl_users->save_user($data);

            if($insid!=0){
                $dataa['user_id']=$insid;
                echo $this->mdl_users->save_login($dataa);

            }

            else{
                echo false;
            }
        }
       
    }

    public function fetch_user($user_id){
        $data['user'] = $this->mdl_users->get_user_by_id($user_id);
        $data ['accountid'] = $this->session->userdata('hawk_account_id');
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

        $data['content'] = 'admin/users/fetch_user.php';
        $this->load->view('admin/main.php', $data);
    }

    public function edit_user ($user_id) {
        
        $data['user'] = $this->mdl_users->get_user_by_id($user_id);
        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] = $this->session->userdata('hawk_user_id');
        
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

     public function update_user () {
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