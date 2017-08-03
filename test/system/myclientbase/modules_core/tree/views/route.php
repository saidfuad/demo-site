<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("History","List","Create Route");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'3');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
?>
<?php 
$routeComb="route_device".time();
?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
?>
<script type="text/javascript">
	loadColorSelection();
	loadMarkerClusters();
</script>
<style>
	#dialog_save_route<?php echo time(); ?> td{
		height:25px;
	}
#route-detail{
	color:black;
	background-color:#fff;
	width:300px;
	height:440px;
	z-index:1;
	border:1px solid #ccc;
	padding:5px;
}
#route_det td{
	height:20px;
	padding:2px;
}
	.error_route {
		border:1px solid red;
	}
	.not_err_route {
		border:1px solid black;
	}


#adp-placemark img, .adp-placemark img {
   display:none;
}
#adp-placemark {
   font-weight: bold;
   padding: 10px 10px 10px 30px;
   background: white url(../images/map_icons/number_1.png) no-repeat left center;
}
.adp-placemark {
   font-weight: bold;
   padding: 10px 10px 10px 30px;
   background: white url(../images/map_icons/number_2.png) no-repeat left center;
}	
</style>
<div id="history_div" style="display:none;">
<span id="countr" style="display:none;padding-right: 10px;"></span>
<form onsubmit="return searchhistorys()">
<table border="5" width="100%" class="formtable">
	<tr>
		<td></td>
		<td style='text-align:center;padding:0px;'><?php echo $this->lang->line("Start"); ?>&nbsp;</td>
		<td style='text-align:center;padding:0px;'><?php echo $this->lang->line("End"); ?>&nbsp;</td>
		<td style='text-align:center;padding:0px;'><?php echo $this->lang->line("Assets"); ?>&nbsp;</td>
		<td align="center" colspan="2" style='text-align:center;padding:0px;'><input id="clear_v_map_id_show_hide" onclick="show_hide_marker_of_line_history(this)" type="button" value="<?php echo $this->lang->line("Show Markers"); ?>" style='display:none'/>
        </td>
	</tr>
	<tr>
		<td><b><?php echo $this->lang->line("history"); ?>&nbsp;&nbsp;&nbsp;</b></td>
		<td style='text-align:center;padding:0px;'><input type="text" name="sdate" id="history_sdate" class="date text ui-widget-content ui-corner-all" style="width:180px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/>&nbsp;</td>
		<td style='text-align:center;padding:0px;'><input type="text" name="edate" id="history_edate" class="date text ui-widget-content ui-corner-all" style="width:180px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/>&nbsp;</td>
		<td style='text-align:center;padding:0px;'><select name="device" id="history_device" class="select ui-widget-content ui-corner-all"></select>&nbsp;</td>
		<td align="center" colspan="2" style="padding:0px;">&nbsp;&nbsp;&nbsp;<input id="v_map_id" onclick="viewOnMapHistory()" type="button" value="<?php echo $this->lang->line("map_view"); ?>"/><input id="clear_v_map_id" onclick="clear_line_route()" type="button" value="<?php echo $this->lang->line("Clear"); ?>"/>
        </td>
	</tr>
</table>
</form> 
</div>

<div id="map_route" style="width: 100%; height: 98%; position:relative;">
    <span style="color:Gray;"><?php echo $this->lang->line("Loading map"); ?>...</span>
  </div>
