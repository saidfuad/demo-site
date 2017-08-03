<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<?php $device_id = $_REQUEST['device']; ?>
	<script type="text/javascript">
		var latArr = new Array();
		var lngArr = new Array();
		var htmlArr = new Array();
		var ignitionArr = new Array();
		var directionsDisplayAllpoint = [];
		var arrowMarkerAllpoint = [];
		var mapmapAllpoint = null;
		$(document).live( "pagecreate", function() {
				var picker = $( "input[type='text']", this );
				$("#all_point_report_live_m").mobipick({   locale: "en" });
				$("#all_point_report_live_m1").mobipick({  locale: "en" });
				$("#all_point_report_live_m").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
				$("#all_point_report_live_m1").bind( "change", function() {
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
		$names1 = "";
		if(mysql_num_rows($res)==1){
			$row =mysql_fetch_assoc($res);
			$names = " of ".$row['assets_name']." (".$row['device_id'].")";
			$names1 = $row['assets_name']." (".$row['device_id'].")";
			
		}
	?>
	<center><h3><?php echo $lang['All Point Map'].$names; ?></h3></center>
	<?php if(isset($_REQUEST['date']) && isset($_REQUEST['date1']) && isset($_REQUEST['device'])){ ?>
	<div data-role="content">
		<div class="ui-body ui-body-d">
			<script>
				onLoadmapAllpoint();
			<?php
				$device = $_REQUEST['device'];
				$sdate = date("Y-m-d H:i:s",strtotime($_REQUEST['date']));
				$edate = date("Y-m-d H:i:s",strtotime($_REQUEST['date1']));
				$user_tz = $_SESSION['timezone'];
				$qry_rs="SELECT id, lati, longi, phone_imei, CONVERT_TZ(add_date,'+00:00','$user_tz') as add_date, speed, device_id, dt, ignition, address, odometer FROM tbl_track WHERE ";
				if($sdate && $edate){	//search by date
					$sdate = date("Y-m-d H:i:s", strtotime($sdate));
					$edate = date("Y-m-d H:i:s", strtotime($edate));
				}else{
					$sdate = date("Y-m-d H:i:s");
					$edate = date("Y-m-d H:i:s");
				}
				$qry_rs.="CONVERT_TZ(add_date,'+00:00','$user_tz') BETWEEN '".$sdate."' AND '" . $edate . "'";
				$qry_rs.="AND assets_id='$device'  Order by id ";
				$res =mysql_query($qry_rs) or die($qry_rs.mysql_error());
				$lats = 0;
				$lngs = 0;
				$count=0;
				$DistanceVal=0;
				$i =0;
				$total = mysql_num_rows($res);
				while($rows =mysql_fetch_assoc($res)){
						if($total>1){
							if($i==0){
								$DistanceVal=$rows['odometer'];
							}
							$i++;
							if($total==$i){
								$DistanceVal=floatval(($rows['odometer']-$DistanceVal)/1000);
							}
						}
						echo "\n latArr.push(".$rows['lati'].");";
						echo "\n lngArr.push(".$rows['longi'].");";
						$lats = $rows['lati'];
						$lngs = $rows['longi'];
						$text = 'Date : '.date($date_format.' '.$time_format, strtotime($rows['add_date']))."<br>";
						$text .= 'Speed : '.$rows['speed']."<br>";
						$text .= 'Address : '.$rows['address'].'<br>';
						echo "\n htmlArr.push('".$text."');";
						echo "\n ignitionArr.push(1);";
						$ignition_status[]=1;
				}
				echo "\n var distance = ".$DistanceVal."; \n	distance=Math.round(distance*100)/100; \n	var txt = '".$names1."  Distance : ' + distance + ' KM '; \n viewTrackAllpoint(txt); \n";

			?>
			
			function viewTrackAllpoint(devText){
				clearOverlaysAllpoint();
				var myTextDiv = document.createElement('div');
				myTextDiv.id = 'my_text_div';
				myTextDiv.innerHTML = '<Span id="distance_txt_all_p" style="color:black;background-color:rgba(255,255,255,0.7);display:none">'+devText+'</span>';
				myTextDiv.style.color = 'white';
				mapmapAllpoint.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv);
				//alert(latArr.length);
				totalDir=Math.floor(latArr.length/9);
				call_start_new_line(0);
			}
			function onLoadmapAllpoint() {
				$("#allpoints_grid_div<?php echo time(); ?>").hide();
				$("#all_pont_map<?php echo time(); ?>").show();
				directionsService = new google.maps.DirectionsService();
				var mapObjmap = document.getElementById("all_pont_map<?php echo time(); ?>");
				if (mapObjmap != 'undefined' && mapObjmap != null) {

				mapOptionsmapAllpoint = {
					zoom: 5,
					mapTypeId: google.maps.MapTypeId.HYBRID,
					mapTypeControl: true,
					mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
				};

				mapOptionsmapAllpoint.center = new google.maps.LatLng(
					<?php echo $lats; ?>,<?php echo $lngs; ?>
				);
				
				mapmapAllpoint = new google.maps.Map(mapObjmap,mapOptionsmapAllpoint);
				mapmapAllpoint.enableKeyDragZoom();	
				allpointBounds = new google.maps.LatLngBounds();
			  }
			}
			function clearOverlaysAllpoint() {
				if (directionsDisplayAllpoint) {
					for (i in directionsDisplayAllpoint) {
					  directionsDisplayAllpoint[i].setMap(null);
					}
				 }
				directionsDisplayAllpoint = [];	
				if(arrowMarkerAllpoint.length > 0){
					arrowMarkerAllpoint = [];
					markerClusterAllpoint.clearMarkers();
				}
				for(i=0; i< (mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
					mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
				}
				if (markersmapAllpoint) {
					for (i in markersmapAllpoint) {
						markersmapAllpoint[i].setMap(null);
					}
				}
				if (polylinesmapAllpoint) {
					for (i in polylinesmapAllpoint) {
						polylinesmapAllpoint[i].setMap(null);
					}
				}
				markersmapAllpoint = [];
				polylinesmapAllpoint = [];
				wayptsAllpoint = [];
			}
			function viewLocationAllpoint(lat, lng, html){
				onLoadmapAllpoint();
				clearOverlaysAllpoint();
				var point = new google.maps.LatLng(lat, lng);
				var text = "<div style='font-size:12px;line-height: 14px;'> " + html + "</div>";
				markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",text, '', '', "sidebar_map", '' ));	
				mapmapAllpoint.setCenter(point);
				
			}
						
			
			</script>
			<div id="all_pont_map<?php echo time(); ?>" style="width: 100%; height: 90%; position:relative;"></div>
		</div>
		<div class="ui-body ui-body-d" style='text-align:center'>
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
						<td  align='right'><?php echo $lang['From Date']; ?>:</td>
  	                	<td><input type="text" name="date" id="all_point_report_live_m"   /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['To Date']; ?>:</td>
  	                	<td><input type="text" name="date1" id="all_point_report_live_m1"   /></td>
					</tr>
					<?php 
						$user = $_SESSION['user_id'];
						$query = "select * from assests_master where status=1 AND del_date is null AND device_id='".$device_id."'";
						
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
						<td colspan='2'><input data-theme="e" value="Search" type="submit" ></td>
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