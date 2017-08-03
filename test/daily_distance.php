<?php include("db.php"); ?>
<?php
	$cmd 		= $_REQUEST['cmd'];
	$user 		= $_REQUEST['user'];//631
	$to 		= $_REQUEST['sdate'];
	$from 		= $_REQUEST['edate'];
	
	$current	= date("Y-m-d h:i:s");
	
	if(strtotime($current) < strtotime($current_date)){
		$current_date = $current;
	}
	
	$current_date	= date("Y-m-d", strtotime($from))." 05:30:00";
	$last_date = date('Y-m-d', strtotime($to))." 05:30:00";
	
	$today = date('d-m-Y', strtotime($current_date."+5:30 Hour" ));
	
	$today_small = date('d-M', strtotime($from));
	$yesterday_small = date('d-M', strtotime($to));
	
	
	
	$sql = "SELECT id, assets_name, assets_friendly_nm FROM assests_master WHERE FIND_IN_SET( id, (SELECT assets_ids FROM user_assets_map WHERE user_id = $user ) ) order by assets_friendly_nm";
	$rs = mysql_query($sql);
	$tbl_row = '';
	$i = 1;
	$assets = array();
	$assets_friendly_nm = array();
	while($row = mysql_fetch_array($rs)){
		$assets[] = $row['assets_name'];
		$assets_friendly_nm[] = $row['assets_friendly_nm'];
	}
	$query1 = "SELECT am.assets_name, am.assets_friendly_nm, c.odometer,c.address From tbl_track as c left join assests_master am on am.id = c.assets_id JOIN (
		SELECT assets_id, MAX(add_date) Maxdatetime
		FROM tbl_track where add_date < '$last_date'
		GROUP BY assets_id
	) r ON c.assets_id = r.assets_id AND c.add_date = r.Maxdatetime and FIND_IN_SET( am.id, (SELECT assets_ids FROM user_assets_map WHERE user_id = $user ) )
	ORDER BY am.assets_friendly_nm";
	$rs1 = mysql_query($query1);
	$data1 = array();
	while($row1 = mysql_fetch_array($rs1)){		
		
		$data1[$row1['assets_name']]['odometer'] = $row1['odometer'];
		$data1[$row1['assets_name']]['address'] = $row1['address'];
	}
	
	$query2 = "SELECT am.assets_name, am.assets_friendly_nm, c.odometer,c.address From tbl_track as c left join assests_master am on am.id = c.assets_id JOIN (
		SELECT assets_id, MAX(add_date) Maxdatetime
		FROM tbl_track where add_date < '$current_date'
		GROUP BY assets_id
	) r ON c.assets_id = r.assets_id AND c.add_date = r.Maxdatetime and FIND_IN_SET( am.id, (SELECT assets_ids FROM user_assets_map WHERE user_id = $user ) )
	ORDER BY am.assets_friendly_nm";
	$rs2 = mysql_query($query2);
	$data2 = array();
	while($row2 = mysql_fetch_array($rs2)){		
		$data2[$row2['assets_name']]['odometer'] = $row2['odometer'];
		$data2[$row2['assets_name']]['address'] = $row2['address'];
	}
	
	for($i=0; $i<count($assets); $i++){	
		$km = $data2[$assets[$i]]['odometer'] - $data1[$assets[$i]]['odometer'];
		$km = intval($km/1000);
		
		$tbl_row .= "<tr><td>".($i+1)."</td><td>".$assets_friendly_nm[$i]."</td><td>".$assets[$i]."</td><td align='right'>".$data1[$assets[$i]]['odometer']."</td><td>".$data1[$assets[$i]]['address']."</td><td align='right'>".$data2[$assets[$i]]['odometer']."</td><td>".$data2[$assets[$i]]['address']."</td><td align='right'>".$km."</td></tr>";
	}
	
	$table = "<style>
		.daily_distance_tbl {
			border-collapse: collapse; font-size:13px; font-family:Arial, Helvetica, sans-serif;
		}
		.daily_distance_tbl td{
			border-collapse: collapse; border:1px solid #000000;padding:3px;
		}
	</style>";
	$border = '';
	if($cmd == "export"){
		$border = "border='1'" ;
	}
	$table .= "<center><font style='font-weight:bold;font-family:Arial, Helvetica, sans-serif'>Daily Truck Running Report List.<br>(Date :- ".$today.")</font></center>";
	$table .= "<table ".$border." align='center' class='daily_distance_tbl' width='95%'>";
	$table .= "<tr style='font-weight:bold;'><td>Sr No</td><td>Company Name</td><td>Truck No</td><td>Opp. Mtr</td><td>Station Name</td><td>Closing Mtr</td><td>Station Name</td><td>Running Km.@day</td></tr>";
	$table .= "<tr><td></td><td></td><td></td><td>$yesterday_small</td><td>11:00am</td><td>$today_small</td><td>11:00am</td><td></td></tr>";
	$table .= $tbl_row;
	$table .= "</table>";
	if($cmd == "export"){
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=Daily_Distance('.$today.').xls');
	}
	if($cmd == "print"){
		$table .= '<script type="text/javascript">window.print();</script>';	
	}
	echo $table;
?>