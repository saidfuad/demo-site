		<?php if($this->form_model->name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Add Emails"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Emails"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_emails_frm" class="error" style="display:none"></div>
		<div class="content toggle" align='center'>
			<form id="frm_emails" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormemails('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="60%" align="center" class="formtable">
				<tbody>
					<tr>
						<td><label><?php echo $this->lang->line("Name"); ?>&nbsp;:</label><input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->name; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php echo $this->lang->line("Email ID"); ?>&nbsp;:</label><input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->email; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php echo $this->lang->line("Mobile"); ?>&nbsp;:</label><input type="text" name="mobile" id="mobile" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->mobile; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php echo $this->lang->line("Email Status"); ?></label>&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="status" id="status" class="ui-widget-content ui-corner-all" value=1 <?php if(isset($this->form_model->status) && $this->form_model->status==1){ echo "checked='checked'"; }else if(!isset($this->form_model->status)){  echo "checked='checked'"; } ?>/>&nbsp;<?php echo $this->lang->line("Active"); ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="status" id="status" class="ui-widget-content ui-corner-all" value=0  <?php if(isset($this->form_model->status) && $this->form_model->status==0){ echo "checked='checked'"; } ?>/>&nbsp;<?php echo $this->lang->line("Inactive"); ?><Br/><Br/></td>
					</tr>
					<tr>
						<td><label>Email Server Fail alert</label>&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="email_stop_alert" id="email_stop_alert" class="ui-widget-content ui-corner-all" value=1 <?php if(isset($this->form_model->email_stop_alert) && $this->form_model->email_stop_alert==1){ echo "checked='checked'"; }else if(!isset($this->form_model->email_stop_alert)){  echo "checked='checked'"; } ?>/>&nbsp;<?php echo $this->lang->line("Active"); ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="email_stop_alert" id="email_stop_alert" class="ui-widget-content ui-corner-all" value=0  <?php if(isset($this->form_model->email_stop_alert) && $this->form_model->email_stop_alert==0){ echo "checked='checked'"; } ?>/>&nbsp;<?php echo $this->lang->line("Inactive"); ?><Br/><Br/></td>
					</tr>
					<tr>
						<td align="center">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_emails()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			
			

			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
		$(document).keypress(function(e) { 
		if($('#emails_form_div').css("display") != "none" && $('#emails_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_emails_var_bitbot.dialog("open");
			}
		}
	});
	jQuery("input:button, input:submit, input:reset").button();	
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	});
	


</script>