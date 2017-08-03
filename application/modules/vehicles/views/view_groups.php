<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <a style="float:right; margin-right: 16px" href="<?php echo "".site_url('vehicles/add_group').""; ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>
                Add Group
            </a>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($groups)) {?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count = 1;
                    foreach ($groups as $key => $value) { ?>
                        <tr class="gradeU">
                            <td><?php echo $value->group_name; ?></td>
                            <td><?php echo $value->group_description; ?></td>
                             <td><?php echo "<a data-placement='top' data-toggle='tooltip' data-original-title='Edit Group'  href='".base_url('index.php/vehicles/edit_group/'.$value->group_id)
                                                ."'class='btn btn-xs btn-success'><span class='fa  fa-pencil'></span></a>
                                                <a data-placement='top' data-toggle='tooltip' data-original-title='Delete Group'  href='".base_url('index.php/vehicles/delete_group/'.$value->group_id)
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
                    <h2><i class="fa fa-sitemap"></i> Groups</h2>
                    <br>
                    <p>a collection of vehicles that has a collective goal and is linked to a particular route/area/locaton.</p>

                    <a href="<?php echo site_url('vehicles/add_group');?>" class="btn btn-success">Add Groups</a> 
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
