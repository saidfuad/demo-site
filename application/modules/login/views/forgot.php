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
        
        <title>HAWK | Sign In</title>
        
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
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/hawk_logo.png">
        <link href="<?php echo base_url('assets/css/sweetalert.css') ?>" rel="stylesheet">  
        <!--[if lte IE 9]><link rel="stylesheet" href="<?php echo base_url('assets/landing/css/ie/v9.css')?>" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo base_url('assets/landing/css/ie/v8.css')?>" /><![endif]-->
    </head>
    <body class="loading">
        <div id="wrapper">
            <div id="bg"></div>
            <div id="main">
            
                <center>
                <form role="form" id="login-form">
                    <!-- Logo -->
                    <div class="hawk_logo_login"></div>
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">
                                <span class="fa fa-m fa-user"></span>
                                <span class="line">|</span>
                            </span>
                            <input class="form-control" placeholder="Email/Phone Number" name="input" id="input" type="text" autofocus required="required" aria-describedby="basic-addon1">
                        </div>

                        <div id="spacer"></div>

                        <div class="input-group">
                            <button type="submit" name="submit" id="btn-login" class="btn btn-primary btn-block login-button"><span>Reset Password</span></button>
                        </div>

                    </fieldset>
                </form>
                </center>
            </div>
        </div>

        <!-- Core Scripts - Include with every page -->
         <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
          <script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
         <script>
        $(function () {

            $('#login-form').on('submit' , function () {


                var input = $('#input').val().trim();

                if (input.length <= 0) {
                    swal({   title: "Info",   text: "Email/Phone Number required",   type: "info",   confirmButtonText: "ok" });
                    return false;   
                }


                $('.login-button').html('<i class="fa fa-spinner fa-spin"></i>');
                $('.login-button').prop('disabled', true);
               
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/api/apimobile/forgot_password') ?>',
                    data: $(this).serialize(),
                    success: function (response) {
                        
                        if (response.status == 1) {
                        swal({title:"Success",   text: "An Email/SMS Has Been Sent With Your Login Details",   type:"success",confirmButtonText: "ok" },
                            function(){
                                 window.location.replace('<?= base_url('index.php/login') ?>');
                            });
                           
                        }else{
                            swal({title:"Error",   text: "Failed.....Try Again Later",   type:"error",   confirmButtonText: "ok" },
                                function(){
                                    window.location.replace('<?= base_url('index.php/login') ?>');
                                });
                        }

                        
                    }
                });

                
                return false;     
            });

        });
    </script>
                
    </body>
</html>
