<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?php if (sizeof($vehicle_geofence_assignment)) { ?>
                <a style="float:right; margin-right: 16px" href="<?php echo "" . site_url('vehicle_geofence/add_view') . ""; ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>
                    Add to Geofence/Landmark
                </a>
            <?php } ?>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">

                <?php if (sizeof($vehicle_geofence_assignment)) { ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Geofence/Landmark/Route</th>
                                    <th>Type</th>
                                    <th>General Status</th>
                                    <th>Assigned By</th>
                                    <th>Assign Date</th>
                                    <th>Updated By</th>
                                    <th>Update Date</th>
                                    <th>In Alert</th>
                                    <th>Out Alert</th>
                                    <th>SMS Alert</th>
                                    <th>Email Alert</th>
                                    <th>Action</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php foreach ($vehicle_geofence_assignment as $key => $value) { ?>
                                    <tr class="gradeU">
                                        <td><?php echo $value->plate_no; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->type; ?></td>
                                        <td><?php echo  ($value->status == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php echo $value->assign_uid; ?></td>
                                        <td><?php echo $value->assign_date; ?></td>
                                        <td><?php echo $value->update_uid; ?></td>
                                        <td><?php echo $value->update_date; ?></td>
                                        <td><?php echo ($value->in_alert == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php echo ($value->out_alert == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php echo ($value->sms_alert == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php echo ($value->email_alert == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php
                                            echo "<a data-placement='top' data-toggle='tooltip' data-original-title='View Vehicle'  href='" . base_url('index.php/vehicle_geofence/edit_view/' . $value->id)
                                            . "'class='btn btn-primary btn-xs'>Edit Details</span></a>"
                                            ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                        <h2><i class="fa fa-car"></i> Vehicle Geofence</h2>
                        <p>Add and manage your vehicles to geofence/landmark and begin monitoring their location.</p>
                        <br>
                        <a href="<?php echo site_url('vehicle_geofence/add'); ?>" class="btn btn-success">Add to Geofence/Landmarks</a> 
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>    

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js') ?>"></script>

<script>
// Initialize Loadie for Page Load
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });

    //$('a').tooltip();
</script>

