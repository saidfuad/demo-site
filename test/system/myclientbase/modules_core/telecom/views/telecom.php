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
		$va1l->where("menu_id",'85');
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
#load_telecom_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_telecom_var_bitbot;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#telecom_grid").jqGrid({
		url:"<?php echo site_url('telecom/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Telecom Provider Name"); ?>'],
		colModel:[
			{name:"id", hidden:true,editable:true, index:"id", width:120, align:"center", jsonmap:"id"},
			{name:"telecom_provider_name",editable:true, index:"telecom_provider_name", width:200, align:"center", jsonmap:"telecom_provider_name"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#telecom_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Telecom Master List"); ?>",
		editurl:"<?php echo site_url('telecom/deleteData'); ?>",
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
	jQuery("#telecom_grid").jqGrid("navGrid", "#telecom_pager", {add:false, edit:false, del : <?php echo $delete; ?>, deltitle: 'Delete Records.....!',  search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	$(".delmsg").html("Delte msg");
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#telecom_grid").jqGrid("navButtonAdd","#telecom_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#telecom_list_div').hide();
			$('#telecom_form_div').show();
			$('#telecom_form_div').load('<?php echo site_url('/telecom/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#telecom_grid").jqGrid("navButtonAdd","#telecom_pager",{caption:"<?php echo $this->lang->line("edit"); ?>", title: "Edit?",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			var gsr = jQuery("#telecom_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#telecom_form_div').show();
					$('#telecom_list_div').hide();
					$('#telecom_form_div').load('<?php echo site_url('telecom/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_telecom_var_bitbot=$("#conf_dialog_telecom<?php $time=time(); ?>");
	conf_dialog_telecom_var_bitbot.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_telecom_var_bitbot.dialog("close");
				cancel_telecom(); 
			},
			No: function () {
				conf_dialog_telecom_var_bitbot.dialog("close");
			}
		},
	}); 
	cancelloading();
});
function submitFormtelecom(id){
	$("#loading_top").css("display","block");
	$("#error_telecom_frm").hide();
	var nm=$("#telecom_name").val();

	$.post("<?php echo site_url('telecom/form/id'); ?>/"+id, $("#frm_telecom").serialize(), 
		function(data){
		//$("#loading_dialog").dialog("close");
			if(data){
				$('#telecom_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				$('#telecom_list_div').show();
				$('#telecom_form_div').hide();
				jQuery("#telecom_grid").trigger("reloadGrid");
			}
			$("#loading_top").css("display","none");
		} 
	);
		
	return false;
}
function cancel_telecom(){
	//$("#loading_dialog").dialog("open");
	$('#telecom_list_div').show();
	$('#telecom_form_div').hide();
	jQuery("#telecom_grid").trigger("reloadGrid");
}
</script>
<div id="telecom_list_div">
	<table id="telecom_grid" class="jqgrid"></table>
</div>
<div id="telecom_pager"></div>

<div id="telecom_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_telecom<?php $time=time(); ?>" style="display:none;">
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