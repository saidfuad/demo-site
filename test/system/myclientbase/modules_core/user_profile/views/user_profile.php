<?php $time=time(); ?>
<?php $rNo = strtotime(date("H:i:s")); ?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_user_profile_grid
{
	display:none !important;
}
</style>
<script type="text/javascript">
var vfields= new Array();
var user_conf_dialog_usr_abcd;
jQuery().ready(function (){

	jQuery(".date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#user_profile_grid").jqGrid({
		url:"<?php echo site_url('user_profile/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("profile_name"); ?>','<?php echo $this->lang->line("Charges(Per Day)"); ?>','<?php echo $this->lang->line("profile_desc"); ?>','<?php echo $this->lang->line("status"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"profile_name",editable:true, index:"profile_name", width:120, align:"center", jsonmap:"profile_name"},
			{name:"charges_per_day",editable:true, index:"charges_per_day", width:120, align:"center", jsonmap:"charges_per_day"},
			{name:"profile_desc",editable:true, index:"profile_desc", width:250, align:"center", jsonmap:"profile_desc"},
			{name:"status",editable:true, index:"status", width:120, align:"center", jsonmap:"status", formatter:'select', editoptions:{value:"1:Active;0:Inactive"}}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#user_profile_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("User Profile List"); ?>",
		editurl:"<?php echo site_url('user_profile/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" },
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
		   var subgrid_table_id;
		   subgrid_table_id = subgrid_id+"_t";
		   jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
		   jQuery("#"+subgrid_table_id).jqGrid({
			  url:"<?php echo site_url('user_profile/subdata'); ?>?q=2&id="+row_id,
			  datatype: "json",
			  colNames: ['<?php echo $this->lang->line("ID"); ?>','<?php echo $this->lang->line("description"); ?>','<?php echo $this->lang->line("Type"); ?>','<?php echo $this->lang->line("Sub-Topic"); ?>'],
			  colModel: [
				{name:"id",hidden:true, index:"id", width:50, align:"center", jsonmap:"id"},
				{name:"menu_id",editable:true, index:"menu_id", width:150, align:"center", jsonmap:"menu_id"},
				{name:"type",editable:true, index:"type", width:80, align:"center", jsonmap:"type",formatter:sub_type},
				{name:"sub_setting",editable:true, index:"sub_setting", width:200, align:"center", jsonmap:"sub_setting",formatter:sub_setting},
				                  
			  ],
			rownumbers: true,  
			 height: 'auto', 
			  sortname: 'id',
			  sortorder: "asc",
			  jsonReader: { repeatitems : false, id: "0" },
		   });
		 
		 },
		jsonReader: { repeatitems : false, id: "0" },
	});

	 user_conf_dialog_usr_abcd=$("#user_conf_dialog_usr_abcd<?php echo $time; ?>");
		user_conf_dialog_usr_abcd.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					user_conf_dialog_usr_abcd.dialog("close");
					cancel_user_profile();
				},
				No: function () {
					user_conf_dialog_usr_abcd.dialog("close");
				}
			},
		
		});
	
	jQuery("#user_profile_grid").jqGrid("navGrid", "#user_profile_pager", {add:false, edit:false, del:true, search:true}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#user_profile_grid").jqGrid("navButtonAdd","#user_profile_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
		$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#user_profile_list_div').hide();
			$('#user_profile_form_div').show();
			$('#user_profile_form_div').load($.trim('<?php echo site_url('/user_profile/form/'); ?>'));
			
		}
	});
	jQuery("#user_profile_grid").jqGrid("navButtonAdd","#user_profile_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#user_profile_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#user_profile_form_div').show();
					$('#user_profile_list_div').hide();
					$('#user_profile_form_div').load($.trim('<?php echo site_url('user_profile/form/id'); ?>/'+gsr[0]));
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	jQuery("#user_profile_grid").jqGrid("navButtonAdd","#user_profile_pager",{caption:"<?php echo $this->lang->line("Copy"); ?>",
		onClickButton:function(){
		
			var gsr = jQuery("#user_profile_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#user_profile_list_div').hide();
			$('#user_profile_form_div').show();
			$('#user_profile_form_div').load($.trim('<?php echo site_url('/user_profile/form/copy_id'); ?>/'+gsr[0]));
			
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
				
		}
	});
	
	$("#jsonDatatest").click(function(){
		$.post("<?php echo site_url('user_profile/get_json_data'); ?>",function(data){
			$("#jsonDatatest").html(data);
		});
	});
	$("#processingDialogUser_profile").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true
	});

	$("#divLoading<?php echo $rNo; ?>").dialog({
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true
		});
	$("#loading_top").css("display","none");
});

function submitFormUser_profile(id){
 	$("#loading_top").css("display","block");
	 $.post("<?php echo site_url('user_profile/form/id'); ?>/"+id, $("#frm_user_profile").serialize(), 
		function(data){
			if($.trim(data)){
				$('#user_profile_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				cancel_user_profile();
				}
				$("#loading_top").css("display","none");
			}
		);
	return false;	
}

function cancel_user_profile(){
	//$("#loading_dialog").dialog("open");
	$('#user_profile_list_div').show();
	$('#user_profile_form_div').hide();
	jQuery("#user_profile_grid").trigger("reloadGrid");
}
function sub_setting(cellvalue, options, rowObject){
 return cellvalue.replace("main,","").replace(",main","").replace("main","");

}
function sub_type(cellvalue, options, rowObject){
 if(cellvalue==0)
	return "Menu";
else
	return "Report";

}


</script>
<div id="user_profile_list_div">
	<table id="user_profile_grid" class="jqgrid"></table>
</div>
<div id="user_profile_pager"></div>

<div id="user_profile_form_div" style="padding:10px;display:none;">
</div>
<div id="user_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
<!-- <div id="jsonDatatest" style="background-color:yellow">&nbsp; click here</div> -->


<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialogUser_profile" style="display:none">
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