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

    <title>ITMS Africa</title>

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
    <link href="<?php echo base_url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/css/styles/default.css')?>" type="text/css" rel="stylesheet" id="style_color" />
    
	<!-- Style LESS -->
    <link href="<?php echo base_url('assets/less/animate.less?1436965415')?>" rel="stylesheet/less" />
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/logo1.png">
        
    
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
            <ul class="nav navbar-top-links navbar-left mega-menu hidden-xs hidden-sm">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-list-ul fa-fw"></i> Summary <span class="badge badge-danger animated pulse">new</span>
                    </a>
                    <div class="dropdown-menu mega-menu animated flipInY">
                        <div class="row">
                            <div class="col-sm-4 border right">
                                <h3><i class="fa fa-list-ol fa fw"></i> Tasks</h3>
                                <div>
                                    <p>
                                        <strong>Assign Driver</strong>
                                        <span class="pull-right text-muted">40%</span>
                                    </p>
                                    <div class="progress progress-bar-mini progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% (success)</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p>
                                        <strong>Moving to new Headquarters</strong>
                                        <span class="pull-right text-muted">20%</span>
                                    </p>
                                    <div class="progress progress-bar-mini progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="sr-only">20%</span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-sm-4 border right">
                                <h3><i class="fa fa-list-alt fa fw"></i> Notifications</h3>
                                <div class="has-nice-scroll" style="overflow-y: hidden; outline: none; height: 263px; z-index:3;">
                                    <h4>Aliquam</h4>
                                    <p>Aliquam erat volutpat. Nulla nec justo dui. Aeneanoi atet accumsan egestas tortor at lacinia. Pellentesque netus habitant morbi tristique senectus et netus etor egestasio malesuada famesac turpis egestas. </p>
                                    
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <h3><i class="fa fa-envelope fa fw"></i> New Bookings</h3>
                                
                                <div>
                                    <img src="<?php echo base_url('assets/images/people/25x25/1.jpg')?>" alt="" data-src="<?php echo base_url('assets/images/people/25x25/1.jpg')?>" data-src-retina="../assets/images/people/x2/1x2.jpg')?>" class="img-responsive img-circle">
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em class="label label-info">Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                        
                                <div>
                                    <img src="<?php echo base_url('assets/images/people/25x25/1.jpg')?>" alt="" data-src="<?php echo base_url('assets/images/people/25x25/1.jpg')?>" data-src-retina="../assets/images/people/x2/1x2.jpg')?>" class="img-responsive img-circle">
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em class="label label-info">Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                        
                               
                               
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- // MEGA MENU -->
            <div class="col-md-6"><h3 style="margin:0px;margin-top:10px;font-weight:500"><?php echo $this->session->userdata('company_name');?></h3></div>

            <ul class="nav navbar-top-links navbar-right hidden-xs">
               <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i> <span class="badge badge-notification badge-danger animated fadeIn">3</span>
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
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                
                <li class="dropdown">
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
                    <!-- /.dropdown-alerts -->
                </li>
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
                        <li><a href="#"><i class="fa fa-tasks"></i> Tasks <span class="badge badge-info pull-right">3 new</span></a></li>
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
                        <div class="user-img">
                            <img src="<?php echo base_url('assets/images/users/65x65/') .'/'. $this->session->userdata('user_logo'); ?>" alt="" data-src="<?php echo base_url('assets/images/people/65x65/1.jpg')?>" data-src-retina="../assets/images/people/x2/1x2.jpg')?>" width="65" height="65" class="img-responsive img-circle animated bounceIn">
                        </div>
                        <div class="user-info">
                            <div class="user-greet">Welcome</div>
                            <div class="user-name"><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?></div>
                            <div class="user-status animated bounceInLeft">
                                <span class="label label-success dropdown-toggle">Online</span>
                            </div>
                        </div>
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
                            <a href="index.html"><i class="fa fa-desktop fa-fw"></i> Dashboard</a>
                        </li>
                    <?php } ?>
                   
                    <?php if (in_array(3, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li>
                            <a href=""><i class="fa fa-automobile fa-fw"></i> Vehicles <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="fleet-dashboard.html">View Vehicles <span class="label label-info pull-right">64</span></a>
                                </li>
                                <li >
                                    <a href="fleet-view-vehicle.html">Categories</a>
                                </li>
    							<li >
                                    <a href="fleet-view-vehicle.html">Types</a>
                                </li>
    							<li >
                                    <a href="fleet-view-vehicle.html">Owners</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php } ?>
                    
                    <?php if (in_array(1, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>

                        <?php if (in_array(4, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-map-marker fa-spin fa-fw"></i> GPS Tracking <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="panels-wells.html">Dashboard<span class="label label-info pull-right">+10</span></a>
                                    </li>
        							<li >
                                        <a href="panels-wells.html">Telematics<span class="label label-info pull-right">+10</span></a>
                                    </li>
        							<li >
                                        <a href="panels-wells.html">Create LandMarks<span class="label label-info pull-right">+10</span></a>
                                    </li>
                                    <li >
                                        <a href="buttons.html">Create Zones</a>
                                    </li>
                                    <li >
                                        <a href="animations.html">Create Routes<span class="label label-info pull-right">NEW</span></a>
                                    </li>
                                    
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                    <?php }} ?>
                    
                    <?php if (in_array(2, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(5, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href=""><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> TPMS Management  <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="fleet-dashboard.html">Dashboard</a>
                                    </li>
                                    <li >
                                        <a href="fleet-view-vehicle.html">View Vehicles</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
    				<?php } }?>
                    
                    <?php if (in_array(3, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(6, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href=""><i class="fa fa-home fa-fw"></i> Yard Management  <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="fleet-dashboard.html">RFID</a>
                                    </li>
                                    <li >
                                        <a href="fleet-view-vehicle.html">Tasks</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
					<?php } }?>
                    
                    <?php if (in_array(4, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(7, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-calendar-o fa-fw"></i> Parts Management<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="invoice.html">View Parts</a>
                                    </li>
                                    <li >
                                        <a href="calendar.html">Add New</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
					<?php } } ?>

                    <?php if (in_array(10, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li >
                            <a href=""><i class="fa fa-users fa-fw"></i> Personnel <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="fleet-dashboard.html">View Personnel</a>
                                </li>
                                <li >
                                    <a href="fleet-view-vehicle.html">Roles</a>
                                </li>
                                <li >
                                    <a href="fleet-view-vehicle.html">Permissions</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php } ?>
                    
                    <?php if (in_array(5, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(8, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-calendar-o fa-fw"></i> Schedule Management<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
        							<li >
                                        <a href="invoice.html">Vehicle Schedule</a>
                                    </li>
        							<li >
                                        <a href="finances.html">Personnel Tasks</a>
                                    </li>
                                    <li >
                                        <a href="calendar.html">View Calender</a>
                                    </li>
        						</ul>
                                <!-- /.nav-second-level -->
                            </li>
                        <?php } } ?>
                    
                    <?php if (in_array(6, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
                        <?php if (in_array(9, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                            <li >
                                <a href="#"><i class="fa fa-calendar-o fa-fw"></i> Financial<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    <li >
                                        <a href="invoice.html">Invoices</a>
                                    </li>
                                    <li >
                                        <a href="calendar.html">Expenditure</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
					<?php } }?>

                     <?php if (in_array(2, explode(',',$this->session->userdata('itms_menu_permissions')))) {?>
                        <li >
                            <a href="#"><i class="fa fa-cog fa-spin fa-fw"></i> Settings <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level ">
                                <li >
                                    <a href="panels-wells.html">LandMarks</a>
                                </li>
                                <li >
                                    <a href="buttons.html">Zones</a>
                                </li>
                                <li >
                                    <a href="animations.html">Routes <span class="label label-info pull-right">4</span></a>
                                </li>
                                 <li >
                                    <a href="animations.html">Devices <span class="label label-info pull-right">4</span></a>
                                </li>
                                
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php } ?>
                    
                    
                    
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->

          

            
        </nav>
        <!-- /.navbar-static-side -->

        
        <div id="page-wrapper" class="fixed-navbar ">
<div class="page-header">
    <h3 class="heading"><i class="fa fa-car animated flip"></i> Fleet Management <span class="sub-heading">Fleet Management demonstration</span></h3>
</div>
<!-- /.col-lg-12 -->

<div class="container-fluid">
    <div class="row fleet-content">
        <div class="col-lg-6">
            <div class="row">
                <!-- Service Reminders -->
                <div class="col-md-6">
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h3 class="panel-title">Service Reminders</h3>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row">
                                <div class="col-sm-6 text-center">
                                    <h1 class="success">3</h1>
                                    <span class="caption">Open</span>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <h1 class="warning">1</h1>
                                    <span class="caption">Overdue</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Renewal Reminders -->
                <div class="col-md-6">
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h3 class="panel-title">Renewel Reminders</h3>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row">
                                <div class="col-sm-6 text-center">
                                    <h1 class="success">4</h1>
                                    <span class="caption">Open</span>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <h1 class="success">0</h1>
                                    <span class="caption">Overdue</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Service Costs
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="service-costs"></div>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Fuel Costs
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="fuel-costs"></div>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Latest Meter Readings
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="latest-meter-readings"></div>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading padding padding-all">
                    Vehicles
                </div>
                <!-- /.panel-heading -->

                <div class="panel-body">
                    <table class="table table-hover table-striped">
                      <tbody>
                        <tr>
                          <td width="10%"><img src="<?php echo base_url('assets/images/photos/car1.jpg')?>" class="img-circle img-vehicle" /></td>
                          <td>
                            Vehicle number 1<br />
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View stats of this vehicle" title="View stats of this vehicle"><i class="fa fa-area-chart"></i>
                            </a>&nbsp;|&nbsp;
                            <i class="fa fa-dashboard"></i>
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit odometer" title="Edit odometer">153,745</a> km
                            </td>
                          <td class="text-right">
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View this vehicle" title="View this vehicle"><i class="fa fa-eye"></i>
                            </a>&nbsp;
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit this vehicle" title="Edit this vehicle"><i class="fa fa-pencil"></i>
                            </a>&nbsp;
                            <a class="text-muted delete-comment" data-toggle="tooltip" data-container="body" data-placement="top" title="Are you sure?" href="#" rel="nofollow" title="Delete this comment"><i class="fa fa-trash-o"></i>
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td><img src="<?php echo base_url('assets/images/photos/car2.jpg')?>" class="img-circle img-vehicle" /></td>
                          <td>
                            Vehicle number 2<br />
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View stats of this vehicle" title="View stats of this vehicle"><i class="fa fa-area-chart"></i>
                            </a>&nbsp;|&nbsp;
                            <i class="fa fa-dashboard"></i>
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit odometer" title="Edit odometer">53,674</a> km
                            </td>
                          <td class="text-right">
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View this vehicle" title="View this vehicle"><i class="fa fa-eye"></i>
                            </a>&nbsp;
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit this vehicle" title="Edit this vehicle"><i class="fa fa-pencil"></i>
                            </a>&nbsp;
                            <a class="text-muted delete-comment" data-toggle="tooltip" data-container="body" data-placement="top" title="Are you sure?" href="#" rel="nofollow" title="Delete this comment"><i class="fa fa-trash-o"></i>
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td><img src="<?php echo base_url('assets/images/photos/car3.jpg')?>" class="img-circle img-vehicle" /></td>
                          <td>
                            Vehicle number 3<br />
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View stats of this vehicle" title="View stats of this vehicle"><i class="fa fa-area-chart"></i>
                            </a>&nbsp;|&nbsp;
                            <i class="fa fa-dashboard"></i>
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit odometer" title="Edit odometer">102,745</a> km
                            </td>
                          <td class="text-right">
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View this vehicle" title="View this vehicle"><i class="fa fa-eye"></i>
                            </a>&nbsp;
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit this vehicle" title="Edit this vehicle"><i class="fa fa-pencil"></i>
                            </a>&nbsp;
                            <a class="text-muted delete-comment" data-toggle="tooltip" data-container="body" data-placement="top" title="Are you sure?" href="#" rel="nofollow" title="Delete this comment"><i class="fa fa-trash-o"></i>
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td><img src="<?php echo base_url('assets/images/photos/car4.jpg')?>" class="img-circle img-vehicle" /></td>
                          <td>
                            Vehicle number 4<br />
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View stats of this vehicle" title="View stats of this vehicle"><i class="fa fa-area-chart"></i>
                            </a>&nbsp;|&nbsp;
                            <i class="fa fa-dashboard"></i>
                            <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit odometer" title="Edit odometer">178,745</a> km
                            </td>
                          <td class="text-right">
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View this vehicle" title="View this vehicle"><i class="fa fa-eye"></i>
                            </a>&nbsp;
                            <a class="text-muted edit-comment" href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit this vehicle" title="Edit this vehicle"><i class="fa fa-pencil"></i>
                            </a>&nbsp;
                            <a class="text-muted delete-comment" data-toggle="tooltip" data-container="body" data-placement="top" title="Are you sure?" href="#" rel="nofollow" title="Delete this comment"><i class="fa fa-trash-o"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>                        
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->



            <div class="panel panel-info">
                <div class="panel-heading">
                    Latest Updates
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <ul class="list-unstyled" id="fleet-updates">
                        <li>
                            <span class="label label-success">New</span>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.  <a href="">Issues feature</a>.
                            <div class="date">Jun 27</div>
                        </li>
                        <li>
                            <span class="label label-warning">Update</span>
                            Cras maximus arcu dui, nec rutrum lacus placerat a.Nunc eget justo nec neque congue feugiat.
                            <a href="">neque congue feugiat.</a>.
                            <div class="date">May 22</div>
                        </li>
                        <li>
                            <span class="label label-warning">Update</span>
                            Integer non orci velit. Donec sit amet leo et orci accumsan
                            <div class="date">May 19</div>
                        </li>
                        <li>
                            <span class="label label-warning">Update</span>
                            In vulputate, ligula quis euismod cursus, risus ante ultricies tortor, at suscipit magna sem eu neque.
                            <div class="date">Apr 4</div>
                        </li>
                        <li>
                            <span class="label label-warning">Update</span>
                             Mauris nulla urna, rhoncus non lorem vitae, <a href="">ultrices malesuada sem. </a>.
                            <div class="date">Apr 2</div>
                        </li>
                    </ul>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.row --> 
</div>        </div>
        <!-- /#page-wrapper -->

        <!-- Side Bar Chat & Mail & History & Settings -->
        <div class="sidebar-right">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#chat" data-toggle="tab"><i class="fa fa-comments"></i></a></li>
                <li><a href="#inbox" data-toggle="tab"><i class="fa fa-envelope"></i></a></li>
                <li><a href="#history" data-toggle="tab"><i class="fa fa-eye"></i></a></li>
            </ul>

            <div class="sidebar-right-search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Quick Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                    </span>
                </div><!-- /input-group -->
            </div>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Chat Content -->
                <div class="tab-pane fade in active inner-all" id="chat">
                    <h5 class="sidebar-title">online friends <span class="label label-success pull-right">5</span></h5>
                    <ul class="chat-list">
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="success" alt="" src="<?php echo base_url('assets/images/people/50x50/1.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">John Smith</span>
                                        <span class="message">Hey, How it is going?...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="success" alt="" src="<?php echo base_url('assets/images/people/50x50/2.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Stephanie Carr</span>
                                        <span class="message">Haha that's was so funny...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="warning" alt="" src="<?php echo base_url('assets/images/people/50x50/3.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Rebecca Harris</span>
                                        <span class="message">When do you have a...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="warning" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Kimberly Stanley</span>
                                        <span class="message">I miss you honey so much...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="danger" alt="" src="<?php echo base_url('assets/images/people/50x50/3.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Nicholas Gibson</span>
                                        <span class="message">Are you available tomorrow...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="success" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Steven Taylor</span>
                                        <span class="message">Hi there!...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="warning" alt="" src="<?php echo base_url('assets/images/people/50x50/5.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Jack Rivera</span>
                                        <span class="message">Tomorrow i need that file...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                            </ul>
                    <h5 class="sidebar-title">offline friends</h5>
                    <ul class="chat-list">
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/1.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">John Smith</span>
                                        <span class="message">Hey, How it is going?...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/2.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Stephanie Carr</span>
                                        <span class="message">Haha that's was so funny...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/3.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Rebecca Harris</span>
                                        <span class="message">When do you have a...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Kimberly Stanley</span>
                                        <span class="message">I miss you honey so much...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/3.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Nicholas Gibson</span>
                                        <span class="message">Are you available tomorrow...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Steven Taylor</span>
                                        <span class="message">Hi there!...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/5.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Jack Rivera</span>
                                        <span class="message">Tomorrow i need that file...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/2.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Steven Taylor</span>
                                        <span class="message">I miss you honey so much...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">John Smith</span>
                                        <span class="message">Are you available tomorrow...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- End of Chat Content -->
                <div class="tab-pane fade inner-all" id="inbox">
                    <h5 class="sidebar-title">inbox <span class="label label-success pull-right">5</span></h5>
                    <ul class="inbox-list">
                        <li>
                            <div class="media">
                                <span class="label label-success pull-left">14/07</span>
                                <div class="media-body">
                                    <strong>Eileen Sideways</strong>
                                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <div class="pull-left text-center">
                                    <span class="label label-danger">14/07</span>
                                    <i class="fa fa-paperclip" data-container="body" data-toggle="tooltip" data-placement="left" title="Attachment to this email"></i>
                                </div>
                                <div class="media-body">
                                    <strong>Eileen Sideways</strong>
                                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <span class="label label-success pull-left">13/07</span>
                                <div class="media-body">
                                    <strong>Eileen Sideways</strong>
                                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </small>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="media">
                                <div class="pull-left text-center">
                                    <span class="label label-danger">14/07</span>
                                    <i class="fa fa-paperclip" data-container="body" data-toggle="tooltip" data-placement="left" title="Attachment to this email"></i>
                                </div>
                                <div class="media-body">
                                    <strong>Eileen Sideways</strong>
                                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <span class="label label-success pull-left">13/07</span>
                                <div class="media-body">
                                    <strong>Eileen Sideways</strong>
                                    <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade inner-all" id="history">
                    <h5 class="sidebar-title">Latest conversations <span class="label label-success pull-right">5</span></h5>
                    <ul class="chat-list">
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="success" alt="" src="<?php echo base_url('assets/images/people/50x50/1.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">John Smith</span>
                                        <span class="message">Hey, How it is going?...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="success" alt="" src="<?php echo base_url('assets/images/people/50x50/2.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Stephanie Carr</span>
                                        <span class="message">Haha that's was so funny...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="warning" alt="" src="<?php echo base_url('assets/images/people/50x50/3.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Rebecca Harris</span>
                                        <span class="message">When do you have a...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="warning" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Kimberly Stanley</span>
                                        <span class="message">I miss you honey so much...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="danger" alt="" src="<?php echo base_url('assets/images/people/50x50/3.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Nicholas Gibson</span>
                                        <span class="message">Are you available tomorrow...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="success" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Steven Taylor</span>
                                        <span class="message">Hi there!...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="warning" alt="" src="<?php echo base_url('assets/images/people/50x50/5.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Jack Rivera</span>
                                        <span class="message">Tomorrow i need that file...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                                                <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/2.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Rebecca Harris</span>
                                        <span class="message">When do you have a...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="media">
                                    <div class="media-left">
                                        <img class="primary" alt="" src="<?php echo base_url('assets/images/people/50x50/4.jpg')?>" class="media-object">
                                    </div>
                                    <div class="media-body">
                                        <span class="time">11:42</span>
                                        <span class="name">Steven Taylor</span>
                                        <span class="message">Are you available tomorrow...</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End of Sidebar Right -->
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
    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>

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
    
	<script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.tooltip.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.time.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.resize.js')?>"></script>
    <script src="<?php echo base_url('assets/js/demo/fleet_dashboard.js')?>"></script>
    

    <script src="<?php echo base_url('assets/js/plugins/nicescroll/jquery.nicescroll.min.js')?>"></script> 
	<!-- Init Scripts - Include with every page -->
    <script src="<?php echo base_url('assets/js/init.js')?>"></script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-48137309-1', 'defthemes.com');
      ga('send', 'pageview');

    </script>
    </body>

</html>