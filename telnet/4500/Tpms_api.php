<?php

require_once("../db.php");
require_once("functions.php");

$dummydt = date('ymd');
$dummytm = date('His');


$input= $_REQUEST['tpms'];
print_r("<pre>");
/*$input="010000000090TPMS".$dummydt . $dummytm."55AA01FFFF08F655AA02FFFF08F555AA03FFFF08
F455AA04FFFF08F355AA05FFFF08F255AA06FFFF08F155AA07FFFF08F055AA08FFFF08FF55AA09FF
FF08FE55AA0AFFFF08FD55AA0BFFFF08FC55AA0CFFFF08FB55AA0DFFFF08FA55AA0EFFFF08F955AA
0FFFFF08F855AA10FFFF08E755AA11FFFF08E655AA12FFFF08E555AA13FFFF08E455AA14FFFF08E3
55AA15FFFF08E255AA16FFFF08E155AA17FFFF08E055AA18FFFF08EF55AA19FFFF08EE55AA1AFFFF";
*/
$input = trim($input);
$input = preg_replace('/\s+/', ' ', $input);
$input = str_replace("	","",$input);
$input= str_replace(" ","",$input);

//Uweis:if input not posted or is blank write log

print_r("TPMS data check initialized...\n");
if(trim($input) == '') {
	WriteLogTpms("Blank Input String '$input'");
	die('Blank Input String');	
} 

print_r("  [Status: Ok]\n");
print_r("Saving raw data...\n"); 
save_raw($input);

function save_raw($input) {
	$aSql = "INSERT INTO tpms_raw_data 
				SET 
					raw_field = '". $input. "'";
	$aRs = mysql_query($aSql);

	if ($aRs) {
		print_r("  [Action completed successfully]\n");
	} else {
		print_r("  [Error: Failed to save raw data]\n");
	}
}


list($device_id, $command, $date, $time, $hexString) 
		= array(substr($input,0,12), substr($input,12,4), substr($input,16,6),substr($input,22,6), substr($input,28,364));

print_r("Tyre Info Check Initialized...\n");

if(trim($hexString) == '') {
	WriteLogTpms("Blank TPMS hex string:No tyres information");
	die('Blank TPMS hex string:No tyres information');	
}

print_r("  [Success: Valid Tyre info found]\n");

$aSql = "SELECT am.*, ip.*, am.company_id as add_company_id,
			(select group_concat(user_id) from itms_assigned_groups where assets_group_id = am.assets_group_id) AS alert_users
			
			FROM itms_assets am 
				LEFT JOIN itms_personnel_master ip ON (ip.personnel_id=am.personnel_id)
				WHERE 
					am.device_id = '".addslashes($device_id)."' 
				AND 
					am.del_date IS NULL AND am.status = 1";

	$aRs = mysql_query($aSql);
	$aRowCount = false;

	if($aRs) {
		$aRowCount = mysql_num_rows($aRs);
	}
	


	if(!$aRowCount) {
		WriteLog("Device ID: $device_id - Data Received, But No Assets Defined"); 
		die("Data Received, But No Assets Defined [Device ID: $device_id]");
	}

$data = mysql_fetch_array($aRs);
	$asset_id = $data['asset_id'];
	$assets_name = $data['assets_name'];
	$nick_name = $data['assets_friendly_nm'];
	$driver_name = $data['fname'] . ' ' . $data['lname'];
	$add_company_id = $data['add_company_id'];
	$max_speed = $data['max_speed_limit'];
	$driver_phone = $data['phone_no'];
	$driver_id = $data['personnel_id'];
	$no_of_tyres = $data['no_of_tyres'];
	$alert_users = $data['alert_users'];
	$company_id = $data['add_company_id'];
	$axle_tyre_config = $data['axle_tyre_config'];
	//$tyres = $data['no_of_tyres'];

	$pattern = explode('-', $axle_tyre_config);
	$arrayAxles = array('X1'=>'1,2', 'X2'=>'3,4,5,6','X3'=>'7,8,9,10', 'X4'=>'11,12,13,14', 'X5'=>'15,16,17,18', 'X6'=>'19,20,21,22','X7'=>'23,24,25,26');

	$tpmsData = array();
    $arrayIds = array('1,2','3,4,5,6','7,8,9,10','11,12,13,14','15,16,17,18','19,20,21,22','23,24,25,26'); 
                            
    $new_axlearray = array();
    $ids = array();

    foreach($pattern as $k=>$p) {

        if ($p == 'S' && $k>0) {
            $tyre_pos = $arrayIds[$k];
            $values  = explode(',', $tyre_pos);
            $values = $values[0].','.$values[3];
            $x = $k+1;
        } else if ($p == 'T' && $k>0) {
            $values = $arrayIds[$k];
            $x = $k+1;
        } else {
            $values = '1,2';
            $x = $k+1;
        }

        $new_axlearray['X'.$x] = $values;
        //array_push($ids,$values);
	}

    //$ids = implode(',', $ids);
    //$ids = explode(',', $ids);


    //GEt each axles' min and max pressure if already set

	$minmax_pressure = array();

    if ($no_of_tyres > 0) {
    	$aSql = "SELECT axle_no, min_pressure, max_pressure
			FROM itms_axle_pressure_config 
				WHERE 1
				AND
					asset_id = '".$asset_id."'";
		$aRs = mysql_query($aSql);
		while($data = mysql_fetch_array($aRs)) {
			$minmax_pressure [] = $data;
		}
    } 



    //print_r($minmax_pressure);
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


