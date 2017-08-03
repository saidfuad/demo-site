<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-personnel-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Personnel Details
	                </div>
	                <div class="panel-body">
	                    <?php foreach ($personnel as $value) { ?> 
	                    <input class="form-control" type="hidden" name="personnel_id" id="personnel_id" value="<?php echo $value->personnel_id; ?>" required="required"/>	
	                    <div class="form-group">
	                        <label for="reservation">Role <sup>*</sup>:</label>
	                        <select class="form-control" type="text" name="role_id" id="role_id" required="required">
	                        	<option value="0">--Select--</option>
	                        	<?php echo $rolesOpt; ?>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">ID No <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="id_no" id="id_no" value="<?php echo $value->id_no; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Firstname <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="fname" id="fname" value="<?php echo $value->fname; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Lastname <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="lname" id="lname" value="<?php echo $value->lname; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Gender <sup>*</sup>:</label>
	                        <select class="form-control" type="text" name="gender" id="gender" required="required">
	                        	<option value="0"><?php echo $value->gender; ?></option>
	                        	<option value="Male">Male</option>
								<option value="Female">Female</option>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Phone <sup>*</sup>:</label>
                            <input type="text" class="form-control" name="phone_no" id="phone_no" value="<?php echo $value->phone_no; ?>" required="required">
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Email <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="email" id="email" value="<?php echo $value->email; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Address:</label>
	                        <input class="form-control" type="text" name="address" id="address" value="<?php echo $value->address; ?>">
	                    </div>
	                                      
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="update-personnel">Update</button>
                </div>
	           <?php } ?>
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			<div class="panel panel-default">
                <div class="panel-heading">
                    Upload Personnel Photo here
                </div>
                <div class="panel-body">
                    <div id="dropzone">
				    	<form action="<?php echo base_url('index.php/upload_images/upload_personnel_image') ?>" class="dropzone" id="dropzone-container">
				    		<div class="fallback">
				    	    	<input name="file" type="file" multiple />
				    	  	</div>
				    	</form>
				    </div>
                </div>
            </div>

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

            	if ($('#role_id').val().trim() == '' || $('#id_no').val().trim() == '' || 
					$('#fname').val().trim() == '' || $('#lname').val().trim() == '' ||
						 $('#gender').val().trim() == ''  || $('#phone_no').val().trim()== '' ||
							$('#email').val().trim() == '') {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
				}

				$('#update-personnel').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#update-personnel').prop('disabled', true);
               // alert ("yes");
    //            var str1 = "+254";
				// str2 = document.getElementById('phone_no').value;

				// while( str2.charAt( 0 ) === '0' )
	   //  		str2 = str2.slice( 1 );

				// var res = str1.concat(str2);

				var e = document.getElementById("phone_no"); 
				// var e = elem.value = res;
				// alert(e.value.length);

				if (e.value.length == 13){
	                
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

	            } else {
	            	$('#update-personnel').html('Update');
	                $('#update-personnel').prop('disabled', false);
	                // document.getElementById('phone_no').value="";
	            	swal({   title: "Info",   text: "Wrong Phone number",   type: "error",   confirmButtonText: "ok" });
	            }

                return false;     
            });

        });
</script>       