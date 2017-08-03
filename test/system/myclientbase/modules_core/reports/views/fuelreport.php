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
		$va1l->where("menu_id",'7');
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
	 
?>
<style>
#load_fuelreport_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("#fuelreportsdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#fuelreportedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#fuelreport_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/fuelreport/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>','Date','Start KM','End KM','Total Distance','Fuel','Mileage'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:120, align:"center", jsonmap:"assets_name"},
			{name:"add_date",editable:true, index:"add_date", width:180, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d",newformat:"<?php echo $date_format; ?>"}},
			{name:"start_km",editable:true, index:"start_km", width:120, align:"center", jsonmap:"start_km"},
			{name:"end_km",editable:true, index:"end_km", width:120, align:"center", jsonmap:"end_km"},
			{name:"km",editable:true, index:"km", width:150, align:"center", jsonmap:"km"},
			{name:"fuel_used",editable:true, index:"fuel_used", width:150, align:"center", jsonmap:"fuel_used"},
			{name:"mileage",editable:true, index:"mileage", width:150, align:"center", jsonmap:"mileage"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#fuelreport_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#fuelreport_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"Distance & Fuel Consumption Report",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});
  
	jQuery("#fuelreport_grid").jqGrid("navGrid", "#fuelreport_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#fuelreport_grid").jqGrid("navButtonAdd","#fuelreport_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#fuelreportsdate').val();
			var edate = $('#fuelreportedate').val();
			//var device = $('#fuelreportdevice').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-fuelreportdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-fuelreportdevice-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev;
			
			document.location = "<?php echo base_url(); ?>index.php/reports/fuelreport/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#fuelreportsdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#fuelreportedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#fuelreport_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#fuelreportdevice").html(assets_combo_opt_report);
	$("#fuelreportdevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-fuelreportdevice").css('vertical-align','middle');
	$("#ddcl-fuelreportdevice-ddw").css('overflow-x','hidden');
	$("#ddcl-fuelreportdevice-ddw").css('overflow-y','auto');
	$("#ddcl-fuelreportdevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function searchfuelreport(){
	
	var sdate = $('#fuelreportsdate').val();
	var edate = $('#fuelreportedate').val();
	//var device = $('#fuelreportdevice').val();
	
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-fuelreportdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-fuelreportdevice-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");	
	jQuery("#fuelreport_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}
function cancel(){
	$('#fuelreport_frm').html('');
	$('#fuelreport_list_div').show();
}

</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="fuelreport_list_div">
<form onsubmit="return searchfuelreport()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="fuelreportsdate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="fuelreportedate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="fuelreportdevice" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="fuelreport_grid" class="jqgrid"></table>
<div id="fuelreport_pager"></div>
<div id="fuelreport_alert"></div>
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
