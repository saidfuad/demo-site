<script type="text/javascript">
loadJQPLOT();
</script>
<?php
$date_format = $this->session->userdata('date_format');  
$time_format = $this->session->userdata('time_format');  
$js_date_format = $this->session->userdata('js_date_format');  
$js_time_format = $this->session->userdata('js_time_format');  
?>
<style type="text/css">
.jqplot-title{
	top : -9px !important;
}
.jqplot-yaxis-label{
	left : -17px !important;
}
table.jqplot-table-legend{
	margin:0px;
}
.jqplot-point-label{
	font-size:12px;
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
  -ms-transform: rotate(-90deg);
  -o-transform: rotate(-90deg);
  transform: rotate(-90deg);

  /* also accepts left, right, top, bottom coordinates; not required, but a good idea for styling */
  -webkit-transform-origin: 50% 50%;
  -moz-transform-origin: 50% 50%;
  -ms-transform-origin: 50% 50%;
  -o-transform-origin: 50% 50%;
  transform-origin: 50% 50%;

  /* Should be unset in IE9+ I think. */
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
function searchdistancegraph(){
	
	var from_date = $('#distance_report_from_date').val();
	var to_date = $('#distance_report_to_date').val();
	var group = $('#group_distance_graph').val();
	/*var start   = $('#distance_report_from_date').datepicker('getDate');
    var end = $('#distance_report_to_date').datepicker('getDate');
	var days   = (end - start)/1000/60/60/24;
	*/
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-distance_report_device-i"+i).is(':checked')){
			dev+=$("#ddcl-distance_report_device-i"+i).val()+",";
		}
	}
	/*var sg_device = $('#distance_report_device').val();
	if(days >= 15) {
		$("#alert_dialog").html('<?php echo $this->lang->line("Date Difference Must be Smaller Than 15 Days"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}*/
	if(dev == "") {
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	 $("#divAjaxIndex").dialog("close");
	$.post("<?php echo base_url(); ?>index.php/reports/distancegraph/loadData", { group:group, sdate: from_date, edate: to_date, device:dev },
	 function(data) {
		 	$("#distance_graph").html('');	 
		 
			// line = [['Cup Holder Pinion Bob', 7], ['Generic Fog Lamp', 9], ['HDTV Receiver', 15], ['8 Track Control Module', 12], [' Sludge Pump Fourier Modulator', 3], ['Transcender/Spice Rack', 6], ['Hair Spray Danger Indicator', 18]];
		
		//alert(data.distance[0][data.assets[0]].length);
		
		
		if(data.distance.length > 0){
			ticks1 = data.dates;
			min_val = 0;
			max_val = data.max_distance;
			
			/*plot2 = $.jqplot('distance_graph', data.distance, {
				title : '<?php echo $this->lang->line("Distance Travelled Vs. Date"); ?>',
				 legend:{
				   show:true,
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
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: { show: true },
					rendererOptions:{
						barWidth: '15',
						barPadding: 9
						
					}
					//shadowAngle : 45
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						label: 'Date',
						numberTicks:15,
						ticks: ticks1,
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
							angle: -30
						}
					},
					yaxis: {
						label: 'Distance Travelled',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						  labelPosition:'middle'
						},
						min:min_val,
						max:max_val						
					}
				},
					cursor:{
			  		show : false,
					}
			});*/
				// Can specify a custom tick Array.
				// Ticks should match up one for each y value (category) in the series.
				//var ticks = ['May', 'June', 'July', 'August'];
				var ticks = data.dates;
				plot2 = $.jqplot('distance_graph', data.distance, {
				title : '<?php echo $this->lang->line("Distance Travelled Vs. Date"); ?>',
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: {
						show: true
						<?php if($this->session->userdata('user_id') == 655){?>
						,escapeHTML:false,
						labels:data.label
						<?php } ?>
					  },
				},
				legend: {
					labels: data.device,
					placement:'outsideGrid',
					rendererOptions: {
						disableIEFading: false,
						numberColumns: 1,
					},
					show: true,
					
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						label: 'Date',
						numberTicks:15,
						ticks: ticks,
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
							angle: -30
						}
					},
					yaxis: {
						label: 'Distance Travelled(KM)',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						  labelPosition:'middle'
						},
						min:min_val,
						max:max_val
					}
				}
			});
				 /*
				var plot2 = $.jqplot('distance_graph', data.distance, {
					// The "seriesDefaults" option is an options object that will
					// be applied to all series in the chart.
					seriesDefaults:{
						renderer:$.jqplot.BarRenderer,
						pointLabels: { show: true,hideZeros:true, formatString: '%d' },
					},
					 /*seriesDefaults:{
						renderer:$.jqplot.BarRenderer,
						shadowAngle: 135,
						rendererOptions: {
							barDirection: 'vertical',
							barWidth:15,
							barMargin: 25
							}
						},*-/
					// Custom labels for the series are specified with the "label"
					// option on the series option.  Here a series option object
					// is specified for each series.
				
					// Show the legend and put it outside the grid, but inside the
					// plot container, shrinking the grid to accomodate the legend.
					// A value of "outside" would not shrink the grid and allow
					// the legend to overflow the container.
					legend: {
						show: true,
						placement: 'outsideGrid'
					},
					axes: {
						// Use a category axis on the x axis and use our custom ticks.
						xaxis: {
							renderer: $.jqplot.CategoryAxisRenderer,
							label: 'Date',
							numberTicks:15,
							ticks: ticks,
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							tickRenderer: $.jqplot.CanvasAxisTickRenderer,
							tickOptions: {
								angle: -30
							}
						},
						// Pad the y axis just a little so bars can get close to, but
						// not touch, the grid boundaries.  1.2 is the default padding.
						yaxis: {
							label: 'Distance Travelled',
							pad: 1.05,
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							tickRenderer: $.jqplot.CanvasAxisTickRenderer,
							tickOptions	: {
							  labelPosition:'middle'
							},
							min:min_val,
							max:max_val		
						},
						cursor:{
						show : false,
						}
					}
				});*-/
				var plot2 = $.jqplot('distance_graph', data.distance, {
				// The "seriesDefaults" option is an options object that will
				// be applied to all series in the chart.
				seriesDefaults:{
						renderer:$.jqplot.BarRenderer,
						pointLabels: { show: true,hideZeros:true, fillToZero: false },
					},
				// Custom labels for the series are specified with the "label"
				// option on the series option.  Here a series option object
				// is specified for each series.
			   
				// Show the legend and put it outside the grid, but inside the
				// plot container, shrinking the grid to accomodate the legend.
				// A value of "outside" would not shrink the grid and allow
				// the legend to overflow the container.
				legend: {
					labels: data.device,
					placement:'outsideGrid',
					rendererOptions: {
						disableIEFading: false,
						numberColumns: 1,
					},
					show: true,
					
				},
				axes: {
					// Use a category axis on the x axis and use our custom ticks.
					xaxis: {
							renderer: $.jqplot.CategoryAxisRenderer,
							label: 'Date',
							numberTicks:15,
							ticks: ticks,
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							tickRenderer: $.jqplot.CanvasAxisTickRenderer,
							tickOptions: {
								angle: -30
							}
						},
					// Pad the y axis just a little so bars can get close to, but
					// not touch, the grid boundaries.  1.2 is the default padding.
					yaxis: {
							label: 'Distance Travelled',
							pad: 1.05,
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							tickRenderer: $.jqplot.CanvasAxisTickRenderer,
							tickOptions	: {
							  labelPosition:'middle'
							},
							min:min_val,
							max:max_val		
						},
				}
			});
			//plot2.legend.labels = data.device;
			plot2.replot( { resetAxes: false } );*/
		}else{
			$('#distance_graph').html('<center><?php echo $this->lang->line("No_Data_Found"); ?></center>');
		}
		 $("#divAjaxIndex").dialog("close");
		
		$("#loading_top").css("display","none");
	 }, 'json');
	
	return false;	
}

