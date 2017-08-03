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
		$va1l->where("menu_id",'6');
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
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
?>
<style>
#load_in_landmark_grid
{
	display:none !important; 
}/*
#ui_tpicker_hour_label_in_landmark_sdate,#ui_tpicker_hour_label_in_landmark_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_in_landmark_sdate,#ui_tpicker_minute_label_in_landmark_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_in_landmark_sdate,#ui_tpicker_second_label_in_landmark_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}*/
dt
{
	width:auto !important
}
</style>

<script type="text/javascript">
var assets_nm;
jQuery().ready(function (){

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#in_landmark_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/in_landmark/loadData",
		datatype: "local",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset_Name"); ?>'],
		colModel:[
			{name:"id",index:"tm.id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:150, align:"center", jsonmap:"assets_name"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#in_landmark_pager"),
		sortname: "am.assets_name",
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			$("#in_landmark_grid").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"Vehicle In Landmark",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#in_landmark_grid").jqGrid("navGrid", "#in_landmark_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#in_landmark_grid").jqGrid("navButtonAdd","#in_landmark_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#in_landmark_sdate').val();
			var edate = $('#in_landmark_edate').val();
			var in_landmark_id = $('#in_landmark_id').val();
		 	var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&landmark="+in_landmark_id;
			document.location = "<?php echo base_url(); ?>index.php/reports/in_landmark/loaddata"+qrystr;
		}
	});
	<?php } ?>
	$("#in_landmark_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#in_landmark_alert_dialog").dialog("close")',5000);
		}
	});
		
	$("#in_landmark_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#in_landmark_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	
	$("#in_landmark_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#in_landmark_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	
});
function searchin_landmark(){
	var sdate = $('#in_landmark_sdate').val();
	var edate = $('#in_landmark_edate').val();
	var in_landmark_id = $('#in_landmark_id').val();
	
	$("#loading_top").css("display","block");
	jQuery("#in_landmark_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate, landmark:in_landmark_id, page:1}}).trigger("reloadGrid");
	return false;
}

$(document).ready(function(){	
	jQuery("input:button, input:submit, input:reset").button();
});
function CancelReq()
{
	jQuery("#in_landmark_grid").jqGrid().stop();
}
</script>
<div id="in_landmark_list_div">
<form onsubmit="return searchin_landmark()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="in_landmark_sdate" class="date text ui-widget-content ui-corner-all" style="width:160px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="in_landmark_edate" class="date text ui-widget-content ui-corner-all" style="width:160px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td><td width="20%" style="vertical-align:middle">Landmark : <select name="in_landmark_id" id="in_landmark_id" class="select ui-widget-content ui-corner-all" style="width:70% !important"><?php echo $landmark; ?></select></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<table id="in_landmark_grid" class="jqgrid"></table>
<div id="in_landmark_alert_dialog"></div>
<div id="in_landmark_pager"></div>
</div>
<script type="text/javascript">
//window.onbeforeunload = function(event){ event.preventDefault;}
</script>
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