<div class="realContent" style=" position:absolute; top:55px;">
  <div id="route-detail" class="formtable ui-shadow ui-corner-bottom" style="display:none; z-index:4; position:absolute; left:0.7%; font-size:12px;">
	<input type="text" id="routeSrch" style="width:150px;" class="text ui-widget-content ui-corner-all">&nbsp;<input type="button" value="Search" onclick="loadRouteList();"><a style="padding-right:5px; cursor:pointer;float:right;" onclick="$('#route-detail').fadeOut(500)"><?php echo $this->lang->line("Hide"); ?></a>
	<div id="route_list" style="overflow:auto;padding-top:10px;">
		<!--overflow-x:hidden;table id="route_det">
			<tr><td><a style="cursor:pointer;" onclick="toggleDetails('1')"><img id="img_1" src="<?php echo base_url(); ?>/assets/style/css/images/add.png"></a></td><td><input type="checkbox" value="2"></td><td align="left">Ahmedabad-Udaipur</td><td>(Delete)</td></tr>
			<tr id="route_det_1" style="display:none;">
				<td></td>
				<td colspan="3">
					Distance Unit : KM, Alert Distance Value : 1 KM<br>
					Total Distance : 250 KM, Time : 4 Hours<br>
					<b>Assets</b> : GJ12 7777(2210), 2522, 2566, 2332, 2471, 2210, 2522, 2566, 2332, 2471, 2210, 2522, 2566, 2332, 2471, 2210, 2522, 2566, 2332, 2471, 2210, 2522, 2566, 2332, 2471, 2210, 2522, 2566, 2332, 2471
				</td>
			</tr>
		</table-->
		
	</div>
	
	<center><input type="button" value="<?php echo $this->lang->line("refresh"); ?>" onclick="loadRouteList();">&nbsp;&nbsp;<input type="button" value="<?php echo $this->lang->line("Load"); ?>" onclick="loadRoute()"></center>
  </div>
  
  <br/>
  <div id="dialog_route<?php echo time(); ?>" style="dispaly:none;">
		<?php echo $this->lang->line("Select Landmark to add a location_Min Two Times"); ?>
		<table>
			<tr>
				<td colspan="2">
					<select id="location<?php echo time(); ?>" style="width:400px;height:90px">
						<?php echo $landOpt; ?>
					</select>&nbsp;&nbsp;
					<input type="button" onclick="AddLocation()" value="<?php echo $this->lang->line("Add_location"); ?>"/><br>
				</td>
			</tr>
			<tr>
				<td>
					<input type="button" onclick="Undo()" value="<?php echo $this->lang->line("Undo"); ?>"/>
					<input type="button" onclick="ClearPolyLine()" value="<?php echo $this->lang->line("Clear"); ?>"/>
				</td>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" id="roundTrip<?php echo time(); ?>" /><label for="roundTrip"><?php echo $this->lang->line("Round_trip"); ?></label>
				</td>
				<td><?php echo $this->lang->line("Color"); ?> : <input type="text" name="color" id="route_color<?php echo time(); ?>" value="#ff0000" class="color-picker" size="6" autocomplete="on" maxlength="10" /></td>
				<!--td>If selected, your first location will be used as the end point of the journey</td-->
			</tr>
			
		</table>
		<input type="button" onclick="GetDirections()" value="<?php echo $this->lang->line("Get_directions"); ?>"/>&nbsp;<input id="saveBtn" style="display:none" type="button" onclick="open_dialog()" value="Save"/><br/>
    <span id="distance<?php echo time(); ?>"></span> <span id="duration<?php echo time(); ?>"></span>
  </div>
  <div id="dialog_save_route<?php echo time(); ?>" style="dispaly:none;">
		<table class="formtable" width="100%" cellspacing="4" cellpadding="4">
			<TR>
				<td width="50%" style="vertical-align:top;">
					<table cellspacing="4" cellpadding="4">
						<tr>
							<td valign="top" width="25%" style="padding-right:10px;"><?php echo $this->lang->line("Name"); ?> : <br><input class="text ui-widget-content ui-corner-all" type="text" id="route_name<?php echo time(); ?>"></td>
							<td valign="top" width="25%" style="padding-right:10px;"><?php echo $this->lang->line("Distance_Total"); ?> : <br><input class="text ui-widget-content ui-corner-all" type="text" id="total_distance<?php echo time(); ?>"></td>
						</tr>
						<tr>
							<td style="padding-right:10px;">
								<?php echo $this->lang->line("Time_minutes"); ?> : <input class="text ui-widget-content ui-corner-all" type="text" id="total_time_in_minutes<?php echo time(); ?>"><br>
							</td>
							<td><?php echo $this->lang->line("Alert_When_Distance"); ?> : <br> <div style="width:40%;padding-right:10px;float:left;"><input class="text ui-widget-content ui-corner-all" type="text" id="distance_value<?php echo time(); ?>"></div>	
							<div style="width:50%;float:right;"><select id='distance_unit<?php echo time(); ?>' class="select ui-widget-content ui-corner-all">
								<option>KM</option>
								<option>Mile</option>
								<option>Meter</option>
							</select></div></td>
							
						</tr>
						<tr>
							<td style="padding-right:10px;"><br><input type="checkbox" id="sms_alert<?php echo time(); ?>" /><label for="sms_alert<?php echo time(); ?>" checked="true"><?php echo $this->lang->line("Sms Alert"); ?></label></td>
							<td><br><input type="checkbox" id="email_alert<?php echo time(); ?>" /><label for="email_alert<?php echo time(); ?>" checked="true"><?php echo $this->lang->line("Email Alert"); ?> </label></td>
						</tr>
					</table>
				</td>
				<td width="30%">
					<?php echo $this->lang->line("Search"); ?> : <input class="not_err_route text ui-widget-content ui-corner-all" type="text" name="search_route" id="search_route" onKeyUp="searchComb_route('<?php echo $routeComb; ?>', this.value,'search_route')"  />
					<?php echo $this->lang->line("Assets"); ?> : <a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAllroute<?php echo time(); ?>('#<?php echo $routeComb; ?>')"><?php echo $this->lang->line("Select/Unselect All"); ?></a><br>
					<select class="select ui-widget-content ui-corner-all" id='<?php echo $routeComb; ?>' style='height:120px;' multiple='multiple'>
					<?php echo $deviceOpt; ?>
					</select>
				</td>
				
			</TR>
			
		</table>
		
		<input type="button" onclick="saveRoute()" value="Save Route"/><br/>
  </div>
  <div id="directions">
  </div>

  <!--script src="http://maps.google.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.min.js"></script-->

  <script type="text/javascript">
	var editDistance = 0;
	var editTime = 0;
  $(document).ready(function(){
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$("#history_device").html(assets_combo_opt);
	$("#history_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#history_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
  });
    /* <![CDATA[ */

var html_r_edit = '<input type="hidden" id="edit_id_route<?php echo time(); ?>" value=""><table class="formtable" width="100%" cellspacing="4" cellpadding="4">';
	
	html_r_edit += '<tr>';
	html_r_edit += '<td valign="top" width="25%" style="padding-right:10px;"><?php echo $this->lang->line("Name"); ?> : <br><input class="text ui-widget-content ui-corner-all" type="text" id="edit_route_name<?php echo time(); ?>"></td>';
	html_r_edit += '<td valign="top" width="25%" style="padding-right:10px;"><?php echo $this->lang->line("Distance_Total"); ?> : <br><input class="text ui-widget-content ui-corner-all" type="text" id="edit_total_distance<?php echo time(); ?>"></td>';
	html_r_edit += '</tr>';
	html_r_edit += '<tr>';
	html_r_edit += '<td>';
	html_r_edit += '<input type="checkbox" id="edit_roundTrip<?php echo time(); ?>" checked="true" /><label for="roundTrip"><?php echo $this->lang->line("Round_trip"); ?></label>';
	html_r_edit += '</td>';
	html_r_edit += '<td>Color : <input type="text" name="color" id="edit_route_color<?php echo time(); ?>" value="#ff0000" class="color-picker" size="6" autocomplete="on" maxlength="10" /></td>';
	html_r_edit += '</tr>';
	html_r_edit += '<tr>';
	html_r_edit += '<td style="padding-right:10px;">Time(minutes) : <input class="text ui-widget-content ui-corner-all" type="text" id="edit_total_time_in_minutes<?php echo time(); ?>"><br></td>';
	html_r_edit += '<td><?php echo $this->lang->line("Alert_When_Distance"); ?> : <br> <div style="width:40%;padding-right:10px;float:left;"><input class="text ui-widget-content ui-corner-all" type="text" id="edit_distance_value<?php echo time(); ?>"></div>';	
	html_r_edit += '<div style="width:50%;float:right;"><select id="edit_distance_unit<?php echo time(); ?>" class="select ui-widget-content ui-corner-all"><option>KM</option><option>Mile</option><option>Meter</option></select></div></td>';
							
	html_r_edit += '</tr>';
	html_r_edit += '<tr>';
	html_r_edit += '<td style="padding-right:10px;"><br><input type="checkbox" id="edit_route_sms_alert<?php echo time(); ?>" checked="true" /><label for="sms_alert<?php echo time(); ?>"><?php echo $this->lang->line("Sms Alert"); ?></label></td>';
	html_r_edit += '<td><br><input type="checkbox" id="edit_route_email_alert<?php echo time(); ?>"  checked="true" /><label for="email_alert<?php echo time(); ?>"><?php echo $this->lang->line("Email Alert"); ?></label></td>';
	html_r_edit += '</tr>';
	html_r_edit += '<tr>';
	html_r_edit += '<td colspan=2>';
	html_r_edit += '<?php echo $this->lang->line("Search"); ?> : <input class="not_err_route text ui-widget-content ui-corner-all" type="text" name="search_route" id="edit_search_route" onKeyUp="searchComb_route(\'edit_<?php echo $routeComb; ?>1\', this.value, \'edit_search_route\')"  />';
	html_r_edit += '<span><?php echo $this->lang->line("Assets"); ?>: <a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAllroute<?php echo time(); ?>(\'#edit_<?php echo $routeComb."1"; ?>\')"><?php echo $this->lang->line("Select/Unselect All"); ?></a></span> <br>';
	html_r_edit += '<select class="select ui-widget-content ui-corner-all" id="edit_<?php echo $routeComb."1"; ?>" style="height:120px;" multiple="multiple">'
	html_r_edit += "<?php echo $deviceOpt; ?>";
	html_r_edit += '</select>';
	html_r_edit += '</td>';
	html_r_edit += '</TR>';
	html_r_edit += '<TR>';
	html_r_edit += '<td><input type="button" onclick="updateRoute()" value="<?php echo $this->lang->line("Save"); ?>"/></td><td><input type="button" onclick="deleteRoute()" value="<?php echo $this->lang->line("delete"); ?>"/></td>';
	html_r_edit += '</TR>';
	
	html_r_edit += '</table>';
	
	var options_route = [];
/* all points start */
	var route_poly = [];
	var directionsService_route ;
	var latArr =  new Array();
	var lngArr =  new Array();
	var htmlArr =  new Array();
	var allpointBounds_route;

var polylinesmapAllpoint = [];
var polylineCoordsmapAllpoint = [];
var mapOptionsmapAllpoint;
var totalDir=0;
var totalDir_count=0;
var Timer_counter=500;
var Poin_Cntr=0;
var current_all_p;
var percentage_all_p;
var val_all_p;
var distance_all_p=0;
var distance_all_total=0;
var directionsDisplayAllpoint = [];
var rendererOptionsAllpoint = {
					preserveViewport: true,
					draggable: false,
					suppressMarkers: true,
					polylineOptions: {
					   map: map,
					   strokeColor:'#FF0000',
					   //strokeWidth: 3,
					   strokeOpacity: 0.7}

			};
var wayptsAllpoint = [];

var arrowMarkerAllpoint = [];
var mcOptionsAllpoint = {gridSize: 50, maxZoom: 15};
var markerClusterAllpoint;

var allpointBounds_route;
var markersmapAllpoint  = [];
/* all points */
function selectAllroute<?php echo time(); ?>(id){
	if($(id+" option:selected").length == $(id+" option").length){
		$(id+" option").removeAttr('selected');
	}else{
		$(id+" option").attr('selected', 'selected');
	}
}
var routeEdited = false;
function editRoute(id){
	//$("#loading_dialog").dialog('open');
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>index.php/home/edit_route", { id: id },
	 function(data) {
		
		
	$(".color-picker").miniColors({
					letterCase: 'uppercase',
		});
		
				
		$("#dialog_edit_route<?php echo time(); ?>").html(html_r_edit);
		
		$("#dialog_edit_route<?php echo time(); ?>").dialog("open");
		jQuery("input:button, input:submit, input:reset").button();
		
		$("#edit_id_route<?php echo time(); ?>").val(data.data.id);
		$("#edit_route_name<?php echo time(); ?>").val(data.data.routename);
		$("#edit_total_distance<?php echo time(); ?>").val(data.data.total_distance);
		$("#edit_route_color<?php echo time(); ?>").val(data.data.route_color);
		$("#edit_total_time_in_minutes<?php echo time(); ?>").val(data.data.total_time_in_minutes);
		$("#edit_distance_value<?php echo time(); ?>").val(data.data.distance_value);
		$("#edit_distance_unit<?php echo time(); ?>").val(data.data.distance_unit);
				
		var devc = data.data.deviceid;
		devc = devc.split(",");
		$("#edit_route_device<?php echo time(); ?>1").val(devc);
		
		if(data.data.sms_alert == 0)
			$("#edit_route_sms_alert<?php echo time(); ?>").attr("checked", false);
		if(data.data.email_alert == 0)
			$("#edit_route_email_alert<?php echo time(); ?>").attr("checked", false);
		if(data.data.round_trip == 0)
			$("#edit_roundTrip<?php echo time(); ?>").attr("checked", false);		
		
		//$("#loading_dialog").dialog('close');
		$("#loading_top").css("display","none");
		
		for(i=0; i<dDisplay.length; i++){
			dDisplay[i].setMap(null);
		}
		dDisplay = [];
			path = [];
			//draw route
			points = [];
			clearMarkers();
			clearLandmarkMarkers();
			ClearRouteDetails();
			ClearAllRouteDetails();
			for(i=0; i<data.coords.length; i++){
				var rId = data.coords[i].id;
				var rName = data.coords[i].routename;
				var rColor = data.coords[i].route_color;
				var rStart = data.coords[i].start_point.split(",");
				var rEnd = data.coords[i].end_point.split(",");
				var rWaypoints = data.coords[i].waypoints;
								
				var start = new google.maps.LatLng(parseFloat(rStart[0]), parseFloat(rStart[1]));
				var end = new google.maps.LatLng(parseFloat(rEnd[0]), parseFloat(rEnd[1]));
				var waypts = [];
				if(rWaypoints != "" && rWaypoints != null){
					rWaypoints = rWaypoints.split(":");
				}else{
					rWaypoints = [];
				}
				
				/*for(j=0; j<data.landmarksRoute[rId].length; j++){
					rWaypoints.push(data.landmarksRoute[rId][j].lat+','+data.landmarksRoute[rId][j].lng);
				}*/
				wpts = [];
				for (var k=0; k<rWaypoints.length; k++) {
				  wpts = rWaypoints[k].split(",");
				  waypts.push({location:new google.maps.LatLng(parseFloat(wpts[0]), parseFloat(wpts[1])),'stopover':false});
				}
				
				drawRouteEdit(start, end, waypts, rColor, i, path);
			}
			setTimeout(function(){
				$("#edit_total_distance<?php echo time(); ?>").val(editDistance);
				$("#edit_total_time_in_minutes<?php echo time(); ?>").val(editTime);
				}, 5000);
			for(i=0; i<data.landmarks.length; i++){
				var text = data.landmarks[i].name+"<br>";
				text += data.landmarks[i].address+"<br>";
			
				var point = new google.maps.LatLng(data.landmarks[i].lat, data.landmarks[i].lng);		
				landmarkMarkersRoute.push(createMarkerRoute(map, point, data.landmarks[i].name, text, data.landmarks[i].icon_path, '', "sidebar_map", '' ));
				//DrawCircle(point, '<?php /*echo $coord->radius;*/ ?>', mapmap);				
			}
			routeEdited = true;

	}, 'json');
}
function updateRoute()
{
	var str = [];
	
	for(s=0; s<dDisplay.length;s++){
		var w=[],wp;
		var distance = 0;
		var rleg = dDisplay[s].directions.routes[0].legs[0];
		data.start = {'lat': rleg.start_location.lat(), 'lng':rleg.start_location.lng()}
		data.end = {'lat': rleg.end_location.lat(), 'lng':rleg.end_location.lng()}
		var wp = rleg.via_waypoints
		
		for(var i=0;i<wp.length;i++)w[i] = [wp[i].lat(),wp[i].lng()]
		data.waypoints = w;
		//alert(w);
		var k=0;
		for(i=0;i<dDisplay[s].directions.routes[0].legs[0].steps.length;i++)
		{
			for(j=0;j<dDisplay[s].directions.routes[0].legs[0].steps[i].path.length;j++)
			{
				dPoints[k] = [dDisplay[s].directions.routes[0].legs[0].steps[i].path[j].lat(),dDisplay[s].directions.routes[0].legs[0].steps[i].path[j].lng()];
				k++;
			}
		}
		var theRoute = dDisplay[s].directions.routes[0];
		for (var r=0; r<theRoute.legs.length; r++) {
		  var theLeg = theRoute.legs[r];
		  distance += theLeg.distance.value;
		}
		distance = Math.round(distance/1000);
		data.points = dPoints;
		data.distance = distance;
		str.push(JSON.stringify(data));
	}
	//return false;
	var route_id = $("#edit_id_route<?php echo time(); ?>").val();
    var route_name = $("#edit_route_name<?php echo time(); ?>").val();
	var route_device = $("#edit_<?php echo $routeComb."1"; ?>").val();
	if(route_device == null)
		route_device = "";
	
	var route_color = $("#edit_route_color<?php echo time(); ?>").val();
	var distance_value = $("#edit_distance_value<?php echo time(); ?>").val();
	var distance_unit = $("#edit_distance_unit<?php echo time(); ?>").val();
	
	if($("#edit_route_sms_alert<?php echo time(); ?>").attr('checked') == "checked"){
		sms_alert = 1;
	}else{
		sms_alert = 0
	}
	
	if($("#edit_route_email_alert<?php echo time(); ?>").attr('checked') == "checked"){
		email_alert = 1;
	}else{
		email_alert = 0
	}
	
	if($("#edit_roundTrip<?php echo time(); ?>").attr('checked') == "checked"){
		roundTrip = 1;
	}else{
		roundTrip = 0
	}
	
	if(route_name == ""){
		alert("<?php echo $this->lang->line("Please insert route name"); ?>");
		$("#edit_route_name<?php echo time(); ?>").focus();
		//$("#route_name<?php echo time(); ?>").css("background", "#ff0000");
		return false
	}
	if(distance_value == ""){
		alert("<?php echo $this->lang->line("Please insert distance value"); ?>");
		$("#edit_distance_value<?php echo time(); ?>").focus();
		//$("#route_name<?php echo time(); ?>").css("background", "#ff0000");
		return false
	}
	
	var total_time_in_minutes = $("#edit_total_time_in_minutes<?php echo time(); ?>").val();
	var total_distance = $("#edit_total_distance<?php echo time(); ?>").val();
	$("#loading_top").css("display","block");
	$.post( 
	   "<?php echo site_url('home/updateRoute'); ?>", {'str':str, 'id':route_id, 'route_name': route_name, 'route_device':route_device, 'route_color':route_color, 'distance_value':distance_value, 'distance_unit':distance_unit, 'sms_alert':sms_alert, 'email_alert':email_alert, 'roundTrip':roundTrip, 'total_time_in_minutes':total_time_in_minutes, 'total_distance':total_distance},
	   function(data){
			$("#loading_top").css("display","none");
			$("#dialog_edit_route<?php echo time(); ?>").dialog('close');
			
			$("#alert_dialog").html("<?php echo $this->lang->line("Route Saved Successfully"); ?>");
			$("#alert_dialog").dialog('open');
			loadRouteList(data);
			ClearPolyLine();
	});
}	
function toggleDetails(id){
	//$("#route_det_"+id).slideToggle();
	if($("#route_det_"+id).css('display') == "none"){
		$("#route_det_"+id).show();
		$("#img_"+id).attr('src', '<?php echo base_url(); ?>assets/style/css/images/close.png');
	}else{
		$("#route_det_"+id).hide();
		$("#img_"+id).attr('src', '<?php echo base_url(); ?>assets/style/css/images/add.png');
	}
}
function TrackControl(controlDiv, mapmap) {
<?php
	if(in_array('Create Route',$data)){
	?>
  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = '<?php echo $this->lang->line("Click to Create Route"); ?>';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '20px';
  areaText.style.paddingTop = '3px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = '<?php echo $this->lang->line("Create_Route"); ?>';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	$("#dialog_route<?php echo time(); ?>").dialog('open');
  });
  <?php } ?>
}