$tyres = str_split($hexString, 14);

$newTyres = array();

$keys = array('sync1', 'sync2', 'tyre_number', 'psi', 'temperature', 'status', 'checksum');

list($year, $month, $day, $hour, $min, $sec) = array(substr($date,0,2), substr($date,2,2), substr($date,4), substr($time,0,2), substr($time,2,2), substr($time,4,2));
	$ist = $gmt = date(DATE_TIME,mktime($hour, $min, $sec, $month, $day, $year));
	$g_date = date(DATE, strtotime($ist));
	$g_time = date(TIME, strtotime($ist));


	


print_r("Processing tyre information...\n");

foreach ($tyres as $k=>$tyre) {
	$info = str_split($tyre, 2);
	$data = array();
	$count = 0;

	$data['device_id'] = $device_id;
	$data['command'] = $command;
	$data['date_recieved'] = $ist;
	$data['company_id']  = $add_company_id;
	$data['asset_id'] = $asset_id;

	

	if (sizeof($info)==sizeof($keys)) {

		foreach ($info as $v=>$hex) {

			$bin=pack('H*',$hex);
			$bytes = unpack('C*', $bin);
			
			$value = $bytes[1];
			$key = $keys[$v];

			if ($v==3) {
				$data['pressure_decimal'] = $value;
				$data['kpa'] = round($value/0.1818 , 2);
				$data['bar'] = round(($data['kpa'] - 100)/100, 2);
				$psi = round($data['bar'] * 14.5);
				$value= ($psi > 0) ? $psi:0;
			}

			if ($v==4) {
				$data['temperature_decimal'] = $value;
				$degrees = $value - 50;
				$value = $degrees;
			}

			if ($v==5) {
				if ($hex == 0) {
					$status = 'Normal';
				} else if ($hex == 8) {
					$status = 'Tire leak';
				} else if ($hex == 10) {
					$status = 'Battery Low Voltage';
				} else if ($hex == 20) {
					$status = 'Loss of signal';
				} else if ($hex == 18) {
					$status = 'Tire leak, Battery Low Voltage';
				} else if ($hex == 30) {
					$status = 'Battery Low Voltage, Loss of signal';
				} else if ($hex == 28) {
					$status = 'Tire leak, Loss of signal';
				} else if ($hex == 38) {
					$status = 'Tire leak, Battery Low Voltage, Loss of signal';
				}

				$value = $status;
			}

			$data[$key] = $value;

			$count++;
		}
	}

	array_push($newTyres, $data);

}

$sorted = array();

$x = 1;
foreach ($newTyres as $kv => $tyre) {
	foreach ($keys as $k => $key) {
		if(!array_key_exists($key, $tyre)) {
			$tyre[$key] = '';

			if($key == 'tyre_number') {
				$tyre[$key] = $x;
			}
		}
	}

	$tyre['pressure_decimal']='';
	$tyre['temperature_decimal']='';
	$tyre['kpa']='';
	$tyre['bar']='';

	array_push($sorted, $tyre);
	$x++;
}

$newTyres = $sorted;

//print_r($newTyres);

$tyre_alerts = array();
$low_psi_alert = array();
$high_psi_alert = array();
$tyre_id_alerts = array();
$tyre_nbr = 1;
$count_alerts = 0;
$count_warnings = 0;
$count_dangers = 0;
$Alert_msg = "TPMS ALert:$assets_name - 
				$driver_name ($driver_phone).
				";

print_r("  [Action completed successfully]\n\n");
print_r("Tyre status check initilized...\n");



