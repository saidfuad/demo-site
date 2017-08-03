<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'mst_user_profile';
		
		$this->primary_key = 'mst_user_profile.id';
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('profile_name', 'Profile Name', 'required');
		$this->form_validation->set_rules('charges_per_day', 'Charges Per Day', 'required');
		$this->form_validation->set_rules('profile_desc', "Profile Desc");
		$this->form_validation->set_rules('menu_setting', "Menu Setting");
		$this->form_validation->set_rules('status', "Status", "required");
		
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
		$query = $this->db->get('mst_user_profile');
		
		to_excel($query, 'user_profile');
	}

}

?>