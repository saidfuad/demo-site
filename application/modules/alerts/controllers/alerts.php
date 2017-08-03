<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alerts extends Base_Controller {

	function __construct() {

        parent::__construct();

        $this->load->library('encrypt');

        $this->load->model('mdl_alerts');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('cart');

    }

    public function index(){

        $data['alerts'] = $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), null, null);
        $data['power_cut_alerts'] = $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), 1, null);
        $data['overspeed_alerts'] =  $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), 2, null);
        $data['arm_alerts'] =  $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), 3, null);
        $data['geofence_alerts'] =  $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), 4, null);
        $data['landmark_alerts'] =  $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), 5, null);
        $data['route_alerts'] =  $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), 6, null);

        /* Count Alerts */
        $data['count_power_cut_alerts'] = $this->mdl_alerts->fetch_counts($this->session->userdata('hawk_account_id'), 1, 0);
        $data['count_overspeed_alerts'] =  $this->mdl_alerts->fetch_counts($this->session->userdata('hawk_account_id'), 2, 0);
        $data['count_arm_alerts'] =  $this->mdl_alerts->fetch_counts($this->session->userdata('hawk_account_id'), 3, 0);
        $data['count_geofence_alerts'] =  $this->mdl_alerts->fetch_counts($this->session->userdata('hawk_account_id'), 4, 0);
        $data['count_landmark_alerts'] =  $this->mdl_alerts->fetch_counts($this->session->userdata('hawk_account_id'), 5, 0);
        $data['count_route_alerts'] =  $this->mdl_alerts->fetch_counts($this->session->userdata('hawk_account_id'), 6, 0);

        /*echo "<pre>";
        print_r($data);
        exit;*/

        $data['content_url'] = 'Alerts';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'Hawk | View Alerts';
        $data['content_title'] = 'View Alerts';
        $data['content_subtitle'] = '';
        $data['content'] = 'alerts/view_alerts.php';
        $this->load->view('main/main.php', $data);
    }

    public function read_alert(){
        $data = $this->input->post();
        return $this->mdl_alerts->read_alert($data);
    }

}
