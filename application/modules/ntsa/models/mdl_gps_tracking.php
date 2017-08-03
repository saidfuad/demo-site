<?php

class Mdl_gps_tracking extends CI_Model {

    var $user;
    var $current_time;

    function __construct() {
        parent::__construct();

        $this->user_id = $this->session->userdata('itms_user_id');
        $this->current_time = date("Y-m-d H:i:s");
    }

    function get_gps_vehicles($account_id) {
        $this->db->select('vehicles.*');
        $this->db->from('vehicles');
        $this->db->where('vehicles.device_id !=', 0);

        if($account_id != null){
            $this->db->where('vehicles.account_id', $account_id);
        }

        $query = $this->db->get();

        return $query->result();
    }

    function count_moving_vehicles() {
        $this->db->select('vehicles.vehicle_id');
        $this->db->from('vehicles');
        $this->db->where('vehicles.device_id!=', 0);
        $this->db->where('vehicles.speed !=', 0);
        $this->db->where('vehicles.ignition', 1);

        $query = $this->db->get();

        return $query->num_rows();
    }

    function count_idle_vehicles() {
        $this->db->select('vehicles.vehicle_id');
        $this->db->from('vehicles');
        $this->db->where('vehicles.device_id!=', 0);
        $this->db->where('vehicles.speed', 0);
        $this->db->where('vehicles.ignition', 1);

        $query = $this->db->get();

        return $query->num_rows();
    }

    function count_parked_vehicles() {
        $this->db->select('vehicles.vehicle_id');
        $this->db->from('vehicles');
        $this->db->where('vehicles.device_id!=', 0);
        $this->db->where('vehicles.speed', 0);
        $this->db->where('vehicles.ignition', 0);

        $query = $this->db->get();

        return $query->num_rows();
    }

    function count_alert_vehicles() {
        $this->db->select('vehicles.vehicle_id');
        $this->db->from('vehicles');
        $this->db->where('vehicles.device_id!=', 0);
        $this->db->where('vehicles.speed_alert', 1);
        $this->db->or_where('vehicles.arm_alert', 1);
        $this->db->or_where('vehicles.power_cut', 1);

        $query = $this->db->get();

        return $query->num_rows();
    }

    function count_disabled_vehicles() {
        $this->db->select('vehicles.vehicle_id');
        $this->db->from('vehicles');
        $this->db->where('vehicles.active_status', 0);

        $query = $this->db->get();

        return $query->num_rows();
    }

    function fetch_vehicle_history($data) {

		$this->db->select('vehicles.plate_no, vehicles.model, gps_track_points.*');
		$this->db->from('gps_track_points');
		$this->db->join('vehicles', 'vehicles.vehicle_id = gps_track_points.vehicle_id');

		$this->db->where('vehicles.account_id', $data['account_id']);
        //$this->db->where('gps_track_points.add_date >= DATE_SUB(NOW(), INTERVAL 24 HOUR)');
        //$this->db->where('gps_track_points.add_date < (DATE_SUB(NOW(), INTERVAL 1 HOUR))');
        $this->db->where('gps_track_points.add_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)');

        $query = $this->db->get();

        return $query->result();
    }

    function get_zones($account_id = null) {
        if ($account_id != null) {
            $this->db->where('account_id', $account_id);
        }

        $this->db->where('status', 1);
        $this->db->where('del_date', NULL);

        $query = $this->db->get('itms_zones');

        return $query->result_array();
    }

    function get_vertices($account_id = null) {
        if ($account_id != null) {
            $this->db->where('account_id', $account_id);
        }

        $query = $this->db->get('itms_zones_vertices');

        return $query->result();
    }

    function get_current_trip_points($device_id, $trip_id) {
        $this->db->select('*');
        $this->db->from('itms_gps_track_points');
        $this->db->where('device_id', $device_id);

        if ($trip_id != null || $trip_id != "") {
            $this->db->where('trip_id', $trip_id);
        } else {
            //$this->db->where('DATE(fixture_date) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)');
            $this->db->where('add_date >= DATE_SUB(NOW(), INTERVAL 24 HOUR)');
        }

        $query = $this->db->get();

        return $query->result();
    }

