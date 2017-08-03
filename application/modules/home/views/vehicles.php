<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($vehicles)) {?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Vehicle Name</th>
                                <th>Number Plate</th>
                                <th>Device ID</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Driver Name</th>
                                <!-- <th>Action</th> -->
                            </tr>

                        </thead>
                        <tbody>
                        <?php foreach ($vehicles as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $value->assets_friendly_nm; ?></td>
                                <td><?php echo $value->assets_name; ?></td>
                                <td><?php if (strlen($value->device_id)!=0) {echo $value->device_id;} else { echo 'Not Set';} ?></td>
                                <td><?php echo $value->assets_type_nm; ?></td>
                                <td><?php echo $value->assets_cat_name; ?></td>
                                <td><?php if (strlen($value->driver_name)!=0 || $value->driver_name != 0) {echo $value->driver_name;} else { echo 'Not Assigned';} ?></td>
                                <!-- <td><?php echo "<a href='".base_url('index.php/vehicles/delete_vehicle/'.$value->asset_id)
                                            ."'class='btn btn-danger btn-xs' id='del'><span class='fa fa-trash'onclick='javascript:return confirm(\"Are you sure you want to delete?\")'></span></a>
                                                <a href='".base_url('index.php/vehicles/fetch_vehicle/'.$value->asset_id)
                                                ."'class='btn btn-info btn-xs'><span class='fa fa-eye'></span></a>
                                                <a href='".base_url('index.php/vehicles/edit_vehicle/'.$value->asset_id)
                                                ."'class='btn btn-success btn-xs'><span class='fa fa-pencil'></span></a>"?></td> -->
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-car"></i> Vehicles</h2>
                    <br>
                    <p>Manage Vehicles and begin monitoring your assets Location, Fuel usage driver efficiency and schedule preventative maintenance</p>

                    <a href="<?php echo site_url('vehicles/add_vehicle');?>" class="btn btn-success">Add Vehicles</a> 
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
