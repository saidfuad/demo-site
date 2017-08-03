<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Add","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'102');
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
<?php
	$timestamp = strtotime("-30 day");
	$tomorrow=date($date_format,$timestamp);
?>
<?php $time=time(); ?>
<style>
#load_payment_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_payment_var_bitbot;
var payment_id;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#payment_grid").jqGrid({
		url:"<?php echo site_url('payment/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("User"); ?>', '<?php echo $this->lang->line("Payment Date"); ?>', '<?php echo $this->lang->line("Payment For"); ?>', '<?php echo $this->lang->line("Payment Type"); ?>', '<?php echo $this->lang->line("Cheque Number"); ?>', '<?php echo $this->lang->line("Amount"); ?>', '<?php echo $this->lang->line("Cheque Date"); ?>', '<?php echo $this->lang->line("Bank Name"); ?>', '<?php echo $this->lang->line("Comments"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"user",editable:true, index:"user", width:120, align:"center", jsonmap:"user"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}},
			{name:"payment_for",editable:true, index:"payment_for", width:120, align:"center", jsonmap:"payment_for"},
			{name:"payment_type",editable:true, index:"payment_type", width:120, align:"center", jsonmap:"payment_type"},
			{name:"amount",editable:true, index:"amount", width:120, align:"center", jsonmap:"amount"},
			{name:"cheque_number",editable:true, index:"cheque_number", width:120, align:"center", jsonmap:"cheque_number"},
			{name:"cheque_date",editable:true, index:"cheque_date", width:150, align:"center", jsonmap:"cheque_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?>"}},
			{name:"cheque_bank_name",editable:true, index:"cheque_bank_name", width:200, align:"center", jsonmap:"cheque_bank_name"},
			{name:"comments",editable:true, index:"comments", width:200, align:"center", jsonmap:"comments"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#payment_pager"),
		sortname: "id",
		viewrecords: true,
		footerrow : true, 
		userDataOnFooter : true,
		multiselect: false, 
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Payment List"); ?>",
		editurl:"<?php echo site_url('payment/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	$.jgrid.defaults.loadtext='123';
	<?php
	if(in_array('Delete',$data))
		$delete = "true";
	else
		$delete = "false";
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#payment_grid").jqGrid("navGrid", "#payment_pager", {add:false, edit:false, del : false, deltitle: 'Delete Records.....!',  search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	$(".delmsg").html("Delte msg");
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#payment_grid").jqGrid("navButtonAdd","#payment_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#payment_list_div').hide();
			$('#payment_form_div').show();
			$('#payment_form_div').load('<?php echo site_url('/payment/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#payment_grid").jqGrid("navButtonAdd","#payment_pager",{caption:"<?php echo $this->lang->line("edit"); ?>", title: "Edit?",
		onClickButton:function(){			
			var gsr = jQuery("#payment_grid").jqGrid("getGridParam","selrow");
			if(gsr){
				$("#loading_top").css("display","block");
				$('#payment_form_div').show();
				$('#payment_list_div').hide();
				$('#payment_form_div').load('<?php echo site_url('payment/form/id'); ?>/'+gsr);
				
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	<?php
	if(in_array('Delete',$data)){
	?>
	jQuery("#payment_grid").jqGrid("navButtonAdd","#payment_pager",{caption:"<?php echo $this->lang->line("delete"); ?>", title: "Delete?",
		onClickButton:function(){
			var gsr = jQuery("#payment_grid").jqGrid("getGridParam","selrow");
			if(gsr){
				payment_id = gsr;
				conf_dialog_payment_var_bitbot.dialog("open")
				
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_payment_var_bitbot=$("#conf_dialog_payment<?php $time=time(); ?>");
	conf_dialog_payment_var_bitbot.dialog({
		modal: true, title: 'Delete this item ?', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_payment_var_bitbot.dialog("close");
				deletePayment();
			},
			No: function () {
				conf_dialog_payment_var_bitbot.dialog("close");
			}
		},
	}); 
	
	cancelloading();
});
function submitFormPayment(id){
	$("#loading_top").css("display","block");
	$("#error_payment_frm").hide();
	var nm=$("#payment_name").val();
	
	$.post("<?php echo site_url('payment/form/id'); ?>/"+id, $("#frm_payment").serialize(), 
			function(data){
			//$("#loading_dialog").dialog("close");
				if(data){
					$('#payment_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#payment_list_div').show();
					$('#payment_form_div').hide();
					jQuery("#payment_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
		
	return false;
}
function deletePayment(){
	$("#loading_top").css("display","block");
		
	$.post("<?php echo site_url('payment/deleteData/id'); ?>/"+payment_id, 
			function(data){
			
				if(data == 1){
					$("#alert_dialog").html('Record Deleted Successfully');
					$("#alert_dialog").dialog('open');
					jQuery("#payment_grid").trigger("reloadGrid");
				}else{
					$("#alert_dialog").html('Error in deleting record');
					$("#alert_dialog").dialog('open');
				}
				$("#loading_top").css("display","none");
			} 
		);
		
	return false;
}
function cancel_payment(){
	//$("#loading_dialog").dialog("open");
	$('#payment_list_div').show();
	$('#payment_form_div').hide();
	jQuery("#payment_grid").trigger("reloadGrid");
}
function search_payment(){
	
	var payment_sdate = $('#payment_sdate').val();
	var payment_edate = $('#payment_edate').val();
	var payment_user = $('#payment_user').val();
	
	 $("#loading_top").css("display","block");
	jQuery("#payment_grid").jqGrid('setGridParam',{postData:{usr:payment_user, payment_sdate:payment_sdate, payment_edate:payment_edate, page:1}}).trigger("reloadGrid");
	return false;
}
</script>
<div id="payment_list_div">
<form onsubmit="return search_payment()">
<table width="100%">
	<tr>
		<td width="2%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="payment_sdate" id="payment_sdate" class="date text ui-widget-content ui-corner-all" style="width:110px;padding:5px;" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td>
		<td width="2%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="payment_edate" id="payment_edate" class="date text ui-widget-content ui-corner-all" style="width:110px;padding:5px;" value="<?php echo date($date_format); ?>" readonly="readonly"/></td>
		<?php if($this->session->userdata('usertype_id') == 1){ ?>
		<td width="2%">User : <select name="payment_user" id="payment_user" class="select ui-widget-content ui-corner-all" style="width:200px;padding:5px;"><option value=''><?php echo $this->lang->line("Please Select"); ?></option><?php echo $users; ?></select></td>
		<?php } ?>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
</form>
	<table id="payment_grid" class="jqgrid"></table>
</div>
<div id="payment_pager"></div>

<div id="payment_form_div" style="padding:10px;display:none;height:450px;">
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
<div id="conf_dialog_payment<?php $time=time(); ?>" style="display:none;">
Are You Sure ! You Want to Delete ?
</div>

</body>
</html>