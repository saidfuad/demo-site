<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sacco extends CI_Controller {
    function __construct() {
       
        parent::__construct();
   
        if ($this->session->userdata('hawk_user_type_id') != $this->config->item("sacco_user_type")) {
            redirect('login');
        }

        $this->load->model('mdl_sacco_dashboard');
        $this->load->model('mdl_sacco');
        $this->load->model('reminders/mdl_reminders');
        $this->load->model('users/mdl_users');
        $this->load->model('accounting/mdl_accounting');
        $this->load->model('mdl_gps_tracking');
        $this->load->model('mdl_gps_history');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->model('listener_api/mdl_trip_master');
        $this->load->library('gps_utilities');
        $this->load->library('sendmail');
        $this->load->library('smssend');

        $this->account_id = $this->session->userdata('hawk_account_id');
        $this->sacco_id = $this->session->userdata('hawk_user_id');
        $this->vehicles =  $this->mdl_vehicles->get_sacco_vehicles($this->sacco_id);
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

        $vehicles = $this->vehicles;
        $owners = $this->mdl_sacco->get_owners($this->sacco_id);
       

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
        $data['owners'] = $owners;
     

        $data['content_url'] = 'sacco';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | GPS Tracking';
        $data['content_title'] = 'GPS Tracking';
        $data['content_subtitle'] = 'Vehicle Location Tracking';
        $data['content'] = 'sacco/gps_tracking/gps_home.php';

        $this->load->view('sacco/main.php', $data);
    }

    public function refresh_grid() {
        $query = $this->input->post('query');
        $owner_id = $this->input->post('owner_id');
        $vehicles = $this->mdl_vehicles->get_sacco_vehicles($this->sacco_id, $owner_id);
        //print_r($vehicles);
       //  exit;

        for ($i = 0; $i < sizeof($vehicles); $i++) {
            $vehicles[$i]->address = $this->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
        }

        $res = array('vehicles' => $vehicles);

        echo json_encode($vehicles);
    }

    public function filter_grid() {
        $owner_id = $this->input->post('owner_id');
        $vehicles = $this->mdl_vehicles->get_sacco_vehicles($this->sacco_id, $owner_id);
        $res = array('vehicles' => $vehicles);
        echo json_encode($res);
    }

    public function vehicles(){

        $vehicles = $this->mdl_vehicles->get_sacco_vehicles($this->sacco_id);

        for ($i = 0; $i < sizeof($vehicles); $i++) {

            if ($vehicles[$i]->latitude != NULL) {

                $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
            } else {
                $vehicles[$i]->address = $this->gps_utilities->getaddress(null, null);
            }
        }

        $data['vehicles'] = $vehicles;

        $data['content_url'] = 'sacco/vehicles';
        $data['fa'] = 'fa fa-car';
        $data['fa1'] = 'fa fa-car';
        $data['fa2'] = 'fa fa-motorcycle';
        $data['fa3'] = 'fa fa-bus';
        $data['fa4'] = 'fa fa-truck';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Vehicles';
        $data['content_title'] = 'Vehicles';
        $data['content_subtitle'] = 'View Vehicles';
        $data['content'] = 'sacco/vehicles/view_vehicles.php';

        $this->load->view('sacco/main.php', $data);
    }

    public function fetch_vehicle($vehicle_id) {

        $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
        $map_lat = '-4.0434771';
        $map_long = '39.6682065';
    
        $data['map_lat'] = $map_lat;
        $data['map_long'] = $map_long;

        $user_id = $this->sacco_id;
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
        $this->load->view('sacco/main.php', $data);
    }

    public function view_history($id) {

        $data['history'] = $this->mdl_trip_master->get_history($id);

        $data['content_url'] = 'sacco/gps_history';
        $data['fa'] = 'fa fa-map-marker';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Track History';
        $data['content_title'] = 'Track History';
        $data['content_subtitle'] = 'Vehicle History';
        $data['content'] = 'sacco/gps_history/view_history.php';

        $this->load->view('sacco/main.php', $data);
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

        $this->load->view('sacco/main.php', $data);

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

        $this->load->view('sacco/main.php', $data);
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



     public function reminders(){
        $data['insurances'] = $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('insurances'));
        $data['licenses'] = $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('licenses'));
        $data['services'] =  $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('services'));
        $data['permit'] = $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('permit'));
 
        $data['content_url'] = 'sacco/reminders';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'Hawk | View Reminders';
        $data['content_title'] = 'View Reminders';
        $data['content_subtitle'] = 'Click the tabs below to see the list of your set reminders';
        $data['content'] = 'sacco/reminders/view_reminders.php';
        $this->load->view('sacco/main.php', $data);
    }

     public function add_insurance() {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->sacco_id;
        $data ['vehicles'] = $this->vehicles;
        $data['content_url'] = 'sacco/reminders/add_insurance';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add Insurance Reminder';
        $data['content_title'] = 'Add Insurance Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'sacco/reminders/add_insurance.php';
        $this->load->view('sacco/main.php', $data);
    }

      public function add_license() {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->sacco_id;
        $data ['drivers'] = $this->mdl_users->get_users( $this->account_id);
        $data['content_url'] = 'sacco/reminders/add_license';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add License Reminder';
        $data['content_title'] = 'Add License Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'sacco/reminders/add_license.php';
        $this->load->view('sacco/main.php', $data);
    }

     public function save_reminder(){
        $data=$this->input->post();
        $data['status']=1;
        
        $dat['vehicle_id']=$this->input->post('vehicle_id');
        $dat['expense_type_id']=$this->input->post('reminder_type_id');
        $dat['amount']=$this->input->post('amount_to_pay');
        $dat['account_id']=$this->input->post('account_id');
        $dat['add_uid']=$this->input->post('add_uid');

        $this->mdl_accounting->save_expense($dat);

        return $this->mdl_reminders->save_rem($data);
    }

    public function update_reminder(){
        $data=$this->input->post();

        return $this->mdl_reminders->update_rem($data);
    }

    public function edit_insurance($id) {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->sacco_id;
        $data ['vehicles'] = $this->vehicles;
        $data ['reminder'] = $this->mdl_reminders->fetch_reminder($id);
        $data['content_url'] = 'sacco/reminders/edit_insurance';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit Insurance Reminder';
        $data['content_title'] = 'Edit Insurance Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'sacco/reminders/edit_insurance.php';
        $this->load->view('sacco/main.php', $data);
    }

      public function edit_license($id) {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->sacco_id;
        $data ['drivers'] = $this->mdl_users->get_users($this->account_id);
        $data ['reminder'] = $this->mdl_reminders->fetch_license($id);
        $data['content_url'] = 'sacco/reminders/edit_license';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit License Reminder';
        $data['content_title'] = 'Edit License Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'sacco/reminders/edit_license.php';
        $this->load->view('sacco/main.php', $data);
    }

     public function send_remsms($id){
        $userdet=$this->mdl_reminders->get_user_by_id($this->user_id);
        $reminder = $this->mdl_reminders->fetch_reminder($id);
        if(!empty($userdet['email'])){
        //$to = array($userdet['email']);
        $subj = "Hawk - Reminder";
        $url = $this->base_url();
        
        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
            <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                <h1>FeedBack Details</h1>
            </div>
            <div style="padding:20px;">
                Dear User,<br><br>
                Please Read The Reminder Below.
                <br>
                <br>
                Reminder Details<br>
                Reminder For : ' . $reminder['reminder_name'] . '<br>
                Vehicle Plate Number That The Reminder Applies To is: ' . $reminder['model'] .' - '.$reminder['plate_no'] . ' <br>
                Company Name To Pay : ' . $reminder['company'] . '<br>
                Amount To Pay : ' . $reminder['amount_to_pay'] . '<br>
                <br>
                <br>
                Verify carefully the Company information.
                <br>
                In case of any doubts, please feel free to contact HAWK Registrar.<br>
                <a href="#">info@svs.com</a> on or <a href="#">+254 (0)729 220 777</a>
                <br>                        
            </div>
        </div>';
        
    $this->Sendmail->send_mail($user_data['email'],$subj,$message);

        }

        $recipient = array($userdet['phone_no']);
        $message = "HAWK Reminder.\r\nReminder For:".$reminder['reminder_name'].".\r\nApplies To Vehicle  : ". $reminder['model'] .' - '.$reminder['plate_no'].".\r\nCompany Name To Pay : " . $reminder['company'] . " \r\nAmount To Pay : " . $reminder['amount_to_pay'] . "";

        $res = $this->smssend->send_text_message($recipient, $message);
    }

}
