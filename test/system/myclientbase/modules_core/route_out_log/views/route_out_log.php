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
		$va1l->where("menu_id",'48');
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
#load_route_out_log_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){  
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#route_out_log_grid").jqGrid({
		url:"<?php echo site_url('route_out_log/loadData'); ?>",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Device_Name"); ?> ','<?php echo $this->lang->line("Route Name"); ?>','<?php echo $this->lang->line("Date Time"); ?>','<?php echo $this->lang->line("Distance From Route"); ?>', '<?php echo $this->lang->line("In/Out"); ?>','<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"tl.id",hidden:true, width:15, jsonmap:"tl.id"},
			{name:"device_name",editable:true, index:"device_name", width:90, align:"center", jsonmap:"device_name"},
			{name:"name",editable:true, index:"name", width:90, align:"center", jsonmap:"name"},
			{name:"date_time",editable:true, index:"date_time", width:90, align:"center", jsonmap:"date_time" ,formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			//{name:"lat",editable:true, index:"lat", width:90, align:"center", jsonmap:"lat"},
			//{name:"lng",editable:true, index:"lng", width:90, align:"center", jsonmap:"lng"},
			{name:"distance",editable:true, index:"distance", width:90, align:"center", jsonmap:"distance"},
			{name:"on_route",editable:true, index:"on_route", width:90, align:"center", jsonmap:"on_route"},
			{name:"view_on_map",editable:true, index:"view_on_map", width:100, align:"center", jsonmap:"view_on_map",formatter:view_on_map_format}
		],
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#route_out_log_pager"),
		sortname: "tl.id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#route_out_log_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false,
		caption:"<?php echo $this->lang->line("Rout Out Log List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#route_out_log_grid").jqGrid("navGrid", "#route_out_log_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#route_out_log_grid").jqGrid("navButtonAdd","#route_out_log_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var routoutlogsdate = $('#routoutlogsdate').val();
			var routoutlogedate = $('#routoutlogedate').val();
			//var devicename = $("#droutoutlogevice").val();
			var dev="";
			for(i=0;i<assets_count;i++){
				if($("#ddcl-lanamarkdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-lanamarkdevice-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			qrystr ="/cmd/export?sdate="+routoutlogsdate+"&edate="+routoutlogedate+"&device="+dev;
			document.location = "<?php echo site_url('route_out_log/loadData'); ?>"+qrystr;
		}
	});
	<?php } ?>
	$("#droutoutlogevice").html(assets_combo_opt_report);
	$("#routoutlogsdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#routoutlogedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#routoutlogsdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#routoutlogedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#droutoutlogevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-droutoutlogevice").css('vertical-align','middle');
	$("#ddcl-droutoutlogevice-ddw").css('overflow-x','hidden');
	$("#ddcl-droutoutlogevice-ddw").css('overflow-y','auto');
	$("#ddcl-droutoutlogevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});

function search_route_out_log(){
	
	var routoutlogsdate = $('#routoutlogsdate').val();
	var routoutlogedate = $('#routoutlogedate').val();
	//var devicename = $("#droutoutlogevice").val();
	//$("#allpoints_list").flexOptions({params: [{name:'routoutlogsdate', value: routoutlogsdate},{name:'routoutlogedate',value:routoutlogedate},{name:'device',value:device}]}).flexReload(); 
	var dev="";
	for(i=0;i<assets_count;i++){
		if($("#ddcl-droutoutlogevice-i"+i).is(':checked')){
			dev+=$("#ddcl-droutoutlogevice-i"+i).val()+",";
		}
	}
	$("#loading_top").css("display","block");
	jQuery("#route_out_log_grid").jqGrid('setGridParam',{postData:{sdate:routoutlogsdate, edate:routoutlogedate,device : dev,  page:1}}).trigger("reloadGrid");
	
	return false;	
}

function view_on_map_format(cellvalue, options, rowObject){
	if(rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
		return "<a href='#' onclick='view_map(\""+rowObject.id+"\")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-BLUE1-BLANK.png'></a>";
	}
	return '&nbsp;';
}
function view_map(id){
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/route_out_log/view_map?cmd=route_out_log&id="+id, 'View Route Log ', 1);
	//viewLocation(lat,lang,html);
}


function payment_status_forother(cellvalue, options, rowObject){
	if(rowObject.payment_status=='Unpaid')
		return "<span style='color:red'>"+cellvalue+"</span>";
	else
		return "<span style='color:green'>"+cellvalue+"</span>";
}
function payment_status(cellvalue, options, rowObject){
	if(cellvalue=='Unpaid')
		return "<span style='color:red'>"+cellvalue+"</span>";
	else
		return "<span style='color:green'>"+cellvalue+"</span>";
//	rowObject.account
}
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="route_out_log_list_div">
<form onsubmit="return search_route_out_log()">
<table width="100%" class="formtable">
	<tr>
		<td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="routoutlogsdate" id="routoutlogsdate" class="date text ui-widget-content ui-corner-all" style="width:128px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="8%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="routoutlogedate" id="routoutlogedate" class="date text ui-widget-content ui-corner-all" style="width:128px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="14%"><?php echo $this->lang->line("Assets"); ?> :<select name="device" id="droutoutlogevice" class="select ui-widget-content ui-corner-all" style="width:75% !important" multiple='multiple'></select></td>
		<td width="3%"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
    </tr>
</table><br/>
</form> 
	<table id="route_out_log_grid" class="jqgrid"></table>
</div>
<div id="route_out_log_pager"></div>

</body>
</html>