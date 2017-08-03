<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_users';
		
		$this->primary_key = 'tbl_users.user_id';
		
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
	}
	
	public function validate() {  

		//all fields add, update
		$this->form_validation->set_rules('user_id', 'User Id');		
		$this->form_validation->set_rules('menu_group', 'Minimum 1 Menu ');		
		$this->form_validation->set_rules('where_to_show', 'Show ');		
		$this->form_validation->set_rules('priority', 'priority ');	
		$this->form_validation->set_rules('status', 'status ');			
			
		//$this->form_validation->set_rules('menu_sound', "Menu Sound", 'required');
		
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
		$query = $this->db->get('country');
		
		to_excel($query, 'country');
	}

}

?>