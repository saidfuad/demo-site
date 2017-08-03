<link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css') ?>" rel="stylesheet" type="text/css" media="all">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <form id="edit-geo_data-form">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Map Data Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name<sup title="Required field">*</sup>:</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="name" value="<?php echo $geo_data[0]['name']; ?>" class="form-control" id="name" />
                                </div>
                            </div>
                            <br/>
                            <br/>

                            <div class="form-group">
                                <label for="fill_color" class="col-md-4 control-label">Fill Color</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" name="fill_color" value="<?php echo $geo_data[0]['fill_color']; ?>" class="form-control" id="fill_color" />
                                </div>
                            </div>
                            <br/>
                            <br/>
                            <div class="form-group">
                                <label for="status" class="col-md-4 control-label">Status<sup title="Required field">*</sup>:</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="status" name="status">
                                        <?php
                                        $status_one = ($geo_data[0]['status'] == 1) ? " selected" : "";
                                        $status_zero = ($geo_data[0]['status'] == 0) ? " selected" : "";
                                        echo "<option {$status_one} value='1' >Active</option>";
                                        echo "<option {$status_zero} value='0' >Inactive</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br/>
                            <br/>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button id="save_data" type="submit" class="btn btn-primary pull-right">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-lg-6">

            <div class="col-md-12 bg-crumb" align="center" style="height: 249px; padding-top: 92px">
                <h2><i class="fa fa fa-university"></i> Map Data</h2>
                <br>
                <p>Manage landmarks, geofences and routes</p>

                <a href="<?php echo site_url('gps_tracking/geo_data'); ?>" class="btn btn-primary">View Map Data</a>
            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js') ?>"></script>
<script>
    $(function () {
        $('#edit-geo_data-form').on('submit', function () {

            var $this = $(this);

            if ($('#name').val().trim().length === 0 || $('#status').val().trim().length === 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-data').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-data').prop('disabled', true);

            swal({
                title: "Info",
                text: "Edit Geo Data?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/gps_tracking/edit_save_geo_data/' . $geo_data[0]['id']) ?>',
                    data: $this.serialize(),
                    success: function (response) {
                        if (response === "1") {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                    function () {
                                        document.location.href = "<?php echo base_url('index.php/gps_tracking/geo_data') ?>";
                                    }
                            );

                        } else if (response === "0") {
                            swal({title: "Error", text: "Failed, Try again later", type: "error", confirmButtonText: "ok"});
                        }

                        $('#save-user').html('Save');
                        $('#save-user').prop('disabled', false);
                    }
                });
            });

            $('#save-data').html('Save');
            $('#save-data').prop('disabled', false);

            return false;
        });

        $("#fill_color").ColorPickerSliders({
            placement: 'right',
            hsvpanel: true,
            previewformat: 'hex'
        });

    });
</script>
<script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js') ?>"></script>
