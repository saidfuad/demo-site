<?php session_start(); ?>
<?php include("../../db.php"); 
	$x_user = $_POST["Username"];
	$x_pass = $_POST["Password"];
	$x_lang = $_POST["language"];
	if($x_user == "" or $x_pass == "")
	{
		generateMSG("", "User Name or password Not Provided");
	}
	$query = "SELECT * FROM tbl_users WHERE username = '$x_user' AND password = '".md5($x_pass)."' and NOW() BETWEEN from_date AND to_date and status = 1 ";
	$res =mysql_query($query) or generateMSG("", "SQL : ".$query."<br> Error : ".mysql_error());
	if(mysql_num_rows($res)>0)
	{
		$row = mysql_fetch_array($res);
		$_SESSION["user_id"] = $row["user_id"];
		$_SESSION["user"] = $x_user;
		$_SESSION["lang"] = $x_lang;
		if($row["date_format"]!="")
			$_SESSION["date_format"] =$row["date_format"];
		else
			$_SESSION["date_format"]='d.m.Y';
		if($row["time_format"]!="")
			$_SESSION["time_format"] =$row["time_format"];
		else
			$_SESSION["time_format"]='h:i a';
			
		$_SESSION['timezone'] = $row['timezone'];
		$_SESSION['show_owners'] = $row['show_owners'];
		$_SESSION['show_divisions'] = $row['show_divisions'];

		generateMSG('', "./", true);
	}
	else
	{
		generateMSG("", "Username or Password Incorrect Or Account Expired.");
	}
?>