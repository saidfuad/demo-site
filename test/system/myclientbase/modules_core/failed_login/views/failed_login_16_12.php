<?php
	//session date & time format
	$date_format = $this->session->userdata('date_format');  
	$time_format = $this->session->userdata('time_format'); 
?>
<style>
#load_failed_login_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#failed_login_grid").jqGrid({
		url:"<?php echo site_url('failed_login/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("IP Address"); ?>','<?php echo $this->lang->line("Date & Time"); ?>','<?php echo $this->lang->line("Country Name"); ?>','<?php echo $this->lang->line("State Name"); ?>','<?php echo $this->lang->line("City Name"); ?>','<?php echo $this->lang->line("Os Name"); ?>','<?php echo $this->lang->line("Device"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"ip_address",editable:true, index:"ip_address", width:90, align:"center", jsonmap:"ip_address"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"center", jsonmap:"add_date",formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"country_name",editable:true, index:"country_name", width:90, align:"center", jsonmap:"country_name"},
			{name:"state_name",editable:true, index:"state_name", width:90, align:"center", jsonmap:"state_name"},
			{name:"city_name",editable:true, index:"city_name", width:90, align:"center", jsonmap:"city_name"},
			{name:"os_name",editable:true, index:"os_name", width:90, align:"center", jsonmap:"os_name"},
			{name:"device",editable:true, index:"device", width:90, align:"center", jsonmap:"device"}
		],
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#failed_login_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false,
		caption:"<?php echo $this->lang->line("Failed Login List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#failed_login_grid").jqGrid("navGrid", "#failed_login_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#failed_login_grid").jqGrid("navButtonAdd","#failed_login_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
		
			qrystr ="/cmd/export";
			document.location = "<?php echo site_url('failed_login/loadData'); ?>"+qrystr;
		}
	});
	$("#loading_top").css("display","none");
});

function search_failed_login(){
	var fail_login_sdate = $('#fail_login_sdate').val();
	var fail_login_edate = $('#fail_login_edate').val();
	var device = $('#device').val();
	
	//$("#allpoints_list").flexOptions({params: [{name:'fail_login_sdate', value: fail_login_sdate},{name:'fail_login_edate',value:fail_login_edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");
	jQuery("#failed_login_grid").jqGrid('setGridParam',{postData:{fail_login_sdate:fail_login_sdate, fail_login_edate:fail_login_edate, device:device, page:1}}).trigger("reloadGrid");
	
	return false;	
}



</script>
<div id="failed_login_list_div">
<form onsubmit="return search_failed_login()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="fail_login_sdate" id="fail_login_sdate" class="date text ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 54%;" value="<?php echo date('1.m.Y'); ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="fail_login_edate" id="fail_login_edate" class="date text ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 54%;" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
	<table id="failed_login_grid" class="jqgrid"></table>
</div>
<div id="failed_login_pager"></div>
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