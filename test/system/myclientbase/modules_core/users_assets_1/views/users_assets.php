<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Search","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'16');
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
#load_user_assets_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_usr_assets_var_yesno;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#user_assets_grid").jqGrid({
		url:"<?php echo site_url('users_assets/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("user"); ?>','<?php echo $this->lang->line("agroup_name"); ?>','<?php echo $this->lang->line("Assets"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"user",editable:true, index:"CONCAT(um.first_name,' ',um.last_name)", width:120, align:"center", jsonmap:"user"},
			{name:"group",editable:true, index:"group", width:300, align:"center", jsonmap:"group"},
			{name:"assets",editable:true, index:"assets", width:600, align:"center", jsonmap:"assets"}		
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#user_assets_pager"),
		sortname: "id",
		loadComplete: function(){
			cancelloading();
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("User Assest List"); ?>",
		editurl:"users_assets/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});
<?php
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#user_assets_grid").jqGrid("navGrid", "#user_assets_pager", {add:false, edit:false, del:false, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	
	/*jQuery("#user_assets_grid").jqGrid("navButtonAdd","#user_assets_pager",{caption:"Add",
		onClickButton:function(){
			////$("#loading_dialog").dialog("open");
			$('#user_assets_list_div').hide();
			$('#user_assets_form_div').show();
			$('#user_assets_form_div').load('<?php echo site_url('/users_assets/form/'); ?>');
		}
	});*/
<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#user_assets_grid").jqGrid("navButtonAdd","#user_assets_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
		
			var gsr = jQuery("#user_assets_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Only One Row'); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#loading_top").css("display","block");
					////$("#loading_dialog").dialog("open");
					$('#user_assets_form_div').show();
					$('#user_assets_list_div').hide();
					$('#user_assets_form_div').load('<?php echo site_url('users_assets/form/id'); ?>/'+gsr[0]);
				}
			} else {
					$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Row'); ?>");
					$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_usr_assets_var_yesno=$("#conf_dialog_user_assets<?php echo $time; ?>");
	conf_dialog_usr_assets_var_yesno.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					conf_dialog_usr_assets_var_yesno.dialog("close");
					cancel_users_assets(); 
				},
				No: function () {
					conf_dialog_usr_assets_var_yesno.dialog("close");
				}
			},
		});
	cancelloading();
}); 
function submitFormUsersAssets(id){
	$("#loading_top").css("display","block");
	////$("#loading_dialog").dialog("open");
	//if($("#assets_ids").val()==null)

	$.post("<?php echo site_url('users_assets/form/id'); ?>/"+id, $("#frm_users_assets").serialize(), 
			function(data){
				if(data){
					$('#user_assets_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog("open");
					$('#user_assets_list_div').show();
					$('#user_assets_form_div').hide();
					jQuery("#user_assets_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	return false;	
}
$("#alert_dialog").dialog({
	autoOpen: false,
	modal: true,
	title:'<?php echo $this->lang->line("Alert_Box"); ?>',
	open : function(){
		setTimeout('$("#alert_dialog").dialog("close")',5000);
	}
});
function cancel_users_assets(){
	////$("#loading_dialog").dialog("open");
	$('#user_assets_list_div').show();
	$('#user_assets_form_div').hide();
	jQuery("#user_assets_grid").trigger("reloadGrid");
}
</script>
<div id="user_assets_list_div">
	<table id="user_assets_grid" class="jqgrid"></table>
</div>
<div id="user_assets_pager"></div>

<div id="user_assets_form_div" style="padding:10px;display:none;height:450px;">
</div>

<div id="conf_dialog_user_assets<?php echo $time; ?>" style="display:none;">
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