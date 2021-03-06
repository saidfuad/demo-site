<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            
            <div class="col-md-12 col-lg-12">
            <?php if (sizeof($users)) {?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Email</th>
                            <th>Firt Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Ordered Devices</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $count = 1;
                    foreach ($users as $key => $value) { ?>
                        <tr class="gradeU">
                        <?php if($value->company_email==""){?>
                            <td><?php echo $value->account_name; ?></td>
                            <td><?php echo $value->email; ?></td>
                            <td><?php echo $value->first_name; ?></td>
                            <td><?php echo $value->last_name; ?></td>
                            <td><?php echo $value->phone_no; ?></td>
                            
                        <?php }  
                        else{?>
                            <td><?php echo $value->account_name; ?></td>
                            <td><?php echo $value->company_email; ?></td>
                            <td><?php echo $value->company_name; ?></td>
                            <td><?php echo "N/A"; ?></td>
                            <td><?php echo $value->company_phone_no; ?></td>
                           
                       <?php } ?>
                       <td><?php 
                       $devices = $this->mdl_devices->get_devices_client($value->account_id);
                        echo $devices;
                       ?></td>
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