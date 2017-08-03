<?php
       
	require_once("db.php");
	require_once('PHPMailer/class.phpmailer.php');
	
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
	
	function create_image_file($str, $img_name) {
		
		$strCamData = $str;

		$StrAscii = "";
		While (strlen($strCamData) > 0) {
			$strTemp = substr($strCamData, 0, 2);    	
			$Value1 = chr(hexdec($strTemp));
			$StrAscii = $StrAscii.$Value1;
			//remove first 2 chars
			$strCamData = substr($strCamData, 2);
		}
		//8859_1 is the ISO code for ASCII plus Latin. Require this for image.
		mb_detect_encoding($StrAscii, 'UTF-8, ISO-8859-1',true);
		// write to file
		$fp = fopen(CAPTURE_PATH . $img_name, 'w');
		fwrite($fp, $StrAscii);
		fclose($fp);
	}
	
	function convert_time_zone($date_time, $to_tz, $ret_format = 'Y-m-d H:i:s', $from_tz = 'UTC') {
		$time_object = new DateTime($date_time, new DateTimeZone($from_tz));
		$time_object->setTimezone(new DateTimeZone($to_tz));
		return $time_object->format($ret_format);
    }

	function deg_to_decimal_satish($deg) {
		
		if($deg == '') return 0.000000;
		
		$sign = substr($deg, -1);
		
		if(strtoupper($sign) == "E" || strtoupper($sign) == "W") $sign = -1;
		if(strtoupper($sign) == "N" || strtoupper($sign) == "S") $sign = 1;
		
		$deg = substr($deg, 0, strlen($deg)-1);
		
		$deg = preg_replace('/^0+/','',$deg);
		
		$t_deg = explode('.', $deg);
		
		if(strlen($t_deg[0]) > 4) $len = 3; 
		elseif($sign==-1) $len = 1;
		else $len = 2;
		
		$degree = substr($deg, 0, $len);
		
		$deg = substr($deg, $len);
		
		$decimal = $sign * number_format(floatval((($degree * 1.0) + ($deg/60))),6);
		
		return $decimal;
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
	function deg_to_decimal_new($deg) {
		
		if($deg == '') return 0.000000;
				
		if($deg < 0)
			$sign = -1;
		else
			$sign = 1;
		
		$deg = substr($deg, 1, strlen($deg));
				
		$deg = preg_replace('/^0+/','',$deg);
		
		$degree = substr($deg, 0, 2);
		
		$deg = substr($deg, 2);
		
		$decimal = $sign * number_format(floatval((($degree * 1.0) + ($deg/60))),6);
		
		return $decimal;
	}
	
	function getNearest($lat, $lng){
		$query = "SELECT latitude, longitude, address, 
    ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
     * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) 
     * sin( radians( latitude ) ) ) ) AS distance 
   FROM tbl_geodata HAVING distance < 0.9 
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
	
	function distance($a, $b)
	{
		list($lat1, $lon1) = $a;
		list($lat2, $lon2) = $b;

		$theta = $lon1 - $lon2;
		$dist = sin(@deg2rad($lat1)) * sin(@deg2rad($lat2)) +  cos(@deg2rad($lat1)) * cos(@deg2rad($lat2)) * cos(@deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		return $miles;
	}

	function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
	{
	  $i = $j = $c = 0;
	  for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
	    if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
	    ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
		$c = !$c;
	  }
	  return $c;
	}


	function getAddress($lat, $lng) {
		
		$today = gmdate(DATE);
		
		if(intval($lat) == 0 && intval($lng) == 0) {
			return '';
		}

		$query = "SELECT address FROM tbl_geodata WHERE latitude = '$lat' AND longitude = '$lng' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		
		if(mysql_num_rows($res) > 0){
			$row = mysql_fetch_assoc($res);
			return $row['address'];
		}
		else {
			
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
			
			$insert = "INSERT INTO tbl_geodata(latitude, longitude, address, add_date, add_uid, status) VALUES ('".addslashes($lat)."', '".addslashes($lng)."', '".addslashes($address)."', '".$today."', 1, 1)";
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


	function yahooGeocode($lat,$lng){
		$address = "";
		//$strURL = "http://where.yahooapis.com/geocode?q=".$latlng."&gflags=R&appid=7c4fb61963dbd896a6237ba53d896f85629ee99b";
		$strURL = 'http://query.yahooapis.com/v1/public/yql?q='.urlencode('select * from geo.placefinder where text="'.$lat.','.$lng.'" and gflags="R"');
		$resURL = curl_init();
		curl_setopt($resURL, CURLOPT_URL, $strURL);
		curl_setopt($resURL, CURLOPT_RETURNTRANSFER, true);
		$xmlstr = curl_exec($resURL);
		
		$objDOM = new DOMDocument();
		
		if(! @$objDOM->loadXML($xmlstr)) {
			//die("XML Parsing Failed");
			return '';
		}
		if( strstr(@$objDOM->getElementsByTagName("line2")->item(0)->nodeValue,"$lat")){
			return '';
		}
		
		$address = @$objDOM->getElementsByTagName("line1")->item(0)->nodeValue;
		if( @$objDOM->getElementsByTagName("line2")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("line2")->item(0)->nodeValue;
		}
		if( @$objDOM->getElementsByTagName("line3")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("line3")->item(0)->nodeValue;
		}
		if( @$objDOM->getElementsByTagName("line4")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("line4")->item(0)->nodeValue;
		}
		$address = iconv('UTF-8', '', $address);
		return $address;
	}

	function MapQuestGeocode($lat,$lng){
	
		$strURL = "http://www.mapquestapi.com/geocoding/v1/reverse?key=Fmjtd|luuan16b21%2Ca2%3Do5-96rauy&lat=$lat&lng=$lng&callback=renderReverse&inFormat=kvp&outFormat=xml";
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
		
		$address = @$objDOM->getElementsByTagName("street")->item(0)->nodeValue;
		if( @$objDOM->getElementsByTagName("adminArea5")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("adminArea5")->item(0)->nodeValue;
		}
		if( @$objDOM->getElementsByTagName("adminArea3")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("adminArea3")->item(0)->nodeValue;
		}
		if( @$objDOM->getElementsByTagName("adminArea4")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("adminArea4")->item(0)->nodeValue;
		}
		if( @$objDOM->getElementsByTagName("adminArea1")->item(0)->nodeValue!=""){
			$address .= ", ".@$objDOM->getElementsByTagName("adminArea1")->item(0)->nodeValue;
		}
		$address = iconv('UTF-8', '', $address);
		return $address;
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
			 }  
			 else if ($unit == "N") {  
				  $distance = ($miles * 0.8684);  
			 }  
			 else {  
				 $distance = $miles;  
			 }
		}
		return $distance;
	}

	function chat_alert($to_mail, $to_mesg) {
		if($to_mail != '' && $to_mesg != '') {
			
			$data = array('to' => $to_mail, 'msg' => $to_mesg);
			
			$ch = curl_init();
			$url = "http://localhost/telnet/chat_alert.php";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	
			$curl_result = curl_exec ($ch);
			curl_close ($ch);
		}
	}
	
	function WriteLog($string) {
		$myFile = "log_".date(DISP_DATE).".txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		$current = date(DISP_TIME);
		$stringData = "[$current] : $string\r\n\r\n";
		fwrite($fh, $stringData);
		fclose($fh);		
	}


	function sec2HourMinute($seconds){
		
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		//$seconds = $seconds % 60;
		$HourMinute = "";
		if($hours > 0)		$HourMinute .= "$hours Hours ";
		if($minutes > 0)	$HourMinute .= "$minutes Minutes ";
		
		return $HourMinute;
	}

	function send_sms($mobile, $smsText, $template=0, $template_data = NULL) {
	   return true;
	  
	}

	function send_email($to, $subject, $msg) {
		 return true;

	}
	function sms_log($mobile, $smsText, $user_id = 1) {
		$mobile = explode(",", $mobile);
		$values = array();
		foreach($mobile as $mob){
			$values[] = "($user_id, '$mob', '$smsText', '".gmdate(DATE_TIME)."')";
		}
		$values = implode(",", $values);
		$sqlU = "INSERT INTO smslog (user_id, mobile, sms_text, add_date) VALUES $values";
		mysql_query($sqlU) or die(mysql_error().":".$sqlU);
	}
	
	function email_log($emailid, $smsText, $user_id = 1, $desc = "") {
		$emailid = explode(",", $emailid);
		$values = array();
		foreach($emailid as $em){
			$values[] = "($user_id, '$em', '$smsText', '$desc', '".gmdate(DATE_TIME)."')";
		}
		$values = implode(",", $values);
		$sqlU = "INSERT INTO emaillog (user_id, email_id, email_text, description, add_date) VALUES $values";
		mysql_query($sqlU) or die(mysql_error().":".$sqlU);
	}

	function final_result_xml($theString, $bool, $SQLError='', $ex_tag = '') {

		$myFile = "xml_log_".date(DISP_DATE).".txt";
		if($SQLError != '') {
			$fh = fopen($myFile, 'a') or die("can't open file");
			$stringData = "\r\n" . CURRENT_TIME . " : " . $theString ." : SQL : ". $SQLError;
			fwrite($fh, $stringData);
			fclose($fh);
		}

		$final_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<root>";
		$final_xml .= "<time>".CURRENT_TIME."</time>\n\n<result>$bool</result>\n<message>$theString</message>";

		if($ex_tag != '') {
			$final_xml .= $ex_tag;
		}
		$final_xml .=  "\n</root>";
		@mysql_close();
		die($final_xml);
	}

	function log_raw_data($device, $data) {
	
		//writelog("time".date(DATE_TIME));
		$rawsql = "INSERT INTO tbl_raw_data (device_id, raw_data, add_uid, add_date) VALUES ('".$device."','".$data."','1','".date(DATE_TIME)."')";
		
		$raw_res = mysql_query($rawsql) or die(mysql_error().":".$rawsql);
		
	}
	
	function area_in_out($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $longitude_x, $latitude_y, $current_speed, $ist){
		global $current_area, $current_area_id, $dts;
		
		$insert_data = false;
		
		$sqlP = "SELECT DISTINCT (am.polyid) AS area_id, am.out_alert, am.in_alert, am.speed_value, am.speed_unit, am.email_alert as email_alert, am.sms_alert as sms_alert, um.user_id, um.first_name, um.username, um.mobile_number, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, (SELECT group_concat(mobile_no) as mobile_no FROM addressbook where find_in_set(id, am.addressbook_ids)) as addressbook_mobile FROM areas am LEFT JOIN tbl_users um ON um.user_id = am.Audit_Enter_uid WHERE FIND_IN_SET( $assets_id, deviceid ) and am.Audit_Del_Dt is null and am.Audit_Status = 1";

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
			$language =  $rowP['language'];
			if($language == "portuguese"){
				$file = "portuguese_alert_lang.php";
			}
			else{
				$file = "english_alert_lang.php";
				
			}	
			include($file);	
			/*
			if($l_speed) $current_speed = convertSpeed($current_speed, $l_unit);
			
			if($current_speed > $l_speed) {
				
			}
			*/
			
			$sql = "SELECT * FROM areas WHERE polyid = $area_id";
			$rs = mysql_query($sql) or die("Failed to Execute, SQL : $sql, Error : " . mysql_error());
			$vertices_x = array();
			$vertices_y = array();
			while($row = mysql_fetch_array($rs)){
				$point_id = $row['pointid'];
				$vertices_x[$point_id] = $row['lat'];
				$vertices_y[$point_id] = $row['lng'];
				$area_name = $row['polyname'];
			}

			$vx = array_values($vertices_x);
			$vy = array_values($vertices_y);

			//$vertices_x = array(22.304732, 22.304573, 22.315134, 22.315809); // x-coordinates of the vertices of the polygon
			//$vertices_y = array(70.763755,70.77178,70.761781,70.771737); // y-coordinates of the vertices of the polygon
			$points_polygon = count($vx); // number vertices

			//$longitude_x = $_GET["longitude"]; // x-coordinate of the point to test
			//$latitude_y = $_GET["latitude"]; // y-coordinate of the point to test


			//// For testing.  This point lies inside the test polygon.
			// $longitude_x = 37.62850;
			// $latitude_y = -77.4499;
			
			$sql = "SELECT * FROM area_inout_log where area_id = $area_id and device_id = $assets_id order by id desc limit 1";
			$rs = mysql_query($sql) or die(mysql_error().":".$sql);
			if(mysql_num_rows($rs) > 0){ 
				$row = mysql_fetch_array($rs);
				if($row['inout_status'] == 'in')
					$status = 1;
				else
					$status = 0;
			}else{
				$status = 0;
			}
			if (is_in_polygon($points_polygon, $vx, $vy, $longitude_x, $latitude_y)){
				//echo "In polygon!";
				//WriteLog("\nArea Name : $area_name");
				$current_area = $area_name;
				$current_area_id = $area_id;
				if($status == 0){
					
					$areaLogSql = "insert into area_inout_log (user_id, device_id, area_id, lat, lng, date_time, inout_status) values($user_id, '".$assets_id."', '".$area_id."', '".$longitude_x."', '".$latitude_y."', '".date(DATE_TIME)."', 'in')";
					mysql_query($areaLogSql) or die(mysql_error().":".$areaLogSql);
					$insert_data = true;
					
					$smsText = $lang['Alert for']." $assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['Entered Area']." $area_name, " . convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME);

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
						// $smsText .= ', Click <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$longitude_x.','.$latitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">here</a> to view on map.';
						$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$longitude_x.','.$latitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
						
						send_email($email, $lang['In Area Alert to User'], $smsText);
						email_log($email, $smsText, $user_id, 'In Area Alert to User');
						
						// chat_alert($email, $smsText);
					}
					if($addressbook_mobile != ""){					
					    //send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					
					//insert in alert master
					$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['In Area Alert to User']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
					mysql_query($alertSql) or die(mysql_error().":".$alertSql);
				}		
			}
			else{
				//echo "Is not in polygon";
				if($status == 1){
					
					$areaLogSql = "INSERT INTO area_inout_log (user_id, device_id, area_id, lat, lng, date_time, inout_status) VALUES ($user_id, '".$assets_id."', '".$area_id."', '".$longitude_x."', '".$latitude_y."', '".date(DATE_TIME)."', 'out')";
					mysql_query($areaLogSql) or die(mysql_error().":".$areaLogSql);
					$insert_data = true;

					//Dear $fname, 
					$smsText = "$assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['is now out of area']." $area_name, ".convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME);
					
					
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
						$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$longitude_x.','.$latitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
						send_email($email, $lang['Out of Area alert to User'], $smsText);
						email_log($email, $smsText, $user_id, 'Out of Area alert to User');
						// chat_alert($email, $smsText);
					}
					
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);					
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);		
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					//insert in alert master
					$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Out of Area alert to User']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
					mysql_query($alertSql) or die(mysql_error().":".$alertSql);
				}		
			}
		 }
		 return $insert_data;
	}

	function zone_in_out($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $longitude_x, $latitude_y, $current_speed, $ist){
		global $current_zone, $current_zone_id, $dts;
		
		$insert_data = false;
		
		$sqlP = "SELECT DISTINCT (am.polyid) AS area_id, am.out_alert, am.in_alert, am.speed_value, am.speed_unit, am.email_alert as email_alert, am.sms_alert as sms_alert, um.user_id, um.first_name, um.username, um.mobile_number, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, (SELECT group_concat(mobile_no) as mobile_no FROM addressbook where find_in_set(id, am.addressbook_ids)) as addressbook_mobile FROM landmark_areas am LEFT JOIN tbl_users um ON um.user_id = am.Audit_Enter_uid WHERE FIND_IN_SET( $assets_id, deviceid ) and am.Audit_Del_Dt is null and am.Audit_Status = 1";

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
			$language =  $rowP['language'];
			if($language == "portuguese"){
				$file = "portuguese_alert_lang.php";
			}
			else{
				$file = "english_alert_lang.php";
				
			}	
			include($file);	
			if($l_speed) $current_speed = convertSpeed($current_speed, $l_unit);
			
			if($current_speed > $l_speed) {
				
			}
			
			$sql = "SELECT * FROM landmark_areas WHERE polyid = $area_id";
			$rs = mysql_query($sql) or die("Failed to Execute, SQL : $sql, Error : " . mysql_error());
			$vertices_x = array();
			$vertices_y = array();
			while($row = mysql_fetch_array($rs)){
				$point_id = $row['pointid'];
				$vertices_x[$point_id] = $row['lat'];
				$vertices_y[$point_id] = $row['lng'];
				$area_name = $row['polyname'];
			}

			$vx = array_values($vertices_x);
			$vy = array_values($vertices_y);

			//$vertices_x = array(22.304732, 22.304573, 22.315134, 22.315809); // x-coordinates of the vertices of the polygon
			//$vertices_y = array(70.763755,70.77178,70.761781,70.771737); // y-coordinates of the vertices of the polygon
			$points_polygon = count($vx); // number vertices

			//$longitude_x = $_GET["longitude"]; // x-coordinate of the point to test
			//$latitude_y = $_GET["latitude"]; // y-coordinate of the point to test


			//// For testing.  This point lies inside the test polygon.
			// $longitude_x = 37.62850;
			// $latitude_y = -77.4499;
			
			$sql = "SELECT * FROM zone_inout_log where area_id = $area_id and device_id = $assets_id order by id desc limit 1";
			// WriteLog("\nPoly SQL : $sql");
			$rs = mysql_query($sql) or die(mysql_error().":".$sql);
			if(mysql_num_rows($rs) > 0){ 
				$row = mysql_fetch_array($rs);
				if($row['inout_status'] == 'in')
					$status = 1;
				else
					$status = 0;
			}else{
				$status = 0;
			}

			/*
			if($area_id == 44 && $assets_id == 381) {
				$sub[] = print_r($vx, true);
				$sub[] = print_r($vy, true);
				$sub[] = $points_polygon;
				$sub[] = $longitude_x;
				$sub[] = $latitude_y;
				$sub_str = implode(': ', $sub);
				$polygon = is_in_polygon($points_polygon, $vx, $vy, $longitude_x, $latitude_y);
				WriteLog("\ZONE SQL : $sql, $sub_str, Polygon Status : $polygon");
			}
*/
			
			if (is_in_polygon($points_polygon, $vx, $vy, $longitude_x, $latitude_y)){
				//echo "In polygon!";
				$current_zone = $area_name;
				$current_zone_id = $area_id;
				if($status == 0){
					
					$areaLogSql = "insert into zone_inout_log (user_id, device_id, area_id, lat, lng, date_time, inout_status) values($user_id, '".$assets_id."', '".$area_id."', '".$longitude_x."', '".$latitude_y."', '".date(DATE_TIME)."', 'in')";
					mysql_query($areaLogSql)or die(mysql_error().":".$areaLogSql);
					$insert_data = true;
					
					$smsText = $lang['Alert for']." $assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['Entered Zone']." $area_name, ".convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME);

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
					
					if($mobile != "" && $user_sms_alert == 1 && $area_sms_alert == 1 && $in_alert == 1){
						send_sms($mobile, $smsText, $template_id, $template_data);
						sms_log($mobile, $smsText, $user_id);
					}
					
					if($email!="" && $user_email_alert ==1 && $area_email_alert ==1 && $in_alert == 1) {
							$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$longitude_x.','.$latitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
						send_email($email, $lang['In Zone Alert to User'], $smsText);
						email_log($email, $smsText, $user_id, 'In Zone Alert to User');
						// chat_alert($email, $smsText);
					}
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					//insert in alert master
					if($area_sms_alert == 1 || $area_email_alert == 1) {
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Zone In Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
						// mysql_query($alertSql);
					}
				}		
			}
			else{
				//echo "Is not in polygon";
				if($status == 1){
					
					$areaLogSql = "INSERT INTO zone_inout_log (user_id, device_id, area_id, lat, lng, date_time, inout_status) VALUES ($user_id, '".$assets_id."', '".$area_id."', '".$longitude_x."', '".$latitude_y."', '".date(DATE_TIME)."', 'out')";
					mysql_query($areaLogSql) or die(mysql_error().":".$areaLogSql);
					$insert_data = true;

					//Dear $fname, 
					$smsText = "$assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['is now out of zone']." $area_name, ". convert_time_zone($ist, $dts, DISP_TIME); // . date(DISP_TIME);
					
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
							$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$longitude_x.','.$latitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
						send_email($email, $lang['Out of Zone alert to User'], $smsText);
						email_log($email, $smsText, $user_id, 'Out of Zone alert to User');
						// chat_alert($email, $smsText);
					}
					
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);					
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);		
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					//insert in alert master
					if($area_sms_alert == 1 || $area_email_alert == 1) {
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Zone Out Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
						// mysql_query($alertSql);
					}
				}		
			}
		 }
		 return $insert_data;
	}

	function checkLandmark($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $lati, $longi, $current_speed, $ist, $odometer){
		
		global $current_landmark, $current_landmark_id, $dts;
		$insert_data = false;
		
		$sql = "SELECT group_concat(landmark_id) as device_landmark FROM assets_landmark WHERE assets_id = '$assets_id'";
		$rs = mysql_query($sql) or die(mysql_error().":".$sql);
		$row = mysql_fetch_array($rs);
		$device_landmark = $row['device_landmark'];
		
		$sqlP = "SELECT lm.*, um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, um.alert_stop_time, (SELECT group_concat(mobile_no) as mobile_no FROM addressbook where find_in_set(id, lm.addressbook_ids)) as addressbook_mobile FROM landmark lm left join tbl_users um on um.user_id = lm.add_uid WHERE FIND_IN_SET( $assets_id, lm.device_ids ) and lm.del_date is null and lm.status = 1";
		if($device_landmark != ""){
			$sqlP .= " and lm.id not in($device_landmark)";
		}
		$rs = mysql_query($sqlP) or die(mysql_error().":".$sqlP);
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
			$language               =  $row['language'];
			if($language == "portuguese"){
				$file = "portuguese_alert_lang.php";
			}
			else{
				$file = "english_alert_lang.php";
				
			}	
			include($file);	
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

			// WriteLog("\n$landmark_name : $distanceFromLandmark < $distance_value");
			
			if($distanceFromLandmark < $distance_value){	//"Device is near to Landmark"
				
				$checkLast = "SELECT landmark_id, in_out FROM landmark_log WHERE device_id = $assets_id and landmark_id = $landmark_id order by id desc limit 1";
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
					
					$distanceText = number_format($distanceFromLandmark, 2)." ".$distance_unit;
					
					$checkLast1 = "select landmark_id, odometer from landmark_log where device_id = $assets_id order by id desc limit 1";
					$checkRs1 = mysql_query($checkLast1) or die(mysql_error().":".$checkLast1);
					$checkRow1 = mysql_fetch_array($checkRs1);
					$last_landmark_id = $checkRow1['landmark_id'];
					$last_odometer = $checkRow1['odometer'];
					$distance_from_last = ($odometer - $last_odometer)/1000;
					$distance_from_last += $distanceFromLandmark;
					
					$ins = "INSERT INTO landmark_log(device_id, landmark_id, date_time, lat, lng, distance, in_out, odometer, last_landmark_id, distance_from_last) VALUES 	($assets_id, $landmark_id, '".date(DATE_TIME)."', '$lati', '$longi', '$distanceText', 'in', '$odometer', '$last_landmark_id', '$distance_from_last')";
					mysql_query($ins) or die(mysql_error().":".$ins);
					$insert_data = true;
					
					// Dear $fname, 
					$smsText = "$assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['is near landmark']." $landmark_name (".$lang['Distance']." : $distanceText), ".convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME);
					// $smsText .= ' Landmark Points : '.$row['lat'].', '.$row['lng'].' and Asset Points : '.$lati.', '.$longi;
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
						$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$lati.','.$longi.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
						send_email($email, $lang['Near Landmark Alert'], $smsText);
						email_log($email, $smsText, $user_id, 'Near Landmark Alert');
						// chat_alert($email, $smsText);
					}
					
					if($addressbook_mobile != ""){					//send sms addressbook contact
						//send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
						sms_log($addressbook_mobile, $smsText, $user_id);
					}
					
					//insert in alert master
					if($landmark_sms_alert == 1 || $landmark_email_alert == 1) {
						$alertSql = "INSERT INTO alert_master (alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ('".$lang['Near Landmark Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
						mysql_query($alertSql) or die(mysql_error().":".$alertSql);
					}
					
					/****************************/
					//rfid alert
					$sub_sql = "select * from tbl_rfid where del_date is null and status = 1 and landmark_id = $landmark_id";
					$sub_rs = mysql_query($sub_sql) or die(mysql_error().":".$sub_sql);
					while($sub_row = mysql_fetch_array($sub_rs)){
						$person 			= $sub_row['person'];
						$inform_mobile 		= $sub_row['inform_mobile'];
						$inform_email 		= $sub_row['inform_email'];
						$send_sms = $sub_row['send_sms'];
						$send_email 	= $sub_row['send_email'];

						//Dear $person
						$smsText = "$assets_name ($nick_name, $driver_name, $driver_mobile) is near landmark $landmark_name (Distance : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME); //date(DISP_TIME);
											
						if($inform_mobile != "" && $send_sms == 1 && $landmark_sms_alert == 1){
							send_sms($inform_mobile, $smsText);
							sms_log($mobile, $smsText, $user_id);
						}						
						if($inform_email!="" && $send_email == 1 && $landmark_email_alert == 1) {
							$smsText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$lati.','.$longi.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
							send_email($inform_email, $lang['RFID alert to Person'], $smsText);
							email_log($inform_email, $smsText, $user_id, $lang['RFID alert to Person']);
							// chat_alert($email, $smsText);
						}
					}
				}
			}else{		//out
				$checkLast = "select id from landmark_log where device_id = $assets_id and landmark_id = $landmark_id and in_out = 'in' order by id desc limit 1";
				$checkRs = mysql_query($checkLast) or die(mysql_error().":".$checkLast);
				
				if(mysql_num_rows($checkRs) > 0){
					$checkRow = mysql_fetch_array($checkRs);
					$lId = $checkRow['id'];
					
					//update next landmark
					$sqlAss = "select trip.landmark_ids, am.current_trip, am.next_trip_landmark from assests_master am left join tbl_routes trip on trip.id = am.current_trip where am.id = $assets_id";
					$rsAss = mysql_query($sqlAss) or die(mysql_error().":".$sqlAss);
					$rowAss = mysql_fetch_array($rsAss);
					if($rowAss['current_trip'] != "" && $rowAss['current_trip'] != 0){
						$landmark_ids = explode(",",$rowAss['landmark_ids']);
						if(in_array($landmark_id, $landmark_ids)){
							$lorder = array_search($landmark_id, $landmark_ids) + 1;
							$next_trip_landmark = $landmark_ids[$lorder];
							$nextLSql = "update assests_master set next_trip_landmark = $next_trip_landmark where id = $assets_id";
							mysql_query($nextLSql) or die(mysql_error().":".$nextLSql);
						}
					}
					
					$uLSql = "update landmark_log set in_out = 'out' where id = $lId";
					mysql_query($uLSql) or die(mysql_error().":".$uLSql);
					
					//update sub-route
					$tSql = "select trip_id from trip_log where device_id = $assets_id and is_complete = 0 order by id desc limit 1";
					$tRs = mysql_query($tSql) or die(mysql_error().":".$tSql);
					if(mysql_num_rows($tRs) > 0){
						$tRow = mysql_fetch_array($tRs);
						$trip_id = $tRow['trip_id'];
						$query = "update tbl_sub_routes set start_time = '".$ist."', start_km_reading = (select km_reading from assests_master where id = $assets_id) WHERE route_id = $trip_id and landmark_ids like '$lId,%' and start_time is null";
						mysql_query($query) or die(mysql_error().":".$query);
					}
					
					if($dealer_code != ""){
						//update user assets mapping, remove assets id
						$sub_sql = "select * from user_assets_map where user_id = (select user_id from tbl_users where username = '$dealer_code')";
						$sub_rs = mysql_query($sub_sql) or die(mysql_error().":".$sub_sql);
						if(mysql_num_rows($tRs) > 0){
							$sub_row = mysql_fetch_array($sub_rs);
							$uam_id = $sub_row['id'];
							$assetsIds = $sub_row['assets_ids'];
							$assetsIds = str_replace("$assets_id", "", $assetsIds);
							$assetsIds = str_replace(",,", ",", $assetsIds);
							$assetsIds = trim($assetsIds, ",");
							
							$sql = "update user_assets_map set assets_ids = '$assetsIds' where id = '$uam_id'";
							mysql_query($sql) or die(mysql_error().":".$sql);
							
						}
					}
				}
			}

			/*
			if($alert_before_landmark!=""){
			
				$distanceText = number_format($distanceFromLandmark, 2)." ".$distance_unit;
				if($distanceFromLandmark < $alert_before_landmark){	//"Device is near to Landmark"
					$checkLast = "select landmark_id from landmark_distance_log where device_id = $assets_id order by id desc limit 1";
					$checkRs = mysql_query($checkLast);
					$numRows = mysql_num_rows($checkRs);
					
					$checkRow = mysql_fetch_array($checkRs);
					$lastLandmarkId = $checkRow['landmark_id'];
					if(($lastLandmarkId != $landmark_id) || ($numRows==0)){
					
						$ins = "INSERT INTO landmark_distance_log (device_id, landmark_id, date_time, distance) values($assets_id, $landmark_id, '".date('Y-m-d H:i:s', strtotime($ist))."', '$distanceText')";
						mysql_query($ins);
															
						// Dear $dealer_fname, 
						$smsText = "$assets_name ($driver_name, $driver_mobile) is $distanceText away from Landmark $landmark_name, ".date(DISP_TIME, strtotime($ist));
						
						$emailText = $smsText;
						// WriteLog("$smsText");
						
						if($mobile != "" && $user_sms_alert == 1 && $landmark_sms_alert == 1 && $send_sms_now){
							send_sms($mobile, $smsText, $template_id, $template_data);
							sms_log($mobile, $smsText, $user_id);
						}
						
						if($email!="" && $user_email_alert == 1 && $landmark_email_alert == 1) {
							$smsText .= ', Click <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$lati.','.$longi.'('.$assets_name.')&ie=UTF8&z=12&om=1">here</a> to view on map.';
							send_email($email, "Device is near to Landmark", $smsText);
							email_log($email, $smsText, $user_id,'Device is near to Landmark');
							// chat_alert($email, $smsText);
						}
						
						if($addressbook_mobile != ""){					//send sms addressbook contact
							//send_sms($mobile, $smsText, $template_id, $template_data);
							send_sms($addressbook_mobile, $smsText, $template_id, $template_data);
							sms_log($addressbook_mobile, $smsText, $user_id);
						}

						if($landmark_sms_alert == 1 || $landmark_email_alert == 1) {
							$alertSql = "insert into alert_master(assets_id, alert_header, alert_msg, alert_type, user_id, add_date) values ($assets_id, 'Near Landmark Alert', '".$smsText."', 'alert', '".$user_id."', '".date(DATE_TIME)."')";
							mysql_query($alertSql);
						}
					}
				}
			}
			*/
		}
		return $insert_data;
	}

	function checkSpeed($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $max, $current_speed, $ist, $lati, $longi) {
		global $cross_speed, $dts;
		global $uRow;
		$insert_data = false;
		
		if($current_speed > $max && $max != "" && $max != 0){
			$speedSql = "select date_time from over_speed_report where assets_id = '$assets_id' order by id desc limit 1";
			$speedRs = mysql_query($speedSql);
			$minutes = 11;
			if(mysql_num_rows($speedRs) > 0){
				$speedRow = mysql_fetch_array($speedRs);
				$start = $speedRow['date_time'];
				$minutes = round(abs(strtotime($ist) - strtotime($start)) / 60,2);
			}
			if($minutes > 10){
			
				$us_sql = "SELECT um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, um.alert_stop_time, um.ignition_on_alert, um.ignition_off_alert FROM user_assets_map lm left join tbl_users um on um.user_id = lm.user_id WHERE FIND_IN_SET($assets_id, lm.assets_ids) and lm.del_date is null and lm.status = 1";
				// writeLog($us_sql);
				$rs = mysql_query($us_sql);
				while($row = mysql_fetch_array($rs)){
				
					$user_id = $row['user_id'];
					$fname = $row['first_name'];
					$mobile = $row['mobile_number'];
					$email = $row['email_address'];
					$user_sms_alert = $row['user_sms_alert'];
					$user_email_alert = $row['user_email_alert'];
					
					$language =  $row['language'];
					if($language == "portuguese"){
						$file = "portuguese_alert_lang.php";
					}
					else{
						$file = "english_alert_lang.php";
						
					}	
					include($file);	

					// Dear $fname, 
					$smsText = "$assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['cross the maximum speed limit']." (".$lang['Speed']." : $current_speed Km/H),".convert_time_zone($ist, $dts, DISP_TIME); 
					
                                        $smsText1 = "$assets_name ($nick_name, $driver_name, $driver_mobile) ".$lang['cross the maximum speed limit']." (".$lang['Speed']." : $current_speed Km/H),".convert_time_zone($ist, $dts, DISP_TIME); 
					
					$smsText1 .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$lati.','.$longi.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
                                      //Dear [F1], [F2] ([F3], [F4]), cross the maximum speed limit (Speed : [F5])
					$template_id = '3825';
					$f1 = $fname;
					$f2 = $assets_name;
					$f3 = $nick_name;
					$f4 = $driver_name;
					$f5 = $current_speed." Km/H";
					$f6 = ",". convert_time_zone($ist, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($ist));
					$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6);

					
					$cross_speed = 1;
					$insert_data = true;

					if($mobile != "" && $user_sms_alert == 1){
						// send_sms($mobile, $smsText, $template_id, $template_data);
						send_sms($mobile, $smsText);
						sms_log($mobile, $smsText, $user_id);
					}
					
					if($email!="" && $user_email_alert == 1) {
						send_email($email, $lang['Over Speed Alert'], $smsText1);	
						// chat_alert($email, $smsText);
						//writeLog($email."text:".$smsText1);
					}
					$speedSql = "insert into over_speed_report(user_id, assets_id, max_speed_limit, speed, date_time, comments) values ( '$user_id', '".$assets_id."', '$max', '$current_speed', '".$ist."', '".$smsText."')";
					mysql_query($speedSql);
				
					//insert in alert master
					$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Over Speed Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
					mysql_query($alertSql);
				}
			}
		}
		return $insert_data;
	}