//new window button
function routeListBtn(controlDiv, mapmap) {
<?php
	if(in_array('List',$data)){
	?>
  controlDiv.style.padding = '5px';
	 
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to view List"); ?>';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.height = '20px';
  controlText.style.paddingTop = '3px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = '<?php echo $this->lang->line("List"); ?>';
  controlUI.appendChild(controlText);
  
  google.maps.event.addDomListener(controlUI, 'click', function() {
	$('#route-detail').slideToggle();
  });
  <?php } ?>
}
function TrackControl_H(controlDiv, mapmap) {
<?php
	if(in_array('History',$data)){
	?>
  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = 'History';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var seachText = document.createElement('DIV');
  seachText.style.fontFamily = 'Arial,sans-serif';
  seachText.style.fontSize = '12px';
  seachText.style.height = '20px';
  seachText.style.paddingTop = '3px';
  seachText.style.paddingLeft = '4px';
  seachText.style.paddingRight = '4px';
  seachText.innerHTML = 'History';
  control_UI.appendChild(seachText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	if($("#history_div").css('display') == 'block'){
		$("#history_div").css('display', 'none');
	}else{
		$("#history_div").css('display', 'block');
	}	
  });
  <?php } ?>
}      
	  var landmarkArr = [];
	  var landmarkTextArr = [];
	  var latlng = new google.maps.LatLng(22.296024, 70.785540);
      var options = {
        zoom: 7,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        draggableCursor: "crosshair"
      };
	 
	directionsService = new google.maps.DirectionsService();
	var map = new google.maps.Map(document.getElementById("map_route"), options);
	map.enableKeyDragZoom();
	
	var trackControlDiv = document.createElement('DIV');
	var trackControl = new TrackControl(trackControlDiv, map);
	trackControlDiv.index = 1;
	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
		
	//new window btn		
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new routeListBtn(trackNewWindowDiv, map);
	trackNewWindowDiv.index = 1;	
	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	//new window btn		
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new TrackControl_H(trackNewWindowDiv, map);
	trackNewWindowDiv.index = 1;	
	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
		
	  var points = [];
      var markers = [];
	  var landmarkMarkersRoute = [];
		google.maps.event.addListener(map,"click", function(location)
		{
			//GetLocationInfo(location.latLng);
		});
	  
	  var data = {};	
      var directionsDisplay = new google.maps.DirectionsRenderer({'draggable':true, suppressMarkers : true});
	  
		//directionsDisplay.suppressMarkers = true;
			/* autocomplete
			var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'), {});
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();
				GetLocationInfo(place.geometry.location);
        map.setCenter(place.geometry.location);
        $("#location").val("");
		
			});
		
		<?php	
		if(count($coords) > 0) {
			foreach ($coords as $coord) {
				$text = $coord->name."<br>";
				$text .= $coord->address."<br>";
		?>				
				var point = new google.maps.LatLng(<?php echo floatval($coord->lat); ?>, <?php echo floatval($coord->lng); ?>);		
				createMarkerRoute(map, point, "<?php echo $coord->name; ?>","<?php echo $text; ?>", '<?php echo $coord->icon_path; ?>', '', "sidebar_map", '' );
				//DrawCircle(point, '<?php echo $coord->radius; ?>', mapmap);
			
		<?php			
			} // End For Loop
		}
	?>
		*/
      function AddLocation(){
        /*var localSearch = new GlocalSearch();
        localSearch.setSearchCompleteCallback(null,
          function()
          {
            if (localSearch.results[0])
            {
              var results = localSearch.results[0];
              var latLong = new google.maps.LatLng(results.lat, results.lng);

              GetLocationInfo(latLong);
              map.setCenter(latLong);
              $("#location").val("");
            }
            else
            {
              alert("Location not found");
            }
          });

        localSearch.execute(document.getElementById("location").value);
		*/
		pt = $("#location<?php echo time(); ?>").val();
		landmarkTextArr.push($("#location<?php echo time(); ?> option:selected").text());
		pt = pt.split(",");
		//alert(pt[1]);
		//alert(pt[2]);
		//alert(pt[0]);
		point = new google.maps.LatLng(parseFloat(pt[1]), parseFloat(pt[2]));
		GetLocationInfo(point);
		landmarkArr.push(pt[0])
      }

      function GetLocationInfo(latlng)
      {
        if (latlng != null)
        {
          points.push(latlng);
          BuildPoints();
        }
      }

      function clearMarkers(){
        for (var i=0; i<markers.length; i++)
        {
          markers[i].setMap(null);
        }
        markers = [];
      }

      function BuildPoints()
      {
        clearMarkers();

        // add to the code
        for (var i=0; i<points.length; i++)
        {
          var icon = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + (i + 1) + "|FF0000|000000";
		  var marker = new google.maps.Marker({position: points[i], icon:icon, title:landmarkTextArr[i]});
          markers.push(marker);
          marker.setMap(map);
        }
      }

      function ClearPolyLine()
      {
        landmarkArr = [];
		landmarkTextArr = [];
		points = [];
        clearMarkers();
        ClearRouteDetails();
      }

      function ClearRouteDetails()
      {
		directionsDisplay.setMap(null);
        directionsDisplay.setPanel(null);
        $("#distance<?php echo time(); ?>").html("");
        $("#duration<?php echo time(); ?>").html("");
      }
		
      function Undo()
      {
		landmarkArr.pop();
		landmarkTextArr.pop();
        points.pop();
        BuildPoints();
        ClearRouteDetails();
      }

      function GetDirections()
      {
        var directionsDiv = document.getElementById("directions");
        directionsDiv.innerHTML = "<?php echo $this->lang->line("Loading"); ?>...";

        var directions = new google.maps.DirectionsService();
        directionsDisplay.setMap(map);
        //directionsDisplay.setPanel(directionsDiv);

        // build array of waypoints (excluding start and end)
        var waypts = [];
        var end = points.length-1;
        var dest = points[points.length-1];
        if (document.getElementById("roundTrip<?php echo time(); ?>").checked) {
          end = points.length;
          dest = points[0];
        }
        for (var i=1; i<end; i++) {
          waypts.push({location:points[i]});
        }

		var travelMode = google.maps.DirectionsTravelMode.DRIVING;
				
        var optimiseRoute = true;
        var request = {
          origin: points[0],
          destination: dest,
          waypoints: waypts,
          travelMode: travelMode,
          optimizeWaypoints: optimiseRoute
        };
        directions.route(request, function(result, status) {
          $("#saveBtn").show();
		  if (status == google.maps.DirectionsStatus.OK) {
			$("#dialog_save_route<?php echo time(); ?>").dialog('open');
			directionsDiv.innerHTML = "";
            directionsDisplay.setDirections(result);
						
            // calculate total distance and duration
            var distance = 0;
            var time = 0;
            var theRoute = result.routes[0];
            for (var i=0; i<theRoute.legs.length; i++) {
              var theLeg = theRoute.legs[i];
              distance += theLeg.distance.value;
              time += theLeg.duration.value;
            }
            $("#distance<?php echo time(); ?>").html("<?php echo $this->lang->line("Total distance"); ?>: " +
              Math.round(distance/1000) + "km (" +
              Math.round((distance*0.621371192)/1000) + " miles), ");
            $("#duration<?php echo time(); ?>").html("<?php echo $this->lang->line("total duration"); ?>: " +
              Math.round(time/60) + " minutes");
			  
			$("#total_distance<?php echo time(); ?>").val(Math.round(distance/1000));
            $("#total_time_in_minutes<?php echo time(); ?>").val(Math.round(time/60));
          }
          else {
            var statusText = getDirectionStatusText(status);
            directionsDiv.innerHTML = "<?php echo $this->lang->line("An error occurred"); ?> - " + statusText;
          }
        });
      }

