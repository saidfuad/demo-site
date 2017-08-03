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

    <title><?php echo $title?></title>

    <!-- Begin Page Progress Bar Files -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/pace-0.5.1/pace.min.js')?>"></script>
    <link href="<?php echo base_url('assets/js/plugins/pace-0.5.1/themes/pace-theme-minimal.css')?>" rel="stylesheet">
    
    <!-- Core CSS - Include with every page -->
    <link href="<?php echo base_url('assets/css/bootstrap1.css')?>" rel="stylesheet">
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
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/logo1.png">
    <link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css')?>" rel="stylesheet" type="text/css" media="all">
    <link href="<?php echo base_url('assets/css/styles/custom.css')?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/sweetalert2.css') ?>" rel="stylesheet">



    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
    <!--JQGRID SCRIPTS-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.css') ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('assets/phpgrid/lib/js/jqgrid/css/ui.jqgrid.css') ?>" type="text/css" media="screen" />
    <script type="text/javascript" src="<?php echo base_url('assets/phpgrid/lib/js/jquery.min.js') ?>" ></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/phpgrid/lib/js/jqgrid/js/i18n/grid.locale-en.js') ?>" ></script>
    <script type="text/javascript" src="<?php echo base_url('assets/phpgrid/lib/js/jqgrid/js/jquery.jqGrid.min.js') ?>" ></script>
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/jquery-ui.css') ?>" type="text/css" media="screen" />    -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinycolor/0.11.1/tinycolor.min.js"></script>
    <script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js')?>"></script>
    
</head>

