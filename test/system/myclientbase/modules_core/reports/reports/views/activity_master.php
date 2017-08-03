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
	 
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
	 $prefix = time();
	 
?>
<style>
#load_activity_master_grid
{
	display:none !important; 
}
#activity_master_grid_rpt tr,#activity_master_grid_rpt td,#activity_master_grid_rpt th
{
	border: 1px solid; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	
	
	$("#activity_mastersdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#activity_mastersdate_to").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	
	jQuery("input:button, input:submit, input:reset").button();
	
	jQuery("#activity_mastersdate").val("<?php echo date("d.m.Y h:i:s A"); ?>");
	jQuery("#activity_mastersdate_to").val("<?php echo date("d.m.Y h:i:s A"); ?>");
	
	
	
	$("#loading_top").css("display","none");
	$("#activity_master_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#activity_masterdevice").html(assets_combo_opt);	
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	
	
});

function view_activity_master_map(id,asset){
	var nameToCheck = "Activity Report Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/activity_master/view_map/0/id/'+id+'/asset/'+asset, 'Activity Report Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/activity_master/view_map/0/id/'+id+'/asset/'+asset, 'Activity Report Map');
	}
}
function searchactivity_master(type){
	
	var sdate = $('#activity_mastersdate').val();
	var sdate_to = $('#activity_mastersdate_to').val();
	var device = $('#activity_masterdevice').val();
	
	if(device == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	if(type == 2){
		window.location = "<?php echo base_url(); ?>index.php/reports/activity_master/loadData?sdate="+sdate+"&sdate_to="+sdate_to+"&device="+device+"&type="+type;
	}
	$("#loading_top").css("display","block");
	
	$.post("<?php echo base_url(); ?>index.php/reports/activity_master/loadData",{sdate:sdate,sdate_to:sdate_to, device:device},function(data)
	{
		$("#loading_top").css("display","none");
		$('#activity_master_grid_rpt').html(data);
	});
	
	return false;	
}
function cancel(){
	$('#activity_master_frm').html('');
	$('#activity_master_list_div').show();
}

</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="activity_master_list_div">
<form onsubmit="return searchactivity_master(1)">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="20%">From Date : <input type="text" name="sdate" id="activity_mastersdate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="20%">To Date : <input type="text" name="sdate_to" id="activity_mastersdate_to" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		
		<td width="20%"><?php echo $this->lang->line("Assets"); ?> :<select name="device" id="activity_masterdevice" class="select ui-widget-content ui-corner-all"></select></td>
		<td width="5%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
		<td width="5%"><input type="button" value="Export" onclick="searchactivity_master(2)" /></td>
	</tr>
</table>
</form>
<div id="activity_master_grid_rpt" ></div>

<div id="activity_master_alert"></div>
</div>
