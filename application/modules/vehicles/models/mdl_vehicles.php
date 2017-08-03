<?php

class Mdl_vehicles extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_vehicles($hawk_account_id = null, $user_id = null, $owner_id = null) {
        $this->db->select('vehicles.*');
        $this->db->from('vehicles');

        if ($hawk_account_id != null) {
            $this->db->where('account_id', $hawk_account_id);
        }

        if ($user_id != null) {
            $this->db->where('vehicle_user_assignment.user_id', $user_id);
            $this->db->join('vehicle_user_assignment', 'vehicles.vehicle_id = vehicle_user_assignment.vehicle_id');
        }

        $this->db->order_by('model', 'ASC');
        return $this->db->get()->result();
    }

    function get_vehicle_by_id($vehicle_id) {
        $this->db->select('v.vehicle_id,plate_no,model,max_speed_limit');
        $this->db->from('vehicles v');

        $this->db->where('v.vehicle_id', $vehicle_id);


        $this->db->order_by('model', 'ASC');
        $query = $this->db->get();

        return $query->row_array();
    }

    function get_reminder($vehicle_id) {
        $this->db->select('reminders.*,vehicles.model, vehicles.plate_no,reminder_types.reminder_name');
        $this->db->from('reminders');
        $this->db->join('vehicles', 'vehicles.vehicle_id = reminders.vehicle_id');
        $this->db->join('reminder_types', 'reminder_types.reminder_type_id = reminders.reminder_type_id');
        $this->db->where('reminders.vehicle_id', $vehicle_id);

        $query = $this->db->get();

        return $query->result_array();
    }

    function get_vehicle_types() {
        $this->db->select('id,name')
                ->from('vehicle_types');

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get vehicle alert preferences
     * @param type $vehicle_id
     * @return type
     */
    function get_vehicle_alert_prefs($vehicle_id) {
        $this->db->select('vehicle_id,vat.alert_type_id,sms_alert,email_alert,name,vat.id');
        $this->db->from('vehicle_alert_types vat')
                ->join('alert_types at', 'vat.alert_type_id = at.alert_type_id');
        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->order_by('alert_type_id', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    function save_vehicle($data) {
        if ($this->check_vehicle_by_plate_number($data['plate_no'])) {
            return array("status" => 77);
            exit;
        }

        //Strip io_state alert settings data   
        $arm_sms = $data['arm_sms'];
        $power_cut_sms = $data['power_cut_sms'];
        $overspeed_sms = $data['overspeed_sms'];

        $arm_email = $data['arm_email'];
        $power_cut_email = $data['power_cut_email'];
        $overspeed_email = $data['overspeed_email'];

        unset($data['arm']);
        unset($data['power_cut']);
        unset($data['overspeed']);
        unset($data['id_arm']);
        unset($data['id_power_cut']);
        unset($data['id_overspeed']);
        unset($data['arm_sms']);
        unset($data['power_cut_sms']);
        unset($data['overspeed_sms']);
        unset($data['arm_email']);
        unset($data['power_cut_email']);
        unset($data['overspeed_email']);

        $this->db->trans_start();

        $this->db->insert('vehicles', $data);
        $vehicle_id = $this->db->insert_id();
        $user_id = $this->session->userdata('hawk_user_id');

        $alert_prefs = array(
            array('vehicle_id' => $vehicle_id, 'alert_type_id' => POWER_CUT_ALERT, 'sms_alert' => $power_cut_sms, 'email_alert' => $power_cut_email, 'add_uid' => $user_id, 'user_id' => $user_id),
            array('vehicle_id' => $vehicle_id, 'alert_type_id' => OVER_SPEED_ALERT, 'sms_alert' => $overspeed_sms, 'email_alert' => $overspeed_email, 'add_uid' => $user_id, 'user_id' => $user_id),
            array('vehicle_id' => $vehicle_id, 'alert_type_id' => ARM_ALERT, 'sms_alert' => $arm_sms, 'email_alert' => $arm_email, 'add_uid' => $user_id, 'user_id' => $user_id)
        );

        $this->db->insert_batch('vehicle_alert_types', $alert_prefs);
        $this->db->trans_complete();

        return array("status" => $this->db->trans_status(), "vehicle_id" => $vehicle_id, "plate_no" => $pl);
    }

    function update_vehicle($data) {

        //Strip io_state alert settings data
        $id_arm = $data['id_arm'];
        $id_powercut = $data['id_powercut'];
        $id_overspeed = $data['id_overspeed'];

        $arm_sms = $data['arm_sms'];
        $power_cut_sms = $data['powercut_sms'];
        $overspeed_sms = $data['overspeed_sms'];

        $arm_email = $data['arm_email'];
        $power_cut_email = $data['powercut_email'];
        $overspeed_email = $data['overspeed_email'];

        $vehicle_id = $data['vehicle_id'];

        unset($data['id_arm']);
        unset($data['id_powercut']);
        unset($data['id_overspeed']);
        unset($data['arm']);
        unset($data['powercut']);
        unset($data['overspeed']);
        unset($data['arm_sms']);
        unset($data['powercut_sms']);
        unset($data['overspeed_sms']);
        unset($data['arm_email']);
        unset($data['powercut_email']);
        unset($data['overspeed_email']);
        unset($data['vehicle_id']);

        $this->db->trans_start();

        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->update('vehicles', $data);

        //Updates arm data
        $data_arm = array(
            'sms_alert' => $arm_sms,
            'email_alert' => $arm_email
        );

        $this->db->where('id', $id_arm);
        $this->db->update('vehicle_alert_types', $data_arm);

        //Update power cut data
        $data_power_cut = array(
            'sms_alert' => $power_cut_sms,
            'email_alert' => $power_cut_email
        );

        $this->db->where('id', $id_powercut);
        $this->db->update('vehicle_alert_types', $data_power_cut);

        //Update overspeed data
        $data_overspeed = array(
            'sms_alert' => $overspeed_sms,
            'email_alert' => $overspeed_email
        );

        $this->db->where('id', $id_overspeed);
        $this->db->update('vehicle_alert_types', $data_overspeed);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return true;
        }

        return false;
    }

    function delete_vehicle($vehicle_id) {
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql = "UPDATE vehicles SET del_date = '" . $t . "' WHERE vehicle_id ='" . $vehicle_id . "'";
        $this->db->query($sql);
    }

    function check_vehicle_by_plate_number($plate_number) {
        $query = $this->db->get_where('vehicles', array('plate_no' => $plate_number));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    function get_vehicle($vehicle_id, $user_id = null) {
        $this->db->select("vehicles.*");
        $this->db->from('vehicles');

        // if ($this->session->userdata('protocal') <= 7 && $user_id!=null) {
        $this->db->where('vehicles.vehicle_id', $vehicle_id);
        //}

        $query = $this->db->get();

        return $query->result();
    }

    public function get_vehicle_categories($keyword) {
        $this->db->select('vehicles_categories.assets_cat_name,vehicles_categories.assets_category_id');
        $this->db->from('vehicles_categories');
        // $this->db->where('user_id', $this->session->userdata('itms_user_id'));
        $this->db->order_by('assets_category_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_vehicle_type($hawk_account_id = null) {
        $this->db->where('account_id', $hawk_account_id);
        $this->db->order_by('model', 'ASC');
        return $this->db->get('vehicles')->result();
    }

    function get_terminal_id($device_id) {
        $this->db->select("terminal_id")
                ->from("devices")
                ->where("device_id", $device_id);

        $query = $this->db->get();
        return $query->row_array()["terminal_id"];
    }

    function get_vehicle_id($device_id) {
        $this->db->select("vehicle_id")
                ->from("vehicles")
                ->where("device_id", $device_id);

        $query = $this->db->get();
        return $query->row_array()["vehicle_id"];
    }

    function toggle_vehicle_engine($device_id, $command) {
        $terminal_id = $this->get_terminal_id($device_id);
        $vehicle_id = $this->get_vehicle_id($device_id);
        return $this->db->insert("scheduled_commands", array("vehicle_id" => $vehicle_id, "terminal_id" => $terminal_id,
                    "command" => $command,
                    "add_uid" => $this->session->userdata("hawk_user_id"),
                    "account_id" => $this->session->userdata("hawk_account_id")));
    }

    function get_command_history($vehicle_id) {
        $this->db->select("plate_no,model,command,CONCAT(u.first_name,' ',u.last_name) as add_uid,count,response,sc.add_date")
                ->from("scheduled_commands sc")
                ->join("vehicles", "vehicles.vehicle_id = sc.vehicle_id")
                ->join("users u", "u.user_id = sc.add_uid")
                ->where("sc.vehicle_id", $vehicle_id);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Check if vehicle has device and is active
     * @param String $vehicle_id
     * @return Boolean
     */
    function has_active_device($vehicle_id) {
        $this->db->select('count(*) as count')
                ->from('vehicles v')
                ->join('vehicle_device_assignment vda', 'vda.vehicle_id = v.vehicle_id')
                ->join('devices d', 'vda.device_id = d.device_id')
                ->where('d.status', 'Assigned')
                ->where('v.vehicle_id', $vehicle_id);

        $query = $this->db->get();
        $data = $query->row_array();
        return ($data['count'] > 0) ? TRUE : FALSE;
    }

    /**
     * Function to check whether a vehicle has active command.
     * Only one command can exist at any given time.
     * @param String $vehicle_id
     * @return Boolean
     */
    function has_active_cmd($vehicle_id) {
        $this->db->select('count(*) as count')
                ->from('scheduled_commands sc')
                ->where('sc.vehicle_id', $vehicle_id)
                ->where('sc.count < 3')
                ->where('response IS NULL');

        $query = $this->db->get();
        $data = $query->row_array();
        return ($data['count'] > 0) ? TRUE : FALSE;
    }

    /**
     * Get hours for a scheduled worktime command
     * @param type $id
     * @return type
     */
    function get_hours($id) {
        $this->db->select("hours")
                ->from("scheduled_commands")
                ->where("id", $id);

        $query = $this->db->get();
        return $query->row_array()["hours"];
    }

    /**
     * Update vehicles' work times' expiry date
     * @param type $id
     * @param type $vehicle_id
     */
    function update_work_time_expiry_date($id, $vehicle_id) {
        $hours_in_secs = $this->get_hours($id) * 60 * 60;
        $now = strtotime(date("Y-m-d H:i:s"));
        $new_expiry_date = date('Y-m-d H:i:s', $hours_in_secs + $now);

        $this->db->query("UPDATE vehicles SET expiry_date = '$new_expiry_date' WHERE vehicle_id = '$vehicle_id' ");
    }

    ##Sacco Details

    public function get_sacco_vehicles($sacco_id = null, $owner_id = null) {
        $this->db->select('vehicles.vehicle_id,  plate_no, latitude, longitude, address, DATE_FORMAT(last_seen, "%d-%m-%Y %h:%i %p") as last_seen');
        $this->db->from('vehicles');
        $this->db->join('sacco_vehicles', 'vehicles.vehicle_id = sacco_vehicles.vehicle_id');

        if ($sacco_id != null) {
            $this->db->where('sacco_vehicles.sacco_id', $sacco_id);
        }

        if ($owner_id != null) {
            $this->db->where('sacco_vehicles.owner_id', $owner_id);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get Plate No by vehicle_id
     * @param type $vehicle_id
     * @return type
     */
    function get_plate_no($vehicle_id) {
        $this->db->where('vehicle_id', $vehicle_id)
                ->select('plate_no')
                ->from('vehicles');

        $query = $this->db->get();
        return $query->row_array()['plate_no'];
    }

    function get_vehicle_id_by_terminal($terminal_id) {
        $this->db->select('vehicle_id')
                ->from('vehicles')
                ->join('devices', 'vehicles.device_id = devices.device_id')
                ->where('terminal_id', $terminal_id);

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row_array()['vehicle_id'] : NULL;
    }

    function get_account_id_by_vehicle_id($vehicle_id) {
        $this->db->select('account_id')
                ->from('vehicles')
                ->where('vehicle_id', $vehicle_id);

        $query = $this->db->get();
        return $query->row_array()['account_id'];
    }

    /**
     * Update vehicle current status
     * @param type $vehicle_id
     * @param type $latitude
     * @param type $longitude
     * @param type $orientation
     * @param type $ignition
     * @param type $speed_alert
     * @param type $arm_alert
     * @param type $power_cut
     * @param type $speed
     * @param type $address
     */
    function update_vehicle_current_status($vehicle_id, $latitude, $longitude, $orientation, $ignition, $speed_alert, $arm_alert, $power_cut, $speed, $address, $tracking_time) {
        $this->db->query("update vehicles set latitude = '" . $latitude . "', longitude='" . $longitude .
                "', orientation='" . $orientation .
                "', ignition='" . $ignition .
                "', speed_alert='" . $speed_alert .
                "', arm_alert='" . $arm_alert .
                "', power_cut='" . $power_cut .
                "', address='" . $address .
                "', speed='" . $speed . "', last_seen='".$tracking_time."' where vehicle_id = '" . $vehicle_id . "'");
    }

    function get_vehicle_by_plate_number($plate_no) {
        $this->db->where('plate_no', $plate_no);
        $this->db->where('device_id', NULL);
        return $this->db->get('vehicles')->row();
    }

    function check_vehicle_linked($plate_no) {
        $this->db->where('plate_no', $plate_no);
        $this->db->where('device_id!=', NULL);
        return $this->db->get('vehicles')->row();
    }

    function get_vehicles_by_last_seen(){
        $this->db->select('plate_no,last_seen')
        ->from('vehicles')
        ->where('last_seen < (NOW() - INTERVAL 15 MINUTE)')
        ->where('account_id',46);

        $query = $this->db->get();
        return $query->result_array();
    }

}
