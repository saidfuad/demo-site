<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'addressbook';
		
		$this->primary_key = 'addressbook.id';
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
		$this->form_validation->set_rules('name', 'Name','required');	
		$this->form_validation->set_rules('address', 'Address');	
		$this->form_validation->set_rules('group_id', 'Group Id');
		$this->form_validation->set_rules('mobile_no', 'Mobile Number','required');	
		$this->form_validation->set_rules('email', 'Email','valid_emails');	
		$this->form_validation->set_rules('send_sms', 'Send Sms');	
		$this->form_validation->set_rules('send_email', 'Send Email');	
			
		//$this->form_validation->set_rules('user_id', "User");
		
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