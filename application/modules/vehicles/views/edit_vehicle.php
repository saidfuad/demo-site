<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css') ?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-vehicle-form">
            <div class="col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <input class="form-control" type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>" />
                            <div class="form-group">
                                <label for="reservation">Vehicle Model <sup title="Required field">*</sup>:</label>
                                <input class="form-control" type="text" name="model" id="model" value="<?php echo $vehicle['model']; ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label for="reservation">Plate Number <sup title="Required field">*</sup>:</label>
                                <input class="form-control" type="text" name="plate_no" id="plate_no" value="<?php echo $vehicle['plate_no']; ?>" readonly/>
                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reservation">Maximum Speed:</label>
                                <input class="form-control" type="number" name="max_speed_limit" id="max_speed_limit"  value="<?php echo $vehicle['max_speed_limit']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">                            
                            <div class="form-group">
                                <div class='row'> 
                                    <div class='col-md-7'>
                                        <label class=''>Alert Types</label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label class=''>SMS</label>
                                    </div>
                                    <div class='col-md-2'>
                                        <label class=''>Email</label>
                                    </div>
                                </div>
                                <?php
                                foreach ($alert_prefs as $value) {
                                    $name = str_replace(' ', '', $value['name']);
                                    echo "<div class='row'> 
                                        <input name='id_" . $name . "' type='hidden' value='" . $value['id'] . "'/>
                                    <div class='col-md-7'>         
                                        <div class='row'>
                                            <label for='' class='col-md-6'>" . ucwords($value['name']) . ":</label>
                                            <input class='col-md-6' type='hidden' name='" . $name . "' value='0'/>
                                            <input class='col-md-6' type='checkbox' id='" . $name . "' name='" . $name . "' checked='" . ((($value['sms_alert'] == 1) && ($value['sms_alert'] == 1)) ? 1 : 0) . "' value='1'/>
                                        </div>
                                    </div>                                    
                                    <div class='col-md-2 pull-right'>
                                        <div class='row'>
                                            <input class='col-md-6' type='hidden' name='" . $name . "_sms' value='0'/>
                                            <input class='col-md-6 " . $name . "' type='checkbox' name='" . $name . "_sms' checked='" . $value['sms_alert'] . "' value='1'/>
                                        </div>
                                    </div>
                                    <div class='col-md-2 pull-right'>
                                        <div class='row'>
                                            <input class='col-md-6' type='hidden' name='" . $name . "_email' value='0'/>
                                            <input class='col-md-6 " . $name . "' type='checkbox' name='" . $name . "_email' checked='" . $value['email_alert'] . "' value='1'/>
                                        </div>
                                    </div>
                                </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer " align="right">
                    <button data-toggle='tooltip' data-original-title='Click to update the changes you have made for your vehicle.' class="btn btn-primary" type="submit" id="update-vehicle">Update </button>
                </div>


            </div>
        </form>
        <div class="col-md-6 col-lg-5">

            <div class="col-md-12 bg-crumb" align="center">
                <h2><i class="fa fa-car"></i> Vehicles</h2>
                <br>
                <p>Manage Vehicles and begin monitoring your assets Location, Fuel usage driver efficiency and schedule preventative maintenance</p>

                <a href="<?php echo site_url('vehicles'); ?>" class="btn btn-primary">View Vehicles</a>	
            </div>

        </div>

    </div>
</div> 

<?php include("new_owner.php"); ?>
<?php include("new_driver.php"); ?>

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js') ?>"></script>  

<script>
    $(function () {

        $('#edit-vehicle-form').on('submit', function () {

            var $this = $(this);
            if ($('#model').val().trim().length == 0 || $('#plate_no').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }


            $('#update-vehicle').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#update-vehicle').prop('disabled', true);
            // alert ("yes");
            swal({

                title: "Info",
                text: "Edit Vehicle?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/vehicles/update_vehicle') ?>',
                    data: $this.serialize(),
                    success: function (response) {
                        if (response==="1") {
                            swal({title: "Info", text: "Updated successfully", type: "success", confirmButtonText: "ok"},
                                    function () {
                                        document.location.href = "<?php echo base_url('index.php/vehicles') ?>";
                                    }
                            );
                        } else {
                            swal({title: "Error", text: "Failed to Update, Try again later", type: "error", confirmButtonText: "ok"});
                        }
                        $('#update-vehicle').html('Update');
                        $('#update-vehicle').prop('disabled', false);
                    }
                });
            });
            $('#update-vehicle').html('Update');
            $('#update-vehicle').prop('disabled', false);
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

        $('#powercut').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $('.powercut').each(function () {
                    this.checked = true;
                });
            } else {
                $('.powercut').each(function () {
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
