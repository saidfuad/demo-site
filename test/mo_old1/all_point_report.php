<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<?php $device_id = $_REQUEST['device']; ?>

	<?php 
			$user = $_SESSION['user_id'];
			 if(isset($_REQUEST['date']) && isset($_REQUEST['device'])){ 
			$query = "select am.assets_name,am.device_id	from assests_master am where am.id='".$_REQUEST['device']."' and find_in_set(am.id,(SELECT assets_ids FROM user_assets_map where user_id = $user))";
			 }else{		
			$query = "select am.assets_name,am.device_id	from assests_master am where am.device_id='".$_REQUEST['device']."' and find_in_set(am.id,(SELECT assets_ids FROM user_assets_map where user_id = $user))";
			 } 
			$res =mysql_query($query) or die($query.mysql_error());
			$names = "";
			if(mysql_num_rows($res)==1){
				$row =mysql_fetch_assoc($res);
				$names = " of ".$row['assets_name']." (".$row['device_id'].")";
			}
	?>
	<center><h3><?php echo $lang['All Point Report'].$names; ?></h3></center>
	<div data-role="content">
			<div class="ui-body ui-body-d">
				<a href="all_point_report_live.php?device=<?php echo $device_id; ?>" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['GridView']; ?></a>
				<a href="all_point_report_live_map.php?device=<?php echo $device_id; ?>" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['MapView']; ?></a>
				<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
		</div>
	</div>
<?php include("footer.php"); ?>