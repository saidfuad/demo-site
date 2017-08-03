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
		$va1l->where("menu_id",'83');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	

?>
<?php
	//session date & time format
	$date_format = $this->session->userdata('date_format');  
	$time_format = $this->session->userdata('time_format');  
	$js_date_format = $this->session->userdata('js_date_format');  
	$js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_dealer_login_grid
{
	display:none !important; 
}
.date_txt_dealer{
	margin-top: 5px;
    padding: 0.4em;
    width: 94%;
}
</style>
<script type="text/javascript">
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#dealer_login_grid").jqGrid({
		url:"<?php echo site_url('dealer_login/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>","<?php echo $this->lang->line("Dealer"); ?>",'<?php echo $this->lang->line("Last Login Date & Time"); ?>','<?php echo $this->lang->line("Last Logout Date & Time"); ?>','<?php echo $this->lang->line("IP Address"); ?>','<?php echo $this->lang->line("Country Name"); ?>','<?php echo $this->lang->line("State Name"); ?>','<?php echo $this->lang->line("City Name"); ?>','<?php echo $this->lang->line("Os Name"); ?>','<?php echo $this->lang->line("Device"); ?>','<?php echo $this->lang->line("Duration Of Stay(In Min.)"); ?>','<?php echo $this->lang->line("Langitute"); ?>','<?php echo $this->lang->line("Longitute"); ?>','<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"dealer",editable:true, index:"tu.first_name", width:200, align:"center", jsonmap:"dealer"},
			{name:"last_login_time",editable:true, index:"last_login_time", width:200, align:"center", jsonmap:"last_login_time" ,formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"last_logout_time",editable:true, index:"last_logout_time", width:200, align:"center", jsonmap:"last_logout_time" ,formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"ip_address",editable:true, index:"ip_address", width:100, align:"center", jsonmap:"ip_address"},
			{name:"country_name",editable:true, index:"country_name", width:100, align:"center", jsonmap:"country_name"},
			{name:"state_name",editable:true, index:"state_name", width:100, align:"center", jsonmap:"state_name"},
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
		pager: jQuery("#dealer_login_pager"),
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
		caption:"<?php echo $this->lang->line("Dealer Login Report"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#dealer_login_grid").jqGrid("navGrid", "#dealer_login_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#dealer_login_grid").jqGrid("navButtonAdd","#dealer_login_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#sdate_dealer_login').val();
			var edate = $('#edate_dealer_login').val();
			qrystr ="/cmd/export/sdate/"+sdate+"/edate/"+edate;
			document.location = "<?php echo site_url('dealer_login/loadData'); ?>"+qrystr;
		}
	});
	<?php } ?>
	$("#sdate_dealer_login").datepicker('setDate', new Date('<?php echo date('Y/m/d H:i:s'); ?>'));
	$("#edate_dealer_login").datepicker('setDate', new Date('<?php echo date('Y/m/d H:i:s'); ?>'));
	$("#loading_top").css("display","none");
});

function search_dealer_login(){
	
	var sdate = $('#sdate_dealer_login').val();
	var edate = $('#edate_dealer_login').val();
	
	//$("#allpoints_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","blocks");
	jQuery("#dealer_login_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate,  page:1}}).trigger("reloadGrid");
	return false;	
}
 
function view_on_map_format(cellvalue, options, rowObject){
	if(rowObject.latitude != "" && rowObject.latitude != 0 && rowObject.longitude!= "" && rowObject.longitude != 0 )
	{
	//	var html="Login Time : "+rowObject.last_login_time+"<br>Logout Time : "+rowObject.last_logout_time+"<br>IP Address : "+rowObject.ip_address+"<br>Duration Of Stay : "+rowObject.duration_of_stay+"<br>";
		return "<a href='#' onclick='view_map(\""+rowObject.id+"\")'> <img src='<?php echo base_url(); ?>assets/marker-images/mini-BLUE1-BLANK.png'></a>";
	}
	return '&nbsp;';
}
function view_map(id){
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/dealer_login/view_map?cmd=dealer_login&id="+id, 'View Login ', 1);
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
<form onsubmit="return search_dealer_login()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate_dealer_login" id="sdate_dealer_login" class="date_txt_dealer date text ui-widget-content ui-corner-all" style="width:128px" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate_dealer_login" id="edate_dealer_login" class="date_txt_dealer date text ui-widget-content ui-corner-all" style="width:128px" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<div id="dealer_login_list_div">
	<table id="dealer_login_grid" class="jqgrid"></table>
</div>
<div id="dealer_login_pager"></div>
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