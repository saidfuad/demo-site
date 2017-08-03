<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Add","Edit","Import");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'77');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	}
	

?>
<?php $time=time(); ?>
<?php $rNo = strtotime(date("H:i:s")); ?>
<style>
#load_command_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#command_grid").jqGrid({
		url:"<?php echo site_url('command/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("id"); ?>","<?php echo $this->lang->line("Device Type"); ?>","<?php echo $this->lang->line("Command"); ?>","<?php echo $this->lang->line("Comments"); ?>"],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_class_name",editable:true, width:120, index:"assets_class_name", align:"center", jsonmap:"assets_class_name"},
			{name:"command",editable:true, index:"command", width:120, align:"center", jsonmap:"command"},
			{name:"comments",editable:true, width:320, index:"comments", align:"center", jsonmap:"comments"}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#command_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true,
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
		},	
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Command Master"); ?>",
		editurl:"<?php echo site_url('command/deleteData'); ?>",
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
	jQuery("#command_grid").jqGrid("navGrid", "#command_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});

	$("#command_pager option[value=10000]").text('All');
	$("#command_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#command_pager .ui-pg-selbox").val();
	});
	
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#command_grid").jqGrid("navButtonAdd","#command_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			$('#command_list_div').hide();
			$('#command_form_div').show();
			$('#command_form_div').load('<?php echo site_url('/command/form/'); ?>');
			$("#loading_top").css("display","none");
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#command_grid").jqGrid("navButtonAdd","#command_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#command_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					
					
					$("#loading_top").css("display","block");
			
					$('#command_form_div').show();
					$('#command_list_div').hide();
					var gsrval = jQuery("#command_grid").jqGrid('getCell', gsr[0], 'id');
					$('#command_form_div').load('<?php echo site_url('command/form/id'); ?>/'+gsrval);
					$("#loading_top").css("display","none");
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	<?php
	if(in_array('Import',$data)){
	?>
	jQuery("#command_grid").jqGrid('navButtonAdd','#command_pager',{caption:"<?php echo $this->lang->line("Import"); ?>",
		onClickButton:function(){
			$('#command_list_div').hide();	
			$("#dialog-import-driver").show();
		} 
	});
	<?php } ?>
	conf_dialog_command_lokkup=$("#conf_dialog_command<?php $time=time(); ?>");
	conf_dialog_command_lokkup.dialog({
		modal: true, title: '<?php echo $this->lang->line("Conform message"); ?>', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_command_lokkup.dialog("close");
				cancel_command(); 
			},
			No: function () {
				conf_dialog_landmark_group_lokkup.dialog("close");
			}
		},
	

	});
	
cancelloading();
});

function submitFormcommand(id){
	
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('command/form/id'); ?>/"+id, $("#frm_command").serialize(), 
		function(data){
			if(data){
				$('#command_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				$('#command_list_div').show();
				$('#command_form_div').hide();
				jQuery("#command_grid").trigger("reloadGrid");
			}
			$("#loading_top").css("display","none");
		} 
	);
	return false;	
}
function cancel_command(){
	//$("#loading_dialog").dialog("open");
	$('#command_list_div').show();
	$('#command_form_div').hide();
	jQuery("#command_grid").trigger("reloadGrid");
}
</script>
<div id="command_list_div">
	<table id="command_grid" class="jqgrid"></table>
</div>
<div id="command_pager"></div>
<div id="command_form_div" style="padding:10px;display:none;height:450px;">

<div id="conf_dialog_command<?php $time=time(); ?>" style="display:none;">
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