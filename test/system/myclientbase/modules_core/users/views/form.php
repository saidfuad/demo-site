<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
	 
?>
<script type="text/javascript">
	load_dropdown_div();
</script>
<style>
#ui_tpicker_hour_label_from_date,#ui_tpicker_hour_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_minute_label_from_date,#ui_tpicker_minute_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_second_label_from_date,#ui_tpicker_second_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

.dropdown-panel {
	padding:10px 20px;
}
.dropdown-panel li {
	margin:10px 0;
}
.dropdown-panel a {
	cursor:pointer;
	background:lightblue;
	border-radius:4px;
	padding:2px 4px;
	margin:4px 0;
}
</style>
<script type="text/javascript">
		$(document).ready(function() {
			$("#btn_submit").click(function(){
			    /*
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
				*/
			});
	    $("#birth_date").datepicker({maxDate: new Date(),dateFormat:'<?php echo $js_date_format; ?>',changeMonth: true,changeYear: true,yearRange: '1960:2020'});
		$("#from_date").datepicker({dateFormat:'<?php echo $js_date_format; ?>',changeMonth: true,changeYear: true});
		$("#to_date").datepicker({dateFormat:'<?php echo $js_date_format; ?>',changeMonth: true,changeYear: true});

		var newDate = new Date('<?php echo date("Y/m/d H:i:s");?>');
		newDate.setFullYear(newDate.getFullYear() + 1);
		<?php if($this->form_model->username == ""){ ?>
		$("#from_date").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s");?>'));
		$("#to_date").datepicker('setDate', newDate);
		<?php }else{ ?>
		$("#from_date").val('<?php echo date($date_format, strtotime($this->form_model->from_date)); ?>');
		$("#to_date").val('<?php echo date($date_format, strtotime($this->form_model->to_date)); ?>');
		<?php } ?>
		jQuery("input:button, input:submit, input:reset").button();
		$("#loading_top").css("display","none");
	
		$('#daysSelect').dropdown();
		});

		$(document).keypress(function(e) { 
			if (e.keyCode == 27) { 
				if($("#users_form_div").css("display")!="none" && $("#users_form_div").css("display") != undefined)
					user_conf_dialog_usr_abcd.dialog("open");
			}   	
		}); 
		
		function usr_state(val,city){
			if(city!=0)
			{
				$.post(	
					"<?php echo site_url('users/state_data/id');  ?>/"+val+"/state/"+city,function(data){
						$('#state').html("<option value=''><?php echo $this->lang->line("Select State"); ?></option>");

						$('#state').html(data);
						//$('#city').html("<option value=''><?php echo $this->lang->line("Select State"); ?></option>");
					});
			}
			else
			{
				$.post(	
				"<?php echo site_url('users/state_data/id');  ?>/"+val,function(data){
						$('#state').html("<option value=''><?php echo $this->lang->line("Select State"); ?></option>");

						$('#state').html(data);
						//$('#city').html("<option value=''><?php echo $this->lang->line("Select State"); ?></option>");
				});
			}
		}
		function usr_city(value,ct){
			if(ct!=0)
			{
				$.post(
					"<?php echo site_url('users/city_data/id');  ?>/"+value+"/city/"+ct,function(data){
							$('#city').html("<option value='' ><?php echo $this->lang->line("Select City"); ?></option>");
							$('#city').html(data);
				});
			}
			else
			{
				$.post(
					"<?php echo site_url('users/city_data/id');  ?>/"+value,function(data){
							$('#city').html("<option value='' ><?php echo $this->lang->line("Select City"); ?></option>");

							$('#city').html(data);
				});

			}
		}
		</script>
		<?php if($this->form_model->username == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create_Users"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update_Userss"); ?></h3>
		<?php } ?>

		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_users" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormUsers('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("username"); ?> *</label><input type="text" name="username" id="username" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->username; ?>" /></td>
						<?php 
							if(empty($this->form_model->password)){ ?>
						<td width="50%"><label><?php echo $this->lang->line("password"); ?> *</label><input type="text" name="password" id="password" class="text ui-widget-content ui-corner-all" value="" />
						</td>
						<?php  }else { ?>
						<td width="50%"><label><?php echo $this->lang->line("password"); ?> *</label><input type="text" name="password" id="password" class="text ui-widget-content ui-corner-all" value="" />
						</td>
						<?php } ?>
					</tr>
					<tr>
                         <td width="50%"><label><?php echo $this->lang->line("Confirm Password"); ?> *</label><input type="text" name="confirm_password" class="text ui-widget-content ui-corner-all" value="" /></td>
					
						<td width="50%"><label><?php echo $this->lang->line("first_name"); ?> *</label><input type="text" name="first_name" id="first_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->first_name; ?>" /></td>
                    </tr>
					<tr>
				    	<td width="50%"><label><?php echo $this->lang->line("last_name"); ?></label><input type="text" name="last_name" id="last_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->last_name; ?>" />
                            </td>	
			         <td width="50%"><label><?php  echo "D.O.B"; ?></label><input type="text" name="birth_date" id="birth_date" class="date text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->birth_date; ?>"/></td>
                   
					</tr>
					<tr>
					<td width="50%"><label><?php echo $this->lang->line("Email_Address"); ?></label><input type="text" name="email_address" id="email_address" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->email_address; ?>" /></td>
					   <td width="50%"><label><?php echo $this->lang->line("mobile"); ?> *</label><input type="text" name="mobile_number" id="mobile_number" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->mobile_number; ?>" />	
                      
					</tr>
                    <tr>
					<td width="50%"><label><?php  echo $this->lang->line("User Valid(From Date)"); ?></label><input type="text" name="from_date" id="from_date" class="date text ui-widget-content ui-corner-all" /></td>
                    <td width="50%"><label><?php echo $this->lang->line("User Valid(To Date)"); ?></label><input type="text" name="to_date" id="to_date" class="date text ui-widget-content ui-corner-all" /></td>
			        </tr>
					<tr>
						<td width="50%"><span><label><?php echo $this->lang->line("Login Days"); ?></label><br/><div id="daysSelect" class="dropdown text ui-widget-content ui-corner-all"><a class="dropdown-button"><?php echo $this->lang->line("Select Display Days"); ?><img src="<?php echo base_url(); ?>assets/style/img/dropdown.png" class="dropdown-icon"/></a>
						<div class="dropdown-panel">
						<input type="checkbox" value="all" id="selectAlldays" checked="checked"/><?php echo $this->lang->line("All Days"); ?><br/><input type="checkbox" value="sunday" name="display_day[]"/><?php echo $this->lang->line("Sunday"); ?><br/><input type="checkbox" value="monday" name="display_day[]"/><?php echo $this->lang->line("Monday"); ?><br/><input type="checkbox" value="tuesday" name="display_day[]"/><?php echo $this->lang->line("Tuesday"); ?><br/>
						<input type="checkbox" value="wednesday"name="display_day[]"/><?php echo $this->lang->line("Wednesday"); ?><br/><input type="checkbox" value="thursday" name="display_day[]"/><?php echo $this->lang->line("Thursday"); ?><br/><input type="checkbox" value="friday" name="display_day[]"/><?php echo $this->lang->line("Friday"); ?><br/>
						<input type="checkbox" value="saturday"name="display_day[]"/><?php echo $this->lang->line("Saturday"); ?>
						</div></div></span></td><td width="50%"><br/><input type="checkbox" style="width:11%" name="status" id="status" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->status) && $this->form_model->status==1) || (!isset($this->form_model->status)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("active_user"); ?></label></td>
					</tr>
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="sms_alert" id="sms_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sms_alert) && $this->form_model->sms_alert==1) || (!isset($this->form_model->sms_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Mobile_Alerts"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="email_alert" id="email_alert" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->email_alert) && $this->form_model->email_alert==1) || (!isset($this->form_model->email_alert)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Email_Alerts"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="sms_enable" id="sms_enable" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sms_enable) && $this->form_model->sms_enable==1) || (!isset($this->form_model->sms_enable)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Sms Enable"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="change_password" id="change_password" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->change_password) && $this->form_model->change_password==1) || (!isset($this->form_model->change_password)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("change_password"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="history" id="history" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->history) && $this->form_model->history==1) || (!isset($this->form_model->history)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("history"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="allow_user_profile" id="allow_user_profile" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->allow_user_profile) && $this->form_model->allow_user_profile==1) || (!isset($this->form_model->allow_user_profile)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("allow_user_profile"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="report_view" id="report_view" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->report_view) && $this->form_model->report_view==1) || (!isset($this->form_model->report_view)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("report_view"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="menu_view" id="menu_view" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->menu_view) && $this->form_model->menu_view==1) || (!isset($this->form_model->menu_view)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("menu_view"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="expiry_service_sms" id="expiry_service_sms" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->expiry_service_sms) && $this->form_model->expiry_service_sms==1) || (!isset($this->form_model->expiry_service_sms)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Service Expiry Sms"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="expiry_service_email" id="expiry_service_email" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->expiry_service_email) && $this->form_model->expiry_service_email==1) || (!isset($this->form_model->expiry_service_email)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Service Expiry Email"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="show_owners" id="show_owners" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->show_owners) && $this->form_model->show_owners==1) || (!isset($this->form_model->show_owners)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Show Owners"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="show_divisions" id="show_divisions" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->show_divisions) && $this->form_model->show_divisions==1) || (!isset($this->form_model->show_divisions)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Show Divisions"); ?></label></td>
					</tr> 
					<tr>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="auto_refresh_setting" id="auto_refresh_setting" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->auto_refresh_setting) && $this->form_model->auto_refresh_setting==1) || (!isset($this->form_model->auto_refresh_setting)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Auto Refresh"); ?></label></td>
						<td width="50%"><br/><input type="checkbox" style="width:11%" name="device_id_not_editable" id="device_id_not_editable" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->device_id_not_editable) && $this->form_model->device_id_not_editable==1) || (!isset($this->form_model->device_id_not_editable)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Device Id Not Editable"); ?></label></td>
					</tr> 
					<tr>
						<!--td width="50%"><label>Website</label><input type="text" name="web_address" id="web_address" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->web_address; ?>" />
						</td-->
						<!--td width="50%"><label>Website</label><input type="text" name="web_address" id="web_address" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->web_address; ?>" />
						</td-->
					</tr>
					<tr>
						<td width="50%"> <label><?php echo $this->lang->line("Country"); ?>&nbsp;&nbsp;</label>
						<select name="country" id="country" class="text ui-widget-content ui-corner-all" <?php if(isset($this->form_model->state)){ echo 'value="'.$this->form_model->state.'"'; }?> onchange="usr_state(this.value,0);">
							<option id="0" value=""><?php echo $this->lang->line("Please Select"); ?></option>
							<?php
							$cntry=$this->form_model->cntry;
							print_r($cntry);
							//foreach($country as $row){ ?>
							<?php // }	?>
						</select>
						<!-- <input type="text" name="country" id="country" class="text ui-widget-content ui-corner-all" value="<?php //echo $this->form_model->country; ?>" /> -->
						<td width="50%"><label><?php echo $this->lang->line("State"); ?></label>
						<select  name="state" id="state" class="text ui-widget-content ui-corner-all" onchange="usr_city(this.value,0);">
							<option value=""><?php echo $this->lang->line("Please Select"); ?></option>
							<?php
								$stt=$this->form_model->stt;
								if($stt!="")
									print_r($stt);
								//foreach($country as $row){ ?>
							<?php // }	?>
						</select>
						</td>
					</td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("City"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<select name="city" id="city" class="text ui-widget-content ui-corner-all" >
							<option value=""><?php echo $this->lang->line("Please Select"); ?></option>
						</select>
			<!-- <input type="text" name="city" id="city" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->city; ?>" /> --></td> 
						<td width="50%" rowspan='2'><label><?php echo $this->lang->line("Address"); ?></label><textarea name="address" id="address" style='resize:none;height:75px' class="text ui-widget-content ui-corner-all" ><?php echo $this->form_model->address; ?></textarea></td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Zip"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="text" name="zip" id="zip" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->zip; ?>" /></td>
						
					</tr>
					<tr>
						<td width="50%">
							<label><?php echo $this->lang->line("profile"); ?> *</label>
							<select name="profile_id" id="profile_id" class="text ui-widget-content ui-corner-all" >
							<option value=''><?php echo $this->lang->line("Please Select"); ?></option>
							<?php
								$query =" select id,profile_name from mst_user_profile where del_date is null and status=1 and add_uid=".$this->session->userdata('user_id');
								$res =$this->db->query($query);
								foreach($res->result_Array() as $row){
									echo "<option value='".$row['id']."'";
									if($this->form_model->profile_id==$row['id']){
										echo " selected='selected' ";
									}
									
									echo " >".$row['profile_name']."</option>";
								}
								 ?></select>
						</td>
						<td width="50%"><label><?php echo $this->lang->line("Phone No"); ?></label><input type="text" name="phone_number" id="phone_number" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->phone_number; ?>" />
						</td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Company_Name"); ?></label><input type="text" name="company_name" id="company_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->company_name; ?>" /></td>
						<td width="50%"><label><?php echo $this->lang->line("Fax"); ?>&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="text" name="fax_number" id="fax_number" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fax_number; ?>" /></td>
						</td>
					</tr>
					
					<tr>
						<span style="display:none"><label><?php echo $this->lang->line("Company_Logo"); ?></label><input type="text" name="user_logo" id="user_logo" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->user_logo; ?>" /></span>
						<td width="50%"></td>
						<td width="50%"></td>						
					</tr> 
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_users()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>					
				</tbody>
			</table>
			</form>
		</div>
<script type="text/javascript"> 
$(document).ready(function() {
	$("#loading_dialog").dialog('close');
	jQuery("input:button, input:submit, input:reset").button();
	
	$("#callJson").click(function(){
		$.post("<?php echo site_url('users/get_json_data');  ?>",function(data){
			alert(data.toSource());
		});
	});
	$("#selectAlldays").change(function(){
		if($(this).attr("checked")=="checked"){
		$.each($("input[name='display_day[]']"), function() {
				$(this).attr("checked","checked");
			});
		}else
		{
		$.each($("input[name='display_day[]']"), function() {
				$(this).removeAttr("checked");
			});
		}
	});
	$("input[name='display_day[]']").change(function(){
		var bool=true;
		$.each($("input[name='display_day[]']"), function() {
			if($(this).attr("checked")!="checked"){
				bool=false;
			}
		});
		if(bool==true){
			$("#selectAlldays").attr("checked","checked");
		}else{
			$("#selectAlldays").removeAttr("checked");
		}
	});
	<?php 
	if(isset($this->form_model->country) && $this->form_model->country!=""){ 
		if($this->form_model->state!=""){
		?>
		usr_state(<?php echo $this->form_model->country.",".$this->form_model->state; ?>);
	<?php } else { ?>
		usr_state(<?php echo $this->form_model->country.",0"; ?>);
	<?php }
	
	if($this->form_model->state!="" && $this->form_model->city!="")
	{ ?>	
		usr_city(<?php echo $this->form_model->state.",".$this->form_model->city; ?>);
	<?php
	}
	else if($this->form_model->state=="" && $this->form_model->city!="")
	{ ?>
		usr_city(<?php echo $this->form_model->city.",0"; ?>);
	<?php }
	}
	?>	
	
	$("#zip").NumericOnly();
	$("#fax_number").NumericOnly();
	$("#phone_number").NumericOnly();
	$("#username").UserName();
	//$("#password").Password();
	$("#mobile_number").Mobile_Comma_Only();
	vfields['username']="NoBlank";
	vfields['mobile_number'] = "NoBlank";
	//vfields['password']="NoBlank";
	vfields['email_address']="NoBlank";
	vfields['email_address']="Email";
	
	<?php if(isset($weekdays) && isset($this->form_model->username) && $this->form_model->username!=""){
		for($i=0;$i<count($weekdays);$i++){ ?>
			 $("input[value='<?php echo $weekdays[$i] ?>']").attr("checked","checked");
			 
			 <?php
			 if($i==count($weekdays)-1){?>
			 $("input[value='<?php echo $weekdays[$i] ?>']").trigger("change");
			 <?php }
		}
	}else{?>
		$("#selectAlldays").trigger("change");
	<?php }
	?>
});

</script>