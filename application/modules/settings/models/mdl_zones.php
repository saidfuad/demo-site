<?php

class Mdl_zones extends CI_Model {

    function get_zones($company_id = null) {
        if ($company_id != null) {
            $this->db->where('company_id', $company_id);
        }

        $this->db->where('status', 1);
        $this->db->where('del_date', NULL);

        $query = $this->db->get('itms_zones');

        return $query->result();
    }

    function get_vertices($company_id = null) {
        if ($company_id != null) {
            $this->db->where('company_id', $company_id);
        }

        $query = $this->db->get('itms_zones_vertices');

        return $query->result();
    }

    public function save_geofence($data, $vertices) {
        $data['name'] = $data['zone_name'];
        $data['type'] = 'geofence';
        $data['status'] = 1;
        $data['fill_color'] = $data['zone_color'];
        unset($data['zone_name']);
        unset($data['zone_color']);

        $this->db->trans_start();
        $this->db->insert('geofence', $data);
        $geofence_id = $this->db->insert_id();
        $this->save_geofence_vertices($geofence_id, $vertices);
        $this->db->trans_complete();

        return ($this->db->trans_status()) ? TRUE : FALSE;
    }

    public function save_geofence_vertices($geofence_id, $vertices) {
        $cordinates = array();
        foreach ($vertices as $key => $vertex) {
            $vertex = str_replace("(", "", $vertex);
            $vertex = str_replace(")", "", $vertex);

            $data = array();
            $cords = explode(',', $vertex);
            $data['geofence_id'] = $geofence_id;
            $data['latitude'] = $cords[0];
            $data['longitude'] = $cords[1];

            array_push($cordinates, $data);
        }

        $values = '';
        
        //close loop for polygon
        array_push($cordinates, array('geofence_id'=>$cordinates[0]['geofence_id'],'latitude'=>$cordinates[0]['latitude'],'longitude'=>$cordinates[0]['longitude']));
        
        foreach ($cordinates as $key => $cords) {
            $values [] = "('" . $cords['geofence_id'] . "', '" . $cords['latitude'] . "', '" . $cords['longitude'] . "')";
        }

        $values = implode(',', $values);

        $res = $this->db->query("INSERT INTO geofence_points (geofence_id, latitude, longitude) VALUES $values");

        return $res;
    }

    public function update_zone($data) {

        $this->db->where('zone_id', $data['zone_id']);
        $query = $this->db->update('itms_zones', $data);

        if ($query) {
            echo 1;
        };
    }

}

?>
