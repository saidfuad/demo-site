<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Edit","Import");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'70');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	
	$rNo = $time = time();
	$date_format = $this->session->userdata('date_format');  
	$time_format = $this->session->userdata('time_format');  
	$js_date_format = $this->session->userdata('js_date_format');  
	$js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_routes_grid
{
	display:none !important; 
}
#routes_grid tr.jqgrow td {
	word-wrap: break-word; /* IE 5.5+ and CSS3 */
	white-space: pre-wrap; /* CSS3 */
	white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
	white-space: -pre-wrap; /* Opera 4-6 */
	white-space: -o-pre-wrap; /* Opera 7 */
	overflow: hidden;
	height: auto;
	vertical-align: middle;
	padding-top: 3px;
	padding-bottom: 3px
}	
</style>
<script type="text/javascript">
var vfields= new Array();
var user_conf_dialog_usr_abcd;
jQuery().ready(function (){

	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#routes_grid").jqGrid({
		url:"<?php echo site_url('routes/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Route Name"); ?>',  '<?php echo $this->lang->line("Landmarks"); ?>', '<?php echo $this->lang->line("Assets"); ?>', '<?php echo $this->lang->line("Color"); ?>', '<?php echo $this->lang->line("Alert_When_Distance"); ?>','<?php echo $this->lang->line("Distance_Total"); ?>','<?php echo $this->lang->line("Time_minutes"); ?>','<?php echo $this->lang->line("Round_trip"); ?>', '<?php echo $this->lang->line("Comments"); ?>','<?php echo $this->lang->line("Sms Alert"); ?>', '<?php echo $this->lang->line("Email Alert"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"routename",editable:true, index:"routename", width:120, align:"center", jsonmap:"routename"},
			{name:"landmark_ids",editable:true, index:"landmark_ids", width:120, align:"center", jsonmap:"landmark_ids"},
			{name:"deviceid",editable:true, index:"deviceid", width:120, align:"center", jsonmap:"deviceid"},
			{name:"route_color",editable:true, index:"route_color", width:100, align:"center", jsonmap:"route_color",formatter:colorFormat_routes},
			{name:"distance_value",editable:true, index:"distance_value", width:150, align:"center", jsonmap:"distance_value"},
			{name:"total_distance",editable:true, index:"total_distance", width:120, align:"center", jsonmap:"total_distance"},
			{name:"total_time_in_minutes",editable:true, index:"total_time_in_minutes", width:120, align:"center", jsonmap:"total_time_in_minutes"},
			{name:"round_trip",editable:true, index:"round_trip", width:80, align:"center", jsonmap:"round_trip",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"comments",editable:true, index:"comments", width:100, align:"center", jsonmap:"comments"},
			{name:"sms_alert",editable:true, index:"sms_alert", width:80, align:"center", jsonmap:"sms_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"email_alert",editable:true, index:"email_alert", width:80, align:"center", jsonmap:"email_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#routes_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("Routes"); ?>",
		editurl:"<?php echo site_url('routes/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	 user_conf_dialog_usr_abcd=$("#user_conf_dialog_usr_abcd<?php echo $time; ?>");
		user_conf_dialog_usr_abcd.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					user_conf_dialog_usr_abcd.dialog("close");
					cancel_routes();
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
	
	?>
	jQuery("#routes_grid").jqGrid("navGrid", "#routes_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:false}, {}, {}, {}, {multipleSearch:false});
	$("#routes_pager option[value=10000]").text('All');
	$("#routes_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#routes_pager .ui-pg-selbox").val();
	});

	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#routes_grid").jqGrid("navButtonAdd","#routes_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#routes_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#routes_form_div').show();
					$('#routes_list_div').hide();
					$('#routes_form_div').load($.trim('<?php echo site_url('routes/form/id'); ?>/'+gsr[0]));
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
	jQuery("#routes_grid").jqGrid('navButtonAdd','#routes_pager',{caption:"<?php echo $this->lang->line("Import"); ?>",
		onClickButton:function(){
			$('#routes_list_div').hide();	
			$("#dialog-import-trip").show();
		} 
	});
	<?php } ?>
	$("#jsonDatatest").click(function(){
		$.post("<?php echo site_url('routes/get_json_data'); ?>",function(data){
			$("#jsonDatatest").html(data);
		});
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
	$("#btnBackTrip").click(function(){
		jQuery("#processingImportDetail").html('');
		jQuery("#uploadmsg").html('');
		jQuery("#uploadedfile").html('');
		jQuery("#sheet").html('');	
		$('#routes_list_div').show();	
		$("#dialog-import-trip").hide();
	});
	$("#divLoading<?php echo $rNo; ?>").dialog({
			autoOpen: false,
			draggable: false,
			resizable: false,
			modal: true
		});
	$("#loading_top").css("display","none");
});
function AssetsPathFormatter_area(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function colorFormat_routes(cellvalue, options, rowObject){

	return "<span style='min-width:12px;min-height:8px;display:inline-block;background-color:"+cellvalue+"'></span>&nbsp;&nbsp;"+cellvalue;
}
function submitForm_area(id){
 	$("#loading_top").css("display","block");
	var nm=$("#username").val();
	

		 $.post("<?php echo site_url('routes/form/id'); ?>/"+id, $("#frm_routes").serialize(), 
			function(data){
				if($.trim(data)){
					$('#routes_form_div').html(data);
				}else{
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					cancel_routes();
					}
					$("#loading_top").css("display","none");
				}
			);

	return false;	
}

function cancel_routes(){
	$('#routes_list_div').show();
	$('#routes_form_div').hide();
	jQuery("#routes_grid").trigger("reloadGrid");
} 

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
	$("#dialog-import-trip").css("display","block");
}
function resetDisplay()
{	
	$("#divDisplayProcessing").css("display","none");
	$("#dialog-import-trip").css("display","block");
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
				$('#routes_list_div').show();	
				$("#dialog-import-trip").hide();
				jQuery("#routes_grid").trigger("reloadGrid");
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
			$("#dialog-import-trip").css("display","none");			
        }, 'json' 
    );
	
}
</script>
<div id="routes_list_div">
	<table id="routes_grid" class="jqgrid"></table>
</div>
<div id="routes_pager"></div>

<div id="routes_form_div" style="padding:10px;display:none;">
</div>
<div id="user_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>

<div id="randno" style="display:none;"><?php echo $rNo; ?></div>
<div id="divLoading<?php echo $rNo; ?>" style="display:none; padding: 40px 70px;"><img src="<?php echo base_url(); ?>assets/images/16.gif"/></div>
<div id="processingDialog" style="display:none">
</div>
<div id="divDisplayProcessing" style="display:none;height:400px;overflow:scroll;text-align:center">
<input type="button" name="btnImport" id="btnImport" onClick="importExcel()" value="Import"/>
<input type="button" name="btnCancel" id="btnCancel" value="Cancel" onClick="resetDisplay()"/>
</div>
<div id="dialog-import-trip" title="Import" style="overflow:auto;display:none">
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
                <input type="reset" name="btnClearProcessing" id="btnClearProcessing"/>&nbsp;<input type="button" value="<?php echo $this->lang->line("Back"); ?>" name="btnBackTrip" id="btnBackTrip"/>
				</td>
            </tr>
        </table>
        </fieldset>
	</form>
</div>
<!-- <div id="jsonDatatest" style="background-color:yellow">&nbsp; click here</div> -->
</body>
</html>