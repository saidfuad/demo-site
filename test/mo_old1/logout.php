<?php
	session_start();
	$host = $_SERVER['HTTP_HOST'];
	//if($host == '') $host = 'gatti.nkonnect.com/mo';
	session_destroy();	//destroy session
?>
<html>
<head>
<script type="text/javascript">
window.location='http://<?php echo $host; ?>/';
</script>
</head>
<body>
<h3>Redirecting to <a href="http://<?php echo $host; ?>/"><?php echo $host; ?></a></h3>
</body>