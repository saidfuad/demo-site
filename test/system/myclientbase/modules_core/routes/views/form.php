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
				$.post("<?php echo base_url(); ?>index.php/routes/getIco",function(data){
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
					$("#route_frm_addressbook_ids").html(data.opt);
				}, 'json');	
		
		}
		function selectedMarker_land(img)
		{
			$("#icon_path").val(img);
			$("#ic_path_id").attr("src","<?php echo base_url();?>"+img);
			$("#Assets_icon_dialog_land<?php echo $tme; ?>").dialog("close");
		}
		</script>
		<h3 class="title_black"><?php echo $this->lang->line("Update_Routes"); ?></h3>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_routes" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitForm_area('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Route Name"); ?>*</label><input type="text" name="routename" id="area_frm_routename" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->routename; ?>" /></td>
						<td width="50%" ><label><?php echo $this->lang->line("Routes_Color"); ?></label><br/><input type="text" name="route_color" id="route_frm_route_color" style="width:41%" class="color-picker text ui-widget-content ui-corner-all" size="6" autocomplete="on" maxlength="10"  value="<?php echo $this->form_model->route_color; ?>"/></td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Distance_Total"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="total_distance" id="route_frm_total_distance" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->total_distance; ?>"/></td>
						<td width="50%"><label><?php echo $this->lang->line("Time_minutes"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="text" name="total_time_in_minutes" id="route_frm_total_time_in_minutes" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->total_time_in_minutes; ?>"/></td>
					</tr>
					<tr>
						<td width="50%">
						<label><span style="width:49%;display:inline-block"><?php echo $this->lang->line("Alert_When_Distance"); ?></span><?php echo $this->lang->line("Distance_Unit"); ?></label><br/><input type="text" name="distance_value" id="route_frm_distance_value" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->distance_value; ?>" style="width:45%"/><select name="distance_unit" id="route_frm_distance_unit" class="select ui-widget-content ui-corner-all" style="width:45%"><?php echo $this->form_model->distance_unit; ?></select>
						</td>
						<td width="50%"  rowspan="3"><label><?php echo $this->lang->line("Assets_List"); ?></label><select name="deviceid[]" id="route_frm_deviceid" class="select ui-widget-content ui-corner-all" multiple="multiple" size="9">
						<?php echo $this->form_model->deviceid; ?></select></td>
					</tr>
					<tr>
						<td width="50%" style="vertical-align:middle"><input type="checkbox" style="width:11%" name="round_trip" id="round_trip" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->round_trip) && $this->form_model->round_trip==1) || (!isset($this->form_model->round_trip)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Round_trip"); ?></label></td>
					</tr>
					<tr>
						<td width="50%" ><label><?php echo $this->lang->line("Comments"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<textarea name="comments" id="route_frm_comments" class="text ui-widget-content ui-corner-all" ><?php echo $this->form_model->comments; ?></textarea>
						</td>
					</tr>
					<tr>
						<td width="50%"><input type="checkbox" style="width:11%" name="sms_alert" id="sms_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sms_alert) && $this->form_model->sms_alert==1) || (!isset($this->form_model->sms_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Mobile_Alerts"); ?></label></td>
						
						<td width="50%"><input type="checkbox" style="width:11%" name="email_alert" id="email_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->email_alert) && $this->form_model->email_alert==1) || (!isset($this->form_model->email_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Email_Alerts"); ?></label></td>
					</tr> 
					<tr>
						<td align="center" colspan="2"><br/>
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_routes()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="Assets_icon_dialog_land<?php echo $tme; ?>" class="assestimage_oad"></div>
<script type="text/javascript">
