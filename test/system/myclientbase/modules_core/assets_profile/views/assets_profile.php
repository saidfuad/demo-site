<script type="text/javascript">
cancelloading();
</script>
<?php $time=time(); die(); ?>
<script type="text/javascript">
var conf_dialog_assest_profile_fitcut;
jQuery().ready(function (){
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#assets_profile_grid").jqGrid({
		url:"<?php echo site_url('assets_profile/loadData'); ?>",
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line('profile'); ?>','<?php echo $this->lang->line('Min_Consecutive_Speed'); ?>', '<?php echo $this->lang->line('Max_Consecutive_Speed'); ?>','<?php echo $this->lang->line('Max_Idle_Time'); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"profile_name",editable:true, index:"profile_name", width:180, align:"center", jsonmap:"profile_name"},
			{name:"min_consecutive_speed",editable:true, index:"min_consecutive_speed", width:180, align:"center", jsonmap:"min_consecutive_speed"},
			{name:"max_consecutive_speed",editable:true, index:"max_consecutive_speed", width:120, align:"center", jsonmap:"max_consecutive_speed"},
			{name:"max_idle_time",editable:true, index:"max_idle_time", width:120, align:"center", jsonmap:"max_idle_time"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#assets_profile_pager"),
		sortname: "id",
		viewrecords: true,
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		multiselect: true, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("Assets Profile"); ?>",
		editurl:"<?php echo site_url('assets_profile/deleteData'); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#assets_profile_grid").jqGrid("navGrid", "#assets_profile_pager", {add:false, edit:false, del:true, search:true}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#assets_profile_grid").jqGrid("navButtonAdd","#assets_profile_pager",{caption:"<?php echo $this->lang->line("add"); ?>",
		onClickButton:function(){
			//$("#loading_dialog").dialog("open");
			$('#assets_profile_list_div').hide();
			$('#assets_profile_form_div').show();
			$('#assets_profile_form_div').load('<?php echo site_url('/assets_profile/form/'); ?>');
		}
	});

	jQuery("#assets_profile_grid").jqGrid("navButtonAdd","#assets_profile_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
		onClickButton:function(){
			var gsr = jQuery("#assets_profile_grid").jqGrid("getGridParam","selarrrow");
			if(gsr.length > 0){
				if(gsr.length > 1){
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
				else{
					//$("#loading_dialog").dialog("open");
					$('#assets_profile_form_div').show();
					$('#assets_profile_list_div').hide();
					$('#assets_profile_form_div').load('<?php echo site_url('assets_profile/form/id'); ?>/'+gsr[0]);
				}
			} else {
				$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
				$("#alert_dialog").dialog("open");
			}
		}
	});
	conf_dialog_assest_profile_fitcut=$("#conf_dialog_assets_profile<?php $time=time(); ?>");
	conf_dialog_assest_profile_fitcut.dialog({
		modal: true, title: 'Conform message', zIndex: 10000, autoOpen: false,
		width: 'auto', resizable: false,
		buttons: {
			Yes: function () {
				conf_dialog_assest_profile_fitcut.dialog("close");
				cancel_assets_profile(); 
			},
			No: function () {
				conf_dialog_assest_profile_fitcut.dialog("close");
			}
		},
	
	}); 
	 cancelloading();
});

function submitFormAssetsProfile(id){
	var val=$("#profile_name").val();
	val+=","+id;
	$.post("<?php echo site_url('assets_profile/check_duplicate/val'); ?>/"+val, function(data){
		if(data=="true")
		{
		$("#chkDup").hide();
		//$("#loading_dialog").dialog("open");
		$.post("<?php echo site_url('assets_profile/form/id'); ?>/"+id, $("#frm_assets_profile").serialize(), 
			function(data){
			//$("#loading_dialog").dialog("close");
				if(data){
					$('#assets_profile_form_div').html(data);
				}else{
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Updated_Successfully'); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line('Record_Inserted_Successfully'); ?>');
					$("#alert_dialog").dialog('open');
					$('#assets_profile_list_div').show();
					$('#assets_profile_form_div').hide();
					jQuery("#assets_profile_grid").trigger("reloadGrid");
				}
			} 
			);
		}
		else
		{	
			$("#chkDup").html(data);
			$("#chkDup").show();
		}
	});
	return false;	
}
function cancel_assets_profile(){
	//$("#loading_dialog").dialog("open");
	$('#assets_profile_list_div').show();
	$('#assets_profile_form_div').hide();
	jQuery("#assets_profile_grid").trigger("reloadGrid");
}
</script>
<div id="assets_profile_list_div">
	<table id="assets_profile_grid" class="jqgrid"></table>
</div>
<div id="assets_profile_pager"></div>
<div id="assets_profile_form_div" style="padding:10px;display:none;height:450px;">
</div>

<div id="conf_dialog_assets_profile<?php $time=time(); ?>" style="display:none;">
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