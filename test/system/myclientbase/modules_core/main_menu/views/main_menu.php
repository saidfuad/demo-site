<style>
#load_menu_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadSWFupload();
</script>
<script type="text/javascript">
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true}); 
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#menu_grid").jqGrid({
		url:"<?php echo site_url('main_menu/loadData'); ?>", 
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Menu Name"); ?>','<?php echo $this->lang->line("Menu Link"); ?>','<?php echo $this->lang->line("Where To Show"); ?>','<?php echo $this->lang->line("Menu Sound"); ?>','<?php echo $this->lang->line("Tab Title"); ?>','<?php echo $this->lang->line("Menu Level"); ?>','<?php echo $this->lang->line("Parent Menu"); ?>','<?php echo $this->lang->line("Menu Image"); ?>','<?php echo $this->lang->line("Priority"); ?>','<?php echo $this->lang->line("Type"); ?>','<?php echo $this->lang->line("Priority"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"menu_name",editable:true, index:"menu_name", width:200, align:"center", jsonmap:"menu_name"},
			{name:"menu_link",editable:true, index:"menu_link", width:200, align:"center", jsonmap:"menu_link"},
			{name:"where_to_show",editable:true, index:"where_to_show", width:200, align:"center", jsonmap:"where_to_show"},
			{name:"menu_sound",editable:true, index:"menu_sound", width:200, align:"center", jsonmap:"menu_sound"},
			{name:"tab_title",editable:true, index:"tab_title", width:200, align:"center", jsonmap:"tab_title"},
			{name:"menu_level",editable:true, index:"menu_level", width:200, align:"center", jsonmap:"menu_level"},
			{name:"parent_menu_id",editable:true, index:"parent_menu_id", width:200, align:"center", jsonmap:"parent_menu_id"},
			{name:"menu_image",editable:true, index:"menu_image", width:200, align:"center", jsonmap:"menu_image"},
			{name:"priority",editable:true, index:"priority", width:200, align:"center", jsonmap:"priority"},
			{name:"type",editable:true, index:"type", width:200, align:"center", jsonmap:"type", formatter:'select', editoptions:{value:"1:Report;0:Master"}},
			{name:"sub_settings",editable:true, index:"sub_settings", width:200, align:"center", jsonmap:"sub_settings"},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#menu_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false, 
		caption:"<?php echo $this->lang->line("menu List"); ?>",
		editurl:"<?php echo site_url('main_menu/deleteData'); ?>", 
		jsonReader: { repeatitems : false, id: "0" }
	});
	 
	jQuery("#menu_grid").jqGrid("navGrid", "#menu_pager", {add:false, edit:false, del:true, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#menu_grid").jqGrid("navButtonAdd","#menu_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			$('#menu_list_div').hide();
			$('#menu_form_div').show();
			$('#menu_form_div').load('<?php echo site_url('/main_menu/form/'); ?>');
		}
	});
	
	jQuery("#menu_grid").jqGrid("navButtonAdd","#menu_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#menu_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#loading_top").css("display","block");
					$('#menu_form_div').show();
					$('#menu_list_div').hide();
					$('#menu_form_div').load('<?php echo site_url('main_menu/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	jQuery("#menu_grid").jqGrid("navButtonAdd","#menu_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
		
			qrystr ="/cmd/export";
			document.location = "<?php echo site_url('main_menu/loadData'); ?>"+qrystr;
		}
	});
	 cancelloading();
});
 
function submitForm_Main_menu(id){

	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('main_menu/form/id'); ?>/"+id, $("#frm_menu").serialize(), 
			function(data){
				$("#loading_dialog").dialog("close"); 
				if(data){
					$('#menu_form_div').html(data);
				}
				//else{
				if(id != "")  
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else 
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				//$("#alert_dialog").dialog('open');
				$('#menu_form_div').hide();
				$('#menu_list_div').show();
				jQuery("#menu_grid").trigger("reloadGrid");
				//}
				$("#loading_top").css("display","none");
			}  
		);
	return false;	
}

function cancel_menu(){
	$('#menu_list_div').show();
	$('#menu_form_div').hide();
	jQuery("#menu_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
} 
function searchallpoints(){
	
	var sdate = $('#sdate').val();
	var edate = $('#edate').val();
	var device = $('#device').val();
	
	//$("#allpoints_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
		
	jQuery("#menu_grid").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:device, page:1}}).trigger("reloadGrid");
	
	return false;	
}

function payment_status_forother(cellvalue, options, rowObject){
	if(rowObject.payment_status=='Unpaid')
		return "<span style='color:red'>"+cellvalue+"</span>";
	else
		return "<span style='color:green'>"+cellvalue+"</span>";
}
function payment_status(cellvalue, options, rowObject){
	if(cellvalue=='Unpaid')
		return "<span style='color:red'>"+cellvalue+"</span>";
	else
		return "<span style='color:green'>"+cellvalue+"</span>";
//	rowObject.account
}

</script>
<div id="menu_list_div">
<table id="menu_grid" class="jqgrid"></table> 
</div>
<div id="menu_pager"></div>
<!--<div id="alert_dialog" title="" style="display:none"></div>-->
<div id="menu_form_div" style="padding:10px;display:none;height:450px;">
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