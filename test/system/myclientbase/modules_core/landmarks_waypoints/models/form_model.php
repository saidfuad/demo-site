<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_landmarks_waypoints';
		
		$this->primary_key = 'tbl_landmarks_waypoints.id';
		
	/*	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
		*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('waypoint_name', 'Landmark Waypoint Name', 'required');
		$this->form_validation->set_rules('landmark1', "Landmark 1", "required");
		$this->form_validation->set_rules('landmark2', "Landmark 2", "required");
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
	public function export(){
		$this->db->select('id, first_name, last_name');
		$this->order_by = 'id';
		// run joins, order by, where, or anything else here
		$query = $this->db->get('tbl_users');
		to_excel($query, 'users');
	}

}

?>