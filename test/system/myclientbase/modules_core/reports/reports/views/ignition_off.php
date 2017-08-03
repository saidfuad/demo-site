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
#load_ignition_off_grid
{
	display:none !important; 
}/*
#ui_tpicker_hour_label_ignition_off_sdate,#ui_tpicker_hour_label_ignition_off_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_ignition_off_sdate,#ui_tpicker_minute_label_ignition_off_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_ignition_off_sdate,#ui_tpicker_second_label_ignition_off_edate
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
	jQuery("#ignition_off_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/ignition_off/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','Ignition Stop Time','Ignition Start Time', '<?php echo $this->lang->line("Duration"); ?>'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"},
			{name:"ignition_stop_time",editable:true, index:"ignition_stop_time", width:200, align:"center", jsonmap:"ignition_stop_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format." ".$time_format; ?>"}},
			{name:"ignition_start_time",editable:true, index:"ignition_start_time", width:200, align:"center", jsonmap:"ignition_start_time",formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format." ".$time_format; ?>"}},
			{name:"duration",editable:true, index:"duration", width:100, align:"center", jsonmap:"duration"}
		],
		rowNum:10,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#ignition_off_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#ignition_off_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Stop Reports"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#ignition_off_grid").jqGrid("navGrid", "#ignition_off_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#ignition_off_grid").jqGrid("navButtonAdd","#ignition_off_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#ignition_off_sdate').val();
			var edate = $('#ignition_off_edate').val();
			var dev="";
			for(i=1;i<=assets_count;i++){
				if($("#ddcl-ignition_off_device-i"+i).is(':checked')){
					dev+=$("#ddcl-ignition_off_device-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/ignition_off/loaddata"+qrystr;
		}
	});
	<?php } ?>
	$("#ignition_off_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#ignition_off_alert_dialog").dialog("close")',5000);
		}
	});
	$("#ignition_off_device").html(assets_combo_opt_report);
	
	$("#ignition_off_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#ignition_off_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});

	$("#ignition_off_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#ignition_off_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	
	
	/*$("#ignition_off_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#ignition_off_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));*/
	//$("#ignition_off_device").dropdownchecklist( {icon: {}, emptyText: "Please Select", width: 150 } );	
	$("#ignition_off_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-ignition_off_device").css('vertical-align','middle');
	$("#ddcl-ignition_off_device-ddw").css('overflow-x','hidden');
	$("#ddcl-ignition_off_device-ddw").css('overflow-y','auto');
	$("#ddcl-ignition_off_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function format_ignition_off_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_ignition_off_report_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-RED-BLANK.png'></a>";
}
function searchignition_off(){
	var sdate = $('#ignition_off_sdate').val();
	var edate = $('#ignition_off_edate').val();

	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-ignition_off_device-i"+i).is(':checked')){
			dev+=$("#ddcl-ignition_off_device-i"+i).val()+",";
		}
	}
	if(dev == ""){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	assets_nm=$('#ignition_off_device option:selected').val();
	jQuery("#ignition_off_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate,  device:dev, page:1}}).trigger("reloadGrid");
	return false;
}
function cancel(){
	$('#ignition_off_frm').htmsl('');
	$('#ignition_off_list_div').show();
}
$(document).ready(function(){	
	jQuery("input:button, input:submit, input:reset").button();
});
function view_ignition_off_report_map(id,asset){
	var nameToCheck = "Stop Report Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			//window.location.href ='reports/ignition_off/view_map/0/id/'+id+'/asset/'+asset;
			$('#tabs').tabs('add', 'reports/ignition_off/view_map/0/id/'+id+'/asset/'+asset, 'Stop Report Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', 'reports/ignition_off/view_map/0/id/'+id+'/asset/'+asset, 'Stop Report Map');
	}
}
function CancelReq()
{
	jQuery("#ignition_off_grid").jqGrid().stop();
}
</script>
<div id="ignition_off_list_div">
<form onsubmit="return searchignition_off()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="ignition_off_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="ignition_off_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" readonly="readonly"/></td><td width="20%" style="vertical-align:middle"><?php echo $this->lang->line("Assets"); ?> : <select name="device_1" id="ignition_off_device" class="select ui-widget-content ui-corner-all" style="width:70% !important" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="ignition_off_grid" class="jqgrid"></table>
<div id="ignition_off_alert_dialog"></div>
<div id="ignition_off_pager"></div>
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