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
		$va1l->where("menu_id",'121');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}

	$date_format = $this->session->userdata('date_format');
	$time_format = $this->session->userdata('time_format');
	$js_date_format = $this->session->userdata('js_date_format');
	$js_time_format = $this->session->userdata('js_time_format');
	 
	/*	$Assest="<option value=''>Select Assest </option>";
		$user_id=$this->session->userdata('user_id');
		$query="select assets_name, device_id from assests_master where find_in_set(id, (select assets_ids from user_assets_map where user_id = $user_id))";
		
		$result=mysql_query($query);
		while($row=mysql_fetch_array($result))
		{
			$Assest .="<option value='".$row['device_id']."'>".$row['assets_name']."(".$row['device_id'].")"."</option>";
		}*/
?>
<style>
#load_area_report_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#area_report_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/area_report/loadData",
		datatype: "json", 
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Area"); ?>','<?php echo $this->lang->line("Assets"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"current_area",editable:true, index:"current_area", width:150, align:"center", jsonmap:"current_area"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#area_report_pager"),
		sortname: "assets_name",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#area_report_grid").setGridParam({datatype: 'json'}); 
		},	
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Area Report"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#area_report_grid").jqGrid("navGrid", "#area_report_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	$("#area_report_pager option[value=10000]").text('All');
	$("#area_report_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#area_report_pager .ui-pg-selbox").val();
	});
	
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#area_report_grid").jqGrid("navButtonAdd","#area_report_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			//var sdate = $('#sdate_area_report').val();
			//var edate = $('#edate_area_report').val();
			var zone = $('#area_report').val();
			var group = $('#group_device_area_report').val();
			//var device = $('#device_area_report').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-device_area_report-i"+i).is(':checked')){
					dev+=$("#ddcl-device_area_report-i"+i).val()+",";
				}
			}
			// sdate="+sdate+"&edate="+edate+"&
			var qrystr ="/export?group="+group+"&zone="+zone+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/zone/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#device_area_report").html(assets_combo_opt_report);
	//$("#sdate_area_report").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	//$("#edate_area_report").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	//$("#sdate_area_report").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	//$("#edate_area_report").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#device_area_report").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options){
		var selectedOptions = options.filter(":selected");
		var countOfSelected = selectedOptions.size();
		switch(countOfSelected) {
			case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
			case 1: return selectedOptions.text();
			case options.size(): return "<b><?php echo $this->lang->line("all_assets"); ?></b>";
			default: return countOfSelected + " <?php echo $this->lang->line("Assets"); ?>";
		}
	}, icon: {}, width: 150});
	$("#ddcl-device_area_report").css('vertical-align','middle');
	$("#ddcl-device_area_report-ddw").css('overflow-x','hidden');
	$("#ddcl-device_area_report-ddw").css('overflow-y','auto');
	$("#ddcl-device_area_report-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
}); 

function searcharea(){
	
	//var sdate = $('#sdate_area_report').val();
	//var edate = $('#edate_area_report').val();
	var area = $('#area_report').val();
	var group = $('#group_device_area_report').val();
	//var device = $('#device_area_report');
	
	//alert(device.attr('checked'));
	var dev="";
	
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-device_area_report-i"+i).is(':checked')){
			dev+=$("#ddcl-device_area_report-i"+i).val()+",";
		}
	}
	/*
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	*/
	
	$("#loading_top").css("display","block");
	jQuery("#area_report_grid").jqGrid('setGridParam',{postData:{device:dev, area:area, group:group, page:1}}).trigger("reloadGrid");
	
	return false;	
}
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="zone_list_div">
<form onsubmit="return searcharea()">
<table width="100%" class="formtable">
	<tr>
		<!-- td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="sdate" id="sdate_area_report" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="10%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="edate" id="edate_area_report" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td -->
		<td width="14%"><?php echo $this->lang->line("Area"); ?> : <br><select name="area" id="area_report" class="select ui-widget-content ui-corner-all" ><?php echo $area; ?></select></td>
		<td width="14%"><?php echo $this->lang->line("Group"); ?> : <br><select onchange="filterAssetsCombo(this.value,'device_area_report')" name="group" id="group_device_area_report" class="select ui-widget-content ui-corner-all" ><?php echo $group; ?></select></td>
		
		<td width="14%"><?php echo $this->lang->line("assets"); ?> : <br><select name="device[]" id="device_area_report" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple='multiple'></select></td>
		<td width="3%"><br><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
        </tr></table><br/>
</form>
</div>
<table id="area_report_grid" class="jqgrid"></table>

<div id="area_report_pager"></div>
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