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
                                    <li class="active">
                                        <a id="badge1" href="#power_cut" data-toggle="tab">Power Cut Alerts &nbsp; <span class="badge"><?php if($count_power_cut_alerts > 0) echo $count_power_cut_alerts; ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a id="badge2" href="#overspeed" data-toggle="tab">Overspeed Alerts &nbsp; <span class="badge"><?php if($count_overspeed_alerts > 0) echo $count_overspeed_alerts; ?></span></a>
                                    </li>
                                    <li>
                                        <a id="badge3" href="#arm" data-toggle="tab">Arm Alerts &nbsp; <span class="badge"><?php if($count_arm_alerts > 0) echo $count_arm_alerts; ?></span></a>
                                    </li>
                                    <li>
                                        <a id="badge4" href="#geofence" data-toggle="tab">Geofence Alerts &nbsp; <span class="badge"><?php if($count_geofence_alerts > 0) echo $count_geofence_alerts; ?></span></a>
                                    </li>
                                    <li>
                                        <a id="badge5" href="#landmarks" data-toggle="tab">Landmark Out Alerts &nbsp; <span class="badge"><?php if($count_landmark_alerts > 0) echo $count_landmark_alerts; ?></span></a>
                                    </li>
                                    <li>
                                        <a id="badge6" href="#routes" data-toggle="tab">Route Infringement Alerts &nbsp; <span class="badge"><?php if($count_route_alerts > 0) echo $count_route_alerts; ?></span></a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    <!-- Power Cut -->
                                    <div class="tab-pane active" id="power_cut">
                                        <?php if (sizeof($power_cut_alerts)) {?>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="power_cut_table">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle Plate No.</th>
                                                        <th>Alert Location</th>
                                                        <th>Alert Time</th>
                                                        <th>Resolve Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php ;
                                                    foreach ($power_cut_alerts as $key => $value) { ?>
                                                    <tr id="item<?php echo $value->alert_id; ?>" class="gradeU <?php if($value->viewed == 0){ echo "unread"; }else{ echo "read";} ?>">
                                                        <td><?php echo $value->plate_no; ?></td>
                                                        <td><?php echo $value->start_address; ?></td>
                                                        <td><?php echo $value->start_date; ?> | <b><span am-time-ago="<?php echo strtotime($value->start_date)*1000;?>"></span></b>
                                                        </td>
                                                        <td><?php if($value->status == 1){ ?>
                                                            <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                                                            <?php }else{ ?>
                                                            <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a onclick="view(<?php echo $value->alert_id;?>, <?php echo $value->alert_type_id;?>)" href="#view_alert<?php echo $value->alert_id;?>" data-toggle="modal" class="btn btn-primary btn-xs">View Details</a></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } else {?>
                                        <br>
                                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                            <h2><i class="fa fa-exclamation-triangle"></i> No Power Cut Alerts</h2>
                                            <br>
                                            <p>Currently you have no power cut alerts</p>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Speed Alert -->
                                    <div class="tab-pane" id="overspeed">
                                        <?php if (sizeof($overspeed_alerts)) {?>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="overspeed_table">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle Plate No.</th>
                                                        <th>Alert Location</th>
                                                        <th>Alert Time</th>
                                                        <th>Resolve Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php ;
                                                    foreach ($overspeed_alerts as $key => $value) { ?>
                                                    <tr id="item<?php echo $value->alert_id; ?>" class="gradeU <?php if($value->viewed == 0){ echo "unread"; }else{ echo "read";} ?>">
                                                        <td><?php echo $value->plate_no; ?></td>
                                                        <td><?php echo $value->start_address; ?></td>
                                                        <td><?php echo $value->start_date; ?> | <b><span am-time-ago="<?php echo strtotime($value->start_date)*1000;?>"></span></b>
                                                        </td>
                                                        <td><?php if($value->status == 1){ ?>
                                                            <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                                                            <?php }else{ ?>
                                                            <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a onclick="view(<?php echo $value->alert_id;?>, <?php echo $value->alert_type_id;?>)" href="#view_alert<?php echo $value->alert_id;?>" data-toggle="modal" class="btn btn-primary btn-xs">View Details</a></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } else {?>
                                        <br>
                                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                            <h2><i class="fa fa-exclamation-triangle"></i> No Overspeed Alerts</h2>
                                            <br>
                                            <p>Currently you have no overspeeding alerts</p>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Arm Alert -->
                                    <div class="tab-pane" id="arm">
                                        <?php if (sizeof($arm_alerts)) {?>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="arm_table">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle Plate No.</th>
                                                        <th>Alert Location</th>
                                                        <th>Alert Time</th>
                                                        <th>Resolve Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php ;
                                                    foreach ($arm_alerts as $key => $value) { ?>
                                                    <tr id="item<?php echo $value->alert_id; ?>" class="gradeU <?php if($value->viewed == 0){ echo "unread"; }else{ echo "read";} ?>">
                                                        <td><?php echo $value->plate_no; ?></td>
                                                        <td><?php echo $value->start_address; ?></td>
                                                        <td><?php echo $value->start_date; ?> | <b><span am-time-ago="<?php echo strtotime($value->start_date)*1000;?>"></span></b>
                                                        </td>
                                                        <td><?php if($value->status == 1){ ?>
                                                            <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                                                            <?php }else{ ?>
                                                            <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a onclick="view(<?php echo $value->alert_id;?>, <?php echo $value->alert_type_id;?>)" href="#view_alert<?php echo $value->alert_id;?>" data-toggle="modal" class="btn btn-primary btn-xs">View Details</a></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } else {?>
                                        <br>
                                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                            <h2><i class="fa fa-exclamation-triangle"></i> No Arm Alerts</h2>
                                            <br>
                                            <p>Currently you have no arm alerts</p>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Geofence -->
                                    <div class="tab-pane" id="geofence">
                                        <?php if (sizeof($geofence_alerts)) {?>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="geofence_table">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle Plate No.</th>
                                                        <th>Alert Type</th>
                                                        <th>Alert Location</th>
                                                        <th>Alert Time</th>
                                                        <th>Resolve Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php ;
                                                    foreach ($geofence_alerts as $key => $value) { ?>
                                                    <tr id="item<?php echo $value->alert_id; ?>" class="gradeU <?php if($value->viewed == 0){ echo "unread"; }else{ echo "read";} ?>">
                                                        <td><?php echo $value->plate_no; ?></td>
                                                        <td><?php echo ucwords($value->type); ?></td>
                                                        <td><?php echo $value->start_address; ?></td>
                                                        <td><?php echo $value->start_date; ?> | <b><span am-time-ago="<?php echo strtotime($value->start_date)*1000;?>"></span></b>
                                                        </td>
                                                        <td><?php if($value->status == 1){ ?>
                                                            <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                                                            <?php }else{ ?>
                                                            <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a onclick="view(<?php echo $value->alert_id;?>, <?php echo $value->alert_type_id;?>)" href="#view_alert<?php echo $value->alert_id;?>" data-toggle="modal" class="btn btn-primary btn-xs">View Details</a></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } else {?>
                                        <br>
                                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                            <h2><i class="fa fa-exclamation-triangle"></i> No Geofence Alerts</h2>
                                            <br>
                                            <p>Currently you have no geofence alerts</p>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Landmarks -->
                                    <div class="tab-pane" id="landmarks">
                                        <?php if (sizeof($landmark_alerts)) {?>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="geofence_table">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle Plate No.</th>
                                                        <th>Alert Type</th>
                                                        <th>Alert Location</th>
                                                        <th>Alert Time</th>
                                                        <th>Resolve Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php ;
                                                    foreach ($landmark_alerts as $key => $value) { ?>
                                                    <tr id="item<?php echo $value->alert_id; ?>" class="gradeU <?php if($value->viewed == 0){ echo "unread"; }else{ echo "read";} ?>">
                                                        <td><?php echo $value->plate_no; ?></td>
                                                        <td><?php echo ucwords($value->type); ?></td>
                                                        <td><?php echo $value->start_address; ?></td>
                                                        <td><?php echo $value->start_date; ?> | <b><span am-time-ago="<?php echo strtotime($value->start_date)*1000;?>"></span></b>
                                                        </td>
                                                        <td><?php if($value->status == 1){ ?>
                                                            <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                                                            <?php }else{ ?>
                                                            <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a onclick="view(<?php echo $value->alert_id;?>, <?php echo $value->alert_type_id;?>)" href="#view_alert<?php echo $value->alert_id;?>" data-toggle="modal" class="btn btn-primary btn-xs">View Details</a></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } else {?>
                                        <br>
                                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                            <h2><i class="fa fa-exclamation-triangle"></i> No Geofence Alerts</h2>
                                            <br>
                                            <p>Currently you have no geofence alerts</p>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Routes -->
                                    <div class="tab-pane" id="routes">
                                        <?php if (sizeof($route_alerts)) {?>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="geofence_table">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle Plate No.</th>
                                                        <th>Alert Type</th>
                                                        <th>Alert Location</th>
                                                        <th>Alert Time</th>
                                                        <th>Resolve Status</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <?php ;
                                                    foreach ($route_alerts as $key => $value) { ?>
                                                    <tr id="item<?php echo $value->alert_id; ?>" class="gradeU <?php if($value->viewed == 0){ echo "unread"; }else{ echo "read";} ?>">
                                                        <td><?php echo $value->plate_no; ?></td>
                                                        <td><?php echo ucwords($value->type); ?></td>
                                                        <td><?php echo $value->start_address; ?></td>
                                                        <td><?php echo $value->start_date; ?> | <b><span am-time-ago="<?php echo strtotime($value->start_date)*1000;?>"></span></b>
                                                        </td>
                                                        <td><?php if($value->status == 1){ ?>
                                                            <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                                                            <?php }else{ ?>
                                                            <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                                                            <?php } ?>
                                                        </td>
                                                        <td><a onclick="view(<?php echo $value->alert_id;?>, <?php echo $value->alert_type_id;?>)" href="#view_alert<?php echo $value->alert_id;?>" data-toggle="modal" class="btn btn-primary btn-xs">View Details</a></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } else {?>
                                        <br>
                                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                            <h2><i class="fa fa-exclamation-triangle"></i> No Geofence Alerts</h2>
                                            <br>
                                            <p>Currently you have no geofence alerts</p>
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
<?php require("view_alert.php"); ?>

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
            url: '<?= base_url('index.php/alerts/read_alert') ?>',
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
