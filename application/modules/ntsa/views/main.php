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
        <title>
            <?php echo $title ?>
        </title>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,500,700,500italic,700italic,900,900italic,300,300italic,100italic,100' rel='stylesheet' type='text/css'>
        <!-- Begin Page Progress Bar Files -->
        <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pace-0.5.1/pace.min.js') ?>"></script>
        <link href="<?php echo base_url('assets/js/plugins/pace-0.5.1/themes/pace-theme-minimal.css') ?>" rel="stylesheet">
        <!-- Core CSS - Include with every page -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
        <!-- Font Awesome Icons -->
        <link href="<?php echo base_url('assets/icons/font-awesome/css/font-awesome.css') ?>" rel="stylesheet">
        <!-- Themify Icons -->
        <link href="<?php echo base_url('assets/icons/themify/themify-icons.css') ?>" rel="stylesheet">
        <!-- IonIcons Pack -->
        <link href="<?php echo base_url('assets/icons/ionicons-2.0.1/css/ionicons.min.css') ?>" rel="stylesheet">
        <!-- Awesome Bootstrap Checkboxes -->
        <link href="<?php echo base_url('assets/css/awesome-bootstrap-checkbox.css') ?>" rel="stylesheet">
        <!-- Page-Level Plugin CSS - Dashboard -->
        <link href="<?php echo base_url('assets/css/plugins/morris/morris.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/css/plugins/timeline/timeline.css') ?>" rel="stylesheet">
        <!-- Date Range Picker Stylesheet -->
        <link href="<?php echo base_url('assets/css/styles/default.css') ?>" type="text/css" rel="stylesheet" id="style_color" />
        <!-- Style LESS -->
        <link href="<?php echo base_url('assets/less/animate.less?1436965415') ?>" rel="stylesheet/less" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/hawk_logo.png">
        <link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css') ?>" rel="stylesheet" type="text/css" media="all">
        <link href="<?php echo base_url('assets/css/styles/custom.css') ?>" rel="stylesheet" />
        <link href="<?php echo base_url('assets/sweetalert.css') ?>" rel="stylesheet">
        <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js') ?>"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/tinycolor/0.11.1/tinycolor.min.js"></script>
        <script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js') ?>"></script>
        <script src="<?php echo base_url('assets/angular.min.js') ?>"></script>
        <!--<script src="<?php echo base_url('assets/locale.js') ?>"></script>-->
        <script src="<?php echo base_url('assets/moment.js') ?>"></script>
        <script src="<?php echo base_url('assets/angular_moment.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/app.js') ?>"></script>
        <!-- Date Range Picker -->
        <!--<link rel="stylesheet" href="<?php echo base_url('assets/daterangepicker.css') ?>" type="text/css" media="screen" />
        <script src="<?php echo base_url('assets/daterangepicker.js') ?>"></script>
        -->
        <!-- Include Required Prerequisites -->
        <!-- <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
       <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />-->
        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/bootstrap-datetimepicker.min.js') ?>"></script>
        <!--<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap-datetimepicker.min.css') ?>" />
        <link href="<?php echo base_url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css') ?>" rel="stylesheet">
        <script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js') ?>"></script>
    </head>

    <body ng-app="app">
        <div id="wrapper">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom:0px; margin-left: 200px; height: 64px; padding-top:6px;">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle pull-left margin left" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                </div>
                <!-- /.navbar-header -->
                <!-- MEGA MENU -->
                <ul class="nav navbar-top-links navbar-left mega-menu mega-menu-dark hidden-xs hidden-sm">
                    <li class="dropdown">
                        <!--<a class="nav-items" data-toggle="dropdown" href="#"> <i style="color:#c1d72e" class="fa fa-cog fa-fw"></i> &nbsp; Settings </a>-->
                        <div class="dropdown-menu mega-menu animated flipInY" style="margin-left: 16px; margin-top: 16px;">
                            <div class="row">
                                <div class="col-sm-6 border right">
                                    <h3 style="margin-left: 16px;"><i class="fa fa-plus fa-2x fa fw"></i> Add</h3>
                                    <div style="margin-left: 16px;"> <a href="<?php echo site_url('vehicles/add_vehicle'); ?>"><i class="fa fa-car"></i>New Vehicle</a> </div>
                                </div>
                                <div class="col-sm-6 border right">
                                    <h3><i class="fa fa-map-marker fa fw"></i> Geofencing</h3>
                                    <div> <a href="<?php echo site_url('settings/create_landmarks'); ?>"><i class="fa fa-location-arrow"></i>Create Landmarks</a> </div>
                                    <div> <a href="<?php echo site_url('settings/create_zones'); ?>"><i class="fa fa-area-chart"></i>Create Geofence</a> </div>
                                    <div> <a href="<?php echo site_url('settings/create_routes'); ?>"><i class="fa fa-road"></i>Create Routes</a> </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <!-- // MEGA MENU -->
                <ul class="nav navbar-top-links navbar-right hidden-xs">
               
              <li class="dropdown">
                        <a class="dropdown-toggle user" data-toggle="dropdown" href="#">
                            <span style="text-transform: uppercase"><?php echo $this->session->userdata('hawk_account_name'); ?></span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeInUp" style="margin-right: 10px; margin-top: 10px !important; padding-top: 0; border-radius: 10px;s">

                            <li class="divider"></li>
                            <li><a href="#reset_pass" data-toggle="modal"><i class="fa fa-lock fa-fw"></i> Reset Password</a></li>

                            <li class="divider"></li>
                            <li> <a data-toggle='tooltip' data-original-title='Log off the system' data-placement='bottom' href="<?php echo base_url('index.php/login/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                        </ul>
                    </li>

                </ul>
                <!-- /.navbar-top-links -->
            </nav>
            <!-- /.navbar-static-top -->
            <nav id="menu" class="navbar-default navbar-fixed-side hidden-xs" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-user">
                            <div class="hawk_logo"></div>
                        </li>
                        <li>
                            <a class="<?php if(str_replace('/hawk_ntsa/index.php/',"",$_SERVER['REQUEST_URI']) == 'ntsa'){ echo 'active'; } ?>" href="<?php echo site_url('ntsa') ?>"> <i class="fa fa-map-marker fa-fw"></i>Locate Vehicle </a>
                        </li>
                        <li> <a class="<?php
                            if(str_replace('/hawk_ntsa/index.php/ntsa/',"",$_SERVER['REQUEST_URI']) == 'vehicles')
                            { echo 'active'; }
                            ?>" href="<?php echo site_url('ntsa/vehicles') ?>"><i class="fa fa-automobile fa-fw"></i>View Vehicles</a> </li>
                    </ul>
                    <!-- /#side-menu -->
                </div>
                <!-- /.sidebar-collapse -->
            </nav>
            <!-- /.navbar-static-side -->
            <div id="page-wrapper" class="">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 ">
                            <h3 class="heading">
                                <?php if ($fa1 == "" && $fa2 == "" && $fa3 == "" && $fa4 == "" && $fa5 == "") { ?>

                                    <i class="fa <?php echo $fa; ?>"></i> &nbsp; | &nbsp; <?php echo $content_title; ?>
                                    <span class="sub-heading"><?php echo $content_subtitle; ?></span>

                                <?php } else { ?>

                                    <i class="fa <?php echo $fa1; ?>"></i> &nbsp; <i class="fa <?php echo $fa2; ?>"></i> &nbsp;
                                    <i class="fa <?php echo $fa3; ?>"></i> &nbsp; <i class="fa <?php echo $fa4; ?>"></i> &nbsp;
                                    <i class="fa <?php echo $fa5; ?>"></i> &nbsp; | &nbsp; <?php echo $content_title; ?>
                                    <span class="sub-heading"><?php echo $content_subtitle; ?></span>

                                <?php } ?>

                            </h3>
                        </div>
                    </div>
                </div>
                <!--<ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">HOME</a></li>
                    <li><a href="<?php echo site_url($content_url); ?>"><?php echo strtoupper($content_title); ?></a></li>
                </ol>-->
                <?php $this->load->view($content); ?>
            </div>
            <div class="page-alert" style="z-index: 10000;"></div>
            <!-- /#page-wrapper -->
        </div>
        <!--Reports panel-->
        <?php //include 'report_panel.php'; ?>
            <!-- /#wrapper -->
            <!-- Core Scripts - Include with every page -->
            <!-- Button that triggers the popup -->
            <!-- Element to pop up -->
            <div id="element_to_pop_up" class="alert-popup" style="z-index: 10000; top:100px;">Content of popup</div>
            <script src="<?php echo base_url('assets/js/plugins/jquery-cookie/jquery.cookie.js') ?>"></script>
            <div class="overshadow"></div>
            <!-- jQuery easing | Script -->
            <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
            <!-- Bootstrap minimal -->
            <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
            <!-- Sparkline | Script -->
            <script src="<?php echo base_url('assets/js/plugins/sparklines/jquery.sparkline.js') ?>"></script>
            <!-- Easy Pie Charts | Script -->
            <script src="<?php echo base_url('assets/js/plugins/easy-pie/jquery.easypiechart.min.js') ?>"></script>
            <!-- Date Range Picker | Script -->
            <!-- BlockUI for reloading panels and widgets -->
            <script src="<?php echo base_url('assets/js/plugins/block-ui/jquery.blockui.js') ?>"></script>
            <script src="<?php echo base_url('assets/js/jquery-ui.custom.min.js') ?>"></script>
            <script src="<?php echo base_url('assets/js/holder.js') ?>"></script>
            <script src="<?php echo base_url('assets/js/plugins/nicescroll/jquery.nicescroll.min.js') ?>"></script>
            <!-- Init Scripts - Include with every page -->
            <script src="<?php echo base_url('assets/js/init.js') ?>"></script>
            <script src="<?php echo base_url('assets/sweetalert.min.js') ?>"></script>
            <script src="<?php echo base_url('assets/js/jquery.bpopup.min.js') ?>"></script>
            <script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js') ?>"></script>
            <script src="<?php echo base_url('assets/js/plugins/select2/select2.js') ?>"></script>
            <script type="text/javascript">
                $(function () {
                    //alert();
                    if ($("#select2_multiple").length > 0) $("#select2_multiple").select2();
                    if ($("#reservation").length > 0) $("#reservation").daterangepicker();
                });
            </script>
            <script type="text/javascript">
                $(function () {
                    refresh_alerts();
                    var refresh = setInterval(function () {
                        refresh_alerts()
                    }, 15000);

                    function refresh_alerts() {
                        console.log('Refreshing alerts....');
                        $.ajax({
                            type: "POST"
                            , cache: false
                            , url: "<?php echo base_url('index.php/main/refresh_alerts') ?>"
                            , success: function (response) {
                                $('#alerts-badge').html(response);
                            }
                        });
                    }
                    $('#my-button').bind('click', function (e) {
                        // Prevents the default action to be triggered.
                        e.preventDefault();
                        // Triggering bPopup when click event is fired
                        $('#element_to_pop_up').bPopup();
                    });
                    //check_pass_change();
                    function check_pass_change() {
                        var change = '<?= $this->session->userdata('
                        change_password '); ?>';
                        if (!$('#change-input').val()) {
                            if (change == 1) {
                                swal({
                                    title: 'Action Required'
                                    , text: "Please! Update your password"
                                    , type: 'warning'
                                    , showCancelButton: true
                                    , confirmButtonColor: '#3085d6'
                                    , cancelButtonColor: '#d33'
                                    , confirmButtonText: 'Update Password'
                                    , closeOnConfirm: false
                                }, function () {
                                    window.location.href = "<?= site_url('userprofile'); ?>";
                                });
                            }
                        }
                    }
                });
            </script>
    </body>

    </html>
