<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends Base_Controller {

    function __construct() {

        parent::__construct();

        if ($this->session->userdata('hawk_user_type_id') != 2 || $this->session->userdata('hawk_user_type_id') == "") {
            redirect('login');
        }

        if ($this->session->userdata('hawk_user_id') == "") {
            redirect('login');
        }

        $this->load->model('mdl_main');
        $this->load->model('devices/mdl_devices');
        $this->load->model('mpdf_main/mdl_reports');
        $this->load->library('cart');
        $this->load->library('encrypt');

    }

    public function refresh_alerts() {
        echo $this->mdl_main->fetch_alerts($this->session->userdata('hawk_account_id'));
    }

    public function companydetails(){
        
        $data['company_subscriptions'] = $this->mdl_main->fetch_company_subscriptions($this->session->userdata('hawk_account_id'));
        
        $data['content_url'] = 'main/companydetails';
        $data['fa'] = 'fa fa-fw fa-info-circle';
        $data['title'] = 'HAWK | Company Details';
        $data['content_title'] = 'Company Details';
        $data['content_subtitle'] = '';
        $data['content'] = 'main/company_details.php';
        $this->load->view('main/main.php', $data); 
    }
    
    public function edit_company_name(){
        
        $data = $this->input->post('company_name');
        $res = $this->mdl_main->edit_company_name($data);
        echo $res;   
    }
    
    public function upload_company_logo(){
        
        $data = array();
        $data['company_logo'] = ($this->session->userdata('company_logo') != '') ? $this->session->userdata('company_logo') :'user-default.png';
        $result = $this->mdl_main->upload_company_logo($data);
        
        echo $result;
        
    }

    public function reset_password(){

        $current_password = $this->input->post('password');
        $new_password = $this->input->post('new_password1');

        $data['password'] = $current_password;

        $db_password = $this->mdl_main->check_current_password();
        $decrypted_db_password = $this->encrypt->decode($db_password['password']);

        if($decrypted_db_password == $current_password){

            $encrypted_new_password = $this->encrypt->encode($new_password);
            $res = $this->mdl_main->reset_password($encrypted_new_password);
            echo $res;

        }else{

            echo 77;

        }


    }

}
