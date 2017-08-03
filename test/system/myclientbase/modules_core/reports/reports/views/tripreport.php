<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_tripreport_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("#trip_reports_edate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#trip_reports_sdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#tripreport_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/tripreport/loadData",
		datatype: "local",
		colNames:["Id",'Datetime','Start Point', 'End Point', 'Avg. Speed', 'Distance'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:100, align:"center", jsonmap:"add_date"},
			{name:"start_point",editable:true, index:"start_point", width:200, align:"center", jsonmap:"start_point"},
			{name:"end_point",editable:true, index:"end_point", width:200, align:"center", jsonmap:"end_point"},
			{name:"avrg_speed",editable:true, index:"avrg_speed", width:100, align:"center", jsonmap:"avrg_speed"},
			{name:"dist",editable:true, index:"dist", width:100, align:"center", jsonmap:"dist"},
		],
		rowNum:10,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#tripreport_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#tripreport_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"Trip Report",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#tripreport_grid").jqGrid("navGrid", "#tripreport_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	$("#trip_reports_sdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#trip_reports_edate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#trip_reports_device").html(assets_combo_opt_report);
	$("#trip_reports_edate").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
			var selectedOptions = options.filter(":selected");
			var countOfSelected = selectedOptions.size();
			switch(countOfSelected) {
				case 0: return "<i>Please Select<i>";
				case 1: return selectedOptions.text();
				case options.size(): return "<b>All Assets</b>";
				default: return countOfSelected + " Assets";
			}
		}, icon: {}, width: 150});
	$("#ddcl-trip_reports_edate").css('vertical-align','middle');
	$("#ddcl-trip_reports_edate-ddw").css('overflow-x','hidden');
	$("#ddcl-trip_reports_edate-ddw").css('overflow-y','auto');
	$("#ddcl-trip_reports_edate-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function searchtripreport(){
	
	var sdate = $('#trip_reports_sdate').val();
	var edate = $('#trip_reports_edate').val();
//	var device = $('#trip_reports_device').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-trip_reports_edate-i"+i).is(':checked')){
			dev+=$("#ddcl-trip_reports_edate-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	//$("#tripreport_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");		
	jQuery("#tripreport_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}
function cancel(){
	$('#tripreport_frm').html('');
	$('#tripreport_list_div').show();
	$("#trip_reports_device").html(assets_combo_opt);
}

</script>
<div id="tripreport_list_div">
<form onsubmit="return searchtripreport()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->line->line("Start"); ?> : <input type="text" name="sdate" id="trip_reports_sdate" class="date text ui-widget-content ui-corner-all" style="width:120px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->line->line("End"); ?> : <input type="text" name="edate" id="trip_reports_edate" class="date text ui-widget-content ui-corner-all" style="width:120px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="20%"><select name="device" id="trip_reports_device" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="View"/></td>
</form>


<table id="tripreport_grid" class="jqgrid"></table>

<div id="tripreport_pager"></div>
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