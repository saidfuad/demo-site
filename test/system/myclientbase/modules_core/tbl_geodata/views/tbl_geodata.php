<?php $time=time(); ?>
<style>
#load_ttbl_geodata_ds_list
{
	display:none !important; 
}
</style>
<script type="text/JavaScript">
var asets_t_exist=true;
var conf_dialog_assest_type_vtsloglog;
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#ttbl_geodata_ds_list").jqGrid({
		url:"<?php echo site_url('tbl_geodata/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Cell"); ?>','<?php echo $this->lang->line("Lac"); ?>','Latitude','Longitude','<?php echo $this->lang->line("Address"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"cell_id",editable:true, index:"cell_id", width:90, align:"center", jsonmap:"cell_id"},
			{name:"lac",editable:true, index:"lac", width:90, align:"center", jsonmap:"lac"},
			{name:"latitude",editable:true, index:"latitude", width:90, align:"center", jsonmap:"latitude"},
			{name:"longitude",editable:true, index:"longitude", width:90, align:"center", jsonmap:"longitude"},
			{name:"address",editable:true, index:"address", width:320, align:"center", jsonmap:"address"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#tbl_geodata_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Geofence Data"); ?>",
		editurl:"<?php echo site_url('tbl_geodata/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#ttbl_geodata_ds_list").jqGrid("navGrid", "#tbl_geodata_pager", {add:false, edit:false, del:true, search:true}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#ttbl_geodata_ds_list").jqGrid("navButtonAdd","#tbl_geodata_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#ttbl_geodata_ds_list_div").hide();
			$("#tbl_geodata_form_div").show();
			$("#tbl_geodata_form_div").load("<?php echo site_url("/tbl_geodata/form/"); ?>");
		}
	});

	jQuery("#ttbl_geodata_ds_list").jqGrid("navButtonAdd","#tbl_geodata_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#ttbl_geodata_ds_list").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					$("#tbl_geodata_form_div").show();
					$("#ttbl_geodata_ds_list_div").hide();
					$("#tbl_geodata_form_div").load("<?php echo site_url("tbl_geodata/form/id"); ?>/"+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	}); 
	
	jQuery("#ttbl_geodata_ds_list").jqGrid("navButtonAdd","#tbl_geodata_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var squery = "";
			document.location = "<?php echo site_url("/tbl_geodata/export/cmd/export"); ?>"+squery;
		}
	});
	
	jQuery("#ttbl_geodata_ds_list").jqGrid('navButtonAdd','#tbl_geodata_pager',{caption:"<?php echo $this->lang->line("Import"); ?>",
		onClickButton:function(){
			$('#ttbl_geodata_ds_list_div').hide();	
			$("#dialog-import-tbl_geodata").show();
		} 
	});
	
	$("#btnClearProcessingtbl_geodata").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg_tbl_geodata").html('');
		jQuery("#uploadedfile_tbl_geodata").html('');
		jQuery("#sheet").html('');	
	});
	$("#divLoading<?php echo $rNo; ?>").dialog({
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true
	});
	$("#btnBacktbl_geodata").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg_tbl_geodata").html('');
		jQuery("#uploadedfile_tbl_geodata").html('');
		jQuery("#sheet").html('');	
		$('#ttbl_geodata_ds_list_div').show();	
		$("#dialog-import-tbl_geodata").hide();
	});
	
	$("#processingDialogtbl_geodata").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true
	});
	
});

function submitFormtbl_geodata(id){
	$("#alert_dialog").html("Loading");
	$("#alert_dialog").dialog("open");
	$.post("<?php echo site_url("tbl_geodata/form/id"); ?>/"+id,$("#frm_tbl_geodata").serialize(), function(data){
		$("#alert_dialog").dialog("close");
		if($.trim(data)){
			$("#tbl_geodata_form_div").html(data);
		}else{
			if(id != "")  
				$("#alert_dialog").html("Record Updated Successfully");
			else 
				$("#alert_dialog").html("Record Inserted Successfully");
			$("#alert_dialog").dialog("open");
			$("#tbl_geodata_form_div").hide();
			$("#ttbl_geodata_ds_list_div").show();
			jQuery("#ttbl_geodata_ds_list").trigger("reloadGrid");
		}
	});
	return false;	
}

function cancel_tbl_geodata(){
	$("#ttbl_geodata_ds_list_div").show();
	$("#tbl_geodata_form_div").hide();
	jQuery("#ttbl_geodata_ds_list").trigger("reloadGrid");
}

//var +="";
var oldDisplayContenttbl_geodata="";
function resetAllImporttbl_geodata(){
	$("#frmImporttbl_geodata")[0].reset();
	jQuery("#processingImportDetail").html('');
	jQuery("#uploadmsg_tbl_geodata").html('<b><span id="uploadmsg_tbl_geodata">Select File</span></b>');
	jQuery("#uploadedfile_tbl_geodata").html('');
	jQuery("#sheet").html('');
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-tbl_geodata").css("display","block");
}
function resetDisplaytbl_geodata()
{	
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-tbl_geodata").css("display","block");
}

function uploadExceltbl_geodata(){
	//$("#frmImporttbl_geodata").attr('action',"<?php echo base_url(); ?>upload_excel_tbl_geodata.php");
	//$("#frmImporttbl_geodata").submit();
	
	document.frmImporttbl_geodata.action='<?php echo base_url(); ?>upload_excel_tbl_geodata.php';  // send it to server which will open this contents in excel file
	document.frmImporttbl_geodata.submit();
	iTimeOutltbl_geodata = setTimeout('getStatustbl_geodata();',500);
}

