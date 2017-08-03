<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Gps_tracking extends Base_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('mdl_gps_tracking');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->model('devices/mdl_devices');
        $this->load->model('alerts/mdl_alerts');
        $this->load->model('vehicle_geofence/mdl_vehicle_geofence');
        $this->load->library('cart');
        $this->load->library('emailsend');
        $this->load->library('gps_utilities');
    }

    function send_email() {
        $to = array('makaweys@gmail.com');
        $subj = 'test';
        $message = "ITMS Registration Successful. \n\n
                                Username : email \nPassword : pass";

        $this->emailsend->send_email_message($to, $subj, $message);
    }

    public function index() {

        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $coords = array();
        $data = array();
        $vehicleNames = array();
        $vehicleList = '';

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles($this->session->userdata('hawk_account_id'));

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

        $data['moving_vehicles'] = $this->mdl_gps_tracking->count_moving_vehicles($this->session->userdata('hawk_account_id'));
        $data['idle_vehicles'] = $this->mdl_gps_tracking->count_idle_vehicles($this->session->userdata('hawk_account_id'));
        $data['parked_vehicles'] = $this->mdl_gps_tracking->count_parked_vehicles($this->session->userdata('hawk_account_id'));
        $data['alert_vehicles'] = $this->mdl_gps_tracking->count_alert_vehicles($this->session->userdata('hawk_account_id'));
        $data['disabled_vehicles'] = $this->mdl_gps_tracking->count_disabled_vehicles($this->session->userdata('hawk_account_id'));

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;
        $data['vehicleList'] = $vehicleList;
        $data['vehicles'] = $vehicles;
        $data['vehicleNames'] = $vehicleNames;

        /* echo "<pre>";
          print_r($data);
          exit; */

        $data['content_url'] = 'gps_tracking';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | GPS Tracking';
        $data['content_title'] = 'GPS Tracking';
        $data['content_subtitle'] = 'Vehicle Location Tracking';
        $data['content'] = 'gps_tracking/gps_home2.php';

        $this->load->view('main/main.php', $data);
    }

    public function refresh_grid() {

        $query = $this->input->post('query');

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles($this->session->userdata('hawk_account_id'), $query);

        $data['moving_vehicles'] = $this->mdl_gps_tracking->count_moving_vehicles($this->session->userdata('hawk_account_id'));
        $data['idle_vehicles'] = $this->mdl_gps_tracking->count_idle_vehicles($this->session->userdata('hawk_account_id'));
        $data['parked_vehicles'] = $this->mdl_gps_tracking->count_parked_vehicles($this->session->userdata('hawk_account_id'));
        $data['alert_vehicles'] = $this->mdl_gps_tracking->count_alert_vehicles($this->session->userdata('hawk_account_id'));
        $data['disabled_vehicles'] = $this->mdl_gps_tracking->count_disabled_vehicles($this->session->userdata('hawk_account_id'));

        for ($i = 0; $i < sizeof($vehicles); $i++) {
            $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
        }

        $res = array('vehicles' => $vehicles);

        echo json_encode($vehicles);
    }

    public function filter_grid() {

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles($this->session->userdata('hawk_account_id'));

        $res = array('vehicles' => $vehicles);

        echo json_encode($res);
    }

    public function history() {

        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $coords = array();
        $data = array();
        $vehicleNames = array();
        $vehicleList = '';

        $vehicles = $this->mdl_gps_tracking->get_gps_vehicles($this->session->userdata('hawk_account_id'));

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

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;
        $data['vehicleList'] = $vehicleList;
        $data['vehicleNames'] = $vehicleNames;

        $data['content_url'] = 'gps_tracking';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Track History';
        $data['content_title'] = 'Track History';
        $data['content_subtitle'] = 'Vehicle History';
        // $data['content'] = 'gps_tracking/history.php';
        $data['content'] = 'gps_tracking/history2.php';

        $this->load->view('main/main.php', $data);
    }

    public function vehicle_history() {
        $result = $this->mdl_gps_tracking->get_vehicle_history("13");
        $result = array("history" => $result);
        echo json_encode($result);
    }

    public function history_track_points() {

        $data = $this->input->post();

        $data['account_id'] = $this->session->userdata('hawk_account_id');

        $history = $this->mdl_gps_tracking->fetch_vehicle_history($data);

        echo json_encode($history);
    }

    public function zones() {

        $data['zones'] = $this->mdl_gps_tracking->get_zones($this->session->userdata('hawk_account_id'));

        $data['content_url'] = 'gps_tracking/zones';
        $data['fa'] = 'fa fa-location-arrow';
        $data['title'] = 'HAWK | Zones';
        $data['content_title'] = 'Zones';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/zones.php';
        $this->load->view('main/main.php', $data);
    }

    public function routes() {
        $data['routes'] = $this->mdl_gps_tracking->get_routes($this->session->userdata('hawk_account_id'));

        $data['content_url'] = 'gps_tracking/routes';
        $data['fa'] = 'fa fa-road';
        $data['title'] = 'HAWK | Routes';
        $data['content_title'] = 'Routes';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/routes.php';
        $this->load->view('main/main.php', $data);
    }

    public function edit_route($route_id) {
        $data['routes'] = $this->mdl_gps_tracking->get_route_by_id($route_id);

        $data['content_url'] = 'gps_tracking/edit_route';
        $data['fa'] = 'fa fa-road';
        $data['title'] = 'HAWK | Edit Route';
        $data['content_title'] = 'Edit Route';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/edit_route.php';
        $this->load->view('main/main.php', $data);
    }

    public function getroute() {
        $keyword = $this->input->post('keyword');
        $data = $this->mdl_gps_tracking->getroute($keyword);
        echo json_encode($data);
    }

    public function routes1() {
        $word = $this->input->get('route_id');

        // die();
        //$sql = $this->db->query('SELECT start_address FROM itms_routes WHERE route_name = "'.$word.'"');
        $this->db->select('start_address,end_address')
                ->from('itms_routes')
                ->where('route_name', $word)
                ->where('company_id', $this->session->userdata('hawk_account_id'));
        $query = $this->db->get();
        echo json_encode($query->result_array());
    }

    public function trips() {

        $data['content_btn'] = '<a href="' . site_url('gps_tracking/create_trip') . '" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Create Trip</a>';

        $vehicle_id = null;

        if (isset($_REQUEST['vehicle'])) {
            $vehicle_id = $_REQUEST['vehicle'];
        }

        $data['trips'] = $this->mdl_gps_tracking->get_trips($vehicle_id, $this->session->userdata('hawk_account_id'));

        $data['content_url'] = 'gps_tracking/trips';
        $data['fa'] = 'fa fa-paper-plane';
        $data['title'] = 'HAWK | Trips';
        $data['content_title'] = 'Trips';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/trips.php';
        $this->load->view('main/main.php', $data);
    }

    public function geo_data() {

        $data['landmarks'] = $this->mdl_gps_tracking->get_landmarks($this->session->userdata('hawk_account_id'));
        $data['geofences'] = $this->mdl_gps_tracking->get_geofences($this->session->userdata('hawk_account_id'));
        $data['routes'] = $this->mdl_gps_tracking->get_routes($this->session->userdata('hawk_account_id'));
        $data['vehicle_geofence_assignment'] = $this->mdl_vehicle_geofence->get_all_vehicle_geofence($this->input->get('vehicle_id'));
        //$this->mdl_userprofile->get_user_menu_permissions();
        $data['content_url'] = 'gps_tracking/geo_data';
        $data['fa'] = 'fa fa-university';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Geo Data';
        $data['content_title'] = 'Geo Data';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/geo_data.php';
        $this->load->view('main/main.php', $data);
    }

    public function gps_devices_integration() {
        $deviceOpt = '';
        $deviceList = '';
        $vehicleOpt = '';
        $vehicleList = '';

        $devices = $this->mdl_gps_tracking->get_device($this->session->userdata('hawk_account_id'), $role_id = 2, $user_id = null);
        foreach ($devices as $device) {
            $deviceOpt .= "<option value='" . $device->id . "'>" . $device->device_id . "</option>";
            // $deviceList .= "<li data-id='".$device->id."'><a href=''>".addslashes($device->device_id)."</a></li>";
        }

        $vehicles = $this->mdl_gps_tracking->get_vehicle($this->session->userdata('hawk_account_id'), $role_id = 2, $user_id = null);
        foreach ($vehicles as $vehicle) {
            $vehicleOpt .= "<option value='" . $vehicle->vehicle_id . "'>" . $vehicle->plate_no . "</option>";
            // $vehicleList .= "<li data-id='".$vehicle->vehicle_id."'><a href=''>".addslashes($vehicle->plate_no)."</a></li>";
        }

        //$this->mdl_userprofile->get_user_menu_permissions();
        $data['deviceOpt'] = $deviceOpt;
        $data['vehicleOpt'] = $vehicleOpt;
        $data['content_url'] = 'gps_tracking/gps_devices_integration';
        $data['fa'] = 'fa fa-university';
        $data['title'] = 'HAWK | GPS Devices Integration';
        $data['content_title'] = 'GPS Devices Integration';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/gps_devices_integration.php';
        $this->load->view('main/main.php', $data);
    }

    public function fetch_landmark() {
        $data['vehicle'] = $this->mdl_gps_tracking->get_landmark($this->session->userdata('hawk_account_id'));

        $data['content_btn'] = '<a href="' . site_url('settings/create_landmarks') . '" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Vehicles</a>';
        $data['content_url'] = 'settings/create_landmarks';
        $data['fa'] = 'fa fa-car';
        $data['title'] = 'HAWK | Landmark';
        $data['content_title'] = 'Landmark';
        $data['content_subtitle'] = 'Landmark Details';
        $data['content'] = 'settings/view_landmark.php';
        $this->load->view('main/main.php', $data);
    }

    public function edit_landmark($landmark_id) {
        $this->load->library('googlemaps');
        $this->load->model('settings/mdl_landmarks');

        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
        } else {
            $map_center = sprintf("%f, %f", ' -4.0434771', '39.6682065');
        }

        $config['center'] = $map_center;
        $config['zoom'] = '12';
        $config['map_width'] = '100%';
        $config['map_height'] = '600';
        $config['trafficOverlay'] = TRUE;
        $config['onclick'] = '  countM++;
                                clearMarkers();
                                clearCircles();

                                if ($("#landmark-radius").val().trim().length==0 || !$.isNumeric(parseFloat($("#landmark-radius").val().trim()))) {
                                    $("#landmark-radius").val(1)
                                } 

                                newLatLng = event.latLng;
                                fillcolor = $("#full-popover").val();
                                range = parseFloat($("#landmark-radius").val());

                                addCircle(newLatLng);
                                addMarker(newLatLng);

                                $("#input-latitude").val(event.latLng.lat());
                                $("#input-longitude").val(event.latLng.lng());

                                $(".page-alert").children("p").html("You have <strong>selected</strong> the position on the <strong>marker</strong>");
                                
                                ';

        $this->googlemaps->initialize($config);

        $marker = array();
        $marker['position'] = $this->session->userdata('company_latitude') . ',' . $this->session->userdata('company_longitude');
        $this->googlemaps->add_marker($marker);

        $deviceGrp = "";

        $rows = $this->mdl_landmarks->get_landmarks($this->session->userdata('hawk_account_id'));
        $coords = array();

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $row->name = str_replace(array("\n", '\n\r'), " ", addslashes($row->landmark_name));
                $row->address = str_replace(array("\n", '\n\r'), " ", addslashes($row->address));
                $row->comments = str_replace(array("\n", '\n\r'), " ", addslashes($row->comments));
                $coords[] = sprintf("%f, %f", $row->latitude, $row->longitude);
            }
        }
        $data['coords'] = $coords;

        $rows = $this->mdl_landmarks->getIconPaths();
        $images = '';

        foreach ($rows as $row) {
            $images .= '<li title="' . base_url() . '/' . $row->image_path . '" value="' . $row->image_path . '">
                            <img src="' . base_url() . '/' . $row->image_path . '" alt="landmark image" />
                        </li>';
        }

        $rows = $this->mdl_landmarks->getAllCoord($this->session->userdata('hawk_account_id'));

        $opt_latlng = "";
        if (count($rows) > 0) {
            $i = 0;
            foreach ($rows as $row) {
                if ($row['latitude'] != '' || $row['latitude'] != null) {
                    $opt_latlng .= "<option value='" . $row['latitude'] . "," . $row['longitude'] . "'>" . addslashes($row['plate_no']) . "</option>";
                } else {
                    $opt_latlng .= "<option value='0,0'>" . addslashes($row['plate_no']) . "</option>";
                }
            }
        }

        $landmarks = $this->mdl_landmarks->get_landmarks($this->session->userdata('hawk_account_id'));


        if (count($landmarks) > 0) {
            $marker = array();
            $circle = array();

            $i = 0;
            foreach ($landmarks as $row) {
                $position = sprintf("%f, %f", $row->latitude, $row->longitude);
                $marker['position'] = $position;
                $this->googlemaps->add_marker($marker);

                $circle['center'] = $position;
                $circle['radius'] = $row->radius;
                $circle['fillColor'] = $row->landmark_circle_color;
                $circle['fillOpacity'] = 0.35;
                $this->googlemaps->add_circle($circle);
            }
        }

        $data['map'] = $this->googlemaps->create_map();
        $data['live_combo'] = $opt_latlng;

        $data['landmark_images'] = $images;

        $data['fetch_landmark'] = $this->mdl_gps_tracking->get_landmark_by_id($landmark_id);

        $data['content_url'] = 'landmarks';
        $data['fa'] = 'fa fa-pencil';
        $data['title'] = 'HAWK | Edit landmark';
        $data['content_title'] = 'Edit Landmark';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/edit_landmark.php';
        $this->load->view('main/main.php', $data);
    }

    public function edit_zone($zone_id) {

        $data['fetch_zone'] = $this->mdl_gps_tracking->get_zone_by_id($zone_id);

        $data['content_url'] = 'landmarks';
        $data['fa'] = 'fa fa-pencil';
        $data['title'] = 'HAWK | Edit Zone';
        $data['content_title'] = 'Edit Zone';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/edit_zone.php';
        $this->load->view('main/main.php', $data);
    }

    public function edit_geo_data($geofence_id) {

        $data['geo_data'] = $this->mdl_gps_tracking->get_geo_data_by_id($geofence_id);

        $data['content_url'] = 'gps_tracking/edit_geo_data';
        $data['fa'] = 'fa fa-pencil';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit Geo Data';
        $data['content_title'] = 'Edit Geo Data';
        $data['content_subtitle'] = '';
        $data['content'] = 'gps_tracking/edit_geo_data.php';
        $this->load->view('main/main.php', $data);
    }

    function edit_save_geo_data($geofence_id) {
        $data = $this->input->post();
        echo $this->mdl_gps_tracking->edit_save_geo_data($geofence_id, $data);
    }

}

?>