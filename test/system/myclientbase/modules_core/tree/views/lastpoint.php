<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');
	 $network_timeout = $this->session->userdata('network_timeout');
?>
<style>
#load_treelastpoint_grid
{
	display:none !important; 
}
#distanceBtn_grid
{
		display:none; 
}
</style>
<script type="text/javascript">
var rowColorRecharge=new Array();
var borderCrossSpeed=new Array();
var rowsToColor = [];
jQuery().ready(function (){
	
	jQuery("input:button, input:submit, input:reset").button();
	
	jQuery("#treelastpoint_grid").jqGrid({
		url:"<?php echo base_url(); ?>index.php/tree/loadGridData",
		datatype: "json",
		mtype: 'POST',		
		colNames:["<?php echo $this->lang->line("ID"); ?>", '', '', '<?php echo $this->lang->line("Asset_Name"); ?>','Device ID', 'Friendly Name', 'AST_ID', 'Dev Status', 'Img', 'Direction', '<?php echo $this->lang->line("Datetime"); ?>', '<?php echo $this->lang->line("Address"); ?>', 'Latitude', 'Longitude', 'MImage', 'MText', '<?php echo $this->lang->line("Speed_KM"); ?>', 'Mileage (KM)', 'Run Time', 'Ignition', 'Parked From', '<?php echo $this->lang->line("Status"); ?>', '<?php echo $this->lang->line("Before"); ?>','<?php echo $this->lang->line("Before"); ?>', 'Zone', '<?php echo $this->lang->line("In_Area"); ?>', '<?php echo $this->lang->line("Near Landmark"); ?>', '<?php echo $this->lang->line("Battery Status"); ?>', '<?php echo $this->lang->line("alarm_type"); ?>'<?php if($this->session->userdata('show_divisions') == 1) { ?>, '<?php echo $this->lang->line("assets_division"); ?>'<?php } ?><?php if($this->session->userdata('show_owners') == 1) { ?>, '<?php echo $this->lang->line("assets_owner"); ?>'<?php } ?>, '<?php echo $this->lang->line("message_cause"); ?>', 'selected', '<?php echo $this->lang->line("Speed Limit Cross"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"assets_id",index:"assets_id", width:15, hidden:true, jsonmap:"assets_id"}, // , formatter:format_chkd
			{name:"map", index:"map", width:30, align:"center", jsonmap:"map",formatter:format_map},
			{name:"am.assets_name", index:"am.assets_name", width:150, align:"left", jsonmap:"assets_name"},
			{name:"am.device_id",editable:true, index:"am.device_id", width:120,align:"center", jsonmap:"device_id"},
			{name:"assets_friendly_name",editable:true, index:"assets_friendly_name", hidden: true, align:"center", jsonmap:"assets_friendly_name"},
			{name:"ast_id",index:"ast_id", hidden:true, jsonmap:"ast_id"},
			{name:"dev_status",index:"dev_status", hidden:true, jsonmap:"dev_status"},
			{name:"status_img",index:"status_img", hidden:true, jsonmap:"status_img"},
			{name:"direction",index:"direction", hidden:true, jsonmap:"direction"},
			{name:"lm.add_date",editable:true, index:"lm.add_date", width:150, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"lm.address",editable:true, index:"lm.address", width:220, align:"center", jsonmap:"address"},
			{name:"lati",editable:true, index:"lati", hidden:true, align:"center", jsonmap:"lati"},
			{name:"longi",editable:true, index:"longi", hidden:true, align:"center", jsonmap:"longi"},
			{name:"maker_image",editable:true, index:"maker_image", hidden:true, align:"center", jsonmap:"maker_image"},
			{name:"maker_text",editable:true, index:"maker_text", hidden:true, align:"center", jsonmap:"maker_text"},
			{name:"speed",editable:true, index:"tlp.speed", width:80, align:"center", jsonmap:"speed"},
			{name:"km_reading",editable:true, index:"km_reading", width:80, align:"center", jsonmap:"km_reading"},
			{name:"runtime",editable:true, index:"runtime", width:80, align:"center", jsonmap:"runtime"},
			{name:"ignition",hidden:true, index:"tlp.ignition", width:80, align:"center", jsonmap:"ignition"},
			{name:"stop_from",editable:true, index:"stop_from", width:150, align:"center", jsonmap:"stop_from"},
			{name:"status",editable:true, index:"beforeTime", width:100, align:"center", jsonmap:"beforeTime",formatter:formatStatus},
			
			{name:"received_time", editable:true, index:"beforeTime", width:100, align:"center", jsonmap:"received_time"},
			{name:"beforeTime", hidden:true, index:"beforeTime", width:15, jsonmap:"beforeTime", formatter:formatColor},
			{name:"current_zone",editable:true, index:"current_zone", width:90, align:"center", jsonmap:"current_zone"},
			{name:"in_area",editable:true, index:"in_area", width:90, align:"center", jsonmap:"current_area"},
			{name:"near_landmark",editable:true, index:"near_landmark", width:105, align:"center", jsonmap:"current_landmark"},
			{name:"battery_status",editable:true, index:"battery_status", width:105, align:"center", jsonmap:"battery_status"},
			{name:"alarm_type",editable:true, index:"alarm_type", width:105, align:"center", jsonmap:"alarm_type"},
<?php if($this->session->userdata('show_divisions') == 1) { ?>
			{name:"assets_division",editable:true, index:"assets_division", width:100, align:"center", jsonmap:"assets_division"},
<?php } ?>
<?php if($this->session->userdata('show_owners') == 1) { ?>
			{name:"assets_owner",editable:true, index:"assets_owner", width:100, align:"center", jsonmap:"assets_owner"},
<?php } ?>
			{name:"data_type",editable:true, index:"data_type", width:105, align:"center", jsonmap:"data_type"},
			{name:"selected", hidden:true, index:"selected", width:10, align:"center", jsonmap:"selected"},
			{name:"cross_speed", index:"cross_speed", width:15, hidden:true, jsonmap:"cross_speed",formatter:changeCrossSpeed}
		],
		rowNum: limit,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: false,
		rowList:[50,100,200,300,400,500,600,700,800,900,10000],
		pager: jQuery("#treelastpoint_pager"),
		sortname: "am.assets_name",
		viewrecords: true,
		sortorder: "ASC",
		caption:"<?php echo $this->lang->line("Last Point List"); ?>",
		gridComplete: changeRow,
		jsonReader: { repeatitems : false, id: "0" },
		postData: {'assets' : tree_elements}
		// , sel:selected_assets_ids
	
	});

	jQuery("#treelastpoint_grid").jqGrid("navGrid", "#treelastpoint_pager", {add:false, edit:false, del:false, search:false	}, {}, {}, {}, {multipleSearch:false});

	$("#treelastpoint_pager option[value=10000]").text('All');

});

