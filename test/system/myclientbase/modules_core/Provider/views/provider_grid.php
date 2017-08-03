<div id="providerDivAjaxMsg" style="display:none"></div>
<script type="text/javascript">
var duplicate_prov=false;
jQuery().ready(function (){
		$("#providerDivAjaxMsg").dialog({
		modal: true,
        bgiframe: true,
        width: 320,
        height: 150,
		autoOpen: false,
	  	draggable: false,
		resizable: false,
	});
	$("#tprovider_del_confirm").dialog({
      modal: true,
        bgiframe: true,
        width: 320,
        height: 150,
		autoOpen: false,
	  	draggable: false,
		resizable: false,
	
      });
	  
	  
$("#providerDivAjaxMsg").dialog('option', 'buttons', {
						"Ok" : function() {
							$(this).dialog("close");
							 }
						});
	//$("div").live("mouseover", function(){$(this).css({border:"3px solid red"});});
	//$("div").live("mouseout", function(){$(this).css({border:""});});
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#tprovider_ds_list").jqGrid({
		url: "<?php echo site_url('/provider/loadData'); ?>",
		datatype: "json",
		colNames:["Id",'menu_name','menu_link','where_to_show','menu_sound','tab_title','menu_level','parent_menu_id','menu_image','user_id','Comments'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"menu_name",editable:true, index:"menu_name", width:90, align:"center", jsonmap:"menu_name"},
			{name:"menu_link",editable:true, index:"menu_link", width:90, align:"center", jsonmap:"menu_link"},
			{name:"where_to_show",editable:true, index:"where_to_show", width:90, align:"center", jsonmap:"where_to_show"},
			{name:"menu_sound",editable:true, index:"menu_sound", width:90, align:"center", jsonmap:"menu_sound"},
			{name:"tab_title",editable:true, index:"tab_title", width:90, align:"center", jsonmap:"tab_title"},
			{name:"menu_level",editable:true, index:"menu_level", width:90, align:"center", jsonmap:"menu_level"},
			{name:"parent_menu_id",editable:true, index:"parent_menu_id", width:90, align:"center", jsonmap:"parent_menu_id"},
			{name:"menu_image",editable:true, index:"menu_image", width:90, align:"center", jsonmap:"menu_image"},
			{name:"user_id",editable:true, index:"user_id", width:90, align:"center", jsonmap:"user_id"},
			{name:"Comments",editable:true, index:"Comments", width:90, align:"center", jsonmap:"Comments"}
		],
		rowNum:100,
		height: "100%",
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#provider_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "ASC",
		caption:"Providers Available in the System",
		editurl: "<?php echo site_url('/provider/loadData'); ?>",
		jsonReader: { repeatitems : false, id: "0" },
	//	toolbar: [true,"top"]
	});

	jQuery("#tprovider_ds_list").jqGrid("navGrid", "#provider_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#tprovider_ds_list").jqGrid("navButtonAdd","#provider_pager",{caption:"Add",
		onClickButton:function(){
			$("#loading_dialog").dialog("open");
			$('#provider_grid_div').hide();
			$('#provider_frm_div').show();
			$('#provider_frm_div').load('<?php echo site_url('/provider/form/'); ?>');
		}
	});

	jQuery("#tprovider_ds_list").jqGrid("navButtonAdd","#provider_pager",{caption:"Edit",
		onClickButton:function(){
			var gsr = jQuery("#tprovider_ds_list").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#providerDivAjaxMsg").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#providerDivAjaxMsg").dialog("open");
				}
				else{
					$("#loading_dialog").dialog("open");
					$('#provider_frm_div').show();
					$('#provider_grid_div').hide();
					$('#provider_frm_div').load('<?php echo site_url('/provider/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#providerDivAjaxMsg").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#providerDivAjaxMsg").dialog("open");
			}
		}
	});
	
	jQuery("#tprovider_ds_list").jqGrid("navButtonAdd","#provider_pager",{caption:"Delete",
		onClickButton:function(e){
		e.preventDefault();
			
			var gsr = jQuery("#tprovider_ds_list").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				$("#tprovider_del_confirm").dialog('option', 'buttons', {
                "Confirm" : function() {
				    $(this).dialog("close");
					$("#loading_dialog").dialog("open");
					for(i=0;i<gsr.length;i++)
					{
					$.post("<?php echo site_url('provider/delete_Data/id'); ?>/"+gsr, $("#tprovider_ds_frm").serialize(),
						function(data){
							$("#loading_dialog").dialog("close");
							$("#providerDivAjaxMsg").html(data);
							$("#providerDivAjaxMsg").dialog("open");
							jQuery("#tprovider_ds_list").trigger("reloadGrid");
						});
					}
				   },
				   
                "Cancel" : function() {
                    $(this).dialog("close");
                    }
                });
			$("#tprovider_del_confirm").dialog("open");
			
			} else {
				$("#providerDivAjaxMsg").html("Please Select Row");
				$("#providerDivAjaxMsg").dialog("open");
			}
		}
	});

});

function check_provider_duplication(id){
	val = $("#ttbl_provider_ds_form_provider").val();
	
	if(val.length>0)
	{	
		$("#loading_dialog").dialog("open");
		$.post("<?php echo site_url('provider/provider_duplicate/provider'); ?>/"+val+"/id/"+id, 
			function(data){
				$("#loading_dialog").dialog("close");
				if(data=="false")
				{
					$("#app_menu_master_name_duplicate_error").html("<?php echo $this->lang->line("Duplicate_Provider_Found"); ?>");
					$("#app_menu_master_name_duplicate_error").show();
					duplicate_prov=true;
				}
				else
				{
					$("#app_menu_master_name_duplicate_error").html("");
					$("#app_menu_master_name_duplicate_error").hide();
					duplicate_prov=false;
				}
				//$("#ttbl_provider_ds_form_comments").focus();
		});
		//document.getElementById("ttbl_provider_ds_form_comments").focus();
		$("#ttbl_provider_ds_form_comments").focus();
	}
	//$("#ttbl_provider_ds_form_comments").focus();
}
function submitFormUsers_tbl_provider(id){
if(duplicate_prov!=true)
{
	$("#loading_dialog").dialog("open");
	$.post("<?php echo site_url('provider/form/id'); ?>/"+id, $("#ttbl_provider_ds_frm").serialize(), 
			function(data){
				if(data){
					$('#provider_frm_div').html(data);
				}else{
					if(id != "")
						$("#providerDivAjaxMsg").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
						$("#providerDivAjaxMsg").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#providerDivAjaxMsg").dialog('open');
					$('#provider_frm_div').hide();
					$('#provider_grid_div').show();
					jQuery("#tprovider_ds_list").trigger("reloadGrid");
				}
				$("#loading_dialog").dialog("close");
			} 
		);
		return false;
}
	return false;	
}

function cancel_users_tbl_provider(){
	$('#provider_grid_div').show();
	$('#provider_frm_div').hide();
	jQuery("#tprovider_ds_list").trigger("reloadGrid");
}

</script>
<style>
.ui-jqgrid tr.jqgrow td {vertical-align:middle}
</style>
<div id="provider_grid_div">
	<table id="tprovider_ds_list"></table>
</div>
<div id="provider_pager"></div>

<div id="provider_frm_div" style="padding:10px;display:none;height:450px;">
</div>

<div id="tprovider_del_confirm" title="" style="display:none"><?php echo $this->lang->line("Are_You_sure_you_want_to_delete_selected_records"); ?>.?</div>
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