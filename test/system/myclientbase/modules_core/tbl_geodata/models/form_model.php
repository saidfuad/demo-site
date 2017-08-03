<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_geodata';
		
		$this->primary_key = 'tbl_geodata.id';
	}
	
	public function validate() {

		$this->form_validation->set_rules('cell_id', 'cell_id', '');
		$this->form_validation->set_rules('lac', 'lac', '');
		$this->form_validation->set_rules('latitude', 'latitude', 'required');
		$this->form_validation->set_rules('longitude', 'longitude', 'required');
		$this->form_validation->set_rules('address', 'address');
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>