<body >

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
                    <img src="<?php echo base_url('assets/images/system/logo1.png')?>">
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
                                    <a href="<?php echo site_url('vehicles/add_owner');?>"><i class="fa fa-user-plus"></i> New Owner</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('dealers/add_dealer');?>"><i class="fa fa-file"></i> New Dealer</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('clients/add_client');?>"><i class="fa fa-money"></i> New CLient</a>
                                </div>
                                
                            </div>
                            <div class="col-sm-4 border right">
                                <h3><i class="fa fa-map-marker fa fw"></i> Geofencing</h3>
                                <div>
                                    <a href="<?php echo site_url('settings/create_landmarks');?>"><i class="fa fa-location-arrow"></i> Create Landmarks</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/create_areas');?>"><i class="fa fa-area-chart"></i> Create Areas</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/create_routes');?>"><i class="fa fa-road"></i> Create Routes</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/create_trips');?>"><i class="fa fa-circle-o"></i> Create Trips</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/create_locations');?>"><i class="fa fa-institution"></i> Create Points Of Interests</a>
                                </div>
                                
                            </div>
                            <div class="col-sm-4">
                                <h3><i class="fa fa-cogs fa fw"></i> Advanced</h3>
                                <div>
                                    <a href="<?php echo site_url('gps_tracking/gps_devices_integration');?>"><i class="fa fa-map-marker fa-spin"></i> GPS Integration</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('tpms/tpms_devices_integration');?>"><i class="fa fa-circle-o-notch"></i> TPMS Sensors Integration</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('vehicles/vehicles_pairing');?>"><i class="fa fa-truck"></i> Vehicle Pairing</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('vehicles/tyre_axle_configurations');?>"><i class="fa fa-circle fa-spin"></i> Tyres and axles configurations</a>
                                </div>
                                <div>
                                    <a href="<?php echo site_url('settings/user_permisions');?>"><i class="fa fa-padlock fa-spin"></i> User permissions</a>
                                </div>
                                
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
                        <i class="fa fa-list fa-fw"></i> Reports
                    </a>
                    <div class="dropdown-menu drop2 animated fadeInUp">
                        <div class="row">
                            <div class="col-sm-4 border right">
                                <div class="rpt-holder">
                                    <h3>Vehicle Reports</h3>
                                    <ul class="reports-mn">
                                       

                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_summary_report');?>">
                                                    Vehicles Summary Report
                                                </a>    
                                            </h4>
                                        </li> 
                                        <!-- <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/driver_performance_report');?>">
                                                    Driver performance Report
                                                </a>    
                                            </h4>
                                        </li>-->
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_owners');?>">
                                                    Vehicle Owners
                                                </a>    
                                            </h4>
                                        </li> 
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_groups');?>">
                                                    Vehicle Groups
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_categories');?>">
                                                    Vehicle Categories
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_types');?>">
                                                    Vehicle Types
                                                </a>    
                                            </h4>
                                        </li>
                                    </ul>
                                 </div>

                                <div class="rpt-holder">
                                    <h3>GPS Tracking Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_list');?>">
                                                    Trips Report
                                                </a>    
                                            </h4>
                                        </li>

                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_list');?>">
                                                    Points of interests Report
                                                </a>    
                                            </h4>
                                        </li>

                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_list');?>">
                                                    Route Infringement Report
                                                </a>    
                                            </h4>
                                        </li>

                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_list');?>">
                                                    Landmark Report
                                                </a>    
                                            </h4>
                                        </li>

                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_list');?>">
                                                    Stops Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/vehicle_list');?>">
                                                    Distance Report
                                                </a>    
                                            </h4>
                                        </li>

                                    </ul>
                                 </div>    

                            </div>
                            <div class="col-sm-4 border right">
                                <div class="rpt-holder  disabled-content">
                                    <h3>TPMS Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                   Status Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Tyre status Report
                                                </a>    
                                            </h4>
                                           
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div class="rpt-holder  disabled-content">
                                    <h3>Yard/Truck Management Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/schedule_report');?>">
                                                   Truck Status Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Vehicle trips reports
                                                </a>    
                                            </h4>
                                           
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Service Reports
                                                </a>    
                                            </h4>
                                        </li>
                                       
                                        
                                    </ul>
                                </div>
                                 <div class="rpt-holder  disabled-content">
                                    <h3>Parts Management Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Parts Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Parts Usage Report
                                                </a>    
                                            </h4>
                                        </li>
                                   </ul>
                                </div>
                                <div class="rpt-holder  disabled-content">
                                    <h3>Speed Limiter Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                   Speed Report
                                                </a>    
                                            </h4>
                                        </li>
                                                                                
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-4 border">
                                <div class="rpt-holder  disabled-content">
                                    <h3>Order Management Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Delivery Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Schedule Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Transactions Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Clients Report
                                                </a>    
                                            </h4>
                                        </li>
                                        
                                        
                                    </ul>
                                </div>
                                <div class="rpt-holder  disabled-content">
                                    <h3>Advanced Reports</h3>
                                    <ul class="reports-mn">
                                       
                                         <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Overall Summary Report
                                                </a>    
                                            </h4>
                                           
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    User Logging reports
                                                </a>    
                                            </h4>
                                           
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Alerts Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Battery Status Report
                                                </a>    
                                            </h4>
                                        </li>
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Data Logs Report
                                                </a>    
                                            </h4>
                                        </li>
                                    </ul>
                                </div>
                                <div class="rpt-holder">
                                    <h3>Other Reports</h3>
                                    <ul class="reports-mn">
                                        <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/schedule_report');?>">
                                                   Personnel Report
                                                </a>    
                                            </h4>
                                        </li>
                                         <li>
                                            <h4>
                                                <a href="<?php echo site_url('reports/fleet_summary_report');?>">
                                                    Dealers Reports
                                                </a>    
                                            </h4>
                                        </li>
                                       
                                        
                                        
                                    </ul>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </li>
                </ul>
                <!--<li class="">
                    <a class="" data-toggle="" href="<?php echo site_url('reports')?>" style="color:#fff">
                        <i class="fa fa-list fa-fw"></i> Reports
                    </a>
                </li> -->   
               <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-exclamation-triangle fa-fw"></i> <span id="badge-alerts" class="badge badge-notification badge-danger animated fadeIn">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts d-timeline p-l-15 p-t-0 p-b-0 animated fadeInUp">
                        <li>
                            <ul class="alerts-holder">
                                
                            </ul>
                        </li>               
                        <li style="height:40px;">
                        <br>
                            <span class="circle"></span>
                            <a href="<?php echo site_url('main/alerts_view'); ?>"><i class="fa fa-fw fa-exclamation-triangle"></i> <strong class="see-all">See All Alerts</strong></a>

                            <i class="fa fa-angle-right"></i>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->

                <!--<li class="dropdown">

                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i> <span class="badge badge-notification badge-info animated fadeIn">3</span>
