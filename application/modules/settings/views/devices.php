<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($devices)) {?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Serial Number</th>
                            <th>Device Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count = 1;
                    foreach ($devices as $key => $value) { ?>
                        <tr class="gradeU">
                            <td><?php echo $value->device_id; ?></td>
                            <td><?php echo $value->serial_no; ?></td>
                            <td><?php echo $value->device_name; ?></td>
                            <td><?php if ($value->assigned == 1) {
                                echo "Assigned";
                            }else echo "Not Assigned"; ?></td>
                             <td><?php echo "<a data-placement='top' data-toggle='tooltip' data-original-title='Edit Device' href='".base_url('index.php/settings/edit_device/'.$value->id)
                                                ."'class='btn btn-xs btn-success'><span class='fa  fa-pencil'></span></a>
                                                <a data-placement='top' data-toggle='tooltip' data-original-title='Delete Device'  href='".base_url('index.php/settings/delete_device/'.$value->id)
                                            ."'class='btn btn-xs btn-danger'><span class='fa fa-trash'onclick='javascript:return confirm(\"Are you sure you want to delete?\")'></span></a>
                                                "?></td>
                        </tr>
                    <?php 
                        $count++;
                    }?>
                    </tbody>
                </table>
            </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-sitemap"></i> devices</h2>
                    <br>
                    <p>a collection of vehicles that has a collective goal and is linked to a particular route/area/locaton.</p>

                    <a href="<?php echo site_url('vehicles/add_group');?>" class="btn btn-success">Add devices</a> 
                </div>
            <?php }?>
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
