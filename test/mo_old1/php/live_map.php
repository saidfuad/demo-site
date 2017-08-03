<?php
include("session.php");
require_once("../../db.php");
$cmd = $_REQUEST['cmd'];
$user 		= $_SESSION["user_id"];

if($cmd=="get_data")
{
	$coords = array();
	$stopArr = array();
	
	$page = $_REQUEST['page'];
	$limit = $_REQUEST['limit'];
	$reports = $_REQUEST['report'];
	$assets_ids = $_POST['assets_ids'];
	
	foreach($reports as $report) {
		$rptsub = substr($report, 0, 2);
		
		if($rptsub == "g-"){
			$group = str_replace($rptsub, "", $report);
		}
		
		if($rptsub == "u-"){
			$user = str_replace($rptsub, "", $report);
		}
		
		if($rptsub == "a-"){
			$us_ar = str_replace($rptsub, "", $report);
		}
		
		if($rptsub == "l-"){
			$us_ln = str_replace($rptsub, "", $report);
		}
		
		if($rptsub == "o-"){
			$us_ow = str_replace($rptsub, "", $report);
		}
		
		if($rptsub == "d-"){
			$us_dv = str_replace($rptsub, "", $report);
		}
	}
	
	if($us_ar != ""){
		$us_area = '';
		$query = "SELECT polyid, polyname FROM `areas` WHERE Audit_Status = 1 AND Audit_Del_Dt is null AND polyid = '".$us_ar."' limit 0,1";
		$res = mysql_query($query) or die($query. mysql_error());
		if(mysql_num_rows($res)>0){
			while($row = mysql_fetch_array($res)){
				$us_area = $row['polyname'];
			}
		}
		if($us_area!="")
			$gsub .= " AND lm.current_area = '".addslashes($us_area)."'";
	}
	
	if($us_ln != ""){
		$us_land = '';
		$query = "SELECT id, name FROM `landmark` WHERE Audit_Status = 1 AND Audit_Del_Dt is null AND id = '".$us_ln."' limit 0,1";
		$res = mysql_query($query) or die($query. mysql_error());
		if(mysql_num_rows($res)>0){
			while($row = mysql_fetch_array($res)){
				$us_land = $row['name'];
			}
		}
		if($us_land!="")
			$gsub .= " AND lm.current_landmark = '".addslashes($us_land)."'";
	}
	
	if($group != ""){
		
		$query = "SELECT id, assets FROM `group_master` WHERE Audit_Status = 1 AND Audit_Del_Dt is null AND id = '".$group."' limit 0,1";
		$res = mysql_query($query) or die($query. mysql_error());
		if(mysql_num_rows($res)>0){
			while($row = mysql_fetch_array($res)){
				$assets = $row['assets'];
			}
		}
		if($assets!="")
			$gsub .= " AND am.id in($assets)";
		else
			$gsub .= " AND am.id in(-1)";
	}
	
	if($user != ""){
		if($_SESSION['usertype_id'] != 1 || $user != $_SESSION['user_id'])
			$gsub .= " AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
		else {
			//$sub = "am.id IN(SELECT assets_ids FROM user_assets_map WHERE 1)";
			$gsub .= " AND 1=1";
		}
	}

	if(trim($us_ow) != '') {
		$gsub .= " AND am.assets_owner = '".intval($us_ow)."'";
	}
	
	if(trim($us_dv) != '') {
		$gsub .= " AND am.assets_division = '".intval($us_dv)."'";
	}
	
	$srch = "";
	if(isset($_REQUEST['txt']) && $_REQUEST['txt'] != ""){
		$txt = $_REQUEST['txt'];
		$srch = " AND am.assets_name LIKE ('%".$txt."%')";
	}
	
	$Fqry1="SELECT sp.device_id, TIME_TO_SEC(TIMEDIFF(now(), MAX( sp.ignition_off )))/60 stop_from FROM tbl_stop_report sp LEFT JOIN assests_master am ON am.id = sp.device_id LEFT JOIN tbl_last_point lm ON lm.device_id = am.device_id WHERE lm.speed = 0 ".$gsub." ".$srch." ".$stsWhr." GROUP BY sp.device_id";
	$res = mysql_query($Fqry1) or die($Fqry1. mysql_error());	
	if(mysql_num_rows($res) > 0) {
		while($row = mysql_fetch_array($res)){
			$minutes = $row['stop_from'];
			
			$d = floor ($minutes / 1440);
			$h = floor (($minutes - $d * 1440) / 60);
			$m = $minutes - ($d * 1440) - ($h * 60);
			$stop_time = '';
			if($d > 0)
				$stop_time .= $d." Day ";
			if($h > 0)
				$stop_time .= $h." Hour ";
			if($m > 0)
				$stop_time .= intval($m)." Min";
			
			$stopArr[$row['device_id']] = $stop_time;
		}
	}
	
	//----------2
	
	$qryFinal ="SELECT count(am.id) as total from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $gsub $srch $stsWhr";
	
	$res = mysql_query($qryFinal) or die($qryFinal. mysql_error());
	$data_arr=mysql_fetch_array($res);
	
	$totaldata = $data_arr[0]['total'];		
	if($limit == "all"){
		$limit = $totaldata;
		$lmt = 'all';
	}else{
		$lmt = $limit;
	}
	if( $totaldata > 0 ) {
		$total_pages = ceil($totaldata/$limit);	
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;	
	} else {
		$total_pages = 0;
		$start = 0;
	}
	
	$Fqry1="SELECT (select sum(fuel_used) from distance_master where assets_id = am.id) as fuel_used, lm.fuel_in, lm.fuel_out, trip.routename, um.username, um.first_name, am.id as assets_id, am.assets_category_id, ad.division as assets_division, ao.owner as assets_owner, am.fuel_in_out_sensor, am.fuel_in_per_lit, am.fuel_in_company_name, am.fuel_in_product_code, am.fuel_out_per_lit, am.fuel_out_company_name, am.fuel_out_product_code, am.xyz_sensor, am.battery_size, am.device_id, am.assets_friendly_nm, am.max_fuel_liters, am.assets_name, am.driver_name, am.assets_image_path, am.driver_mobile, am.sim_number, am.driver_image, am.km_reading, am.eng_runtime, CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ), CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) as beforeTime, lm.address, lm.runtime, lm.lati, lm.longi, lm.speed, lm.old_speed, lm.ignition, im.icon_path, lm.current_area, lm.current_landmark, lm.cross_speed, lm.fuel_percent, lm.fuel_liter, CONVERT_TZ(lm.fuel_time,'+00:00','".$_SESSION['timezone']."') as fuel_time, lm.temperature, lm.reason_text, lm.reason, lm.command_key, lm.command_key_value, lm.msg_key, lm.sat_mode, lm.gps_fixed, lm.gsm_register, lm.gsm_strength, lm.gprs_register, lm.server_avail, lm.in_batt, lm.ext_batt_volt, lm.captured_image FROM assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id LEFT JOIN tbl_users um on um.user_id = am.add_uid LEFT JOIN assests_owner_master ao ON ao.id = am.assets_owner LEFT JOIN assests_division_master ad ON ad.id = am.assets_division LEFT JOIN tbl_routes trip on trip.id = am.current_trip where am.status=1 AND am.del_date IS NULL ".$gsub." ".$usub." ".$srch." ".$stsWhr." ORDER BY am.assets_name LIMIT ".$start.", ".$limit;
	// echo $Fqry1;
	
	$res = mysql_query($Fqry1) or die($qryFinal. mysql_error());
	$totalPage = $total_pages;
	$page = $page;
	$totalRecords = $totaldata;
	$limit = $lmt;
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			if (array_key_exists($row['assets_id'], $stopArr)) {
				$row['stop_from'] = $stopArr[$row['assets_id']];
			}
			$row['received_time'] = ago($row->add_date)." ".'ago';
			$row['received_time'] = str_replace("weeks",'weeks',$row['received_time']);
			$row['received_time'] = str_replace("week",'week',$row['received_time']);
			$row['received_time'] = str_replace("months",'months',$row['received_time']);
			$row['received_time'] = str_replace("month",'month',$row['received_time']);
			$row['received_time'] = str_replace("years",'years',$row['received_time']);
			$row['received_time'] = str_replace("year",'year',$row['received_time']);
			$row['received_time'] = str_replace("days",'days',$row['received_time']);
			$row['received_time'] = str_replace("day",'day',$row['received_time']);
			$row['received_time'] = str_replace("hours",'hours',$row['received_time']);
			$row['received_time'] = str_replace("hour",'hour',$row['received_time']);
			$row['received_time'] = str_replace("minutes",'minutes',$row['received_time']);
			$row['received_time'] = str_replace("minute",'minute',$row['received_time']);
			$row['received_time'] = str_replace("seconds",'seconds',$row['received_time']);
			$type=str_replace("weeks",'wk',$row['received_time']);
			
			$coords[] = $row;
		}
	}

	$qry="SELECT ";
	//running
	if($user == 1){
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) <= 5400 and lm.speed > 0)) as Running,";
		
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) <= 5400 and lm.speed = 0) ) as Parked,";
		
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) between 5401 and 86399) ) as out_of_network,";
		
		$qry.="(select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND ((TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."')))) >= 86400 or (lm.add_date is null))) as device_fault,";
		
		$qry.="(SELECT count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group) as total";
	}else{
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) <= 5400 and lm.speed > 0)) as Running,";
		
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) <= 5400 and lm.speed = 0) ) as Parked,";
		
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."'))) between 5401 and 86399) ) as out_of_network,";
		
		$qry.="(select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND ((TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$_SESSION['timezone']."')))) >= 86400 or (lm.add_date is null))) as device_fault,";
		
		$qry.="(SELECT count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user."))) as total";
	}
	$res = mysql_query($qry) or die($qry. mysql_error());
	$running="";
	$parked="";
	$out_of_network="";
	$device_fault="";
	$total="";
	
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$running .= $row[0]['Running'];
			$parked .= $row[0]['Parked'];
			$out_of_network .= $row[0]['out_of_network'];
			$device_fault .= $row[0]['device_fault'];
			$total .= $row[0]['total'];
		}
	}
	
	$data['running_1'] = $running;
	$data['parked_1'] = $parked;
	$data['out_of_network_1'] = $out_of_network;
	$data['device_fault_1'] = $device_fault;
	$data['total_1'] = $total;
	$data['coords'] = $coords;
	$data['totalPage'] = $totalPage;
	$data['page'] = $page;
	$data['totalRecords'] = $totalRecords;
	$data['limit'] = $limit;
	
	$SQL="select auto_refresh_setting from tbl_users where user_id = $user";
	$res = mysql_query($SQL) or die($qry. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$data['auto_refresh_setting'] = $row['auto_refresh_setting'];
		}
	}
	print(json_encode($data));
}

?>