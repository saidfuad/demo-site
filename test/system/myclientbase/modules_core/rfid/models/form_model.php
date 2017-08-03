<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_rfid';
		
		$this->primary_key = 'tbl_rfid.id';
		
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('rfid', 'FRID', 'required');		
		$this->form_validation->set_rules('person', "Person", 'required');
		$this->form_validation->set_rules('asset_id', "Assets", 'required'); 	
		$this->form_validation->set_rules('inform_mobile', "Mobile No."); 	
		$this->form_validation->set_rules('inform_email', "Email Id"); 	
		$this->form_validation->set_rules('send_sms', "Sms Alert"); 	
		$this->form_validation->set_rules('send_email', "Email Alert"); 	
		$this->form_validation->set_rules('comments', "Comments"); 	
		$this->form_validation->set_rules('landmark_id', "Landmark"); 	
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
	public function export(){
		
			
		$this->db->select('id, rfid, person, asset_id');
		$this->order_by = 'id';
		// run joins, order by, where, or anything else here
		$query = $this->db->get('country');
		
		to_excel($query, 'country');
	}
}
?>