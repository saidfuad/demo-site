<style>

.nav a{
color: #131b26;
}

.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover{
color: #c1d72e;
}

.badge{
background: #131b26;
color:#fff;
width: 20px;
height: 20px;
font-size: 10px;
text-align: center;
padding: 0;
line-height: 18px;
border-radius: 50%;
}

.unread{
font-weight: 900;
font-size: 14px;
background: #ccc !important;
}

.read{
font-weight: 400;
}

</style>
<div class="container-fluid">
<div class="row">
<div class="col-md-12 col-lg-12">
<div class="row">
<div class="col-xs-12">
    <div class="box">
        <div class="box-header"> </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive" style="margin-top:40px;">
                        <?php if (sizeof($expenses)) {?>
                        <br>
                        <div class="table-responsive">
                            <div>
                                <a href='<?= site_url('accounting/add_expense') ?>' data-original-title='Click here to add an expense incurred ' data-toggle='tooltip' class='btn btn-primary pull-right btn-sm'>Record Expense Incurred</a>
                            </div>
                            <br />
                            <br />
                            <table class="table table-striped table-hover" id="arm_table">
                                <thead>
                                    <tr data-toggle='tooltip' data-original-title='The table shows the list of the expenses incurred. You can view and edit details of the expenses shown in the table'>
                                        <th>Date</th>
                                        <th>Vehicle Model</th>
                                        <th>Vehicle Plate No.</th>
                                        <th>Expense Type</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($expenses as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo date_format(date_create($value->add_date),"d-m-Y"); ?></td>
                                        <td><?php echo $value->model; ?></td>
                                        <td><?php echo $value->plate_no; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->amount; ?></td>
                                       <td><?php echo "<a data-original-title='Click here to edit the details of this specific expense ' data-toggle='tooltip'  href='".base_url('index.php/accounting/edit_expense/'.$value->accounting_id)."'class='btn btn-primary btn-xs'>Edit Details</a>"?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else {?>
                        <br>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-exclamation-triangle"></i> No Expense Incurred Records</h2>
                            <br>
                            <p>Currently you have no records of expenses incurred</p>
                            <a href='<?= site_url('accounting/add_expense') ?>' class='btn btn-primary pull-center btn-sm'>Record Expense Incurred</a>
                        </div>
                        <?php } ?>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
</div>
</div>
</div>
</div>

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<script>
// Initialize Loadie for Page Load
$(document).ready(function () {
$('#power_cut_table').dataTable();
$('#overspeed_table').dataTable();
$('#arm_table').dataTable();
$('#geofence_table').dataTable();
});

function view(id, type){

$.ajax({
method: 'post',
url: '<?= base_url('index.php/reminders/read_alert') ?>',
data: {alert_id:id},
success: function (response) {

window.base_url = <?php echo json_encode(base_url()); ?>;
$("#badge"+type).load(base_url + "index.php/alerts #badge"+type);
//$("#badge_alert").load(base_url + "index.php/alerts #badge_alert");

$('#item'+id).removeClass('unread');
$('#item'+id).removeClass('read');

}
});

}

</script>
