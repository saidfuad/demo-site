<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<style type="text/css">
/*
.ui-widget .ui-widget{
	overflow : auto !important;
}
*/
.jqplot-yaxis-label{
	left : -25px !important;
}
.jqplot-title{
	top : -9px !important;
}
.jqplot-table-legend{
	position : relative !important;
	margin-left : 692px;
}
.overHidden{
	overflow : hidden !important !important;
}
#ui_tpicker_hour_label_speedgraph_stime,#ui_tpicker_hour_label_speedgraph_etime
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_speedgraph_stime,#ui_tpicker_minute_label_speedgraph_etime
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
</style>
<link rel="stylesheet" type="text/css" href="css/mobipick.css" />
	<script type="text/javascript" src="js/xdate.js"></script>
	<script type="text/javascript" src="js/xdate.i18n.js"></script>
	<script type="text/javascript" src="js/mobipick.js"></script>
	<link href="css/mobiscroll-2.0.3.custom.min.css" rel="stylesheet" type="text/css" />
	<script src="js/mobiscroll-2.0.3.custom.min.js" type="text/javascript"></script>
	
	
	<script type="text/javascript" src="js/jqplot/jquery.jqplot_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.logAxisRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.canvasTextRenderer.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.barRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.categoryAxisRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.dateAxisRenderer_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.pointLabels_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.cursor_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.highlighter_min.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.canvasAxisLabelRenderer.js"></script>
	<script type="text/javascript" src="js/jqplot/jqplot.canvasAxisTickRenderer.js"></script>
	
	
	
	
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
	<center><h3>Speed Graph</h3></center>
	<?php if(isset($_REQUEST['date']) && isset($_REQUEST['test_start']) && isset($_REQUEST['test_stop']) && isset($_REQUEST['device'])){ ?>
	
		<script>
		$(document).ready(function(){
		var sg_date='<?php echo $_REQUEST['date']; ?>';		
		var sg_stime='<?php echo $_REQUEST['test_start']; ?>';		
		var sg_etime='<?php echo $_REQUEST['test_stop']; ?>';		
		var sg_device='<?php echo $_REQUEST['device']; ?>';		
		var sg_avg='<?php echo $_REQUEST['average_select']; ?>';	
		if(sg_avg == "All")
		{
				sg_avg=0;
		}	

		/*var sg_date = $('#datepick').val();
		var sg_stime = $('#test_start').val();
		var sg_etime = $('#test_stop').val();
		var sg_device = $('#device').val();*/
	if(sg_date != undefined && sg_stime != undefined && sg_etime != undefined && sg_device != undefined && sg_avg != undefined){
	$.post("php/loadData.php", { device: sg_device, date: sg_date, test_start: sg_stime, test_stop: sg_etime,time:sg_avg},
	 function(response) {	
		  $('#speed_chart')	.html('');
		  var dataLines = [];  // initialize the array of lines.
		  var labels = [];  // initialize array of line labels.
		  var current, i;  // some variables we'll need.
	
		if(response.XAxis.length > 0){
		  dataLines.push([]);  // add an empty line.
		  labels.push(response.Name);
		  
		  for (i=0; i<response.XAxis.length; i++) {
			  dataLines[0].push([response.XAxis[i], response.Speed[i]]);
		  }
	
		  var options = {
			  legend: { show: true },
			  title: 'Speed Vs Time',
			  series: [{ label: labels[0] }, { label: labels[1]}],           
			  axesDefaults: {				  
			  	  pad: 1.2,
				  tickOptions: {
				  enableFontSupport: true,
				  fontSize: '9pt',
				  angle: -30
       		 	  }
			  },
			  seriesDefaults: { showMarker:true , trendline: { show: false }, lineWidth: 2 },
			  axes: {
				  yaxis: { min:0, max: 100, 
						label: 'Speed',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
						tickOptions:{ angle: -30}, 
				  xaxis: {
				  	label: 'Time',
					//tickInterval:'30 minutes', 
					numberTicks:5,
				  	tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions:{formatString:'%I:%M %p', angle: -30}, 
					renderer: $.jqplot.DateAxisRenderer }
			  },
			  cursor:{
			  		show : true,
					zoom:true,
					tooltipOffset: 10,
					tooltipLocation: 'n'
			  },
			  highlighter: {
					sizeAdjust: 6
			  }
		  };
		  plot1 = $.jqplot('speed_chart', dataLines, options);
		  }else{
			plot1=null;
			$('#speed_chart').html('<center>No Data Found</center>');
		  }
	 }, 'json');
	
	return false;
}else{
	return false;
}	
});
		</script>
	
	<div data-role="content">
		<div id="speed_chart" style="margin-top:20px; margin-left:20px;"></div>
		<input data-theme="e" value="Back" type="button" onclick='window.location="speed_graph.php"' />
	</div>	
	<?php } else { ?>
<div data-role="content">
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
				<table style='width:100%;'>
					<tr  align='center'>
						<td  align='right'>Date:</td>
  	                	<td><input type="text" name="date" id="datepick"   /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'>Start Time:</td>
  	                	<td> <input type="text" name="test_start" id="test_start" /></td>
					</tr>
					<tr  align='center'>
						<td  align='right'>End Time:</td>
  	                	<td> <input type="text" name="test_stop" id="test_stop" /></td>
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
						<td align='right'>Distance :</td>
					<td>
						<select name='average_select' data-theme="b">
						<option value='60'>hourly</option>
						<option value='30'>half-hourly</option>
						<option value='10'>10 min.</option>
						<option value='5'>5 min.</option>
						<option value='All'>All</option>
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
	

	
