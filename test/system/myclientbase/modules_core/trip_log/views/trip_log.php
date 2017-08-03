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
		$va1l->where("menu_id",'49');
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
#load_trip_log_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){   
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#trip_log_grid").jqGrid({
		url:"<?php echo site_url('trip_log/loadData'); ?>",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Device"); ?>','<?php echo $this->lang->line("Trip Name"); ?>','<?php echo $this->lang->line("Start_Time"); ?>','<?php echo $this->lang->line("End_Time"); ?>', '<?php echo $this->lang->line("Total Time"); ?>'],
		colModel:[
			{name:"id",index:"trip_id",hidden:true, width:15, jsonmap:"trip_id"},
			{name:"device_name",editable:true, index:"device_name", width:90, align:"center", jsonmap:"device_name"},
			{name:"name",editable:true, index:"name", width:90, align:"center", jsonmap:"name"},
			{name:"start_time",editable:true, index:"start_time", width:90, align:"center", jsonmap:"start_time" ,formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"end_time",editable:true, index:"end_time", width:90, align:"center", jsonmap:"end_time" ,formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"time_taken",editable:true, index:"time_taken", width:90, align:"center", jsonmap:"time_taken"},
			//{ name: 'trip_id', index: 'trip_id', width: 150, align: 'center',formatter:view_on_map_format_main , jsonmap:"trip_id"},                   
		],
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#trip_log_pager"),
		sortname: "tl.id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#trip_log_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false,
		caption:"<?php echo $this->lang->line("Trip Log List"); ?>",
		jsonReader: { repeatitems : false, id: "0" },
		subGrid : true, 
		subGridRowExpanded: function(subgrid_id, row_id) {
		   var subgrid_table_id;
		   subgrid_table_id = subgrid_id+"_t";
		   jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
		   jQuery("#"+subgrid_table_id).jqGrid({
			  url:"<?php echo site_url('trip_log/sub_grid'); ?>?q=2&id="+row_id,
			  datatype: "json",
			  colNames: ['<?php echo $this->lang->line("ID"); ?>','<?php echo $this->lang->line("Name"); ?>','<?php echo $this->lang->line("Date Time"); ?>','<?php echo $this->lang->line("Distance"); ?>','<?php echo $this->lang->line("View Map"); ?>'],
			  colModel: [
				{ name: 'id', index: 'id', width: 150, align: 'center',hidden:true},
				{ name: 'name', index: 'name', width: 150, align: 'center'},
				{ name: 'date_time', index: 'date_time', width: 150, align: 'center',formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"} },                   
				{ name: 'distance', index: 'distance', width: 150, align: 'center' },                   
				{ name: 'view_map', index: 'view_map', width: 150, align: 'center',formatter:view_on_map_format },                   
			  ],
			rownumbers: true,  
			 height: 'auto', 
			  sortname: 'date_time',
			  sortorder: "asc",
			  jsonReader: { repeatitems : false, id: "0" },
		   });
		 
		 }
	});
	
	jQuery("#trip_log_grid").jqGrid("navGrid", "#trip_log_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#trip_log_grid").jqGrid("navButtonAdd","#trip_log_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var triplogsdate = $('#triplogsdate').val();
			var triplogedate = $('#triplogedate').val();
			//var device = $('#triplogdevice').val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-triplogdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-triplogdevice-i"+i).val()+",";
				}
			}
			qrystr ="/cmd/export?triplogsdate="+triplogsdate+"&triplogedate="+triplogedate+"&device="+dev;
			document.location = "<?php echo site_url('trip_log/loadData'); ?>"+qrystr;
		}
	});
	<?php } ?>
	$("#triplogdevice").html(assets_combo_opt_report);
	 $("#triplogsdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	 $("#triplogedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	 $("#triplogsdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	 $("#triplogedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	 $("#loading_top").css("display","none");
	 $("#triplogdevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b><?php echo $this->lang->line("All Assets"); ?></b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-triplogdevice").css('vertical-align','middle');
	$("#ddcl-triplogdevice-ddw").css('overflow-x','hidden');
	$("#ddcl-triplogdevice-ddw").css('overflow-y','auto');
	$("#ddcl-triplogdevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});

function view_on_map_format_main(cellvalue, options, rowObject){
	
	if(rowObject.end_time != "" && rowObject.end_time != null && rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
		return "<a href='#' onclick='view_map_all_trip_report(\""+cellvalue+"\")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-BLUE1-BLANK.png'></a>";
	}
	return '&nbsp;';
}

function view_on_map_format(cellvalue, options, rowObject){
	if(rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
		return "<a href='#' onclick='view_map_trip_report(\""+rowObject.id+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-BLUE1-BLANK.png'></a>";
	}
	return '&nbsp;';
}
function view_map_trip_report(id){
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/trip_log/view_map?cmd=trip_log&id="+id, 'Trip Log Map', 1);
	//viewLocation(lat,lang,html);
}
function view_map_all_trip_report(id,start,end){
	//alert(id+"-"+start+"-"+end);
	
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/trip_log/view_map_all/cmd/trip_log/id/"+id, 'Trip Log Map', 1);
	//viewLocation(lat,lang,html);
}
function search_trip_log(){
	
	var triplogsdate = $('#triplogsdate').val();
	var triplogedate = $('#triplogedate').val();
	//var device = $('#triplogdevice').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-triplogdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-triplogdevice-i"+i).val()+",";
		}
	}
	if(dev == ""){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#trip_log_grid").jqGrid('setGridParam',{postData:{triplogsdate:triplogsdate,triplogedate:triplogedate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}

</script>

<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="trip_log_list_div">
<form onsubmit="return search_trip_log()">
<table width="100%" class="formtable">
	<tr>
		<td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="triplogsdate" id="triplogsdate" class="date text ui-widget-content ui-corner-all" style="width:110px" readonly="readonly"/></td>
			<td width="10%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="triplogedate" id="triplogedate" class="date text ui-widget-content ui-corner-all" style="width:110px" readonly="readonly"/></td>
		<td width="14%"><?php echo $this->lang->line("assets"); ?> :<select name="device" id="triplogdevice" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple='multiple'></select></td>
		<td width="3%"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
    </tr>
</table><br/>
</form>
<table id="trip_log_grid" class="jqgrid"></table> 
</div>
<div id="trip_log_pager"></div>
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