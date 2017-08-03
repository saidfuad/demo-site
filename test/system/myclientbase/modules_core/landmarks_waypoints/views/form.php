<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
?>
<script type="text/javascript">
loadDropdown();
</script>
		<?php if($this->form_model->waypoint_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create_landmarks_waypoints"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update_landmarks_waypointss"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div id="err_duplicate_waypt" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_landmarks_waypoints" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormlandmarks_waypoints('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Waypoint Name"); ?> *</label></td>
						<td width="50%"><input type="text" name="waypoint_name" id="waypoint_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->waypoint_name; ?>" /></td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Landarmk1"); ?> *</label></td>
						<td width="50%"><select name="landmark1" id="landmark1" class="text ui-widget-content ui-corner-all">
							<?php echo $this->form_model->landmark1; ?>
						</select></td>						
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Landarmk2"); ?> *</label></td>
						<td width="50%"><select name="landmark2" id="landmark2" class="text ui-widget-content ui-corner-all">
						<?php echo $this->form_model->landmark2; ?>
						</select></td>						
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_landmarks_waypoints()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			</form>
		</div>
<script type="text/javascript"> 
$(document).ready(function() {
	$("#loading_top").css('display','none');
	jQuery("input:button, input:submit, input:reset").button();
	$("#landmark1").msDropDown();
	$("#landmark2").msDropDown();
		$(document).keypress(function(e){
		if (e.keyCode == 27) { 
			if($("#landmarks_waypoints_form_div").css("display")!="none" && $("#landmarks_waypoints_form_div").css("display") != undefined)
				landmarks_waypoints_conf_dialog_usr_abcd.dialog("open");
		}
	});
});

</script>