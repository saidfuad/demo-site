<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-group-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Edit Group Details
	                </div>
	                <div class="panel-body">
	                   <?php foreach ($groups as $value) { ?>
	                   <input class="form-control" type="hidden" name="group_id" id="group_id" value="<?php echo $value->group_id; ?>" required="required"/>
	                    <div class="form-group">
	                        <label for="reservation">Group Name <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="group_name" id="group_name" value="<?php echo $value->group_name; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Group Description:</label>
	                        <input height="50px" class="form-control" type="text" name="group_description" value="<?php echo $value->group_description; ?>" id="group_description">
	                    </div>
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="update-group">Update</button>
                </div>
                <?php } ?>
	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			<div class="col-md-12 bg-crumb" align="center">
				<h2><i class="fa fa-sitemap"></i> Groups</h2>
				<br>
				<p>a collection of vehicles that has a collective goal and is linked to a particular route/area/locaton.</p>

				<a href="<?php echo site_url('vehicles/groups');?>" class="btn btn-success">View Groups</a>	
			</div>
		</div>

    </div>
</div> 

<!--<script src="<?php echo  base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>   -->
<script>
        $(function () {

            $('#edit-group-form').on('submit' , function () {

                var $this = $(this);

				$('#update-group').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#update-group').prop('disabled', true);
               // alert ("yes");
               swal({   
                title: "Info",   
                text: "Edit Group?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true                            
                }, function(){
                    $.ajax({
                        method: 'post',
                        url: '<?= base_url('index.php/vehicles/update_vehicle_group') ?>',
                        data: $this.serialize(),
                        success: function (response) {
                            if (response==0) {
                            	swal({   title: "Info",   text: "Updated successfully",   type: "success",   confirmButtonText: "ok" });
                            } else if (response==1) {
                            	swal({   title: "Error",   text: "Failed to Update, Try again later",   type: "error",   confirmButtonText: "ok" });
                            } 
                            $('#update-group').html('Update');
                    		$('#update-group').prop('disabled', false);
                         }
                    });
                });


               $('#update-group').html('Update');
                $('#update-group').prop('disabled', false);
                
                return false;     
            });

        });
    </script>   