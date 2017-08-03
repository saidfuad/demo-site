<?php session_start(); ?>
<?php include("../db.php"); 


if(isset($_SESSION['host'])){
	$host = $_SESSION['host'];
}else{
	$host = $_SERVER['HTTP_HOST'];
	$_SESSION['host'] = $host;
}

$HTTP_ACCEPT_ENCODING = $_SERVER['HTTP_ACCEPT_ENCODING']; 

// Include this function on your pages

 include("php/lang.php");
 
 
 
 ?>
<!DOCTYPE html> 
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if($host == 'omexsol.com') { ?>
	<title>Omex - VTS</title> 
	<?php } else if($host == 'vts.trackeron.com' || $host == 'test.trackeron.com') {?>
	<title>Trackeron - VTS</title>
	<?php } else if($host == 'vehicle.worldwidetrackingservices.com') {?>
	<title>Worldwide Tracking Services - VTS</title>
	<?php } else { ?>
	<title>NKonnect - VTS</title>
	<?php } ?>
	<script src="js/jquery-1.8.1.min.js"></script>
	<link rel="stylesheet"  href="jquery.mobile-1.3.2/jquery.mobile-1.3.2.css" />
	
	<script src="jquery.mobile-1.3.2/jquery.mobile-1.3.2.js"></script>
	<link rel="stylesheet" href="css/jqm-docs.css"/>

	<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

	<script type="text/javascript" charset="utf-8" src="js/ui/jquery.ui.map.js"></script>
	
</head> 
<body> 
	
	<div data-role="page" data-theme="d">

		<div data-role="header" data-theme="b">
			<?php if($host == 'omexsol.com') { ?>
			<h1 style='overflow:visible;'>Omex</h1>
			<?php } else if($host == 'vts.trackeron.com' || $host == 'test.trackeron.com') { ?>
			<h1 style='overflow:visible;'>Trackeron</h1>
			<?php } else if($host == 'vehicle.worldwidetrackingservices.com') { ?>
			<h1 style='overflow:visible;'>Worldwide Tracking Services</h1>
			<?php } else { ?>
			<h1 style='overflow:visible;'>NKonnect</h1>
			<?php } ?>
			<a href="./" data-icon="home" data-iconpos="notext" data-direction="reverse"><?php echo $lang['Home']; ?></a>
			<div style="float: right; margin-right: 40px; margin-top: -32px;"><?php echo $_SESSION["user"];?></div>
			<a href="#" data-icon="back" data-iconpos="notext" data-direction="reverse" onclick='window.location="logout.php"'><?php echo $lang['Logout']; ?></a>
		</div><!-- /header -->