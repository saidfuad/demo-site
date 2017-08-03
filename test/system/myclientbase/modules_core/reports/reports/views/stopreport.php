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
		$va1l->where("menu_id",'6');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	

?>
<?php
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
?>
<style>
#load_stopreport_grid
{
	display:none !important; 
}/*
#ui_tpicker_hour_label_stop_sdate,#ui_tpicker_hour_label_stop_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_stop_sdate,#ui_tpicker_minute_label_stop_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_stop_sdate,#ui_tpicker_second_label_stop_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}*/
dt
{
	width:auto !important
}
</style>

<script type="text/javascript">
loadMultiSelectDropDown();
var assets_nm;
jQuery().ready(function (){

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#stopreport_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/stopreport/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','<?php echo $this->lang->line("End_Date_Time"); ?>','<?php echo $this->lang->line("Start_Date_Time"); ?>', '<?php echo $this->lang->line("Duration"); ?>', '<?php echo $this->lang->line("Location"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>','Latitude','Longitude'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:120, align:"center", jsonmap:"assets_name"},
			{name:"ignition_off",editable:true, index:"ignition_off", width:150, align:"center", jsonmap:"ignition_off", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format." ".$time_format; ?>"}},
			//{name:"ignition_off",editable:true, index:"ignition_off", width:150, align:"center", jsonmap:"ignition_off", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $time_format; ?>"}},
			{name:"ignition_on",editable:true, index:"ignition_on", width:150, align:"center", jsonmap:"ignition_on",formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format." ".$time_format; ?>"}},
			{name:"duration",editable:true, index:"duration", width:100, align:"center", jsonmap:"duration"},
			{name:"address",editable:true, index:"address", width:150, align:"center", jsonmap:"address"},
			{name:"map",editable:true, index:"map", width:60, align:"center", jsonmap:"map",formatter:format_stop_map},
			{name:"lat",editable:true, index:"lat", width:130, align:"center", jsonmap:"lat"},
			{name:"lng",editable:true, index:"lng", width:130, align:"center", jsonmap:"lng"}
		],
		rowNum:10,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#stopreport_pager"),
		sortname: "id",
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			$("#stopreport_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Stop Reports"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#stopreport_grid").jqGrid("navGrid", "#stopreport_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#stopreport_grid").jqGrid("navButtonAdd","#stopreport_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#stop_sdate').val();
			var edate = $('#stop_edate').val();
			var group = $("#group_device_stopreport").val();
			var stop_minute = $("#stop_minute").val();
			//var device = $('#stop_reports_device').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-stop_reports_device-i"+i).is(':checked')){
					dev+=$("#ddcl-stop_reports_device-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&group="+group+"&stop_minute="+stop_minute+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/stopreport/loaddata"+qrystr;
		}
	});
	<?php } ?>
	$("#stopreport_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#stopreport_alert_dialog").dialog("close")',5000);
		}
	});
	$("#stop_reports_device").html(assets_combo_opt_report);
	
	$("#stop_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#stop_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});

	$("#stop_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#stop_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	
	
	/*$("#stop_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#stop_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));*/
	//$("#stop_reports_device").dropdownchecklist( {icon: {}, emptyText: "Please Select", width: 150 } );	
	$("#stop_reports_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-stop_reports_device").css('vertical-align','middle');
	$("#ddcl-stop_reports_device-ddw").css('overflow-x','hidden');
	$("#ddcl-stop_reports_device-ddw").css('overflow-y','auto');
	$("#ddcl-stop_reports_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function format_stop_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_stop_report_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-RED-BLANK.png'></a>";
}
function searchstopreport(){
	var sdate = $('#stop_sdate').val();
	var edate = $('#stop_edate').val();
	var group = $("#group_device_stopreport").val();
	var stop_minute = $("#stop_minute").val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-stop_reports_device-i"+i).is(':checked')){
			dev+=$("#ddcl-stop_reports_device-i"+i).val()+",";
		}
	}
	if(dev == ""){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	assets_nm=$('#stop_reports_device option:selected').val();
	jQuery("#stopreport_grid").jqGrid('setGridParam',{postData:{stop_minute:stop_minute, sdate:sdate,edate:edate, group:group, device:dev, page:1}}).trigger("reloadGrid");
	return false;
}
function cancel(){
	$('#stopreport_frm').htmsl('');
	$('#stopreport_list_div').show();
}
$(document).ready(function(){	
	jQuery("input:button, input:submit, input:reset").button();
});
function view_stop_report_map(id,asset){
	var nameToCheck = "Stop Report Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/stopreport/view_map/0/id/'+id+'/asset/'+asset, 'Stop Report Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/stopreport/view_map/0/id/'+id+'/asset/'+asset, 'Stop Report Map');
	}
}
function CancelReq()
{
	jQuery("#stopreport_grid").jqGrid().stop();
}
</script>
<div id="stopreport_list_div">
<form onsubmit="return searchstopreport()">
<table width="100%" class="formtable" style="margin-bottom: 5px;"  cellspacing="4" cellpadding="4">
	<tr>
		<td><?php echo $this->lang->line("Start"); ?> : <br><input type="text" name="sdate" id="stop_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" readonly="readonly"/></td>
		<td><?php echo $this->lang->line("End"); ?> : <br><input type="text" name="edate" id="stop_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" readonly="readonly"/></td>
		<td>Minute : <br><input type="text" name="stop_minute" id="stop_minute" class="text ui-widget-content ui-corner-all" style="width:50px"/></td>
		<td>Group : <br><select onchange="filterAssetsCombo(this.value,'stop_reports_device')" name="group" id="group_device_stopreport" class="select ui-widget-content ui-corner-all" ><?php echo $group; ?></select></td>
		<td style="vertical-align:middle"><?php echo $this->lang->line("Assets"); ?> : <br><select name="device_1" id="stop_reports_device" class="select ui-widget-content ui-corner-all" style="width:70% !important" multiple='multiple'></select></td>		
		<td><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="stopreport_grid" class="jqgrid"></table>
<div id="stopreport_alert_dialog"></div>
<div id="stopreport_pager"></div>
</div>
<script type="text/javascript">
//window.onbeforeunload = function(event){ event.preventDefault;}
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