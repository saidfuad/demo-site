<?php

class Mdl_routes extends CI_Model {

    function get_routes($company_id = null) {
        if ($company_id != null) {
            $this->db->where('company_id', $company_id);
        }

        $this->db->where('status', 1);
        $this->db->where('del_date', NULL);

        $query = $this->db->get('itms_routes');

        return $query->result();
    }

    public function save_route($data) {
        $this->db->insert('geofence', $data);
        $res = $this->db->insert_id();

        return $res;
    }

    public function save_waypoints($data) {

        if ($this->db->insert_batch('itms_routes_waypoints', $data)) {
            $res = true;
        } else {
            $res = false;
        }

        return $res;
    }

    public function set_route_waypoints($route_id) {

        $this->db->where('route_id', $route_id);
        $query = $this->db->update('itms_routes', array('waypoints' => 1));

        return $query;
    }

    public function save_routepoints($data) {

        if ($this->db->insert_batch('itms_routes_points', $data)) {
            $res = true;
        } else {
            $res = false;
        }

        return $res;
    }

    public function set_route_points($route_id) {

        $this->db->where('route_id', $route_id);
        $query = $this->db->update('itms_routes', array('points' => 1));

        return $query;
    }

    public function update_route($data) {

        $this->db->where('route_id', $data['route_id']);
        $query = $this->db->update('itms_routes', $data);

        if ($query) {
            echo 1;
        };
    }

}

?>
