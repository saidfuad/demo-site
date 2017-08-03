<?php

//----------------------------------------------//
	// Set time limit to indefinite execution
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;
	set_time_limit (0);
	
	// Include the file for Database Connection
	require_once("../db.php");
	require_once("functions.php");


	print_r('<pre>');
	
	//"014140114184BP05160108A0401.0871S03943.5554E000.0024021000.0000100000L00000004";//
	//0403.1862S03940.4561E000.0150932000.0000000000L06355834
	//gps:010000000090BP05160114A0403.1102S03940.4651E000.0151356002.0000100000L06355917
/*

	$lat = deg_to_decimal('0403.1102S');
	$long = deg_to_decimal('03940.4651E');

	print_r($lat .',' .$long);
	exit;


*/
	$dummydt = date('ymd');
	$dummytm = date('His');

	$input = "010000000090BP05".$dummydt."A0403.1102S03940.4651E060.0".$dummytm."002.0000100000L06355917";//$_REQUEST['gps'];
	//$input = $_REQUEST['gps'];
	$output = '';
	$is_insert = true;
	$current_area = '';
	$current_landmark = '';
	$current_area_id = '';
	$current_landmark_id = '';
	$cross_speed = 0;
	$ignition = 0;
	$odomVal = NULL;

	//Uweis:if input not posted or is blank write log
	if(trim($input) == '') {
		WriteLog("Blank Input String '$input'");	
	} 

	$RecievedBytes = strlen($input);
	//Check if string length is of required length - if not write in log kill script
	if($RecievedBytes != 78) {
		WriteLog("Invalid String : '$input'"); 
		die("Invalid string: $input - length:".strlen($input));
	}
	
	//Assign GPS Values to variables
	list($device_id, $command, $date, $gps_fixed, $latitude, $latitude_indicator, 
			$longitude, $longitude_indicator, $speed, $time, $orientation, $io_state, $milepost, $miledata) 
		= array(substr($input,0,12), substr($input,12,4), substr($input,16,6),substr($input,22,1),substr($input,23,9),
					substr($input,32,1), substr($input,33,10), substr($input,43,1), substr($input,44,5), 
						substr($input,49,6), substr($input,55,6), substr($input,61,8), substr($input,69,1),
							substr($input,70,8));


	$ignition = substr($io_state,2,1);

	
		
	//Log raw data
	log_raw_data($device_id, $input);

	$aSql = "SELECT am.*, ip.*, iu.mobile_number as add_user_phone, iu.email_address as add_user_email, iu.user_id as add_user_id,
						iu.company_id as add_company_id
					FROM itms_assets am 
				LEFT JOIN itms_personnel_master ip ON (ip.personnel_id=am.personnel_id)
				LEFT JOIN itms_users iu ON (iu.user_id=am.add_uid)
				WHERE 
					am.device_id = '".addslashes($device_id)."' 
				AND 
					am.del_date IS NULL AND am.status = 1";

	$aRs = mysql_query($aSql);
	$aRowCount = mysql_num_rows($aRs);


	if(! $aRowCount) {
		WriteLog("Data Received, But No Assets Defined"); 
		die('Data Received, But No Assets Defined');
	}

	$data = mysql_fetch_array($aRs);
	$asset_id = $data['asset_id'];
	$assets_name = $data['assets_name'];
	$nick_name = $data['assets_friendly_nm'];
	$driver_name = $data['fname'] . ' ' . $data['lname'];
	$add_user_phone = $data['add_user_phone'];
	$add_company_id = $data['add_company_id'];
	$add_user_email = $data['add_user_email'];
	$add_user_id = $data['add_user_id'];
	$max_speed = $data['max_speed_limit'];
	$driver_phone = $data['phone_no'];
	//$reciever = $data['phone_no'];
	$driver_id = $data['personnel_id'];

	
	list($year, $month, $day, $hour, $min, $sec) = array(substr($date,0,2), substr($date,2,2), substr($date,4), substr($time,0,2), substr($time,2,2), substr($time,4,2));
	$ist = $gmt = date(DATE_TIME,mktime($hour, $min, $sec, $month, $day, $year));
	
	$g_date = date(DATE, strtotime($ist));
	$g_time = date(TIME, strtotime($ist));


	$latitude = deg_to_decimal($latitude.$latitude_indicator);
	$longitude = deg_to_decimal($longitude.$longitude_indicator);
	$x_address = getNearest($latitude,$longitude);
	$current_area_id = NULL; 							//getCurrentArea($latitude,$longitude);
	$current_landmark_id = NULL; 						//getCurrentLandmark($latitude,$longitude); 
	$overspeed = false;

	$speed = intval($speed);

	$alert_permissions = get_alert_permissions($data['company_id']);
	
	print_r("Speed Check Initialised..\n");
	
	print_r("  [Speed: $speed Kmh]\n");
	//print_r("$driver_phone\n");
	if ($speed > $max_speed) {

		//print_r("Overs : Phone $add_user_phone");
		
		print_r("  [Overspeed detected: limit $max_speed Kmh]\n");

		$check_last = check_overspeed_log($device_id, $x_address, $ist);

		if ($check_last == false) {

			$message = "Overspeeding! Name: $driver_name ($assets_name). Speed Limit Exceeded at ". intval($speed) ." Kmh - Maximum Speed Limit is : $max_speed Kmh";

			$overspeed = true;
			$speedReport = save_speed_report($asset_id, $device_id, $driver_id, $speed, $max_speed, $g_date, $g_time,
									$current_landmark_id, $current_area_id, $x_address, $add_user_id, $driver_phone, $message, $ist);

			if ($speedReport) {
				print_r("  >> Overpeed Report Saved\n");
			}


			$sendSpeedText = false;
			$sendSpeedEmail = false;

			foreach ($alert_permissions as $key => $value) {
				if ($value['alert_name'] == 'User Speed Alert') {
					$recs = explode(',', $value['recievers']);
					if ((in_array($add_user_id, $recs) || in_array('all', $recs)) && $value['it_sms'] == 1) {
						$sendSpeedText = true;
					}

					if ((in_array($add_user_id, $recs) || in_array('all', $recs)) && $value['it_email'] == 1) {
						$sendSpeedEmail = true;
					}
				}
			}

			$reason = 'Overspeeding';
				
			if ($sendSpeedText) {

				print_r("  [SMS alerts activated]\n");
				
				$reciever = array();
				array_push($reciever, $add_user_phone);
				//$message = "Overspeeding! Name: $driver_name ($assets_name). Speed Limit Exceeded at ". intval($speed) ." Kmh - Maximum Speed Limit is : $max_speed Kmh";

				$sent = false;
				
				$sent = send_text_message ($device_id, $reciever, $reason, $message);
				
				if ($sent) {
					print_r("  >> Overspeeding sms alert sent successfully\n");
				} else {
					print_r("  >> Failed to send overspeeding sms alert\n");
				}

			}

			$to = array();


			if ($sendSpeedEmail) {
				
				print_r("  [Emailing permissions granted]\n");

				$reciever = array();
				array_push($to, $add_user_email);
				$email_subject = "$reason : $driver_name ($assets_name)";
				//$message = "Overspeeding! Name: $driver_name ($assets_name). Speed Limit Exceeded at ". intval($speed) ." Kmh - Maximum Speed Limit is : $max_speed Kmh";
				
				$sent = false;
				$sent = send_email_message ($add_company_id, $device_id, $to, $reason, $email_subject,$message);
				
				if ($sent) {
					print_r("  >> Overspeed email alert sent successfully\n");
				} else {
					print_r("  >> Failed to send overspeeding email alert\n");
				}

			}
		}	
	} else {
		print_r("  [Asset at allowed speed]\n");
	}
	
	print_r("Status: Speed Check completed\n\n");

	exit;

	//Check if cordinates are valid
	print_r("Cordinates validation initialized..\n");

	if((intval($latitude) != 0 && intval($longitude) != 0)) {
		
		print_r("  [Valid Coordinates]\n");
		print_r("  [Address:$x_address]\n\n");
		


		//Check for landmarks nearby
		print_r("Landmarks Check initiated...\n");
		
		checkLandmark(addslashes($device_id), $asset_id, $assets_name, $nick_name, $driver_name, $driver_phone,
							addslashes($latitude), addslashes($longitude), $speed, $ist, $odomVal);

		print_r("Status: Landmark check completed\n\n");

		$query = "SELECT ignition, add_date, latitude, longitude, address,speed, stop_duration 
					FROM itms_last_gps_point 
					WHERE device_id = '".addslashes($device_id)."'";
		
		$result = mysql_query($query);
		
		$countD= mysql_num_rows($result);
		
		if ($countD) {
			$arr_row = mysql_fetch_array($result);
		}
        


        if ($countD) {


			$last_lignition = $arr_row['ignition'];
			$last_time 	= $arr_row['add_date'];
			$old_ignition = $arr_row['ignition'];
			$old_latitude= $arr_row['latitude'];
			$old_longitude = $arr_row['longitude'];
			$lastSpeed = $arr_row['speed'];
			$new_longitude = $longitude;
			$new_latitude = $latitude;
			$old_address =  $arr_row['address'];
			$new_address = $x_address;
			$timeFirst  = strtotime($last_time);
			$timeSecond = strtotime($ist);
			$differenceInSeconds = $timeSecond - $timeFirst;
			$stop_duration = $arr_row['stop_duration'];
			
					
			if ((($old_latitude == $new_latitude  && $old_longitude == $new_longitude ) || ($old_address == $new_address)) && ($last_lignition == 0)) {
				
				

				$duration = $differenceInSeconds + $stop_duration;
				//$lSql = "UPDATE tbl_last_point SET add_date = '".addslashes($g_dt_tm)."', dt = '".$g_date."', speed = '".addslashes($speed)."' WHERE device_id = '".addslashes($device)."'";
			  	$lSql = "UPDATE itms_last_gps_point set 
				  				latitude='".addslashes($latitude)."', 
				  				longitude='".addslashes($longitude)."', 
				  				add_date='".addslashes($ist)."', 
				  				speed='".addslashes($speed)."', 
				  				old_speed = '".$lastSpeed."',
				  				overspeed = '".$overspeed."', 
				  				max_speed_limit = '".$max_speed."', 
				  				gps_availability='".addslashes($gps_fixed)."', 
				  				dt='".addslashes($g_date)."', 
				  				tm='".addslashes($g_time)."', 
				  				angle_dir='".addslashes($orientation)."', 
				  				address='".addslashes($x_address)."', 
				  				input_data = '".$input."',
				  				stop_duration = '".addslashes($duration)."', 
				  				gps_fixed = '".addslashes($gps_fixed)."', 
				  				ignition = '".addslashes($ignition)."' 
			  				WHERE 
			  					device_id = '".addslashes($device_id)."'";

			  	$query = mysql_query($lSql);


			  	
			  	if ($query) {
			  		//echo '';
			  		print_r("  [Duplicate location:updated successfully]\n");
				} else {
			  		//echo '';
			  		print_r("  [Duplicate location:failed to update]\n");
			  	}

			  	$sql = "UPDATE `itms_stop_report` SET 
			  					duration='".$duration."' WHERE 1
			  					AND id='(SELECT max(id) from itms_stop_report)' 
			  					AND device_id='".$device_id."'";
			  		$query = mysql_query($sql);	
			  		
			  		if ($query) {
			  			print_r("  [Stop Duration updated]\n");
			  		}
				

			  	log_raw_data($device_id, $input);
			  	

				if($differenceInSeconds < 9){
		            //die('');
		            print_r("  [Data received before 9 Seconds]\n");
		        }

		        

		    } else {



		    	$lSql = "UPDATE itms_last_gps_point set 
				  				latitude='".addslashes($latitude)."', 
				  				longitude='".addslashes($longitude)."', 
				  				add_date='".addslashes($ist)."', 
				  				speed='".addslashes($speed)."', 
				  				old_speed = '".$lastSpeed."', 
				  				overspeed = '".$overspeed."', 
				  				max_speed_limit = '".$max_speed."', 
				  				gps_availability='".addslashes($gps_fixed)."', 
				  				dt='".addslashes($g_date)."', 
				  				tm='".addslashes($g_time)."', 
				  				angle_dir='".addslashes($orientation)."', 
				  				address='".addslashes($x_address)."', 
				  				input_data = '".$input."',
				  				stop_duration = 0, 
				  				gps_fixed = '".addslashes($gps_fixed)."', 
				  				ignition = '".addslashes($ignition)."' 
			  				WHERE 
			  					device_id = '".addslashes($device_id)."'";

			  	$query = mysql_query($lSql) or die(mysql_error() . 'Cannot Update_new Location');
			  	
			  	if ($query) {
			  		echo 'New Location:updated successfully';
				} else {
			  		echo $query;
			  	}	

			  	$duration = 0;

			  		if ($ignition==0) {
				  		$res = save_new_stop ();

				  		if ($res) {
				  			echo 'New stop saved';
				  		} else {
				  			echo "Failed to save new stop: Device [$device_id]: $input";
				  		}
				  	}  
		    }    
		    
		} else {

			$duration = 0;
			
		  	$sql = "INSERT INTO itms_last_gps_point 
		  				(latitude, longitude, add_date, speed, 
		  					device_id, gps_availability, dt, tm, ignition, overspeed, max_speed_limit, 
				  				stop_duration, angle_dir, address, command_key) 
					VALUES 
						('".addslashes($latitude)."', '".addslashes($longitude)."', 
							'".addslashes($ist)."', '".addslashes($speed)."', '".addslashes($device_id)."', 
									'".addslashes($gps_fixed)."', '".addslashes($ist)."', '".addslashes($time)."', 
										'".addslashes($ignition)."', '".addslashes($overspeed)."', '".addslashes($max_speed)."',
											'".addslashes($duration)."' , '".addslashes($orientation)."', '".addslashes($x_address)."', '".addslashes($command)."')";

			$track_res = mysql_query($sql) or die(mysql_error().":".$sql);
			
			if ($track_res) {
			  	echo 'Save new device location: Data saved successfully <br>';
			}

			$alert_given = 0;

			  	if ($ignition==0) {
			  		$res = save_new_stop ();

			  		if ($res) {
			  			echo 'New stop saved';
			  		} else {
			  			echo "Failed to save new stop: Device [$device_id]: $input";
			  		}
			  	}  
		}
		

	} else {

		print_r("  [Invalid Coordinates]\n\n");

	}



	



?>