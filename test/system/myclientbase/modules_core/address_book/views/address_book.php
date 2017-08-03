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
		$va1l->where("menu_id",'65');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	
?>
<?php $time=time(); ?>
<style>
#load_address_book_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#address_book_grid").jqGrid({
		url:"<?php echo site_url('address_book/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("id"); ?>",'<?php echo $this->lang->line("name"); ?>','<?php echo $this->lang->line("group_name"); ?>','<?php echo $this->lang->line("Address"); ?>','<?php echo $this->lang->line("mobile_number"); ?>','<?php echo $this->lang->line("Email"); ?>','<?php echo $this->lang->line("send_sms"); ?>','<?php echo $this->lang->line("send_email"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"name",editable:true, width:120, index:"name", align:"center", jsonmap:"name"},
			{name:"group_name",editable:true, index:"group_name", width:120, align:"center", jsonmap:"group_name"},
			{name:"address",editable:true, index:"address", width:180, align:"center", jsonmap:"address"},
			{name:"mobile_no",editable:true, index:"mobile_no", width:120, align:"center", jsonmap:"mobile_no"},
			{name:"email",editable:true, index:"email", width:150, align:"center", jsonmap:"email"},
			{name:"send_sms",editable:true, index:"send_sms", width:80, align:"center", jsonmap:"send_sms",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"send_email",editable:true, index:"send_email", width:80, align:"center", jsonmap:"send_email",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#address_book_pager"),
		sortname: "name",
		viewrecords: true,
		multiselect: true,
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
		},	
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Address Book"); ?>",
		editurl:"<?php echo site_url('address_book/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
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
	jQuery("#address_book_grid").jqGrid("navGrid", "#address_book_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#address_book_grid").jqGrid("navButtonAdd","#address_book_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){

			$('#address_book_list_div').hide();
			$('#address_book_form_div').show();
			$('#address_book_form_div').load('<?php echo site_url('/address_book/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#address_book_grid").jqGrid("navButtonAdd","#address_book_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#address_book_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$("#loading_top").css("display","block");
					$('#address_book_form_div').show();
					$('#address_book_list_div').hide();
					var gsrval = jQuery("#address_book_grid").jqGrid('getCell', gsr[0], 'id');
					$('#address_book_form_div').load('<?php echo site_url('address_book/form/id'); ?>/'+gsrval);
					$("#loading_top").css("display","none");
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_address_book_lokkup=$("#conf_dialog_address_book<?php $time=time(); ?>");
	conf_dialog_address_book_lokkup.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_address_book_lokkup.dialog("close");
				cancel_address_book(); 
			},
			No: function () {
				conf_dialog_landmark_group_lokkup.dialog("close");
			}
		},
	

	});
cancelloading();
});
function mobile_number_valid()
{
if($("#mobile_number_add").val() != ""){
	var emails=$("#mobile_number_add").val();
		//var em=emails.split(/[;,]+/);
		var em=emails.split(/,/);
	for(i=0;i<em.length;i++)
	{
		if(em[i].length == 10)
		{
			$("#error_frm_address").hide();
			//return true;
		}else{
			$("#error_frm_address").show();
			$("#error_frm_address").html(em[i]+" Mobile Number Formate is Not Valid");
			return false;
		}
	}
}
return true;
}
function email_valid(){
if($("#email_add").val() != ""){
	var emails=$("#email_add").val();
		//var em=emails.split(/[;,]+/);
		var em=emails.split(/,/);
		
	for(i=0;i<em.length;i++)
	{
		var RegularExpression2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/;
		var email_valid = RegularExpression2.test(em[i])
		if(email_valid == true)
		{
			$("#error_frm_address").hide();
			//return true;
		}else{
			$("#error_frm_address").show();
			$("#error_frm_address").html("'"+em[i]+"' Email Id Formate is Not Valid");
			return false;
		}
	}
}
return true;
}
function check_address_book(id)
{
	var a_t_nm=$.trim($("#group_name_add").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('address_book/chk_nm/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
//		alert(data);
		if(data=="false")
		{
			asets_t_exist=false;
			$("#address_book_error").show();
		}
		else
		{
			asets_t_exist=true;
			$("#address_book_error").hide();
		}
	});
}
function submitFormaddress_book(id){
	//check_address_book(id);
	/*if(asets_t_exist==true)
	{*/
	if(mobile_number_valid() && email_valid())
	{
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('address_book/form/id'); ?>/"+id, $("#frm_address_book").serialize(), 
			function(data){
				if(data){
					$('#address_book_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#address_book_list_div').show();
					$('#address_book_form_div').hide();
					jQuery("#address_book_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	}
	/*}*/
	return false;	
}
function cancel_address_book(){
	//$("#loading_dialog").dialog("open");
	$('#address_book_list_div').show();
	$('#address_book_form_div').hide();
	jQuery("#address_book_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="address_book_list_div">
	<table id="address_book_grid" class="jqgrid"></table>
</div>
<div id="address_book_pager"></div>
<div id="address_book_form_div" style="padding:10px;display:none;height:450px;">

<div id="conf_dialog_address_book<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
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