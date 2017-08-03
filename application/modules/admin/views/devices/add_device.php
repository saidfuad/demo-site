<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css') ?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-device-form">
            <div class="col-md-6">
                <div class="panel panel-default">
                   <!--  <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div> -->
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="reservation">Serial No.<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="serial_no" id="serial_no" />
                        </div>
                        <div class="form-group">
                            <label for="reservation">Phone No.<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="phone_no" id="phone_no" />
                        </div>
                        <br>

                        <div class="form-group" align="right">
                        <button class="btn btn-primary" type="submit" id="save-device">Save </button>
                        </div>


                    </div>
                </div>

            </div>
        </form>
        <div class="col-md-12 col-lg-6">
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
                <br><br>
                <h2><i class="fa fa-car"></i> Devices</h2>
                <br>
                <p>Manage devices</p>

                <a href="<?php echo site_url('admin/devices'); ?>" class="btn btn-primary">View Devices</a>	
            <br><br><br>
            </div>

        </div>

    </div>
</div> 

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js') ?>"></script>  

<script>

    $(function () {

        $('#add-device-form').on('submit', function () {

            var $this = $(this);

            if ($('#serial_no').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-device').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-device').prop('disabled', true);

            swal({
                title: "Info",
                text: "Add Device?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/admin/devices/save_device') ?>',
                    data: $this.serialize(),
                    success: function (response) {
                        if (response == 1) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/admin/devices') ?>";
                                }
                            );
                            
                        } else if (response == 0) {
                            swal({title: "Error", text: "Failed, Try again later", type: "error", confirmButtonText: "ok"});
                        } else if (response == 77) {
                            swal({title: "Info", text: "A user with that email already exists", type: "error", confirmButtonText: "ok"});
                        }

                        $('#save-device').html('Save');
                        $('#save-device').prop('disabled', false);
                    }
                });
            });

            $('#save-device').html('Save');
            $('#save-device').prop('disabled', false);

            return false;
        });

    });
</script> 
