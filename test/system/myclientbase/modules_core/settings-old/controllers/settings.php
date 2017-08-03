<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Settings extends Admin_Controller {

	function index()
	{
		
		$this->load->model('settings_model');
		$data = array();
		$this->load->view('settings',$data);
		
	}
}
?>