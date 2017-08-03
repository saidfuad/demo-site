<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'assests_command_master';
		
		$this->primary_key = 'assests_command_master.id';
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
		$this->form_validation->set_rules('assets_class_id', 'Device Type','required');	
		$this->form_validation->set_rules('command', 'Command','required');	
		$this->form_validation->set_rules('comments', 'Comments','required');
		
		return parent::validate();

	}
	public function export(){
		
			
		$this->db->select('id, name');
		$this->order_by = 'id';
		// run joins, order by, where, or anything else here
		$query = $this->db->get('assests_command_master');
		
		to_excel($query, 'assests_command_master');
	}

}

?>