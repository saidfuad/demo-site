<?php require_once("db.php"); ?>
<?php

	$device = $_REQUEST["device"];
	
	if(intval($device) > 0) {
		$query = "SELECT * FROM `tbl_track` where device_id = $device order by id desc limit 0, 10";
		$res   = mysql_query($query) or die("Failed to Fetch Details");
		
		$no_rs = mysql_num_rows($res);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Check The Device</title>
<style type="text/css">
<!--
@import url("table/style.css");
-->
</style>
</head>

<body>
<form name="frm" method="post">
Device ID : <input type="text" value="<?php echo $device; ?>" name="device" id="device" />&nbsp;&nbsp;<input type="submit" value='Check'>
</form>
<br />
<?php

if($no_rs) { ?>

<table id="newspaper-b" summary="Data Check for Nkonnect VTS">
<thead>
    	<tr>
		<th scope="col">ID</th>
        <th scope="col">Device</th>
        <th scope="col">Lat</th>
        <th scope="col">Lng</th>
        <th scope="col">Add Date</th>
        <th scope="col">Speed</th>
        <th scope="col">GPS</th>
        <th scope="col">Ignition</th>
        <th scope="col">Box Open</th>
        <th scope="col">Power St.</th>
        <th scope="col">Reason</th>
        <th scope="col">Int. Batt.</th>
        <th scope="col">Ext. Batt. Volt</th>
        <th scope="col">RFID</th>
        <th scope="col">Fuel Percent</th>
        <th scope="col">Temperature</th>
        </tr>
</thead>
        <tfoot>
    	<tr>
        	<td colspan="19" class="rounded-foot-left"><em>Data Check for Nkonnect VTS Device - <?php echo $device; ?></em></td>
        </tr>
    </tfoot>
    <tbody>        
<?php
	$l = 0;
	while($row = mysql_fetch_assoc($res)) {
		$l++;
		echo "<tr><td>".$l."</td><td>".$row['device_id']."</td><td>".$row['lati']."</td><td>".$row['longi']."</td><td nowrap='nowrap'>".date(DISP_TIME, strtotime($row['add_date']))."</td><td>".$row['speed']."</td><td>".$row['gps']."</td><td>".$row['ignition']."</td><td>".$row['box_open']."</td><td>".$row['power_st']."</td><td>".$row['reason']."</td><td>".$row['in_batt']."</td><td>".$row['ext_batt_volt']."</td><td>".$row['rfid']."</td><td>".$row['fuel_percent']."</td><td>".$row['temperature']."</td></tr>";
		
	}
?>
  </tbody>
</table>
<?php
}
?>
</body>
</html>