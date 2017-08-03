<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />
<div class="container-fluid fleet-view">
    <div class="row" ng-app>
        <!-- Sidebar -->
        <div class="col-md-3 fleet-sidebar">
            <div class="panel fleet-panel panel-blockquote panel-border-info right">
                <div class="panel-body">
                    <!-- Car image -->
                    <div class="text-center fleet-img" id="company_logo">
                    <?php
                    if ($this->session->userdata('company_logo')) { ?>
                        <img src="<?php echo base_url('uploads/companies/128')  .'/'. $this->session->userdata('company_logo'); ?>" alt="" class="img-rounded" style="width:150px; height:150px;" />
                        
                    <?php } ?>
                        <br>
                        <button class="btn btn-primary btn-sm btn-block" id="change-photo">Change Company Photo</button>
                        <br>
                        <label class="label label-success">Company</label>
                    </div>
                    <!-- Title/Car name -->
                    <h3 id="company_label" class="sidebar-title text-center"> <?php echo $this->session->userdata('company_name');?></h3>

                    <!-- Car stats -->
                    <ul style="list-style-type:none;font-size:14px;">
                        <li>
                            <i class="fa fa-phone text-info" data-toggle="tooltip" data-placement="top" title="Vehicle type"></i> 
                            <a href="#">
                                <?php echo $this->session->userdata('company_phone_no_1');?>
                            </a>
                        </li>
                         <li>
                            <i class="fa fa-envelope text-info" data-toggle="tooltip" data-placement="top" title="Vehicle type"></i>  
                             <a href="#">
                                <?php echo $this->session->userdata('company_email');?>
                            </a>
                        </li> 
                           
                        <li>
                            <i class="fa fa-map-marker text-warning" data-toggle="tooltip" data-placement="top" title="Location"></i> 
                            <?php echo $this->session->userdata('company_address_1');?>
                        </li>
                    </ul>
                    <br>
                    <div class="panel panel-square" id="fleet-car-stats">
                                <div class="panel-heading panel-info clearfix">
                                    <h3 class="panel-title" style="font-size: 14px; text-align: center">Edit Company Name</h3>
                                </div>
                                <div class="panel-body">
                                    <form id="formChangeName">
                                        <div class="form-group">
                                            <label for="reservation">New Company Name <sup title="Required field">*</sup>:</label>
                                            <input class="form-control" type="text" name="company_name" id="company_name" />
                                        </div>
                                        <div class="panel-footer " align="right">
                                            <button class="btn btn-primary" type="submit" id="change-name">Edit</button>
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
                    <h1 id="fleet-car-title"><i class="fa fa-info-circle"></i> Company Information</h1>
                    <div class="separator bottom"></div>

                    <!-- Details Content -->
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Details -->
                            <div class="panel panel-blockquote panel-border-success left" id="fleet-car-details">
                                <div class="panel-heading">
                                    <!-- Panel Menu -->
                                    <div class="panel-menu">
                                        <button type="button" data-action="minimize" class="btn btn-default btn-action btn-xs tooltips hidden-xs hidden-sm" data-original-title="Minimize" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-angle-down"></i></button>
                                        <button type="button" data-action="reload" class="btn btn-default btn-action btn-xs tooltips" data-original-title="Reload" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-refresh"></i></button>
                                        
                                    </div>
                                    <!-- Panel Menu -->
                                </div>
                                <div class="panel-body">
                                    <div class='col-md-12'>
                                        <h3>Subscribed Services</h3>
                                        <?php if (sizeof($company_subscriptions)) {?>
                                            <table class="table table-striped table-hover">
                                                <tbody>
                                                    <?php foreach ($company_subscriptions as $k=>$menu) {?>
                                                    <tr>
                                                        <td class="text-muted"><?php echo $menu->services_name; ?></td>
                                                    </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <div class="alert alert-info">
                                                You have 0 Subscription Services
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class='col-md-6'>
                                    
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

            
        $('#formChangeName').on('submit' , function () {

            var new_name = $('#company_name').val().trim();
            var $this = $(this);

            if (new_name.length ==0) {
                swal({   title: "Info",   text: "Fill in the required field ( * )",   type: "info",   confirmButtonText: "ok" });
                return false;
            }

            $('#change-name').prop('disabled', true);
                swal({
                  title: 'Are you sure?',
                  text: "Click continue to edit company name",
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
                        data: {company_name:new_name},
                        url: '<?= base_url('index.php/main/edit_company_name') ?>',

                        success: function (response) {

                            if (response==1) {
                                $('#company_name').val('');
                                swal({   title: "Info",   text: "Company name updated successfully",   type: "success",   confirmButtonText: "ok" });
                                window.base_url = <?php echo json_encode(base_url()); ?>;
                                $( "#company_label" ).load( base_url + "index.php/main/companydetails #company_label" );

                            } else if (response==0) {
                                swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                            }

                            $('#change-name').html('Edit');
                            $('#change-name').prop('disabled', false);
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
                    url: '<?= base_url('index.php/main/upload_company_logo') ?>',
                    data: $(this).serialize(),
                    success: function (response,data) {
                        if (response==1) {

                            $("#file").val("");
                            $("#upload-panel").fadeOut(1000);
                            $('.overshadow').fadeOut(1000);

                            swal({   title: "Info",   text: "File Uploaded successfully",   type: "success",   confirmButtonText: "ok" });
                            window.base_url = <?php echo json_encode(base_url()); ?>;
                            $( "#company_logo" ).load( base_url + "index.php/main/companydetails #company_logo img" );
                            
                        } else if (response==0) { 
                            swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                        }
                     }
                });
            });  
        });
        
    });
</script>

<div id="upload-panel" style="z-index:3000;border:10px solid #f5f5f5; background: #fff;border-radius:10px; width:40%; position:fixed;top:20%;left:30%;min-height:200px;box-shadow: 5px 5px 5px #333;display:none">

    <div class="panel-body">
        <div id="dropzone">
            <form action="<?php echo base_url('index.php/upload_images/upload_company_logo') ?>" class="dropzone" id="dropzone-container">
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