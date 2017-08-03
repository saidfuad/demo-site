<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
	
class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'assests_class_master';
		
		$this->primary_key = 'assests_class_master.id';
		
		/*$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';
		*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('assets_class_name', 'Asset Class Name', 'required');		
		
		return parent::validate();

	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
	public function export(){
		
			
		$this->db->select('id, name');
		$this->order_by = 'id';
		// run joins, order by, where, or anything else here
		$query = $this->db->get('assests_class_master');
		
		to_excel($query, 'assests_class_master');
	}

}