<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-device-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Device Details
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group">
	                        <label for="reservation">Device ID:</label>
	                        <input class="form-control" type="text" name="device_id" id="device_id" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Device Name:</label>
	                        <input class="form-control" type="text" name="device_name" id="device_name" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Serial Number:</label>
	                        <input class="form-control" type="text" name="serial_no" id="serial_no" required="required"/>
	                    </div>               
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="save-device">Save</button>
                </div>

	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			<div class="col-md-12 bg-crumb" align="center">
				<h2><i class="fa fa-user-plus	"></i> Devices</h2>
				<br>
				<p>These are the devices used to transmit data from tyres and axles to the sytem in order to be viewed as live data by clients..</p>

				<a href="<?php echo site_url('settings/devices');?>" class="btn btn-success">View Devices</a>	
			</div>
		</div>

    </div>
</div> 

<!--<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>-->

<script>
    $(function () {

        $('#add-device-form').on('submit' , function () {

			if ($('#device_id').val().trim().length==0 || $('#device_name').val().trim().length==0 ||
						$('#serial_no').val().trim().length ==0 ) {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

			$data = $(this).serialize();

			 swal({   
                title: "Info",   
                text: "Add new device?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true                            
                }, function(){

					$('#save-device').html('<i class="fa fa-spinner fa-spin"></i>');
		            $('#save-device').prop('disabled', true);



		            $.ajax({
		                method: 'post',
		                url: '<?= base_url('index.php/settings/save_device') ?>',
		                data: $data,
		                success: function (response) {
		                    if (response==0) {
		                    	$('#add-device-form').find('input[type="text"]').val('');
		                    	$('#add-group-form').find('select').val(0);
		                    	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });
		                    } else if (response==1) {
		                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
		                    } else if (response==77) {
		                    	swal({   title: "Info",   text: "Device already exists",   type: "error",   confirmButtonText: "ok" });
		                    } else if (response==78) {
		                    	swal({   title: "Info",   text: "Serial number already exists",   type: "error",   confirmButtonText: "ok" });
		                    }

		                    $('#save-device').html('Save');
		            		$('#save-device').prop('disabled', false);
		                 }
            		});
            	});

            
            return false;     
        });

    });
</script>        