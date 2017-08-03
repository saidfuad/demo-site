<script>
$(document).ready(function () {
	get_speed_graph<?php echo time(); ?>();
});
	//window[selected_assets_ids+"_t"] = window.setInterval('refreshDash<?php echo time(); ?>()',10000); 
	
	function refreshDash<?php echo time(); ?>(){
		
		get_speed_graph<?php echo time(); ?>();
	}	
	
function get_speed_graph<?php echo time(); ?>(){
	$('#speed_chart<?php echo time(); ?>').html('');	
	$.post("<?php echo base_url(); ?>index.php/reports/speedgraph/loadData", { device: <?php echo $id; ?>, date: '<?php echo date('d.m.Y'); ?>', stime: '<?php echo date('H:i', strtotime('-3 hour')); ?>', etime: '<?php echo date('H:i'); ?>' },
	 function(response) {	
		  $('#speed_chart<?php echo time(); ?>')	.html('');
		  var dataLines<?php echo time(); ?> = [];  // initialize the array of lines.
		  var labels<?php echo time(); ?> = [];  // initialize array of line labels.
		  var current, i;  // some variables we'll need.
	
		if(response.Speed.length > 0){
		  dataLines<?php echo time(); ?>.push([]);  // add an empty line.
		  labels<?php echo time(); ?>.push(response.Name);
		/*  for (i=0; i<response.Speed.length; i++) {
			  dataLines<?php echo time(); ?>[0].push([response.XAxis[i], response.Speed[i]]);
		  }**/
	
		  var options = {
			  legend: { show: false },
			  title: '<?php echo $this->lang->line("Speed Vs Time"); ?>',
			  //series: [{ label: labels<?php echo time(); ?>[0] }, { label: labels<?php echo time(); ?>[1]}],           
			  axesDefaults: {				  
			  	  pad: 1.2,
				  tickOptions: {
				  enableFontSupport: true,
				  fontSize: '9pt'               
				  }
			  },
			  seriesDefaults: { showMarker:true , trendline: { show: false }, lineWidth: 2 },
			  axes: {
				  yaxis: {min:0, max: 100, label: 'Speed', labelRenderer: $.jqplot.CanvasAxisLabelRenderer, angle: 250 },
				  xaxis: {
				  	label: 'Time[30 min intervals]', 
					tickInterval:'30 minutes', 
				  	tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions:{formatString:'%I:%M %p', angle: -30}, 
					renderer: $.jqplot.DateAxisRenderer}
			  },
			  cursor:{
			  		show : true,
					zoom:true,
					tooltipOffset: 10,
					tooltipLocation: 'n'
			  },
			  highlighter: {
			        show : true,
					sizeAdjust: 6,
					tooltipLocation:'n',
				formatString:'<table class="jqplot-highlighter"><tr><td align="left"><?php echo "<span>"."Double Click To Reset Map"."</span>";?></td></tr></table>'
			  }


		  };	
		  var plot1 = $.jqplot('speed_chart<?php echo time(); ?>', response.Speed, options);
		  }else{
			$('#speed_chart<?php echo time(); ?>').html('<center>No Data Found</center>');
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
<div id="speed_chart<?php echo time(); ?>" style="margin-top:20px; margin-left:20px;"></div>
			