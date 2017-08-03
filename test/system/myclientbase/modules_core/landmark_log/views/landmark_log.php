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
#load_landmark_log_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){  
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#landmark_log_grid").jqGrid({
		url:"<?php echo site_url('landmark_log/loadData'); ?>", 
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Assests Name(Device)"); ?>','<?php echo $this->lang->line("Landmark_Name"); ?>','<?php echo $this->lang->line("Date Time"); ?>','<?php echo $this->lang->line("Distance From Landmark"); ?>','<?php echo $this->lang->line("View_on_Map"); ?>'],  
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"device_id",editable:true, index:"device_id", width:200, align:"center", jsonmap:"device_name"},
			{name:"landmark_id",editable:true, index:"landmark_id", width:180, align:"center", jsonmap:"landmark_name"},
			{name:"date_time",editable:true, index:"date_time", width:180, align:"center", jsonmap:"date_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			//{name:"lat",editable:true, index:"lat", width:180, align:"center", jsonmap:"lat"},  
			//{name:"lng",editable:true, index:"lng", width:180, align:"center", jsonmap:"lng"},
			{name:"distance",editable:true, index:"distance", width:180, align:"center", jsonmap:"distance"},
			{name:"view_on_map",editable:true, index:"id", width:100, align:"center", jsonmap:"view_on_map",formatter:view_on_map_format}
		], 
		rowNum:grid_paging,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#landmark_log_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#landmark_log_grid").setGridParam({datatype: 'json'}); 
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
	
	jQuery("#landmark_log_grid").jqGrid("navGrid", "#landmark_log_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});

	$("#landmark_log_pager option[value=10000]").text('All');
	$("#landmark_log_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#landmark_log_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});
	
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#landmark_log_grid").jqGrid("navButtonAdd","#landmark_log_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var lanamarksdate = $('#lanamarksdate').val();
			var lanamarkedate = $('#lanamarkedate').val();
			//var devicename = $("#lanamarkdevice").val();
			var dev="";
			for(i=0;i<=assets_count;i++){
				if($("#ddcl-lanamarkdevice-i"+i).is(':checked')){
					dev+=$("#ddcl-lanamarkdevice-i"+i).val()+",";
				}
			}
			var myPostData = $('#landmark_log_grid').jqGrid("getGridParam", "postData");
			var sidx = myPostData.sidx;
			var sord = myPostData.sord;
			// sdate="+sdate+"&edate="+edate+"&
			qrystr ="/cmd/export?sdate="+lanamarksdate+"&edate="+lanamarkedate+"&device="+dev+"&sidx="+sidx+"&sord="+sord;
			document.location = "<?php echo site_url('landmark_log/loadData'); ?>"+qrystr;
	
		}
	});
	<?php } ?>
	 $("#lanamarkdevice").html(assets_combo_opt_report);
	 $("#lanamarksdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	 $("#lanamarkedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	 $("#lanamarksdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s");?>'));
	 $("#lanamarkedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s");?>'));
	 $("#loading_top").css("display","none");
	 $("#lanamarkdevice").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b><?php echo $this->lang->line("All Assets"); ?></b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-lanamarkdevice").css('vertical-align','middle');
	$("#ddcl-lanamarkdevice-ddw").css('overflow-x','hidden');
	$("#ddcl-lanamarkdevice-ddw").css('overflow-y','auto');
	$("#ddcl-lanamarkdevice-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
});

function searchlandmark_log(){	
	var lanamarksdate = $('#lanamarksdate').val();
	var lanamarkedate = $('#lanamarkedate').val();
	//var devicename = $("#lanamarkdevice").val();
	//$("#allpoints_list").flexOptions({params: [{name:'lanamarksdate', value: lanamarksdate},{name:'lanamarkedate',value:lanamarkedate},{name:'device',value:device}]}).flexReload(); 
	var dev="";
	
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-lanamarkdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-lanamarkdevice-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#landmark_log_grid").jqGrid('setGridParam',{postData:{sdate:lanamarksdate, edate:lanamarkedate, device : dev,  page:1}}).trigger("reloadGrid");
	
	return false;	
}
function view_on_map_format(cellvalue, options, rowObject){
	if(rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
	//	var html="Login Time : "+rowObject.last_login_time+"<br>Logout Time : "+rowObject.last_logout_time+"<br>IP Address : "+rowObject.ip_address+"<br>Duration Of Stay : "+rowObject.duration_of_stay+"<br>";
		return "<a href='#' onclick='view_map_landmark_log(\""+rowObject.id+"\")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-BLUE1-BLANK.png'></a>";
	}
	return '&nbsp;';
}
function view_map_landmark_log(id){ 
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/landmark_log/view_map?cmd=landmark_log&id="+id, '<?php echo $this->lang->line("View LandMark Log Det"); ?>', 1);
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
<!--
<form onsubmit="return searchallpoints()">
<table width="100%">
	<tr>
		<td width="20%">Start : <input type="text" name="lanamarksdate_landmark_log" id="lanamarksdate_landmark_log" class="date text ui-widget-content ui-corner-all" style="width:120px" value="<?php echo date('1.m.Y'); ?>" readonly="readonly"/></td>
		<td width="20%">End : <input type="text" name="lanamarkedate_landmark_log" id="lanamarkedate_landmark_log" class="date text ui-widget-content ui-corner-all" style="width:120px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="View"/></td>
	</tr>
</table>
</form>
-->
<?php
	$timestamp=date("d.m.Y");
	$timestamp = strtotime("+2 day");
	$tomorrow=strftime( "%d.%m.%Y",$timestamp); 
?>
<div id="landmark_log_list_div">
<form onsubmit="return searchlandmark_log()">
<table width="100%" class="formtable">
	<tr>
		<td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="lanamarksdate" id="lanamarksdate" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
			<td width="10%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="lanamarkedate" id="lanamarkedate" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="14%"><?php echo $this->lang->line("assets"); ?> :<select name="device" id="lanamarkdevice" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple='multiple'></select></td>
		<td width="3%"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
       
    </tr>
</table><br/>
</form>
<table id="landmark_log_grid" class="jqgrid"></table>
</div>
<div id="landmark_log_pager"></div>
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
