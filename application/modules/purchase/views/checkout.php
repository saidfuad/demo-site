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

    ul li{
        text-align: left;
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
<!--                            <img style="top: 50%;position:absolute;margin-top:-70px; right: 0" class="media-object user-profile-image img-circle col-xs-6 col-md-5" src="<?php echo base_url('assets/images/gps/phone.png'); ?>" />-->

                            <div class="col-xs-12 col-md-12">

                                <div class="panel-heading" style="text-align: left">
                                    <h4>Enter Installation Billing Details</h4>
                                    <legend></legend>
                                </div>
                                <div class="panel-body">

                                    <div class="form-group" style="text-align: left">
                                        <label for="reservation">Installation Location</label>
                                        <select name="location_id" id="location_id" class="form-control">
                                        <option value="">Select Installation Location</option>
                                        <?php foreach($locations as $new) {
                                            $sel = "";
                                            if(set_select('location_id', $new->location_id)) $sel = "selected='selected'";
                                            echo '<option value="'.$new->location_id.'" '.$sel.'>'.$new->name.'</option>';
                                        }

                                        ?>

                                    </select>
                                    </div>


                                    <div id="instype"></div>

                                     <div class="form-group" style="text-align: left;display:none;" id="insaddress">
                                        <label for="reservation">Delivery Address</label>
                                        <input class="form-control" type="text" name="address" id="address"/>
                                    </div>

                                     <div class="form-group" style="text-align: left;display:none;" id="insdelivery_time">
                                        <label for="reservation">Delivery Time</label>
                                        <input class="form-control" type="text" name="delivery_time" id="delivery_time"/>
                                    </div>

                                    <div id="inscenter"></div>


<!--
                                    <div class="form-group" style="text-align: left">
                                        <label for="reservation">Phone Number:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">+254</div>
                                            <input placeholder="712345678" class="form-control" type="text" name="billing_phone_no" id="billing_phone_no"/>
                                        </div>
                                    </div>
-->
                                </div>
                                <br>
                                <input type="submit" name="submit" id="save-order" class="btn btn-success" value="Pay Via Mpesa <?php "<i class='fa fa-shopping-cart'></i>" ?>" />
                                </div>
                            </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/choosen/chosen.jquery.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js')?>"></script>
<script>

    $(function () {

        $('#location_id').on('change',function(){
            location_id=$('#location_id').val();

            $.ajax({
            url: '<?php echo site_url('purchase/get_instype') ?>' + '/' + location_id,
            type:'POST',
            data:{},
            success:function(result){

              $('#instype').html(result);

             }
          });

           $.ajax({
            url: '<?php echo site_url('purchase/get_inscenter') ?>' + '/' + location_id,
            type:'POST',
            data:{},
            success:function(result){

              $('#inscenter').html(result);

             }
          });

                $('#insaddress').css({
                display: 'none'
                });

                $('#insdelivery_time').css({
                    display: 'none'
                });

                $('#inscenter_id').css({
                display: 'none'
                });


        });

          $('#delivery_time').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().subtract(24, 'hours'),
            minDate: moment().subtract(1, 'days'),
            format: 'YYYY-MM-DD'
        });

          $('#instype').on('change',function(){
            installation_type_id=$('#installation_type_id').val();

            if(installation_type_id==1){
                 $('#insaddress').css({
                display: 'block'
                });

                $('#insdelivery_time').css({
                    display: 'block'
                });

                $('#inscenter_id').css({
                display: 'none'
                });

            }else{
                $('#insaddress').css({
                display: 'none'
                });

                $('#insdelivery_time').css({
                    display: 'none'
                });

                $('#inscenter_id').css({
                display: 'block'
                });
            }

        });

        $('#form-submit-order').on('submit', function(){

            var $this = $(this);

            if ($('#location_id').val().trim().length == 0 || $('#installation_type_id').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-order').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-order').prop('disabled', true);


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
                        data: $this.serialize(),
                        url: '<?=base_url('index.php/purchase/save_order'); ?>',
                        success: function (response) {
                             swal({title: "Info", text: "Order Made successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                   document.location.href="<?php echo base_url('index.php/purchase') ?>";
                                }
                            );
                        }

                    });
            });

        });

    });

</script>
