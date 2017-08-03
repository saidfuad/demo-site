<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_lastpoint_grid
{
	display:none !important; 
}
#distanceBtn_grid
{
		display:none; 
}
</style>
<script type="text/javascript">
var rowColorRecharge=new Array();
var borderCrossSpeed=new Array();
var rowsToColor = [];
jQuery().ready(function (){
	

	jQuery("input:button, input:submit, input:reset").button();
	
	var txt = $('#srcTxt').val();
	if(txt == "Search Assets..."){
		txt = "";
	}
	var report = $('#optdetail').val();
	var data_query="";
	var all=5000;
	jQuery("#lastpoint_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/lastpoint/loadData",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'', '<?php echo $this->lang->line("Datetime"); ?>','<?php echo $this->lang->line("Asset_Name"); ?>', '<?php echo $this->lang->line("Address"); ?>', '<?php echo $this->lang->line("Speed_KM"); ?>','Parked From','<?php echo $this->lang->line("Status"); ?>','<?php echo $this->lang->line("Before"); ?>','<?php echo $this->lang->line("Before"); ?>', '<?php echo $this->lang->line("In_Area"); ?>', '<?php echo $this->lang->line("Near Landmark"); ?>','<?php echo $this->lang->line("Speed Limit Cross"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_id",index:"assets_id", width:15, jsonmap:"assets_id", formatter:format_chkd},
			{name:"tlp.add_date",editable:true, index:"tlp.add_date", width:140, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"am.assets_name",editable:true, index:"am.assets_name", width:120, align:"center", jsonmap:"assets_name"},
			{name:"tlp.address",editable:true, index:"tlp.address", width:220, align:"center", jsonmap:"address"},
			/*
			{name:"tlp.lati",editable:true, index:"tlp.lati", width:50, align:"center", jsonmap:"lati"},
			{name:"tlp.longi",editable:true, index:"tlp.longi", width:50, align:"center", jsonmap:"longi"},
			*/
			{name:"speed",editable:true, index:"tlp.speed", width:80, align:"center", jsonmap:"speed"},
			{name:"stop_from",editable:true, index:"stop_from", width:80, align:"center", jsonmap:"stop_from"},
			{name:"status",editable:true, index:"beforeTime", width:100, align:"center", jsonmap:"beforeTime",formatter:formatStatus},
			{name:"received_time", editable:true, index:"beforeTime", width:100, align:"center", jsonmap:"received_time"},
			{name:"beforeTime", hidden:true, index:"beforeTime", width:15, jsonmap:"beforeTime", formatter:formatColor},
			{name:"in_area",editable:true, index:"in_area", width:90, align:"center", jsonmap:"in_area"},
			{name:"near_landmark",editable:true, index:"near_landmark", width:105, align:"center", jsonmap:"near_landmark"},
			{name:"cross_speed", index:"cross_speed", width:15, hidden:true, jsonmap:"cross_speed",formatter:changeCrossSpeed},
			{name:"map",editable:true, index:"map", width:100, align:"center", jsonmap:"map",formatter:format_map},
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,999],
		pager: jQuery("#lastpoint_pager"),
		sortname: "am.assets_name",
		viewrecords: true,
		loadComplete: function(data) {
			$("#loading_top").css("display","none");
			var json = $('#lastpoint_grid').getGridParam('userData');
			if(json.running!="")
			{
				$("#assets_running_1").html(json.running);
			}
			if(json.parked!="")
			{
				$("#assets_parked_1").html(json.parked);
			}
			if(json.out_of_network!="")
			{
				$("#assets_out_1").html(json.out_of_network);
			}
			if(json.device_fault!="")
			{
				$("#assets_fault_1").html(json.device_fault);
			}
			if(json.total!="")
			{
				$("#assets_total_1").html(json.total);
			}
			//$("#distanceBtn_grid").hide();
			getSelectJGridChkd();
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Last Point List"); ?>",
		gridComplete: changeRow,
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" },
		postData: {"report":report, "txt":txt},
	
	});

	jQuery("#lastpoint_grid").jqGrid("navGrid", "#lastpoint_pager", {add:false, edit:false, del:false, search:false	}, {}, {}, {}, {multipleSearch:false});
	$("#lastpoint_pager option[value=999]").text('All');
	$("#lastpoint_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#lastpoint_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});
	
	jQuery("#lastpoint_grid").jqGrid("navButtonAdd","#lastpoint_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
		var txt = $('#srcTxt').val();
		var txtlast = $('#srcTxt').val().lastIndexOf("(");
		if(txtlast!=-1)
		{
			txt = txt.substring(0,txtlast);
		}
		if(txt == "Search Assets..."){
			txt = "";
		}
		var report = $('#optdetail').val();

		var qrystr ="/export?txt="+txt+"&report="+report;
			document.location = "<?php echo base_url(); ?>index.php/reports/lastpoint/loadData"+qrystr;
		}
	});
	<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
	jQuery("#lastpoint_grid").jqGrid("navButtonAdd","#lastpoint_pager",{caption:"<?php echo $this->lang->line("Distance"); ?>",id:"distanceBtn_grid",
		onClickButton:function(){
				getAssetsDistance();
		}
	});
	<?php } ?>
	
	$("#lastpoint_pager .ui-pg-selbox option[value='"+grid_paging+"']").attr("selected","selected");
	
});

