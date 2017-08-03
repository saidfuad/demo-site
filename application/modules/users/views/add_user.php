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
        <form id="add-user-form">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                         <div class="col-md-6">
                        <div class="form-group">
                            <label for="reservation">First Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="first_name" id="first_name" />
                        </div>
                        <div class="form-group">
                            <label for="reservation">Last Name <sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="last_name" id="last_name" />
                        </div>

                        <div class="form-group">
                            <label for="reservation">Email:</label>
                            <input class="form-control" type="email" name="email" id="email"/>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group">
                            <label for="reservation">Phone Number<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="phone_no" id="phone_no" />
                        </div>

                        <div class="form-group">
                            <label for="reservation">User Type<sup title="Required field">*</sup>:</label><span class="badge pull-right" data-placement='top' data-toggle='tooltip' data-original-title='Normal User can only monitor vehicles assigned to him.An Admin monitors all vehicles and has all rights as you'><i class="fa fa-question fa-1x" aria-hidden="true"></i></span>
                            <select name="user_type_id" id="user_type_id" class="form-control">
                                <option value="">Select User Type</option>
                                <option value="2">Admin</option>
                                <option value="3">Normal</option>
                                <!-- <option value="4">Installer</option> -->
                            </select>
                        </div>


                        <div class="form-group" id="avh">
                                <label for="reservation">Assign Vehicles<sup title="Required field">*</sup>:</label>
                                    <select name="assign[]" id="assign" class="chzn col-md-12" multiple="multiple" >
                                        <?php foreach($assign as $new) {
                                            $sel = "";
                                            if(set_select('assign', $new->vehicle_id)) $sel = "selected='selected'";
                                            echo '<option value="'.$new->vehicle_id.'" '.$sel.'>'.$new->model." - ".$new->plate_no.'</option>';
                                        }
                                        
                                        ?>
                                        
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
                        <br>


                    </div>

                    </div>
                </div>

                <div class="panel-footer " align="right">
                    <button class="btn btn-primary" type="submit" id="save-user">Save </button>
                </div>


            </div>
        </form>
        <div class="col-md-12 col-lg-12">
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

        </div>

    </div>
</div> 

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js') ?>"></script>  
<script src="<?php echo base_url('assets/choosen/chosen.jquery.min.js')?>" type="text/javascript"></script>

<script>

    $(function () {

        $('#user_type_id').on('change',function () {
            if($('#user_type_id').val()=="2"){

                $('#avh').css({
                    display: 'none'
                });

            }else{
                 $('#avh').css({
                    display: 'block'
                 });
            }

        });

        $('.chzn').chosen();

        $('#add-user-form').on('submit', function () {

            var $this = $(this);

            if ($('#first_name').val().trim().length == 0 || $('#last_name').val().trim().length == 0 || $('#phone_no').val().trim().length == 0 || $('#user_type_id').val().trim().length == 0 ) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-user').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-user').prop('disabled', true);

            swal({
                title: "Info",
                text: "Add User?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/users/save_user') ?>',
                    data: $this.serialize(),
                    success: function (response) {
                        if (response == 1) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/users') ?>";
                                }
                            );
                            
                        } else{
                            swal({title: "Error", text: response, type: "error", confirmButtonText: "ok"});
                        }

                        $('#save-user').html('Save');
                        $('#save-user').prop('disabled', false);
                    }
                });
            });

            $('#save-user').html('Save');
            $('#save-user').prop('disabled', false);

            return false;
        });

    });
</script> 
