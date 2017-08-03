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

    .product_device{
        border-radius: 10px;
        border-bottom: none;
        padding-bottom: 20px;
    }

    .product_device:hover{
        border-radius: 10px;
        box-shadow: 0 0 7px 2px rgba(0,0,0,.1);
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

                <?php if(sizeof($products)) { ?>

                <!--<div class="col-xs-6 col-md-3">
                    <div class="product_device bg-crumb device" align="center">

                        <form id="form-device">

                            <input type="hidden" id="product_id" value="<?php echo $products[0]->product_id; ?>" />
                            <input type="hidden" id="product_type" value="<?php echo $products[0]->product_type; ?>" />
                            <input type="hidden" id="product_price" value="<?php echo $products[0]->product_price; ?>" />
                            <input type="hidden" id="product_quantity" value="<?php echo $products[0]->product_quantity; ?>" />

                            <h4><b> &nbsp; <?php echo strtoupper($products[0]->product_type); ?></b></h4>
                            <p><b> &nbsp; <?php echo strtoupper($products[0]->product_name); ?></b></p>
                            <p><img class="media-object user-profile-image img-circle" src="<?php echo base_url('assets/images/gps/gps.png');?>" style="width:124px;height:124px;"></p>
                            <p>Place an order for a new device</p>
                            <p id="product_price_1"><b>Ksh.<?php echo $products[0]->product_price; ?></b></p>
                            <button type="submit" class="btn btn-success" id="addCart1">Add to Cart</button>
                            <br>

                        </form>

                    </div>
                </div>-->

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script>

    $(function () {

        /* Devices */
        $('#form-device').on('submit', function(){

            var product_id = $('#product_id').val().trim();
            var product_type = $('#product_type').val().trim();
            var product_price = $('#product_price').val().trim();
            var product_quantity = $('#product_quantity').val().trim();
            var vehicle_id = null;
            var vehicle_plate = null;

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
