<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alerts_map
 *
 * @author Benson
 */
class Alerts_map extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library('encrypt');
        $this->load->model('mdl_alerts_map');
    }

    public function view_geo_alerts($geofence_id, $alert_id) {
        $data['geo_data'] = $this->mdl_alerts_map->get_geo_data($geofence_id);
        $data['vehicle_details'] = $this->mdl_alerts_map->get_vehicle_data($alert_id);
        $data['title'] = "Hawk | Alerts";
        
        $this->load->view('alerts_map/view_geo_alerts.php', $data);
    }

}
