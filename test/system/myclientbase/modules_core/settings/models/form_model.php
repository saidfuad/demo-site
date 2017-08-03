<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'tbl_settings';
		
		$this->primary_key = 'tbl_settings.data_id';
		
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
	}
	
	public function validate($data){

			//all fields add, update
			$this->form_validation->set_rules('message', 'Message');
		return parent::validate();
	}
	
}

?>