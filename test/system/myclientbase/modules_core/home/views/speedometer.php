<script>
loadSpeedoMeter();
$(document).ready(function () {
	
	$('#speedo_meter<?php echo time(); ?>').speedometer();
	
	get_speed<?php echo time(); ?>();
});
	function get_speed<?php echo time(); ?>(){
		$.post(
			"<?php echo base_url(); ?>index.php/home/get_speed/id/<?php echo $id; ?>",
			function(data){
					$('#speedo_meter<?php echo time(); ?>').speedometer({ percentage: data || 0 });
			}
		);
	}
</script>
<style>
.speedo_meter_div{
	left : 110px !important;
}
</style>
<div id="speedo_meter<?php echo time(); ?>" class="speedo_meter_div"></div>
				