<?php $time=time(); ?>
<?php $rNo = strtotime(date("H:i:s")); ?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_custom_profile_grid
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
	jQuery("#custom_profile_grid").jqGrid({
		url:"<?php echo site_url('custom_profile/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("profile_name"); ?>','<?php echo $this->lang->line("Charges"); ?>', '<?php echo $this->lang->line("Single Assets Price"); ?>', '<?php echo $this->lang->line("Sub User Price"); ?>', '<?php echo $this->lang->line("Sms Price"); ?>', '<?php echo $this->lang->line("Email Price"); ?>', '<?php echo $this->lang->line("profile_desc"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"profile_name",editable:true, index:"profile_name", width:120, align:"center", jsonmap:"profile_name"},
			{name:"charges",editable:true, index:"charges", width:120, align:"center", jsonmap:"charges"},
			{name:"assets_price",editable:true, index:"assets_price", width:150, align:"center", jsonmap:"assets_price"},
			{name:"sub_user_price",editable:true, index:"sub_user_price", width:150, align:"center", jsonmap:"sub_user_price"},
			{name:"sms_price",editable:true, index:"sms_price", width:120, align:"center", jsonmap:"sms_price"},
			{name:"email_price",editable:true, index:"email_price", width:120, align:"center", jsonmap:"email_price"},
			{name:"profile_desc",editable:true, index:"profile_desc", width:150, align:"center", jsonmap:"profile_desc"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#custom_profile_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("Custom Profile"); ?>",
		editurl:"<?php echo site_url('custom_profile/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" },
		
		subGrid: true,
		subGridOptions: {
			  "plusicon"  : "ui-icon-triangle-1-e",
			  "minusicon" : "ui-icon-triangle-1-s",
			  "openicon"  : "ui-icon-arrowreturn-1-e",
			  multiselect: true, 
		},
		//ondblClickRow: loadPlanningSummary,
		subGridUrl: '<?php echo site_url('custom_profile/subdata'); ?>', subGridModel: [{ name : ['Menu', 'Price'], width:[200,100], align:['center', 'center', 'center', 'center', 'center'], params:['id']}],
		subGridBeforeExpand : function(rowid) {
		  var rowIds = jQuery("#custom_profile_grid").jqGrid('getDataIDs');
		   $.each(rowIds, function (index, rowId) {
				  $("#custom_profile_grid").jqGrid('collapseSubGridRow',rowId);
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
					cancel_custom_profile();
				},
				No: function () {
					user_conf_dialog_usr_abcd.dialog("close");
				}
			},
		
		});
	
	jQuery("#custom_profile_grid").jqGrid("navGrid", "#custom_profile_pager", {add:false, edit:false, del:true, search:true}, {}, {}, {}, {multipleSearch:false});
	
	/*jQuery("#custom_profile_grid").jqGrid("navButtonAdd","#custom_profile_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
		$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#custom_profile_list_div').hide();
			$('#custom_profile_form_div').show();
			$('#custom_profile_form_div').load($.trim('<?php echo site_url('/custom_profile/form/'); ?>'));
			
		}
	});*/
	jQuery("#custom_profile_grid").jqGrid("navButtonAdd","#custom_profile_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#custom_profile_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#custom_profile_form_div').show();
					$('#custom_profile_list_div').hide();
					$('#custom_profile_form_div').load($.trim('<?php echo site_url('custom_profile/form/id'); ?>/'+gsr[0]));
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	/*jQuery("#custom_profile_grid").jqGrid("navButtonAdd","#custom_profile_pager",{caption:"Copy",
		onClickButton:function(){
		
			var gsr = jQuery("#custom_profile_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#custom_profile_list_div').hide();
			$('#custom_profile_form_div').show();
			$('#custom_profile_form_div').load($.trim('<?php echo site_url('/custom_profile/form/copy_id'); ?>/'+gsr[0]));
			
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
				
		}
	});
	*/
	$("#jsonDatatest").click(function(){
		$.post("<?php echo site_url('custom_profile/get_json_data'); ?>",function(data){
			$("#jsonDatatest").html(data);
		});
	});
	$("#processingDialogcustom_profile").dialog({
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

function submitFormcustom_profile(id){
 	$("#loading_top").css("display","block");
	 $.post("<?php echo site_url('custom_profile/form/id'); ?>/"+id, $("#frm_custom_profile").serialize(), 
		function(data){
			if($.trim(data)){
				$('#custom_profile_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				cancel_custom_profile();
				}
				$("#loading_top").css("display","none");
			}
		);
	return false;	
}

function cancel_custom_profile(){
	//$("#loading_dialog").dialog("open");
	$('#custom_profile_list_div').show();
	$('#custom_profile_form_div').hide();
	jQuery("#custom_profile_grid").trigger("reloadGrid");
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
<div id="custom_profile_list_div">
	<table id="custom_profile_grid" class="jqgrid"></table>
</div>
<div id="custom_profile_pager"></div>

<div id="custom_profile_form_div" style="padding:10px;display:none;">
</div>
<div id="user_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
<!-- <div id="jsonDatatest" style="background-color:yellow">&nbsp; click here</div> -->


<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialogcustom_profile" style="display:none">
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