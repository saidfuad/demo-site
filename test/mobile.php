<?php session_start(); ?>
<?php include("db.php"); ?>
<?php 

$host = $_SERVER['HTTP_REFERER'];
preg_match("/^(http:\/\/)?([^\/]+)/i", $host, $matches); 
$host = $matches[2];
$_SESSION['host'] = $host;

?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
    <?php if($host == 'omexsol.com') { ?>
	<title>Omex Vehical Tracking System</title>
    <?php } else { ?>
	<title>NKonnect Vehical Tracking System</title>
    <?php } ?>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="mobile/stylesheets/base.css">
	<link rel="stylesheet" href="mobile/stylesheets/skeleton.css">
	<link rel="stylesheet" href="mobile/stylesheets/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="assets/images/nk.ico">
</head>
<body>



	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	<div class="container">
		<div class="sixteen columns">
			
			<?php if($host == 'omexsol.com') { ?>
			<h1 class="remove-bottom" style="margin-top: 35px">Omex</h1>
			<?php }else{ ?>
			<h1 class="remove-bottom" style="margin-top: 35px">NKonnect</h1>
			<?php } ?>
			<hr />
		</div>
		<div class="eight columns">
			<a href="mobile/" class="full-width button" style='height:30px;font-size:16pt'>Basic Version</a>
		</div>
		<div class="eight columns">
			<a href="mo/" class="full-width button" style='height:30px;font-size:16pt'>Advanced Version</a>
		</div>
		
		
		<div class="sixteen columns">
			<hr />
			<?php if($host != 'omexsol.com') { ?>
				<p>&copy; 2012 NKonnect.com</p>
			<?php } ?>
		</div>

	</div>
</body>
</html>
		