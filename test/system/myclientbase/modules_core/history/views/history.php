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
#load_history_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
jQuery().ready(function (){ 
   
	
	$("#sdate_history").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#edate_history").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#sdate_history").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("12:00:00 am")); ?>'));
	$("#edate_history").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("11:59:59 pm")); ?>'));
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#history_grid").jqGrid({
		url:"<?php echo site_url('history/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Last Login Date & Time"); ?>','<?php echo $this->lang->line("Last Logout Date & Time"); ?>','<?php echo $this->lang->line("IP Address"); ?>','<?php echo $this->lang->line("Country Name"); ?>','<?php echo $this->lang->line("City Name"); ?>','<?php echo $this->lang->line("Os Name"); ?>','<?php echo "Browser"; ?>','<?php echo $this->lang->line("Duration Of Stay(In Min.)"); ?>','<?php echo $this->lang->line("Langitute"); ?>','<?php echo $this->lang->line("Longitute"); ?>','<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"last_login_time",editable:true, index:"last_login_time", width:200, align:"center", jsonmap:"last_login_time" ,formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"last_logout_time",editable:true, index:"last_logout_time", width:200, align:"center", jsonmap:"last_logout_time" ,formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"ip_address",editable:true, index:"ip_address", width:100, align:"center", jsonmap:"ip_address"},
			{name:"country_name",editable:true, index:"country_name", width:100, align:"center", jsonmap:"country_name"},
			
			{name:"city_name",editable:true, index:"city_name", width:100, align:"center", jsonmap:"city_name"},
			{name:"os_name",editable:true, index:"os_name", width:100, align:"center", jsonmap:"os_name"},
			{name:"device",editable:true, index:"device", width:100, align:"center", jsonmap:"device"},
			{name:"duration_of_stay",editable:true, index:"duration_of_stay", width:100, align:"center", jsonmap:"duration_of_stay"},
			{name:"latitude",hidden:true, index:"latitude", width:100, align:"center", jsonmap:"latitude"},
			{name:"longitude",hidden:true, index:"longitude", width:100, align:"center", jsonmap:"longitude"},
			{name:"view_on_map",editable:true, index:"id", width:100, align:"center", jsonmap:"view_on_map",formatter:view_on_map_format}
		],
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#history_pager"),
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
		caption:"<?php echo $this->lang->line("History List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#history_grid").jqGrid("navGrid", "#history_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#history_grid").jqGrid("navButtonAdd","#history_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
		
			qrystr ="/cmd/export";
			document.location = "<?php echo site_url('history/loadData'); ?>"+qrystr;
		}
	});
	 $("#loading_top").css("display","none");
});

function search_history(){
	
	var sdate_history = $('#sdate_history').val();
	var edate_history = $('#edate_history').val();
	
	//$("#allpoints_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","blocks");
	jQuery("#history_grid").jqGrid('setGridParam',{postData:{sdate_history:sdate_history, edate_history:edate_history,  page:1}}).trigger("reloadGrid");
	return false;	
}
 
function view_on_map_format(cellvalue, options, rowObject){
	if(rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
	//	var html="Login Time : "+rowObject.last_login_time+"<br>Logout Time : "+rowObject.last_logout_time+"<br>IP Address : "+rowObject.ip_address+"<br>Duration Of Stay : "+rowObject.duration_of_stay+"<br>";
		return "<a href='#' onclick='view_map(\""+rowObject.id+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-RED-BLANK.png'></a>";
	}
	return '&nbsp;';
}
function view_map(id){
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/history/view_map?cmd=history&id="+id, '<?php echo $this->lang->line("View Login"); ?>', 1);
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
<form onsubmit="return search_history()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate_history" id="sdate_history" class="date text ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 54%;" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate_history" id="edate_history" class="date text ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 54%;" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<div id="history_list_div">
	<table id="history_grid" class="jqgrid"></table>
</div>
<div id="history_pager"></div>
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