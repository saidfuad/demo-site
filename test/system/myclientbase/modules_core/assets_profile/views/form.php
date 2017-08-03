		<?php if($this->form_model->profile_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create Assets Profile"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Assets Profile"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		
		<div class="content toggle" align="center">
		<div id="chkDup" class="error" style="display:none"></div>
			<form id="frm_assets_profile" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormAssetsProfile('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="70%" align="center" class="formtable">
				<tbody>
				<tr>
          <td width="20%">
          <?php echo $this->lang->line("Profile Name"); ?>*</td>
          <td width="50%">
            <input type="text" name="profile_name" id="profile_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->profile_name; ?>"/></td>
        </tr>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Min Consecutive Speed"); ?>*</td>
          <td width="50%">
            <input type="text" name="min_consecutive_speed" id="min_consecutive_speed"  class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->min_consecutive_speed; ?>"/></td>
        </tr>
		<tr>
          <td width="20%">
         <?php echo $this->lang->line("Max Consecutive Speed"); ?>*</td>
          <td width="50%">
            <input type="text" name="max_consecutive_speed" id="max_consecutive_speed"  class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_consecutive_speed; ?>"/></td>
        </tr>
		<tr>
          <td width="20%">
         <?php echo $this->lang->line("Max Idle Time"); ?>*</td>
          <td width="50%">
            <input type="text" name="max_idle_time" id="max_idle_time" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_idle_time; ?>"/></td>
        </tr>
			<tr>
			<td width="20%" style="vertical-align:middle"><?php echo $this->lang->line("Assets"); ?> *</td><td><select multiple="multiple" size="10" name="device_ids[]" id="device_ids" class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->assets; ?></select>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_assets_profile()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			
			

			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
	$("#max_consecutive_speed").NumericOnly();
	$("#min_consecutive_speed").NumericOnly();
	$("#max_idle_time").NumericOnly();
});
$(document).keypress(function(e) { 
		if($('#assets_profile_form_div').css("display") != "none" && $('#assets_profile_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_profile_fitcut.dialog("open");
			}    
		}
	});

$("#loading_dialog").dialog("close");
</script>