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
        
        <title>ITMS Africa | Sign In</title>
        
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
    </head>
    <body class="loading">
        <div id="wrapper">
            <div id="bg"></div>
            <div id="overlay"></div>
            <div id="main">

                <!-- Header -->
                    <header id="header">
                        <div class="col-md-4 col-md-offset-4" align="center">
                            
                            <div class="login-panel panel panel-primary" style="margin-top:20px; background:rgba(255, 255, 255, 0.4); border-radius:10px 10px 0 0;font-family:raleway;">
                                <div class="panel-heading" align="center" style="border-radius:10px 10px 0 0 ">
                                    <img src="<?php echo base_url('assets/landing/css/images/logoy.png')?>" style="margin-bottom:10px;"/>
                                    <h2 style="font-family:raleway; font-size:30px" >Sign In</h2>
                                </div>
                                <div class="panel-body">
                                    <form role="form" id="login-form">
                                        <fieldset>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="Enter username/email" name="username" id="username" type="text" autofocus required="required">
                                            </div>

                                            <div class="form-group">
                                                <input class="form-control" placeholder="Password" name="password" id="password" type="password" value="" required="required">
                                            </div>
                                            <!--<div class="form-group">
                                                <div class="checkbox ">
                                                    <label>
                                                        <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                                                    </label>
                                                </div>
                                            </div>-->
                                            <div class="form-group">
                                                <button type="submit" name="submit" id="btn-login" class="btn btn-primary btn-block login-button">Login</button>
                                            </div>
                                            <div class="form-group">
                                                <a href="<?php echo base_url('index.php/register'); ?>" class="btn btn-success btn-block">Register for an Account</a>
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
            <span class="copyright"> &copy; <?php echo date('Y')?> ITMS Africa</a></span>
        </footer>
        <!-- Core Scripts - Include with every page -->
         <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
          <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
         <script>
        $(function () {

            $('#login-form').on('submit' , function () {


                var username = $('#username').val().trim();
                var password = $('#password').val().trim();

                if (username.length <= 0) {
                    swal({   title: "Info",   text: "Username required",   type: "info",   confirmButtonText: "ok" });
                    return false;   
                } else if (password.length <= 0) {
                    swal({   title: "Info",   text: "Password required",   type: "info",   confirmButtonText: "ok" });
                    return false; 
                }


                $('.login-button').html('<i class="fa fa-spinner fa-spin"></i>');
                $('.login-button').prop('disabled', true);
               
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/login/authenticate') ?>',
                    data: $(this).serialize(),
                    success: function (response) {
                        response = JSON.parse(response);
                        
                        if (response.message == 'redirect_admin') {
                            window.location.replace('<?= base_url('index.php/admin') ?>');
                        } else if (response.message == 'redirect_home') {
                            window.location.replace('<?= base_url('index.php/home') ?>');
                        } else {
                            swal({title:response.title,   text: response.message,   type:response.type,   confirmButtonText: "ok" });
                            $('#btn-login').html('Login');
                            $('.login-button').prop('disabled', false);
                        }

                        
                    }
                });

                
                return false;     
            });

        });
    </script>
                
    </body>
</html>