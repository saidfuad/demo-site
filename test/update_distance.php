<?php
/*
include("db.php");

$sql = "SELECT distinct assets_id as id FROM `distance_master` where add_date = '2013-08-08' order by assets_id";
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)){
	$id = $row['id'];

	$sub_sql = "select sum(distance) as distance from distance_master where assets_id = $id and add_date = '2013-08-08'";
	$sub_rs = mysql_query($sub_sql);
	$sub_row = mysql_fetch_array($sub_rs);
	
	$distance = $sub_row['distance'];
	
	
	$sub_sql = "select `current_reading` from distance_master where assets_id = $id order by id desc limit 1";
	$sub_rs = mysql_query($sub_sql) or die(mysql_error().":".$sub_sql);
	$sub_row = mysql_fetch_array($sub_rs);
	$current_reading = $sub_row['current_reading'];
	
	$sub_sql = "select `first_reading` from distance_master where assets_id = $id and add_date = '2013-08-07' order by id limit 1";
	$sub_rs = mysql_query($sub_sql) or die(mysql_error().":".$sub_sql);
	$sub_row = mysql_fetch_array($sub_rs);
	$first_reading = $sub_row['first_reading'];
	
	$upd = "insert into distance_master_tmp (select * from from distance_master where add_date = '2013-08-09')";
	
	mysql_query($upd) or die(mysql_error().":".$upd);

}
*/
/*
$sql = "SELECT distinct assets_id as id FROM `distance_master` order by assets_id";
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)){
	$id = $row['id'];
	$sub_sql = "select * from distance_master where assets_id = $id order by id";
	$sub_rs = mysql_query($sub_sql);
	$last_reading = '';
	$total = 0;
	$old_total = 0;
	while($sub_row = mysql_fetch_array($sub_rs)){
		$sub_id = $sub_row['id'];
		$first_reading = $sub_row['first_reading'];
		$current_reading = $sub_row['current_reading'];
		$distance_reading = $sub_row['distance'];
		if($last_reading != "" && $last_reading != $current_reading){
			$first_reading = $last_reading;
			$distance = ($current_reading - $first_reading)/1000;
			
			$upd = "update distance_master set first_reading = '$first_reading', distance = '$distance' where id = $sub_id";
			mysql_query($upd) or die(mysql_error());
			echo $id." : ".$distance." : ".$distance_reading."<br>";
		}
		
		$last_reading = $current_reading;
	}
}
*/
?>