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
class Mdl_apimobile extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->load->library("encrypt");
        $this->load->library('emailsend');
    }

    function tracking_time($date,$time){
        $year=substr($date, 0,2);
        $month=substr($date, 2,2);
        $day=substr($date, 4,2);
        $year="20".$year;

        $hours=substr($time, 0,2);
        $min=substr($time, 2,2);
        $sec=substr($time, 4,2);

        $timestamp=$year."-".$month."-".$day." ".$hours.":".$min.":".$sec;

        return date( "Y-m-d H:i:s", strtotime( $timestamp ) - 5 * 3600 );


    }


    function updatetime(){
        $date=date("Y-m-d H:i:s");
        $this->db->select('id,date,time');
        $this->db->where('tracking_time',"00-00-00 00:00:00");
        $this->db->limit(100000);
        $data =  $this->db->get('gps_track_points')->result_array();
        $result = "";
        $i = 0;
        $last_id = 0;
      foreach ($data as $new) {
       
            $this->updatedb($new);
            $i = $i + 1;
            $last_id = $new['id'];
            
        }

        return "start_time ".$date." end_time".date("Y-M-d H:i:s"). " count ". $i ." last id: ".$last_id ;

    }

    function updatedb($data){
        $this->db->where('id',$data['id']);
        $this->db->set('tracking_time',$this->tracking_time($data['date'],$data['time']));
        $this->db->update('gps_track_points');
      
    }

    function app_auth($email, $password) {
        $this->db->select('users.*,accounts.account_name,password')
                ->from('logins')
                ->join('users','users.user_id = logins.user_id')
                ->join('accounts','accounts.account_id = users.account_id')
                ->where('users.status', 1)
                ->where('logins.email', $email)
                ->or_where('logins.phone_no', $email);

        $query = $this->db->get();
        $data = $query->row_array();

        if(!empty($data)){
        if ($this->encrypt->decode($data['password']) == $password) {
            if($data["company_email"]==null){
                return array("status" => 1, "data" => array("user"=>array("first_name" => $data["first_name"], "last_name" => $data["last_name"],"account_id" => $data["account_id"],"account_name" => $data["account_name"],"user_id" => $data["user_id"])));
            }
            else if($data["company_email"]!=null){
                return array("status" => 2, "data" => array("user"=>array("company_name" => $data["company_name"],"account_id" => $data["account_id"],"account_name" => $data["account_name"],"user_id" => $data["user_id"])));       
            }
        }
        else{
            return FALSE;
        }
        }
        else{
            return FALSE;
        }

     
    }

    public function vehicles($user_id){
        $this->db->select('users.*')
                 ->from('users')
                 ->where('user_id', $user_id);

        $query = $this->db->get();
        $data = $query->row_array();


        if(!empty($data)){
            if($data['user_type_id']==2){
                $this->db->select('vehicles.vehicle_id,vehicles.model,vehicles.plate_no,vehicles.latitude,vehicles.longitude,vehicles.last_seen,devices.phone_no')
                 ->from('vehicles')
                 ->join('vehicle_device_assignment','vehicle_device_assignment.vehicle_id=vehicles.vehicle_id')
                 ->join('devices','devices.device_id=vehicle_device_assignment.device_id')
                 ->where('vehicles.account_id', $data['account_id']);
                 $query_result = $this->db->get();
                 $message="No Vehicles Added";

            }else{
                $this->db->select('vehicles.vehicle_id,vehicles.model,vehicles.plate_no,vehicles.latitude,vehicles.longitude,vehicles.last_seen,devices.phone_no')
                ->from('vehicles')
                ->join('vehicle_user_assignment','vehicle_user_assignment.vehicle_id = vehicles.vehicle_id')
                ->join('vehicle_device_assignment','vehicle_device_assignment.vehicle_id=vehicles.vehicle_id')
                ->join('devices','devices.device_id=vehicle_device_assignment.device_id')
                ->where('vehicle_user_assignment.user_id',$user_id)
                ->where('vehicle_user_assignment.unassign_uid', null);
                $query_result = $this->db->get();
                $message="No Vehicles Assigned";

            }


                $data = $query_result->result_array();
                if(!empty($data)){
                    return array("status" => 1, "data" => array("vehicle_list"=> $data));
                }else{
                    return array("status" => 10, "data" => array("message" => $message));
                }

        }
        else{
            return array("status" => 10, "data" => array("message" => "User Does Not Exist"));
        }
        


    }

    public function vehicle($vehicle_id){
        $this->db->select('vehicles.vehicle_id,vehicles.model,vehicles.plate_no,vehicles.latitude,vehicles.longitude,vehicles.last_seen,devices.phone_no')
            ->from('vehicles')
            ->join('vehicle_device_assignment','vehicle_device_assignment.vehicle_id=vehicles.vehicle_id')
            ->join('devices','devices.device_id=vehicle_device_assignment.device_id')
            ->where('vehicles.vehicle_id', $vehicle_id);

            $query = $this->db->get();
            $data = $query->row_array();

            if(!empty($data)){
                 return array("status" => 1, "data" => array("vehicle"=> $data));
            }else{
                return false; 
            }

    }

    public function forgot($input){
        $this->db->select('logins.*')
                 ->from('logins')
                 ->where('email',$input)
                 ->or_where('phone_no',$input);

        $query = $this->db->get();
        $data = $query->row_array();

        if(!empty($data)){
            return $data;     
        }else{
            return false; 
        }
    }

      public function account_name($logins){

        if ($this->check_login_phone_no($logins['phone_no'])) {

            return 2;

        }else{

            return 1;
        }
    }

    public function change_pass($user_id,$newpass){
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$newpass);
        $this->db->update('logins');
    }

      public function check_login_phone_no($phone_no){

        $query = $this->db->get_where('logins', array('phone_no'=>$phone_no));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;

    }

     public function open_account($account){

        $query = $this->db->insert('accounts', $account);

        if($query){
            $insertaid= $this->db->insert_id();
            $account_name="Hawk_$insertaid";

            $sql="UPDATE accounts SET account_name = '".$account_name."' WHERE account_id ='".$insertaid."'";
            $this->db->query($sql);

            return $insertaid;
        }

        return false;

    }

      public function save_account($select, $data){

        if($select == 1){

            if ($this->check_user_phone_no($data['phone_no'])) {
                return "phone_exists";
            }
            if (!empty($data['email']) && $this->check_user_email($data['email'])) {
                return "email_exists";
            }

        }else{

            if ($this->check_company_phone_no($data['company_phone_no'])) {
                return "phone_exists";
            }
            if (!empty($data['company_email']) && $this->check_company_email($data['company_email'])) {
                return "email_exists";
            }

        }

        $query = $this->db->insert('users', $data);
        $inserted = $this->db->insert_id();

        $sql="UPDATE users SET add_uid = '".$this->db->insert_id()."' WHERE user_id ='".$this->db->insert_id()."'";
        $this->db->query($sql);

        if ($query) {
            return $inserted;
        }

        return "phone_exists";

    }

      public function check_user_phone_no($phone_no){

        $query = $this->db->get_where('users', array('phone_no'=>$phone_no));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

     public function check_company_phone_no($phone_no){

        $query = $this->db->get_where('users', array('company_phone_no'=>$phone_no));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;

    }

     public function save_logins($logins){

        if ($this->check_login_phone_no($logins['phone_no'])) {
            return 2;
        }

        $query = $this->db->insert('logins', $logins);
        if($query){
            return 1;
        }

        return 2;

    }

    public function check_user_email($email){

        $query = $this->db->get_where('users', array('email'=>$email));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;

    }

      public function check_company_email($email){

        $query = $this->db->get_where('users', array('company_email'=>$email));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function get_trip_master($vehicle_id){
        $this->db->select('trip_id,start_time,stop_time,start_latitude,stop_latitude,start_longitude,stop_longitude,start_address,stop_address,distance');
        $this->db->where('vehicle_id',$vehicle_id);
        $this->db->where('status',0);
        $this->db->limit(15);
        $this->db->order_by("start_time","desc");

        $data=$this->db->get('trip_master')->result_array();

        if(!empty($data)){
            return array("status" => 1, "data" => array("history"=>$data));
        }else{
          return false;
        }

        
    }
    
    public function get_vehicle_details($reg_no)
    {
        //$this->db->select('vehicle_id');
       // $this->
       // $vehicle_assignment_id 
    }
    
    

    public function get_route_points($trip_id){
        $this->db->select('start_time,stop_time,vehicle_id');
        $this->db->where('trip_id',$trip_id);
        $query=$this->db->get('trip_master')->row_array();

        

        if(!empty($query)){
            $this->db->select('latitude,longitude,distance,speed');
        $this->db->where('vehicle_id',$query['vehicle_id']);
        $this->db->where('tracking_time >=',$query['start_time']);
        $this->db->where('tracking_time <=',$query['stop_time']);
            $this->db->order_by('tracking_time', 'desc');

        $data=$this->db->get('gps_track_points')->result_array();
               if(!empty($query)){
            return array("status" => 1, "data" => array("points"=>$data));
               }
 
        }
        
        return false;

    }

}