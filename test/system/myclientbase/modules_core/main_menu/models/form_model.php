<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Form_Model extends MY_Model {
 
	public function __construct() {

		parent::__construct();
		
		$this->table_name = 'main_menu_master';
		
		$this->primary_key = 'main_menu_master.id';
		
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		
		$this->order_by = 'id';
	}
	
	public function validate() {
		//all fields add, update
		$this->form_validation->set_rules('menu_name', 'Menu Name', 'required');$this->form_validation->set_rules('menu_link', 'Menu Link');
		$this->form_validation->set_rules('where_to_show', 'where To Show', 'required');
		$this->form_validation->set_rules('tab_title', 'Tab Title', 'required');
		$this->form_validation->set_rules('menu_level', 'Menu Level', 'required');
		$this->form_validation->set_rules('parent_menu_id', 'Parent Menu');
		$this->form_validation->set_rules('menu_image', 'Menu Image');	
		$this->form_validation->set_rules('menu_sound', 'Menu Sound');
		$this->form_validation->set_rules('priority', 'Priority');
		$this->form_validation->set_rules('type', 'type');
		$this->form_validation->set_rules('sub_settings', 'sub_settings');
		$this->form_validation->set_rules('is_admin', 'Is Admin');		
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
	
	public function getlevel(){ 
		if(uri_assoc('id') == "1"){
			$sub_data ="<option value=''>Please select</option>";
		}
		else
		{
			$level_no = 0;
			$SQL = "SELECT id, menu_name, where_to_show FROM `main_menu_master` where del_date is null and status=1 and where_to_show != 'link' and menu_level < ".uri_assoc('id') ." order by menu_level"; 
			//die($SQL);
			$query = $this->db->query($SQL);
			foreach ($query->result() as $row)
			{
			   $where_to_show[] = $row->where_to_show;
			   $id[] = $row->id; 
			   $menu_name[] = $row->menu_name; 
			   $level_no++;
			}
			//$row = $query->result();
			//$level_no =$row[0]->where_to_show;
			//die($level_no);
			$sub_data = '';  
			$sub_data .="<option value=''>Please select</option>";
			for($i=0;$i<$level_no;$i++){
				$sub_data .="<option value='$id[$i]'>".$menu_name[$i]."</option>"; 
			}
		}
		die($sub_data);
	} 
	
}

?>