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
		$va1l->where("menu_id",'66');
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
#load_address_book_group_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var asets_t_exist=true;
var conf_dialog_assest_type_vtsloglog;
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#address_book_group_grid").jqGrid({
		url:"<?php echo site_url('address_book_group/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Group Name"); ?>','<?php echo $this->lang->line("Comments"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"address_book_group_nm",editable:true, index:"address_book_group_nm", width:180, align:"center", jsonmap:"group_name"},
			{name:"comments",editable:true, index:"comments", width:180, align:"center", jsonmap:"comments"},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#address_book_group_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Address Book Group"); ?>",
		editurl:"<?php echo site_url('address_book_group/deleteData'); ?>",
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
	jQuery("#address_book_group_grid").jqGrid("navGrid", "#address_book_group_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#address_book_group_grid").jqGrid("navButtonAdd","#address_book_group_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$('#address_book_group_list_div').hide();
			$('#address_book_group_form_div').show();
			$('#address_book_group_form_div').load('<?php echo site_url('/address_book_group/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#address_book_group_grid").jqGrid("navButtonAdd","#address_book_group_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#address_book_group_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#address_book_group_form_div').show();
					$('#address_book_group_list_div').hide();
					$('#address_book_group_form_div').load('<?php echo site_url('address_book_group/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	}); 
	<?php } ?>
	conf_dialog_assest_type_vtsloglog=$("#conf_dialog_address_book_group<?php $time=time(); ?>");
	conf_dialog_assest_type_vtsloglog.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_assest_type_vtsloglog.dialog("close");
				cancel_address_book_group(); 
			},
			No: function () {
				conf_dialog_assest_type_vtsloglog.dialog("close");
			}
		},
	

	});
 cancelloading();
});
function check_address_book_group(id)
{
	var a_t_nm=$.trim($("#group_name_add").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('address_book_group/chk_nm/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
//		alert(data);
		if(data=="false")
		{
			asets_t_exist=false;
			$("#address_book_group_error").show();
		}
		else
		{
			asets_t_exist=true;
			$("#address_book_group_error").hide();
		}
	});
}
function submitFormaddress_book_group(id){
//	check_address_book_group()
	if(asets_t_exist==true)
	{
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('address_book_group/form/id'); ?>/"+id, $("#frm_address_book_group").serialize(), 
			function(data){
				if(data){
					$('#address_book_group_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#address_book_group_list_div').show();
					$('#address_book_group_form_div').hide();
					jQuery("#address_book_group_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	}
	return false;	
}
function cancel_address_book_group(){
	//$("#loading_dialog").dialog("open");
	$('#address_book_group_list_div').show();
	$('#address_book_group_form_div').hide();
	jQuery("#address_book_group_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="address_book_group_list_div">
	<table id="address_book_group_grid" class="jqgrid"></table>
</div>
<div id="address_book_group_pager"></div>
<div id="address_book_group_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_address_book_group<?php $time=time(); ?>" style="display:none;">
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