<script>
$(document).ready(function () {
	get_distance_wise<?php echo time(); ?>();
	$("#loading_top").css("display","none");
});

function get_distance_wise<?php echo time(); ?>(){
	$.post("<?php echo base_url(); ?>index.php/home/get_distance_wise/", { device: <?php echo $id; ?>},
	 function(data) {
		$("#distance_wise<?php echo time(); ?>").html('');
		$("#distance_wise<?php echo time(); ?>").html(data);
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
<div id="distance_wise<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			