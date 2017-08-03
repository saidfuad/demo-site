<div class="modal fade" id="reset_pass" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>RESET PASSWORD</b></h4>
            </div>

            <form id="reset-password-form">

            <div class="modal-body">
               <div class="form-group">
                    <label for="reservation">Current Password <sup title="Required field">*</sup>:</label>
                    <input class="form-control" type="password" name="password" id="password" />
                </div>

                <div class="form-group">
                    <label for="reservation">New Password <sup title="Required field">*</sup>:</label>
                    <input class="form-control" type="password" name="new_password1" id="new_password1" />
                </div>

                <div class="form-group">
                    <label for="reservation">Retype New Password <sup title="Required field">*</sup>:</label>
                    <input class="form-control" type="password" name="new_password2" id="new_password2"/>
                </div>

            </div>

            <div class="modal-footer">

                <div align="right">
                    <button class="btn btn-primary" type="submit" id="reset-password">Reset Password</button>
                </div>

            </div>

        </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

    $(function () {

        $('#reset-password-form').on('submit', function () {

            var $this = $(this);

            if ($('#password').val().trim().length == 0 || $('#new_password1').val().trim().length == 0 || $('#new_password2').val().trim().length == 0) {

                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;

            }else if ($('#new_password1').val().trim() != $('#new_password2').val().trim()) {

                swal({title: "Error", text: "New Passwords don't match. Try again", type: "info", confirmButtonText: "ok"});
                return false;

            }else{

                validatePassword();
            }

            function validatePassword() {

                var newPassword = $('#new_password1').val().trim();
                var minNumberofChars = 8;
                var maxNumberofChars = 16;
                var regularExpression  = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;

                if(newPassword.length < minNumberofChars || newPassword.length > maxNumberofChars){

                    swal({title: "Error", text: "Number of characters should be between 8-16. Try again", type: "info", confirmButtonText: "ok"});
                    return false;

                }else if(!regularExpression.test(newPassword)) {

                    swal({title: "Error", text: "Password should contain atleast one number and one special character", type: "info", confirmButtonText: "ok"});
                    return false;

                }else{
                    resetPassword();
                }
            }

            function resetPassword(){

                swal({
                    title: "Info",
                    text: "Reset Password?",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true
                }, function () {

                    $.ajax({
                        method: 'post',
                        url: '<?= base_url('index.php/main/reset_password') ?>',
                        data: $this.serialize(),
                        success: function (response) {

                            if (response == 1) {

                                swal({title: "Info", text: "Password reset successfully", type: "success", confirmButtonText: "ok"});

                            } else if (response == 0) {

                                swal({title: "Error", text: "Failed, Try again later", type: "error", confirmButtonText: "ok"});

                            } else if (response == 77) {

                                swal({title: "Info", text: "Wrong current Password. Try again", type: "error", confirmButtonText: "ok"});

                            }

                        }
                    });

                });
            }
        });

    });
</script>

