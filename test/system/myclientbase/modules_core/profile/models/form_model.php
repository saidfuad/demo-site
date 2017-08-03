<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_users';
		
		$this->primary_key = 'tbl_users.user_id';
		
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
	}
	
	public function validate($data) {
			//all fields add, update
			$this->form_validation->set_rules('first_name', 'First Name', 'required');		
			$this->form_validation->set_rules('last_name', "Last Name", 'required');
			$this->form_validation->set_rules('address', 'First Address');		
			$this->form_validation->set_rules('address_2', 'Second Address');		
			$this->form_validation->set_rules('city', "City");
			$this->form_validation->set_rules('state', 'State');		
			$this->form_validation->set_rules('country', "Country");
			$this->form_validation->set_rules('zip', 'Zip');		
			$this->form_validation->set_rules('phone_number', "Phone");
			$this->form_validation->set_rules('fax_number', 'Fax');		
			$this->form_validation->set_rules('mobile_number', "Mobile Number");
			$this->form_validation->set_rules('email_address', 'Email Address');		
			$this->form_validation->set_rules('web_address', "Web Address");
			$this->form_validation->set_rules('company_name', "Company Name");
			$this->form_validation->set_rules('sms_alert', "Sms Alert");
			$this->form_validation->set_rules('email_alert', "Email Alert");
			$this->form_validation->set_rules('alert_time', "Alert Time");
			$this->form_validation->set_rules('alert_start_time', "Alert From Time");
			$this->form_validation->set_rules('alert_stop_time', "Alert To Time");	
			$this->form_validation->set_rules('def_dash_view', "Default Dashboard View");	
			$this->form_validation->set_rules('network_timeout', "Assets Timeout Hours");	

			$this->form_validation->set_rules('date_format', 'Date Format');		
			$this->form_validation->set_rules('time_format', "Time Format");
			$this->form_validation->set_rules('language', "Language");
			$this->form_validation->set_rules('currency_format', "Currency Format");
			$this->form_validation->set_rules('max_stop_time', "Max Stop time");
			$this->form_validation->set_rules('timezone', "Date-Time Zone");
			$this->form_validation->set_rules('all_point_setting', "All Points Remove Days");
			$this->form_validation->set_rules('alert_box_open_time', "Alert Box Open Time");
			$this->form_validation->set_rules('location_with_tag', "Location With Tag");
			$this->form_validation->set_rules('ignition_on_speed_off_minutes', "Alert If Ignition On And Vehicle Stop");
			$this->form_validation->set_rules('ignition_off_speed_on_minutes', "Alert If Ignition Off And Vehicle Running");
			$this->form_validation->set_rules('ignition_on_alert', "Ignition On Alert");				
			$this->form_validation->set_rules('ignition_off_alert', "Ignition Off Alert");
			$this->form_validation->set_rules('show_zone_name', "Show Zone Name");
			$this->form_validation->set_rules('auto_refresh_setting', "Auto Refresh Setting");
			$this->form_validation->set_rules('network_timeout', "Assets Timeout Hours");	
			$this->form_validation->set_rules('country_lati', "Country Lati");	
			$this->form_validation->set_rules('country_longi', "Country Longi");
			$this->form_validation->set_rules('onscreen_alert', "Show Alert On Screen");			

		return parent::validate();
	}
}

?>