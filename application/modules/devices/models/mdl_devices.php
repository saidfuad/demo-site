<?php

class Mdl_devices extends CI_Model{

    function __construct () {
        parent::__construct();
    }

    function get_devices () {
        $query = $this->db->get('devices');
        return $query->num_rows();
    }

    function get_all(){
        $this->db->select('devices.*');
        // $this->db->join('vehicle_device_assignment','vehicle_device_assignment.device_id=devices.device_id','right');
        // $this->db->join('vehicles','vehicles.vehicle_id=vehicle_device_assignment.vehicle_id');
        return $this->db->get('devices')->result();
    }

    function save_device($data){
        $query=$this->db->insert('devices',$data);
        if($query){
            return true;
        }
        return false;
    }

    function get_plate($device_id){
        $this->db->select('vehicles.plate_no,vehicles.vehicle_id');
        $this->db->where('vehicle_device_assignment.device_id',$device_id);
        $this->db->join('vehicle_device_assignment','vehicle_device_assignment.device_id=devices.device_id');
        $this->db->join('vehicles','vehicles.vehicle_id=vehicle_device_assignment.vehicle_id');
        return $this->db->get('devices')->row();

    }

    function update_device($data,$did,$vid,$dataa){
        $this->db->where('device_id',$did);
        $this->db->update('devices',$data);

        $this->db->where('device_id',$did);
        $this->db->where('vehicle_id',$vid);
        $query=$this->db->update('vehicle_device_assignment',$dataa);

        if($query){
            return true;
        }
        else{
            return false;
        }


    }

    function get_installer($installer_id){
        $this->db->where('user_id',$installer_id);
        return $this->db->get('users')->row();
    }

    function get_account($account_id){
        $this->db->where('account_id',$account_id);
        return $this->db->get('accounts')->row();
    }

    function get_device($device_id){
        $this->db->where('device_id',$device_id);
        return $this->db->get('devices')->row();
    }

    // function save_user($data){
    // 	$query = $this->db->insert('users', $data);

    //     if ($query) {
    //         return $this->db->insert_id();
    //     }
        
    //     return 77;
    // }

    // function save_login($dataa){
    // 	$query = $this->db->insert('logins', $dataa);

    //     if ($query) {
    //         return true;
    //     }
        
    //     return false;
    // }


    // function update_user($data,$dataa) {
    //     $this->db->where('user_id', $data['user_id']);
    //     $query = $this->db->update('users', $data);

    //     if ($query) {
    //     // $this->db->where('user_id', $dataa['user_id']);
    //     // $query = $this->db->update('logins', $dataa);

    //     // 	if($query){
    //     // 		return true;
    //     // 	}

    //     	return true;
            
    //     }
        
    //     return false;
        
    // }

    // function get_user_by_id ($user_id) {
    //     $this->db->select('users.*');
    //     $this->db->from('users');
        
    //     $this->db->where('user_id', $user_id);
    //     $query = $this->db->get();
       
    //     return $query->row_array();
    // }

    // function check_email($email){
    // 	$this->db->where('email',$email);
    // 	return $this->db->get('users')->row();
    // }

    // function check_emaill($email,$userid){
    // 	$this->db->where('email',$email);
    // 	$this->db->where('user_id !=',$userid);
    // 	return $this->db->get('users')->row();
    // }
    
    
    function get_device_id_by_vehicle_id($vehicle_id) {
        $this->db->select('device_id')
                ->from('vehicles')
                ->where('vehicle_id', $vehicle_id);

        $query = $this->db->get();
        return $query->row_array()['device_id'];
    }

}