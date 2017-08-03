<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Export");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'91');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}

	//session date & time format
	$date_format = $this->session->userdata('date_format');  
	$time_format = $this->session->userdata('time_format');  
	$js_date_format = $this->session->userdata('js_date_format');  
	$js_time_format = $this->session->userdata('js_time_format');  
?>
<style>
#load_alerts_grid
{
	display:none !important; 
}

.date_txt_dealer{
    padding: 0.4em;
    width: 94%;
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){ 
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#alerts_grid").jqGrid({
		url:"<?php echo site_url('alerts/loadData'); ?>",
		datatype: "json",
		// ,'<?php echo $this->lang->line("Alert Link"); ?>'
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Date-Time"); ?>',"<?php echo $this->lang->line("Alert Header"); ?>",'<?php echo $this->lang->line("Message"); ?>','<?php echo $this->lang->line("Alert Type"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:200, align:"center", jsonmap:"add_date" ,formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"alert_header",editable:true, index:"alert_header", width:200, align:"center", jsonmap:"alert_header"},
			{name:"alert_msg",editable:true, index:"alert_msg", width:800, align:"center", jsonmap:"alert_msg"},
//			{name:"alert_link",editable:true, index:"alert_link", width:200, align:"center", jsonmap:"alert_link"},
			{name:"alert_type",editable:true, index:"alert_type", width:100, align:"center", jsonmap:"alert_type"}
			
		],
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[10,20,30,50,100],
		pager: jQuery("#alerts_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false,
		caption:"<?php echo $this->lang->line("Alert Report"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	$("#assets_id_alerts").html(assets_combo_opt_report);
	
	jQuery("#alerts_grid").jqGrid("navGrid", "#alerts_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#alerts_grid").jqGrid("navButtonAdd","#alerts_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var type = $('#alert_type').val();
			var sdate = $('#sdate_alerts').val();
			var edate = $('#edate_alerts').val();
			//var assets_id = $('#assets_id_alerts').val();
			var dev="";
			for(i=0;i<(assets_count+1);i++){
				if($("#ddcl-assets_id_alerts-i"+i).is(':checked')){
					dev+=$("#ddcl-assets_id_alerts-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			qrystr ="/cmd/export/sdate/"+sdate+"/edate/"+edate+"/assets_id/"+dev+"/type/"+type;
			document.location = "<?php echo site_url('alerts/loadData'); ?>"+qrystr;
		}
	});
	<?php } ?>
	$("#sdate_alerts").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#edate_alerts").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});	
	$("#sdate_alerts").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#edate_alerts").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));

	$("#assets_id_alerts").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b><?php echo $this->lang->line("All Assets"); ?></b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-assets_id_alerts").css('vertical-align','middle');
	$("#ddcl-assets_id_alerts-ddw").css('overflow-x','hidden');
	$("#ddcl-assets_id_alerts-ddw").css('overflow-y','auto');
	$("#ddcl-assets_id_alerts-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	$("#loading_top").css("display","none");
});

function search_alerts(){
	
	var type = $('#alert_type').val();
	var sdate = $('#sdate_alerts').val();
	var edate = $('#edate_alerts').val();
	//var assets_id = $('#assets_id_alerts').val();
	var dev="";
	for(i=0;i<(assets_count+1);i++){
		if($("#ddcl-assets_id_alerts-i"+i).is(':checked')){
			dev+=$("#ddcl-assets_id_alerts-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	//$("#allpoints_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","blocks");
	jQuery("#alerts_grid").jqGrid('setGridParam',{postData:{type:type, sdate:sdate, edate:edate, assets_id:dev,  page:1}}).trigger("reloadGrid");
	return false;	
}
  
</script>
<form onsubmit="return search_alerts()">
<table width="100%">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate_alerts" id="sdate_alerts" class="date_txt_dealer date text ui-widget-content ui-corner-all" style="width:128px" readonly="readonly"  value='<?php echo date('d.m.Y'); ?>' /></td>
		
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate_alerts" id="edate_alerts" class="date_txt_dealer date text ui-widget-content ui-corner-all" style="width:128px" readonly="readonly" value='<?php echo date('d.m.Y'); ?>' /></td>
		
		<td width="20%"><?php echo $this->lang->line("Alert Type"); ?> : <select name="type" id="alert_type" class="select ui-widget-content ui-corner-all" style="width: 128px; padding: 4px;">
		<option value=""><?php echo $this->lang->line("All"); ?></option>
		<option value="Area In Alert">Area In Alert</option>
		<option value="Area">Area in/Out Alert</option>
		<option value="Area Out Alert">Area Out Alert</option>
		<option value="Box Close Alert">Box Close Alert</option>
		<option value="Box Open Alert">Box Open Alert</option>
		<option value="Box">Box Open/Close Alert</option>
		<option value="Fuel Alert">Fuel Alert</option>
		<option value="Ignition">Ignition Alert</option>
		<option value="Ignition Off Alert">Ignition Off Alert</option>
		<option value="Ignition On Alert">Ignition On Alert</option>
		<option value="Login Info">Login Info</option>
		<option value="Near Landmark Alert">Near Landmark Alert</option>
		<option value="Over Speed Alert">Over Speed Alert</option>
		<option value="Vehicle Running With No Ignition">Running With No Ignition Alert</option>
		<option value="Temperature">Temperature Alert</option>
		<option value="Vehicle Stop Alert">Vehicle Stop Alert</option>
		</select></td>
		
		

		
		
		<td width="20%"><?php echo $this->lang->line("Assets"); ?> : <select name="assets_id_alerts" id="assets_id_alerts" class="select ui-widget-content ui-corner-all" style="width: 128px; padding: 4px;" multiple='multiple'>
			<?php
				/*$SQL = "SELECT assets_name , device_id , id  from assests_master where add_uid = ".$this->session->userdata('user_id');
				
				$result = $this->db->query($SQL);
				
				foreach($result->result_array() as $data)  
				{
					echo "<option value='".$data['device_id']."'>".$data['assets_name']."</option>";
				}*/
			?>
			
		</select>
		</td>
		
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</form>
<div id="alerts_list_div">
	<table id="alerts_grid" class="jqgrid"></table>
</div>
<div id="alerts_pager"></div>
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