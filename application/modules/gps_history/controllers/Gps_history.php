<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Gps_history extends Base_Controller
{
    function __construct()
    {

        parent::__construct();

        $this->load->model('mdl_gps_history');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->model('alerts/mdl_alerts');
        $this->load->model('devices/mdl_devices');
        $this->load->model('listener_api/mdl_trip_master');
        $this->load->library('cart');
        $this->load->library('emailsend');
        $this->load->library('gps_utilities');
    }

    public function view_history($id)
    {

        $data['history'] = $this->mdl_trip_master->get_history($id);
        $data['vehicle_id'] = $id;

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
        $data['content'] = 'gps_history/view_history.php';

        $this->load->view('main/main.php', $data);

    }


    public function view_playback($id)
    {

        $vehicle_id = $id;
        $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
        $map_lat = '-4.0434771';
        $map_long = '39.6682065';
        $vehicle = $this->mdl_gps_history->get_vehicle($id);
        $name = $vehicle[0]->plate_no;

        //  $coords= json_encode($this->vehicle_history($vehicle_id));
        //  $coords = json_encode($this->vehicle_history_date($id,$start_date,$stop_date));
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
        $data['title'] = 'HAWK | Playback';
        $data['content_title'] = 'Playback (' . $name . ')';
        $data['content_subtitle'] = 'view vehicle playback for at most three hours.';
        $data['content'] = 'gps_history/view_playback.php';
        //     $data['coords'] = $coords;
        $data['vehicle'] = $vehicle;
        $data['vehicle_id'] = $vehicle_id;

        $this->load->view('main/main.php', $data);

    }

    function send_email()
    {
        $to = array('makaweys@gmail.com');
        $subj = 'test';
        $message = "ITMS Registration Successful. \n\n
                                Username : email \nPassword : pass";

        $this->emailsend->send_email_message($to, $subj, $message);
    }

    function getaddress($lat, $lng)
    {
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
        return $obj["results"][0]["formatted_address"];
    }

    public function history($id, $start_date, $stop_date)
    {

        $vehicle_id = $id;

        $arr = $this->vehicle_history_date($id, $start_date, $stop_date);

        $coords = json_encode($arr);
        $vehicle = json_encode($this->mdl_gps_history->get_vehicle($id));

        $map_center = sprintf("%f, %f", '-4.0434771', '39.6682065');
     
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
        $data['content'] = 'gps_history/view_history_road.php';
        $data['coords'] = $coords;
        $data['vehicle'] = $vehicle;
        $data['vehicle_id'] = $vehicle_id;

        $this->load->view('main/main.php', $data);

    }

    public function refresh_grid()
    {

        $query = $this->input->post('query');

        $vehicles = $this->mdl_gps_history->get_gps_vehicles($this->session->userdata('hawk_account_id'), $query);

        for ($i = 0; $i < sizeof($vehicles); $i++) {
            $vehicles[$i]->address = $this->gps_utilities->getaddress($vehicles[$i]->latitude, $vehicles[$i]->longitude);
        }

        $res = array('vehicles' => $vehicles);

        echo json_encode($vehicles);
    }

    public function vehicle_history($vehicle_id)
    {
        $result = $this->mdl_gps_history->get_vehicle_history($vehicle_id);
        $result = $this->get_distinct_routes($result);
        //print_r($result);
        return $result;
    }


    public function vehicle_history_date($id, $start_date, $stop_date)
    {
        //   $data = $this->input->post();
        //  $vehicle_id =  $data['vehicle_id'];
        // $start_date = $data['start_date'];
        //  $end_date = $data['end_date'];
        // $result = $this->mdl_gps_history->get_vehicle_history($id, $start_date, $end_date);
        //   print_r($this->get_distinct_routes($result));
        // $result = array("history"=>$result);
        // echo json_encode($result);
        //    echo "vehicle id $id start date ".str_replace("%20", " ",$start_date)." stop date $stop_date";
        $result = $this->mdl_gps_history->get_vehicle_history($id, str_replace("%20", " ", $start_date), str_replace("%20", " ", $stop_date));
        return $result;
    }

    public function vehicle_playback()
    {
        $data = $this->input->post();
        $vehicle_id = $data['vehicle_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        // $result = $this->mdl_gps_history->get_vehicle_history($id, $start_date, $end_date);
        //   print_r($this->get_distinct_routes($result));
        // $result = array("history"=>$result);
        // echo json_encode($result);
        //    echo "vehicle id $id start date ".str_replace("%20", " ",$start_date)." stop date $stop_date";
        $result = $this->mdl_gps_history->get_vehicle_history($vehicle_id, $start_date, $end_date);
        $result = array("history" => $result);
        echo json_encode($result);
    }

    private function get_distinct_routes($pathCoords)
    {
        $result = array();

        if (count($pathCoords) > 1) {
            array_push($result, $pathCoords[0]);
            $latitude = $pathCoords[0]->latitude;
            $longitude = $pathCoords[0]->longitude;
            for ($i = 1; $i < count($pathCoords); $i++) {
                if ($latitude != $pathCoords[$i]->latitude && $longitude != $pathCoords[$i]->longitude) {
                    array_push($result, $pathCoords[$i]);
                    $latitude = $pathCoords[$i]->latitude;
                    $longitude = $pathCoords[$i]->longitude;
                }
            }
        }
        //print_r($result);

        return $result;
    }

    public function history_track_points()
    {

        $data = $this->input->post();

        $data['account_id'] = $this->session->userdata('hawk_account_id');

        $history = $this->mdl_gps_history->fetch_vehicle_history($data);

        echo json_encode($history);
    }

    /* Get Trips */
    function get_history($vehicle_id, $start, $end)
    {
        if (($start != null && $end != null) || ($start != "null" && $end != "null")) {
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }

        if ($vehicle_id == "null") {
            $vehicle_id = null;
        }

        $res = $this->mdl_trip_master->get_history($vehicle_id, $start, $end);
        print_r(json_encode($res));
    }
}
