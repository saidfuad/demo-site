<div id="container-fluid" id="page-wrapper">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <a style="float:right; margin-right: 16px" href="<?php echo "".site_url('vehicles/add_owner').""; ?>" class="btn btn-primary btn-xsm">
                <i class="fa fa-plus"></i>
                Add Owner
            </a>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Owner Name</th>
                            <th>Phone No.</th>
                            <th>Email</th>
                            <th>Address</th>
                            <!--<th>Vehicle Plate No.</th>-->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count = 1;
                    foreach ($owners as $key => $value) { ?>
                       <tr class="gradeU">
                            <td><?php echo $value->owner_name; ?></td>
                            <td><?php echo "+254".$value->phone_no; ?></td>
                            <td><?php echo $value->email; ?></td>
                            <td><?php echo $value->address; ?></td>
                            <!--<td><?php echo $value->assets_name; ?></td>-->
                            <td><?php if ($value->status == 1) {echo 'Active';}else {echo 'Inactive';}?></td>
                             <td><?php echo "<a data-placement='top' data-toggle='tooltip' data-original-title='Edit Owner'  href='".base_url('index.php/vehicles/edit_owner/'.$value->owner_id)
                                                ."'class='btn btn-xs btn-success'><span class='fa fa-pencil'></span></a>
                                                <a data-placement='top' data-toggle='tooltip' data-original-title='Delete Owner' href='".base_url('index.php/vehicles/delete_owner/'.$value->owner_id)
                                            ."'class='btn btn-xs btn-danger'><span class='fa fa-trash'onclick='javascript:return confirm(\"Are you sure you want to delete?\")'></span></a>
                                                "?></td>
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
