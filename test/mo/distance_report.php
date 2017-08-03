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
	<center><h3><?php echo $lang['Distance Report']; ?></h3></center>
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
					$sdate=date('Y-m-d 00:00:00',strtotime($_REQUEST['date']));
					$edate=date('Y-m-d 23:59:59',strtotime($_REQUEST['date1']));
					$device=$_REQUEST['device'];
					
					$limit = 5;
					$start = ($page-1)*$limit;
					$end = $page*$limit;
					
					$query = "SELECT count(*) as count from distance_master dm left join assests_master am on am.id=dm.assets_id WHERE  find_in_set(dm.assets_id,(SELECT assets_ids FROM user_assets_map where user_id = $user)) and CONVERT_TZ(dm.add_date,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
					if($device != "all")
					{
						$query .=" AND dm.assets_id = '".$device."'";
					}
					
					$res =mysql_query($query) or die($query.mysql_error());
					$row =mysql_fetch_array($res);
					$total =$row['count'];
					if($end >$total)
						$end =$total;
					
					
					$query = "SELECT dm.id,CONCAT(am.assets_name,'(',am.device_id,')') as assets_id, CONVERT_TZ(dm.add_date,'+00:00','".$_SESSION['timezone']."') as add_date, dm.distance from distance_master dm left join assests_master am on am.id=dm.assets_id  WHERE find_in_set(dm.assets_id,(SELECT assets_ids FROM user_assets_map where user_id = $user)) and CONVERT_TZ(dm.add_date,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "'"; 	
					
					
					if($device != "all")
					{
						$query .=" AND dm.assets_id = '".$device."'";
					}
					$query .= " order by dm.id desc limit $start,$limit";
					$res =mysql_query($query) or die($query.mysql_error());
					if(mysql_num_rows($res)<1)
					{
						echo "<center><b>".$lang['No Data Found'] ."</b></center>";
					}
					while($row=mysql_fetch_array($res))
					{
						echo "<div data-role='collapsible-set' data-theme='b' data-content-theme=''><div data-role='collapsible' data-collapsed=''><h3>".$row['assets_id']."</h3><div data-role='fieldcontain'><b>".$lang['Date'] ." : </b>".date($_SESSION['date_format'],strtotime($row['add_date']))."<br><b>".$lang['Distance'] ." : </b>".$row['distance']." </div></div></div>";
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
						<td align='right'><?php echo $lang['Assets']; ?> :</td>
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