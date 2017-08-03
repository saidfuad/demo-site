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
		$va1l->where("menu_id",'17');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	


	$time=time();
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.fileupload-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/bootstrap.min.css" type="text/css" />
<style>
#load_assets_category_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_assests_catagory_assets_dopost;
var asets_c_exist=true;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#assets_category_grid").jqGrid({
		url:"<?php echo site_url('assets_category/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Asset Category Name"); ?>','<?php echo $this->lang->line("Type"); ?>', '<?php echo $this->lang->line("Assets_Status"); ?>', '<?php echo $this->lang->line("Icon"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_cat_name",editable:true, index:"assets_cat_name", width:180, align:"center", jsonmap:"assets_cat_name"},
			{name:"t_name",editable:true, index:"tm.assets_type_nm", width:180, align:"center", jsonmap:"t_name"},
			{name:"assets_status",editable:true, index:"assets_status", width:120, align:"center", jsonmap:"assets_status",formatter:'select', editoptions:{value:"1:Active;0:Inactive"}},
			{name:"icon_path",editable:true, index:"icon_path", width:120, align:"center", jsonmap:"icon_path", formatter: AssetsPathFormatter_landmark}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#assets_category_pager"),
		sortname: "id",
		viewrecords: true,
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Assets Category List"); ?>",
		editurl:"<?php echo site_url('assets_category/deleteData'); ?>",
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
		
	jQuery("#assets_category_grid").jqGrid("navGrid", "#assets_category_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	$("#assets_category_pager option[value=10000]").text('All');
	$("#assets_category_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#assets_category_pager .ui-pg-selbox").val();
		//alert(grid_paging);
	});

	
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#assets_category_grid").jqGrid("navButtonAdd","#assets_category_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#assets_category_list_div').hide();
			$('#assets_category_form_div').show();
			$('#assets_category_form_div').load('<?php echo site_url('/assets_category/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#assets_category_grid").jqGrid("navButtonAdd","#assets_category_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#assets_category_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					
					$("#loading_top").css("display","block");
					$('#assets_category_form_div').show();
					$('#assets_category_list_div').hide();
					$('#assets_category_form_div').load('<?php echo site_url('assets_category/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_assests_catagory_assets_dopost=$("#conf_dialog_assets_cate<?php echo $time; ?>");
	conf_dialog_assests_catagory_assets_dopost.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_assests_catagory_assets_dopost.dialog("close");
				cancel_assets_category(); 
			},
			No: function () {
				conf_dialog_assests_catagory_assets_dopost.dialog("close");
			}
		},
	});
	cancelloading();
	
});

function AssetsPathFormatter_landmark(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/'+cellvalue+'" border="0">';
	}
	else
		return '';
}

function check_assets_category(id)
{
//	alert();
	var a_c_nm=$.trim($("#assets_cat_name").val());
	var a_c_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('assets_category/chk_nm/nm'); ?>/"+a_c_nm+"/id/"+a_c_id,function(data)
	{
//		alert(data);
		if(data=="false")
		{
			asets_c_exist=false;
			$("#assets_category_error").show();
		}
		else
		{
			asets_c_exist=true;
			$("#assets_category_error").hide();
		}
	});
}
function submitFormAssetsCategory(id){
	if(asets_c_exist==true)
	{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('assets_category/form/id'); ?>/"+id, $("#frm_assets_category").serialize(), 
			function(data){
				//////$("#loading_dialog").dialog("close");
				if(data){
					$('#assets_category_form_div').html(data);
				}else{
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Updated_Successfully'); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Inserted_Successfully'); ?>');
						
					$("#alert_dialog").dialog('open');
					$('#assets_category_form_div').hide();
					$('#assets_category_list_div').show();
					jQuery("#assets_category_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	}
	return false;	
}
function cancel_assets_category(){
	//////$("#loading_dialog").dialog("open");
	$('#assets_category_list_div').show();
	$('#assets_category_form_div').hide();
	jQuery("#assets_category_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return ''; 
}

</script>

<div id="assets_category_list_div">
	<table id="assets_category_grid"></table>
</div>
<div id="assets_category_pager"></div>

<div id="assets_category_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_assets_cate<?php echo $time; ?>" style="display:none;">
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