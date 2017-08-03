<?php 

class Mdl_reports extends CI_Model{


	public function fetch_general_reports($account_id, $start, $end, $plate, $sum){

        $this->db->select('
        ROUND((SUM(gps_track_points.distance))/1000) as total_mileage,
        MAX(gps_track_points.speed) as max_speed,
        ROUND((SUM(CASE WHEN gps_track_points.ignition = 1 THEN 1 ELSE 0 END))*10/3600) as ignition_on,
        ROUND((SUM(CASE WHEN gps_track_points.ignition = 0 THEN 1 ELSE 0 END))*10/3600) as ignition_off,
        DATE_FORMAT(DATE(gps_track_points.add_date),"%d-%m-%Y") as add_date,
        gps_track_points.speed,
        gps_track_points.vehicle_id,
        vehicles.plate_no,
        vehicles.model');

        $this->db->from('gps_track_points');
        $this->db->join('vehicles', 'vehicles.vehicle_id = gps_track_points.vehicle_id', 'left');
        $this->db->where('gps_track_points.account_id', $account_id);

        if($start != null && $end != null){

            $this->db->where('date(gps_track_points.add_date) BETWEEN "'.$start.'" AND "'.$end.'"');

        }else{

            $this->db->where('gps_track_points.add_date BETWEEN SYSDATE() - INTERVAL 14 DAY AND SYSDATE()');

        }

        if($plate != null){
            $this->db->where('vehicles.plate_no', $plate);
        }

        if($sum){

            $this->db->select('CONCAT(MIN(DATE_FORMAT(DATE(gps_track_points.add_date),"%d/%m/%Y")), " <b>to</b> ", MAX(DATE_FORMAT(DATE(gps_track_points.add_date),"%d/%m/%Y"))) as add_date');
            $this->db->group_by('gps_track_points.vehicle_id');

        }else{

            $this->db->group_by('gps_track_points.vehicle_id');
            $this->db->group_by('date(gps_track_points.add_date)');
        }

        $this->db->order_by('add_date', 'desc');
        $query = $this->db->get()->result();
        return $query;

    }

    public function fetch_mileage_reports($account_id, $start, $end, $plate, $sum){

        $this->db->select('ROUND((SUM(gps_track_points.distance))/1000) as total_mileage,
         DATE_FORMAT(DATE(gps_track_points.add_date),"%d-%m-%Y") as add_date, gps_track_points.vehicle_id, vehicles.plate_no, vehicles.model');

        $this->db->from('gps_track_points');
        $this->db->join('vehicles', 'vehicles.vehicle_id = gps_track_points.vehicle_id', 'left');
        $this->db->where('gps_track_points.account_id', $account_id);

        if($start != null && $end != null){

            $this->db->where('date(gps_track_points.add_date) BETWEEN "'.$start.'" AND "'.$end.'"');

        }else{

            $this->db->where('gps_track_points.add_date BETWEEN SYSDATE() - INTERVAL 14 DAY AND SYSDATE()');

        }

        if($plate != null){
            $this->db->where('vehicles.plate_no', $plate);
        }

        if($sum){

            $this->db->select('CONCAT(MIN(DATE_FORMAT(DATE(gps_track_points.add_date),"%d/%m/%Y")), " <b>to</b> ", MAX(DATE_FORMAT(DATE(gps_track_points.add_date),"%d/%m/%Y"))) as add_date');
            $this->db->group_by('gps_track_points.vehicle_id');

        }else{

            $this->db->group_by('gps_track_points.vehicle_id');
            $this->db->group_by('date(gps_track_points.add_date)');
        }

        $this->db->order_by('add_date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_alert_reports($account_id, $start, $end, $plate){

        $this->db->select('
        vehicles.plate_no,
        vehicles.model,
        alerts.vehicle_id,
        alert_types.name as alert_type,
        DATE_FORMAT(DATE(alerts.start_date),"%d-%m-%Y") as alert_date,
        alerts.start_address as alert_location,
        alerts.status as alert_status,
        geofence.type as geofence_type');

        $this->db->from('alerts');
        $this->db->join('vehicles', 'vehicles.vehicle_id = alerts.vehicle_id', 'left');
        $this->db->join('alert_types', 'alert_types.alert_type_id = alerts.alert_type_id', 'left');
        $this->db->join('geofence', 'geofence.id = alerts.geofence_id', 'left');
        $this->db->where('alerts.account_id', $account_id);

        if($start != null && $end != null){

            $this->db->where('date(alerts.start_date) BETWEEN "'.$start.'" AND "'.$end.'"');

        }else{

            $this->db->where('alerts.start_date BETWEEN SYSDATE() - INTERVAL 14 DAY AND SYSDATE()');

        }

        if($plate != null){
            $this->db->where('vehicles.plate_no', $plate);
        }

        $this->db->group_by('alerts.vehicle_id');
        $this->db->group_by('alerts.alert_id');
        $this->db->group_by('date(alerts.start_date)');
        $this->db->order_by('alerts.start_date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_purchase_reports($account_id, $start, $end, $plate, $product, $sum){

        $this->db->select('
        DATE_FORMAT(DATE(orders.order_date),"%d-%m-%Y") as order_date,
        SUM(order_details.total_price) as total_price,
        order_details.vehicle_id,
        order_details.quantity,
        vehicles.plate_no,
        vehicles.model,
        products.product_name,
        products.product_id');
        $this->db->from('orders');
        $this->db->join('order_details', 'order_details.order_id = orders.order_id', 'left');
        $this->db->join('vehicles', 'vehicles.vehicle_id = order_details.vehicle_id', 'left');
        $this->db->join('products', 'products.product_id = order_details.product_id', 'left');
        $this->db->where('orders.account_id', $account_id);

        if($start != null && $end != null){

            $this->db->where('date(orders.order_date) BETWEEN "'.$start.'" AND "'.$end.'"');

        }else{

            $this->db->where('orders.order_date BETWEEN SYSDATE() - INTERVAL 14 DAY AND SYSDATE()');

        }

        if($plate != null){
            $this->db->where('vehicles.plate_no', $plate);
        }

        if($product != null){
            $this->db->where('products.product_id', $product);
        }

        if($sum){

            $this->db->select('CONCAT(MIN(DATE_FORMAT(DATE(orders.order_date),"%d/%m/%Y")), " <b>to</b> ", MAX(DATE_FORMAT(DATE(orders.order_date),"%d/%m/%Y"))) as order_date');
            $this->db->group_by('products.product_id');

        }else{
            $this->db->group_by('vehicles.plate_no', $plate);
        }

        $this->db->order_by('orders.order_date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function get_vehicles($hawk_account_id = null) {
        $this->db->where('account_id', $hawk_account_id);
        $this->db->order_by('plate_no', 'ASC');
        return $this->db->get('vehicles')->result();
    }

    function get_products(){
        return $this->db->get('products')->result();
    }
				
}

?>
