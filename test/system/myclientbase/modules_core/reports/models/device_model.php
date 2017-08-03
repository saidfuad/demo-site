<?php 
class Device_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Device_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_locations() 
	{
		//Select table name
		$id = uri_assoc('id');
		//$device = $this->input->get('device');
		$this->db->where('phone_imei', $id);
		$this->db->order_by("id", "DESC");
		$this->db->limit(1);
		$query = $this->db->get($this->table_name);
		return $query->result();
	}
	public function get_new_locations() 
	{
		//Select table name
		$id 	= $this->input->post('id');
		$device = uri_assoc('id');
		$query = $this->db->select()->from($this->table_name)->where('phone_imei',$device)->where('id >',$id)->order_by('id');
		return $query->result();
	}
	public function get_links() 
	{
		//Select table name
		$this->db->select('phone_imei');
		$this->db->distinct();
		$query = $this->db->get($this->table_name);
		// $this->db->last_query();
		return $query->result();
	}
	
}
?>