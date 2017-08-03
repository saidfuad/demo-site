<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
          <?php if (sizeof($saccos)) {?>
             <a style="float:right; margin-right: 16px" href="<?php echo "".site_url('admin/add_sacco').""; ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>
                Add SACCO
            </a>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">
          
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>SACCO Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
 
                    foreach ($saccos as $key => $value) { ?>
                        <tr class="gradeU">
                         <td><?php echo $value->company_name; ?></td>
                        <?php if($value->company_email==""){?>
                           <td><?php echo "N/A"; ?></td>
                        <?php }   else{?>
                       <td><?php echo $value->company_email; ?></td>
           <?php } ?>
          <td><?php echo $value->company_phone_no; ?></td>
                    <td><?php echo "<a data-original-title='Edit SACCO'  href='".base_url('index.php/admin/edit_sacco/'.$value->user_id)
                                                ."'class='btn btn-success btn-xs'><span class='fa fa-pencil' style='color:black;'>Edit</span></a>
                                                
                                                "?></td>
                        </tr>
                    <?php 
                   
                    }?>
                    </tbody>
                </table>
            </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-sitemap"></i> SACCOs</h2>
                    <br>
                  
                    <a href="<?php echo site_url('admin/add_sacco');?>" class="btn btn-success">Add SACCO</a> 
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