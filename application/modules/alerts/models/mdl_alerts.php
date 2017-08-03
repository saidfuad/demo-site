<?php

class Mdl_alerts extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function fetch_alerts($account_id, $alert_type_id, $alert_id) {

        $this->db->from('alerts');
        $this->db->join('vehicles', 'vehicles.vehicle_id = alerts.vehicle_id', 'right');
        $this->db->join('alert_types', 'alert_types.alert_type_id = alerts.alert_type_id', 'right');

        if ($alert_type_id != null && ($alert_type_id != LANDMARK_ALERT || $alert_type_id != GEOFENCE_ALERT || $alert_type_id != ROUTE_ALERT)) {

            $this->db->select('alerts.*, vehicles.plate_no, vehicles.model, alert_types.name');
            $this->db->where('alerts.alert_type_id', $alert_type_id);
        }

        if ($alert_type_id == LANDMARK_ALERT || $alert_type_id == GEOFENCE_ALERT || $alert_type_id == ROUTE_ALERT) {

            $this->db->select('alerts.*, vehicles.plate_no, vehicles.model, alert_types.name, geofence.type');
            $this->db->where('alerts.alert_type_id', $alert_type_id);
            $this->db->join('geofence', 'geofence.id = alerts.geofence_id', 'left');
        }

        if ($alert_id != null) {
            $this->db->where('alerts.alert_id', $alert_id);
        }

        $this->db->where('alerts.account_id', $account_id);

        $this->db->order_by('viewed', 'asc');
        $this->db->order_by('start_date', 'asc');

        $query = $this->db->get();

        return $query->result();
    }

    public function fetch_counts($account_id, $alert_type_id, $viewed) {

        $this->db->select('alerts.*');
        $this->db->from('alerts');
        $this->db->where('alerts.viewed', $viewed);

        if ($account_id != null) {
            $this->db->where('alerts.account_id', $account_id);
        }

        if ($alert_type_id != null) {
            $this->db->where('alerts.alert_type_id', $alert_type_id);
        }

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function read_alert($data) {

        $sql = "UPDATE alerts SET viewed = 1 WHERE alert_id ='" . $data['alert_id'] . "'";
        $this->db->query($sql);
    }

    /**
     * Adds Alerts from listener
     * @param type $vehicle_id
     * @param type $alert_type_id
     * @param type $lat
     * @param type $lng
     * @param type $address
     * @param type $geofence_id
     * @param type $distance
     * @param type $speed
     * @return type
     */
    function add_alert($vehicle_id, $alert_type_id, $lat, $lng, $address, $geofence_id = NULL, $distance = NULL, $speed = NULL) {
        $account_id = $this->mdl_vehicles->get_account_id_by_vehicle_id($vehicle_id);

        $data = array(
            "geofence_id" => $geofence_id,
            "distance" => $distance,
            "speed" => $speed,
            "account_id" => $account_id,
            "vehicle_id" => $vehicle_id,
            "alert_type_id" => $alert_type_id,
            "start_lat" => $lat,
            "start_lng" => $lng,
            "start_address" => $address);

        $alert_id = NULL;

        $this->db->trans_start();

        $response = $this->db->insert("alerts", $data);
        $alert_id = $this->db->insert_id();

        $this->db->query("update vehicles set alert_status = 1 where vehicle_id = '$vehicle_id'");


        echo ($response) ? "ALERT ADDED TO TABLE " . $alert_type_id.PHP_EOL : "ALERT CREATION ERROR".PHP_EOL;

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
           return $alert_id; 
        }else{
            return NULL;
        }
        
    }

    /**
     * Check for active alerts for vehicles
     * @param type $vehicle_id
     * @param type $alert_type_id
     * @return type
     */
    function check_for_active_alert($vehicle_id, $alert_type_id) {

        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->where('status', 1);
        $this->db->where('alert_type_id', $alert_type_id);
        $this->db->from('alerts');
        return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
    }

    /**
     * Stop an active alert
     * @param type $vehicle_id
     * @param type $alert_type_id
     * @param type $lat
     * @param type $lng
     * @param type $address
     */
    function stop_alert($vehicle_id, $alert_type_id, $lat, $lng, $address) {
        $response = $this->db->query("UPDATE alerts SET stop_date= NOW(), "
                . "stop_lat = '$lat',"
                . "stop_lng='$lng', "
                . "stop_address = '" . $address . "',"
                . "status = '0' "
                . "WHERE vehicle_id='$vehicle_id' "
                . "AND alert_type_id='" . $alert_type_id . "' "
                . "AND status = '1'");

        echo ($response) ? "ALERT STOPPED " . $alert_type_id . PHP_EOL : "ALERT STOPPING ERROR" . PHP_EOL;
    }
    
    

    /**
     * Updates geofence alert
     * @param type $vehicle_id
     * @param type $lat
     * @param type $lng
     * @param type $geofence_id
     * @return type
     */
    function update_geofence_alert($vehicle_id, $lat, $lng, $geofence_id, $address) {
        $response = $this->db->query("UPDATE alerts SET stop_date= NOW(), "
                . "stop_lat = '$lat',"
                . "stop_lng='$lng', "
                . "stop_address = '" . $address . "', "
                . "status = '0' "
                . "WHERE geofence_id = '$geofence_id' "
                . "AND vehicle_id='$vehicle_id' "
                . "AND status = '1'");

        echo ($response) ? "UPDATED GEOFENCE ALERT" . PHP_EOL : "UPDATE GEOFENCE ALERT ERROR" . PHP_EOL;
        return $response;
    }

}

?>
