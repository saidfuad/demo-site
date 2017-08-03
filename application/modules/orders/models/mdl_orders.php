<?php

class Mdl_orders extends CI_Model {

    function __construct() {
        parent::__construct();

    }

    function fetch_orders($data){

        $this->db->select('orders.*, SUM(order_details.total_price) as total_price, billing_details.*');
        $this->db->from('orders');
        $this->db->join('order_details', 'order_details.order_id = orders.order_id', 'left');
        $this->db->join('billing_details', 'billing_details.billing_details_id = orders.billing_details_id', 'left');
        $this->db->group_by('order_details.order_id');

        $query = $this->db->get();

        return $query->result();
    }

    function fetch_order($data){

        $this->db->select('orders.*, order_details.*, products.product_type, vehicles.plate_no');
        $this->db->from('orders');
        $this->db->join('order_details', 'order_details.order_id = orders.order_id', 'right');
        $this->db->join('products', 'products.product_id = order_details.product_id', 'right');
        $this->db->join('vehicles', 'vehicles.vehicle_id = order_details.vehicle_id', 'left');
        $this->db->where('orders.order_id', $data);

        $query = $this->db->get();

        return $query->result();
    }

    function fetch_products($hawk_account_id){

        $this->db->select('products.*');
        $this->db->from('products');
        //$this->db->where('product_type', 'Device');

        $query = $this->db->get();

        return $query->result();
    }

    function fetch_assigned_vehicles($hawk_account_id){

        $this->db->select('vehicles.*');
        $this->db->from('vehicles');

        $this->db->where('device_id !=', null);
        $this->db->where('device_id !=', 0);
        $this->db->where('account_id', $hawk_account_id);

        $query = $this->db->get();

        return $query->result();

    }

    function insert_order($order){

        $this->db->insert('orders', $order);
        $id = $this->db->insert_id();
        return (isset($id)) ? $id : FALSE;
    }

    function insert_order_detail($order_detail){
        $order_detail['total_price'] = $order_detail['quantity'] * $order_detail['total_price'];
        $this->db->insert('order_details', $order_detail);
    }

    function user_email($user_id){

        $this->db->select('logins.email');
        $this->db->from('logins');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        return $query->row_array();
    }

}

?>
