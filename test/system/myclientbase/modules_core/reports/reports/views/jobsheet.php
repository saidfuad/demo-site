<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Export");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'7');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}	
	}
	
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
	 
?>
<style>
#load_jobsheet_grid
{
	display:none !important; 
}
#jobsheet_grid_rpt tr,#jobsheet_grid_rpt td,#jobsheet_grid_rpt th
{
	border: 1px solid; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	
	jQuery("#jobsheetsdate").datepicker({
		dateFormat:"MM yy",
		changeMonth: true,
		changeYear: true,
		onClose: function(dateText, inst) { 
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
		},
		beforeShow: function() {
			   if ((selDate = $(this).val()).length > 0) 
			   {
				  iYear = selDate.substring(selDate.length - 4, selDate.length);
				  iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
						   $(this).datepicker('option', 'monthNames'));
				  $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
				  $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			   }
			}
	});
	jQuery("input:button, input:submit, input:reset").button();
	
	jQuery("#jobsheetsdate").val("<?php echo date("M Y"); ?>");
	
	$("#loading_top").css("display","none");
	$("#jobsheet_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#jobsheetdevice").html(assets_combo_opt);	
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	
	
});
function searchjobsheet(type){
	
	var sdate = $('#jobsheetsdate').val();
	var device = $('#jobsheetdevice').val();
	
	if(device == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	if(type == 2){
		window.location = "<?php echo base_url(); ?>index.php/reports/jobsheet/loadData?sdate="+sdate+"&device="+device+"&type="+type;
	}
	$("#loading_top").css("display","block");
	
	$.post("<?php echo base_url(); ?>index.php/reports/jobsheet/loadData",{sdate:sdate, device:device},function(data)
	{
		$("#loading_top").css("display","none");
		$('#jobsheet_grid_rpt').html(data);
	});
	
	return false;	
}
function cancel(){
	$('#jobsheet_frm').html('');
	$('#jobsheet_list_div').show();
}

</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="jobsheet_list_div">
<form onsubmit="return searchjobsheet(1)">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="20%">Select Month - Year : <input type="text" name="sdate" id="jobsheetsdate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="jobsheetdevice" class="select ui-widget-content ui-corner-all"></select></td>
		<td width="5%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
		<td width="5%"><input type="button" value="Export" onclick="searchjobsheet(2)" /></td>
	</tr>
</table>
</form>
<div id="jobsheet_grid_rpt" ></div>

<div id="jobsheet_alert"></div>
</div>
