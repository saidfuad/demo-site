<?php
	$date_format = $this->session->userdata('date_format'); 
	$time_format = $this->session->userdata('time_format');  
	$time_format=str_replace (":s", "" ,$time_format);
	$js_date_format = $this->session->userdata('js_date_format');  
	$js_time_format = $this->session->userdata('js_time_format');  
	$js_time_format=str_replace (":ss", "" ,$js_time_format);
	$ampm="";
	$js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	if(strpos($js_time_format, 'TT'))
	{
		$ampm=",ampm:true";
	}
?>
<style>
#ui_tpicker_hour_label_alert_start_time,#ui_tpicker_hour_label_alert_stop_time
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_minute_label_alert_start_time,#ui_tpicker_minute_label_alert_stop_time
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_second_label_alert_start_time,#ui_tpicker_second_label_alert_stop_time
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
</style>
<script>
	jQuery().ready(function (){
	$("#loading_dialog").dialog("close");
		jQuery("input:button, input:submit, input:reset").button();
		/**/
		$("#user_img_form a").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>' class='user_img_set' alt='image' width='148'></img>");
	/*	$.post("<?php echo base_url(); ?>index.php/profile/get_profile_detail",
		function(data) {
			
		});
		/*$.post("<?php echo base_url(); ?>index.php/profile/get_country/country/<?php echo $result->country; ?>",
		function(data) {
			$("#country").html(data);
		});*/
		
		$.post("<?php echo base_url(); ?>index.php/profile/get_time_zone/zone/<?php echo $result->timezone; ?>",
		function(data) {
			$("#timezone").html(data);
		});
		/*$.post("<?php echo base_url(); ?>index.php/profile/get_State_name/state/<?php echo $result->state; ?>",
		function(data) {
			$("#state").html(data);
		});*/
		/*$.post("<?php echo base_url(); ?>index.php/profile/get_city_name/city/<?php echo $result->city; ?>",
		function(data) {
			$("#city").html(data);
		});*/
		/*$.post("<?php echo base_url(); ?>index.php/profile/get_date_format/date_format/<?php echo $result->date_format; ?>",
		function(data) {
			$("#date_format").html(data);
		});*/
		/*$.post("<?php echo base_url(); ?>index.php/profile/get_time_format/time_format/<?php echo $result->time_format; ?>",
		function(data) {
			$("#time_format").html(data);
		});*/
	/*	$.post("<?php echo base_url(); ?>index.php/profile/get_language/language/<?php echo $result->language; ?>",
		function (data){
			$("#language").html(data);
		});*/
		/*$.post("<?php echo base_url(); ?>index.php/profile/get_currency_format/currency_format/<?php echo $result->currency_format; ?>",
		function(data) {
			$("#currency_format").html(data);
		});*/ 
		$('#in_area_alert').timepicker({
			timeFormat: 'hh:mm:ss',showSecond: false
		});	
		$('#out_area_alert').timepicker({
			timeFormat: 'hh:mm:ss',showSecond: false
		});
		$('#alert_start_time').timepicker({
			timeFormat: <?php echo "'".$js_time_format."'".$ampm; ?>,showSecond: false
		});	
		$('#alert_stop_time').timepicker({
			timeFormat: <?php echo "'".$js_time_format."'".$ampm; ?>,showSecond: false
		});
		$("#alert_start_time").timepicker('setDate', new Date(0,0,0,6));
		$("#alert_stop_time").timepicker('setDate', new Date(0,0,0,21));
		<?php if($result->alert_time == 'any' || $result->alert_time == '' || $result->alert_time == null){ ?>
			$("#alert_me").hide();
		<?php } ?>
		<?php if($this->session->userdata("usertype_id")==1){ ?>
			$("#all_point_setting").NumericOnly();
		<?php } ?>
	});
	function state_change(val)
	{
		$.post("<?php echo base_url(); ?>index.php/profile/get_city/state/"+val,
		function(data) {
			$("#city").html(data);
		});
	}
	function country_change(val)
	{
		$.post("<?php echo base_url(); ?>index.php/profile/get_state/country/"+val,
		function(data) {
			var data = data.split('@#$%');
			$("#state").html(data[0]);
			$("#country_lat").val(data[1]);
			$("#country_lang").val(data[2]);
		});
	}
	function profile_setting_toggle(val)
	{
		if(val==0)
		{
			$("#account_settings_tbl").hide();
			$("#per_txt").css('font-weight', 'bold');
			$("#per_txt").css('background-color', '#D6CFCF');
			$("#acc_txt").css('font-weight', 'normal');
			$("#acc_txt").css('background-color', 'white');
			$("#pesonal_settings_tbl").show();
		}
		else
		{
			$("#pesonal_settings_tbl").hide();
			$("#acc_txt").css("font-weight", "bold");
			$("#acc_txt").css('background-color', '#D6CFCF');
			$("#per_txt").css("font-weight", "normal");
			$("#per_txt").css('background-color', 'white');
			$("#account_settings_tbl").show();
		}
	}
	function alert_toggle(val){
		
		if(val==0){
			$("#alert_me").hide();
		}else{
			$("#alert_me").show();		
		}
	}
	
