<?php include("php/session.php"); ?>
<?php include("header.php"); 
$user		= $_SESSION['user_id'];
$timezone 	= $_SESSION['timezone'];
$user_agent     = $_SERVER['HTTP_USER_AGENT'];

if($user != 1){
	$query ="select *, CONVERT_TZ(tlp.add_date,'+00:00','".$timezone."') as add_date, Now() as current, TIME_TO_SEC(TIMEDIFF( NOW( ) , tlp.add_date)) as beforeTime from assests_master am left join tbl_last_point tlp on tlp.device_id=am.device_id  where am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user)) and tlp.device_id='".$_REQUEST['device']."' and am.device_id='".$_REQUEST['device']."'"; 
}else{
	$query ="select *, CONVERT_TZ(tlp.add_date,'+00:00','".$timezone."') as add_date, Now() as current, TIME_TO_SEC(TIMEDIFF( NOW( ) , tlp.add_date)) as beforeTime from assests_master am left join tbl_last_point tlp on tlp.device_id=am.device_id  where am.status=1 AND am.del_date is null AND tlp.device_id='".$_REQUEST['device']."' and am.device_id='".$_REQUEST['device']."'"; 
}

$res = mysql_query($query) or die($query. mysql_error());

if(mysql_num_rows($res)>0){
	$row=mysql_fetch_array($res);
	$device_id = $row['device_id'];
	
	$minutes = $row['beforeTime']/60;
	if($minutes <= 1200 && $row['speed'] > 0 && $minutes != ""){
			$status= 'Running';
	}else if($minutes <= 1200  && $row['speed'] == 0 && $minutes != ""){
			$status= 'Parked';
	}else if($minutes >= 1201 && $minutes <= 86399 && $minutes != ""){
			$status= 'Out of Network'; 
	}else if($minutes >= 86400 or $minutes ==""){
			$status= 'Device Fault';
	} 
}else{
	echo '<div data-role="content"><div class="ui-body ui-body-d"><div data-role="fieldcontain"><a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false">Device Not Found '.$lang['back'].'</a></div></div></div>';
	include("footer.php");
		die;
}
?>
<style type="text/css">
	.tbl { border-collapse: collapse; }
	.tbl td { padding:3px; border: solid gold 1px; }
</style>
		<h4 style="margin-top: 10px; margin-bottom: -4px;"><center><?php echo $lang['Assets Details']; ?></center></h4>
		
		<div data-role="content"><!-- style='padding:0px;'-->
				<div class="ui-body ui-body-d">
					<div data-role="fieldcontain">
<?php if(mysql_num_rows($res)<1) { echo "<center>Device Not Found</center>";} else{ ?>
<table class="tbl" style='max-width:100%' width="100%" align="center">
	<tr>
		<td width="50%"><?php echo $row['assets_name']; ?><br>(<?php echo $device_id; ?>)</td>
		<td width="50%"><?php echo $lang[$status]; ?></td>
	</tr>
	<tr>
		<td><?php if($row['ignition']==0){ echo $lang['Engine Off'];} else { echo $lang['Engine On'];}; ?></td>
		<td><?php echo $row['speed']; ?> KM</td>
	</tr>
	<tr>
		<?php $address = explode(",",$row['address']);
				if($address[0]>6){
					$address=$address[0];
				}else{
					$address=$address[0]." ".$address[1];
				}

		?>
		
		<td><?php echo $address; ?></td>
		<td><?php echo date($_SESSION["date_format"]." ".$_SESSION["time_format"],strtotime($row['add_date'])); ?></td>
	</tr>
	<?php if($row['driver_name'] != "" || $row['driver_mobile'] != ""){ ?>
	<tr>
		<td><?php echo $row['driver_name']; ?></td>
		<td><?php echo $row['driver_mobile']; ?></td>
	</tr>
	<?php } ?>
	<?php if($row['current_area'] != ""){ ?>
	<tr>
		<td><B><?php echo $lang['Area']; ?>:</B></td>
		<td><?php echo $row['current_area']; ?></td>
	</tr>
	<?php } ?>
	<?php if( $row['current_landmark'] != ""){ ?>
	<Tr>
		<td><b><?php echo $lang['Landmark']; ?>:</B></td>
		<td><?php echo $row['current_landmark']; ?></td>
	</tr>
	<?php } ?>
</table>
<?php /*	
Assets Name : <br/>
Assets Nickname : <?php echo $row['assets_friendly_nm']; ?><br/>
Device ID : <?php echo $row['device_id']; ?><br/>
Sim Number : <?php echo $row['sim_number']; ?><br/>
Engine : <?php if($row['ignition']==0){ echo "Off";} else { echo "On";}; ?><br/>
Status : <?php echo $status; ?><br/>
Speed : <?php echo $row['speed']; ?> KM <?php if($row['cross_speed']==1){ echo "(Overspeed)";} else { echo "(Normalspeed)";}; ?><br/>
Driver Name : <?php echo $row['driver_name']; ?><br/>
Driver Mobile : <?php echo $row['driver_mobile']; ?><br/>
Address : <?php echo $row['address']; ?><br/>
Date-Time : <?php echo date($_SESSION["date_format"]." ".$_SESSION["time_format"],strtotime($row['add_date'])); ?><br/>
Data Received : <?php echo ago($row['add_date']); ?> ago<br/>
In Area : <?php echo $row['current_area']; ?><br/>
In Landmark : <?php echo $row['current_landmark']; ?><br/> */ ?>
<a href="#" onclick="window.location='live_map.php?device=<?php echo $row['device_id']; ?>'" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['Map']; ?></a>
<a href="all_point_report.php?device=<?php echo $row['device_id']; ?>" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['All Point Report']; ?></a>
<a href="stop_report_live.php?device=<?php echo $row['device_id']; ?>" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['Stop Report']; ?></a>
<a href="area_in_out_report_live.php?device=<?php echo $row['device_id']; ?>" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['Area In Out Report']; ?></a>
<a href="landmark_report_live.php?device=<?php echo $row['device_id']; ?>" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['Landmark Report']; ?></a>
<a href="distance_report_live.php?device=<?php echo $row['device_id']; ?>" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['Distance Report']; ?></a>
<?php if(preg_match('/ipod/i',$user_agent) || preg_match('/iphone/i',$user_agent) || preg_match('/ipad/i',$user_agent)) { ?>
<a href="sms://<?php echo $row['sim_number']; ?>;body=+XT:7005,2,1" data-role='button' data-theme='e' data-inline='false'>Start/Close</a>
<a href="sms://<?php echo $row['sim_number']; ?>;body=+XT:7005,1,1" data-role='button' data-theme='e' data-inline='false'>Stop/Open</a>
<?php } ?>
<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>				
<?php } echo "</div></div></div>";include("footer.php"); ?>
