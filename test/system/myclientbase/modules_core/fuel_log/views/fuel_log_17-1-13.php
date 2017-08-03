<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_stopreport_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var assets_nm;
jQuery().ready(function (){
	jQuery("#fuel_sdate").datetimepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#fuel_edate").datetimepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#stopreport_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/fuel_log/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','<?php echo $this->lang->line("Datetime"); ?>','Fuel Reading', 'Fuel Percentage'],
		//,'<?php echo $this->lang->line("Start_Time"); ?>', '<?php echo $this->lang->line("Location"); ?>', '<?php echo $this->lang->line("Duration"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>'
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"},
			{name:"date_time",editable:true, index:"date_time", width:250, align:"center", jsonmap:"date_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"fuel_reading",editable:true, index:"fuel_reading", width:300, align:"center", jsonmap:"fuel_reading"},
			{name:"fuel_percent",editable:true, index:"fuel_percent", width:300, align:"center", jsonmap:"fuel_percent"}
			/*{name:"map",editable:true, index:"map", width:100, align:"center", jsonmap:"map",formatter:format_fuel_map},*/
		],
		rowNum:100000,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		//rowList:[10,20,30,50,100],
		//pager: jQuery("#stopreport_pager"),
		sortname: "tm.add_date",
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			$("#stopreport_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		caption:"<?php echo $this->lang->line("Stop Reports"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	//jQuery("#stopreport_grid").jqGrid("navGrid", "#stopreport_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#stopreport_grid").jqGrid("navButtonAdd","#stopreport_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#fuel_sdate').val();
			var edate = $('#fuel_edate').val();
			var device = $('#fuel_reports_device').val();
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+device;
			document.location = "<?php echo base_url(); ?>index.php/reports/stopreport/loaddata"+qrystr;
		}
	});
	$("#stopreport_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#stopreport_alert_dialog").dialog("close")',5000);
		}
	});
	$("#fuel_reports_device").html(assets_combo_opt);
	$("#fuel_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#fuel_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
});
function format_fuel_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_fuel_report_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-RED-BLANK.png'></a>";
}
function searchstopreport(){
	var sdate = $('#fuel_sdate').val();
	var edate = $('#fuel_edate').val();
	var device = $('#fuel_reports_device').val();
	var fuel_status = $('#fuel_status').val();
	/*if(device=="")
	{
		$("#stopreport_alert_dialog").html("<?php echo $this->lang->line("Please select device"); ?>");
		$("#stopreport_alert_dialog").dialog("open");
		return false;
	}*/
	$("#loading_top").css("display","block");
	assets_nm=$('#fuel_reports_device option:selected').html();
	jQuery("#stopreport_grid").jqGrid('setGridParam',{postData:{fuel_status:fuel_status, sdate:sdate,edate:edate,  device:device, page:1}}).trigger("reloadGrid");
	return false;
}
function cancel(){
	$('#stopreport_frm').htmsl('');
	$('#stopreport_list_div').show();
}
$(document).ready(function(){
	
	jQuery("input:button, input:submit, input:reset").button();
});
function view_fuel_report_map(id,asset){
	var nameToCheck = "Stop Report Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			//window.location.href ='reports/stopreport/view_map/0/id/'+id+'/asset/'+asset;
			$('#tabs').tabs('add', 'reports/stopreport/view_map/0/id/'+id+'/asset/'+asset, 'Stop Report Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', 'reports/stopreport/view_map/0/id/'+id+'/asset/'+asset, 'Stop Report Map');
	}	
}
function CancelReq()
{
	jQuery("#stopreport_grid").jqGrid().stop();
}
</script>
<?php
	$timestamp = strtotime("-2 day");
	$yesterday=date($date_format." ".$time_format,$timestamp);
?>
<div id="stopreport_list_div">
<form onsubmit="return searchstopreport()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="fuel_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo $yesterday; ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="fuel_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td><td width="20%"><?php echo $this->lang->line("Assets"); ?> : <select name="device" id="fuel_reports_device" class="select ui-widget-content ui-corner-all" style="width:70% !important"></select></td>
		<td width="20%">Status : <select name="fuel_status" id="fuel_status" class="select ui-widget-content ui-corner-all" style="width:70% !important"><option value=''>All</option><option value='1'>Fuel Filled</option></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="stopreport_grid" class="jqgrid"></table>
<div id="stopreport_alert_dialog"></div>
<div id="stopreport_pager"></div>
</div>
<script type="text/javascript">
window.onbeforeunload = function(event){ event.preventDefault;}
</script>


