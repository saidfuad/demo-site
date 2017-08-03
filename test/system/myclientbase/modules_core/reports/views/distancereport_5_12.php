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
	

	$date_format = $this->session->userdata('date_format');  
	$time_format = $this->session->userdata('time_format');  
	$js_date_format = $this->session->userdata('js_date_format');  
	$js_time_format = $this->session->userdata('js_time_format');  
	 
?>
<style>
#load_distancereport_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("#distancereportsdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#distancereportedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#distancereport_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/distancereport/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("date"); ?>','<?php echo $this->lang->line("Distance_KM"); ?>', '<?php echo $this->lang->line("Device"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:180, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d",newformat:"<?php echo $date_format; ?>"}},
			{name:"distance",editable:true, index:"distance", width:180, align:"center", jsonmap:"distance"},
			{name:"assets_id",editable:true, index:"assets_id", width:100, align:"center", jsonmap:"assets_id"},
			//{name:"running_time",editable:true, index:"running_time", width:100, align:"center", jsonmap:"running_time"}
//			{name:"run_time",editable:true, index:"run_time", width:100, align:"center", jsonmap:"run_time"}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#distancereport_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#distancereport_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Distance Report"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});
  
	jQuery("#distancereport_grid").jqGrid("navGrid", "#distancereport_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});

	$("#distancereport_pager option[value=10000]").text('All');
	$("#distancereport_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#distancereport_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});
	
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#distancereport_grid").jqGrid("navButtonAdd","#distancereport_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#distancereportsdate').val();
			var edate = $('#distancereportedate').val();
			var group = $('#group_distance_report').val();
			//var device = $('#distancereportdevice').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-distancereportdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-distancereportdevice-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&group="+group+"&device="+dev;
			
			document.location = "<?php echo base_url(); ?>index.php/reports/distancereport/loadData"+qrystr;
		}
	});
	jQuery("#distancereport_grid<?php echo time(); ?>").jqGrid("navButtonAdd","#distancereport_pager<?php echo time(); ?>",{caption:"Pdf",
		onClickButton:function(){
			var sdate = $('#distancereportsdate').val();
			var edate = $('#distancereportedate').val();
			var device = $('#group_distance_report').val();
			var qrystr ="/pdf?sdate="+sdate+"&edate="+edate+"&device="+device;
			
			window.open("<?php echo site_url('reports/distancereport/export_pdf'); ?>"+qrystr,'_blank');
			
		}
	});
	<?php } ?>
	$("#distancereportsdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#distancereportedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#distancereport_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#distancereportdevice").html(assets_combo_opt_report);
	$("#distancereportdevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-distancereportdevice").css('vertical-align','middle');
	$("#ddcl-distancereportdevice-ddw").css('overflow-x','hidden');
	$("#ddcl-distancereportdevice-ddw").css('overflow-y','auto');
	$("#ddcl-distancereportdevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function searchdistancereport(){
	
	var sdate = $('#distancereportsdate').val();
	var edate = $('#distancereportedate').val();
	//var device = $('#distancereportdevice').val();
	var group = $('#group_distance_report').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-distancereportdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-distancereportdevice-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");	
	jQuery("#distancereport_grid").jqGrid('setGridParam',{postData:{group:group, sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}
function cancel(){
	$('#distancereport_frm').html('');
	$('#distancereport_list_div').show();
}

function viewOnMap(){
	
	
	var device = $('#device').val();
	if(device == ""){
		alert('<?php echo $this->lang->line("Please select device"); ?>');
		return false;
	}
	
	$('#tabs').tabs('add', "live/device/window/current/id/"+device, '<?php echo $this->lang->line("View_on_Map"); ?>', 1);

	$("#divAjaxIndex").dialog('open');
	var start_date = $('#sdate').val();
	var end_date = $('#edate').val();
	$.post("<?php echo base_url(); ?>index.php/reports/distancereport/trackOnMap", { device: device, start_date: start_date, end_date: end_date },
	 function(data) {
		if(data){
			var latArr = new Array();
			var lngArr = new Array();
			var htmlArr = new Array();
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			if(lat.length > 0){
				for(i=0; i<lat.length; i++){
					latArr.push(lat[i]);
					lngArr.push(lng[i]);
					htmlArr.push(html[i]);
				}
				var devText = $('#device option:selected').text();
				var distance = data.distance;
				var txt = devText + " Distance : " + distance + " KM";
				viewTrack(latArr,lngArr,htmlArr, txt);
				
			}else{
				alert("<?php echo $this->lang->line("No_Data_Found"); ?>");
			}
			$("#divAjaxIndex").dialog('close');
		}
	 }, 'json'
	);
	
}
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="distancereport_list_div">
<form onsubmit="return searchdistancereport()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="10%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="distancereportsdate" class="date text ui-widget-content ui-corner-all" style="width:80px" readonly="readonly"/></td>
		<td width="10%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="distancereportedate" class="date text ui-widget-content ui-corner-all" style="width:80px" readonly="readonly"/></td>
		<td width="14%">Group : <select onchange="filterAssetsCombo(this.value,'distancereportdevice')" style="width:120px;" name="group" id="group_distance_report" class="select ui-widget-content ui-corner-all" ><?php echo $group; ?></select></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="distancereportdevice" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="distancereport_grid" class="jqgrid"></table>
<div id="distancereport_pager"></div>
<div id="distancereport_alert"></div>
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