<script>
$(document).ready(function () {
	get_area_in_out<?php echo time(); ?>();
	$("#loading_top").css("display","none");
});

function get_area_in_out<?php echo time(); ?>(){
	$.post("<?php echo base_url(); ?>index.php/home/get_area_in_out/", {device: <?php echo $id; ?>},
	 function(data) {
		$("#area_in_out_report<?php echo time(); ?>").html('');
		$("#area_in_out_report<?php echo time(); ?>").html(data);
	 });	
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
<div id="area_in_out_report<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			