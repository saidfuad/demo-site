<?php (defined("BASEPATH")) OR exit("No direct script access allowed");

class Form_Model extends MY_Model {
 
	public function __construct() {

		parent::__construct();
		
		$this->table_name = "assests_division_master";
		
		$this->primary_key = "assests_division_master.id";
		
		$this->select_fields = "SQL_CALC_FOUND_ROWS *";
		$this->order_by = "id";
	}
	
	public function validate() {
		$this->form_validation->set_rules("division", "Division", "required");
		return parent::validate();  

	} 
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>