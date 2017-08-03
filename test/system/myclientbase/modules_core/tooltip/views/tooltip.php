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
#load_tooltip_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
jQuery().ready(function (){ 
    $("#tootip_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#tootip_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#tootip_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("12:00:00 am")); ?>'));
	$("#tootip_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("11:59:59 pm")); ?>'));
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#tooltip_grid").jqGrid({
		url:"<?php echo site_url('tooltip/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Header"); ?>','<?php echo $this->lang->line("String"); ?>','<?php //echo $this->lang->line("Link"); ?>','<?php echo $this->lang->line("Popup Type"); ?>'],
		colModel:[ 
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"header",editable:true, index:"header", width:100, align:"center", jsonmap:"header"},
			{name:"string",editable:true, index:"string", width:200, align:"center", jsonmap:"string"},
			{name:"link",editable:true, hidden:true,index:"link", width:50, align:"center", jsonmap:"link", formatter:formatStage_Report, search:false},
			{name:"type",editable:true, index:"type", width:100, align:"center", jsonmap:"type"}
		], 
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#tooltip_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : true,
		caption:"<?php echo $this->lang->line("Popup List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#tooltip_grid").jqGrid("navGrid", "#tooltip_pager", {add:false, edit:false, del:false, search:true}, {}, {}, {}, {multipleSearch:false});
	/*
	jQuery("#tooltip_grid").jqGrid("navButtonAdd","#tooltip_pager",{caption:"Export",
		onClickButton:function(){
		
			qrystr ="/cmd/export";
			document.location = "<?php echo site_url('tooltip/loadData'); ?>"+qrystr;
		}
	});
	*/
	$("#loading_top").css("display","none");
});

function search_tooltip_popups(){
	
	var tootip_sdate = $('#tootip_sdate').val();
	var tootip_edate = $('#tootip_edate').val();
		
	//$("#allpoints_list").flexOptions({params: [{name:'tootip_sdate', value: tootip_sdate},{name:'tootip_edate',value:tootip_edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");
	jQuery("#tooltip_grid").jqGrid('setGridParam',{postData:{tootip_sdate:tootip_sdate, tootip_edate:tootip_edate, page:1}}).trigger("reloadGrid");
	
	return false;	
}

function payment_status_forother(cellvalue, options, rowObject){
	if(rowObject.payment_status==0)
		return "<span style='color:red'>"+cellvalue+"</span>";
	else
		return "<span style='color:green'>"+cellvalue+"</span>";
}
function formatStage_Report(cellvalue, options, rowObject){
	
	if(cellvalue != null && cellvalue !="") { 
		var header  = rowObject.header;
		var link  = rowObject.link;
//		cellVal = "<a class=\"show_details\" href='dashboard.php'>" + cellVal + "</a>"; 
		cellvalue = "<a href='javascript:void(0);' style='text-decoration: underline; color:#15428B;' onclick=\"javascript:topMenuToTab('"+cellvalue+"','"+header+"','"+header+"')\"> More </a>";
		return cellvalue;
	}
	return '&nbsp;';
//   $(el).html(cellval);
}                                                                  

</script>
<div id="tooltip_list_div">
<form onsubmit="return search_tooltip_popups()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="tootip_sdate" id="tootip_sdate" class="date text ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 54%;" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="tootip_edate" id="tootip_edate" class="date text ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 54%;" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
		
</form>

	<table id="tooltip_grid" class="jqgrid"></table>
</div> 
<div id="tooltip_pager"></div>
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