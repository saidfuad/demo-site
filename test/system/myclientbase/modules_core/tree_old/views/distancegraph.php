<script>
$(document).ready(function () {
	get_distance_graph<?php echo time(); ?>();
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
});

function get_distance_graph<?php echo time(); ?>(){
	$.post("<?php echo base_url(); ?>index.php/reports/distancegraph/loadData", { device: <?php echo $id; ?>, sdate: '<?php echo date('d.m.Y', strtotime('-6 days')); ?>', edate: '<?php echo date('d.m.Y'); ?>' },
	 function(data) {
		 $("#distance_graph<?php echo time(); ?>").html('');
		 
		if(data.distance.length > 0){
			ticks1 = data.dates;
			min_val = 0;
			//max_val = data.x_max;
			max_val = data.max_distance;
			
			plot1 = $.jqplot('distance_graph<?php echo time(); ?>', data.distance, {
				title : '<?php echo $this->lang->line("Distance Travelled Vs. Date"); ?>',
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: { show: true },
					rendererOptions:{
						barWidth: '20'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						label: 'Date',
						ticks: ticks1,
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						  fontSize: '10pt',
							angle: -30
						}
					},
					yaxis: {
						label: 'Distance Travelled',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						  fontSize: '10pt',
							angle: -30
						},
						min:min_val,
						max:max_val
					}
				}
			});
		}else{
			$('#distance_graph<?php echo time(); ?>').html('<center><?php echo $this->lang->line("No_Data_Found"); ?></center>');
		}
	 }, 'json');
	
	return false;	
}
</script>
<style type="text/css">
.ui-widget .ui-widget{
	overflow : auto !important;
}
.jqplot-yaxis-label{
	left : -20px !important;
}
.widgetcontent{
	overflow : auto !important;
}
.jqplot-title{
	top : -11px !important;
}
.jqplot-xaxis-label{
	top : 40px !important;
}
</style>
<div id="distance_graph<?php echo time(); ?>" style="margin-top:20px; margin-left:20px;"></div>
			