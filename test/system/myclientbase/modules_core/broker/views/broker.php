<?php $time=time(); ?>
<?php $rNo = strtotime(date("H:i:s")); ?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_broker_grid
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
	jQuery("#broker_grid").jqGrid({
		url:"<?php echo site_url('broker/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("first_name"); ?>','<?php echo $this->lang->line("last_name"); ?>', '<?php echo $this->lang->line("Address"); ?>', '<?php echo $this->lang->line("City"); ?>', '<?php echo $this->lang->line("State"); ?>', '<?php echo $this->lang->line("Country"); ?>', '<?php echo $this->lang->line("Zip"); ?>', '<?php echo $this->lang->line("Phone No"); ?>', '<?php echo $this->lang->line("Fax_No"); ?>', '<?php echo $this->lang->line("Mobile_No"); ?>', '<?php echo $this->lang->line("Email"); ?>', '<?php echo $this->lang->line("company_name"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"first_name",editable:true, index:"first_name", width:80, align:"center", jsonmap:"first_name"},
			{name:"last_name",editable:true, index:"last_name", width:120, align:"center", jsonmap:"last_name"},
			{name:"address",editable:true, index:"address", width:120, align:"center", jsonmap:"address"},
			{name:"city",editable:true, index:"us.city", width:120, align:"center", jsonmap:"city"},
			{name:"state",editable:true, index:"us.state", width:120, align:"center", jsonmap:"state"},
			{name:"country",editable:true, index:"us.country", width:120, align:"center", jsonmap:"country"},
			{name:"zip",editable:true, index:"zip", width:120, align:"center", jsonmap:"zip"},
			{name:"phone_number",editable:true, index:"phone_number", width:120, align:"center", jsonmap:"phone_number"},
			{name:"fax_number",editable:true, index:"fax_number", width:120, align:"center", jsonmap:"fax_number"},
			{name:"mobile_number",editable:true, index:"mobile_number", width:120, align:"center", jsonmap:"mobile_number"},
			{name:"email_address",editable:true, index:"email_address", width:120, align:"center", jsonmap:"email_address"},
			{name:"company_name",editable:true, index:"company_name", width:120, align:"center", jsonmap:"company_name"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#user_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("User List"); ?>",
		editurl:"<?php echo site_url('broker/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	 user_conf_dialog_usr_abcd=$("#user_conf_dialog_usr_abcd<?php echo $time; ?>");
		user_conf_dialog_usr_abcd.dialog({
			modal: true, title: '<?php echo $this->lang->line("Conform message"); ?>', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					user_conf_dialog_usr_abcd.dialog("close");
					cancel_users();
				},
				No: function () {
					user_conf_dialog_usr_abcd.dialog("close");
				}
			},
		
		});
	jQuery("#broker_grid").jqGrid("navGrid", "#user_pager", {add:false, edit:false, del:true, search:true}, {}, {}, {}, {multipleSearch:false});

	jQuery("#broker_grid").jqGrid("navButtonAdd","#user_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
		$("#loading_top").css("display","block");
			//$("#loading_dialog").dialog("open");
			$('#users_list_div').hide();
			$('#users_form_div').show();
			$('#users_form_div').load($.trim('<?php echo site_url('/broker/form/'); ?>'));
			
		}
	});

	jQuery("#broker_grid").jqGrid("navButtonAdd","#user_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#broker_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#users_form_div').show();
					$('#users_list_div').hide();
					$('#users_form_div').load($.trim('<?php echo site_url('broker/form/id'); ?>/'+gsr[0]));
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php
	//if(in_array('Export',$data)){
	?>
	jQuery("#broker_grid").jqGrid("navButtonAdd","#user_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var qrystr ="/export";
			
			document.location = "<?php echo base_url(); ?>index.php/broker/loadData"+qrystr;
		}
	});
	<?php //} ?>
	jQuery("#broker_grid").jqGrid('navButtonAdd','#user_pager',{caption:"<?php echo $this->lang->line("Import"); ?>",
		onClickButton:function(){
			$('#users_list_div').hide();	
			$("#dialog-import-users").show();
		} 
	});
	$("#jsonDatatest").click(function(){
		$.post("<?php echo site_url('broker/get_json_data'); ?>",function(data){
			$("#jsonDatatest").html(data);
		});
	});
	$("#processingDialogUsers").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true
	});
	$("#btnClearProcessingUsers").click(function(){
		jQuery("#processingImportDetailUsers").html('');
		jQuery("#uploadmsg_users").html('');
		jQuery("#uploadedfile_users").html('');
		jQuery("#sheet").html('');	
	});
	$("#btnBackUsers").click(function(){
		jQuery("#processingImportDetailUsers").html('');
		jQuery("#uploadmsg_users").html('');
		jQuery("#uploadedfile_users").html('');
		jQuery("#sheet").html('');	
		$('#users_list_div').show();	
		$("#dialog-import-users").hide();
	});
	$("#divLoading<?php echo $rNo; ?>").dialog({
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true
		});
	$("#loading_top").css("display","none");
});

