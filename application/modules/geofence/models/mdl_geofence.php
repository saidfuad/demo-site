<?php

class Mdl_geofence extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('gps_utilities');
    }

    /**
     * Checks if vehicle is in a route
     * Assumption: A vehicle can only be in one active route
     * @param type $vehicle_id
     * @param type $vehicle_lat
     * @param type $vehicle_lng
     * @param type $address
     * @return type
     */
    function is_in_route($vehicle_id, $vehicle_lat, $vehicle_lng, $address) {
        $this->db->select('g.id,g.name,latitude,longitude,g.type')
                ->from('geofence_points gp')
                ->join('vehicle_geofence_assignment vga', 'vga.geofence_id = gp.geofence_id')
                ->join('geofence g', 'g.id = vga.geofence_id')
                ->where('vga.vehicle_id', $vehicle_id)
                ->where('g.type', 'route')
                ->where('vga.status', 1);

        $query = $this->db->get();
        $route_data = $query->result_array();

        if (count($route_data) > 0) {
            $distance_array = array();
            echo "CALCULATE DISTANCE FOR ROUTE" . PHP_EOL;
            //$check = $this->calculate_distance($route_data[0]['latitude'], $route_data[0]['longitude'], $vehicle_lat, $vehicle_lng);
            for ($i = 0; $i < count($route_data); $i++) {
                $distance = $this->gps_utilities->calculate_distance($route_data[$i]['latitude'], $route_data[$i]['longitude'], $vehicle_lat, $vehicle_lng);

                array_push($distance_array, abs($distance));
            }

            $minimum_distance = min($distance_array);
                        
            $active = $this->mdl_alerts->check_for_active_alert($vehicle_id, ROUTE_ALERT);
            if ($minimum_distance > 100) {
                /* check for active alerts and add alert */
                echo "ROUTE INFRINGEMENT ALERT ON " . $route_data[0]['name'] . ", DISTANCE:" . $minimum_distance . PHP_EOL;

                $minimum_distance = ceil($minimum_distance);
                $distance = ($minimum_distance) < 1000 ? $minimum_distance : ($minimum_distance / 1000);
                $unit = ($minimum_distance) < 1000 ? " m" : " km";

                if ($active == FALSE) {
                    $alert_id = $this->mdl_alerts->add_alert($vehicle_id, ROUTE_ALERT, $vehicle_lat, $vehicle_lng, $address, $route_data[0]['id'], $minimum_distance);

                    return array('geofence_id' => $route_data[0]['id'],
                        'alert_id' => $alert_id,
                        'type' => $route_data[0]['type'],
                        'name' => $route_data[0]['name'],
                        'distance' => $distance,
                        'unit' => $unit);
                }
            } else {
                /* check close active route alert, if any. */

                echo "IN ROUTE" . PHP_EOL;
                if ($active) {
                    $this->mdl_alerts->update_geofence_alert($vehicle_id, $vehicle_lat, $vehicle_lng, $route_data[0]['id'], $address);
                }
            }
        } else {
            echo "NO ROUTES ATTACHED TO VEHICLE" . PHP_EOL;
        }
    }

    /**
     * Checks if vehicle is is landmark
     * Assumption: A vehicle can be attached to multiple landmarks, 
     * but can only be in one landmark physically. 
     * @param type $vehicle_id
     * @param type $lat
     * @param type $lng
     */
    function is_in_landmark($vehicle_id, $lat, $lng, $address) {
        $query = "SELECT g.id,g.radius,in_alert,out_alert,sms_alert,email_alert, name,plate_no,g.type, 
		    ( 6371 * acos( cos( radians($lat) ) * cos( radians( gp.latitude ) ) 
		    * cos( radians( gp.longitude ) - radians($lng) ) + sin( radians($lat) ) 
		    * sin( radians( gp.latitude ) ) ) ) AS distance 
                    FROM geofence g"
                . " LEFT JOIN geofence_points gp ON gp.geofence_id = g.id"
                . " LEFT JOIN vehicle_geofence_assignment vga ON vga.geofence_id = g.id"
                . " LEFT JOIN vehicles v ON vga.vehicle_id = v.vehicle_id"
                . " WHERE vga.vehicle_id = $vehicle_id"
                . " AND g.type ='landmark'"
                . " LIMIT 1";
        $res = $this->db->query($query);
        $data = $res->row_array();

        $is_active = $this->mdl_alerts->check_for_active_alert($vehicle_id, LANDMARK_ALERT);

        if ($res->num_rows() > 0) {

            if (abs($data["distance"]) <= ($data["radius"] * 1000)) {
                $distance = ceil($data["distance"]);
                echo "WITHIN RANGE(" . $data["radius"] . ") " . $distance . " m" . PHP_EOL;


                if ($is_active == FALSE) {

                    echo "IN LANDMARK" . PHP_EOL;

                    $alert_id = $this->mdl_alerts->add_alert($vehicle_id, LANDMARK_ALERT, $lat, $lng, $address, $data["id"], $distance);

                    $unit = ($distance) < 1000 ? " m" : " km";

                    return array('geofence_id' => $data['id'], 'alert_id' => $alert_id, 'type' => $data['type'], 'name' => $data['name'], 'distance' => $distance, 'unit' => $unit);
                } else {
                    
                }
            } else {
                echo "NOT IN RANGE, DISTANCE: " . $data["distance"] . PHP_EOL;
                if ($is_active) {
                    echo "CLOSING HANGING LANDMARKS" . PHP_EOL;
                    $this->mdl_alerts->update_geofence_alert($vehicle_id, $lat, $lng, $data["id"], $address);
                }
            }
        } else {
            echo "NO LANDMARKS ATTACHED TO VEHICLE" . PHP_EOL;
        }
    }
    /**
     * Get active geofence attached to a vehicle
     * Assumption: A vehicle can only be attached to one active geofence
     * @param type $vehicle_id
     * @return type
     */
    function get_vehicle_goefence($vehicle_id) {
        $this->db->select('count(*) as count,name,type,g.id,GROUP_CONCAT(gp.latitude) AS vertices_x,GROUP_CONCAT(gp.longitude) AS vertices_y')
                ->from('geofence_points gp')
                ->join('geofence g', 'gp.geofence_id = g.id')
                ->join('vehicle_geofence_assignment vga', 'vga.geofence_id = g.id')
                ->where('vga.vehicle_id', $vehicle_id)
                ->where('g.type', 'geofence')
                ->where('vga.status', 1);

        $query = $this->db->get();
        $data = $query->row_array();
        return ($data['count'] > 0) ? $data : NULL;
    }

}
