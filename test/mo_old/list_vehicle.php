<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
		<div data-role="content"><!-- style='padding:0px;'-->
				<div class="ui-body ui-body-d">
					<div data-role="fieldcontain">
						<?php if($_REQUEST['device_ids']!=""){ ?>
						<a href="#" onclick='window.location="all_assets_map.php?device=<?php echo $_REQUEST['device_ids']; ?>"' data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['All Assets Map']; ?></a><br />
						<?php } ?>
						<ul data-role="listview" data-filter="true" data-divider-theme="b" data-inset="true">
							
        
						<?php
							
							$user = $_SESSION['user_id'];
							
							if(trim($_REQUEST['device_ids']) == '') {
								$query = "SELECT am.id, am.assets_name, am.device_id, tlp.speed, TIME_TO_SEC(TIMEDIFF( NOW(), tlp.add_date)) as beforeTime FROM assests_master am LEFT JOIN tbl_last_point tlp ON tlp.device_id=am.device_id WHERE am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
							}
							else {
								$query = "SELECT am.id, am.assets_name, am.device_id, tlp.speed, TIME_TO_SEC(TIMEDIFF( NOW(), tlp.add_date)) as beforeTime FROM assests_master am LEFT JOIN tbl_last_point tlp ON tlp.device_id=am.device_id WHERE am.status=1 AND am.del_date is null";
							}
							
							if(isset($_REQUEST['device_ids'])) {
								$query .= " AND am.device_id IN ('".str_replace(',', "','", $_REQUEST['device_ids'])."')";
							}

							$query .= " ORDER BY am.assets_name ";
							
							$res =mysql_query($query) or die($query.mysql_error());
							
							if(mysql_num_rows($res)==0){
								echo "<li data-theme='c'><a href='#' data-rel='back' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>No Assets to View Click Here To Back</div></div></a></li>";
							}
							while($row =mysql_fetch_array($res))
							{
								if($row['speed']=="")
									$row['speed']="N/A";
								$status = '';
								$minutes = $row['beforeTime']/60;
								if($minutes <= 1200 && $row['speed'] > 0 && $minutes != ""){
										//$status= 'Running';
										$status= 'width:50px;color:#74B042;';
								}else if($minutes <= 1200  && $row['speed'] == 0 && $minutes != ""){
										//$status= 'Parked';
										$status= 'width:50px;color:#74B042;';
								}else if($minutes >= 1201 && $minutes <= 86399 && $minutes != ""){
										//$status= 'Out of Network'; 
										$status= 'width:50px;color:red;';
								}else if($minutes >= 86400 or $minutes ==""){
										//$status= 'Device Fault';
										$status= 'width:50px;color:red;';
								} 
								//echo "<a href='view.php?device=".$row['device_id']."' data-role='button' data-theme='b'  data-inline='false'>".$row['assets_name']."</a>";
								
								echo "<li data-theme='c'><a href='view.php?device=".$row['device_id']."' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-a'  style='".$status."'>".$row['speed']."</div><div class='ui-block-b'>".$row['assets_name']."</div></div></a></li>";
							}
							echo "</ul>";
							?>
							<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
					</div>
					
				</div><!-- /body-d -->
		</div>

<?php include("footer.php"); ?>
