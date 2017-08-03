<?php

class Mdl_listener_api extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('gps_utilities');
        $this->load->model('devices/mdl_devices');
    }
    
    function get_current_location($vehicle_id)
    {
          $this->db->select("latitude,longitude,last_seen")
                ->from("vehicles")
                ->where("vehicle_id", $vehicle_id)
               ->limit(1);
        $query = $this->db->get();
       return $query->row_array();
    }

    function get_distance($vehicle_id, $lat_to, $lng_to, $tracking_time) {
        $data= $this->get_current_location($vehicle_id);
        
        echo "CALCULATING DISTANCE..." . PHP_EOL;
        echo "Time difference : " .$data['last_seen']." < " . $tracking_time. PHP_EOL;
        if ($data['last_seen'] < $tracking_time && (
           $data['latitude'] != $lat_to ||
           $data['longitude'] != $lng_to)) {
            $distance = $this->gps_utilities->calculate_distance($data['latitude'], $data['longitude'], $lat_to, $lng_to);
            echo "calculate_distance(".$data['latitude'].", ".$data['longitude'].", $lat_to, $lng_to)". PHP_EOL;
            echo "CALCULATED DISTANCE: " . $distance . PHP_EOL;
            return ceil($distance);
        }

        return 0;
    }

    
    function listen($vehicle_id, $command, $gps_availability, $latitude, $longitude, $speed, $orientation, $ignition, $overspeed, $arm_alert, $power_cut, $mile_post, $mile_data, $date, $time, $tracking_time) {
        $device_id = $this->mdl_devices->get_device_id_by_vehicle_id($vehicle_id);
        $account_id = $this->mdl_vehicles->get_account_id_by_vehicle_id($vehicle_id);
        $distance = $this->get_distance($vehicle_id, $latitude, $longitude,  $tracking_time);

        $data = array('account_id' => $account_id,
            'vehicle_id' => $vehicle_id,
            'device_id' => $device_id,
            'distance' => abs($distance),
            'command' => $command,
            'date' => $date,
            'gps_availability' => $gps_availability,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'speed' => $speed,
            'time' => $time,
            'orientation' => $orientation,
            'ignition' => $ignition,
            'speed_alert' => $overspeed,
            'arm_alert' => $arm_alert,
            'power_cut' => $power_cut,
            'mile_post' => $mile_post,
            'mile_data' => $mile_data,
            'tracking_time' => $tracking_time);

        $address = "";
        
        $last_seen = $this->get_current_location($vehicle_id)['last_seen'];
        if($last_seen < $tracking_time) 
        {
           $this->mdl_vehicles->update_vehicle_current_status($vehicle_id, $latitude, $longitude, $orientation, $ignition, $overspeed, $arm_alert, $power_cut, $speed, $address, $tracking_time);
        }

        return $this->db->insert('gps_track_points', $data);
    }

    function get_reciever_data($vehicle_id, $alert_type_id) {
        $this->db->where('vehicle_id', $vehicle_id)
                ->where('alert_type_id', $alert_type_id)
                ->select('phone_no,email,u.user_id,u.account_id')
                ->from('vehicle_alert_types vat')
                ->join('users u', 'u.user_id = vat.user_id');

        $query = $this->db->get();
        return $query->row_array();
    }

    function get_sms_reciever_data($vehicle_id) {
        $this->db->where('vehicle_id', $vehicle_id)
                ->select('phone_no,email,u.user_id,u.account_id')
                ->from('vehicles v')
                ->join('users u', 'u.user_id = v.add_uid');

        $query = $this->db->get();
        return $query->row_array();
    }

    function get_geofence_reciever_data($vehicle_id, $geofence_id) {
        $this->db->where('vehicle_id', $vehicle_id)
                ->where('geofence_id', $geofence_id)
                ->select('phone_no,email,u.user_id,u.account_id')
                ->from('vehicle_geofence_assignment vga')
                ->join('users u', 'u.user_id = vga.assign_uid');

        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * To check if a vehicle's email alert preferences are active
     * @param String $vehicle_id
     * @param String $alert_type_id
     * @return String boolean
     */
    function has_email_alert($vehicle_id, $alert_type_id) {
        $this->db->where('vehicle_id', $vehicle_id)
                ->where('alert_type_id', $alert_type_id)
                ->where('email_alert', 1)
                ->from('vehicle_alert_types vat')
        //->join('users u', 'u.user_id = vat.user_id')
        ;

        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }

    function geofence_has_alerts($vehicle_id, $geofence_id, $type) {
        $this->db->select('email_alert,sms_alert,in_alert,out_alert,g.type')
                ->from('vehicle_geofence_assignment vga')
                ->join('geofence g', 'g.id = vga.geofence_id')
                ->where('vga.geofence_id', $geofence_id)
                ->where('vga.vehicle_id', $vehicle_id)
                ->where('g.type', $type)
                ->where('vga.status', 1)
                ->limit(1);

        $query = $this->db->get();
        $data = $query->row_array();
        return (count($data) > 0) ? $data : NULL;
    }

    /**
     * To check if a vehicle's SMS alert preferences are active
     * @param String $vehicle_id
     * @param String $alert_type_id
     * @return String boolean
     */
    function has_sms_alert($vehicle_id, $alert_type_id) {
        $this->db->where('vehicle_id', $vehicle_id)
                ->where('alert_type_id', $alert_type_id)
                ->where('sms_alert', 1)
                ->from('vehicle_alert_types vat')
        //->join('users u', 'u.user_id = vat.user_id')
        ;

        $query = $this->db->get();

        return ($query->num_rows() > 0) ? 1 : 0;
    }

    function get_alert_name($alert_type_id) {
        $this->db->where('alert_type_id', $alert_type_id)
                ->select('name')
                ->from('alert_types');

        $query = $this->db->get();
        return $query->row_array()['name'];
    }

}
