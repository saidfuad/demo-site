<script type="text/javascript">
loadJQPLOT();
loadMultiSelectDropDown();
</script>
<?php
	 $date_format = $this->session->userdata('date_format'); 
	 $time_format = $this->session->userdata('time_format');  
	 $time_format=str_replace (":s", "" ,$time_format);
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
	 $js_time_format=str_replace (":ss", "" ,$js_time_format);
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm=",ampm:true";
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
table.jqplot-table-legend{
	margin:0 0 0 0; 
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");	
});

function searchspeedgraph(){
	
	var sg_date = $('#speedgraph_date').val();
	var sg_stime = $('#speedgraph_stime').val();
	var sg_etime = $('#speedgraph_etime').val();
	//var sg_device = $('#speedgraph_device').val();
	

	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-speedgraph_device-i"+i).is(':checked')){
			dev+=$("#ddcl-speedgraph_device-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	$("#divAjaxIndex").dialog("open");
	$.post("<?php echo base_url(); ?>index.php/reports/speedgraph/loadData", { device: dev, date: sg_date, stime: sg_stime, etime: sg_etime },
	 function(response) {	
		  $('#speed_chart')	.html('');
		 
		  var dataLines = [];  // initialize the array of lines.
		  //var labels = [];  // initialize array of line labels.
		  var current, i;  // some variables we'll need.
		
		if(response.Speed.length > 0){
			var grid = {
				gridLineWidth: 1.5,
				gridLineColor: 'rgb(235,235,235)',
				drawGridlines: true
			};

		  var options = {
			   legend:{
				   show:true,
				   labels: response.Devices,
				   location: 'ne',
				   placement : "outsideGrid",
				   marginTop:0,
				   marginLeft:0,
				   marginRight:0,
				   marginBottom:0,
				   rendererOptions: {
					numberRows: 1,
					numberColumns: 3,
					seriesToggle: false,
					disableIEFading: true
					}
				},
				//series:[{label:"Max Speed"}],
			  title: '<?php echo $this->lang->line("Speed Vs Time"); ?>',
			 axesDefaults: {			
				  tickOptions: {
					  fontSize: '10pt',
					  angle: -30
       		 	  }
			  },
			  axes: {
				  yaxis: { min:0, max: 140, 
						label: 'Speed',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
						tickOptions:{ angle: -30}, 
				  xaxis: {
				  	label: 'Time',
					//tickInterval:'30 minutes', 
					numberTicks:15,
				  	tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions:{formatString:'%I:%M %p', angle: -30},
					//ticks:response.XAxis,
					ticks: response.XAxis,
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
						yvalues: 3,
						tooltipLocation:'n',
						formatString:'<table class="jqplot-highlighter"><tr><td align="left"><?php echo $this->lang->line("Time"); ?>:</td><td align="left"> &nbsp; %s</td></tr><tr><td align="left"><?php echo $this->lang->line("Speed"); ?>:</td><td align="left"> &nbsp; %s</td></tr><tr><td align="left"><?php echo $this->lang->line("Vehicle"); ?>:</td><td align="left"> &nbsp; %s</td></tr></table>'
				  }				 
				};
			  plot1 = $.jqplot('speed_chart', response.Speed, options);
			  
			//  $("table.jqplot-table-legend").css('margin-right','-25px');
		  }else{
			plot1=null;
			$('#speed_chart').html('<center><?php echo $this->lang->line("No_Data_Found"); ?></center>');
		  }
		  $("#divAjaxIndex").dialog("close");
		  $("#loading_top").css("display","none");
	 }, 'json');
	
	return false;	
}
/*
$("#exportto_excel").click(function(){
			var newCanvas = document.createElement("canvas");
			var obj=$('.jqplot-event-canvas');
			var size = findPlotSize(obj);
			
			newCanvas.width = size.width;
			newCanvas.height = size.height;
			
			// check for plot error
			var baseOffset = obj.offset();
		if (obj.find("canvas.jqplot-base-canvas").length) {
        baseOffset = obj.find("canvas.jqplot-base-canvas").offset();
        baseOffset.left -= parseInt(obj.css('margin-left').replace('px', ''));
		}
		
		 obj.find('canvas').each(function () {
        var offset = $(this).offset();
        newCanvas.getContext("2d").drawImage(this,
            offset.left - baseOffset.left,
            offset.top - baseOffset.top
			);
		});
		  return newCanvas.toDataURL("image/png");
});
	function findPlotSize(obj) {
    var width = obj.width();
    var height = obj.height();
    var legend = obj.find('.jqplot-table-legend');
    if (legend.position()) {
        height = legend.position().top + legend.height();
    }
    obj.find('*').each(function() {
        var offset = $(this).offset();
        tempWidth = offset.left + $(this).width()
        tempHeight = $(this).height()
        if(tempWidth > width) {width = tempWidth;}
        if(tempHeight > height) {height = tempHeight;}
    });
    return {width: width, height: height};
}
*/
$(document).ready(function() {	
	jQuery("input:button, input:submit, input:reset").button();	
	$('#speedgraph_stime').timepicker({
		timeFormat: <?php echo "'".$js_time_format."'".$ampm; ?>,showSecond: false
	});	
	$('#speedgraph_etime').timepicker({
		timeFormat: <?php echo "'".$js_time_format."'".$ampm; ?>,showSecond: false
	});
	$("#speedgraph_date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#speedgraph_stime").timepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#speedgraph_etime").timepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#speedgraph_date").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));

	$("#speedgraph_device").html(assets_combo_opt);
	$("#speedgraph_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b><?php echo $this->lang->line("all_assets"); ?></b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-speedgraph_device").css('vertical-align','middle');
	$("#ddcl-speedgraph_device-ddw").css('overflow-x','hidden');
	$("#ddcl-speedgraph_device-ddw").css('overflow-y','auto');
	$("#ddcl-speedgraph_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});

</script>
<div style="height:100px;">
  <form onsubmit="return searchspeedgraph()">
    <table width="100%" class="formtable" style="margin-bottom: 5px;">
      <tr>
        <td align='center'><?php echo $this->lang->line("date"); ?> </td>
		<td align='center'><?php echo $this->lang->line("Start_Time"); ?> </td>
		<td align='center'><?php echo $this->lang->line("End_Time"); ?> </td>
		<td align='center'><?php echo $this->lang->line("Assets"); ?> </td>
		<td align='center'>&nbsp;</td>
	</tr>
	<tr>
	<td align='center'><input type="text" name="sg_date" id="speedgraph_date" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date($date_format); ?>" readonly="readonly"/></td>
        <td align='center'><input type="text" name="sg_stime" id="speedgraph_stime" class="timepicker text ui-widget-content ui-corner-all" style="width:80px" readonly="readonly" value="<?php echo date($time_format); ?>"/></td>
        <td align='center'><input type="text" name="sg_etime" id="speedgraph_etime" class="timepicker text ui-widget-content ui-corner-all" value="<?php echo date($time_format); ?>" style="width:80px" readonly="readonly"/></td>
   		<td align='center'>
		
          <select style="width:120px;" name="sg_device" id="speedgraph_device" class="select ui-widget-content ui-corner-all" multiple='multiple'></select>
          </select></td>
        <td align='center'><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	  </tr>
    </table>
  </form>
</div>
<div id="speed_chart" style="margin-top:20px; margin-left:20px;"></div>
<div id="chart1" style="margin-top:20px; margin-left:20px;"></div>

<?php // this.renderer.setBarWidth.call(this); ?>
<script type="text/javascript">
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>