function changeCrossSpeed(cellValue, options, rowObject) {
	if (cellValue == 1)
	{
		borderCrossSpeed.push(rowObject.id);
	}    
}

function rowColorFormatter(cellValue, options, rowObject) {
	//alert(cellValue);
        if (cellValue == "Server")
            rowsToColor[rowsToColor.length] = options.rowId;
        return cellValue;
}

function format_chkd(cellValue, options, rowObject)
{	
	//var minutes = Math.floor(rowObject.beforeTime/60);
	var minutes = cellValue;
	var class_nm="";
	if(minutes < <?php echo $network_timeout; ?> && rowObject.speed > 0){
		class_nm = "running_asts";
	}
	else if(minutes < <?php echo $network_timeout;?> && rowObject.speed == 0){
		class_nm = "parked_asts";
	}else if(minutes > <?php echo $network_timeout;?> && minutes < <?php echo ($network_timeout+36000);?>  ){
		class_nm = "device_fault_asts";
	}else{
		class_nm = "out_of_network_asts";
	}
	
	if(rowObject.beforeTime=="" || rowObject.beforeTime==null)
		class_nm = "device_fault_asts";
	
	var string = "<input onclick='selectedAssets("+rowObject.assets_id+");' name='assets_check[]' value='"+rowObject.assets_id+"' type='checkbox' style='vertical-align:middle' "

	// if(rowObject.selected == 1)	string += " checked='true'";

	string += " class='"+class_nm+"'/>";
	
	return string;
}

