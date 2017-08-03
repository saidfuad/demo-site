<?php 
class Settings_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Settings_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	public function get_alll_data()
	{
		$this->db->where("data_key = 'message'");
		$query = $this->db->get("tbl_settings");
		$row = $query->result();
		return $row[0];
	}
	
	public function save($data){
		
		$this->db->query("update tbl_settings set data_value = '".$data['message']."' where data_key = 'message'");
		return true;
	}
}
?>