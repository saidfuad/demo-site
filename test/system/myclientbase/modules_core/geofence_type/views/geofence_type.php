<?php $time=time(); ?>
<style>
#load_geofence_type_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var asets_t_exist=true;
var conf_dialog_assest_type_vtsloglog;
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#geofence_type_grid").jqGrid({
		url:"<?php echo site_url('geofence_type/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'Type', 'Comments'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"type",editable:true, index:"type", width:180, align:"center", jsonmap:"type"},
			{name:"comments",editable:true, index:"comments", width:280, align:"center", jsonmap:"comments"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#geofence_type_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"Geofence Type",
		editurl:"<?php echo site_url('geofence_type/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#geofence_type_grid").jqGrid("navGrid", "#geofence_type_pager", {add:false, edit:false, del:true, search:true}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#geofence_type_grid").jqGrid("navButtonAdd","#geofence_type_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$('#geofence_type_list_div').hide();
			$('#geofence_type_form_div').show();
			$('#geofence_type_form_div').load('<?php echo site_url('/geofence_type/form/'); ?>');
		}
	});

	jQuery("#geofence_type_grid").jqGrid("navButtonAdd","#geofence_type_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#geofence_type_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#geofence_type_form_div').show();
					$('#geofence_type_list_div').hide();
					$('#geofence_type_form_div').load('<?php echo site_url('geofence_type/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	}); 
	conf_dialog_assest_type_vtsloglog=$("#conf_dialog_geofence_type<?php $time=time(); ?>");
	conf_dialog_assest_type_vtsloglog.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_assest_type_vtsloglog.dialog("close");
				cancel_geofence_type(); 
			},
			No: function () {
				conf_dialog_assest_type_vtsloglog.dialog("close");
			}
		},
	

	});
 cancelloading();
});
function check_geofence_type(id)
{
	var a_t_nm=$.trim($("#type").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('geofence_type/check_duplicates/type'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
//		alert(data);
		if(data=="false")
		{
			asets_t_exist=false;
			$("#geofence_type_error").show();
		}
		else
		{
			asets_t_exist=true;
			$("#geofence_type_error").hide();
		}
	});
}
function submitFormgeofence_type(id){

	if(asets_t_exist==true)
	{
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('geofence_type/form/id'); ?>/"+id, $("#frm_geofence_type").serialize(), 
			function(data){
				if(data){
					$('#geofence_type_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#geofence_type_list_div').show();
					$('#geofence_type_form_div').hide();
					jQuery("#geofence_type_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	}
	return false;	
}
function cancel_geofence_type(){
	//$("#loading_dialog").dialog("open");
	$('#geofence_type_list_div').show();
	$('#geofence_type_form_div').hide();
	jQuery("#geofence_type_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="geofence_type_list_div">
	<table id="geofence_type_grid" class="jqgrid"></table>
</div>
<div id="geofence_type_pager"></div>
<div id="geofence_type_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_geofence_type<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
</body>
</html>