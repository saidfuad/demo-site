<?php

	$mtime = microtime();
	set_time_limit (0);
	// Include the file for Database Connection
	require_once("../db.php");
	require_once("../functions.php");

	$input = //$_REQUEST['data'];
	$loginput=$input;

	$output = '';
	$current_area = '';
	$current_landmark = '';
	$current_area_id = '';
	$current_landmark_id = '';
	$cross_speed = 0;

	if(trim($input) == '') die("Blank Input String '$input'");
    $input = end(explode('imei:', $input));
	WriteLog($input);



	
	//imei:359710041529935,tracker,1408250529,,F,235956.000,A,1849.8088,N,07422.3225,E,0.00,0,,0,0,0.11%,,; :Data Received

	list($device, $reason_text, $date_time, $temp, $temp1, $gmtTime,
	$gps_fixed, $latitude, $latitude_d, $longitude, $longitude_d,
	$speedMPH, $direction) = @explode(",", $input);


	
	/*Added by Ashwini Gaikwad*/

	if(strtolower($reason_text)== 'oil' || strtolower($reason_text)== 'speed' || strtolower($reason_text)== 'accident alarm'){
	die("Not a Valid Tracker Data");
	}

	if($latitude == '' || $longitude == '' || is_numeric($latitude) == false || is_numeric($longitude) == false){
		die("Invalid Latitude and Longitude - Non Numeric");
	}
	/*end*/


	list($year, $month, $day, $hour, $min, $sec) = array(substr($date_time,0,2), substr($date_time,2,2), substr($date_time,4,2), substr($date_time,6,2), substr($date_time,8,2), substr($date_time,10,2));

	$localtime = date(DATE_TIME,mktime($hour, $min, $sec, $month, $day, $year));



	// $g_dt_time = convert_time_zone($localtime, 'UTC', 'Y-m-d H:i:s', 'America/Panama');

	$ist = $g_dt_tm = gmdate(DATE_TIME);
      //writelog("gm time".$ist);
	$g_date = date(DATE, strtotime($ist));
	$g_time = date(TIME, strtotime($ist));

	list($hour, $minute, $second) = array(substr($gmtTime,0,2), substr($gmtTime,2,2), substr($gmtTime,4,2));
	
	$latitude = deg_to_decimal($latitude.$latitude_d);
	$longitude = deg_to_decimal($longitude.$longitude_d);
	$speed = floatval($speedMPH * 1.852);
	$direction = floatval($direction);
	$odomVal = 0;
	$alarm_status = 0;
	$ignition = 0;
	$eventCode = '';
	$in_batt = '';
	$ext_batt = ''; 
							


	if(strtolower($reason_text) == 'help me') {
		$alarm_status = 1;
	}

  	if(strtolower($reason_text) == 'acc on'){
	 	$ignition_status = 'on';
	 	$ignition = 1;
	} else if (strtolower($reason_text) == 'acc off'){
		$ignition_status = 'off';
		$ignition = 0;
		//WriteLog($device."reason".$reason_text."status".$ignition_status);
	}

	if(isset($reason_text)  && strtolower($reason_text) == 'tracker') {
		$query = "SELECT ignition, add_date, latitude, longitude, address FROM itms_last_gps_point WHERE device_id = '".addslashes($device)."'";
		$result = mysql_query($query);
        $arr_row = mysql_fetch_array($result);
		$last_lignition = $arr_row['ignition'];
		if($last_lignition == 1)
		{
        	$ignition = $last_lignition;
		}
		$x_address = getNearest($latitude,$longitude);
		$last_time 	= $arr_row['add_date'];
		$old_ignition = $arr_row['ignition'];
		$old_latitude= $arr_row['latitude'];
		$old_longitude = $arr_row['longitude'];
		$new_longitude = $longitude;
		$new_latitude = $latitude;
		$old_address =  $arr_row['address'];
		$new_address = $x_address;
		$timeFirst  = strtotime($last_time);
		$timeSecond = strtotime($ist);
		$differenceInSeconds = $timeSecond - $timeFirst;
		
		
	 if ((($old_latitude == $new_latitude  && $old_longitude == $new_longitude ) || ($old_address == $new_address)) && $last_lignition == 0){


	  //$lSql = "UPDATE tbl_last_point SET add_date = '".addslashes($g_dt_tm)."', dt = '".$g_date."', speed = '".addslashes($speed)."' WHERE device_id = '".addslashes($device)."'";
	  $lSql = "UPDATE itms_last_gps_point set latitude='".addslashes($latitude)."', longitude='".addslashes($longitude)."', add_date='".addslashes($g_dt_tm)."', speed='".addslashes($speed)."', old_speed = '".$lastSpeed."', gps='".addslashes($gps_fixed)."', dt='".addslashes($g_date)."', tm='".addslashes($g_time)."', altitude='".addslashes($altitude)."', gsm_strength='".addslashes($gsm_strength)."', angle_dir='".addslashes($direction)."', address='".addslashes($x_address)."', odometer = '".addslashes($odomVal)."', reason_text = '".addslashes($reason_text)."', input_data = '".$input."', sat_mode = '".addslashes($sat_mode)."', gps_fixed = '".addslashes($gps_fixed)."', ext_batt_volt = '".addslashes($ext_batt_volt)."', ignition = '".addslashes($ignition)."' WHERE device_id = '".addslashes($device)."'";

	  mysql_query($lSql);

          log_raw_data($device, $loginput);
	  die("Duplicate Location");

	}

	if($differenceInSeconds < 9){
                die('Data received before 9 Seconds');
        }
	}

	log_raw_data($device, $loginput);
	//writeLog("hii");

	if((intval($latitude) != 0 && intval($longitude) != 0)) {

		$x_address = getNearest($latitude,$longitude); // getNearest($latitude, $longitude);

		$sql = "SELECT asset_id, add_uid, assets_name from itms_assets where device_id = '$device' limit 1";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$assets_id = $row['asset_id'];
		$user_id = $row['add_uid'];
		$assets_name = $row['assets_name'];

		//get assets details

		$aSql = "SELECT am.asset_id, am.add_uid, am.assets_name, am.assets_friendly_nm, ip.phone_no as driver_mobile, (ip.fname + ' ' +ip.lname) as driver_name, 
							am.max_speed_limit, am.current_trip, am.km_reading,  tm.time_zone, um.sms_enable, 
								um.first_name, um.user_id, um.language, um.max_stop_time, um.mobile_number, um.email_address, 
									um.email_alert, um.sms_alert, um.alert_stop_time, um.alert_start_time, um.ignition_on_alert, 
										um.ignition_off_alert, um.ignition_on_speed_off_minutes, um.ignition_off_speed_on_minutes, 
											um.from_date, um.to_date 
												FROM itms_assets am 
													left join itms_users um on um.user_id = am.add_uid 
													left join itms_personnel_master ip on ip.personnel_id = am.personnel_id 
													left join itms_timezone tm on tm.diff_from_gmt = um.timezone 
												WHERE am.device_id = '".addslashes($device)."' 
														AND am.del_date IS NULL AND am.status = 1";
		$aRs = mysql_query($aSql);

		$aRowCount = mysql_num_rows($aRs);

		if(! $aRowCount) {
			die('Data Received, But No Assets Defined');
		}
		$aRow = mysql_fetch_array($aRs);

		$valid_from  = $aRow['from_date'];
		$valid_to 	 = $aRow['to_date'];
		if(date('Y-m-d H:i:s') < $valid_from || date('Y-m-d H:i:s') > $valid_to){
			//die('Data Received, But User Expired');
		}

		$assets_id 	 = $aRow['asset_id'];
		$user_id 	 = $aRow['add_uid'];
		$assets_name = $aRow['assets_name'];
		$nick_name 	 = $aRow['assets_friendly_nm'];
		$driver_name = $aRow['driver_name'];
		$driver_mobile = $aRow['driver_mobile'];
		$speed_limit = $aRow['max_speed_limit'];
		$current_trip = $aRow['current_trip'];
                $km_reading = $aRow['km_reading'];
		$dts 		 = $aRow['time_zone'];
		$sms_enable	 = $aRow['sms_enable'];

		$uRow = array();
		$uRow['user_id'] = $aRow['user_id'];
		$uRow['first_name'] = $aRow['first_name'];
		$uRow['mobile_number'] = $aRow['mobile_number'];
		$uRow['email_address'] = $aRow['email_address'];
		$uRow['email_alert'] = $aRow['email_alert'];
		$uRow['sms_alert'] = $aRow['sms_alert'];
		$uRow['alert_stop_time'] = $aRow['alert_stop_time'];
		$uRow['alert_start_time'] = $aRow['alert_start_time'];
		$uRow['ignition_on_alert'] = $aRow['ignition_on_alert'];
		$uRow['ignition_off_alert'] = $aRow['ignition_off_alert'];
		$uRow['ignition_on_speed_off_minutes'] = $aRow['ignition_on_speed_off_minutes'];
		$uRow['ignition_off_speed_on_minutes'] = $aRow['ignition_off_speed_on_minutes'];
		$uRow['max_stop_time'] = $aRow['max_stop_time'];
                $uRow['language'] = $aRow['language'];

		area_in_out(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, 
						addslashes($latitude),addslashes($longitude), $speed, $ist);

		checkLandmark(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, 
						($latitude), addslashes($longitude), $speed, $ist, $odomVal);

		//panic_status($alarm_status, $device, $assets_id, $assets_name, $longitude, $latitude, $ist);
		$distance_by_latlong = '';
		$sql = "INSERT INTO itms_track SET assets_id = '$assets_id', latitude = '".addslashes($latitude)."', 
							longitude = '".addslashes($longitude)."', add_date = '".addslashes($g_dt_tm)."', 
							speed = '".addslashes($speed)."', device_id = '".addslashes($device)."', 
							gps = '".addslashes($gps_fixed)."', dt = '".date(DATE_TIME)."', 
							tm = '".addslashes($g_time)."', angle_dir = '".addslashes($direction)."', 
							address = '".addslashes($x_address)."', odometer = '".addslashes($odomVal)."', 
							reason = '".addslashes($eventCode)."', reason_text = '".addslashes($reason_text)."', 
							in_batt = '".addslashes($in_batt)."', ext_batt_volt = '".addslashes($ext_batt)."', 
							current_area_id = '$current_area_id', current_landmark_id = '$current_landmark_id', 
							data_type = 0, distance_by_latlong = '".$distance_by_latlong."'";

		$sql .= ", ignition = '".addslashes($ignition)."'";

		$track_res = mysql_query($sql) or die(mysql_error().":".$sql);

		//check for last point
		$sql = "SELECT address, add_date, speed, odometer, latitude, longitude FROM itms_last_gps_point WHERE device_id = '".addslashes($device)."'";
		$rs = mysql_query($sql);
		$lastRowCount = mysql_num_rows($rs);
		if(mysql_num_rows($rs) > 0){
			$lRow = mysql_fetch_array($rs);

			$last_lat 	= $lRow['latitude'];
			$last_lng 	= $lRow['longitude'];
			$lastSpeed 	= $lRow['speed'];
			$last_odometer 	= $lRow['odometer'];
			$last_address 	= $lRow['address'];
			$last_time 	= $lRow['add_date'];
			$timeFirst  	= strtotime($last_time);
			$timeSecond 	= strtotime($ist);
			$differenceInSeconds = $timeSecond - $timeFirst;

			$lSql = "UPDATE itms_last_gps_point set latitude='".addslashes($latitude)."', longitude='".addslashes($longitude)."', 
							add_date='".addslashes($g_dt_tm)."', speed='".addslashes($speed)."', old_speed = '".$lastSpeed."', 
								gps='".addslashes($gps_fixed)."', dt='".addslashes($g_date)."', tm='".addslashes($g_time)."', 
									altitude='".addslashes($altitude)."', gsm_strength='".addslashes($gsm_strength)."', 
										angle_dir='".addslashes($direction)."', address='".addslashes($x_address)."', 
											odometer = '".addslashes($odomVal)."', reason_text = '".addslashes($reason_text)."', 
												input_data = '".$input."', sat_mode = '".addslashes($sat_mode)."', 
													gps_fixed = '".addslashes($gps_fixed)."', ext_batt_volt = '".addslashes($ext_batt_volt)."'";
			$lSql .= ", ignition = '".addslashes($ignition)."'";

//			$lSql = "UPDATE tbl_last_point SET lati = '".addslashes($latitude)."', longi = '".addslashes($longitude)."', add_date = '".addslashes($g_dt_tm)."', ignition = '".addslashes($ignition)."', speed = '".addslashes($speed)."', gps = '".addslashes($gps_fixed)."', dt = '".$g_date."', tm = '".$g_time."', angle_dir = '".addslashes($direction)."', address = '".addslashes($x_address)."', odometer = '".addslashes($odomVal)."', in_batt = '".addslashes($in_batt)."', ext_batt_volt = '".addslashes($ext_batt)."', reason = '".addslashes($eventCode)."', reason_text = '".addslashes($reason_text)."'";

			$lSql .= ", current_landmark = '".$current_landmark."', landmark_id = '".$current_landmark_id."'";

			$lSql .= ", current_area = '".$current_area."', area_id = '".$current_area_id."'";

			$lSql .= " WHERE device_id = '$device'";

			mysql_query($lSql) or die("SQL : $lSql : Error : " . mysql_error());

			/*
			if ($eventCode != 35) {
				$smsText = "Alarm Alert For $assets_name is : $reason_text";
				$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ( 'Alarm Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".date(DATE_TIME, strtotime($g_dt_tm))."')";
				//mysql_query($alertSql);
			}
*/
		}
		else {
			$lSql = "INSERT INTO itms_last_gps_point (latitude, longitude, add_date, ignition, speed, device_id, gps, dt, tm, angle_dir, address, odometer, reason, reason_text, in_batt, ext_batt_volt, current_landmark, current_area) VALUES ('".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($g_dt_tm)."', '".addslashes($ignition)."', '".addslashes($speed)."', '".addslashes($device)."', '".addslashes($gps_fixed)."', '".$g_date."', '".$g_time."', '".addslashes($direction)."', '".addslashes($x_address)."', '".addslashes($odomVal)."', '".addslashes($eventCode)."', '".addslashes($reason_text)."', '".addslashes($in_batt)."', '".addslashes($ext_batt)."', '".$current_landmark."', '".$current_area."')";
			mysql_query($lSql) or die("SQL : $lSql : Error : " . mysql_error());
		}
		
		///Distance traveled Started
		$user_date = convert_time_zone($g_dt_tm, $dts, 'Y-m-d');
		$distance_travelled_trip = 0;
		if($odomVal > 0){
			$distance_travelled = 0;
			if($last_odometer != "" && $odomVal > $last_odometer){
				$distance_travelled = $odomVal - $last_odometer;
				$distance_travelled_trip = $odomVal - $last_odometer;
			}

			if($distance_travelled > 0) {
				$update = "UPDATE itms_assets SET km_reading = (km_reading + ".floatval($distance_travelled).") WHERE asset_id = ".$assets_id;
				mysql_query($update);
			}
				
			if($last_odometer != "" && $odomVal > $last_odometer){
				$sql = "SELECT current_reading FROM itms_distance_master WHERE assets_id = '".addslashes($assets_id)."' AND add_date = '".$user_date."'";
				
				$rs = mysql_query($sql);
				
				if(mysql_num_rows($rs) > 0){
					
					if($last_ignition == 1 AND $ignition == 1 && $speed > 0 ) $up_sub = ", running_time = (running_time + $differenceInSeconds)";
					
					$query = "UPDATE itms_distance_master SET distance = ($odomVal - first_reading), current_reading = '".addslashes($odomVal)."' $up_sub WHERE assets_id = '".addslashes($assets_id)."' and add_date = '".$user_date."'";
					mysql_query($query);
				}else{
					$sql = "SELECT current_reading FROM itms_distance_master WHERE assets_id = '".addslashes($assets_id)."' order by add_date desc limit 1";
					$rs = mysql_query($sql);
					if(mysql_num_rows($rs) > 0){
						$d_row = mysql_fetch_assoc($rs);
						$last_odometer = $d_row['current_reading'];
						$temp_dist = '';
						if($last_odometer < $odomVal && $last_odometer != "") {
							$distance = floatval($odomVal - $last_odometer);
						}
					}else{
						$last_odometer = $odomVal;
						$distance = 0;
					}
					$query = "INSERT INTO itms_distance_master (assets_id, add_date, first_reading, current_reading, distance) VALUES ('".addslashes($assets_id)."', '".$user_date."', '".addslashes($last_odometer)."', '".addslashes($odomVal)."', '$distance')";
					mysql_query($query);
				}
			}
		}
		else if($old_latitude != "" && $old_longitude != ""){
			$R = 6371;
			$dLat = ($old_latitude - $new_latitude) * pi() / 180;
			$dLon = ($old_longitude - $new_longitude) * pi() / 180;
			$a = sin($dLat / 2) * sin($dLat / 2) +	cos($lat1 * pi() / 180) * cos($lat2 * pi() / 180) *	sin($dLon / 2) * sin($dLon / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$d = $R * $c;
			$distance_by_latlong = round($d, 2);
			$distance_travelled_trip = $distance_by_latlong;
			$update = "UPDATE itms_assets SET km_reading = (km_reading + ".floatval($distance_by_latlong).") WHERE asset_id = ".$assets_id;
			mysql_query($update);
			$sql = "SELECT distance FROM itms_distance_master WHERE assets_id = '".addslashes($assets_id)."' AND add_date = '".$user_date."'";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs) > 0){
				//$query = "UPDATE itms_distance_master SET distance = (distance + ".floatval($distance_by_latlong).") WHERE assets_id = ".$assets_id." and add_date = ".$g_dt_tm;
				$query = "UPDATE itms_distance_master SET distance = (distance + ".floatval($distance_by_latlong).") WHERE assets_id = '".addslashes($assets_id)."' and add_date = '".$user_date."'";				
				mysql_query($query);
			}else{			
				$query = "INSERT INTO itms_distance_master (assets_id, add_date, distance) VALUES ('".addslashes($assets_id)."', '".$user_date."', '$distance_by_latlong')";
				mysql_query($query);
			}
		
		}
		///Distance traveled Completed
		checkSpeed(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $speed_limit, $speed, $ist, $latitude, $longitude);

		stop_report_insert($speed, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $ist);

		if($odomVal > 0){   
		start_report_insert($speed, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $ist, $odomVal);
                }
                else{
                run_report($speed, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $ist, $km_reading);                  
                }

		if($reason_text == 'acc on' || $reason_text == 'acc off'){

		ignitionAlert(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $ist);

		}
		$current=date(DATE_TIME);
		checkIgnitionOnSpeedOff(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $lastSpeed, $latitude, $longitude, $x_address, $current);
		checkIgnitionOffSpeedOn(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $last_speed, $latitude,$longitude, $x_address, $current);
		
		startStopTrip(addslashes($device), $assets_id, $assets_name, $nick_name, $driver_name, $latitude, $longitude, $speed, $ist, $distance_travelled_trip);
	
                checkRoute(addslashes($device), $assets_id, $current_trip, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist); 
        }
        
	echo "Data Received..";
?>