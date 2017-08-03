<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Set Radius","Edit","Import");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'68');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	
	$time=time();
	$rNo = strtotime(date("H:i:s"));
	$date_format = $this->session->userdata('date_format');
	$time_format = $this->session->userdata('time_format'); 
	$js_date_format = $this->session->userdata('js_date_format');  
	$js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_landmarks_grid
{
	display:none !important;
}
#landmarks_grid td {           
    word-wrap: break-word; /* IE 5.5+ and CSS3 */
    white-space: pre-wrap; /* CSS3 */
    white-space: -pre-wrap; /* Opera 4-6 */
    white-space: -o-pre-wrap; /* Opera 7 */
    white-space: normal !important;
    height: auto;
    vertical-align: text-top;
    padding-top: 2px;
    padding-bottom: 3px;
}
</style>
<script type="text/javascript">
var vfields= new Array();
var user_conf_dialog_usr_abcd;
jQuery().ready(function (){
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#landmarks_grid").jqGrid({
		url:"<?php echo site_url('landmarks/loadData'); ?>",
		datatype: "json",
		// , '<?php echo $this->lang->line("Address Book"); ?>'
		// , '<?php echo $this->lang->line("Group"); ?>'
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Landmark"); ?>', '<?php echo $this->lang->line("Address"); ?>', '<?php echo $this->lang->line("Landmark Circle"); ?>', '<?php echo $this->lang->line("Assets"); ?>', '<?php echo $this->lang->line("Icon"); ?>', '<?php echo $this->lang->line("Comments"); ?>', '<?php echo $this->lang->line("Sms Alert"); ?>', '<?php echo $this->lang->line("Email Alert"); ?>', '<?php echo $this->lang->line("Alert Before Landmark"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"name",editable:true, index:"name", width:120, align:"center", jsonmap:"name"},
			{name:"address",editable:true, index:"address", width:120, align:"center", jsonmap:"address"},
			{name:"radius",editable:true, index:"radius", width:120, align:"center", jsonmap:"radius"},
			{name:"assets",editable:true, index:"assets", width:500, align:"center", jsonmap:"assets"},
			{name:"icon_path",editable:true, index:"icon_path", width:120, align:"center", jsonmap:"icon_path", formatter: AssetsPathFormatter_landmark},
			// {name:"address_book_nm",editable:true, index:"am.name", width:120, align:"center", jsonmap:"address_book_nm"},
			{name:"comments",editable:true, index:"comments", width:120, align:"center", jsonmap:"comments"},
			// {name:"landmark_group_name",editable:true, index:"landmark_group_name", width:120, align:"center", jsonmap:"landmark_group_name"},
			{name:"sms_alert",editable:true, index:"sms_alert", width:80, align:"center", jsonmap:"sms_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"email_alert",editable:true, index:"email_alert", width:80, align:"center", jsonmap:"email_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"alert_before_landmark",editable:true, index:"alert_before_landmark", width:80, align:"center", jsonmap:"alert_before_landmark"}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#landmarks_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("Landmarks"); ?>",
		editurl:"<?php echo site_url('landmarks/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	 user_conf_dialog_usr_abcd=$("#user_conf_dialog_usr_abcd<?php echo $time; ?>");
		user_conf_dialog_usr_abcd.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					user_conf_dialog_usr_abcd.dialog("close");
					cancel_landmarks();
				},
				No: function () {
					user_conf_dialog_usr_abcd.dialog("close");
				}
			},
		
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
	jQuery("#landmarks_grid").jqGrid("navGrid", "#landmarks_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});

	$("#landmarks_pager option[value=10000]").text('All');
	$("#landmarks_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#landmarks_pager .ui-pg-selbox").val();
	});
	
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#landmarks_grid").jqGrid("navButtonAdd","#landmarks_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#landmarks_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#landmarks_form_div').show();
					$('#landmarks_list_div').hide();
					$('#landmarks_form_div').load($.trim('<?php echo site_url('landmarks/form/id'); ?>/'+gsr[0]));
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	jQuery("#landmarks_grid").jqGrid("navButtonAdd","#landmarks_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var qrystr ="/export";
			
			document.location = "<?php echo base_url(); ?>index.php/landmarks/loadData"+qrystr;
		}
	});
	<?php
	if(in_array('Set Radius',$data)){
	?>
	jQuery("#landmarks_grid").jqGrid("navButtonAdd","#landmarks_pager",{caption:"<?php echo $this->lang->line("Set Radius"); ?>",
		onClickButton:function(){
			
			$('#landmarks_list_div').hide();
			$('#landmarks_radius_form_div').load($.trim('<?php echo site_url('landmarks/radius_form'); ?>'));
			$('#landmarks_radius_form_div').show();
		}
	});
	<?php } ?>
	<?php
	if(in_array('Import',$data)){
	?>
	jQuery("#landmarks_grid").jqGrid('navButtonAdd','#landmarks_pager',{caption:"<?php echo $this->lang->line("Import"); ?>",
		onClickButton:function(){
			$('#landmarks_list_div').hide();	
			$("#dialog-import-landmark").show();
		} 
	});
	<?php } ?>
	$("#jsonDatatest").click(function(){
		$.post("<?php echo site_url('landmarks/get_json_data'); ?>",function(data){
			$("#jsonDatatest").html(data);
		});
	});
	$("#processingLDialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true
	});
	$("#btnClearLProcessing").click(function(){
		jQuery("#processingImportLDetail").html('');
		jQuery("#uploadmsg").html('');
		jQuery("#uploadedfile").html('');
		jQuery("#sheetL").html('');	
	});
	$("#btnBackLandmarks").click(function(){
		jQuery("#processingImportLDetail").html('');
		jQuery("#uploadmsg").html('');
		jQuery("#uploadedfile").html('');
		jQuery("#sheetL").html('');
		$('#landmarks_list_div').show();	
		$("#dialog-import-landmark").hide();
	});
	$("#divLoading<?php echo $rNo; ?>").dialog({
		autoOpen: false,
		draggable: false,
		resizable: false,
		modal: true
	});
	$("#loading_top").css("display","none");
});
function AssetsPathFormatter_landmark(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function submitForm_Landmark(id){
	$("#loading_top").css("display","block");
	var nm=$("#username").val();

	$.post("<?php echo site_url('landmarks/form/id'); ?>/"+id, $("#frm_landmarks").serialize(), 
	function(data){
		if($.trim(data)){
			$('#landmarks_form_div').html(data);
		}else{
			$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
			$("#alert_dialog").dialog('open');
			cancel_landmarks();
			}
			$("#loading_top").css("display","none");
		}
	);
	return false;
}
function submit_landmark_radius(){
	
	if(!$.isNumeric($("#landmakr_frm_radius_list").val())){
		$("#error_frm_landmarks").html("<?php echo $this->lang->line("Radius zero not allowed"); ?>");
		$("#error_frm_landmarks").show();
		return false;
	}
	$("#error_frm_landmarks").html("");
	$("#error_frm_landmarks").hide();
	$("#loading_top").css("display","block");
	var hasValue = $('#landmakr_frm_list :selected').length;
	if(hasValue){
		$.post("<?php echo site_url('landmarks/submit_landmark_radius'); ?>", $("#frm_landmarks_radius").serialize(), 
			function(data){
				if(data.response.result){
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					$("#alert_dialog").dialog('open');
				}else{
					if(data.response.err=="radius"){
						$("#error_frm_landmarks").html("<?php echo $this->lang->line("Radius zero not allowed"); ?>");
						$("#error_frm_landmarks").show();
					}
				}
			},'json');
	}else{
		$("#alert_dialog").html('<?php echo $this->lang->line("Please Select Atleast One Landmar"); ?>');
		$("#alert_dialog").dialog('open');
	}
	return false;
}
function cancel_landmarks(){
	$("#error_frm_landmarks").html("");
	$("#error_frm_landmarks").hide();
	$('#landmarks_list_div').show();
	$('#landmarks_form_div').hide();
	$('#landmarks_radius_form_div').hide();
	jQuery("#landmarks_grid").trigger("reloadGrid");
} 

function edit_in_map_lnd(landmark_id){
	var nameToCheck = "Landmark";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck){
			$('#tabs').tabs('remove', $(this).attr("href"));
		}
	});
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/landmark/landmark_id/"+landmark_id,"Landmark");
}

