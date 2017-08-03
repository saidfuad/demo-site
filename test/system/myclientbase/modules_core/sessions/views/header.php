<!DOCTYPE html>
<html lang="en">
<head>
<title>Gatti-Vehicle Tracking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--meta http-equiv="X-UA-Compatible" content="IE=edge" /-->
<meta name="keywords" content="gatti-vehicle-tracking-system">

<meta name="description" content="Gatti - is a product of NKonnect Infoway an IT company specialized in GPS vehicle tracking system in India.">
<meta name="author" content="">
<link href="<?php echo $site_url; ?>css/base.css" rel="stylesheet">
<!-- Le styles -->
<link href="<?php echo $site_url; ?>css/bootstrap.css" rel="stylesheet">
<!-- REVOLUTION SLIDER STYLE SETTINGS-->
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>rs-plugin/css/settings.css" media="screen" />
<link href="<?php echo $site_url; ?>css/captions.css" rel="stylesheet" type="text/css">
<!-- get jQuery from the google apis -->
<script type="text/javascript" src="<?php echo $site_url; ?>js/jquery.min.js"></script>
<!-- THE FANYCYBOX ASSETS -->
<link rel="stylesheet" href="<?php echo $site_url; ?>megafolio/fancybox/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
<!-- MEGAFOLIO STYLE SETTINGS-->
<link href="<?php echo $site_url; ?>megafolio/css/settings.css" rel="stylesheet" type="text/css">
<!-- Modifications of StyleSheet -->
<link href="<?php echo $site_url; ?>css/style.css" rel="stylesheet" type="text/css">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="<?php echo $site_url; ?>images/tiles/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $site_url; ?>assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="a<?php echo $site_url; ?>ssets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $site_url; ?>assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $site_url; ?>assets/ico/apple-touch-icon-57-precomposed.png">


</head>
<body>
<!-- THE RESPONSIVE MENU ON THE TOP -->
<div class="responsive_wrapper">
<div id="responsive-menu">
<div class="resp-closer"></div>
<div class="resp-menuheader">Gatti-VTS</div>
<ul>
</ul>
</div>
</div>
<!-- HEADER -->
<div class="header">
<div class="container header-container">
<a href="<?php echo site_url(); ?>"><div class="logo"></div></a>
<!--
#########################################
- MENU NAVIGATION -
#########################################
-->
<div id="navholder">
<div id="nav" class="hidden-phone green">
	<ul>
		<?php if(!isset($page)){ $page = ""; } ?>
		<li><a href="<?php echo site_url("home"); ?>"  <?php if($page =="home"){ ?> class='activepage' <?php } ?>><div class="nav-mainline">Home</div></a><div class="clear"></div></li>
		<li><a href="<?php echo site_url("gatti_feature"); ?>"  <?php if($page =="gatti_feature"){ ?> class='activepage' <?php } ?>><div class="nav-mainline">Feature</div></a><div class="clear"></div></li>
		<li><a href="<?php echo site_url("media_center"); ?>" <?php if($page =="media_center"){ ?> class='activepage' <?php } ?>><div class="nav-mainline">Media Center</div></a><div class="clear"></div></li>
		<!--<li><a href="<?php echo site_url("gatti_buy_purchase"); ?>" <?php if($page =="buy"){ ?> class='activepage' <?php } ?> ><div class="nav-mainline">Buy</div></a><div class="clear"></div></li>-->
		<li><a href="<?php echo site_url("blog"); ?>" <?php if($page =="blog"){ ?> class='activepage' <?php } ?>><div class="nav-mainline">Blog</div></a><div class="nav-subline"></div><div class="clear"></div></li>
		<li><a href="http://www.nkonnect.com/technology-solutions/contact/index_1.php"  target='_blank' <?php if($page =="contact"){ ?> class='activepage' <?php } ?>><div class="nav-mainline">Contact</div><div class="nav-subline"></div></a><div class="clear"></div></li>
	</ul>
	<div class="clear"></div></div>
</div>
<div class="resp-navigator reversefadeitem"></div>
<div class="clear"></div></div>
</div><!-- END OF CONTAINER -->