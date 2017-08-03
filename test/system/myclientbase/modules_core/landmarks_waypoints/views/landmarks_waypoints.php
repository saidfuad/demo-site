<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'88');
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
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_landmarks_waypoints_grid
{
	display:none !important;
}
</style>
<script type="text/javascript">

var landmarks_waypoints_conf_dialog_usr_abcd;
jQuery().ready(function (){

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#landmarks_waypoints_grid").jqGrid({
		url:"<?php echo site_url('landmarks_waypoints/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Waypoint Name"); ?>','<?php echo $this->lang->line("Landarmk1"); ?>', '<?php echo $this->lang->line("Landarmk2"); ?>'],
		colModel:[
			{name:"id",index:"tlw.id",hidden:true, width:15, jsonmap:"id"},
			{name:"tlw.waypoint_name",editable:true, index:"tlw.waypoint_name", width:220, align:"center", jsonmap:"waypoint_name"},
			{name:"landmark1",editable:true, index:"tlw.landmark1", width:220, align:"center", jsonmap:"landmark1"},
			{name:"landmark2",editable:true, index:"tlw.landmark2", width:220, align:"center", jsonmap:"landmark2"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#landmarks_waypoints_pager"),
		sortname: "tlw.id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("Landarmk Waypoints List"); ?>",
		editurl:"<?php echo site_url('landmarks_waypoints/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	 landmarks_waypoints_conf_dialog_usr_abcd=$("#landmarks_waypoints_conf_dialog_usr_abcd<?php echo $time; ?>");
		landmarks_waypoints_conf_dialog_usr_abcd.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					landmarks_waypoints_conf_dialog_usr_abcd.dialog("close");
					cancel_landmarks_waypoints();
				},
				No: function () {
					landmarks_waypoints_conf_dialog_usr_abcd.dialog("close");
				}
			},		
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

	jQuery("#landmarks_waypoints_grid").jqGrid("navGrid", "#landmarks_waypoints_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#landmarks_waypoints_grid").jqGrid("navButtonAdd","#landmarks_waypoints_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#landmarks_waypoints_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#landmarks_waypoints_form_div').show();
					$('#landmarks_waypoints_list_div').hide();
					$('#landmarks_waypoints_form_div').load($.trim('<?php echo site_url('landmarks_waypoints/form/id'); ?>/'+gsr[0]));
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	$("#loading_top").css("display","none");
});

function submitFormlandmarks_waypoints(id){
 	$("#loading_top").css("display","block");
	var nm=$("#landmarks_waypointsname").val();
	$("#err_duplicate_waypt").html("");
	$("#err_duplicate_waypt").hide();
	if($("#landmark1").val()!=$("#landmark2").val()){
		$.post("<?php echo site_url('landmarks_waypoints/checkDuplicate_way/id'); ?>/"+id,$("#frm_landmarks_waypoints").serialize(),
		function(data){
		if(data){
			 $.post("<?php echo site_url('landmarks_waypoints/form/id'); ?>/"+id, $("#frm_landmarks_waypoints").serialize(), 
				function(data){
					if($.trim(data)){
						$('#landmarks_waypoints_form_div').html(data);
					}else{
						if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
						else
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
						$("#alert_dialog").dialog('open');
						jQuery("#landmarks_waypoints_grid").trigger("reloadGrid");
						cancel_landmarks_waypoints();
						}
						$("#loading_top").css("display","none");
					}
				);
			}else{
				$("#err_duplicate_waypt").html("<?php echo $this->lang->line("Waypoint Already Exist"); ?>");
				$("#err_duplicate_waypt").show();
			}
		});
	}else{
		$("#err_duplicate_waypt").html("<?php echo $this->lang->line('Both Landmark Should be Different'); ?>");
		$("#err_duplicate_waypt").show();
	}
	return false;	
}

function cancel_landmarks_waypoints(){
	//$("#loading_dialog").dialog("open");
	$('#landmarks_waypoints_list_div').show();
	$('#landmarks_waypoints_form_div').hide();
	$("#err_duplicate_waypt").html("");
	$("#err_duplicate_waypt").hide();
	jQuery("#landmarks_waypoints_grid").trigger("reloadGrid");
} 
</script>
<div id="landmarks_waypoints_list_div">
	<table id="landmarks_waypoints_grid" class="jqgrid"></table>
</div>
<div id="landmarks_waypoints_pager"></div>
<div id="landmarks_waypoints_form_div" style="padding:10px;display:none;">
</div>
<div id="landmarks_waypoints_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
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