var dPoints = [];
function saveRoute()
{
    var w=[],wp;
    var rleg = directionsDisplay.directions.routes[0].legs[0];
    data.start = {'lat': rleg.start_location.lat(), 'lng':rleg.start_location.lng()}
    data.end = {'lat': rleg.end_location.lat(), 'lng':rleg.end_location.lng()}
    var wp = rleg.via_waypoints

	for(var i=0;i<wp.length;i++)w[i] = [wp[i].lat(),wp[i].lng()]
    data.waypoints = w;
	var k=0;
	for(i=0;i<directionsDisplay.directions.routes[0].legs[0].steps.length;i++)
	{
		for(j=0;j<directionsDisplay.directions.routes[0].legs[0].steps[i].path.length;j++)
		{
			dPoints[k] = [directionsDisplay.directions.routes[0].legs[0].steps[i].path[j].lat(),directionsDisplay.directions.routes[0].legs[0].steps[i].path[j].lng()];
			k++;
		}
	}
	data.points = dPoints
    var str = JSON.stringify(data);
    var route_name = $("#route_name<?php echo time(); ?>").val();
	var route_device = $("#<?php echo $routeComb; ?>").val();
	if(route_device == null)
		route_device = "";
	
	var route_color = $("#route_color<?php echo time(); ?>").val();
	var distance_value = $("#distance_value<?php echo time(); ?>").val();
	var distance_unit = $("#distance_unit<?php echo time(); ?>").val();
	
	if($("#sms_alert<?php echo time(); ?>").attr('checked') == "checked"){
		sms_alert = 1;
	}else{
		sms_alert = 0
	}
	
	if($("#email_alert<?php echo time(); ?>").attr('checked') == "checked"){
		email_alert = 1;
	}else{
		email_alert = 0
	}
	
	if($("#roundTrip<?php echo time(); ?>").attr('checked') == "checked"){
		roundTrip = 1;
	}else{
		roundTrip = 0
	}
	
	if(route_name == ""){
		alert("<?php echo $this->lang->line("Please insert route name"); ?>");
		$("#route_name<?php echo time(); ?>").focus();
		//$("#route_name<?php echo time(); ?>").css("background", "#ff0000");
		return false
	}
	if(distance_value == ""){
		alert("Please insert distance value<?php echo $this->lang->line("Please insert distance value"); ?>");
		$("#distance_value<?php echo time(); ?>").focus();
		//$("#route_name<?php echo time(); ?>").css("background", "#ff0000");
		return false
	}
	var landmarkStr = landmarkArr.join(",");
	var total_time_in_minutes = $("#total_time_in_minutes<?php echo time(); ?>").val();
	var total_distance = $("#total_distance<?php echo time(); ?>").val();
	$("#loading_top").css("display","block");
	$.post( 
	   "<?php echo site_url('home/saveRoute'); ?>", { 'str': str, 'route_name': route_name, 'route_device':route_device, 'route_color':route_color, 'distance_value':distance_value, 'distance_unit':distance_unit, 'sms_alert':sms_alert, 'email_alert':email_alert, 'roundTrip':roundTrip, 'total_time_in_minutes':total_time_in_minutes, 'total_distance':total_distance,'landmarks':landmarkStr},
	   function(data){
			$("#loading_top").css("display","none");
			$("#dialog_route<?php echo time(); ?>").dialog('close');
			$("#dialog_save_route<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("<?php echo $this->lang->line("Route Created Successfully"); ?>");
			$("#alert_dialog").dialog('open');
			loadRouteList(data);
			ClearPolyLine();
	});
}

function searchComb_route(id, val, sid)
{	//alert(val);
	var search_route = $.trim(val);
    var regex = new RegExp(search_route,"gi");
	
	for(i=0;i<options_route.length;i++)
	{
		var option = options_route[i];
		if(option.text.match(regex) !== null) {
			if ($('#'+id+' option:contains('+option.text+')').attr('selected')) {
				$('#'+id+' option:contains('+option.text+')').attr('selected', false);
				$('#'+id+' option:contains('+option.text+')').attr('selected', 'selected');
			} else {
				$('#'+id+' option:contains('+option.text+')').attr('selected', 'selected');
				$('#'+id+' option:contains('+option.text+')').attr('selected', false);
			}
			$("#"+sid).removeClass("error_route");
			$("#"+sid).addClass("not_err_route");
			break;
		}
		else
		{
			$("#search_route").removeClass("not_err_route");
			$("#search_route").addClass("error_route");
		}
	}
}
function deleteRoute(){
	$("#trip_confirm_dialog<?php echo time(); ?>").dialog('open');
}
function confirmDeleteTrip(){
	var route_id = $("#edit_id_route<?php echo time(); ?>").val();
	$("#loading_top").css("display","block");
	$.post( 
	   "<?php echo site_url('home/deleteRoute'); ?>",{'id':route_id},
	   function(data){
			$("#loading_top").css("display","none");
			$("#dialog_edit_route<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html(data);
			$("#alert_dialog").dialog('open');
			loadRouteList();
	});
}
function loadRouteList(id){
	var routeSrch = $("#routeSrch").val();
	$("#loading_top").css("display","block");
	$.post( 
	   "<?php echo site_url('home/loadRouteList'); ?>",
	   {'routeSrch' : routeSrch},
	   function(data){
			$("#loading_top").css("display","none");
			$('#route_list').html(data);
			if(id != "")
				$('input[value='+id+']').attr('checked', true);
			//loadRoute();
	});
}

var slArr = [];
var elArr = [];
var wayPointsArr = [];
var clrArr = [];
function loadRoute(){
	var route_ids = $("#route_det input:checked").map(
		function () {return this.value;}).get().join(",");
	
	$("#loading_top").css("display","block");
	
	$.post( 
	   "<?php echo site_url('home/loadRoute'); ?>",{'route_ids':route_ids},
	   function(data){
			
			clearMarkers();
			clearLandmarkMarkers();
			ClearRouteDetails();
			ClearAllRouteDetails();
			ClearAllRouteDetailsNew();
			slArr = [];
			elArr = [];
			wayPointsArr = [];
			clrArr = [];
			var sss = 0;
			for(s=0; s<data.coords.length; s++){
				
				for(i=0; i<data.coords[s].length; i++){
				
					var rId = data.coords[s][i].id;
					var rName = data.coords[s][i].routename;
					var rColor = data.coords[s][i].route_color;
					var rStart = data.coords[s][i].start_point.split(",");
					var rEnd = data.coords[s][i].end_point.split(",");
					var rWaypoints = data.coords[s][i].waypoints;
					var rPoints = data.coords[s][i].points;
					
					var start = new google.maps.LatLng(parseFloat(rStart[0]), parseFloat(rStart[1]));
					if(data.coords[s][i].round_trip == 1){
						var end = start;
					}else{
						var end = new google.maps.LatLng(parseFloat(rEnd[0]), parseFloat(rEnd[1]));
					}
					var waypts = [];
					if(rWaypoints != "" && rWaypoints != null){
						rWaypoints = rWaypoints.split(":");
					}else{
						rWaypoints = [];
					}
					/*
					for(j=0; j<data.landmarksRoute[rId].length; j++){
						rWaypoints.push(data.landmarksRoute[rId][j].lat+','+data.landmarksRoute[rId][j].lng);
					}*/
					wpts = [];
					for (var k=0; k<rWaypoints.length; k++) {
					  wpts = rWaypoints[k].split(",");
					  waypts.push({location:new google.maps.LatLng(parseFloat(wpts[0]), parseFloat(wpts[1]))});
					}
					
					//drawRoute(start, end, waypts, rColor, sss);
					/*gDirRequest(directionsService, waypts, function drawGDirLine(path) {
						var line = new google.maps.Polyline({strokeColor: rColor,clickable:false,map:map,path:path});
						routePolyArr.push(line);
					});
					*/
					slArr[sss] = start;
					elArr[sss] = end;
					wayPointsArr[sss] = waypts;
					clrArr[sss] = rColor;
					
					sss++;
				}
				
			}
			for(i=0; i<data.landmarks.length; i++){
				var text = data.landmarks[i].name+"<br>";
				text += data.landmarks[i].address+"<br>";
			
				var point = new google.maps.LatLng(data.landmarks[i].lat, data.landmarks[i].lng);		
				landmarkMarkersRoute.push(createMarkerRoute(map, point, data.landmarks[i].name, text, data.landmarks[i].icon_path, '', "sidebar_map", '' ));
				//DrawCircle(point, '<?php /*echo $coord->radius;*/ ?>', mapmap);				
			}
			drawRoute();
			$("#loading_top").css("display","none");
			
	},'json');
}

var dDisplay = [];
var routePolyArr = [];
var plotDirection = 0;
var historyPolyArr = [];
//function drawRoute(s1, e1, wp1, color, i, path){
function drawRoute(){		
		
		if(plotDirection < slArr.length){
			i = plotDirection;
			s1 = slArr[i];
			e1 = elArr[i];
			wp1 = wayPointsArr[i];
			color = clrArr[i];
			
			var polylineOptionsActual = new google.maps.Polyline({
				strokeColor: color,
				strokeOpacity: 1.0,
				strokeWeight: 4
				});

			dDisplay2 = new google.maps.DirectionsRenderer({polylineOptions: polylineOptionsActual});
			//dDisplay[i] = new google.maps.DirectionsRenderer({'draggable':true, suppressMarkers : true});
			//dDisplay[i].suppressMarkers = true;
			//dDisplay1.preserveViewport = true;
			dDisplay2.setMap(map);
			var request1 = {
				origin:s1, 
				destination:e1,
				waypoints: wp1,
				optimizeWaypoints: true,
				/*avoidHighways: true,
				avoidTolls: true,*/
				provideRouteAlternatives: false,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			directionsService.route(request1, function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) 
				{
					////dDisplay[i].setDirections(response);				
					//alert(response.routes[0].toSource());
					var stepss = response.routes[0].legs;
					//alert(steps.length);
					var path = response.routes[0].overview_path;
					for(var step = 0; step < stepss.length; step++)
					{
						stp = stepss[step].steps;
						for(var ss = 0; ss < stp.length; ss++)
						{	
							//alert(stp[ss].path);
							//return false;
							//alert(steps[step].lat_lngs);
							polylineOptions = {
									map: map,
									strokeColor: color,
									strokeOpacity: 0.7,
									strokeWeight: 4,
									path: stp[ss].path
									//path: path,
							}
							routePolyArr.push(new google.maps.Polyline(polylineOptions));
						}
					}
					/*polylineOptions = {
							map: map,
							strokeColor: color,
							strokeOpacity: 0.7,
							strokeWeight: 4,
							//path: stp[ss].path,
							path: path,
					}
					routePolyArr.push(new google.maps.Polyline(polylineOptions));
					*/
					setTimeout("drawRoute()", 200);
					//drawRoute();
					plotDirection++;
				}
				else {
					//var statusText = getDirectionStatusText(status);
					alert("<?php echo $this->lang->line("An error occurred"); ?> - " + status);
				  }
				  
			});	
			
		}else{
			plotDirection = 0;
		}
  }

function drawRouteEdit(s1, e1, wp1, color, i, path){		
		
		dDisplay[i] = new google.maps.DirectionsRenderer({'draggable':true});
		//suppressMarkers : true, 'preserveViewport': true 
		//http://maps.gstatic.com/mapfiles/markers2/marker_greenB.png
		dDisplay[i].setMap(map);
		google.maps.event.addListener(dDisplay[i], 'directions_changed', function() {
			computeTotalDistance();
		});
		var request1 = {
			origin:s1, 
			destination:e1,
			waypoints: wp1,
			optimizeWaypoints: true,
			/*avoidHighways: true,
			avoidTolls: true,*/
			provideRouteAlternatives: false,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request1, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				dDisplay[i].setDirections(response);
				// calculate total distance and duration
				var distance = 0;
				var time = 0;
				var theRoute = response.routes[0];
				for (var r=0; r<theRoute.legs.length; r++) {
				  var theLeg = theRoute.legs[r];
				  distance += theLeg.distance.value;
				  time += theLeg.duration.value;
				}
				editDistance += Math.round(distance/1000);
				editTime += Math.round(time/60);
				/*
				var rleg = dDisplay[i].directions.routes[0].legs[0];
		
				var wp = rleg.via_waypoints
		
				for(var h=0;h<wp.length;h++){
					var pos = new google.maps.LatLng(wp[h].lat(), wp[h].lng());
					addMarker(pos);
				}
				*/
				setTimeout(function(){
				$("img[src$='http://maps.gstatic.com/mapfiles/markers2/marker_greenB.png']").parent().remove();
				$("img[src$='http://maps.gstatic.com/mapfiles/markers2/marker_greenA.png']").parent().remove();
				$("img[src$='http://maps.gstatic.com/mapfiles/markers2/marker_sprite.png']").parent().remove();
				
				}, 3000);
				
			}
			else {
				//var statusText = getDirectionStatusText(status);
				alert("<?php echo $this->lang->line("An error occurred"); ?> - " + status);
			  }
			  
		});	
  } 
  /*
function addMarker(pos){
	var marker = new google.maps.Marker({
	  position: pos, 
	  map: map, 
	  draggable:true,
	  icon: '<?php echo base_url(); ?>assets/marker-images/cyanblank.png'
	});
}
*/
function computeTotalDistance(){
	var distance = 0;
	var time = 0;
	for (g in dDisplay) {
		 var myroute = dDisplay[g].directions.routes[0];
		for (i = 0; i < myroute.legs.length; i++) {
			distance += myroute.legs[i].distance.value;
			time += myroute.legs[i].duration.value;
		}
	}	
	distance = Math.round(distance/1000);
	time = Math.round(time/60);
	$("#edit_total_distance<?php echo time(); ?>").val(distance);
	$("#edit_total_time_in_minutes<?php echo time(); ?>").val(time);

}
function clearLandmarkMarkers(){
        for (var i=0; i<landmarkMarkersRoute.length; i++)
        {
          landmarkMarkersRoute[i].setMap(null);
        }
        landmarkMarkersRoute = [];
      }
