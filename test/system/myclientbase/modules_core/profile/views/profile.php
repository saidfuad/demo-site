<?php $data=$result;?>
<style>
.formtable input.text{
padding:0.4em;
margin-top:
}

#account_settings_tbl td,#pesonal_settings_tbl td {
	vertical-align: middle;
	padding:0px 4px 9px 4px;
	width:50%;
}
#profile_left_panel tr td a{
	display:block;
	text-decoration:none;
	line-height:22px;
	padding:5px;
}
</style>
<script>
jQuery().ready(function (){
cancelloading();
});
	function form_submit(val)
	{	
		if(val=="account_settings_tbl")
		{
			var time=0;
			if($("#max_stop_time_hour").val()!=0 && $("#max_stop_time_minute").val()!=0)
			{
				time=Number($("#max_stop_time_hour").val()*60)+Number($("#max_stop_time_minute").val());
			}
			else if($("#max_stop_time_hour").val()==0)
			{
				time=Number($("#max_stop_time_minute").val());
			}
			else
			{
				time=Number($("#max_stop_time_hour").val())*60;
			}
			$("#max_stop_time").val(time);
			// alert time for box open
			var alert_time=0;
			if($("#alert_box_open_time_hour").val()!=0 && $("#alert_box_open_time_minute").val()!=0)
			{
				alert_time=Number($("#alert_box_open_time_hour").val()*60)+Number($("#alert_box_open_time_minute").val());
			}
			else if($("#alert_box_open_time_hour").val()==0)
			{
				alert_time=Number($("#alert_box_open_time_minute").val());
			}
			else
			{
				alert_time=Number($("#alert_box_open_time_hour").val())*60;
			}
			$("#alert_box_open_time").val(alert_time);
		}
		jQuery().ready(function (){
			$.post("<?php echo base_url(); ?>index.php/profile/form/form_name/"+val, $("#form_profile").serialize(),
			function(data) {
				if($.trim(data)!=""){
					$('#profile_form_div').html(data);
				}else{
					$("#profile_dialog").html("<?php echo $this->lang->line("Record Updated Successfully"); ?>  <br/> <?php echo $this->lang->line("To Apply Changes"); ?> <a href='<?php echo site_url('sessions/logout'); ?>'><b><?php echo $this->lang->line("To Apply Changes"); ?><?php echo $this->lang->line("Click here"); ?></b></a><br/>(<?php echo $this->lang->line("By Clicking"); ?>'<?php echo $this->lang->line("To Apply Changes"); ?>
<?php echo $this->lang->line("Click here"); ?>', <?php echo $this->lang->line("you will be logout from this site"); ?>)");
					$("#profile_dialog").dialog('open');
				}
			});
		});
	}
</script>
<div id='profile_form_div'>
	<?php $this->load->view('form',$data); ?>
</div>
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

	