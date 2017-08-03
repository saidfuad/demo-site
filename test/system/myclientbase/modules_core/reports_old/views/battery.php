<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	
	$data = array("Search","Export");
?>
<script type="text/javascript">
$("#loading_top").css("display","block");
</script>
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

#load_battery_grid<?php echo time(); ?>
{
	display:none !important;
}
#ui_tpicker_hour_label_battery_sdate,#ui_tpicker_hour_label_battery_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_battery_sdate,#ui_tpicker_minute_label_battery_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_battery_sdate,#ui_tpicker_second_label_battery_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
</style>
<script type="text/javascript">

loadMultiSelectDropDown();

jQuery().ready(function (){
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#battery_grid<?php echo time(); ?>").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/battery/loadData",
		datatype: "json",
		colNames:["<?php $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>', 'Device Battery', 'Vehicle Battery'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:250, align:"center",jsonmap:"assets_name"},
			{name:"in_batt",editable:true, index:"in_batt", width:250, align:"center", jsonmap:"in_batt"},
			{name:"ext_batt_volt",editable:true, index:"ext_batt_volt", width:250, align:"center", jsonmap:"ext_batt_volt"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#battery_pager<?php echo time(); ?>"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#battery_grid<?php echo time(); ?>").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		caption:"Battery Report",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
<?php
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#battery_grid<?php echo time(); ?>").jqGrid("navGrid", "#battery_pager<?php echo time(); ?>", {add:false, edit:false, del:false, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#battery_grid<?php echo time(); ?>").jqGrid("navButtonAdd","#battery_pager<?php echo time(); ?>",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#battery_sdate').val();
			var edate = $('#battery_edate').val();
			//var device = $('#battery_device').val();
			var dev="";
			for(i=0;i<assets_count;i++){
				if($("#ddcl-battery_device-i"+i).is(':checked')){
					dev+=$("#ddcl-battery_device-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var myPostData = $('#battery_grid<?php echo time(); ?>').jqGrid("getGridParam", "postData");
			var sidx = myPostData.sidx;
			var sord = myPostData.sord;
			// sdate="+sdate+"&edate="+edate+"&
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev+"&sidx="+sidx+"&sord="+sord;
			document.location = "<?php echo base_url(); ?>index.php/reports/battery/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#battery_device").html(assets_combo_opt_report);
	//$(".date").datepicker('setDate', new Date());
	cancelloading();
	//$(".date").datepicker({dateFormat:'dd.mm.yy',changeMonth: true,changeYear: true});
	//jQuery("input:button, input:submit, input:reset").button();	
	$("#battery_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#battery_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});

	$("#battery_sdate").datetimepicker('setDate', new Date());
	$("#battery_edate").datetimepicker('setDate', new Date());
	$("#battery_sdate").val('<?php echo date($date_format." ".$time_format);?>');
	$("#battery_edate").val('<?php echo date($date_format." ".$time_format);?>');
	$("#battery_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-battery_device").css('vertical-align','middle');
	$("#ddcl-battery_device-ddw").css('overflow-x','hidden');
	$("#ddcl-battery_device-ddw").css('overflow-y','auto');
	$("#ddcl-battery_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
}); 
function searchinspection(){
	$("#battery_grid_div<?php echo time(); ?>").show();
	var sdate = $('#battery_sdate').val();
	var edate = $('#battery_edate').val();
	//var device = $('#battery_device').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-battery_device-i"+i).is(':checked')){
			dev+=$("#ddcl-battery_device-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#battery_grid<?php echo time(); ?>").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	return false;	
}
</script>
<?php
	$timestamp = strtotime("+2 day");
	$tomorrow=date($date_format." ".$time_format,$timestamp);
?>

<div id="battery_list_div">
<form onsubmit="return searchinspection()">
	<table border="5" width="100%" class="formtable" style="margin-bottom: 5px;">
		<tr>
			<!--td width="30%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="battery_sdate" class="date text ui-widget-content ui-corner-all" style="width:160px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
			<td width="30%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="battery_edate" class="date text ui-widget-content ui-corner-all" style="width:160px" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td-->
			<td width="30%">Group : <select onchange="filterAssetsCombo(this.value,'battery_device')" name="group" id="group_device_battery" class="select ui-widget-content ui-corner-all"  style="width:150px"><?php echo $group; ?></select></td>
			<td width="30%"><?php echo $this->lang->line("Assets"); ?> : <select name="device" id="battery_device" class="select ui-widget-content ui-corner-all" style="width:150px" multiple='multiple'></select></td>
			<td width="10%"><input type="submit" value="<?php echo $this->lang->line("grid_view"); ?>"/>
			</td>
		</tr>
	</table>
</form> 
<div id="battery_grid_div<?php echo time(); ?>">
<table id="battery_grid<?php echo time(); ?>" class="jqgrid"></table>
<div id="battery_pager<?php echo time(); ?>"></div>
</div>
</div>
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