		<?php if($this->form_model->group_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create Group"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Group"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_group_frm" class="error" style="display:none"></div>
		<div class="content toggle">
			<form id="frm_group" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormGroup('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("group_name"); ?></label><input type="text" name="group_name" id="group_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->group_name; ?>" /></td>
						
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_group()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			
			

			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
		$(document).keypress(function(e) { 
		if($('#group_form_div').css("display") != "none" && $('#group_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_group_var_bitbot.dialog("open");
			}    
		}
	}); 
	
	jQuery("input:button, input:submit, input:reset").button();	
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	});
	


</script>