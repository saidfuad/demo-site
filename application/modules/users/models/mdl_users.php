<?php

class Mdl_users extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('login/mdl_create_account');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('sendmail');
        $this->load->library('smssend');
    }

    function get_users($hawk_account_id = null, $user_type_id = null) {
        $this->db->select('users.*');


        if ($user_type_id != null) {
            $this->db->where('users.user_type_id', $user_type_id);
        }

        if ($hawk_account_id != null) {
            $this->db->select('accounts.*');
            $this->db->where('users.account_id', $hawk_account_id);
            $this->db->join('accounts', 'accounts.account_id=users.account_id');
        }

        $this->db->where('users.user_type_id !=', 4);
        $this->db->order_by('first_name', 'ASC');
        $query = $this->db->get('users');
        return $query->result();
    }

    function assignedvehicles($user_id) {
        $this->db->select('vehicles.*,vehicle_user_assignment.assign_id');
        $this->db->where('userid', $user_id);
        $this->db->join('vehicle_user_assignment', 'vehicle_user_assignment.vehicle_id=vehicles.vehicle_id');
        $query = $this->db->get('vehicles');

        return $query->result();
    }

    function get_assigned($user_id) {
        $this->db->select('vehicles.*,assign_id');
        $this->db->where('vehicle_user_assignment.user_id', $user_id);
        $this->db->where('vehicle_user_assignment.unassign_uid', null);
        $this->db->join('vehicles', 'vehicles.vehicle_id=vehicle_user_assignment.vehicle_id');
        return $this->db->get('vehicle_user_assignment')->result();
    }

    function assign_vehicles($give) {
        $query = $this->db->insert_batch('vehicle_user_assignment', $give);

        // if ($query) {
        //      $this->db->insert_id();
        // }
    }

    function unassign($take) {
        $this->db->where('user_id', $take['user_id']);
        $this->db->where('vehicle_id', $take['vehicle_id']);
        $this->db->update('vehicle_user_assignment', $take);
    }

    function unassignn($take) {
        $this->db->where('assign_id', $take['assign_id']);
        $this->db->update('vehicle_user_assignment', $take);
    }

    function update_assign($give) {

        $this->db->where('user_id', $give['user_id']);
        $this->db->where('vehicle_id', $give['vehicle_id']);
        $vehuser = $this->db->get('vehicle_user_assignment')->row();

        if (empty($vehuser)) {
            $query = $this->db->insert('vehicle_user_assignment', $give);
        } else {
            $give['unassign_uid'] = null;
            $give['unassign_date'] = null;
            $this->db->where('user_id', $give['user_id']);
            $this->db->where('vehicle_id', $give['vehicle_id']);
            $this->db->update('vehicle_user_assignment', $give);
        }
    }

    function pickvehicles($hawk_user_id = null) {
        $this->db->where('account_id', $hawk_user_id);
        $query = $this->db->get('vehicles');

        return $query->result();
    }

    function get_all_vehicles($hawk_account_id = null) {
        $this->db->where('account_id', $hawk_account_id);
        $this->db->order_by('model', 'ASC');
        return $this->db->get('vehicles')->result();
    }

    function save_user($data, $logins = null, $account_type = null) {
        $this->db->trans_start();

        if ($account_type != null) {
            $data['account_id'] = $this->mdl_create_account->open_account(array("account_type" => $account_type));
        }

        $this->db->insert('users', $data);
        $logins['user_id'] = $this->db->insert_id();
        $this->db->insert('logins', $logins);
        $this->db->trans_complete();
        return array("status" => $this->db->trans_status(), "user_id" => $logins['user_id'],
            "account_id" => $data["account_id"],
            "account_name" => "Hawk_" . $data['account_id']);
    }

    function update_user($data, $dataa) {
        $this->db->where('user_id', $data['user_id']);
        $query = $this->db->update('users', $data);

        if ($query) {
            $this->db->where('user_id', $dataa['user_id']);
            $query = $this->db->update('logins', $dataa);

            if ($query) {
                return true;
            }

            return false;
        }

        return false;
    }

    function create_login_details($password, $phone_no, $email) {
        $logins['password'] = $this->encrypt->encode($password);
        $logins['email'] = $email;
        $logins['phone_no'] = $phone_no;
        return $logins;
    }

    function get_user_by_id($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->get('users')->row();
    }

    function check_email($email) {
        $this->db->where('email', $email);
        $this->db->or_where('company_email', $email);
        return $this->db->get('users')->row();
    }

    function check_phone_no($phone_no) {
        $this->db->where('phone_no', $phone_no);
        return $this->db->get('logins')->row();
    }

    function check_emaill($email, $userid) {
        $this->db->where('email', $email);
        $this->db->where('user_id !=', $userid);
        return $this->db->get('users')->row();
    }

    function check_phone_nos($phone_no, $userid) {
        $this->db->where('phone_no', $phone_no);
        $this->db->where('user_id !=', $userid);
        return $this->db->get('logins')->row();
    }


     function get_user_by_phone($phone_no) {
        $this->db->where('phone_no', $phone_no);
        $this->db->or_where('company_phone_no',$phone_no);
        return $this->db->get('users')->row();
    }
  
    function send_registration_email($email, $phone_no, $password) {
        $to = array($email);
        $subj = "HAWK Account Registration";
        $hawk_logo = base_url() . "/assets/images/system/hawk_logo.png";
        $url = base_url();

        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                        <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                            <h1><img src="' . $hawk_logo . '"></h1>
                        </div>
                        <div style="padding:20px;">
                            Dear User,<br><br>
                            Your have successfully been registered on HAWK.
                            Your HAWK account account is now active.
                            <br>
                            New Login Details<br>
                            Email : ' . $phone_no . '<br>
                            Password: ' . $password . ' <br>
                            Url: ' . $url . ' <br>
                            <br>
                            <br>
                            Hawk. Always Watching | All Rights Reserved.
                            <br>
                        </div>
                    </div>';

        return $this->sendmail->send_mail($to, $subj, $message);
    }

    function send_registration_sms($phone_no, $password)    {
          
             $message = "HAWK Account Registration was Successful.\r\nPlease Login to your account using the following credentials.\r\nUrl  : ".base_url()." \r\nUserName : " 
            . $phone_no . " \r\nPassword : " . $password;
           $res = $this->smssend->send_text_message($phone_no, $message);
    }

      function send_vehicle_added_sms($phone_no, $plate_no)    {
                       $message = "HAWK: $plate_no has been added to your account. \r\nLogin to your account to see the updates\r\nUrl  : ".base_url()."\r\n!! Always Watching !!";
             $res = $this->smssend->send_text_message($phone_no, $message);
    }

    function create_sacco_member($sacco_id, $first_name, $last_name, $phone_no, $plate_no, $user_id) {
        $data = array();
        $data ["first_name"] = $first_name;
        $data ["last_name"] = $last_name;
        $data ["phone_no"] = $phone_no;
        $data ["add_uid"] = $user_id;
        
        //Check if vehicle is linked or not if linked return message vehicle linked already
        $vehicle = $this->mdl_vehicles->check_vehicle_linked($plate_no);
        if($vehicle)
        {
            return array("status"=> 10, "data"=>array("message"=>"$plate_no already Linked"));
        }

        //Check if vehicle exists but hasn't been linked. this will prevent creating a new vehicle if it exists already
        $vehicle = $this->mdl_vehicles->get_vehicle_by_plate_number($plate_no);
        $attendant =$this->get_user_by_id($user_id);
     
        if($attendant->user_type_id != $this->config->item("admin_user_type")){
            if($attendant->user_type_id != $this->config->item("installer")){
                         return array("status"=> 10, "data"=>array("message"=>"User not allowed to add vehicle. Contact Admin."));
            }
        }
        
        $user = $this->get_user_by_phone($phone_no);
        $this->db->trans_start();
    
        //if user does not exists create a new user
        if(!$user)
        {
            $phone_number = str_split($phone_no);
            $length = sizeof($phone_number)-1;
            $password = strtolower($first_name) .  $phone_number[$length - 3] .
                    $phone_number[$length - 2] .  $phone_number[$length - 1] .
                    $phone_number[$length];

            $logins = $this->create_login_details($password, $phone_no, '');
            $attendant = (object) $this->save_user($data, $logins, "personal");
         }

        $account_id = $attendant->account_id;
        $owner_id = $attendant->user_id;
  
 
        //if vehicle does not exist create new vehicle 
        if(!$vehicle)
        {
             $vehicle_data = array("account_id" => $account_id, "plate_no" => $plate_no, "add_uid" => $user_id, "vehicle_type_id"=> 7, "model"=> "Matatu");
             $vehicle =(object) $this->mdl_vehicles->save_vehicle($vehicle_data);
        }
       
        $vehicle_id = $vehicle->vehicle_id;
      
        //check to see if a vehicle exist in a sacco. if it does not need to add it again.
        $check_sacco_vehicle =$this->mdl_sacco->check_vehicle_exist($vehicle_id);
        if(!$check_sacco_vehicle)
        {
             $this->mdl_sacco->add_sacco_vehicle(array("vehicle_id" => $vehicle_id, "sacco_id" => $sacco_id, "owner_id" => $owner_id));
        }
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status()) {
            if(!$user)
            {
                $this->send_registration_sms($phone_no, $password);  
            }
            
            $this->send_vehicle_added_sms($phone_no, $plate_no);
            $response = $this->Mdl_api->get_vehicles_details($plate_no);

            return array("status"=> 1, "data"=>$response[0]);
         
         } else {
               return array("status"=> 10, "data"=>array("message"=>"SACCO Member not created"));
            }
    }

    function get_users_api($hawk_account_id) {
        $this->db->select('user_id, first_name, last_name');
        $this->db->where('users.account_id', $hawk_account_id);
        $query = $this->db->get('users');
        return $query->result();
    }
}

?>
