<?php 
include("session.php"); 
require_once("../../db.php");
		$sdate=$_REQUEST['from_date'];
		$edate=$_REQUEST['to_date'];
		$device=$_REQUEST['device'];
			//search by date
		$sdate = date("Y-m-d 00:00:00", strtotime($sdate));
		$edate = date("Y-m-d 23:59:59", strtotime($edate));

	
		$query="SELECT assets_id, CONVERT_TZ(add_date,'+00:00','".$_SESSION['timezone']."') as add_date,distance from distance_master WHERE CONVERT_TZ(add_date,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		
		
		if($device!="all")
			$query .=" AND assets_id='".$device."'" ;
		
		
		$query .= " group by date_format(add_date, '%Y-%m-%d %H:%i')";
		//echo $query;
		$result=mysql_query($query) or generateMSG("", "SQL : ".$query."<br> Error : ".mysql_error());
		
		$record_items = array();
		$distance = array();
		$lat1 = '';
		$lng1 = '';
		$date2 = '';
		while($row=mysql_fetch_array($result))
		{
			$date = date('d.m.Y',strtotime($row['add_date']));
			$distance[$date] = $row['distance'];
			$device = $row['assets_id'];
		}
		$total = 0;
		$XAxis = array();
		$Speed = array();
		$x_axis = array();
		$y_axis = array();
		foreach ($distance as $date => $value) {
            $x_axis[] = $date;
			$y_axis[] = round($value, 2);
        }
		$values = array_values($distance);
		if(count($distance)) {
			$data["x_max"] = ceil(max($y_axis)) + 100;
		}
		$data['x_axis']=$x_axis;
		$data['y_axis']=$y_axis;
		die(json_encode($data));
	
?>
