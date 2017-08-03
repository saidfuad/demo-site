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
		$va1l->where("menu_id",'78');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	

?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<?php
	/*	$Assest="<option value=''>Select Assest </option>";
		$user_id=$this->session->userdata('user_id');
		$query="select assets_name, device_id from assests_master where find_in_set(id, (select assets_ids from user_assets_map where user_id = $user_id))";
		
		$result=mysql_query($query);
		while($row=mysql_fetch_array($result))
		{
			$Assest .="<option value='".$row['device_id']."'>".$row['assets_name']."(".$row['device_id'].")"."</option>";
		}*/
?>
<style>
#load_rfid_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
loadMultiSelectDropDown();
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#rfid_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/rfid/loadData",
		datatype: "local", 
		colNames:["<?php echo $this->lang->line("ID"); ?>", '<?php echo $this->lang->line("Person"); ?>', '<?php echo $this->lang->line("Assets 1"); ?>', '<?php echo $this->lang->line("Boarding Time"); ?>','<?php echo $this->lang->line("Boarding Address"); ?>','<?php echo $this->lang->line("Assets 2"); ?>', '<?php echo $this->lang->line("Leave Time"); ?>', '<?php echo $this->lang->line("Leave Address"); ?>'],
		colModel:[
			{name:"id",index:"rf.id",hidden:true, width:15, jsonmap:"id"},
			{name:"Person",editable:true, index:"tr.person", width:120, align:"center", jsonmap:"person"},
			{name:"device",editable:true, index:"am.assets_name", width:120, align:"center", jsonmap:"device"},
			{name:"b_time",editable:true, index:"rf.boarding_time", width:200, align:"center", jsonmap:"b_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"b_address",editable:true, index:"rf.b_address", width:150, align:"center", jsonmap:"b_address"},
			{name:"device1",editable:true, index:"am1.assets_name", width:150, align:"center", jsonmap:"device1"},
			{name:"leaving_time",editable:true, index:"rf.leaving_time", width:200, align:"center", jsonmap:"l_time", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"l_address",editable:true, index:"rf.l_address", width:150, align:"center", jsonmap:"l_address"}
		],
		rowNum:10,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#rfid_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#rfid_grid").setGridParam({datatype: 'json'}); 
		},	
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "desc",
		caption:"<?php echo $this->lang->line("RFID report"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#rfid_grid").jqGrid("navGrid", "#rfid_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#rfid_grid").jqGrid("navButtonAdd","#rfid_pager",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			
			var sdate = $('#sdate_rfid').val();
			var edate = $('#edate_rfid').val();
			//var device = $('#device_rfid').val();
			var dev="";
			for(i=0;i<assets_count;i++){
				if($("#ddcl-device_rfid-i"+i).is(':checked')){
					dev+=$("#ddcl-device_rfid-i"+i).val()+",";
				}
			}
			if(dev == ''){
				$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
				$("#alert_dialog").dialog("open");
				return false;
			}
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+dev;
			document.location = "<?php echo base_url(); ?>index.php/reports/rfid/loadData"+qrystr;
		}
	});
	<?php } ?>
	$("#device_rfid").html(assets_combo_opt_report);
	$("#sdate_rfid").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#edate_rfid").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#sdate_rfid").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#edate_rfid").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#loading_top").css("display","none");
	$("#device_rfid").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-device_rfid").css('vertical-align','middle');
	$("#ddcl-device_rfid-ddw").css('overflow-x','hidden');
	$("#ddcl-device_rfid-ddw").css('overflow-y','auto');
	$("#ddcl-device_rfid-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
}); 

function searchrfid(){
	
	var sdate = $('#sdate_rfid').val();
	var edate = $('#edate_rfid').val();
	//var device = $('#device_rfid').val();
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-device_rfid-i"+i).is(':checked')){
			dev+=$("#ddcl-device_rfid-i"+i).val()+",";
		}
	}
	if(dev == ''){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please select device"); ?>');
		$("#alert_dialog").dialog("open");
		return false;
	}
	$("#loading_top").css("display","block");
	jQuery("#rfid_grid").jqGrid('setGridParam',{postData:{sdate:sdate,edate:edate, device:dev, page:1}}).trigger("reloadGrid");
	
	return false;	
}
</script>
<div id="rfid_list_div">
<form onsubmit="return searchrfid()">
<table width="100%" class="formtable">
	<tr>
		<td width="10%"><?php echo $this->lang->line("from_date"); ?> : <input type="text" name="sdate" id="sdate_rfid" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
			<td width="10%"> <?php echo $this->lang->line("to_date"); ?> : <input type="text" name="edate" id="edate_rfid" class="date text ui-widget-content ui-corner-all" style="width:110px" value="<?php echo date('d.m.Y'); ?>" readonly="readonly"/></td>
		<td width="14%"><?php echo $this->lang->line("assets"); ?> : <select name="device" id="device_rfid" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple='multiple'></select></td>
		<td width="3%"><input type="submit" value="<?php echo $this->lang->line("Search"); ?>"/></td>
       
        </tr></table><br/>
</form>
</div>
<table id="rfid_grid" class="jqgrid"></table>

<div id="rfid_pager"></div>
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