var oldTblContent="";
var oldDisplayContent="";
function exportNotFound(){
	
	document.forms[0].csvBuffer.value= $("#ReportTable").html();			
	document.forms[0].method='POST';
	document.forms[0].action='<?php echo base_url(); ?>import_landmarks.php';  // send it to server which will open this contents in excel file
	document.forms[0].target='_blank';
	document.forms[0].submit();
}
function resetAllImport(){
	$("#frmImportL")[0].reset();
	jQuery("#processingImportLDetail").html('');
	jQuery("#uploadmsg").html('<b><span id="uploadmsg">Select File</span></b>');
	jQuery("#uploadedfile").html('');
	jQuery("#sheetL").html('');
	$("#divDisplayLProcessing").css("display","none");
	$("#dialog-import-landmark").css("display","block");
}
function resetDisplay()
{	
	$("#divDisplayLProcessing").css("display","none");
	$("#dialog-import-landmark").css("display","block");
}

function uploadExcel(){
	//$("#frmImport").attr('action',"<?php echo base_url(); ?>upload_excel.php");
	//$("#frmImport").submit();
	
	document.frmImportL.action='<?php echo base_url(); ?>upload_excel.php';  // send it to server which will open this contents in excel file
	document.frmImportL.submit();
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
	
	var sUrl = "<?php echo base_url(); ?>import_landmarks.php";
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
	
	$("#sheetL").html(msg);
	fillFields(0);
}
function fillFields(sheetvalue){
	
	var sUrl = "<?php echo base_url(); ?>import_landmarks.php";
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
	$("#processingImportLDetail").html(msg);
	$("#divLoading<?php echo $rNo; ?>").dialog("close");
}

