<?php 
	$rNo = strtotime(date("H:i:s"));
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
<script type="text/javascript">
var mydata = Array();
jQuery().ready(function (){
jQuery("input:button, input:submit, input:reset").button();
jQuery('.ui-widget-content').Upper();
jQuery(".date").datepicker({dateFormat:'dd.mm.yy',changeMonth: true,changeYear: true,timeFormat: 'hh:mm TT', ampm: true});

});

$("#processingDialog").dialog({
	autoOpen: false,
	height: 'auto',
	width: 'auto',
	draggable: false,
	resizable: false,
	modal: true
});
$("#btnClearProcessing").click(function(){
	jQuery("#processingImportDetail").html('');
	jQuery("#uploadmsg").html('');
	jQuery("#uploadedfile").html('');
	jQuery("#sheet").html('');	
});
$("#divLoading<?php echo $rNo; ?>").dialog({
		autoOpen: false,
		draggable: false,
		resizable: false,
		modal: true
	});

var oldTblContent="";
var oldDisplayContent="";
function exportNotFound(){
	
	document.forms[0].csvBuffer.value= $("#ReportTable").html();			
	document.forms[0].method='POST';
	document.forms[0].action='<?php echo base_url(); ?>import.php';  // send it to server which will open this contents in excel file
	document.forms[0].target='_blank';
	document.forms[0].submit();
}
function resetAllImport(){
	$("#frmImport")[0].reset();
	jQuery("#processingImportDetail").html('');
	jQuery("#uploadmsg").html('<b><span id="uploadmsg">Select File</span></b>');
	jQuery("#uploadedfile").html('');
	jQuery("#sheet").html('');
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import").css("display","block");
}
function resetDisplay()
{	
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import").css("display","block");
}

function uploadExcel(){
	//$("#frmImport").attr('action',"<?php echo base_url(); ?>upload_excel.php");
	//$("#frmImport").submit();
	
	document.frmImport.action='<?php echo base_url(); ?>upload_excel.php';  // send it to server which will open this contents in excel file
	document.frmImport.submit();
	iTimeOut = setTimeout('getStatus();',500);
}

function getStatus()
{
	if($("#uploadedfile").text()==""){
		iTimeOut = setTimeout('getStatus();',500);
	}
	else{
		clearTimeout(iTimeOut);
		fillDetails();		
	}
}
function fillDetails()
{
	
	var sUrl = "<?php echo base_url(); ?>import.php";
	var file = $('#uploadedfile').text();
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	var parameters = "cmd=sheet&file=" + file + "&table=" + strTable ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handleFillDetails
	});
					
}
function handleFillDetails(msg)
{
	
	$("#sheet").html(msg);
	fillFields(0);
}
function fillFields(sheetvalue){
	
	var sUrl = "<?php echo base_url(); ?>import.php";
	var file = $('#uploadedfile').text();
	var parameters = "cmd=fields&file=" + file + "&table=" + strTable + "&sheet=" + sheetvalue ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handleFillField
	});
}
function handleFillField(msg)
{
	$("#processingImportDetail").html(msg);
	$("#divLoading<?php echo $rNo; ?>").dialog("close");
}

function importExcel(){
	var file = $('#uploadedfile').text();
   
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	$.post( 
        "<?php echo base_url(); ?>import.php?cmd=insert&table="+strTable+"&file="+file, 
        $("#frmImport").serialize(), 
        function(data){
			if(data.result == "true"){
				$("#processingDialog").html(data.msg);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingDialog").dialog("open");
				jQuery("#processingImportDetail").html('');
				jQuery("#uploadmsg").html('');
				jQuery("#uploadedfile").html('');
				jQuery("#sheet").html('');
				jQuery("#filename").val('');							
				resetAllImport();
			}else{
				$("#processingDialog").html(data.error);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingDialog").dialog("open");
				
			}
        },"json" 
    );
	
}

function displayExcel(){
	var file = $('#uploadedfile').text();
	
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
    $.post( 
        "<?php echo base_url(); ?>import.php?cmd=display&table="+strTable+"&file="+file, 
        $("#frmImport").serialize(), 
        function(data){
			$("#divLoading<?php echo $rNo; ?>").dialog("close");
			if(oldDisplayContent == ""){
				oldDisplayContent = $("#divDisplayProcessing").html();
			}
			var html = data.html + oldDisplayContent;
			$("#divDisplayProcessing").html(html);
			
			if(data.importRec == 'false'){
				$('#btnImport').hide();
			}
			else{
				$('#btnImport').show();
			}
			$("#divDisplayProcessing").css("display","block");
			$("#dialog-import").css("display","none");			
        }, 'json' 
    );
	
}

$("#loading_top").hide();
</script>
<div id="randno" style="display:none;"><?php echo $rNo; ?></div>
<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialog" style="display:none">
</div>
<div id="divDisplayProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExcel()" value="Import"/>
<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplay()"/>
</div>
<div id="dialog-import" title="Import" style="overflow:auto;">
	<p class="importTips"></p>
<form id="frmImport" name="frmImport" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>upload_excel.php" method="POST" onSubmit="return false">
        <fieldset>
        <table width="100%" class="formtable" id="importtable" name="importtable">
            <tr>
                <td width="50%">
                    <label for="file"><?php echo $this->lang->line("Excel_File"); ?></label>
                    <input type="file" name="filename" id="filename" class="text ui-widget-content ui-corner-all" onChange="uploadExcel()"/>
                    <iframe name="hiddenframe" style="display:none"></iframe><b><span id="uploadmsg"><?php echo $this->lang->line("select_file"); ?></span></b>
					  <span id="uploadedfile" style="display:none"></span>
                </td>
                <td>
                    <label for="sheets"><?php echo $this->lang->line("Sheet"); ?></label>
                    <select name="sheet" id="sheet" class="select ui-widget-content ui-corner-all" onChange="fillFields(this.value)"></select>
                </td>
            </tr>
        </table>
		<table width="100%" class="formtable" id="processingImportDetail"></table>
        <table width="100%" class="formtable">
        	<tr align="center">
            	<td><input type="button" name="btnDisplay" id="btnDisplay" onClick="displayExcel()" value="Display"/>
                <input type="reset" name="btnClearProcessing" id="btnClearProcessing"/>
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