$(document).ready(function() {
	jQuery("#distance_report_from_date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#distance_report_to_date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();	
	$("#distance_report_from_date").datepicker('setDate', new Date());
	$("#distance_report_to_date").datepicker('setDate', new Date());
	$("#distance_report_from_date").val('<?php echo date($date_format);?>');
	$("#distance_report_to_date").val('<?php echo date($date_format);?>');
	$("#loading_top").css("display","none");
	$("#distance_report_device").html(assets_combo_opt_report);
	$("#distance_report_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-distance_report_device").css('vertical-align','middle');
	$("#ddcl-distance_report_device-ddw").css('overflow-x','hidden');
	$("#ddcl-distance_report_device-ddw").css('overflow-y','auto');
	$("#ddcl-distance_report_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});

</script>
<div style="height:100px;">
  <form onsubmit="return searchdistancegraph()">
    <table width="100%" class="formtable" style="margin-bottom:5px;">
      <tr>
        <td width="10%"><?php echo $this->lang->line("from_date"); ?> :
          <input type="text" name="from_date" id="distance_report_from_date" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
        <td width="10%"><?php echo $this->lang->line("to_date"); ?>:
          <input type="text" name="to_date" id="distance_report_to_date" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y');?>" readonly="readonly"/></td>
        <td width="14%">Group : <select onchange="filterAssetsCombo(this.value,'distance_report_device')" style="width:120px;" name="group" id="group_distance_graph" class="select ui-widget-content ui-corner-all" ><?php echo $group; ?></select></td>
		<td width="30%"><?php echo $this->lang->line("Assets"); ?> :
          <select style="width:120px;" name="sg_device" id="distance_report_device" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
        <td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
      </tr>
    </table>
  </form>
</div>
<div class="jqplot graph" id="distance_graph"></div>
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