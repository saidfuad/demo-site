<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Add","Edit","Import");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'93');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
?>
<?php $time=time(); ?>
<?php $rNo = strtotime(date("H:i:s")); ?>
<style>
#load_import_locations_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
jQuery.fn.LatLongValidate =
function()
{
    return this.each(function()
    { 
		$(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
			
			if(this.value.indexOf('.') != -1){
				var val = this.value.split('.');
				val = val[1];
				if(val.length == 6){
					return(key == 8 || key == 9 || key == 46 || key == 18 || (key >= 35 && key <= 40));
				}else{
					return (
					key == 109 ||
					key == 173 ||
					key == 110 || 
					key == 190 || 
					key == 8 || 
					key == 9 ||
					key == 46 ||
					key == 18 ||
					(key >= 35 && key <= 40) ||
					(key >= 48 && key <= 57 && e.shiftKey === false) ||
					(key >= 96 && key <= 105));
				}
			}else{
				if(this.value.length >= 3){
					return (key == 8 || key == 46 || (key==190 && e.shiftKey === false) || key==110 || (key >= 35 && key <= 40));
				}else{
					return (
					key == 109 ||
					key == 173 ||
					key == 110 || 
					key == 190 || 
					key == 8 || 
					key == 9 ||
					key == 46 ||
					key == 18 ||
					(key >= 35 && key <= 40) ||
					(key >= 48 && key <= 57 && e.shiftKey === false) ||
					(key >= 96 && key <= 105));
				}
			}			
        });
    });
};
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#import_locations_grid").jqGrid({
		url:"<?php echo site_url('import_locations/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("id"); ?>",'<?php echo $this->lang->line("Cell_Id"); ?>','<?php echo $this->lang->line("lac"); ?>','<?php echo $this->lang->line("Address"); ?>','<?php echo $this->lang->line("latitude"); ?>','<?php echo $this->lang->line("longitude"); ?>','<?php echo $this->lang->line("Add Date"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"cell_id", hidden:true, width:120, index:"cell_id", align:"center", jsonmap:"cell_id"},
			{name:"lac", hidden:true, width:120, index:"lac", align:"center", jsonmap:"lac"},
			{name:"address",editable:true, index:"address", width:200, align:"center", jsonmap:"address"},
			{name:"latitude",editable:true, index:"latitude", width:150, align:"center", jsonmap:"latitude"},
			{name:"longitude",editable:true, index:"longitude", width:150, align:"center", jsonmap:"longitude"},
			{name:"add_date",editable:true, index:"add_date", width:120, align:"center", jsonmap:"add_date"},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#import_locations_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true,
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
		},	
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Import Location"); ?>",
		editurl:"<?php echo site_url('import_locations/deleteData'); ?>",
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
	jQuery("#import_locations_grid").jqGrid("navGrid", "#import_locations_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#import_locations_grid").jqGrid("navButtonAdd","#import_locations_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			$('#import_locations_list_div').hide();
			$('#import_locations_form_div').show();
			$('#import_locations_form_div').load('<?php echo site_url('/import_locations/form/'); ?>');
			$("#loading_top").css("display","none");
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#import_locations_grid").jqGrid("navButtonAdd","#import_locations_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#import_locations_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					
					
					$("#loading_top").css("display","block");
			
					$('#import_locations_form_div').show();
					$('#import_locations_list_div').hide();
					var gsrval = jQuery("#import_locations_grid").jqGrid('getCell', gsr[0], 'id');
					$('#import_locations_form_div').load('<?php echo site_url('import_locations/form/id'); ?>/'+gsrval);
					$("#loading_top").css("display","none");
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	<?php
	if(in_array('Import',$data)){
	?>
	jQuery("#import_locations_grid").jqGrid('navButtonAdd','#import_locations_pager',{caption:"Import",
		onClickButton:function(){
			$('#import_locations_list_div').hide();	
			$("#dialog-import-import_locations").show();
		} 
	});
	<?php } ?>
	
	conf_dialog_import_locations_lokkup=$("#conf_dialog_import_locations<?php $time=time(); ?>");
	conf_dialog_import_locations_lokkup.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_import_locations_lokkup.dialog("close");
				cancel_import_locations(); 
			},
			No: function () {
				conf_dialog_landmark_group_lokkup.dialog("close");
			}
		},
	});
	$("#processingDialogimport_locations").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true
	});
	$("#btnClearProcessingimport_locations").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg_import_locations").html('');
		jQuery("#uploadedfile_import_locations").html('');
		jQuery("#sheet").html('');	
	});
	$("#divLoading<?php echo $rNo; ?>").dialog({
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true
	});
	$("#btnBackImport_locations").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg_import_locations").html('');
		jQuery("#uploadedfile_import_locations").html('');
		jQuery("#sheet").html('');	
		$('#import_locations_list_div').show();	
		$("#dialog-import-import_locations").hide();
	});
