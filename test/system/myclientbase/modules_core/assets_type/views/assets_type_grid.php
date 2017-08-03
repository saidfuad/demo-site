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
		$va1l->where("menu_id",'19');
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
#load_assets_type_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var asets_t_exist=true;
var conf_dialog_assest_type_vtsloglog;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#assets_type_grid").jqGrid({
		url:"<?php echo site_url('assets_type/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset Type Name"); ?>','<?php echo $this->lang->line("Comments"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_type_nm",editable:true, index:"assets_type_nm", width:180, align:"center", jsonmap:"assets_type_nm"},
			{name:"comments",editable:true, index:"comments", width:180, align:"center", jsonmap:"comments"},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#assets_type_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Assets Type List"); ?>",
		editurl:"<?php echo site_url('assets_type/deleteData'); ?>",
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
	jQuery("#assets_type_grid").jqGrid("navGrid", "#assets_type_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#assets_type_grid").jqGrid("navButtonAdd","#assets_type_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$('#assets_type_list_div').hide();
			$('#assets_type_form_div').show();
			$('#assets_type_form_div').load('<?php echo site_url('/assets_type/form/'); ?>');
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#assets_type_grid").jqGrid("navButtonAdd","#assets_type_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#assets_type_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#assets_type_form_div').show();
					$('#assets_type_list_div').hide();
					$('#assets_type_form_div').load('<?php echo site_url('assets_type/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	}); 
	<?php } ?>
	conf_dialog_assest_type_vtsloglog=$("#conf_dialog_assets_type<?php $time=time(); ?>");
	conf_dialog_assest_type_vtsloglog.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_assest_type_vtsloglog.dialog("close");
				cancel_assets_type(); 
			},
			No: function () {
				conf_dialog_assest_type_vtsloglog.dialog("close");
			}
		},
	

	});
	cancelloading();
});
function check_assets_type(id)
{
	var a_t_nm=$.trim($("#assets_type_nm").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('assets_type/chk_nm/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
//		alert(data);
		if(data=="false")
		{
			asets_t_exist=false;
			$("#assets_type_error").show();
		}
		else
		{
			asets_t_exist=true;
			$("#assets_type_error").hide();
		}
	});
}
function submitFormassets_type(id){
//	check_assets_type()
	if(asets_t_exist==true)
	{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('assets_type/form/id'); ?>/"+id, $("#frm_assets_type").serialize(), 
			function(data){
				if(data){
					$('#assets_type_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#assets_type_list_div').show();
					$('#assets_type_form_div').hide();
					jQuery("#assets_type_grid").trigger("reloadGrid");
				}
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			} 
		);
	}
	return false;	
}
function cancel_assets_type(){
	//$("#loading_dialog").dialog("open");
	$('#assets_type_list_div').show();
	$('#assets_type_form_div').hide();
	jQuery("#assets_type_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="assets_type_list_div">
	<table id="assets_type_grid" class="jqgrid"></table>
</div>
<div id="assets_type_pager"></div>
<div id="assets_type_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_assets_type<?php $time=time(); ?>" style="display:none;">
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