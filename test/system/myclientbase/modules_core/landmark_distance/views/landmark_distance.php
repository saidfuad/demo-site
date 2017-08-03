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
		$va1l->where("menu_id",'50');
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
#load_landmark_distance_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){  
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#landmark_distance_grid").jqGrid({
		url:"<?php echo site_url('landmark_distance/loadData'); ?>", 
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Assets Name"); ?>','<?php echo $this->lang->line("Distance From Landmark"); ?>','<?php echo $this->lang->line("View_on_Map"); ?>'],  
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:200, align:"center", jsonmap:"assets_name"},
			{name:"distance",editable:true, index:"distance", width:200, align:"center", jsonmap:"distance"},
			{name:"view_on_map", hidden:true, index:"id", width:100, align:"center", jsonmap:"view_on_map",formatter:view_on_map_format}
		], 
		rowNum:grid_paging,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#landmark_distance_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#landmark_distance_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false,
		caption:"<?php echo $this->lang->line("Landmark Log List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#landmark_distance_grid").jqGrid("navGrid", "#landmark_distance_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	$("#landmark_distance_pager option[value=10000]").text('All');
	$("#landmark_distance_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#landmark_distance_pager .ui-pg-selbox").val();
	});
	

	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#landmark_distance_grid").jqGrid("navButtonAdd","#landmark_distance_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var landmark = $('#landmark_distance_id').val();
			var distance = $('#landmark_distance_value').val();
			if(landmark == ''){
				$("#alert_dialog").html("Please select landmark");
				$("#alert_dialog").dialog("open");
				return false;
			}
			if(distance == ''){
				$("#alert_dialog").html("Please insert distance");
				$("#alert_dialog").dialog("open");
				return false;
			}
			var myPostData = $('#landmark_distance_grid').jqGrid("getGridParam", "postData");
			var sidx = myPostData.sidx;
			var sord = myPostData.sord;
			// sdate="+sdate+"&edate="+edate+"&
			qrystr ="/cmd/export?landmark="+landmark+"&distance="+distance+"&sidx="+sidx+"&sord="+sord;
			document.location = "<?php echo site_url('landmark_distance/loadData'); ?>"+qrystr;
		
		}
	});
	<?php } ?>
});

function searchlandmark_distance(){	
	var landmark = $('#landmark_distance_id').val();
	var distance = $('#landmark_distance_value').val();
	if(landmark == ''){
		$("#alert_dialog").html("<?php echo $this->lang->line("Please select landmark"); ?>");
		$("#alert_dialog").dialog("open");
		return false;
	}
	if(distance == ''){
		$("#alert_dialog").html("Please insert distance");
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#landmark_distance_grid").jqGrid('setGridParam',{postData:{landmark:landmark, distance:distance,  page:1}}).trigger("reloadGrid");
	
	return false;	
}
function view_on_map_format(cellvalue, options, rowObject){
	if(rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
	//	var html="Login Time : "+rowObject.last_login_time+"<br>Logout Time : "+rowObject.last_logout_time+"<br>IP Address : "+rowObject.ip_address+"<br>Duration Of Stay : "+rowObject.duration_of_stay+"<br>";
		return "<a href='#' onclick='view_map_landmark_distance(\""+rowObject.id+"\")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-BLUE1-BLANK.png'></a>";
	}
	return '&nbsp;';
}
function view_map_landmark_distance(id){ 
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/landmark_distance/view_map?cmd=landmark_distance&id="+id, 'View LandMark Log Det. ', 1);
	//viewLocation(lat,lang,html);
}

</script>
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="landmark_distance_list_div">
<form onsubmit="return searchlandmark_distance()">
<table width="100%" class="formtable">
	<tr>	
		<td width="14%"><?php echo $this->lang->line("Landmark"); ?> :<select id="landmark_distance_id" class="select ui-widget-content ui-corner-all" style="width:50% !important"><option value=''><?php echo $this->lang->line("Please Select"); ?></option><?php echo $landmark; ?></select></td>
		<td width="10%"><?php echo $this->lang->line("Distance"); ?> : <input type="text" id="landmark_distance_value" class="text ui-widget-content ui-corner-all" style="width:110px" value="10"/></td>
		<td width="3%"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
       
    </tr>
</table><br/>
</form>
<table id="landmark_distance_grid" class="jqgrid"></table>
</div>
<div id="landmark_distance_pager"></div>
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
