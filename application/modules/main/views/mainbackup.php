<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
$this->load->model('login/mdl_auth');
$subsc = $this->mdl_auth->get_company_subscriptions(25);
print_r('<pre>');
print_r($subsc);
exit;*/

//date_default_timezone_set("E. Africa Standard Time");
//$now = gmdate("Y-m-d H:i:s");
//echo $now;exit;       


$this->load->model('main/mdl_main');
$v = $this->mdl_main->fetch_report_vehicles($this->session->userdata('itms_company_id'));

$count_vehicles = sizeof($v);

?>
<!DOCTYPE html>

<html lang="en" class="no-js">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

    <title><?php echo $title?></title>
    
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,500,700,500italic,700italic,900,900italic,300,300italic,100italic,100' rel='stylesheet' type='text/css'>
    
    <!-- Begin Page Progress Bar Files -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pace-0.5.1/pace.min.js')?>"></script>
    <link href="<?php echo base_url('assets/js/plugins/pace-0.5.1/themes/pace-theme-minimal.css')?>" rel="stylesheet">
    
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
    
    <link href="<?php echo base_url('assets/css/styles/default.css')?>" type="text/css" rel="stylesheet" id="style_color" />


    <!-- Style LESS -->
    <link href="<?php echo base_url('assets/less/animate.less?1436965415')?>" rel="stylesheet/less" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/logo1.png">
    <link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css')?>" rel="stylesheet" type="text/css" media="all">
    <link href="<?php echo base_url('assets/css/styles/custom.css')?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/sweetalert.css') ?>" rel="stylesheet">



    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinycolor/0.11.1/tinycolor.min.js"></script>
    <script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js')?>"></script>
    
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
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <!--<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>-->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap-datetimepicker.min.js') ?>"></script>
    


    <!--<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap-datetimepicker.min.css') ?>" />
    <link href="<?php echo base_url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js')?>"></script>
   
    

</head>

