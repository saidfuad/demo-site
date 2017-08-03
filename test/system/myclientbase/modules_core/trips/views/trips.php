<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Search","Edit");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'65');
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
#load_trips_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var conf_dialog_trips_var_kitkotlit;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#trips_grid").jqGrid({
		url:"<?php echo site_url('trips/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Route Name"); ?>','<?php echo $this->lang->line("Assets"); ?>'],
		colModel:[
			{name:"id",index:"tr.id",hidden:true, width:15, jsonmap:"id"},
			{name:"routename",editable:true, index:"tr.routename", width:120, align:"center", jsonmap:"routename"},
			{name:"assets",editable:true, index:"assets", width:600,align:"center", jsonmap:"assets"},
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#trips_pager"),
		sortname: "id",
		viewrecords: true,
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Trip List"); ?>",
		editurl:"<?php echo site_url('trips/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
<?php
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#trips_grid").jqGrid("navGrid", "#trips_pager", {add:false, edit:false, del:false, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Edit',$data)){
	?>
	jQuery("#trips_grid").jqGrid("navButtonAdd","#trips_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#trips_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$("#loading_top").css("display","block");
					$('#trips_form_div').show();
					$('#trips_list_div').hide();
					$('#trips_form_div').load('<?php echo site_url('trips/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	<?php } ?>
	conf_dialog_trips_var_kitkotlit=$("#conf_dialog_trip<?php $time=time(); ?>");
	conf_dialog_trips_var_kitkotlit.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_trips_var_kitkotlit.dialog("close");
				cancel_trips();
			},
			No: function () {
				conf_dialog_trips_var_kitkotlit.dialog("close");
			}
		},
	

	});
	cancelloading();
});
function submitFormGroup(id){
	//$("#loading_dialog").dialog("open");
	id=$("#routename").val();
	$.post("<?php echo site_url('trips/form/id'); ?>/"+id, $("#frm_trips").serialize(), 
			function(data){
				if(data){
					$('#trips_form_div').html(data);
				}else{
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
				
					$('#trips_list_div').show();
					$('#trips_form_div').hide();
					jQuery("#trips_grid").trigger("reloadGrid");
				}
				//$("#loading_dialog").dialog("close");
			} 
		);
	return false;	
}
function cancel_trips(){
	//$("#loading_dialog").dialog("open");
	$('#trips_list_div').show();
	$('#trips_form_div').hide();
	jQuery("#trips_grid").trigger("reloadGrid");
}
</script>
<div id="trips_list_div">
	<table id="trips_grid" class="jqgrid"></table>
</div>
<div id="trips_pager"></div>
<div id="trips_form_div" style="padding:10px;display:none;height:450px;">

<div id="conf_dialog_trip<?php $time=time(); ?>" style="display:none;">
<?php echo $this->lang->line("Are You Sure ! You Want to Exit"); ?> ?
</div>
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