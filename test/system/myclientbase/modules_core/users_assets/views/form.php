		<?php if($this->form_model->user == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Add Users Assets"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Users Assets"); ?></h3>
		<?php } 
		$tim=time();
		?>
		
		<?php $this->load->view('dashboard/system_messages'); ?>
		
		<div class="content toggle">
			<form id="frm_users_assets" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormUsersAssets('<?php echo uri_assoc('id')?>')">
			<input type='hidden' name='status' value=1 />
            <input type="hidden" name="selected_ast" id="selected_ast" value="<?php echo $ast; ?>" />
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("user"); ?> :</label><input readonly="readonly" type="text" id="user" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->user; ?>" Readonly='readonly'/></td>
						<td width="50%" rowspan="2"><label><?php echo $this->lang->line("Assets"); ?>:</label><select multiple="multiple" size="10" name="assets_ids[]" id="assets_ids" class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->assets_ids; ?></select>
						</td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Group"); ?>:</label><select multiple="multiple" size="7" name="group_id[]" id="group_id" class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->group_id; ?></select>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_users_assets()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>					
				</tbody>
			</table>
			</form>

		</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#loading_dialog").dialog("close");
	jQuery("input:button, input:submit, input:reset").button();

	$('#group_id').change(function(){
		$("#loading_top").css("display","block");
		$.post("<?php echo base_url(); ?>/index.php/users_assets/fetchAssets", {group:$(this).val(), selected : $("#selected_ast").val()},
			function(data) {
				$("#loading_top").css("display","none");
				$("#assets_ids").html(data);
			}
		);
	});

	$(document).keypress(function(e) { 
			
				if (e.keyCode == 27) {
if($('#user_assets_form_div').css("display") != "none" &&  $('#user_assets_form_div').css("display") != undefined){				
					conf_dialog_usr_assets_var_yesno.dialog("open");
				}   
			}
		}); 
	$("#loading_top").css("display","none");
});

</script>