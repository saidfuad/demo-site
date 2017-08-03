<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Device_settings extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('device_settings_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('file');
		$this->load->helper('uri');
	}

	function index() {
		
		if (!$this->form_model->validate()) {
			$this->load->helper('form');
			$rows = $this->device_settings_model->fetch_device_class();
			$opt = '';
			
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				$opt .= '>'.$row->assets_class_name.'</option>';
			}
			
			$this->form_model->device_class = $opt;
			$this->load->view('device_settings');
		}
		else {
			
			$formdata = $this->form_model->db_array();
			$data = $this->device_settings_model->save($formdata);
			//die($data);
			$this->output->set_output($data);
			//die();
		}
	}

	function get_devices() {
		$class = uri_assoc('class');
		
		$rows = $this->device_settings_model->fetch_devices($class);
		$opt = '';
		
		foreach ($rows as $row) {
			$opt .= '<option value="'.$row->id.'"';
			$opt .= '>'.$row->assets_name.'</option>';
		}
		
		die($opt);
	}
	
	function get_commands() {
		$class = uri_assoc('class');
		
		$rows = $this->device_settings_model->fetch_device_commands($class);
		$opt = '';
		
		foreach ($rows as $row) {
			$opt .= '<option value="'.$row->command.'"';
			$opt .= '>'.$row->comments.'</option>';
		}
		
		die($opt);
	}
	
}
?>