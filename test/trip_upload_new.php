<?php
$user_id = 6;
include("../telnet/db.php");
require_once 'Excel/PHPExcel/IOFactory.php';

$target_path = "Excel/files/";
$timestamp = time();
$filename = $_FILES['filename']['name'];
$file_type  = $_FILES['filename']['type'];
$pos = strrpos($filename,".");
$Excel_file = "";
//?heck that we have a file
if((!empty($_FILES["filename"])) && ($_FILES['filename']['error'] == 0)) {
	//Check if the file is excel
	$ext = substr($filename, strrpos($filename, '.') + 1);
	//if (($ext == "xls") && ($_FILES["filename"]["type"] == "application/vnd.ms-excel")){
	if (($ext == "xls") || ($ext == "XLS") || ($ext == "xlsx") ){
		$Excel_file = substr($filename,0,$pos).substr($filename,$pos);
		$target_path = $target_path . $Excel_file;
		if(move_uploaded_file($_FILES['filename']['tmp_name'], $target_path)) {
			
			if($ext == "xlsx"){
				$objReader = PHPExcel_IOFactory::createReader('Excel2007');
				$objReader->setReadDataOnly(false);	
				$objPHPExcel = $objReader->load($target_path);	
			}
			else{
				// Automatic file type resolving
				$objPHPExcel = PHPExcel_IOFactory::load($target_path);
			}
			$sheet=0;			
			$colArray = array( 'c_empnm', 'driver', 'vehicle', 'c_id', 'c_dt', 'c_flnm', 'c_totdlr', 'order', 'dealer_code', 'c_tel', 'c_mail', 'c_dlrnm', 'from_landmark', 'to_landmark');
			
			$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheet);
			$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
			$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
			
			for ($row = 2; $row <= $highestRow; ++$row)
			{
				$ss = 0;
				foreach($colArray as $key)
				{
					$imp_data[$key] = trim($objWorksheet->getCellByColumnAndRow($ss, $row)->getValue());
					$ss++;
				}
				if($row == 2){	
					$driver 		= $imp_data['driver'];
					$vehicle 		= $imp_data['vehicle'];
					$from_landmark 	= $imp_data['from_landmark'];
					$to_landmark 	= $imp_data['to_landmark'];
				}
				$orderArr[] = $imp_data['order'];
				$dealer_codeArr[] = "'".$imp_data['dealer_code']."'";
			}
			$color = generateRandomColor();
			
			$driver_image_arr = array();
			$driver_code = $driver;
			
			//get driver
			$sql = "select group_concat(driver_name) as driver, group_concat(mobile_no) as mobile_no from driver_master where driver_code in ($driver)";
			$rs = mysql_query($sql) or die($sql);
			if(!mysql_num_rows($rs)){
				die('Driver Not Found');
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
			$rs = mysql_query($sql) or die($sql);
			if(!mysql_num_rows($rs)){
				die('Vehicle Not Found');
			}
			$row = mysql_fetch_array($rs);
			$assets_id = $row['id'];
						
			//select start point
			$sql = "select * from landmark where comments = '$from_landmark'";
			$rs = mysql_query($sql) or die($sql);
			if(!mysql_num_rows($rs)){
				die('Start Location Not Found');
			}
			$row = mysql_fetch_array($rs);
			$start_id = $row['id'];
			$start_point = $row['lat'].",".$row['lng'];
			$start_point_name = $row['name'];
			
			//select start point
			$sql = "select * from landmark where comments = '$to_landmark'";
			$rs = mysql_query($sql) or die($sql);
			if(!mysql_num_rows($rs)){
				die('End Location Not Found');
			}
			$row = mysql_fetch_array($rs);
			$end_id = $row['id'];
			$end_point = $row['lat'].",".$row['lng'];
			$end_point_name = $row['name'];
			
			//get dealer landmark id
			$dealer_code = implode(",", $dealer_codeArr);
			$sql = "select id from landmark where comments in ($dealer_code) ORDER BY FIELD( comments, $dealer_code ) ";
			$rs = mysql_query($sql) or die($sql);
			if(!mysql_num_rows($rs)){
				die('Dealer Location Not Found');
			}
			$middle_point = array();
			while($row = mysql_fetch_array($rs)){
				$middle_point[] = $row['id'];
			}
			$middle_point = implode(",", $middle_point);
			
			//set assets for dealer landmark
			$upd = "update landmark set device_ids = concat(device_ids,',','$assets_id') where comments in ($dealer_code)";
			mysql_query($upd) or generateMSG('', $upd, false);
			
			//all landmark
			$landmark_ids = $start_id.",".$middle_point.",".$end_id;
			
			//update user assets mapping
			
			//remove assets from mapping for dealers
			$sql = "select * from user_assets_map where find_in_set('$assets_id', assets_ids) and user_id <> $user_id";
			$rs = mysql_query($sql) or die($sql);	
			while($row = mysql_fetch_array($rs)){
				$uam_id = $row['id'];
				$assetsIds = $row['assets_ids'];
				$assetsIds = str_replace("$assets_id", "", $assetsIds);
				$assetsIds = str_replace(",,", ",", $assetsIds);
				$assetsIds = trim($assetsIds, ",");
				
				$sql = "update user_assets_map set assets_ids = '$assetsIds' where id = $uam_id";
				mysql_query($sql) or die($sql);		
			}
			
			//set assets
			$sql = "select * from user_assets_map where find_in_set(user_id, (select group_concat(user_id) from tbl_users where username in($dealer_code)))";
			
			$rs = mysql_query($sql) or die($sql);		
			while($row = mysql_fetch_array($rs)){
				$uam_id = $row['id'];
				$assetsIds = $row['assets_ids'];
				if($assetsIds != ""){
					$assetsIds = $assetsIds.",".$assets_id;
				}else{
					$assetsIds = $assets_id;
				}				
				$sql = "update user_assets_map set assets_ids = '$assetsIds' where id = $uam_id";
				mysql_query($sql) or die($sql);		
			}
			
			//insert in to trip
			$update = "update tbl_routes set del_date = '".date('Y-m-d H:i:s')."', status = 0 where deviceid = '$assets_id' and del_date is null and status = 1";
			mysql_query($update) or die($update);	
			
			$dealer_code_str = str_replace(",", "-", $dealer_code);
			$dealer_code_str = str_replace("'", "", $dealer_code_str);
			//$tripname = "(".$vehicle.")".$from_landmark." To ".$dealer_code_str." To ".$to_landmark;
			$tripname = $vehicle." - ".$from_landmark." To ".$to_landmark;
			
			$sql = "insert into tbl_routes(userid, routename, route_color, landmark_ids, start_point, end_point, deviceid, round_trip, distance_value, distance_unit, add_uid, add_date) values('$user_id', '$tripname', '$color', '$landmark_ids', '$start_point', '$end_point', '$assets_id', '0', '0', 'KM', '$user_id', '".date('Y-m-d H:i:s')."')";
			
			$rs = mysql_query($sql) or die(mysql_error().":".$sql);			
			$insert_id = mysql_insert_id();
			
			//sub trip 
			$landmark_arr = explode(",", $landmark_ids);
			$first_landmark = $landmark_arr[0];
			$last_landmark = end($landmark_arr);
			
			$sql = "select * from landmark where id in ($landmark_ids) ORDER BY FIELD( id, $landmark_ids ) ";			
			$rs = mysql_query($sql) or die($sql);
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
					mysql_query($sql) or die($sql);
					
					$start_id_sub = $end_id_sub;
					$start_point_sub = $end_point_sub;
				}
				$i++;
			}
			if($first_landmark == $last_landmark){		//return
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
			$rs = mysql_query($sql) or die($sql);
			die("Trip Created Successfully");
						
		}else{
			die("Error: Uploading File");
		}
	}else{
		die("Error: Only .xls or .xlsx files");
	}
}else{
	die("Error: No file uploaded");
}

function generateRandomColor(){
    $color = array('#0000FF','#8A2BE2','#A52A2A','#DEB887','#5F9EA0','#7FFF00','#D2691E','#FF7F50','#6495ED','#DC143C','#00FFFF','#00008B','#008B8B','#B8860B','#006400','#8B008B','#FF0000','#FF8C00','#9932CC','#8B0000','#E9967A','#8FBC8F','#00CED1','#9400D3','#FF1493','#00BFFF','#B22222','#228B22','#FF00FF','#FFD700','#DAA520','#F08080','#9ACD32','#008080','#FF6347','#40E0D0','#EE82EE','#4682B4','#DDA0DD','#FFC0CB', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000', '#FF0000');
	$rand = rand(0,49);
	return $color[$rand];
}
?>