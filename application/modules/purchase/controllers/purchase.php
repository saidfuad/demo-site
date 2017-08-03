<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends Base_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('alerts/mdl_alerts');
        $this->load->model('devices/mdl_devices');
        $this->load->model('mdl_purchase');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('cart');
        $this->load->library('emailsend');

    }

    public function index(){

        $check_order= $this->mdl_purchase->fetch_order($this->session->userdata('hawk_account_id'));

        if($check_order !=0){
            $data['pending_order'] = $check_order;
            /*echo "<pre>";
            print_r($data);
            exit;*/

            $data['content_url'] = 'purchase/pending_orders';
            $data['fa'] = 'fa fa-fw fa-shopping-cart';
            $data['fa1'] = '';
            $data['fa2'] = '';
            $data['fa3'] = '';
            $data['fa4'] = '';
            $data['fa5'] = '';
            $data['title'] = 'HAWK | Pending Checkout Order';
            $data['content_title'] = 'Pending Checkout Order';
            $data['content_subtitle'] = 'Confirm Payment For Pending Order Here.';
            $data['content'] = 'purchase/pending_orders.php';
            $this->load->view('main/main.php', $data);

        }else{
            $data['products'] = $this->mdl_purchase->fetch_products($this->session->userdata('hawk_account_id'));
            $data['assigned_vehicles'] = $this->mdl_purchase->fetch_assigned_vehicles($this->session->userdata('hawk_account_id'));

            /*echo "<pre>";
            print_r($data);
            exit;*/

            $data['content_url'] = 'purchase';
            $data['fa'] = 'fa fa-fw fa-cart-plus';
            $data['fa1'] = '';
            $data['fa2'] = '';
            $data['fa3'] = '';
            $data['fa4'] = '';
            $data['fa5'] = '';
            $data['title'] = 'HAWK | Purchase';
            $data['content_title'] = 'Purchase';
            $data['content_subtitle'] = 'To add an item to your shopping cart click on "Add to Cart" Button';
            $data['content'] = 'purchase/products.php';
            $this->load->view('main/main.php', $data);
        }

    }

    /* Cart Functions */
    function add(){
            $insert_data = array(
            'id' => $this->input->post('product_id'),
            'name' => $this->input->post('product_type'),
            'price' => $this->input->post('product_price'),
            'qty' => $this->input->post('product_quantity'),
            'options' => array('vehicle_id' => $this->input->post('vehicle_id'), 'plate_no' => $this->input->post('vehicle_plate'))
        );

        // This function add items into cart.
        $this->cart->insert($insert_data);

        // This will show insert data in cart.
        redirect('purchase');

    }

    function remove($rowid) {
        // Check rowid value.
        if ($rowid==="all"){
            // Destroy data which store in session.
            $this->cart->destroy();
        }else{
            // Destroy selected rowid in session.
            $data = array(
            'rowid' => $rowid,
            'qty' => 0
            );
            // Update cart data, after cancel.
            $this->cart->update($data);
        }
        // This will show cancel data in cart.
        redirect('purchase');
    }

    function update_cart(){

        // Recieve post values,calcute them and update
        $cart_info = $_POST['cart'] ;

        foreach( $cart_info as $id => $cart){

            $rowid = $cart['rowid'];
            $price = $cart['price'];
            $amount = $price * $cart['qty'];
            $qty = $cart['qty'];

            $data = array(
            'rowid' => $rowid,
            'price' => $price,
            'amount' => $amount,
            'qty' => $qty
            );

            $this->cart->update($data);
        }
        redirect('purchase');

    }

    function get_instype($id){
        $instype= $this->mdl_purchase->fetch_location($id);
         echo '<div class="form-group" style="text-align: left">
                                        <label for="reservation">Installation Mode</label><select name="installation_type_id" id="installation_type_id" class="form-control">
            <option value="">Select Installation Mode</option>';
            foreach($instype as $new){
                        $sel = "";
                        if(set_select('installation_type_id', $new->installation_type_id)) $sel = "selected='selected'";
                        echo '<option value="'.$new->installation_type_id.'" '.$sel.'>'.$new->type_name.'</option>';
                    }

        echo '</select></div>';
    }

    function get_inscenter($id){
        $inscenter= $this->mdl_purchase->fetch_center($id);
         echo '<div class="form-group" style="text-align: left;display:none;" id="inscenter_id">
                                        <label for="reservation">Choose Installation Center</label><select name="center_id" id="center_id" class="form-control">
            <option value="">Select Installation Center</option>';
            foreach($inscenter as $new){
                        $sel = "";
                        if(set_select('center_id', $new->center_id)) $sel = "selected='selected'";
                        echo '<option value="'.$new->center_id.'" '.$sel.'>'.$new->name.'</option>';
                    }

        echo '</select></div>';

    }

    function checkout(){

        $data['locations'] = $this->mdl_purchase->fetch_locations();
        $data['installation_types'] = $this->mdl_purchase->fetch_installation_types();

        $data['content_url'] = 'purchase/checkout';
        $data['fa'] = 'fa fa-fw fa-shopping-cart';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Checkout Order';
        $data['content_title'] = 'Checkout Order';
        $data['content_subtitle'] = 'Confirm and submit your order here.';
        $data['content'] = 'purchase/checkout.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_order(){

        /* Empty for now */
        $transaction_no = '';

        $order = array(
            'transaction_no' => $transaction_no,
            'account_id' => $this->session->userdata('hawk_account_id'),
            'user_id' => $this->session->userdata('hawk_user_id')
        );

        $order_id = $this->mdl_purchase->insert_order($order);
        $_SESSION['order_id']=$order_id;

        if ($cart = $this->cart->contents()):
        foreach ($cart as $item):
        $order_detail = array(
            'order_id' => $order_id,
            'product_id' => $item['id'],
            'quantity' => $item['qty'],
            'total_price' => $item['price'],
            'vehicle_id' => $item['options']['vehicle_id']
        );

        // Insert product imformation with order detail, store in cart also store in database.
        $cust_id = $this->mdl_purchase->insert_order_detail($order_detail);

        $checkout = $this->input->post();
        $checkout['account_id'] = $this->session->userdata('hawk_account_id');
        $checkout['order_id']=$order_id;
        $checkout['status']=0;
        $checkout_id = $this->mdl_purchase->insert_checkout($checkout);

        /* Send Email Notification */
        $email = $this->mdl_purchase->user_email($this->session->userdata('hawk_user_id'));
        $this->email_alert($email, $order_id, $order_detail);

        endforeach;
        endif;

        /* Destroy Cart */
        $cart = $this->cart->destroy();

        return $checkout_id;

    }

    function email_alert($email, $order_id, $order_detail){

        $total_price = 0;
        $i = 0;
        for(; $i<sizeof($order_detail); $i++){
        $total_price += $order_detail['total_price'];
        }

        $to = array($email['email']);
        $subj = "HAWK Order";
        $url = "http://192.168.52.90:4040/hawk/assets/images/system/hawk_logo.png";

        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                        <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                            <h1><img src="'.$url.'"></h1>
                        </div>
                        <div style="padding:20px;">
                            Dear User,<br><br>
                            Order has successfully been place on HAWK.
                            Your order is pending your payment approval.
                            <br>
                            <b>Order Details: </b>
                            <br>
                            <br>
                            Order ID: '.$order_id.'
                            <br>
                            Total Price: '.$total_price.'
                            <br>
                            Order Status: Pending
                            <br>
                            <br>
                            Hawk. Always Watching | All Rights Reserved.
                            <br>
                        </div>
                    </div>';

        return $this->send_email_message ($to, $subj, $message);
    }

    function send_email_message ($to, $subj, $message) {
        return $this->emailsend->send_email_message ($to, $subj, $message);
    }

    function save_payment(){
        $data=$this->input->post();
       $gd= $this->mdl_purchase->save_pay($data);
       echo $gd;
    }

}
