<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {

	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'assests_master';
		
		$this->primary_key = 'assests_master.id';
		
//		$this->select_fields = "SQL_CALC_FOUND_ROWS *";
		
//		$this->order_by = 'id';
		
	}
	
	public function validate() {

		//all fields add, update
		//$this->form_validation->set_rules('assets_name', 'Asset Name', 'required');	
		$this->form_validation->set_rules('assets_name', 'Asset Name', 'required'. $required_if .'|max_length[11]');
		$this->form_validation->set_rules('device_id', "Device");
		$this->form_validation->set_rules('assets_friendly_nm', "Assets Friendly Name");
		$this->form_validation->set_rules('device_desc', "Device Description");
		$this->form_validation->set_rules('icon_id', "Icon");	
		$this->form_validation->set_rules('sim_number', "Sim Number");	
		$this->form_validation->set_rules('assets_type_id', "Assets Type", 'required');
		$this->form_validation->set_rules('assets_category_id', "Assets Category", 'required');	
		$this->form_validation->set_rules('assets_division', "Assets Division");
		$this->form_validation->set_rules('assets_owner', "Assets Owner");
		$this->form_validation->set_rules('assets_group_id', "Assets Group");
		$this->form_validation->set_rules('assets_image_path', "Assets Image");	
		$this->form_validation->set_rules('driver_name', "Driver Name");	
		$this->form_validation->set_rules('driver_image', "Driver Image");	
		$this->form_validation->set_rules('driver_mobile', "Driver Mobile");	
		$this->form_validation->set_rules('device_status', "Device Status");	
		$this->form_validation->set_rules('max_speed_limit', "Max speed limit");	
		$this->form_validation->set_rules('max_fuel_capacity', "Max Fual Capacity");	
		$this->form_validation->set_rules('max_fuel_liters', "Max Fual Liters");	
		$this->form_validation->set_rules('battery_size', "Battery Size");	
		$this->form_validation->set_rules('telecom_provider', "Telecome Provider");	
		$this->form_validation->set_rules('km_reading', "KM Reading");	
		$this->form_validation->set_rules('max_fuel_dropout', "Max Fuel DropOut");	
		$this->form_validation->set_rules('sensor_type', "Sensor Type");	
		$this->form_validation->set_rules('min_temprature', "Minimum Temprature");	
		$this->form_validation->set_rules('max_temprature', "Maximum Temprature");
		$this->form_validation->set_rules('eng_runtime', "Engine Runtime");	
		$this->form_validation->set_rules('sensor_fuel', "Sensor Fuel");	
		$this->form_validation->set_rules('sensor_tempr', "Sensor Temprature");	
		//$this->form_validation->set_rules('user_id', "User");
		
		$this->form_validation->set_rules('tank_type', "Tank Type");
		$this->form_validation->set_rules('tank_diameter', "Tank Diameter");
		$this->form_validation->set_rules('tank_width', "Tank Width");
		$this->form_validation->set_rules('tank_length', "Tank Length");
		$this->form_validation->set_rules('fuel_in_out_sensor', "Fuel In OUt ");
		$this->form_validation->set_rules('xyz_sensor', "Fuel In OUt ");
		$this->form_validation->set_rules('rollover_tilt', "Rollover/Tilt");
		$this->form_validation->set_rules('panic', "Panic");
		$this->form_validation->set_rules('runtime', "Runtime ");
		$this->form_validation->set_rules('fuel_in_per_lit', "Fuel IN Per Liter");
		$this->form_validation->set_rules('fuel_in_company_name', "Fuel In Company Name");
		$this->form_validation->set_rules('fuel_in_product_code', "Fuel In Product Code");
		$this->form_validation->set_rules('fuel_out_per_lit', "Fuel Out Per Lit.");
		$this->form_validation->set_rules('fuel_out_company_name', "Fuel out Company Name");
		$this->form_validation->set_rules('fuel_out_product_code', "Fuel out Product Code");
		$this->form_validation->set_rules('asset_users_id', "asset_users_id");
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
}