<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css') ?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-sacco-form">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                         <div class="col-md-6">
                        <div class="form-group">
                            <label for="reservation">Company Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="company_name" id="company_name" />
                        </div>

                          <div class="form-group">
                            <label for="reservation">Phone Number<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="company_phone_no" id="company_phone_no" />
                        </div>

                        <div class="form-group">
                            <label for="reservation">Password<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="password" id="password" />
                        </div>

                       <div class="form-group">
                            <label for="reservation">Email<sup title="Required field"></sup>:</label>
                            <input class="form-control" type="email" name="company_email" id="company_email"/>
                        </div>

                      

                        <div class="form-group" align="right">
                        <button class="btn btn-primary" type="submit" id="save-sacco">Save </button>
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="col-md-12 bg-crumb" align="center">
                            <br><br><br>
                            <h2><i class="fa fa-car"></i>SACCOs</h2>
                            <br>
                            <p>Manage SACCOs and begin monitoring your assets through them</p>

                            <a href="<?php echo site_url('admin/sacco'); ?>" class="btn btn-primary">View SACCOs</a> 
                        <br><br><br><br>
                        </div>

                    </div>

                    </div>
                </div>

                <div class="panel-footer " align="right">
                    
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

<script>

    $(function () {

        $('#add-sacco-form').on('submit', function () {

            var $this = $(this);

            if ($('#company_name').val().trim().length == 0 
            || $('#company_phone_no').val().trim().length == 0 
            || $('#password').val().trim().length == 0  ) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-sacco').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-sacco').prop('disabled', true);

            swal({
                title: "Info",
                text: "Add SACCO?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/admin/save_sacco') ?>',
                    data: $this.serialize(),
                    success: function (response) {
                        if (response == 1) {
                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/admin/sacco') ?>";
                                }
                            );
                            
                      } else  {
                            swal({title: "Info", text: "This SACCO already exists", type: "error", confirmButtonText: "ok"});
                        }

                        $('#save-sacco').html('Save');
                        $('#save-sacco').prop('disabled', false);
                    }
                });
            });

            $('#save-sacco').html('Save');
            $('#save-sacco').prop('disabled', false);

            return false;
        });

    });
</script> 
