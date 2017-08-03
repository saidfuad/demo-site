<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Add","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'77');
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
#load_driver_master_grid
{
	display:none !important; 
}
.ui-pg-div .ui-icon-search{display:none !important;}
</style>
<script type="text/javascript">
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#driver_master_grid").jqGrid({
		url:"<?php echo site_url('driver_master/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("id"); ?>",'<?php echo $this->lang->line("Driver Name"); ?>','<?php echo $this->lang->line("Driver Code"); ?>','<?php echo $this->lang->line("Address"); ?>','<?php echo $this->lang->line("Mobile_No"); ?>','<?php echo $this->lang->line("email"); ?>','<?php echo $this->lang->line("Send_Mobile_Alerts"); ?>','<?php echo $this->lang->line("Send_Email_Alerts"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"Driver_name",editable:true, width:120, index:"Driver_name", align:"center", jsonmap:"driver_name"},
			{name:"Driver_Code",editable:true, width:120, index:"Driver_Code", align:"center", jsonmap:"driver_code"},
			{name:"address",editable:true, index:"address", width:120, align:"center", jsonmap:"address"},
			{name:"mobile_no",editable:true, index:"mobile_no", width:180, align:"center", jsonmap:"mobile_no"},
			{name:"email",editable:true, index:"email", width:120, align:"center", jsonmap:"email"},
			{name:"sms_alert",editable:true, index:"sms_alert", width:120, align:"center", jsonmap:"sms_alert"},
			{name:"email_alert",editable:true, index:"email_alert", width:120, align:"center", jsonmap:"email_alert"},
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#driver_master_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true,
		loadComplete: function(){
			//$("#loading_dialog").dialog("close");
		},	
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Driver Master"); ?>",
		editurl:"<?php echo site_url('driver_master/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
<?php
	if(in_array('Delete',$data))
		$delete = "true";
	else
		$delete = "false";
	/*if(in_array('Search'))
		$Search = "true";
	else
		$Search = "false";	*/
	?>
	jQuery("#driver_master_grid").jqGrid("navGrid", "#driver_master_pager", {add:false, edit:false, del:<?php echo $delete; ?>}, {}, {}, {}, {multipleSearch:false});

	$("#driver_master_pager option[value=10000]").text('All');
	$("#driver_master_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#driver_master_pager .ui-pg-selbox").val();
	});
	
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#driver_master_grid").jqGrid("navButtonAdd","#driver_master_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			$("#loading_top").css("display","block");
			$('#driver_master_list_div').hide();
			$('#driver_master_form_div').show();
			$('#driver_master_form_div').load('<?php echo site_url('/driver_master/form/'); ?>');
			$("#loading_top").css("display","none");
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#driver_master_grid").jqGrid("navButtonAdd","#driver_master_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#driver_master_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					
					
					$("#loading_top").css("display","block");
			
					$('#driver_master_form_div').show();
					$('#driver_master_list_div').hide();
					var gsrval = jQuery("#driver_master_grid").jqGrid('getCell', gsr[0], 'id');
					$('#driver_master_form_div').load('<?php echo site_url('driver_master/form/id'); ?>/'+gsrval);
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
	jQuery("#driver_master_grid").jqGrid('navButtonAdd','#driver_master_pager',{caption:"Import",
		onClickButton:function(){
			$('#driver_master_list_div').hide();	
			$("#dialog-import-driver").show();
		} 
	});
	<?php } ?>
	conf_dialog_driver_master_lokkup=$("#conf_dialog_driver_master<?php $time=time(); ?>");
	conf_dialog_driver_master_lokkup.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_driver_master_lokkup.dialog("close");
				cancel_driver_master(); 
			},
			No: function () {
				conf_dialog_landmark_group_lokkup.dialog("close");
			}
		},
	

	});
	$("#processingDialogDriver").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true
	});
	$("#btnClearProcessingDriver").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg_driver").html('');
		jQuery("#uploadedfile_driver").html('');
		jQuery("#sheet").html('');	
	});
	$("#btnBackDriver").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg_driver").html('');
		jQuery("#uploadedfile_driver").html('');
		jQuery("#sheet").html('');	
		$('#driver_master_list_div').show();	
		$("#dialog-import-driver").hide();
	});
	$("#divLoading<?php echo $rNo; ?>").dialog({
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true
		});
