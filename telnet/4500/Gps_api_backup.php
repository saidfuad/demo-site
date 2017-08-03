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
	
	$dummydt = date('ymd');
	$dummytm = date('His');


	//Far : "010000000090BP05".$dummydt."A0403.1102S03940.4651E020.0".$dummytm."090.0000100000L06355917";
	//Near Pickup : "010000000090BP05".$dummydt."A0403.1102S03940.4651E020.0".$dummytm."090.0000100000L06355917";
	//Near Destination : "010000000090BP05".$dummydt."A0403.1102S03940.4651E020.0".$dummytm."090.0000100000L06355917"; 

	//$input = "010000000090BP05".$dummydt."A0403.1102S03940.4651E020.0".$dummytm."090.0000100000L06355917";

	$input = $_REQUEST['gps'];
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
						iu.company_id as add_company_id, (iu.first_name + ' ' +iu.last_name) as add_user_name, iu.overspeeding_sms_alert, 
						iu.overspeeding_email_alert,
						(select group_concat(user_id) from itms_assigned_groups where assets_group_id = am.assets_group_id) AS alert_users, it.end_lat, it.end_lng, it.destination_address,it.pick_lat, it.pick_lng, it.pickup_address, it.destination_out, it.pickup_out, it.destination_in, it.pickup_in, icm.email AS client_email, icm.phone_no AS client_phone, it.distance_to_alert
					FROM itms_assets am 
				LEFT JOIN itms_personnel_master ip ON (ip.personnel_id=am.personnel_id)
				LEFT JOIN itms_users iu ON (iu.user_id=am.add_uid)
				LEFT JOIN itms_trips it ON (it.trip_id=am.current_trip)
				LEFT JOIN itms_client_master icm ON (it.client_id=icm.client_id)
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
	$add_user_name = $data['add_user_name'];
	$overspeed_sms_alert = $data['overspeeding_sms_alert'];
	$overspeed_email_alert = $data['overspeeding_email_alert'];
	$add_user_id = $data['add_user_id'];
	$max_speed = $data['max_speed_limit'];
	$driver_phone = $data['phone_no'];
	//$reciever = $data['phone_no'];
	$driver_id = $data['personnel_id'];
	$alert_users = $data['alert_users'];
	$company_id = $data['company_id'];
	$route_id = $data['current_route'];
	$trip_id = $data['current_trip'];
	$client_phone = $data['client_phone'];
	$client_email = $data['client_email'];
	$client_address = $data['destination_address'];
	$client_lat = $data['end_lat'];
	$client_lng = $data['end_lng'];
	$pickup_address = $data['pickup_address'];
	$pick_lat = $data['pick_lat'];
	$pick_lng = $data['pick_lng'];
	$out_pickup = $data['pickup_out'];
	$in_pickup = $data['pickup_in'];
	$out_destination = $data['destination_out'];
	$in_destination = $data['destination_in'];
	$alert_distance = $data['distance_to_alert'];


	//echo $driver_phone;
	//exit;

	$sms_conts = array();
	$email_conts = array();

	if (strlen(trim($alert_users)) > 0) {
		$aSql = "SELECT iu.*
			FROM itms_users iu 
				WHERE 1
				AND
					iu.user_id IN($alert_users)
				AND 
					iu.del_date IS NULL AND iu.status = 1";

		$aRs = mysql_query($aSql);

		while($data = mysql_fetch_array($aRs)) {
			$sms_allowed = $data['sms_alert'];
			$email_allowed = $data['email_alert'];

			if($sms_allowed == 1) {
				array_push($sms_conts, $data['phone_number']);
			}

			if($email_allowed == 1) {
				array_push($email_conts, $data['email_address']);
			}
		}	
	}

	
	list($year, $month, $day, $hour, $min, $sec) = array(substr($date,0,2), substr($date,2,2), substr($date,4), substr($time,0,2), substr($time,2,2), substr($time,4,2));
	$ist = $gmt = date(DATE_TIME,mktime($hour, $min, $sec, $month, $day, $year));
	
	$g_date = date(DATE, strtotime($ist) + (3*60*60));
	$g_time = date(TIME, strtotime($ist) + (3*60*60));

	$hdate = date('jS M Y H:i:s', strtotime($ist) + (3*60*60));
	$ist = date('Y-m-d H:i:s', strtotime($ist) + (3*60*60));

	//print_r($hdate);



	$latitude = deg_to_decimal($latitude.$latitude_indicator);
	$longitude = deg_to_decimal($longitude.$longitude_indicator);
	$x_address = getNearest($latitude,$longitude);
	$current_area_id = NULL; 							//getCurrentArea($latitude,$longitude);
	$current_landmark_id = NULL; 						//getCurrentLandmark($latitude,$longitude); 
	$overspeed = false;

	//Near Pickup
	/*$latitude = "-4.08271";
	$longitude = "39.65825330000007";

	//Near destination
	$latitude = "-1.2901665";
	$longitude = "36.82384980000006";*/



	$speed = intval($speed);

	//$alert_permissions = get_alert_permissions($data['company_id']);
	
	print_r("Speed Check Initialised..\n");
	
	print_r("  [Speed: $speed Kmh]\n");
	
	if ($speed > $max_speed) {

		//print_r("Overs : Phone $add_user_phone");
		
		print_r("  [Overspeed detected: limit $max_speed Kmh]\n");

		$check_last = check_overspeed_log($device_id, $x_address, $ist);

		if ($check_last == false) {

			$message = "Overspeeding!! Driver: $driver_name ($assets_name). Speed Limit Exceeded: ". intval($speed) ." Kmh (Limit: $max_speed Kmh). Location: $x_address on $hdate";

			$overspeed = true;
			$speedReport = save_speed_report($asset_id, $device_id, $driver_id, $speed, $max_speed, $g_date, $g_time,
									$current_landmark_id, $current_area_id, $x_address, $add_user_id, $driver_phone, $message, $ist, $add_company_id);

			if ($speedReport) {
				print_r("  >> Overpeed Report Saved\n");
			}


			$sendSpeedText = false;
			$sendSpeedEmail = false;

			/*foreach ($alert_permissions as $key => $value) {
				if ($value['alert_name'] == 'User Speed Alert') {
					$recs = explode(',', $value['recievers']);
					if ((in_array($add_user_id, $recs) || in_array('all', $recs)) && $value['it_sms'] == 1) {
						$sendSpeedText = true;
					}

					if ((in_array($add_user_id, $recs) || in_array('all', $recs)) && $value['it_email'] == 1) {
						$sendSpeedEmail = true;
					}
				}
			}*/

			$alerts_enabled = false;

			$aSql = "SELECT itms_repeat_alert.repeat_interval FROM itms_repeat_alert WHERE company_id = $add_company_id";
			$query = mysql_query($aSql);
			$aRowCount = false;

			if($query) {
				$aRowCount = mysql_num_rows($aRs);

				if ($aRowCount) {
					$data = mysql_fetch_array($query);
					$interval = $data['repeat_interval'];
					$interval = $interval * 60 ;
				} else {
					$interval = 1800;
				}

			} else {
				$interval = 1800;
			}

			$interval = 300;



			$now_t = strtotime($ist);
			$countNotify = false;

			//$tyre_id_alerts = implode(',', $tyre_id_alerts);

			$query = "SELECT * FROM itms_notification_time WHERE 1
					AND
						alert_type = 'overspeeding'
					AND 
						device_id = '".addslashes($device_id)."'";

			$result = mysql_query($query);


			

			if($result) {
				$countNotify= mysql_num_rows($result);
				if($countNotify) {
					$dbdata = mysql_fetch_array($result);
					
					$d = strtotime($dbdata['last_notification_time']);
					$diff = $now_t - $d;

					//$difference = array_diff($tyre_id_alerts, explode(',', $dbdata['notify_on']));

					if($diff > $interval) {
						$alerts_enabled = true;
					}


				} else {
					$alerts_enabled = true;
					
				}	
			} else {
				$alerts_enabled = true;

			}	




			if($alerts_enabled) {


				if ($countNotify) {
		   			$query = "UPDATE itms_notification_time
		   						SET 
		   							last_notification_time = '".$ist."' 
		   					WHERE alert_type = 'overspeeding' AND device_id = '".addslashes($device_id)."'";

					$result = mysql_query($query);
		   		} else {
		   			$query = "INSERT INTO itms_notification_time
		   						SET 
		   							last_notification_time = '".$ist."' ,
		   							alert_type = 'overspeeding',
		   							device_id = '".addslashes($device_id)."'";

					$result = mysql_query($query);
		   		}

				$reason = 'Overspeeding';
					
				if (sizeof($sms_conts)) {

					print_r("  [SMS alerts activated]\n");
					
					$reciever = $sms_conts;
					array_push($reciever, $add_user_phone);
					//$message = "Overspeeding! Name: $driver_name ($assets_name). Speed Limit Exceeded at ". intval($speed) ." Kmh - Maximum Speed Limit is : $max_speed Kmh";

					$sent = false;
					
					$sent = send_text_message ($device_id, $reciever, $reason, $message);
					
					if ($sent) {
						print_r("  >> Overspeeding sms alert sent successfully\n");
					} else {
						print_r("  >> Failed to send overspeeding sms alert\n");
					}

				} else {
					print_r("  No SMS contacts - Notifying admin\n");
				}

				


				if (sizeof($email_conts)) {

					$message .= '. Click <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude.','.$longitude.'('.$assets_name.')&ie=UTF8&z=12&om=1">here</a> to view on map.';
					
					print_r("  [Emailing permissions granted]\n");
					$to = array();

					$to = $email_conts;
					$email_subject = "$reason : $driver_name ($assets_name)";
					//$message = "Overspeeding! Name: $driver_name ($assets_name). Speed Limit Exceeded at ". intval($speed) ." Kmh - Maximum Speed Limit is : $max_speed Kmh";
					
					$sent = false;
					$sent = send_email_message ($add_company_id, $device_id, $to, $reason, $email_subject,$message);
					
					if ($sent) {
						print_r("  >> Overspeed email alert sent successfully\n");
					} else {
						print_r("  >> Failed to send overspeeding email alert\n");
					}

				} else {
					print_r("  No email contacts - Notifying admin\n");
				}
			} else {
				print_r(" Alerts Disabled:Notifications sent within the set interval : " .$interval/60 ." minutes\n");
			}
		}	
	} else {
		print_r("  [Asset at allowed speed]\n");
	}
	
	print_r("Status: Speed Check completed\n\n");

	//exit;

	//Check if cordinates are valid
	print_r("Cordinates validation initialized..\n");

	if((intval($latitude) != 0 && intval($longitude) != 0)) {
		
		print_r("  [Valid Coordinates]\n");
		print_r("  [Address:$x_address]\n\n");
		

		//Check if near destination
		if ($trip_id!=null || $trip_id!="" || $client_phone !=null || $client_email != null || $client_phone!="" || $client_email != "") {

			print_r("Trip Check initiated...\n\n");


			
			if ($out_pickup == 0 || $in_pickup == 0) {	
				print_r("Checking pickup location...\n");
			
				checkPickup($add_company_id, addslashes($device_id), $asset_id, $driver_id, $trip_id, $assets_name, $nick_name, $driver_name, $driver_phone, addslashes($latitude), addslashes($longitude), $speed, $ist, $hdate, $odomVal,$client_phone,$client_email, $pick_lat, $pick_lng, $pickup_address, $x_address, $sms_conts,$email_conts, $alert_distance);
				
			} else if ($in_pickup == 1 && $out_pickup == 0) {
				print_r(" [ Asset at pickup location ]\n\n");
			} else if ($in_pickup == 1 && $out_pickup == 1) {
				print_r(" [ Asset Departed pickup location ]\n\n");
			}			
			
				
			if($out_pickup == 1 && ($out_destination == 0 || $in_destination == 0)) {
				print_r("Checking destination...\n");
			
				checkDestination($add_company_id, addslashes($device_id), $asset_id, $driver_id, $trip_id, $assets_name, $nick_name, $driver_name, $driver_phone, addslashes($latitude), addslashes($longitude), $speed, $ist, $hdate, $odomVal,$client_phone, $client_email, $client_lat, $client_lng, $client_address, $x_address, $sms_conts,$email_conts, $alert_distance);
				print_r("Status: Destination check completed\n\n");
			} else if ($in_destination == 1 && $out_destination == 0) {
				print_r(" [ Asset at destination location ]\n\n");
			} else if ($in_destination == 1 && $out_destination == 1) {
				print_r(" [ Asset Departed destination location ]\n\n");
			}

			
		} else {
			print_r("Status: Trip not defined\n\n");
		}

		//Check for landmarks nearby
		print_r("Landmarks Check initiated...\n");

		checkLandmark(addslashes($device_id), $asset_id, $driver_id,$assets_name, $nick_name, $driver_name, $driver_phone,
							addslashes($latitude), addslashes($longitude), $speed, $ist, $odomVal,$sms_conts,$email_conts, $trip_id, $client_phone, $client_email);
		
		print_r("Status: Landmark check completed\n\n");

		if ($route_id!=null || $route_id!="") {
			print_r("Route Adherance Check initiated...\n");
			checkRoute($add_company_id , addslashes($device_id), addslashes($route_id), $asset_id, $driver_id, $assets_name, $nick_name, $driver_name, $driver_phone,addslashes($latitude), addslashes($longitude), $x_address, $speed, $hdate, $ist ,$odomVal,$sms_conts,$email_conts);
			print_r("Status: Route check completed\n\n");
		}

		
		
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
			  					asset_id = '".$asset_id."',
			  					driver_id = '".$driver_id."',
		    					company_id = '".$add_company_id."',				  				 
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
		    					asset_id = '".$asset_id."',
		    					driver_id = '".$driver_id."',
		    					company_id = '".$add_company_id."',
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
		  				(company_id, asset_id, driver_id,latitude, longitude, add_date, speed, 
		  					device_id, gps_availability, dt, tm, ignition, overspeed, max_speed_limit, 
				  				stop_duration, angle_dir, address, command_key) 
					VALUES 
						('".$add_company_id."', '".$asset_id."', '".$driver_id."',".addslashes($latitude)."', '".addslashes($longitude)."', 
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