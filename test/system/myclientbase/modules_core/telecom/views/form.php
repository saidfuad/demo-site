		<?php if($this->form_model->telecom_provider_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Add telecom Details"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update telecom Details"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_telecom_frm" class="error" style="display:none"></div>
		<div class="content toggle">
			<form id="frm_telecom" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormtelecom('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="100%" align="center"><label><?php echo $this->lang->line("Telecom Provider Name"); ?></label> : <input type="text" name="telecom_provider_name" id="telecom_provider_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->telecom_provider_name; ?>" style="width:20%"/></td>
					</tr>
					<tr>
						<td align="center">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_telecom()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			
			

			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
		$(document).keypress(function(e) { 
		if($('#telecom_form_div').css("display") != "none" && $('#telecom_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_telecom_var_bitbot.dialog("open");
			}    
		}
	}); 
	
	jQuery("input:button, input:submit, input:reset").button();	
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$("#volt").NumericOnly();
	});
	


</script>