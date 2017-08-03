<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Base_Controller {

    function __construct() {

        parent::__construct();

//        if ($this->session->userdata('hawk_user_type_id') == "") {
//            redirect('login');
//        }
//
//        if ($this->session->userdata('hawk_user_type_id') == 1) {
//            redirect('admin');
//        }
//
//        if ($this->session->userdata('hawk_user_id') != "") {
//            redirect('home');
//        }

        $this->load->model('mdl_landmarks');
        $this->load->model('mdl_user_settings');
        $this->load->model('mdl_settings');
        $this->load->model('alerts/mdl_alerts');
        $this->load->model('mdl_zones');
        $this->load->model('mdl_routes');
        $this->load->model('gps_tracking/mdl_gps_tracking');
    }

    public function index() {
        $this->load->view('settings');
    }

    public function create_landmarks() {

        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $images = '';

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;

        $data['landmark_images'] = $images;
        $data['content_url'] = 'settings/create_landmarks';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Create Landmarks';
        $data['content_title'] = 'Create Landmarks';
        $data['content_subtitle'] = 'Define Custom Landmarks';
        $data['content'] = 'settings/create_landmarks.php';

        $this->load->view('main/main.php', $data);
    }

    public function get_company_landmarks() {
        $data = $this->mdl_landmarks->get_landmarks($this->session->userdata('itms_company_id'));

        echo json_encode($data);
    }

    public function save_landmark() {
        $data = $this->input->post();
        //$data['radius'] = $data['radius'] / 100;
        $data['status'] = 1;

        $data['account_id'] = $this->session->userdata('hawk_account_id');
        $data['add_uid'] = $this->session->userdata('hawk_user_id');

        echo $this->mdl_landmarks->save_landmark($data);
    }

    public function update_landmark() {
        $data = $this->input->post();
        $this->mdl_landmarks->update_landmark($data);
    }

    public function create_zones() {
        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;

        $data['content_url'] = 'settings/create_zones';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Create Geofence';
        $data['content_title'] = 'Create Geofence';
        $data['content_subtitle'] = 'Define Custom Geofence';
        $data['content'] = 'settings/create_zones.php';

        $this->load->view('main/main.php', $data);
    }

    public function update_zone() {
        $data = $this->input->post();
        $this->mdl_zones->update_zone($data);
    }

    public function get_company_zones() {
        $zones = $this->mdl_zones->get_zones($this->session->userdata('itms_company_id'));
        $vertices = $this->mdl_zones->get_vertices($this->session->userdata('itms_company_id'));

        $data['zones'] = $zones;
        $data['vertices'] = $vertices;

        echo json_encode($data);
    }

    public function save_geofence() {
        $data = $this->input->post();
        $data['account_id'] = $this->session->userdata('hawk_account_id');
        $data['add_uid'] = $this->session->userdata('hawk_user_id');
        $data['add_date'] = date('Y-m-d H:i:s');

        $vertices = $data['vertices'];

        $vertices = explode('),', $vertices);

        unset($data['vertices']);

        //echo json_encode($data);
        echo $this->mdl_zones->save_geofence($data, $vertices);
    }

    public function create_routes() {
        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;


        $data['content_url'] = 'settings/create_routes';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Create Routes';
        $data['content_title'] = 'Create Routes';
        $data['content_subtitle'] = 'Define Custom Routes';
        $data['content'] = 'settings/create_routes.php';

        $this->load->view('main/main.php', $data);
    }

    public function update_route() {
        $data = $this->input->post();
        $this->mdl_routes->update_route($data);
    }

    public function create_trips() {
        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
            $map_lat = $this->session->userdata('company_latitude');
            $map_long = $this->session->userdata('company_longitude');
        } else {
            $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        }

        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;

        $data['all_assets'] = $this->mdl_settings->getassets($this->session->userdata('itms_company_id'));
        $data['all_routes'] = $this->mdl_settings->getroutes($this->session->userdata('itms_company_id'));
        $data['all_clients'] = $this->mdl_settings->getclients($this->session->userdata('itms_company_id'));

        $data['content_url'] = 'settings/create_trips';
        $data['fa'] = 'fa fa-map-marker';
        $data['title'] = 'HAWK | Create Trips';
        $data['content_title'] = 'Create trips';
        $data['content_subtitle'] = 'Define custom trips';
        $data['content'] = 'settings/create_trips.php';

        $this->load->view('main/main.php', $data);
    }

    public function save_trip() {
        $data = $this->input->post();
        $data['company_id'] = $this->session->userdata('itms_company_id');
        $data['add_date'] = date('Y-m-d H:i:s');

        /* print_r('<pre>');
          print_r($data);
          exit; */

        echo $this->mdl_gps_tracking->save_trips($data);
    }

    public function save_route() {
        $posts = $this->input->post();

        $data = array();
        //$data['company_id'] = $this->session->userdata('itms_company_id');
        //$data['add_uid'] = $this->session->userdata('user_id');
        //$data['add_date'] = date('Y-m-d H:i:s');
        //str_replace("\\", "", $posts['data']);

        $json = json_decode($posts['data'], true);
        $start_latlng = json_decode($json['start_latlng'], true);
        $end_latlng = json_decode($json['end_latlng'], true);
        //print_r('<pre>');
        //print_r($json);


        $data['name'] = $posts['route_name'];
        $data['fill_color'] = $posts['fill_color'];
        $data['type'] = 'route';
        $data['status'] = 1;
        //$data['start_address'] = $json['start_address'];
        //$data['start_lat'] = $start_latlng['lat'];
        //$data['start_lng'] = $start_latlng['lng'];
        //$data['end_address'] = $json['end_address'];
        //$data['end_lat'] = $end_latlng['lat'];
        //$data['end_lng'] = $end_latlng['lng'];
        //$data['distance'] = $json['distance'];
        //$data['distance_value'] = $json['distance_value'];
        //$data['duration'] = $json['duration'];
        //$data['duration_value'] = $json['duration_value'];
        //$data['raw_route'] = $posts['raw_route'];
        //$data['route_path'] = json_encode($json['route_path']);
        //$data['company_id'] = $this->session->userdata('itms_company_id');
        $data['add_uid'] = $this->session->userdata('hawk_user_id');
        $data['account_id'] = $this->session->userdata('hawk_account_id');
        //$data['add_date'] = date('Y-m-d H:i:s');
        $this->db->trans_start();

        $route_id = $this->mdl_routes->save_route($data);

        $routepoints = array();

        foreach ($json['route_path'] as $k => $routepoint) {
            $wp = array();
            $latlng = json_decode($routepoint, true);
            $wp['geofence_id'] = $route_id;
            $wp['latitude'] = $latlng['lat'];
            $wp['longitude'] = $latlng['lng'];

            array_push($routepoints, $wp);
        }
        //batch insert
        $this->db->insert_batch('geofence_points', $routepoints);

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            echo TRUE;
            exit;
        }
        echo FALSE;
        exit;
    }

    // public function create_trips() {
    //     $this->load->library('googlemaps');
    //     if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
    //         $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
    //     } else {
    //         $map_center = sprintf("%f, %f", ' -4.0434771', '39.6682065');
    //     }
    //     $config['center'] = $map_center;
    //     $config['zoom'] = '12';
    //     $config['map_width'] = '100%';
    //     $config['map_height'] = '600';
    //     $config['map_type'] = 'HYBRID';
    //     //$config['onclick'] = 'alert(\'You just clicked at: \' + event.latLng.lat() + \', \' + event.latLng.lng());';
    //     $this->googlemaps->initialize($config);
    //     $marker = array();
    //     $marker['position'] = $this->session->userdata('company_latitude') . ',' . $this->session->userdata('company_longitude');
    //     $this->googlemaps->add_marker($marker);
    //     $data['map'] = $this->googlemaps->create_map();
    //     $data['content_url'] = 'settings/create_trips';
    //     $data['fa'] = 'fa fa-map-marker';
    //     $data['title'] = 'HAWK | Create Trips';
    //     $data['content_title'] = 'Create Trips';
    //     $data['content_subtitle'] = 'Define custom trips';
    //     $data['content'] = 'settings/create_trips.php';
    //     $this->load->view('main/main.php', $data);
    // }

    public function create_locations() {
        $this->load->library('googlemaps');

        if ($this->session->userdata('company_latitude') != 0 && $this->session->userdata('company_longitude') != 0) {
            $map_center = sprintf("%f, %f", $this->session->userdata('company_latitude'), $this->session->userdata('company_longitude'));
        } else {
            $map_center = sprintf("%f, %f", ' -4.0434771', '39.6682065');
        }

        $config['center'] = $map_center;
        $config['zoom'] = '12';
        $config['map_width'] = '100%';
        $config['map_height'] = '600';
        $config['map_type'] = 'HYBRID';
        //$config['onclick'] = 'alert(\'You just clicked at: \' + event.latLng.lat() + \', \' + event.latLng.lng());';

        $this->googlemaps->initialize($config);

        $marker = array();
        $marker['position'] = $this->session->userdata('company_latitude') . ',' . $this->session->userdata('company_longitude');
        $this->googlemaps->add_marker($marker);

        $data['map'] = $this->googlemaps->create_map();

        $data['content_url'] = 'settings/create_locations';
        $data['fa'] = 'fa fa-map-marker';
        $data['title'] = 'HAWK | Create Locations';
        $data['content_title'] = 'Create Locations';
        $data['content_subtitle'] = 'Define custom locations';
        $data['content'] = 'settings/create_locations.php';

        $this->load->view('main/main.php', $data);
    }

    public function user_permissions() {

        $data ['users'] = $this->mdl_user_settings->get_user_permissions();

        $data['content_url'] = 'settings/user_permissions';
        $data['fa'] = 'fa fa-lock';
        $data['title'] = 'HAWK | User Permissions';
        $data['content_title'] = 'User Permissions';
        $data['content_subtitle'] = 'Assigned access priveledges';
        $data['content'] = 'settings/user_permissions.php';

        $this->load->view('main/main.php', $data);
    }

    public function edit_permissions($user_id) {

        $data ['user'] = $this->mdl_user_settings->get_user_permissions_details($user_id);
        $data ['menus'] = $this->mdl_user_settings->get_menus();
        $data ['reports'] = $this->mdl_user_settings->get_reports();
        $data ['groups'] = $this->mdl_user_settings->get_vehicle_groups();

        $data['content_url'] = 'settings/edit_permissions';
        $data['fa'] = 'fa fa-lock';
        $data['title'] = 'HAWK | Edit Permissions';
        $data['content_title'] = 'Edit Permissions';
        $data['content_subtitle'] = 'Assign access priveledges';
        $data['content'] = 'settings/edit_permissions.php';

        $this->load->view('main/main.php', $data);
    }

    public function set_menu_permissions() {

        $menu_ids = $this->input->post('menu_ids');
        $menu_ids = explode(',', $menu_ids);
        $user_id = $this->input->post('user_id');
        $md_array = array();

        $date = date('Y-m-d H:i:s');

        foreach ($menu_ids as $key => $value) {
            $arr = array();
            $arr['user_id'] = $user_id;
            $arr['menu_id'] = $value;
            $arr['company_id'] = $this->session->userdata('itms_company_id');
            $arr['date_created'] = $date;

            array_push($md_array, $arr);
        }


        echo $this->mdl_user_settings->save_menu_permissions($user_id, $md_array);
    }

    public function set_alert_permissions() {

        $data['sms_alert'] = $this->input->post('sms_alert');
        $data['email_alert'] = $this->input->post('email_alert');
        $data['user_id'] = $this->input->post('user_id');

        echo $this->mdl_user_settings->save_alert_permissions($data);
    }

    public function set_group_permissions() {

        $group_ids = $this->input->post('group_ids');
        $group_ids = explode(',', $group_ids);
        $user_id = $this->input->post('user_id');
        $md_array = array();

        $date = date('Y-m-d H:i:s');

        foreach ($group_ids as $key => $value) {
            $arr = array();
            $arr['user_id'] = $user_id;
            $arr['assets_group_id'] = $value;
            $arr['company_id'] = $this->session->userdata('itms_company_id');
            $arr['date_created'] = $date;

            array_push($md_array, $arr);
        }


        echo $this->mdl_user_settings->save_group_permissions($user_id, $md_array);
    }

    public function set_report_permissions() {

        $report_ids = $this->input->post('report_ids');
        $report_ids = explode(',', $report_ids);
        $user_id = $this->input->post('user_id');
        $md_array = array();

        $date = date('Y-m-d H:i:s');

        foreach ($report_ids as $key => $value) {
            $arr = array();
            $arr['user_id'] = $user_id;
            $arr['report_id'] = $value;
            $arr['company_id'] = $this->session->userdata('itms_company_id');
            $arr['date_created'] = $date;

            array_push($md_array, $arr);
        }


        echo $this->mdl_user_settings->save_report_permissions($user_id, $md_array);
    }

    public function add_device() {
        $data['content_url'] = 'settings/add_device';
        $data['fa'] = 'fa fa-plus';
        $data['title'] = 'HAWK | Add Device';
        $data['content_title'] = 'Add Device';
        $data['content_subtitle'] = '';
        $data['content'] = 'settings/add_device.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_device() {
        $data = $this->input->post();
        $data['company_id'] = $this->session->userdata('itms_company_id');

        echo $this->mdl_settings->save_device($data);
    }

    public function devices() {
        $data ['devices'] = $this->mdl_settings->get_devices($this->session->userdata('itms_company_id'));

        $data['content_btn'] = '<a href="' . site_url('settings/add_device') . '" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Device</a>';
        $data['content_url'] = 'settings/add_device';
        $data['fa'] = 'fa fa-sitemap';
        $data['title'] = 'HAWK | View Devices';
        $data['content_title'] = 'Devices';
        $data['content_subtitle'] = '';
        $data['content'] = 'settings/devices.php';
        $this->load->view('main/main.php', $data);
    }

    function delete_device($id) {
        $this->mdl_settings->delete_device($id);
        header('location:' . base_url('index.php/settings/devices'));
    }

    public function edit_device() {
        $data ['devices'] = $this->mdl_settings->get_device($this->session->userdata('itms_company_id'));

        $data['content_url'] = 'settings/add_device';
        $data['fa'] = 'fa fa-plus';
        $data['title'] = 'HAWK | Edit Device';
        $data['content_title'] = 'Edit Device';
        $data['content_subtitle'] = '';
        $data['content'] = 'settings/edit_device.php';
        $this->load->view('main/main.php', $data);
    }

    function update_device() {
        $data = array('id' => $this->input->post('id'),
            'device_id' => $this->input->post('device_id'),
            'device_name' => $this->input->post('device_name'),
            'serial_no' => $this->input->post('phone_no'));

        $this->mdl_settings->update_device($data);
    }

    public function test() {
        $datetime = time();
        // echo 'Next Week: '. date('Y-m-d', strtotime($datetime)) ."\n";
        // $unixtime = strtotime($datetime);
        // $show = date('d M Y g:ia', $unixtime);
        $t = strtotime($datetime);

        echo $t;

        // echo $datetime;
    }

}
