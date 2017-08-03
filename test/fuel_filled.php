<?php
include("db.php");
$assets_id = $_REQUEST['assets_id'];
if($assets_id == ""){
	exit;
}
$from = $_REQUEST['from'];
$to = $_REQUEST['to'];
if($from && $to){
	$from 	= date('Y-m-d', strtotime($from));
	$to		= date('Y-m-d', strtotime($to));
}
$sql = "select * from fuel_log where assets_id = '$assets_id' and date(convert_tz(add_date, '+00:00', '+05:30')) between '$from' and '$to' order by id";
$rs = mysql_query($sql) or die(mysql_error());
$old_fuel = '';
$first_reading = '';
$last_reading = '';
$stack = array();

while($row = mysql_fetch_array($rs)){
	$new_fuel = $row['fuel_liters'];
	if($first_reading != "" && $first_reading < $new_fuel){
		$stack[] = intval($new_fuel);
	}
	$date = $row['add_date'];
	$date = date('d.m.Y h:i a',strtotime($date . " +5 hours 30 minutes"));
	if(count($stack) > 4){
		$items = array_slice($stack, -5);
	}
	if($old_fuel != "" && $old_fuel < $new_fuel && $first_reading == ""){
		$first_reading = $old_fuel;
	}else if( count($stack) > 4 && $items[0] == $items[1] && $items[1] == $items[2] && $items[2] == $items[3] && $items[3] == $items[4]){
		$last_reading = $old_fuel;
	}
	if($first_reading != "" && $last_reading != ""){
		$diff = intval($last_reading - $first_reading);	
		if($diff > 5){
			echo "<b>Old Fuel : </b>$first_reading Ltr, <b>New Fuel : </b>$last_reading Ltr, <b>Filled Fuel : </b>$diff Ltr, <b>Date : </b>$date<br>";
		}
		$old_fuel = '';
		$first_reading = '';
		$last_reading = '';
		$stack = array();
	}
	
	$old_fuel = $new_fuel;
}
?>
