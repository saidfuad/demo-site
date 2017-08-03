<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Nkonnect</title>
<link type="text/css" href="http://vts.nkonnect.com/assets/jquery/ui-themes/redmond/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
<link rel='stylesheet' type='text/css' href='http://vts.nkonnect.com/assets/jqgrid/css/ui.jqgrid.css' />

<script type="text/javascript" src="http://vts.nkonnect.com/assets/jquery/jquery.min.js"></script>
<script type="text/javascript" src="http://vts.nkonnect.com/assets/jquery/jquery.ui.all_min.js"></script>
<script type="text/javascript" src="http://vts.nkonnect.com/assets/jquery/jquery.layout_min.js"></script>
<script type="text/javascript" src="http://vts.nkonnect.com/assets/jquery/jquery-ui.min.js"></script>
  
<script type="text/javascript" src="http://vts.nkonnect.com/assets/jquery-ui-timepicker-addon.js"></script>
<script type='text/javascript' src='http://vts.nkonnect.com/assets/jqgrid/js/i18n/grid.locale-en_min.js'></script>
<script type='text/javascript' src='http://vts.nkonnect.com/assets/jqgrid/js/jquery.jqGrid.src_min.js'></script>
<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
</script>
</HEAD>
<body>

<div id="data_grid_div">
<table id="tdata_ds_list"></table>
</div>
<div id="data_pager"></div>
<?php
	 $date_format = 'd.m.Y';  
	 $time_format = 'H:i:s';  
	 $js_date_format = 'd.m.Y'; 
	 $js_time_format = 'H:i:s';
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,'H:i:s');
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
?>
<script type="text/javascript"> 
jQuery().ready(function (){
jQuery("input:button, input:submit, input:reset").button();
jQuery("#tdata_ds_list").jqGrid({
   	url:'php_row_data.php',
	datatype: "json",
   	colNames:['Id','lati', 'longi', 'add_date', 'speed', 'url_id', 'device_id', 'gps', 'dt', 'tm', 'ignition', 'box_open', 'altitude', 'direction', 'gsm_strength', 'angle_dir', 'power_st', 'acc_st', 'reserved', 'mileage', 'address', 'msg_serial_no', 'reason', 'reason_text', 'command_key', 'command_key_value', 'msg_key', 'odometer', 'sat_mode', 'gsm_register', 'gprs_register', 'server_avail', 'in_batt', 'ext_batt_volt', 'digital_io', 'analog_in_1', 'analog_in_2', 'analog_in_3', 'analog_in_4', 'HW. Ver.', 'SW. Ver.', 'Data Type'],
   	height: "auto",
	colModel:[
   		{name:'id',index:'id',hidden:true, width:15, jsonmap:'id'},
		{name:'lati',editable:true, index:'lati', width:90, align:"center", jsonmap:'lati'},
		{name:'longi',editable:true, index:'longi', width:90, align:"center", jsonmap:'longi'},
		{name:'add_date',editable:true, index:'add_date', width:120, align:"center", jsonmap:'add_date'},
		{name:'speed',editable:true, index:'speed', width:90, align:"center", jsonmap:'speed'},
		{name:'url_id',editable:true, index:'url_id', width:90, align:"center", jsonmap:'url_id'},
		{name:'device_id',editable:true, index:'device_id', width:90, align:"center", jsonmap:'device_id'},
		{name:'gps',editable:true, index:'gps', width:90, align:"center", jsonmap:'gps'},
		{name:'dt',editable:true, index:'dt', width:120, align:"center", jsonmap:'dt'},
		{name:'tm',editable:true, index:'tm', width:90, align:"center", jsonmap:'tm'},
		{name:'ignition',editable:true, index:'ignition', width:90, align:"center", jsonmap:'ignition'},
		{name:'box_open',editable:true, index:'box_open', width:90, align:"center", jsonmap:'box_open'},
		{name:'altitude',editable:true, index:'altitude', width:90, align:"center", jsonmap:'altitude'},
		{name:'direction',editable:true, index:'direction', width:90, align:"center", jsonmap:'direction'},
		{name:'gsm_strength',editable:true, index:'gsm_strength', width:90, align:"center", jsonmap:'gsm_strength'},
		{name:'angle_dir',editable:true, index:'angle_dir', width:90, align:"center", jsonmap:'angle_dir'},
		{name:'power_st',editable:true, index:'power_st', width:90, align:"center", jsonmap:'power_st'},
		{name:'acc_st',editable:true, index:'acc_st', width:90, align:"center", jsonmap:'acc_st'},
		{name:'reserved',editable:true, index:'reserved', width:90, align:"center", jsonmap:'reserved'},
		{name:'mileage',editable:true, index:'mileage', width:90, align:"center", jsonmap:'mileage'},
		{name:'address',editable:true, index:'address', width:90, align:"center", jsonmap:'address'},
		{name:'msg_serial_no',editable:true, index:'msg_serial_no', width:90, align:"center", jsonmap:'msg_serial_no'},
		{name:'reason',editable:true, index:'reason', width:90, align:"center", jsonmap:'reason'},
		{name:'reason_text',editable:true, index:'reason_text', width:90, align:"center", jsonmap:'reason_text'},
		{name:'command_key',editable:true, index:'command_key', width:90, align:"center", jsonmap:'command_key'},
		{name:'command_key_value',editable:true, index:'command_key_value', width:90, align:"center", jsonmap:'command_key_value'},
		{name:'msg_key',editable:true, index:'msg_key', width:90, align:"center", jsonmap:'msg_key'},
		{name:'odometer',editable:true, index:'odometer', width:90, align:"center", jsonmap:'odometer'},
		{name:'sat_mode',editable:true, index:'sat_mode', width:90, align:"center", jsonmap:'sat_mode'},
		{name:'gsm_register',editable:true, index:'gsm_register', width:90, align:"center", jsonmap:'gsm_register'},
		{name:'gprs_register',editable:true, index:'gprs_register', width:90, align:"center", jsonmap:'gprs_register'},
		{name:'server_avail',editable:true, index:'server_avail', width:90, align:"center", jsonmap:'server_avail'},
		{name:'in_batt',editable:true, index:'in_batt', width:90, align:"center", jsonmap:'in_batt'},
   		{name:'ext_batt_volt', editable:true, index:'ext_batt_volt', width:80, align:"center", jsonmap:'ext_batt_volt'},
		{name:'digital_io',editable:true, index:'digital_io', width:90, align:"center", jsonmap:'digital_io'},
		{name:'analog_in_1',editable:true, index:'analog_in_1', width:90, align:"center", jsonmap:'analog_in_1'},
		{name:'analog_in_2',editable:true, index:'analog_in_2', width:90, align:"center", jsonmap:'analog_in_2'},
		{name:'analog_in_3',editable:true, index:'analog_in_3', width:90, align:"center", jsonmap:'analog_in_3'},
		{name:'analog_in_4',editable:true, index:'analog_in_4', width:90, align:"center", jsonmap:'analog_in_4'},
		{name:'hw_version',editable:true, index:'hw_version', width:60, align:"center", jsonmap:'hw_version'},
		{name:'sw_version',editable:true, index:'sw_version', width:60, align:"center", jsonmap:'sw_version'},
		{name:'data_type',editable:true, index:'data_type', width:60, align:"center", jsonmap:'data_type'}
   	],
   	rowNum:20,
	rownumbers: true,
	height: "100%",
   	autowidth: true,
   	shrinkToFit: false,
   	rowList:[10,20,30],
   	pager: '#data_pager',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	jsonReader: { repeatitems : false, id: "0" },
	multiselect: true, 
	toolbar: [true,"top"],
    caption:"Data"
});
jQuery("#tdata_ds_list").jqGrid('navGrid','#data_pager',{edit:false,add:false,del:true,search:false},{},{},{},{multipleSearch:false});

$("#t_tdata_ds_list").append("<div style='font-size:10px;margin-top:5px;margin-left:5px'>Device Id. : <input type='text' id='device_id' style='width:100px'>&nbsp;&nbsp;From :<input type='text' id='sdate' class='date'  style='width:160px'>&nbsp;&nbsp;To : <input type='text' id='edate' class='date' style='width:160px'>&nbsp;&nbsp;<input type='button' id='search' value='View'/>&nbsp;&nbsp;<input type='button' id='export' value='Export'/></div>");

	$("#sdate").datetimepicker({dateFormat:'dd.mm.yy',timeFormat: 'hh:mm:ss TT',ampm:true,changeMonth: true,showSecond: true,changeYear: true});
	$("#edate").datetimepicker({dateFormat:'dd.mm.yy',timeFormat: 'hh:mm:ss TT',ampm:true,changeMonth: true,showSecond: true,changeYear: true});
	
	$("#sdate").datetimepicker('setDate', new Date());
	$("#edate").datetimepicker('setDate', new Date());
	$("#t_tdata_ds_list").css('height', '35px');
	
$("#search").click(function(){
	
	var device_id = $("#device_id").val();
	var sdate = $("#sdate").val();
	var edate = $("#edate").val();
	
	jQuery("#tdata_ds_list").jqGrid('setGridParam',{url:"php_row_data.php"});
	jQuery("#tdata_ds_list").jqGrid('setGridParam',
		{postData:{device_id:device_id, sdate:sdate, edate:edate, page:1}
	}).trigger("reloadGrid");
});

$("#export").click(function(){
	var device_id = $("#device_id").val();
	var sdate = $("#sdate").val();
	var edate = $("#edate").val();
	document.location = "php_row_data.php?cmd=export&device_id="+device_id+"&sdate="+sdate+"&edate="+edate;
});


});

</script>
</body>
</HTML>
