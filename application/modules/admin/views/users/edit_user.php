<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-user-form">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="reservation">First Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="hidden" name="user_id" id="user_id" value="<?php echo $user['user_id']; ?>" />
                            <input class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $user['first_name'] ?>" />
                        </div>
                        <div class="form-group">
                            <label for="reservation">Last Name <sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $user['last_name'] ?>" />
                        </div>

                    </div>

                     <div class="col-md-6">
                        <div class="form-group">
                            <label for="reservation">Email<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="email" name="email" id="email" value="<?php echo $user['email'] ?>" />
                        </div>

                        <div class="form-group">
                            <label for="reservation">Phone Number<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="phone_no" id="phone_no" value="<?php echo $user['phone_no'] ?>" />
                        </div>

                        <div class="form-group" style="display:none;">
                            <label for="reservation">User Type<sup title="Required field">*</sup>:</label>
                            <select name="user_type_id" id="user_type_id" class="form-control" value="<?php echo $user['user_type_id'] ?>">
                                <option value="4">Installer</option>
                             <!--  <?php //if($user['user_type_id']==1){?>
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                                <option value="3">Normal</option>
                                <option value="4">Installer</option>
                                <?php //}//else if($user['user_type_id']==2){?>
                                <option value="2">Admin</option>
                                <option value="3">Normal</option>
                                <option value="4">Installer</option>
                                <option value="1">Super Admin</option>
                                <?php //}//else if($user['user_type_id']==3){?>
                                <option value="3">Normal</option>
                                <option value="4">Installer</option>
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                                <?php //}//else if($user['user_type_id']==4){?>
                                <option value="4">Installer</option>
                                <option value="3">Normal</option>
                                <option value="2">Admin</option>
                                <option value="1">Super Admin</option>
                                <?php //}?> -->

                            </select>
                        </div>

                        <div class="form-group" style="display:none;">
                            <label for="reservation">Account Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="account_id" id="account_id" value="<?php echo $user['account_id']; ?>" />
                        </div>

                         <div class="form-group" style="display:none;">
                            <label for="reservation">Add UID<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="add_uid" id="add_uid" value="<?php echo $user['add_uid']; ?>" />
                        </div>
                        <br>

                        <div class="form-group" align="right">
                        <button class="btn btn-primary" type="submit" id="update-user">Update </button>
                        </div>

                    </div>
                       
                        
                    </div>
                </div>

                <div class="panel-footer " align="right">
                    
                </div>

                
            </div>
        </form>
        <div class="col-md-6 col-lg-6" style="display:none;">
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

            <!--<div class="col-md-12 bg-crumb" align="center">
                <h2><i class="fa fa-car"></i> Vehicles</h2>
                <br>
                <p>Manage Vehicles and begin monitoring your assets Location, Fuel usage driver efficiency and schedule preventative maintenance</p>

                <a href="<1?php echo site_url('vehicles/groups');?>" class="btn btn-success">View Groups</a>    
            </div>-->

        </div>

    </div>
</div> 

<script src="<?php echo  base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>  

<script>
   $(function () {

            $('#edit-user-form').on('submit' , function () {

                var $this = $(this);

                 if ($('#first_name').val().trim().length == 0 || $('#last_name').val().trim().length == 0 || $('#email').val().trim().length == 0 || $('#phone_no').val().trim().length == 0 || $('#user_type_id').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
                }


                $('#update-user').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#update-user').prop('disabled', true);

               // alert ("yes");
               swal({   

                title: "Info",   
                text: "Edit User?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true                            
                }, function(){
                    $.ajax({
                        method: 'post',
                        url: '<?=base_url('index.php/admin/users/update_user') ?>',
                        data: $this.serialize(),
                        success: function (response) {
                            if (response) {
                                swal({   title: "Info",   text: "Updated successfully",   type: "success",   confirmButtonText: "ok" },
                                function (){
                                    document.location.href="<?php echo base_url('index.php/admin/users') ?>";
                                });
                            } else {
                                swal({   title: "Error",   text: "Failed to Update, Try again later",   type: "error",   confirmButtonText: "ok" });
                            } 
                            $('#update-user').html('Update');
                            $('#update-user').prop('disabled', false);
                         }
                    });
                    });

                $('#update-user').html('Update');
                $('#update-user').prop('disabled', false);
                
                return false;     
            });
        });     
</script>