foreach ($newTyres as $key => $tyre) {

	$tn = $tyre["tyre_number"];

	if($tyre_nbr <=$no_of_tyres) {

		foreach ($new_axlearray as $s => $value) {
			$tconfs = explode(",", $value);
			if (in_array($tn, $tconfs)) {
				$this_axle = $s;
				
			}
		}

		$this_axle = str_replace("X","",$this_axle);
		//echo $this_axle;
		//exit;	

		$min_pressure_limit = 100;
        $max_pressure_limit = 130;	

		if (sizeof($minmax_pressure)) {
			foreach ($minmax_pressure as $v => $axle_pressure) {
				$axl = $axle_pressure["axle_no"];
				if ($this_axle == $axl) {
					$min_pressure_limit = $axle_pressure["min_pressure"];;
					$max_pressure_limit = $axle_pressure["max_pressure"];;
				}
			}
		}

		if ($tyre['psi'] < $min_pressure_limit) {
			$tyre['status'] = 'Low Pressure';
			$tyre['tnbr'] = $tyre_nbr;
			array_push($tyre_alerts, $tyre);
			array_push($low_psi_alert, $tyre_nbr);
			array_push($tyre_id_alerts, $tyre['tyre_number']);
			$count_alerts++;
			$count_warnings++;
			print_r("  >>Warning: Tyre ".$tyre_nbr." : low pressure\n");
		} else if ($tyre['psi'] > $max_pressure_limit){
			$tyre['status'] = 'High Pressure';
			$tyre['tnbr'] = $tyre_nbr;
			array_push($tyre_alerts, $tyre);
			array_push($high_psi_alert, $tyre_nbr);
			array_push($tyre_id_alerts, $tyre['tyre_number']);
			$count_alerts++;
			$count_dangers++;
			print_r("  >>Warning: Tyre ".$tyre_nbr." : high pressure\n");
		}
	}	

	$tyre_nbr++;
}

print_r("  [WARNING : ".$count_dangers." tyre(s): High pressure, ".$count_warnings." tyre(s): low Pressure]\n");
print_r("  [Status check completed]\n\n");

if ($count_alerts > 0) {

	if(sizeof($low_psi_alert)) {
			$Alert_msg .= "Tyre(s) " . implode(',', $low_psi_alert) . ' at Low pressure.';
		} 

		if(sizeof($high_psi_alert)) {
			$Alert_msg .= "Tyre(s) " . implode(',', $high_psi_alert) . ' at High pressure.';
		}

	$alertSql = "INSERT INTO itms_alert_master (company_id, alert_header, asset_id, driver_id,alert_msg, driver_phone, alert_type, user_id, data_time, add_date) 
						VALUES ('".$company_id."','Tyre pressure', '".$asset_id."', '".$driver_id."','".addslashes($Alert_msg)."', '".$driver_phone."', 'Alert', '1','".$ist."','".date(DATE_TIME)."')";
		mysql_query($alertSql) or die(mysql_error().":".$alertSql);
		
	print_r("Initializing alerts..\n");

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

	//$interval = 300;



	$now_t = strtotime($ist);
	$countNotify = false;

	//$tyre_id_alerts = implode(',', $tyre_id_alerts);

	$query = "SELECT * FROM itms_notification_time WHERE 1
			AND
				alert_type = 'pressure'
			AND 
				device_id = '".addslashes($device_id)."'";

	$result = mysql_query($query);


	

	if($result) {
		$countNotify= mysql_num_rows($result);
		if($countNotify) {
			$dbdata = mysql_fetch_array($result);
			
			$d = strtotime($dbdata['last_notification_time']);
			$diff = $now_t - $d;

			$difference = array_diff($tyre_id_alerts, explode(',', $dbdata['notify_on']));

			if($diff > $interval || sizeof($difference)) {

				$alerts_enabled = true;
			}


		} else {
			$alerts_enabled = true;
			
		}	
	} else {
		$alerts_enabled = true;

	}	


	$alerts_enabled = false;

   if($alerts_enabled) {

   		if ($countNotify) {
   			$query = "UPDATE itms_notification_time
   						SET 
   							last_notification_time = '".$ist."',
   							notify_on = '".addslashes(implode(',', $tyre_id_alerts))."' 
   					WHERE alert_type = 'pressure' AND device_id = '".addslashes($device_id)."'";

			$result = mysql_query($query);
   		} else {
   			$query = "INSERT INTO itms_notification_time
   						SET 
   							last_notification_time = '".$ist."' ,
   							alert_type = 'pressure',
   							notify_on = '".addslashes(implode(',', $tyre_id_alerts))."'
   							device_id = '".addslashes($device_id)."'";

			$result = mysql_query($query);
   		}

	   	 

		$reason = 'Tyre Pressure';
					
		if (sizeof($sms_conts)) {

			print_r("  [SMS alerts activated]\n");
			
			$reciever = $sms_conts;

			$sent = false;
			
			$sent = send_text_message ($device_id, $reciever, $reason, $Alert_msg);
			
			if ($sent) {
				print_r("  >> Tyre pressure sms alert sent successfully\n");
			} else {
				print_r("  >> Failed to send tyre sms alert\n");
			}

		} else {
			print_r("  No SMS contacts - Notifying admin\n");
		}

		if (sizeof($email_conts)) {

			print_r("  [Alerts Emailing enabled]\n");

			$to = $email_conts;
			$email_subject = "$reason : $assets_name - $driver_name ($driver_phone)";
			//$message = "Overspeeding! Name: $driver_name ($assets_name). Speed Limit Exceeded at ". intval($speed) ." Kmh - Maximum Speed Limit is : $max_speed Kmh";
			
			$sent = false;
			$sent = send_email_message ($add_company_id, $device_id, $to, $reason, $email_subject, $Alert_msg);
			
			if ($sent) {
				print_r("  >> Email alert sent successfully\n");
			} else {
				print_r("  >> Failed to send email alert\n");
			}

		} else {
			print_r("  No Email contacts - Notifying admin\n");
		}
	} else {
		print_r(" Alerts Disabled:Notifications sent within the set interval : " .$interval/60 ." minutes\n");
	}	

} else {
	print_r("  [TPMS condition stable]\n");
}

