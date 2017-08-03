<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?php if (sizeof($vehicles)) {
                ?>
                <a data-toggle='tooltip' data-original-title="Click to add a new vehicle" style="float:right; margin-right: 16px" href="<?php echo " ".site_url('vehicles/add_vehicle')." "; ?>" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add Vehicle </a>
            <?php } ?>
                    <br>
                    <br>
                    <div class="col-md-12 col-lg-12">
                        <?php if (sizeof($vehicles)) {?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dataTables-example">
                                    <thead>
                                        <tr data-toggle='tooltip' data-original-title='The table shows the list of your vehicles. You can view more details, view history and replay history movement of your vehicle.'>
                                            <!--  <th>Vehicle Model</th>-->
                                            <th>Number Plate</th>
                                            <th>Last Seen Address</th>
                                            <th>Last Seen Time</th>
                                            <!-- <th>Maximum Speed</th>-->
                                            <th>Device Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vehicles as $key => $value) { ?>
                                            <tr class="gradeU">
                                                <!-- <td><?php echo $value->model; ?></td>-->
                                                <td>
                                                    <?php echo strtoupper($value->plate_no); ?>
                                                </td>
                                                <td>
                                                    <?php if ($value->address != "") {
                                              echo $value->address;
                                          } else { ?> Not Available
                                                        <?php }?>
                                                </td>

                                                <td>
                                                    <?php if ($value->last_seen != "") {
                                              echo $value->last_seen;
                                          } else { ?> Not Available
                                                        <?php }?>
                                                </td>
                                                <!-- <td><?php echo number_format($value->max_speed_limit)." km/h"; ?></td>-->
                                                <td>
                                                    <?php if ($value->device_id!=0) {
                                              echo "Assigned";
                                          } else { ?> <b>Not Assigned</b>
                                                        <?php }?>
                                                </td>
                                                <td>
                                                    <?php echo "<a data-toggle='tooltip' data-original-title='Track the status of your vehicle, view its location, commands history and more detailed information' href='".base_url('index.php/vehicles/fetch_vehicle/'.$value->vehicle_id)
                                          ."'class='btn btn-primary btn-xs'>View Details</span></a> &nbsp;
                                        <a data-toggle='tooltip' data-original-title='Track the movement history of your vehicle' href='".base_url('index.php/gps_history/view_history/'.$value->vehicle_id)
                                        ."'class='btn btn-primary btn-xs' style='color:#fff;'> History</span></a> &nbsp;
                                         <a data-toggle='tooltip' data-original-title='Playback the history of your vehicle' href='".base_url('index.php/gps_history/view_playback/'.$value->vehicle_id)
                                        ."'class='btn btn-primary btn-xs' style='color:#fff;'> Playback</span></a> &nbsp;";
                                        /*if($value->alert_status == 1){ echo "<a href='".base_url('index.php/alerts/'.$value->vehicle_id)
                                        ."'class='btn btn-primary btn-xs' style='background: #E64A19; color:#FFF; border:0'>3 Alerts</span></a>"; }*/ ?></td>
                                            </tr>
                                            <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else {?>
                                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                    <h2><i class="fa fa-car"></i>Vehicles</h2>
                                    <p>Add and manage your vehicles or motorcycles and begin monitoring their location.</p>
                                    <br> <a href="<?php echo site_url('vehicles/add_vehicle');?>" class="btn btn-success">Add Vehicles</a> </div>
                                <?php } ?>
                    </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<script>
    // Initialize Loadie for Page Load
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
    //$('a').tooltip();
</script>
