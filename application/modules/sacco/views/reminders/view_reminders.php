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
            <div class="tabbable">
                <!-- Only required for left/right tabs -->
                <ul class="nav nav-tabs">
                    <li class="active" data-original-title='Click here to view the set list of insurance reminders' data-toggle='tooltip'>
                        <a href="#insurance" data-toggle="tab">Insurance
                        </a>
                    </li>
                 
                    
                </ul>

                <div class="tab-content">

                    <!-- Insurance -->
                    <div class="tab-pane active" id="insurance">
                        <?php if (sizeof($insurances)) {?>
                        <br>
                        <div class="table-responsive">
                            <div>
                                <a href='<?= site_url('sacco/add_insurance') ?>' data-original-title='Click here to add an insurance reminder ' data-toggle='tooltip' class='btn btn-primary pull-right btn-sm'>Add Insurance Reminder</a>
                            </div>
                            <br />
                            <br />
                            <table class="table table-striped table-hover" id="power_cut_table">
                                <thead>
                                    <tr data-toggle='tooltip' data-original-title='The table shows the list of your insurance reminders. You can view and edit details of the reminders shown in the table'>
                                        <th>Vehicle Plate No.</th>
                                        <th>Insurance Company</th>
                                        <th>Value Covered</th>
                                        <th>Premium Amount</th>
                                        <th>Expiry Date</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php ;
                                    foreach ($insurances as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo $value->plate_no; ?></td>
                                        <td><?php echo $value->company; ?></td>
                                        <td><?php echo number_format($value->value_covered); ?></td>
                                        <td><?php echo number_format($value->amount_to_pay); ?></td>
                                        <td><?php
                                            $total=strtotime($value->expiry_date)-strtotime(date("Y-m-d"));
                                            $rem=$total/86400;

                                            if($value->reminder_time=="1 Week"){
                                                
                                                if($total<=604800 && $total>=0){
                                                    $this->mdl_reminders->send_remsms($value->reminder_id);
                                                    $this->mdl_reminders->update_remdate($value->reminder_id);
                                                    echo date_format(date_create($value->expiry_date),"d-m-Y")." <i class='fa fa-exclamation-triangle'></i> <b>Go Pay Premium</b>";
                                                    //date_format(date_create($value->expiry_date),"d F ,Y")
                                                }else if($total>604800){
                                                    echo date_format(date_create($value->expiry_date),"d-m-Y")."|<b>  ".$rem." Days To Go</b>";
                                                }

                                                if($total<0){
                                                    $this->mdl_reminders->update_remdate($value->reminder_id);
                                                    echo date_format(date_create($value->expiry_date),"d-m-Y")." <i class='fa fa-exclamation-triangle'></i> <b>Go For Service</b>";
                                                }


                                            }else{
                                                 if($total<=1209600 && $total>=0){
                                                    $this->mdl_reminders->send_remsms($value->reminder_id);
                                                    $this->mdl_reminders->update_remdate($value->reminder_id);
                                                    echo date_format(date_create($value->expiry_date),"d-m-Y")." <i class='fa fa-exclamation-triangle'></i> <b>Go Pay Premium</b>";
                                                }else if($total>1209600){
                                                    echo date_format(date_create($value->expiry_date),"d-m-Y")."|<b>  ".$rem." Days To Go</b>";
                                                }

                                                if($total<0){
                                                    $this->mdl_reminders->update_remdate($value->reminder_id);
                                                    echo date_format(date_create($value->expiry_date),"d-m-Y")." <i class='fa fa-exclamation-triangle'></i> <b>Go For Service</b>";
                                                }

                                            }
                                         ?></td>
                                        <td><?php echo "<a data-original-title='Click here to edit the details of this specific reminder ' data-toggle='tooltip'  href='".base_url('index.php/sacco/edit_insurance/'.$value->reminder_id)."'class='btn btn-primary btn-xs'>Edit Details</a>"?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else {?>
                        <br>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-exclamation-triangle"></i> No Insurance Reminders</h2>
                            <br>
                            <p>Currently you have no power insurance reminders</p>
                            <a href='<?= site_url('sacco/add_insurance') ?>' class='btn btn-primary pull-center btn-sm'>Add Insurance Reminder</a>
                        </div>
                        <?php } ?>
                    </div>

             
                </div>
            </div>
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
url: '<?= base_url('index.php/sacco/read_alert') ?>',
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
