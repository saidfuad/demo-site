<?php $tme=time(); ?>
<script type="text/javascript">
loadColorSelection();
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
						$("#error_frm").html("Mobile Number Formate is Not Valid");
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
		title:'Choose Marker Icon',
		resizable: true,
		});
		$("#getIconList_land").click(function(){
		$("#Assets_icon_dialog_land<?php echo $tme; ?>").dialog("open");
		if($("#Assets_icon_dialog_land<?php echo $tme; ?>").html()=="")
		{
			$("#Assets_icon_dialog_land<?php echo $tme; ?>").html("<div style='text-align:center;verticle-align:middle' id='Imgloading'><img src='<?php echo base_url(); ?>assets/images/loading.gif'> Loading...</div>");	
				$.post("<?php echo base_url(); ?>index.php/zone/getIco",function(data){
					if(data!=""){
						//$("#Assets_icon_dialog").append(data);
						$("#Assets_icon_dialog_land<?php echo $tme; ?>").html(data);
						 
					}
					else
						$("#Assets_icon_dialog_land<?php echo $tme; ?>").html("");
				});
			}
		});
		
			$(".color-picker").miniColors({
						letterCase: 'uppercase',
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
		<h3 class="title_black"><?php echo $this->lang->line("Update_Zone"); ?></h3>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_zone" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitForm_zone('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Zone_Name"); ?> *</label><input type="text" name="polyname" id="zone_frm_polyname" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->polyname; ?>" /><input type="hidden" name="polyid" id="polyid" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->polyid; ?>" /></td>
						
						<td width="50%" ><label><?php echo $this->lang->line("Zone_Color"); ?></label><input type="text" name="color" id="landmakr_frm_color" class="color-picker text ui-widget-content ui-corner-all" size="6" autocomplete="on" maxlength="10"  value="<?php echo $this->form_model->color; ?>"/></td>
						
						
					</tr>
				<?php /*	<tr>
						<td width="50%" ><label><?php echo $this->lang->line("Area Type"); ?></label><select id="area_type_opt" name="area_type_opt" class="select ui-widget-content ui-corner-all"><option value="Dealer" <?php echo ($this->form_model->area_type_opt=='Dealer')?"selected='selected'":""; ?>>Dealer</option><option value="Tall Tax" <?php echo ($this->form_model->area_type_opt=='Tall Tax')?"selected='selected'":""; ?>>Tall Tax</option><option value="Others" <?php echo ($this->form_model->area_type_opt=='Others')?"selected='selected'":""; ?>>Others</option><option value="All" <?php echo ($this->form_model->area_type_opt=='All')?"selected='selected'":""; ?>>All</option></select></td>
						<td width="50%"><label><?php echo $this->lang->line("Address Book Group"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select name="address_book_group" id="landmakr_frm_address_book_group" class="select ui-widget-content ui-corner-all" onchange="getAddressBookDetail(this.value)">
							<?php echo $this->form_model->address_book_group; ?>
						</select>
						</td>
					</tr> */ ?>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Assets_List"); ?></label><select name="deviceid[]" id="landmakr_frm_deviceid" class="select ui-widget-content ui-corner-all" multiple="multiple" size="5">
						<?php echo $this->form_model->deviceid; ?></select></td>
						<td width="50%"><label><?php echo $this->lang->line("Address Book"); ?></label><select name="addressbook_ids[]" id="landmakr_frm_addressbook_ids" multiple='multiple' size="5" class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->addressbook_ids; ?></select>
						</td>
					</tr>
					<tr> 
						<td width="50%"><input type="checkbox" style="width:11%" name="in_alert" id="in_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->in_alert) && $this->form_model->in_alert==1) || (!isset($this->form_model->in_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("In Alert"); ?></label></td>
						<td width="50%"><input type="checkbox" style="width:11%" name="out_alert" id="out_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->out_alert) && $this->form_model->out_alert==1) || (!isset($this->form_model->out_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Out Alert"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><input type="checkbox" style="width:11%" name="sms_alert" id="sms_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sms_alert) && $this->form_model->sms_alert==1) || (!isset($this->form_model->sms_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Mobile_Alerts"); ?></label></td>
						<td width="50%"><input type="checkbox" style="width:11%" name="email_alert" id="email_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->email_alert) && $this->form_model->email_alert==1) || (!isset($this->form_model->email_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Email_Alerts"); ?></label></td>
					</tr> 
					<tr>
						<td align="center" colspan="2"><br/>
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_zone()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" />
						&nbsp;&nbsp;
						<input type="button" id="btn_edit" onclick="edit_in_map_zone(<?php echo $this->form_model->polyid; ?>)" name="btn_edit" value="<?php echo $this->lang->line("Edit In Map"); ?>" /></td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="Assets_icon_dialog_land<?php echo $tme; ?>" class="assestimage_oad"></div>
<script type="text/javascript">
