<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'landmark';
		
		$this->primary_key = 'landmark.id';
		
	/*	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
		*/
	}
	
	public function validate() {

		//all fields add, update

		$this->form_validation->set_rules('name', 'Landmark Name', 'required');
		$this->form_validation->set_rules('address', 'Address');
		$this->form_validation->set_rules('radius', "Radius",'required');
		$this->form_validation->set_rules('distance_unit', 'Distance Unit');
		$this->form_validation->set_rules('device_ids', "Assets");
		$this->form_validation->set_rules('icon_path', 'Icon Path');
		$this->form_validation->set_rules('addressbook_ids', "Address Book");
		$this->form_validation->set_rules('comments', 'Comments');
		$this->form_validation->set_rules('group_id', "Group Name");
		$this->form_validation->set_rules('sms_alert', "SMS Alert Name");
		$this->form_validation->set_rules('email_alert', "Email Alert");
		$this->form_validation->set_rules('alert_before_landmark', "Alert Before Landmark Location");
		return parent::validate();
	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>