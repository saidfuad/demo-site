<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = ' landmark_areas';
		
		$this->primary_key = 'id';
		
	/*	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
		*/
	}
	
	public function validate() {

		$this->form_validation->set_rules('deviceid', 'Assets');
		$this->form_validation->set_rules('polyname', 'Area Name', 'required');
		$this->form_validation->set_rules('polyid', 'Poly Id');
		$this->form_validation->set_rules('color', "Color");
		$this->form_validation->set_rules('in_alert', 'In Alert');
		$this->form_validation->set_rules('out_alert', "Out Alert");
		$this->form_validation->set_rules('addressbook_ids', 'Address Book');
		$this->form_validation->set_rules('sms_alert', "SMS Alert");
		$this->form_validation->set_rules('email_alert', "Email Alert");
		$this->form_validation->set_rules('area_type_opt', "Area Type");
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>