<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />
<div class="container-fluid fleet-view">
    <div class="row" ng-app>
        <!-- Sidebar -->
        <div class="col-md-3 fleet-sidebar">
            <div class="panel fleet-panel panel-blockquote panel-border-info right">
                <div class="panel-body">
                    <!-- Car image -->
                    <div class="text-center fleet-img">
                    <?php 
                    if ($user_profile == null) {
                        echo "<img src=\"<?php echo base_url('assets/images/users/35x35/')  .'/'. $this->session->userdata('user_logo');?>\" alt=\"\" class=\"img-rounded\" style=\"width:150px; height:150px;\">";
                    } else {
                        foreach ($user_profile as $key => $value) {
                        echo "<div id=\"user-img\"><img src=\"../uploads/users/128/$value->user_logo\" alt=\"\" class=\"img-rounded\" style=\"width:150px; height:150px;\"></div>";
                        }
                       
                    }
                    
                     ?>
                        <br>
                        <button class="btn btn-primary btn-sm btn-block" id="change-photo">Change Photo</button>
                        <br>
                        <label class="label label-success">USER</label>
                    </div>
                    <!-- Title/Car name -->
                    <h3 class="sidebar-title text-center"> <?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?></h3>

                    <!-- Car stats -->
                    <ul style="list-style-type:none;font-size:14px;">
                        <li>
                            <i class="fa fa-phone text-info" data-toggle="tooltip" data-placement="top" title="Vehicle type"></i> 
                            <a href="#">
                                <?php echo $this->session->userdata('mobile_number');?>
                            </a>
                        </li>
                         <li>
                            <i class="fa fa-envelope text-info" data-toggle="tooltip" data-placement="top" title="Vehicle type"></i>  
                             <a href="#">
                                <?php echo $this->session->userdata('email_address');?>
                            </a>
                        </li> 
                           
                        <li>
                            <i class="fa fa-map-marker text-warning" data-toggle="tooltip" data-placement="top" title="Location"></i> 
                            Mombasa, Kenya
                        </li>
                    </ul>
                    <br>
                    <div class="panel panel-square" id="fleet-car-stats">
                                <div class="panel-heading panel-info clearfix">
                                    <h3 class="panel-title" style="font-size: 14px; text-align: center">Change Password</h3>
                                </div>
                                <div class="panel-body">
                                    <form id="formCheckPassword">
                                        <div class="form-group">
                                            <label for="reservation">Current Password <sup title="Required field">*</sup>:</label>
                                            <input class="form-control" type="password" name="current_password" id="current_password" />
                                        </div>
                                        <div class="form-group">
                                            <label for="reservation">New Password <sup title="Required field">*</sup>:</label>
                                            <input class="form-control" type="password" name="password" id="password" />
                                        </div>
                                        <div class="form-group">
                                            <label for="reservation">Repeat New Password <sup title="Required field">*</sup>:</label>
                                            <input class="form-control" type="password" name="cfmPassword" id="cfmPassword"  onkeyup=""/>
                                            <p style="color:red" id="err"></p>
                                        </div>
                                        <div class="panel-footer " align="right">
                                            <button class="btn btn-primary" type="submit" id="change-password">Change Password </button>
                                        </div>
                                    </form>
                                   
                                </div>
                            </div>

                    <!-- Quick actions -->
                   
                </div>
            </div>
        </div>
        <!-- Sidebar -->

        <!-- Content -->
        <div class="col-md-9 fleet-content">
            <!-- Content Panel -->
            <div class="panel fleet-panel panel-blockquote">
                <div class="panel-body">
                    <!-- Fleet title -->
                    <h1 id="fleet-car-title"><i class="fa fa-lock"></i> User Settings</h1>
                    <div class="separator bottom"></div>

                    <!-- Details Content -->
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Details -->
                            <div class="panel panel-blockquote panel-border-success left" id="fleet-car-details">
                                <div class="panel-heading">
                                    <h3 class="panel-title" style="color: #fff; margin-top: 3px">Permissions</h3>
                                    <!-- Panel Menu -->
                                    <div class="panel-menu">
                                        <button type="button" data-action="minimize" class="btn btn-default btn-action btn-xs tooltips hidden-xs hidden-sm" data-original-title="Minimize" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-angle-down"></i></button>
                                        <button type="button" data-action="reload" class="btn btn-default btn-action btn-xs tooltips" data-original-title="Reload" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-refresh"></i></button>
                                        
                                    </div>
                                    <!-- Panel Menu -->
                                </div>
                                <div class="panel-body">
                                    <div class='col-md-6'>
                                    <h3>Menus</h3>
                                    <?php if (sizeof($menu_permissions)) {?>
                                        <table class="table table-striped table-hover">
                                            <tbody>
                                                <?php foreach ($menu_permissions as $k=>$menu) {?>
                                                <tr>
                                                    <td class="text-muted"><?php echo $menu->menu_name; ?></td>
                                                </tr>
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="alert alert-info">
                                            You have 0 menu permissions
                                        </div>
                                    <?php } ?>
                                    <h3>Alerts</h3>
                                    <table class="table table-list">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted">
                                                        <input type="checkbox" name="sms_alerts" id="sms_alerts" <?php if($alert['sms_alert'] ==1) {?> checked="checked" <?php }?> value="<?php echo $alert['sms_alert']?>" class="alert-check"> SMS alerts
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">
                                                        <input type="checkbox" name="email_alerts" id="email_alerts"  <?php if($alert['email_alert'] ==1) {?> checked="checked" <?php }?> value="<?php echo $alert['email_alert']?>" class="alert-check" > Email alerts
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    
                                    </div>
                                    <div class='col-md-6'>
                                    <h3>Reports</h3>
                                    <?php if (sizeof($report_permissions)) {?>
                                        <table class="table table-list">
                                            <tbody>
                                                <?php foreach ($report_permissions as $k=>$report) {?>
                                                <tr>
                                                    <td class="text-muted"><?php echo $report->report_name; ?></td>
                                                </tr>
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="alert alert-info">
                                            You have 0 reports permissions
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>

            
                        </div>
                        <div class="col-md-4">
                            <!-- Stats -->
                            <div class="panel panel-blockquote panel-border-default left" id="fleet-car-details">
                                <div class="panel-heading">
                                    <h3 class="panel-title"  style="color: #fff; margin-top: 3px">Groups</h3>
                                    <!-- Panel Menu -->
                                    <div class="panel-menu">
                                        <button type="button" data-action="minimize" class="btn btn-default btn-action btn-xs tooltips hidden-xs hidden-sm" data-original-title="Minimize" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-angle-down"></i></button>
                                        <button type="button" data-action="reload" class="btn btn-default btn-action btn-xs tooltips" data-original-title="Reload" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-refresh"></i></button>
                                        
                                    </div>
                                    <!-- Panel Menu -->
                                </div>
                                <div class="panel-body">
                                    <?php if (sizeof($assigned_groups)) {?>
                                        <?php foreach ($assigned_groups as $k=>$group) {?>
                                            <h4><?php echo $group->assets_group_nm; ?></h4>
                                            <?php if (sizeof($assigned_vehicles)) {?>
                                                <table class="table table-list">
                                                    <tbody>
                                                        <?php foreach ($assigned_vehicles as $k=>$vehicle) {?>
                                                        <tr>
                                                            <td class="text-muted"><?php echo $vehicle->assets_name . '('.$vehicle->assets_friendly_nm.')'; ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {?>
                                                <div class="alert alert-warning">
                                                    No vehicles in this group
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="alert alert-info">
                                            You are not assigned to any group
                                        </div>
                                    <?php } ?>
                                </div>
                             </div>   
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- //. Content -->
    </div>
    <!-- /.row -->
</div> 
<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>
<script>
    
    $(function () {

            
        $('#formCheckPassword').on('submit' , function () {

            var cur_pass = $('#current_password').val().trim();
            var new_pass = $('#password').val().trim();
            var conf_pass = $('#cfmPassword').val().trim();
            var $this = $(this);
            
            if (cur_pass.length ==0 || new_pass.length ==0 || conf_pass.length ==0) {
                swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
                return false;
            }

            if (new_pass != conf_pass) {
                swal({   title: "Info",   text: "The passwords do not match",   type: "info",   confirmButtonText: "ok" });
                return false;
            }

            //$('#change-password').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#change-password').prop('disabled', true);
        
                swal({
                  title: 'Are you sure?',
                  text: "Click continue to edit your password",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Continue!',
                  closeOnConfirm: false
                },
                function() {
                    $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/userprofile/edit_password') ?>',
                    data: {password:new_pass, current_password:cur_pass},
                    success: function (response) {

                        if (response==1) {
                            $('#current_password').val('');
                            $('#password').val('');
                            $('#cfmPassword').val('');
                            swal({   title: "Info",   text: "Password changed successfully",   type: "success",   confirmButtonText: "ok" });

                            window.location.replace("<?= site_url('login');?>");
                        } else if (response==0) {
                            swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                        }

                        $('#change-password').html('Change Password');
                        $('#change-password').prop('disabled', false);
                     }
                });

            });
            return false;     
        });

        $("#change-photo").on('click', function () {
            $("#upload-panel").fadeIn(1000);
            $('.overshadow').fadeIn(1000);

        });

        $("#btn-cancel").on('click', function () {
            $("#upload-panel").fadeOut(1000);
            $('.overshadow').fadeOut(1000);

        });
        
        $("#btn-save-upload").on('click', function () {
            /*
            if($("#file").val() == 0){
                swal({   title: "Error",   text: "Choose a file to upload",   type: "error",   confirmButtonText: "ok" });
                
            }else{
                */
                swal({   
                title: "Info",   
                text: "Upload Image?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true                            
                }, function(){
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/userprofile/upload_profile_photo') ?>',
                    data: $(this).serialize(),
                    success: function (response,data) {
                        if (response==1) {

                            $("#file").val("");
                            $("#upload-panel").fadeOut(1000);
                            $('.overshadow').fadeOut(1000);
                           
                            swal({   title: "Info",   text: "File Uploaded successfully",   type: "success",   confirmButtonText: "ok" });
                            window.base_url = <?php echo json_encode(base_url()); ?>;
                            $( "#user-img" ).load( base_url + "index.php/userprofile #user-img img" );

                        } else if (response==0) { 
                            swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                        }
                     }
                });
            });
            /*}*/   
        });
        
    });
</script>

<div id="upload-panel" style="z-index:3000;border:10px solid #f5f5f5; background: #fff;border-radius:10px; width:40%; position:fixed;top:20%;left:30%;min-height:200px;box-shadow: 5px 5px 5px #333;display:none">

    <div class="panel-body">
        <div id="dropzone">
            <form action="<?php echo base_url('index.php/upload_images/upload_user_image') ?>" class="dropzone" id="dropzone-container">
                <div class="fallback">
                    <input name="file" id="file" type="file" multiple />
                </div>
            </form>
        </div>
        
    </div>
    <div class="panel-footer">
        <button class="btn btn-default" id="btn-cancel">Cancel</button>
        <button class="btn btn-success" id="btn-save-upload">Save</button>
        
    </div>

    
</div>

<input type="hidden" value="1" id="change-input">