<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_broker';
		
		$this->primary_key = 'tbl_broker.id';
		
	/*	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
		*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', "Last Name", "required");
		$this->form_validation->set_rules('address', 'Address');
		$this->form_validation->set_rules('city', "City");
		$this->form_validation->set_rules('state', 'State');
		$this->form_validation->set_rules('country', "Country");
		$this->form_validation->set_rules('zip', 'Zip');
		$this->form_validation->set_rules('phone_number', "Phone");
		$this->form_validation->set_rules('fax_number', 'Fax');
		$this->form_validation->set_rules('mobile_number', "Mobile Number");
		$this->form_validation->set_rules('email_address', 'Email Address','valid_emails');	
		$this->form_validation->set_rules('web_address', "Web Address");
		$this->form_validation->set_rules('company_name', "Company Name");
		
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
		$query = $this->db->get('tbl_broker');
		
		to_excel($query, 'broker');
	}

}

?>