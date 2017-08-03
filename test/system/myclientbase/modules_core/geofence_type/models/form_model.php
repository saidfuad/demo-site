<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'geofence_type';
		
		$this->primary_key = 'geofence_type.id';
	}
	
	public function validate() {

		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('comments', 'Comments');
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>