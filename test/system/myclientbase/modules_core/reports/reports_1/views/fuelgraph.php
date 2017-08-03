<script type="text/javascript">
loadJQPLOT();
</script>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format'); 
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
?>
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
.overHidden{
	overflow : hidden !important !important;
}
#ui_tpicker_hour_label_fuelgraph_stime,#ui_tpicker_hour_label_fuelgraph_etime
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_fuelgraph_stime,#ui_tpicker_minute_label_fuelgraph_etime
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
.jqplot-highlighter-tooltip{
	   
	background: none repeat scroll 0 0 #BBBBBB;
    border-radius: 5px 5px 5px 5px;
    color: black;
    display: none;
    opacity: 0.7;
    padding: 5px;
    position: absolute;
	font-size:12px;
}
div.jqplot-table-legend-swatch{
	border-color:rgba(250, 0, 0,0.45) !important;
	background-color:rgba(250, 0, 0,0.45) !important;
}
.jqplot-table-legend{
	font-size:12px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");	
});

function searchfuelgraph(){
	
	var sg_date = $('#fuelgraph_date').val();
	var sg_stime = $('#fuelgraph_stime').val();
	var sg_etime = $('#fuelgraph_etime').val();
	var sg_device = $('#fuelgraph_device').val();
	if(sg_device == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	$("#divAjaxIndex").dialog("open");
	$.post("<?php echo base_url(); ?>index.php/reports/fuelgraph/loadData", { device: sg_device, date: sg_date, stime: sg_stime, etime: sg_etime },
	 function(response) {	
		  $('#fuel_chart').html('');
		  var dataLines = [];  // initialize the array of lines.
		  var labels = [];  // initialize array of line labels.
		  var current, i;  // some variables we'll need.
	
		if(response.XAxis.length > 0){
		  dataLines.push([]);  // add an empty line.
		  labels.push(response.Name);
		  for (i=0; i<response.XAxis.length; i++) {
			  dataLines[0].push([response.XAxis[i], response.Fuel[i]]);
		  }
			var grid = {
				gridLineWidth: 1.5,
				gridLineColor: 'rgb(235,235,235)',
				drawGridlines: true
			};
		  var options = {
			   legend:{
				   show:true
				},
				series:[{label:"Fuel 50 Ltr"}],
			  title: '<?php echo $this->lang->line("Fuel Vs Time"); ?>',
			 axesDefaults: {			
				  tickOptions: {
					  fontSize: '10pt',
					  angle: -30
       		 	  }
			  },
			  axes: {
				  yaxis: { min:0, max: 350, 
						label: '<?php echo $this->lang->line("Fuel"); ?>',
						tickInterval:50,
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
						tickOptions:{ angle: -30}, 
				  xaxis: {
				  	label: '<?php echo $this->lang->line("Time"); ?>',
					//tickInterval:'30 minutes', 
					numberTicks:15,
				  	tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions:{formatString:'%I:%M %p', angle: -30},
					renderer: $.jqplot.DateAxisRenderer }
			  },
			  cursor:{
			  		show : true,
					zoom:true,
					tooltipLocation: 'n'
			  },
			 highlighter: {
						show : true,
						sizeAdjust: 7.5,
						tooltipLocation:'n',
						formatString:'<table class="jqplot-highlighter"><tr><td align="left"><?php echo $this->lang->line("Time"); ?>:</td><td align="left">%s</td></tr><tr><td align="left"><?php echo $this->lang->line("Fuel"); ?>:</td><td align="left"> &nbsp; %s</td></tr></table>'
				  },
				  grid: grid,					   
				  canvasOverlay: {
                    show: true,
                    objects: [{
					 horizontalLine:
						{
							name: 'medium',
							y: response.fuelLimit,
							lineWidth: 1,
							color: 'rgba(250, 0, 0,0.45)',
							shadow: false
						}
					}]
					}
		  };
		  plot1 = $.jqplot('fuel_chart', dataLines, options);
		  }else{
			plot1=null;
			$('#fuel_chart').html('<center><?php echo $this->lang->line("No Data Found"); ?></center>');
		  }
		  $("#divAjaxIndex").dialog("close");
		  $("#loading_top").css("display","none");
	 }, 'json');
	
	return false;	
}
$(document).ready(function() {
	
	jQuery("input:button, input:submit, input:reset").button();	
	$('#fuelgraph_stime').datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$('#fuelgraph_etime').datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#fuelgraph_stime").datetimepicker('setDate', new Date());
	$("#fuelgraph_etime").datetimepicker('setDate', new Date());
	$("#fuelgraph_device").html("<?php echo $assets_fuel_opt; ?>");
});

</script>
<div style="height:100px;">
  <form onsubmit="return searchfuelgraph()">
    <table width="100%" class="formtable" style="margin-bottom: 5px;">
      <tr>
		<td align='center'><?php echo $this->lang->line("Start_Time"); ?> </td>
		<td align='center'><?php echo $this->lang->line("End_Time"); ?> </td>
		<td align='center'><?php echo $this->lang->line("Assets"); ?> </td>
		<td align='center'>&nbsp;</td>
	</tr>
	<tr>
        <td align='center'><input type="text" name="sg_stime" id="fuelgraph_stime" class="text ui-widget-content ui-corner-all" style="width:160px" readonly="readonly" value="<?php echo date($date_format." ".$time_format); ?>"/></td>
        <td align='center'><input type="text" name="sg_etime" id="fuelgraph_etime" class="text ui-widget-content ui-corner-all" value="<?php echo date($date_format." ".$time_format); ?>" style="width:160px" readonly="readonly"/></td>
   		<td align='center'><select style="width:120px;" name="sg_device" id="fuelgraph_device" class="select ui-widget-content ui-corner-all">
          </select></td>
        <td align='center'><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	  </tr>
    </table>
  </form>
</div>
<div id="fuel_chart" style="margin-top:20px; margin-left:20px;"></div>
<div id="chart1" style="margin-top:20px; margin-left:20px;"></div>

<?php // this.renderer.setBarWidth.call(this); ?>