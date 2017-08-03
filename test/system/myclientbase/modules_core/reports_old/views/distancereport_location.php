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
#load_distancereport_location_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("#distancereport_locationsdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#distancereport_locationedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#distancereport_location_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/distancereport_location/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("date"); ?>','Vehicle', 'From','To', 'Distance(KM)'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"date_time",editable:true, index:"date_time", width:180, align:"center", jsonmap:"date_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"assets_name",editable:true, index:"assets_name", width:100, align:"center", jsonmap:"assets_name"},
			{name:"from_location",editable:true, index:"from_location", width:100, align:"center", jsonmap:"from_location"},
			{name:"to_location",editable:true, index:"to_location", width:100, align:"center", jsonmap:"to_location"},
			{name:"distance",editable:true, index:"distance", width:180, align:"center", jsonmap:"distance"}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#distancereport_location_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#distancereport_location_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		caption:"<?php echo $this->lang->line("Distance Report"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});
  
	jQuery("#distancereport_location_grid").jqGrid("navGrid", "#distancereport_location_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});

	$("#distancereport_location_pager option[value=10000]").text('All');
	$("#distancereport_location_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#distancereport_location_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});
	
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#distancereport_location_grid").jqGrid("navButtonAdd","#distancereport_location_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#distancereport_locationsdate').val();
			var edate = $('#distancereport_locationedate').val();
			//var device = $('#distancereport_locationdevice').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-distancereport_locationdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-distancereport_locationdevice-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var myPostData = $('#distancereport_location_grid').jqGrid("getGridParam", "postData");
			var sidx = myPostData.sidx;
			var sord = myPostData.sord;
			// sdate="+sdate+"&edate="+edate+"&
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev+"&sidx="+sidx+"&sord="+sord;
			
			document.location = "<?php echo base_url(); ?>index.php/reports/distancereport_location/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#distancereport_locationsdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#distancereport_locationedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#distancereport_location_alert").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#distancereport_locationdevice").html(assets_combo_opt_report);
	$("#distancereport_locationdevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-distancereport_locationdevice").css('vertical-align','middle');
	$("#ddcl-distancereport_locationdevice-ddw").css('overflow-x','hidden');
	$("#ddcl-distancereport_locationdevice-ddw").css('overflow-y','auto');
	$("#ddcl-distancereport_locationdevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});
function searchdistancereport_location(){
	
	var sdate = $('#distancereport_locationsdate').val();
	var edate = $('#distancereport_locationedate').val();
	//var device = $('#distancereport_locationdevice').val();
	
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-distancereport_locationdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-distancereport_locationdevice-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");	
	jQuery("#distancereport_location_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}
function cancel(){
	$('#distancereport_location_frm').html('');
	$('#distancereport_location_list_div').show();
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
	$.post("<?php echo base_url(); ?>index.php/reports/distancereport_location/trackOnMap", { device: device, start_date: start_date, end_date: end_date },
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
<div id="distancereport_location_list_div">
<form onsubmit="return searchdistancereport_location()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="distancereport_locationsdate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="distancereport_locationedate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="distancereport_locationdevice" class="select ui-widget-content ui-corner-all" multiple='multiple'></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="distancereport_location_grid" class="jqgrid"></table>
<div id="distancereport_location_pager"></div>
<div id="distancereport_location_alert"></div>
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