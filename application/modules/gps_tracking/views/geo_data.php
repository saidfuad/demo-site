<div class="container-fluid fleet-view">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-md-12 col-lg-12">
                <ul class="nav nav-tabs">
                    <li data-toggle='tooltip' data-original-title="Create/view/edit all landmarks from here." class="<?=empty($_GET['vehicle_id']) ? 'active' : NULL ?>"><a data-toggle="tab" href="#home">All Landmarks</a></li>
                    <li data-toggle='tooltip' data-original-title="Create/view/edit all geofences from here." ><a data-toggle="tab" href="#menu1">All Geofences</a></li>
                    <li data-toggle='tooltip' data-original-title="Create/view/edit all routes from here."><a data-toggle="tab" href="#menu2">All Routes</a></li>
                    <li data-toggle='tooltip' data-original-title="Assign/edit assignment of landmarks/geofence/routes to a vehicle from here." class="<?=!empty($_GET['vehicle_id']) ? 'active' : NULL ?>" ><a data-toggle="tab" href="#menu3">Assigned</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in <?=empty($_GET['vehicle_id']) ? 'active' : NULL ?>">
                        <br />
                        <?php if (sizeof($landmarks)) { ?>
                        <div class="table-responsive">
                            <div>
                                <a data-toggle='tooltip' data-original-title="Create a landmark" href='<?= site_url('settings/create_landmarks') ?>' class='btn btn-primary pull-right btn-sm'>Create Landmark</a>
                            </div>
                            <br />
                            <br />
                            <table class="table table-striped table-hover" id="landmarks">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>                                       
                                        <th>Status</th>
                                        <th>Address</th>
                                        <th>Radius (km)</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                  $count = 1;
                                  foreach ($landmarks as $key => $value) {
                                    ?>
                                    <tr class="gradeU">
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo ($value->status == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php echo $value->address; ?></td>
                                        <td><?php echo $value->radius; ?></td>
                                        <td><?php
                                      echo "<a data-toggle='tooltip' data-original-title='Edit landmark details' href='" . base_url('index.php/gps_tracking/edit_geo_data/' . $value->geofence_id)
                                      . "'class='btn btn-primary btn-xs'>Edit Details</a>";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                      $count++;
                                  }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-map"></i>Landmarks</h2>
                            <br>
                            <p>No Landmarks created yet.</p>
                            <br>
                            <a data-toggle='tooltip' data-original-title="Create a landmark" href="<?php echo site_url('settings/create_landmarks'); ?>" class="btn btn-primary">Create Landmark</a>
                        </div>
                        <?php } ?>

                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <br />
                        <?php if (sizeof($geofences)) { ?>
                        <div class="table-responsive">
                            <div>
                                <a data-toggle='tooltip' data-original-title="Create a geofence" href='<?= site_url('settings/create_zones') ?>' class='btn btn-primary pull-right btn-sm'>Create Geofence</a>
                            </div>
                            <br />
                            <br />
                            <table class="table table-striped table-hover" id="geofence">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                  $count = 1;
                                  foreach ($geofences as $key => $value) {
                                    ?>
                                    <tr class="gradeU">
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->type; ?></td>
                                        <td><?php echo ($value->status == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php echo $value->address; ?></td>
                                        <td><?php
                                      echo "<a data-toggle='tooltip' data-original-title='Edit geofence details' href='" . base_url('index.php/gps_tracking/edit_geo_data/' . $value->geofence_id)
                                      . "'class='btn btn-primary btn-xs'>Edit Details</a>";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                      $count++;
                                  }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-map"></i>Geofence</h2>
                            <br>
                            <p>No Geofences created yet.</p>
                            <br>
                            <a data-toggle='tooltip' data-original-title="Create a geofence from here" href="<?php echo site_url('settings/create_zones'); ?>" class="btn btn-success">Create Geofence</a>
                        </div>
                        <?php } ?>

                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <br />
                        <?php if (sizeof($routes)) { ?>
                        <div class="table-responsive">
                            <div>
                                <a data-toggle='tooltip' data-original-title="Create a route from here." href='<?= site_url('settings/create_routes') ?>' class='btn btn-primary pull-right btn-sm'>Create Routes</a>
                            </div>
                            <br />
                            <br />
                            <table class="table table-striped table-hover" id="geofence">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                  $count = 1;
                                  foreach ($routes as $key => $value) {
                                    ?>
                                    <tr class="gradeU">
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->type; ?></td>
                                        <td><?php echo ($value->status == 1) ? "Active" : "Inactive"; ?></td>
                                        <td><?php
                                      echo "<a data-toggle='tooltip' data-original-title='Edit route details' href='" . base_url('index.php/gps_tracking/edit_geo_data/' . $value->geofence_id)
                                      . "'class='btn btn-primary btn-xs'>Edit Details</a>";
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                      $count++;
                                  }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-map"></i>Routes</h2>
                            <br>
                            <p>No Routes created yet.</p>
                            <br>
                            <a data-toggle='tooltip' data-original-title="Create a route" href="<?php echo site_url('settings/create_routes'); ?>" class="btn btn-success">Create Routes</a>
                        </div>
                        <?php } ?>

                    </div>                    
                    <div id="menu3" class="tab-pane fade in <?=!empty($_GET['vehicle_id']) ? 'active' : NULL ?>">
                        <br />
                        <?php if (sizeof($vehicle_geofence_assignment)) { ?>
                        <a data-toggle='tooltip' data-original-title="Assign a vehicle to a landmark/route/geofence" style="" href="<?php echo "" . site_url('vehicle_geofence/add_view') . ""; ?>" class="btn btn-primary btn-sm pull-right">
                            <i class="fa fa-plus"></i>
                            Assign Vehicle to Geofence/Landmark
                        </a>
                        <?php } ?>
                        <br>
                        <br>

                        <?php if (sizeof($vehicle_geofence_assignment)) { ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="assigned">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Geofence/Landmark/Route</th>
                                        <th>Type</th>
                                        <th>General Status</th>
                                        <th>Assigned By</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vehicle_geofence_assignment as $key => $value) { ?>
                                            <tr class="gradeU">
                                                <td><?php echo $value->plate_no; ?></td>
                                                <td><?php echo $value->name; ?></td>
                                                <td><?php echo $value->type; ?></td>
                                                <td><?php echo ($value->status == 1) ? "Active" : "Inactive"; ?></td>
                                                <td><?php echo $value->assign_uid; ?></td>
                                                
                                                <!-- <td><?php echo $value->assign_date; ?></td><td><?php echo $value->update_uid; ?></td>
                                                <td><?php echo $value->update_date; ?></td> -->
        <!--                                                        <td><?php echo ($value->in_alert == 1) ? "Active" : "Inactive"; ?></td>
                                                <td><?php echo ($value->out_alert == 1) ? "Active" : "Inactive"; ?></td>
                                                <td><?php echo ($value->sms_alert == 1) ? "Active" : "Inactive"; ?></td>
                                                <td><?php echo ($value->email_alert == 1) ? "Active" : "Inactive"; ?></td>-->
                                                <td><?php
                                                    echo "<a data-placement='top' data-toggle='tooltip' data-original-title='View Details'  href='" . base_url('index.php/vehicle_geofence/fetch_view/' . $value->id). "'class='btn btn-primary btn-xs'>View Details</span></a>    <a data-placement='top' data-toggle='tooltip' data-original-title='Edit Details'  href='" . base_url('index.php/vehicle_geofence/edit_view/' . $value->id)
                                                    . "'class='btn btn-primary btn-xs'>Edit Details</span></a> &nbsp; "
                                                            
                                                    ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php } else { ?>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-car"></i>Vehicle Geofence</h2>
                            <p>Add and manage your vehicles to geofence/landmark and begin monitoring their location.</p>
                            <br>
                            <a data-toggle='tooltip' data-original-title='Assign a vehicle to a landmark/geofence/route' href="<?php echo site_url('vehicle_geofence/add_view'); ?>" class="btn btn-success">Assign to Geofence/Landmarks</a>
                        </div>
                        <?php } ?>

                    </div>

                    <div id="menu3" class="tab-pane fade in <?=!empty($_GET['vehicle_id']) ? 'active' : NULL ?>">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="assigned">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Location</th>
                                        <th>Date</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js') ?>"></script>

<script>
    // Initialize Loadie for Page Load
    $(document).ready(function () {
        $('#landmarks,#geofence,#assigned').dataTable();
    });
</script>
