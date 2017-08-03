<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
	<script type="text/javascript">
		$(document).live( "pagecreate", function() {
				var picker = $( "input[type='text']", this );
				$("#datepick").mobipick();
				$("#datepick").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
		});
	</script>
	<?php if(isset($_REQUEST['date']) && isset($_REQUEST['device'])){ ?>
	<div data-role="content">
		<div class="ui-body ui-body-d">
			<table width='100%' id='table' border="1" style='font-size:12px !important'>
				<Tr style='font-weight:bold'><th>Assets Name</th><th>Stop Time</th><th>Start Time</th><th>Location Name</th><th>Duration </th></tr>
				<?php
					if(isset($_REQUEST['page']))
						$page = $_REQUEST['page'];
					else
						$page = 1;
						
					$limit = 10;
					$start = ($page-1)*$limit;
					$end = $page*$limit;
					
					$query = "SELECT count(*) as count FROM tbl_stop_report tm left join assests_master am on tm.device_id=am.device_id WHERE date(tm.ignition_off)='" .date('Y-m-d',strtotime($_REQUEST['date']))."' and ignition_on is not null ";
					if($_REQUEST['device']!='all')
						$query .= " AND tm.device_id ='".$_REQUEST['device']."'";
					
					$res =mysql_query($query) or die($query.mysql_error());
					$row =mysql_fetch_array($res);
					$total =$row['count'];
					if($end >$total)
						$end =$total;
					
					$query = "SELECT tm.device_id, tm.ignition_off, tm.ignition_on, tm.duration, tm.address, am.assets_name FROM tbl_stop_report tm left join assests_master am on tm.device_id=am.device_id WHERE date(tm.ignition_off)='" .date('Y-m-d',strtotime($_REQUEST['date']))."' and ignition_on is not null ";
					if($_REQUEST['device']!='all')
						$query .= " AND tm.device_id ='".$_REQUEST['device']."'";
					$query .= " limit $start,$limit";
					//die($query);
					$res =mysql_query($query) or die($query.mysql_error());
					if(mysql_num_rows($res)<1)
					{
						echo "<Tr><td colspan='5'>No Data Found</td></tr>";
					}
					while($row=mysql_fetch_array($res))
					{
						echo "<Tr style='text-align:center'><td>".$row['assets_name']."</td><td>".date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($row['ignition_off']))."</td><td>".date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($row['ignition_on']))."</td><td>".$row['address']."</td><td>".$row['duration']." </td></tr>";
				}
			?>
		</table>
	</div>
	<div class="ui-body ui-body-d" style='text-align:center'>
		<b>View  <?php echo $start+1; ?>-<?php echo $end; ?> from <?php echo $total; ?></b>
	</div>
	<div class="ui-body ui-body-d">
		<?php if($page >1) { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?date=<?php echo $_REQUEST['date']; ?>&device=<?php echo $_REQUEST['device']; ?>&page=<?php echo $page-1; ?>"  data-role='button' data-theme='b'  data-inline='false' >Previous</a>
		<?php }else {?>
			&nbsp;
		<?php } ?>
		<?php if($end <$total) { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?date=<?php echo $_REQUEST['date']; ?>&device=<?php echo $_REQUEST['device']; ?>&page=<?php echo $page+1; ?>"  data-role='button' data-theme='b'  data-inline='false' >Next</a>
		<?php }?>
	</div>
	<div class="ui-body ui-body-d">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>" data-role='button' data-theme='b'  data-inline='false'>Back</a>
	</div>
</div>
		
	<?php } else { ?>
<div data-role="content">
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='stop_report.php' method='get'>
				<table style='width:100%;'>
					<tr  align='center'>
						<td  align='right'>Date:</td>
  	                	<td><input type="text" name="date" id="datepick"   /></td>
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
										echo "<option value='".$row['device_id']."'";
										if(isset($_REQUEST['device']))
										{
											if($row['device_id']==$_REQUEST['device'])
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
	

	
