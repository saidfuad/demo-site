<?php
       
	require_once("../db.php");
	require_once("../AfricasTalkingGateway.php");
	require_once('../PHPMailer-master/class.phpmailer.php');
	//require("../PHPMailer/PHPMailerAutoload.php");

	//log raw data
	function log_raw_data($device_id, $data) {
		//writelog("time".date(DATE_TIME));
		$rawsql = "INSERT INTO itms_raw_data 
						(device_id, raw_data, add_uid, add_date) 
					VALUES 
						('".$device_id."','".$data."','1','".date(DATE_TIME)."')";
		
		$raw_res = mysql_query($rawsql) or die(mysql_error().":".$rawsql);
	
	}

	function WriteLog($string) {
		$myFile = "log_".date(DISP_DATE).".txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		$current = date(DISP_TIME);
		$stringData = "[$current] : $string\r\n\r\n";
		fwrite($fh, $stringData);
		fclose($fh);		
	}

	function WriteLogTpms($string) {
		$myFile = "tpms_log_".date(DISP_DATE).".txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		$current = date(DISP_TIME);
		$stringData = "[$current] : $string\r\n\r\n";
		fwrite($fh, $stringData);
		fclose($fh);		
	}
	
	function deg_to_decimal($deg) {
  
	  if($deg == '') return 0.000000;
	  
	  $sign = substr($deg, -1);
	  
	  if(strtoupper($sign) == "N" || strtoupper($sign) == "E") $sign = 1;
	  if(strtoupper($sign) == "W" || strtoupper($sign) == "S") $sign = -1;
	  
	  $deg = substr($deg, 0, strlen($deg)-1);
	  
	  // $deg = floatval($deg);
	  $degree  = substr($deg, 0, -7);
	  $decimal = substr($deg, -7);
	  
	  //echo "Degree : $degree, Decimal : $decimal";
	  //echo "$sign * number_format(floatval((($degree * 1.0) + ($deg/60))),6);";
	  $decimal = $sign * number_format(floatval((($degree * 1.0) + ($decimal/60))),6);
	  
	  return $decimal;
	}

	// This function is used to get the time difference from two dates passed into it.
	// Difference can have different formats, such as seconds, minutes, hours, weeks, days et.c
	function timeBetween($startDate, $endDate, $format = 1)
	{
		list($date,$time) = explode(' ',$endDate);
		$startdate = explode("-",$date);
		$starttime = explode(":",$time);
	
		list($date,$time) = explode(' ',$startDate);
		$enddate = explode("-",$date);
		$endtime = explode(":",$time);
	
		$secondsDifference = mktime($endtime[0],$endtime[1],$endtime[2],
			$enddate[1],$enddate[2],$enddate[0]) - mktime($starttime[0],
				$starttime[1],$starttime[2],$startdate[1],$startdate[2],$startdate[0]);
		
		switch($format){
			// Difference in Minutes
			case 1: 
				return floor($secondsDifference/60);
			// Difference in Hours    
			case 2:
				return floor($secondsDifference/60/60);
			// Difference in Days    
			case 3:
				return floor($secondsDifference/60/60/24);
			// Difference in Weeks    
			case 4:
				return floor($secondsDifference/60/60/24/7);
			// Difference in Months    
			case 5:
				return floor($secondsDifference/60/60/24/7/4);
			// Difference in Years    
			default:
				return floor($secondsDifference/365/60/60/24);
		}                
	}
	   
	function convertSpeed($kms, $unit="miles") {
		
		if($unit == 'angstrom'){
			$ratio = 10000000000000;
			$result = $kms * $ratio;
		}
		if($unit == 'centimeters'){
			$ratio = 100000;
			$result = $kms * $ratio;
		}
		if($unit == 'feet'){
			$ratio = 3280.84;
			$result = $kms * $ratio;
		}
		if($unit == 'furlongs'){
			$ratio = 4.97;
			$result = $kms * $ratio;
		}
		if($unit == 'inches'){
			$ratio = 39370.08;
			$result = $kms * $ratio;
		}
		if($unit == ' meters'){
			$ratio = 1000;
			$result = $kms * $ratio;
		}
		if($unit == 'microns'){
			$ratio = 1000000000;
			$result = $kms * $ratio;
		}
		if($unit == 'miles'){
			$ratio = 1.609344;
			$result = $kms / $ratio;
		}
		if($unit == 'millimeters'){
			$ratio = 1000000;
			$result = $kms * $ratio;
		}
		if($unit == 'yards'){
			$ratio = 1093.61;
			$result = $kms * $ratio;
		}
		return $result;
	}

	function getNearest($lat, $lng){
		$query = "SELECT latitude, longitude, address, 
					    ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
					     * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) 
					     * sin( radians( latitude ) ) ) ) AS distance 
				   FROM itms_geodata HAVING distance < 0.9 
				   ORDER BY distance LIMIT 1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		
		if(mysql_num_rows($res) > 0){
			$row = mysql_fetch_assoc($res);
			// number_format($row['distance'], 2)." Km from ".
			return $row['address'];
			
		}else{
			$temp=getAddress($lat, $lng);
			return $temp;
		}
	}

	function getAddress($lat, $lng) {
		
		$today = gmdate(DATE);
		
		if(intval($lat) == 0 && intval($lng) == 0) {
			return '';
		}

		$query = "SELECT address FROM itms_geodata 
					WHERE latitude = '$lat' 
					AND longitude = '$lng' 
					ORDER BY id 
					DESC LIMIT 0,1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		
		if(mysql_num_rows($res) > 0){
			$row = mysql_fetch_assoc($res);
			return $row['address'];	
		} else {
			
			$limit_query = "SELECT google, mapquest, yahoo FROM api_requests WHERE add_date = '$today'";
			$limit_res   = mysql_query($limit_query) or die(mysql_error().":".$limit_query);
			
			if(mysql_num_rows($limit_res)>0){
				$row = mysql_fetch_assoc($limit_res);
				
				if($row['google']<=2500){				
					$address = googleGeocode($lat, $lng);
					$qryUpdt = "UPDATE api_requests SET google=".intval($row['google']+1)." WHERE add_date='$today'";
					$insrtGeo = mysql_query($qryUpdt) or die(mysql_error().":".$qryUpdt);
				}
				else if($row['yahoo']<=4500){
					$address = yahooGeocode($lat, $lng);
					$qryUpdt = "UPDATE api_requests SET yahoo=".intval($row['yahoo']+1)." WHERE add_date='$today'";
					$insrtGeo = mysql_query($qryUpdt) or die(mysql_error().":".$qryUpdt);
				}
				else{
					$lat = substr($lat, 0, 7);
					$lng = substr($lng, 0, 7);
					$query = "SELECT address FROM tbl_cell_data WHERE latitude like '%$lat%' AND longitude like '%$lng%' ORDER BY id DESC LIMIT 0,1";
					$res = mysql_query($query) or die(mysql_error().":".$query);
					
					if(mysql_num_rows($res) > 0){
						$row = mysql_fetch_assoc($res);
						return $row['address'];
					}
					return '';
				}
			}else{
				$qryGeo = "Insert into api_requests values(NULL,1,0,0,'$today')";
				$insrtGeo = mysql_query($qryGeo) or die(mysql_error().":".$qryGeo);
				$address = googleGeocode($lat, $lng);
				
			}
			if($address == '')
				return '';
				
			//$insert = "INSERT INTO tbl_cell_data(latitude, longitude, address, add_date) VALUES ('".addslashes($lat)."', '".addslashes($lng)."', '".addslashes($address)."', '".$today."')";
			//@mysql_query($insert);
			
			$insert = "INSERT INTO itms_geodata(latitude, longitude, address, add_date, add_uid, status) VALUES ('".addslashes($lat)."', '".addslashes($lng)."', '".addslashes($address)."', '".$today."', 1, 1)";
			mysql_query($insert) or die(mysql_error().":".$insert);			
			return $address;
			
		}
	}

	function googleGeocode($lat,$lng){
		//Google Map
		$latlng = "$lat,$lng";
		$strURL = "http://maps.google.com/maps/api/geocode/xml?latlng=". $latlng ."&sensor=false&region=in";
		
		$address = "";
		
		$resURL = curl_init();
		curl_setopt($resURL, CURLOPT_URL, $strURL);
		curl_setopt($resURL, CURLOPT_RETURNTRANSFER, true);
		$xmlstr = curl_exec($resURL);
		
		$objDOM = new DOMDocument();
		
		if(! @$objDOM->loadXML($xmlstr)) {
			//die("XML Parsing Failed");
			return '';
		}
		
		$address = @$objDOM->getElementsByTagName("formatted_address")->item(0)->nodeValue;
		$address = iconv('UTF-8', '', $address);
		if($address!=""){
			return $address;
		}else{
			return '';
		}		
	}

	function DMStoDEC($deg,$min,$sec) {
		// Converts DMS ( Degrees / minutes / seconds ) 
		// to decimal format longitude / latitude
	    return $deg+((($min*60)+($sec))/3600);
	} 

	function save_new_stop () {
		$sql = "INSERT INTO `itms_stop_report` 
				  					(`device_id`, `ignition_off`, `ignition_on`, `duration`, `address`, 
				  						`lat`, `lng`, `ignition_status`, `add_date`, `alert_given`, `current_area`, 
				  							`current_landmark`) 
								VALUES ('".addslashes($device_id)."', '".addslashes(1)."','".addslashes(0)."', '".addslashes($duration)."',
									'".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".addslashes($ignition)."', 
									'".addslashes($ist)."', '".addslashes($alert_given)."', '".addslashes($current_area_id)."',
									'".addslashes($current_landmark_id)."')";
						
		$stop_report = mysql_query($sql) or die(mysql_error().": stop report");

		return $stop_report;

	}


	function get_alert_permissions($company_id) {
		
		$rawsql = "SELECT iap.*, ia.* 
					FROM itms_alert_permissions iap
						LEFT JOIN itms_alerts ia ON (iap.alert_id=ia.alert_id)
					WHERE
						company_id = '".$company_id."'";
		
		$raw_res = mysql_query($rawsql) or die(mysql_error().":".$rawsql);

		while ($row = mysql_fetch_assoc($raw_res))  {
			$perms [] = $row;
		}

		return $perms;
	} 


	function log_text_messages ($device_id, $reciever, $reason, $message) {
		//writelog("time".date(DATE_TIME));

		$rawsql = "INSERT INTO itms_sms_log 
						(device_id, reciever, reason, message, add_date) 
					VALUES 
						('".$device_id."','".$reciever."', '".$reason."', '".$message."', '".date(DATE_TIME)."')";
		
		$raw_res = mysql_query($rawsql) or die(mysql_error().":".$rawsql);

		return $raw_res;
	}

	function email_log($emailid, $company_id, $device_id, $reason, $message, $user_id = 1, $desc = "") {
		
		//$emailid = explode(",", $emailid);
		$values = array();
		foreach($emailid as $em){
			$values[] = "($user_id, '$em', '$company_id', '$device_id','$message', '$reason','$desc', '".gmdate(DATE_TIME)."')";
		}

		$values = implode(",", $values);
		$sqlU = "INSERT INTO itms_emaillog (user_id, email_id, company_id, device_id, email_text, reason, description, add_date) VALUES $values";
		$saved = mysql_query($sqlU) or die(mysql_error().":".$sqlU);

		return $saved;
	}

	function send_email_message ($add_company_id, $device_id, $to, $reason, $subj, $message) {
		//require("../PHPMailer/PHPMailerAutoload.php");

		$mail = new PHPMailer();
		$mail->IsSMTP(); // send via SMTP
		//IsSMTP(); // send via SMTP
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->Username = "makaweys@gmail.com"; // Enter your SMTP username
		$mail->Password = "Eddah@uweys16"; // SMTP password
		$webmaster_email = "fso@kits.co.ke"; //Add reply-to email address
		$email= "makaweys@gmail.com"; // Add recipients email address
		$name= "Uweis S"; // Add Your Recipient’s name
		$mail->From = $webmaster_email;
		$mail->FromName = "Webmaster";
		$mail->AddAddress($email,$name);
		$mail->AddReplyTo($webmaster_email,"Webmster");
		$mail->WordWrap = 50; // set word wrap
		
		$mail->IsHTML(true); // send as HTML

		$mail->Subject = "sub";

		$mail->Body = "Subfhjgf kksdf fgdisf" ;      //HTML Body

		$mail->AltBody = "Text Sets df";     //Plain Text Body

		echo "test";
		if(!$mail->Send()){
		echo $mail->ErrorInfo;
		} else {
		echo "Message has been sent";
		}

		exit;
		
		/*//To address and name
		foreach ($to as $key => $email) {
			$mail->addAddress($email); //Recipient name is optional
		}
		
		

		*/
		$jto = implode(',', $to);

		print_r("Sending email alert...\n To : $jto ");

		$mail_sent = false;

		if ($mail->Send()) {
			$log_saved = email_log($to, $add_company_id, $device_id, $reason, $message);

			if ($log_saved) {
				print_r("  \n[Email logged successfully]\n");
			} else {
				print_r("  \n[Failed to log email]\n");
			}

			$mail_sent = true;
		
		}

		return $mail_sent;
	}


	function check_overspeed_log($device_id, $x_address, $ist) {
		$n_date = strtotime($ist);


		$rawsql = "SELECT * FROM itms_overspeed_log  WHERE device_id = $device_id ORDER BY id DESC LIMIT 1";
		$raw_res = mysql_query($rawsql) or die(mysql_error().":".$rawsql);

		$data = mysql_fetch_array($raw_res);

		$res = false;

		if ($data['address'] == $x_address) {
			$p_date = strtotime($data['add_date']);

			if (($n_date - $p_date) < 60) {
				$res = true;
			}
		} 

		return $res;

	}


	function save_speed_report($asset_id, $device_id, $driver_id, $speed, $max_speed, $g_date, $gtime,
								$current_landmark_id, $current_area_id, $x_address, $user_id, $driver_phone, $message, $ist) {

		$new_date = date('l, F d Y H:i:s', strtotime($ist));

		$rawsql = "INSERT INTO itms_overspeed_log 
						(asset_id, device_id, driver_id, speed, address, landmark_id, area_id, max_speed_limit, add_date, add_time) 
					VALUES 
						('".$asset_id."','".$device_id."', '".$driver_id."', '".$speed."', '".$x_address."', '".$current_landmark_id."', 
								'".$current_area_id."', '".$max_speed."', '".$g_date."', '".$gtime."')";
		
		$raw_res = mysql_query($rawsql) or die(mysql_error().":".$rawsql);

		if ($raw_res) {
			$alertSql = "INSERT INTO itms_alert_master (alert_header, asset_id, alert_msg, driver_phone, alert_type, user_id, data_time, add_date) 
										VALUES ('Overspeeding', '".$asset_id."', '".$message."', '".$driver_phone."', 'Alert', '".$user_id."','".$new_date."','".date(DATE_TIME)."')";
						mysql_query($alertSql) or die(mysql_error().":".$alertSql);
		}

		return $raw_res;
	}


	function send_text_message ($device_id, $reciever, $reason, $message) {

		//$reciever = '+254729220777';
		$phones = array();
		foreach ($reciever as $k=>$phone) {
			array_push($phones , '+254'.intval($phone));
		}
		$reciever = implode(',', $phones);
		$result = false;


		//echo $reciever;
		//exit;


		$Obj = new AfricasTalkingGateway();
		$res = $Obj->sendMessage($reciever, $message);

		foreach ($res as $key => $value) {
			$result = $value->status;
		}

		if ($result=='Success') {
			$result = true;
		}		

		if ($result) {
			log_text_messages ($device_id, $reciever, $reason,$message);
		}

		return $result;
	}

	function checkLandmark($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, 
									$lati, $longi, $current_speed, $ist, $odometer) {

		$new_date = date('l, F d Y H:i:s', strtotime($ist));

		//print_r($new_date."\n");



		
		global $current_landmark, $current_landmark_id, $dts;
		$insert_data = false;
		
		print_r("Get device landmarks..\n");
		$sql = "SELECT group_concat(landmark_id) as device_landmark FROM assets_landmark WHERE assets_id = '$assets_id'";
		$rs = mysql_query($sql) or die(mysql_error().":".$sql);
		$row = mysql_fetch_array($rs);
		$device_landmark = $row['device_landmark'];
		
		print_r("Fetching landmarks..\n\n");

		$sqlP = "SELECT lm.*, um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, 
					um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, 
						um.alert_stop_time FROM itms_landmarks lm 
					left join itms_users um on um.user_id = lm.add_uid 
					and lm.del_date is null and lm.status = 1";
		if($device_landmark != ""){
			$sqlP .= " and lm.landmark_id in($device_landmark)";
		}

		$rs = mysql_query($sqlP) or die(mysql_error().":".$sqlP);

		$rdat = array();
		
		while($row = mysql_fetch_array($rs)){
			print_r("Landmark Check Initialised...\n");
			
			$distance_value 		= $row['radius'];
			$alert_before_landmark ="";
			if($row['alert_before_landmark']!="")
				$alert_before_landmark = floatval($distance_value + $row['alert_before_landmark']);

			if($distance_value == ""){
				continue;
			}
			
			$landmark_id 			= $row['landmark_id'];
			$dealer_code 			= $row['comments'];
			$landmark_name 			= $row['landmark_name'];
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
			$language               =  $row['language'];
			
			print_r("Landmark name : $landmark_name\n");
			
			/*if($language == "portuguese"){
				$file = "portuguese_alert_lang.php";
			}
			else{
				$file = "english_alert_lang.php";
				
			}	
			include($file);	*/

			if($distance_unit == "Mile"){
				$unit = "Mile";
			}else{
				$unit = "KM";
			}

			$send_sms_now = true;

			if($alert_start_time != "" && $alert_stop_time != ""){
				if(time() < strtotime($alert_start_time) && time() > strtotime($alert_stop_time)){
					$send_sms_now = false;
				}
			}
			
			$distanceFromLandmark = getDistance($lati, $longi, $row['latitude'], $row['longitude'], $unit);
			print_r("  [Distance from landmark : $distanceFromLandmark $unit] \n");
			
			if($distance_unit == "Meter")
				$distanceFromLandmark  = $distanceFromLandmark * 1000;

			// WriteLog("\n$landmark_name : $distanceFromLandmark < $distance_value");

			if($distanceFromLandmark < $distance_value){	//"Device is near to Landmark"
				print_r("   > $landmark_name - Within Range\n");	
				
				$checkLast = "SELECT landmark_id, in_out FROM itms_landmarks_log 
								WHERE device_id = $device_id 
								AND landmark_id = $landmark_id 
								ORDER BY id DESC limit 1";

				$checkRs = mysql_query($checkLast) or die(mysql_error().":".$checkLast);
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
				
					print_r("  [Vehicle moving towards landmark]\n");	

					$distanceText = number_format($distanceFromLandmark, 2)." ".$distance_unit;
					$checkLast1 = "SELECT landmark_id, odometer FROM itms_landmarks_log
										WHERE device_id = $device_id ORDER by id desc limit 1";
					$checkRs1 = mysql_query($checkLast1) or die(mysql_error().":".$checkLast1);
					$checkRow1 = mysql_fetch_array($checkRs1);
					$last_landmark_id = $checkRow1['landmark_id'];
					$last_odometer = $checkRow1['odometer'];
					$distance_from_last = ($odometer - $last_odometer)/1000;
					$distance_from_last += $distanceFromLandmark;

					print_r('Device ID: '.$device_id . "\n");
					
					$ins = "INSERT INTO itms_landmarks_log (device_id,landmark_id, date_time, lat, lng, distance, in_out, 
									odometer, last_landmark_id, distance_from_last) 
								VALUES 	('".$device_id."', $landmark_id, '".date(DATE_TIME)."', '$lati', '$longi', 
											'$distanceText', 'in', '$odometer', '$last_landmark_id', 
												'$distance_from_last')";
					mysql_query($ins) or die(mysql_error().":".$ins);
					$insert_data = true;

					print_r('Device ID: '.$device_id . "\n");

					
					// Dear $fname, 
					$smsText = "$assets_name ($nick_name, Driver:$driver_name, $driver_mobile) 
									is near landmark $landmark_name (Distance : $distanceText), 
										".$new_date;

					//$alert_link = addslashes("gps_tracking/view_vehicle_map/$assets_id");					 

					print_r("Checking sms/email alerts permissions...\n");	
										// .date(DISP_TIME);
					if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1){

						print_r("[ SMS Alert Permissions granted ]\n");	
						

						$reciever = array();
						array_push($reciever, $mobile);
						$reason = 'Near Landmark';
						$message = $smsText;

						$sent = false;
						
						print_r("Sending...\n");	
						//$sent = send_text_message ($device_id, $reciever, $reason, $message);
						
						if ($sent) {
							print_r("  >> Near Landmark Sms alert sent successfully \n\n");
						} else {
							print_r("  >> Failed to send landmark alert\n\n");
						}
					} else {
						print_r("[ SMS Alert Permissions denied ]\n\n");	
					}
					/*
					if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
						$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$lati.','.$longi.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
						send_email($email, $lang['Near Landmark Alert'], $smsText);
						email_log($email, $smsText, $user_id, 'Near Landmark Alert');
						// chat_alert($email, $smsText);
					}
					
					/*if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}*/


					
					//insert in alert master
					//if($landmark_sms_alert == 1 || $landmark_email_alert == 1) {
						$alertSql = "INSERT INTO itms_alert_master (alert_header, asset_id,alert_msg, driver_phone, alert_type, user_id, data_time, add_date) 
										VALUES ('Near Landmark Alert', '".$assets_id."', '".$smsText."', '".$driver_mobile."', 'Alert', '".$user_id."','".$new_date."','".date(DATE_TIME)."')";
						mysql_query($alertSql) or die(mysql_error().":".$alertSql);
					//}
					
					
				}
			}else{		//out
				print_r("   > $landmark_name - Out Of Range\n");	

				$checkLast = "SELECT id FROM itms_landmarks_log 
								WHERE device_id = $device_id 
								AND landmark_id = $landmark_id  
								AND in_out = 'in' ORDER BY id DESC LIMIT 1";

				$checkRs = mysql_query($checkLast) or die(mysql_error().":".$checkLast);
				$checkRow = mysql_fetch_array($checkRs);


				if(mysql_num_rows($checkRs) > 0){
					print_r("Initiating Status - In Out update....\n");
					//$checkRow = mysql_fetch_array($checkRs);
					$lId = $checkRow['id'];
					//print_r($checkRow);
					
					$uLSql = "UPDATE itms_landmarks_log SET in_out = 'out' WHERE id = '".$lId."'";
					mysql_query($uLSql) or die(mysql_error().":".$uLSql);

					print_r("Update Complete\n");
				}
			}

			
		}
		
		//print_r("Asset moving towards landmark\n");	
		return $insert_data;
	}


	function convert_time_zone($date_time, $to_tz, $ret_format = 'Y-m-d H:i:s', $from_tz = 'UTC') {
		$time_object = new DateTime($date_time, new DateTimeZone($from_tz));
		$time_object->setTimezone(new DateTimeZone($to_tz)); 
		return $time_object->format($ret_format);
    }


	function getDistance($lat1, $lng1, $lat2, $lng2, $unit){
		$distance = 0;				
		
		if($lat1 && $lng1){
			$dist = 0;
			$theta = $lng1 - $lng2;  
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));  
			$dist = acos($dist);  
			$dist = rad2deg($dist);  
			$miles = $dist * 60 * 1.1515;  
			 
			 if ($unit == "KM") {  
				$dstn = round(($miles * 1.609344), 2);
				if(!is_nan($dstn)){
					$distance = $dstn;  
				}
			 } else if ($unit == "N") {  
				  $distance = ($miles * 0.8684);  
			 } else {  
				 $distance = $miles;  
			 }
		}
		return $distance;
	}
