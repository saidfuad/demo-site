<?php $tme=time(); ?>
<script type="text/javascript">
$(document).ready(function() {
	/*$("#btn_submit").click(function(){
		
		if(devices == null) {
			$("#alert_dialog").html('Please Select the Devices');
			$("#alert_dialog").dialog('open');
		}
		if(command == '') {
			$("#alert_dialog").html('Please Enter the Command Value');
			$("#alert_dialog").dialog('open');
		}
	});*/
	
	$("#landmakr_frm_device_class").change(function(){
		$("#loading_top").css("display","block");
		var val = $(this).val();
		if(val != '') {
			$.post("<?php echo base_url(); ?>index.php/device_settings/get_devices/class/"+val,function(result)
			{
				if(result!=""){
					$("#landmakr_frm_device_ids").html(result);
				}
			});
			$.post("<?php echo base_url(); ?>index.php/device_settings/get_commands/class/"+val,function(result)
			{
				if(result!=""){
					$('#cmd_1').html(" <option value=''><?php echo $this->lang->line("Please Select"); ?></option>");
					$("#cmd_1").html(result);
				}
			});
			$("#loading_top").css("display","none");
			
		} else {
			$("#cmd_1").html('<option value=""><?php echo $this->lang->line('Please Select'); ?></option>');
			$("#landmakr_frm_device_ids").html('');
		}
	});

	jQuery("input:button, input:submit, input:reset").button();
	$("#loading_top").css("display","none");
	
	$("#cmd_1").change(function(){

		var val = $(this).val();
		if(val != '') {
			$("#cmd_2").val(val);			
		} else {
			$("#cmd_2").val('');
		}
	});
});

function submitForm_dSettings()
{
	var cmd = $("#cmd_2").val();
	$("#command").val($("#cmd_2").val());
	var devices = $("#landmakr_frm_device_ids").val();
	var command = $("#cmd_2").val();
	
	if(devices == null) {
		$("#alert_dialog").html('<?php echo $this->lang->line('Please_Select_the_Devices'); ?>');
		$("#alert_dialog").dialog('open');
		return false;
	}
	
	if(command == null || command == '') {
		$("#alert_dialog").html('<?php echo $this->lang->line('Please Provide command to send to device'); ?>');
		$("#alert_dialog").dialog('open');
		return false;
	}
	
	$.post("<?php echo site_url($this->uri->uri_string()); ?>",$("#frm_dsettings").serialize(), 
			function(data){
				if($.trim(data)){
					if(data != '') {
						$("#alert_dialog").html(data);
						$("#alert_dialog").dialog('open');
					}
				}
				$("#loading_top").css("display","none");
			}
		);
	return false;
}
			
</script>
<h3 class="title_black"><?php echo $this->lang->line("device_settings"); ?></h3>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="error_frm" class="error" style="display:none"></div>
<form id="frm_dsettings" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitForm_dSettings()">
<table width="100%" align="center" class="formtable">
<tbody>
    <tr>
        <td width="15%"><label><?php echo $this->lang->line("Device Class"); ?> </label></td>
		<td width="70%"><select name="device_class" id="landmakr_frm_device_class" class="select ui-widget-content ui-corner-all" style="width:70%">
        <option value=''><?php echo $this->lang->line("Please Select"); ?></option><?php echo $this->form_model->device_class; ?></select></td>
    </tr>
    <tr>
        <td width="15%"><label><?php echo $this->lang->line("Assets_List"); ?> </label></td>
		<td width="70%"><select name="device_ids[]" id="landmakr_frm_device_ids" class="select ui-widget-content ui-corner-all" multiple="multiple" size="7" style="width:70%">
        <?php echo $this->form_model->device_ids; ?></select></td>
    </tr>
    <tr>
        <td width="15%"><label><?php echo$this->lang->line("Command Name"); /* $this->lang->line("command"); */ ?> </label></td>
		<td width="70%"><select id="cmd_1" class="select ui-widget-content ui-corner-all" style="width:70% !important">
		</select></td>
    </tr>
	<tr>
        <td width="15%"><label><?php echo$this->lang->line("Command Value"); /* $this->lang->line("command"); */ ?> </label></td>
		<td width="70%"><input type="text" id="cmd_2" class="text ui-widget-content ui-corner-all" value="" style="width:70%"/><input type="hidden" name="command" id="command" class="text ui-widget-content ui-corner-all" value=""/></td>
    </tr>
	<tr>
    <td align="center" colspan="2"><br/>
    <input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
    &nbsp;&nbsp;
    </td>
</tr>
</tbody>
</table>
</form>
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