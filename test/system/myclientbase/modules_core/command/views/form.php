<style type="text/css">
	table{
		width: 598px; 
		height: 10px;
		margin-left: -40px;
		margin-top: -16px;
	}
</style>
<script type="text/javascript">

	$(document).ready(function() {
		//$("#icon_id").msDropDown();
		
		$(document).keypress(function(e) { 
		if($('#command_form_div').css("display") != "none" && $('#command_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_type_vtsloglog.dialog("open");
			}    
		}
	});
	});
	</script>
<?php if($this->form_model->command == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Add Command"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Command"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="error_frm_address" class="error" style="display:none"></div>
<div class="content toggle" align="center">
  <form id="frm_command" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormcommand('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="100%" class="formtable" style="padding: 0;margin-left: -40px;margin-top: -16px;">
      <tbody>
        <tr>
			<td width="50%">
			  <label><?php echo $this->lang->line("Device"); ?>*</label>
				<select name="assets_class_id" id="assets_class_id" class="select ui-widget-content ui-corner-all">
					<?php echo $this->form_model->assets_class_opt; ?>
				</select>
			</td>
			<td width="50%">
				<label><?php echo $this->lang->line("Command"); ?>*</label>
				<input type="text" name="command" id="command" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->command; ?>"/>
			</td>
		 </tr>
			<tr>
          <td width="50%">
			<label><?php echo $this->lang->line("Comments"); ?></label>
            <textarea name="comments" id="comments" class="text ui-widget-content ui-corner-all" style="height:72px"><?php echo $this->form_model->comments; ?></textarea></td>
			
		</tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_command_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_command_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_command()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();
	$("#mobile_number_add").Mobile_Comma_Only();
	$("#driver_code").Mobile_Comma_Only();
});
$("#loading_dialog").dialog("close");

</script>