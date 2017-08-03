<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/choosen/chosen.css')?>" rel="stylesheet" type="text/css" />
<style>
.chosen-choices{
    border-radius: 5px;
    padding-top: 5px !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <form id="edit-insurance-form">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                         <div class="col-md-6">
                             <input class="form-control" style="display:none;" type="text" name="reminder_id" id="reminder_id" value="<?php echo $reminder['reminder_id'];?>" />
                          <div class="form-group">
                                <label for="reservation">Choose Vehicle<sup title="Required field">*</sup>:</label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-control">
                                        <?php foreach($vehicles as $new) {
                                            $sel = "";
                                            if($reminder['vehicle_id']==$new->vehicle_id){?>
                                                <option selected="selected" value="<?php echo $new->vehicle_id;?>"><?php echo $new->model." - ".$new->plate_no;?></option>
                                            <?php }else{?>
                                                <option value="<?php echo $new->vehicle_id;?>"><?php echo $new->model." - ".$new->plate_no;?></option>
                                            <?php }
                                        }
                                        
                                        ?>
                                        
                                    </select>
                        </div>

                        <div class="form-group">
                            <label for="reservation">Insurance Company<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="company" id="company" value="<?php echo $reminder['company'];?>" />
                        </div>

                         <div class="form-group">
                            <label for="reservation">Type Of Cover<sup title="Required field">*</sup>:</label>
                             <select name="type_of_cover" id="type_of_cover" class="form-control">
                                 <?php if($reminder['type_of_cover']=="Comprehensive"){?>
                                <option value="Comprehensive">Comprehensive</option>
                                <option value="Third Party">Third Party</option>
                                <?php }else{?>
                                <option value="Third Party">Third Party</option>
                                <option value="Comprehensive">Comprehensive</option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reservation">Value Covered<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="number" name="value_covered" id="value_covered" value="<?php echo $reminder['value_covered']?>" />
                        </div>

                    </div>

                     <div class="col-md-6">
                         <div class="form-group">
                            <label for="reservation">Premium Amount<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="number" name="amount_to_pay" id="amount_to_pay" value="<?php echo $reminder['amount_to_pay']?>"/>
                        </div>

                        <div class="form-group">
                            <label for="reservation">Expiry Date<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="expiry_date" id="expiry_date" value="<?php echo $reminder['expiry_date']?>" />
                        </div>

                        <div class="form-group">
                            <label for="reservation">Reminder Schedule<sup title="Required field">*</sup>:</label>
                            <select name="reminder_time" id="reminder_time" class="form-control">
                                 <?php if($reminder['reminder_time']=="1 Week"){?>
                                <option value="1 Week">1 Week</option>
                                <option value="2 Weeks">2 Weeks</option>
                                <?php }else{?>
                                <option value="2 Weeks">2 Weeks</option>
                                <option value="1 Week">1 Week</option>
                                <?php }?>
                            </select>
                        </div>                      

                        <div class="form-group" style="display:none;">
                            <label for="reservation">Account Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="account_id" id="account_id" value="<?php echo $accountid; ?>" />
                        </div>

                         <div class="form-group" style="display:none;">
                            <label for="reservation">Add UID<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="add_uid" id="add_uid" value="<?php echo $uid; ?>" />
                        </div>

                        <div class="form-group" style="display:none;">
                            <label for="reservation">Reminder Type<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="reminder_type_id" id="reminder_type_id" value="<?php echo 1; ?>" />
                        </div>
                        <br>


                    </div>

                    </div>
                </div>

                <div class="panel-footer " align="right">
                    <button class="btn btn-primary" type="submit" id="save-reminder">Save </button>
                </div>


            </div>
        </form>
    </div>
</div> 

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js') ?>"></script>  
<script src="<?php echo base_url('assets/choosen/chosen.jquery.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js')?>"></script>

<script>

    $(function () {

        $('.chzn').chosen();

        $('#expiry_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().subtract(24, 'hours'),
            minDate: moment().subtract(1, 'days'),
            format: 'YYYY-MM-DD'
        });


        $('#edit-insurance-form').on('submit', function () {

            var $this = $(this);

            if ($('#vehicle_id').val().trim().length == 0 || $('#company').val().trim().length == 0 || $('#expiry_date').val().trim().length == 0 || $('#value_covered').val().trim().length == 0 || $('#amount_to_pay').val().trim().length == 0 || $('#reminder_time').val().trim().length == 0 || $('#type_of_cover').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-reminder').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-reminder').prop('disabled', true);

            swal({
                title: "Info",
                text: "Edit Insurance Reminder?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/reminders/update_reminder') ?>',
                    data: $this.serialize(),
                    success: function (response) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/reminders') ?>";
                                }
                            );

                        $('#save-reminder').html('Save');
                        $('#save-reminder').prop('disabled', false);
                    }
                });
            });

            $('#save-reminder').html('Save');
            $('#save-reminder').prop('disabled', false);

            return false;
        });

    });
</script> 
