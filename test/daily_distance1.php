<?php include("db.php"); ?>
<?php
	$cmd 		= $_REQUEST['cmd'];
	$user 		= $_REQUEST['user'];
	$current	= date("Y-m-d h:i:s");
	$current_date	= date("Y-m-d")." 05:30:00";
	if(strtotime($current) < strtotime($current_date)){
		$current_date = $current;
	}
	$today = date('d-m-Y', strtotime($current."+5:30 Hour" ));
	
	$today_small = date('d-M', strtotime($current."+5:30 Hour" ));
	$yesterday_small = date('d-M', strtotime($today."- 1 day" ));
	
	$last_date = date('Y-m-d', strtotime($current."- 1 day" ))." 05:30:00";
	
	$sql = "SELECT id, assets_name, assets_friendly_nm FROM assests_master WHERE FIND_IN_SET( id, (SELECT assets_ids FROM user_assets_map WHERE user_id =631 ) ) order by assets_friendly_nm";
	$rs = mysql_query($sql);
	$tbl_row = '';
	$i = 1;
	while($row = mysql_fetch_array($rs)){
		$assets_id = $row['id'];
		$query1 = "select odometer, address from tbl_track where assets_id = $assets_id and add_date < '$last_date' order by id desc limit 1";
		$rs1 = mysql_query($query1);
		$row1 = mysql_fetch_array($rs1);
		$odometer1 = $row1['odometer'];
		$address1 = $row1['address'];
		
		$query2 = "select * from tbl_track where assets_id = $assets_id and add_date < '$current_date' order by id desc limit 1";
		$rs2 = mysql_query($query2);
		$row2 = mysql_fetch_array($rs2);
		$odometer2 = $row2['odometer'];
		$address2 = $row2['address'];
		
		$km = $odometer2 - $odometer1;
		$km = intval($km/1000);
		
		$tbl_row .= "<tr><td>".$i."</td><td>".$row['assets_friendly_nm']."</td><td>".$row['assets_name']."</td><td align='right'>".$odometer1."</td><td>".$address1."</td><td align='right'>".$odometer2."</td><td>".$address2."</td><td align='right'>".$km."</td></tr>";
		$i++;
	}
	
	$table = "<style>
		.daily_distance_tbl {
			border-collapse: collapse; font-size:13px; font-family:Arial, Helvetica, sans-serif;
		}
		.daily_distance_tbl td{
			border-collapse: collapse; border:1px solid #000000;padding:3px;
		}
	</style>";
	$table .= "<center><font style='font-weight:bold;font-family:Arial, Helvetica, sans-serif'>Daily Truck Running Report List.<br>(Date :- ".$today.")</font></center>";
	$table .= "<table align='center' class='daily_distance_tbl' width='95%'>";
	$table .= "<tr style='font-weight:bold;'><td>Sr No</td><td>Company Name</td><td>Truck No</td><td>Opp. Mtr</td><td>Station Name</td><td>Closing Mtr</td><td>Station Name</td><td>Running Km.@day</td></tr>";
	$table .= "<tr><td></td><td></td><td></td><td>$yesterday_small</td><td>11:00am</td><td>$today_small</td><td>11:00am</td><td></td></tr>";
	$table .= $tbl_row;
	$table .= "</table>";
	echo $table;
?>