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
		$va1l->where("menu_id",'117');
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
#load_data_logs_grid
{
	display:none !important; 
}
#data_logs_grid td {           
    word-wrap: break-word; /* IE 5.5+ and CSS3 */
    white-space: pre-wrap; /* CSS3 */
    white-space: -pre-wrap; /* Opera 4-6 */
    white-space: -o-pre-wrap; /* Opera 7 */
    white-space: normal !important;
    height: auto;
    vertical-align: text-top;
    padding-top: 2px;
    padding-bottom: 3px;
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
var assets_nm;
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#data_logs_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/data_logs/loadData",
		datatype: "json",
		//,'Device ID'
		colNames:["<?php echo $this->lang->line("ID"); ?>", '<?php echo $this->lang->line("Datetime"); ?>','<?php echo $this->lang->line("Raw Data"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"left", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
//			{name:"device_id",editable:true, index:"device_id", width:60, align:"left", jsonmap:"device_id"},
			{name:"raw_data",editable:true, index:"raw_data", width:900, align:"left", jsonmap:"raw_data"},
			
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#data_logs_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#data_logs_grid").setGridParam({datatype: 'json'}); 
		},	
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Data Logs"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#data_logs_grid").jqGrid("navGrid", "#data_logs_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#data_logs_grid").jqGrid("navButtonAdd","#data_logs_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#sdate_data_logs').val();
			var edate = $('#edate_data_logs').val();
			var device = $('#data_logs_device').val();
			var speed = $('#speed').val();
			
			var qrystr ="?cmd=export&sdate="+sdate+"&edate="+edate+"&device="+device;
			document.location = "<?php echo base_url(); ?>index.php/reports/data_logs/loadData"+qrystr;
		}
	});
	<?php } ?>	
	$("#sdate_data_logs").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#edate_data_logs").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	
	$("#sdate_data_logs").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#edate_data_logs").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
//	$("#data_logs_device").html(assets_combo_opt);
//	$("#loading_top").css("display","none");
	
}); 

function searchdata_logs(){
	
	var sdate = $('#sdate_data_logs').val();
	var edate = $('#edate_data_logs').val();
	var dev=$('#data_logs_device').val();
	
	if(dev == ""){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	assets_nm=$('#data_logs_device option:selected').val();
	jQuery("#data_logs_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate,  device:dev, page:1}}).trigger("reloadGrid");
	
	
	return false;	
}
function format_data_logs_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_data_logs_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-BLUE1-BLANK.png'></a>";
}

function view_data_logs_map(id,asset){
	var nameToCheck = "Over Speed Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/data_logs/view_map/0/id/'+id+'/asset/'+asset, 'Over Speed Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/data_logs/view_map/0/id/'+id+'/asset/'+asset, 'Over Speed Map');
	}
}
function AllPointfilterAssetsCombolog(val, cid){
	$.post("<?php echo base_url(); ?>index.php/reports/data_logs/filter_assets", { grp: val},
	 function(data) {
		$("#"+cid).html("<option value=''><?php echo $this->lang->line("Please Select"); ?></option>"+data);
		$("#ddcl-"+cid).css('vertical-align','middle');
		$("#ddcl-"+cid+"-ddw").css('overflow-x','hidden');
		$("#ddcl-"+cid+"-ddw").css('overflow-y','auto');
		$("#ddcl-"+cid+"-ddw").css('height','200px');
		$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	 });
}
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="data_logs_list_div">
<form onsubmit="return searchdata_logs()">
		<table border="5" width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="sdate" id="sdate_data_logs" class="date text ui-widget-content ui-corner-all" style="width:180px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("to_date"); ?> : <input type="text" name="edate" id="edate_data_logs" class="date text ui-widget-content ui-corner-all" style="width:180px" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("Group"); ?> : <select onchange="AllPointfilterAssetsCombolog(this.value,'data_logs_device')" name="group" id="group_device_logs" class="select ui-widget-content ui-corner-all" ><?php echo $group1; ?></select></td>
		<td width="20%"><?php echo $this->lang->line("Assets"); ?> : <select name="device_1" id="data_logs_device" class="select ui-widget-content ui-corner-all"><?php echo $devices; ?></select></td>
	</tr>
	<tr>
		<td align="center" colspan="4"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/>
		
        </td>
        </tr></table>
</form>
</div>
<table id="data_logs_grid" class="jqgrid"></table>

<div id="data_logs_pager"></div>
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