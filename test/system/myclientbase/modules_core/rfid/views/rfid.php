<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Add","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'75');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	

?>
<?php $time=time(); ?>
<style>
#load_rfid_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var con_asse_dis;
jQuery().ready(function (){
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#rfid_grid").jqGrid({
		url:"<?php echo site_url('rfid/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line('RF_ID'); ?>','<?php echo $this->lang->line('Person'); ?>','<?php echo $this->lang->line('Assets'); ?>', '<?php echo $this->lang->line('Landmark'); ?>', '<?php echo $this->lang->line('Mobile'); ?>', '<?php echo $this->lang->line('Email_Address'); ?>', '<?php echo $this->lang->line('Sms Alert'); ?>', '<?php echo $this->lang->line('Email Alert'); ?>','<?php echo $this->lang->line('Comments'); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"rfid",editable:true, index:"rfid", width:180, align:"center", jsonmap:"rfid"},
			{name:"person",editable:true, index:"person", width:120, align:"center", jsonmap:"person"},
			{name:"assets_name",editable:true, index:"assets_name", width:120, align:"center", jsonmap:"assets_name"},
			{name:"landmark_name",editable:true, index:"landmark_name", width:120, align:"center", jsonmap:"landmark_name"},
			{name:"inform_mobile",editable:true, index:"inform_mobile", width:150, align:"center", jsonmap:"inform_mobile"},
			{name:"inform_email",editable:true, index:"inform_email", width:150, align:"center", jsonmap:"inform_email"},
			{name:"send_sms",editable:true, index:"send_sms", width:150, align:"center", jsonmap:"send_sms", formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"send_email",editable:true, index:"send_email", width:150, align:"center", jsonmap:"send_email", formatter:'select', editoptions:{value:"1:Yes;0:No"}},
			{name:"comments",editable:true, index:"comments", width:150, align:"center", jsonmap:"comments"},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#rfid_pager"),
		sortname: "id",
		loadComplete: function(){
			cancelloading();
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line('RF_ID_List'); ?>",
		editurl:"<?php echo site_url('rfid/deleteData'); ?>",
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
	jQuery("#rfid_grid").jqGrid("navGrid", "#rfid_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#rfid_grid").jqGrid("navButtonAdd","#rfid_pager",{caption:"<?php echo $this->lang->line('add'); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$('#rfid_list_div').hide();
			$('#rfid_form_div').show();
			$('#rfid_form_div').load('<?php echo site_url('/rfid/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#rfid_grid").jqGrid("navButtonAdd","#rfid_pager",{caption:"<?php echo $this->lang->line('edit'); ?>",
		onClickButton:function(){
			var gsr = jQuery("#rfid_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Only One Row'); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$("#loading_top").css("display","block");
					$('#rfid_form_div').show();
					$('#rfid_list_div').hide();
					$('#rfid_form_div').load('<?php echo site_url('rfid/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Row'); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	
	 con_asse_dis=$("#conf_dialog_rfid<?php echo $time; ?>");
	con_asse_dis.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					con_asse_dis.dialog("close");
					cancel_rfid(); 
				},
				No: function () {
					con_asse_dis.dialog("close");
				}
			},
		});
	cancelloading();
	
});
function submitFormrfid(id){
	var check_format_e_m=true;
	if($("#inform_mobile").val() != ""){
		var emails=$("#inform_mobile").val();
		var em=emails.split(/[;,]+/);
		for(i=0;i<em.length;i++)
		{
			if(em[i].length == 10)
			{
				$("#error_frm_M_rfid").hide();
			}else{
				$("#error_frm_M_rfid").show();
				$("#error_frm_M_rfid").html("<?php echo $this->lang->line("Mobile_Number_Formate_is_Not_Valid"); ?>");
				check_format_e_m=false;
			}
		}
	}
	
	if($("#inform_email").val() != ""){
		var emails=$("#inform_email").val();
		var em=emails.split(/[;,]+/);
		var regexp =/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
		for(i=0;i<em.length;i++)
		{
			if(regexp.test(em[i]))
			{
				$("#error_frm_E_rfid").hide();
			}else{
				$("#error_frm_E_rfid").show();
				$("#error_frm_E_rfid").html("<?php $this->lang->line("Email ID Formate is Not Valid"); ?>");
				check_format_e_m=false;
			}
		}
	}
	if(!check_format_e_m) return false;
			
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('rfid/form/id'); ?>/"+id, $("#frm_rfid").serialize(), 
			function(data){
				if($.trim(data)){
				
					$('#rfid_form_div').html(data);
				}else{
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Updated_Successfully'); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Inserted_Successfully'); ?>');
						$('#rfid_list_div').show();
						$('#rfid_form_div').hide();
						$("#alert_dialog").dialog('open');
					jQuery("#rfid_grid").trigger("reloadGrid");
				}
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			} 
		);
	return false;	
}

function cancel_rfid(){
	//$("#loading_dialog").dialog("open");
	$('#rfid_list_div').show();
	$('#rfid_form_div').hide();
	jQuery("#rfid_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>rfid/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function rfidPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>rfid/rfid_photo/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function DriverPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != "" && cellvalue != null){
		return '<img src="<?php echo base_url(); ?>rfid/driver_photo/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="rfid_list_div">
	<table id="rfid_grid" class="jqgrid"></table>
</div>
<div id="rfid_pager"></div>
<div id="rfid_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_rfid<?php echo $time; ?>" style="display:none;"> 
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
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