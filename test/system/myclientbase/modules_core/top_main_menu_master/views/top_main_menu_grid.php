<style>
#load_top_main_menu_master_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var asets_t_exist_text=true;
var asets_t_exist_link=true;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#top_main_menu_master_grid").jqGrid({
		url:"<?php echo site_url('top_main_menu_master/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Text"); ?>','<?php echo $this->lang->line("Link"); ?>', '<?php echo $this->lang->line("Add uid"); ?>', '<?php echo $this->lang->line("Add Date"); ?>', '<?php echo $this->lang->line("Del uid"); ?>', '<?php echo $this->lang->line("Del Date"); ?>','<?php echo $this->lang->line("Status"); ?>','<?php echo $this->lang->line("Comments"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"Text",editable:true, index:"Text", width:180, align:"center", jsonmap:"test"},
			{name:"link",editable:true, index:"link", width:120, align:"center", jsonmap:"link"},
			{name:"add_uid",editable:true,hidden:true, index:"add_uid", width:150, align:"center", jsonmap:"add_uid"},
			{name:"add_date",editable:true,hidden:true, index:"add_date", width:150, align:"center", jsonmap:"add_date"},
			{name:"del_uid",editable:true,hidden:true, index:"del_uid", width:150, align:"center", jsonmap:"del_uid"},
			{name:"del_date",editable:true,hidden:true, index:"del_date", width:150, align:"center", jsonmap:"del_date"},
			{name:"status",editable:true,hidden:true, index:"status", width:150, align:"center", jsonmap:"status"},
			{name:"comments",editable:true, index:"comments", width:150, align:"center", jsonmap:"comments"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#top_main_menu_master_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Top Main Menu Master"); ?>",
		editurl:"<?php echo site_url('top_main_menu_master/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#top_main_menu_master_grid").jqGrid("navGrid", "#top_main_menu_master_pager", {add:false, edit:false, del:true, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#top_main_menu_master_grid").jqGrid("navButtonAdd","#top_main_menu_master_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			//$("#top_main_menu_master_loading").dialog("open");
			$('#top_main_menu_master_list_div').hide();
			$('#top_main_menu_master_form_div').show();
			$('#top_main_menu_master_form_div').load('<?php echo site_url('/top_main_menu_master/form/'); ?>');
		}
	});

	jQuery("#top_main_menu_master_grid").jqGrid("navButtonAdd","#top_main_menu_master_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#top_main_menu_master_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#loading_dialog").dialog("open");
					$('#top_main_menu_master_form_div').show();
					$('#top_main_menu_master_list_div').hide();
					$('#top_main_menu_master_form_div').load('<?php echo site_url('top_main_menu_master/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	cancelloading();
});

function check_text_type(id)
{
	var a_t_nm=$.trim($("#Text").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('top_main_menu_master/chk_text/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
		//alert(data);
		if(data=="false")
		{
			asets_t_exist_text=false;
			$("#text_type_error").show();
			return false;
		}
		else
		{
			asets_t_exist_text=true;
			$("#text_type_error").hide();
		}
	});
}

function check_link_type(id)
{
	var a_t_nm=$.trim($("#link").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('top_main_menu_master/chk_link/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,{url : a_t_nm},function(data)
	{
	//	alert(data);
		if(data=="false")
		{
			asets_t_exist_link=false;
			$("#link_type_error").show();
		}
		else
		{
			asets_t_exist_link=true;
			$("#link_type_error").hide();
		}
	});
}
function submitFormtop_main_menu_master(id){
if(id != "")
{
	$.post("<?php echo site_url('top_main_menu_master/form/id'); ?>/"+id, $("#frm_top_main_menu_master").serialize(), 
					function(data){
						if($.trim(data)){
				
							$('#top_main_menu_master_form_div').html(data);
						}else{
							if(id != "")
								$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
							else
								$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
								$('#top_main_menu_master_list_div').show();
								$('#top_main_menu_master_form_div').hide();
								$("#alert_dialog").dialog('open');
							jQuery("#top_main_menu_master_grid").trigger("reloadGrid");
						}
				//$("#top_main_menu_master_loading").dialog("close");
					} 
				);
				return false;
}else{
$.post("<?php echo site_url('top_main_menu_master/chk_link/nm'); ?>/"+id, {val:$("#link").val()},function (data) {
		if(data == "true") 
		{
				$.post("<?php echo site_url('top_main_menu_master/chk_text/nm'); ?>/"+id, {val:$("#Text").val()},function (data) {
				if(data == "true") 
				{
					$.post("<?php echo site_url('top_main_menu_master/form/id'); ?>/"+id, $("#frm_top_main_menu_master").serialize(), 
					function(data){
						if($.trim(data)){
				
							$('#top_main_menu_master_form_div').html(data);
						}else{
							if(id != "")
								$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
							else
								$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
								$('#top_main_menu_master_list_div').show();
								$('#top_main_menu_master_form_div').hide();
								$("#alert_dialog").dialog('open');
							jQuery("#top_main_menu_master_grid").trigger("reloadGrid");
						}
				//$("#top_main_menu_master_loading").dialog("close");
					} 
				);
			}else{
				$("#text_type_error").show();
				return false;
			}
			});
		}else{
			$("#link_type_error").show();
			return false;
		}
	});
	return false;	
}
return false;
}
function cancel_top_main_menu_master(){
	$('#top_main_menu_master_list_div').show();
	$('#top_main_menu_master_form_div').hide();
	jQuery("#top_main_menu_master_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>top_main_menu_master/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function top_main_menu_masterPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>top_main_menu_master/top_main_menu_master_photo/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function DriverPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != "" && cellvalue != null){
		return '<img src="<?php echo base_url(); ?>top_main_menu_master/driver_photo/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="top_main_menu_master_list_div">
	<table id="top_main_menu_master_grid" class="jqgrid"></table>
</div>
<div id="top_main_menu_master_pager"></div>

<div id="top_main_menu_master_form_div" style="padding:10px;display:none;height:450px;">
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