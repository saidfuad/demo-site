<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Add","Edit","Export");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'124');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	
	$time=time();
?>
<script type="text/javascript">
var asets_c_exist=true;
$(document).ready(function(){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true}); 
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#assests_division_master_grid").jqGrid({
		url:"<?php echo site_url('assests_division_master/loadData'); ?>", 
		datatype: "json",
		colNames:["Id","<?php echo $this->lang->line('Assets Division'); ?>"],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"division",editable:true, index:"division", width:200, align:"center", jsonmap:"division"}
			
		],
		rowNum:100,
		height: "auto",
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#assests_division_master_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		footerrow : false, 
		userDataOnFooter : false, 
		caption:"<?php echo $this->lang->line('Assets Division'); ?>",
		editurl:"<?php echo site_url('assests_division_master/deleteData'); ?>", 
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
	jQuery("#assests_division_master_grid").jqGrid("navGrid", "#assests_division_master_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});

	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#assests_division_master_grid").jqGrid("navButtonAdd","#assests_division_master_pager",{caption:"<?php echo $this->lang->line('add'); ?>",
		onClickButton:function(){
			$("#assests_division_master_list_div").hide();
			$("#assests_division_master_form_div").show();
			$("#assests_division_master_form_div").load("<?php echo site_url("/assests_division_master/form/"); ?>");
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#assests_division_master_grid").jqGrid("navButtonAdd","#assests_division_master_pager",{caption:"<?php echo $this->lang->line('Edit'); ?>",
		onClickButton:function(){
			var gsr = jQuery("#assests_division_master_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("Please Select Only One Row");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#assests_division_master_form_div").show();
					$("#assests_division_master_list_div").hide();
					$("#assests_division_master_form_div").load("<?php echo site_url("assests_division_master/form/id"); ?>/"+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("Please Select Row");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	$("#t_assests_division_master_grid").css({padding:"5px 0px",height:"30px"});
<?php } ?>
});


	<?php if(in_array('Export',$data)){ ?>
	$("#assests_division_master_grid").jqGrid("navButtonAdd","#assests_division_master_pager",{caption:"<?php echo $this->lang->line('Export'); ?>",
		onClickButton:function(){
			var qrystr ="/export";
			document.location = "<?php echo site_url('assests_division_master/export/cmd/export'); ?>";
		}
	});
	<?php } ?>

    function submitFormassests_division_mastermaster(id){
	//$("#alert_dialog").html("Loading");
	//$("#alert_dialog").dialog("open");
	$.post("<?php echo site_url("assests_division_master/form/id"); ?>/"+id,$("#frm_assests_division_master").serialize(), function(data){
		$("#alert_dialog").dialog("close");
		if($.trim(data)){
			$("#assests_division_master_form_div").html(data);
		}else{
			if(id != "")  
				$("#alert_dialog").html("Record Updated Successfully");
			else 
				$("#alert_dialog").html("Record Inserted Successfully");
			$("#alert_dialog").dialog("open");
			$("#assests_division_master_form_div").hide();
			$("#assests_division_master_list_div").show();
			jQuery("#assests_division_master_grid").trigger("reloadGrid");
		}
	});
	return false;	
}

function cancel_assests_division_master(){
	$("#assests_division_master_list_div").show();
	$("#assests_division_master_form_div").hide();
	jQuery("#assests_division_master_grid").trigger("reloadGrid");
}
</script>
<div id="assests_division_master_list_div">
<table id="assests_division_master_grid" class="jqgrid"></table> 
</div>
<div id="assests_division_master_pager"></div>
<div id="assests_division_master_form_div" style="padding:10px;display:none;"></div>
