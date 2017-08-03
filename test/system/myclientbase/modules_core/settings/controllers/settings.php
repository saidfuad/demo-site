<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Settings extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->model('settings_model','',TRUE);
	}
	function form()
	{
		if (!$this->form_model->validate(uri_assoc('form_name'))) {
			$this->load->helper('form');
			$this->form_model->prep_validation(uri_assoc('form_name'));
			$data['result'] = $this->settings_model->get_alll_data();
			$this->load->view('settings',$data);
		}
		else {
			$formdata = $this->form_model->db_array();		
			//die(print_r($formdata));
			$this->settings_model->save($formdata, $this->session->userdata('user_id'));
		}
	}
	function index()
	{
		$data['result'] = $this->settings_model->get_alll_data();
		$this->load->view('settings',$data);
	}
}
?>