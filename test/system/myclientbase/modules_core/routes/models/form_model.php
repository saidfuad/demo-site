<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_routes';
		
		$this->primary_key = 'tbl_routes.id';
		
	/*	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
		*/
	}
	public function validate() {
		$this->form_validation->set_rules('routename', 'Route Name', 'required');
		$this->form_validation->set_rules('route_color', 'route_color');
		$this->form_validation->set_rules('landmark_ids', 'landmark_ids');
		$this->form_validation->set_rules('deviceid', "deviceid");
		$this->form_validation->set_rules('distance_value', 'distance_value');
		$this->form_validation->set_rules('distance_unit', 'distance_unit');
		$this->form_validation->set_rules('total_distance', 'total_distance');
		$this->form_validation->set_rules('total_time_in_minutes', 'total_time_in_minutes');
		$this->form_validation->set_rules('round_trip', "round_trip");
		$this->form_validation->set_rules('comments', 'comments');
		$this->form_validation->set_rules('sms_alert', "SMS Alert");
		$this->form_validation->set_rules('email_alert', "Email Alert");
		return parent::validate();
	}
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>