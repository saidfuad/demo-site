<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personnel extends CI_Controller {

    function __construct() {

        parent::__construct();

        if ($this->session->userdata('itms_protocal') == "") {
            redirect('login');
        }

        if ($this->session->userdata('itms_protocal') == 71) {
            redirect('admin');
        }

        if ($this->session->userdata('itms_user_id') != "") {
           redirect('home');
        }

        $this->load->library('encrypt');
        $this->load->model('mdl_personnel');
        $this->load->library('smssend');


    }

    public function index() {

        $data['personnel'] = $this->get_personnel ();

        $data['content_btn']= '<a href="'.site_url('personnel/add_personnel').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Personnel</a>';
        $data['content_url'] = 'personnel';
        $data['fa'] = 'fa fa-users';
        $data['title'] = 'ITMS Africa | Personnel';
        $data['content_title'] = 'View Personnel';
        $data['content_subtitle'] = 'List of all personnel';
        $data['content'] = 'personnel/view_personnel.php';

        $this->load->view('main/main.php', $data);
    }

    public function add_personnel () {
        $rolesOpt = '';
        $rolesList = '';

        //$roles = $this->mdl_personnel->get_all_roles();
        $roles = $this->mdl_personnel->get_roles();
        if(sizeof($roles)) {
            foreach ($roles as $role) {
                //if($role->role_id !=1) {
                    $rolesOpt .= "<option value='".$role->role_id."'>".addslashes($role->role_name)."</option>";
                    $rolesList .= "<li user-id='".$role->role_id."'><a href=''>".addslashes($role->role_name)."</a></li>";
                //}
            }
        }



        $data['rolesOpt'] = $rolesOpt;

        //print_r($rolesOpt);
        //exit;

        $data['content_url'] = 'personnel/add_personnel';
        $data['fa'] = 'fa fa-plus';
        $data['title'] = 'ITMS Africa | Add Personnel';
        $data['content_title'] = 'Add Personnel';
        $data['content_subtitle'] = '';
        $data['content'] = 'personnel/add_personnel.php';
        $this->load->view('main/main.php', $data);
    }

    public function v_personnel () {

        $rolesOpt = '';
        $rolesList = '';

        $roles = $this->mdl_personnel->get_all_roles();
        if(count($roles)) {
            foreach ($roles as $role) {
                $rolesOpt .= "<option value='".$role->role_id."'>".addslashes($role->role_name)."</option>";
                $rolesList .= "<li assets-category-id='".$role->role_id."'><a href=''>".addslashes($role->role_name)."</a></li>";
            }
        }

        $data['rolesOpt'] = $rolesOpt;

        $data ['personnel'] = $this->mdl_personnel->edit_personnel($this->session->userdata('itms_company_id'));

        $data['content_btn']= '<a href="'.site_url('personnel/add_personnel').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Personnel</a>';
        $data['content_url'] = 'personnel';
        $data['fa'] = 'fa fa-user';
        $data['title'] = 'ITMS Africa | Personnel';
        $data['content_title'] = 'View Personnel';
        $data['content_subtitle'] = 'Shows Personnel Details';
        $data['content'] = 'personnel/v_personnel.php';

        $this->load->view('main/main.php', $data);
    }

    public function save_personnel () {
        $data = $this->input->post();

        $data['company_id'] = $this->session->userdata('itms_company_id');
        $data['add_uid'] = $this->session->userdata('user_id');
        $data['thumbnail'] = ($this->session->userdata('personnel_pic') != '') ? $this->session->userdata('personnel_pic') :'personnel-default.png';

        // var_dump($data);

        echo $this->mdl_personnel->save_personnel($data);
    }

   public function edit_personnel () {
    $rolesOpt = '';
        $rolesList = '';

        $roles = $this->mdl_personnel->get_all_roles();
        if(count($roles)) {
            foreach ($roles as $role) {
                if($role->role_id !=1) {
                    $rolesOpt .= "<option value='".$role->role_id."'>".addslashes($role->role_name)."</option>";
                    $rolesList .= "<li user-id='".$role->role_id."'><a href=''>".addslashes($role->role_name)."</a></li>";
                }
                // $rolesOpt .= "<option value='".$role->role_id."'>".addslashes($role->role_name)."</option>";
                // $rolesList .= "<li assets-category-id='".$role->role_id."'><a href=''>".addslashes($role->role_name)."</a></li>";
            }
        }

        $data['rolesOpt'] = $rolesOpt;

    $data ['personnel'] = $this->mdl_personnel->edit_personnel($this->session->userdata('itms_company_id'));
    $data['content_url'] = 'personnel';
    $data['fa'] = 'fa fa-pencil';
    $data['title'] = 'ITMS Africa | Edit Personnel';
    $data['content_title'] = 'Edit Personnel';
    $data['content_subtitle'] = '';
    $data['content'] = 'personnel/edit_personnel.php';
    $this->load->view('main/main.php', $data);
    }

    function update_personnel(){
        $data = array('personnel_id' => $this->input->post('personnel_id'),
                      'role_id' => $this->input->post('role_id'),
                      'id_no' => $this->input->post('id_no'),
                      'fname' => $this->input->post('fname'),
                      'lname' => $this->input->post('lname'),
                      'gender' => $this->input->post('gender'),
                      'phone_no' => $this->input->post('phone_no'),
                      'email' => $this->input->post('email'),
                      'address' => $this->input->post('address'));
        // $data = $this->input->post();
        $data['company_id'] = $this->session->userdata('itms_company_id');
        $data['add_uid'] = $this->session->userdata('user_id');
        $data['thumbnail'] = ($this->session->userdata('personnel_pic') != '') ? $this->session->userdata('personnel_pic') :'personnel-default.png';

        $this->mdl_personnel->update_personnel($data);
    }

    public function add_user () {

        $data['content_url'] = 'personnel/add_user';
        $data['fa'] = 'fa fa-plus';
        $data['title'] = 'ITMS Africa | Add User';
        $data['content_title'] = 'Add User';
        $data['content_subtitle'] = 'Create users to access and manage the system';
        $data['content'] = 'personnel/add_user.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_user () {
        $data = $this->input->post();
        $data['company_id'] = $this->session->userdata('itms_company_id');
        $data['add_uid'] = $this->session->userdata('user_id');
        $data['username'] = $data['first_name'] .'.'.$data['last_name'];
        $name = $data['first_name'] .' '.$data['last_name'];
        $data['mobile_number'] = $data['phone_number'];
        $pass = uniqid();
        $data['password'] = $this->encrypt->encode($pass);
        $data['user_logo'] = ($this->session->userdata('personnel_pic') != '') ? $this->session->userdata('personnel_pic') :'user.png';

        $res =  $this->mdl_personnel->save_user($data);

        if (is_numeric($res) && $res > 0) {
            $recipient = array($data['phone_number']);
            $username = $data['username'];
            $link = base_url();
            $message = "Use this link to login to you ITMS account
                        $link .
                        Username : $username and Password : $pass";
            $response = $this->smssend->send_text_message ($recipient, $message);
        }

        echo $res;
    }

    public function get_personnel () {
        $user_id = ($this->input->get('add_uid') != "") ? $this->input->get('add_uid') : null;
        $role_id = ($this->input->get('role_id') != "") ? $this->input->get('role_id') : null;
        $company_id = $this->session->userdata('itms_company_id');

        $personnel = $this->mdl_personnel->get_personnel($company_id, $user_id, $role_id);

        return $personnel;
    }

    function delete_personnel($personnel_id){
        $this->mdl_personnel->delete_personnel($personnel_id);
        header('location:'.base_url('index.php/personnel'));
    }


    public function create_personnel () {

        $data['content_url'] = 'personnel/create_personnel';
        $data['fa'] = 'fa fa-plus';
        $data['title'] = 'ITMS Africa | Create Personnel';
        $data['content_title'] = 'Create Personnel';
        $data['content_subtitle'] = 'Add new personnel';
        $data['content'] = 'personnel/create_personnel.php';

        $this->load->view('main/main.php', $data);

    }

}
