<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_fuel_report_grid
{
	display:none !important; 
}
#ui_tpicker_hour_label_fuel_rpt_sdate,#ui_tpicker_hour_label_fuel_rpt_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_fuel_rpt_sdate,#ui_tpicker_minute_label_fuel_rpt_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_fuel_rpt_sdate,#ui_tpicker_second_label_fuels_edate
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
	jQuery("#fuel_rpt_sdate").datetimepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#fuel_rpt_edate").datetimepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#fuel_report_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/fuel_report/loadData",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','<?php echo $this->lang->line("Datetime"); ?>','Distance(KM)', 'Used Fuel Percentage', 'Used Fuel Liters'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"},
			{name:"date_time",editable:true, index:"date_time", width:250, align:"center", jsonmap:"date_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"km_run",editable:true, index:"km_run", width:300, align:"center", jsonmap:"km_run"},
			{name:"fuel_percent",editable:true, index:"fuel_percent", width:300, align:"center", jsonmap:"fuel_percent"},
			{name:"fuel_liters",editable:true, index:"fuel_liters", width:300, align:"center", jsonmap:"fuel_liters"}
			
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#fuel_report_pager"),
		sortname: "tm.add_date",
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			$("#fuel_report_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		//footerrow : true, 
		//userDataOnFooter : true,
		caption:"<?php echo "Fuel Reports"; ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#fuel_report_grid").jqGrid("navGrid", "#fuel_report_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#fuel_report_grid").jqGrid("navButtonAdd","#fuel_report_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#fuel_rpt_sdate').val();
			var edate = $('#fuel_rpt_edate').val();
			var device = $('#fuel_rpt_device').val();
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+device;
			document.location = "<?php echo base_url(); ?>index.php/reports/stopreport/loaddata"+qrystr;
		}
	});
	$("#fuel_report_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#fuel_report_alert_dialog").dialog("close")',5000);
		}
	});
	$("#fuel_rpt_device").html(assets_combo_opt);
	$("#fuel_rpt_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#fuel_rpt_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
});
function format_fuel_rpt_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_fuel_rpt_report_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-RED-BLANK.png'></a>";
}
function searchfuelreport(){
	var sdate = $('#fuel_rpt_sdate').val();
	var edate = $('#fuel_rpt_edate').val();
	var device = $('#fuel_rpt_device').val();
	var fuel_rpt_status = $('#fuel_rpt_status').val();
	
	$("#loading_top").css("display","block");
	assets_nm=$('#fuel_rpt_device option:selected').html();
	jQuery("#fuel_report_grid").jqGrid('setGridParam',{postData:{fuel_status:fuel_rpt_status, sdate:sdate,edate:edate,  device:device, page:1}}).trigger("reloadGrid");
	
	$.post("<?php echo base_url(); ?>fuel_filled.php",
	{from:sdate, to:edate, assets_id:device},
	 function(data) {	
		$("#fuel_filled_data").html(data);
	});

	return false;
}
function cancel(){
	$('#fuel_report_frm').htmsl('');
	$('#fuel_report_list_div').show();
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
	jQuery("#fuel_report_grid").jqGrid().stop();
}
</script>
<?php
	$timestamp = strtotime("-2 day");
	$yesterday=date($date_format." ".$time_format,$timestamp);
?>
<div id="fuel_report_list_div">

<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="fuel_rpt_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo $yesterday; ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="fuel_rpt_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td><td width="20%"><?php echo $this->lang->line("Assets"); ?> : <select name="device" id="fuel_rpt_device" class="select ui-widget-content ui-corner-all" style="width:70% !important"></select></td>
		<td width="20%"><?php echo $this->lang->line("Status"); ?> : <select name="fuel_status" id="fuel_rpt_status" class="select ui-widget-content ui-corner-all" style="width:70% !important"><option value=''>All</option><option value='1'>Fuel Filled</option><option value='2'>Fuel Used</option></select></td>
		<td width="10%"><input type="button" onclick="searchfuelreport()" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
<div id="fuel_filled_data"></div>
<table id="fuel_report_grid" class="jqgrid"></table>
<div id="fuel_report_alert_dialog"></div>
<div id="fuel_report_pager"></div>
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