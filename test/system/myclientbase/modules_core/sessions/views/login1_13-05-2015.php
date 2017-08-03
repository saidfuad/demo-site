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
		if(intval($_browser['version']) < 8 )
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
//print_r($yourbrowser);
/*
$_SESSION["mobile"]	= browser_detect();
if($_SESSION["mobile"] === true){
	header("Location: ./mobile/");
	exit();
}
*/
?>

<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if('test.trackeron.com' == $_SERVER['HTTP_HOST'] || 'vts.trackeron.com' == $_SERVER['HTTP_HOST']) { ?>
<title><?php echo $this->lang->line('trackon'); ?></title>
<?php } else if('itmsafrica.com' == $_SERVER['HTTP_HOST'] || 'www.itmsafrica.com' == $_SERVER['HTTP_HOST']) { ?>
<title><?php echo $this->lang->line('itms'); ?></title>
<?php } else if('eazytrace.co' == $_SERVER['HTTP_HOST'] || 'www.eazytrace.co' == $_SERVER['HTTP_HOST']) { ?>
<title><?php echo $this->lang->line('acm'); ?></title>
<?php } else { ?>
<title><?php echo $this->lang->line('itms'); ?></title>
<?php }  ?>
<meta name="ref" content="<?php echo $this->session->userdata('site_referer'); ?>" />

<link href="<?php echo base_url(); ?>assets/css_login/login_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css_login/icon.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/components/tipsy/tipsy.css" media="all"/>
 <?php if('itmsafrica.com' == $_SERVER['HTTP_HOST'] || 'www.itmsafrica.com' == $_SERVER['HTTP_HOST']) { ?>

<?php } else if('eazytrace.co' == $_SERVER['HTTP_HOST'] || 'www.eazytrace.co' == $_SERVER['HTTP_HOST']) { ?>
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images_login/acm_icon.png" />

<?php } else { ?>

<?php }  ?>




<!--[if lt IE 9]>
              <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--<link href="<?php echo base_url(); ?>assets/style/css/style_login_min.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/dashboard/images/nk.ico">-->
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie7.css" /><![endif]-->
<style type="text/css">
html {
	background-image: none;
}
#versionBar {
	background-color: #212121;
	position: fixed;
	width: 100%;
	height: 35px;
	bottom: 0;
	left: 0;
	text-align: center;
	line-height: 35px;
}
.copyright {
	text-align: center;
	font-size: 10px;
	color: #CCC;
}
.copyright a {
	color: #A31F1A;
	text-decoration: none
}
#login .logo{top:2%!important;}
</style>
</head>
<body>

