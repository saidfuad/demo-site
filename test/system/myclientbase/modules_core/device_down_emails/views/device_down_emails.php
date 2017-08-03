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
		$va1l->where("menu_id",'94');
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
#load_emails_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_emails_var_bitbot;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#emails_grid").jqGrid({
		url:"<?php echo site_url('device_down_emails/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Name"); ?>','<?php echo $this->lang->line("Email ID"); ?>', '<?php echo $this->lang->line("Mobile"); ?>', '<?php echo $this->lang->line("Email Server Fail alert"); ?>', '<?php echo $this->lang->line("Email Status"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"name",editable:true, index:"name", width:80, align:"center", jsonmap:"name"},
			{name:"email",editable:true, index:"email", width:200, align:"center", jsonmap:"email"},
			{name:"mobile",editable:true, index:"mobile", width:200, align:"center", jsonmap:"mobile"},
			{name:"email_stop_alert",editable:true, index:"email_stop_alert", width:200, align:"center", jsonmap:"email_stop_alert",formatter:'select', editoptions:{value:"1:Active;0:Inactive"}},
			{name:"status",editable:true, index:"status", width:50, align:"center", jsonmap:"status", formatter:'select', editoptions:{value:"1:Active;0:Inactive"}}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#emails_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Email List"); ?>",
		editurl:"<?php echo site_url('device_down_emails/deleteData'); ?>",
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
	jQuery("#emails_grid").jqGrid("navGrid", "#emails_pager", {add:false, edit:false, del : <?php echo $delete; ?>, deltitle: 'Delete Records.....!',  search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	$(".delmsg").html("Delte msg");
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#emails_grid").jqGrid("navButtonAdd","#emails_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#emails_list_div').hide();
			$('#emails_form_div').show();
			$('#emails_form_div').load('<?php echo site_url('/device_down_emails/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#emails_grid").jqGrid("navButtonAdd","#emails_pager",{caption:"<?php echo $this->lang->line("edit"); ?>", title: "Edit?",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			var gsr = jQuery("#emails_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#emails_form_div').show();
					$('#emails_list_div').hide();
					$('#emails_form_div').load('<?php echo site_url('device_down_emails/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_emails_var_bitbot=$("#conf_dialog_emails<?php $time=time(); ?>");
	conf_dialog_emails_var_bitbot.dialog({
		modal: true, title: '<?php echo $this->lang->line("Conform_message"); ?>', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_emails_var_bitbot.dialog("close");
				cancel_emails(); 
			},
			No: function () {
				conf_dialog_emails_var_bitbot.dialog("close");
			}
		},
	}); 
	cancelloading();
});
function submitFormemails(id){
	$("#loading_top").css("display","block");
	$("#error_emails_frm").hide();
	var nm=$("#emails_name").val();
	$.post("<?php echo site_url('device_down_emails/form/id'); ?>/"+id, $("#frm_emails").serialize(), 
		function(data){
		//$("#loading_dialog").dialog("close");
			if(data){
				$('#emails_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				$('#emails_list_div').show();
				$('#emails_form_div').hide();
				jQuery("#emails_grid").trigger("reloadGrid");
			}
			$("#loading_top").css("display","none");
		} 
	);
		
	return false;
}
function cancel_emails(){
	//$("#loading_dialog").dialog("open");
	$('#emails_list_div').show();
	$('#emails_form_div').hide();
	jQuery("#emails_grid").trigger("reloadGrid");
}
</script>
<div id="emails_list_div">
	<table id="emails_grid" class="jqgrid"></table>
</div>
<div id="emails_pager"></div>

<div id="emails_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_emails<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
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