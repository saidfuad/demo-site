<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_cell_data';
		
		$this->primary_key = 'tbl_cell_data.id';
		/*
		$this->select_fields = "SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';
		*/
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
	public function validate() {

		//all fields add, update
		
		$this->form_validation->set_rules('latitude', 'Latitude','required');	
		$this->form_validation->set_rules('longitude', 'Longitude','required');	
		$this->form_validation->set_rules('address', 'Address','required');	
		return parent::validate();

	}
}

?>