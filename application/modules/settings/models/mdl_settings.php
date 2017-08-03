<?php 

class Mdl_settings extends CI_Model{

	public function save_landmark () {

	}

	public function get_landmark($user)
	{
                //Added by Poonam 21-1-2015 7:47 PM
		$query = $this->db->query("SELECT lm.*, (select group_concat(assets_name) assets from assests_master where find_in_set(id, lm.device_ids)) as assets FROM `landmark` lm where lm.add_uid = $user and lm.del_date is null and lm.status = 1", FALSE);
		//End
                return $query->result();
	}	

    function check_device_number($device_id) {
        $query = $this->db->get_where('itms_devices', array('device_id'=>$device_id));
        
        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }
	function check_serial($serial_no) {
		
        $query = $this->db->get_where('itms_devices', array('serial_no'=>$serial_no));
        
        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function save_device($data) {
        if ($this->check_device_number($data['device_id'])) {
            return 77;
            exit;
        }

        if ($this->check_serial($data['serial_no'])) {
            return 78;
            exit;
        }

        $query = $this->db->insert('itms_devices', $data);
        
        return false;
        
    }

    public function get_devices(){
    	$this->db->where('del_date IS NULL');
        $query = $this->db->get('itms_devices');

        return $query->result();
    }
    public function get_device ($company_id=null) {
          
       if ($company_id != null) {
            $this->db->where('itms_devices.company_id', $company_id);
       }
        $this->db->where('id', $this->uri->segment(3));
        $query = $this->db->get('itms_devices');
       
        return $query->result();
    }

    function update_device($data){
        $this->db->where('id', $data['id']);
        $this->db->update('itms_devices', $data);
    }

    function delete_device($id){
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_devices SET del_date = '".$t."' WHERE id ='".$id."'";
        $this->db->query($sql);
    }


    function getroutes($company_id=nulls){
        $this->db->select('itms_routes.route_id,itms_routes.route_name');
        $this->db->from('itms_routes');
        $this->db->where('company_id', $this->session->userdata('itms_company_id'));
        $this->db->order_by('route_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getclients($company_id=nulls){
        $this->db->select('itms_client_master.client_name,itms_client_master.client_id');    
        $this->db->from('itms_client_master'); 
        $this->db->where('company_id', $this->session->userdata('itms_company_id'));
        $this->db->order_by('client_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getassets($company_id=nulls){
        $this->db->select('CONCAT(itms_assets.assets_friendly_nm, " - ", itms_assets.assets_name ) AS asset_name, itms_assets.assets_friendly_nm, itms_assets.assets_name, itms_assets.asset_id, itms_assets.personnel_id');  
        $this->db->from('itms_assets');
        $this->db->where('company_id', $this->session->userdata('itms_company_id'));
        $this->db->where('del_date', NULL);
        $this->db->order_by('asset_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}

?>