/***********#Developed By: Prashant D. Date: 28-02-2015 For Stop Report#***************************/
	function stop_report_insert($speed, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $current)
	{	global $uRow;
		global $current_area;
		global $current_landmark;
		if($ignition==0){
			$ignitionStatus="Ignition Off";
		}else if($ignition==1){
			$ignitionStatus="Ignition On";
		}
		
		$query = "SELECT id, ignition_off, ignition_on, ignition_status, alert_given FROM tbl_stop_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query);
		$rowcount = mysql_num_rows($res);
		$row=mysql_fetch_array($res);
		$lastignitionstatus = $row['ignition_status'];
		$defaultspeed=0.5;
		if((floatval($speed) < $defaultspeed) && ($lastignitionstatus == $ignitionStatus))
		{
			if($rowcount  == 1) {
				$stop_report_id = $row['id'];
				if(trim($row['ignition_on'] != ""))
				{
					$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, ignition_status) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$ignitionStatus."')";
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

						//Dear $fname, 
						$smsText = "$assets_name ($nick_name, $driver_name, $driver_mobile) stop more than $stop_time,";
						$smsText1 = "$assets_name ($nick_name, $driver_name, $driver_mobile) stop more than $stop_time,";
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
							$smsText1 .= " near Landmark $current_landmark";
							list($f6, $f7) = str_split(" near Landmark $current_landmark", 30);
							if($f7 == "")	$f7 = " ";
						}else if($current_area != ""){
							$smsText .= " in Area $current_area";
							$smsText1 .= " in Area $current_area";
							list($f6, $f7) = str_split(" in Area $current_area", 30);
							if($f7 == "")	$f7 = " ";
						}
						else if($x_address){
							$smsText .= " at $x_address";
							$smsText1 .= " at $x_address";
							list($f6, $f7) = str_split(" at ".$x_address, 30);
							if($f7 == "")	$f7 = " ";
						}else{
							$f6 = " ";
							$f7 = " ";
						}
						$f8 = ",". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));
						$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7, "F8"=>$f8);	

						$smsText .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));	
						$smsText1 .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($current));	
						//////////
						
						if($mobile != "" && $user_sms_alert == 1){
							send_sms($mobile, $smsText, $template_id, $template_data);
							//send_sms($mobile, $alert_text);
							sms_log($mobile, $smsText, $user_id);
						}
						
						if($email!="" && $user_email_alert == 1) {
							
							$smsText1 .= ', Click <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude.','.$longitude.'('.$assets_name.')&ie=UTF8&z=12&om=1">here</a> to view on map.';
							send_email($email, "Vehicle Stop time more than set time", $smsText1);
							email_log($email, $smsText1, $user_id,'Vehicle Stop time more than set time');
							// chat_alert($email, $smsText);
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
			else if($rowcount == 0)
			{	
				$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, ignition_status) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$ignitionStatus."')";
					@mysql_query($query);
			}
		}
		else
		{
			if($rowcount == 1) {
				//$row = mysql_fetch_array($res);
				
				$row_id = $row['id'];
				
				if(trim($row['ignition_on']) == "") {
					

					$start = strtotime($row['ignition_off']);
					$end = strtotime($current);
					$delta = $end - $start;
					if($delta > 5){
						$hours = floor($delta / 3600);
						$remainder = $delta - $hours * 3600;
						$formattedDelta = sprintf('%02d', $hours) . date(':i:s', $remainder);
						$query="UPDATE tbl_stop_report SET ignition_on = '".$current."', duration='". addslashes($formattedDelta)."', add_date = '".$current."' WHERE device_id = $assets_id AND id = ".$row_id;
						@mysql_query($query);

						if(floatval($speed) < $defaultspeed)
						{
							$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, ignition_status) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$ignitionStatus."')";
							@mysql_query($query);
						}

					}
					
				} else 
					{	
                                             if(floatval($speed) < $defaultspeed)
                                            {
						$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, ignition_status) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$ignitionStatus."')";
							@mysql_query($query);
                                            }            
					}
			}
			else if($rowcount == 0)
			{	
				$query="INSERT INTO tbl_stop_report (device_id, ignition_off, address, lat, lng, ignition_status) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$ignitionStatus."')";
					@mysql_query($query);
			}
		}
		
	}

	function start_report_insert($speed, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $ist, $odometer)
	{
		$current = date(DATE_TIME);
		global $current_area;
		global $current_landmark;
		
		global $uRow;
		$query = "SELECT * FROM tbl_start_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		if($ignition == 1)
		{
			if(mysql_num_rows($res) == 1 ) {
				$row = mysql_fetch_array($res);
				if($row['ignition_flag'] == 1)
				{
					$query="INSERT INTO tbl_start_report (device_id, ignition_on, start_odometer, stop_odometer, distance, address, lat, lng, current_area, current_landmark, add_date, ignition_on_address) VALUES ('".addslashes($assets_id)."', '".$current."', '".$odometer."', '".$odometer."', 0,'".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."', '".$current."', '".addslashes($x_address)."')";
					mysql_query($query) or die(mysql_error().":".$query);
				}

				else if($row['ignition_flag'] == 0) {
					$row_id = $row['id'];
					$start = strtotime($row['ignition_on']);
					$end = strtotime($current);
					$delta = $end - $start;
					// if($assets_id == 407) writeLog("UPDATE tbl_start_report SET ignition_off = '".$current."', stop_odometer = '".$odometer."', duration = '". addslashes($formattedDelta)."', distance = ($odometer - start_odometer) WHERE device_id = $assets_id AND id = " . $row_id);
					if($delta > 5){
						$hours = floor($delta / 3600);
						$remainder = $delta - $hours * 3600;
						$formattedDelta = sprintf('%02d', $hours) . date(':i:s', $remainder);

						$query="UPDATE tbl_start_report SET ignition_off = '".$current."', stop_odometer = '".$odometer."', duration = '". addslashes($formattedDelta)."', distance = ($odometer - start_odometer) WHERE device_id = $assets_id AND id = " . $row_id;
						mysql_query($query) or die(mysql_error().":".$query);
					}

				}
			}
			else if(mysql_num_rows($res) == 0)
			{
				$query="INSERT INTO tbl_start_report (device_id, ignition_on, start_odometer, stop_odometer, distance, address, lat, lng, current_area, current_landmark, add_date, ignition_on_address) VALUES ('".addslashes($assets_id)."', '".$current."', '".$odometer."', '".$odometer."', 0,'".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."', '".$current."','".addslashes($x_address)."')";
				mysql_query($query) or die(mysql_error().":".$query);
			}
		}
		else
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$add_date = $row['add_date'];
                                 $avg_speed = 0;
                                $max_speed = 0;
                                $maxspeed_query = "select max(speed) as max_speed, avg(speed) as avg_speed from tbl_track where dt BETWEEN  '" . $add_date . "' AND '" . $current . "' and assets_id = $assets_id";
				$res1 = mysql_query($maxspeed_query) or die(mysql_error().":".$maxspeed_query);
                                if(mysql_num_rows($res1) == 1) {
				$row_speed = mysql_fetch_array($res1);
                                $max_speed = $row_speed['max_speed'];
                                $avg_speed = $row_speed['avg_speed'];
                               // Writelog($avg_speed." ".$max_speed." ".$maxspeed_query);
                                
                                }
				// If the data is coming for smae date.
//				if(date('Y-m-d', strtotime($add_date)) == date('Y-m-d')) {
					$row_id = $row['id'];
					if($row['ignition_flag'] == 0) {

						$start = strtotime($row['ignition_on']);
						$end = strtotime($current);
						$delta = $end - $start;
						if($delta > 5){
							$hours = floor($delta / 3600);
							$remainder = $delta - $hours * 3600;
							$formattedDelta = sprintf('%02d', $hours) . date(':i:s', $remainder);

							$query="UPDATE tbl_start_report SET ignition_off = '".$current."', stop_odometer = '".$odometer."', distance = ($odometer - start_odometer), duration='". addslashes($formattedDelta)."', ignition_flag = 1 , ignition_off_address = '".addslashes($x_address)."'  ,max_speed = '".$max_speed."' ,avg_speed = '".$avg_speed."' WHERE device_id = $assets_id AND id = " . $row_id;
							mysql_query($query) or die(mysql_error().":".$query);
						}
					}
//				}
			}
		}
	}
         function run_report($speed, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $ist, $km_reading)
	{
		$current = date(DATE_TIME);
		global $current_area;
		global $current_landmark;
		 // Writelog($km_reading." ".$ignition);
                  
		global $uRow;
		$query = "SELECT * FROM tbl_start_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		if($ignition == 1)
		{
			if(mysql_num_rows($res) == 1 && $speed > 2) {
				$row = mysql_fetch_array($res);
				if($row['ignition_flag'] == 1)
				{
					$query="INSERT INTO tbl_start_report (device_id, ignition_on, start_odometer, stop_odometer, distance, address, lat, lng, current_area, current_landmark, add_date,ignition_on_address) VALUES ('".addslashes($assets_id)."', '".$current."', '".$km_reading."', '".$km_reading."', 0,'".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."', '".$current."','".addslashes($x_address)."')";
					mysql_query($query) or die(mysql_error().":".$query);
				}

				else if($row['ignition_flag'] == 0) {
					$row_id = $row['id'];
					$start = strtotime($row['ignition_on']);
					$end = strtotime($current);
					$delta = $end - $start;
					if($delta > 5){
						$hours = floor($delta / 3600);
						$remainder = $delta - $hours * 3600;
						$formattedDelta = sprintf('%02d', $hours) . date(':i:s', $remainder);

						$query="UPDATE tbl_start_report SET ignition_off = '".$current."', stop_odometer = '".$km_reading."', duration = '". addslashes($formattedDelta)."', distance = ($km_reading - start_odometer) WHERE device_id = $assets_id AND id = " . $row_id;
						mysql_query($query) or die(mysql_error().":".$query);
					}

				}
			}
			else if(mysql_num_rows($res) == 0)
			{
				$query="INSERT INTO tbl_start_report (device_id, ignition_on, start_odometer, stop_odometer, distance, address, lat, lng, current_area, current_landmark, add_date,ignition_on_address) VALUES ('".addslashes($assets_id)."', '".$current."', '".$km_reading."', '".$km_reading."', 0,'".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."', '".$current."','".addslashes($x_address)."')";
				mysql_query($query) or die(mysql_error().":".$query);
			}
		}
		else
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$add_date = $row['add_date'];
                                $avg_speed = 0;
                                $max_speed = 0;
                                $maxspeed_query = "select max(speed) as max_speed, avg(speed) as avg_speed from tbl_track where dt BETWEEN  '" . $add_date . "' AND '" . $current . "' and assets_id = $assets_id";
				$res1 = mysql_query($maxspeed_query) or die(mysql_error().":".$maxspeed_query);
                                if(mysql_num_rows($res1) == 1) {
				$row_speed = mysql_fetch_array($res1);
                                $max_speed = $row_speed['max_speed'];
                                $avg_speed = $row_speed['avg_speed'];
                               // Writelog($avg_speed." ".$max_speed." ".$maxspeed_query);
                                
                                }
				// If the data is coming for smae date.
//				if(date('Y-m-d', strtotime($add_date)) == date('Y-m-d')) {
					$row_id = $row['id'];
					if($row['ignition_flag'] == 0) {

						$start = strtotime($row['ignition_on']);
						$end = strtotime($current);
						$delta = $end - $start;
						if($delta > 5){
							$hours = floor($delta / 3600);
							$remainder = $delta - $hours * 3600;
							$formattedDelta = sprintf('%02d', $hours) . date(':i:s', $remainder);

							//$query="UPDATE tbl_start_report SET ignition_off = '".$current."', stop_odometer = '".$km_reading."', distance = ($km_reading - start_odometer), duration='". addslashes($formattedDelta)."', ignition_flag = 1 , ignition_off_address = '".addslashes($x_address)."' WHERE device_id = $assets_id AND id = " . $row_id;
							$query="UPDATE tbl_start_report SET ignition_off = '".$current."', stop_odometer = '".$km_reading."', distance = ($km_reading - start_odometer), duration='". addslashes($formattedDelta)."', ignition_flag = 1 , ignition_off_address = '".addslashes($x_address)."'  ,max_speed = '".$max_speed."' ,avg_speed = '".$avg_speed."' WHERE device_id = $assets_id AND id = " . $row_id;
                                                        mysql_query($query) or die(mysql_error().":".$query);
						}
					}
//				}
			}
		}
	}

	function ignitionAlert($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $ignition, $latitude, $longitude, $x_address, $current)
	{
		global $current_area;
		global $current_landmark;
		
		global $dts;
		global $uRow;
		$give_alert = false;
		
		if($ignition == 0){
		
			$ignition_type = "ignition_off";
		}else{
		
			$ignition_type = "ignition_on";
		}
		$query = "SELECT id, ignition_status FROM tbl_ignition_report WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		if(mysql_num_rows($res) == 0)
		{
			$query="INSERT INTO tbl_ignition_report (device_id, ignition_status, date_time, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','ignition_off','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
			mysql_query($query) or die(mysql_error().":".$query);
			$give_alert = true;
		}else{
			$row = mysql_fetch_array($res);
			if(trim($row['ignition_status'] != $ignition_type))
			{
				//writelog($assets_id.$row['ignition_status'].$ignition_type);
				$query="INSERT INTO tbl_ignition_report (device_id, ignition_status, date_time, address, lat, lng, current_area, current_landmark) VALUES ('".addslashes($assets_id)."','$ignition_type','".$current."','".addslashes($x_address)."','".addslashes($latitude)."','".addslashes($longitude)."', '".$current_area."', '".$current_landmark."')";
				mysql_query($query) or die(mysql_error().":".$query);
				$give_alert = true;
			}
			else{
			// writelog("in else".$assets_id.$row['ignition_status'].$ignition_type);
			 $give_alert = false;
			}
		}
		if($give_alert) {

			$us_sql = "SELECT um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, um.alert_stop_time, um.ignition_on_alert, um.ignition_off_alert FROM user_assets_map lm left join tbl_users um on um.user_id = lm.user_id WHERE FIND_IN_SET($assets_id, lm.assets_ids) and lm.del_date is null and lm.status = 1";
			 
			$rs = mysql_query($us_sql) or die(mysql_error().":".$us_sql);
			while($row = mysql_fetch_array($rs)){
				$user_id = $row['user_id'];
				$fname = $row['first_name'];
				$mobile = $row['mobile_number'];
				$email = $row['email_address'];
				$user_sms_alert = $row['user_sms_alert'];
				$user_email_alert = $row['user_email_alert'];
				$ignition_on_alert = $row['ignition_on_alert'];
				$ignition_off_alert = $row['ignition_off_alert'];
				$language =  $row['language'];
				// writelog($fname." ".$language);
				 
				if($language == "portuguese"){
					$file = "portuguese_alert_lang.php";
					
				}
				else{
					$file = "english_alert_lang.php";
					
				}	
				//writelog($file);
				include($file);
				
				if($ignition_type == "ignition_on"){
					$ignition_alert = $ignition_on_alert;
					$ignition_alert_text = $lang['Ignition On'];
				}else if($ignition_type == "ignition_off"){
					$ignition_alert = $ignition_off_alert;
					$ignition_alert_text = $lang['Ignition Off'];
				}
                               // $srt=$lang['Dear'];
                               // $encoded= mb_detect_encoding($str);
                               // echo  $encoded;
				if($ignition_alert == 1){
					$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) $ignition_alert_text";

					if($current_landmark != ''){
						$smsText .= " ".$lang['near Landmark']." $current_landmark";
					}else if($current_area != ""){
						$smsText .= " ".$lang['in Area']." $current_area";
					}
					else if($x_address){
						$smsText .= " ".$lang['at']." $x_address";
					}

					$smsText .= ", ". convert_time_zone($current, $dts, DISP_TIME); //.date(DISP_TIME, 

					if($mobile != "" && $user_sms_alert == 1){
						send_sms($mobile, $smsText, '', '');
						//send_sms($mobile, $alert_text);
						sms_log($mobile, $smsText, $user_id);
						//WriteLog("mobile:".$mobile."".$smsText);
					}

					if($email!="" && $user_email_alert == 1) {
					
						send_email($email, $lang['Vehicle']." $ignition_alert_text ".$lang['Alert'], $smsText);
						email_log($email, $smsText, $user_id, $lang['Vehicle']." $ignition_alert_text ".$lang['Alert']);
						//WriteLog("email:".$email."".$smsText);
						// chat_alert($email, $smsText);
					}				
					//insert in alert master
					$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$ignition_alert_text." ".$lang['Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
					// echo "<br />$alertSql";
					mysql_query($alertSql) or die(mysql_error().":".$alertSql);
				}
			}
		}
	}
	/* Added By Ashwini Gaikwad 13-10-2014 For Ignition on speed off alerts.*/
	function checkIgnitionOnSpeedOff($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $last_speed, $latitude, $longitude, $x_address, $current)
   {
		global $alert_header;
		
		global $dts;
		global $uRow;
		$query = "SELECT * FROM tbl_ignition_on_speed_off WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1"; 
                

		$res = mysql_query($query);
                 

		if($ignition==1 && $last_speed == 0 && $speed == 0)
		{
			//writeLog("in if");

                if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$stop_report_id = $row['id'];
				if(trim($row['motion_start_time'] != ""))
				{
					$query="INSERT INTO tbl_ignition_on_speed_off (device_id, motion_stop_time, address, add_date, lat, lng) VALUES "
                     . "('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."', '".$current."','".addslashes($latitude)."','".addslashes($longitude)."')";
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
					$alert_time=		$uRow['alert_time'];
					$alert_start_time = $uRow['alert_start_time'];
					$alert_stop_time = $uRow['alert_stop_time'];
					$max_stop_time = $uRow['ignition_on_speed_off_minutes'];
					$language = $uRow['language'] ;
					if($language == "portuguese"){
						$file = "portuguese_alert_lang.php";
					}
					else{
						$file = "english_alert_lang.php";
						
					}	
					include($file);	
					//WriteLog("start: ".$row['motion_stop_time']."end: ".$current."minutes: ".$minutes."max_stop_time ".$max_stop_time."assets_name ".$assets_name);
					//WriteLog("start: ".$start."end: ".$end."minutes: ".$minutes);					
					//if stop time more than set time and alert not given
					if($max_stop_time != "" && $max_stop_time != 0 && $minutes > $max_stop_time && $row['alert_given'] == 0){
						//writeLog("alert given");
  				
						$stop_time = sec2HourMinute($max_stop_time * 60);
					
						$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['stop more than']." $stop_time .";	
						
						$smsText1 = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['stop more than']." $stop_time .";	
						
						
						if($mobile != "" && $user_sms_alert == 1 ){
					    send_sms($mobile, $smsText1);
						sms_log($mobile, $smsText1, $user_id);
					   }
				
						if($email!="" && $user_email_alert == 1 ) {
							send_email($email, $lang['Vehicle Stop And Ignition On Alert'], $smsText);
							email_log($email, $smsText, $user_id, $lang['Vehicle Stop And Ignition On Alert']);
							//WriteLog($email."sms".$smsText);
							//chat_alert($email, $smsText);
						}
			
												
						//update alert given
						$uSql = "update tbl_ignition_on_speed_off set alert_given = 1 where id = $stop_report_id";
						@mysql_query($uSql);
						
						if($user_email_alert == 1 || $user_sms_alert == 1 ){
						//insert in alert master
						$alert_header = $lang['Vehicle Stop And Ignition On Alert'];
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Vehicle Stop And Ignition On Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".$current."')";
						//mysql_query($alertSql);
						}
					}
				}
			}
			else if(mysql_num_rows($res) == 0)
			{
              //WriteLog($assets_id." ".$current." ".$x_address." ".$latitude." ".$longitude);

			 // echo $assets_id." ".$current." ".$x_address." ".$latitude." ".$longitude;
                          $query="INSERT INTO tbl_ignition_on_speed_off (device_id, motion_stop_time, address, add_date, lat, lng) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."', '".$current."','".addslashes($latitude)."','".addslashes($longitude)."')";
				
			}
		}
		else
		{ 
                        //writeLog("in else");


			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$row_id = $row['id'];
				if(trim($row['motion_start_time']) == "") {
					
					$start = strtotime($row['motion_stop_time']);
					$end = strtotime($current);
					$delta = $end - $start;
					$hours = floor($delta / 3600);
					$remainder = $delta - $hours * 3600;
					//WriteLog("row_id:".$row_id."start:".$start"end:".$end"delta:".$delta"hours:".$hours."remainder:".$remainder);
					if($remainder < 60){
						$query="delete from tbl_ignition_on_speed_off WHERE device_id = '$assets_id' AND id = " . $row_id;
					}else{
						$formattedDelta = sprintf('%02d', $hours) . gmdate(':i:s', $remainder);
						
						$query="UPDATE tbl_ignition_on_speed_off SET motion_start_time = '".$current."', duration='". addslashes($formattedDelta)."' WHERE device_id = '$assets_id' AND id = " . $row_id;
					}
					
				}
			}
		}
		if($query != '') {
			@mysql_query($query);
		}
	}
	/* Added By Ashwini Gaikwad 13-10-2014 For Ignition off speed On alerts.*/
	function checkIgnitionOffSpeedOn($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $ignition, $speed, $last_speed, $latitude, $longitude, $x_address, $current)
	{
		global $alert_header;
		
		global $dts;
		global $uRow;
		$query = "SELECT * FROM tbl_ignition_off_speed_on WHERE device_id = '$assets_id' ORDER BY id DESC LIMIT 0,1";
		$res = mysql_query($query) or die(mysql_error().":".$query);
		if($ignition==0 && $speed > 0.50)
		{
			if(mysql_num_rows($res) == 1) {
				$row = mysql_fetch_array($res);
				$stop_report_id = $row['id'];
				if(trim($row['ignition_start_time'] != ""))
				{
					$query="INSERT INTO tbl_ignition_off_speed_on (device_id, ignition_stop_time, address, add_date, ignition, speed) VALUES ('".addslashes($assets_id)."','".$current."','".addslashes($x_address)."','".$current."', '$ignition', '$speed')";
					
				}
				
				//alert if running more than given time with ignition off
				if(trim($row['ignition_start_time'] == "")){
					
					
					$start = strtotime($row['ignition_stop_time']);
					$end = strtotime($current);
					$minutes = round(abs($end - $start) / 60,2);
					
					$user_id = $uRow['user_id'];
					$fname = $uRow['first_name'];
					$mobile = $uRow['mobile_number'];
					$email = $uRow['email_address'];
					$user_sms_alert = $uRow['sms_alert'];
					$user_email_alert = $uRow['email_alert'];
					$alert_time=$rowP['alert_time'];
					$alert_start_time = $uRow['alert_start_time'];
					$alert_stop_time = $uRow['alert_stop_time'];
					$max_stop_time = $uRow['ignition_off_speed_on_minutes'];
					$language = $uRow['language'] ;
					if($language == "portuguese"){
						$file = "portuguese_alert_lang.php";
					}
					else{
						$file = "english_alert_lang.php";
						
					}	
					include($file);	
				//	WriteLog("start: ".$row['ignition_stop_time']."end: ".$current."minutes: ".$minutes."max_stop_time ".$max_stop_time."assets_name ".$assets_name);
					//WriteLog("user id : ".$user_id." language: ".$language);
					$minute1 = $max_stop_time;
					$query1="select add_date from alert_master where assets_id = '$assets_id' and alert_header = '".$lang['Vehicle Running And Ignition Off Alert']."' ORDER BY id DESC LIMIT 0,1";
					$res1 = mysql_query($query1) or die(mysql_error().":".$query1);
					if(mysql_num_rows($res1) == 1) {
					$row1 = mysql_fetch_array($res1);
					$before_send_alert = strtotime($row1['add_date']);
					
					$minute1 = round(abs($end - $before_send_alert) / 60,2);
				//	WriteLog($minute1." asset_id".$assets_id." ".$assets_name);
					}
					if($minute1 >= $max_stop_time){
					
					
					//if stop time more than set time and alert not given
					if($max_stop_time != "" && $max_stop_time != 0 && $minutes > $max_stop_time){						  				
						$stop_time = sec2HourMinute($max_stop_time * 60);
					
						$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['running more than']." $stop_time .";	
						$smsText1 = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['running more than']." $stop_time .";		
						if($mobile != "" && $user_sms_alert == 1){
					    send_sms($mobile, $smsText1);
						sms_log($mobile, $smsText1, $user_id);
					   }
					if($email!="" && $user_email_alert == 1 ) {
						//$email="h.kumbhar@chateglobalservices.com";
							send_email($email, $lang['Vehicle Running And Ignition Off Alert'], $smsText);
							email_log($email, $smsText, $user_id, $lang['Vehicle Running And Ignition Off Alert']);
							//chat_alert($email, $smsText);
							//WriteLog($email."sms".$smsText);
						}
										
						//update alert given
						$uSql = "update tbl_ignition_off_speed_on set alert_given = 1 where id = $stop_report_id";
						mysql_query($uSql) or die(mysql_error().":".$uSql);
						
						if($user_email_alert == 1 || $user_sms_alert == 1 ){

						//insert in alert master
						$alert_header = 'Vehicle Running And Ignition Off Alert';
						$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ('".$lang['Vehicle Running And Ignition Off Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".$current."')";
						mysql_query($alertSql) or die(mysql_error().":".$alertSql);
						}
					}
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
			mysql_query($query) or die(mysql_error().":".$sql);
		}
	}
	/*Added by Ashwini 12-9-2014 for below new VTS alerts :
	  1)SOS Alert 
	  2)Door Open Alert 
	  3)Low Battery Alert 
	  4)Power Off Alert
	
	 function new_vts_alerts($device,$reason_text, $assets_id, $assets_name, $nick_name, $latitude_x, $longitude_y, $x_address, $current){
            
            global $dts;
            $current_time=convert_time_zone($current, $dts, DISP_TIME);
            $us_sql = "SELECT um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert FROM user_assets_map lm left join tbl_users um on um.user_id = lm.user_id WHERE FIND_IN_SET($assets_id, lm.assets_ids) and lm.del_date is null and lm.status = 1";
			 
		$rs = mysql_query($us_sql);
		while($row = mysql_fetch_array($rs)){
			$user_id = $row['user_id'];
			$fname = $row['first_name'];
			$mobile = $row['mobile_number'];
			$email = $row['email_address'];
			$user_sms_alert = $row['user_sms_alert'];
			$user_email_alert = $row['user_email_alert'];
			$language = $row['language'];
			if($language == "portuguese")
			{
				include_once("portuguese_alert_lang.php");
			}
			else{
				include_once("english_alert_lang.php");
			}	
			
                        $reason_text=  strtolower($reason_text);
			switch ($reason_text) {
			 case "help me":
				$alert_text = $lang['Vehicle SOS Alert'];
				$emailText = $lang['Dear']." $fname, ".$lang['your vehicle']." $assets_name ($nick_name) ".$lang['just send SOS Alert at']." $x_address, $current_time ";
				$smsText = $lang['Dear']." $fname, ".$lang['your vehicle']." $assets_name ($nick_name) ".$lang['just send SOS Alert at']." $x_address, $current_time ";
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
				 
			 break;
			 
			 case "low battery":
				$alert_text = $lang['Vehicle Low Battery Alert'];
				$emailText = $lang['Dear']." $fname, ".$lang['the voltage of your tracker device']." ($device) ".$lang['for vehicle']." $assets_name ".$lang['is low at']." $x_address, $current_time";
				$smsText = $lang['Dear']." $fname, ".$lang['the voltage of your tracker device']." ($device) ".$lang['for vehicle']." $assets_name ".$lang['is low at']." $x_address, $current_time.";
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
				
			 break;
			 
			 case "door alarm":
				$alert_text = $lang['Vehicle Door Open Alert'];
				$emailText = $lang['Dear']." $fname, ".$lang['the door of your vehicle']." $assets_name ($nick_name) ".$lang['is open near']." $x_address, $current_time";
				$smsText = $lang['Dear']." $fname, ".$lang['the door of your vehicle']." ($nick_name) ".$lang['is open near']." $x_address, $current_time";
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
			 break;
                     
                 case "power alarm":
				$alert_text = $lang['Vehicle Power Off Alert'];
				$emailText = $lang['Dear']." $fname, ".$lang['the power of your tracker device']." ($device) ".$lang['is cut off for vehicle']." $assets_name near $x_address, $current_time";
				$smsText = $lang['Dear']." $fname, ".$lang['the power of your tracker device']." ($device) ".$lang['is cut off for vehicle']." $assets_name near $x_address, $current_time";
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
			 break;
			 
			 default : return true ;
		 
			}
			
			if($mobile != "" && $user_sms_alert == 1 && $smsText != ""){
				//send_sms($mobile, $smsText, '', '');
				//sms_log($mobile, $smsText, $user_id);
			}

			if($email!="" && $user_email_alert == 1 && $emailText != "" ) {
			    
				send_email($email, "$alert_text", $emailText);
				email_log($email, $emailText, $user_id, "$alert_text");
				WriteLog($email."emailText".$emailText);
			}				
			//insert into alert master table
			
			$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ('".$alert_text."', '".$emailText."', 'alert', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
			
			mysql_query($alertSql) or die(mysql_error().":".$alertSql);
		}
	}
*/ 
	// added by harshal all status alert 
		//status_alert($device, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $status, $latitude, $longitude, $x_address, $ist);

	function status_alert($unit_no, $assets_id, $assets_name, $nick_name, $driver_name, $driver_mobile, $status, $latitude_x, $longitude_y, $x_address, $current)
	{
		
		global $current_area;
		global $current_landmark;
		
		$us_sql = "SELECT um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, um.alert_start_time, um.alert_stop_time, um.ignition_on_alert, um.ignition_off_alert FROM user_assets_map lm left join tbl_users um on um.user_id = lm.user_id WHERE FIND_IN_SET($assets_id, lm.assets_ids) and lm.del_date is null and lm.status = 1";
			 
		$rs = mysql_query($us_sql);
		while($row = mysql_fetch_array($rs)){
			$user_id = $row['user_id'];
			$fname = $row['first_name'];
			$mobile = $row['mobile_number'];
			$email = $row['email_address'];
			$user_sms_alert = $row['user_sms_alert'];
			$user_email_alert = $row['user_email_alert'];
			$ignition_on_alert = $row['ignition_on_alert'];
			$ignition_off_alert = $row['ignition_off_alert'];
			$language = $row['language'];
			if($language == "portuguese"){
				$file = "portuguese_alert_lang.php";
			}
			else{
				$file = "english_alert_lang.php";
				
			}	
			include($file);	
			$alert_text = "";
                        $alert_header = "";
			switch ($status) {
			 case "ACC OS":
				$alert_text = $lang['Displacement On Alarm'];
                                $alert_header = $lang['Vehicle']." ".$alert_text." ".$lang['Alert'];
				$emailText = $lang['Alarm For Asset2']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." ". date(DISP_TIME);
				$smsText = $lang['Alarm For Asset2']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." ". date(DISP_TIME);
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
				 
			 break;
			 
			 case "ACC RS":
				$alert_text = $lang['Displacement Out Alarm'];
                                $alert_header = $lang['Vehicle']." ".$alert_text." ".$lang['Alert'];
				$emailText = $lang['Alarm For Asset2']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." ". date(DISP_TIME);
				$smsText = $lang['Alarm For Asset2']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." ". date(DISP_TIME);
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
				
			 break;
			 
			 case "DEF":
				$alert_text = $lang['Failure Main Battery Alarm'];
                               $alert_header = $lang['Vehicle Failure Main Battery Alarm'];
				$emailText = $lang['Alarm For Asset1']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." ". date(DISP_TIME);
				$smsText = $lang['Alarm For Asset1']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." ". date(DISP_TIME);
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
			 break;
			 
			 case "SOS":
				$alert_text = $lang['Alarm ON'];
                                $alert_header = $lang['Vehicle alarm with active SOS'];
				//writeLog($alert_text);
				$emailText = $lang['Alarm For Asset']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." " . date(DISP_TIME);
				$smsText = $lang['Alarm For Asset']." $assets_name ($device_id, , $driver_name, $driver_mobile) : $alert_text ".$lang['at']." " . date(DISP_TIME);
				$emailText .= ', '.$lang['Click'].' <a href="https://maps.google.com/maps?f=q&hl=en&geocode=&q='.$latitude_x.','.$longitude_y.'('.$assets_name.')&ie=UTF8&z=12&om=1">'.$lang['here'].'</a>'.$lang['to view on map'].' .';
			
			  break;
			 
			 
			 default : return true ;
		 
			}
			//WriteLog($email.''.$user_email_alert);
			if($mobile != "" && $user_sms_alert == 1){
						send_sms($mobile, $smsText, '', '');
						//send_sms($mobile, $alert_text);
						sms_log($mobile, $smsText, $user_id);
					}
                                           
					if($email!="" && $user_email_alert == 1) {
						send_email($email, $alert_header, $emailText);
						email_log($email, $emailText, $user_id, "Vehicle $alert_text Alert");
						// chat_alert($email, $smsText);
						//writeLog($emailText);
					}				
					//insert in alert master
					$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$alert_header."', '".$emailText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".date(DATE_TIME)."')";
					// echo "<br />$alertSql";
					mysql_query($alertSql);
		}
	}
	// trip alerts added by harshal 
	function startStopTrip($device_id, $assets_id, $assets_name, $nick_name, $driver_name, $lati, $longi, $current_speed, $ist, $distance_travelled){
		
		global $dts;
		$sqlP = "SELECT trip.*, um.user_id, um.first_name, um.mobile_number, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert, am.driver_name, am.km_reading FROM tbl_routes trip LEFT JOIN tbl_users um ON um.user_id = trip.add_uid left join assests_master am on am.current_trip = trip.id WHERE am.id = $assets_id and trip.del_date IS NULL AND trip.status = 1";
		
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
			$language = $rowP['language'];
						if($language == "portuguese"){
							$file = "portuguese_alert_lang.php";
						}
						else{
							$file = "english_alert_lang.php";
							
						}	
						//writelog($file);
						include($file);
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
						mysql_query($ins) or die("Failed to Execute, SQL : $ins, Error : " . mysql_error());
						$trip_start_alert = true;
						
						//update next landmark
						$next_trip_landmark = $landmark_ids[1];
						$nextLSql = "update assests_master set next_trip_landmark = $next_trip_landmark where id = $assets_id";
						mysql_query($nextLSql) or die("Failed to Execute, SQL : $nextLSql, Error : " . mysql_error());
						
					}else{
						$update = "update trip_log set distance_travelled = distance_travelled + $distance_travelled where id = ".$row['id'];
						mysql_query($update) or die("Failed to Execute, SQL : $update, Error : " . mysql_error());
					}
				}else{
					$ins = "insert into trip_log(trip_id, device_id, driver_name, start_km_reading, start_time) values($trip_id, $assets_id, '$driver_name', '$km_reading', '".$ist."')";
					
					mysql_query($ins) or die("Failed to Execute, SQL : $ins, Error : " . mysql_error());
					$trip_start_alert = true;
					
					//update next landmark
					$next_trip_landmark = $landmark_ids[1];
					$nextLSql = "update assests_master set next_trip_landmark = $next_trip_landmark where id = $assets_id";
					mysql_query($nextLSql) or die("Failed to Execute, SQL : $nextLSql, Error : " . mysql_error());
				}
				if($trip_start_alert == true){
						//writeLog("hello");
					//send alert to sub users
					/*$sql = "select um.first_name, um.mobile_number, um.email_address, um.language, um.email_alert, um.sms_alert from tbl_users um left join user_assets_map uam on uam.user_id = um.user_id where FIND_IN_SET( $assets_id, uam.assets_ids ) and um.del_date is null and um.status = 1 and um.user_id";
					$rs = mysql_query($sql) or die("Failed to Execute, SQL : $sql, Error : " . mysql_error());
					while($row = mysql_fetch_array($rs)){
						$fname 					= $row['first_name'];
						$mobile 				= $row['mobile_number'];
						$email 					= $row['email_address'];
						$user_email_alert 		= $row['email_alert'];
						$user_sms_alert 		= $row['sms_alert'];
						*/
						
						
										
						$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['started trip']." $trip_name ".$lang['on time']." ". convert_time_zone($ist, $dts, DISP_TIME);
						if($mobile != "" && $user_sms_alert == 1){
							send_sms($mobile, $smsText, '', '');
							sms_log($mobile, $smsText, $user_id);
						}	
						
						if($email!="" && $user_email_alert == 1) {
							send_email($email, $lang['Trip Start Alert'], $smsText);
							email_log($email, $smsText, $user_id, 'Trip Start Alert');
							//writeLog($email." ".$smsText);
							//chat_alert($email, $smsText);
						}
						
						//insert in alert master
						$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Trip Start Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";						
						mysql_query($alertSql);
					//}
				}
			}
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
							
							$ins = "update trip_log set end_km_reading = '$km_reading', end_time = '".$ist."', is_complete = '1' where id = '".$row['id']."'";
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
								$smsText .= "$late ".$lang['Late'].".";
								
								$template_id = '3829';
								$f6 = $time_taken;
								$f7 = $late;
								
								$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['has completed']." $trip_name ".$lang['trip in']." $time_taken, $late ".$lang['Late'].", ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
								
							}else{
								$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['has completed']." $trip_name ".$lang['trip in']." $time_taken, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
								//Dear [F1], [F2] ([F3], [F4]) has completed the [F5] trip in [F6][F7]
								$template_id = '3828';
								
								list($f6, $f7) = str_split($time_taken, 30);
								if($f7 == '')	$f7 = ' ';
								$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang['has completed']." $trip_name ".$lang['trip in']." $time_taken, ". convert_time_zone($ist, $dts, DISP_TIME); // date(DISP_TIME, strtotime($ist));
								
							}
							$f8 = ",". convert_time_zone($ist, $dts, DISP_TIME); // .date(DISP_TIME, strtotime($ist));
							$template_data = array("F1"=>$f1, "F2"=>$f2, "F3"=>$f3, "F4"=>$f4, "F5"=>$f5, "F6"=>$f6, "F7"=>$f7, "F8"=>$f8);
							//
							
							if($mobile != "" && $user_sms_alert == 1){
								
								send_sms($mobile, $smsText, $template_id, $template_data);
								sms_log($mobile, $smsText, $user_id);
							}							
							if($email!="" && $user_email_alert ==1) {
								send_email($email, $lang['Trip Complete at Last Location Alert'], $smsText);
								email_log($email, $smsText, $user_id, 'Trip Complete at Last Location Alert');
								//chat_alert($email, $smsText);
								//writeLog($email." ".$smsText);
							}
							//insert in alert master
							$alertSql = "insert into alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) values ( '".$lang['Trip Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".$ist."')";
							mysql_query($alertSql);
						}
					}
				}
			}
		}
	}
        function checkRoute($device_id, $assets_id, $current_trip, $assets_name, $nick_name, $driver_name, $lati, $longi, $current_speed, $ist){
		
		$insert_data = false;
		$route_flag	 = false;
		global $current_landmark, $dts;
		
		if($current_trip != ""){
			$sqlP = "SELECT trip.*, um.first_name, um.mobile_number, um.user_id, um.language, um.email_address, um.email_alert as user_email_alert, um.sms_alert as user_sms_alert FROM tbl_routes trip left join tbl_users um on um.user_id = trip.userid WHERE trip.del_date is null and trip.status = 1 and trip.id = $current_trip";
			
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
				$language = $row['language'];
				if($language == "portuguese"){
					$file = "portuguese_alert_lang.php";
				}
				else{
					$file = "english_alert_lang.php";
				}	
				include($file);
				$landmark_ids = explode(",", $landmark_ids);
				$start_point = $landmark_ids[0];
				if($row['round_trip'] == 1)
					$end_point = $start_point;
				else	
					$end_point = end($landmark_ids);
					
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
						
						$smsText = $lang['Dear']." $fname, $assets_name ($nick_name, $driver_name) ".$lang[' is not on route']." : $distanceText), ". convert_time_zone($ist, $dts, DISP_TIME); //.date(DISP_TIME, strtotime($ist));
						
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
							send_email($email, $lang['Route Break Alert'], $smsText);
							email_log($email, $smsText, $user_id,$lang['Route Break Alert']);
							//chat_alert($email, $smsText);
						}
						//insert in alert master
						$alertSql = "INSERT INTO alert_master(alert_header, alert_msg, alert_type, user_id, assets_id, add_date) VALUES ( '".$lang['Route Break Alert']."', '".$smsText."', '".$lang['Alert']."', '".$user_id."', '".$assets_id."', '".gmdate(DATE_TIME, strtotime($ist))."')";
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


?>
