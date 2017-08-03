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
		$va1l->where("menu_id",'126');
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
	 
?>
<style>
#load_runreport_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#ignition_motion_report_grid").jqGrid({
		url:"<?php echo site_url("ignition_motion_report/loadData"); ?>", 
		datatype: "json",
		colNames:["Id","Assets Name(Device)","Date","Motion Hour","Ignition Hour"],
		colModel:[
			{name:"id",index:"id",hidden:true, width:10, jsonmap:"id"},
			{name:"device_name",editable:true, index:"device_name", width:345, align:"center", jsonmap:"device_name"},
			{name:"re_date",editable:true, index:"re_date", width:150, align:"center", jsonmap:"re_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}},
			{name:"motion_hour",editable:true, index:"motion_hour", width:150, align:"center", jsonmap:"motion_hour"},
			{name:"ignition_hour",editable:true, index:"ignition_hour", width:150, align:"center", jsonmap:"ignition_hour"},	
		],
		rowNum:10,
		height: "auto",
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#ignition_motion_report_pager"),
		sortname: "im.id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#ignition_motion_report_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false, 
		caption:"Operating Hours", 
		jsonReader: { repeatitems : false, id: "0" },
	});
  
	jQuery("#ignition_motion_report_grid").jqGrid("navGrid", "#ignition_motion_report_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	$("#ignition_motion_report_pager option[value=10000]").text('All');
	$("#ignition_motion_report_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#ignition_motion_report_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});
	
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#ignition_motion_report_grid").jqGrid("navButtonAdd","#ignition_motion_report_pager",{caption:"Excel",
		onClickButton:function(){
			var sdate = $('#ignition_motion_report_sdate').val();
			var edate = $('#ignition_motion_report_edate').val();
			//var assets = $('#device_id').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-ignition_motion_report_device_id-i"+i).is(':checked')){
					dev+=$("#ddcl-ignition_motion_report_device_id-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select assets"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var myPostData = $('#ignition_motion_report_grid').jqGrid("getGridParam", "postData");
			var sidx = myPostData.sidx;
			var sord = myPostData.sord;
			// sdate="+sdate+"&edate="+edate+"&
			var qrystr ="/cmd/excel?sdate="+sdate+"&edate="+edate+"&device="+dev+"&sidx="+sidx+"&sord="+sord;
			document.location = "<?php echo site_url("ignition_motion_report/loadData"); ?>" + qrystr;
		}
	});
	jQuery("#ignition_motion_report_grid").jqGrid("navButtonAdd","#ignition_motion_report_pager",{caption:"Pdf",
		onClickButton:function(){
			var sdate = $('#ignition_motion_report_sdate').val();
			var edate = $('#ignition_motion_report_edate').val();
			//var assets = $('#device_id').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-ignition_motion_report_device_id-i"+i).is(':checked')){
					dev+=$("#ddcl-ignition_motion_report_device_id-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var myPostData = $('#ignition_motion_report_grid').jqGrid("getGridParam", "postData");
			var sidx = myPostData.sidx;
			var sord = myPostData.sord;
			// sdate="+sdate+"&edate="+edate+"&
			var qrystr ="/cmd/pdf?sdate="+sdate+"&edate="+edate+"&device="+dev+"&sidx="+sidx+"&sord="+sord;
			//document.location = "<?php echo site_url("ignition_motion_report/export_pdf"); ?>" + qrystr;
			window.open("<?php echo site_url("ignition_motion_report/export_pdf"); ?>" + qrystr, '_blank');
		}
	});
	<?php } ?>
	$("#ignition_motion_report_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#ignition_motion_report_device_id").html(assets_combo_opt_report);
	$("#ignition_motion_report_sdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#ignition_motion_report_edate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#ignition_motion_report_sdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s");?>'));
	$("#ignition_motion_report_edate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s");?>'));
	$("#ignition_motion_report_device_id").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-ignition_motion_report_device_id").css('vertical-align','middle');
	$("#ddcl-ignition_motion_report_device_id-ddw").css('overflow-x','hidden');
	$("#ddcl-ignition_motion_report_device_id-ddw").css('overflow-y','auto');
	$("#ddcl-ignition_motion_report_device_id-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function searchignitionmotionreport(){
	
	var sdate = $('#ignition_motion_report_sdate').val();
	var edate = $('#ignition_motion_report_edate').val();
	//var device = $('#ignition_motion_report_device_id').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-ignition_motion_report_device_id-i"+i).is(':checked')){
			dev+=$("#ddcl-ignition_motion_report_device_id-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select assets"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	console.log(dev);
	$("#loading_top").css("display","block");	
	jQuery("#ignition_motion_report_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	return false;	
}

</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="ignition_motion_report_list_div">
<form onsubmit="return searchignitionmotionreport()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="10%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="ignition_motion_report_sdate" id="ignition_motion_report_sdate" class="date text ui-widget-content ui-corner-all" style="width:80px" readonly="readonly"/></td>
		<td width="10%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="ignition_motion_report_edate" id="ignition_motion_report_edate" class="date text ui-widget-content ui-corner-all" style="width:80px" readonly="readonly"/></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="ignition_motion_report_device_id" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="ignition_motion_report_grid" class="jqgrid"></table>
<div id="ignition_motion_report_pager"></div>
<div id="ignition_motion_report_alert"></div>
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