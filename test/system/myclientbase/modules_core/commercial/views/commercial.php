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
		$va1l->where("menu_id",'21');
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
#load_commercial_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">       
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input :button, input:submit, input:reset").button(); 
	jQuery("#commercial_grid").jqGrid({
		url:"<?php echo site_url('commercial/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Date-Time"); ?>','<?php echo $this->lang->line("Mobile_No"); ?>','<?php echo $this->lang->line("SMS_Text"); ?>','<?php echo $this->lang->line("Payment_Status"); ?>'],
		colModel:[  
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			//{name:"add_date",editable:true, index:"add_date",  align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"},formatter:payment_status_forother},     
			{name:"add_date",editable:true, index:"add_date",  align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},   
			{name:"mobile",editable:true, index:"mobile",  align:"center", jsonmap:"mobile",formatter:payment_status_forother},
			{name:"sms_text",editable:true, index:"sms_text", width:450, align:"center", jsonmap:"sms_text",formatter:payment_status_forother},
			{name:"payment_status",editable:true, index:"payment_status",  align:"center", jsonmap:"payment_status",formatter:payment_status},
		], 
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#commercial_pager"),
		sortname: "id",
		loadComplete: function(){
			 $("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : true, 
		userDataOnFooter : true,
		caption:"<?php echo $this->lang->line("Commercial List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#commercial_grid").jqGrid("navGrid", "#commercial_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#commercial_grid").jqGrid("navButtonAdd","#commercial_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var commercial_sdate = $('#commercial_sdate').val();
			var commercial_edate = $('#commercial_edate').val();
			var device = $('#device').val();
			qrystr ="/cmd/export?commercial_sdate="+commercial_sdate+"&commercial_edate="+commercial_edate+"&device="+device;
			document.location = "<?php echo site_url('commercial/loadData'); ?>"+qrystr;
		}
	});
	<?php } ?>
	 $("#loading_top").css("display","none");
});

function search_commercial(){
	
	var commercial_sdate = $('#commercial_sdate').val();
	var commercial_edate = $('#commercial_edate').val();
	var device = $('#device').val();
	
	//$("#allpoints_list").flexOptions({params: [{name:'commercial_sdate', value: commercial_sdate},{name:'commercial_edate',value:commercial_edate},{name:'device',value:device}]}).flexReload();
	 $("#loading_top").css("display","block");
	jQuery("#commercial_grid").jqGrid('setGridParam',{postData:{commercial_sdate:commercial_sdate, commercial_edate:commercial_edate, device:device, page:1}}).trigger("reloadGrid");
	return false;
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
	$timestamp = strtotime("-2 day");
	$tomorrow=date($date_format,$timestamp);
?>
<div id="commercial_list_div">
<form onsubmit="return search_commercial()">
<table width="100%">
	<tr>
		<td width="2%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="commercial_sdate" id="commercial_sdate" class="date text ui-widget-content ui-corner-all" style="width:110px;padding:5px;" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td>
		<td width="2%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="commercial_edate" id="commercial_edate" class="date text ui-widget-content ui-corner-all" style="width:110px;padding:5px;" value="<?php echo date($date_format); ?>" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
</form>
<table id="commercial_grid" class="jqgrid"></table>
</div>
<div id="commercial_pager"></div>
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