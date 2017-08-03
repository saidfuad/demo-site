<?php

class Mdl_email_reports extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    

    function get_vehicle_summary($company_id, $vehicle_ids = null) {
        $this->db->select('itms_assets.*, itms_assets_categories.assets_category_id, itms_assets_categories.assets_cat_name,
                                itms_assets_categories.assets_cat_image, itms_assets_types.assets_type_id, 
                                    itms_assets_types.assets_type_nm, itms_personnel_master.personnel_id AS driver_id, 
                                        CONCAT(itms_personnel_master.fname, " ", itms_personnel_master.lname) AS driver_name, itms_personnel_master.phone_no AS driver_phone,
                                            itms_owner_master.owner_id, itms_owner_master.owner_name');
        $this->db->from('itms_assets')
                ->join('itms_assets_categories', 'itms_assets_categories.assets_category_id = itms_assets.assets_category_id', 'left')
                ->join('itms_assets_types', 'itms_assets_types.assets_type_id = itms_assets.assets_type_id', 'left')
                ->join('itms_personnel_master', 'itms_personnel_master.personnel_id = itms_assets.personnel_id', 'left')
                ->join('itms_owner_master', 'itms_owner_master.owner_id = itms_assets.owner_id', 'left');
        if (!is_null($vehicle_ids)) {
            $this->db->where_in('itms_assets.asset_id', $vehicle_ids);
        }
        $this->db->where('itms_assets.company_id', $company_id);

        $this->db->order_by('assets_name', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    function get_alerts($compnay_id, $vehicle_ids = null, $start_period = null, $stop_period = null) {
        $string_query = "SELECT count(*) as data_size,
            SUM(CASE WHEN alert_header = 'Overspeeding' THEN 1 ELSE 0 END) as overspeeding,
            SUM(CASE WHEN alert_header = 'Tyre Pressure' THEN 1 ELSE 0 END) as tyre_pressure,
            itms_assets.assets_name,fname,lname
            FROM 
            itms_alert_master
            LEFT JOIN itms_assets ON (itms_assets.asset_id = itms_alert_master.asset_id) 
            LEFT JOIN itms_personnel_master ON (itms_personnel_master.personnel_id = itms_alert_master.driver_id) 
            WHERE 
            itms_alert_master.del_date is null 
            AND itms_alert_master.company_id ='" . $compnay_id . "'";

        if (!empty($vehicle_ids)) {
            $string_query .= " AND itms_alert_master.asset_id IN ('" . $vehicle_ids . "')";
        }
        if (!empty($start_period) && !is_null($start_period)) {

            $string_query .= " AND itms_alert_master.add_date BETWEEN '" . $start_period . "' AND '" . $stop_period . "'";
        }
        $string_query .=" ORDER BY id DESC";
        $query = $this->db->query($string_query);
        $data = $query->result();
        return count($data) > 0 ? $data : null;
    }

    function get_dealers($dealer_ids = null, $company_id) {
        $this->db->select('dealer_id,dealer_name,dealer_in,phone_no,email,address');
        $this->db->from('itms_dealer_master')
                ->where('company_id', $company_id);
        if (!empty($dealer_ids)) {
            $dealer_ids = implode(',', $dealer_ids);
            $this->db->where_in('dealer_id', $dealer_ids);
        }

        $this->db->order_by('dealer_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_distances($company_id, $vehicle_ids = null, $start_period = null, $end_period = null) {
        $this->db->select('ia.assets_name,iom.owner_name,idm.first_reading,'
                . 'idm.current_reading,idm.distance,idm.running_time,'
                . 'idm.fuel_used,idm.fuel_filled');
        $this->db->from('itms_assets ia')
                ->join('itms_owner_master iom', 'ia.owner_id = iom.owner_id')
                ->join('itms_distance_master idm', 'ia.asset_id = idm.assets_id')
                ->where('idm.add_date between "' . $start_period . '" and "' . $end_period . '"')
                ->where('ia.company_id', $company_id);
        if (!empty($vehicle_ids)) {
            $vehicle_ids = implode(',', $vehicle_ids);
            $this->db->where_in('ia.asset_id', $vehicle_ids);
        }

        $this->db->order_by('ia.assets_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_personnel($company_id, $role_ids = null) {
        $this->db->select('ipm.fname,ipm.lname,ir.role_name,ipm.id_no,ipm.gender,ipm.phone_no,ipm.email,ipm.status');
        $this->db->from('itms_personnel_master ipm')
                ->join('itms_roles ir', 'ir.role_id = ipm.role_id')
                ->where('ipm.company_id', $company_id);
        if (!empty($role_ids)) {
            $role_ids = implode(',', $role_ids);
            $this->db->where_in('ir.role_id', $role_ids);
        }

        $this->db->order_by('ipm.fname', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_owners($owner_ids = null, $company_id) {
        $this->db->select('owner_id,owner_name,phone_no,email,address,status');
        $this->db->from('itms_owner_master')
                ->where('company_id', $company_id);
        if (!empty($owner_ids)) {
            $owner_ids = implode(',', $owner_ids);
            $this->db->where_in('owner_id', $owner_ids);
        }

        $this->db->order_by('owner_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_assets($owner_ids = null, $company_id) {
        $this->db->select('ia.owner_id,ia.assets_name,ia.add_date,iat.assets_type_nm as asset_type,ia.status');
        $this->db->from('itms_assets ia')
                ->join('itms_assets_types iat', 'iat.assets_type_id = ia.assets_type_id')
                ->where('ia.company_id', $company_id);
        if (!empty($owner_ids)) {
            $owner_ids = implode(',', $owner_ids);
            $this->db->where_in('ia.owner_id', $owner_ids);
        }

        $this->db->order_by('ia.assets_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_assets_name($asset_id) {
        $this->db->select('assets_name')
                ->from('itms_assets')
                ->where('company_id', $this->session->userdata('itms_company_id'))
                ->where('del_date is null')
                ->where('asset_id', $asset_id);

        $query = $this->db->get();
        $data = $query->row_array();
        return $data['assets_name'];
    }

    function get_ignition_data($company_id, $vehicle_ids, $driver_ids, $start_period, $end_period) {
        $this->db->select('a.ignition,unix_timestamp(a.add_date) as time')
                ->from('itms_gps_track_points a')
                ->where('a.company_id', $company_id);
        if (!empty($driver_ids)) {
            //$driver_ids = implode(',', $driver_ids);
            $this->db->where_in('a.driver_id', $driver_ids);
        }
        if (!empty($vehicle_ids)) {
            //$vehicle_ids = implode(',', $vehicle_ids);
            $this->db->where_in('a.asset_id', $vehicle_ids);
        }
        if (!empty($start_period) && !empty($end_period)) {
            $this->db->where('a.add_date between "' . date('Y-m-d', strtotime($start_period)) . '" and "' . date('Y-m-d', strtotime($end_period)) . '"');
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_overspeeds($company_id, $vehicle_ids = null, $driver_ids = null, $start_period = null, $end_period = null) {
        $this->db->select('iol.add_date,iol.add_time,ia.assets_friendly_nm,iol.max_speed_limit,iol.speed,ipm.fname,ipm.lname,iol.address')
                ->from('itms_overspeed_log iol')
                ->join('itms_assets ia', 'ia.asset_id = iol.asset_id')
                ->join('itms_personnel_master ipm', 'ipm.personnel_id = iol.driver_id');
        if (!empty($driver_ids)) {
            $driver_ids = implode(',', $driver_ids);
            $this->db->where_in('iol.driver_id', $driver_ids);
        }
        if (!empty($vehicle_ids)) {
            $vehicle_ids = implode(',', $vehicle_ids);
            $this->db->where_in('ia.asset_id', $vehicle_ids);
        }
        if (!empty($start_period) && !empty($end_period)) {
            $this->db->where('iol.add_date between "' . date('Y-m-d', strtotime($start_period)) . '" and "' . date('Y-m-d', strtotime($end_period)) . '"');
        }
        $this->db->where('iol.company_id', $company_id);
        $query = $this->db->get();
        return $query->result();
    }

}
