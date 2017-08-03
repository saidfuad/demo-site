<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-personnel-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Personnel Details
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group">
	                        <label for="reservation">Role <sup>*</sup>:</label>
	                       
	                        <select class="form-control" type="text" name="role_id" id="role_id">
	                        	<option value="0">--Select--</option>
	                        	<?php echo $rolesOpt; ?>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">ID No <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="id_no" id="id_no"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Firstname <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="fname" id="fname"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Lastname <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="lname" id="lname"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Gender <sup>*</sup>:</label>
	                        <select class="form-control" type="text" name="gender" id="gender">
	                        	<option value="">--Select--</option>
	                        	<option value="Male">Male</option>
								<option value="Female">Female</option>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Phone <sup>*</sup>:</label>
	                    </div>
	                    <div class="input-group" style="margin-bottom: 20px; margin-top: -15px;">
						  <span class="input-group-addon" id="basic-addon1">+254</span>
						  <input type="text" class="form-control" name="phone_no" id="phone_no">
						</div>
	                    <div class="form-group">
	                        <label for="reservation">Email <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="email" id="email"/>
	                    </div>
	                    
	                    <div class="form-group">
	                        <label for="reservation">Address:</label>
	                        <textarea class="form-control" type="text" name="address" id="address" rows="3"></textarea>
	                    </div>
	                    
	                    
							                    
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="save-personnel">Save</button>
                </div>
	           
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

<script type="text/javascript">
    $(function () {

        $('#add-personnel-form').on('submit' , function () {

        	// alert('getting in the submit');

			if ($('#role_id').val().trim() == '' || $('#id_no').val().trim() == '' || 
					$('#fname').val().trim() == '' || $('#lname').val().trim() == '' ||
						 $('#gender').val().trim() == ''  || $('#phone_no').val().trim()== '' ||
							$('#email').val().trim() == '') {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

			//$('#save-personnel').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-personnel').prop('disabled', true);

			var str1 = "+254";
			str2 = document.getElementById('phone_no').value;

				while( str2.charAt( 0 ) === '0' )
	    		str2 = str2.slice( 1 );

				var res = str1.concat(str2);

				var elem = document.getElementById("phone_no"); 
				var e = elem.value = res;

			var $this = $(this);
				
            if (e.length == 13){
            	swal({   
                title: "Info",   
                text: "Add Personnel?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true                            
                }, function(){
			
	            $.ajax({
	            	
	                method: 'post',

	                url: '<?= base_url('index.php/personnel/save_personnel') ?>',

	                data: $this.serialize(),

	                success: function (response) {

	                    if (response==1) {

	                    	$('#add-personnel-form').find('input[type="text"]').val('');
	                    	$('#add-personnel-form').find('select').val(0);
	                    	$('#add-personnel-form').find('textarea').val('');
	                    	
	                    	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });
	                    } 
	                    else if (response==0) {

	                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
	                    } 
	                    else if (response==77) {

	                    	swal({   title: "Info",   text: "ID No. already exists",   type: "error",   confirmButtonText: "ok" });
	                    } 
	                    else if (response==78) {

	                    	swal({   title: "Info",   text: "Phone number already exists",   type: "error",   confirmButtonText: "ok" });
	                    } 
	                    else if (response==79) {

	                    	swal({   title: "Info",   text: "Email already exists",   type: "error",   confirmButtonText: "ok" });
	                    }

	                    $('#save-personnel').html('Save');
	            		$('#save-personnel').prop('disabled', false);
	                 }
	            });
	        });
            
            }
            else{
            	$('#save-personnel').html('Save');
            	$('#save-personnel').prop('disabled', false);

            	document.getElementById('phone_no').value="";
            	swal({   title: "Info",   text: "Wrong Phone number",   type: "error",   confirmButtonText: "ok" });
            }

            return false;     
        });

    });
</script>
