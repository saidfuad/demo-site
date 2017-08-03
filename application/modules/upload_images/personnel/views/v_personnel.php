<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-personnel-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Personnel Details
                        <?php foreach ($personnel as $value) { ?> 
                        <a href="<?= base_url('index.php/personnel/edit_personnel/' .$value->personnel_id); ?>">
                            <span class="edit_personnel btn btn-xs btn-success">Edit <i class="fa fa-pencil"></i></span>
                        </a>
	                </div>
	                <div class="panel-body">
	                    <input class="form-control" type="hidden" name="personnel_id" id="personnel_id" value="<?php echo $value->personnel_id; ?>" />
                        
                        <!-- Personnel Roles-->
                        <!--<div class="form-group">
	                        <label for="reservation">Personnel Role:</label>
	                        <input disabled class="form-control" type="text" name="role" id="role" value="<?php //echo $value->$rolesOpt; ?>" />
	                    </div>-->
                        
	                    <div class="form-group">
	                        <label for="reservation">ID No:</label>
	                        <input disabled class="form-control" type="text" name="id_no" id="id_no" value="<?php echo $value->id_no; ?>" />
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Firstname:</label>
	                        <input disabled class="form-control" type="text" name="fname" id="fname" value="<?php echo $value->fname; ?>" />
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Lastname:</label>
	                        <input disabled class="form-control" type="text" name="lname" id="lname" value="<?php echo $value->lname; ?>" />
	                    </div>
                        <div class="form-group">
	                        <label for="reservation">Gender:</label>
	                        <input disabled class="form-control" type="text" name="gender" id="gender" value="<?php echo $value->gender; ?>" />
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Phone:</label>
	                        <input disabled class="form-control" type="text" name="phone_no" id="phone_no" value="<?php echo $value->phone_no; ?>" />
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Email:</label>
	                        <input disabled class="form-control" type="text" name="email" id="email" value="<?php echo $value->email; ?>" />
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Address:</label>
	                        <input disabled class="form-control" type="text" name="address" id="address" value="<?php echo $value->address; ?>">
	                    </div>	                    
	                </div>
	                
	            </div>
	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			<div class="panel panel-default">
                <div class="panel-heading">
                    Personnel Photo Pic
                </div>
                <div class="panel-body">
                    <div id="dropzone">
				    	<img src="../../../uploads/personnel/128/<?php echo $value->thumbnail ?>" alt="profile_pic" />
				    </div>
                </div>
            </div>
<?php } ?>
            <div class="col-md-12 bg-crumb" align="center">
				<h2><i class="fa fa-users"></i> Personnel</h2>
				<br>
				<p>Manage drivers and other employees information. Assign roles to all personnel and permissions 
					to all users determining who accesses what and when </p>

				<a href="<?php echo site_url('personnel');?>" class="btn btn-success">View Personnel</a>	
			</div>
		</div>

    </div>
</div> 

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>

<script>
    $(function () {

            $('#edit-personnel-form').on('submit' , function () {

				$('#update-personnel').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#update-personnel').prop('disabled', true);
               // alert ("yes");

                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/personnel/update_personnel') ?>',
                    data: $(this).serialize(),
                    success: function () {
                        	swal({   title: "Info",   text: "Updated successfully",   type: "success",   confirmButtonText: "ok" });
                        $('#update-personnel').html('Update');
                		$('#update-personnel').prop('disabled', false);
                     }
                });

                
                return false;     
            });

        });
</script>     