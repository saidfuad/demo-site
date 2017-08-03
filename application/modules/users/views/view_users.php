<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
             <a style="float:right; margin-right: 16px" data-original-title='Click here to add a user ' data-toggle='tooltip' href="<?php echo "".site_url('users/add_user').""; ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>
                Add Users
            </a>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($users)) {?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr data-toggle='tooltip' data-original-title='The table shows the list of users in your account. You can view and edit details of the users shown in the table'>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count = 1;
                    foreach ($users as $key => $value) { ?>
                        <tr class="gradeU">
                        <?php if($value->company_email==""){?>
                            <td><?php echo $value->first_name; ?></td>
                            <td><?php echo $value->last_name; ?></td>
                            <td><?php echo $value->phone_no; ?></td>
                            <td><?php
                            if(!empty($value->email)){
                                echo $value->email;
                            }else{
                                echo "Not Set";
                            } ?></td>
                            <td><?php if($value->user_type_id==2){echo "Admin";}else if($value->user_type_id==3){echo "Normal";}else if($value->user_type_id==4){echo "Installer";} ?></td>
                        <?php }  
                        else{?>
                            <td><?php echo $value->company_name; ?></td>
                            <td><?php echo "N/A"; ?></td>
                            <td><?php echo $value->company_phone_no; ?></td>
                            <td><?php
                            if(!empty($value->company_email)){
                                echo $value->company_email;
                            }else{
                                echo "Not Set";
                            } ?>
                            <td><?php if($value->user_type_id==2){echo "Admin";}else if($value->user_type_id==3){echo "Normal";}else if($value->user_type_id==4){echo "Installer";} ?></td>
                       <?php } ?>
                       <!-- <a data-original-title='Edit User'  href='".base_url('index.php/users/edit_user/'.$value->user_id)
                                                ."'class='btn btn-success btn-xs'>Edit</a> -->
                       <td><?php echo "<a data-original-title='Click here to view the details of this specific user' data-toggle='tooltip'  href='".base_url('index.php/users/fetch_user/'.$value->user_id)
                                                ."'class='btn btn-primary btn-xs'>View Details</a>"?></td>
                        </tr>
                    <?php 
                        $count++;
                    }?>
                    </tbody>
                </table>
            </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-sitemap"></i> users</h2>
                    <br>
                    <p>a collection of vehicles that has a collective goal and is linked to a particular route/area/locaton.</p>

                    <a href="<?php echo site_url('vehicles/add_group');?>" class="btn btn-success">Add users</a> 
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