cancelloading();
});
function mobile_number_valid()
{
if($("#mobile_number_add").val() != ""){
	var emails=$("#mobile_number_add").val();
	/*	var em=emails.split(/[;,]+/);
	for(i=0;i<em.length;i++)
	{
		if(em[i].length == 10)
		{
			$("#error_frm_address").hide();
			return true;
		}else{
			$("#error_frm_address").show();
			$("#error_frm_address").html("<?php echo $this->lang->line("Mobile_Number_Formate_is_Not_Valid"); ?>");
			return false;
		}
	}*/
}
return true;
}
function check_driver_master(id)
{
	var a_t_nm=$.trim($("#group_name_add").val());
	var a_t_id=id;
//	alert(a_t_nm);
	$.post("<?php echo site_url('driver_master/chk_nm/nm'); ?>/"+a_t_nm+"/id/"+a_t_id,function(data)
	{
//		alert(data);
		if(data=="false")
		{
			asets_t_exist=false;
			$("#driver_master_error").show();
		}
		else
		{
			asets_t_exist=true;
			$("#driver_master_error").hide();
		}
	});
}
/*function submitFormdriver_master(id){
	
	if(mobile_number_valid())
	{
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('driver_master/form/id'); ?>/"+id, $("#frm_driver_master").serialize(), 
			function(data){
				if(data){
					$('#driver_master_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#driver_master_list_div').show();
					$('#driver_master_form_div').hide();
					jQuery("#driver_master_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
	}
	
	return false;	
}*/
function submitFormdriver_master(id){
	//check_driver_master(id);
	/*if(asets_t_exist==true)
	{*/
        var nm=$("#driver_code").val();
       
	if(mobile_number_valid())
	{
	$("#loading_top").css("display","block");
       
        $.post("<?php echo base_url(); ?>index.php/driver_master/check_duplicates/driver_code/"+nm+"/id/"+id,function(data){
             if(data=="")
        {
	$.post("<?php echo site_url('driver_master/form/id'); ?>/"+id, $("#frm_driver_master").serialize(), 
			function(data){
				if(data){
					$('#driver_master_form_div').html(data);
				}else{
					if(id != "")
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#driver_master_list_div').show();
					$('#driver_master_form_div').hide();
					jQuery("#driver_master_grid").trigger("reloadGrid");
				}
				$("#loading_top").css("display","none");
			} 
		);
            }
        else
		{
			$("#error_frm_address").html(data);
			$("#error_frm_address").show();	
			$("#loading_top").css("display","none");
			return false;
		}
	});
        
        }
	/*}*/
	return false;	
}
function cancel_driver_master(){
	//$("#loading_dialog").dialog("open");
	$('#driver_master_list_div').show();
	$('#driver_master_form_div').hide();
	jQuery("#driver_master_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}

var oldTblContent="";
var oldDisplayContentDriver="";
function exportNotFoundDriver(){
	
	document.forms[0].csvBuffer.value= $("#ReportTable").html();			
	document.forms[0].method='POST';
	document.forms[0].action='<?php echo base_url(); ?>import_driver.php';  // send it to server which will open this contents in excel file
	document.forms[0].target='_blank';
	document.forms[0].submit();
}
function resetAllImportDriver(){
	$("#frmImportDriver")[0].reset();
	jQuery("#processingImportDetail").html('');
	jQuery("#uploadmsg_driver").html('<b><span id="uploadmsg_driver">Select File</span></b>');
	jQuery("#uploadedfile_driver").html('');
	jQuery("#sheet").html('');
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-driver").css("display","block");
}
function resetDisplayDriver()
{	
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-driver").css("display","block");
}

function uploadExcelDriver(){
	//$("#frmImportDriver").attr('action',"<?php echo base_url(); ?>upload_excel_driver.php");
	//$("#frmImportDriver").submit();
	
	document.frmImportDriver.action='<?php echo base_url(); ?>upload_excel_driver.php';  // send it to server which will open this contents in excel file
	document.frmImportDriver.submit();
	iTimeOutlDriver = setTimeout('getStatusDriver();',500);
}

function getStatusDriver()
{
	if($("#uploadedfile_driver").text()==""){
		iTimeOutlDriver = setTimeout('getStatusDriver();',500);
	}
	else{
		clearTimeout(iTimeOutlDriver);
		fillDetailsDriver();		
	}
}
function fillDetailsDriver()
{
	
	var sUrl = "<?php echo base_url(); ?>import_driver.php";
	var file = $('#uploadedfile_driver').text();
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	var parameters = "cmd=sheet&file=" + file + "&table=" + strTable ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handlefillDetailsDriver
	});
					
}
function handlefillDetailsDriver(msg)
{
	
	$("#sheet").html(msg);
	fillFieldsDriver(0);
}
function fillFieldsDriver(sheetvalue){
	
	var sUrl = "<?php echo base_url(); ?>import_driver.php";
	var file = $('#uploadedfile_driver').text();
	var parameters = "cmd=fields&file=" + file + "&table=" + strTable + "&sheet=" + sheetvalue ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handleFillFieldDriver
	});
}
function handleFillFieldDriver(msg)
{
	$("#processingImportDetail").html(msg);
	$("#divLoading<?php echo $rNo; ?>").dialog("close");
}

