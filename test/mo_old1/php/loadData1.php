<?php 

require_once("../../db.php");
		$sdate=$_POST['date'];
		$stime=$_POST['test_start'];
		$etime=$_POST['test_stop'];
		$device=$_POST['device'];
		$time=$_POST['time'];
		list($shour,$sminute,$ssecond) = explode(':', $stime)."<br>";
	
	
		die($shour);
		$sddate = date("Y-m-d H:i:s", strtotime($sdate." ".$stime));
		$eedate = date("Y-m-d H:i:s", strtotime($sdate." ".$etime));

		$query="select distinct(date_format(add_date, '%Y-%m-%d %H:%i')) as add_date, max(speed) as speed from tbl_track where add_date BETWEEN '" . $sddate . "' AND '" . $eedate . "' AND device_id=".$device." group by date_format(add_date, '%Y-%m-%d %H:%i')";
	
		$result=mysql_query($query);
		$XAxis = array();
		$Speed = array();
		while($row=mysql_fetch_array($result)){
				 $XAxis[]=$row['add_date'];
				 $Speed[]=$row['speed'];
		}
		$data['XAxis']=$XAxis;
		$data['Speed']=$Speed;
		$data['Name']="speed";
		die(json_encode($data));

function sum_the_time($time1, $time2) {
  $times = array($time1, $time2);
  $seconds = 0;
  foreach ($times as $time)
  {
    list($hour,$minute,$second) = explode(':', $time);
    $seconds += $hour*3600;
    $seconds += $minute*60;
    $seconds += $second;
  }
  $hours = floor($seconds/3600);
  $seconds -= $hours*3600;
  $minutes  = floor($seconds/60);
  $seconds -= $minutes*60;
  // return "{$hours}:{$minutes}:{$seconds}";
  return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
}
?>
