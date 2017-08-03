<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'device_down_email';
		
		$this->primary_key = 'device_down_email.id';
		/*
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';*/
	}
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('name', 'Name', 'required');		
		$this->form_validation->set_rules('email', 'Email Id', 'required|valid_emails');
                //Added by Poonam 24-01-2015 12:52 PM
                $this->form_validation->set_rules('mobile','Mobile','required|regex_match[/^[0-9]{10}$/]');	
                $this->form_validation->set_rules('email_stop_alert','Email Server Fail alert','required');
                //End			
		$this->form_validation->set_rules('status', 'Email Status', 'required');		
		
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