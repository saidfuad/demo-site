<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
		<div data-role="content"><!-- style='padding:0px;'-->
				<div class="ui-body ui-body-d">
					<input type="search" name="search" id="searc-basic" value="<?php echo $_REQUEST['search']; ?>" onblur="window.location='live.php?search='+this.value"/>
					<div data-role="fieldcontain">
						<ul data-role="listview" data-divider-theme="b" data-inset="true">
							
        
						<?php
							if(isset($_REQUEST['page']))
								$page = $_REQUEST['page'];
							else
								$page = 1;
							$limit = 15;
							$start = ($page-1)*$limit;
							$end = $page*$limit;
							$user = $_SESSION['user_id'];
							
							$query = "select count(*) as count from assests_master where status=1 AND del_date is null AND find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
							if(isset($_REQUEST['search']) && $_REQUEST['search']!="")
							$query .= " and assets_name like ('%".$_REQUEST['search']."%')";
	
							$res =mysql_query($query) or die($query.mysql_error());
							$row =mysql_fetch_array($res);
							$total =$row['count'];
							if($end >$total)
								$end =$total;
							$query = "select am.id, am.assets_name, am.device_id,tlp.speed, TIME_TO_SEC(TIMEDIFF( NOW( ) , tlp.add_date)) as beforeTime from assests_master am left join tbl_last_point tlp on tlp.device_id=am.device_id  where am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
							if(isset($_REQUEST['search']) && $_REQUEST['search']!="")
							$query .= " and assets_name like ('%".$_REQUEST['search']."%')";
							$query .= " limit $start,$limit";
							$res =mysql_query($query) or die($query.mysql_error());
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
							echo "</ul><center>
							View ".($start+1)." - $end of $total<br>";
							if($page >1) { ?>
									<a href="live.php?page=<?php echo $page-1; ?>" data-role='button' data-theme='e'  data-inline='false'>Previous</a>
							<?php }?>
							<?php if($end <$total) { ?>
								<a href="live.php?page=<?php echo $page+1; ?>" data-role='button' data-theme='e'  data-inline='false'>Next</a>
							<?php }
							echo "</center>";
							?>
					</div>
					
				</div><!-- /body-d -->
		</div>

<?php include("footer.php"); ?>
