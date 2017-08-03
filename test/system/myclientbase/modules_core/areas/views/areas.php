<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'69');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}


?>
<?php $time=time(); ?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');    
?>
<style>
#load_areas_grid
{
	display:none !important; 
}
#areas_grid td {           
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
	jQuery("#areas_grid").jqGrid({
		url:"<?php echo site_url('areas/loadData'); ?>",
		datatype: "json",
		// , '<?php echo $this->lang->line("Address Book"); ?>', '<?php echo $this->lang->line("Area Type"); ?>'
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("ID"); ?>','<?php echo $this->lang->line("Area_Name"); ?>', '<?php echo $this->lang->line("Assets"); ?>', '<?php echo $this->lang->line("Area_Color"); ?>', '<?php echo $this->lang->line("In Alert"); ?>', '<?php echo $this->lang->line("Out Alert"); ?>','<?php echo $this->lang->line("Sms Alert"); ?>', '<?php echo $this->lang->line("Email Alert"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"polyid",index:"polyid",hidden:true, width:15, jsonmap:"polyid"},
			{name:"polyname",editable:true, index:"polyname", width:120, align:"center", jsonmap:"polyname"},
			{name:"deviceid",editable:true, index:"deviceid", width:600, align:"center", jsonmap:"deviceid"},
			{name:"color",editable:true, index:"color", width:100, align:"center", jsonmap:"color",formatter:colorFormat_area},
			// {name:"address_book_nm",editable:true, index:"am.name", width:120, align:"center", jsonmap:"address_book_nm"},
			// {name:"area_type_opt",editable:true, index:"am.area_type_opt", width:80, align:"center", jsonmap:"area_type_opt"},
			{name:"in_alert",editable:true, index:"in_alert", width:80, align:"center", jsonmap:"in_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"out_alert",editable:true, index:"out_alert", width:80, align:"center", jsonmap:"out_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"sms_alert",editable:true, index:"sms_alert", width:80, align:"center", jsonmap:"sms_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"email_alert",editable:true, index:"email_alert", width:80, align:"center", jsonmap:"email_alert",formatter:'select', editoptions:{value:"1:Yes;0:No"}}
		],
		rowNum:grid_paging,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100,10000],
		pager: jQuery("#areas_pager"),
		sortname: "id",
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		caption:"<?php echo $this->lang->line("Areas"); ?>",
		editurl:"<?php echo site_url('areas/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	 user_conf_dialog_usr_abcd=$("#user_conf_dialog_usr_abcd<?php echo $time; ?>");
		user_conf_dialog_usr_abcd.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					user_conf_dialog_usr_abcd.dialog("close");
					cancel_areas();
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
	jQuery("#areas_grid").jqGrid("navGrid", "#areas_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:false}, {}, {}, {}, {multipleSearch:false});

	$("#areas_pager option[value=10000]").text('All');
	$("#areas_pager .ui-pg-selbox").change(function(){
		grid_paging=$("#areas_pager .ui-pg-selbox").val();
	});
	
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#areas_grid").jqGrid("navButtonAdd","#areas_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			
			var gsr = jQuery("#areas_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
				$("#loading_top").css("display","block");
					//$("#loading_dialog").dialog("open");
					$('#areas_form_div').show();
					$('#areas_list_div').hide();
					$('#areas_form_div').load($.trim('<?php echo site_url('areas/form/id'); ?>/'+gsr[0]));
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
<?php } ?>
	jQuery("#areas_grid").jqGrid("navButtonAdd","#areas_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var qrystr ="/export";
			
			document.location = "<?php echo base_url(); ?>index.php/areas/loadData"+qrystr;
		}
	});
	$("#jsonDatatest").click(function(){
		$.post("<?php echo site_url('areas/get_json_data'); ?>",function(data){
			$("#jsonDatatest").html(data);
		});
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
function colorFormat_area(cellvalue, options, rowObject){

	return "<span style='min-width:12px;min-height:8px;display:inline-block;background-color:"+cellvalue+"'></span>&nbsp;&nbsp;"+cellvalue;
}
function submitForm_area(id){
 	$("#loading_top").css("display","block");
	var nm=$("#username").val();
	

		 $.post("<?php echo site_url('areas/form/id'); ?>/"+id, $("#frm_areas").serialize(), 
			function(data){
				if($.trim(data)){
					$('#areas_form_div').html(data);
				}else{
					$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					cancel_areas();
					}
					$("#loading_top").css("display","none");
				}
			);

	return false;	
}

function cancel_areas(){
	$('#areas_list_div').show();
	$('#areas_form_div').hide();
	jQuery("#areas_grid").trigger("reloadGrid");
} 
function edit_in_map(area_id){
	var nameToCheck = "Area";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			$('#tabs').tabs('remove', $(this).attr("href"));
		}
	});
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/geofence/area_id/"+area_id,"Area");
} 
</script>
<div id="areas_list_div">
	<table id="areas_grid" class="jqgrid"></table>
</div>
<div id="areas_pager"></div>

<div id="areas_form_div" style="padding:10px;display:none;">
</div>
<div id="user_conf_dialog_usr_abcd<?php echo $time; ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
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