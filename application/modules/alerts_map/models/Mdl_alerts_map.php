<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mdl_alerts_map extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_geo_data($geofence_id) {
        $this->db->select('gp.latitude,gp.longitude')
                ->from('geofence_points gp')
                ->where('gp.geofence_id', $geofence_id);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_vehicle_data($alert_id) {
        $this->db->select('type,g.name,plate_no,start_lat,start_lng,start_address,start_date,distance,fill_color')
                ->from('alerts a')
                ->join('vehicles v', 'a.vehicle_id = v.vehicle_id')
                ->join('geofence g','g.id = a.geofence_id')
                ->where('alert_id', $alert_id);

        $query = $this->db->get();
        return $query->row_array();
    }

}
