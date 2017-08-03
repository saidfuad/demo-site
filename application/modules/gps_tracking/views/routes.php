<div class="container-fluid fleet-view">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($routes)) {?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Route Name</th>
                                <th>Start Address</th>
                                <th>Destination Address</th>
                                <th>Distance</th>
                                <th>Duration</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                        <?php 
                        $count = 1;
                        foreach ($routes as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $count; ?></td>
                                <td><?php echo $value->route_name; ?></td>
                                <td><?php echo $value->start_address; ?></td>
                                <td><?php echo $value->end_address; ?></td>
                                <td><?php echo $value->distance; ?></td>
                                <td><?php echo $value->duration; ?></td>
                                <td>
                                <?php echo "<a href='".base_url('index.php/gps_tracking/edit_route/'.$value->route_id)
                                                ."'class='btn btn-success btn-xs'><span class='fa fa-pencil'></span></a>
                                                <a href='".base_url('index.php/gps_tracking/delete_route/'.$value->route_id)
                                                ."'class='btn btn-danger btn-xs' id='del'><span class='fa fa-trash'onclick='javascript:return confirm(\"Are you sure you want to delete?\")'></span></a>
                                                "?>
                                </td>
                            </tr>
                        <?php $count++;}?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                 <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-map"></i> Routes</h2>
                    <br>
                    <p>No routes created yet.</p>
                     <br>
                    <a href="<?php echo site_url('settings/create_routes');?>" class="btn btn-success">Create Routes</a>
                </div>
            <?php } ?>

            </div>
        </div>
    </div>
</div>    

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>

<script>
// Initialize Loadie for Page Load
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
</script>