    function get_vehicle_history($vehicle_id)
    {
        date_default_timezone_set('Africa/Nairobi');
        $date   = time() - 60*60*10;
          $SQL = "select DISTINCT latitude,longitude from gps_track_points where add_date >='" . date("Y-m-d H:i:s",$date) . "' AND vehicle_id = '" . $vehicle_id."'" ;
          $query = $this->db->query($SQL);

        return $query->result();
    }

    function get_route_by_id($route_id) {
        $this->db->select('*');
        $this->db->from('itms_routes');
        $this->db->where('route_id', $route_id);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_devices($account_id = null, $user_id = null) {
        $this->db->where('vehicles.status', 1);
        $this->db->where('vehicles.del_date', null);
        if ($account_id != null) {
            $this->db->where('vehicles.account_id', $account_id);
        }

        $whereUser = '';

        if ($user_id != null) {
            $whereUser = " AND find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)) ";
        }

        $query = $this->db->query("select vehicle_id, device_id, model
                                    from vehicles {$whereUser}");

        return $query->result();
    }

    public function get_vehicles_status($account_id, $user_assets = null) {

        if ($account_id != null) {
            $this->db->where('itms_alert_master.account_id', $account_id);
        }

        $current_user_id = $this->session->userdata('itms_userid');

        //echo $current_user_id;
        //exit;

        $whereUser = '';
        /*
          if ($us{$whereUser}er_id!=null) {
          $whereUser = " AND find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)) ";
          }
         */

        $query = $this->db->query("SELECT vehicles.*,vehicles.assets_name, vehicles.model,
                                                    itms_personnel_master.fname, itms_personnel_master.lname,itms_personnel_master.phone_no
                                    FROM
                                        vehicles
                                    LEFT JOIN vehicles LEFT JOIN itms_personnel_master ON (vehicles.personnel_id=itms_personnel_master.personnel_id) ON (vehicles.device_id=vehicles.device_id)
                                    WHERE
                                        FIND_IN_SET(vehicles.vehicle_id, (select group_concat(vehicle_id) from itms_alerts_contacts where user_id='$current_user_id'))
                                    OR
                                        FIND_IN_SET(vehicles.vehicle_id, (select group_concat(vehicle_id) from vehicles where add_uid='$current_user_id'))
                                    ORDER BY
                                        id DESC");

        return $query->result();
    }

    function get_vehicles_owners($account_id = null) {
        $this->db->select('itms_owner_master.*');
        $this->db->from('itms_owner_master');

        if ($account_id != null) {
            $this->db->where('itms_owner_master.account_id', $account_id);
        }

        $this->db->order_by('owner_name', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    public function getAutoRefreshSettings() {
        $user_id = $this->session->userdata('itms_user_id');

        $SQL = "select auto_refresh_setting from itms_users where user_id = '" . $user_id . "'";
        $query = $this->db->query($SQL);
        return $query->row();
    }

    public function getOnscreenAlertSettings() {
        $user_id = $this->session->userdata('itms_user_id');

        $SQL = "select onscreen_alert from itms_users where user_id = '" . $user_id . "'";
        $query = $this->db->query($SQL);
        return $query->row();
    }

    function get_map_display_routes($account_id = null) {
        if ($account_id != null) {
            $this->db->where('account_id', $account_id);
        }

        $this->db->select('*');
        $this->db->from('itms_routes');
        $query = $this->db->get();

        return $query->result();
    }

    function get_trips($vehicle_id = null, $account_id = null) {

        $this->db->select("itms_trips.*, vehicles.model, vehicles.assets_name, vehicles.device_id,
            (itms_personnel_master.fname + ' ' +itms_personnel_master.lname) AS driver_name ,itms_personnel_master.phone_no AS driver_phone, itms_client_master.client_name as client");
        $this->db->from('itms_trips');
        $this->db->join('vehicles', 'vehicles.vehicle_id=itms_trips.vehicle_id');
        $this->db->join('itms_personnel_master', 'itms_personnel_master.personnel_id=itms_trips.driver_id', 'left');
        $this->db->join('itms_client_master', 'itms_client_master.client_id=itms_trips.client_id', 'left');

        if ($account_id != null) {
            $this->db->where('itms_trips.account_id', $account_id);
        }

        if ($vehicle_id != null) {
            $this->db->where('itms_trips.vehicle_id', $vehicle_id);
        }

        $this->db->order_by('itms_trips.trip_id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    function get_personnel($account_id = null, $role_id = null, $user_id = null) {
        $whereCompany = '';
        $whereUser = "";
        $whereRole = "";
        if ($account_id != null) {
            $whereCompany = " AND itms_personnel_master.account_id = '" . $account_id . "' ";
        }

        if ($user_id != null) {
            $whereUser = " AND itms_personnel_master.add_uid = '" . $user_id . "' ";
        }

        if ($role_id != null) {
            $whereRole = " AND itms_personnel_master.role_id = '" . $role_id . "' ";
        }

        $SQL = "SELECT itms_personnel_master.*, itms_roles.role_name from itms_personnel_master
                INNER JOIN
                    itms_roles ON(itms_personnel_master.role_id = itms_roles.role_id)
                    WHERE 1
                        {$whereCompany}
                        {$whereUser}
                        {$whereRole}";

        $rarr = $this->db->query($SQL);
        return $rarr->result();
    }

    function get_all_devices($account_id = null) {

        $this->db->select('itms_devices.*');
        $this->db->from('itms_devices');
        if ($account_id != null) {
            $this->db->where('itms_devices.account_id', $account_id);
        }

        $this->db->order_by('device_id', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    function get_all_assets($account_id = null) {

        $this->db->select('vehicles.*');
        $this->db->from('vehicles');
        if ($account_id != null) {
            $this->db->where('vehicles.account_id', $account_id);
        }

        $this->db->order_by('assets_name', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    function s_vehicle() {
        $query = $this->db->get('itms_landmarks');

        return $query->result();
    }

    public function getroute($keyword) {
        $this->db->select('itms_routes.route_name');
        $this->db->order_by('route_id', 'DESC');
        $this->db->like("route_name", $keyword);
        $this->db->where('account_id', $this->session->userdata('itms_account_id'));
        // $this->db->like();
        return $this->db->get('itms_routes')->result_array();
    }

    public function get_device() {
        $assigned = 0;
        $this->db->where('assigned', $assigned);
        $query = $this->db->get('itms_devices');
        return $query->result();
    }

    public function get_vehicle() {
        $this->db->where('device_id IS NULL');
        $query = $this->db->get('vehicles');
        return $query->result();
    }

    public function get_geofence_data($account_id) {
        $this->db->select("id as geofence_id,name,radius,fill_color,address,type,geofence.status,address,first_name,last_name")
                ->from("geofence")
                ->join("users", "users.user_id = geofence.add_uid")
                ->where("geofence.account_id", $account_id);

        $query = $this->db->get();
        return $query->result();
    }

    function get_geofences($account_id){
        $this->db->select("id as geofence_id,name,radius,fill_color,address,type,geofence.status,address,first_name,last_name")
                ->from("geofence")
                ->join("users", "users.user_id = geofence.add_uid")
                ->where("geofence.account_id", $account_id)
                ->where("type","geofence")
                ->order_by("geofence.status", "desc");

        $query = $this->db->get();
        return $query->result();
    }

    function get_routes($account_id){
        $this->db->select("id as geofence_id,name,radius,fill_color,address,type,geofence.status,address,first_name,last_name")
                ->from("geofence")
                ->join("users", "users.user_id = geofence.add_uid")
                ->where("geofence.account_id", $account_id)
                ->where("type","route")
                ->order_by("geofence.status", "desc");;

        $query = $this->db->get();
        return $query->result();
    }

    function get_landmarks($account_id){
        $this->db->select("id as geofence_id,name,radius,fill_color,address,type,geofence.status,address,first_name,last_name")
                ->from("geofence")
                ->join("users", "users.user_id = geofence.add_uid")
                ->where("geofence.account_id", $account_id)
                ->where("type","landmark")
                ->order_by("geofence.status", "desc");

        $query = $this->db->get();
        return $query->result();
    }

    function get_geo_data_by_id($geofence_id) {
        $this->db->select("id,name,status,fill_color")
                ->from("geofence")
                ->where("geofence.id", $geofence_id);

        $query = $this->db->get();
        return $query->result_array();
    }

    function edit_save_geo_data($geofence_id, $data) {
        $data['update_uid'] = $this->session->userdata("hawk_account_id");
        $data['update_date'] = date("Y-m-d H:i:s");
        $this->db->where("id", $geofence_id);
        return $this->db->update("geofence", $data);
    }
}

?>
