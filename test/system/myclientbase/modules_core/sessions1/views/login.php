<?php
	require 'browser_detect.php';
	function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
	   
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
	   
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	   
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
	   
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
	   
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'type'      => $ub,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	}

	$_browser=getBrowser();
	$yourbrowser= "Your browser: " . $_browser['type'] . " " . $_browser['version'] . " on " .$_browser['platform'] . " reports: <br >" . $_browser['userAgent'];
	//print_r($yourbrowser);
	$yourbrowser_p= "Your browser: " . $_browser['type'] . " " . $_browser['version'] . " on " .$_browser['platform'];
	
	
	$msg = "<h2>Please use following browser</h2><hr /><br><ul><li>Internet Explorer 8 or above</li><li>Firefox 4 or above</li><li>Safari 4 or above</li><li>Opera 10 or above</li></ul>";
	if($_browser['type'] == "MSIE")
	{
		if(intval($_browser['version']) < 8 )
		{
			echo $yourbrowser_p;
			die($msg);
		}
	}
	else if($_browser['type'] == "Firefox")
	{
		if(intval($_browser['version']) < 4 )
		{
			echo $yourbrowser_p;
			die($msg);
		}
	}else if($_browser['type'] == "Safari")
	{
		if(intval($_browser['version']) < 4 )
		{
			echo $yourbrowser_p;
			die($msg);
		}
	}else if($_browser['type'] == "Opera")
	{
		if(intval($_browser['version']) < 10 )
		{
			echo $yourbrowser_p;
			die($msg);
		}
	}

	$nk_url = 'http://nkonnect.com';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta content="width=device-width, initial-scale=1, minimum-scale=1" name="viewport">
<meta name="keywords" content="Vehicle tracking systems demo,Online Vehicle tracking systems,Vehicle tracking systems Live demo,Free Vehicle tracking systems, Gps Tracker demo, Live gps based Vehicle tracking systems, Demo of Vehicle tracking system India">
<meta name="description" content="Vehicle tracking system | vehicle tracking system Live Demo | vehicle tracking system India | No 1 vehicle tracking system By Nkonnect | gps Base vehicle tracking device demo"/>
<title>Nkonnect Vehicle Tracking Systems India | Live Demo | Gps Tracker Live Demo</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- End Retina Images -->

<!-- Fonts -->
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
<!-- End Fonts -->

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

<!-- <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico"> -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/dashboard/images/nk.ico">
<link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>assets/images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>assets/images/apple-touch-icon-114x114.png">

<!-- JAVASCRIPT LIBRARY -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-1.8.2.min.js"></script>

<!-- Main JS -->

<style>
#features {
	background:none repeat scroll 0 0 #5B4099;
	color: white;
}
#features h2 {
	color: white;
}
</style>
</head>

<body>
<meta name="google-translate-customization" content="c034b1b9658f7447-f2bad41f9a90aee9-g36c27391ddf3d33a-12">
</meta>
<div id="mobile-nav">
  <div class="container clearfix">
    <div> 
      
      <!-- Mobile Nav Button -->
      <div class="navigationButton sixteen columns clearfix"> <img src="<?php echo base_url(); ?>assets/images/mobile-nav.png" alt="Navigation" width="29" height="17" /> </div>
      <!-- End Mobile Nav Button -->
      
      <div class="navigationContent sixteen columns clearfix"> 
        
        <!-- Mobile Navigation -->
        <ul>
          <li><a class="active" href="<?php echo $nk_url; ?>/">Home</a></li>
          <li><a href="<?php echo $nk_url; ?>/applicable-area/">Applicable Area</a></li>
          <li><a href="<?php echo $nk_url; ?>/technologies-solutions/">Technologies & Solution</a></li>
          <li><a href="<?php echo $nk_url; ?>/blog/">Blog</a> </li>
          <li><a href="<?php echo $nk_url; ?>/about/">About US</a></li>
          <li><a href="<?php echo $nk_url; ?>/contact/">Contact US</a></li>
        </ul>
        <!-- End Mobile Navigation --> 
        
      </div>
    </div>
  </div>
  <!-- END CONTAINER --> 
  