</script>
<div class="content toggle">
	<?php $this->load->view('dashboard/system_messages'); ?>
	<form class='formtable' id='form_profile'>
	<table border=1 style='float: left;width:170px' id='profile_left_panel'>
		<tr><td id="per"><a  href='JavaScript:void(0);' style="font-weight:bold;background-color:#D6CFCF" id="per_txt" onclick='profile_setting_toggle(0)'><?php echo $this->lang->line("Personal Settings"); ?></a></td></tr>
		<tr><td style="line-height:5px"></td></tr>
		<tr><td id="acc"><a  href='JavaScript:void(0);' id="acc_txt" onclick='profile_setting_toggle(1)'><?php echo $this->lang->line("Account Settings"); ?></a></td></tr>
	</table>
	<table border=1 width='80%' id='pesonal_settings_tbl'>
		<thead>
			<tr><th colspan=2><h4><?php echo $this->lang->line("Personal Settings"); ?></h4></th></tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $this->lang->line("first_name"); ?> *<input type='text'  class='text ui-widget-content ui-corner-all' value='<?php echo $result->first_name; ?>' id='first_name' name='first_name'></td>
				<td><?php echo $this->lang->line("last_name"); ?> *<input type='text'  class='text ui-widget-content ui-corner-all' value='<?php echo $result->last_name; ?>' id="last_name" name='last_name'></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Address"); ?>1<textarea class='textarea ui-widget-content ui-corner-all' id='address' name='address'><?php echo $result->address; ?> </textarea></td>
				<td rowspan=2 align='center' id="user_img_form"><a href='Javascript:void(0)' onclick='profile_div()'>
					</a>
				</td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Address"); ?>2<textarea class='textarea ui-widget-content ui-corner-all'  id='address_2' name='address_2' ><?php echo $result->address_2; ?></textarea></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Country"); ?><select id='country' onchange='country_change(this.value)'  class='text ui-widget-content ui-corner-all' name='country'><?php echo $countryOpt; ?></select><input type="hidden" value='<?php echo $result->country_lati; ?>' id="country_lat" name="country_lati"><input type="hidden" value='<?php echo $result->country_longi; ?>' id="country_lang" name="country_longi"></td>
				<td><?php echo $this->lang->line("State"); ?><select id='state' onchange='state_change(this.value)'  class='text ui-widget-content ui-corner-all' name='state'><?php echo $stateOpt; ?></select></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("City"); ?><select id='city'  class='text ui-widget-content ui-corner-all' name='city'><?php echo $cityOpt; ?></select></td>
				<td><?php echo $this->lang->line("Zip"); ?><input type='text'  class='text ui-widget-content ui-corner-all' id='zip' name='zip' value='<?php echo $result->zip; ?>'></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Phone No"); ?><input type='text' id='phone_number' name='phone_number'  class='text ui-widget-content ui-corner-all' value='<?php echo $result->phone_number ; ?>'></td>
				<td><?php echo $this->lang->line("Mobile_No"); ?> *<input type='text' id='mobile_number' name='mobile_number' class='text ui-widget-content ui-corner-all' value='<?php echo $result->mobile_number ; ?>'></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Fax_No"); ?><input type='text' id='fax_number' name='fax_number' class='text ui-widget-content ui-corner-all' value='<?php echo $result->fax_number ; ?>'></td>
				<td><?php echo $this->lang->line("Email"); ?><input type='text' id='email_address' name='email_address' class='text ui-widget-content ui-corner-all'  value='<?php echo $result->email_address ; ?>'></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("web_address"); ?><input type='text' id='web_address' name='web_address' class='text ui-widget-content ui-corner-all' value='<?php echo $result->web_address; ?>'></td>
				<td><?php echo $this->lang->line("company_name"); ?><input type='text' id='company_name' name='company_name'  class='text ui-widget-content ui-corner-all' value='<?php echo $result->company_name; ?>'></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="sms_alert" id="sms_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->sms_alert == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line('send_sms'); ?></label>
				</td>
				<td><input type="checkbox" name="email_alert" id="email_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->email_alert == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line('send_email'); ?></label>
				</td>
			</tr>
			<tr>
				<td><div style="width:40%;display:inline-block"><input type='radio' name='alert_time' value='any' <?php if($result->alert_time=="" || $result->alert_time==null){ echo "checked='checked'"; }else if($result->alert_time == 'any') echo "checked='checked'"; ?> onClick="alert_toggle(0)"><label><?php echo $this->lang->line("Alert Me At Any Time"); ?></label></div><div style="width:40%;display:inline-block;"><input type='radio' name='alert_time' value='given' onClick="alert_toggle(1)"  <?php if($result->alert_time == 'given') echo "checked='checked'"; ?>><label><?php echo $this->lang->line("Alert Me At Given Time"); ?></label></div><div style="width:20%;display:inline-block;"></div>
				</td>
				<td id="alert_me"><div style="width:50%;display:inline-block"><label><?php echo $this->lang->line("From"); ?></label>&nbsp;:&nbsp;<input type="text" name="alert_start_time" id="alert_start_time" class="date text ui-widget-content ui-corner-all" style="width:50%"/></div><div style="width:50%;display:inline-block"><label><?php echo $this->lang->line("To"); ?></label>&nbsp;:&nbsp;<input type="text" name="alert_stop_time" id="alert_stop_time"  style="width:50%" class="date text ui-widget-content ui-corner-all" /></div></td>
				
			</tr>
			<!--<tr>
				<td><?php echo $this->lang->line("default_dashboard_view"); ?><select id='def_dash_view' class='select ui-widget-content ui-corner-all' name='def_dash_view'><?php echo $def_dash_viewOpt; ?></select></td>
                		<td><?php echo $this->lang->line("network_timeout"); ?><select id='network_timeout' class='select ui-widget-content ui-corner-all' name='network_timeout'><?php echo $timeSpan; ?></select></td>
			</tr>-->
			<tr>
				 <td><?php echo $this->lang->line("network_timeout"); ?><select id='network_timeout' class='select ui-widget-content ui-corner-all' name='network_timeout'><?php echo $timeSpan; ?></select></td>

				<td><!--<?php echo $this->lang->line("default_dashboard_view"); ?>--><select id='def_dash_view' class='select ui-widget-content ui-corner-all' name='def_dash_view' style="visibility: hidden"><?php echo $def_dash_viewOpt; ?></select></td>
          		</tr>
			<tr>
				<td colspan=2 align='center' style='height:47px;padding-top:14px'><input type='button' value='<?php echo $this->lang->line("Save Changes"); ?>' onclick='form_submit("pesonal_settings_tbl")'></td>
			</tr>
		</tbody>
	</table>
	<table border=1 width='80%' id='account_settings_tbl' style='display:none'>
		<thead>
			<tr><th colspan=2><h4><?php echo $this->lang->line("Account Setting"); ?></h4></th></tr>
		</thead>
		<tbody> 
			<tr>
				<td><?php echo $this->lang->line("date_format"); ?> *<select  name='date_format' id='date_format'  class='select ui-widget-content ui-corner-all'><?php echo $date_format_Opt;?></select></td>
				<td><?php echo $this->lang->line("Time Format"); ?> *<select  name='time_format' id='time_format'  class='select ui-widget-content ui-corner-all'><?php echo $time_format_Opt;?></select></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Language"); ?> *<select  name='language' id='language' class='select ui-widget-content ui-corner-all'><?php echo $language_Opt;?></select></td>
				<td><?php echo $this->lang->line("Currency Format"); ?> *<select  name='currency_format' id='currency_format' class='select ui-widget-content ui-corner-all'><?php echo $currency_format_Opt;?></select></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Alert_When_Stop_At_On_Place"); ?> *<br/><?php echo $this->lang->line("hours"); ?> : <select  name='max_stop_time_hour' id='max_stop_time_hour' class='select ui-widget-content ui-corner-all' style="width:25%;"><?php echo $max_stop_time_hour;?></select>&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("minutes"); ?> : <select  name='max_stop_time_minute' id='max_stop_time_minute' class='select ui-widget-content ui-corner-all' style="width:25%;"><?php echo $max_stop_time_minute;?></select><input type="hidden" id="max_stop_time" name="max_stop_time"></td>
				<td><?php echo $this->lang->line("Date-Time Zone"); ?> *<select  name='timezone' id='timezone'  class='select ui-widget-content ui-corner-all'><?php echo $timezoneOpt;?></select></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Alert Timing For Box Open"); ?> *<br/><?php echo $this->lang->line("hours"); ?> : <select  name='alert_box_open_time_hour' id='alert_box_open_time_hour' class='select ui-widget-content ui-corner-all' style="width:25%;"><?php echo $alert_box_time_hour;?></select>&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("minutes"); ?> : <select  name='alert_box_open_time_minute' id='alert_box_open_time_minute' class='select ui-widget-content ui-corner-all' style="width:25%;"><?php echo $alert_box_time_minute;?></select><input type="hidden" id="alert_box_open_time" name="alert_box_open_time"></td>
			</tr>
			<?php if($this->session->userdata("usertype_id")==1){ ?>
			<tr>
				<td><?php echo $this->lang->line("Remove All points data"); ?><br/><input type="text" name='all_point_setting' id='all_point_setting' class='text ui-widget-content ui-corner-all' value="<?php //echo $all_point_setting;?>"/></td>
				<td>&nbsp;</td>
			</tr>
			<?php } ?>
			<tr>
				<td><?php echo $this->lang->line("Server Charges(Per Day)"); ?><br/><input type="text" readonly="readonly" class='text ui-widget-content ui-corner-all' value="<?php echo $charges;?>"/></td>
				<td><?php echo $this->lang->line("Account Expiry Date"); ?><br/><input type="text" readonly="readonly" class='text ui-widget-content ui-corner-all' value="<?php if($expirt_date != "") echo date($date_format, strtotime($expirt_date));?>"/></td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Sms Balance"); ?><br/><input type="text" readonly="readonly" class='text ui-widget-content ui-corner-all' value="<?php echo $sms_balance;?>"/></td>
				<td><input type="checkbox" name="location_with_tag" id="location_with_tag" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->location_with_tag == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line("Location on map with tag ?"); ?></label>
				</td>
			</tr>
			<tr>
				<td><?php echo $this->lang->line("Alert If Ignition On And Vehicle Stop"); ?><br><input type='text'  name='ignition_on_speed_off_minutes' id='ignition_on_speed_off_minutes' class='text ui-widget-content ui-corner-all' style="width:50%;" value='<?php echo $result->ignition_on_speed_off_minutes; ?>'>&nbsp;<?php echo $this->lang->line("minutes"); ?></td>
				<td><?php echo $this->lang->line("Alert If Ignition Off And Vehicle Running"); ?><br><input type='text'  name='ignition_off_speed_on_minutes' id='ignition_off_speed_on_minutes' class='text ui-widget-content ui-corner-all' value='<?php echo $result->ignition_off_speed_on_minutes; ?>' style="width:50%;">&nbsp;<?php echo $this->lang->line("minutes"); ?></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ignition_on_alert" id="ignition_on_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->ignition_on_alert == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line("Ignition On Alert"); ?></label>
				</td>
				<td><input type="checkbox" name="ignition_off_alert" id="ignition_off_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->ignition_off_alert == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line("Ignition Off Alert"); ?></label>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="show_zone_name" id="show_zone_name" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->show_zone_name == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line("Show Zone Name(without mouse over)"); ?></label>
				</td>
				<td><input type="checkbox" name="auto_refresh_setting" id="auto_refresh_setting" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->auto_refresh_setting == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line("Auto Refresh Setting"); ?></label>
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="onscreen_alert" id="onscreen_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($result->onscreen_alert == 1) echo "checked='checked'"; ?> /><label><?php echo $this->lang->line("Show Alert On Screen"); ?></label>
				</td>
			</tr>
			<tr>
				<td colspan=2 align='center' style='height:47px;padding-top:14px'><input type='button' value='<?php echo $this->lang->line("Save Changes"); ?>'  onclick='form_submit("account_settings_tbl")'></td>
			</tr>
		</tbody>
	</table>
	</form>
</div>