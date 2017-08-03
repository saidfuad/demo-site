<!DOCTYPE HTML>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        
        <title>ITMS | Reset Password</title>
        
        <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url('assets/icons/font-awesome/css/font-awesome.css')?>" rel="stylesheet">
        <!-- Themify Icons -->
        <link href="<?php echo base_url('assets/icons/themify/themify-icons.css')?>" rel="stylesheet">
        <!-- IonIcons Pack -->
        <link href="<?php echo base_url('assets/icons/ionicons-2.0.1/css/ionicons.min.css')?>" rel="stylesheet">
        <!-- Awesome Bootstrap Checkboxes -->
        <link href="<?php echo base_url('assets/css/awesome-bootstrap-checkbox.css')?>" rel="stylesheet">
        <!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
        <script src="<?php echo base_url('assets/landing/js/skel.min.js')?>"></script>
        <script src="<?php echo base_url('assets/landing/js/init.js')?>"></script>
        <link href="<?php echo base_url('assets/landing/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/style.css')?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/style-wide.css')?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/skel.css')?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/landing/css/style-noscript.css')?>" />
        <link href="<?php echo base_url('assets/css/styles/custom.css')?>" rel="stylesheet" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/logo1.png">
        <link href="<?php echo base_url('assets/css/sweetalert.css') ?>" rel="stylesheet">  
        <!--[if lte IE 9]><link rel="stylesheet" href="<?php echo base_url('assets/landing/css/ie/v9.css')?>" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo base_url('assets/landing/css/ie/v8.css')?>" /><![endif]-->
        
        <style>
            #result{
                display: none;
            }
            #login{
                display: none;
            }
        </style>
    </head>
    <body class="loading">
        <div id="wrapper">
            <div id="bg"></div>
            <div id="overlay"></div>
            <div id="main">

                <!-- Header -->
                    <header id="header">
                        <div class="col-md-4 col-md-offset-4" align="center">
                            
                            <div class="reset-panel panel panel-primary" style="margin-top:20px; background:rgba(255, 255, 255, 0.4); border-radius:10px 10px 0 0;font-family:raleway;">
                                <div class="panel-heading" align="center" style="border-radius:10px 10px 0 0 ">
                                    <!--<img src="<?php echo base_url('assets/landing/css/images/logoy.png')?>" style="margin-bottom:10px;"/>-->
                                    <h2 style="font-family:raleway; font-size:30px" >Reset Password</h2>
                                </div>
                                <div class="panel-body">
                                    <form role="form" id="reset-form">
                                        <fieldset>
                                            <div class="form-group" id="input">
                                                <input class="form-control" placeholder="Enter Email Address" name="email" id="email" type="email" autofocus>
                                            </div>

                                            <div class="form-group" id="reset">
                                                <button type="submit" name="submit" id="btn-reset" class="btn btn-primary btn-block reset-button">Reset Password</button>
                                            </div>
                                            <span id="result">Your password has been reset. Check your email for details.</span>
                                            <div class="form-group" id="login">
                                                <a href="<?php echo base_url('index.php/login'); ?>" class="btn btn-success btn-block">Login</a>
                                            </div>
                                        </fieldset>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </header>

                
            </div>
        </div>
        <!-- Footer -->
        <footer id="footer">
            <span class="copyright"> &copy; <?php echo date('Y'); ?> ITMS Africa</span>
        </footer>
        <!-- Core Scripts - Include with every page -->
         <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
          <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
         <script>
        $(function () {

            $('#reset-form').on('submit' , function () {
                
                var email = $('#email').val().trim();
                
                if (email.length == 0) {
                    swal({   title: "Info",   text: "Email required",   type: "info",   confirmButtonText: "ok" });
                    return false;   
                }
                
                /*swal({
                  title: 'Warning',
                  text: "Are you sure you want to reset your password?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Continue!',
                  closeOnConfirm: true
                },
                function() {
                    */
                    $('.reset-button').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('.reset-button').prop('disabled', true);

                    $.ajax({
                        method: 'post',
                        url: '<?= base_url('index.php/login/reset') ?>',
                        data: $(this).serialize(),
                        success: function (response) {

                            if(response == 1){

                                $('#input').css("display", "none");
                                $('#reset').css("display", "none");
                                $('#result').css({display: "block", fontSize: "16px", marginBottom: "20px", color: "#000"});
                                $('#login').css("display", "block");

                                swal("Password Reset Success!", "Check your email to find your new reset password", "success");

                            }else if(response == -1) {
                                swal("Info", "Email does not exist", "error");
                            }else if(response == 0){
                                swal("Error", "An error occurred, try again later", "error")
                            }

                            $('#btn-reset').html('Reset Password');
                            $('.reset-button').prop('disabled', false);
                        }
                    });
                    return false;     
                /*});*/
            });

        });
    </script>
                
    </body>
</html>