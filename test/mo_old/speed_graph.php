<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<style type="text/css">

.overHidden{
	overflow : hidden !important !important;
}

.jqplot-highlighter-tooltip{
	background: none repeat scroll 0 0 #BBBBBB;
    border-radius: 5px 5px 5px 5px;
    color: black;
    font-weight: bold;
    opacity: 0.7;
    padding: 5px;
    position: absolute;
}
</style>
	
	
	
	<script type="text/javascript">
		$(document).live( "pagecreate", function() {
				var picker = $( "input[type='text']", this );
				$("#datepick").mobipick();
				$("#datepick").bind( "change", function() {
					var date = $( this ).val();
					var dateObject = $( this ).mobipick( "option", "date" );
				});	
			$(function(){
			$('#test_start').scroller({
			preset: 'time',
			theme: 'android',
			display: 'modal',
			mode: 'clickpick'
			}); 
		});   
		$(function(){
			$('#test_stop').scroller({
			preset: 'time',
			theme: 'android',
			display: 'modal',
			mode: 'clickpick'
		});    
	});
		});
</script>
	<center><h3 style='margin:0px;padding:0px'><?php echo $lang['Speed Graph']; ?></h3></center>
	<?php if(isset($_REQUEST['date']) && isset($_REQUEST['test_start']) && isset($_REQUEST['test_stop']) && isset($_REQUEST['device'])){ ?>
		<script>
		
		function call_data (device,date,test_start,test_stop,distance)
		{
			$.post("php/loadData.php", { device: device, date: date, test_start: test_start, test_stop: test_stop,distance:distance},
				 function(response) {	
					  $('#speed_chart')	.html('');
					  var dataLines = [];  // initialize the array of lines.
					  var labels = [];  // initialize array of line labels.
					  var current, i;  // some variables we'll need.
				
					if(response.XAxis.length > 0){
					  dataLines.push([]);  // add an empty line.
					  labels.push(response.Name);
					  
					  for (i=0; i<response.XAxis.length; i++) {
						  dataLines[0].push([response.XAxis[i], response.y_axis[i]]);
					  }
				
					  var options = {
						  series: [{ label: labels[0] }, { label: labels[1]}],           
						  axesDefaults: {				  
							  pad: 1,
							  tickOptions: {
							  enableFontSupport: true,
							  fontSize: '9pt',
							  angle: -30
							  }
						  },
						  seriesDefaults: { showMarker:true , trendline: { show: false }, markerOptions: { size:12 }, lineWidth: 2 },
						  axes: {
							  yaxis: { min:0,max:120, 
									//label: 'Speed',
									labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
									tickOptions:{ angle: -30}, 
							  xaxis: {
								label: 'Speed V/s. Time',
								numberTicks:8,	
								tickRenderer: $.jqplot.CanvasAxisTickRenderer,
								tickOptions:{formatString:'%I:%M %p', angle: -30}, 
								renderer: $.jqplot.DateAxisRenderer }
						  },
						   cursor:{
							show : true,
							},
					  highlighter: {
								show : true,
								tooltipLocation:'n',
								sizeAdjust: 5,
								formatString:'<table class="jqplot-highlighter"> \
							<tr><td align="left">Date:</td><td align="left"> %s</td></tr> \
							<tr><td align="left">Speed:</td><td align="left"> %s </td></tr>\
						</table>'
						  }
					  };
					  plot1 = $.jqplot('speed_chart', dataLines, options);
					  }else{
						plot1=null;
						$('#speed_chart').html('<center>No Data Found</center>');
					  }
				 }, 'json');
				
				return false;
			}
		$(document).ready(function(){
			call_data('<?php echo $_REQUEST['device']; ?>','<?php echo $_REQUEST['date']; ?>','<?php echo $_REQUEST['test_start']; ?>','<?php echo $_REQUEST['test_stop']; ?>','10 minits');
			
		});
		window.addEventListener("resize", function() {
		if(plot1!=null)
				plot1.replot();
		}, false);
		</script>
	<div data-role="content" style='margin-top:0px;padding-top:0px'>
		<div data-role="fieldcontain">
            </div>
		<div id="speed_chart" ></div>
	</div>	
	<?php } else { ?>
<div data-role="content">
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
				<table style='width:100%;'>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['Date']; ?>:</td>
  	                	<td><input type="text" name="date" id="datepick"   /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['Start Time']; ?>:</td>
  	                	<td> <input type="text" name="test_start" id="test_start" /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'><?php echo $lang['End Time']; ?>:</td>
  	                	<td> <input type="text" name="test_stop" id="test_stop" /></td>
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
						<td colspan='2'><input data-theme="e" value="Search" type="submit" id='search_btn'></td>
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
	
	