</div>
<header class="container clearfix"> 
  
  <!-- Logo -->
  <div id="logo" class="two columns clearfix"><a href='<?php echo base_url(); ?>'><img style='margin-top: -13px;' src="<?php echo base_url(); ?>assets/images/nkonnect-logo.png" alt="NKonnect Sensing Future"  /></a></div>
  <!-- End Logo --> 
  
  <!-- Navigation -->
  <nav id="nav" class="fourteen columns clearfix">
    <ul id="navigation">
      <li><a href="<?php echo $nk_url; ?>/">Home</a></li>
      <li class="active"><a href="#">VTS</a></li>
      <li><a href="<?php echo $nk_url; ?>/applicable-area/">Applicable Area</a></li>
      <li><a href="<?php echo $nk_url; ?>/technologies-solutions/">Technologies & Solutions</a></li>
      <li><a href="<?php echo $nk_url; ?>/about/" >About Us</a></li>
      <li><a href="<?php echo $nk_url; ?>/contact/">Contact Us</a></li>
    </ul>
  </nav>
  <!-- End Navigation -->
  
  <div id="sidebar-button" style='' class="three columns clearfix">
    <div style='float: left;  padding-left: 10px;font-size:14px;'> <a href='http://gatti.nkonnect.com/' style='color: #1155CC;'>Demo</a> </div>
    <div style='float: left; padding-left: 10px;font-size:14px;'> <a href="javascript:void(window.open('http://nkonnect.com/live_support/livezilla.php','','width=590,height=550,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img align="top" src="http://nkonnect.com/live_support/image.php?id=05" alt="Live Assistance">Live Chat</a> </div>
    <div style="float: right;" id="google_translate_element"></div>
    <script type="text/javascript">
				function googleTranslateElementInit() {
				  new google.translate.TranslateElement({pageLanguage: 'gu', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
				}
			</script> 
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> 
  </div>
  <style>
		
		
	</style>
</header>
<!-- END CONTAINER -->
<section id="title" class="container clearfix"> 
  
  <!-- Title -->
  <div class="ten columns">
    <h1>Login</h1>
    <h2>So, you've arrived at the login page... from here after login u can track all your valuable assets.</h2>
	<br>
	<h2>
	For demonstration you just have enter as below detail in form.<br>
	Username : demo<br>
	Password : devindia<br> 
	</h2>
  </div>
  <!-- End Title --> 
  
</section>
<!-- END CONTAINER -->

<section id="contact" class="container clearfix">
  <div class="two-thirds column">
	<?php echo $this->load->view('dashboard/system_messages'); // display all messages
    $messages = $this->messages->get(); 
    if (is_array($messages)):
        foreach ($messages as $type => $msgs):
            if (count($msgs > 0)):
                foreach ($msgs as $message):
                    echo ('<div class="' .  $type .'">' . $message . '</div>');
               endforeach;
           endif;
        endforeach;
    endif;
    ?>  
    <!-- Form -->
    <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" name="loginform" id="loginform">
      <fieldset>
        <div class="form-field">
          <label for="name">
          <h5><?php echo $this->lang->line('username'); ?></h5>
          </label>
          <span>
          <input type="text" name="username" id="username"  />
          </span> </div>
        <div class="form-field">
          <label for="email">
          <h5><?php echo $this->lang->line('password'); ?></h5>
          </label>
          <span>
          <input type="password"  id="password" name="password" />
          </span> </div>
      </fieldset>
      <div class="form-click"> <span>
        <input type="submit" value="<?php echo $this->lang->line('log_in'); ?>" name="btn_submit" id="login_submit" />
        </span> </div>
       
    </form>
   
    <!-- End Form -->
    <div id="alert"></div>
    <br><Br>
   	<section id="contact-body" class="container clearfix">
	
			<h3 class="first">Headquarter</h3>
			<br>
			<h2 style='color:black;'>NKonnect Sensing Future</h2>
			<ul class="address">
				<li>'VINOD', 4/6 Kishanpara, Nr. Kishanpara circle, Gaurav Path, 
Rajkot-360001, Gujarat, INDIA</li>				
			</ul>
			
			<br>
			
			
			<aside class="six columns">
				<ul class="address">
					<li>Land line : +91 281 2 45 38 09 </li>
					<li>Contact No : +91 98240 84414</li>
				</ul>
			</aside>
			
			<aside class="six columns">
				<ul class="address">
					<li>Fax : +91 281 2 45 84 49</li>
					<li> Email: <a href="mailto:info@nkonnect.com">info@nkonnect.com</a></li>
				</ul>
			</aside>
			<br><br><br><br>
			
			<aside class="six columns">
				<h2 style='color:black;'>Marketing & sales:</h2>
				<ul class="address">
					<li><a href="mailto:mkt@nkonnect.com">mkt@nkonnect.com</a></li>
					<li>call: +91 97143 25000</li>
					<li>chat: mkt.nkonnect@gmail.com</li>
					<li>talk: mkt.nkonnect@skype.com</li>
					<li>
						
						<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
						<a href="skype:mkt.nkonnect?call"><img src="http://mystatus.skype.com/bigclassic/mkt%2Enkonnect" style="border: none;" width="182" height="44" alt="My status" /></a>

					
					</li>
				</ul>
			</aside>
			
			<aside class="six columns">
				<h2 style='color:black;'>Support:</h2>
				<ul class="address">
					<li><a href="mailto:support@nkonnect.com">support@nkonnect.com</a></li>
					<li>call: on +91 97142 25000</li>
					<li>chat: helpdesk.nkonnect@gmail.com</li>
					<li>talk: helpdesk.nkonnect@skype.com</li>
					<li>
						<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
						<a href="skype:helpdesk.nkonnect?call"><img src="http://mystatus.skype.com/bigclassic/helpdesk%2Enkonnect" style="border: none;" width="182" height="44" alt="My status" /></a>
					
					</li>
				</ul>
			</aside>
			<br style='clear:both;'><br><br>
			<p style='clear:both;'>Or simply miss-call on +91 97141 25000, we will contact. !</p>
			
			<br>
			
			
			<aside class="six columns">
			
				<h2 style='color:black;'>Social</h2>
				<br>
							
				<div class='share-container'>
					<div class="social_icon" style="float:left;">
						<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fnkonnect.sensing.future&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:91px; height:21px;" allowTransparency="true"></iframe>
					
						<script type="text/javascript" src="https://platform.linkedin.com/in.js"></script>
						<script type="IN/FollowCompany" data-id="2631074" data-counter="none"></script>
						
						<a href="https://twitter.com/nkonnectsf" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @NKonnect</a>
						
						<script>
							!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
						</script>
							
						<div class="g-plusone" data-size="medium" data-annotation="none" data-width="120"></div>
						
						<script type="text/javascript">
						  (function() {
							var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							po.src = 'https://apis.google.com/js/plusone.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script> 
						
						<a href="https://www.youtube.com/user/devindiainfoway" target="_blank" ><img src="<?php echo base_url(); ?>/assets/images/youtube.png" alt="youtube" style="margin-top: -11px;" title="Watch Us on YouTube"/></a>
						
						<!--
						<a href="rss.php" target="_blank" >
							<img src="<?php //echo $site_url; ?>images/rss_feed_1.gif" width="18" height="20" alt="rss" />
						</a>
						-->
					 
					</div>
				</div>
			
			
			</aside>	
			
			<br><br><br><br>
			
	</section>
  </div>
 <!-- <aside class="one-third column">
    <h3 class="first">Email</h3>
    <a class="button" href="mailto:info@nkonnect.com" style='margin-bottom:10px;'>info@nkonnect.com</a> <a class="button" href="mailto:dubai@nkonnect.com" style='margin-bottom:10px;'>dubai@nkonnect.com</a>
    <h3>Telephone</h3>
    <p><span class="telephone">+91 281 2 45 38 09</span></p>
    <h3>Mobile</h3>
    <p><span class="telephone">+91 97143 25000&nbsp;&nbsp;&nbsp;&nbsp;Marketing</span></p>
    <p><span class="telephone">+91 97142 25000&nbsp;&nbsp;&nbsp;&nbsp;Support</span></p>
    <p><span class="telephone">+91 98240 84414&nbsp;&nbsp;&nbsp;&nbsp;Contact</span></p>
    <h3>Address</h3>
    <h3>India</h3>
    <ul class="address">
      <li>'VINOD'</li>
      <li>4/6 Kishanpara</li>
      <li>Nr. Kishanpara circle</li>
      <li>Gaurav Path</li>
      <li>Rajkot-360001 | Gujarat | INDIA</li>
    </ul>
    <h3>Middle-East</h3>
    <ul class="address">
      <li>Stunning image information technology</li>
      <li>Bur Dubai</li>
      <li>Dubai | UAE | PO Box: 46358.</li>
      <li>Contact :&nbsp;&nbsp;+971 50 4239272</li>
    </ul>
    <h3>Social</h3>
    <div class='share-container'>
      <div class="social_icon" style="float:left;">
        <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fnkonnect.sensing.future&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:91px; height:21px;" allowTransparency="true"></iframe>
        <script type="text/javascript" src="https://platform.linkedin.com/in.js"></script> 
        <script type="IN/FollowCompany" data-id="2631074" data-counter="none"></script> 
        <a href="https://twitter.com/nkonnectsf" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @NKonnect</a> 
        <script>
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
		</script>
<div class="g-plusone" data-size="medium" data-annotation="none" data-width="120"></div>
		<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script> 
      </div>
    </div>
  </aside>-->
</section>
<footer class="container clearfix"> 
  
  <!-- Navigation -->
  <div id="foot-nav" class="twelve1 columns">
    <ul>
      <li class="first"><a href="<?php echo $nk_url; ?>/product-guide/">Product Guide</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/technologies-solutions/">Technologies & Solutions</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/applicable-area/">Applicable Area</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/internet-of-things/">Internet of things</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/clients/">Clients</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/about/">About Us</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/blog/">Blog</a><span class='seprator'>|</span></li>
      <li><a href="<?php echo $nk_url; ?>/sitemap/">Sitemap</a></li>
    </ul>
  </div>
  <!-- End Navigation --> 
  
  <!-- Copyright -->
  <div id="copyright" class="four1 columns">
    <p><a href="http://www.nkonnect.com/">NKonnect</a> &copy; copyright 2012</p>
  </div>
  <!-- End Copyright --> 
</footer>
<!-- END CONTAINER -->
<script type="text/javascript">
document.getElementById("username").focus();function checkEntr(e){ var key = e.charCode || e.keyCode || 0;
if(key==13){ document.getElementById("password").focus(); return false;}
return key; }
</script>
<script type="text/javascript">
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>