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

    .product_hours{
        margin-bottom: 16px;
        border-radius: 10px;
        border-bottom: none;
        padding-bottom: 20px
    }

    #form-hours{
        width: 90%;
    }

    .submit_order{
        border-radius: 10px;
        border-bottom: none;
        padding-bottom: 20px
    }

    .shopping-cart{
        width: 100%;
        background: #fff
    }

    #main_heading{
        background: #c1d72e;
    }

    #phone_no{
        border: none;
        height: 32px;
        border-radius: 0 5px 5px 0;
    }

    .phone_confirm{
        margin-top: 32px;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <?php

            $cart_check = $this->cart->contents();

            if(empty($cart_check)){}else{ require_once("cart_process.php"); } ?>

            <div class="col-md-12 col-lg-12">

                <div class="submit_order col-xs-6 col-md-5 col-md-offset-3 bg-crumb" align="center">

                    <form id="form-submit-order">

                        <div style="margin:0; padding:0" class="col-md-12 col-lg-12">
                            <img style="margin-top: 20px;" class="media-object user-profile-image img-circle col-xs-6 col-md-5" src="<?php echo base_url('assets/images/gps/phone.png'); ?>" />
                            <div class="col-xs-6 col-md-7">
                                <div class="phone_confirm">

                                    <h4>Enter Phone Number</h4>
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon2">
                                            <span>+254</span>
                                        </span>
                                        <input placeholder="712345678" class="form-control" type="text" name="phone_no" id="phone_no" required="required" autofocus/>
                                    </div>

                                    <br>
                                    <input type="submit" name="submit" id="save-order" class="btn btn-success" value="Submit Order <?php "<i class='fa fa-shopping-cart'></i>" ?>" />

                                </div>
                            </div>
	                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {

        $('#form-submit-order').on('submit', function(){

            $('#save-order').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-order').prop('disabled', true);

            var phone_no = $('#phone_no').val().trim();

            swal({
                  title: 'Place Order?',
                  text: "Click continue to place your order",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Continue!',
                  closeOnConfirm: false
                },
                function() {

                    $.ajax({
                        method: 'POST',
                        cache : false,
                        data: {phone_no:phone_no},
                        url: '<?=base_url('index.php/orders/save_order'); ?>',
                        success: function (response) {
                            location.href = "../orders";
                        }

                    });
            });

        });

    });

</script>
