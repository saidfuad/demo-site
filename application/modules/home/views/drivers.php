<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <div class="col-md-12 col-lg-12">
                <?php if (sizeof($personnel)) {?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Role</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($personnel as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $value->fname; ?></td>
                                <td><?php echo $value->lname; ?></td>
                                <td><?php echo $value->role_name; ?></td>
                                <td><?php echo $value->gender; ?></td>
                                <td><?php echo $value->phone_no; ?></td>
                                 <!-- <td><?php echo "<a href='".base_url('index.php/personnel/delete_personnel/'.$value->personnel_id)
                                            ."'class='btn btn-xs btn-danger'><span class='fa fa-trash'onclick='javascript:return confirm(\"Are you sure you want to delete?\")'></span></a>
                                                <a href='".base_url('index.php/personnel/edit_personnel/'.$value->personnel_id)
                                                ."'class='btn btn-xs btn-success'><span class='fa  fa-pencil'></span></a>"?></td> -->
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
                <?php } else {?>
                    <div class="col-sm-6 col-md-8 col-md-offset-2 bg-crumb" align="center">
                        <h2><i class="fa fa-users"></i> Personnel</h2>
                        <br>

                        <p>Manage drivers and other employees information. Assign roles to all personnel and permissions 
                            to all users determining who accesses what and when </p>

                        <a href="<?php echo site_url('personnel/add_personnel');?>" class="btn btn-success">Add Personnel</a>    
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