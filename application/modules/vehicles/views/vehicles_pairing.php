<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form>
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   Vehicle Pairing
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group">
	                        <label for="reservation">Select Towing Vehicle:</label>
	                        <select class="form-control" type="text" name="asset_id_one" id="asset_id_one" required="required">
	                        	<?php echo $optVehicles; ?>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Select vehicle (trailer):</label>
	                        <select class="form-control" type="text" name="asset_id_two" id="asset_id_two" required="required">
	                        	<?php echo $optVehicles; ?>
	                        </select>
	                    </div>
	                    
	                    						                    
	                </div>
	                <div class="panel-footer" align="right">
	                	<button class="btn btn-primary" type="submit">Pair Vehicles</button>
	                </div>
	            </div>

	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			
		</div>

    </div>
</div> 

<!--<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>-->
    