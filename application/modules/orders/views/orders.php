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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?php if (sizeof($orders)) {?>
            <a style="float:right; margin-right: 16px" href="<?php echo "".site_url('purchase/devices').""; ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-cart-plus"></i> &nbsp; Go to Purchases
            </a>
            <?php } ?>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">

            <?php if (sizeof($orders)) {?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Transaction Number</th>
                                <th>Total Price</th>
                                <th>Billing Address</th>
                                <th>Billing Phone</th>
                                <th>Order Status</th>
                                <th>Order Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php if($value->transaction_no == ""){ echo strtoupper("Processing"); }else{echo strtoupper($value->transaction_no);} ?></td>
                                <td><?php echo strtoupper($value->total_price.".00 ksh"); ?></td>
                                <td><b><?php echo strtoupper($value->billing_address); ?></b></td>
                                <td><b><?php echo strtoupper($value->billing_phone_no); ?></b></td>
                                <td><b><?php echo strtoupper($value->order_status); ?></b></td>
                                <td><?php echo $value->order_date; ?></td>
                                <td><a href="#view_order<?php echo $value->order_id;?>" data-toggle="modal" class="btn btn-success btn-xs">View Order</a></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                <div style="margin-bottom:42px" class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-shopping-cart"></i> No Orders</h2>
                    <p>You currently have no previous orders. Click on purchases here or on the menu to place your order. </p>
                    <a href="<?php echo site_url('purchase/devices');?>" class="btn btn-success">Go to Purchases</a>
                    <br>
                </div>
            <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php require_once("view_order.php"); ?>

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>

<script>

    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });

</script>

