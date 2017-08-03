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
<link rel="shortcut icon" href="assets/dashboard/images/nk.png">
<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
</script>
</HEAD>
<body>

<div id="data_grid_div" width='90%'>
<table id="tdata_ds_list" width='90%'></table>
</div>
<div id="data_pager"></div>
<br/><br/>
<div id="ignition_grid_div" width='90%'>
<table id="tignition_ds_list" width='90%'></table>
</div>
<div id="ignition_pager"></div>
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
   	url:'php_device_status.php?cmd=fault',
	datatype: "json",
   	colNames:['ID','Client Name', 'Username', 'Device Down', 'Mobile', 'Email ID'],
   	height: "auto",
	colModel:[
   		{name:'id',index:'user_id',hidden:true, width:15, jsonmap:'user_id'},
		{name:'name',editable:true, index:'name', width:130, align:"center", jsonmap:'name'},
		{name:'username',editable:true, index:'username', width:130, align:"center", jsonmap:'username'},
		{name:'device_down',editable:true, index:'device_down', width:120, align:"center", jsonmap:'device_down'},
		{name:'mobile',editable:true, index:'mobile', width:230, align:"center", jsonmap:'mobile'},
		{name:'email',editable:true, index:'email', width:280, align:"center", jsonmap:'email'}
	],
	rownumbers: true,
	height: 'auto',
   	width: '100%',
   	shrinkToFit: false,
   	pager: '#data_pager',
   	sortname: 'id',
    viewrecords: true,
	rowNum:-1,
    sortorder: "desc",
	jsonReader: { repeatitems : false, id: "0" },
	multiselect: false,
	subGrid : true, 
	subGridRowExpanded: function(subgrid_id, row_id) {
	   var subgrid_table_id;
	   subgrid_table_id = subgrid_id+"_t";
	   jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
	   jQuery("#"+subgrid_table_id).jqGrid({
		  url:'php_device_status.php?cmd=subgrid_fault&id='+row_id,
		  datatype: "json",
		  colNames: ['id', 'Asset Name','Device Id','Close since (Hrs)','Last Recieved Data','Details'],
		  colModel: [
			{ name: 'id', index: 'id', width: 150, align: 'center',hidden:true},
			{ name: 'assets_name', index: 'assets_name', width: 150, align: 'center', jsonmap:'assets_name'},
			{ name: 'device_id', index: 'device_id', width: 150, align: 'center', jsonmap:'device_id'},
			{ name: 'close_since', index: 'close_since', width: 150, align: 'center', jsonmap:'close_since'},
			{ name: 'add_date', index: 'add_date', width: 150, align: 'center', jsonmap:'add_date'},
			{ name: 'details', index: 'details', width: 150, align: 'center', jsonmap:'details'},
		  ],
			rownumbers: true,  
			height: 'auto', 
			sortname: 'id',
			sortorder: "asc",
			viewrecords: true,
			rowNum:-1,
			jsonReader: { repeatitems : false, id: "0" },
	  });	 },
	 
	caption:"Device Down from Last 30 Minutes"
});
jQuery("#tdata_ds_list").jqGrid('navGrid','#data_pager',{edit:false,add:false,del:false,search:false},{},{},{},{multipleSearch:false});

jQuery("#tdata_ds_list").jqGrid("navButtonAdd","#data_pager",{caption:"Export",
	onClickButton:function(){
		var qrystr ="?cmd=export_fault";
		document.location = "php_device_status.php"+qrystr;
	}
});

jQuery("#tignition_ds_list").jqGrid({
   	url:'php_device_status.php?cmd=ignition',
	datatype: "json",
   	colNames:['ID','Client Name', 'Username', 'Ignition Fault', 'Mobile', 'Email ID'],
   	height: "auto",
	colModel:[
   		{name:'id',index:'user_id',hidden:true, width:15, jsonmap:'user_id'},
		{name:'name',editable:true, index:'name', width:130, align:"center", jsonmap:'name'},
		{name:'username',editable:true, index:'username', width:130, align:"center", jsonmap:'username'},
		{name:'device_down',editable:true, index:'device_down', width:120, align:"center", jsonmap:'device_down'},
		{name:'mobile',editable:true, index:'mobile', width:230, align:"center", jsonmap:'mobile'},
		{name:'email',editable:true, index:'email', width:280, align:"center", jsonmap:'email'}
	],
	rownumbers: true,
	height: 'auto',
   	width: '100%',
   	shrinkToFit: false,
   	pager: '#ignition_pager',
   	sortname: 'id',
    viewrecords: true,
	rowNum:-1,
    sortorder: "desc",
	jsonReader: { repeatitems : false, id: "0" },
	multiselect: false,
	subGrid : true, 
	subGridRowExpanded: function(subgrid_id, row_id) {
	   var subgrid_table_id;
	   subgrid_table_id = subgrid_id+"_t";
	   jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
	   jQuery("#"+subgrid_table_id).jqGrid({
		  url:'php_device_status.php?cmd=subgrid_ignition&id='+row_id,
		  datatype: "json",
		  colNames: ['id', 'Asset Name','Device Id','Ignition','Speed'],
		  colModel: [
			{ name: 'id', index: 'id', width: 150, align: 'center',hidden:true},
			{ name: 'assets_name', index: 'assets_name', width: 150, align: 'center', jsonmap:'assets_name'},
			{ name: 'device_id', index: 'device_id', width: 150, align: 'center', jsonmap:'device_id'},
			{ name: 'ignition', index: 'ignition', width: 150, align: 'center', jsonmap:'ignition'},
			{ name: 'speed', index: 'speed', width: 150, align: 'center', jsonmap:'speed'}		
		  ],
			rownumbers: true,  
			height: 'auto', 
			sortname: 'id',
			sortorder: "asc",
			viewrecords: true,
			rowNum:-1,
			jsonReader: { repeatitems : false, id: "0" },
	  });	 },
	 
	caption:"Vehicles which are running and ignition 0 (Ignition Fault)"
});
jQuery("#tignition_ds_list").jqGrid('navGrid','#ignition_pager',{edit:false,add:false,del:false,search:false},{},{},{},{multipleSearch:false});

jQuery("#tignition_ds_list").jqGrid("navButtonAdd","#ignition_pager",{caption:"Export",
	onClickButton:function(){
		var qrystr ="?cmd=export_ignition";
		document.location = "php_device_status.php"+qrystr;
	}
});

$("#search").click(function(){
	
	var device_id = $("#device_id").val();
	var sdate = $("#sdate").val();
	var edate = $("#edate").val();
	
	jQuery("#tdata_ds_list").jqGrid('setGridParam',{url:"php_device_status.php"});
	jQuery("#tdata_ds_list").jqGrid('setGridParam',
		{postData:{device_id:device_id, sdate:sdate, edate:edate, page:1}
	}).trigger("reloadGrid");
});

});

</script>
</body>
</HTML>
