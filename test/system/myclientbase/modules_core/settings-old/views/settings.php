<?php $this->load->view('settings/header'); ?>
<?php $this->load->view('settings/sidebar', array('side_block'=>array('settings/sidebar', 'settings/sidebar'),'hide_quicklinks'=>TRUE)); 
?>
<?php

?>
<script type="text/javascript" charset="utf-8">

$(document).ready(function () {				
	$("#dialog").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false
	});
				
});

</script>
<div id="dialog" style="display:none">
</div>
<div class="ui-layout-center" id="tabs" style="overflow: hidden;">
	<ul>
		<li><a href="#tabs-1"><?php echo $this->lang->line("Default"); ?></a></li>
		
		<div class="addons">
    		<img id="imgmaxmin" src="<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png" style="cursor:pointer;" alt="max" title="Maximize" onclick="maximize(this)" />
		</div>
	
	</ul>
	<div id="tabs-1">
		
	</div>
	
</div>
<div id="info"></div>
<?php $this->load->view('dashboard/footer'); ?>
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