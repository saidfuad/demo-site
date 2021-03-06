<style>

    .bg-crumb.device{
        background: #c1d72e;
    }

    .btn.btn-success{
        background: #172230;
        border: 0px !important;
        color: #fff !important;
    }

    .btn.btn-success:hover{
        background: #131b26 !important;
        color: #fff !important;
    }

    .product_subscription{
        margin-bottom: 16px;
        border-radius: 10px;
        border-bottom: none;
        padding-bottom: 20px;
    }

    .product_subscription:hover{
        border-radius: 10px;
        box-shadow: 0 0 7px 2px rgba(0,0,0,.1);
    }

    #form-subscription{
        width: 90%;
    }

    .shopping-cart{
        width: 100%;
        background: #fff
    }

    #main_heading{
        background: #c1d72e;
    }

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <?php

            $cart_check = $this->cart->contents();

            if(empty($cart_check)) {

            }else{ require_once("cart.php"); }

            ?>

            <div class="col-md-12 col-lg-12">
                <br>
                <?php if(sizeof($assigned_vehicles)) {?>

                <?php foreach ($assigned_vehicles as $key => $value) { ?>

                <div class="col-xs-6 col-md-3">
                    <div class="product_subscription bg-crumb" align="center">

                        <form id="form-subscription">

                            <input type="hidden" id="product_id1" value="<?php echo $products[1]->product_id; ?>" />
                            <input type="hidden" id="product_type1" value="<?php echo $products[1]->product_type; ?>" />
                            <input type="hidden" id="product_price1" value="<?php echo $products[1]->product_price; ?>" />
                            <input type="hidden" id="product_quantity1" value="<?php echo $products[1]->product_quantity; ?>" />
                            <input type="hidden" id="vehicle_id" value="<?php echo $value->vehicle_id; ?>" />
                            <input type="hidden" id="plate_no" value="<?php echo $value->plate_no; ?>" />

                            <h4><b> &nbsp; SUBSCRIPTION</b></h4>
                            <p style="font-size: 12px"> &nbsp; BUY SUBSCRIPTION FOR:</p>
                            <p style="font-size: 16px"><b><?php echo strtoupper($value->plate_no); ?></b></p>
                            <p><img class="media-object user-profile-image img-circle" src="<?php echo base_url('assets/images/gps/subscription.png');?>" style="width:96px;height:96px;"></p>
                            <p>Add device subscription</p>
                            <p id="product_price_2"><b>Ksh.<?php echo $products[1]->product_price; ?>/month</b></p>
                            <button data-toggle='tooltip' data-original-title='Click to add subscription to your cart.' data-placement='bottom' type="submit" class="btn btn-success" id="addCart2">Add to Cart</button>
                            <br>

                        </form>

                    </div>
                </div>

                <?php }} else {?>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script>

    $(function () {

        /* Subscription */
        $('#form-subscription').on('submit', function(){

            var product_id = $('#product_id1').val().trim();
            var product_type = $('#product_type1').val().trim();
            var product_price = $('#product_price1').val().trim();
            var product_quantity = $('#product_quantity1').val().trim();
            var vehicle_id = $('#vehicle_id').val().trim();
            var vehicle_plate = $('#plate_no').val().trim();

            $.ajax({
                method: 'POST',
                cache : false,
                data: {product_id:product_id, product_type:product_type, product_price:product_price, product_quantity:product_quantity, vehicle_id:vehicle_id, vehicle_plate:vehicle_plate},
                url: '<?=base_url('index.php/orders/add'); ?>',
                success: function (response) {
                    location.reload();
                }

            });

        });

    });

</script>