function submitFormUsers(id){
 	$("#loading_top").css("display","block");
	var nm=$("#username").val();
	
	
	 $.post("<?php echo site_url('broker/form/id'); ?>/"+id, $("#frm_broker").serialize(), 
		function(data){
			if($.trim(data)){
				$('#users_form_div').html(data);
			}else{
				if(id != "")
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
				else
				$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
				$("#alert_dialog").dialog('open');
				jQuery("#broker_grid").trigger("reloadGrid");
				$.post("<?php echo base_url(); ?>index.php/broker/getDashCombo",function(dt_data){
				if($.trim(dt_data)!="")
				{
					$("#optdetail").html(dt_data);
				}
				});
				cancel_users();
				}
				$("#loading_top").css("display","none");
			}
		);
	
	return false;	
}

function cancel_users(){
	//$("#loading_dialog").dialog("open");
	$('#users_list_div').show();
	$('#users_form_div').hide();
	jQuery("#broker_grid").trigger("reloadGrid");
}
//import
var oldTblContent="";
var oldDisplayContentUsers="";
function exportNotFoundUsers(){	
	document.forms[0].csvBuffer.value= $("#ReportTable").html();			
	document.forms[0].method='POST';
	document.forms[0].action='<?php echo base_url(); ?>import_users.php';  // send it to server which will open this contents in excel file
	document.forms[0].target='_blank';
	document.forms[0].submit();
}
function resetAllImportUsers(){
	$("#frmImportUsers")[0].reset();
	jQuery("#processingImportDetailUsers").html('');
	jQuery("#uploadmsg_users").html('<b><span id="uploadmsg_users">Select File</span></b>');
	jQuery("#uploadedfile_users").html('');
	jQuery("#sheet").html('');
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-users").css("display","block");
}
function resetDisplayUsers()
{	
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-users").css("display","block");
}

function uploadExcelUsers(){
	//$("#frmImportUsers").attr('action',"<?php echo base_url(); ?>upload_excel_users.php");
	//$("#frmImportUsers").submit();
	
	document.frmImportUsers.action='<?php echo base_url(); ?>upload_excel_users.php';  // send it to server which will open this contents in excel file
	document.frmImportUsers.submit();
	iTimeOutlUsers = setTimeout('getStatusUsers();',500);
}

function getStatusUsers()
{
	if($("#uploadedfile_users").text()==""){
		iTimeOutlUsers = setTimeout('getStatusUsers();',500);
	}
	else{
		clearTimeout(iTimeOutlUsers);
		fillDetailsUsers();		
	}
}
function fillDetailsUsers()
{
	
	var sUrl = "<?php echo base_url(); ?>import_users.php";
	var file = $('#uploadedfile_users').text();
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	var parameters = "cmd=sheet&file=" + file + "&table=" + strTable ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handlefillDetailsUsers
	});
					
}
function handlefillDetailsUsers(msg)
{
	
	$("#sheet").html(msg);
	fillFieldsUsers(0);
}
function fillFieldsUsers(sheetvalue){
	
	var sUrl = "<?php echo base_url(); ?>import_users.php";
	var file = $('#uploadedfile_users').text();
	var parameters = "cmd=fields&file=" + file + "&table=" + strTable + "&sheet=" + sheetvalue ;
	$.ajax({
		type: "POST",
		url: sUrl,
		data: parameters,
		success: handleFillFieldUsers
	});
}
function handleFillFieldUsers(msg)
{
	$("#processingImportDetailUsers").html(msg);
	$("#divLoading<?php echo $rNo; ?>").dialog("close");
}

