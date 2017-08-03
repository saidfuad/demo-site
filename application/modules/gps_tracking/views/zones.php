<div class="container-fluid fleet-view">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($zones)) {?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Zone Name</th>
                                <th>Address</th>
                                <th>ACTION</th>
                            </tr>

                        </thead>
                        <tbody>
                        <?php 
                        $count = 1;
                        foreach ($zones as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $count; ?></td>
                                <td><?php echo $value->zone_name ?></td>
                                <td><?php echo $value->address; ?></td>
                                <td><?php echo "<a href='".base_url('index.php/gps_tracking/edit_zone/'.$value->zone_id)
                                                ."'class='btn btn-success btn-xs'><span class='fa fa-pencil'></span></a>
                                                <a href='".base_url('index.php/gps_tracking/disable_zone/'.$value->zone_id)
                                            ."'class='btn btn-warning btn-xs' id='del'><span class='fa fa-power-off' onclick='onclick='javascript:return confirm(\"Are you sure you want to disable?\")''></span></a>"?></td>
                            </tr>
                        <?php $count++; }?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                 <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-map"></i> Zones</h2>
                    <br>
                    <p>No Zones created yet.</p>
                     <br>
                    <a href="<?php echo site_url('settings/create_zones');?>" class="btn btn-success">Create Zones</a>
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
