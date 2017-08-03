<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<script type="text/javascript">
		$(document).live( "pagecreate", function() {
				var picker = $( "input[type='text']", this );
				$("#datepick").mobipick();
				$("#datepick1").mobipick();
				$("#datepick").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
				$("#datepick1").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
		});
	</script>
	<center><h3><?php echo $lang['Trip Report']; ?></h3></center>
	<?php if(isset($_REQUEST['date']) && isset($_REQUEST['device'])){ ?>
	<div data-role="content">
	
		<div class="ui-body ui-body-d">
		<!--	<table width='100%' id='table' border="1" style='font-size:12px !important'>
				<Tr style='font-weight:bold'><th>Assets Name</th><th>Stop Time</th><th>Start Time</th><th>Location Name</th><th>Duration </th></tr>-->
				<?php
					$user = $_SESSION['user_id'];
					if(isset($_REQUEST['page']))
						$page = $_REQUEST['page'];
					else
						$page = 1;
					$sdate=date('Y-m-d',strtotime($_REQUEST['date']));
					$edate=date('Y-m-d',strtotime($_REQUEST['date1']));
					$device=$_REQUEST['device'];
					
					$limit = 5;
					$start = ($page-1)*$limit;
					$end = $page*$limit;
							
					$query = "select count(*) as count from trip_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id";
					
					$query .=" where CONVERT_TZ(tl.start_time,'+00:00','".$_SESSION['timezone']."') between  '" . $sdate . "' AND '" . $edate . "' and find_in_set(tl.device_id,(SELECT assets_ids FROM user_assets_map where user_id = $user))"; 
					
					if($device != "all")
					{
						$query .=" AND tl.device_id = '".$device."'";
					}
					//echo $query;
					$res =mysql_query($query) or die($query.mysql_error());
					$row =mysql_fetch_array($res);
					$total =$row['count'];
					if($end >$total)
						$end =$total;
					
					
					
					$query = "select TIME_TO_SEC(TIMEDIFF(tl.end_time , tl.start_time)) as time_taken, CONVERT_TZ(tl.start_time,'+00:00','".$_SESSION['timezone']."') as start_time, CONVERT_TZ(tl.end_time,'+00:00','".$_SESSION['timezone']."') as end_time ,concat(am.assets_name, concat('(',am.device_id,')')) as device_name, rm.routename as name from trip_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id";
					
					$query .=" where CONVERT_TZ(tl.start_time,'+00:00','".$_SESSION['timezone']."') between  '" . $sdate . "' AND '" . $edate . "' and find_in_set(tl.device_id,(SELECT assets_ids FROM user_assets_map where user_id = $user))"; 
					if($device != "all")
					{
						$query .=" AND tl.device_id = '".$device."'";
					}
					$query .= " ORDER BY tl.id desc limit $start,$limit";
					$res =mysql_query($query) or die($query.mysql_error());
					if(mysql_num_rows($res)<1)
					{
						echo "<center><b>".$lang['No Data Found'] ."</b></center>";
					}
					while($row=mysql_fetch_array($res))
					{
						$seconds = $row['time_taken'];
						$hours = floor($seconds / (60 * 60));
						$divisor_for_minutes = $seconds % (60 * 60);
						$minutes = floor($divisor_for_minutes / 60);
						$row['time_taken'] = '';
						if($hours > 0)
							$row['time_taken'] .= $hours." Hour,";
						if($minutes > 0)	
							$row['time_taken'] .= $minutes." Min";
							
						echo "<div data-role='collapsible-set' data-theme='b' data-content-theme=''><div data-role='collapsible' data-collapsed=''><h3>".$row['device_name']."</h3><div data-role='fieldcontain'><b>".$lang['Trip name'] ." : </b>".$row['name'];
						echo "<br><b>".$lang['Start Time'] ." : </b>";
						if($row['start_time']!=""){
							echo date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($row['start_time']));
						}
						
						echo "<br><b>".$lang['End Time'] ." : </b>";
						if($row['end_time']!=""){
							echo date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($row['end_time']));
						}
						echo "<br><b>".$lang['Total Time'] ." : </b>".$row['time_taken'];
						
						echo " </div></div></div>";
					}
			?>
	<!--	</table>-->
	</div>
	<div class="ui-body ui-body-d" style='text-align:center'>
	<?php if($total>1) { ?>
		<b><?php echo $lang['View']." "; echo $start+1; ?>-<?php echo $end." "; echo $lang['from']." "; echo $total; ?></b>

		<?php if($page >1) { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?date1=<?php echo $_REQUEST['date1']; ?>&date=<?php echo $_REQUEST['date']; ?>&device=<?php echo $_REQUEST['device']; ?>&page=<?php echo $page-1; ?>"  data-role='button' data-theme='e'  data-inline='false' ><?php echo $lang['Previous']; ?></a>
		<?php }else {?>
			&nbsp;
		<?php } ?>
		<?php if($end <$total) { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?date1=<?php echo $_REQUEST['date1']; ?>&date=<?php echo $_REQUEST['date']; ?>&device=<?php echo $_REQUEST['device']; ?>&page=<?php echo $page+1; ?>"  data-role='button' data-theme='e'  data-inline='false' ><?php echo $lang['Next']; ?></a>
		<?php } } ?>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>" data-role='button' data-theme='e'  data-inline='false'><?php echo $lang['back']; ?></a>
	</div>
</div>
		
	<?php } else { ?>
<div data-role="content">
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
				<table style='width:100%;'>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['From Date']; ?>:</td>
  	                	<td><input type="text" name="date" id="datepick"   /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['To Date']; ?>:</td>
  	                	<td><input type="text" name="date1" id="datepick1"   /></td>
					</tr>
					<?php 
						$user = $_SESSION['user_id'];
						$query = "select id, assets_name, device_id from assests_master where status=1 AND del_date is null AND find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
						$res =mysql_query($query) or die($query.mysql_error());
					?>
					<tr align='center'>
						<td align='right'>Assets :</td>
						<td>
							<select name="device" id="device" data-theme="b">
								<?php
									if(mysql_num_rows($res)>1)
										echo "<option value='all'>All Assets</option>";
									while($row =mysql_fetch_array($res))
									{
										echo "<option value='".$row['id']."'";
										if(isset($_REQUEST['device']))
										{
											if($row['id']==$_REQUEST['device'])
											{
												echo " selected=selected ";
											}
										}
										echo " >".$row['assets_name']."</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr align='center'>
						<td colspan='2'><input data-theme="e" value="Search" type="submit" ></td>
					</tr>
					<tr>
						<td colspan='2'><input data-theme="e" value="Back" type="button" onclick='window.location="reports.php"' /></td>
					</tr>
				</table>
			</form>
        </div>
	</div>
</div>
<?php } include("footer.php"); ?>