<?php session_start(); ?>
<?php include("../../db.php");
function get_new_locations() 
{
		//Select table name
		
		$date_time = date("Y-m-d H:i:s", $_REQUEST['date_time']);
		$timezone 	= $_SESSION['timezone'];
		
		$device = $_REQUEST['device'];
		
		$fqry="SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$timezone."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.speed, lm.ignition, im.icon_path FROM assests_master am LEFT JOIN tbl_last_point lm ON lm.device_id = am.device_id LEFT JOIN icon_master AS im ON im.id = am.icon_id WHERE lm.device_id =".$device." AND am.status = 1 AND CONVERT_TZ(lm.add_date,'+00:00','".$timezone."') > '".$date_time."' ";
		$query = mysql_query($fqry);
		return $query;
}
	function get_old_locations($id) 
	{
		$timezone 	= $_SESSION['timezone'];
		
		$old_query = "SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$timezone."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.angle_dir, lm.speed, lm.ignition, im.icon_path, am.assets_category_id from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where lm.device_id = $id AND am.status = 1 limit 1";
				
		$query = mysql_query($old_query);
		return $query;
	}
		$rs = get_new_locations();
		
		$lat = array();
		$lng = array();
		$ignition = array();
		$speedArr = array();
		$html = array();
		$html_addr = array();
		$text_address="";
	
		if(mysql_num_rows($rs)>0)
		{			
		while ($row = mysql_fetch_array($rs)) {
		
            $lat[] = $row['lati'];
			$lng[] = $row['longi'];
			$ignition[] = $row['ignition'];
			$speedArr[] = $row['speed'];
			
			$text_address .= $row['assets_name'].'('.$row['device_id'].')';
			$text_address .= ", ".ago($row['add_date'])." ago";
			$text_address .= ", ".date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($row['add_date']));
			$text_address .= ", Speed: ".$row['speed']." KM";
		
			if($row['address'] != ""){
				$text_address .= ", ".$row['address'];
			}
			
			//$text .= 'Mobile : '.$row['sim_number.'<br>';
			$html_addr[] = $text_address;
			$data["last_id"] = $row['id'];
			$data["last_datetime"] = strtotime($row['add_date']);
			}
		}
		else{
			
			$rs = get_old_locations($_REQUEST['device']);
		
			if(mysql_num_rows($rs)>0) {
			
				$text_address="";
				while ($row = mysql_fetch_array($rs)) {
					
					$text_address .= $row['assets_name'].'('.$row['device_id'].')';
					$text_address .= ", ".ago($row['add_date'])." ago";
					$text_address .= ", ".date("Y-m-d H:i:s",strtotime($row['add_date']));
					$text_address .= ", Speed: ".$row['speed']." KM";
					if($row['address'] != ""){
						$text_address .= ", ".$row['address'];
					}
					$html_addr = $text_address;					
				}
			}
		}
		
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['ignition'] = $ignition;
		$data['speed'] = $speedArr;
		$data['html'] = $html;
		$data['html_address'] = $html_addr;
		die(json_encode($data));
?>