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
		$va1l->where("menu_id",'11');
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
<?php
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
#load_area_in_out_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#area_in_out_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/area_in_out/loadData",
		datatype: "local", 
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Assets"); ?>','<?php echo $this->lang->line("Area"); ?>', '<?php echo $this->lang->line("date"); ?>', '<?php echo $this->lang->line("Status"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"Assets",editable:true, index:"am.assets_name", width:150, align:"center", jsonmap:"device"},
			{name:"Area",editable:true, index:"ta.polyname", width:150, align:"center", jsonmap:"area"},
			{name:"Date",editable:true, index:"tm.date_time", width:120, align:"center", jsonmap:"date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"Status",editable:true, index:"tm.inout_status ", width:150, align:"center", jsonmap:"status"}
		],
		rowNum:10,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#area_in_out_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#area_in_out_grid").setGridParam({datatype: 'json'}); 
		},	
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Area In/Out Detail"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#area_in_out_grid").jqGrid("navGrid", "#area_in_out_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#area_in_out_grid").jqGrid("navButtonAdd","#area_in_out_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#sdate_area').val();
			var edate = $('#edate_area').val();
			var area = $('#area').val();
			var group = $('#group_device_area').val();
			//var device = $('#device_area').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-device_area-i"+i).is(':checked')){
					dev+=$("#ddcl-device_area-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&group="+group+"&area="+area+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/area_in_out/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#device_area").html(assets_combo_opt_report);
	$("#sdate_area").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#edate_area").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#sdate_area").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#edate_area").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#device_area").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options){
		var selectedOptions = options.filter(":selected");
		var countOfSelected = selectedOptions.size();
		switch(countOfSelected) {
			case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
			case 1: return selectedOptions.text();
			case options.size(): return "<b><?php echo $this->lang->line("all_assets"); ?></b>";
			default: return countOfSelected + " Assets";
		}
	}, icon: {}, width: 150});
	$("#ddcl-device_area").css('vertical-align','middle');
	$("#ddcl-device_area-ddw").css('overflow-x','hidden');
	$("#ddcl-device_area-ddw").css('overflow-y','auto');
	$("#ddcl-device_area-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
}); 

function searcharea_in_out(){
	
	var sdate = $('#sdate_area').val();
	var edate = $('#edate_area').val();
	var area = $('#area').val();
	var group = $('#group_device_area').val();
	//var device = $('#device_area');
	
	//alert(device.attr('checked'));
	var dev="";
	
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-device_area-i"+i).is(':checked')){
			dev+=$("#ddcl-device_area-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#area_in_out_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate, device:dev, area:area, group:group, page:1}}).trigger("reloadGrid");
	
	return false;	
}/*
function cancel(){
	$('#area_in_out_frm').html('');
	$('#area_in_out_list_div').show();
}

$(document).ready(function() {
	$(".date").datepicker({dateFormat:'dd.mm.yy',changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();	

		
});
$("#loading_dialog").dialog("close");
function viewOnMap(){
	
	
	var device = $('#device').val();
	if(device == ""){
		alert('Please select device');
		return false;
	}
	
	$('#tabs').tabs('add', "live/device/window/current/id/"+device, 'View On Map', 1);

	$("#divAjaxIndex").dialog('open');
	var start_date = $('#sdate').val();
	var end_date = $('#edate').val();
	$.post("<?php echo base_url(); ?>index.php/reports/area_in_out/trackOnMap", { device: device, start_date: start_date, end_date: end_date },
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
				alert("No Data Found");
			}
			$("#divAjaxIndex").dialog('close');
		}
	 }, 'json'
	);
	
}
*/
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="area_in_out_list_div">
<form onsubmit="return searcharea_in_out()">
<table width="100%" class="formtable">
	<tr>
		<td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="sdate" id="sdate_area" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="10%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="edate" id="edate_area" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="14%">Area : <br><select name="area" id="area" class="select ui-widget-content ui-corner-all" ><?php echo $area; ?></select></td>
		<td width="14%">Group : <br><select onchange="filterAssetsCombo(this.value,'device_area')" name="group" id="group_device_area" class="select ui-widget-content ui-corner-all" ><?php echo $group; ?></select></td>
		
		<td width="14%"><?php echo $this->lang->line("assets"); ?> : <br><select name="device[]" id="device_area" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple='multiple'></select></td>
		<td width="3%"><br><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
        </tr></table><br/>
</form>
</div>
<table id="area_in_out_grid" class="jqgrid"></table>

<div id="area_in_out_pager"></div>
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