function changeCrossSpeed(cellValue, options, rowObject) {
	if (cellValue == 1)
	{
		borderCrossSpeed.push(rowObject.id);
	}    
}

function rowColorFormatter(cellValue, options, rowObject) {
	//alert(cellValue);
        if (cellValue == "Server")
            rowsToColor[rowsToColor.length] = options.rowId;
        return cellValue;
}
function format_chkd(cellValue, options, rowObject)
{	
	var minutes = Math.floor(rowObject.beforeTime/60);
	var class_nm="";
	if(minutes <= 20 && rowObject.speed > 0){
		class_nm = "running_asts";
	}
	else if(minutes <= 20  && rowObject.speed == 0){
		class_nm = "parked_asts";
	}else if(minutes > 1440){
		class_nm = "device_fault_asts";
	}else{
		class_nm = "out_of_network_asts";
	}
	if(rowObject.beforeTime=="" || rowObject.beforeTime==null)
		class_nm = "device_fault_asts";
	return "<input onclick='selectedAssets("+rowObject.assets_id+");' name='assets_check[]' value='"+rowObject.assets_id+"' type='checkbox' style='vertical-align:middle' class='"+class_nm+"'/>";
}
function changeRow(){
	var ids = jQuery("#lastpoint_grid").jqGrid('getDataIDs');
	
	for (i=0; i<ids.length; i++) {
		// alert(ids[i] + " : " + rowColorRecharge[i]);
		//document.getElementById('lastpoint_grid').getElementsByTagName('tr')[i].style.color=rowColorRecharge[ids[i]];
		$("#lastpoint_grid tr#"+ids[i]).css("color",rowColorRecharge[ids[i]]);
	}
/*	for(i=0;i<borderCrossSpeed.length;i++)
	{
		$("#lastpoint_grid tr#"+borderCrossSpeed[i]).css("border","2px solid red");
	
	}*/
}
function formatStatus(cellValue, options, rowObject)
{
	var minutes = Math.floor(cellValue/60);
	var vehicleStatus="";
	if(minutes <= 20 && rowObject.speed > 0){
		vehicleStatus = "Running";
	}
	else if(minutes <= 20  && rowObject.speed == 0){
		vehicleStatus = "Parked";
	}else if(minutes > 1440){
		vehicleStatus = "Device Fault";
	}else{
		vehicleStatus = "Out Of Network";
	}
	if(cellValue=="" || cellValue==null)
		vehicleStatus = "Device Fault";
	return vehicleStatus;
}
function formatColor(cellValue, options, rowObject) {
	
	var color;
	intRowRecharge = rowObject.id;
	var minutes = Math.floor(cellValue/60);
			if(minutes > 20){
				color = "red";
			}else{
				color = "green";
			}
			if(cellValue=="" || cellValue==null || cellValue==undefined)
				color = "red";
	rowColorRecharge[intRowRecharge]=color;
	var re= /<\S[^><]*>/g;
	if(cellValue == undefined)
		cellValue = "";
	else
		var cellValue = cellValue.replace(re, "");
	var cellHtml = "<span style='color:" + color + "' originalValue='" + cellValue + "'>" + cellValue + "</span>";
	return cellHtml;
}
function searchlastpoint(){
	
	var sdate = $('#sdate').val();
	var edate = $('#edate').val();
	var device = $('#device').val();
	
	//$("#lastpoint_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");
	jQuery("#lastpoint_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:device, page:1}}).trigger("reloadGrid");
	
	return false;	
}
function format_map(cellVal, options, rowObject){
	var html="Date : "+rowObject.add_date+"<br>Speed : "+rowObject.speed+"<br>Address : "+rowObject.address+"<br>";
	return "<a href='#' onclick='directTab("+rowObject.device_id+","+rowObject.assets_id+")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-RED-BLANK.png'></a>";
}
function view_map(lat,lang,html,device){
	$('#tabs').tabs('add', "live/device/window/current/id/"+device, '<?php echo $this->lang->line("View_on_Map"); ?>', 1);
	viewLocation(lat,lang,html);
}
function cancel(){ 
	$('#lastpoint_frm').html('');
	$('#lastpoint_list_div').show();
}
$(document).ready(function() {

	$("#attendanceDivAjaxMsg").dialog({
		autoOpen: false,
		height: 120,
		draggable: false,
		resizable: false,
		modal: true,
		close : function(){}
	});
	
});

</script>
<div id="attendanceDivAjaxMsg" style="display:none"></div>
<div id="lastpoint_list_div">

<table id="lastpoint_grid" class="jqgrid"></table>

<div id="lastpoint_pager"></div>

</div>
<span style="font-size: 12px;font-weight: bold;margin-top:5px;display:block"><?php echo $this->lang->line("Last Data Recieved")." : "; echo date("$date_format $time_format"); ?></span> 
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