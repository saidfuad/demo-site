<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reports extends CI_Controller {
    function __construct() {

        parent::__construct();

        $this->load->model('mdl_reports');
        $this->load->model('alerts/mdl_alerts');

    }

    public function index() {

        $data['vehicles'] = $this->mdl_reports->get_vehicles($this->session->userdata('hawk_account_id'));
        $data['products'] = $this->mdl_reports->get_products();

        /*echo "<pre>";
        print_r($data);
        exit;*/

        $data['content_url'] = 'gps_tracking';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Reports';
        $data['content_title'] = 'Reports';
        $data['content_subtitle'] = 'View, Print or Export Reports to Excel or PDF';
        $data['content'] = 'reports/reports.php';

        $this->load->view('main/main.php', $data);
    }

    public function get_general_reports($start, $end, $plate, $sum){

        if(($start != null && $end != null) || ($start != "null" && $end != "null")){
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }

        if($plate == "null"){
            $plate = null;
        }

        if($sum == "true"){
            $sum = 1;
        }else{
            $sum = 0;
        }

        $res = $this->mdl_reports->fetch_general_reports($this->session->userdata('hawk_account_id'), $start, $end, $plate, $sum);

        print_r(json_encode($res));

    }

    public function get_mileage_reports($start, $end, $plate, $sum){

        if(($start != null && $end != null) || ($start != "null" && $end != "null")){
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }

        if($plate == "null"){
            $plate = null;
        }

        if($sum == "true"){
            $sum = 1;
        }else{
            $sum = 0;
        }

        $res = $this->mdl_reports->fetch_mileage_reports($this->session->userdata('hawk_account_id'), $start, $end, $plate, $sum);

        print_r(json_encode($res));

    }

    public function get_alert_reports($start, $end, $plate){

        if(($start != null && $end != null) || ($start != "null" && $end != "null")){
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }

        if($plate == "null"){
            $plate = null;
        }

        $res = $this->mdl_reports->fetch_alert_reports($this->session->userdata('hawk_account_id'), $start, $end, $plate);

        print_r(json_encode($res));

    }

    public function get_purchase_reports($start, $end, $plate, $product, $sum){

        if(($start != null && $end != null) || ($start != "null" && $end != "null")){
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }

        if($plate == "null"){
            $plate = null;
        }

        if($product == "null"){
            $product = null;
        }

        if($sum == "true"){
            $sum = 1;
        }else{
            $sum = 0;
        }

        $res = $this->mdl_reports->fetch_purchase_reports($this->session->userdata('hawk_account_id'), $start, $end, $plate, $product, $sum);

        print_r(json_encode($res));
    }

}
?>
