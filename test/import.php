<?php
	session_start();
	set_time_limit(0);
	include("../telnet/db.php");
	$user_id = $_SESSION['userid'];
	
	$dir = "Excel/files";
	$cmd = $_REQUEST['cmd'];
	$Excel_file=$_REQUEST['file'];
	
	$extension = explode(".",$Excel_file);
	$extension = $extension[sizeof($extension) - 1]; 
	$path = $dir."/".$Excel_file;
	$insertArray = array();
	
	$checkInvoce = false;
	
	$entry_table = $_REQUEST["action"];
	
	$html = '<table width="100%" border=1 cellpadding=0 cellspacing=0><tr><th>Vehicle</th><th>Driver</th><th>Order</th><th>Dealer Code</th><th>Start Landmark</th><th>End Landmark</th></tr>';
	
	error_reporting(E_ALL);
	
	require_once 'Excel/PHPExcel/IOFactory.php';

	if($extension == "xlsx"){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(false);	
		$objPHPExcel = $objReader->load($path);
		
	}
	else{
		// Automatic file type resolving
		$objPHPExcel = PHPExcel_IOFactory::load($path);
	}
	
	if(isset($_REQUEST['sheet']))
		$sheet=$_REQUEST['sheet'];
	else
		$sheet=0;
		
	
	$colArray = array( 'c_empnm', 'driver', 'vehicle', 'c_id', 'c_dt', 'c_flnm', 'c_totdlr', 'order', 'dealer_code', 'c_tel', 'c_mail', 'c_dlrnm', 'from_landmark', 'to_landmark');
	
					
	if($cmd == "sheet"){
	
		foreach ($objPHPExcel->getSheetNames() as $sheetIndex => $sheetName) {
			$option="<option value='".$sheetIndex."'";
			if($sheetIndex==$sheet)
				$option.=" selected='true'";
			$option.=">".$sheetName."</option>";
			echo $option;
		}
	}
	else if($cmd == "fields"){
		$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheet);
	//	$colArray = array( 'from_area', 'to_area', 'beat_number');
		$j=0;
		foreach($colArray as $arr){
			echo '<tr><td><label for="'.$arr.'">'.$arr.'</label></td><td>';
			
			foreach ($objWorksheet->getRowIterator() as $row) {
			  $cellIterator = $row->getCellIterator();
			  $cellIterator->setIterateOnlyExistingCells(false);
			  echo '<select class="select ui-widget-content ui-corner-all" id="'.$arr.'" name="'.$arr.'">';
			  echo '<option value="-1">Please Select</option>';
			  $i=0;
			  foreach ($cellIterator as $cell) {
				echo "<option value='".$i."'";
				if($i==$j){
					echo " selected='true'";
				}
				echo ">".$cell->getValue()."</option>";
				$i++;
			  }
			  break;
			}
			echo '</select></td></tr>';
			$j++;
		}
		echo '</table>';
	}
	else if($cmd == "display" || $cmd=="insert"){
		$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheet);
		$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
		
		for ($row = 2; $row <= $highestRow; ++$row)
		{
		
			$imp_data = array();
			
			foreach($colArray as $key)
			{
				$imp_data[$key] = trim($objWorksheet->getCellByColumnAndRow($_POST[$key], $row)->getValue());
			}
			
			if($cmd=="display"){
				insertData($imp_data,$row,$cmd=="display"?true:false,$row,$highestRow);
			}else{
				if($row == 2){	
					$driver 		= $imp_data['driver'];
					$vehicle 		= $imp_data['vehicle'];
					$from_landmark 	= $imp_data['from_landmark'];
					$to_landmark 	= $imp_data['to_landmark'];
				}
				$orderArr[] = $imp_data['order'];
				$dealer_codeArr[] = "'".$imp_data['dealer_code']."'";
				
			}			
			
		}
		if($cmd=="display"){
			$data['result'] = "true";
			
			$data['html'] = $html;
			die(json_encode($data));
		}
		else{
			if($user_id == ''){
				generateMSG('', "PLease Re-login for Import", false);
			}
			$color = generateRandomColor();
			
			$driver_image_arr = array();
			$driver_code = $driver;
			
			//get driver
			$sql = "select group_concat(driver_name) as driver, group_concat(mobile_no) as mobile_no from driver_master where driver_code in ($driver)";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(!mysql_num_rows($rs)){
				generateMSG('', 'Driver Not Found', false);
			}
			$row = mysql_fetch_array($rs);
			$driver = $row['driver'];
			$driver_mobile = $row['mobile_no'];
			
			//driver_image
			$driver_code = explode(",", $driver_code);
			foreach($driver_code as $drv){
				$driver_image_arr[] = $drv.".jpg";
			}
			$driver_image = '';
			if(count($driver_image_arr) > 0)
				$driver_image = implode(",", $driver_image_arr);
			
			//get assets id
			$sql = "select id from assests_master where assets_name = '$vehicle'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(!mysql_num_rows($rs)){
				generateMSG('', 'Vehicle Not Found', false);
			}
			$row = mysql_fetch_array($rs);
			$assets_id = $row['id'];
						
			//select start point
			$sql = "select * from landmark where comments = '$from_landmark'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(!mysql_num_rows($rs)){
				generateMSG('', 'Start Location Not Found', false);
			}
			$row = mysql_fetch_array($rs);
			$start_id = $row['id'];
			$start_point = $row['lat'].",".$row['lng'];
			$start_point_name = $row['name'];
			
			//select start point
			$sql = "select * from landmark where comments = '$to_landmark'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(!mysql_num_rows($rs)){
				generateMSG('', 'End Location Not Found', false);
			}
			$row = mysql_fetch_array($rs);
			$end_id = $row['id'];
			$end_point = $row['lat'].",".$row['lng'];
			$end_point_name = $row['name'];
			
			//get dealer landmark id
			$dealer_code = implode(",", $dealer_codeArr);
			$sql = "select id from landmark where comments in ($dealer_code) ORDER BY FIELD( comments, $dealer_code ) ";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(!mysql_num_rows($rs)){
				generateMSG('', 'Dealer Location Not Found', false);
			}
			$middle_point = array();
			while($row = mysql_fetch_array($rs)){
				$middle_point[] = $row['id'];
			}
			$middle_point = implode(",", $middle_point);
			
			//set assets for dealer landmark
			$upd = "update landmark set device_ids = concat(device_ids,',','$assets_id') where comments in ($dealer_code) and NOT find_in_set('$assets_id', device_ids)";
			mysql_query($upd) or generateMSG('', $upd, false);
			
			//all landmark
			$landmark_ids = $start_id.",".$middle_point.",".$end_id;
			
			//update user assets mapping
			
			//remove assets from mapping for dealers
			$sql = "select * from user_assets_map where find_in_set('$assets_id', assets_ids) and user_id <> $user_id";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);	
			while($row = mysql_fetch_array($rs)){
				$uam_id = $row['id'];
				$assetsIds = $row['assets_ids'];
				$assetsIds = str_replace("$assets_id", "", $assetsIds);
				$assetsIds = str_replace(",,", ",", $assetsIds);
				$assetsIds = trim($assetsIds, ",");
				
				$sql = "update user_assets_map set assets_ids = '$assetsIds' where id = $uam_id";
				mysql_query($sql) or generateMSG('', $sql, false);		
			}
			
			//set assets
			$sql = "select * from user_assets_map where find_in_set(user_id, (select group_concat(user_id) from tbl_users where username in($dealer_code)))";
			
			$rs = mysql_query($sql) or generateMSG('', $sql, false);		
			while($row = mysql_fetch_array($rs)){
				$uam_id = $row['id'];
				$assetsIds = $row['assets_ids'];
				if($assetsIds != ""){
					$assetsIds = $assetsIds.",".$assets_id;
				}else{
					$assetsIds = $assets_id;
				}				
				$sql = "update user_assets_map set assets_ids = '$assetsIds' where id = $uam_id";
				mysql_query($sql) or generateMSG('', $sql, false);		
			}
			
			//insert in to trip
			$update = "update tbl_routes set del_date = '".date('Y-m-d H:i:s')."', status = 0 where deviceid = '$assets_id' and del_date is null and status = 1";
			mysql_query($update) or generateMSG('', $update, false);	
			
			$dealer_code_str = str_replace(",", "-", $dealer_code);
			$dealer_code_str = str_replace("'", "", $dealer_code_str);
			//$tripname = "(".$vehicle.")".$from_landmark." To ".$dealer_code_str." To ".$to_landmark;
			$tripname = $vehicle." - ".$from_landmark." To ".$to_landmark;
			
			$sql = "insert into tbl_routes(userid, routename, route_color, landmark_ids, start_point, end_point, deviceid, round_trip, distance_value, distance_unit, add_uid, add_date) values('$user_id', '$tripname', '$color', '$landmark_ids', '$start_point', '$end_point', '$assets_id', '0', '0', 'KM', '$user_id', '".date('Y-m-d H:i:s')."')";
			
			$rs = mysql_query($sql) or generateMSG('', mysql_error().":".$sql, false);			
			$insert_id = mysql_insert_id();
			
			//sub trip 
			$landmark_arr = explode(",", $landmark_ids);
			$first_landmark = $landmark_arr[0];
			$last_landmark = end($landmark_arr);
			
			$sql = "select * from landmark where id in ($landmark_ids) ORDER BY FIELD( id, $landmark_ids ) ";			
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			$i=0;
			$totalDistance = 0;
			$total_rows = mysql_num_rows($rs);
			while($row = mysql_fetch_array($rs)){
				if($i == 0){
					$start_id_sub = $row['id'];
					$start_point_sub = $row['lat'].",".$row['lng'];
					$first_id_sub = $row['id'];
					$first_point_sub = $row['lat'].",".$row['lng'];
					
				}else{
					if($i == ($total_rows-1)){
						if($first_landmark != $last_landmark){
							$color = "#000000";
						}
					}
					$end_id_sub = $row['id'];
					$end_point_sub = $row['lat'].",".$row['lng'];
					$sub_order = $i;
					$landmark_ids = "$start_id_sub,$end_id_sub";
					
					$wSql = "select waypoints, points, total_km from tbl_sub_routes where landmark_ids = '$landmark_ids' order by id desc limit 1";
					$wRs = mysql_query($sql) or generateMSG('', $sql, false);
					$waypoints = '';
					if(mysql_num_rows($wRs) > 0){
						$wRow = mysql_fetch_array($wRs);
						$waypoints = $wRow['waypoints'];
						$points = $wRow['points'];
						$distance = $wRow['total_km'];
						$totalDistance += $distance;
					}
					$sql = "insert into tbl_sub_routes(route_id, sub_order, route_color, landmark_ids, start_point, end_point, waypoints, points, total_km, add_uid, add_date) values('$insert_id', '$sub_order', '$color', '$landmark_ids', '$start_point_sub', '$end_point_sub', '$waypoints', '$points', '$distance', '$user_id', '".date('Y-m-d H:i:s')."')";
					mysql_query($sql) or generateMSG('', $sql, false);
					
					$start_id_sub = $end_id_sub;
					$start_point_sub = $end_point_sub;
				}
				$i++;
			}
			if($first_landmark == $last_landmark){		//round trip
				$color = "#000000";
				$sub_order = $sub_order + 1;
				$end_id_sub = $first_id_sub;
				$end_point_sub = $first_point_sub;
				$landmark_ids = "$start_id_sub,$end_id_sub";
				$wSql = "select waypoints, points, total_km from tbl_sub_routes where landmark_ids = '$landmark_ids' order by id desc limit 1";
				$wRs = mysql_query($sql) or generateMSG('', $sql, false);
				$waypoints = '';
				if(mysql_num_rows($wRs) > 0){
					$wRow = mysql_fetch_array($wRs);
					$waypoints = $wRow['waypoints'];
					$points = $wRow['points'];
					$distance = $wRow['total_km'];
					$totalDistance += $distance;
				}
				$sql = "insert into tbl_sub_routes(route_id, sub_order, route_color, landmark_ids, start_point, end_point, waypoints, points, total_km, add_uid, add_date) values('$insert_id', '$sub_order', '$color', '$landmark_ids', '$start_point_sub', '$end_point_sub', '$waypoints', '$points', '$distance', '$user_id', '".date('Y-m-d H:i:s')."')";
				mysql_query($sql) or generateMSG('', $sql, false);
			}
			//update distance
			$upd = "update tbl_routes set total_distance = '$totalDistance' where id = '$insert_id'";
			mysql_query($upd);
			
			//update driver details, set current trip
			$sql = "update assests_master set driver_name = '$driver', driver_image = '$driver_image', driver_mobile = '$driver_mobile', current_trip = '$insert_id' where assets_name = '$vehicle'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			generateMSG('',"Imported Successfully",TRUE);
		}		
	}
	

