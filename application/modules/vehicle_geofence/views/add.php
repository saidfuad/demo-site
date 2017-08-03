<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?= form_open('vehicle_geofence/add_assignment', array("id" => "form_assignment", "class" => "form-horizontal")); ?>
            <div class="form-group">
                <label for="vehicle_id" class="col-md-2">Vehicle</label>
                <div class="col-md-5">
                    <select id="vehicle_id" name="vehicle_id" class="form-control">
                        <option value="">Select a vehicle</option>
                        <?php
                        foreach ($vehicle_data as $value) {
                            echo '<option value="' . $value["vehicle_id"] . '" >' . $value["plate_no"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="geofence_id" class="col-md-2">Geofence/Landmark/Route</label>
                <div class="col-md-5">
                    <select id="geofence_id" name="geofence_id" class="form-control">
                        <option value="">Select a geofence/landmark/route</option>
                        <?php
                        foreach ($geofence_data as $value) {
                            $selected = ($value["id"] == $this->input->post('id')) ? ' selected="selected"' : null;

                            echo '<option value="' . $value["id"] . '" ' . $selected . '>' . $value["name"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="in_alert" class="col-md-4">In Alert</label>
                        <div class="col-md-8">
                            <input type="checkbox" id="in_alert" name="in_alert" value="1" value="<?php echo $this->input->post('in_alert'); ?>" id="in_alert" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="out_alert" class="col-md-4">Out Alert</label>
                        <div class="col-md-8">
                            <input type="checkbox" id="out_alert" name="out_alert" value="1" value="<?php echo $this->input->post('out_alert'); ?>" id="out_alert" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="sms_alert" class="col-md-4">SMS Alert</label>
                        <div class="col-md-8">
                            <input type="checkbox" id="sms_alert" name="sms_alert" value="1" value="<?php echo $this->input->post('sms_alert'); ?>" id="sms_alert" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email_alert" class="col-md-4">Email Alert</label>
                        <div class="col-md-8">
                            <input type="checkbox" id="email_alert" name="email_alert" value="1" value="<?php echo $this->input->post('email_alert'); ?>" id="email_alert" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#form_assignment").submit(
                function (event) {
                    event.preventDefault();

                    var vehicle_id = $('#vehicle_id').val().trim();
                    var geofence_id = $('#geofence_id').val().trim();
                    var in_alert = $('#in_alert').val().trim();
                    var out_alert = $('#out_alert').val().trim();
                    var sma_alert = $('#sms_alert').val().trim();
                    var email_alert = $('#email_alert').val().trim();
                    var $this = $(this);

                    //validate
                    if (vehicle_id.length === 0 || geofence_id.length === 0) {
                        swal({title: "Info", text: "Please fill in vehicle and geofence/landmark", type: "warning", confirmButtonText: "ok"});
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: $(this).attr("action"),
                        data: $this.serialize(),
                        success: function (response) {
                            if (response === "1") {
                                swal({title: "Success", text: "Vehicle assigned to geofence/landmark/landmark successfully", type: "success", confirmButtonText: "ok"}, function () {
                                    document.location.href = "<?php echo site_url('gps_tracking/geo_data') ?>";
                                });
                            } else if (response === "88") {
                                swal({title: "Info", text: "That vehicle already has an active route", type: "info", confirmButtonText: "ok"});
                            } else if (response === "77") {
                                swal({title: "Info", text: "That vehicle already has an active geofence", type: "info", confirmButtonText: "ok"});
                            } else if (response === "66") {
                                swal({title: "Info", text: "The landmark/geofence/route is already assigned to that vehicle", type: "info", confirmButtonText: "ok"});
                            } else {
                                swal({title: "Error", text: "Vehicle not assigned to geofence/landmark", type: "error", confirmButtonText: "ok"});
                            }
                        }
                    });
                }
        );
    });
</script>