<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'driver_master';
		
		$this->primary_key = 'driver_master.id';
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
		$this->form_validation->set_rules('driver_name', 'Driver Name','required');	
		$this->form_validation->set_rules('driver_code', 'Driver code','required');	
		$this->form_validation->set_rules('address', 'Address');	
		$this->form_validation->set_rules('mobile_no', 'Mobile 	No');	
		$this->form_validation->set_rules('email', 'Email Is','valid_email');
		
		return parent::validate();

	}
	public function export(){
		
			
		$this->db->select('id, name');
		$this->order_by = 'id';
		// run joins, order by, where, or anything else here
		$query = $this->db->get('country');
		
		to_excel($query, 'country');
	}

}

?>