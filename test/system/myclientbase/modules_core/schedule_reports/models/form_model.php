<?php (defined("BASEPATH")) OR exit("No direct script access allowed");

class Form_Model extends MY_Model {
 
	public function __construct() {

		parent::__construct();
		
		$this->table_name = "schedule_reports";
		
		$this->primary_key = "schedule_reports.id";
		
		$this->select_fields = "SQL_CALC_FOUND_ROWS *";
		$this->order_by = "id";
	}
	
	public function validate() {
		$this->form_validation->set_rules("assets_ids", $this->lang->line("Assests Name(Device)"), "required");
		$this->form_validation->set_rules("email_addresses", $this->lang->line("Email_Address"), "required|valid_emails");
		$this->form_validation->set_rules("daily_monthly_weekly", $this->lang->line("Report_Type"));
		$this->form_validation->set_rules("excel_pdf", $this->lang->line("File_Type"));
		$this->form_validation->set_rules("reports",$this->lang->line("Reports"));
		
		return parent::validate();  

	} 
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}

}

?>