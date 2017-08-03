<?php
require_once "config.php";
/*
session_start();
define ("INCLUDE_PATH", "./");
require_once INCLUDE_PATH."lib/inc.php";
require_once INCLUDE_PATH."lib/common.php";
require_once INCLUDE_PATH."lib/FileIcon.inc.php";
$script_name = basename($_SERVER['SCRIPT_NAME']);
$today = date("jS F Y, h.i A");*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<meta charset="utf-8">
 <meta charset="utf-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta content="width=device-width, initial-scale=1, minimum-scale=1" name="viewport">

<meta name="keywords" content="control nxt,DMS,Distributor management system, edu nxt, gsm nxt, internet of things, iot, monitor nxt,nkonnect, nxt pbx, setu, switch nxt, vts, vehicle tracking system">

<meta name="description" content="NKonnect is establishing itself as pioneer in several industry solutions including fleet and asset tracking, mobile & cloud, embedded mobile & Internet of things. NKonnect is research based Innovative, next generation technology product developing company. From cloud to consumer electronics, we are working in computer, mobile, embedded & cloud based solution development. NKonnect.com is the core platform of Internet of things (IoT), is the result of vigorous research and development efforts by Indian researchers."/>
  <title>NKonnect Sensing Future</title>
<script type="text/javascript" src="javascripts/jquery-1.7.1.js"></script> 
<script type="text/javascript" src="javascripts/tabs.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<!--<script type="text/javascript" src="jcart/js/jcart.min.js"></script>-->
<link rel="shortcut icon" href="images/favicon.ico">
<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
<!--<link rel="stylesheet" type="text/css" media="screen, projection" href="jcart/css/jcart.css" />-->
<link rel="stylesheet" href="stylesheets/base.css" type="text/css">
<link rel="stylesheet" href="stylesheets/skeleton.css" type="text/css">
<link rel="stylesheet" href="stylesheets/layout.css" type="text/css">
<link rel="stylesheet" href="stylesheets/style.css" type="text/css">
<link rel="stylesheet" href="stylesheets/header.css" type="text/css">
<link rel="stylesheet" href="stylesheets/jquery.fancybox-1.3.0.css">
<script type="text/javascript">
$('document').ready(function(){
	$('#share_icon').click(function(){
		$('#share_icon_list').toggle();	
	});
	$('#sl_one , #loader ').click(function(){
		$('#share_icon_list').hide();	
	});
	$("#bundle").click(function(event){
		$('#login-panel').slideUp();
	});
	$("#btn-login").click(function(event){
		event.stopPropagation();
		$('#login-panel').toggle();
	});
	$("#btn-login-cancel").click(function(event){
		$('#login-panel').hide();
	});
	
});
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34256255-1']);
  _gaq.push(['_setDomainName', 'nkonnect.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script> 
</head>
<body>
<div id="msg" class="sixteen columns clearfix" style="display:none;color:#CC9966;font-size:1.5 em;text-align:center;border:solid 1px #CC9966;margin:2px" > 
    <?php echo $this->lang->line("For enhansing your viewing experience please view this site in browser other than IE.");
	?>
	<div>
		<a href="http://www.mozilla.org/en-US/products/download.html?product=firefox-12.0&os=win&lang=en-US">
			<img src="images/firefox.jpg" alt="firefox" width="25" /></a>
		<a href="https://www.google.com/chrome/thankyou.html?"><img src="images/chrome.jpg" alt="chrome" width="25" /></a>
		<a href="https://swdlp.apple.com/cgi-bin/WebObjects/SoftwareDownloadApp.woa/wo/AFlCMkwRCCyjUtQDRjKse0/2.5">
			<img src="images/Safari.jpg" alt="safari" width="25" /></a>
		<a href="http://www.opera.com/download/get.pl?id=34497&thanks=true&sub=true">
		<img src="images/opera.jpg" alt="opera" width="25" /></a>
	</div>
</div>

<?php include("header.php"); ?>
<style>
		*+html #tool_tip{
			margin-top:0px !important;
			margin-left:0px !important;
		}
		* html #tool_tip{
			margin-top:0px !important;
			margin-left:0px !important;
		}
</style>
<div class="container landscape" id="bundle">
  <div class="sixteen columns" style='padding: 11% 0;height:0px;width:100%;'>
   	<div class="sixteen columns" id="sl_one" style='width:95%;'>
	  <ul id="slider1" >
				<li class="first"><a href='#'><img style='width:100%;' src="images/mobile-and-cloud-big.png" alt="Mobile-and-cloud"   /></a></li>
				<li class="second"><a href='#'><img style='width:100%;' src="images/internet_of_things_big.png" alt="internet-of-things"/></a></li>
				<li class="third"><a href='#'><img style='width:100%;' src="images/embedded_mobile_big.png" alt="embedded-mobile-big"/></a></li>
				<li class="four"><a href='#'><img style='width:100%;' src="images/human_machine_interface_big.jpg" alt="human-machine-interface"/></a></li>
	  </ul>      
	</div>

  		<div id="search_results"></div>
	<div class="sixteen columns">
  </div>
		<script type="text/javascript" src="bxSlider/jquery.bxSlider.min.js"></script> 
        <script type="text/javascript" src="javascripts/jquery.mousewheel-3.0.2.pack.js"></script> 
        <script type="text/javascript" src="javascripts/jquery.fancybox-1.3.0.pack.js"></script>
        <script type="text/javascript" src="javascripts/script.js"></script> 
	</div></div>
  <?php include("footer.php"); ?>
</body>
</html>