function insertData($imp_data,$i,$display=false,$row,$highestRow)
{
	global $html, $uid;
	
	if($display)
	{
		if($imp_data['driver'] != ""){
			
			//get driver
			$sql = "select group_concat(driver_name) as driver from driver_master where driver_code in (".$imp_data['driver'].")";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(mysql_num_rows($rs) > 0){
				$row = mysql_fetch_array($rs);
				$driver = $row['driver'];
			}else{
				$driver = $imp_data['driver']."-Not Found";
			}
			$sql = "select * from landmark where comments = '".$imp_data['from_landmark']."'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(mysql_num_rows($rs) > 0){
				$row = mysql_fetch_array($rs);
				$start_point_name = $row['name'];
			}else{
				$start_point_name = $imp_data['from_landmark']."-Not Found";
			}
			$sql = "select * from landmark where comments = '".$imp_data['to_landmark']."'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);
			if(mysql_num_rows($rs) > 0){
				$row = mysql_fetch_array($rs);
				$end_point_name = $row['name'];
			}else{
				$end_point_name = $imp_data['to_landmark']."-Not Found";
			}
			$sql = "select * from landmark where comments = '".$imp_data['dealer_code']."'";
			$rs = mysql_query($sql) or generateMSG('', $sql, false);			
			if(mysql_num_rows($rs) > 0){
				$row = mysql_fetch_array($rs);
				$dealer_name = $row['name'];			
			}else{
				$dealer_name = $imp_data['dealer_code']."-Not Found";
			}
			$html .= '<tr><td align="center">'.$imp_data['vehicle'].'</td><td align="center">'.$driver.'</td><td align="center">'.$imp_data['order'].'</td><td align="center">'.$dealer_name.'</td><td align="center">'.$start_point_name.'</td><td align="center">'.$end_point_name.'</td><tr>';
			
		}
		
	}
	return true;
}
function generateMSG($id, $msg, $result=false) {
	if($result == false) {
		$data["result"] = $result;
		$data["eid"] = $id;
		$data["error"] = $msg;
	}
	else {
		$data["result"] = "true";
		$data["msg"] = $msg;
	}
	die(json_encode($data));
}
function generateRandomColor(){
    $color = array('#0000FF','#8A2BE2','#A52A2A','#DEB887','#5F9EA0','#7FFF00','#D2691E','#FF7F50','#6495ED','#DC143C','#00FFFF','#00008B','#008B8B','#B8860B','#006400','#8B008B','#FF0000','#FF8C00','#9932CC','#8B0000','#E9967A','#8FBC8F','#00CED1','#9400D3','#FF1493','#00BFFF','#B22222','#228B22','#FF00FF','#FFD700','#DAA520','#F08080','#9ACD32','#008080','#FF6347','#40E0D0','#EE82EE','#4682B4','#DDA0DD','#FFC0CB');
	$rand = rand(0,39);
	return $color[$rand];
}
?>
