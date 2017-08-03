<?php $tme=time(); ?>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();
}); 
</script>
<h3 class="title_black"><?php echo $this->lang->line("Set Landmarks Radius"); ?></h3>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="error_frm_landmarks" class="error" style="display:none"></div>
<div class="content toggle">
<form id="frm_landmarks_radius" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submit_landmark_radius()">
	<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
	<table width="100%" align="center" class="formtable">
		<tbody>
			<tr>
				<td><label><?php echo $this->lang->line("Landmarks List"); ?></label><select name="landmark_ids[]" id="landmakr_frm_list" class="select ui-widget-content ui-corner-all" multiple="multiple" size="7">
				<?php echo $device_ids; ?></select></td>
			</tr>
			<tr>
				<td><label><span style="width:49%;display:inline-block"><?php echo $this->lang->line("Radius"); ?></span><?php echo $this->lang->line("Distance_Unit"); ?></label><br/><input type="text" name="radius" id="landmakr_frm_radius_list" class="text ui-widget-content ui-corner-all" value="<?php echo $radius; ?>" style="width:45%"/><select name="distance_unit" id="landmakr_frm_distance_unit_list" class="select ui-widget-content ui-corner-all" style="width:45%"><?php echo $distance_unit; ?></select>
				</td>
			</tr>
			<tr>
				<td align="center"><br/>
				<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
				&nbsp;&nbsp;
				<input type="button" id="btn_cancel" onclick="cancel_landmarks()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
			</tr>
		</tbody>
	</table>
	</form>
</div>
<div id="Assets_icon_dialog_land<?php echo $tme; ?>" class="assestimage_oad"></div>