function ClearAllRouteDetails()
{
	for(i=0; i<routePolyArr.length; i++){
		routePolyArr[i].setMap(null);
		//routePolyArr[i].setPanel(null);
	}
	routePolyArr = [];
}
function ClearHistoryDetails()
{
	for(i=0; i<historyPolyArr.length; i++){
		historyPolyArr[i].setMap(null);		
	}
	historyPolyArr = [];
}
function ClearAllRouteDetailsNew()
{
	
	if(routeEdited == true){
		for (i in dDisplay) {
		  
		  dDisplay[i].setMap(null);
		}
	}
	dDisplay = [];
}

function open_dialog(){
	$("#dialog_save_route<?php echo time(); ?>").dialog('open');
}
function createMarkerRoute(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>"+icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		
		var infoBubble = new InfoBubble({
          map: map,
		  content:html,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 2,
		  minWidth : 200
        });

		google.maps.event.addListener(new_marker, 'click', function() {
			update_timeout = setTimeout(function(){
				if (!infoBubble.isOpen()) {
					//infoBubble.setContent(html);
					infoBubble.open(map, new_marker);
				}
			}, 200);
				
		});
		google.maps.event.addListener(new_marker, 'dblclick', function() {
			dArr.push(point);
			  if(dArr.length == 2){
					calcRoute(dArr[0], dArr[1]);
					dArr = [];
			  }
			  if(dArr.length == 1 && directionsDisplay != undefined){
				clearDirection();
				}
			 clearTimeout(update_timeout);
		});
		
		if(openInfo == true) {
			infoBubble.open(map,new_marker);
		}
		
		if(openers != ''&&!isEmpty(openers)){
		   for(var i in openers){
			 var opener = document.getElementById(openers[i]);
			 opener.onclick = function(){infoBubble.open(map,new_marker); return false};
		   }
		}
		
		
		if(sidebar_id != ''){
			var sidebar = document.getElementById(sidebar_id);
			if(sidebar!=null && sidebar!=undefined && title!=null && title!=''){
				var newlink = document.createElement('a');
				newlink.onclick=function(){infoBubble.open(map,new_marker); return false};
				newlink.innerHTML = title;
				sidebar.appendChild(newlink);
			}
		}
	}
	return new_marker;  
}

