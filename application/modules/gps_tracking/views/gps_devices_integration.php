<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="form-integrates">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   GPS Devices Integration
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group" id="vehicle">
	                        <label for="reservation">Select Vehicle:</label>
	                        <select class="form-control" type="text" name="asset_id" id="asset_id" required="required">
	                        	<option value="0">--Select--</option>
							  	<?php echo $vehicleOpt; ?>
							</select>
							<a style="float: right; margin-top: -28px; margin-right: 5px" href="<?php echo site_url('vehicles/add_vehicle');?>" class='btn btn-success btn-xs'>New Vehicle
                            	<span class='fa fa-plus'></span>
                            </a>
	                    </div>
	                    <div class="form-group" id="device">
	                        <label for="reservation">Available GPS Devices:</label>
	                        <select class="form-control" type="text" name="device_id" id="device_id" required="required">
	                        	<option value="0">--Select--</option>
							  	<?php echo $deviceOpt; ?>
							</select>
							<a style="float: right; margin-top: -28px; margin-right: 5px" href="<?php echo site_url('settings/add_device');?>" class='btn btn-success btn-xs'>New Device
                            	<span class='fa fa-plus'></span>
                            </a>
	                    </div>
	                    						                    
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="btn-integrate">Integrate</button>
                </div>
	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			
		</div>

    </div>
</div> 

<!--<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>-->
 

<script type="text/javascript">
        $(function () {
            $('#form-integrates').on('submit' , function () {

				$('#btn-integrate').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#btn-integrate').prop('disabled', true);
               

                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/gps_tracking/integrate') ?>',
                    data: $(this).serialize(),
                    success: function (response) {
                        	$('#form-integrates').find('input[type="text"]').val('');
                        	$('#form-integrates').find('select').val(0);
                        	swal({   title: "Info",   text: "Integration successfull",   type: "success",   confirmButtonText: "ok" });
                            window.base_url = <?php echo json_encode(base_url()); ?>;
                            $( "#vehicle" ).load( base_url + "index.php/gps_tracking/gps_devices_integration #vehicle" );
                            $( "#device" ).load( base_url + "index.php/gps_tracking/gps_devices_integration #device" );
                            
                        $('#btn-integrate').html('Integrate');
                		$('#btn-integrate').prop('disabled', false);
                     }
                });

                
                return false;     
            });

        });
    </script>     