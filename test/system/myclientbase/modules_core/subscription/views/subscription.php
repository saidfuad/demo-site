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
		$va1l->where("menu_id",'22');
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
#load_subscription_grid
{
	display:none !important;
}
</style>
<script type="text/javascript">
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#subscription_grid").jqGrid({
		url:"<?php echo site_url('subscription/loadData'); ?>",
		datatype: "json", 
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Subcription_Date"); ?>','<?php echo $this->lang->line("Subcription_Valide_From"); ?>','<?php echo $this->lang->line("Subcription_Valide_To"); ?>','<?php echo $this->lang->line("Subcription Charges"); ?>','<?php echo $this->lang->line("Payment_Status"); ?>'], 
		colModel:[ 
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"sub_date",editable:true, index:"sub_date", width:90, align:"center", jsonmap:"sub_date" ,formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}},
			{name:"sub_valide_from",editable:true, index:"sub_valide_from", width:90, align:"center", jsonmap:"sub_valide_from" ,formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}},
			{name:"sub_valide_to",editable:true, index:"sub_valide_to", width:90, align:"center", jsonmap:"sub_valide_to" ,formatter: "date", formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}}, 
			{name:"sub_charges",editable:true, index:"sub_charges", width:90, align:"center", jsonmap:"sub_charges"},
			{name:"payment_status",editable:true, index:"payment_status",  align:"center", jsonmap:"payment_status",formatter:payment_status},
		], 
		rowNum:20,
		height: "100%",
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#subscription_pager"),
		sortname: "id",
		loadComplete: function(){
			 $("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : true,
		caption:"<?php echo $this->lang->line("subscription List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	<?php
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#subscription_grid").jqGrid("navGrid", "#subscription_pager", {add:false, edit:false, del:false, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#subscription_grid").jqGrid("navButtonAdd","#subscription_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
		
			qrystr ="/cmd/export";
			document.location = "<?php echo site_url('subscription/loadData'); ?>"+qrystr;
		}
	});
	<?php } ?>
	 $("#loading_top").css("display","none");
});

function search_subscription(){
	
	var subscription_sdate = $('#subscription_sdate').val();
	var subscription_edate = $('#subscription_edate').val();
	var device = $('#device').val();
	
	//$("#allpoints_list").flexOptions({params: [{name:'subscription_sdate', value: subscription_sdate},{name:'subscription_edate',value:subscription_edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");
	jQuery("#subscription_grid").jqGrid('setGridParam',{postData:{subscription_sdate:subscription_sdate, subscription_edate:subscription_edate, device:device, page:1}}).trigger("reloadGrid");
	
	return false;	
}

function payment_status_forother(cellvalue, options, rowObject){
	if(rowObject.payment_status==0)
		return "<span style='color:red'>"+cellvalue+"</span>";
	else
		return "<span style='color:green'>"+cellvalue+"</span>";
}
function payment_status(cellvalue, options, rowObject){
	if(cellvalue==0)
		return "<span style='color:red'>Unpaid</span>";
	else
		return "<span style='color:green'>Paid</span>";
//	rowObject.account
}

</script>
<?php
	$timestamp = strtotime("+2 day");
	$tomorrow=date($date_format,$timestamp);
?>
<div id="subscription_list_div">
<form onsubmit="return search_subscription()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="subscription_sdate" id="subscription_sdate" class="date text ui-widget-content ui-corner-all" style="width:110px;padding:5px;" value="<?php echo date($date_format); ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="subscription_edate" id="subscription_edate" class="date text ui-widget-content ui-corner-all" style="width:110px;padding:5px;" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
</form>

	<table id="subscription_grid" class="jqgrid"></table>
</div>
<div id="subscription_pager"></div>
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