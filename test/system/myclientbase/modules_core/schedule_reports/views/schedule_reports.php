<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Add","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'125');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
?>
<script type="text/javascript">
var asets_c_exist=true;
loadMultiSelectDropDown();
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true}); 
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#schedule_reports_grid").jqGrid({
		url:"<?php echo site_url("schedule_reports/loadData"); ?>", 
		datatype: "json",
		colNames:["<?php echo $this->lang->line("id"); ?>","<?php echo $this->lang->line("Assests Name(Device)"); ?>","<?php echo $this->lang->line("Email_Address"); ?>","<?php echo $this->lang->line("Reports"); ?>","<?php echo $this->lang->line("Report_Type"); ?>","<?php echo $this->lang->line("File_Type"); ?>"],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_ids",editable:true, index:"assets_ids", width:200, align:"center", jsonmap:"assets_ids"},
			{name:"email_addresses",editable:true, index:"email_addresses", width:200, align:"center", jsonmap:"email_addresses"},
			{name:"reports",editable:true, index:"reports", width:200, align:"center", jsonmap:"reports"},
			{name:"daily_monthly_weekly",editable:true, index:"daily_monthly_weekly", width:200, align:"center", jsonmap:"daily_monthly_weekly"},
			{name:"excel_pdf",editable:true, index:"excel_pdf", width:200, align:"center", jsonmap:"excel_pdf"},
			
		],
		rowNum:10,
		height: "auto",
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#schedule_reports_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false, 
		caption:"Schedule Reports",
		editurl:"<?php echo site_url("schedule_reports/deleteData"); ?>", 
		jsonReader: { repeatitems : false, id: "0" },
	});
	 <?php
	if(in_array('Delete',$data))
		$delete = "true";
	else
		$delete = "false";
	?>
	jQuery("#schedule_reports_grid").jqGrid("navGrid", "#schedule_reports_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:false}, {}, {}, {}, {multipleSearch:false});
<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#schedule_reports_grid").jqGrid("navButtonAdd","#schedule_reports_pager",{caption:"Add",
		onClickButton:function(){
			console.log("add");
			$("#schedule_reports_list_div").hide();
			$("#schedule_reports_form_div").show();
			$("#schedule_reports_form_div").load("<?php echo site_url("/schedule_reports/form"); ?>");
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#schedule_reports_grid").jqGrid("navButtonAdd","#schedule_reports_pager",{caption:"Edit",
		onClickButton:function(){
			var gsr = jQuery("#schedule_reports_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("Please Select Only One Row");
					$("#alert_dialog").dialog("open");
				}
				else{
					console.log("edit");
					$("#schedule_reports_form_div").show();
					$("#schedule_reports_list_div").hide();
					$("#schedule_reports_form_div").load("<?php echo site_url("schedule_reports/form/id"); ?>/"+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("Please Select Row");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
});

function submitFormschedule_reportsmaster(id){
	$("#alert_dialog").html("Loading");
	$("#alert_dialog").dialog("open");
	$.post("<?php echo site_url("schedule_reports/form/id"); ?>/"+id,$("#frm_schedule_reports").serialize(), function(data){
		$("#alert_dialog").dialog("close");
		if($.trim(data)){
			$("#schedule_reports_form_div").html(data);
		}else{
			if(id != "")  
				$("#alert_dialog").html("Record Updated Successfully");
			else 
				$("#alert_dialog").html("Record Inserted Successfully");
			$("#alert_dialog").dialog("open");
			$("#schedule_reports_form_div").hide();
			$("#schedule_reports_list_div").show();
			jQuery("#schedule_reports_grid").trigger("reloadGrid");
		}
	});
	return false;	
}

function cancel_schedule_reports(){
	$("#schedule_reports_list_div").show();
	$("#schedule_reports_form_div").hide();
	jQuery("#schedule_reports_grid").trigger("reloadGrid");
}
</script>
<div id="schedule_reports_list_div">
<table id="schedule_reports_grid" class="jqgrid"></table> 
</div>
<div id="schedule_reports_pager"></div>
<div id="schedule_reports_form_div" style="padding:10px;display:none;"></div>