>>>>>>> 55d1ce1fd4e138b260cbc390575b50d7869024c0
                    </a>
                    <ul class="dropdown-menu dropdown-tasks animated fadeInUp">
                        <li class="dropdown-header text-center">You have 3 Tasks alerts</li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Assign Mechanics to KBS 123J</strong>
                                        <span class="pull-right text-muted">40%</span>
                                    </p>
                                    <div class="progress progress-bar-mini progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                            <span class="sr-only">Critical</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Schedule Trip for Clients</strong>
                                        
                                    </p>
                                    <div class="progress progress-bar-mini progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        
                        <li class="divider"></li>
                        <li class="dropdown-footer">
                            <a class="text-center" href="#">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>

                </li>
                <!-- /.dropdown -->
                
                <!--<li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-exclamation-triangle fa-fw"></i> <span class="badge badge-notification badge-info animated fadeIn">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts d-timeline p-l-15 p-t-0 p-b-0 animated fadeInUp">
                        <li></li>
                        
                        <li class="warning">
                            <span class="circle"></span>
                            <span class="stacked-text">
                                <i class="fa fa-tasks fa-fw"></i> <strong>Tyre Under Pressure</strong> KBS 345J
                                <small class="text-muted help-block">4 minutes ago</small>
                            </span>
                        </li>
                        <li class="success">
                            <span class="circle"></span>
                            <span class="stacked-text">
                                <i class="fa fa-cloud-upload fa-fw"></i> <strong>Tyre High Temperature</strong> Risk.
                                <small class="text-muted help-block">4 minutes ago</small>
                            </span>
                        </li>
                        
                        <li>
                            <span class="circle"></span>
                            <i class="fa fa-fw fa-exclamation-triangle"></i> <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </li>
                    </ul>
                    
                </li>-->
                <!-- /.dropdown -->
                

                <li class="dropdown">
                    <a class="dropdown-toggle user" data-toggle="dropdown" href="#">
                        <?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?>
                        <img src="<?php echo base_url('assets/images/users/35x35/')  .'/'. $this->session->userdata('user_logo');?>" alt="" class="img-responsive img-circle user-img" style="width:35px;height:35px;">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeInUp">
                        <li class="user-information">
                             <div class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object user-profile-image img-circle" src="<?php echo base_url('assets/images/users/65x65/') .'/'. $this->session->userdata('user_logo');?>" style="width:65px;height:65px;">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?></h4>
                                    <hr style="margin:8px auto">

                                    <span class="label label-info">User</span>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <!--<li><a href="#"><i class="fa fa-tasks"></i> Tasks <span class="badge badge-info pull-right">3 new</span></a></li>
                        <li class="divider"></li>-->
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
                        <h3 style="color:#18bc9c;margin:0px;margin-top:10px;font-weight:500;text-align:center"><?php echo $this->session->userdata('company_name');?></h3>
                    </li>
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Quick Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-inverse" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <!-- /input-group -->
                    </li>
                    
                    <?php if (in_array(1, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li >
                            <a href="<?php echo base_url();?>"><i class="fa fa-desktop fa-fw"></i> Dashboard</a>
                        </li>
                    <?php } ?>
                   
                    <?php if (in_array(3, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li>
                            <a href=""><i class="fa fa-automobile fa-fw"></i> Vehicles <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo site_url('vehicles')?>">List Vehicles<span class="label label-info pull-right">64</span></a>
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
                    <?php if (in_array(2, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(5, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href=""><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> TPMS <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?php echo site_url('tpms')?>">TPMS Dashboard</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('tpms/telematics')?>">Status</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } }?>
                    
                    <?php if (in_array(1, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>

                        <?php if (in_array(4, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-map-marker fa-spin fa-fw"></i> GPS Tracking <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking')?>">Dashboard</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/telematics')?>">Status</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/areas')?>">Areas</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/landmarks')?>">LandMarks</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/routes')?>">Routes</a>
                                    </li>
                                    <li >
                                        <a href="<?php echo site_url('gps_tracking/trips')?>">Trips</a>
                                    </li>
                                    
                                    
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php }} ?>
                    <?php if (!isset($allMenus)) { ?>
                    <?php if (in_array(3, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(6, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href=""><i class="fa fa-home fa-fw"></i> Yard/Truck Management  <span class="fa arrow"></span></a>
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
                                <a href="#"><i class="fa fa-circle fa-fw"></i> Tyre Assets Management<span class="fa arrow"></span></a>
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
                                <a href="#"><i class="fa fa-calendar-o fa-fw"></i> Order Management<span class="fa arrow"></span></a>
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
                                <a href="#"><i class="fa fa-train fa-fw"></i> Speed Limiter<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    
                                   
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php } }?>
                    <?php }?>
                    <?php if (in_array(10, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li >
                            <a href="<?php echo site_url('personnel')?>"><i class="fa fa-users fa-fw"></i> Personnel</a>
                        </li>
                    <?php } ?>
                     <?php if (in_array(11, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li >
                            <a href="<?php echo site_url('dealers')?>"><i class="fa fa-file fa-fw"></i> Dealers</a>
                        </li>
                    <?php } ?>
                        <li >
                            <a href="<?php echo site_url('reports')?>"><i class="fa fa-list fa-fw"></i> Reports</a>
                        </li>        
                    
                    

                   

                    
                    
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->

          

            
        </nav>
        <!-- /.navbar-static-side -->

    <div id="page-wrapper" class="fixed-navbar ">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6 col-md-6 ">
                    <h3 class="heading">
                        <i class="fa <?php echo $fa; ?> animated flip"></i> <?php echo $content_title; ?> <span class="sub-heading"><?php echo $content_subtitle; ?></span>
                    </h3>
                </div>
                <div class="col-sm-6 col-md-6 ">
                    <span class="hr-content pull-right">
                    

                    <?php if (isset($content_btn)) { echo $content_btn; } ?></span>    
                </div>
           </div>
        </div>
        <ol class="breadcrumb">
            <li><a href="<?php echo  base_url();?>">HOME</a></li>
            <li><a href="<?php echo  site_url($content_url);?>"><?php echo strtoupper($content_title); ?></a></li>
        </ol>

        <?php $this->load->view($content);?>

    </div>
        <!-- /#page-wrapper -->

       
<!--
                
        <div id="style_switcher" class="switcher_open hidden-print">
            <a class="switcher_toggle"><i class="fa fa-cog fa-spin"></i></a>
            <div class="style_items">
                <h4><i class="fa fa-adjust fa-fw"></i> Style Color</h4>
                <ul class="clearfix colors">
                    <li class="switch-style style_active" data-toggle="tooltip" data-container="body" data-placement="top" title="Default" data-bg-color="#18bc9c" data-link-color="#ffffff" data-border-color="#18bc9c" data-style="default" style="background-color: #18bc9c;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Green" data-bg-color="#5cb85c" data-link-color="#ffffff" data-border-color="#5cb85c" data-style="green" style="background-color: #5cb85c;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Red" data-bg-color="#e96363" data-link-color="#ffffff" data-border-color="#e96363" data-style="red" style="background-color: #e96363;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Blue" data-bg-color="#23bab5" data-link-color="#ffffff" data-border-color="#23bab5" data-style="blue" style="background-color: #23bab5;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Orange" data-bg-color="#e97436" data-link-color="#ffffff" data-border-color="#e97436" data-style="orange" style="background-color: #e97436;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Alice" data-bg-color="#E4F1FE" data-link-color="#444" da data-border-color="#cccccc" data-style="alice" style="background-color: #E4F1FE;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Honey Flower" data-bg-color="#674172" data-link-color="#ffffff" data-border-color="#674172" data-style="honey_flower" style="background-color: #674172;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Razzmatazz" data-bg-color="#DB0A5B" data-link-color="#ffffff" data-border-color="#DB0A5B" data-style="razzmatazz" style="background-color: #DB0A5B;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="White" data-bg-color="#ffffff" data-link-color="#444" da data-border-color="#ccc" data-style="white" style="background-color: #ffffff;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Snuff" data-bg-color="#DCC6E0" data-link-color="#ffffff" data-border-color="#DCC6E0" data-style="snuff" style="background-color: #DCC6E0;"></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="Ming" data-bg-color="#336E7B" data-link-color="#ffffff" data-border-color="#336E7B" data-style="ming" style="background-color: #336E7B;"></li>

                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Green" data-bg-color="#fff" data-sidebar-color="#5cb85c" data-link-color="#444" data-border-color="#5cb85c" data-style="half_green"><span class="half_style" style="background-color: #5cb85c;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Red" data-bg-color="#fff" data-sidebar-color="#e96363" data-link-color="#444" data-border-color="#e96363" data-style="half_red"><span class="half_style" style="background-color: #e96363;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Blue" data-bg-color="#fff" data-sidebar-color="#23bab5" data-link-color="#444" data-border-color="#23bab5" data-style="half_blue"><span class="half_style" style="background-color: #23bab5;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Orange" data-bg-color="#fff" data-sidebar-color="#e97436" data-link-color="#444" data-border-color="#e97436" data-style="half_orange"><span class="half_style" style="background-color: #e97436;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Alice" data-bg-color="#fff" data-sidebar-color="#E4F1FE" data-link-color="#444" da data-border-color="#cccccc" data-style="half_alice"><span class="half_style" style="background-color: #E4F1FE;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Honey Flower" data-bg-color="#fff" data-sidebar-color="#674172" data-link-color="#444" data-border-color="#674172" data-style="half_honey_flower"><span class="half_style" style="background-color: #674172;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Razzmatazz" data-bg-color="#fff" data-sidebar-color="#DB0A5B" data-link-color="#444" data-border-color="#DB0A5B" data-style="half_razzmatazz"><span class="half_style" style="background-color: #DB0A5B;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Snuff" data-bg-color="#fff" data-sidebar-color="#DCC6E0" data-link-color="#444" data-border-color="#DCC6E0" data-style="half_snuff"><span class="half_style" style="background-color: #DCC6E0;"></span></li>
                    <li class="switch-style" data-toggle="tooltip" data-container="body" data-placement="top" title="(HALF) Ming" data-bg-color="#fff" data-sidebar-color="#336E7B" data-link-color="#444" data-border-color="#336E7B" data-style="half_ming"><span class="half_style" style="background-color: #336E7B;"></span></li>
                </ul>
                <h4><i class="fa fa-cogs fa-fw"></i> Layout Settings</h4>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>Fixed Navbar</span>
                        <div class="checkbox checkbox-success checkbox-inline pull-right"><input type="checkbox" class="layout_switch" id="fixed_navbar" checked="checked"><label for="fixed_navbar"></label></div>
                    </li>
                    <li class="list-group-item">
                        <span>Sidebar Fixed Sidebar</span>
                        <div class="checkbox checkbox-success checkbox-inline pull-right"><input type="checkbox" class="layout_switch" id="fixed_sidebar" checked="checked"><label for="fixed_sidebar"></label></div>
                    </li>
                    <li class="list-group-item">
                        <span>Fixed Footer</span>
                        <div class="checkbox checkbox-success checkbox-inline pull-right"><input type="checkbox" class="layout_switch" id="fixed_footer"><label for="fixed_footer"></label></div>
                    </li>
                </ul>
            </div>
        </div>
        -->
        
                <!-- footer -->
        <footer >
            <p>&copy; <?php echo date('Y')?>, ITMS Africa</p>
        </footer>
                
    </div>
    <!-- /#wrapper -->
    <!-- Core Scripts - Include with every page -->
    
    <div class="page-alert">
        
    </div>


    <!-- Button that triggers the popup -->
        
        <!-- Element to pop up -->
        <div id="element_to_pop_up" class="alert-popup">Content of popup</div> 

    <script src="<?php echo base_url('assets/js/plugins/jquery-cookie/jquery.cookie.js')?>"></script>

    <script type="text/javascript">
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
    </script>

    <!- - jQuery easing | Script -->
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

    
    

    <script src="<?php echo base_url('assets/js/plugins/nicescroll/jquery.nicescroll.min.js')?>"></script> 
    <!-- Init Scripts - Include with every page -->
    <script src="<?php echo base_url('assets/js/init.js')?>"></script>

    <script src="<?php echo base_url('assets/sweetalert2.min.js') ?>"></script>      
    <script src="<?php echo base_url('assets/js/jquery.bpopup.min.js') ?>"></script>
    

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

                             if (res.data[alert].alert_header == "Overspeeding") {
                                $line = "danger";
                             } else {
                                $line = "warning";
                             }
                                $('.alerts-holder').append('<li class="'+$line+'">' +
                                                '<span class="circle"></span><span class="stacked-text">' + 
                                                '<i class="fa fa-tasks fa-fw"></i>' +
                                                '<strong>'+ res.data[alert].alert_header+'</strong><br>' +
                                                res.data[alert].assets_friendly_nm +'('+res.data[alert].assets_name+')' + 
                                                '<small class="text-muted help-block">'+res.data[alert].add_date+'</small></span>');
                            
                                count++;
                            };


                            if (v > 0) {
                               str = str.concat('</table></div>');
                               swal({title:'',   html: str,   type:'info',   confirmButtonText: "Close" });
                            }


                            //swal({title: '',   html:'You can use <b>bold text</b>, ' +     '<a href="//github.com">links</a> ' +     'and other HTML tags', confirmButtonText: 'Close', });
                        } else {
                            $('#badge-alerts').hide();
                             $('.alerts-holder').html();
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
       
        });
    </script       
</body>

</html>