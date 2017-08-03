<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<?php $device_id = $_REQUEST['device']; ?>
	<script type="text/javascript">
		$(document).live( "pagecreate", function() {
				var picker = $( "input[type='text']", this );
				$("#landmark_report_live").mobipick();
				$("#landmark_report_live1").mobipick();
				$("#landmark_report_live").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
				$("#landmark_report_live1").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
		});
	</script>
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
	<center><h3><?php echo $lang['Landmark Report'].$names; ?></h3></center>
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
					
					$limit = 50;
					$start = ($page-1)*$limit;
					$end = $page*$limit;
					
					$query = "SELECT count(*) as count from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where find_in_set(lg.device_id,(SELECT assets_ids FROM user_assets_map where user_id = $user))"; 
					$query .= " AND CONVERT_TZ(lg.date_time,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
					if($device != "all")
					{
						$query .=" AND lg.device_id = '".$device."'";
					}
					
					$res =mysql_query($query) or die($query.mysql_error());
					$row =mysql_fetch_array($res);
					$total =$row['count'];
					if($end >$total)
						$end =$total;					
					
					$query = "SELECT CONVERT_TZ(lg.date_time,'+00:00','".$_SESSION['timezone']."') as date_time, lg.distance, concat(am.assets_name, concat('(',am.device_id,')')) as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where find_in_set(lg.device_id,(SELECT assets_ids FROM user_assets_map where user_id = $user))"; 
		
					$query .= " AND CONVERT_TZ(lg.date_time,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
					
					
					if($device != "all")
					{
						$query .=" AND lg.device_id = '".$device."'";
					}
					$query .= " ORDER BY lg.id desc limit $start,$limit";
					$res =mysql_query($query) or die($query.mysql_error());
					if(mysql_num_rows($res)<1)
					{
						echo "<center><b>".$lang['No Data Found'] ."</b></center>";
					}else{
						$row=mysql_fetch_array($res);
						echo "<div data-role='collapsible-set' data-theme='b' data-content-theme=''><div data-role='collapsible' data-collapsed=''><h3>".$row['device_name']."</h3><div data-role='fieldcontain'>";
						echo '<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="'.$lang['Columns to display...'].'" data-column-popup-theme="a"><thead><tr><th data-priority="1">'.$lang['Landmark Name'].' </th><th data-priority="1">'.$lang['Date Time'] .' </th><th data-priority="1" >'.$lang['Distance'] .' </th></tr></thead>';
						$footer="</tbody></table></div></div></div>";
					}
					$res =mysql_query($query) or die($query.mysql_error());
					while($row=mysql_fetch_array($res))
					{
						echo '<tr><td>'.$row['landmark_name'].'</td><td>'.date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($row['date_time'])).'</td><td style="text-align:center">'.$row['distance'].'</td></tr>';
					}
					echo $footer;
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
		<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
	</div>
</div>
		
	<?php } else { ?>
<div data-role="content">
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
				<table style='width:100%;'>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['From Date']; ?> :</td>
  	                	<td><input type="text" name="date" id="landmark_report_live"   /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['To Date']; ?> :</td>
  	                	<td><input type="text" name="date1" id="landmark_report_live1" /></td>
					</tr>
					<?php 
						$user = $_SESSION['user_id'];
						$query = "select * from assests_master where status=1 AND del_date is null AND device_id=".$device_id;
						$res =mysql_query($query) or die($query.mysql_error());
						while($row =mysql_fetch_array($res))
						{
							$div_id = $row['id'];
						}
					?>
					<tr align='center'>
						<td> <input type="hidden" name="device" id="device" value="<?php echo $div_id; ?>"/>
							
						</td>
					</tr>
					<tr align='center'>
						<td colspan='2'><input data-theme="e" value=<?php echo $lang['search']; ?> type="submit" ></td>
					</tr>
					<tr>
						<td colspan='2'><a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a></td>
					</tr>
				</table>
			</form>
        </div>
	</div>
</div>
<?php } include("footer.php"); ?>