function changeRow(){
	var ids = jQuery("#treelastpoint_grid").jqGrid('getDataIDs');

	$("#assets_total_1").html(jQuery("#treelastpoint_grid").jqGrid('getGridParam', 'records'));

	// var data = jQuery("#treelastpoint_grid").getGridParam('userData');
	
	// selected_assets_ids = data.selected;

	if (dashboardMarkers) {
		for (i in dashboardMarkers) {
			dashboardMarkers[i].setMap(null);
			dLabelArr[i].setMap(null);
		}
	}

	for (i=0; i<ids.length; i++) {
		
		var ret = jQuery("#treelastpoint_grid").jqGrid('getRowData', ids[i]);
		
		var ast_name	= ret.assets_friendly_name;
		var ast_id 		= ret.ast_id;
		var latitude 	= ret.lati;
		var longitude 	= ret.longi;
		var m_image   	= ret.maker_image;
		var m_text    	= ret.maker_text;
		var st_image   	= ret.status_img;
		var Status		= ret.dev_status;
		var direction 	= ret.direction;
		
		if((parseInt(latitude) != 0) && (parseInt(longitude) != 0) && latitude != '' && longitude != '') {
			//var content = "<img src='"+m_image+"' title='"+ast_name+"'>";
			var point = new google.maps.LatLng(parseFloat(latitude), longitude);
			
			var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; color: #2E6E9E; border: 1px solid black; background: none repeat scroll 0% 0% #DFEFFC; padding: 2px; margin-top: 4px; text-align:center; -moz-border-radius: 8px; border-radius: 8px; white-space: nowrap;'><img src='<?php echo base_url(); ?>assets/images/"+st_image+"' title='"+Status+"'><img src='<?php echo base_url(); ?>assets/images/direction.jpg' style='transform: rotate("+direction+"deg);-ms-transform:rotate("+direction+"deg);-webkit-transform:rotate("+direction+"deg);' title='Direction'>&nbsp;"+ast_name+"</div>";
			
			var myOptions1 = {
				 content: boxText
				,disableAutoPan: true
				,maxWidth: 500
				,position: point
				,pixelOffset: new google.maps.Size(-90,0)
				,zIndex: null
				,boxStyle: { 
				  //background: "url('tipbox.gif') no-repeat"
				  opacity: 0.75
				  ,width: 'auto'
				 }
				,closeBoxMargin: "10px 2px 2px 2px"
				,closeBoxURL: ""
				//http://www.google.com/intl/en_us/mapfiles/close.gif
				,infoBoxClearance: new google.maps.Size(1, 1)
				,isHidden: false
				,pane: "floatPane"
				,enableEventPropagation: true
			};
			dLabel = new InfoBox(myOptions1);                
			dLabel.open(dMap);
			dLabelArr[ast_id] = dLabel;			
			
			dashboardMarkers[ast_id] = createMarker(dMap, point, ast_name, m_text, m_image, '', 'sidebar_map', '' );
			
			/*
			if(ret.selected != 1) {
				dashboardMarkers[ret.ast_id].setMap(null);
				dLabelArr[ret.ast_id].setMap(null);
			}
			*/
			
		}
		$("#treelastpoint_grid tr#"+ids[i]).css("color",rowColorRecharge[ids[i]]);
	}

}

function formatStatus(cellValue, options, rowObject)
{
	var minutes = cellValue;
	var vehicleStatus="";
	if(minutes < <?php echo $network_timeout; ?> && rowObject.speed > 10){
		vehicleStatus = "Running";
	}
	else if(minutes < <?php echo $network_timeout; ?>  && rowObject.speed <= 10 && rowObject.ignition == 0){
		vehicleStatus = "Parked";
	}else if(minutes < <?php echo $network_timeout; ?> && rowObject.speed <= 10 && rowObject.ignition == 1){
		vehicleStatus = "Idle";
	}else if(minutes > <?php echo $network_timeout; ?> && minutes < <?php echo ($network_timeout+36000); ?>){
		vehicleStatus = "Out of network";
	}else{
		vehicleStatus = "Out Of Network";
	}
	if(cellValue=="" || cellValue==null)
		vehicleStatus = "Out of network";
	return vehicleStatus;
}