function importExcelDriver(){
	var file = $('#uploadedfile_driver').text();
   
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	$.post( 
        "<?php echo base_url(); ?>import_driver.php?cmd=insert&table="+strTable+"&file="+file, 
        $("#frmImportDriver").serialize(), 
        function(data){
			if(data.result == "true"){
				$("#processingDialogDriver").html(data.msg);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingDialogDriver").dialog("open");
				jQuery("#processingImportDetail").html('');
				jQuery("#uploadmsg_driver").html('');
				jQuery("#uploadedfile_driver").html('');
				jQuery("#sheet").html('');
				jQuery("#filename").val('');							
				resetAllImportDriver();
				$('#driver_master_list_div').show();	
				$("#dialog-import-driver").hide();
				jQuery("#driver_master_grid").trigger("reloadGrid");
			}else{
				$("#processingDialogDriver").html(data.error);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingDialogDriver").dialog("open");
				
			}
        },"json" 
    );
	
}

function displayExcelDriver(){
	var file = $('#uploadedfile_driver').text();
	
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
    $.post( 
        "<?php echo base_url(); ?>import_driver.php?cmd=display&table="+strTable+"&file="+file, 
        $("#frmImportDriver").serialize(), 
        function(data){
			$("#divLoading<?php echo $rNo; ?>").dialog("close");
			if(oldDisplayContentDriver == ""){
				oldDisplayContentDriver = $("#divDisplayProcessing").html();
			}
			var html = data.html + oldDisplayContentDriver;
			$("#divDisplayProcessing").html(html);
			
			if(data.importRec == 'false'){
				$('#btnImport').hide();
			}
			else{
				$('#btnImport').show();
			}
			$("#divDisplayProcessing").css("display","block");
			$("#dialog-import-driver").css("display","none");			
        }, 'json' 
    );
	
}
</script>
<div id="driver_master_list_div">
	<table id="driver_master_grid" class="jqgrid"></table>
</div>
<div id="driver_master_pager"></div>
<div id="driver_master_form_div" style="padding:10px;display:none;height:450px;">

<div id="conf_dialog_driver_master<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
</div>
<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialogDriver" style="display:none">
</div>
<div id="divDisplayProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExcelDriver()" value="Import"/>
<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplayDriver()"/>
</div>
<div id="dialog-import-driver" title="Import" style="overflow:auto; display:none;">
	<p class="importTips"></p>
<form id="frmImportDriver" name="frmImportDriver" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>upload_excel_driver.php" method="POST" onSubmit="return false">
        <fieldset>
        <table width="100%" class="formtable" id="importtable" name="importtable">
            <tr>
                <td width="50%">
                    <label for="file"><?php echo $this->lang->line("Excel_File"); ?></label>
                    <input type="file" name="filename" id="filename" class="text ui-widget-content ui-corner-all" onChange="uploadExcelDriver()"/>
                    <iframe name="hiddenframe" style="display:none"></iframe><b><span id="uploadmsg_driver"><?php echo $this->lang->line("select_file"); ?></span></b>
					  <span id="uploadedfile_driver" style="display:none"></span>
                </td>
                <td>
                    <label for="sheets"><?php echo $this->lang->line("Sheet"); ?></label>
                    <select name="sheet" id="sheet" class="select ui-widget-content ui-corner-all" onChange="fillFieldsDriver(this.value)"></select>
                </td>
            </tr>
        </table>
		<table width="100%" class="formtable" id="processingImportDetail"></table>
        <table width="100%" class="formtable">
        	<tr align="center">
            	<td><input type="button" name="btnDisplay" id="btnDisplay" onClick="displayExcelDriver()" value="Display"/>
                <input type="reset" name="btnClearProcessingDriver" id="btnClearProcessingDriver"/>&nbsp;<input type="button" value="Back" name="btnBackDriver" id="btnBackDriver"/>
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