function importExcelUsers(){
	var file = $('#uploadedfile_users').text();
   
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	$.post( 
        "<?php echo base_url(); ?>import_users.php?cmd=insert&table="+strTable+"&file="+file, 
        $("#frmImportUsers").serialize(), 
        function(data){
			if(data.result == "true"){
				$("#processingDialogUsers").html(data.msg);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingDialogUsers").dialog("open");
				jQuery("#processingImportDetailUsers").html('');
				jQuery("#uploadmsg_users").html('');
				jQuery("#uploadedfile_users").html('');
				jQuery("#sheet").html('');
				jQuery("#filename").val('');							
				resetAllImportUsers();
				$('#users_list_div').show();	
				$("#dialog-import-users").hide();
				jQuery("#broker_grid").trigger("reloadGrid");
			}else{
				$("#processingDialogUsers").html(data.error);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingDialogUsers").dialog("open");
				
			}
        },"json" 
    );
	
}

function displayExcelUsers(){
	var file = $('#uploadedfile_users').text();
	
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
    $.post( 
        "<?php echo base_url(); ?>import_users.php?cmd=display&table="+strTable+"&file="+file, 
        $("#frmImportUsers").serialize(), 
        function(data){
			$("#divLoading<?php echo $rNo; ?>").dialog("close");
			if(oldDisplayContentUsers == ""){
				oldDisplayContentUsers = $("#divDisplayProcessing").html();
			}
			var html = data.html + oldDisplayContentUsers;
			$("#divDisplayProcessing").html(html);
			
			if(data.importRec == 'false'){
				$('#btnImport').hide();
			}
			else{
				$('#btnImport').show();
			}
			$("#divDisplayProcessing").css("display","block");
			$("#dialog-import-users").css("display","none");			
        }, 'json' 
    );
	
}
</script>
<div id="users_list_div">
	<table id="broker_grid" class="jqgrid"></table>
</div>
<div id="user_pager"></div>

<div id="users_form_div" style="padding:10px;display:none;">
</div>
<div id="user_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
<!-- <div id="jsonDatatest" style="background-color:yellow">&nbsp; click here</div> -->


<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialogUsers" style="display:none">
</div>
<div id="divDisplayProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExcelUsers()" value="Import"/>
<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplayUsers()"/>
</div>
<div id="dialog-import-users" title="Import" style="overflow:auto; display:none;">
	<p class="importTips"></p>
<form id="frmImportUsers" name="frmImportUsers" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>upload_excel_users.php" method="POST" onSubmit="return false">
        <fieldset>
        <table width="100%" class="formtable" id="importtable" name="importtable">
            <tr>
                <td width="50%">
                    <label for="file"><?php echo $this->lang->line("Excel_File"); ?></label>
                    <input type="file" name="filename" id="filename" class="text ui-widget-content ui-corner-all" onChange="uploadExcelUsers()"/>
                    <iframe name="hiddenframe" style="display:none"></iframe><b><span id="uploadmsg_users"><?php echo $this->lang->line("select_file"); ?></span></b>
					  <span id="uploadedfile_users" style="display:none"></span>
                </td>
                <td>
                    <label for="sheets"><?php echo $this->lang->line("Sheet"); ?></label>
                    <select name="sheet" id="sheet" class="select ui-widget-content ui-corner-all" onChange="fillFieldsUsers(this.value)"></select>
                </td>
            </tr>
        </table>
		<table width="100%" class="formtable" id="processingImportDetailUsers"></table>
        <table width="100%" class="formtable">
        	<tr align="center">
            	<td><input type="button" name="btnDisplay" id="btnDisplay" onClick="displayExcelUsers()" value="Display"/>
                <input type="reset" name="btnClearProcessingUsers" id="btnClearProcessingUsers"/>&nbsp;<input type="button" value="Back" name="btnBackUsers" id="btnBackUsers"/>
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