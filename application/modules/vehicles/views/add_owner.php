<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-owner-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Owner Details
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group">
	                        <label for="reservation">Owner Name:</label>
	                        <input class="form-control" type="text" name="owner_name" id="owner_name" required="required"/>
	                    </div>
	                     <div class="form-group">
	                        <label for="reservation">Phone <sup>*</sup>:</label>
	                        <!-- <input class="form-control" type="text"> -->
	                    </div>
	                    <div class="input-group" style="margin-bottom: 20px; margin-top: -15px;">
						  <span class="input-group-addon" id="basic-addon1">+254</span>
						  <input type="text" class="form-control" aria-describedby="basic-addon1" name="phone_no" id="phone_no" required="required">
						</div>
	                    <div class="form-group">
	                        <label for="reservation">Email:</label>
	                        <input class="form-control" type="text" name="email" id="email" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Address:</label>
	                        <textarea class="form-control" type="text" name="address" id="address" required="required" row="3"></textarea>
	                    </div>                
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="save-owner">Save</button>
                </div>

	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			<div class="col-md-12 bg-crumb" align="center">
				<h2><i class="fa fa-user-plus	"></i> Owners</h2>
				<br>
				<p>These are individuals/companies who own the Vehicle/Assets in the company. 
				Can be the company itself (Owned) and or other external individuals or companies (Leased or rented).</p>

				<a href="<?php echo site_url('owners');?>" class="btn btn-success">View Owners</a>	
			</div>
		</div>

    </div>
</div> 

<!--<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>-->

<script>
    $(function () {

        $('#add-owner-form').on('submit' , function () {

        	var owner_name = $('#owner_name').val().trim();
        	// var phone_no = $('#phone_no').val().trim();
        	var email = $('#email').val().trim();
        	var address = $('#address').val().trim();
        	var $this = $(this);

			if ($('#owner_name').val().trim().length==0 || $('#phone_no').val().trim().length==0 ||
						$('#email').val().trim().length ==0 || $('#address').val().trim().length ==0) {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

			var str1 = "+254";
            str2 = document.getElementById('phone_no').value;

            while( str2.charAt( 0 ) === '0' )
            str2 = str2.slice( 1 );

            var res = str1.concat(str2);

			$('#save-owner').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-owner').prop('disabled', true);

            var $this = $(this);
            if (res.length == 13){

            swal({   
                title: "Info",   
                text: "Add Owner?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true                            
                }, function(){
       
		            $.ajax({
		                method: 'post',
		                url: '<?= base_url('index.php/owners/save_owner') ?>',
		                data: $this.serialize(),
		                success: function (response) {
		                    if (response==1) {
		                    	$('#add-owner-form').find('input[type="text"]').val('');
		                    	$('#add-owner-form').find('select').val(0);
		                    	$('#add-owner-form').find('textarea').val('');
		                    	$('div.dz-success').remove();
		                    	$('div.dz-message').show();	
		                    	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });
		                    } else if (response==0) {
		                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
		                    } else if (response==77) {
		                    	swal({   title: "Info",   text: "Phone number already exists",   type: "error",   confirmButtonText: "ok" });
		                    } else if (response==78) {
		                    	swal({   title: "Info",   text: "Email already exists",   type: "error",   confirmButtonText: "ok" });
		                    }

		                    $('#save-owner').html('Save');
		            		$('#save-owner').prop('disabled', false);
		                 }
		            });
		        });
            	$('#save-owner').html('Save');
		        $('#save-owner').prop('disabled', false);
		            }
            else{
            	swal({   title: "Info",   text: "Wrong Phone number",   type: "error",   confirmButtonText: "ok" });
                $('#save-client').html('Save');
            	$('#save-client').prop('disabled', false);
            }
            
            
            return false;     
        });

    });
</script>