$(document).ready(function () {
	options_route	= [];
		 $("#<?php echo $routeComb; ?>").find('option').each(function() {
                options_route.push({value: $(this).val(), text: $(this).text()});
            });	
	$("#location<?php echo time(); ?>").msDropDown();	
	//$(".ddChild").width(150);
	$(".ddTitle").height(22);
	//$(".ddTitle").width(150);
	$(".ddTitle img").css('height', '22px');
	
	$("#dialog_route<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		width:600,
		position:['right',0],
		title:'<?php echo $this->lang->line("Create Trip"); ?>'
	});
	$("#dialog_save_route<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		width:600,
		position:['right',0],
		title:'<?php echo $this->lang->line("Save Trip"); ?>'
	});
	
	jQuery("input:button, input:submit, input:reset").button();
	
	$(".color-picker").miniColors({
		letterCase: 'uppercase',
		change: function(hex, rgb) {
			/*var polylineOptionsActual = new google.maps.Polyline({
			strokeColor: hex,
			strokeOpacity: 1.0,
			strokeWeight: 4
			});

			directionsDisplay = new google.maps.DirectionsRenderer({polylineOptions: polylineOptionsActual,'draggable':true});
			*/
		}
	});	
	//$( "#route-detail" ).resizable();
	loadRouteList();
	loadRoute();
});
$(document).ready(function () {				

	
	$("#dialog_edit_route<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		position:['right',50],
		title:'<?php echo $this->lang->line("Route Edit"); ?>'
	});
	$("#trip_confirm_dialog<?php echo time(); ?>").dialog({
		autoOpen: false,
      buttons : {
        "Confirm" : function() {
          confirmDeleteTrip();
		  $(this).dialog("close");
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });
	$(".ddChild").css("height","90px");
});
var pointNewArr = [];
function viewOnMapHistory(){
	allpointBounds_route = new google.maps.LatLngBounds();
	directionsService_route = new google.maps.DirectionsService();
	var device = $('#history_device').val();
	if(device == ""){
		$("#alert-dialog-history").html("<?php echo $this->lang->line("Please select device"); ?>");
		$("#alert-dialog-history").dialog("open");
		return false;
	}
	
	var start_date = $('#history_sdate').val();
	var end_date = $('#history_edate').val();

	$("#loading_top").css("display","block");
	
	$.post("<?php echo base_url(); ?>index.php/reports/allpoints/trackOnMap", { device: device, start_date: start_date, end_date: end_date },
	 function(data) {
		if(data){
			//clearMapRoutLoading();
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			var ign = data.ignition_status;
			latArr.length = 0;
			lngArr.length = 0;
			htmlArr.length = 0;
			clear_line_route();		
			if(lat.length > 0){
				for(i=0; i<lat.length; i++){
					latArr.push(lat[i]);
					lngArr.push(lng[i]);
					htmlArr.push(html[i]);
				}
				viewTrackAllpoint_route(0);				
			}else{
				$("#alert-dialog-allpoint").html("<?php echo $this->lang->line("No_Data_Found"); ?>");
				$("#alert-dialog-allpoint").dialog('open');
				//clearMapRoutLoading();
				$("#all_point_pBar").css("display","none");
				$("#v_map_id").removeClass("ui-state-disabled");
				$("#v_map_id").removeAttr("disabled");
			}
			$("#loading_top").css("display","none");
		}
	 }, 'json'
	);
}
function viewTrackAllpoint_route(arr_i){
	//$("#countr").css("display") != "block"
	//alert(totalDir);
	totalDir=Math.floor(latArr.length/9);
	$("#countr").css("display","inline-block");
	route_process_line(0);
}
last_s1="";
last_s1="";
last_e1="";

function arrowMarkerRouteFunction(map, point, title, html, img){
	//create marker
	var new_marker = new google.maps.Marker({
		position: point,
		icon: new google.maps.MarkerImage(img,
									new google.maps.Size(24,24),
									new google.maps.Point(0,0),
									new google.maps.Point(12,12)
								   ),
		map: null,
		//title: Math.round((dir>360)?dir-360:dir)+'°'
		title : title
	});
	if(html!=''){
		html = '<div style="height:100px;">'+html+'</div>';
		var infowindow = new google.maps.InfoWindow({content: html, maxWidth:100});
		google.maps.event.addListener(new_marker, 'click', function() {
		  infowindow.open(map,new_marker);
		});
	}
	return new_marker;  
}

function route_process_line(arr_i)
{
	if(totalDir_count!=0){
		if(((totalDir_count*100)/totalDir)!=100){
			var pers=Math.ceil((totalDir_count*100)/totalDir);
			$("#countr").html("Loading "+pers+"% ");
		}else{
			setTimeout(function(){
				$("#countr").css("display","none");
				$("#clear_v_map_id_show_hide").css("display","inline-block");
			},2000);
		}
	}
	if($("#countr").css("display") != "block" && $("#countr").css("display") != "inline-block" )
	{
		clearMapRoutLoading_route();
		return false;
	}
	var point = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
	allpointBounds_route.extend(point);
	var image = '';
	var shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
	if(arr_i == 0){
		//map.setCenter(point);
		//alert("0->"+arr_i);
		var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-START.png';
		image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
		markersmapAllpoint.push(createMarkerRoute(map, point,"Marker Description",htmlArr[arr_i], img, shadow, "sidebar_map", '' ));
	}
	else if(arr_i == (latArr.length-1)){
		//alert("1-="+arr_i);
		var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-END.png';
		image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
		markersmapAllpoint.push(createMarkerRoute(map, point,"Marker Description",htmlArr[arr_i], img, shadow, "sidebar_map", '' ));
	}else{
		//alert("2->"+arr_i);
		var p1 = new google.maps.LatLng(latArr[arr_i-1], lngArr[arr_i-1]);
		var p2 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
		
		var dir = bearing(p1, p2);
		var dir = Math.round(dir/3) * 3;
		while (dir >= 120) {dir -= 120;}
		
		a=p1,
		z=p2,
		dir=((Math.atan2(z.lng()-a.lng(),z.lat()-a.lat())*180)/Math.PI)+360,
		ico=((dir-(dir%3))%120);
		var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+ico+".png";
		var mkr = arrowMarkerRouteFunction(map, point, "Marker Description", htmlArr[arr_i], img)
		markersmapAllpoint.push(mkr);
		//arrowMarkerAllpoint.push(mkr);
		call_marker_status(mkr);
		//markerClusterAllpoint = new MarkerClusterer(map, arrowMarkerAllpoint, mcOptionsAllpoint);
	}
	if(totalDir > totalDir_count)
	{
		//alert("4->"+arr_i);
		var point1 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
		wayptsAllpoint=[];
		Poin_Cntr++;
		for(i=0;i<=7;i++)
		{
		arr_i++;
		wayptsAllpoint.push({
			location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
			stopover:true
			});
		}
		arr_i++;
		var point2 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
		calcRouteAllpoint_route(point1,point2,Poin_Cntr);
		if(latArr.length != arr_i)
			setTimeout(function(){route_process_line(arr_i)},700);
		totalDir_count++;
		
		current_all_p=Number(totalDir_count);
		percentage_all_p = Number(current_all_p/(totalDir)*100)-Number(0.99/(totalDir)*100);
		val_all_p=100-percentage_all_p;
	//	$("#all_point_pBar").progressbar("value" , percentage_all_p);
	}else if(totalDir == totalDir_count && latArr.length > arr_i && totalDir!=0)
	{
		//alert("5->"+arr_i);
		var total_ar=latArr.length-arr_i;
		if(total_ar>=2)
		{
		//	$("#all_point_pBar").progressbar("value" , 99.99);
			var point1 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			wayptsAllpoint=[];
			Poin_Cntr++;
			if(total_ar>2)
			{
				//alert("6->"+arr_i);
				for(i=0;i<=total_ar-2;i++)
				{
				arr_i++;
				wayptsAllpoint.push({
					location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
					stopover:true});
				}
			}
			arr_i++;
			var point2 = new google.maps.LatLng(latArr[latArr.length-1], lngArr[latArr.length-1]);
			calcRouteAllpoint_route(point1,point2,Poin_Cntr);
			if(latArr.length != arr_i)
				setTimeout(function(){route_process_line(arr_i)},700);
				
			setTimeout(function(){
				//	map.fitBounds(allpointBounds_route);
					//markerClusterAllpoint = new MarkerClusterer(map, arrowMarkerAllpoint, mcOptionsAllpoint);
				},1000);
		}
		
		$("#v_map_id").removeClass("ui-state-disabled");
		$("#v_map_id").removeAttr("disabled");
	}
	else
	{
	
		//$("#distance_txt_all_p").css("display","block");
		//$("#v_map_id").removeClass("ui-state-disabled");
		//$("#v_map_id").removeAttr("disabled");
		//$("#all_point_pBar").css("display","none");
		//var txt = device_jq + " Distance : " + distance_all_total.toFixed(2) + " KM";
		//$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
		//markerClusterAllpoint = new MarkerClusterer(map, arrowMarkerAllpoint, mcOptionsAllpoint);
	//	alert(arr_i+"of -> total"+latArr.length+", Total"+totalDir+", CountTotal"+totalDir_count);
	}
}
var last_s1="";
var last_e1="";
function clearMapRoutLoading_route()
{
	totalDir=0;
	totalDir_count=0;
	arr_i=0;
	Timer_counter=1000;
	last_s1="";
	last_e1="";
	latArr = [];
	lngArr = [];
	htmlArr = [];
	ignitionArr = [];
	Poin_Cntr=0;
}
function call_marker_status(mkr){
	/*if(1==1){
		mkr.setMap(null);
	}*/
}
function show_hide_marker_of_line_history(){
	if($("#clear_v_map_id_show_hide").val()=="<?php echo $this->lang->line("Show Markers"); ?>"){
		$("#clear_v_map_id_show_hide").val("<?php echo $this->lang->line("Hide Markers"); ?>");
		if (markersmapAllpoint) {
			for (i in markersmapAllpoint) {
				markersmapAllpoint[i].setMap(map);
			}
		}
	}else{
		$("#clear_v_map_id_show_hide").val("<?php echo $this->lang->line("Show Markers"); ?>");
		if (markersmapAllpoint) {
			for (i in markersmapAllpoint) {
				markersmapAllpoint[i].setMap(null);
			}
		}
	}
}
function clear_marker_of_line_history(){
	/*if(arrowMarkerAllpoint.length > 0){
		arrowMarkerAllpoint[i].setmap = [];
		markerClusterAllpoint.clearMarkers();
	}*/
	markerClusterAllpoint.set(map);
	//markerClusterAllpoint.set("map", HIDDEN_MAP);
	/*if (arrowMarkerAllpoint) {
		for (i in arrowMarkerAllpoint) {
		  arrowMarkerAllpoint[i].setMap(null);
		}
	}
	if (arrowMarkerAllpoint) {
		for (i in arrowMarkerAllpoint) {
		  arrowMarkerAllpoint[i].setMap(null);
		}
	}*/
}
function clear_line_route(){
$("#clear_v_map_id_show_hide").css("display","none");
	clearMapRoutLoading_route();
	if (directionsDisplayAllpoint) {
		for (i in directionsDisplayAllpoint) {
		  directionsDisplayAllpoint[i].setMap(null);
		}
	}
	directionsDisplayAllpoint.length=0;
	if (markersmapAllpoint) {
		for (i in markersmapAllpoint) {
			markersmapAllpoint[i].setMap(null);
		}
	}
	markersmapAllpoint.length=0;
	$("#countr").css("display","none");
}
function calcRouteAllpoint_route(s1, e1, pointCounter){
		directionsDisplayAllpoint[pointCounter] = new google.maps.DirectionsRenderer(rendererOptionsAllpoint);
		directionsDisplayAllpoint[pointCounter].setMap(map);
		var request = {
			origin:s1, 
			destination:e1,
			waypoints: wayptsAllpoint,
			optimizeWaypoints: true,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplayAllpoint[pointCounter].setDirections(response);
				distance_all_p = Number((response.routes[0].legs[0].distance.value)/1000);
				distance_all_total += Math.round(distance_all_p*100)/100;
				//var txt = device_jq + " Distance : " + distance_all_total.toFixed(2) + " KM";
				//$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
				return true;
			}
			else
			{
				if(last_s1!=s1 && last_e1!=e1)
				{
				//	Timer_counter=Timer_counter+30;
					last_s1=s1;
					last_e1=e1;
					calcRouteAllpoint_route(s1, e1, pointCounter);
				}
				
			}
		});	
		wayptsAllpoint = [];
  }
