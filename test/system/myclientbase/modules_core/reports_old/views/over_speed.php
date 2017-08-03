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
<style>
#load_over_speed_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#over_speed_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/over_speed/loadData",
		datatype: "local", 
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Assets"); ?>','Speed', 'Datetime', '<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"},
			{name:"speed",editable:true, index:"speed", width:150, align:"center", jsonmap:"speed"},
			{name:"add_date",editable:true, index:"add_date", width:120, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"map",editable:true, index:"map", width:60, align:"center", jsonmap:"map",formatter:format_over_speed_map},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#over_speed_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#over_speed_grid").setGridParam({datatype: 'json'}); 
		},	
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"Over Speed Report",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#over_speed_grid").jqGrid("navGrid", "#over_speed_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#over_speed_grid").jqGrid("navButtonAdd","#over_speed_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#sdate_over_speed').val();
			var edate = $('#edate_over_speed').val();
			var group = $('#group_over_speed').val();
			var speed = $('#speed').val();
			
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&group="+group+"&speed="+speed;
			document.location = "<?php echo base_url(); ?>index.php/reports/over_speed/loadData"+qrystr;
		}
	});
	<?php } ?>	
	$("#sdate_over_speed").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#edate_over_speed").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	
	$("#sdate_over_speed").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#edate_over_speed").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
}); 

function searchover_speed(){
	
	var sdate = $('#sdate_over_speed').val();
	var edate = $('#edate_over_speed').val();
	var group = $('#group_group_over_speed').val();
	var speed = $('#speed').val();
	$("#loading_top").css("display","block");
	jQuery("#over_speed_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate, speed:speed, group:group, page:1}}).trigger("reloadGrid");
	
	return false;	
}
function format_over_speed_map(cellVal, options, rowObject){
	return "<a href='#' onclick='view_over_speed_map("+rowObject.id+",\""+rowObject.assets_name+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-BLUE1-BLANK.png'></a>";
}

function view_over_speed_map(id,asset){
	var nameToCheck = "Over Speed Map";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('remove', $(this).attr("href"));
			$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/over_speed/view_map/0/id/'+id+'/asset/'+asset, 'Over Speed Map');
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', '<?php echo base_url(); ?>index.php/reports/over_speed/view_map/0/id/'+id+'/asset/'+asset, 'Over Speed Map');
	}
}
</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="over_speed_list_div">
<form onsubmit="return searchover_speed()">
<table width="100%" class="formtable">
	<tr>
		<td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="sdate" id="sdate_over_speed" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="10%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="edate" id="edate_over_speed" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="10%">Speed : <input type="text" name="speed" id="speed" class="text ui-widget-content ui-corner-all" style="width:80px" value="60" /></td>
		<td width="14%">Group : <select style="width:120px;" name="group" id="group_group_over_speed" class="select ui-widget-content ui-corner-all" ><?php echo $group; ?></select></td>		
		<td width="3%"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
        </tr></table><br/>
</form>
</div>
<table id="over_speed_grid" class="jqgrid"></table>

<div id="over_speed_pager"></div>
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