<!--new login vts-->
<div id="alertMessage" class="error"></div>
<div id="successLogin"></div>
<div class="text_success"><img src="<?php echo base_url(); ?>assets/images_login/loadder/loader_green.gif"  alt="RASTREARNET" /><span>Please wait</span></div>
<div id="login" >
  <div class="ribbon"></div>
  <div class="inner" >
       <?php if('itmsafrica.com' == $_SERVER['HTTP_HOST'] || 'www.itmsafrica.com' == $_SERVER['HTTP_HOST']) { ?>
    <div  class="logo" style="margin-top:20px"><img src="<?php echo base_url(); ?>assets/images_login/logo_itms.png" alt="ITMS AFRICA" /></div>
        <?php } else if('eazytrace.co' == $_SERVER['HTTP_HOST'] || 'www.eazytrace.co' == $_SERVER['HTTP_HOST']) { ?>
     <div  class="logo" style="margin-top:20px;left: 44%""><img src="<?php echo base_url(); ?>assets/images_login/acm_logo.png" alt="EasyTrace"/></div>
	<?php } else{ ?>
	<div  class="logo" style="margin-top:20px"><img src="<?php echo base_url(); ?>assets/images_login/logo_itms.png" alt="ITMS AFRICA" /></div>
        <?php } ?>
    <div class="userbox"></div>
    
    <div class="formLogin" >
     <!--<form name="formLogin"  id="formLogin" action="#">-->
     <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>"  name="formLogin"  id="formLogin">
    
  <div class="tip">
          <input name="username" type="text"  id="username_id"  title="Username"   />
        </div>
        <div class="tip">
          <input name="password" type="password" id="password"   title="Password"  />
        </div>
         
	
        <div class="tip" style="margin-left:10px;">
		<?php echo $this->load->view('dashboard/system_messages'); // display all messages
       $messages = $this->messages->get(); 
       if (is_array($messages)){
	        foreach ($messages as $type => $msgs){
		if (count($msgs > 0)){
			foreach ($msgs as $message){
	    echo "<div class=".$type.">" . $message . "</div>";
              }
         }
    }
}
?>
		</div>
        <div style="padding:20px 0px 0px 0px ;">
            <?php if('itmsafrica.com' == $_SERVER['HTTP_HOST'] || 'www.itmsafrica.com' == $_SERVER['HTTP_HOST']){ ?>
                <div style="float:right;padding:2px 0px ;">
                    <ul class="uibutton-group">
                        <li><input type="submit" value="Login" name="btn_submit"  /></li>
                    </ul>
                </div>
            <?php } else if('eazytrace.co' == $_SERVER['HTTP_HOST'] || 'www.eazytrace.co' == $_SERVER['HTTP_HOST']) { ?>
                <div style="float:right;padding:2px 0px ;">
                    <ul class="uibutton-group">
                        <li><input type="submit" value="Login" name="btn_submit"  /></li>
                    </ul>
                </div>
            <?php } else { ?>
				<div style="float:right;padding:2px 0px ;">
                    <ul class="uibutton-group">
                        <li><input type="submit" value="Login" name="btn_submit"  /></li>
                    </ul>
                </div>
            <?php } ?>  
        </div>
      </form>
    </div>
  </div>
  <div class="clear"></div>
  <div class="shadow"></div>
</div>

<!--Login div-->
<div class="clear"></div>
<div id="versionBar" >
        <?php if('itmsafrica.com' == $_SERVER['HTTP_HOST'] || 'www.itmsafrica.com' == $_SERVER['HTTP_HOST']) {  ?>
            <div class="copyright" > &copy; Copyright <?php echo date("Y"); ?>  All Rights Reserved 
                <span class="tip"><a  href="#" title="ITMS AFRICA" target="_blank">ITMS AFRICA</a></span>
            </div>
        <?php } else if('eazytrace.co' == $_SERVER['HTTP_HOST'] || 'www.eazytrace.co' == $_SERVER['HTTP_HOST']) { ?>
            <div class="copyright" > &copy; Copyright <?php echo date("Y"); ?>  All Rights Reserved 
            <span class="tip"><a  href="#" title="EasyTrace" target="_blank">EasyTrace</a></span>
        <?php  }  else { ?>
             <div class="copyright" > &copy; Copyright <?php echo date("Y"); ?>  All Rights Reserved 
                <span class="tip"><a  href="#" title="ITMS AFRICA" target="_blank">ITMS AFRICA</a></span>
            </div>
        <?php } ?>
  <!-- // copyright--> 
</div>
<!-- Link JScript--> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/components/effect/jquery-jrumble.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/components/ui/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/components/tipsy/jquery.tipsy.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/components/checkboxes/iphone.check.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/login.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jquery-1.3.2.min.js"></script>

<script type="text/javascript">
 $(document).ready(function(){
 
    $('#buttton_login').click(function () {
 
	$('#username_id').val("vts");
	$('#password').val("vts");
     
 
    });
    $('#btn_register').click(function () {
	window.location = '<?php echo base_url(); ?>register/';
    });
  });
</script>
<script type="text/javascript">
document.getElementById("username").focus();function checkEntr(e){ var key = e.charCode || e.keyCode || 0;
if(key==13){ document.getElementById("password").focus(); return false;}
return key; }
</script>
</body>
</html>
