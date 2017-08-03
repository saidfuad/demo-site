<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-device-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Device Details
	                </div>
	                <div class="panel-body">
	                    <?php foreach ($devices as $value) { ?>
	                    <input class="form-control" type="hidden" name="device_id" id="device_id" value="<?php echo $value->id; ?>" />
	                    <div class="form-group">
	                        <label for="reservation">Device ID:</label>
	                        <input class="form-control" type="text" name="device_id" id="device_id" value="<?php echo $value->device_id; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Device Name:</label>
	                        <input class="form-control" type="text" name="device_name" id="device_name" value="<?php echo $value->device_name; ?>" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Serial Number:</label>
	                        <input class="form-control" type="text" name="serial_no" id="serial_no" value="<?php echo $value->serial_no; ?>" required="required"/>
	                    </div>               
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="edit-device">Update</button>
                </div>

	           <?php } ?>
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

        $('#edit-device-form').on('submit' , function () {

			if ($('#device_id').val().trim().length==0 || $('#device_name').val().trim().length==0 ||
						$('#serial_no').val().trim().length ==0 ) {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

				$('#edit-device').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#edit-device').prop('disabled', true);

            $.ajax({
                method: 'post',
                url: '<?= base_url('index.php/settings/update_device') ?>',
                data: $(this).serialize(),
                success: function (response) {
                    if (response==1) {
                    	swal({   title: "Info",   text: "Updated successfully",   type: "success",   confirmButtonText: "ok" });
                    } else if (response==0) {
                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                    }

                    $('#edit-device').html('Update');
            		$('#edit-device').prop('disabled', false);
                 }
            });

            
            return false;     
        });

    });
</script>        