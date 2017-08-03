<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Normal extends CI_Controller {

    function __construct() {

        parent::__construct();

        
        if ($this->session->userdata('hawk_user_type_id') != 3) {
            redirect('login');
        }
    
         $this->load->model('vehicles/mdl_vehicles');
        $this->load->model('main/mdl_main');
        $this->load->model('alerts/mdl_alerts');
        $this->load->model('gps_tracking/mdl_gps_tracking');
        $this->load->library('gps_utilities');

        $this->user_id = $this->session->userdata('hawk_user_id');
        
    }

    public function index() {

            $map_center = sprintf( "%f, %f", '-4.0434771', '39.6682065');
            $map_lat = '-4.0434771';
            $map_long = '39.6682065';
        

        $coords = array();
        $data = array();
        $vehicleNames = array();
        $vehicleList = '';

        $vehicles = $this->mdl_vehicles->get_vehicles($this->session->userdata('hawk_account_id'),$this->session->userdata('hawk_user_id'),null);
        // print_r($vehicles);
        // exit;
        if(count($vehicles)) {
            foreach ($vehicles as $vehicle) {
                $txt="";
                $txt = addslashes($vehicle->plate_no);
                if($vehicle->model!="")
                    $txt.="(".addslashes($vehicle->model).")";
                $vehicleNames[] =$txt;
                $vehicleList .= "<li vehicle-id='".$vehicle->vehicle_id."'><span class='fa fa-car'></span>&nbsp;".$vehicle->plate_no."</li>";
            }
        }else{
            $vehicleList .= "<li><span class='fa fa-car'></span>&nbsp;<a href='../index.php/vehicles/add_vehicle'>Add Vehicles</a></li>";
        }

$vehicle_count = $this->mdl_gps_tracking->count_vehicles($this->session->userdata('hawk_account_id'), $this->user_id);
        $data['moving_vehicles'] = $vehicle_count['moving'];
        $data['idle_vehicles'] = $vehicle_count['idle'];
        $data['parked_vehicles'] = $vehicle_count['parked'];
        $data['alert_vehicles'] = $vehicle_count['alert'];
        $data['disabled_vehicles'] = $vehicle_count['disabled'];


        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;
        $data['vehicleList'] = $vehicleList;
        $data['vehicleNames'] = $vehicleNames;   
        $data['content_url'] = 'tracking';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Track Vehicle';
        $data['content_title'] = 'Track Vehicle';
        $data['content_subtitle'] = '';
        $data['content'] = 'normal/gps_home2.php';
        $this->load->view('normal/main.php', $data);

    }
    
    public function refresh_alerts() {
        echo $this->mdl_main->fetch_alerts($this->session->userdata('hawk_account_id'));
    }
    
    public function companydetails(){
        
        $data['company_subscriptions'] = $this->mdl_main->fetch_company_subscriptions($this->session->userdata('hawk_account_id'));
        
        $data['content_url'] = 'main/companydetails';
        $data['fa'] = 'fa fa-fw fa-info-circle';
        $data['title'] = 'HAWK | Company Details';
        $data['content_title'] = 'Company Details';
        $data['content_subtitle'] = '';
        $data['content'] = 'main/company_details.php';
        $this->load->view('main/main.php', $data); 
    }

    public function refresh_grid () {

        $query = $this->input->post('query');

        $vehicles = $this->mdl_vehicles->get_vehicles($this->session->userdata('hawk_account_id'),$this->session->userdata('hawk_user_id'),$query);
        
        
        for($i = 0; $i < sizeof($vehicles);$i++)
        {
            $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude,$vehicles[$i]->longitude);
        }

        $res = array('vehicles'=>$vehicles);

        echo json_encode($vehicles);
    }

    public function filter_grid () {

       $vehicles = $this->mdl_vehicles>get_vehicles($this->session->userdata('hawk_account_id'),$this->session->userdata('hawk_user_id'),null);
        
        $res = array('vehicles'=>$vehicles);

        echo json_encode($res);
    }

    public function vehicles() {

        $vehicles = $this->mdl_vehicles->get_vehicles($this->session->userdata('hawk_account_id'), $this->user_id);

        for ($i = 0; $i < sizeof($vehicles); $i++) {

            if ($vehicles[$i]->latitude != NULL) {

                $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
            } else {
                $vehicles[$i]->address = $this->gps_utilities->getaddress(null, null);
            }
        }

        

        $data['vehicles'] = $vehicles;
        $data['content_url'] = 'normal/vehicles';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = 'fa fa-ambulance';
        $data['title'] = 'HAWK | View Vehicles';
        $data['content_title'] = 'View Vehicles';
        $data['content_subtitle'] = 'Add, manage and monitor the locations of your vehicles.';
        $data['content'] = 'normal/vehicles/view_vehicles.php';
        $this->load->view('normal/main.php', $data);
    }
}
