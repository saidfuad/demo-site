<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		$this->table_name = 'app_menu_master';
		
		$this->primary_key = 'id';
		
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';
		
	}
	
	public function validate() {
		//all fields add, update
		$this->form_validation->set_rules('menu_name', 'Menu Name', 'required');
		$this->form_validation->set_rules('comments', 'comments');
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
}

?>