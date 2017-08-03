		<?php if($this->form_model->routename == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create Group"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Current Trip"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		
		<div class="content toggle">
			<form id="frm_trips" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormGroup('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Route Name"); ?></label><select type="text" name="routename" id="routename" class="text ui-widget-content ui-corner-all" ><?php echo $this->form_model->routename; ?>" <select/></td>
						<td width="50%"><label><?php echo $this->lang->line("Assets"); ?>:</label><select multiple="multiple" size="10" name="deviceid[]" id="deviceid" class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->deviceid; ?></select>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_trips()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>  
			</table>
		</form>
		</div>

<script type="text/javascript">
$(document).ready(function() {
	$("#loading_dialog").dialog("close");
	jQuery("input:button, input:submit, input:reset").button();	
	$("#loading_dialog").dialog("close");
	$("#routename").change(function(){
		var val=$(this).val();
		$.post("<?php echo site_url('trips/getAssets_select_Ids'); ?>/id/"+val,function(data){
		$("#deviceid").html(data);
		$("#loading_dialog").dialog("close");
		});
	});
	$("#routename").trigger("change");
	
	$(document).keypress(function(e) { 
		if($('#trips_form_div').css("display") != "none" && $('#trips_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_trips_var_kitkotlit.dialog("open");
			}   
		}
	});	
	$("#loading_top").css("display","none");
});

</script>