function gDirRequest(service, waypoints, userFunction, waypointIndex, path) {
    
    // set defaults
    waypointIndex = typeof waypointIndex !== 'undefined' ? waypointIndex : 0;
    path = typeof path !== 'undefined' ? path : [];

    // get next set of waypoints
    var s = gDirGetNextSet(waypoints, waypointIndex);

    // build request object
    var startl = s[0].shift()["location"];
    var endl = s[0].pop()["location"];
    var request = {
        origin: startl,
        destination: endl,
        waypoints: s[0],
        travelMode: google.maps.TravelMode.DRIVING,
        //unitSystem: google.maps.UnitSystem.METRIC,
        optimizeWaypoints: true,
        provideRouteAlternatives: false,
       /* avoidHighways: true,
        avoidTolls: true*/
    };
    console.log(request);

    service.route(request, function(response, status) {

        if (status == google.maps.DirectionsStatus.OK) {
			
			if(path.length>0){
            path = path.concat(response.routes[0].overview_path);
			}else{
			path = response.routes[0].overview_path;
			}
            if (s[1] != null) {
                gDirRequest(service, waypoints, userFunction, s[1], path)
            } else {
                userFunction(path);
            }

        } else {
            console.log(status);
			alert(status);
        }

    });
}
function gDirGetNextSet (waypoints, startIndex) {
    var MAX_WAYPOINTS_PER_REQUEST = 8;

    var w = [];    // array of waypoints to return

    if (startIndex > waypoints.length - 1) { return [w, null]; } // no more waypoints to process

    var endIndex = startIndex + MAX_WAYPOINTS_PER_REQUEST;

    // adjust waypoints, because Google allows us to include the start and destination latlongs for free!
    endIndex += 2;

    if (endIndex > waypoints.length - 1) { endIndex = waypoints.length ; }

    // get the latlongs
    for (var i = startIndex; i < endIndex; i++) {
        w.push(waypoints[i]);
    }

    if (endIndex != waypoints.length) {
        return [w, endIndex -= 1];
    } else {
        return [w, null];
    }
}

    /* ]]> */
  </script>

 </div>
<div id="dialog_edit_route<?php echo time(); ?>" style="display:none">
</div>
<div id="trip_confirm_dialog<?php echo time(); ?>" style="display:none"><?php echo $this->lang->line("Do you want to delete this record"); ?> ?
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