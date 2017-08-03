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
function print_gzipped_page() {

    global $HTTP_ACCEPT_ENCODING;
    if( headers_sent() ){
        $encoding = false;
    }elseif( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false ){
        $encoding = 'x-gzip';
    }elseif( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false ){
        $encoding = 'gzip';
    }else{
        $encoding = false;
    }

    if( $encoding ){
        $contents = ob_get_contents();
        ob_end_clean();
        header('Content-Encoding: '.$encoding);
        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $size = strlen($contents);
        $contents = gzcompress($contents, 9);
        $contents = substr($contents, 0, $size);
        print($contents);
        exit();
    }else{
        ob_end_flush();
        exit();
    }
}
ob_start();
//ob_start('gz_handler'); for one line zip support
ob_implicit_flush(0); 
 include("php/lang.php");
 
 
 
 ?>
<!DOCTYPE html> 
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if($host == 'omexsol.com') { ?>
	<title>Omex - VTS</title> 
	<?php } else if($host == 'vts.trackeron.com') {?>
	<title>Trackeron - VTS</title>
	<?php } else if($host == 'vehicle.worldwidetrackingservices.com') {?>
	<title>Worldwide Tracking Services - VTS</title>
	<?php } else { ?>
	<title>NKonnect - VTS</title>
	<?php } ?>
	<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.min.css" />
	<script src="js/jquery-1.8.1.min.js"></script>
	<script src="js/jquery.mobile-1.1.1.min.js"></script>
	<link rel="stylesheet" href="css/jqm-docs.css"/>
</head> 
<body> 
	
	<div data-role="page" data-theme="d">

		<div data-role="header" data-theme="b">
			<?php if($host == 'omexsol.com') { ?>
			<h1 style='overflow:visible;'>Omex</h1>
			<?php } else if($host == 'vts.trackeron.com') { ?>
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