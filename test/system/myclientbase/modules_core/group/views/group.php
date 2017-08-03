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
		$va1l->where("menu_id",'14');
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
#load_group_grid
{
	display:none !important; 
}
#group_grid td {           
    word-wrap: break-word; /* IE 5.5+ and CSS3 */
    white-space: pre-wrap; /* CSS3 */
    white-space: -pre-wrap; /* Opera 4-6 */
    white-space: -o-pre-wrap; /* Opera 7 */
    white-space: normal !important;
    height: auto;
    vertical-align: text-top;
    padding-top: 2px;
    padding-bottom: 3px;
}
</style>
<script type="text/javascript">
var conf_dialog_group_var_bitbot;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#group_grid").jqGrid({
		url:"<?php echo site_url('group/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("group_name"); ?>','<?php echo $this->lang->line("Assets"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"group_name",editable:true, index:"group_name", width:120, align:"center", jsonmap:"group_name"},
			{name:"assets",editable:true, index:"assets", width:600, align:"center", jsonmap:"assets"}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#group_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("group List"); ?>",
		editurl:"<?php echo site_url('group/deleteData'); ?>",
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
	jQuery("#group_grid").jqGrid("navGrid", "#group_pager", {add:false, edit:false, del : <?php echo $delete; ?>, deltitle: 'Delete Records.....!',  search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	$(".delmsg").html("Delte msg");
	
	$("#group_pager option[value=10000]").text('All');
	$("#group_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#group_pager .ui-pg-selbox").val();
	});
	
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#group_grid").jqGrid("navButtonAdd","#group_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#group_list_div').hide();
			$('#group_form_div').show();
			$('#group_form_div').load('<?php echo site_url('/group/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#group_grid").jqGrid("navButtonAdd","#group_pager",{caption:"<?php echo $this->lang->line("edit"); ?>", title: "Edit?",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			var gsr = jQuery("#group_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#group_form_div').show();
					$('#group_list_div').hide();
					$('#group_form_div').load('<?php echo site_url('group/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_group_var_bitbot=$("#conf_dialog_group<?php $time=time(); ?>");
	conf_dialog_group_var_bitbot.dialog({
		modal: true, title: '<?php echo $this->lang->line("Conform_message"); ?>', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_group_var_bitbot.dialog("close");
				cancel_group(); 
			},
			No: function () {
				conf_dialog_group_var_bitbot.dialog("close");
			}
		},
	}); 
	cancelloading();
});
function submitFormGroup(id){
	$("#loading_top").css("display","block");
	$("#error_group_frm").hide();
	var nm=$("#group_name").val();
	$.post("<?php echo base_url(); ?>index.php/group/check_duplicates/name/"+nm+"/id/"+id,function(data){
		if(data=="")
		{
		$.post("<?php echo site_url('group/form/id'); ?>/"+id, $("#frm_group").serialize(), 
				function(data){
				//$("#loading_dialog").dialog("close");
					if(data){
						$('#group_form_div').html(data);
					}else{
						if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
						else
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
						$("#alert_dialog").dialog('open');
						$('#group_list_div').show();
						$('#group_form_div').hide();
						jQuery("#group_grid").trigger("reloadGrid");
					}
					$("#loading_top").css("display","none");
				} 
			);
		}
		else
		{
			$("#error_group_frm").html(data);
			$("#error_group_frm").show();
			$("#loading_top").css("display","none");
			return false;
		}
	});
	return false;
}
function cancel_group(){
	//$("#loading_dialog").dialog("open");
	$('#group_list_div').show();
	$('#group_form_div').hide();
	jQuery("#group_grid").trigger("reloadGrid");
}
function filterAssetsCombo1(val, cid){
	$.post("<?php echo base_url(); ?>index.php/group/filter_group_assets", { grp: val},
	 function(data) {
		$("#"+cid).html(data);
		$("#ddcl-"+cid).css('vertical-align','middle');
		$("#ddcl-"+cid+"-ddw").css('overflow-x','hidden');
		$("#ddcl-"+cid+"-ddw").css('overflow-y','auto');
		$("#ddcl-"+cid+"-ddw").css('height','200px');
		$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	 });
}
</script>
<div id="group_list_div">
	<table id="group_grid" class="jqgrid"></table>
</div>
<div id="group_pager"></div>

<div id="group_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_group<?php $time=time(); ?>" style="display:none;">
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