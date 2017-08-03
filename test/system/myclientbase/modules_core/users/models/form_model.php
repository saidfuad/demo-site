<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_users';
		
		$this->primary_key = 'tbl_users.user_id';
		
	/*	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'user_id';
		*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', "Last Name", "required");
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('profile_id', 'Profile', 'required');
		/*if (!$_POST AND uri_assoc('id')) 
		{
			$this->form_validation->set_rules('password', "Password", 'required');
		}
		else
		{
			$this->form_validation->set_rules('password', "Password");
		}*/
		/*if (uri_assoc('id')) {
                  $this->form_validation->set_rules('password', "Password");
                } else {
                  $this->form_validation->set_rules('password', "Password", 'required|matches[confirm_password]');
                }
		if(uri_assoc('id')){
                $this->form_validation->set_rules('confirm_password', "Password Confirmation");
		}
		else{
		 $this->form_validation->set_rules('confirm_password', "Password Confirmation",'required');

		}*/
		if (uri_assoc('id')){
		   $this->form_validation->set_rules('password',"Password");
            	}else{
                   $this->form_validation->set_rules('password', "Password", 'required|matches[confirm_password]');
           	}
		if (uri_assoc('id')){		
                   $this->form_validation->set_rules('confirm_password', "Password Confirmation");
		}else{
		   $this->form_validation->set_rules('confirm_password', "Password Confirmation",'required');
		}
		$this->form_validation->set_rules('from_date', "From Date", 'required');
		$this->form_validation->set_rules('to_date', "To Date", 'required');
		$this->form_validation->set_rules('birth_date', 'Birth Date');
		$this->form_validation->set_rules('address', 'Address');
		$this->form_validation->set_rules('city', "City");
		$this->form_validation->set_rules('state', 'State');
		$this->form_validation->set_rules('country', "Country");
		$this->form_validation->set_rules('timezone', "Timezone");
		$this->form_validation->set_rules('zip', 'Zip');
		$this->form_validation->set_rules('phone_number', "Phone");
		$this->form_validation->set_rules('fax_number', 'Fax');
		$this->form_validation->set_rules('mobile_number', "Mobile Number");
		$this->form_validation->set_rules('email_address', 'Email Address','valid_emails');	
		$this->form_validation->set_rules('web_address', "Web Address");
		$this->form_validation->set_rules('company_name', "Company Name");
		$this->form_validation->set_rules('display_day', "Display Days");
		$this->form_validation->set_rules('sms_alert', "SMS Alert Name");
		$this->form_validation->set_rules('sms_enable', "SMS Enable");
		$this->form_validation->set_rules('user_logo', "Company Logo");
		$this->form_validation->set_rules('email_alert', "Email Alert");
		$this->form_validation->set_rules('status', "User Status");
		$this->form_validation->set_rules('menu_view', "Menu View");
		$this->form_validation->set_rules('expiry_service_sms', "Service Expiry Sms");
		$this->form_validation->set_rules('expiry_service_email', "Service Expiry Email");
		$this->form_validation->set_rules('show_owners', "Show Owners");
		$this->form_validation->set_rules('show_divisions', "Show Divisions");
		$this->form_validation->set_rules('auto_refresh_setting', "Auto Refresh");
		$this->form_validation->set_rules('device_id_not_editable', "Device Id Not Editable");
		$this->form_validation->set_rules('report_view', "Report View");
		$this->form_validation->set_rules('change_password', "Change Password");
		$this->form_validation->set_rules('history', "History");
		$this->form_validation->set_rules('allow_user_profile', "Allow Create User & Profile");
		
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