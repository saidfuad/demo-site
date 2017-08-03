<?php 

class Mdl_address_book extends CI_Model{

	public function addressbook_opt() {		
		
		$company_id = $this->session->userdata('itms_company_id');
		$this->db->select('id, name');
		$this->order_by = 'name';
		if(isset($_REQUEST['id']) && $_REQUEST['id'] != ""){
			$this->db->where("group_id", $_REQUEST['id']);
		}
		$this->db->where("company_id",$company_id);
		$query = $this->db->get('itms_addressbook');
		return $query->result();
	}	
}

?>