print_r("Database check Initialized..\n");


$query = "SELECT * FROM itms_tpms_live 
			WHERE device_id = '".addslashes($device_id)."'";

$result = mysql_query($query);
$countD= mysql_num_rows($result);
$dbdata = array();

while($arr_row = mysql_fetch_assoc($result)) {
	$dbdata[] = $arr_row;
}



if ($countD) {

	print_r("  [Device ID FOUND]\n\n");

	$date = date('Y-m-d H:i:s');

	print_r("Tracking device data...\n");
	foreach($dbdata as $k=>$tyre) {
		$sql = "INSERT INTO `itms_track_tpms_live`
					(`device_id`, `command`, `company_id` ,  `asset_id`, `tyre_number`, `pressure_decimal`, `kpa`, 
						`bar`, `psi`, `temperature_decimal`, `temperature`, `status`, `check_sum`, `date_recieved`, `date_saved`) 
				VALUES
						('".addslashes($tyre['device_id'])."', '".addslashes($tyre['command'])."', '".addslashes($tyre['company_id'])."', '".addslashes($tyre['asset_id'])."',
							'".addslashes($tyre['tyre_number'])."', '".addslashes($tyre['pressure_decimal'])."', 
								'".addslashes($tyre['kpa'])."', '".addslashes($tyre['bar'])."', '".addslashes($tyre['psi'])."', 
									'".addslashes($tyre['temperature_decimal'])."', '".addslashes($tyre['temperature'])."', 
										'".addslashes($tyre['status'])."', '".addslashes($tyre['check_sum'])."', '".addslashes($ist)."','".addslashes($date)."')";

			$track_res = mysql_query($sql) or die(mysql_error().":".$sql);	
	}

	print_r("Purging temporary Device data...\n\n");

	$sql = "DELETE FROM `itms_tpms_live` WHERE device_id='".$device_id."'";
	$res = mysql_query($sql) or die(mysql_error().":".$sql);	
	
	print_r("Update Initialized...\n");

} else {
	print_r("  [Untracked Device ID Detected]\n\n");
	print_r("Begin tracking device...\n");
}


print_r("Processing: Saving device data..\n");

$save_count = 0;
$data_count = 0;
foreach ($newTyres as $key => $tyre) {

	$data_count++;
	$sql = "INSERT INTO `itms_tpms_live`
					(`device_id`,  `command`, `company_id` ,  `asset_id`, `tyre_number`, `pressure_decimal`, `kpa`, 
						`bar`, `psi`, `temperature_decimal`, `temperature`, `status`, `check_sum`, `date_recieved`) 
				VALUES
						('".addslashes($tyre['device_id'])."', '".addslashes($tyre['command'])."', '".addslashes($tyre['company_id'])."', '".addslashes($tyre['asset_id'])."', 
							'".addslashes($tyre['tyre_number'])."', '".addslashes($tyre['pressure_decimal'])."', 
								'".addslashes($tyre['kpa'])."', '".addslashes($tyre['bar'])."', '".addslashes($tyre['psi'])."', 
									'".addslashes($tyre['temperature_decimal'])."', '".addslashes($tyre['temperature'])."', 
										'".addslashes($tyre['status'])."', '".addslashes($tyre['checksum'])."', '".addslashes($ist)."')";

			$track_res = mysql_query($sql) or die(mysql_error().":".$sql);

			if ($track_res) {
				$save_count++;
			}	
}


if ($data_count!=$save_count) {
	print_r(" [WARNING :Action Incomplete - $save_count tyre info saved Out of $data_count]\n\n");
} else {
	print_r("  [SUCCESS : Action Completed Successfully.]\n\n");
}

print_r("Closing all open processes\n");
print_r("EXIT\n\n");






//print_r('<pre>');
//print_r ($newTyres);
//exit;




?>
