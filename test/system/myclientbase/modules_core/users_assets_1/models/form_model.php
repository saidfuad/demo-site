<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'user_assets_map';
		
		$this->primary_key = 'user_assets_map.id';
		
		/*$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('user', 'User');		
		$this->form_validation->set_rules('assets_ids', "Assets");
		$this->form_validation->set_rules('status', "status");
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