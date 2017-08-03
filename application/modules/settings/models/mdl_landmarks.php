<?php

class Mdl_landmarks extends CI_Model {

    public function save_geofence_data($db_array) {
        $this->db->trans_start();
        $this->db->insert("geofence", $db_array);
        $this->db->trans_complete();

        return ($this->db->trans_status()) ? TRUE : FALSE;
    }

    public function save_landmark($db_array) {
        $this->db->trans_start();
        $db_array['name'] = $db_array["landmark_name"];
        $latitude = $db_array["latitude"];
        $longitude = $db_array["longitude"];
        $db_array["fill_color"] = $db_array["landmark_circle_color"];

        unset($db_array["landmark_name"]);
        unset($db_array["latitude"]);
        unset($db_array["longitude"]);
        unset($db_array["landmark_circle_color"]);

        $this->db->insert("geofence", $db_array);
        $insert_id = $this->db->insert_id();

        $this->db->insert("geofence_points", array("geofence_id" => $insert_id, "latitude" => $latitude, "longitude" => $longitude));
        $this->db->trans_complete();

        return ($this->db->trans_status()) ? TRUE : FALSE;
    }

    public function get_landmarks($company_id = null) {
        $whereCompany = '';

        if ($company_id != null) {
            $whereCompany = " AND lm.company_id = '" . $company_id . "' ";
        }

        $query = $this->db->query("SELECT lm.* FROM `itms_landmarks` lm 
        											where 1
        												{$whereCompany}
        												and lm.del_date is null and lm.status = 1", FALSE);

        return $query->result();
    }

    public function getAllCoord($company_id = null, $user_id = null) {
        $whereCompany = '';
        $whereUser = '';
        if ($company_id != null) {
            $whereCompany = " AND am.company_id = '" . $company_id . "' ";
        }

        if ($user_id != null) {
            $whereUser = " AND find_in_set(am.asset_id, (SELECT assets_ids FROM user_assets_map where user_id = " . $user . " )) ";
        }

        $qry = "SELECT am.asset_id, am.device_id, am.assets_name, lm.latitude, lm.longitude
				FROM itms_assets am left join itms_last_gps_point lm on lm.device_id = am.device_id 
				where am.status=1 AND am.del_date is null 
				{$whereCompany}
				{$whereUser}
				ORDER BY am.assets_name";
        //die($qry);
        $query = $this->db->query($qry);

        return $query->result_array();
    }

    public function getIconPaths() {
//        $company_id = $this->session->userdata('itms_company_id');
//        //$user=$this->session->userdata("company_id");
//        $qry = "select image_path from itms_landmark_images where status=1 and del_date is null and (company_id=1 or company_id='{$company_id}')";
//        $rarr = $this->db->query($qry);
//        return $rarr->result();
    }

    function getLandmarkGroups($company_id = null) {
        $whereCompany = '';
        if ($company_id != null) {
            $whereCompany = " AND company_id = '" . $company_id . "' ";
        }

        $SQL = "select landmark_group_id,landmark_group_name from itms_landmark_groups where 1 {$whereCompany}";
        $rarr = $this->db->query($SQL);
        return $rarr->result();
    }

    function getCountries() {
        $SQL = "SELECT id, name FROM mst_country";
        $query = $this->db->query($SQL);
        return $query->result_array();
    }

    function getCurrent($id) {
        if ($id != "") {
            $SQL = "SELECT country FROM tbl_users where user_id=" . $id;
            $query = $this->db->query($SQL);
            return $query->result_array();
        }
    }

    public function state() {
        $id = uri_assoc('id');
        $state = uri_assoc('state');
        if ($id == 0 OR $id == "") {
            echo "<option value='' >Select State</option>";
        } else {
            $query = "select id, name from mst_state where FK_mst_country_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
            $data = $this->db->query($query);

            echo "<option value='' >Select State</option>";

            foreach ($data->result() as $row) {
                if ($state != "" && $state == $row->id)
                    echo "<option value='" . $row->id . "' selected='selected' >" . $row->name . "</option>";
                else
                    echo "<option value='" . $row->id . "' >" . $row->name . "</option>";
            }
        }
    }

    public function city() {
        $id = uri_assoc('id');
        $city = uri_assoc('city');

        if ($id == 0 OR $id == "") {
            echo "<option value='' >Select City</option>";
        } else {
            $query = "select id, name from mst_city where FK_mst_state_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
            $data = $this->db->query($query);

            echo "<option value='' >Select City</option>";

            foreach ($data->result() as $row) {
                if ($city != "" && $city == $row->id)
                    echo "<option value='" . $row->id . "' selected='selected' >" . $row->name . "</option>";
                else
                    echo "<option value='" . $row->id . "' >" . $row->name . "</option>";
            }
        }
    }

    public function get_json() {
        $query = "select * from mst_country as cn left join mst_state as st on cn.id=st.FK_mst_country_p_id left join mst_city as ct on st.id=ct.FK_mst_state_p_id";
        $data = $this->db->query($query);
        return $data->result();
    }

    public function checkUserDuplicate($user, $id) {
        $qry = "select * from tbl_users where ";

        if ($id != "") {
            $qry.=" user_id!=" . $id . " AND ";
        }
        $qry.=" username='" . $user . "' AND status=1 and del_date is null";
        $rarr = $this->db->query($qry);
        if ($rarr->num_rows() < 1) {
            return true;
        } else {
            return false;
        }
    }

    public function prepare_assets() {
        $user = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->where('find_in_set(id, (select assets_ids from user_assets_map where user_id = ' . $user . '))');
        $this->db->where('status', 1);
        $this->db->where('del_date', null);
        $this->order_by = 'id';
        $query = $this->db->get('assests_master');
        return $query->result();
    }

    public function getAddressBookGroupList() {
        $user = $this->session->userdata('user_id');
        $this->db->select('id, group_name');
        $this->db->where('add_uid', $user);
        $this->db->where('status', 1);
        $this->db->where('del_date', null);
        $this->order_by = 'id';
        $query = $this->db->get('addressbook_group');
        return $query->result();
    }

    public function prepare_LandmarkGroups_1() {
        $user = $this->session->userdata('user_id');
        $this->db->select('id, landmark_group_name');
        $this->db->where('user_id', $user);
        $this->order_by = 'id';
        $query = $this->db->get('landmark_group');
        return $query->result();
    }

    public function getLandmarkList() {
        $user = $this->session->userdata('user_id');
        $this->db->select('id, name,address');
        //$this->db->where('user_id',$user);
        $this->db->where('add_uid', $user);
        $this->db->where('del_uid', NULL);
        $this->db->where('del_date', NULL);
        $this->order_by = 'id';
        $query = $this->db->get('landmark');
        return $query->result();
    }

    public function updateLandmarkRadius($landmarks, $radius, $distance_unit) {
        if (count($landmarks) > 0 && $radius != "" and $distance_unit != "") {
            $ids = implode(",", $landmarks);
            $user = $this->session->userdata('user_id');
            $qry = "Update landmark set radius=$radius,  distance_unit='$distance_unit' where id in($ids)";
            $rarr = $this->db->query($qry);
            return true;
        } else {
            return false;
        }
    }

    public function update_landmark($data) {

        $this->db->where('landmark_id', $data['landmark_id']);
        $query = $this->db->update('itms_landmarks', $data);

        if ($query) {
            echo 1;
        };
    }

}

?>
