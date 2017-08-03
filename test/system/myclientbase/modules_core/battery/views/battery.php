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
		$va1l->where("menu_id",'84');
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
#load_battery_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_battery_var_bitbot;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#battery_grid").jqGrid({
		url:"<?php echo site_url('battery/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Volt"); ?>'],
		colModel:[
			{name:"id",hidden:true, editable:true, index:"id", width:120, align:"center", jsonmap:"id"},
			{name:"volt",editable:true, index:"volt", width:120, align:"center", jsonmap:"volt"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#battery_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Battery Master List"); ?>",
		editurl:"<?php echo site_url('battery/deleteData'); ?>",
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
	jQuery("#battery_grid").jqGrid("navGrid", "#battery_pager", {add:false, edit:false, del : <?php echo $delete; ?>, deltitle: '<?php echo $this->lang->line('Delete_Records'); ?>.....!',  search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	$(".delmsg").html("<?php echo $this->lang->line('Delte_msg'); ?>");
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#battery_grid").jqGrid("navButtonAdd","#battery_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#battery_list_div').hide();
			$('#battery_form_div').show();
			$('#battery_form_div').load('<?php echo site_url('/battery/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#battery_grid").jqGrid("navButtonAdd","#battery_pager",{caption:"<?php echo $this->lang->line("edit"); ?>", title: "Edit?",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			var gsr = jQuery("#battery_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#battery_form_div').show();
					$('#battery_list_div').hide();
					$('#battery_form_div').load('<?php echo site_url('battery/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_battery_var_bitbot=$("#conf_dialog_battery<?php $time=time(); ?>");
	conf_dialog_battery_var_bitbot.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_battery_var_bitbot.dialog("close");
				cancel_battery(); 
			},
			No: function () {
				conf_dialog_battery_var_bitbot.dialog("close");
			}
		},
	}); 
	cancelloading();
});
function submitFormbattery(id){
	$("#loading_top").css("display","block");
	$("#error_battery_frm").hide();
	var nm=$("#battery_name").val();

	$.post("<?php echo site_url('battery/form/id'); ?>/"+id, $("#frm_battery").serialize(), 
		function(data){
		//$("#loading_dialog").dialog("close");
			if(data){
				$('#battery_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				$('#battery_list_div').show();
				$('#battery_form_div').hide();
				jQuery("#battery_grid").trigger("reloadGrid");
			}
			$("#loading_top").css("display","none");
		} 
	);
		
	return false;
}
function cancel_battery(){
	//$("#loading_dialog").dialog("open");
	$('#battery_list_div').show();
	$('#battery_form_div').hide();
	jQuery("#battery_grid").trigger("reloadGrid");
}
</script>
<div id="battery_list_div">
	<table id="battery_grid" class="jqgrid"></table>
</div>
<div id="battery_pager"></div>

<div id="battery_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_battery<?php $time=time(); ?>" style="display:none;">
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