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

    #main_heading{
        background: #c1d72e;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
             <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr data-toggle='tooltip' data-original-title='The table shows the pending order installation details'>
                            <th>Installation Mode</th>
                            <th>Installation Location</th>
                            <?php if($pending_order['center_id'] == 0){?>
                            <th>Delivery Address</th>
                            <th>Delivery Time</th>
                           <?php }?>
                           <?php if($pending_order['center_id'] != 0){?>
                            <th>Installation Center</th>
                            <th>Center Address</th>
                            <?php }?>

                            <th>Amount To Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="gradeU">
                            <td><?php echo $pending_order['type_name']; ?></td>
                            <td><?php echo $pending_order['locname']; ?></td>
                            <?php if($pending_order['center_id'] == 0){?>
                            <td><?php echo $pending_order['address']; ?></td>
                            <td><?php echo $pending_order['delivery_time'] ?></td>
                            <?php }?>
                            <?php if($pending_order['center_id'] != 0){?>
                            <td><?php echo $pending_order['center_name']; ?></td>
                            <td><?php echo $pending_order['center_address']; ?></td>
                            <?php }?>


                            <td><?php echo $pending_order['amount']; ?></td>
                        </tr>

                    </tbody>
                </table>
            <div class="col-md-12 col-lg-12">

                <div class="submit_order col-xs-6 col-md-5 col-md-offset-3 bg-crumb" align="center">

                    <div id="pending_payment">
                        <div style="margin:0; padding:0" class="col-md-12 col-lg-12">

                            <div class="col-xs-12 col-md-12">

                                <div class="panel-heading" style="text-align: left">
                                    <p>Please Complete This Pending Order To Make A New Order</p>
                                    <h4>Payment Process Details</h4>
                                    <legend></legend>
                                </div>
                                <div class="panel-body">
                                    <ul type="disc">
                                        <li>Go To <b>Safaricom</b> Menu On Your Phone</li>
                                        <li>Select <b>M-PESA</b></li>
                                        <li>Select <b>Lipa na M-PESA</b></li>
                                        <li>Select <b>Pay Bill</b></li>
                                        <li>Select <b>Enter Business No</b></li>
                                        <li>Input <b>546758</b>,Click OK</li>
                                        <li>Select <b>Account No</b></li>
                                        <li>Input <b>Transaction Number</b>Sent Via <b>SMS</b> From Hawk,Click OK</li>
                                        <li>Input <b>Amount To Pay</b>,Click OK</li>
                                        <li>Enter your <b>PIN Number</b>,Click OK</li>
                                        <li>Confirm by <b>Clicking OK</b></li>
                                    </ul>
                                    <br><hr>
                                    Pay <b>Ksh.&nbsp;&nbsp;<?php echo $pending_order['amount']; ?></b>
                                    <br><hr>
                                    Wait for 2 Minutes to allow IPN synchronization.
                                    <br><hr>
                                <form id="payment">
                                <div class="form-group" style="text-align: left">
                                        <label for="reservation">Enter your M-PESA Transaction Code Below</label>
                                        <input class="form-control" type="hidden" name="order_id" id="order_id" value="<?php echo $pending_order['order_id']; ?>"/>
                                        <input class="form-control" type="hidden" name="amount_to_pay" id="amount_to_pay" value="<?php echo $pending_order['amount']; ?>"/>
                                        <input class="form-control" type="hidden" name="status" id="status" value="0"/>
                                        <input class="form-control" type="text" name="confirmation_code" id="confirmation_code"/>
                                </div>
                                </div>
                                <br>
                                <input type="submit" name="submit" id="confirm-payment" class="btn btn-success" value="Confirm Payment <?php "<i class='fa fa-shopping-cart'></i>" ?>" />
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/choosen/chosen.jquery.min.js')?>" type="text/javascript"></script>
<script>

    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });

    $(function () {
        $('#payment').on('submit', function () {

            var $this = $(this);

            if ($('#order_id').val().trim().length == 0 || $('#amount_to_pay').val().trim().length == 0 || $('#status').val().trim().length == 0 || $('#confirmation_code').val().trim().length == 0 ) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#confirm-payment').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#confirm-payment').prop('disabled', true);

            swal({
                title: "Info",
                text: "Submit Confirmation Code?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/purchase/save_payment') ?>',
                    data: $this.serialize(),
                    success: function (response) {
                        if (response == 1) {

                            swal({title: "Info", text: "Successful Transaction.Wait For Confirmation Of Payment From Hawk System", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/purchase') ?>";
                                }
                            );
                            
                        } else{
                            swal({title: "Error", text: "That Confirmation Code Was Used In An Earlier Payment", type: "error", confirmButtonText: "ok"});
                        }

                        $('#confirm-payment').html('Confirm Payment');
                        $('#confirm-payment').prop('disabled', false);
                    }
                });
            });

            $('#confirm-payment').html('Confirm Payment');
            $('#confirm-payment').prop('disabled', false);

            return false;
        });

    });

</script>
