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
		$va1l->where("menu_id",'18');
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
#load_landmark_group_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var asets_t_exist=true;
var conf_dialog_landmark_group_lokkup;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#landmark_group_grid").jqGrid({
		url:"<?php echo site_url('landmark_group/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Landmark_Group_Name"); ?>','<?php echo $this->lang->line("Landark_Group_list"); ?>','<?php echo $this->lang->line("user_id"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"landmark_group_name",editable:true, index:"landmark_group_name", width:180, align:"center", jsonmap:"landmark_group_name"},
			{name:"landmark_group_list",editable:true, width:525, index:"landmark_group_name", align:"center", jsonmap:"landmark_group_list"},
			{name:"comments",editable:true, index:"comments", width:180, align:"center", jsonmap:"comments",hidden:true},
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#landmark_group_pager"),
		sortname: "landmark_group_name",
		viewrecords: true,
		multiselect: true,
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Landmark Group List"); ?>",
		editurl:"<?php echo site_url('landmark_group/deleteData'); ?>",
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
	jQuery("#landmark_group_grid").jqGrid("navGrid", "#landmark_group_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});

	$("#landmark_group_pager option[value=10000]").text('All');
	$("#landmark_group_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#landmark_group_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});
    
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#landmark_group_grid").jqGrid("navButtonAdd","#landmark_group_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$('#landmark_group_list_div').hide();
			$('#landmark_group_form_div').show();
			$('#landmark_group_form_div').load('<?php echo site_url('/landmark_group/form/'); ?>');
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#landmark_group_grid").jqGrid("navButtonAdd","#landmark_group_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#landmark_group_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#landmark_group_form_div').show();
					$('#landmark_group_list_div').hide();
					var gsrval = jQuery("#landmark_group_grid").jqGrid('getCell', gsr[0], 'id');
					$('#landmark_group_form_div').load('<?php echo site_url('landmark_group/form/id'); ?>/'+gsrval);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_landmark_group_lokkup=$("#conf_dialog_landmark_grp<?php $time=time(); ?>");
	conf_dialog_landmark_group_lokkup.dialog({
		modal: true, title: '<?php echo $this->lang->line("Conform_message"); ?>', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_landmark_group_lokkup.dialog("close");
				cancel_landmark_name(); 
			},
			No: function () {
				conf_dialog_landmark_group_lokkup.dialog("close");
			}
		},
	

	});
	cancelloading();
	click_refresh();
});

function check_landmark_name(id)
{
	var a_t_nm=$.trim($("#landmark_group_name").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('landmark_group/chk_nm/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
//		alert(data);
		if($.trim(data)=="false")
		{
			asets_t_exist=false;
	//		$("#landark_group_error").show();
		}
		else
		{
			asets_t_exist=true;
	//		$("#landark_group_error").hide();
		}
	});
}
function submitFormassets_type(id){
//	check_landmark_name();
	if(asets_t_exist==true)
	{
	////$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('landmark_group/form/id'); ?>/"+id, $("#frm_landmark_group").serialize(), 
			function(data){
				if($.trim(data)){
					$('#landmark_group_form_div').html(data);
				}else{
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
						$('#landmark_group_list_div').show();
						$('#landmark_group_form_div').hide();
					jQuery("#landmark_group_grid").trigger("reloadGrid");
				}
				////$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			} 
		);
	}
	return false;	
}

function cancel_landmark_name(){
	////$("#loading_dialog").dialog("open");
	$('#landmark_group_list_div').show();
	$('#landmark_group_form_div').hide();
	jQuery("#landmark_group_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="landmark_group_list_div">
	<table id="landmark_group_grid" class="jqgrid"></table>
</div>
<div id="landmark_group_pager"></div>
<div id="landmark_group_form_div" style="padding:10px;display:none;height:450px;">

<div id="conf_dialog_landmark_grp<?php $time=time(); ?>" style="display:none;">
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