<div class="container-fluid">
    <div class="row">
        <form id="edit-vehicle-geofence">
            <input type="hidden" id="geofence_id" name="geofence_id" value="<?= $vehicle_geofence_assignment['id'] ?>"/>            
            <div class="col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Type:</b> <?= $vehicle_geofence_assignment['type'] ?> <b>Name:</b> <?= $vehicle_geofence_assignment['name'] ?>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="in_alert" class="col-md-4 control-label">In Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" id="in_alert" name="in_alert" <?php echo ($vehicle_geofence_assignment['in_alert'] == 1 ? 'checked="checked"' : ''); ?> id='in_alert' />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="out_alert" class="col-md-4 control-label">Out Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" id="out_alert" name="out_alert" value="<?php echo ($vehicle_geofence_assignment['out_alert'] == 1 ? '1' : '0'); ?>" <?php echo ($vehicle_geofence_assignment['out_alert'] == 1 ? 'checked="checked"' : ''); ?> id='out_alert' />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="sms_alert" class="col-md-4 control-label">SMS Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" id="sms_alert" name="sms_alert" value="<?php echo ($vehicle_geofence_assignment['sms_alert'] == 1 ? '1' : '0'); ?>" <?php echo ($vehicle_geofence_assignment['sms_alert'] == 1 ? 'checked="checked"' : ''); ?> id='sms_alert' />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="email_alert" class="col-md-4 control-label">Email Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" id="email_alert" name="email_alert" value="<?php echo ($vehicle_geofence_assignment['email_alert'] == 1 ? '1' : '0'); ?>" <?php echo ($vehicle_geofence_assignment['email_alert'] == 1 ? 'checked="checked"' : ''); ?> id='email_alert' />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="status" class="col-md-4 control-label">Status</label>
                                    <div class="col-md-8">
                                        <select id="status" name="status">
                                            <option value="" disabled="true">Select status</option>
                                            <?php
                                            $selected = (1 == $vehicle_geofence_assignment['status']) ? ' selected="selected"' : null;
                                            $selected2 = (0 == $vehicle_geofence_assignment['status']) ? ' selected="selected"' : null;

                                            echo '<option value="1" ' . $selected . '>Active</option>';
                                            echo '<option value="0" ' . $selected2 . '>Inactive</option>';
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="right">
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button id="save" type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="col-md-12 bg-crumb" align="center" style="height: 222px; padding-top: 67px;">
                    <h2><i class="fa fa fa-university"></i> Map Data</h2>
                    <br>
                    <p>Manage landmarks, geofences and routes</p>

                    <a href="<?php echo site_url('gps_tracking/geo_data'); ?>" class="btn btn-primary">View Map Data</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        $("#edit-vehicle-geofence").submit(function (e) {
            e.preventDefault();
            //get all form data
            id = $("#geofence_id").val().trim();
            if (document.getElementById('in_alert').checked) {
                in_alert = 1;
            } else {
                in_alert = 0;
            }

            if (document.getElementById('out_alert').checked) {
                out_alert = 1;
            } else {
                out_alert = 0;
            }

            if (document.getElementById('sms_alert').checked) {
                sms_alert = 1;
            } else {
                sms_alert = 0;
            }

            if (document.getElementById('email_alert').checked) {
                email_alert = 1;
            } else {
                email_alert = 0;
            }
            status = $("#status").val().trim();
			
			vehicle_id = "<?=$vehicle_geofence_assignment['vehicle_id']?>";
			geofence_id = "<?=$vehicle_geofence_assignment['geofence_id']?>";
			
            $.ajax({
                method: 'post',
                url: '<?= base_url('index.php/vehicle_geofence/update_vehicle_geofence') ?>',
                data: {id: id,geofence_id:geofence_id, in_alert: in_alert, out_alert: out_alert, sms_alert: sms_alert, email_alert: email_alert, status: status,vehicle_id:vehicle_id},
                success: function (response) {
                    if (response === "1") {

                        swal({title: "Success", text: "Updated successfully", type: "success", confirmButtonText: "ok"},
                                function () {
                                   document.location.href="<?php echo site_url('gps_tracking/geo_data') ?>";
                                }
                        );
                    } else if (response === "66") {
                        swal({title: "Success", text: "That vehicle already has an active route assigned to it", type: "info", confirmButtonText: "ok"});
                    } else if (response === "77") {
                        swal({title: "Success", text: "That vehicle already has an active geofence assigned to it", type: "info", confirmButtonText: "ok"});
                    } else {
                        swal({title: "Error", text: "Failed to Update, Try again later", type: "error", confirmButtonText: "ok"});
                    }
                }
            });

        });
    });
</script>