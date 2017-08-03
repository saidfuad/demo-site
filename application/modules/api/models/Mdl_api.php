<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mdl_api
 *
 * @author Benson
 */
class Mdl_api extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->load->library("encrypt");
    }

    function app_auth($email, $password) {
        $this->db->select('users.user_id,first_name,last_name,password, users.user_type_id')
                ->from('users')
                ->join('logins','logins.user_id = users.user_id','left')
                ->where('logins.email', $email)
                ->where('status', 1)
                ->where('user_type_id', 4);

        $query = $this->db->get();
        $data = $query->row_array();

        if ($this->encrypt->decode($data['password']) == $password) {
            return array("user_id" => $data["user_id"], "first_name" => $data["first_name"], "last_name" => $data["last_name"], "user_type_id" => $data["user_type_id"]);
        } else {
            return FALSE;
        }
    }

    function vehicle($plate_no){
        $this->db->select('devices.phone_no')
            ->from('vehicles')
            ->join('vehicle_device_assignment','vehicle_device_assignment.vehicle_id=vehicles.vehicle_id')
            ->join('devices','devices.device_id=vehicle_device_assignment.device_id')
            ->where('vehicles.plate_no', $plate_no);

            $query = $this->db->get();
            $data = $query->row_array();

            if(!empty($data)){
                 return array("status" => 1, "data" => $data);
            }else{
                return false; 
            }

    }

    function get_account($account_name){
             $this->db->select('account_id')
                ->from('accounts')
                ->where('account_name',$account_name);

        $query = $this->db->get();
        $data = $query->row_array();

        if (!empty($data)) {
           return array("status" => 1, "data" => $data);
        } else {
            return FALSE;
        }


    }

    public function account_name($logins){

    	$this->db->where('phone_no',$logins['phone_no']);
    	$check=$this->db->get('logins')->row();

    	if(empty($check)){
    		$count = $this->db->get('accounts')->num_rows();
	        $count = $count+1;
	        return "Hawk_$count";
    	}

    	return 3;
    
    }

    public function open_account($account){

        $query = $this->db->insert('accounts', $account);

        if($query){
            return $this->db->insert_id();
        }

        return false;

    }

    public function save_account($data){
    	$query = $this->db->insert('users', $data);
        $inserted = $this->db->insert_id();

        $sql="UPDATE users SET add_uid = '".$this->db->insert_id()."' WHERE user_id ='".$this->db->insert_id()."'";
        $this->db->query($sql);

        if ($query) {
            return $inserted;
        }
    }

    public function save_vehicle($data) {

        $this->db->where('plate_no',$data['plate_no']);
        $check=$this->db->get('vehicles')->row();

        if(empty($check)){
        	$query = $this->db->insert('vehicles', $data);
        	$vehicleid = $this->db->insert_id();

         if ($query) {
            // $this->session->set_userdata('vehicle_image', '');
            return $vehicleid;
        }
        else{
        	return 3;
        }
        }

        return 3;
    }

    public function save_logins($logins){

        $query = $this->db->insert('logins', $logins);
        if($query){
            return 1;
        }

        return false;

    }

    function get_vehicles_details($plate_no) {
        $this->db->select("vehicle_id,model,plate_no,account_id,device_id, vehicle_types.name as model")
                ->from("vehicles")
                ->join('vehicle_types', 'vehicles.vehicle_type_id = id', 'left')
                ->where("plate_no", $plate_no);
			
        $query = $this->db->get();
        return $query->result_array();
    }
	
	function get_terminal_id($device_id){
		$this->db->select("terminal_id")
			->from("devices")
			->where("device_id",$device_id);
			
		$query = $this->db->get();
		return $query->row_array()["terminal_id"];			
	}
	
	function get_phone_no($device_id){
		$this->db->select("phone_no")
			->from("devices")
			->where("device_id",$device_id);
			
		$query = $this->db->get();
		return $query->row_array()["phone_no"];			
	}
	
	function get_device_details($serial_no){
		$this->db->select("device_id,status")
			->from("devices")
			->where("serial_no",$serial_no);
			
		$query = $this->db->get();
		return $query->result_array();			
	}
	
	function is_vehicle_assigned($vehicle_id){
		$this->db->select("vehicles.device_id")
			->from("vehicles")
			->where("vehicle_id",$vehicle_id);
		
		$query = $this->db->get();
		$device_id =  $query->row_array()["device_id"];
		if($device_id == NULL){
			return FALSE;
		}else{
		
		}		
	}
    
     function get_vehicles_dets($plate_no){
        $this->db->select("vehicle_id")
            ->from("vehicles")
            ->where("plate_no",$plate_no);

            $query = $this->db->get();
            $data = $query->row_array();


            if(!empty($data)){
                $vehicle_id = $data['vehicle_id'];
                $this->db->where("vehicle_id", $vehicle_id);
                $this->db->where("unassign_uid ", null);
                $this->db->select("device_id");
                $this->db->from("vehicle_device_assignment");

                $query2 = $this->db->get();
                $data2 = $query2->row_array();

                if(!empty($data2)){
                    $device_id = $data2["device_id"];
                    $this->db->where("device_id", $device_id);
                    $this->db->select("terminal_id,phone_no");
                    $this->db->from("devices");
                    $query3 = $this->db->get();
                    $data3 = $query3->row_array();
                     return array("status" => 1, "data" => $data3);

                     if(!empty($data3)){
                        $terminal_id = $data2["terminal_id"];
                        $phone_no = $data2["phone_no"];
                        return array("status" => 1, "data" => array("terminal_id" => $terminal_id, "phone_no" => $phone_no));
                     }else{
                        return array("status" => 10, "data" => array("message" => "Device Not Found"));
                     }
                }else{
                    return array("status" => 10, "data" => array("message" => "Vehicle Not Linked To Device"));
                }
            }else{
                return array("status" => 10, "data" => array("message" => "Vehicle Not Found"));
            }
             return false;
    }

    function assign_device($vehicle_id, $serial_no, $user_id,$account_id, $latitude, $longitude) {
		$device_data = $this->get_device_details($serial_no);
     	$device_id = $device_data[0]["device_id"];
	
		if($device_data[0]["status"]== "Assigned" || $device_data[0]["status"]== "Faulty"){
			return FALSE;
		}
		
		$status = $this->is_vehicle_assigned($vehicle_id);
	
        $this->db->trans_start();
        $this->db->insert("vehicle_device_assignment", array("vehicle_id" => $vehicle_id, "latitude" => $latitude, "longitude" => $longitude, "device_id" => $device_id, "assign_uid" => $user_id, "assign_date" => date("Y-m-d H:i:s")));
		
		$this->db->query("update devices set account_id = '".$account_id."',installer_id = '".$user_id."',installation_date = NOW(),status='Assigned' where serial_no = '".$serial_no."'");
		
		$this->db->query("update vehicles set active_status=1,last_seen=NOW(),device_id = '".$device_id."',latitude = '".$latitude."',longitude = '".$longitude."' where vehicle_id = '".$vehicle_id."'");
        $this->db->trans_complete();

        if($this->db->trans_status()){
			return array("terminal_id"=>$this->get_terminal_id($device_id), "phone_no"=>$this->get_phone_no($device_id));
		}else{
			return FALSE;
		}
    }

}