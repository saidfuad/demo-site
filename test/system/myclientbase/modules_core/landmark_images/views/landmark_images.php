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
		$va1l->where("menu_id",'87');
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
#load_landmark_images_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var con_asse_dis;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#landmark_images_grid").jqGrid({
		url:"<?php echo site_url('landmark_images/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line('Images'); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"image_path",editable:true, index:"image_path", width:150, align:"center", jsonmap:"image_path", formatter: iconPathFormatter}
		
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#landmark_images_pager"),
		sortname: "id",
		loadComplete: function(){
			cancelloading();
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line('Landmark Images List'); ?>",
		editurl:"<?php echo site_url('landmark_images/deleteData'); ?>",
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
	jQuery("#landmark_images_grid").jqGrid("navGrid", "#landmark_images_pager", {add:false, edit:false, del:<?php echo $delete; ?>, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Add',$data)){
	?>
	jQuery("#landmark_images_grid").jqGrid("navButtonAdd","#landmark_images_pager",{caption:"<?php echo $this->lang->line('add'); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$('#landmark_images_list_div').hide();
			$('#landmark_images_form_div').show();
			$('#landmark_images_form_div').load('<?php echo site_url('/landmark_images/form/'); ?>');
		}
	});
<?php } ?>
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#landmark_images_grid").jqGrid("navButtonAdd","#landmark_images_pager",{caption:"<?php echo $this->lang->line('edit'); ?>",
		onClickButton:function(){
			var gsr = jQuery("#landmark_images_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Only One Row'); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$("#loading_top").css("display","block");
					$('#landmark_images_form_div').show();
					$('#landmark_images_list_div').hide();
					$('#landmark_images_form_div').load('<?php echo site_url('landmark_images/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line('Please Select Row'); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	 con_asse_dis=$("#conf_dialog_landmark_images<?php echo $time; ?>");
	con_asse_dis.dialog({
			modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
			width: 'auto', resizable: false,
			buttons: {
				Yes: function () {
					con_asse_dis.dialog("close");
					cancel_landmark_images(); 
				},
				No: function () {
					con_asse_dis.dialog("close");
				}
			},
		});
	cancelloading();
	
});
function submitFormlandmark_images(id){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo site_url('landmark_images/form/id'); ?>/"+id, $("#frm_landmark_images").serialize(), 
			function(data){
				if($.trim(data)){
				
					$('#landmark_images_form_div').html(data);
				}else{
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Updated_Successfully'); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Inserted_Successfully'); ?>');
						$('#landmark_images_list_div').show();
						$('#landmark_images_form_div').hide();
						$("#alert_dialog").dialog('open');
					jQuery("#landmark_images_grid").trigger("reloadGrid");
				}
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			} 
		);
	return false;	
}

function cancel_landmark_images(){
	//$("#loading_dialog").dialog("open");
	$('#landmark_images_list_div').show();
	$('#landmark_images_form_div').hide();
	jQuery("#landmark_images_grid").trigger("reloadGrid");
}
function iconPathFormatter(cellvalue, options, rowObject){
	if(cellvalue != ""){
		return '<img src="<?php echo base_url(); ?>/'+cellvalue+'" border="0">';
	}
	else
		return '';
}
</script>
<div id="landmark_images_list_div">
	<table id="landmark_images_grid" class="jqgrid"></table>
</div>
<div id="landmark_images_pager"></div>
<div id="landmark_images_form_div" style="padding:10px;display:none;height:450px;">
</div>
<div id="conf_dialog_landmark_images<?php echo $time; ?>" style="display:none;"> 
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