function importExcel(){
	var file = $('#uploadedfile').text();
   
	if(file == ""){
		return false;
	}
	$("#divLoading<?php echo $rNo; ?>").dialog("open");
	$.post( 
        "<?php echo base_url(); ?>import_landmarks.php?cmd=insert&table="+strTable+"&file="+file, 
        $("#frmImportL").serialize(), 
        function(data){
			if(data.result == "true"){
				$("#processingLDialog").html(data.msg);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingLDialog").dialog("open");
				jQuery("#processingImportLDetail").html('');
				jQuery("#uploadmsg").html('');
				jQuery("#uploadedfile").html('');
				jQuery("#sheetL").html('');
				jQuery("#filename").val('');							
				resetAllImport();
				$('#landmarks_list_div').show();	
				$("#dialog-import-landmark").hide();
				jQuery("#landmarks_grid").trigger("reloadGrid");
			}else{
				$("#processingLDialog").html(data.error);
				$("#divLoading<?php echo $rNo; ?>").dialog("close");
				$("#processingLDialog").dialog("open");
				
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
        "<?php echo base_url(); ?>import_landmarks.php?cmd=display&table="+strTable+"&file="+file, 
        $("#frmImportL").serialize(), 
        function(data){
			$("#divLoading<?php echo $rNo; ?>").dialog("close");
			if(oldDisplayContent == ""){
				oldDisplayContent = $("#divDisplayLProcessing").html();
			}
			var html = data.html + oldDisplayContent;
			$("#divDisplayLProcessing").html(html);
			
			if(data.importRec == 'false'){
				$('#btnImport').hide();
			}
			else{
				$('#btnImport').show();
			}
			$("#divDisplayLProcessing").css("display","block");
			$("#dialog-import-landmark").css("display","none");			
        }, 'json' 
    );
	
}
</script>
<div id="landmarks_list_div">
	<table id="landmarks_grid" class="jqgrid"></table>
</div>
<div id="landmarks_pager"></div>

<div id="landmarks_form_div" style="padding:10px;display:none;">
</div>
<div id="landmarks_radius_form_div" style="padding:10px;display:none;">
</div>
<div id="user_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>

<div id="lrandno" style="display:none;"><?php echo $rNo; ?></div>
<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingLDialog" style="display:none">
</div>
<div id="divDisplayLProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExcel()" value="Import"/>
<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplay()"/>
</div>
<div id="dialog-import-landmark" title="Import" style="overflow:auto;display:none">
	<p class="importTips"></p>
<form id="frmImportL" name="frmImportL" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>upload_excel.php" method="POST" onSubmit="return false">
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
                    <select name="sheet" id="sheetL" class="select ui-widget-content ui-corner-all" onChange="fillFields(this.value)"></select>
                </td>
            </tr>
        </table>
		<table width="100%" class="formtable" id="processingImportLDetail"></table>
        <table width="100%" class="formtable">
        	<tr align="center">
            	<td><input type="button" name="btnDisplay" id="btnDisplay" onClick="displayExcel()" value="Display"/>
                <input type="reset" name="btnClearProcessing" id="btnClearLProcessing"/>&nbsp;<input type="button" value="Back" name="btnBackLandmarks" id="btnBackLandmarks"/>
				</td>
            </tr>
        </table>
        </fieldset>
	</form>
</div>
<!-- <div id="jsonDatatest" style="background-color:yellow">&nbsp; click here</div> -->
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