function getStatustbl_geodata()
{
	if($("#uploadedfile_tbl_geodata").text()==""){
		iTimeOutltbl_geodata = setTimeout('getStatustbl_geodata();',500);
	}
	else{
		clearTimeout(iTimeOutltbl_geodata);
		fillDetailstbl_geodata();		
	}
}
function fillDetailstbl_geodata()
{	
	var sUrl = "<?php echo base_url(); ?>import_geodata.php";
	var file = $('#uploadedfile_tbl_geodata').text();
	$("#loading_top").css("display","block");;
	var parameters = "cmd=sheet&file=" + file + "&table=" + strTable ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handlefillDetailstbl_geodata
	});					
}
function handlefillDetailstbl_geodata(msg)
{	
	$("#sheet").html(msg);
	fillFieldstbl_geodata(0);
}
function fillFieldstbl_geodata(sheetvalue){
	var sUrl = "<?php echo base_url(); ?>import_geodata.php";
	var file = $('#uploadedfile_tbl_geodata').text();
	var parameters = "cmd=fields&file=" + file + "&table=" + strTable + "&sheet=" + sheetvalue ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handleFillFieldtbl_geodata
	});
}
function handleFillFieldtbl_geodata(msg)
{
	$("#processingImportDetail").html(msg);
	$("#loading_top").css("display","none");;
}

function importExceltbl_geodata(){
	var file = $('#uploadedfile_tbl_geodata').text();
   
	if(file == ""){
		return false;
	}
	$("#loading_top").css("display","block");;
	$.post( 
        "<?php echo base_url(); ?>import_geodata.php?cmd=insert&table="+strTable+"&file="+file, 
        $("#frmImporttbl_geodata").serialize(), 
        function(data){
			if(data.result == "true"){
				$("#processingDialogtbl_geodata").html(data.msg);
				$("#loading_top").css("display","none");;
				$("#processingDialogtbl_geodata").dialog("open");
				jQuery("#processingImportDetail").html('');
				jQuery("#uploadmsg_tbl_geodata").html('');
				jQuery("#uploadedfile_tbl_geodata").html('');
				jQuery("#sheet").html('');
				jQuery("#filename").val('');							
				resetAllImporttbl_geodata();
				$('#ttbl_geodata_ds_list_div').show();	
				$("#dialog-import-tbl_geodata").hide();
				jQuery("#ttbl_geodata_ds_list").trigger("reloadGrid");
			}else{
				$("#processingDialogtbl_geodata").html(data.error);
				$("#loading_top").css("display","none");;
				$("#processingDialogtbl_geodata").dialog("open");				
			}
        },"json" 
    );	
}

function displayExceltbl_geodata(){
	var file = $('#uploadedfile_tbl_geodata').text();
	
	if(file == ""){
		return false;
	}
	$("#loading_top").css("display","block");;
    $.post("<?php echo base_url(); ?>import_geodata.php?cmd=display&table="+strTable+"&file="+file, 
        $("#frmImporttbl_geodata").serialize(), 
        function(data){
			$("#loading_top").css("display","none");;
			if(oldDisplayContenttbl_geodata == ""){
				oldDisplayContenttbl_geodata = $("#divDisplayProcessing").html();
			}
			var html = data.html + oldDisplayContenttbl_geodata;
			$("#divDisplayProcessing").html(html);
			
			if(data.importRec == 'false'){
				$('#btnImport').hide();
			}
			else{
				$('#btnImport').show();
			}
			$("#divDisplayProcessing").css("display","block");
			$("#dialog-import-tbl_geodata").css("display","none");	
        }, 'json' 
    );
	
}

</script>
<div id="ttbl_geodata_ds_list_div">
	<table id="ttbl_geodata_ds_list" class="jqgrid"></table>
</div>
<div id="tbl_geodata_pager"></div>
<div id="tbl_geodata_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_tbl_geodata<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
<div id="processingDialogtbl_geodata" style="display:none">
</div>
<div id="divDisplayProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExceltbl_geodata()" value="Import"/>&nbsp;&nbsp;&nbsp;<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplaytbl_geodata()"/>
</div>
<div id="dialog-import-tbl_geodata" title="Import" style="overflow:auto; display:none;">
	<p class="importTips"></p>
<form id="frmImporttbl_geodata" name="frmImporttbl_geodata" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>upload_excel_tbl_geodata.php" method="POST" onSubmit="return false">
        <fieldset>
        <table width="100%" class="formtable" id="importtable" name="importtable">
            <tr>
                <td width="50%">
                    <label for="file"><?php echo $this->lang->line("Excel_File"); ?></label>
                    <input type="file" name="filename" id="filename" class="text ui-widget-content ui-corner-all" onChange="uploadExceltbl_geodata()"/>
                    <iframe name="hiddenframe" style="display:none"></iframe><b><span id="uploadmsg_tbl_geodata"><?php //echo $this->lang->line("select_file"); ?></span></b>
					  <span id="uploadedfile_tbl_geodata" style="display:none"></span>
                </td>
                <td>
                    <label for="sheets"><?php echo $this->lang->line("Sheet"); ?></label>
                    <select name="sheet" id="sheet" class="select ui-widget-content ui-corner-all" onChange="fillFieldstbl_geodata(this.value)"></select>
                </td>
            </tr>
        </table>
		<table width="100%" class="formtable" id="processingImportDetail"></table>
        <table width="100%" class="formtable">
        	<tr align="center">
            	<td><input type="button" name="btnDisplay" id="btnDisplay" onClick="displayExceltbl_geodata()" value="Display"/>
                <input type="reset" name="btnClearProcessingtbl_geodata" id="btnClearProcessingtbl_geodata"/>&nbsp;<input type="button" value="Back" name="btnBacktbl_geodata" id="btnBacktbl_geodata"/>
				</td>
            </tr>
        </table>
        </fieldset>
	</form>
</div>
</body>
</html>