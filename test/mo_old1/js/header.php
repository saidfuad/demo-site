<?php session_start(); ?>
<?php include("../db.php"); 


 include("php/lang.php");
 
 
 
 ?>
<!DOCTYPE html> 
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if($host == 'omexsol.com') { ?>
	<title>Omex - VTS</title> 
	<?php } else if($host == 'vts.trackeron.com' || $host == 'catsite.trackeron.com') {?>
	<title>Trackeron - VTS</title>
	<?php } else if($host == 'vehicle.worldwidetrackingservices.com') {?>
	<title>Worldwide Tracking Services - VTS</title>
	<?php } else { ?>
	<title>NKonnect - VTS</title>
	<?php } ?>
	<script src="js/jquery-1.8.1.min.js"></script>
	<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.min.css" />
	
	<script src="js/jquery.mobile-1.1.1.min.js"></script>
	<link rel="stylesheet" href="css/jqm-docs.css"/>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src='../assets/javascript/infobox_min.js' type='text/javascript'></script>
<script src='../assets/javascript/jQueryRotate.2.2_min.js' type='text/javascript'></script>
<script type='text/javascript' src='../assets/javascript/infobubble-compiled.js'></script><script type='text/javascript' src='../assets/javascript/elabel_min.js'></script>
	<link href="css/mobiscroll-2.0.3.custom.min.css" rel="stylesheet" type="text/css" />
	<script src="js/mobiscroll-2.0.3.custom.min.js" type="text/javascript"></script>
	
	<link rel="stylesheet" type="text/css" href="css/mobipick.css" />
	<script type="text/javascript" src="js/xdate.js"></script>
	<script type="text/javascript" src="js/xdate.i18n.js"></script>

	<script type="text/javascript" src="js/mobipick.js"></script>
		<script type="text/javascript" src="js/keydragzoom_packed.js"></script>
	<script type="text/javascript" src="js/jqplot/jquery.jqplot_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.logAxisRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.canvasTextRenderer.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.barRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.categoryAxisRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.dateAxisRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.pointLabels_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.cursor_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.highlighter_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.canvasAxisLabelRenderer.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.canvasAxisTickRenderer.js"></script>

</head> 
<body> 
	
	<div data-role="page" data-theme="d">

		<div data-role="header" data-theme="f">
			<?php if($host == 'omexsol.com') { ?>
			<h1 style='overflow:visible;'>Omex</h1>
			<?php } else if($host == 'vts.trackeron.com' || $host == 'catsite.trackeron.com') { ?>
			<h1 style='overflow:visible;'>Trackeron</h1>
			<?php } else if($host == 'vehicle.worldwidetrackingservices.com') { ?>
			<h1 style='overflow:visible;'>Worldwide Tracking Services</h1>
			<?php } else { ?>
			<h1 style='overflow:visible;'>NKonnect</h1>
			<?php } ?>
			<a href="./" data-icon="home" data-iconpos="notext" data-direction="reverse"><?php echo $lang['Home']; ?></a>
			<a href="#" data-icon="back" data-iconpos="notext" data-direction="reverse" onclick='window.location="logout.php"'><?php echo $lang['Logout']; ?></a>
		</div><!-- /header -->