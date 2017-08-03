<?php

class Mdl_gps_history extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_gps_vehicles($account_id = null, $query = null) {
        $this->db->select('vehicles.*');
        $this->db->from('vehicles');
        $this->db->where('vehicles.device_id!=', 0);

        if ($account_id != null) {
            $this->db->where('vehicles.account_id', $account_id);
        }

        if ($query != null && $query != "") {
            $this->db->like('vehicles.model', $query, 'both');
        }

        $query = $this->db->get();

        return $query->result();
    }

    function get_vehicle_history($vehicle_id, $start_date=null, $stop_date=null)
    {
        date_default_timezone_set('Africa/Nairobi');
        $date   = time() - 60*60*1;
        $SQL = "select latitude,longitude from gps_track_points";


        if($start_date!= null)
        {

            $SQL = $SQL." where add_date >='" . $start_date."'";
        }
        else{

            $SQL =  $SQL." where add_date >='" . date("Y-m-d H:i:s",$date) . "' AND vehicle_id = '" . $vehicle_id."' ORDER BY add_date ASC " ;
            $query = $this->db->query($SQL);
            $result = $query->result();
            return     $result ;
        }

        if($stop_date != null)
        {
            $SQL = $SQL." AND add_date <='" . $stop_date."'";
        }

        $SQL = $SQL." AND vehicle_id = '" . $vehicle_id."' ORDER BY add_date ASC " ;
        $query = $this->db->query($SQL);
        return $query->result();
    }

    function get_vehicle($vehicle_id)
    {
        $SQL = "select * from vehicles where vehicle_id = $vehicle_id";
        $query = $this->db->query($SQL);
        return $query->result();
    }
}

?>
