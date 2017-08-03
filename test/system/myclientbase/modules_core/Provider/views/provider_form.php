		<script type="text/javascript">
		$(document).ready(function() {
			$("#ttbl_provider_ds_form_provider").Upper();
			jQuery("input:button, input:submit, input:reset").button();	
			$(".date").datetimepicker({dateFormat:'dd.mm.yy',timeFormat: 'hh:mm', changeMonth: true,changeYear: true});	
		});
		</script>
		<?php if($this->form_model->state_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create_Provider"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update_Provider"); ?.</h3>
		<?php } ?>

		<?php $this->load->view('dashboard/system_messages'); ?>
		
		<div class="content toggle" align="center">
			<form id="ttbl_provider_ds_frm" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormUsers_tbl_provider('<?php echo uri_assoc('id')?>')">
			<div id="app_menu_master_name_duplicate_error" class="error" style="display:none"></div>
			<p id="error" class="addTips">* Fields are mendatory</p>
			<table width="70%" align="center" class="formtable">
				<tbody>
					<tr>
					<td width="12%">menu_name</td>
					<td width="38%"><input type="text" name="menu_name" id="tapp_menu_master_ds_form_menu_name" class="text ui-widget-content ui-corner-all"/></td>
					<td width="12%">menu_link</td>
					<td width="38%"><input type="text" name="menu_link" id="tapp_menu_master_ds_form_menu_link" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td width="12%">where_to_show</td>
					<td width="38%"><input type="text" name="where_to_show" id="tapp_menu_master_ds_form_where_to_show" class="text ui-widget-content ui-corner-all"/></td>
					<td width="12%">menu_sound</td>
					<td width="38%"><input type="text" name="menu_sound" id="tapp_menu_master_ds_form_menu_sound" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td width="12%">tab_title</td>
					<td width="38%"><input type="text" name="tab_title" id="tapp_menu_master_ds_form_tab_title" class="text ui-widget-content ui-corner-all"/></td>
					<td width="12%">menu_level</td>
					<td width="38%"><input type="text" name="menu_level" id="tapp_menu_master_ds_form_menu_level" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td width="12%">parent_menu_id</td>
					<td width="38%"><input type="text" name="parent_menu_id" id="tapp_menu_master_ds_form_parent_menu_id" class="text ui-widget-content ui-corner-all"/></td>
					<td width="12%">menu_image</td>
					<td width="38%"><input type="text" name="menu_image" id="tapp_menu_master_ds_form_menu_image" class="text ui-widget-content ui-corner-all"/></td>
				</tr>
				<tr>
					<td width="12%">user_id</td>
					<td width="38%"><input type="text" name="user_id" id="tapp_menu_master_ds_form_user_id" class="text ui-widget-content ui-corner-all"/></td>
					<td width="12%">Comments</td>
					<td width="38%"><textarea name="Comments" id="tapp_menu_master_ds_form_Comments" class="textarea ui-widget-content ui-corner-all"/></textarea></td>
				</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_users_tbl_provider()" name="btn_cancel" value="Back" /></td>
					</tr>
				</tbody>
			</table>
			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
});
$("#loading_dialog").dialog("close");

</script>