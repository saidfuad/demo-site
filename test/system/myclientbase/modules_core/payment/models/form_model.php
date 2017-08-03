<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_payment_master';
		
		$this->primary_key = 'tbl_payment_master.id';
		/*
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('user_id', 'User');		
		$this->form_validation->set_rules('payment_type', 'Payment Type');		
		$this->form_validation->set_rules('payment_for', 'Payment For');		
		$this->form_validation->set_rules('amount', 'Amount', 'required');		
		$this->form_validation->set_rules('cheque_number', 'cheque Number');		
		$this->form_validation->set_rules('cheque_date', 'cheque Date');		
		$this->form_validation->set_rules('cheque_bank_name', 'cheque Bank Name');
		
		return parent::validate();

	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
}