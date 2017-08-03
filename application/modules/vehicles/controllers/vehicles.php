<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles extends Base_Controller {

    function __construct() {

        parent::__construct();


        if ($this->session->userdata('hawk_user_type_id') == 1) {
            redirect('admin');
        }

//        if ($this->session->userdata('hawk_user_type_id') != 2) {
//            redirect('gps');
//        }

        $this->load->model('alerts/mdl_alerts');
        $this->load->model('mdl_vehicles');
        $this->load->model('devices/mdl_devices');
        $this->load->library('cart');
        $this->load->library('gps_utilities');
    }

    public function index() {

        $vehicles = $this->mdl_vehicles->get_vehicles($this->session->userdata('hawk_account_id'));

        for ($i = 0; $i < sizeof($vehicles); $i++) {

            if ($vehicles[$i]->latitude != NULL) {

                $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
            } else {
                $vehicles[$i]->address = $this->gps_utilities->getaddress(null, null);
            }
        }

        $data['vehicles'] = $vehicles;
        $data['content_btn'] = '<a href="' . site_url('vehicles/add_vehicle') . '" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Vehicles</a>';

        $data['content_url'] = 'vehicles/vehicles';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | View Vehicles';
        $data['content_title'] = 'View Vehicles';
        $data['content_subtitle'] = 'Add, manage and monitor the locations of your vehicles.';
        $data['content'] = 'vehicles/view_vehicles.php';
        $this->load->view('main/main.php', $data);
    }

    function command_history($vehicle_id) {
        $data['command_history'] = $this->mdl_vehicles->get_command_history($vehicle_id);

        $data['content_url'] = 'vehicles/command_history';
        $data['fa'] = 'fa fa-terminal';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Command History';
        $data['content_title'] = 'Command History';
        $data['content_subtitle'] = 'View History of Commands Sent To Your Vehicle.';
        $data['content'] = 'vehicles/command_history.php';
        $this->load->view('main/main.php', $data);
    }

    public function fetch_vehicle($vehicle_id) {

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

        $user_id = $this->session->userdata('hawk_user_id');
        $vehicle = $this->mdl_vehicles->get_vehicle($vehicle_id, $user_id);
        $data['reminder'] = $this->mdl_vehicles->get_reminder($vehicle_id);
        $vehicle[0]->address = $this->gps_utilities->getaddress($vehicle[0]->latitude, $vehicle[0]->longitude);
        //print_r($vehicle);

        $data['vehicle'] = $vehicle;


        //exit;
        // $data['vehicle_top_speed'] = $this->mdl_vehicles->get_vehicle_top_speed($vehicle_id);
        // $data['max_day'] = $this->mdl_vehicles->get_vehicle_max_day($vehicle_id);
        // $data['max_week'] = $this->mdl_vehicles->get_vehicle_max_week($vehicle_id);
        // $data['max_month'] = $this->mdl_vehicles->get_vehicle_max_month($vehicle_id);

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
        $this->load->view('main/main.php', $data);
    }

    public function edit_vehicle($vehicle_id) {

        $data['vehicle'] = $this->mdl_vehicles->get_vehicle_by_id($vehicle_id);
        $data['alert_prefs'] = $this->mdl_vehicles->get_vehicle_alert_prefs($vehicle_id);

        $data['content_url'] = 'vehicles/vehicles';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | Edit Vehicle';
        $data['content_title'] = 'Edit Vehicle Details';
        $data['content_subtitle'] = 'Edit Vehicle Details';

        $data['content'] = 'vehicles/edit_vehicle.php';
        $this->load->view('main/main.php', $data);
    }

    public function update_vehicle() {
        $data = $this->input->post();

//        $data['account_id'] = $this->session->userdata('hawk_account_id');
//        $data['add_uid'] = $this->session->userdata('hawk_user_id');

        echo $this->mdl_vehicles->update_vehicle($data);
    }

    public function add_vehicle() {

        $data['content_url'] = 'vehicles/add_vehicle';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | New Vehicle';
        $data['content_title'] = 'New Vehicle';
        $data['content_subtitle'] = 'Add New Vehicle';
        $data['content'] = 'vehicles/add_vehicle.php';
        $data['vehicle_types'] = $this->mdl_vehicles->get_vehicle_types();
        $this->load->view('main/main.php', $data);
    }

    public function save_vehicle() {
        $data = $this->input->post();

        $data['account_id'] = $this->session->userdata('hawk_account_id');
        $data['add_uid'] = $this->session->userdata('hawk_user_id');
        echo $this->mdl_vehicles->save_vehicle($data);
    }

    //  public function fetch_landmarks () {
    //      $data['landmark'] = $this->mdl_vehicles->get_landmarks($this->session->userdata('itms_user_id'));
    //      $data['content_btn']= '<a href="'.site_url('vehicles/add_vehicle').'" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Add Vehicles</a>';    
    //      $data['content_url'] = 'vehicles/vehicles';
    //      $data['fa'] = 'fa fa-car';
    //      $data['title'] = 'HAWK | Vehicle';
    //      $data['content_title'] = 'Vehicle';
    //      $data['content_subtitle'] = 'Vehicle Details';
    //      $data['content'] = 'vehicles/view_landmarks.php';
    //      $this->load->view('main/main.php', $data);
    //  }
    //  // end of landmark function


    public function toggle_vehicle_engine() {
        $device_id = $this->input->post('device_id');
        $command = $this->input->post('command');
        
        $vehicle_id = $this->mdl_vehicles->get_vehicle_id($device_id);
        $has_device = $this->mdl_vehicles->has_active_device($vehicle_id);

        if (!$has_device) {
            echo "88";
            return;
        }
        
        $has_active_cmd = $this->mdl_vehicles->has_active_cmd($vehicle_id);
        
        if ($has_active_cmd) {
            echo "77";
            return;
        }
        
        echo $this->mdl_vehicles->toggle_vehicle_engine($device_id, $command);
    }

}