function formatColor(cellValue, options, rowObject) {
	
	var color;
	intRowRecharge = rowObject.id;
	var speed = rowObject.speed;
	var ignition = rowObject.ignition;
	if(ignition == null) ignition = 0;
//	var minutes = Math.floor(cellValue/60);
	var minutes = cellValue;
			if(minutes <= <?php echo $network_timeout; ?> && speed == 0 && ignition == 0){
				color = "#06F";
			}else if(minutes <= <?php echo $network_timeout; ?> && ignition == 1){
				color = "green";
			}else{
				color = "red";
			}
			if(cellValue=="" || cellValue==null || cellValue==undefined)
				color = "red";
	rowColorRecharge[intRowRecharge]=color;
	var re= /<\S[^><]*>/g;
	if(cellValue == undefined)
		cellValue = "";
	else
		var cellValue = cellValue.replace(re, "");
	var cellHtml = "<span style='color:" + color + "' originalValue='" + cellValue + "'>" + cellValue + "</span>";
	return cellHtml;
}

function format_map(cellVal, options, rowObject){
	var html="Date : "+rowObject.add_date+"<br>Speed : "+rowObject.speed+"<br>Address : "+rowObject.address+"<br>";
	return "<a href='#' onclick='directTab("+rowObject.device_id+","+rowObject.assets_id+")'> <img src='<?php echo base_url(); ?>/assets/marker-images/mini-BLUE1-BLANK.png'></a>";
}
function view_map(lat,lang,html,device){
	$('#tabs').tabs('add', "live/device/window/current/id/"+device, '<?php echo $this->lang->line("View_on_Map"); ?>', 1);
	viewLocation(lat,lang,html);
}
function cancel(){ 
	$('#lastpoint_frm').html('');
	$('#lastpoint_list_div').show();
}

$(document).ready(function() {

	$("#attendanceDivAjaxMsg").dialog({
		autoOpen: false,
		height: 120,
		draggable: false,
		resizable: false,
		modal: true,
		close : function(){}
	});
	
});

</script>
<br>
<div id="attendanceDivAjaxMsg" style="display:none"></div>
<div id="lastpoint_list_div" class="alist">
<!--div class="sixteen columns half-bottom" style="width:95%;float:left;padding-left:40px;">
						
						<?php if($this->session->userdata('show_dash_assets_combo')==1){ ?>
						<div id="detailed_pan" style="float: left; font-size: 13px; padding-top: 5px; padding-left: 12px;">
						
						<span class="ui-state-default" style="border-radius:7px;padding:2px 5px;border:1px solid">
						<input type='checkbox' onClick="select_all_ast();" style="padding: 0px; margin: 0px 0px 3px 5px;" id="all_ast"/>
						<a onClick='detail_list_a("")' style='text-decoration:underline' id='assets_total' rel="<?php echo $this->lang->line('Number of Total Vehicles'); ?>"><?php echo $this->lang->line("Total Assets"); ?> : <strong><span id="assets_total_1"><?php echo $total_1; ?></span></strong></a></span>&nbsp;
						
						<span class="ui-state-default" style="border-radius:7px;padding:2px 5px;border:1px solid">
						<input type='checkbox' onClick="select_running_ast();" style="padding: 0px; margin: 0px 0px 3px 5px;" id="running_ast"/>
						<a onClick="detail_list_a('running')" id='assets_running' rel="<?php echo $this->lang->line('Vehicles that has speed more than 0 (zero) and connected with Server since 20 minutes'); ?>"><?php echo $this->lang->line('running'); ?> : <strong><span id="assets_running_1"><?php echo $running_1; ?></span></strong></a></span>&nbsp;
						
						<span class="ui-state-default" style="border-radius:7px;padding:2px 5px;border:1px solid">
						<input type='checkbox' onClick="select_parked_ast();" style="padding: 0px; margin: 0px 0px 3px 5px;" id="parked_ast"/>
						<a onClick="detail_list_a('parked')" id='assets_parked' rel="<?php echo $this->lang->line('Vehicles that has speed 0 (zero) and connected with Server since 20 minutes'); ?>"><?php echo $this->lang->line('parked'); ?> : <strong><span id="assets_parked_1"><?php echo $parked_1; ?></span></strong></a></span>&nbsp;
						
						<span class="ui-state-default" style="border-radius:7px;padding:2px 5px;border:1px solid"><input type='checkbox' onClick="select_out_ast();" style="padding: 0px; margin: 0px 0px 3px 5px;" id="out_ast"/>
						<a onClick="detail_list_a('out_of_network')" id='assets_out' rel="<?php echo $this->lang->line('Vehicles that are not connected with Server since 20 minutes'); ?>"><?php echo $this->lang->line('out_of_network'); ?> : <strong><span id="assets_out_1"><?php echo $out_of_network_1; ?></span></strong></a></span>&nbsp;
						
						<span class="ui-state-default" style="border-radius:7px;padding:2px 5px;border:1px solid"><input type='checkbox' onClick="select_fault_ast();" style="padding: 0px; margin: 0px 0px 3px 5px;" id="fault_ast"/>
						<a onClick="detail_list_a('device_fault')" id='assets_fault' rel="<?php echo $this->lang->line('Vehicles that are not Connected with Server since 24 hours'); ?>"><?php echo $this->lang->line('device_fault'); ?> : <strong><span id="assets_fault_1"><?php echo $device_fault_1; ?></span></strong></a></span>
						
						</div>
						<?php } ?>
				</div-->
