<div id="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Menus</th>
                            <th>Reports</th>
                            <th>Assigned Vehicle Groups</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                   $count = 1;
                    foreach ($users as $key => $value) { ?>
                       <tr class="gradeU">
                            <td><?php echo $value->first_name . " " .$value->last_name; ?></td>
                            <td><?php echo $value->menu_permissions; ?></td>
                            <td><?php echo $value->report_permissions; ?></td>
                            <td><?php echo $value->assigned_groups; ?></td>
                            <td><a class="btn btn-primary btn-xs btn-block" href="<?php echo site_url('settings/edit_permissions') .'/'. $value->user_id; ?>">Edit Permissions</a></td>
                        </tr>
                    <?php 
                        $count++;
                    }?>
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