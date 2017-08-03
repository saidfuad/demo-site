<?php

class Mdl_purchase extends CI_Model {

    function __construct() {
        parent::__construct();

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

    function fetch_installation_types(){
        $this->db->select('installation_types.*');
        $this->db->from('installation_types');

        $query = $this->db->get();

        return $query->result();
    }

    function fetch_locations(){

        $this->db->select('locations.*');
        $this->db->from('locations');

        $query = $this->db->get();

        return $query->result();
    }

     function fetch_location($id){

        $this->db->select('locations.*');
        $this->db->from('locations');
        $this->db->where('location_id',$id);

        $query = $this->db->get()->row_array();

        if($query['mobile_operator']==1){
            $this->db->select('installation_types.*');
            $this->db->from('installation_types');

            $query = $this->db->get();

            return $query->result();
        }else{
            $this->db->select('installation_types.*');
            $this->db->from('installation_types');
            $this->db->where('type_name !=',"Personal Delivery");

            $query = $this->db->get();

            return $query->result();

        }


    }

   function fetch_order($account_id){

       $this->db->select('checkout_pending.*');
       $this->db->from('checkout_pending');
       $this->db->where('account_id',$account_id);
       $this->db->where('status',0);

       $queri = $this->db->get()->row_array();

       if(!empty($queri)){
           $this->db->select('mpesa_transactions.*');
           $this->db->from('mpesa_transactions');
           $this->db->where('order_id',$queri['order_id']);
           $this->db->where('status',0);

           $querm = $this->db->get()->row_array();

           $this->db->select('checkout_pending.*,locations.name as locname,installation_types.type_name,products.product_name');
           $this->db->from('checkout_pending');
           $this->db->join('locations','locations.location_id=checkout_pending.location_id');
           $this->db->join('order_details','order_details.order_id=checkout_pending.order_id');
           $this->db->join('products','products.product_id=order_details.product_id');
           $this->db->join('installation_types','installation_types.installation_type_id=checkout_pending.installation_type_id');
           $this->db->where('account_id',$account_id);
           $this->db->where('status',0);

           $query = $this->db->get()->row_array();
           if($query['center_id'] !=0){
                $this->db->select('installation_centers.name,installation_centers.address');
                $this->db->from('installation_centers');
                $this->db->where('center_id',$query['center_id']);
                $centernm=$this->db->get()->row_array();
                $query['center_name']=$centernm['name'];
                $query['center_address']=$centernm['address'];

                $this->db->select('SUM(total_price) as amount');
                $this->db->from('order_details');
                $this->db->where('order_id',$queri['order_id']);
                $totalp=$this->db->get()->row_array();
                $query['amount']=$totalp['amount'];

                if(!empty($querm)){
                    $query['mpesa']=1;
                }else{
                    $query['mpesa']=0;
                }


                return $query;
           }
           else{
                $this->db->select('SUM(total_price) as amount');
                $this->db->from('order_details');
                $this->db->where('order_id',$queri['order_id']);
                $totalp=$this->db->get()->row_array();
                $query['amount']=$totalp['amount'];
             if(!empty($querm)){
                    $query['mpesa']=1;
                }else{
                    $query['mpesa']=0;
                }
            return $query;

           }
       }else{
        return 0;
       }
   }

   function save_pay($data){
    $this->db->select('mpesa_transactions.*');
    $this->db->from('mpesa_transactions');
    $this->db->where('confirmation_code',$data['confirmation_code']);
    $codei=$this->db->get()->row_array();
    if(empty($codei)){
        $this->db->insert('mpesa_transactions', $data);
        $id = $this->db->insert_id();
        if($id){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

    function fetch_center($id){
        $this->db->select('locations.*');
        $this->db->from('locations');
        $this->db->where('location_id',$id);

        $query = $this->db->get()->row_array();

            $this->db->select('installation_centers.*');
            $this->db->from('installation_centers');
            $this->db->where('status',1);
            $this->db->like('address',$query['name']);

            $query = $this->db->get();
            return $query->result();
    }


    function insert_checkout($checkout){
        $this->db->insert('checkout_pending', $checkout);
        $id = $this->db->insert_id();
        return (isset($id)) ? $id : FALSE;
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
