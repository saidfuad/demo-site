<?php 
include("session.php"); 
require_once("../../db.php");
		$sdate=$_POST['date'];
		$stime=$_POST['test_start'];
		$etime=$_POST['test_stop'];
		$device=$_POST['device'];
		$distance=$_POST['distance'];
		
		$sddate = date("Y-m-d H:i:s", strtotime($sdate." ".$stime));
		$eedate = date("Y-m-d H:i:s", strtotime($sdate." ".$etime));
		//echo $sddate." ".$eedate;
		/*if($distance=="")
			$distance = 'hourly';
		if($distance=="10 minits")
		{
			$format = '600';
			$min = '30 minutes';
		}	
		if($distance=="15 minits")
		{
			$format = '900';
			$min = '30 minutes';
		}
		if($distance=="30 minits")
		{
			$format = '1800';
			$min = '30 minutes';
		}
		if($distance=="45 minits")
		{
			$format = '2700';
			$min = '45 minutes';
		}
		if($distance=="Hour")
		{
			$format = '3600';
			$min = '60 minutes';
		}
		
		*/
		
		$hours=floor((strtotime($eedate)-strtotime($sddate))/3600); 
		//echo $sdate;
	// 20
		if($hours>=20)
		{
			$diff=22*180; // 44 mins
		}
		else if($hours>=16)
		{
			$diff=18*180; // 36 mins
		}
		else if($hours>=12)
		{
			$diff=14*180; // 28 mins
		}
		else if($hours>=8)
		{
			$diff=10*180; // 20 mins
		}
		else if($hours>=4){
			$diff=6*180; // 12 mins
		}
		else{
			$diff=2*180; // 4 mins
		} 
		
//echo $hours;
		$query="select date_format(CONVERT_TZ(add_date,'+00:00','".$_SESSION['timezone']."'), '%Y-%m-%d %H:%i') as add_date, speed from tbl_track where CONVERT_TZ(add_date,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sddate . "' AND '" . $eedate . "' ";
		if($device!="all")
			$query .=" AND assets_id=".$device ;
		//$query .= " GROUP BY floor( to_seconds(add_date) /$diff )";
		
		//echo $query;
		$result=mysql_query($query) or generateMSG("", "SQL : ".$query."<br> Error : ".mysql_error());
		$XAxis = array();
		$Speed = array();
		while($row=mysql_fetch_array($result)){
				$XAxis[]=$row['add_date'];
				//$XAxis[]=$row['add_date'];
				 $Speed[]=Round($row['speed'],2);
		}
		$data['XAxis']=$XAxis;
		$data['y_axis']=$Speed;
		$data['Name']="speed";
		die(json_encode($data));
?>
