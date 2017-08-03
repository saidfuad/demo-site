<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {
		parent::__construct();
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('device_ids', "Assets", 'required');
		$this->form_validation->set_rules('command', 'Command', 'required');
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>