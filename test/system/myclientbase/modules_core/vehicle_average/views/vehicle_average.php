<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_vehicle_average_grid
{
	display:none !important; 
}
#ui_tpicker_hour_label_vehicle_average_sdate,#ui_tpicker_hour_label_vehicle_average_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_vehicle_average_sdate,#ui_tpicker_minute_label_vehicle_average_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_vehicle_average_sdate,#ui_tpicker_second_label_fuels_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
</style>
<script type="text/javascript">
var assets_nm;
jQuery().ready(function (){
	jQuery("#vehicle_average_sdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#vehicle_average_edate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#vehicle_average_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/vehicle_average/loadData",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','<?php echo $this->lang->line("Date"); ?>','<?php echo $this->lang->line("Distance"); ?>(KM)', '<?php echo $this->lang->line("Used Fuel"); ?>(Liters)', '<?php echo $this->lang->line("Average"); ?>'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"},
			{name:"date_time",editable:true, index:"date_time", width:250, align:"center", jsonmap:"date_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d",newformat:"<?php echo $date_format; ?>"}},
			{name:"km_run",editable:true, index:"km_run", width:300, align:"center", jsonmap:"km_run"},
			{name:"fuel_liters",editable:true, index:"fuel_liters", width:300, align:"center", jsonmap:"fuel_liters"},
			{name:"average",editable:true, index:"average", width:300, align:"center", jsonmap:"average"},
			
		],
		rowNum:100000,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		//rowList:[10,20,30,50,100],
		//pager: jQuery("#vehicle_average_pager"),
		sortname: "tm.add_date",
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			$("#vehicle_average_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		caption:"<?php echo $this->lang->line("Fuel Reports"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});
	/*
	jQuery("#vehicle_average_grid").jqGrid("navGrid", "#vehicle_average_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#vehicle_average_grid").jqGrid("navButtonAdd","#vehicle_average_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#vehicle_average_sdate').val();
			var edate = $('#vehicle_average_edate').val();
			var device = $('#vehicle_average_device').val();
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+device;
			document.location = "<?php echo base_url(); ?>index.php/reports/stopreport/loaddata"+qrystr;
		}
	});
	*/
	$("#vehicle_average_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#vehicle_average_alert_dialog").dialog("close")',5000);
		}
	});
	$("#vehicle_average_device").html(assets_combo_opt);
	$("#vehicle_average_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#vehicle_average_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
});
function format_vehicle_average_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_vehicle_average_report_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-BLUE1-BLANK.png'></a>";
}
function searchVehicleAverage(){
	var sdate = $('#vehicle_average_sdate').val();
	var edate = $('#vehicle_average_edate').val();
	var device = $('#vehicle_average_device').val();
		
	$("#loading_top").css("display","block");
	assets_nm=$('#vehicle_average_device option:selected').html();
	jQuery("#vehicle_average_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate,  device:device, page:1}}).trigger("reloadGrid");
	return false;
}
function cancel(){
	$('#vehicle_average_frm').htmsl('');
	$('#vehicle_average_list_div').show();
}
$(document).ready(function(){
	
	jQuery("input:button, input:submit, input:reset").button();
});
function view_vehicle_average_map(id,asset){
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
	jQuery("#vehicle_average_grid").jqGrid().stop();
}
</script>
<?php
	$timestamp = strtotime("-2 day");
	$yesterday=date($date_format." ".$time_format,$timestamp);
?>
<div id="vehicle_average_list_div">

<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="vehicle_average_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo $yesterday; ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="vehicle_average_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td><td width="20%"><?php echo $this->lang->line("Assets"); ?> : <select name="device" id="vehicle_average_device" class="select ui-widget-content ui-corner-all" style="width:70% !important"></select></td>
		<td width="10%"><input type="button" onclick="searchVehicleAverage()" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>

<table id="vehicle_average_grid" class="jqgrid"></table>
<div id="vehicle_average_alert_dialog"></div>
<div id="vehicle_average_pager"></div>
</div>
<script type="text/javascript">
window.onbeforeunload = function(event){ event.preventDefault;}
</script>
<script type="text/javascript">
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>