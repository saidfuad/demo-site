		<?php if($this->form_model->battery_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Enter Battery Details"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Battery Details"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_battery_frm" class="error" style="display:none"></div>
		<div class="content toggle">
			<form id="frm_battery" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormbattery('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="100%" align="center"><label><?php echo $this->lang->line("Battery Volt"); ?></label> : <input type="text" name="volt" id="volt" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->volt; ?>" style="width:20%"/></td>
					</tr>
					<tr>
						<td align="center">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_battery()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			
			

			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
		$(document).keypress(function(e) { 
		if($('#battery_form_div').css("display") != "none" && $('#battery_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_battery_var_bitbot.dialog("open");
			}    
		}
	}); 
	
	jQuery("input:button, input:submit, input:reset").button();	
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$("#volt").NumericOnly();
	});
	


</script>