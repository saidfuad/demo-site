<?php

class Mdl_trip_master extends CI_Model
{
    private $latitude;
    private $longitude;
    private $tracking_time;
    private $vehicle_id = 0;
    private $status = 0;
    private $address = "";

    function __construct()
    {
        parent::__construct();
        $this->load->library('gps_utilities');
    }

    public function get_history($vehicle_id = null, $start = null, $stop = null)
    {
        if (!is_null($vehicle_id)) {
            $this->db->where('tm.vehicle_id', $vehicle_id);
        }

        if ($start != null || $stop != null) {
            $this->db->where('date(start_time) >= "' . $start . '"');
            $this->db->where('date(stop_time) <= "' . $stop . '"');
        } else {
            $this->db->where('start_time BETWEEN SYSDATE() - INTERVAL 14 DAY AND SYSDATE()');
        }

        $this->db->select('plate_no, start_address,  DATE_FORMAT(start_time, "%d-%m-%Y %h:%i:%s %p") as start_time, 
         DATE_FORMAT(stop_time, "%d-%m-%Y %h:%i:%s %p") as stop_time, distance, tm.vehicle_id, stop_address')
            ->from('trip_master tm')
            ->join('vehicles v', 'tm.vehicle_id = v.vehicle_id')
            ->order_by('tm.start_time', 'desc');

        $query = $this->db->get();
        return $query->result();
    }

    public function Initialize($vehicle_id, $status, $speed, $lat, $lng, $tracking)
    {
        $this->vehicle_id = $vehicle_id;
        $this->status = $status;
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->tracking_time = $tracking;

        $result = $this->CheckStart();
        
         echo "Initialize($vehicle_id, $status, $speed, $lat, $lng, $tracking)".PHP_EOL;

        if ($status == 1 && $result == NULL && $speed > 5) {
            $this->address = $this->gps_utilities->getaddress($this->latitude, $this->longitude);
            $this->StartTrip();
        }

        if ($status == 0 && $result != NULL) {
            $this->address = $this->gps_utilities->getaddress($this->latitude, $this->longitude);
            $this->StopTrip($result);
        }
    }


    function get_last_seen($vehicle_id)
    {
          $this->db->select("last_seen")
                ->from("vehicles")
                ->where("vehicle_id", $vehicle_id)
               ->limit(1);
        $query = $this->db->get();
       return $query->row_array();
    }


    private function CheckStart()
    {
        $this->db->select("count(*) as count,trip_id, start_time")
            ->from("trip_master")
            ->where("vehicle_id", $this->vehicle_id)
            ->where("status", 1);

        $query = $this->db->get();
        $data = $query->row_array();
        return ($data['count'] > 0) ? $data : NULL;
    }

    private function StartTrip()
    {
        $last_seen= $this->get_last_seen($this->vehicle_id)['last_seen'];
        echo "StartTrip(): ".$this->tracking_time."Last Seen $last_seen tracking_time ".$this->tracking_time.PHP_EOL;
        if($last_seen <= $this->tracking_time)
        {
            $this->db->insert("trip_master", array("vehicle_id" => $this->vehicle_id, "start_latitude" => $this->latitude, "start_longitude" => $this->longitude, "start_address" => $this->address, "start_time" => $this->tracking_time));
        }
    }

    private function StopTrip($data)
    {
        echo "StopTrip: ".$data['start_time']." < " .$data['start_time'].PHP_EOL;
        if($data['start_time'] < $this->tracking_time){
            $distance = $this->CalculateDistance($data['start_time'], $this->tracking_time);
            $update_data = array("stop_latitude" => $this->latitude, "stop_longitude" => $this->longitude, "status" => 0, "distance" => $distance, "stop_time" => $this->tracking_time, "stop_address" => $this->address);
            $this->db->where("trip_id", $data['trip_id'])
                ->update("trip_master", $update_data);
        }
    }

    private function CalculateDistance($start, $stop)
    {
        $this->db->select('SUM(distance) as distance')
            ->from('gps_track_points')
            ->where('vehicle_id', $this->vehicle_id)
            ->where('add_date >= "' . $start . '"')
            ->where('add_date <= "' . $stop . '"');
        $query = $this->db->get();
        return $query->row_array()['distance'];
    }
}

?>