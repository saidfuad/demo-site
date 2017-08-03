<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css') ?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-vehicle-form">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="vehicle_type_id">Vehicle Type<sup title="Required field">*</sup>:</label>
                            <select name="vehicle_type_id" class="form-control">
                                <option disabled="true" selected>Select vehicle type</option>
                            <?php
                                foreach($vehicle_types as $type){
                                    echo "<option value='".$type['id']."'>".$type['name']."</option>";
                                }
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reservation">Vehicle Model<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="model" id="model" />
                        </div>
                        <div class="form-group">
                            <label for="reservation">Plate Number <sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="plate_no" id="plate_no" />
                        </div>

                        <div class="form-group">
                            <label for="reservation">Maximum Speed:</label>
                            <input class="form-control" type="number" name="max_speed_limit" id="max_speed_limit"/>
                        </div>

                        <div class="form-group">
                            <div class="row">                                    
                                <div class="col-md-6">
                                    <label>Alert Types</label>
                                    <div class="row">
                                        <label for="arm" class="col-md-6">Arm:</label>
                                        <input class="col-md-6" type="hidden" name="arm" value="0"/>
                                        <input class="col-md-6" type="checkbox" name="arm" id="arm" value="1"/>
                                    </div>
                                    <div class="row">
                                        <label for="power_cut" class="col-md-6">Power Cut:</label>
                                        <input class="col-md-6" type="hidden" name="power_cut" value="0"/>
                                        <input class="col-md-6" type="checkbox" name="power_cut" id="power_cut" value="1"/>
                                    </div>
                                    <div class="row">
                                        <label for="overspeed" class="col-md-6">Overspeed:</label>
                                        <input class="col-md-6" type="hidden" name="overspeed" value="0"/>
                                        <input class="col-md-6" type="checkbox" name="overspeed" id="overspeed" value="1"/>
                                    </div>
                                </div>                                    
                                <div class="col-md-3">
                                    <label>SMS</label>
                                    <div class="row">
                                        <label class="col-md-2"></label>
                                        <input class="col-md-6" type="hidden" name="arm_sms" id="arm_sms" value="0"/>
                                        <input class="col-md-6 arm" type="checkbox" name="arm_sms" id="arm_sms" value="1"/>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-2"></label>
                                        <input class="col-md-6" type="hidden" name="power_cut_sms" id="power_cut_sms" value="0"/>
                                        <input class="col-md-6 power_cut" type="checkbox" name="power_cut_sms" id="power_cut_sms" value="1"/>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-2"></label>
                                        <input class="col-md-6" type="hidden" name="overspeed_sms" id="overspeed_sms" value="0"/>
                                        <input class="col-md-6 overspeed" type="checkbox" name="overspeed_sms" id="overspeed_sms" value="1"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Email</label>
                                    <div class="row">
                                        <label class="col-md-2"></label>
                                        <input class="col-md-6" type="hidden" name="arm_email" id="arm_email" value="0"/>
                                        <input class="col-md-6 arm" type="checkbox" name="arm_email" id="arm_email" value="1"/>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-2"></label>
                                        <input class="col-md-6" type="hidden" name="power_cut_email" id="power_cut_email" value="0"/>
                                        <input class="col-md-6 power_cut" type="checkbox" name="power_cut_email" id="power_cut_email" value="1"/>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-2"></label>
                                        <input class="col-md-6" type="hidden" name="overspeed_email" id="overspeed_email" value="0"/>
                                        <input class="col-md-6 overspeed" type="checkbox" name="overspeed_email" id="overspeed_email" value="1"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer " align="right">
                    <button class="btn btn-primary" type="submit" id="save-vehicle">Save </button>
                </div>


            </div>
        </form>
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default" style="display:none;">
                <div class="panel-heading">
                    Upload Vehicle Image
                </div>
                <div class="panel-body">
                    <div id="dropzone">
                        <form action="<?php echo base_url('index.php/upload_images/upload_vehicle_image') ?>" class="dropzone" id="dropzone-container">
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 bg-crumb" align="center">
                <h2><i class="fa fa-car"></i> Vehicles</h2>
                <br>
                <p>Manage Vehicles and begin monitoring your assets Location, Fuel usage driver efficiency and schedule preventative maintenance</p>

                <a href="<?php echo site_url('vehicles'); ?>" class="btn btn-primary">View Vehicles</a>	
            </div>

        </div>

    </div>
</div> 

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js') ?>"></script>  

<script>

    $(function () {

        $('#add-vehicle-form').on('submit', function () {

            var $this = $(this);

            if ($('#model').val().trim().length == 0 || $('#plate_no').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-vehicle').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-vehicle').prop('disabled', true);

            swal({
                title: "Info",
                text: "Add Vehicle?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/vehicles/save_vehicle') ?>',
                    data: $this.serialize(),
                    dataType: "JSON",
                    success: function (response) {
                        if (response.status == 1) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                    function () {
                                        document.location.href = "<?php echo base_url('index.php/vehicles') ?>";
                                    }
                            );

                        } else if (response.status == 0) {
                            swal({title: "Error", text: "Failed, Try again later", type: "error", confirmButtonText: "ok"});
                        } else if (response.status == 77) {
                            swal({title: "Info", text: "A vehicle with that plate number already exists", type: "error", confirmButtonText: "ok"});
                        }

                        $('#save-vehicle').html('Save');
                        $('#save-vehicle').prop('disabled', false);
                    }
                });
            });

            $('#save-vehicle').html('Save');
            $('#save-vehicle').prop('disabled', false);

            return false;
        });

        // Listen for click on toggle checkbox
        $('#arm').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.arm').each(function () {
                    this.checked = true;
                });
            } else {
                $('.arm').each(function () {
                    this.checked = false;
                });
            }
        });
        
        $('#power_cut').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.power_cut').each(function () {
                    this.checked = true;
                });
            } else {
                $('.power_cut').each(function () {
                    this.checked = false;
                });
            }
        });
        
        $('#overspeed').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.overspeed').each(function () {
                    this.checked = true;
                });
            } else {
                $('.overspeed').each(function () {
                    this.checked = false;
                });
            }
        });

    });
</script> 
