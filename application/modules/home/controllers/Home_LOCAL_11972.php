<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

	function __construct() {

        parent::__construct();

        if ($this->session->userdata('itms_protocal') == "") {
            redirect('login');
        }
		
		if ($this->session->userdata('itms_protocal') == 71) {
            redirect('admin');
        }

		if ($this->session->userdata('itms_user_id') != "") {
           redirect(home);
        }

       $this->load->model('mdl_fetch');

       
    }
	
	public function index() {
        $data['vehicles'] = $this->mdl_fetch->get_vehicles($this->session->userdata('itms_company_id'));

        // var_dump($data);
        // print_r($data);
        // die();
		$data['content_url'] = 'home';
        $data['fa'] = 'fa fa-dashboard';
        $data['title'] = 'ITMS Africa | Dashboard';
        $data['content_title'] = 'Dashboard';
        $data['content_subtitle'] = 'Fleet Management';
        $data['content'] = 'home/dashboard.php';
		$this->load->view('main/main.php', $data);
		
	}

    public function test(){
        $data['vehicle'] = $this->mdl_fetch->get_vehicle();

        var_dump($data);
        print_r($data);
        die();
    }
}