cancelloading();
});
strTable = 'tbl_cell_data';
function submitFormimport_locations(id){
	$("#error_frm_duplicate").css('display','none');
	$("#error_frm_lati").css('display','none');
	$("#error_frm_longi").css('display','none');
	$("#error_frm_address").css('display','none');
	
	$("#loading_top").css("display","block");
	var latitude=$("#latitude").val();
	var longitude=$("#longitude").val();
	var address=$("#address_add").val();
	if(latitude==""){
		$("#error_frm_lati").html('The Latitude field is required.');
		$("#error_frm_lati").css('display','block');
		return false;
	}else{
		$("#error_frm_lati").css('display','none');
	}
	
	if(longitude==""){
		$("#error_frm_longi").html('The Longitude field is required.');
		$("#error_frm_longi").css('display','block');
		return false;
	}else{
		$("#error_frm_longi").css('display','none');
	}
	
	if(address==""){
		$("#error_frm_address").html('The Address field is required.');
		$("#error_frm_address").css('display','block');
		return false;
	}else{
		$("#error_frm_address").css('display','none');
	}
	
	$.post("<?php echo site_url('import_locations/chkLatLong/id'); ?>/"+id,{latitude:latitude,longitude:longitude},function(data){
	if(Number(data) > 0){
		$("#error_frm_duplicate").html('Duplicate Latitude - Longitude found.');
		$("#error_frm_duplicate").css('display','block');
		return false;
	}
	$("#error_frm_duplicate").css('display','none');
	$.post("<?php echo site_url('import_locations/form/id'); ?>/"+id, $("#frm_import_locations").serialize(), 
			function(data){
				if(data){
					$('#import_locations_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#import_locations_list_div').show();
					$('#import_locations_form_div').hide();
					jQuery("#import_locations_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	});
	return false;	
}
function cancel_import_locations(){
	//$("#loading_dialog").dialog("open");
	$('#import_locations_list_div').show();
	$('#import_locations_form_div').hide();
	jQuery("#import_locations_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}

var oldTblContent="";
var oldDisplayContentimport_locations="";
function exportNotFoundimport_locations(){
	
	document.forms[0].csvBuffer.value= $("#ReportTable").html();			
	document.forms[0].method='POST';
	document.forms[0].action='<?php echo base_url(); ?>import_locations.php';  // send it to server which will open this contents in excel file
	document.forms[0].target='_blank';
	document.forms[0].submit();
}
function resetAllImportimport_locations(){
	$("#frmImportimport_locations")[0].reset();
	jQuery("#processingImportDetail").html('');
	jQuery("#uploadmsg_import_locations").html('<b><span id="uploadmsg_import_locations">Select File</span></b>');
	jQuery("#uploadedfile_import_locations").html('');
	jQuery("#sheet").html('');
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-import_locations").css("display","block");
}
function resetDisplayimport_locations()
{	
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-import_locations").css("display","block");
}

function uploadExcelimport_locations(){
	//$("#frmImportimport_locations").attr('action',"<?php echo base_url(); ?>upload_excel_import_locations.php");
	//$("#frmImportimport_locations").submit();
	
	document.frmImportimport_locations.action='<?php echo base_url(); ?>upload_excel_import_locations.php';  // send it to server which will open this contents in excel file
	document.frmImportimport_locations.submit();
	iTimeOutlimport_locations = setTimeout('getStatusimport_locations();',500);
}

function getStatusimport_locations()
{
	if($("#uploadedfile_import_locations").text()==""){
		iTimeOutlimport_locations = setTimeout('getStatusimport_locations();',500);
	}
	else{
		clearTimeout(iTimeOutlimport_locations);
		fillDetailsimport_locations();		
	}
}
function fillDetailsimport_locations()
{	
	var sUrl = "<?php echo base_url(); ?>import_locations.php";
	var file = $('#uploadedfile_import_locations').text();
	$("#loading_top").css("display","block");;
	var parameters = "cmd=sheet&file=" + file + "&table=" + strTable ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handlefillDetailsimport_locations
	});					
}
function handlefillDetailsimport_locations(msg)
{	
	$("#sheet").html(msg);
	fillFieldsimport_locations(0);
}
function fillFieldsimport_locations(sheetvalue){
	var sUrl = "<?php echo base_url(); ?>import_locations.php";
	var file = $('#uploadedfile_import_locations').text();
	var parameters = "cmd=fields&file=" + file + "&table=" + strTable + "&sheet=" + sheetvalue ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handleFillFieldimport_locations
	});
}
function handleFillFieldimport_locations(msg)
{
	$("#processingImportDetail").html(msg);
	$("#loading_top").css("display","none");;
}

function importExcelimport_locations(){
	var file = $('#uploadedfile_import_locations').text();
   
	if(file == ""){
		return false;
	}
	$("#loading_top").css("display","block");;
	$.post( 
        "<?php echo base_url(); ?>import_locations.php?cmd=insert&table="+strTable+"&file="+file, 
        $("#frmImportimport_locations").serialize(), 
        function(data){
			if(data.result == "true"){
				$("#processingDialogimport_locations").html(data.msg);
				$("#loading_top").css("display","none");;
				$("#processingDialogimport_locations").dialog("open");
				jQuery("#processingImportDetail").html('');
				jQuery("#uploadmsg_import_locations").html('');
				jQuery("#uploadedfile_import_locations").html('');
				jQuery("#sheet").html('');
				jQuery("#filename").val('');							
				resetAllImportimport_locations();
				$('#import_locations_list_div').show();	
				$("#dialog-import-import_locations").hide();
				jQuery("#import_locations_grid").trigger("reloadGrid");
			}else{
				$("#processingDialogimport_locations").html(data.error);
				$("#loading_top").css("display","none");;
				$("#processingDialogimport_locations").dialog("open");				
			}
        },"json" 
    );	
}

function displayExcelimport_locations(){
	var file = $('#uploadedfile_import_locations').text();
	
	if(file == ""){
		return false;
	}
	$("#loading_top").css("display","block");;
    $.post("<?php echo base_url(); ?>import_locations.php?cmd=display&table="+strTable+"&file="+file, 
        $("#frmImportimport_locations").serialize(), 
        function(data){
			$("#loading_top").css("display","none");;
			if(oldDisplayContentimport_locations == ""){
				oldDisplayContentimport_locations = $("#divDisplayProcessing").html();
			}
			var html = data.html + oldDisplayContentimport_locations;
			$("#divDisplayProcessing").html(html);
			
			if(data.importRec == 'false'){
				$('#btnImport').hide();
			}
			else{
				$('#btnImport').show();
			}
			$("#divDisplayProcessing").css("display","block");
			$("#dialog-import-import_locations").css("display","none");	
        }, 'json' 
    );
	
}
</script>
<div id="import_locations_list_div">
	<table id="import_locations_grid" class="jqgrid"></table>
</div>
<div id="import_locations_pager"></div>
<div id="import_locations_form_div" style="padding:10px;display:none;height:450px;">

<div id="conf_dialog_import_locations<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
</div>
<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialogimport_locations" style="display:none">
</div>
<div id="divDisplayProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExcelimport_locations()" value="Import"/>&nbsp;&nbsp;&nbsp;<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplayimport_locations()"/>
</div>
<div id="dialog-import-import_locations" title="Import" style="overflow:auto; display:none;">
	<p class="importTips"></p>
<form id="frmImportimport_locations" name="frmImportimport_locations" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>upload_excel_import_locations.php" method="POST" onSubmit="return false">
        <fieldset>
        <table width="100%" class="formtable" id="importtable" name="importtable">
            <tr>
                <td width="50%">
                    <label for="file"><?php echo $this->lang->line("Excel_File"); ?></label>
                    <input type="file" name="filename" id="filename" class="text ui-widget-content ui-corner-all" onChange="uploadExcelimport_locations()"/>
                    <iframe name="hiddenframe" style="display:none"></iframe><b><span id="uploadmsg_import_locations"><?php //echo $this->lang->line("select_file"); ?></span></b>
					  <span id="uploadedfile_import_locations" style="display:none"></span>
                </td>
                <td>
                    <label for="sheets"><?php echo $this->lang->line("Sheet"); ?></label>
                    <select name="sheet" id="sheet" class="select ui-widget-content ui-corner-all" onChange="fillFieldsimport_locations(this.value)"></select>
                </td>
            </tr>
        </table>
		<table width="100%" class="formtable" id="processingImportDetail"></table>
        <table width="100%" class="formtable">
        	<tr align="center">
            	<td><input type="button" name="btnDisplay" id="btnDisplay" onClick="displayExcelimport_locations()" value="Display"/>
                <input type="reset" name="btnClearProcessingimport_locations" id="btnClearProcessingimport_locations"/>&nbsp;<input type="button" value="Back" name="btnBackImport_locations" id="btnBackImport_locations"/>
				</td>
            </tr>
        </table>
        </fieldset>
	</form>
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