<?php if($this->session->userdata('show_dash_legends')==1){ ?>
<!--div style="padding-left:25px;margin-bottom:8px:display:">
    <center>
    <ul style="list-style-type: none;">
    <li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/running.png" />&nbsp;&nbsp;<?php echo $this->lang->line('running'); ?>/<?php echo $this->lang->line('parked'); ?>,</li>
    <li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/out_of_network.png" />&nbsp;&nbsp;<?php echo $this->lang->line('out_of_network'); ?>/<?php echo $this->lang->line('device_fault'); ?>,</li>
    <li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/speed_limit.png" />&nbsp;&nbsp;<?php echo $this->lang->line('Speed Limit Cross'); ?>,</li>
    <li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/landmark.png" />&nbsp;&nbsp;<?php echo $this->lang->line('Near Landmark'); /* $this->lang->line('near_landmark'); */ ?>,</li>
    <li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/geofence.png" />&nbsp;&nbsp;<?php echo $this->lang->line('In Area'); /* $this->lang->line('near_landmark'); */ ?>,&nbsp;&nbsp;&nbsp;</li>
    </ul>
</center>
</div-->
<?php } ?> 
<div style="clear:both"></div>
<div style="font-size: 12px;font-weight: bold; margin:5px; display:block"><?php echo $this->lang->line('Last Data Recieved'); ?> : <?php echo date("$date_format $time_format"); ?> </div>
<table id="treelastpoint_grid" class="jqgrid"></table>

<div id="treelastpoint_pager"></div>
<!--div style='text-align: center;clear:both'>
	<div style="float:left;width:100%;height:2em;padding-top:0.2em">
		
	<input type='checkbox' onclick='stop_resume_toggle()' <?php if($auto_refresh_setting == 1) echo 'checked="checked"'; ?> id='checkboxToggle'> <?php echo $this->lang->line('data_refresh_after'); ?> 
	<input type='text' size='2' onblur='counter_change()' value='15' id='time_in_seconds'> <?php echo $this->lang->line('seconds'); ?> (<?php echo $this->lang->line('refresh_after'); ?> <span id='seconds'>15</span> <?php echo $this->lang->line('second'); ?>) &nbsp;&nbsp;<span onClick="reloadDashboard_Assets_Timer()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php echo $this->lang->line('refresh'); ?></span></div>
</div-->
	<div style="clear:both"></div>                

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