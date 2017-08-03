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
		$va1l->where("menu_id",'81');
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
#load_dealer_stopreport_grid
{
	display:none !important; 
}
#ui_tpicker_hour_label_dealer_stop_sdate,#ui_tpicker_hour_label_dealer_stop_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_dealer_stop_sdate,#ui_tpicker_minute_label_dealer_stop_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_dealer_stop_sdate,#ui_tpicker_second_label_dealer_stop_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
var assets_nm;
jQuery().ready(function (){
	jQuery("#dealer_stop_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	jQuery("#dealer_stop_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#dealer_stopreport_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/dealer_stopreport/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','<?php echo $this->lang->line("stop_date"); ?>','<?php echo $this->lang->line("stop_time"); ?>','<?php echo $this->lang->line("Start_Time"); ?>', '<?php echo $this->lang->line("Location"); ?>', '<?php echo $this->lang->line("Duration"); ?>', '<?php echo $this->lang->line("Area"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"},
			{name:"ignition_off",editable:true, index:"ignition_off", width:150, align:"center", jsonmap:"ignition_off", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}},
			{name:"ignition_off",editable:true, index:"ignition_off", width:150, align:"center", jsonmap:"ignition_off", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $time_format; ?>"}},
			{name:"ignition_on",editable:true, index:"ignition_on", width:150, align:"center", jsonmap:"ignition_on",formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $time_format; ?>"}},
			{name:"address",editable:true, index:"address", width:300, align:"center", jsonmap:"address"},
			{name:"duration",editable:true, index:"duration", width:100, align:"center", jsonmap:"duration"},
			{name:"current_area",editable:true, index:"current_area", width:100, align:"center", jsonmap:"current_area"},
			{name:"map",editable:true, index:"map", width:100, align:"center", jsonmap:"map",formatter:format_dealer_stop_map},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#dealer_stopreport_pager"),
		sortname: "id",
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			$("#dealer_stopreport_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Dealer Stop Report"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#dealer_stopreport_grid").jqGrid("navGrid", "#dealer_stopreport_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#dealer_stopreport_grid").jqGrid("navButtonAdd","#dealer_stopreport_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#dealer_stop_sdate').val();
			var edate = $('#dealer_stop_edate').val();
			//var device = $('#dealer_stop_reports_device').val();
			var dev="";
			for(i=0;i<assets_count;i++){
				if($("#ddcl-device_rfid-i"+i).is(':checked')){
					dev+=$("#ddcl-device_rfid-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}		
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/dealer_stopreport/loaddata"+qrystr;
		}
	});
	<?php } ?>
	$("#dealer_stopreport_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#dealer_stopreport_alert_dialog").dialog("close")',5000);
		}
	});
	$("#dealer_stop_reports_device").html(assets_combo_opt_report);
	$("#dealer_stop_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#dealer_stop_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#dealer_stop_reports_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-dealer_stop_reports_device").css('vertical-align','middle');
	$("#ddcl-dealer_stop_reports_device-ddw").css('overflow-x','hidden');
	$("#ddcl-dealer_stop_reports_device-ddw").css('overflow-y','auto');
	$("#ddcl-dealer_stop_reports_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function format_dealer_stop_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_dealer_stop_report_map("+rowObject.id+",\""+rowObject.assets_name+"\",\""+rowObject.current_area+"\")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-RED-BLANK.png'></a>";
}
function searchdealer_stopreport(){
	var sdate = $('#dealer_stop_sdate').val();
	var edate = $('#dealer_stop_edate').val();
	//var device = $('#dealer_stop_reports_device').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-device_rfid-i"+i).is(':checked')){
			dev+=$("#ddcl-device_rfid-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	assets_nm=$('#dealer_stop_reports_device option:selected').html();
	jQuery("#dealer_stopreport_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate,  device:dev, page:1}}).trigger("reloadGrid");
	return false;
}
function cancel(){
	$('#dealer_stopreport_frm').html('');
	$('#dealer_stopreport_list_div').show();
}
$(document).ready(function(){	
	jQuery("input:button, input:submit, input:reset").button();
});
function view_dealer_stop_report_map(id,asset,area){
	var nameToCheck = "Dealer Stop Report Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			//window.location.href ='reports/dealer_stopreport/view_map/0/id/'+id+'/asset/'+asset;
			$('#tabs').tabs('add', 'reports/dealer_stopreport/view_map/0/id/'+id+'/asset/'+asset+'/area/'+area, 'Dealer Stop Report Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', 'reports/dealer_stopreport/view_map/0/id/'+id+'/asset/'+asset+'/area/'+area, 'Dealer Stop Report Map');
	}	
}
function CancelReq()
{
	jQuery("#dealer_stopreport_grid").jqGrid().dealer_stop();
}

</script>
<div id="dealer_stopreport_list_div">
<form onsubmit="return searchdealer_stopreport()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="dealer_stop_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="dealer_stop_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td><td width="20%"><?php echo $this->lang->line("Assets"); ?> : <select name="device" id="dealer_stop_reports_device" class="select ui-widget-content ui-corner-all" style="width:70% !important" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="dealer_stopreport_grid" class="jqgrid"></table>
<div id="dealer_stopreport_alert_dialog"></div>
<div id="dealer_stopreport_pager"></div>
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