<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Delete","Search","Add","Edit","Export");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'13');
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
#load_assets_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadSWFupload();
var con_asse_dis;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#assets_grid").jqGrid({
		url:"<?php echo site_url('assets/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line('Asset_Name'); ?>','<?php echo $this->lang->line('Device_Id'); ?>','<?php echo $this->lang->line('assets_friendly_nm'); ?>', '<?php echo $this->lang->line('Icon'); ?>', '<?php echo $this->lang->line('Assets_Category'); ?>', '<?php echo $this->lang->line('Assets_Type'); ?>', '<?php echo $this->lang->line('Sim_Number'); ?>','<?php echo $this->lang->line('Assets_Image'); ?>','<?php echo $this->lang->line('Driver_Name'); ?>','<?php echo $this->lang->line('Driver_Image'); ?>','<?php echo $this->lang->line('Driver_Mobile'); ?>','<?php echo $this->lang->line('Max_speed_limit'); ?>','<?php echo $this->lang->line('Maximum_Fual_Capacity'); ?>','<?php echo $this->lang->line('Max_fual_liters'); ?>','<?php echo $this->lang->line('Sensor Type'); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_name",editable:true, index:"assets_name", width:180, align:"center", jsonmap:"assets_name"},
			{name:"device_id",editable:true, index:"device_id", width:120, align:"center", jsonmap:"device_id"},
			{name:"assets_friendly_nm",editable:true, index:"assets_friendly_nm", width:120, align:"center", jsonmap:"assets_friendly_nm"},
			{name:"icon_path",editable:true, index:"icon_path", width:150, align:"center", jsonmap:"icon_path", formatter: iconPathFormatter},
			{name:"assets_category",editable:true, index:"assets_category", width:150, align:"center", jsonmap:"assets_category"},
			{name:"assets_type",editable:true, index:"assets_type", width:150, align:"center", jsonmap:"assets_type"},
			{name:"sim_number",editable:true, index:"sim_number", width:150, align:"center", jsonmap:"sim_number"},
			{name:"assets_image_path",editable:true, index:"assets_image_path", width:150, align:"center", jsonmap:"assets_image_path", formatter: AssetsPathFormatter},
			{name:"driver_name",editable:true, index:"driver_name", width:150, align:"center", jsonmap:"driver_name"},
			{name:"driver_image",editable:true, index:"driver_image", width:150, align:"center", jsonmap:"driver_image", formatter: DriverPathFormatter},
			{name:"driver_mobile",editable:true, index:"driver_mobile", width:150, align:"center", jsonmap:"driver_mobile"},
			{name:"max_speed_limit",editable:true, index:"max_speed_limit", width:150, align:"center", jsonmap:"max_speed_limit"},
			{name:"max_fuel_capacity",editable:true, index:"am.max_fuel_capacity", width:150, align:"center", jsonmap:"max_fuel_capacity"},
			{name:"max_fuel_liters",editable:true, index:"am.max_fuel_liters", width:150, align:"center", jsonmap:"max_fuel_liters"},
			{name:"sensor_type",editable:true, index:"am.sensor_type", width:150, align:"center", jsonmap:"sensor_type"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#assets_pager"),
		sortname: "am.id",
		loadComplete: function(){
			cancelloading();
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line('Assets_List'); ?>",
		editurl:"<?php echo site_url('assets/deleteData'); ?>",
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
	jQuery("#assets_grid").jqGrid("navGrid", "#assets_pager", {add:false, edit:false, del : <?php echo $delete; ?>, deltitle: 'Delete Records.....!',  search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#assets_grid").jqGrid("navButtonAdd","#assets_pager",{caption:"<?php echo $this->lang->line('add'); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$('#assets_list_div').hide();
			$('#assets_form_div').show();
			$('#assets_form_div').load('<?php echo site_url('/assets/form/'); ?>');
		}
	});
	<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#assets_grid").jqGrid("navButtonAdd","#assets_pager",{caption:"<?php echo $this->lang->line('edit'); ?>",
		onClickButton:function(){
			var gsr = jQuery("#assets_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Only One Row'); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$("#loading_top").css("display","block");
					$('#assets_form_div').show();
					$('#assets_list_div').hide();
					$('#assets_form_div').load('<?php echo site_url('assets/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Row'); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	<?php
	//if(in_array('Export',$data)){
	?>
	jQuery("#assets_grid").jqGrid("navButtonAdd","#assets_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var qrystr ="/export";
			
			document.location = "<?php echo base_url(); ?>index.php/assets/loadData"+qrystr;
		}
	});
	<?php //} ?>
	 con_asse_dis=$("#conf_dialog_assets<?php echo $time; ?>");
	con_asse_dis.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					con_asse_dis.dialog("close");
					cancel_assets(); 
				},
				No: function () {
					con_asse_dis.dialog("close");
				}
			},
		});
	cancelloading();	
});
function submitFormAssets(id){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	
	var deviceId=$("#device_id").val();
	$.post("<?php echo site_url('assets/checkDupli/deviceId'); ?>/"+deviceId+"/id/"+id, 
		function(data){
			if(data.result!=true){
				$("#duplicateDeviceId").hide();
				$.post("<?php echo site_url('assets/form/id'); ?>/"+id, $("#frm_assets").serialize(), 
					function(data){
						if($.trim(data)){				
							$('#assets_form_div').html(data);
						}else{
							if(id != "")
								$("#alert_dialog").html('<?php echo $this->lang->line('Record_Updated_Successfully'); ?>');
							else
								$("#alert_dialog").html('<?php echo $this->lang->line('Record_Inserted_Successfully'); ?>');
								$('#assets_list_div').show();
								$('#assets_form_div').hide();
								$("#alert_dialog").dialog('open');
							jQuery("#assets_grid").trigger("reloadGrid");
						}
						//$("#loading_dialog").dialog("close");
						$("#loading_top").css("display","none");
					} 
				);
			}else if(data.result==true){
				$(".error").hide();
				$("#duplicateDeviceId").html("Device Id already Exist in "+data.users+".");
				$("#duplicateDeviceId").show();
				$("#loading_top").css("display","none");
			}
		},'json'
	);
	return false;	
}
function cancel_assets(){
	//$("#loading_dialog").dialog("open");
	$('#assets_list_div').show();
	$('#assets_form_div').hide();
	$("#duplicateDeviceId").hide();
	jQuery("#assets_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>/assets/marker-images/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function AssetsPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>/assets/assets_photo/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
function DriverPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != "" && cellvalue != null){
		return '<img src="<?php echo base_url(); ?>/assets/driver_photo/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="assets_list_div">
	<table id="assets_grid" class="jqgrid"></table>
</div>
<div id="assets_pager"></div>
<div id="assets_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_assets<?php echo $time; ?>" style="display:none;"> 
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