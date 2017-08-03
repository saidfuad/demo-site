<?php
	// Set time limit to indefinite execution
	
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;
	set_time_limit (0);
	// Include the file for Database Connection
	require_once("db.php");
	require_once("functions.php");
	/*include('XMPPHP/XMPP.php');*/
	
	$input = $_REQUEST['data'];
	$output = '';
	$is_insert = true;
	$current_area = '';
	$current_landmark = '';
	$current_area_id = '';
	$current_landmark_id = '';
	$cross_speed = 0;
	if(trim($input) == '') WriteLog("Blank Input String '$input'");
	
	$RecievedBytes = strlen($input);
	
	$input_data = array();
	$input_data = explode('$', $input);
	$assets_id_j="";
	$device_id_j="";
	list($message_start, $unit_no, $msg_serial_no, $reason, $command_key, $command_key_value, $ignition, $power_cut, $box_open, $msg_key, $odometer, $speed, $sat_mode, $gps_fixed, $latitude, $longitude, $altitude, $direction, $time, $date, $gsm_strength, $gsm_register, $gprs_register, $server_avail, $in_batt, $ext_batt_volt, $digital_io, $analog_in_1, $analog_in_2, $analog_in_3, $analog_in_4, $hw_version, $sw_version, $data_type, $rfid) = @explode(",", $input_data[1]);
	
	log_raw_data($unit_no, $input);

	/* offline data only for history */
	if(count($input_data) > 2){
		list($message_start, $unit_no, $msg_serial_no, $reason, $command_key, $command_key_value, $ignition, $power_cut, $box_open, $msg_key, $odometer, $speed, $sat_mode, $gps_fixed, $latitude, $longitude, $altitude, $direction, $time, $date, $gsm_strength, $gsm_register, $gprs_register, $server_avail, $in_batt, $ext_batt_volt, $digital_io, $analog_in_1, $analog_in_2, $analog_in_3, $analog_in_4, $hw_version, $sw_version, $data_type, $rfid) = @explode(",", $input_data[1]);
		global $device_id_j;
		$device_id_j=$unit_no;
		
		$aSql = "SELECT am.id, tm.time_zone, um.alert_stop_time, um.alert_start_time, um.from_date, um.to_date FROM assests_master am left join tbl_users um on um.user_id = am.add_uid left join timezone tm on tm.diff_from_gmt = um.timezone WHERE am.device_id = '".addslashes($unit_no)."' AND am.del_date IS NULL AND am.status = 1";
		$aRs = mysql_query($aSql);
		$aRowCount = mysql_num_rows($aRs);
		
		if(! $aRowCount) {
			die('Data Received, But No Assets Defined');
		}
		
		$aRow = mysql_fetch_array($aRs);
		$valid_from  = $aRow['from_date'];
		$valid_to 	 = $aRow['to_date'];
		if(date('Y-m-d H:i:s') < $valid_from || date('Y-m-d H:i:s') > $valid_to){
			die('Data Received, But User Expired');
		}
		$assets_id 			= $aRow['id'];
		$dtz 				= $aRow['time_zone'];
		$alert_start_time 	= $aRow['alert_start_time'];
		$alert_stop_time 	= $aRow['alert_stop_time'];
		
		for($i=1; $i<(count($input_data)-1); $i++){
			list($message_start, $unit_no, $msg_serial_no, $reason, $command_key, $command_key_value, $ignition, $power_cut, $box_open, $msg_key, $odometer, $speed, $sat_mode, $gps_fixed, $latitude, $longitude, $altitude, $direction, $time, $date, $gsm_strength, $gsm_register, $gprs_register, $server_avail, $in_batt, $ext_batt_volt, $digital_io, $analog_in_1, $analog_in_2, $analog_in_3, $analog_in_4, $hw_version, $sw_version, $data_type, $rfid) = @explode(",", $input_data[$i]);
			
			$data_end = "";
			$track_res = true;
			$data_type = intval($data_type);
			
			$reason_text = $rs_array[$reason];
			
			if((intval($latitude) != 0 && intval($longitude) != 0) || $unit_no == 9004) {
				
				list($day, $month, $year, $hour, $min, $sec) = array(substr($date,0,2), substr($date,2,2), substr($date,4), substr($time,0,2), substr($time,2,2), substr($time,4,2));
				
				$ist = $gmt = date(DATE_TIME,mktime($hour, $min, $sec, $month, $day, $year));

				// $ist = date(DATE_TIME,strtotime($gmt . " +5 hours 30 minutes"));
				
				$x_address = getNearest($latitude, $longitude);	//getAddress($latitude, $longitude);
				
				$sql = "INSERT INTO tbl_track (assets_id, lati, longi, add_date, speed, device_id, gps, dt, tm, ignition, box_open, altitude, gsm_strength, angle_dir, power_st, address, msg_serial_no, reason, reason_text, command_key, command_key_value, msg_key, odometer, sat_mode, gsm_register, gprs_register, server_avail, in_batt, ext_batt_volt, digital_io, analog_in_1, analog_in_2, analog_in_3, analog_in_4, hw_version, sw_version, data_type) VALUES ($assets_id, '".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($ist)."', '".addslashes($speed)."', '".addslashes($unit_no)."', '".addslashes($gps_fixed)."', '".addslashes($ist)."', '".addslashes($time)."', '".addslashes($ignition)."', '".addslashes($box_open)."', '".addslashes($altitude)."', '".addslashes($gsm_strength)."', '".addslashes($direction)."', '".addslashes($power_cut)."', '".addslashes($x_address)."', '".addslashes($msg_serial_no)."', '".addslashes($reason)."', '".addslashes($reason_text)."', '".addslashes($command_key)."', '".addslashes($command_key_value)."', '".addslashes($msg_key)."', '".addslashes($odometer)."', '".addslashes($sat_mode)."', '".addslashes($gsm_register)."', '".addslashes($gprs_register)."', '".addslashes($server_avail)."', '".addslashes($in_batt)."', '".addslashes($ext_batt_volt)."', '".addslashes($digital_io)."', '".addslashes($analog_in_1)."', '".addslashes($analog_in_2)."', '".addslashes($analog_in_3)."', '".addslashes($analog_in_4)."', '".addslashes($hw_version)."', '".addslashes($sw_verison)."', '".addslashes($data_type)."')";
		
				$track_res = mysql_query($sql) or die(mysql_error().":".$sql);
			}
		}
	}
	/* ================ */
	if(is_array($input_data)) $input_data = end($input_data);
	
	list($message_start, $unit_no, $msg_serial_no, $reason, $command_key, $command_key_value, $ignition, $power_cut, $box_open, $msg_key, $odometer, $speed, $sat_mode, $gps_fixed, $latitude, $longitude, $altitude, $direction, $time, $date, $gsm_strength, $gsm_register, $gprs_register, $server_avail, $in_batt, $ext_batt_volt, $digital_io, $analog_in_1, $analog_in_2, $analog_in_3, $analog_in_4, $hw_version, $sw_version, $data_type, $rfid) = @explode(",", $input_data);
	
	$data_end = "";
	$track_res = true;
	$data_type = intval($data_type);
	
	$reason_text = $rs_array[$reason];
	$device_id_j=$unit_no;
	if($unit_no == 9083){
		$url = "http://zotrack.com/telnet/java.php?data=".$_REQUEST['data'];
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
	}
	if(strtolower($reason) == 'v' && $command_key_value != "")	{
		$rfid = hexdec($command_key_value);
	}	
	// $sql = "INSERT INTO tbl_track_log (message_start, unit_no, msg_serial_no, reason, command_key, command_key_value, ignition, power_cut, box_open, msg_key, odometer, speed, sat_mode, gps_fixed, latitude, longitude, altitude, direction, time, date, gsm_strength, gsm_register, gprs_register, server_avail, in_batt, ext_batt_volt, digital_io, analog_in_1, analog_in_2, analog_in_3, analog_in_4, hw_version, sw_version, data_type, data_end, add_date) VALUES ('".addslashes($message_start)."', '".addslashes($unit_no)."', '".addslashes($msg_serial_no)."', '".addslashes($reason)."', '".addslashes($command_key)."', '".addslashes($command_key_value)."', '".addslashes($ignition)."', '".addslashes($power_cut)."', '".addslashes($box_open)."', '".addslashes($msg_key)."', '".addslashes($odometer)."', '".addslashes($speed)."', '".addslashes($sat_mode)."', '".addslashes($gps_fixed)."', '".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($altitude)."', '".addslashes($direction)."', '".addslashes($time)."', '".addslashes($date)."', '".addslashes($gsm_strength)."', '".addslashes($gsm_register)."', '".addslashes($gprs_register)."', '".addslashes($server_avail)."', '".addslashes($in_batt)."', '".addslashes($ext_batt_volt)."', '".addslashes($digital_io)."', '".addslashes($analog_in_1)."', '".addslashes($analog_in_2)."', '".addslashes($analog_in_3)."', '".addslashes($analog_in_4)."', '".addslashes($hw_version)."', '".addslashes($sw_version)."', '".addslashes($data_type)."', '".addslashes($data_end)."', '".CURRENT_TIME."')";
	// $sql_res = mysql_query($sql);
	
	if((intval($latitude) != 0 && intval($longitude) != 0) || $unit_no == 9004 || $unit_no == 9761 || $unit_no == 9921 || $unit_no == 9906 || $unit_no == 9766) {
	
		if(($unit_no == 9921 || $unit_no == 9906 || $unit_no == 9766) && strtolower($reason) == 'v') {
			$ist = $gmt = CURRENT_TIME;
	
			$sql = "INSERT INTO tbl_track (assets_id, lati, longi, add_date, speed, device_id, gps, dt, tm, ignition, box_open, altitude, gsm_strength, angle_dir, power_st, address, msg_serial_no, reason, reason_text, command_key, command_key_value, msg_key, odometer, sat_mode, gsm_register, gprs_register, server_avail, in_batt, ext_batt_volt, digital_io, analog_in_1, analog_in_2, analog_in_3, analog_in_4, rfid, fuel_percent, temperature, hw_version, sw_version, current_area_id, current_landmark_id, data_type) VALUES (336, '".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($ist)."', '".addslashes($speed)."', '".addslashes($unit_no)."', '".addslashes($gps_fixed)."', '".addslashes($ist)."', '".addslashes($time)."', '".addslashes($ignition)."', '".addslashes($box_open)."', '".addslashes($altitude)."', '".addslashes($gsm_strength)."', '".addslashes($direction)."', '".addslashes($power_cut)."', '".addslashes($x_address)."', '".addslashes($msg_serial_no)."', '".addslashes($reason)."', '".addslashes($reason_text)."', '".addslashes($command_key)."', '".addslashes($command_key_value)."', '".addslashes($msg_key)."', '".addslashes($odometer)."', '".addslashes($sat_mode)."', '".addslashes($gsm_register)."', '".addslashes($gprs_register)."', '".addslashes($server_avail)."', '".addslashes($in_batt)."', '".addslashes($ext_batt_volt)."', '".addslashes($digital_io)."', '".addslashes($analog_in_1)."', '".addslashes($analog_in_2)."', '".addslashes($analog_in_3)."', '".addslashes($analog_in_4)."', '".addslashes($rfid)."', '".addslashes($fuel_percent)."', '".addslashes($temperature)."', '".addslashes($hw_version)."', '".addslashes($sw_version)."', '$current_area_id', '$current_landmark_id', '$data_type')";
			//$rs = mysql_query($sql) or die("SQL : $sql, Error : " . mysql_error());
			//die("Data Received for $unit_no");
		}

		list($day, $month, $year, $hour, $min, $sec) = array(substr($date,0,2), substr($date,2,2), substr($date,4), substr($time,0,2), substr($time,2,2), substr($time,4,2));
		
		$ist = $gmt = date(DATE_TIME,mktime($hour, $min, $sec, $month, $day, $year));

		//$ist = date(DATE_TIME,strtotime($gmt . " +5 hours 30 minutes"));
		
		if(gmdate(DATE, strtotime($ist)) == '1970-01-01'){
			$ist = CURRENT_TIME;
		}
				
		// $minutes_diff = round(abs(strtotime(CURRENT_TIME) - strtotime($ist)) / 60,2);
		
		$check_alert = true;
		
		if($data_type == 1)	{ 		//off line data
			$check_alert = false;
			$is_insert = false;
		}
		
		//check for last point
		$sql = "SELECT fuel_in, fuel_out, address, add_date, speed, odometer, lati, longi, fuel_percent, fuel_liter FROM tbl_last_point WHERE device_id = '".addslashes($unit_no)."'";
		$rs = mysql_query($sql);
		$lastRowCount = mysql_num_rows($rs);
		if(mysql_num_rows($rs) > 0){
			
			$lRow = mysql_fetch_array($rs);
			
			$fuel_in  = $lRow['fuel_in'];
			$fuel_out = $lRow['fuel_out'];
			
			$last_lat 		= $lRow['lati'];
			$last_lng 		= $lRow['longi'];
//			$lastTime 		= $lRow['add_date'];
			$lastSpeed 		= $lRow['speed'];
			$last_odometer 	= $lRow['odometer'];
			$last_address 	= $lRow['address'];
			$x_fuel 		= $lRow['fuel_percent'];
			$lastFuelReading= $lRow['fuel_liter'];
			$last_time 		= $lRow['add_date'];
			$timeFirst  = strtotime($last_time);
			$timeSecond = strtotime($ist);
			$differenceInSeconds = $timeSecond - $timeFirst;
			if($differenceInSeconds < 60){
				//die('Data Received before 60 sec');
			}
			
			if($timeSecond < $timeFirst && $data_type == 0){
				//WriteLog("Old Data Received of Prev Date : $last_time, Current Date $ist Reason : $reason, Device : $unit_no, Data Type : $data_type, Data String : $input_data");
				die('Old Data Received of Date : '.$ist);
			}
			
			$distance_travelled = 0;
			if($last_odometer != ""){
				$distance_travelled = floatval(($odometer - $last_odometer) / 1000);
				// $add_per = $distance_travelled * 0.1;
				// $distance_travelled = $distance_travelled + $add_per;				
			}
			$currentTime = CURRENT_TIME;

			/*$minutes = round(abs(strtotime($currentTime) - strtotime($lastTime)) / 60,2);
			
			if($minutes <= 10){
				$is_insert = false;
			}*/
//			echo "- " . strtotime($lastTime) . " ---- > ---- " .  strtotime($ist) . " <br />";
/*
			if($data_type == 1){ 	//off line data
				$check_alert = false;
				$is_insert = false;
			}
*/			
		}else{
			$x_address = getNearest($latitude, $longitude);
			
			$lSql = "INSERT INTO tbl_last_point (lati, longi, add_date, speed, device_id, gps, dt, tm, ignition, box_open, altitude, gsm_strength, angle_dir, power_st, address, odometer, temperature) VALUES ('".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($ist)."', '".addslashes($speed)."', '".addslashes($unit_no)."', '".addslashes($gps_fixed)."', '".addslashes($ist)."', '".addslashes($time)."', '".addslashes($ignition)."', '".addslashes($box_open)."', '".addslashes($altitude)."', '".addslashes($gsm_strength)."', '".addslashes($direction)."', '".addslashes($power_cut)."', '".addslashes($x_address)."', '".addslashes($odometer)."', '".addslashes($temperature)."')";
			mysql_query($lSql) or die("SQL : $lSql : Error : " . mysql_error());
		}
		
		//get assets details
		
		$aSql = "SELECT am.id, am.fuel_in_per_lit, am.fuel_out_per_lit, am.add_uid, am.assets_name, am.assets_friendly_nm, am.driver_name, am.max_fuel_capacity, am.max_fuel_liters, am.max_speed_limit, am.current_trip, am.sensor_type, am.min_temprature, am.max_temprature, tm.time_zone, um.sms_enable, um.first_name, um.user_id, um.max_stop_time, um.mobile_number, um.email_address, um.email_alert, um.sms_alert, um.alert_stop_time, um.alert_start_time, um.ignition_on_alert, um.ignition_off_alert, um.ignition_on_speed_off_minutes, um.ignition_off_speed_on_minutes, um.from_date, um.to_date FROM assests_master am left join tbl_users um on um.user_id = am.add_uid left join timezone tm on tm.diff_from_gmt = um.timezone WHERE am.device_id = '".addslashes($unit_no)."' AND am.del_date IS NULL AND am.status = 1";
		$aRs = mysql_query($aSql);
		
		$aRowCount = mysql_num_rows($aRs);
		
		if(! $aRowCount) {
			die('Data Received, But No Assets Defined');
		}
		$aRow = mysql_fetch_array($aRs);
		
		$valid_from  = $aRow['from_date'];
		$valid_to 	 = $aRow['to_date'];
		if(date('Y-m-d H:i:s') < $valid_from || date('Y-m-d H:i:s') > $valid_to){
			die('Data Received, But User Expired');
		}
		
		$fuel_in_per_lit 	 = $aRow['fuel_in_per_lit'];
		$fuel_out_per_lit 	 = $aRow['fuel_out_per_lit'];
		
		$assets_id 	 = $aRow['id'];
		$user_id 	 = $aRow['add_uid'];
		$assets_name = $aRow['assets_name'];
		$nick_name 	 = $aRow['assets_friendly_nm']; 
		$driver_name = $aRow['driver_name'];
		$max_fuel 	 = $aRow['max_fuel_capacity'];
		$max_liters  = $aRow['max_fuel_liters'];
		$speed_limit = $aRow['max_speed_limit'];
		$sensor_type = $aRow['sensor_type'];
		$current_trip = $aRow['current_trip'];
		$min_temp	 = $aRow['min_temprature'];
		$max_temp	 = $aRow['max_temprature'];
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
		
		
		$assets_id_j=$assets_id;
		$fuel_percent= 0;
		$temp_alert = false;
		$fuel_alert = false;
		
		/*$uSql = "select um.first_name, um.user_id, um.max_stop_time, um.mobile_number, um.email_address, um.email_alert, um.sms_alert, um.alert_stop_time, um.alert_start_time, um.ignition_on_alert, um.ignition_off_alert, um.ignition_on_speed_off_minutes, um.ignition_off_speed_on_minutes from tbl_users um left join assests_master am on am.add_uid = um.user_id where am.id = $assets_id";
		$uRs = mysql_query($uSql);		
		$uRow = mysql_fetch_array($uRs);
		*/
		
		$sensors = explode(",",$sensor_type);
		
		if(strtolower($reason) == 's')	{	//harsh break
			$hSql = "insert into harsh_break_report(user_id, assets_id, date_time) values('".$user_id."', '".$assets_id."', '".$ist."')";
			mysql_query($hSql);
		}	
		////////////
		foreach($sensors as $sensor) {
			$sensor = trim($sensor);
			
			if($sensor == 'FUEL' && $reason == "E" && $max_liters != "") {
				//if($reason == "E" && intval($max_fuel) != 0 && intval($analog_in_1) !=0) {
					//get reason from last five records
					/*$sqlF = "select reason from tbl_track where assets_id = '$assets_id' order by id desc limit 0, 5";
					$rsF = mysql_query($sqlF);
					$rArr = array();
					while($rowF = mysql_fetch_array($rsF)){
						$rArr[] = $rowF['reason'];
					}
					if($rArr[0] == 'E' && $rArr[1] == 'E' && $rArr[2] == 'E' && $rArr[3] == 'E' && ($rArr[4] == 'E' || $rArr[4] == 'G')){
						$fuel_percent = floatval(($analog_in_1 * 100) / $max_fuel);
						$fuel_alert = true;
					}*/
					$fuel_in_ltr = $analog_in_4 / 100;
					$fuel_percent = floatval(($fuel_in_ltr * 100) / $max_liters);
					if($fuel_percent > 100)
						$fuel_percent = 100;
					$fuel_alert = true;
				//}
			}
			
			if($sensor == 'TEMPERATURE') {
				$temperature = floatval($analog_in_4);
				$temp_alert = true;
			}
		}
		
		//check for alert sending time
		
		$send_alert_flag = true;
		if($alert_start_time != "" && $alert_stop_time != ""){
			if(time() < strtotime($alert_start_time) && time() > strtotime($alert_stop_time)){
				$send_alert_flag = false;
			}
		}
				
		$rfid = str_replace('!', '', $rfid);
		
		if($check_alert == true){
			
			if((strtoupper($reason) == 'Q') || (strtoupper($reason) == 'R')) {
			
				if($command_key == 43 || $command_key == 44){
					$sql = "select id from landmark where add_uid = $user_id and name = '$command_key_value' and del_date is null and status = 1 order by id desc limit 1";
					$rs = mysql_query($sql);
					$row = mysql_fetch_array($rs);
					$landmark_id = $row['id'];
					
					$sql = "select max(landmark_index) + 1 as landmark_index from assets_landmark where assets_id = $assets_id";
					$rs = mysql_query($sql);
					$row = mysql_fetch_array($rs);
					$landmark_index = $row['landmark_index'];
					if($landmark_index == ""){
						$landmark_index = 1;
					}
					$sql = "select * from assets_landmark where assets_id = '$assets_id' and landmark_id = '$landmark_id'";
					$rs = mysql_query($sql);
					if(mysql_num_rows($rs) == 0){
						$alSql = "INSERT INTO assets_landmark(assets_id, landmark_index, landmark_id, add_date) VALUES ( '".$assets_id."', '".$landmark_index."', '".$landmark_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";				
						mysql_query($alSql);
					}
				}else{
					$smsText = "Command Reply : $command_key = $command_key_value";
				
					//insert in alert master
					$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ( 'Command Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";
					
					mysql_query($alertSql);
				}
			}
			if(strtoupper($reason) == 'I') {
				$geofence_name = $command_key_value;
				if($command_key == 1){
					$geofence_status = '1';
				}else{
					$geofence_status = '0';
				}
			}
			
			if($sensor != 'FUEL' && $last_lat == $latitude && $last_lng == $longitude && $unit_no != 9761) {
				$lSql = "UPDATE tbl_last_point SET add_date = '".addslashes($ist)."' WHERE device_id = '".addslashes($unit_no)."'";
				mysql_query($lSql) or die("SQL : $lSql : Error : " . mysql_error());

				$tSql = "UPDATE tbl_track SET to_date = '".addslashes($ist)."', point_counter = (point_counter + 1) WHERE device_id = '".addslashes($unit_no)."' ORDER BY id DESC LIMIT 1";
				mysql_query($tSql) or die("SQL : $lSql : Error : " . mysql_error());
				
				stop_report_insert($speed, $unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $ist);
				
				ignitionAlert($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $ist);
				
				$fSub = '';
				$total_used_fuel = 0;
				if (strpos($command_key_value,'I') !== false){
					$fSub = ", fuel_in = '$command_key_value'";
					
					$fuel_in = $command_key_value;
					
					
				}else if(strpos($command_key_value,'O') !== false){
					$fSub = ", fuel_out = '$command_key_value'";					
					
					$fuel_out = $command_key_value;	//$result['out'];
					
					$fuel_in = preg_replace("/[^0-9,.]/", "", $fuel_in);
					$in_fuel = $fuel_in * 2;
					$in_fuel = $in_fuel/$fuel_in_per_lit;					
					$out_fuel = preg_replace("/[^0-9,.]/", "", $fuel_out);	//$result['out'];
					$out_fuel = $out_fuel/$fuel_out_per_lit;
					$total_used_fuel = round($in_fuel-$out_fuel,2);
					$last_used_fuel = $total_used_fuel - $lastFuelReading;
					
				}
				if($fSub != ""){
					$prev_user_date = date('Y-m-d', strtotime($user_date."-1 day"));
					$query1 = "select command_key_value from tbl_track where device_id = '$unit_no' and date(CONVERT_TZ(add_date,'+00:00','+05:30')) = '$prev_user_date' and command_key_value like '%I%' order by id desc limit 1";
					$rs1 = mysql_query($query1) or die(mysql_error());
					$row1 = mysql_fetch_array($rs1);
					$in_1 = $row1['command_key_value'];
					$in_1 = preg_replace("/[^0-9,.]/", "", $in_1);
					
					$query2 = "select command_key_value from tbl_track where device_id = '$unit_no' and date(CONVERT_TZ(add_date,'+00:00','+05:30')) = '$prev_user_date' and command_key_value like '%O%' order by id desc limit 1";
					$rs2 = mysql_query($query2) or die(mysql_error());
					$row2 = mysql_fetch_array($rs2);
					$out_1 = $row2['command_key_value'];
					$out_1 = preg_replace("/[^0-9,.]/", "", $out_1);
					
					$in_1 = $in_1 * 2;
					$in_1 = $in_1/$fuel_in_per_lit;
					$out_1 = $out_1/$fuel_out_per_lit;
					$diff_1 = $in_1 - $out_1;
					
					$in_fuel = $fuel_in * 2;
					$in_fuel = $in_fuel/$fuel_in_per_lit;
					
					$out_fuel = preg_replace("/[^0-9,.]/", "", $fuel_out);
					$out_fuel = $out_fuel/$fuel_out_per_lit;
					$diff_2 = $in_fuel-$out_fuel;
					$fuel_used_new = $diff_2 - $diff_1; 
					$query = "UPDATE distance_master SET fuel_used = '".$fuel_used_new."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
					mysql_query($query);
				}
				if($lastRowCount > 0){
					$x_address = $last_address;
					$lSql = "UPDATE tbl_last_point set lati='".addslashes($latitude)."', longi='".addslashes($longitude)."', add_date='".addslashes($ist)."', speed='".addslashes($speed)."', old_speed = '".$lastSpeed."', device_id='".addslashes($unit_no)."', gps= '".addslashes($gps_fixed)."', dt='".addslashes($ist)."', tm='".addslashes($time)."', ignition='".addslashes($ignition)."', box_open='".addslashes($box_open)."', altitude='".addslashes($altitude)."', gsm_strength='".addslashes($gsm_strength)."', angle_dir='".addslashes($direction)."', power_st='".addslashes($power_cut)."', address='".addslashes($x_address)."', data_type = '".$data_type."', cross_speed = '".$cross_speed."', odometer = '".addslashes($odometer)."', temperature = '".addslashes($temperature)."', reason_text = '".addslashes($reason_text)."', input_data = '".$input."', reason = '".addslashes($reason)."', command_key = '".addslashes($command_key)."', command_key_value = '".addslashes($command_key_value)."', msg_key = '".addslashes($msg_key)."', sat_mode = '".addslashes($sat_mode)."', gps_fixed = '".addslashes($gps_fixed)."', gsm_register = '".addslashes($gsm_register)."', gprs_register = '".addslashes($gprs_register)."', server_avail = '".addslashes($server_avail)."', in_batt = '".addslashes($in_batt)."', ext_batt_volt = '".addslashes($ext_batt_volt)."', digital_io = '".addslashes($digital_io)."', analog_in_1 = '".addslashes($analog_in_1)."', analog_in_2 = '".addslashes($analog_in_2)."', analog_in_3 = '".addslashes($analog_in_3)."', analog_in_4 = '".addslashes($analog_in_4)."'";
					
					$lSql .= $fSub;
					if($total_used_fuel != 0){
						$query = "UPDATE distance_master SET fuel_used_new = fuel_used_new + '".$last_used_fuel."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
						mysql_query($query);
						$lSql .= ", fuel_liter = '".$total_used_fuel."', fuel_time = '".addslashes($ist)."'";
					}
					$lSql .= " WHERE device_id = '".addslashes($unit_no)."'";
					
					mysql_query($lSql) or die("SQL : $lSql : Error : " . mysql_error());
				}
				
				checkTemperature(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $temperature, $min_temp, $max_temp, $ist);
				if($unit_no != "9921"){
					die('Data Received for Same Loation');
				}
			}
			//$x_address = getAddress($latitude, $longitude);
			$x_address = getNearest($latitude, $longitude);
			/*if($x_nearest != ""){
				$x_address .= "(".$x_nearest.")";
			}*/
			checkIgnitionOnSpeedOff($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $reason, $lastSpeed, $latitude, $longitude, $x_address, $ist);
			
			checkIgnitionOffSpeedOn($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $reason, $lastSpeed, $latitude, $longitude, $x_address, $ist);
			
			if($is_insert == false) {
				$is_insert = area_in_out(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $speed, $ist);
			}
			else {
				area_in_out(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $speed, $ist);
			}
			
			if($is_insert == false) {
				$is_insert = checkSpeed(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, $speed_limit, $speed, $ist);
			}
			else {
				checkSpeed(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, $speed_limit, $speed, $ist);			
			}
			if(strtoupper($reason) != 'I'){
				if($is_insert == false) {
					$is_insert = checkLandmark(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist, $odometer);
				}
				else {
					checkLandmark(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist, $odometer);
				}
			}else{
				geofenceLog(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist, $odometer, $geofence_name, $geofence_status);
			}
			if($is_insert == false) {
				$is_insert = checkRoute(addslashes($unit_no), $assets_id, $current_trip, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist);
			}
			else {
				checkRoute(addslashes($unit_no), $assets_id, $current_trip, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist);
			}
			
			if($is_insert == false) {
				$is_insert = boxOpen(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $box_open, $ist);
			}
			else {
				boxOpen(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $box_open, $ist);
			}
			if($is_insert == false && $temp_alert == true) {
				
				$is_insert = checkTemperature(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $temperature, $min_temp, $max_temp, $ist);
			}
			else if($temp_alert == true) {
				checkTemperature(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude),addslashes($longitude), $temperature, $min_temp, $max_temp, $ist);
			}
			
			/*if($is_insert == false && $fuel_alert == true && $reason == "E" && $fuel_percent != 0) {
				$is_insert = checkFuel($fuel_in_ltr, addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $fuel_percent, $x_fuel, $ist);
			}
			else if($fuel_alert == true && $reason == "E" && $fuel_percent != 0) {
				checkFuel($fuel_in_ltr, addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $fuel_percent, $x_fuel, $ist);
			}*/

			//start stop trip report generate
			startStopTrip(addslashes($unit_no), $assets_id, $assets_name, $nick_name, $driver_name, addslashes($latitude), addslashes($longitude), $speed, $ist, $distance_travelled);
		}
		if($is_insert == true){
			//update km in assets_master
			$update = "update assests_master set km_reading = km_reading + $distance_travelled where id = ".$assets_id;
			mysql_query($update);
			
			$distance = 0;
			
			$user_date = convert_time_zone($ist, $dts, 'Y-m-d');
			$sql = "SELECT current_reading FROM distance_master WHERE assets_id = '".addslashes($assets_id)."' AND `add_date` = '".$user_date."'";
			$rs = mysql_query($sql);
			
			if(mysql_num_rows($rs) > 0){
				
				$d_row = mysql_fetch_assoc($rs);
				$last_odometer = $d_row['current_reading'];
				$temp_dist = '';
				if($last_odometer < $odometer) {
					$distance = floatval(($odometer - $last_odometer) / 1000);
					// $add_per = $distance * 0.1;
					// $distance = $distance + $add_per;
					$temp_dist = ", distance = distance + ".$distance;
					
				}
				
				$query = "UPDATE distance_master SET distance = ($odometer - first_reading)/1000, current_reading = '".addslashes($odometer)."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
				
			}else{
				$sql = "SELECT current_reading FROM distance_master WHERE assets_id = '".addslashes($assets_id)."' order by add_date desc limit 1";
				$rs = mysql_query($sql);
				if(mysql_num_rows($rs) > 0){
					$d_row = mysql_fetch_assoc($rs);
					$last_odometer = $d_row['current_reading'];
					$temp_dist = '';
					if($last_odometer < $odometer) {
						$distance = floatval(($odometer - $last_odometer) / 1000);
						// $add_per = $distance * 0.1;
						// $distance = $distance + $add_per;
					}
				}else{
					$last_odometer = $odometer;
				}
				$query = "INSERT INTO distance_master (`assets_id`, `add_date`, `first_reading`, `current_reading`, `distance`) VALUES ('".addslashes($assets_id)."', '".$user_date."', '".addslashes($last_odometer)."', '".addslashes($odometer)."', $distance)";
				
			}			
			mysql_query($query);
						
			$sql = "INSERT INTO tbl_track (assets_id, lati, longi, add_date, speed, device_id, gps, dt, tm, ignition, box_open, altitude, gsm_strength, angle_dir, power_st, address, msg_serial_no, reason, reason_text, command_key, command_key_value, msg_key, odometer, sat_mode, gsm_register, gprs_register, server_avail, in_batt, ext_batt_volt, digital_io, analog_in_1, analog_in_2, analog_in_3, analog_in_4, rfid, fuel_percent, temperature, hw_version, sw_version, current_area_id, current_landmark_id, data_type) VALUES ($assets_id, '".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($ist)."', '".addslashes($speed)."', '".addslashes($unit_no)."', '".addslashes($gps_fixed)."', '".addslashes($ist)."', '".addslashes($time)."', '".addslashes($ignition)."', '".addslashes($box_open)."', '".addslashes($altitude)."', '".addslashes($gsm_strength)."', '".addslashes($direction)."', '".addslashes($power_cut)."', '".addslashes($x_address)."', '".addslashes($msg_serial_no)."', '".addslashes($reason)."', '".addslashes($reason_text)."', '".addslashes($command_key)."', '".addslashes($command_key_value)."', '".addslashes($msg_key)."', '".addslashes($odometer)."', '".addslashes($sat_mode)."', '".addslashes($gsm_register)."', '".addslashes($gprs_register)."', '".addslashes($server_avail)."', '".addslashes($in_batt)."', '".addslashes($ext_batt_volt)."', '".addslashes($digital_io)."', '".addslashes($analog_in_1)."', '".addslashes($analog_in_2)."', '".addslashes($analog_in_3)."', '".addslashes($analog_in_4)."', '".addslashes($rfid)."', '".addslashes($fuel_percent)."', '".addslashes($temperature)."', '".addslashes($hw_version)."', '".addslashes($sw_version)."', '$current_area_id', '$current_landmark_id', '$data_type')";
	
			$track_res = mysql_query($sql) or die(mysql_error().":".$sql);
			
			if($lastRowCount > 0){
				$fSub = '';
				$total_used_fuel = 0;
				if (strpos($command_key_value,'I') !== false){
					$fSub = ", fuel_in = '$command_key_value'";
					
					$fuel_in = preg_replace("/[^0-9,.]/", "", $command_key_value);
					
				}else if(strpos($command_key_value,'O') !== false){
					$fSub = ", fuel_out = '$command_key_value'";
					
					$fuel_out = preg_replace("/[^0-9,.]/", "", $command_key_value);
					
					$fuel_in = preg_replace("/[^0-9,.]/", "", $fuel_in);
					$in_fuel = $fuel_in * 2;
					$in_fuel = $in_fuel/$fuel_in_per_lit;
					
					$out_fuel = preg_replace("/[^0-9,.]/", "", $fuel_out);
					$out_fuel = $out_fuel/$fuel_out_per_lit;
					$total_used_fuel = round($in_fuel-$out_fuel,2);
					$last_used_fuel = $total_used_fuel - $lastFuelReading;
				}
				if($fSub != ""){
					$prev_user_date = date('Y-m-d', strtotime($user_date."-1 day"));
					$query1 = "select command_key_value from tbl_track where device_id = '$unit_no' and date(CONVERT_TZ(add_date,'+00:00','+05:30')) = '$prev_user_date' and command_key_value like '%I%' order by id desc limit 1";
					$rs1 = mysql_query($query1) or die(mysql_error());
					$row1 = mysql_fetch_array($rs1);
					$in_1 = $row1['command_key_value'];
					$in_1 = preg_replace("/[^0-9,.]/", "", $in_1);
					
					$query2 = "select command_key_value from tbl_track where device_id = '$unit_no' and date(CONVERT_TZ(add_date,'+00:00','+05:30')) = '$prev_user_date' and command_key_value like '%O%' order by id desc limit 1";
					$rs2 = mysql_query($query2) or die(mysql_error());
					$row2 = mysql_fetch_array($rs2);
					$out_1 = $row2['command_key_value'];
					$out_1 = preg_replace("/[^0-9,.]/", "", $out_1);
					
					$in_1 = $in_1 * 2;
					$in_1 = $in_1/$fuel_in_per_lit;
					$out_1 = $out_1/$fuel_out_per_lit;
					$diff_1 = $in_1 - $out_1;
					
					$in_fuel = $fuel_in * 2;
					$in_fuel = $in_fuel/$fuel_in_per_lit;
					
					$out_fuel = preg_replace("/[^0-9,.]/", "", $fuel_out);
					$out_fuel = $out_fuel/$fuel_out_per_lit;
					$diff_2 = $in_fuel-$out_fuel;
					$fuel_used_new = $diff_2 - $diff_1; 
					$query = "UPDATE distance_master SET fuel_used = '".$fuel_used_new."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
					mysql_query($query);
				}
				$lSql = "UPDATE tbl_last_point set lati='".addslashes($latitude)."', longi='".addslashes($longitude)."', add_date='".addslashes($ist)."', speed='".addslashes($speed)."', old_speed = '".$lastSpeed."', device_id='".addslashes($unit_no)."', gps= '".addslashes($gps_fixed)."', dt='".addslashes($ist)."', tm='".addslashes($time)."', ignition='".addslashes($ignition)."', box_open='".addslashes($box_open)."', altitude='".addslashes($altitude)."', gsm_strength='".addslashes($gsm_strength)."', angle_dir='".addslashes($direction)."', power_st='".addslashes($power_cut)."', address='".addslashes($x_address)."', data_type = '".$data_type."', cross_speed = '".$cross_speed."', odometer = '".addslashes($odometer)."', temperature = '".addslashes($temperature)."', reason_text = '".addslashes($reason_text)."', input_data = '".$input."', reason = '".addslashes($reason)."', command_key = '".addslashes($command_key)."', command_key_value = '".addslashes($command_key_value)."', msg_key = '".addslashes($msg_key)."', sat_mode = '".addslashes($sat_mode)."', gps_fixed = '".addslashes($gps_fixed)."', gsm_register = '".addslashes($gsm_register)."', gprs_register = '".addslashes($gprs_register)."', server_avail = '".addslashes($server_avail)."', in_batt = '".addslashes($in_batt)."', ext_batt_volt = '".addslashes($ext_batt_volt)."', digital_io = '".addslashes($digital_io)."', analog_in_1 = '".addslashes($analog_in_1)."', analog_in_2 = '".addslashes($analog_in_2)."', analog_in_3 = '".addslashes($analog_in_3)."', analog_in_4 = '".addslashes($analog_in_4)."'";
				
				$lSql .= $fSub;
				if($total_used_fuel != 0){
					$query = "UPDATE distance_master SET fuel_used_new = fuel_used_new + '".$last_used_fuel."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
					mysql_query($query);
					$lSql .= ", fuel_liter = '".$total_used_fuel."', fuel_time = '".addslashes($ist)."'";
				}	
				$lSql .= ", current_landmark = '".$current_landmark."'";
				
				$lSql .= ", current_area = '".$current_area."'"; 
				
				if($fuel_alert == true && $fuel_percent != 0){					
					
					$lSql .= ", fuel_liter = '".$fuel_in_ltr."', fuel_percent = '".$fuel_percent."', fuel_time = '".addslashes($ist)."'";
					
					$fuel_used = ($lastFuelReading - $fuel_in_ltr);
					$fuel_used_percent = floatval(($fuel_used * 100) / $max_fuel);
					if($fuel_used < -10){
						$query = "UPDATE distance_master SET fuel_filled = fuel_filled + '".intval($fuel_used)."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
						mysql_query($query);
					}else{
						$query = "UPDATE distance_master SET fuel_used = fuel_used + '".$fuel_used."' WHERE assets_id = '".addslashes($assets_id)."' and `add_date` = '".$user_date."'";
						mysql_query($query);
					}						
						
					$sqlFrIn = "INSERT INTO fuel_report (assets_id, km_run, fuel_reading, reading_diff, fuel_percent, fuel_litters, add_date, latitude, longitude) values('$assets_id', '".addslashes($$distance)."', '".$fuel_in_ltr."', '".$fuel_used."', '".$fuel_used_percent."', '".$fuel_used."', '".addslashes($ist)."', '".addslashes($latitude)."', '".addslashes($longitude)."')";
					mysql_query($sqlFrIn);
					
					if($distance == 0 && $fuel_litters > 1){
						//insert in alert master
						$alert_text = round($fuel_litters, 2)." Ltr Fuel Dropout from vehicle $assets_name";
						$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Fuel Alert', '".$alert_text."', 'alert', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";							
						//mysql_query($alertSql);
					}
				
					$fLog = "INSERT INTO fuel_log (assets_id, odometer, fuel_reading, fuel_percent, fuel_liters, add_date, latitude, longitude) values('$assets_id', '".addslashes($odometer)."', '".$analog_in_1."', '".$fuel_percent."', '".$fuel_in_ltr."', '".addslashes($ist)."', '".addslashes($latitude)."', '".addslashes($longitude)."')";
					mysql_query($fLog);					
					
				}
				$lSql .= " WHERE device_id = '".addslashes($unit_no)."'";
				mysql_query($lSql) or die("SQL : $lSql : Error : " . mysql_error());
			}/*else{
				$lSql = "INSERT INTO tbl_last_point (lati, longi, add_date, speed, device_id, gps, dt, tm, ignition, box_open, altitude, gsm_strength, angle_dir, power_st, address, cross_speed, fuel_percent, odometer, temperature, reason_text) VALUES ('".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($ist)."', '".addslashes($speed)."', '".addslashes($unit_no)."', '".addslashes($gps_fixed)."', '".addslashes($ist)."', '".addslashes($time)."', '".addslashes($ignition)."', '".addslashes($box_open)."', '".addslashes($altitude)."', '".addslashes($gsm_strength)."', '".addslashes($direction)."', '".addslashes($power_cut)."', '".addslashes($x_address)."', '".addslashes($cross_speed)."', '".addslashes($fuel_percent)."', '".addslashes($odometer)."', '".addslashes($temperature)."', '".addslashes($reason_text)."')";
			}*/
			
			
			if(trim($rfid != '') && trim($rfid != 'OK')) {
				rfid_data(addslashes($rfid), addslashes($unit_no), $x_address);
			}
		}
		
		if($check_alert){
			//stop report generate
			stop_report_insert($speed, $unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $ist);
			
			ignitionAlert($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $ist);
		}
		
	}

	if(! $track_res) {
		$output = 'Data Received, But Failed to Log Data';
	}
	else {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = ($endtime - $starttime);
		insertDataRecievedLog($totaltime,$device_id_j,$assets_id_j,$RecievedBytes);
		$output = 'Data Received';
	}
	echo $output;
	
	function startStopTrip($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $lati, $longi, $current_speed, $ist, $distance_travelled){
		
		global $dts;
		
		$sqlP = "SELECT trip.*, um.user_id, um.first_name, um.mobile_number, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, am.driver_name, am.km_reading FROM tbl_routes trip LEFT JOIN tbl_users um ON um.user_id = trip.add_uid left join assests_master am on am.current_trip = trip.id WHERE am.id = $assets_id and trip.del_date IS NULL AND trip.status = 1";
		
		$rsP = mysql_query($sqlP) or die("Failed to Execute, SQL : $sqlP, Error : " . mysql_error());
		if(mysql_num_rows($rsP) > 0){
			$rowP = mysql_fetch_array($rsP);
			$isCheckForLastPoint = true;
			
			$user_id 	= $rowP['user_id'];
			$fname 		= $rowP['first_name'];
			$mobile 	= $rowP['mobile_number'];
			$email 		= $rowP['email_address'];
			$user_email_alert 	= $rowP['user_email_alert'];
			$user_sms_alert 	= $rowP['user_sms_alert'];
			$driver_name 	= $rowP['driver_name'];
			$km_reading 	= $rowP['km_reading'];
			
			$trip_id = $rowP['id'];
			$trip_name = $rowP['routename'];
			$total_time_in_minutes = $rowP['total_time_in_minutes'];
			$landmark_ids = $rowP['landmark_ids'];
			$landmark_ids = explode(",", $landmark_ids);
			$start_point = $landmark_ids[0];
			$end_point = end($landmark_ids);
			
			//check for start location
			$sqlL = "SELECT * FROM landmark WHERE id = $start_point";
			$rsL = mysql_query($sqlL);
			$rowL = mysql_fetch_array($rsL);
			
			$distance_unit = $rowL['distance_unit'];
			$distance_value = $rowL['radius'];
			
			if($distance_unit == "Mile"){
				$unit = "Mile";
			}else{
				$unit = "K";
			}
			$distanceFromLandmark = getDistance($lati, $longi, $rowL['lat'], $rowL['lng'], $unit);
			
			
			if($distance_unit == "Meter")
				$distanceFromLandmark = $distanceFromLandmark * 1000;
				
			
			if($distanceFromLandmark > $distance_value){ 
				$sql = "select * from trip_log where trip_id = $trip_id and device_id = $assets_id order by id desc limit 1";
				$rs = mysql_query($sql);
				$trip_start_alert = false;
				if(mysql_num_rows($rs) > 0){
					$row = mysql_fetch_array($rs);
					if($row['is_complete'] == 1){
						
						$ins = "insert into trip_log(trip_id, device_id, driver_name, start_km_reading, start_time) values($trip_id, $assets_id, '$driver_name', '$km_reading', '".$ist."')";
						mysql_query($ins);
						$trip_start_alert = true;
						
						//update next landmark
						$next_trip_landmark = $landmark_ids[1];
						$nextLSql = "update assests_master set next_trip_landmark = $next_trip_landmark where id = $assets_id";
						mysql_query($nextLSql);
						
					}else{
						$update = "update trip_log set distance_travelled = distance_travelled + $distance_travelled where id = ".$row['id'];
						mysql_query($update);
					}
				}else{
					$ins = "insert into trip_log(trip_id, device_id, driver_name, start_km_reading, start_time) values($trip_id, $assets_id, '$driver_name', '$km_reading', '".$ist."')";
					
					mysql_query($ins);
					$trip_start_alert = true;
					
					//update next landmark
					$next_trip_landmark = $landmark_ids[1];
					$nextLSql = "update assests_master set next_trip_landmark = $next_trip_landmark where id = $assets_id";
					mysql_query($nextLSql);
				}
				if($trip_start_alert == true){
					//send alert to sub users
					$sql = "select um.first_name, um.mobile_number, um.email_address, um.email_alert, um.sms_alert from tbl_users um left join user_assets_map uam on uam.user_id = um.user_id where FIND_IN_SET( $assets_id, uam.assets_ids ) and um.del_date is null and um.status = 1 and um.user_id";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						$fname 					= $row['first_name'];
						$mobile 				= $row['mobile_number'];
						$email 					= $row['email_address'];
						$user_email_alert 		= $row['email_alert'];
						$user_sms_alert 		= $row['sms_alert'];
						
						$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) started trip $trip_name on time ". convert_time_zone($ist, $dts, DISP_TIME);
					
						$template_id = '4130';
						$f1 = $fname;
						$f2 = $assets_name;
						$f3 = $nick_name;
						$f4 = $driver_name;
						$f5 = $trip_name;
						$f6 = "on time ". convert_time_zone($ist, $to_tz, DISP_TIME); // date(DISP_TIME, strtotime($ist));
						$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6);
						
						if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1){
							send_sms($mobile, $smsText, $template_id, $template_data);
							sms_log($mobile, $smsText, $user_id);
						}						
						if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
							send_email($email, "From Nkonnect Infoway", $smsText);
							email_log($email, $smsText, $user_id, 'Trip Start Alert');
							chat_alert($email, $smsText);
						}
						//insert in alert master
						$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Trip Start Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";						
						mysql_query($alertSql);
					}
				}
			}
			/*else{
				$sql = "select * from trip_log where trip_id = $trip_id and device_id = $assets_id order by id desc limit 1";
				$rs = mysql_query($sql);
				if(mysql_num_rows($rs) > 0){
					$row = mysql_fetch_array($rs);
					if($row['is_complete'] == 0){
						$upd = "update trip_log set is_complete = 1 where id = ".$row['id'];
						mysql_query($upd);
						if($start_point == $end_point){
							
							$ins = "update trip_log set end_km_reading = '$km_reading', end_time = '".$ist."' where id = '".$row['id']."'";
							mysql_query($ins);
							
							$unsetTripSql = "UPDATE assests_master SET current_trip='null', next_trip_landmark = 'null' WHERE id=$assets_id";
							mysql_query($unsetTripSql);
							
							$isCheckForLastPoint = false;
							
							//create sms template						
							$trip_minutes = round(abs(strtotime($ist) - strtotime($row['start_time'])) / 60,2);
													
							$time_taken = sec2HourMinute($trip_minutes * 60);
							
							$f1 = $fname;
							$f2 = $assets_name;
							$f3 = $nick_name;
							$f4 = $driver_name;
							$f5 = $trip_name;
							if($trip_minutes > $total_time_in_minutes && $total_time_in_minutes != ""){
								
								//Dear [F1], [F2] ([F3], [F4]) has completed [F5] trip in [F6], [F7] Late
								$late_minutes = $trip_minutes - $total_time_in_minutes;
								$late = sec2HourMinute($late_minutes * 60);
								$smsText .= "$late Late.";
								
								$template_id = '3829';
								$f6 = $time_taken;
								$f7 = $late;
								
								$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) has completed $trip_name trip in $time_taken, $late Late, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
							}else{
								
								//Dear [F1], [F2] ([F3], [F4]) has completed the [F5] trip in [F6][F7]
								$template_id = '3828';
								
								list($f6, $f7) = str_split($time_taken, 30);
								if($f7 == '')	$f7 = ' ';
								
								$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) has completed $trip_name trip in $time_taken, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
							}
							$f8 = ",". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
							$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7, "F8"=>$f8);						
							//
							
							if($mobile != "" && $user_sms_alert == 1){
								send_sms($mobile, $smsText, $template_id, $template_data);
								sms_log($mobile, $smsText, $user_id);
							}
							
							if($email!="" && $user_email_alert ==1) {
								send_email($email, "From Nkonnect Infoway", $smsText);
								email_log($email, $smsText, $user_id, 'Trip completed Alert');
								chat_alert($email, $smsText);
							}
							
							//insert in alert master
							$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Trip Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";
							
							mysql_query($alertSql);
						}
					}
				}
			}*/
			if($isCheckForLastPoint == true){
				//check for end location
				$sqlL = "SELECT * FROM landmark WHERE id = $end_point";
				$rs = mysql_query($sqlL);
				$row = mysql_fetch_array($rs);
				
				$distance_unit = $row['distance_unit'];
				$distance_value = $row['radius'];
				
				if($distance_unit == "Mile"){
					$unit = "Mile";
				}else{
					$unit = "K";
				}
				$distanceFromLandmark = getDistance($lati, $longi, $row['lat'], $row['lng'], $unit);
				if($distance_unit == "Meter")
					$distanceFromLandmark  = $distanceFromLandmark * 1000;
				if($distanceFromLandmark < $distance_value){
					$sql = "select * from trip_log where trip_id = $trip_id and device_id = $assets_id order by id desc limit 1";
					$rs = mysql_query($sql);
					if(mysql_num_rows($rs) > 0){
						$row = mysql_fetch_array($rs);
						if($row['start_time'] != "" && $row['end_time'] == ""){
							
							$ins = "update trip_log set end_km_reading = '$km_reading', end_time = '".$ist."' where id = '".$row['id']."'";
							mysql_query($ins);
							
							$unsetTripSql = "UPDATE assests_master SET current_trip='null', next_trip_landmark = 'null' WHERE id=$assets_id";
							mysql_query($unsetTripSql);
							
							//create sms template
							$trip_minutes = round(abs(strtotime($ist) - strtotime($row['start_time'])) / 60,2);
													
							$time_taken = sec2HourMinute($trip_minutes * 60);
																					
							$f1 = $fname;
							$f2 = $assets_name;
							$f3 = $nick_name;
							$f4 = $driver_name;
							$f5 = $trip_name;
							if($trip_minutes > $total_time_in_minutes && $total_time_in_minutes != ""){
								
								//Dear [F1], [F2] ([F3], [F4]) has completed [F5] trip in [F6], [F7] Late
								$late_minutes = $trip_minutes - $total_time_in_minutes;
								$late = sec2HourMinute($late_minutes * 60);
								$smsText .= "$late Late.";
								
								$template_id = '3829';
								$f6 = $time_taken;
								$f7 = $late;
								
								$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) has completed $trip_name trip in $time_taken, $late Late, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
								
							}else{
								$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) has completed $trip_name trip in $time_taken, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
								//Dear [F1], [F2] ([F3], [F4]) has completed the [F5] trip in [F6][F7]
								$template_id = '3828';
								
								list($f6, $f7) = str_split($time_taken, 30);
								if($f7 == '')	$f7 = ' ';
								$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) has completed $trip_name trip in $time_taken, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
								
							}
							$f8 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
							$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7, "F8"=>$f8);
							//
							
							if($mobile != "" && $user_sms_alert == 1){
								
								send_sms($mobile, $smsText, $template_id, $template_data);
								sms_log($mobile, $smsText, $user_id);
							}							
							if($email!="" && $user_email_alert ==1) {
								send_email($email, "From Nkonnect Infoway", $smsText);
								email_log($email, $smsText, $user_id, 'Trip Complete at Last Location Alert');
								chat_alert($email, $smsText);
							}
							//insert in alert master
							$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Trip Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
							mysql_query($alertSql);
						}
					}
				}
			}
		}
	}
	function area_in_out($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $longitude_x, $latitude_y, $current_speed, $ist){
		global $current_area, $current_area_id, $dts;
		
		$insert_data = false;
		
		
		$sqlP = "SELECT DISTINCT (am.polyid) AS area_id, am.out_alert, am.in_alert, am.speed_value, am.speed_unit, am.email_alert as email_alert, am.sms_alert as sms_alert, um.user_id, um.first_name, um.username, um.mobile_number, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, (SELECT group_concat(mobile_no) as mobile_no FROM addressbook where find_in_set(id, am.addressbook_ids)) as addressbook_mobile FROM areas am LEFT JOIN tbl_users um ON um.user_id = am.Audit_Enter_uid WHERE FIND_IN_SET( $assets_id, deviceid ) and am.Audit_Del_Dt is null and am.Audit_Status = 1";

		$rsP = mysql_query($sqlP) or die("Failed to Execute, SQL : $sqlP, Error : " . mysql_error());
		while($rowP = mysql_fetch_array($rsP)){
			$area_id 	= $rowP['area_id'];
			$user_id 	= $rowP['user_id'];
			$fname 		= $rowP['first_name'];
			$mobile 	= $rowP['mobile_number'];
			$email 		= $rowP['email_address'];
			$user_email_alert 	= $rowP['user_email_alert'];
			$user_sms_alert 	= $rowP['user_sms_alert'];
			$area_email_alert 	= $rowP['email_alert'];
			$area_sms_alert 	= $rowP['sms_alert'];
			$l_speed 	= $rowP['speed_value'];
			$l_unit  	= $rowP['speed_unit'];
			$out_alert  = $rowP['out_alert'];
			$in_alert  	= $rowP['in_alert'];
			
			$addressbook_ids 	= $rowP['addressbook_ids'];
			
			if($l_speed) $current_speed = convertSpeed($current_speed, $l_unit);
			
			if($current_speed > $l_speed) {
				
			}
			
			$sql = "SELECT * FROM areas WHERE polyid = $area_id";
			$rs = mysql_query($sql) or die("Failed to Execute, SQL : $sql, Error : " . mysql_error());
			$vertices_x = array();
			$vertices_y = array();
			while($row = mysql_fetch_array($rs)){
				$vertices_x[] = $row['lat'];
				$vertices_y[] = $row['lng'];
				$area_name = $row['polyname'];
			}

			//$vertices_x = array(22.304732, 22.304573, 22.315134, 22.315809); // x-coordinates of the vertices of the polygon
			//$vertices_y = array(70.763755,70.77178,70.761781,70.771737); // y-coordinates of the vertices of the polygon
			$points_polygon = count($vertices_x); // number vertices

			//$longitude_x = $_GET["longitude"]; // x-coordinate of the point to test
			//$latitude_y = $_GET["latitude"]; // y-coordinate of the point to test


			//// For testing.  This point lies inside the test polygon.
			// $longitude_x = 37.62850;
			// $latitude_y = -77.4499;
			
			$sql = "SELECT * FROM area_inout_log where area_id = $area_id and device_id = $assets_id order by id desc limit 1";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs) > 0){ 
				$row = mysql_fetch_array($rs);
				if($row['inout_status'] == 'in')
					$status = 1;
				else
					$status = 0;
			}else{
				$status = 0;
			}
			if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
				//echo "In polygon!";
				$current_area = $area_name;
				$current_area_id = $area_id;
				if($status == 0){
					
					$areaLogSql = "insert into area_inout_log (user_id, device_id, area_id, lat, lng, date_time, inout_status) values($user_id, '".$assets_id."', '".$area_id."', '".$longitude_x."', '".$latitude_y."', '".gmdate(DATE_TIME)."', 'in')";
					mysql_query($areaLogSql);
					$insert_data = true;
					
					$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) is now in area $area_name, ". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
					//sms template
					//Dear  [F1],  [F2] ( [F3],  [F4]) is now in area  [F5]
					$template_id = '3822';
					$f1 = $fname;
					$f2 = $assets_name;
					$f3 = $nick_name;
					$f4 = $driver_name;
					$f5 = $area_name;
					$f6 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
					$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6);
					
					//
					
					if($mobile != "" && $user_sms_alert == 1 && $area_sms_alert == 1 && $in_alert == 1){
						send_sms($mobile, $smsText, $template_id, $template_data);
						sms_log($mobile, $smsText, $user_id);
					}
					
					if($email!="" && $user_email_alert ==1 && $area_email_alert ==1 && $in_alert == 1) {
						send_email($email, "From Nkonnect Infoway", $smsText);
						email_log($email, $smsText, $user_id, 'In Area Alert to User');
						chat_alert($email, $smsText);
					}
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					//insert in alert master
					$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Area In Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
					mysql_query($alertSql);
				}		
			}
			else{
				//echo "Is not in polygon";
				if($status == 1){
					
					$areaLogSql = "INSERT INTO area_inout_log (user_id, device_id, area_id, lat, lng, date_time, inout_status) VALUES ($user_id, '".$assets_id."', '".$area_id."', '".$longitude_x."', '".$latitude_y."', '".gmdate(DATE_TIME)."', 'out')";
					mysql_query($areaLogSql);
					$insert_data = true;
					
					$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) is now out of area $area_name, ". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
					
					
					//sms template
					//Dear [F1], [F2] ([F3], [F4]) is now out of area [F5]
					$template_id = '3823';
					$f1 = $fname;
					$f2 = $assets_name;
					$f3 = $nick_name;
					$f4 = $driver_name;
					$f5 = $area_name;
					$f6 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
					$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6);
										
					if($mobile != "" && $user_sms_alert == 1 && $area_sms_alert ==1 && $out_alert == 1){
						send_sms($mobile, $smsText, $template_id, $template_data);						
						sms_log($mobile, $smsText, $user_id);
					}
					
					if($email!="" && $user_email_alert ==1 && $area_email_alert ==1 && $out_alert == 1) {
						send_email($email, "From Nkonnect Infoway", $smsText);
						email_log($email, $smsText, $user_id, 'Out of Area alert to User');
						chat_alert($email, $smsText);
					}
					
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);					
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);		
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					//insert in alert master
					$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Area Out Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
					mysql_query($alertSql);
				}		
			}
		 }
		 return $insert_data;
	}
	function geofenceLog($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $lati, $longi, $current_speed, $ist, $odometer, $geofence_name, $geofence_status){
		
		global $current_landmark, $dts;
		$insert_data = false;
				
		$sqlP = "SELECT lm.*, um.first_name, um.mobile_number, um.user_id, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, um.alert_stop_time, (SELECT group_concat(mobile_no) as mobile_no FROM addressbook where find_in_set(id, lm.addressbook_ids)) as addressbook_mobile FROM landmark lm left join tbl_users um on um.user_id = lm.add_uid WHERE lm.name = '$geofence_name' and lm.add_uid = (select add_uid from assests_master where id = $assets_id) and lm.del_date is null and lm.status = 1 order by lm.id desc";
		
		$rs = mysql_query($sqlP);
		$row = mysql_fetch_array($rs);
			
		$distance_value 		= $row['radius'];
		
		if($distance_value == ""){
			continue;
		}
		
		$landmark_id 			= $row['id'];
		$dealer_code 			= $row['comments'];
		$landmark_name 			= $row['name'];
		$distance_unit 			= $row['distance_unit'];
		$user_id 				= $row['user_id'];
		$fname 					= $row['first_name'];
		$mobile 				= $row['mobile_number'];
		$email 					= $row['email_address'];
		$user_email_alert 		= $row['user_email_alert'];
		$user_sms_alert 		= $row['user_sms_alert'];
		$landmark_email_alert 	= $row['email_alert'];
		$landmark_sms_alert 	= $row['sms_alert'];
		$alert_start_time 		= $row['alert_start_time'];
		$alert_stop_time		= $row['alert_stop_time'];
		if($distance_unit == "Mile"){
			$unit = "Mile";
		}else{
			$unit = "K";
		}
		$send_sms_now = true;
		if($alert_start_time != "" && $alert_stop_time != ""){
			if(time() < strtotime($alert_start_time) && time() > strtotime($alert_stop_time)){
				$send_sms_now = false;
			}
		}
		$distanceFromLandmark = getDistance($lati, $longi, $row['lat'], $row['lng'], $unit);
		
		if($distance_unit == "Meter")
			$distanceFromLandmark  = $distanceFromLandmark * 1000;

		
		if($geofence_status == 1){	//"Device is near to Landmark"			
				
			$distanceText = number_format($distanceFromLandmark, 2)." ".$distance_unit;
			
			$checkLast1 = "select landmark_id, odometer from landmark_log where device_id = $assets_id order by id desc limit 1";
			$checkRs1 = mysql_query($checkLast1);
			$checkRow1 = mysql_fetch_array($checkRs1);
			$last_landmark_id = $checkRow1['landmark_id'];
			$last_odometer = $checkRow1['odometer'];
			$distance_from_last = ($odometer - $last_odometer)/1000;
			$distance_from_last += $distanceFromLandmark;
			
			$ins = "INSERT INTO landmark_log(device_id, landmark_id, date_time, lat, lng, distance, in_out, odometer, last_landmark_id, distance_from_last) VALUES 	($assets_id, $landmark_id, '".gmdate(DATE_TIME)."', '$lati', '$longi', '$distanceText', 'in', '$odometer', '$last_landmark_id', '$distance_from_last')";
			mysql_query($ins);
			$insert_data = true;
			
			
			$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME);
	
			if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1){
				send_sms($mobile, $smsText);
				sms_log($mobile, $smsText, $user_id);
			}
			
			if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
				send_email($email, "From Nkonnect Infoway", $smsText);
				email_log($email, $smsText, $user_id, 'Landmark Alert to User');
				chat_alert($email, $smsText);
			}
			
			if($addressbook_mobile != ""){					//send sms addressbook contact
				
				send_sms($addressbook_mobile, $smsText);
				sms_log($addressbook_mobile, $smsText, $user_id);
			}
			
			//insert in alert master
			$alertSql = "INSERT INTO alert_master (alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ('Near Landmark Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
			mysql_query($alertSql);
			
			/****************************/
			//rfid alert
			$sub_sql = "select * from tbl_rfid where del_date is null and status = 1 and landmark_id = $landmark_id";
			$sub_rs = mysql_query($sub_sql);
			while($sub_row = mysql_fetch_array($sub_rs)){
				$person 			= $sub_row['person'];
				$inform_mobile 		= $sub_row['inform_mobile'];
				$inform_email 		= $sub_row['inform_email'];
				$send_sms = $sub_row['send_sms'];
				$send_email 	= $sub_row['send_email'];
				
				$smsText = "Dear $person, $assets_name ($nick_name, $driver_name) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME);
									
				if($inform_mobile != "" && $send_sms == 1 && $landmark_sms_alert == 1){
					send_sms($inform_mobile, $smsText);
					sms_log($mobile, $smsText, $user_id);
				}						
				if($inform_email!="" && $send_email == 1 && $landmark_email_alert == 1) {
					send_email($inform_email, "From Nkonnect Infoway", $smsText);
					email_log($inform_email, $smsText, $user_id, 'RFID alert to Person');
					chat_alert($email, $smsText);
				}
			}
			/*
			//update sub-route
			$tSql = "select trip_id from trip_log where device_id = $assets_id and is_complete = 0 order by id desc limit 1";
			$tRs = mysql_query($tSql);
			if(mysql_num_rows($tRs)){
				$tRow = mysql_fetch_array($tRs);
				$trip_id = $tRow['trip_id'];
				$kSql = "select km_reading from assests_master where id = $assets_id";
				$kRs = mysql_query($kSql);
				$kRow = mysql_fetch_array($kRs);
				$km_reading = $kRow['km_reading'];
				$query = "UPDATE tbl_sub_routes SET is_complete = 1, end_time ='".gmdate(DATE_TIME)."', end_km_reading = '$km_reading', total_km = ('$km_reading' - start_km_reading) WHERE route_id = $trip_id and landmark_ids like '%,$landmark_id' and end_time is null";
				mysql_query($query);
			}
			*/
		}
		if($geofence_status == 0){		//out
			
			$uLSql = "update landmark_log set in_out = 'out' where id = $lId";
			mysql_query($uLSql);
			
			/*
			//update sub-route
			$tSql = "select trip_id from trip_log where device_id = $assets_id and is_complete = 0 order by id desc limit 1";
			$tRs = mysql_query($tSql);
			if(mysql_num_rows($tRs) > 0){
				$tRow = mysql_fetch_array($tRs);
				$trip_id = $tRow['trip_id'];
				$query = "update tbl_sub_routes set start_time = '".$ist."', start_km_reading = (select km_reading from assests_master where id = $assets_id) WHERE route_id = $trip_id and landmark_ids like '$lId,%' and start_time is null";
				mysql_query($query);
			}
			*/
		}
		return $insert_data;
	}
	function checkLandmark($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $lati, $longi, $current_speed, $ist, $odometer){
		
		global $current_landmark, $current_landmark_id, $dts;
		$insert_data = false;
		
		$sql = "select group_concat(landmark_id) as device_landmark from assets_landmark where assets_id = '$assets_id'";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$device_landmark = $row['device_landmark'];
		
		$sqlP = "SELECT lm.*, um.first_name, um.mobile_number, um.user_id, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, um.alert_stop_time, (SELECT group_concat(mobile_no) as mobile_no FROM addressbook where find_in_set(id, lm.addressbook_ids)) as addressbook_mobile FROM landmark lm left join tbl_users um on um.user_id = lm.add_uid WHERE FIND_IN_SET( $assets_id, lm.device_ids ) and lm.del_date is null and lm.status = 1";
		if($device_landmark != ""){
			$sqlP .= " and lm.id not in($device_landmark)";
		}
		$rs = mysql_query($sqlP);
		while($row = mysql_fetch_array($rs)){
			$distance_value 		= $row['radius'];
			$alert_before_landmark ="";
			if($row['alert_before_landmark']!="")
				$alert_before_landmark = floatval($distance_value+$row['alert_before_landmark']);

			if($distance_value == ""){
				continue;
			}
			
			$landmark_id 			= $row['id'];
			$dealer_code 			= $row['comments'];
			$landmark_name 			= $row['name'];
			$distance_unit 			= $row['distance_unit'];
			$user_id 				= $row['user_id'];
			$fname 					= $row['first_name'];
			$mobile 				= $row['mobile_number'];
			$email 					= $row['email_address'];
			$user_email_alert 		= $row['user_email_alert'];
			$user_sms_alert 		= $row['user_sms_alert'];
			$landmark_email_alert 	= $row['email_alert'];
			$landmark_sms_alert 	= $row['sms_alert'];
			$alert_start_time 		= $row['alert_start_time'];
			$alert_stop_time		= $row['alert_stop_time'];
			if($distance_unit == "Mile"){
				$unit = "Mile";
			}else{
				$unit = "K";
			}
			$send_sms_now = true;
			if($alert_start_time != "" && $alert_stop_time != ""){
				if(time() < strtotime($alert_start_time) && time() > strtotime($alert_stop_time)){
					$send_sms_now = false;
				}
			}
			$distanceFromLandmark = getDistance($lati, $longi, $row['lat'], $row['lng'], $unit);
			
			if($distance_unit == "Meter")
				$distanceFromLandmark  = $distanceFromLandmark * 1000;

			
			if($distanceFromLandmark < $distance_value){	//"Device is near to Landmark"
				
				$checkLast = "select landmark_id, in_out from landmark_log where device_id = $assets_id and landmark_id = $landmark_id order by id desc limit 1";
				$checkRs = mysql_query($checkLast);
				$checkRow = mysql_fetch_array($checkRs);
				$lastLandmarkId = $checkRow['landmark_id'];
				$inOutStatus = $checkRow['in_out'];
				//if($landmark_id != $lastLandmarkId){		//check for second time
				
				$current_landmark = $landmark_name;	
				$current_landmark_id = $landmark_id;	
				
				if(!mysql_num_rows($checkRs)){		//check for first time log
					$inOutStatus = 'out';
				}
				if($inOutStatus == 'out'){	
					
					$distanceText = number_format($distanceFromLandmark, 2)." ".$distance_unit;
					
					$checkLast1 = "select landmark_id, odometer from landmark_log where device_id = $assets_id order by id desc limit 1";
					$checkRs1 = mysql_query($checkLast1);
					$checkRow1 = mysql_fetch_array($checkRs1);
					$last_landmark_id = $checkRow1['landmark_id'];
					$last_odometer = $checkRow1['odometer'];
					$distance_from_last = ($odometer - $last_odometer)/1000;
					$distance_from_last += $distanceFromLandmark;
					
					$ins = "INSERT INTO landmark_log(device_id, landmark_id, date_time, lat, lng, distance, in_out, odometer, last_landmark_id, distance_from_last) VALUES 	($assets_id, $landmark_id, '".gmdate(DATE_TIME)."', '$lati', '$longi', '$distanceText', 'in', '$odometer', '$last_landmark_id', '$distance_from_last')";
					mysql_query($ins);
					$insert_data = true;
					
					
					$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
					//sms template
					//Dear [F1], [F2] ([F3], [F4]) is near [F5] (Distance is [F6])
			
					$template_id = '3824';
					$f1 = $fname;
					$f2 = $assets_name;
					$f3 = $nick_name;
					$f4 = $driver_name;
					$f5 = $landmark_name;
					$f6 = $distanceText;
					$f7 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
					$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7);
					
					if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1){
						send_sms($mobile, $smsText, $template_id, $template_data);
						sms_log($mobile, $smsText, $user_id);
					}
					
					if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
						send_email($email, "From Nkonnect Infoway", $smsText);
						email_log($email, $smsText, $user_id, 'Landmark Alert to User');
						chat_alert($email, $smsText);
					}
					
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					
					//insert in alert master
					$alertSql = "INSERT INTO alert_master (alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ('Near Landmark Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
					mysql_query($alertSql);
					
					/****************************/
					//rfid alert
					$sub_sql = "select * from tbl_rfid where del_date is null and status = 1 and landmark_id = $landmark_id";
					$sub_rs = mysql_query($sub_sql);
					while($sub_row = mysql_fetch_array($sub_rs)){
						$person 			= $sub_row['person'];
						$inform_mobile 		= $sub_row['inform_mobile'];
						$inform_email 		= $sub_row['inform_email'];
						$send_sms = $sub_row['send_sms'];
						$send_email 	= $sub_row['send_email'];
						
						$smsText = "Dear $person, $assets_name ($nick_name, $driver_name) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME);
											
						if($inform_mobile != "" && $send_sms == 1 && $landmark_sms_alert == 1){
							send_sms($inform_mobile, $smsText);
							sms_log($mobile, $smsText, $user_id);
						}						
						if($inform_email!="" && $send_email == 1 && $landmark_email_alert == 1) {
							send_email($inform_email, "From Nkonnect Infoway", $smsText);
							email_log($inform_email, $smsText, $user_id, 'RFID alert to Person');
							chat_alert($email, $smsText);
						}
					}
					/****************************/
					
					if($dealer_code != ""){										
						//send alert to dealer
						$sub_sql = "select um.first_name, um.mobile_number, um.email_address, um.email_alert, um.sms_alert from tbl_users um where um.username = '$dealer_code' and um.user_id <> $user_id";
						$sub_rs = mysql_query($sub_sql);
						while($sub_row = mysql_fetch_array($sub_rs)){
							$dealer_id 			= $sub_row['user_id'];
							$dealer_fname 		= $sub_row['first_name'];
							$dealer_mobile 		= $sub_row['mobile_number'];
							$dealer_email 		= $sub_row['email_address'];
							$dealer_email_alert = $sub_row['email_alert'];
							$dealer_sms_alert 	= $sub_row['sms_alert'];
							
							$smsText = "Dear $dealer_fname, $assets_name ($nick_name, $driver_name) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
						
							$template_id = '3824';
							$f1 = $dealer_fname;
							$f2 = $assets_name;
							$f3 = $nick_name;
							$f4 = $driver_name;
							$f5 = $landmark_name;
							$f6 = $distanceText;
							$f7 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
							$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7);
							
							if($dealer_mobile != "" && $dealer_sms_alert == 1 && $landmark_sms_alert == 1){
								send_sms($mobile, $smsText, $template_id, $template_data);
								sms_log($mobile, $smsText, $user_id);
							}						
							if($dealer_email!="" && $dealer_email_alert == 1 && $landmark_email_alert == 1) {
								send_email($email, "From Nkonnect Infoway", $smsText);
								email_log($email, $smsText, $user_id,'send Landmark alert to Dealer');
								chat_alert($email, $smsText);
							}
						}
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Near Landmark Alert', '".$smsText."', 'alert', '".$dealer_id."', '".$assets_id."', '".$ist."')";
						mysql_query($alertSql);
						
					}
					
					//update sub-route
					/*$tSql = "select trip_id from trip_log where device_id = $assets_id and is_complete = 0 order by id desc limit 1";
					$tRs = mysql_query($tSql);
					if(mysql_num_rows($tRs)){
						$tRow = mysql_fetch_array($tRs);
						$trip_id = $tRow['trip_id'];
						$kSql = "select km_reading from assests_master where id = $assets_id";
						$kRs = mysql_query($kSql);
						$kRow = mysql_fetch_array($kRs);
						$km_reading = $kRow['km_reading'];
						
						$query = "UPDATE tbl_sub_routes SET is_complete = 1, end_time ='".gmdate(DATE_TIME)."', end_km_reading = '$km_reading', total_km = ('$km_reading' - start_km_reading) WHERE route_id = $trip_id and landmark_ids like '%,$landmark_id' and end_time is null";
						mysql_query($query);
					*/
						//check for skip dealer
						/*$query = "select * from tbl_sub_routes where route_id = $trip_id and landmark_ids like '%,$landmark_id' order by id desc limit 1";
						$vRs = mysql_query($query);
						if(mysql_num_rows($vRs) > 0){
							$vRow = mysql_fetch_array($vRs);
							$lastShoudbe = explode(",",$vRow['landmark_ids']);
							$lastShoudbe = $lastShoudbe[0];
							if($lastLandmarkId != $lastShoudbe){
								//dealer skiped 
								$ldSql = "select name from landmark where id = $lastShoudbe";
								$ldRs = mysql_query($ldSql);
								$ldRow = mysql_fetch_array($ldRs);
								$should_landmark_name = $ldRow['name'];
								
								$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) skip dealer $should_landmark_name, ". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
																
								if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1){
									send_sms($mobile, $smsText, $template_id, $template_data);
									sms_log($mobile, $smsText, $user_id);
								}
								
								if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
									send_email($email, "From Nkonnect Infoway", $smsText);
									chat_alert($email, $smsText);
								}																
								//insert in alert master
								$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Landmark Skip Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
								mysql_query($alertSql);
							}
						}
						*/
					//}
				}
			}else{		//out
				$checkLast = "select id from landmark_log where device_id = $assets_id and landmark_id = $landmark_id and in_out = 'in' order by id desc limit 1";
				$checkRs = mysql_query($checkLast);
				
				if(mysql_num_rows($checkRs) > 0){
					$checkRow = mysql_fetch_array($checkRs);
					$lId = $checkRow['id'];
					
					//update next landmark
					$sqlAss = "select trip.landmark_ids, am.current_trip, am.next_trip_landmark from assests_master am left join tbl_routes trip on trip.id = am.current_trip where am.id = $assets_id";
					$rsAss = mysql_query($sqlAss);
					$rowAss = mysql_fetch_array($rsAss);
					if($rowAss['current_trip'] != "" && $rowAss['current_trip'] != 0){
						$landmark_ids = explode(",",$rowAss['landmark_ids']);
						if(in_array($landmark_id, $landmark_ids)){
							$lorder = array_search($landmark_id, $landmark_ids) + 1;
							$next_trip_landmark = $landmark_ids[$lorder];
							$nextLSql = "update assests_master set next_trip_landmark = $next_trip_landmark where id = $assets_id";
							mysql_query($nextLSql);
						}
					}
					
					$uLSql = "update landmark_log set in_out = 'out', out_time = '".$ist."' where id = $lId";
					mysql_query($uLSql);
					
					//update sub-route
					$tSql = "select trip_id from trip_log where device_id = $assets_id and is_complete = 0 order by id desc limit 1";
					$tRs = mysql_query($tSql);
					if(mysql_num_rows($tRs) > 0){
						$tRow = mysql_fetch_array($tRs);
						$trip_id = $tRow['trip_id'];
						$query = "update tbl_sub_routes set start_time = '".$ist."', start_km_reading = (select km_reading from assests_master where id = $assets_id) WHERE route_id = $trip_id and landmark_ids like '$lId,%' and start_time is null";
						mysql_query($query);
					}
					
					if($dealer_code != ""){
						//update user assets mapping, remove assets id
						$sub_sql = "select * from user_assets_map where user_id = (select user_id from tbl_users where username = '$dealer_code')";
						$sub_rs = mysql_query($sub_sql);
						if(mysql_num_rows($tRs) > 0){
							$sub_row = mysql_fetch_array($sub_rs);
							$uam_id = $sub_row['id'];
							$assetsIds = $sub_row['assets_ids'];
							$assetsIds = str_replace("$assets_id", "", $assetsIds);
							$assetsIds = str_replace(",,", ",", $assetsIds);
							$assetsIds = trim($assetsIds, ",");
							
							$sql = "update user_assets_map set assets_ids = '$assetsIds' where id = '$uam_id'";
							mysql_query($sql);
							
						}
					}
				}
			}
			
			if($device_id==99999 && $alert_before_landmark!=""){
			//WriteLog("CheckLandmark : $distanceFromLandmark < $alert_before_landmark) - Landmark: $landmark_name - Assets : $assets_id ($device_id), Lat: $lati, Long: $longi, LandarkLat: ".$row['lat'].", LandarkLng:".$row['lng']."-testing");
			$distanceText = number_format($distanceFromLandmark, 2)." ".$distance_unit;
			if($distanceFromLandmark < $alert_before_landmark){	//"Device is near to Landmark"
				$checkLast = "select landmark_id from landmark_distance_log where device_id = $assets_id order by id desc limit 1";
				$checkRs = mysql_query($checkLast);
				$numRows=mysql_num_rows($checkRs);
				
				$checkRow = mysql_fetch_array($checkRs);
				$lastLandmarkId = $checkRow['landmark_id'];
				if(($lastLandmarkId != $landmark_id && $dealer_code != "") || ($numRows==0)){
				
					$ins = "insert into landmark_distance_log(device_id, landmark_id, date_time, distance) values($assets_id, $landmark_id, '".date('Y-m-d H:i:s', strtotime($ist))."', '$distanceText')";
					mysql_query($ins);
														
					//send alert to dealer
					/*$sub_sql = "select um.user_id, um.first_name, um.mobile_number, um.email_address, um.email_alert, um.sms_alert, um.alert_start_time, um.alert_stop_time from tbl_users um where um.username = '$dealer_code' limit 1";
					$sub_rs = mysql_query($sub_sql);
					$sub_row = mysql_fetch_array($sub_rs);
					$alert_start_time = $sub_row['alert_start_time'];
					$alert_stop_time = $sub_row['alert_stop_time'];
					
					$dealer_id 			= $sub_row['user_id'];
					$dealer_fname 		= $sub_row['first_name'];
					$dealer_mobile 		= $sub_row['mobile_number'];
					$dealer_email 		= $sub_row['email_address'];
					$dealer_email_alert = $sub_row['email_alert'];
					$dealer_sms_alert 	= $sub_row['sms_alert'];
					*/
					
					$smsText = "Dear $dealer_fname, $assets_name ($driver_name) is $distanceText KM away from Landmark $landmark_name, ".date(DISP_TIME, strtotime($ist));
					
					$emailText = $smsText;
					WriteLog("$smsText");
					/*
					$sfSql = "Select * from `tbl_sms_format_mst` where `sms_alert_name`='Near Landmark' and del_date is null and status=1";
					$sfRs = mysql_query($sfSql);
					$sfRow = mysql_fetch_array($sfRs);
					$smsText = create_sms_text($sfRow["sms_text"], $dealer_fname, $ist);
					$smsText = preg_replace(array('/\[landmark-name\]/','/\[distance-from-landmark\]/'),array($landmark_name,$distanceText),$smsText);
					
					$sfSql = "Select * from `tbl_email_format_mst` where `email_alert_name`='Near Landmark' and del_date is null and status=1";
					$sfRs = mysql_query($sfSql);
					$sfRow = mysql_fetch_array($sfRs);
					$emailSubject = $sfRow['email_subject'];
					$emailText = create_sms_text($sfRow["email_text"], $dealer_fname, $ist);
					$emailText = preg_replace(array('/\[landmark-name\]/','/\[distance-from-landmark\]/'),array($landmark_name,$distanceText),$emailText);
					*-/
					/*if($dealer_mobile != "" && $dealer_sms_alert == 1 && $landmark_sms_alert == 1 && $dealer_sms){
						send_sms($dealer_mobile, $smsText);
						sms_log($dealer_mobile, $smsText, $user_id);
					}						
					if($dealer_email!="" && $dealer_email_alert == 1 && $landmark_email_alert == 1) {
						send_email($dealer_email, $emailSubject, $emailText);
						chat_alert($dealer_email, $smsText);
					}*/
					
					/*if($dealer_code != ""){									
						//send alert to dealer
						$sub_sql = "select um.first_name, um.mobile_number, um.email_address, um.email_alert, um.sms_alert from tbl_users um where um.username = '$dealer_code' and um.user_id <> $user_id";
						$sub_rs = mysql_query($sub_sql);
						while($sub_row = mysql_fetch_array($sub_rs)){
							$dealer_id 			= $sub_row['user_id'];
							$dealer_fname 		= $sub_row['first_name'];
							$dealer_mobile 		= $sub_row['mobile_number'];
							$dealer_email 		= $sub_row['email_address'];
							$dealer_email_alert = $sub_row['email_alert'];
							$dealer_sms_alert 	= $sub_row['sms_alert'];
							
							$smsText = "Dear $dealer_fname, $assets_name ($nick_name, $driver_name) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
						
							$template_id = '3824';
							$f1 = $dealer_fname;
							$f2 = $assets_name;
							$f3 = $nick_name;
							$f4 = $driver_name;
							$f5 = $landmark_name;
							$f6 = $distanceText;
							$f7 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
							$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7);
							
							if($dealer_mobile != "" && $dealer_sms_alert == 1 && $landmark_sms_alert == 1){
								send_sms($mobile, $smsText, $template_id, $template_data);
								sms_log($mobile, $smsText, $user_id);
							}						
							if($dealer_email!="" && $dealer_email_alert == 1 && $landmark_email_alert == 1) {
								send_email($email, "From Nkonnect Infoway", $smsText);
								chat_alert($email, $smsText);
							}
						}
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Near Landmark Alert', '".$smsText."', 'alert', '".$dealer_id."', '".$assets_id."', '".$ist."')";
						mysql_query($alertSql);						
					}*/
					
					if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1 && $send_sms_now){
						send_sms($mobile, $smsText, $template_id, $template_data);
						sms_log($mobile, $smsText, $user_id);
					}
					
					if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
						send_email($email, "From Nkonnect Infoway", $smsText);
						email_log($email, $smsText, $user_id,'Device is near to Landmark');
						chat_alert($email, $smsText);
					}
					
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					$alertSql = "insert into alert_master(assets_id, alert_header, alert_msg, alert_type, user_id, add_date) values ($assets_id, 'Near Landmark Alert', '".$smsText."', 'alert', '".$dealer_id."', '".date('Y-m-d H:i:s', strtotime($ist))."')";
					mysql_query($alertSql);
				}
			}
			}
		}
		return $insert_data;
	}
	function boxOpen($device_id, $ast_id, $assets_name, $nick_name, $driver_name, $longitude_x, $latitude_y, $box_status, $ist){
		
		$insert_data = true;
		global $dts;
		global $uRow;
		$smsText = '';
		$sqlP = "SELECT id, device_id, open_time, closing_time, box_status FROM box_open_log WHERE device_id = $ast_id ORDER BY id DESC LIMIT 0,1";

		$rsP = mysql_query($sqlP) or die("Failed to Execute, SQL : $sqlP, Error : " . mysql_error());
		
		if(mysql_num_rows($rsP)) {
			
			$rowP 		= mysql_fetch_array($rsP);
			
			$id			= $rowP['id'];
			$open_time 	= $rowP['open_time'];
			$close_time	= $rowP['closing_time'];
			$device		= $rowP['device_id'];
			$c_status	= $rowP['box_status'];
			$sms_alert	= 0;
			$slq = '';
			
			//echo "box_status = $box_status, c_status == '$c_status'";
			
			if($box_status == 1 && $c_status == 'closed') {
				// The box was closed and Now the box is Opened
				$sql = "INSERT INTO box_open_log (device_id, open_time, open_lat, open_lng, box_status) VALUES ('".$ast_id."', '".$ist."', '".$longitude_x."', '".$latitude_y."', 'open')";
				$smsText = "The GPS box of $assets_name ($nick_name, $driver_name) is now opened";
				$sms_alert = 1;
				$boxAlertHeading = 'Box Open Alert';
				
			}
			else if($box_status == 0 && $c_status == 'open') {
				echo "Now the Box has Been Closed";
				$sql = "UPDATE box_open_log SET closing_time = '".$ist."', close_lat = '".$longitude_x."', close_lng = '".$latitude_y."', box_status = 'closed' WHERE id = $id";
				$smsText = "The GPS box of $assets_name ($nick_name, $driver_name) is now closed";
				$sms_alert = 1;
				$boxAlertHeading = 'Box Close Alert';
			}
			
			if($sms_alert == 1) {
				$rs = mysql_query($sql);
			}
			
		}
		else {
			if($box_status == 1) {
				// The box has been opened for the first time.
				// The box was closed and Now the box is Opened
				$sql = "INSERT INTO box_open_log (device_id, open_time, open_lat, open_lng, box_status) VALUES ('".$ast_id."', '".$ist."', '".$longitude_x."', '".$latitude_y."', 'open')";
				$smsText = "The GPS box of $assets_name ($nick_name, $driver_name) is now opened";
				$sms_alert = 1;
				$rs = mysql_query($sql);
				
				$boxAlertHeading = 'Box Open Alert';
			}
		}
			
		if($sms_alert) {
						
			$user_id 			= $uRow['user_id'];
			$mobile 			= $uRow['mobile_number'];
			$email 				= $uRow['email_address'];
			$user_email_alert 	= $uRow['email_alert'];
			$user_sms_alert 	= $uRow['sms_alert'];
			$fname 				= $uRow['first_name'];
			
			if($mobile != "" && $user_sms_alert == 1){
				// send_sms($mobile, $smsText);
				sms_log($mobile, $smsText, $user_id);
			}
				
			if($email!="" && $user_email_alert ==1) {
				// send_email($email, "From Nkonnect Infoway", $smsText);
				chat_alert($email, $smsText);
			}
			//insert in alert master
			$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$boxAlertHeading."', '".$smsText."', 'alert', '".$user_id."', '".$ast_id."', '".$ist."')";
			mysql_query($alertSql);
		}
		return $insert_data;
	}
	
	function checkTemperature($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $latitude, $longitude, $temperature, $min, $max, $ist) {
		
		$insert_data = true;
		$smsText = '';
		$tempAlertHeading = '';
		$min_max_temp = '';
		$send_sms = false;
		$update = false;
		$minmaxStat = '';
		global $dts;
		global $uRow;
		if($temperature < $min || $temperature > $max) {
			$new_status = 0;		
		}else{
			$new_status = 1;
		}
		
		$checkLast = "SELECT id, status FROM temperature_log WHERE device_id = $assets_id order by id desc limit 1";
		$checkRs = mysql_query($checkLast);
		$checkRow = mysql_fetch_array($checkRs);
		$last_status = $checkRow['status'];
		$last_id 	 = $checkRow['id'];
		if($last_status == ""){
			$last_status = 1;
		}
		if($new_status == 1 && $last_status == 0){
			$upd = "update temperature_log set status = 1 where id = '$last_id'";
			mysql_query($upd);
		}else if($last_status == 1){
			
			if($temperature < $min && $min != '') {
				
				$smsText = "The Temperature of $assets_name is $temperature C, that has dropped down below the Lowest Point $min C, " . convert_time_zone($ist, $dts, DISP_TIME);
				$send_sms = true;
				$tempAlertHeading = 'Temperature Below Lowest Point';
				$min_max_temp = 'min';
			
			}
			if($temperature > $max && $max != '') {
				
				$smsText = "The Temperature of $assets_name is $temperature C, that has raised above the Highest Point $max C, " . convert_time_zone($ist, $dts, DISP_TIME);
				$send_sms = true;
				$tempAlertHeading = 'Temperature Above Highest Point';
				$min_max_temp = 'max';
			
			}
				
			if($send_sms == true) {
				
				$sql = "INSERT INTO temperature_log (device_id, device_temp, date_time, lat, lng, min_max, min_max_temp, status) VALUES ('".$assets_id."', '".$temperature."', '".$ist."', '".$latitude."', '".$longitude."', '".$min_max_temp."', '".$temperature."', 0)";
				$rs = mysql_query($sql);
								
				$user_id 			= $uRow['user_id'];
				$mobile 			= $uRow['mobile_number'];
				$email 				= $uRow['email_address'];
				$user_email_alert 	= $uRow['email_alert'];
				$user_sms_alert 	= $uRow['sms_alert'];
				$fname 				= $uRow['first_name'];
				
				if($mobile != "" && $user_sms_alert == 1){
					send_sms($mobile, $smsText);
					sms_log($mobile, $smsText, $user_id);
				}
					
				if($email!="" && $user_email_alert ==1) {
					send_email($email, "From Nkonnect Infoway", $smsText);
					email_log($email, $smsText, $user_id,'Temperature is Above or Below');
					chat_alert($email, $smsText);
				}
				//insert in alert master
				$alertSql = "INSERT INTO alert_master (alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$tempAlertHeading."', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
				mysql_query($alertSql);			
			}
		}
		return $insert_data;
	}
	
	function checkFuel($fuel_liter, $unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $latitude, $longitude, $c_fuel, $l_fuel, $ist) {
		
		$insert_data = true;
		$smsText = '';
		$tempAlertHeading = '';
		$min_max_temp = '';
		$send_sms = false;
		global $dts;
		global $uRow;
		$p_diff = floatval($l_fuel - $c_fuel);
		
		if(intval($p_diff) > 15) {
			// If the fuel percentage difference is more than 15 then we must fire an alert to the user.
			$smsText = "The fuel level of $assets_name is $c_fuel, that has dropped down from last reading of $l_fuel.";
			$send_sms = true;
			$tempAlertHeading = 'Fuel dropped Below 15 %';
			$min_max_temp = $min;
			
		}
		
		if($send_sms == true) {
						
			$user_id 			= $uRow['user_id'];
			$mobile 			= $uRow['mobile_number'];
			$email 				= $uRow['email_address'];
			$user_email_alert 	= $uRow['email_alert'];
			$user_sms_alert 	= $uRow['sms_alert'];
			$fname 				= $uRow['first_name'];
			
			if($mobile != "" && $user_sms_alert == 1){
				// send_sms($mobile, $smsText);
				sms_log($mobile, $smsText, $user_id);
			}
				
			if($email!="" && $user_email_alert ==1) {
				// send_email($email, "From Nkonnect Infoway", $smsText);
				chat_alert($email, $smsText);
			}
			//insert in alert master
			$alertSql = "INSERT INTO alert_master (alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ( '".$tempAlertHeading."', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$ist."')";
			mysql_query($alertSql);
			
		}
		return $insert_data;
	}

	function checkRoute($device_id, $assets_id, $current_trip, $assets_name, $nick_name, $driver_name, $lati, $longi, $current_speed, $ist){
		
		$insert_data = false;
		$route_flag	 = false;
		global $current_landmark, $dts;
		
		if($current_trip != ""){
			$sqlP = "SELECT trip.*, um.first_name, um.mobile_number, um.user_id, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert FROM tbl_routes trip left join tbl_users um on um.user_id = trip.userid WHERE trip.del_date is null and trip.status = 1 and trip.id = $current_trip";
			
			$rs = mysql_query($sqlP);
			$row = mysql_fetch_array($rs);
			
			if(mysql_num_rows($rs) > 0 && $row['distance_value'] > 0){
				
				$trip_id 			= $row['id'];
				$distance_unit 		= $row['distance_unit'];
				$distance_value 	= $row['distance_value'];
				$routename 			= $row['routename'];
				$user_id 			= $row['user_id'];
				$fname 				= $row['first_name'];
				$mobile 			= $row['mobile_number'];
				$email 				= $row['email_address'];
				$user_email_alert 	= $row['user_email_alert'];
				$user_sms_alert 	= $row['user_sms_alert'];
				$route_email_alert 	= $row['email_alert'];
				$route_sms_alert 	= $row['sms_alert'];
				$pt 				= $row['points'];
				$total_time_in_minutes = $row['total_time_in_minutes'];
				$landmark_ids = $row['landmark_ids'];
				$landmark_ids = explode(",", $landmark_ids);
				$start_point = $landmark_ids[0];
				if($row['round_trip'] == 1)
					$end_point = $start_point;
				else	
					$end_point = end($landmark_ids);
					
				/*//check for start location
				$sqlL = "SELECT name, radius, distance_unit FROM landmark WHERE id = $start_point";
				$rs = mysql_query($sqlL);
				$row = mysql_fetch_array($rs);
				
				$l_name = $row['name'];
				$l_dist_unit = $row['distance_unit'];
				$l_dist_value = $row['radius'];
				
				if($l_dist_unit == "Mile"){
					$unit = "Mile";
				}else{
					$unit = "K";
				}
				
				$distanceFromLandmark = getDistance($lati, $longi, $row['lat'], $row['lng'], $unit);
				
				if($l_dist_unit == "Meter")
				$distanceFromLandmark = $distanceFromLandmark * 1000;
				
				// If the device is outside the landmark radius of the first landmark, then we have have to check
				// whether the trip is started or not.
				if($distanceFromLandmark > $l_dist_value){
					
					$sql = "SELECT is_complete FROM trip_log WHERE trip_id = $trip_id and device_id = $assets_id order by id desc limit 1";
					$rs = mysql_query($sql);
					$trip_start_alert = false;
					if(mysql_num_rows($rs) > 0){
						$row = mysql_fetch_array($rs);
						if($row['is_complete'] == 1 && $l_name == $current_landmark){
							
							$ins = "INSERT INTO trip_log (trip_id, device_id, start_time) VALUES ($trip_id, $assets_id, '".$ist."')";
							mysql_query($ins);
							$trip_start_alert = true;
							
						}else{
							$update = "UPDATE trip_log SET distance_travelled = distance_travelled + $distance_travelled WHERE id = ".$row['id'];
							mysql_query($update);
							// NOW AS THE TRIP HAS STARTED WE SHOULD SET THE FLAG TO CHECK WHETHER THE POINT IS OUT OR ROUTE
							$route_flag = true;
						}
					}else if($l_name == $current_landmark){
						$ins = "INSERT INTO trip_log(trip_id, device_id, start_time) VALUES ($trip_id, $assets_id, '".$ist."')";
						mysql_query($ins);
						$trip_start_alert = true;
						$route_flag = true;
					}
				}
				*/
				/*
				else {
					$sql = "SELECT is_complete FROM trip_log WHERE trip_id = $trip_id and device_id = $assets_id and is_complete = 0 order by id desc limit 1";
					$rs = mysql_query($sql);
					if(mysql_num_rows($rs) > 0){
						$row = mysql_fetch_array($rs);
						$upd = "update trip_log set is_complete = 1 where id = ".$row['id'];
					}
				}
				*/
				/*
				if(! $route_flag) {
					return $insert_data;
				}
				*/	
				$ref = array($lati, $longi);
				$items = array();
				
				$points = explode(":", $pt);
				
				foreach($points as $point){
					$point = explode(",", $point);
					$items[] = array($point[0],$point[1]);
				}
				$distances = array_map(function($item) use($ref) {				
					$a = array_slice($item, -2);
					return distance($a, $ref);
				}, $items);

				sort($distances);
				$distanceFromRoute = floatval($distances[0]);
					
				
				if($distance_unit == "Meter")
					$distanceFromRoute = ($distanceFromRoute * 1.609344 * 1000);
				if($distance_unit == "KM")
					$distanceFromRoute  = ($distanceFromRoute * 1.609344);
				
				if($distanceFromRoute > $distance_value){	//"Device is away from route"
					$checkLast = "SELECT on_route FROM route_out_log WHERE device_id = $assets_id AND trip_id = $trip_id order by id desc limit 1";
					$checkLastRs = mysql_query($checkLast);
					$checkLastRow = mysql_fetch_array($checkLastRs);
					if(!mysql_num_rows($checkLastRs) || $checkLastRow['on_route'] == 1){
						$distanceText = number_format($distanceFromRoute, 2).' '.$distance_unit;
						$ins = "INSERT INTO route_out_log (device_id, trip_id, date_time, lat, lng, distance, on_route) VALUES ($assets_id, $trip_id, '".gmdate(DATE_TIME)."', '$lati', '$longi', '$distanceText', 0)";
						mysql_query($ins);
						$insert_data = true;
						
						$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) is not on route $routename (Distance from original route is : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($ist));
						
						//sms template
						//Dear [F1], [F2] ([F3], [F4]) is not on route [F5] (Distance from original route is : [F6])
						$template_id = '3826';
						$f1 = $fname;
						$f2 = $assets_name;
						$f3 = $nick_name;
						$f4 = $driver_name;
						$f5 = $routename;
						$f6 = $distanceText;
						$f7 = ",".convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
						$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7);
												
						if($mobile != "" && $user_sms_alert == 1 && $route_sms_alert == 1){
							send_sms($mobile, $smsText, $template_id, $template_data);
							sms_log($mobile, $smsText, $user_id);
						}
						
						if($email!="" && $user_email_alert == 1 && $route_email_alert == 1) {
							send_email($email, "From Nkonnect Infoway", $smsText);
							email_log($email, $smsText, $user_id,'Device is away from route (Route Break)');
							chat_alert($email, $smsText);
						}
						//insert in alert master
						$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ( 'Route Break Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";
						mysql_query($alertSql);
					}
				}else{
					$checkLast = "select on_route from route_out_log where device_id = $assets_id and trip_id = $trip_id order by id desc limit 1";
					$checkLastRs = mysql_query($checkLast);
					$checkLastRow = mysql_fetch_array($checkLastRs);
					if(mysql_num_rows($checkLastRs)> 0 && $checkLastRow['on_route'] == 0){
						$distanceText = number_format($distanceFromRoute, 2).' '.$distance_unit;
						$ins = "INSERT INTO route_out_log (device_id, trip_id, date_time, lat, lng, distance, on_route) VALUES ($assets_id, $trip_id, '".gmdate(DATE_TIME)."', '$lati', '$longi', '$distanceText', 1)";
						mysql_query($ins);
						$insert_data = true;
					}
				}
			}
		}
		return $insert_data;
	}
	
	function checkSpeed($device_id, $ast_id, $assets_name, $nick_name, $driver_name, $max, $current_speed, $ist) {
		global $cross_speed, $dts;
		global $uRow;
		$insert_data = false;
		
		if($current_speed > $max && $max != "" && $max != 0){
			$speedSql = "select date_time from over_speed_report where assets_id = '$ast_id' order by id desc limit 1";
			$speedRs = mysql_query($speedSql);
			$minutes = 11;
			if(mysql_num_rows($speedRs) > 0){
				$speedRow = mysql_fetch_array($speedRs);
				$start = $speedRow['date_time'];
				$minutes = round(abs(strtotime($ist) - strtotime($start)) / 60,2);
			}
			if($minutes > 10){
			
				$user_id 	= $uRow['user_id'];
				$mobile 	= $uRow['mobile_number'];
				$email 		= $uRow['email_address'];
				$user_email_alert 	= $uRow['email_alert'];
				$user_sms_alert 	= $uRow['sms_alert'];
				$fname = $uRow['first_name'];
				$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) cross the maximum speed limit (Speed : $current_speed Km/H), ". convert_time_zone($ist, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($ist));
				//sms template
				//Dear [F1], [F2] ([F3], [F4]), cross the maximum speed limit (Speed : [F5])
				$template_id = '3825';
				$f1 = $fname;
				$f2 = $assets_name;
				$f3 = $nick_name;
				$f4 = $driver_name;
				$f5 = $current_speed." Km/H";
				$f6 = ",". convert_time_zone($ist, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($ist));
				$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6);
				
				//////////
				$cross_speed = 1;
				$insert_data = true;
							
				if($mobile != "" && $user_sms_alert == 1){
					// send_sms($mobile, $smsText, $template_id, $template_data);
					send_sms($mobile, $smsText);
					sms_log($mobile, $smsText, $user_id);
				}
				
				if($email!="" && $user_email_alert == 1) {
					send_email($email, "From Nkonnect Infoway", $smsText);	
					chat_alert($email, $smsText);
				}
				$speedSql = "insert into over_speed_report(user_id, assets_id, max_speed_limit, speed, date_time, comments) values ( '$user_id', '".$ast_id."', '$max', '$current_speed', '".$ist."', '".$smsText."')";
				mysql_query($speedSql);
				
				//insert in alert master
				$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Over Speed Alert', '".$smsText."', 'alert', '".$user_id."', '".$ast_id."', '".$ist."')";
				mysql_query($alertSql);
			}
		}
		return $insert_data;
	}
	
	/*function stop_report_insert($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $current)
	{
		global $current_area;
		global $current_landmark;
		global $send_alert_flag;
		global $dts;
		$ignition_type = '';
		$query = "SELECT id, ignition_off, ignition_on, alert_given FROM tbl_stop_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query);
		if($ignition==0)
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$stop_report_id = $row['id'];
				if(trim($row['ignition_on'] != ""))
				{
					$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
					$ignition_type = "on";
				}
				
				//alert if stop more than given time
				if(trim($row['ignition_on'] == "")){
					$start = strtotime($row['ignition_off']);
					$end = strtotime($current);
					$minutes = round(abs($end - $start) / 60,2);
					
					$uSql = "select um.first_name, um.user_id, um.max_stop_time, um.mobile_number, um.email_address, um.email_alert, um.sms_alert, um.alert_stop_time, um.alert_start_time from tbl_users um left join assests_master am on am.add_uid = um.user_id where am.id = $assets_id";
					$uRs = mysql_query($uSql);
					$uRow = mysql_fetch_array($uRs);
					$user_id = $uRow['user_id'];
					$fname = $uRow['first_name'];
					$mobile = $uRow['mobile_number'];
					$email = $uRow['email_address'];
					$user_sms_alert = $uRow['sms_alert'];
					$user_email_alert = $uRow['email_alert'];
					$alert_start_time = $uRow['alert_start_time'];
					$alert_stop_time = $uRow['alert_stop_time'];
					$max_stop_time = $uRow['max_stop_time'];	//in minutes
										
					//if stop time more than set time and alert not given
					//
					if($max_stop_time != "" && $max_stop_time != 0 && $minutes > $max_stop_time && $row['alert_given'] == 0){						  				
						$stop_time = sec2HourMinute($max_stop_time * 60);
												
						$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) stop more than $stop_time,";
						//sms template
						//STOP LIMIT CROSS	Dear [F1], [F2] ([F3], [F4]) stopped more than [F5][F6][F7]
						$template_id = '3827';
						$f1 = $fname;
						$f2 = $assets_name;
						$f3 = $nick_name;
						$f4 = $driver_name;
						$f5 = " ".$stop_time;
						
						if($current_landmark != ''){
							$smsText .= " near Landmark $current_landmark";
							list($f6, $f7) = str_split(" near Landmark $current_landmark", 30);
							if($f7 == "")	$f7 = " ";
						}else if($current_area != ""){
							$smsText .= " in Area $current_area";
							list($f6, $f7) = str_split(" in Area $current_area", 30);
							if($f7 == "")	$f7 = " ";
						}
						else if($x_address){
							$smsText .= " at $x_address";
							list($f6, $f7) = str_split(" at ".$x_address, 30);
							if($f7 == "")	$f7 = " ";
						}else{
							$f6 = " ";
							$f7 = " ";
						}
						$f8 = ",". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));
						$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7, "F8"=>$f8);	

						$smsText .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));						
						//////////
						
						if($send_alert_flag == true && $mobile != "" && $user_sms_alert == 1){
							send_sms($mobile, $smsText, $template_id, $template_data);
							//send_sms($mobile, $alert_text);
							sms_log($mobile, $smsText, $user_id);
						}
						
						if($email!="" && $user_email_alert == 1) {
							send_email($email, "From Nkonnect Infoway", $smsText);
							email_log($email, $smsText, $user_id,'Vehicle Stop time more than set time');
							chat_alert($email, $smsText);
						}
												
						//update alert given
						$uSql = "update tbl_stop_report set alert_given = 1 where id = $stop_report_id";
						@mysql_query($uSql);
						
						//insert in alert master
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Vehicle Stop Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$current."')";
						mysql_query($alertSql);
					}
				}
				
			}
			else if(mysql_num_rows($res) == 0)
			{
				$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
				$ignition_type = "on";
			}
		}
		else
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$row_id = $row['id'];
				if(trim($row['ignition_on']) == "") {
					
					$start = strtotime($row['ignition_off']);
					$end = strtotime($current);
					$delta = $end - $start;
					$hours = floor($delta / 3600);
					$remainder = $delta - $hours * 3600;
					$formattedDelta = sprintf('%02d', $hours) . gmdate(':i:s', $remainder);
					
					$query="UPDATE tbl_stop_report SET ignition_on = '".$current."', duration='". addslashes($formattedDelta)."', add_date = '".$current."' WHERE device_id = $assets_id AND id = " . $row_id;
					$ignition_type = "off";
				}
			}
		}
		if($query != '') {
			@mysql_query($query);
			
			$uSql = "select um.first_name, um.user_id, um.max_stop_time, um.mobile_number, um.email_address, um.email_alert, um.sms_alert, um.ignition_on_alert, um.ignition_off_alert from tbl_users um left join assests_master am on am.add_uid = um.user_id where am.id = $assets_id";
			$uRs = mysql_query($uSql);
			$uRow = mysql_fetch_array($uRs);
			$user_id = $uRow['user_id'];
			$fname = $uRow['first_name'];
			$mobile = $uRow['mobile_number'];
			$email = $uRow['email_address'];
			$user_sms_alert = $uRow['sms_alert'];
			$user_email_alert = $uRow['email_alert'];
			$ignition_on_alert = $uRow['ignition_on_alert'];
			$ignition_off_alert = $uRow['ignition_off_alert'];									
			if($ignition_type == "on"){
				$ignition_alert = $ignition_on_alert;
				$ignition_alert_text = "Ignition On";
			}else if($ignition_type == "off"){
				$ignition_alert = $ignition_off_alert;
				$ignition_alert_text = "Ignition Off";
			}
			if($ignition_alert == 1){
				$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) $ignition_alert_text";
								
				if($current_landmark != ''){
					$smsText .= " near Landmark $current_landmark";
				}else if($current_area != ""){
					$smsText .= " in Area $current_area";
				}
				else if($x_address){
					$smsText .= " at $x_address";
				}
				
				$smsText .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, 
				
				if($send_alert_flag == true && $mobile != "" && $user_sms_alert == 1){
					send_sms($mobile, $smsText, '', '');
					//send_sms($mobile, $alert_text);
					sms_log($mobile, $smsText, $user_id);
				}
				
				if($email!="" && $user_email_alert == 1) {
					send_email($email, "From Nkonnect Infoway", $smsText);
					email_log($email, $smsText, $user_id, "Vehicle $ignition_alert_text Alert");
					chat_alert($email, $smsText);
				}				
				//insert in alert master
				$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '$ignition_alert_text Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$current."')";
				mysql_query($alertSql);
			}
		}
	}
	*/
	function ignitionAlert($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $current)
	{
		global $current_area;
		global $current_landmark;
		global $send_alert_flag;
		global $dts;
		global $uRow;
		$give_alert = false;
		if($ignition==0){
			$ignition_type = "ignition_off";
		}else{
			$ignition_type = "ignition_on";
		}
		$query = "SELECT id, ignition_status FROM tbl_ignition_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query);
		if(mysql_num_rows($res) == 0)
		{
			$query="INSERT INTO tbl_ignition_report (device_id, ignition_status, date_time, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','ignition_off','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
			@mysql_query($query);
			$give_alert = true;
		}else{
			$row = mysql_fetch_array($res);
			if(trim($row['ignition_status'] != $ignition_type))
			{
				$query="INSERT INTO tbl_ignition_report (device_id, ignition_status, date_time, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','$ignition_type','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
				@mysql_query($query);
				$give_alert = true;
			}
		}
		if($give_alert) {
			
			$user_id = $uRow['user_id'];
			$fname = $uRow['first_name'];
			$mobile = $uRow['mobile_number'];
			$email = $uRow['email_address'];
			$user_sms_alert = $uRow['sms_alert'];
			$user_email_alert = $uRow['email_alert'];
			$ignition_on_alert = $uRow['ignition_on_alert'];
			$ignition_off_alert = $uRow['ignition_off_alert'];									
			if($ignition_type == "ignition_on"){
				$ignition_alert = $ignition_on_alert;
				$ignition_alert_text = "Ignition On";
			}else if($ignition_type == "ignition_off"){
				$ignition_alert = $ignition_off_alert;
				$ignition_alert_text = "Ignition Off";
			}
			if($ignition_alert == 1){
				$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) $ignition_alert_text";
								
				if($current_landmark != ''){
					$smsText .= " near Landmark $current_landmark";
				}else if($current_area != ""){
					$smsText .= " in Area $current_area";
				}
				else if($x_address){
					$smsText .= " at $x_address";
				}
				
				$smsText .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, 
				
				if($send_alert_flag == true && $mobile != "" && $user_sms_alert == 1){
					send_sms($mobile, $smsText, '', '');
					//send_sms($mobile, $alert_text);
					sms_log($mobile, $smsText, $user_id);
				}
				
				if($email!="" && $user_email_alert == 1) {
					send_email($email, "From Nkonnect Infoway", $smsText);
					email_log($email, $smsText, $user_id, "Vehicle $ignition_alert_text Alert");
					chat_alert($email, $smsText);
				}				
				//insert in alert master
				$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '$ignition_alert_text Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$current."')";
				mysql_query($alertSql);
			}
		}
	}
	function stop_report_insert($speed, $unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $latitude, $longitude, $x_address, $current)
	{
		global $current_area;
		global $current_landmark;
		global $send_alert_flag;
		global $uRow;
		$query = "SELECT id, ignition_off, ignition_on, alert_given FROM tbl_stop_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query);
		if($speed==0)
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$stop_report_id = $row['id'];
				if(trim($row['ignition_on'] != ""))
				{
					$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
					@mysql_query($query);
				}
				
				//alert if stop more than given time
				if(trim($row['ignition_on'] == "")){
					$start = strtotime($row['ignition_off']);
					$end = strtotime($current);
					$minutes = round(abs($end - $start) / 60,2);
					
					$user_id = $uRow['user_id'];
					$fname = $uRow['first_name'];
					$mobile = $uRow['mobile_number'];
					$email = $uRow['email_address'];
					$user_sms_alert = $uRow['sms_alert'];
					$user_email_alert = $uRow['email_alert'];
					$alert_start_time = $uRow['alert_start_time'];
					$alert_stop_time = $uRow['alert_stop_time'];
					$max_stop_time = $uRow['max_stop_time'];	//in minutes
										
					//if stop time more than set time and alert not given
					//
					if($max_stop_time != "" && $max_stop_time != 0 && $minutes > $max_stop_time && $row['alert_given'] == 0){						  				
						$stop_time = sec2HourMinute($max_stop_time * 60);
												
						$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) stop more than $stop_time,";
						//sms template
						//STOP LIMIT CROSS	Dear [F1], [F2] ([F3], [F4]) stopped more than [F5][F6][F7]
						$template_id = '3827';
						$f1 = $fname;
						$f2 = $assets_name;
						$f3 = $nick_name;
						$f4 = $driver_name;
						$f5 = " ".$stop_time;
						
						if($current_landmark != ''){
							$smsText .= " near Landmark $current_landmark";
							list($f6, $f7) = str_split(" near Landmark $current_landmark", 30);
							if($f7 == "")	$f7 = " ";
						}else if($current_area != ""){
							$smsText .= " in Area $current_area";
							list($f6, $f7) = str_split(" in Area $current_area", 30);
							if($f7 == "")	$f7 = " ";
						}
						else if($x_address){
							$smsText .= " at $x_address";
							list($f6, $f7) = str_split(" at ".$x_address, 30);
							if($f7 == "")	$f7 = " ";
						}else{
							$f6 = " ";
							$f7 = " ";
						}
						$f8 = ",". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));
						$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7, "F8"=>$f8);	

						$smsText .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));						
						//////////
						
						if($send_alert_flag == true && $mobile != "" && $user_sms_alert == 1){
							send_sms($mobile, $smsText, $template_id, $template_data);
							//send_sms($mobile, $alert_text);
							sms_log($mobile, $smsText, $user_id);
						}
						
						if($email!="" && $user_email_alert == 1) {
							send_email($email, "From Nkonnect Infoway", $smsText);
							email_log($email, $smsText, $user_id,'Vehicle Stop time more than set time');
							chat_alert($email, $smsText);
						}
												
						//update alert given
						$uSql = "update tbl_stop_report set alert_given = 1 where id = $stop_report_id";
						@mysql_query($uSql);
						
						//insert in alert master
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Vehicle Stop Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$current."')";
						mysql_query($alertSql);
					}
				}
				
			}
			else if(mysql_num_rows($res) == 0)
			{
				$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
				@mysql_query($query);
			}
		}
		else
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$row_id = $row['id'];
				if(trim($row['ignition_on']) == "") {
					
					$start = strtotime($row['ignition_off']);
					$end = strtotime($current);
					$delta = $end - $start;
					if($delta > 5){
						$hours = floor($delta / 3600);
						$remainder = $delta - $hours * 3600;
						$formattedDelta = sprintf('%02d', $hours) . gmdate(':i:s', $remainder);
						
						$query="UPDATE tbl_stop_report SET ignition_on = '".$current."', duration='". addslashes($formattedDelta)."', add_date = '".$current."' WHERE device_id = $assets_id AND id = " . $row_id;
						@mysql_query($query);
					}
					
				}
			}
		}
		
	}
	function checkIgnitionOnSpeedOff($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $reason, $last_speed, $latitude, $longitude, $x_address, $current)
	{
		global $send_alert_flag;
		global $dts;
		global $uRow;
		$query = "SELECT * FROM tbl_ignition_on_speed_off WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query);
		if($ignition==1 && $last_speed == 0 && $speed == 0)
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$stop_report_id = $row['id'];
				if(trim($row['motion_start_time'] != ""))
				{
					$query="INSERT INTO tbl_ignition_on_speed_off (device_id, motion_stop_time, address, add_date, lat, lng) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."', '".$current."','".addslashes($latitude)."','".addslashes($longitude)."')";
					
					$alert_ignition = true;
				}
				
			
				//alert if stop more than given time and ignition on
				if(trim($row['motion_start_time'] == "")){
					$start = strtotime($row['motion_stop_time']);
					$end = strtotime($current);
					$minutes = round(abs($end - $start) / 60,2);
					
					$user_id = $uRow['user_id'];
					$fname = $uRow['first_name'];
					$mobile = $uRow['mobile_number'];
					$email = $uRow['email_address'];
					$user_sms_alert = $uRow['sms_alert'];
					$user_email_alert = $uRow['email_alert'];
					$alert_start_time = $uRow['alert_start_time'];
					$alert_stop_time = $uRow['alert_stop_time'];
					$max_stop_time = $uRow['ignition_on_speed_off_minutes'];
										
					//if stop time more than set time and alert not given
					if($max_stop_time != "" && $max_stop_time != 0 && $minutes > $max_stop_time && $row['alert_given'] == 0){						  				
						$stop_time = sec2HourMinute($max_stop_time * 60);
					
						$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) stop more than $stop_time and ignition on";	
						if($email!="" && $user_email_alert == 1) {
							send_email($email, "From Nkonnect Infoway", $smsText);
							email_log($email, $smsText, $user_id,'Vehicle Stop And Ignition On');
							chat_alert($email, $smsText);
						}
												
						//update alert given
						$uSql = "update tbl_ignition_on_speed_off set alert_given = 1 where id = $stop_report_id";
						@mysql_query($uSql);
						
						//insert in alert master
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Vehicle Stop Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$current."')";
						mysql_query($alertSql);
					}
				}
			}
			else if(mysql_num_rows($res) == 0)
			{
				$query="INSERT INTO tbl_ignition_on_speed_off (device_id, motion_stop_time, address, add_date, lat, lng) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."', '".$current."','".addslashes($latitude)."','".addslashes($longitude)."')";
				
			}
		}
		else
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$row_id = $row['id'];
				if(trim($row['motion_start_time']) == "") {
					
					$start = strtotime($row['motion_stop_time']);
					$end = strtotime($current);
					$delta = $end - $start;
					$hours = floor($delta / 3600);
					$remainder = $delta - $hours * 3600;
					if($remainder < 60){
						$query="delete from tbl_ignition_on_speed_off WHERE device_id = $assets_id AND id = " . $row_id;
					}else{
						$formattedDelta = sprintf('%02d', $hours) . gmdate(':i:s', $remainder);
						
						$query="UPDATE tbl_ignition_on_speed_off SET motion_start_time = '".$current."', duration='". addslashes($formattedDelta)."' WHERE device_id = $assets_id AND id = " . $row_id;
					}
					
				}
			}
		}
		if($query != '') {
			@mysql_query($query);
		}
	}
	function checkIgnitionOffSpeedOn($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $reason, $last_speed, $latitude, $longitude, $x_address, $current)
	{
		global $send_alert_flag;
		global $dts;
		global $uRow;
		$query = "SELECT * FROM tbl_ignition_off_speed_on WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query);
		if($ignition==0 && $speed > 0)
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$stop_report_id = $row['id'];
				if(trim($row['ignition_start_time'] != ""))
				{
					$query="INSERT INTO tbl_ignition_off_speed_on (device_id, ignition_stop_time, address, add_date, ignition, speed) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".$current."', '$ignition', '$speed')";
					
				}
				
			
				//alert if running more than given time with ignition off
				if(trim($row['ignition_stop_time'] == "")){
					$start = strtotime($row['ignition_start_time']);
					$end = strtotime($current);
					$minutes = round(abs($end - $start) / 60,2);
					
					$user_id = $uRow['user_id'];
					$fname = $uRow['first_name'];
					$mobile = $uRow['mobile_number'];
					$email = $uRow['email_address'];
					$user_sms_alert = $uRow['sms_alert'];
					$user_email_alert = $uRow['email_alert'];
					$alert_start_time = $uRow['alert_start_time'];
					$alert_stop_time = $uRow['alert_stop_time'];
					$max_stop_time = $uRow['ignition_off_speed_on_minutes'];
										
					//if stop time more than set time and alert not given
					if($max_stop_time != "" && $max_stop_time != 0 && $minutes > $max_stop_time && $row['alert_given'] == 0){						  				
						$stop_time = sec2HourMinute($max_stop_time * 60);
					
						$smsText = "Dear $fname, $assets_name ($nick_name, $driver_name) running more than $stop_time and ignition off";	
						
						if($email!="" && $user_email_alert == 1) {
							send_email($email, "From Nkonnect Infoway", $smsText);
							email_log($email, $smsText, $user_id,'Vehicle Running And Ignition Off');
							chat_alert($email, $smsText);
						}
												
						//update alert given
						$uSql = "update tbl_ignition_off_speed_on set alert_given = 1 where id = $stop_report_id";
						@mysql_query($uSql);
						
						//insert in alert master
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( 'Vehicle Stop Alert', '".$smsText."', 'alert', '".$user_id."', '".$assets_id."', '".$current."')";
						mysql_query($alertSql);
					}
				}
			}
			else if(mysql_num_rows($res) == 0)
			{
				$query="INSERT INTO tbl_ignition_off_speed_on (device_id, ignition_stop_time, address, add_date, ignition, speed) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".$current."', '$ignition', '$speed')";
				
			}
		}
		else
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$row_id = $row['id'];
				if(trim($row['ignition_start_time']) == "") {
					
					$start = strtotime($row['ignition_stop_time']);
					$end = strtotime($current);
					$delta = $end - $start;
					$hours = floor($delta / 3600);
					$remainder = $delta - $hours * 3600;
					$formattedDelta = sprintf('%02d', $hours) . gmdate(':i:s', $remainder);
					
					$query="UPDATE tbl_ignition_off_speed_on SET ignition_start_time = '".$current."', duration='". addslashes($formattedDelta)."' WHERE device_id = $assets_id AND id = " . $row_id;
					
				}
			}
		}
		if($query != '') {
			@mysql_query($query);
		}
	}
	function rfid_data($rfid, $asset, $address) {
		
		global $current_landmark, $current_area, $dts;
		
		if($current_landmark != '' || $current_landmark == 'out') {
			$address = $current_landmark;
		}
		elseif ($current_area != '') {
			$address = $current_area;
		}
		
		$rfid_sql = "SELECT id, person, asset_id, inform_mobile, inform_email, send_sms, send_email FROM tbl_rfid WHERE rfid = '".$rfid."'";
		
		$rfid_res = mysql_query($rfid_sql);
		
		if(! mysql_num_rows($rfid_res)) return ;
		
		$rf_row = mysql_fetch_assoc($rfid_res);
		
		$rfid_id = $rf_row['id'];
		$person = $rf_row['person'];
		$asset_id = $rf_row['asset_id'];
		$sms_to = $rf_row['inform_mobile'];
		$email_to = $rf_row['inform_email'];
		$send_sms = $rf_row['send_sms'];
		$send_email = $rf_row['send_email'];
		
		if($asset_id == '' || $asset_id == NULL) {
			if($send_sms && $sms_to != '') {
				// Sending SMS to the parents of the student who holds the Card
				$smsText = "The student $person is not attached with any bus";
				send_sms($sms_to, $smsText);
				sms_log($mobile, $smsText);
			}
		}

		$as_sql = "SELECT id, assets_name FROM assests_master WHERE device_id = '".$asset."'";
		
		$as_res = mysql_query($as_sql);
		
		if(! mysql_num_rows($as_res)) return ;
		
		$as_row = mysql_fetch_assoc($as_res);
		
		$as_id = $as_row['id'];
		$as_name = $as_row['assets_name'];
		
		if($as_id != $asset_id) {
			if($send_sms == 1 && $sms_to != '') {
				// Sending SMS to the parents of the student who holds the Card

				$smsText = "The student $person is Boarding / Leaving the wrong bus - $as_name";
				send_sms($sms_to, $smsText);
				sms_log($mobile, $smsText);
			}
		}
		else {
		
			$user_sql = "SELECT um.mobile_number, um.user_id, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert FROM user_assets_map uam LEFT JOIN tbl_users um ON um.user_id = uam.user_id WHERE FIND_IN_SET( $asset_id, uam.assets_ids ) and um.usertype_id = 2";
			
			$rs = mysql_query($user_sql);
			
			$row = mysql_fetch_array($rs);
			
			$user_id 			= $row['user_id'];
			$mobile 			= $row['mobile_number'];
			$email 				= $row['email_address'];
			$user_email_alert 	= $row['user_email_alert'];
			$user_sms_alert 	= $row['user_sms_alert'];
			
			$rfid_log = "SELECT id FROM rfid_log WHERE date(add_date) = '".gmdate(DATE)."' AND rfid_id = '".$rfid_id."' ORDER BY id DESC LIMIT 0, 1";
			
			$rfid_res = mysql_query($rfid_log);
			
			if(mysql_num_rows($rfid_res)) {
				$rfid_row = mysql_fetch_array($rfid_res);
				$upd_id = $rfid_row['id'];
				// He is Leaving the Bus 
				$smsText = "The student $person is Leaving the bus at $address";
				$update_log = "UPDATE rfid_log SET assets_id_leaving = '".$asset_id."', leaving_time = '".gmdate(CURRENT_TIME)."', l_address = '".addslashes($address)."' WHERE id = " . $upd_id;
				
			}
			else {
				// He is Baording the Bus for the First Time in a Day
				$smsText = "The student $person is Boarding the bus at $address";
				
				$update_log = "INSERT INTO rfid_log (rfid_id, assets_id_boarding, boarding_time, b_address, add_date) VALUES ('".$rfid_id."', '".$asset_id."', '".gmdate(CURRENT_TIME)."', '".addslashes($address)."', '".gmdate(DATE)."')";				
			}
			
			$update_res = mysql_query($update_log);
			
			if($mobile != "" && $user_sms_alert == 1){
				// Sending SMS to the Pricipal or the admin of the School who is our User
				send_sms($mobile, $smsText);
				sms_log($mobile, $smsText, $user_id);
			}
			
			if($sms_to != '' && $send_sms == 1) {
				// Sending SMS to the parents of the student who holds the Card
				send_sms($sms_to, $smsText);
				sms_log($sms_to, $smsText, $user_id);
			}
			
			if($email !="" && $user_email_alert == 1) {
				// Sending Email to the Pricipal or the admin of the School who is our User
				send_email($email, "From Nkonnect Infoway", $smsText);
				email_log($email, $smsText, $user_id, 'Sending Email to User of RFID');
			}
			
			if($email_to != '' && $send_email == 1) {
				// Sending Email to the Pricipal or the admin of the School who is our User
				send_email($email_to, "From Nkonnect Infoway", $smsText);
				email_log($email_to, $smsText, $user_id,'Sending Email to Informer of RFID');
			}
		}
	}
	
	function sms_log($mobile, $smsText, $user_id = 1) {
		$mobile = explode(",", $mobile);
		$values = array();
		foreach($mobile as $mob){
			$values[] = "($user_id, '$mob', '$smsText', '".gmdate(DATE_TIME)."')";
		}
		$values = implode(",", $values);
		$sqlU = "INSERT INTO smslog (user_id, mobile, sms_text, add_date) VALUES $values";
		mysql_query($sqlU);
	}
	function email_log($emailid, $smsText, $user_id = 1, $desc = "") {
		$emailid = explode(",", $emailid);
		$values = array();
		foreach($emailid as $em){
			$values[] = "($user_id, '$em', '$smsText', '$desc', '".gmdate(DATE_TIME)."')";
		}
		$values = implode(",", $values);
		$sqlU = "INSERT INTO emaillog (user_id, email_id, email_text, description, add_date) VALUES $values";
		mysql_query($sqlU);
	}
	
	//function send_sms($mobile, $smsText){
	function send_sms($mobile, $smsText, $template=0, $template_data = NULL) {
		return true;
		global $sms_enable;
		
		// As we dont need to Send the SMS Alerts we will return blank from this function.
		// Enable sms is Enabled from the Admin login.
		if($sms_enable == 0 || $sms_enable == '') return '';
		
		if(trim($mobile) == '') return '';
		$mobile = explode(",", $mobile);
		//$smsText .= ", Time : " . date(DISP_TIME);
		$sender = "NKINFO";
		$user   = "user=manish@nkonnect.com:devindia2486&dcs=0&state=4";
		$mobNew = array();
		foreach($mobile as $mob){
			$mobNew[] = substr(trim($mob), -10);
		}
		$mob = implode(",", $mobNew);
		
		$url  = "http://api.mVaayoo.com/mvaayooapi/MessageCompose?".$user;
		$url .= "&senderID=" . urlencode($sender);
		$url .= "&receipientno=" . urlencode($mob);
		$url .= "&msgtxt=" . urlencode($smsText);
		
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch); 		
		
		/*
		$username = "nconnecttrans";
		$password = "123456";
		$sender = "NKinfo";
		
		foreach($mobile as $mob){
			$mob = substr($mob, -10);
			if($template != 0) {
				$url  = "http://203.129.203.243/blank/sms/user/urlsmstemp.php?";
				$url .= "username=" . urlencode($username);
				$url .= "&pass=" . urlencode($password);
				$url .= "&senderid=" . urlencode($sender);
				$url .= "&dest_mobileno=91" . urlencode($mob);
				$url .= "&tempid=".$template."&response=Y";
				$url .= "&" . http_build_query($template_data);
			}
			else {
				$url = "http://203.129.203.243/blank/sms/user/urlsms.php?";
				$url .= "username=" . urlencode($username);
				$url .= "&pass=" . urlencode($password);
				$url .= "&senderid=" . urlencode($sender);
				$url .= "&dest_mobileno=91" . urlencode($mob);
				$url .= "&message=" . urlencode($smsText);
				$url .= "&response=Y";
			}
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec   ($ch);
			curl_close  ($ch); 
		}
		*/
		return htmlspecialchars($output);		
	}
	
	
	function insertDataRecievedLog($totaltime,$device_id_j,$assets_id_j,$RecievedBytes){
		
		
		//echo $totaltime;
	
		
		if(($totaltime!=0 || $totaltime!="") && $device_id_j!="" && $assets_id_j!=""){
			$qry="SELECT group_concat(tu.user_id) as user_id FROM tbl_users tu left join user_assets_map uam on tu.user_id=uam.user_id where tu.del_date is null and uam.del_date is null and find_in_set($assets_id_j,assets_ids)";
			
			$qrs=mysql_query($qry);
			if(mysql_num_rows($qrs)>0){
				$qrr=mysql_fetch_array($qrs);
				$users=$qrr['user_id'];
				$dt=gmdate('Y-m-d');
				$sqry="SELECT data_recieved, last_proc_time, avg_proc_time, last_recieved_time FROM java_data_log where add_date like '%$dt%' and assets_id=$assets_id_j";
				
				$sqrs=mysql_query($sqry);
				if(mysql_num_rows($sqrs)>0){
					$sqrr=mysql_fetch_array($sqrs);
					$dt=gmdate('Y-m-d');
					$data_rs=intval($sqrr['data_recieved']+$RecievedBytes);
					$dttime=gmdate('Y-m-d H:i:s');	
					$start = strtotime($sqrr['last_recieved_time']);
					$end = strtotime($dttime);
					
					$delta = $end - $start;
					$minutes = floor($delta / 60);
					$avg_time=intval($minutes);
					$avg_proc=0;
					if($sqrr['avg_proc_time']!=0){
						if(($sqrr['avg_proc_time']+$totaltime)>0){
							$avg_proc=floatval(($sqrr['avg_proc_time']+$totaltime)/2);
						}
					}else{
						$avg_proc=floatval($totaltime);
					}
									
					$insrt=mysql_query("Update java_data_log SET data_recieved=$data_rs, last_proc_time=$totaltime, avg_proc_time=$avg_proc, last_recieved_time='$dttime', avg_received_time=$avg_time where assets_id=$assets_id_j and device_id=$device_id_j and add_date like '%$dt%'");
				}else{
					
					$data_rs=intval($sqrr['data_recieved']+$RecievedBytes);
					$start = strtotime($sqrr['last_recieved_time']);
					$end = strtotime($current);
					$delta = $end - $start;
					$minutes = floor($delta / 60);
					$avg_time=intval($minutes);
					$avg_proc=floatval($totaltime);
					$totaltime=floatval($totaltime);
					$dttime=gmdate('Y-m-d H:i:s');
					$Inqry="Insert into java_data_log values(NULL,$users, $assets_id_j, $device_id_j, $data_rs,$totaltime ,$avg_proc, '$dttime',0,'$dttime',1,NULL)";				
					$insrt=mysql_query($Inqry);
				}
			}
		}		
	}
?>