<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-owner-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Edit Owner Details
	                </div>
	                <div class="panel-body">
	                   <?php foreach ($owners as $value) { ?>
	                   <input class="form-control" type="hidden" name="owner_id" id="owner_id" value="<?php echo $value->owner_id; ?>" required="required"/>
	                    <div class="form-group">
	                        <label for="reservation">Owner Name:</label>
	                        <input class="form-control" type="text" name="owner_name" id="owner_name" value="<?php echo $value->owner_name; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Phone <sup>*</sup>:</label>
	                    </div>
	                    <div class="input-group" style="margin-bottom: 20px; margin-top: -15px;">
						  <span class="input-group-addon" id="basic-addon1">+254</span>
						  <input type="text" class="form-control" aria-describedby="basic-addon1" name="phone_no" id="phone_no" value="<?php echo $value->phone_no; ?>" required="required">
						</div>
	                    <div class="form-group">
	                        <label for="reservation">Email:</label>
	                        <input class="form-control" type="text" name="email" id="email" value="<?php echo $value->email; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Address:</label>
	                        <input class="form-control" type="text" name="address" id="address" value="<?php echo $value->address; ?>" required="required"></input>
	                    </div>               
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="update-owner">Update</button>
                </div>
				<?php } ?>
	           
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

            $('#edit-owner-form').on('submit' , function () {

            	var $this = $(this);

				$('#update-owner').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#update-owner').prop('disabled', true);
               // alert ("yes");
                var str1 = "+254";
      			str2 = document.getElementById('phone_no').value;

      			while( str2.charAt( 0 ) === '0' )
          		str2 = str2.slice( 1 );

      			var res = str1.concat(str2);
				if (res.length == 13){
				swal({   
	                title: "Info",   
	                text: "Edit Owner?",   
	                type: "info",   
	                showCancelButton: true,   
	                closeOnConfirm: false, 
	                allowOutsideClick: false,  
	                showLoaderOnConfirm: true                            
	                }, function(){
		                $.ajax({
		                    method: 'post',
		                    url: '<?= base_url('index.php/owners/update_owner') ?>',
		                    data: $this.serialize(),
		                    success: function () {
		                      swal({   title: "Info",   text: "Updated successfully",   type: "success",   confirmButtonText: "ok" });
		                              $('#update-owner').html('Update');
		                		    $('#update-owner').prop('disabled', false);
		                     }
		                });
		            });
	            }
            else{

            	$('#update-owner').html('Update');
                $('#update-owner').prop('disabled', false);
            	swal({   title: "Error",   text: "Check your Phone number",   type: "error",   confirmButtonText: "ok" });
            }

            	$('#update-owner').html('Update');
		        $('#update-owner').prop('disabled', false);

                return false;     
            });

        });
</script>
