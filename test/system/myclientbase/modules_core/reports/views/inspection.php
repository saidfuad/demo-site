<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Search","Export");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'82');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	

?>
<script type="text/javascript">
loadMarkerClusters();
loadInfoBubble();
$("#loading_top").css("display","block");
</script>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
?>
<style>

#load_inspection_grid<?php echo time(); ?>
{
	display:none !important;
}
#ui_tpicker_hour_label_inspection_sdate,#ui_tpicker_hour_label_inspection_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_inspection_sdate,#ui_tpicker_minute_label_inspection_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_inspection_sdate,#ui_tpicker_second_label_inspection_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#inspection_grid<?php echo time(); ?>").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/inspection/loadData",
		datatype: "local",
		colNames:["<?php $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Datetime"); ?>','<?php echo $this->lang->line("Asset_Name"); ?>', '<?php echo $this->lang->line("Address"); ?>', '<?php echo $this->lang->line("Speed"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"assets_name",editable:true, index:"assets_name", width:180, align:"center",jsonmap:"assets_name"},
			{name:"address",editable:true, index:"address", width:250, align:"center", jsonmap:"address"},
			{name:"speed",editable:true, index:"speed", width:60, align:"center", jsonmap:"speed"},
			{name:"actions",editable:true, index:"id", width:60, align:"center", jsonmap:"actions"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#inspection_pager<?php echo time(); ?>"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#inspection_grid<?php echo time(); ?>").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		caption:"<?php echo $this->lang->line("Inspection Report"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
<?php
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#inspection_grid<?php echo time(); ?>").jqGrid("navGrid", "#inspection_pager<?php echo time(); ?>", {add:false, edit:false, del:false, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#inspection_grid<?php echo time(); ?>").jqGrid("navButtonAdd","#inspection_pager<?php echo time(); ?>",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#inspection_sdate').val();
			var edate = $('#inspection_edate').val();
			//var device = $('#inspection_device').val();
			var dev="";
			for(i=0;i<assets_count;i++){
				if($("#ddcl-inspection_device-i"+i).is(':checked')){
					dev+=$("#ddcl-inspection_device-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/inspection/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#inspection_device").html(assets_combo_opt_report);
	//$(".date").datepicker('setDate', new Date());
	cancelloading();
	//$(".date").datepicker({dateFormat:'dd.mm.yy',changeMonth: true,changeYear: true});
	//jQuery("input:button, input:submit, input:reset").button();	
	$("#inspection_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#inspection_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});

	$("#inspection_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("12:00:00 am")); ?>'));
	$("#inspection_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("11:59:59 pm")); ?>'));
       
    // $("#inspection_sdate").val('<?php echo date($date_format." ".$time_format);?>');
	//$("#inspection_edate").val('<?php echo date($date_format." ".$time_format);?>');
	
        //$("#inspection_sdate").val('<?php echo date($date_format." ".$time_format);?>');
	//$("#inspection_edate").val('<?php echo date($date_format." ".$time_format);?>');

       

	$("#inspection_device").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b><?php echo $this->lang->line("All Assets"); ?></b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-inspection_device").css('vertical-align','middle');
	$("#ddcl-inspection_device-ddw").css('overflow-x','hidden');
	$("#ddcl-inspection_device-ddw").css('overflow-y','auto');
	$("#ddcl-inspection_device-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
}); 
function searchinspection(){
	$("#inspection_grid_div<?php echo time(); ?>").show();
	var sdate = $('#inspection_sdate').val();
	var edate = $('#inspection_edate').val();
	//var device = $('#inspection_device').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-inspection_device-i"+i).is(':checked')){
			dev+=$("#ddcl-inspection_device-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#inspection_grid<?php echo time(); ?>").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	return false;	
}
function viewLocationInspection(id){
	var nameToCheck = "Inspection Report Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i){
		if (this.text == nameToCheck){
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			//window.location.href ='reports/dealer_stopreport/view_map/0/id/'+id+'/asset/'+asset;
			$('#tabs').tabs('add', 'reports/dealer_stopreport/view_map/0/id/'+id, 'Inspection Report Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', 'reports/inspection/view_map/0/id/'+id, 'Inspection Report Map');
	}
}
</script>
<?php
	$timestamp = strtotime("+2 day");
	$tomorrow=date($date_format." ".$time_format,$timestamp);
?>

<div id="inspection_list_div">
<form onsubmit="return searchinspection()">
	<table border="5" width="100%" class="formtable" style="margin-bottom: 5px;">
		<tr>
			<td width="30%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="inspection_sdate" class="date text ui-widget-content ui-corner-all" style="width:160px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
			<td width="30%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="inspection_edate" class="date text ui-widget-content ui-corner-all" style="width:160px" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td>
			<td width="30%"><?php echo $this->lang->line("Assets"); ?> : <select name="device" id="inspection_device" class="select ui-widget-content ui-corner-all" style="width:150px" multiple='multiple'></select></td>
			<td width="10%"><input type="submit" value="<?php echo $this->lang->line("grid_view"); ?>"/>
			</td>
		</tr>
	</table>
</form> 
<div id="inspection_grid_div<?php echo time(); ?>">
<table id="inspection_grid<?php echo time(); ?>" class="jqgrid"></table>
<div id="inspection_pager<?php echo time(); ?>"></div>
</div>
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