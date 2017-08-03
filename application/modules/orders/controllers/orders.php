<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Base_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('alerts/mdl_alerts');
        $this->load->model('mdl_orders');
        $this->load->model('devices/mdl_devices');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('cart');
        $this->load->library('emailsend');

    }

    public function index(){

        $data['orders'] = $this->mdl_orders->fetch_orders($this->session->userdata('hawk_account_id'));

        /*echo "<pre>";
        print_r($data);
        exit;*/

        $data['content_url'] = 'orders';
        $data['fa'] = 'fa fa-fw fa-shopping-cart';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | My Orders';
        $data['content_title'] = 'My Orders';
        $data['content_subtitle'] = '';
        $data['content'] = 'orders/orders.php';
        $this->load->view('main/main.php', $data);
    }

    public function products(){

        $data['products'] = $this->mdl_orders->fetch_products($this->session->userdata('hawk_account_id'));
        $data['assigned_vehicles'] = $this->mdl_orders->fetch_assigned_vehicles($this->session->userdata('hawk_account_id'));

        /*echo "<pre>";
        print_r($data);
        exit;*/

        $data['content_url'] = 'orders/products';
        $data['fa'] = 'fa fa-fw fa-cart-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Products';
        $data['content_title'] = 'Products';
        $data['content_subtitle'] = 'To add a product to your shopping cart click on "Add to Cart" Button';
        $data['content'] = 'orders/products.php';
        $this->load->view('main/main.php', $data);
    }

    public function view_order(){

        $data = $this->input->post();
        $data['account_id'] = $this->session->userdata('hawk_account_id');

        $order = $this->mdl_orders->fetch_order($data);
        //$res = array('order'=>$order);

        echo json_encode($order);

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
        redirect('orders/products');

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
        redirect('orders/products');
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
        redirect('orders/products');

    }

    function checkout(){

        $data['content_url'] = 'orders.checkout';
        $data['fa'] = 'fa fa-fw fa-shopping-cart';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Checkout Order';
        $data['content_title'] = 'Checkout Order';
        $data['content_subtitle'] = 'Confirm and submit your order here.';
        $data['content'] = 'orders/checkout.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_order(){

        /* Empty for now */
        $transaction_no = '';

        $order = array(
            'transaction_no' => $transaction_no,
            'transaction_phone_no' => $this->input->post('phone_no'),
            'account_id' => $this->session->userdata('hawk_account_id'),
            'user_id' => $this->session->userdata('hawk_user_id')
        );

        $order_id = $this->mdl_orders->insert_order($order);

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
        $cust_id = $this->mdl_orders->insert_order_detail($order_detail);

        /* Send Email Notification */
        $email = $this->mdl_orders->user_email($this->session->userdata('hawk_user_id'));
        $this->email_alert($email, $order_id, $order_detail);

        endforeach;
        endif;

        /* Destroy Cart */
        $cart = $this->cart->destroy();

    }

    function email_alert($email, $order_id, $order_detail){

        $total_price = 0;
        $i = 0;
        for(; $i<sizeof($order_detail); $i++){
        $total_price += $order_detail['total_price'];
        }

        $to = array($email['email']);
        $subj = "HAWK Order";
        $url = base_url()."/hawk/assets/images/system/hawk_logo.png";

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

}
