<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>

<html lang="en" class="no-js">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

    <title>ITMS Africa | Sign In</title>

    <!-- Begin Page Progress Bar Files -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pace-0.5.1/pace.min.js')?>"></script>
    <link href="<?php echo base_url('assets/js/plugins/pace-0.5.1/themes/pace-theme-minimal.css')?>" rel="stylesheet">
    <!-- // Page Progress Bar Files -->

    <!-- Core CSS - Include with every page -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url('assets/icons/font-awesome/css/font-awesome.css')?>" rel="stylesheet">
    <!-- Themify Icons -->
    <link href="<?php echo base_url('assets/icons/themify/themify-icons.css')?>" rel="stylesheet">
    <!-- IonIcons Pack -->
    <link href="<?php echo base_url('assets/icons/ionicons-2.0.1/css/ionicons.min.css')?>" rel="stylesheet">
    <!-- Awesome Bootstrap Checkboxes -->
    <link href="<?php echo base_url('assets/css/awesome-bootstrap-checkbox.css')?>" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="<?php echo base_url('assets/css/plugins/morris/morris.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/plugins/timeline/timeline.css')?>" rel="stylesheet">
    <!-- Date Range Picker Stylesheet -->
    <link href="<?php echo base_url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/styles/default.css')?>" type="text/css" rel="stylesheet" id="style_color" />
    <!-- Style LESS -->
    <link href="<?php echo base_url('assets/less/animate.less?1436965415')?>" rel="stylesheet/less" />
	<link href="<?php echo base_url('assets/css/styles/custom.css')?>" rel="stylesheet" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/logo1.png">
    <link href="<?php echo base_url('assets/css/sweetalert.css') ?>" rel="stylesheet">   
    
</head>

<body id="login" class=""style="overflow-x:hidden">

<div id="page-wrapper" class="body-login">
	<div class="no-border ">
		<div class="row">
			<div class="col-md-4 col-md-offset-4" align="center">
				<img src="<?php echo base_url('assets/images/system/main-logo.png')?>">
				<div class="login-panel panel panel-default" style="margin-top:20px; background:rgba(255, 255, 255, 0.4); border-radius:10px 10px 0 0">
					<div class="panel-heading" align="center" style="border-radius:10px 10px 0 0">
						<h3 >Please Sign In</h3>
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
									<button type="submit" name="submit" id="btn-login" class="btn btn-success btn-block login-button">Login</button>
								</div>
								<div class="form-group">
									<a href="<?php echo base_url('index.php/register'); ?>" class="btn btn-inverse btn-block">Register for an Account</a>
								</div>
								
								
								
							</fieldset>
							
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /.row -->
	</div>        
</div>

<!-- footer -->
<footer class="reset margin left">
	<p>&copy; 2015, ITMS Africa</p>
</footer>

    <!-- /#wrapper -->
    <!-- Core Scripts - Include with every page -->
    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>

    <script src="<?php echo base_url('assets/js/plugins/jquery-cookie/jquery.cookie.js')?>"></script>

    <!-- jQuery easing | Script -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    
    <!-- Bootstrap minimal -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
    <!-- Sparkline | Script -->
    <script src="<?php echo base_url('assets/js/plugins/sparklines/jquery.sparkline.js')?>"></script>
    <!-- Easy Pie Charts | Script -->
    <script src="<?php echo base_url('assets/js/plugins/easy-pie/jquery.easypiechart.min.js')?>"></script>
    <!-- Date Range Picker | Script -->
    <script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js')?>"></script>
    <!-- BlockUI for reloading panels and widgets -->
    <script src="<?php echo base_url('assets/js/plugins/block-ui/jquery.blockui.js')?>"></script>



    <script src="<?php echo base_url('assets/js/jquery-ui.custom.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/holder.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js')?>"></script>

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="<?php echo base_url('assets/js/plugins/morris/raphael-2.1.0.min.js')?>"></script>
    
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.tooltip.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.resize.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/nicescroll/jquery.nicescroll.min.js')?>"></script> 
    
    <!--<script src="<?php echo base_url('assets/js/demo/login.js')?>"></script>-->
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
