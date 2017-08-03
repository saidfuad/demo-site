<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'assets_profile_master';
		
		$this->primary_key = 'assets_profile_master.id';
		
		/*$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';*/
	}
	
	public function validate() {

		//all fields add, update
		$this->form_validation->set_rules('profile_name', 'Profile Name', 'required');		
		$this->form_validation->set_rules('min_consecutive_speed', 'Profile Name');		
		$this->form_validation->set_rules('max_consecutive_speed', 'Profile Name');		
		$this->form_validation->set_rules('max_idle_time', 'Profile Name');		
		$this->form_validation->set_rules('device_ids', 'Assets', 'required');		
		
		return parent::validate();

	}
	
	public function db_array() {

		$db_array = parent::db_array();

		return $db_array;

	}
	public function export(){
		$this->db->select('id, name');
		$this->order_by = 'id';
		// run joins, order by, where, or anything else here
		$query = $this->db->get('country');
		to_excel($query, 'country');
	}
	public function assets_list($id){
		$wh="";
		if($id!="")
		{
			$wh="AND id=".$id;
		
		$SQL = "SELECT device_ids FROM assets_profile_master where status=1 and del_date is null and add_uid = '".$this->session->userdata('user_id')."'".$wh;
		$query = $this->db->query($SQL);
			$rs=$query->result_array();
			return $rs[0]['device_ids'].",";
		}
		else
		{
			return "";
		}
	}
	public function delData()
	{
		
	}
	
}

?>