<body ng-app="app">

    <div id="wrapper">
        
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle pull-left margin left" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="?page=index">
                    <div style="padding-top:13px; font-size:18px; font-weight:300;width:100%; color: #18bc9c; overflow:hidden; height:50px; "><?php echo $this->session->userdata('company_name'); ?></div>
                </a>
                
            </div>
            <!-- /.navbar-header -->

            <!-- MEGA MENU -->
            <ul class="nav navbar-top-links navbar-left mega-menu mega-menu-dark hidden-xs hidden-sm">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-cog fa-spin fa-fw"></i> Settings
                    </a>
                    <div class="dropdown-menu mega-menu animated flipInY">
                        <div class="row">
                            <div class="col-sm-4 border right">
                                <h3><i class="fa fa-plus fa-2x fa fw"></i> Add</h3>
                                <div>
                                    <a href="<?php echo site_url('vehicles/add_vehicle');?>"><i class="fa fa-car"></i> New Vehicle</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('vehicles/add_group');?>"><i class="fa fa-sitemap"></i> New Groups</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('personnel/add_personnel');?>"><i class="fa fa-users"></i> New Personnel</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('vehicles/add_owner');?>"><i class="fa fa-plus-circle"></i> New Owner</a>
                                </div>
                                <!--<div>
                                    <a href="<?php echo site_url('dealers/add_dealer');?>"><i class="fa fa-file"></i> New Dealer</a>
                                </div>-->
                                <div>
                                    <a href="<?php echo site_url('clients/add_client');?>"><i class="fa fa-money"></i> New Client</a>
                                </div>
                                
                            </div>
                            <!--<div class="col-sm-3 border right">
                                <h3><i class="fa fa-plus fa-2x fa fw"></i> View</h3>
                                <div>
                                    <a href="<?php echo site_url('vehicles');?>"><i class="fa fa-car"></i> View Vehicle</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('vehicles/groups');?>"><i class="fa fa-sitemap"></i> View Groups</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('personnel');?>"><i class="fa fa-users"></i> View Personnel</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('owners');?>"><i class="fa fa-user-plus"></i> View Owner</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('dealers');?>"><i class="fa fa-file"></i> View Dealer</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('clients');?>"><i class="fa fa-money"></i> View CLient</a>
                                </div>
                                
                            </div>-->
                            <div class="col-sm-4 border right">
                                <h3><i class="fa fa-map-marker fa fw"></i> Geofencing</h3>
                                <div>
                                    <a href="<?php echo site_url('settings/create_landmarks');?>"><i class="fa fa-location-arrow"></i> Create Landmarks</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/create_zones');?>"><i class="fa fa-area-chart"></i> Create Zones</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/create_routes');?>"><i class="fa fa-road"></i> Create Routes</a>
                                </div>
                                
                                <!--<div>
                                    <a href="<?php echo site_url('settings/create_destinations_locations');?>"><i class="fa fa-institution"></i> Create Points Of Interests</a>
                                </div>-->
                                
                            </div>
                            <div class="col-sm-4">
                                <h3><i class="fa fa-cogs fa fw"></i> Advanced</h3>
                                <div>
                                    <a href="<?php echo site_url('settings/add_device');?>"><i class="fa fa-plus fa-spin"></i> Add Devices</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('gps_tracking/gps_devices_integration');?>"><i class="fa fa-map-marker fa-spin"></i> Device Integration</a>
                                </div>
                                <!--<div>
                                    <a href="<?php echo site_url('tpms/tpms_devices_integration');?>"><i class="fa fa-circle-o-notch"></i> TPMS Sensors Integration</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('vehicles/vehicles_pairing');?>"><i class="fa fa-truck"></i> Vehicle Pairing</a>
                                </div>-->
                                <div>
                                    <a href="<?php echo site_url('vehicles/tyre_axle_configurations');?>"><i class="fa fa-circle fa-spin"></i> Tyres axle configuration</a>
                                </div>
                                <?php if ($this->session->userdata('protocal') > 5) {?>
                                <div>
                                    <a href="<?php echo site_url('personnel/add_user');?>"><i class="fa fa-user-plus"></i> Add User</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/user_permissions');?>"><i class="fa fa-lock"></i> User permissions</a>
                                </div>

                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                </li>
                
            </ul>
            <!-- // MEGA MENU -->

            <ul class="nav navbar-top-links navbar-right hidden-xs">
                <ul class="nav navbar-top-links navbar-left mega-menu hidden-xs hidden-sm">
                    
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-map-marker fa-fw"></i>
                            <span id="badge-alerts" class="badge badge-notification badge-danger animated fadeIn">0</span>
                        </a>
                        <div class="dropdown-menu drop2 animated fadeInUp">
                            <div class="row">
                                <div class="col-sm-12 border right">
                                    <div class="rpt-holder">
                                        <h3>GPS tracking alerts</h3>
                                        <ul class="reports-mn alerts-holder">
                                        </ul>
                                     </div>
                                </div>
                            </div> 
                            <br>
                            <div class="col-md-12" align="center" style="background:#eee; margin-top: 10px;">
                                <a href="<?php echo site_url('main/alerts_view'); ?>"><strong class="see-all">See All Alerts</strong></a>
                            </div>
                        </div>
                    </li>
                    
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-dashboard fa-fw"></i> <span id="badge-alerts" class="badge badge-notification badge-danger animated fadeIn">0</span>
                        </a>
                        <div class="dropdown-menu drop2 animated fadeInUp">
                            <div class="row">
                                <div class="col-sm-12 border right">
                                    <div class="rpt-holder">
                                        <h3>Tyre Pressure alerts</h3>
                                        <ul class="reports-mn tpms-alerts-holder">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12" align="center" style="background:#eee; margin-top: 10px;">
                                <a href="<?php echo site_url('main/alerts_view'); ?>"><strong class="see-all">See All Alerts</strong></a>
                            </div>
                        </div>
                    </li>
                    
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-exclamation-triangle fa-fw"></i> <span id="badge-alerts" class="badge badge-notification badge-danger animated fadeIn">0</span>
                        </a>
                        <div class="dropdown-menu drop2 animated fadeInUp">
                            <div class="row">
                                <div class="col-sm-12 border">
                                    <div class="rpt-holder">
                                        <h3>Other Alerts</h3>
                                        <ul class="reports-mn other-alerts-holder">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12" align="center" style="background:#eee; margin-top: 10px;">
                                <a href="<?php echo site_url('main/alerts_view'); ?>"><strong class="see-all">See All Alerts</strong></a>
                            </div>
                        </div>
                    </li>
                    
                    <li class="dropdown">
                        <a class="dropdown-toggle pop-report-panel" data-toggle="dropdown" href="#">
                            <i class="fa fa-files-o fa-fw"></i> Reports
                        </a> 
                    </li>
                </ul>
               
                <li class="dropdown">
                    <a class="dropdown-toggle user" data-toggle="dropdown" href="#">
                        <?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?>
                        <img src="<?php echo base_url('uploads/users/128')  .'/'. $this->session->userdata('user_logo');?>" alt="" class="img-responsive img-circle user-img" style="width:35px;height:35px; border:none">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeInUp">
                        <li class="user-information">
                             <div class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object user-profile-image img-circle" src="<?php echo base_url('uploads/users/128') .'/'. $this->session->userdata('user_logo');?>" style="width:65px;height:65px;">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?></h4>
                                    <hr style="margin:8px auto">

                                    <span class="label label-info">User</span>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url('index.php/main/companydetails')?>"><i class="fa fa-info-circle"></i> Company Details</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url('index.php/userprofile')?>"><i class="fa fa-gear fa-fw"></i> Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url('index.php/login/logout');?>" class="text-danger"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                
            </ul>
            <!-- /.navbar-top-links -->

        </nav>
        <!-- /.navbar-static-top -->


        <nav id="menu" class="navbar-default navbar-fixed-side hidden-xs" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-user">
                        <!--<div class="user-img">
                            <img src="<?php echo base_url('assets/images/users/65x65/') .'/'. $this->session->userdata('user_logo'); ?>" alt="" data-src="<?php echo base_url('assets/images/people/65x65/1.jpg')?>" data-src-retina="../assets/images/people/x2/1x2.jpg')?>" width="65" height="65" class="img-responsive img-circle animated bounceIn">
                        </div>
                        <div class="user-info">
                            <div class="user-greet">Welcome</div>
                            <div class="user-name"><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?></div>
                            <div class="user-status animated bounceInLeft">
                                <span class="label label-success dropdown-toggle">Online</span>
                            </div>
                        </div>-->
                       <!-- <h3 class="menu-name" style="color:#18bc9c;margin:0px;margin-top:10px;font-weight:500;text-align:center"> <?php echo $this->session->userdata('company_name');?> </h3>-->
                    </li>
                    <li class="sidebar-search">
                        <!--<div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Quick Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-inverse" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>-->
                        <!-- /input-group -->
                    </li>
                    
                    <?php if (in_array(1, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li >
                            <a href="<?php echo base_url();?>"><i class="fa fa-desktop fa-fw"></i> <span class="menu-name">Analytics</span></a>
                        </li>
                    <?php } ?>
                    <?php if (in_array(1, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        
                        <?php if (in_array(4, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-map-marker fa-spin fa-fw"></i> <span class="menu-name">GPS Tracking</span> <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking')?>">Dashboard</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/telematics')?>">Status</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/landmarks')?>">Landmarks</a>
                                    </li>
                                   
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/zones')?>">Zones</a>
                                    </li>
                                     <li >
                                        <a href="<?php echo site_url('gps_tracking/routes')?>">Routes</a>
                                    </li>                         
                                    
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php }} ?>
                    <?php if (in_array(2, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(5, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="<?php echo site_url('tpms/telematics')?>"><img style='height:16px;width:16px' src='<?=base_url("assets/images/itms/tpms/tpms.png")?>'/><span class="menu-name">&nbsp; &nbsp; &nbsp; &nbsp;TPMS</span></a>
                                <!-- <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?php echo site_url('tpms')?>">TPMS Dashboard</a>
                                <!--    </li>
                                    <li >
                                        <a href="<?php echo site_url('tpms/telematics')?>">Status</a>
                                <!--    </li>
                                </ul> -->
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } }?>
                    
                    
                    <?php if (!isset($allMenus)) { ?>
                    <?php if (in_array(3, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(6, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href=""><i class="fa fa-home fa-fw"></i> <span class="menu-name">Yard/Truck Management</span>  <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?php echo site_url('yard')?>">Issues</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('yard')?>">Work Orders</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('yard')?>">Service Log</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('yard')?>">Fuel Log</a>
                                    </li>
                                    
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } }?>
                    
                    <?php if (in_array(4, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(7, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-circle fa-fw"></i> <span class="menu-name">Tyre Assets Management</span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="<?php echo site_url('parts')?>">View Assets</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('parts/add_new')?>">Add Assets</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('parts/add_new')?>">Assigned Assets</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } } ?>

                    <?php if (in_array(5, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(8, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-calendar-o fa-fw"></i> <span class="menu-name">Order Management</span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="<?php echo site_url('orders')?>">Transport Orders</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('orders/invoices')?>">Invoices</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('orders/clients')?>">Clients</a>
                                    </li>
                                    
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } } ?>
                    <?php if (in_array(6, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(9, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-train fa-fw"></i> <span class="menu-name">Speed Limiter</span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    
                                   
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } }?>
                    <?php }?>
                    <?php if (in_array(3, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li>
                            <a href=""><i class="fa fa-automobile fa-fw"></i> <span class="menu-name">Vehicles</span> <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo site_url('vehicles')?>">List Vehicles<span class="label label-info pull-right"><?php echo $count_vehicles; ?></span></a>
                                </li>
                                <li >
                                    <a href="<?php echo site_url('vehicles/groups')?>">Groups</a>
                                </li>
                                <!--<li >
                                    <a href="<?php echo site_url('vehicles/categories')?>">Categories</a>
                                </li>
                                <li >
                                    <a href="<?php echo site_url('vehicles/types')?>">Types</a>
                                </li>-->
                                <li >
                                    <a href="<?php echo site_url('owners')?>">Owners</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php } ?>
                    
                            <li >
                                <a href="<?php echo site_url('settings/devices')?>"><i class="fa fa-sitemap fa-fw"></i> <span class="menu-name">Devices</span></a>
                            </li>
                            <li>
                                 <a href="<?php echo site_url('personnel')?>"><i class="fa fa-users fa-fw"></i><span class="menu-name">Personnel</span></a>
                             </li>
                     <?php if (in_array(11, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                             <!--<li >
                                 <a href=""><i class="fa fa-suitcase fa-fw"></i><span class="menu-name">Dealers</span></a>
                             </li>-->
                     <?php } ?>
                            
                            <li >
                                <a href="#" style="color:#eee; font-weight:600"><i class="fa fa-money fa-fw"></i> <span class="menu-name">Trips</span> <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?php echo site_url('trips')?>">List Trips<!--<span class="label label-info pull-right"><?php echo 0; ?></span>--></a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('clients')?>">Clients</a>
                                    </li>
                                    <li>
                                        <a class="" href="<?php echo site_url('trips/create_trips')?>" >Create Trip</a>
                                    </li>
                                </ul>
                            </li>
                    

                        <!--<li >
                            <a href="#" class="pop-report-panel"><i class="fa fa-files-o fa-fw"></i> <span class="menu-name">Reports</span></a>
                        </li>  -->      
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
                        <i class="fa <?php echo $fa; ?> animated flip"></i> <?php echo $content_title; ?> <!--<span class="sub-heading"><?php echo $content_subtitle; ?></span>-->
                    </h3>
                </div>
                <!--<div class="col-sm-6 col-md-6 ">
                    <span class="hr-content pull-right">
                        <?php if (isset($content_btn)) { echo $content_btn; } ?>
                    </span>    
                </div>-->
           </div>
        </div>
        <!--<ol class="breadcrumb">
            <li><a href="<?php echo  base_url();?>">HOME</a></li>
            <li><a href="<?php echo  site_url($content_url);?>"><?php echo strtoupper($content_title); ?></a></li>
        </ol>-->

        <?php $this->load->view($content);?>

    </div>
    <div class="page-alert" style="z-index: 10000;"></div>
    <!-- /#page-wrapper -->
                
    </div>
             
            <!--Reports panel-->
        
    <!-- footer -->
    <footer class="footer">
        <p>&copy; <?php echo date('Y')?> ITMS AFRICA, ALL RIGHTS RESERVED.</p>
    </footer>

    <?php include 'report_panel.php'; ?>
    <!-- /#wrapper -->
    <!-- Core Scripts - Include with every page -->
    
    


    <!-- Button that triggers the popup -->
        
    <!-- Element to pop up -->
    <div id="element_to_pop_up" class="alert-popup" style="z-index: 10000; top:100px;">Content of popup</div> 

    <script src="<?php echo base_url('assets/js/plugins/jquery-cookie/jquery.cookie.js')?>"></script>

    <!--<script type="text/javascript">
        var primaryColor = '#303641',
            dangerColor = '#F22613',
            successColor = '#2ecc71',
            warningColor = '#F5AB35',
            infoColor = '#3498db',
            inverseColor = '#111',
            cursorColor = ( $.cookie('cursorColor') ) ? $.cookie('cursorColor') : '#333';
        
                $.cookie('dev', false);
        
        // Setting URL 
        var url = '<?php echo base_url('assets')?>',
            time = '1436965415';

        var themeStyle = ( $.cookie('themeStyle') ) ? $.cookie('themeStyle') : 'default';
        
        if ( $.cookie('dev') == 'true') {
            $("#style_color").attr('href', url + '/less/styles/'+themeStyle+'.less?' + time);
        } else {
            $("#style_color").attr('href', url + '/css/styles/'+themeStyle+'.css');
        }
    </script>-->

    <div class="overshadow"></div>

    <!-- jQuery easing | Script -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    
    <!-- Bootstrap minimal -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
    <!-- Sparkline | Script -->
    <script src="<?php echo base_url('assets/js/plugins/sparklines/jquery.sparkline.js')?>"></script>
    <!-- Easy Pie Charts | Script -->
    <script src="<?php echo base_url('assets/js/plugins/easy-pie/jquery.easypiechart.min.js')?>"></script>
    <!-- Date Range Picker | Script -->
     <!-- BlockUI for reloading panels and widgets -->
    <script src="<?php echo base_url('assets/js/plugins/block-ui/jquery.blockui.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-ui.custom.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/holder.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js')?>"></script>

    
    

    <script src="<?php echo base_url('assets/js/plugins/nicescroll/jquery.nicescroll.min.js')?>"></script> 
    <!-- Init Scripts - Include with every page -->
    <script src="<?php echo base_url('assets/js/init.js')?>"></script>

    <script src="<?php echo base_url('assets/sweetalert.min.js') ?>"></script>      
    <script src="<?php echo base_url('assets/js/jquery.bpopup.min.js') ?>"></script>
    
    <script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/select2/select2.js')?>"></script>
    <script type="text/javascript">
        $(function() {

            //alert();
            if($("#select2_multiple").length > 0) 
                $("#select2_multiple").select2();    
            
            if ($("#reservation").length > 0)
                $("#reservation").daterangepicker();

        });

    </script>
    <script type="text/javascript">
        $(function () {

            
            refresh_alerts();

            var refresh = setInterval(function(){ refresh_alerts() }, 15000);
            


            function refresh_alerts () {
                console.log('Refreshing alerts....');
                

                $.ajax({
                  type    : "POST",
                  cache   : false,
                  data : {refresh:'refresh'},
                  url     : "<?php echo base_url('index.php/main/refresh_alerts') ?>",
                  success: function(response) {
                      res = JSON.parse(response);
                      

                      count = res.data.length;

                      var count_gps = 0;
                      var count_tpms = 0;
                      var count_others = 0;

                      $appends = '';
                        $('.see-all').html('No Alerts');
                        $('#badge-alerts').hide();
                        

                        $('body').find('.alert-popup').remove();

                        if (count > 0) {
                            if (parseInt(res.views_not) > 0) {
                                $('#badge-alerts').show();
                                $('#badge-alerts').html(res.views_not);
                            } else {
                                $('#badge-alerts').html(0);
                            }

                            $('.see-all').html('See All Alerts');
                            $('.alerts-holder').html('');
                            $('.gps-alerts-holder').html('');
                            $('.tpms-alerts-holder').html('');
                            var count = 0;
                            var v = 0;
                            var str = '<div style="max-height:150px; overflow-y:scroll;overflow-x:hidden"><table class="table table-striped table-hover">';
                            for (var alert in res.data) {
                             //console.log(res.data[alert].alert_header);
                             //count++;

                             if (res.data[alert].pop_shown == 0) {
                                v++;
                                str = str.concat('<tr><td>'+res.data[alert].assets_name+'</td><td>'+res.data[alert].alert_header+'</td><td>'+
                                                            '<a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='+res.data[alert].latitude+','+res.data[alert].longitude+'('+res.data[alert].assets_name+')&ie=UTF8&z=12&om=1" target="_blank" class="btn btn-success btn-xs">View</a></td></tr>');
                                
                             }

                             if (res.data[alert].alert_header == "Overspeeding" || res.data[alert].alert_header == "Tyre pressure") {
                                $line = "danger";
                             } else {
                                $line = "warning";
                             }
                                
                                
                                var tmagoYr = new Date(res.data[alert].add_date).getFullYear();
                                var tmagoMn = new Date(res.data[alert].add_date).getMonth();
                                var tmagoDy = new Date(res.data[alert].add_date).getDate();

                                $alert_content ='<li class="'+$line+'">' +
                                                '<strong>'+ res.data[alert].alert_header+'</strong><br>' +
                                                res.data[alert].assets_friendly_nm +'('+res.data[alert].assets_name+')<br>' + 
                                                '<small class="text-muted help-block">'+moment([tmagoYr, tmagoMn, tmagoDy]).fromNow();+'</small></li>'
                                
                                if (res.data[alert].alert_header == 'Overspeeding') {
                                    $('.alerts-holder').append($alert_content);
                                    count_gps++;
                                } else if (res.data[alert].alert_header == 'Tyre pressure') {
                                     $('.tpms-alerts-holder').append($alert_content);
                                     count_tpms++;
                                } else {
                                    $('.other-alerts-holder').append($alert_content);
                                    count_others++;
                                }

                            
                                count++;
                            };


                                


                            if (v > 0) {
                               str = str.concat('</table></div>');
                               swal({title:'',   html: str,   type:'info',   confirmButtonText: "Close" });
                            }


                            //swal({title: '',   html:'You can use <b>bold text</b>, ' +     '<a href="//github.com">links</a> ' +     'and other HTML tags', confirmButtonText: 'Close', });
                        } else {
                            $('#badge-alerts').hide();
                            $('.alerts-holder').html('<li class="btn btn-block btn-info">No GPS alerts</li>');
                            $('.tpms-alerts-holder').html('<li class="btn btn-block btn-info">No TPMS alerts</li>');
                            $('.other-alerts-holder').html('<li class="btn btn-block btn-info">No Other alerts</li>');
                        }

                        if (count_gps == 0) {
                            $('.alerts-holder').html('<li class="btn btn-block btn-info">No GPS alerts</li>');
                        }

                        if (count_tpms==0) {
                            $('.tpms-alerts-holder').html('<li class="btn btn-block btn-info">No TPMS alerts</li>');
                        } 

                        if (count_others==0) {
                            $('.other-alerts-holder').html('<li class="btn btn-block btn-info">No Other alerts</li>');
                        } 



                        //alert(response);

                        //swal({title:'Notifications',   text: res.data[alert].alert_header,   type:'info',   confirmButtonText: "ok" });
                        //console.log($appends);
                  }
                  
              });

           }


            $('#my-button').bind('click', function(e) {
                // Prevents the default action to be triggered. 
                e.preventDefault();
                // Triggering bPopup when click event is fired
                $('#element_to_pop_up').bPopup();
            });

            $('.pop-report-panel').on('click', function() {
                $('#report-panel').fadeIn(1000);
                $('.overshadow').fadeIn(1000);

            });

            $('.cx').on('click', function() {
                $('#report-panel').fadeOut(1000);
                $('.panel-diagnostics').fadeOut(1000);
                $('.overshadow').fadeOut(1000);
            });



            check_pass_change ();

            function check_pass_change () {
                var change = '<?= $this->session->userdata('change_password'); ?>';

                if (!$('#change-input').val()) {

                    if (change == 1) {
                        swal({
                          title: 'Action Required',
                          text: "Please! Update your password",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Update Password',
                          closeOnConfirm: false
                        },
                        function() {
                            window.location.href = "<?= site_url('userprofile'); ?>";
                            
                        });
                    }
                }
            }


            $(".navbar-fixed-side").hover(function(){
                    $(this).animate({"width":"250px"}, 0);
                    $("#page-wrapper").animate({"margin-left":"250px"}, 1000);
                    $(".menu-name").delay(800).fadeIn(100)
                }, function(){
                    $(this).animate({"width":"70px"}, 0);
                    $("#page-wrapper").animate({"margin-left":"70px"}, 1000);
                    $(".nav-second-level").removeClass("in");
                    $(".menu-name").fadeOut(500);
            });
            
        });
    </script>       
</body>

</html>