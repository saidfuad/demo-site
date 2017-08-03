<?php $time=time(); ?>
<script type="text/javascript">
jQuery().ready(function (){
	
	jQuery("#last_20_points_grid_<?php echo $time; ?>").jqGrid({
		url:"<?php echo base_url(); ?>index.php/home/get_all_points_report",
		datatype: "json",
		colNames:['ID','<?php echo $this->lang->line("Datetime"); ?>', '<?php echo $this->lang->line("Status"); ?>', '<?php echo $this->lang->line("Address"); ?>', 'Sp', 'Map'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date", index:"add_date", width:140, align:"center", jsonmap:"add_date"},
			{name:"status",editable:true, index:"status", width:60, align:"center", jsonmap:"status"},
			{name:"address",editable:true, index:"address", width:350, align:"center", jsonmap:"address"},
			{name:"speed",editable:true, index:"tlp.speed", width:35, align:"center", jsonmap:"speed"},
			{name:"map",editable:true, index:"map", width:30, align:"center", jsonmap:"map"}
		],
		rowNum: 20,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		sortname: "am.assets_name",
		viewrecords: true,
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		sortorder: "ASC",
		// caption:"<?php echo $this->lang->line("Last Point List"); ?>",
		jsonReader: { repeatitems : false, id: "0" },
		loadComplete: function(data) {
			$("#loading_top").css("display","none");
		},
		postData: {device: <?php echo $id; ?>}
	});

	jQuery("#last_20_points_grid_<?php echo $time; ?>").jqGrid("navGrid", "#lastpoint_pager_<?php echo $time; ?>", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
});

</script>
<table id="last_20_points_grid_<?php echo $time; ?>" class="jqgrid"></table>
