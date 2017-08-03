<div class="container-fluid">
    <div class="row">
        <form id="edit-vehicle-geofence">
            <input type="hidden" id="geofence_id" value="<?= $vehicle_geofence_assignment['in_alert'] ?>"/>            
            <div class="col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                         <b>Type:</b> <?= $vehicle_geofence_assignment['type']?> <b>Name:</b> <?= $vehicle_geofence_assignment['name']?>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="in_alert" class="col-md-4 control-label">In Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" disabled="true" id="in_alert" name="in_alert" value="1" <?php echo ($vehicle_geofence_assignment['in_alert'] == 1 ? 'checked="checked"' : ''); ?> id='in_alert' />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="out_alert" class="col-md-4 control-label">Out Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" disabled="true" id="out_alert" name="out_alert" value="1" <?php echo ($vehicle_geofence_assignment['out_alert'] == 1 ? 'checked="checked"' : ''); ?> id='out_alert' />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="sms_alert" class="col-md-4 control-label">SMS Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" disabled="true" id="sms_alert" name="sms_alert" value="1" <?php echo ($vehicle_geofence_assignment['sms_alert'] == 1 ? 'checked="checked"' : ''); ?> id='sms_alert' />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="email_alert" class="col-md-4 control-label">Email Alert</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" disabled="true" id="email_alert" name="email_alert" value="1" <?php echo ($vehicle_geofence_assignment['email_alert'] == 1 ? 'checked="checked"' : ''); ?> id='email_alert' />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="status" class="col-md-4 control-label">Status</label>
                                    <div class="col-md-8">
                                        <?=($vehicle_geofence_assignment['status']=="1")?"Active":"Inactive"; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer" align="">
                        </div>
                    </div>                    
                    <?php echo form_close(); ?>
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
            in_alert = $("#in_alert").val().trim();
            out_alert = $("#out_alert").val().trim();
            sms_alert = $("#sms_alert").val().trim();
            email_alert = $("#email_alert").val().trim();
            status = $("#status").val().trim();
            $.ajax({
                method: 'post',
                url: '<?= base_url('index.php/vehicle_geofence/update_vehicle_geofence') ?>',
                data: {in_alert: in_alert, out_alert: out_alert, sms_alert: sms_alert, email_alert: email_alert, status: status},
                success: function (response) {
                    if (response === "1") {
                        swal({title: "Info", text: "Updated successfully", type: "success", confirmButtonText: "ok"});
                    } else {
                        swal({title: "Error", text: "Failed to Update, Try again later", type: "error", confirmButtonText: "ok"});
                    }
                }
            });

        });
    });
</script>