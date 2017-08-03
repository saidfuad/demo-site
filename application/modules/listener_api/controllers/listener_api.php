<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Listener_api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Mdl_listener_api');
        $this->load->model('Mdl_trip_master');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->model('alerts/mdl_alerts');
        $this->load->model('geofence/mdl_geofence');
        $this->load->library('emailsend');
        $this->load->library('smssend');
        $this->load->library('sendmail');
    }

    function save_raw_data($data) {
        $this->db->insert('raw_data', $data);
    }

    function listen_post() {
        $input = $this->input->post('gps');
        
        $input = $this->parseString($input);
        $input = $this->splitString($input);
        echo "Count ".count($input) . PHP_EOL;
        for($i = 0; $i < count($input); $i++)
        {
                 $this->processRequest($input[$i]);
        }
    }

    //
    //Removes the logs from the string coming in and the spaces
    //
    public  function parseString($input)
    {
        $input = str_replace(array("\r\n","\r"),"",$input);
        $input = preg_replace('~[\r\n]+~', '', $input);
        $input = preg_replace('~[\r\n]+~', '', $input);
        $pos = strpos($input, "log");
        if($pos !== false)
        {
            $input = explode("log", $input);
            $input = implode("", $input);
        }

        return $input;
    }


    //
    //Splits the string and create a unique array 
    //
    public function splitString($input)
    {   
        $result =array() ;
        $length = strlen($input);
        for($i = 0; $i <$length; $i+= 78)
        {
            $data = substr($input, $i, 78);
            echo "Data ".$i." : " . $data . PHP_EOL;
            array_push($result, $data);
        }

        $result = array_unique($result);
        return $result;
    }


    /// process coordinates

    public function processRequest($input)
    {
        $terminal_id = substr($input, 1, 11);
        $command = substr($input, 12, 4);
        $date = substr($input, 16, 6);
        $gps_availability = substr($input, 22, 1);
        $latitude = substr($input, 23, 9);
        $latitude_indicator = substr($input, 32, 1);
        $longitude = substr($input, 33, 10);
        $longitude_indicator = substr($input, 43, 1);
        $speed = ltrim(substr($input, 44, 5), '0');
        $time = substr($input, 49, 6);
        $orientation = substr($input, 55, 6);
        $io_state = substr($input, 61, 8);
        $ignition = substr($io_state, 2, 1);
        $overspeed = substr($io_state, 3, 1);
        $arm_alert = substr($io_state, 4, 1);
        $power_cut = substr($io_state, 5, 1);
        $mile_post = substr($input, 69, 1);
        $mile_data = substr($input, 70, 8);

//        print_r("Ignition: ".$ignition." | Over speed: ".$overspeed." | Arm: ".$arm_alert." | power cut: ".$power_cut);
//        exit;

        $this->save_raw_data(array('terminal_id' => $terminal_id, 'data' => $input));

        if ($orientation == 0 || $orientation == 360) {
            $orientation = 90;
        } else if ($orientation == 90) {
            $orientation = 0;
        } else if ($orientation == 180) {
            $orientation = 270;
        } else if ($orientation == 270) {
            $orientation = 180;
        } else if ($orientation < 90) {
            $diff = 90 - $orientation;
            $orientation = $diff;
        } else if ($orientation > 90 && $orientation < 180) {
            $diff = $orientation - 90;
            $diff = 360 - $diff;
            $orientation = $diff;
        } else if ($orientation > 180 && $orientation < 270) {
            $diff = 270 - $orientation;
            $diff = 180 + $diff;
            $orientation = $diff;
        } else if ($orientation > 270 && $orientation < 360) {
            $diff = 360 - $orientation;
            $diff = 90 + $diff;
            $orientation = $diff;
        }

        $latitude = $this->gps_utilities->deg_to_decimal($latitude . $latitude_indicator);
        $longitude = $this->gps_utilities->deg_to_decimal($longitude . $longitude_indicator);


        $vehicle_id = $this->mdl_vehicles->get_vehicle_id_by_terminal($terminal_id);
        if ($vehicle_id == NULL) {
            //echo "DEVICE NOT LINKED!" . PHP_EOL;
            return;
        }

        //echo "PLATE NO: " . $this->Mdl_listener_api->get_plate_no($vehicle_id) . PHP_EOL;
        //get address
        $address = ""; //$this->gps_utilities->getaddress($latitude, $longitude);
        /* Check if in/out landmark */
        $this->is_in_landmark($vehicle_id, $latitude, $longitude, $address);

        /* Check if out of assigned routes */
        $this->is_in_route($vehicle_id, $latitude, $longitude, $address);

        /* Check if out of geofence */
        $this->is_in_geofence($vehicle_id, $latitude, $longitude, $address);

        /* Checking for io alerts */
        $this->check_alert($vehicle_id, $power_cut, $overspeed, $arm_alert, $latitude, $longitude, $speed, $address);

        $tracking_time =  $this->get_tracking_time($date, $time);

        $result = $this->Mdl_listener_api->listen($vehicle_id, $command, $gps_availability, $latitude, $longitude, $speed, $orientation, $ignition, $overspeed, $arm_alert, $power_cut, $mile_post, $mile_data, $date, $time, $tracking_time);



        //echo "RS: ".$result;
        $this->Mdl_trip_master->Initialize($vehicle_id, $ignition, $speed, $latitude, $longitude, $tracking_time);

        if ($result) {
            echo "DATA INSERTED" . PHP_EOL;
        } else {
            echo "DATA NOT INSERTED" . PHP_EOL;
        }
    }

  function get_tracking_time($date, $time) {
        $year = substr($date, 0, 2);
        $month = substr($date, 2, 2);
        $day = substr($date, 4, 2);
        $year = "20" . $year;

        $hours = substr($time, 0, 2);
        $min = substr($time, 2, 2);
        $sec = substr($time, 4, 2);

        $timestamp = $year . "-" . $month . "-" . $day . " " . $hours . ":" . $min . ":" . $sec;
        return date("Y-m-d H:i:s", strtotime($timestamp) - 5 * 3600);
    }


    /**
     * Check if vehicle is in landmark
     * @param type $vehicle_id
     * @param type $lat
     * @param type $lng
     * @return type void
     */
    function is_in_landmark($vehicle_id, $lat, $lng, $address) {
        $landmark_data = $this->mdl_geofence->is_in_landmark($vehicle_id, $lat, $lng, $address);
        //$this->Mdl_listener_api->is_in_landmark(3, -4.54594, 39.4159146);
        //print_r($landmark_data);
        $this->send_alerts($vehicle_id, LANDMARK_ALERT, NULL, $landmark_data['geofence_id'], $landmark_data['type'], $landmark_data['name'], $landmark_data['distance'], $landmark_data['unit'], $lat, $lng,$landmark_data['alert_id']);
    }

    /**
     * Function to check if vehicle is in assigned route
     * @param type $vehicle_id
     * @param type $lat
     * @param type $lng
     */
    function is_in_route($vehicle_id, $lat, $lng, $address) {
        $route_data = $this->mdl_geofence->is_in_route($vehicle_id, $lat, $lng, $address);

        $this->send_alerts($vehicle_id, ROUTE_ALERT, NULL, $route_data['geofence_id'], $route_data['type'], $route_data['name'], $route_data['distance'], $route_data['unit'], $lat, $lng, $route_data['alert_id']);
    }

    function get_sms_reciever($vehicle_id) {
        return $this->Mdl_listener_api->get_sms_reciever_data($vehicle_id);
    }

    function send_alerts($vehicle_id, $alert_type_id, $speed = NULL, $geofence_id = NULL, $geofence_type = NULL, $geofence_name = NULL, $distance = NULL, $distance_unit = NULL, $vehicle_lat = NULL, $vehicle_lng = NULL, $alert_id = NULL) {

        $email_alert = FALSE;
        $sms_alert = FALSE;
        $user_data = array();

        if (is_null($geofence_id)) {
            if ($alert_type_id == RECONNECTION_ALERT || $alert_type_id == ENGINE_ENABLED_ALERT ||
                    $alert_type_id == ENGINE_DISABLED_ALERT || $alert_type_id == WORK_TIME_UPDATED_ALERT) {

                $sms_alert = TRUE;
                $user_data = $this->get_sms_reciever($vehicle_id);
            } else {
                $email_alert = $this->Mdl_listener_api->has_email_alert($vehicle_id, $alert_type_id);
                $sms_alert = $this->Mdl_listener_api->has_sms_alert($vehicle_id, $alert_type_id);
                $user_data = $this->Mdl_listener_api->get_reciever_data($vehicle_id, $alert_type_id);
            }
        } else {
            $geo_data_prefs = $this->Mdl_listener_api->geofence_has_alerts($vehicle_id, $geofence_id, $geofence_type);

            $email_alert = ($geo_data_prefs['email_alert']) ? TRUE : FALSE;
            $sms_alert = ($geo_data_prefs['sms_alert']) ? TRUE : FALSE;

            $user_data = $this->Mdl_listener_api->get_geofence_reciever_data($vehicle_id, $geofence_id);
        }

        if ($email_alert) {
            $plate_no = $this->mdl_vehicles->get_plate_no($vehicle_id);

            $to = array($user_data['email']);
            $subj = "HAWK Alerts";

            switch ($alert_type_id) {
                case POWER_CUT_ALERT:
                    $message = "HAWK: Power cut on vehicle " . $plate_no;
                    $res = $this->emailsend->send_email_message($to, $subj, $message);

                    echo ($res) ? "\nPOWER CUT ALERT EMAIL SENT " . PHP_EOL : "POWER CUT EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
                case OVER_SPEED_ALERT:
                    $message = "HAWK: Overspeed by " . $plate_no . ", at " . $speed . " km/h.";

                    $res = $this->emailsend->send_email_message($to, $subj, $message);
                    echo ($res) ? "OVERSPEED ALERT EMAIL SENT " . PHP_EOL : "OVERSPEED ALERT EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
                case ARM_ALERT:
                    $message = "HAWK: Alarm activated on " . $plate_no;

                    $res = $this->emailsend->send_email_message($to, $subj, $message);
                    echo ($res) ? "ARM ALERT EMAIL SENT " . PHP_EOL : "ARM ALERT EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
                case LANDMARK_ALERT:
//                    $message = "HAWK: " . $plate_no . " is in landmark " . $geofence_name . ". " . $this->config->item("google_maps") . $vehicle_lat . ',' . $vehicle_lng;

                    $message = "HAWK: Landmark infringement by " . $plate_no . " on " . $geofence_name . " landmark. "
                            . site_url('alerts_map/view_geo_alerts/' . $geofence_id . '/' . $alert_id);

                    $res = $this->emailsend->send_email_message($to, $subj, $message);
                    echo ($res) ? "IN LANDMARK ALERT EMAIL SENT" . PHP_EOL : "IN LANDMARK EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
                case GEOFENCE_ALERT:
                    $message = "HAWK: Geofence infringement by " . $plate_no . " on " . $geofence_name . " geofence. "
                            . site_url('alerts_map/view_geo_alerts/' . $geofence_id . '/' . $alert_id);

                    $res = $this->emailsend->send_email_message($to, $subj, $message);
                    echo ($res) ? "GEOFENCE INFRINGEMENT ALERT EMAIL SENT" . PHP_EOL : "GEOFENCE INFRINGEMENT EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
                case ROUTE_ALERT:
                    $message = "HAWK: Route infringement by " . $plate_no . " by " . $distance . $distance_unit . " on " . $geofence_name . " route. "
                            . site_url('alerts_map/view_geo_alerts/' . $geofence_id . '/' . $alert_id);

                    $res = $this->emailsend->send_email_message($to, $subj, $message);
                    echo ($res) ? "ROUTE INFRINGEMENT ALERT EMAIL SENT" . PHP_EOL : "ROUTE INFRINGEMENT EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
                case RECONNECTION_ALERT:
                    $message = "HAWK: Device reconnected on vehicle " . $plate_no;
                    $res = $this->emailsend->send_email_message($to, $subj, $message);

                    echo ($res) ? "DEVICE RECONNECTED EMAIL SENT" . PHP_EOL : "DEVICE RECONNECTED EMAIL FAILED TO SEND" . PHP_EOL;
                    break;
            }
        }

        if ($sms_alert) {
            $plate_no = $this->mdl_vehicles->get_plate_no($vehicle_id);

            $recipient = array($user_data['phone_no']);

            $message = "";
            switch ($alert_type_id) {
                case POWER_CUT_ALERT:
                    $message = "HAWK: Power cut on vehicle " . $plate_no;
                    //$res = $this->smssend->send_text_message($recipient, $message);

                    echo ($res) ? "POWER CUT ALERT SMS SENT" . PHP_EOL : "POWER CUT SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case OVER_SPEED_ALERT:
                    $message = "HAWK: Overspeed by " . $plate_no . ", at " . $speed . " km/h.";

                    //$res = $this->smssend->send_text_message($recipient, $message);
                    echo ($res) ? "OVERSPEED ALERT SMS SENT" . PHP_EOL : "OVERSPEED ALERT SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case ARM_ALERT:
                    $message = "HAWK: Alarm activated on " . $plate_no;

                   // $res = $this->smssend->send_text_message($recipient, $message);
                    echo ($res) ? "ARM ALERT SMS SENT" . PHP_EOL : "ARM ALERT SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case LANDMARK_ALERT:
//                    $message = "HAWK: " . $plate_no . " is in landmark " . $geofence_name . ". " . $this->config->item("google_maps") . $vehicle_lat . ',' . $vehicle_lng;

                    $message = "HAWK: Landmark infringement by " . $plate_no . " on " . $geofence_name . " landmark. "
                            . site_url('alerts_map/view_geo_alerts/' . $geofence_id . '/' . $alert_id);

                    //$res = $this->smssend->send_text_message($recipient, $message);
                    echo ($res) ? "IN LANDMARK ALERT SMS SENT" . PHP_EOL : "IN LANDMARK EMAIL SMS TO SEND" . PHP_EOL;
                    break;
                case GEOFENCE_ALERT:
                    $message = "HAWK: Geofence infringement by " . $plate_no . " on "
                            . $geofence_name . " geofence. " . site_url('alerts_map/view_geo_alerts/' . $geofence_id . '/' . $alert_id);

                    //$res = $this->smssend->send_text_message($recipient, $message);
                    echo ($res) ? "GEOFENCE INFRINGEMENT ALERT SMS SENT" . PHP_EOL : "GEOFENCE INFRINGEMENT SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case ROUTE_ALERT:
                    $message = "HAWK: Route infringement by " . $plate_no . " by " . $distance . $distance_unit . " on " . $geofence_name . " route "
                            . site_url('alerts_map/view_geo_alerts/' . $geofence_id . '/' . $alert_id);

                    //$res = $this->smssend->send_text_message($recipient, $message);
                    echo ($res) ? "ROUTE INFRINGEMENT SMS EMAIL SENT" . PHP_EOL : "ROUTE INFRINGEMENT SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case RECONNECTION_ALERT:
                    $message = "HAWK: Device reconnected on vehicle " . $plate_no;
                    //$res = $this->smssend->send_text_message($recipient, $message);

                    echo ($res) ? "DEVICE RECONNECTED SMS SENT" . PHP_EOL : "DEVICE RECONNECTED SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case ENGINE_ENABLED_ALERT:
                    $message = "HAWK: " . $plate_no . " engine enabled successfully";
                    //$res = $this->smssend->send_text_message($recipient, $message);

                    echo ($res) ? "ENGINE ENABLED SMS SENT" . PHP_EOL : "ENGINE ENABLED SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case ENGINE_DISABLED_ALERT:
                    $message = "HAWK: " . $plate_no . " engine disabled successfully";
                    //$res = $this->smssend->send_text_message($recipient, $message);

                    echo ($res) ? "ENGINE DISABLED SMS SENT" . PHP_EOL : "ENGINE DISABLED SMS FAILED TO SEND" . PHP_EOL;
                    break;
                case WORK_TIME_UPDATED_ALERT:
                    $message = "HAWK: " . $plate_no . " work time updated successfully";
                    //$res = $this->smssend->send_text_message($recipient, $message);

                    echo ($res) ? "WORK TIME SMS SENT" . PHP_EOL : "WORK TIME SMS FAILED TO SEND" . PHP_EOL;
                    break;
            }

            $this->add_sms_log($user_data['user_id'], $user_data['account_id'], $message);
        }
    }

    /**
     * Add SMS logs
     * @param type $user_id
     * @param type $account_id
     * @param type $message
     */
    function add_sms_log($user_id, $account_id, $message) {
        $data = array('user_id' => $user_id, 'account_id' => $account_id, 'message' => $message);
        $this->db->insert('sms_logs', $data);

        echo "SMS_LOGGED" . PHP_EOL;
    }

    /**
     * Check for I/O alerts.
     * Assumption: There can never be one type of alert with two active status
     * @param type $vehicle_id
     * @param type $power_cut
     * @param type $overspeed
     * @param type $arm_alert
     * @param type $lat
     * @param type $lng
     * @param type $speed
     * @param type $address
     */
    function check_alert($vehicle_id, $power_cut, $overspeed, $arm_alert, $lat, $lng, $speed = NULL, $address) {

        $check_power_cut = $this->mdl_alerts->check_for_active_alert($vehicle_id, POWER_CUT_ALERT);
        $check_overspeed = $this->mdl_alerts->check_for_active_alert($vehicle_id, OVER_SPEED_ALERT);
        $check_arm_alert = $this->mdl_alerts->check_for_active_alert($vehicle_id, ARM_ALERT);



        if ($check_power_cut == "1") {
            if ($power_cut == "0") {
                /* stop power cut alert */
                $this->mdl_alerts->stop_alert($vehicle_id, POWER_CUT_ALERT, $lat, $lng, $address);
                //$this->send_alerts($vehicle_id, RECONNECTION_ALERT);
            }
        } else {

            if ($power_cut == "1") {
                if ($this->mdl_alerts->add_alert($vehicle_id, POWER_CUT_ALERT, $lat, $lng, $address)) {
                    //$this->send_alerts($vehicle_id, POWER_CUT_ALERT);
                }else{
                }
            }
        }

        if ($check_overspeed == "1") {
            if ($overspeed == "0") {
                /* stop overspeed alert */
                $this->mdl_alerts->stop_alert($vehicle_id, OVER_SPEED_ALERT, $lat, $lng, $address);
            }
        } else {
            if ($overspeed == "1") {
                if ($this->mdl_alerts->add_alert($vehicle_id, OVER_SPEED_ALERT, $lat, $lng, $address, NULL, NULL, $speed)) {
                    $this->send_alerts($vehicle_id, OVER_SPEED_ALERT, $speed);
                }
            }
        }

        if ($check_arm_alert == "1") {
            if ($arm_alert == "0") {
                /* stop arm alert */
                $this->mdl_alerts->stop_alert($vehicle_id, ARM_ALERT, $lat, $lng, $address);
            }
        } else {
            if ($arm_alert == "1") {
                if ($this->mdl_alerts->add_alert($vehicle_id, ARM_ALERT, $lat, $lng, $address)) {
                    $this->send_alerts($vehicle_id, ARM_ALERT);
                }
            }
        }
    }

    function is_in_geofence($vehicle_id, $vehicle_lat, $vehicle_lng, $address) {
        $geofence_data = $this->mdl_geofence->get_vehicle_goefence($vehicle_id);
        if (is_null($geofence_data)) {
            echo "NO GEOFENCE ATTACHED" . PHP_EOL;
            return;
        }

        $vertices_x = explode(",", $geofence_data['vertices_x']);
        $vertices_y = explode(",", $geofence_data['vertices_y']);

        $geofence_vertices = array();
        for ($i = 0; $i < count($vertices_x); $i++) {
            array_push($geofence_vertices, array($vertices_x[$i], $vertices_y[$i]));
        }

        /* $in_geofence = array(array(-4.048902, 39.704999)
          ,array(-4.048956, 39.704859)
          ,array(-4.049425, 39.704807)
          ,array(-4.048929, 39.704863)
          ,array(-4.049344, 39.704999)
          ,array(-4.048688, 39.705097)
          ,array(-4.049528, 39.704781)
          ,array(-4.049049, 39.704794));

          $out_geofence =
          array(array(-4.034567, 39.686128)
          ,array(-4.032425, 39.682060)
          ,array(-4.029617, 39.679122)
          ,array(-4.048043, 39.669043)
          ,array(-4.047476, 39.667208)
          ,array(-4.044834, 39.664805)
          ,array(-4.043870, 39.661435)
          ,array(-4.043592, 39.657229));

          foreach($out_geofence as $point){
          //echo $this->gps_utilities->is_in_polygon(array($vehicle_lat,$vehicle_lng),$array)?'IN':'OUT'.PHP_EOL;
          echo $this->gps_utilities->is_in_polygon($point,$array)?'IN':'OUT'.PHP_EOL;
          }
          exit; */
        //$points_polygon = count($vertices_x) - 1;  // number vertices

        $is_active = $this->mdl_alerts->check_for_active_alert($vehicle_id, GEOFENCE_ALERT);

        if ($this->gps_utilities->is_in_polygon(array($vehicle_lat, $vehicle_lng), $geofence_vertices)) {
            echo "IS IN " . $geofence_data['name'] . " GEOFENCE " . $geofence_data["id"] . PHP_EOL;

            if ($is_active) {
                $this->mdl_alerts->update_geofence_alert($vehicle_id, $vehicle_lat, $vehicle_lng, $geofence_data["id"],$address);
            }
        } else {
            echo "GEOFENCE INFRINGEMENT, " . $geofence_data['name'] . " GEOFENCE" . PHP_EOL;

            if ($is_active == FALSE) {
                $alert_id = $this->mdl_alerts->add_alert($vehicle_id, GEOFENCE_ALERT, $vehicle_lat, $vehicle_lng,$address, $geofence_data["id"]);

                $this->send_alerts($vehicle_id, GEOFENCE_ALERT, NULL, $geofence_data['id'], $geofence_data['type'], $geofence_data['name'], NULL, NULL, $vehicle_lat, $vehicle_lng, $alert_id);
            }
        }
    }

    /**
     * Gets responses from listener after sending command
     */
    function send_message_post() {
        $id = $this->post('id');
        $terminal_id = $this->post('terminal_id');
        $message = $this->post('response');
        $vehicle_id = $this->mdl_vehicles->get_vehicle_id_by_terminal($terminal_id);
        switch ($message) {
            case "startok":
                $this->send_alerts($vehicle_id, ENGINE_ENABLED_ALERT);
                break;
            case "stopingok":
                $this->send_alerts($vehicle_id, ENGINE_DISABLED_ALERT);
                break;
            case "successful":
                $this->send_alerts($vehicle_id, WORK_TIME_UPDATED_ALERT);
                $this->mdl_vehicles->update_work_time_expiry_date($id, $vehicle_id);
                break;
        }
    }

    function get_vehicles_by_last_seen_post(){
        $vehicles = $this->mdl_vehicles->get_vehicles_by_last_seen();
        $html = $this->html_table($vehicles);
        $res = $this->sendmail->send_mail('wamaebenson06@gmail.com,fso@kits.co.ke,elishawambiji@divacom.co.ke', "LAST SEEN", $html);
        
    }


    function html_table($data = array()){
        $rows = array();
        foreach ($data as $row) {
            $cells = array();
            foreach ($row as $cell) {
                $cells[] = "<td>{$cell}</td>";
            }
            $rows[] = "<tr>" . implode('', $cells) . "</tr>";
        }
        return "<table class='hci-table'> <tr>
            <th>VEHICLE</th>
            <th>LAST SEEN</th>
        </tr>" . implode('', $rows) . "</table>";
    }

}
