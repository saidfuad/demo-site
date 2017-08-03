<?php $tme=time(); ?>
<script type="text/javascript">
		$(document).ready(function() {
			$("#btn_submit").click(function(){
				if($("#mobile_number").val() != ""){
				var emails=$("#mobile_number").val();
				var em=emails.split(/[;,]+/);
				for(i=0;i<em.length;i++)
				{
					if(em[i].length == 10)
					{
						$("#error_frm").hide();
					}else{
						$("#error_frm").show();
						$("#error_frm").html("<?php echo $this->lang->line("Mobile_Number_Formate_is_Not_Valid"); ?>");
						return false;
					}
				}
				}
			});
		jQuery("input:button, input:submit, input:reset").button();
		$("#loading_top").css("display","none");
				$("#Assets_icon_dialog_land<?php echo $tme; ?>").dialog({
		autoOpen: false,
		modal: true,
		height: 'auto',
		width:'70%',
		draggable: true,
		title:'<?php echo $this->lang->line("Choose_Marker_Icon"); ?>',
		resizable: true,
		});
		$("#getIconList_land").click(function(){
			$("#Assets_icon_dialog_land<?php echo $tme; ?>").dialog("open");
			if($("#Assets_icon_dialog_land<?php echo $tme; ?>").html()=="")
			{
				$("#Assets_icon_dialog_land<?php echo $tme; ?>").html("<div style='text-align:center;verticle-align:middle' id='Imgloading'><img src='<?php echo base_url(); ?>assets/images/loading.gif'> Loading...</div>");	
					$.post("<?php echo base_url(); ?>index.php/landmarks/getIco",function(data){
						if(data!=""){
							//$("#Assets_icon_dialog").append(data);
							$("#Assets_icon_dialog_land<?php echo $tme; ?>").html(data);
							 
						}
						else
							$("#Assets_icon_dialog_land<?php echo $tme; ?>").html("");
				});
			}
		});

		}); 
		function getAddressBookDetail(val)
		{
				$.post("<?php echo base_url(); ?>index.php/home/filterAddressbook",	
				{id : val},
				function(data){
					$("#landmakr_frm_addressbook_ids").html(data.opt);
				}, 'json');	
		
		}
		function selectedMarker_land(img)
		{
			$("#icon_path").val(img);
			$("#ic_path_id").attr("src","<?php echo base_url();?>"+img);
			$("#Assets_icon_dialog_land<?php echo $tme; ?>").dialog("close");
		}
		</script>
		<h3 class="title_black"><?php echo $this->lang->line("Update_Landmarks"); ?></h3>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_landmarks" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitForm_Landmark('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Landmark_Name"); ?> *</label><input type="text" name="name" id="landmark_frm_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->name; ?>" /></td>
						<td width="50%"  rowspan="2"><label><?php echo $this->lang->line("Assets_List"); ?></label><select name="device_ids[]" id="landmakr_frm_device_ids" class="select ui-widget-content ui-corner-all" multiple="multiple" size="7">
						<?php echo $this->form_model->device_ids; ?></select></td>
					</tr>
					<tr>
						<td width="50%" ><label><?php echo $this->lang->line("Address"); ?> </label><textarea name="address" id="landmark_frm_address" class="text ui-widget-content ui-corner-all" ><?php echo $this->form_model->address; ?></textarea></td>
					</tr>
					<tr>
						<td width="50%"><label><span style="width:49%;display:inline-block"><?php echo $this->lang->line("Radius"); ?></span><?php echo $this->lang->line("Distance_Unit"); ?></label><br/><input type="text" name="radius" id="landmakr_frm_radius" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->radius; ?>" style="width:45%"/><select name="distance_unit" id="landmakr_frm_distance_unit" class="select ui-widget-content ui-corner-all" style="width:45%"><?php echo $this->form_model->distance_unit; ?></select>
						</td>
						<td width="50%"><label><?php echo $this->lang->line("Icon"); ?></label></br><img src="<?php echo base_url().$this->form_model->icon_path; ?>" id="ic_path_id" style='margin-right:5px;'/><input type="button" id="getIconList_land" value="<?php echo $this->lang->line("Choose Icon"); ?>"/>
						<input type="hidden" name="icon_path" id="icon_path" class="select ui-widget-content ui-corner-all" value="<?php echo $this->form_model->icon_path; ?>"/></td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Address Book Group"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select name="address_book_group" id="landmakr_frm_address_book_group" class="select ui-widget-content ui-corner-all" onchange="getAddressBookDetail(this.value)">
							<?php echo $this->form_model->address_book_group; ?>
						</select>
						</td>
						<td width="50%"><label><?php echo $this->lang->line("Landmark Group"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select name="group_id" id="landmakr_frm_group_id" class="select ui-widget-content ui-corner-all" >
							<?php echo $this->form_model->group_id; ?>
						</select>
						</td>
						
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Address Book"); ?></label><select name="addressbook_ids[]" id="landmakr_frm_addressbook_ids" multiple='multiple' size=4 class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->addressbook_ids; ?></select>
						</td>
						<td width="50%"><label><?php echo $this->lang->line("Comments"); ?></label><textarea name="comments" id="landmakr_frm_comments" class="text ui-widget-content ui-corner-all" style='height:60px'><?php echo $this->form_model->comments; ?></textarea></td>
					</tr>
					<tr>
						<td width="50%"><input type="checkbox" style="width:11%" name="sms_alert" id="sms_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sms_alert) && $this->form_model->sms_alert==1) || (!isset($this->form_model->sms_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Mobile_Alerts"); ?></label></td>
						<td width="50%"><input type="checkbox" style="width:11%" name="email_alert" id="email_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->email_alert) && $this->form_model->email_alert==1) || (!isset($this->form_model->email_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Email_Alerts"); ?></label></td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Alert Before Landmark"); ?></label>&nbsp;&nbsp;&nbsp;<input type="text" name="alert_before_landmark" id="alert_before_landmark" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->alert_before_landmark; ?>" style="width:35%"/>&nbsp;&nbsp;K.M.</td>
						<td width="50%"></td>
					</tr>
					<tr>
						<td align="center" colspan="2"><br/>
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_landmarks()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" />
						&nbsp;&nbsp;
						<input type="button" id="btn_edit" onclick="edit_in_map_lnd(<?php echo uri_assoc('id'); ?>)" name="btn_edit" value="<?php echo $this->lang->line("Edit In Map"); ?>" /></td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="Assets_icon_dialog_land<?php echo $tme; ?>" class="assestimage_oad"></div>