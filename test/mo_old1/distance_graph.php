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
	
	
<style type="text/css">

.jqplot-highlighter-tooltip{
	    background: none repeat scroll 0 0 #BBBBBB;
    border-radius: 5px 5px 5px 5px;
    color: black;
    display: none;
    left: 299.17px;
    opacity: 0.7;
    padding: 5px;
    position: absolute;
    top: 165.227px;
}
</style>
	<?php if(isset($_REQUEST['from_date']) && isset($_REQUEST['to_date']) && isset($_REQUEST['device'])){ ?>
	<center><h3>Distance Graph</h3></center>
	
		<script>
		$(document).ready(function(){
		function call_data (device,from_date,to_date)
		{
			
			/*var start   =  $('#datepick').datepicker('getDate');
			var end = $('#datepick1').datepicker('getDate');
			var days   = (end - start)/1000/60/60/24;
			var sg_device = $('#device').val();
			if(days >= 15) {
				$("#alert_dialog").html('<?php echo "Date Difference Must be Smaller Than 15 Days;" ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			if(sg_device == "") {
				$("#alert_dialog").html('<?php echo "Please select device"; ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			 $("#divAjaxIndex").dialog("close");
			*/		
			$.post("php/dis_loadData.php", { device: device, from_date: from_date, to_date: to_date},
			function(data) {
		 	$("#distance_graph").html('');
		 
		 
			// line = [['Cup Holder Pinion Bob', 7], ['Generic Fog Lamp', 9], ['HDTV Receiver', 15], ['8 Track Control Module', 12], [' Sludge Pump Fourier Modulator', 3], ['Transcender/Spice Rack', 6], ['Hair Spray Danger Indicator', 18]];
		if(data.x_axis.length > 0){
			line = data.y_axis;
			ticks1 = data.x_axis;
			min_val = 0;
			max_val = data.x_max;
			
			plot2 = $.jqplot('distance_graph', [line], {
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: { show: true },
					location:'n',
					rendererOptions:{
						//barWidth: '20'
					}
				},
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
					  angle: -30,
					  fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						label: 'Date V/s Distance Travelled',
						ticks: ticks1,
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
							angle: -30
						}
					},
					yaxis: {
						
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						   angle: -30,
						},
						min:min_val,
						max:max_val
					}
				}
			});
		}else{
			$('#distance_graph').html('<center><?php echo    "No_Data_Found"; ?></center>');
		}
		 $("#divAjaxIndex").dialog("close");
		$("#loading_top").css("display","none");
	 }, 'json');
	
	return false;	
}
			call_data('<?php echo $_REQUEST['device']; ?>','<?php echo $_REQUEST['from_date']; ?>','<?php echo $_REQUEST['to_date']; ?>');
		});
		window.addEventListener("resize", function() {
			if(plot2!=null)
				plot2.replot();
		}, false);
		</script>
	
	<div data-role="content">
		<div id="distance_graph"></div>
		
		<input data-theme="e" value="Back" type="button" onclick='window.location="distance_graph.php"' />
	</div>	
	<?php } else { ?>
<center><h3 style='padding:0px;margin:0px'><?php echo $lang['Distance Graph']; ?></h3></center>
<div data-role="content" style='padding:0px;margin:0px'>
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' onsubmit ='validation()' method='get'>
				<table style='width:100%;'>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['From Date']; ?>:</td>
  	                	<td><input type="text" name="from_date" id="datepick"   /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['To Date']; ?>:</td>
  	                	<td><input type="text" name="to_date" id="datepick1"   /></td>
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
						<td colspan='2'><input data-theme="e" value="View" type="submit" ></td>
					</tr>
					<tr>
						<td colspan='2'><input data-theme="e" value="Back" type="button" onclick='window.location="reports.php"' /></td>
					</tr>
				</table>
			</form>
        </div>
	</div>
</div>

<?php }  include("footer.php"); ?>