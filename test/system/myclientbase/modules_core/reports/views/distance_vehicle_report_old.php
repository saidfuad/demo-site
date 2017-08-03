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
		$va1l->where("menu_id",'86');
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
#load_distance_vehicle_report_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("#distance_vehicle_reportsdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#distance_vehicle_reportedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#distance_vehicle_report_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/distance_vehicle_report/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Datetime"); ?>','<?php echo $this->lang->line("1st Vehicle"); ?>','<?php echo $this->lang->line("2nd Vehicle"); ?>','<?php echo $this->lang->line("Distance_KM"); ?>', '<?php echo $this->lang->line("map_view"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"asset_id1",editable:true, index:"asset_id1", width:180, align:"center", jsonmap:"asset_id1"},
			{name:"asset_id2",editable:true, index:"asset_id2", width:180, align:"center", jsonmap:"asset_id2"},
			{name:"distance",editable:true, index:"distance", width:180, align:"center", jsonmap:"distance"},
			{name:"map",editable:true, index:"asset1_lat_lng", width:100, align:"center", jsonmap:"map",formatter:format_distance_vehicle_map},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#distance_vehicle_report_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#distance_vehicle_report_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Distance Report"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});
  
	jQuery("#distance_vehicle_report_grid").jqGrid("navGrid", "#distance_vehicle_report_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#distance_vehicle_report_grid").jqGrid("navButtonAdd","#distance_vehicle_report_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#distance_vehicle_reportsdate').val();
			var edate = $('#distance_vehicle_reportedate').val();
			//var device = $('#distance_vehicle_reportdevice').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-distance_vehicle_reportdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-distance_vehicle_reportdevice-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/distance_vehicle_report/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#distance_vehicle_reportsdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#distance_vehicle_reportedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#distance_vehicle_report_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#distance_vehicle_reportdevice").html(assets_combo_opt_report);
	$("#distance_vehicle_reportdevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b><?php echo $this->lang->line("All Assets"); ?></b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-distance_vehicle_reportdevice").css('vertical-align','middle');
	$("#ddcl-distance_vehicle_reportdevice-ddw").css('overflow-x','hidden');
	$("#ddcl-distance_vehicle_reportdevice-ddw").css('overflow-y','auto');
	$("#ddcl-distance_vehicle_reportdevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function format_distance_vehicle_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_distance_vehicle_report_map("+rowObject.id+")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-BLUE1-BLANK.png'></a>";
}
function searchdistance_vehicle_report(){
	
	var sdate = $('#distance_vehicle_reportsdate').val();
	var edate = $('#distance_vehicle_reportedate').val();
	//var device = $('#distance_vehicle_reportdevice').val();
	var dev="";
	
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-distance_vehicle_reportdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-distance_vehicle_reportdevice-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");	
	jQuery("#distance_vehicle_report_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}

function cancel(){
	$('#distance_vehicle_report_frm').html('');
	$('#distance_vehicle_report_list_div').show();
}
function view_distance_vehicle_report_map(id){
	var nameToCheck = "Distance Vehicle Report";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			//window.location.href ='reports/dealer_stopreport/view_map/0/id/'+id+'/asset/'+asset;
			$('#tabs').tabs('add', 'reports/distance_vehicle_report/viewMap/0/id/'+id, 'Distance Vehicle Report');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', 'reports/distance_vehicle_report/viewMap/0/id/'+id, 'Distance Vehicle Report');
	}
}
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="distance_vehicle_report_list_div">
<form onsubmit="return searchdistance_vehicle_report()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="distance_vehicle_reportsdate" class="date text ui-widget-content ui-corner-all" style="width:120px" value="<?php echo date($date_format); ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="distance_vehicle_reportedate" class="date text ui-widget-content ui-corner-all" style="width:120px" value="<?php echo date($date_format); ?>" readonly="readonly"/></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="distance_vehicle_reportdevice" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>

<table id="distance_vehicle_report_grid" class="jqgrid"></table>

<div id="distance_vehicle_report_pager"></div>
<div id="distance_vehicle_report_alert"></div>
</div>
