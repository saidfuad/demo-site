<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <a style="float:right; margin-right: 16px" href="<?php echo "".site_url('settings/create_landmarks').""; ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>
                Add Landmark
            </a>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Landmark Name</th>
                                <th>Device ID</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($landmark as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $value->landmark_name; ?></td>
                                <td><?php if (strlen($value->device_ids)!=0) {echo $value->device_ids;} else { echo 'Not Set';} ?></td>
                                <td><?php echo $value->latitude; ?></td>
                                <td><?php echo $value->longitude; ?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
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