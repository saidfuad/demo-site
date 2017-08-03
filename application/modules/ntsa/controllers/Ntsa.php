<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ntsa extends CI_Controller {

    function __construct() {

        parent::__construct();


        if ($this->session->userdata('hawk_user_type_id') != $this->config->item("ntsa_user_type")) {
            redirect('login ');
        }

        $this->load->model('mdl_ntsa_dashboard');
        $this->load->model('mdl_ntsa');
        $this->load->model('mdl_gps_tracking');
        $this->load->model('mdl_gps_history');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->model('listener_api/mdl_trip_master');
        $this->load->library('gps_utilities');
    }

    function getaddress($lat, $lng) {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=AIzaSyAzFof8b1BJz1t8K_rLafSS_Hah0Y4y1AA';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $contents = curl_exec($ch);

        if (curl_errno($ch)) {

            echo curl_error($ch);
            // echo "\n<br />";
            $contents = '';
        } else {
            curl_close($ch);
        }

        if (!is_string($contents) || !strlen($contents)) {

            echo "Failed to get contents.";
            return $contents = '';
        }

        $obj = json_decode($contents, true);

        if($obj["results"] != null){
            return $obj["results"][0]["formatted_address"];
        }else{
            return null;
        }
    }

    public function index() {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';

        $coords = array();
        $data = array();
        $vehicleNames = array();
        $vehicleList = '';

        $vehicles = $this->mdl_vehicles->get_sacco_vehicles($sacco_id);
        $saccos = $this->mdl_ntsa->get_saccos();

        if (count($vehicles)) {
            foreach ($vehicles as $vehicle) {
                $txt = "";
                $txt = addslashes($vehicle->plate_no);
                if ($vehicle->model != "")
                    $txt .= "(" . addslashes($vehicle->model) . ")";
                $vehicleNames[] = $txt;
                $vehicleList .= "<li vehicle-id='" . $vehicle->vehicle_id . "'><span class='fa fa-car'></span>&nbsp;" . $vehicle->plate_no . "</li>";
            }
        }else {
            $vehicleList .= "<li><span class='fa fa-car'></span>&nbsp;<a href='../index.php/vehicles/add_vehicle'>Add Vehicles</a></li>";
        }

        $data['moving_vehicles'] = $this->mdl_gps_tracking->count_moving_vehicles();
        $data['idle_vehicles'] = $this->mdl_gps_tracking->count_idle_vehicles();
        $data['parked_vehicles'] = $this->mdl_gps_tracking->count_parked_vehicles();
        $data['alert_vehicles'] = $this->mdl_gps_tracking->count_alert_vehicles();
        $data['disabled_vehicles'] = $this->mdl_gps_tracking->count_disabled_vehicles();

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;
        $data['vehicleList'] = $vehicleList;
        $data['vehicles'] = $vehicles;
        $data['vehicleNames'] = $vehicleNames;
        $data['saccos'] = $saccos;

        $data['content_url'] = 'ntsa';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | GPS Tracking';
        $data['content_title'] = 'GPS Tracking';
        $data['content_subtitle'] = 'Vehicle Location Tracking';
        $data['content'] = 'ntsa/gps_tracking/gps_home.php';

        $this->load->view('ntsa/main.php', $data);
    }

    public function refresh_grid() {

        $query = $this->input->post('query');

        $vehicles = $this->mdl_vehicles->get_sacco_vehicles();
        //print_r($vehicles);
      //  exit;

        for ($i = 0; $i < sizeof($vehicles); $i++) {
            $vehicles[$i]->address = $this->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
        }

        $res = array('vehicles' => $vehicles);

        echo json_encode($vehicles);
    }

    public function filter_grid() {
        $sacco_id = $this->input->post("sacco_id");
        $vehicles = $this->mdl_vehicles->get_sacco_vehicles($sacco_id);
        $res = array('vehicles' => $vehicles);
        echo json_encode($res);
    }

    public function vehicles(){

        $vehicles = $this->mdl_vehicles->get_sacco_vehicles(null);

        for ($i = 0; $i < sizeof($vehicles); $i++) {

            if ($vehicles[$i]->latitude != NULL) {

                $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
            } else {
                $vehicles[$i]->address = $this->gps_utilities->getaddress(null, null);
            }
        }

        $data['vehicles'] = $vehicles;

        $data['content_url'] = 'ntsa/vehicles';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Vehicles';
        $data['content_title'] = 'Vehicles';
        $data['content_subtitle'] = 'View Vehicles';
        $data['content'] = 'ntsa/vehicles/view_vehicles.php';

        $this->load->view('ntsa/main.php', $data);
    }

    public function fetch_vehicle($vehicle_id) {

        $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
        $map_lat = '-4.0434771';
        $map_long = '39.6682065';
    
        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;

        $user_id = $this->session->userdata('hawk_user_id');
        $vehicle = $this->mdl_vehicles->get_vehicle($vehicle_id);
        $vehicle[0]->address = $this->gps_utilities->getaddress($vehicle[0]->latitude, $vehicle[0]->longitude);

        $data['vehicle'] = $vehicle;

        $data['content_btn'] = '<a href="' . site_url('vehicles/add_vehicle') . '" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Vehicles</a>';

        $data['content_url'] = 'vehicles/vehicles';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | Vehicle Details';
        $data['content_title'] = 'Vehicle Details';
        $data['content_subtitle'] = 'View Vehicle Details';
        $data['content'] = 'vehicles/view_vehicle.php';
        $this->load->view('ntsa/main.php', $data);
    }

    public function view_history($id) {

        $data['history'] = $this->mdl_trip_master->get_history($id);

        $data['content_url'] = 'ntsa/gps_history';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Track History';
        $data['content_title'] = 'Track History';
        $data['content_subtitle'] = 'Vehicle History';
        $data['content'] = 'ntsa/gps_history/view_history.php';

        $this->load->view('ntsa/main.php', $data);
    }

    public function view_playback($id) {

        $vehicle_id = $id;
        $map_center = sprintf( "%f, %f", '-4.0434771', '39.6682065');
        $map_lat = '-4.0434771';
        $map_long = '39.6682065';

        $vehicle = json_encode($this->mdl_gps_history->get_vehicle($id));
        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;
        $data['content_url'] = 'gps_history';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Track History';
        $data['content_title'] = 'Track History';
        $data['content_subtitle'] = 'Vehicle History';
        $data['content'] = 'gps_history/view_playback.php';

        $data['vehicle'] = $vehicle;
        $data['vehicle_id'] = $vehicle_id;

        $this->load->view('ntsa/main.php', $data);

    }

    public function history($id,$start_date,$stop_date) {

        $vehicle_id = $id;

        $arr = $this->vehicle_history_date($id,$start_date,$stop_date);

        $coords = json_encode($arr);
        $vehicle = json_encode($this->mdl_gps_history->get_vehicle($id));

        $map_center = sprintf( "%f, %f", '-4.0434771', '39.6682065');
        $map_lat = $arr[10]->latitude;
        $map_long = $arr[10]->longitude;

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;

        $data['content_url'] = 'gps_history';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Track History';
        $data['content_title'] = 'Track History';
        $data['content_subtitle'] = 'Vehicle History';
        $data['content'] = 'gps_history/index.php';

        $data['coords'] = $coords;
        $data['vehicle'] = $vehicle;
        $data['vehicle_id'] = $vehicle_id;

        $this->load->view('ntsa/main.php', $data);
    }

    public function vehicle_history($vehicle_id){

        $result = $this->mdl_gps_history->get_vehicle_history($vehicle_id);
        $result = $this->get_distinct_routes($result);
        //print_r($result);
        return $result;
    }


    public function vehicle_history_date($id,$start_date,$stop_date){

        $result = $this->mdl_gps_history->get_vehicle_history($id, str_replace("%20", " ",$start_date), str_replace("%20", " ",$stop_date));
        return $result;

    }

    public function vehicle_playback(){
        $data = $this->input->post();
        $vehicle_id =  $data['vehicle_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $result = $this->mdl_gps_history->get_vehicle_history($vehicle_id,  $start_date,  $end_date);
        $result = array("history"=>$result);
        echo json_encode($result);
    }

    private function get_distinct_routes($pathCoords){
        $result = array();

        if(count($pathCoords) > 1){
            array_push($result,$pathCoords[0]);
            $latitude = $pathCoords[0]->latitude;
            $longitude = $pathCoords[0]->longitude;
            for ($i = 1; $i < count($pathCoords); $i++) {
                if($latitude != $pathCoords[$i]->latitude && $longitude !=$pathCoords[$i]->longitude ){
                    array_push($result,$pathCoords[$i]);
                    $latitude = $pathCoords[$i]->latitude;
                    $longitude = $pathCoords[$i]->longitude;
                }
            }
        }
        return $result;
    }

    public function history_track_points(){

        $data = $this->input->post();

        $data['account_id'] = $this->session->userdata('hawk_account_id');

        $history = $this->mdl_gps_history->fetch_vehicle_history($data);

        echo json_encode($history);
    }

}
