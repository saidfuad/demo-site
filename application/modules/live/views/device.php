<?php $time=time(); ?>
<script type="text/javascript">
//loadDropdown();
</script>
<style type="text/css">
#map_canvas{
    width: 400px; 
    height: 300px;
}

.contextmenu{
    visibility:hidden;
    z-index: 10;  
    position: relative;
    width: 150px;
	cursor:pointer;
	direction: ltr; 
	overflow: hidden; 
	color: rgb(0, 0, 0); 
	font-family: Arial,sans-serif; 
	-moz-user-select: none; 
	font-size: 13px; 
	background: none repeat scroll 0% 0% rgb(255, 255, 255); padding: 1px 6px;
        border: 1px solid rgb(113, 123, 135); 
	box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4); 
}
.contextmenu div{
		padding-left: 5px
}
.contextmenu a:hover{
		font-weight:bold;
		text-decoration:none;
}

#LandmarkId1<?php echo $time; ?>_title{
	height:25px;
}
#LandmarkId2<?php echo $time; ?>_title{
	height:25px;
}
#LandmarkId1<?php echo $time; ?>_child{
	height:70px !important;
}
#LandmarkId2<?php echo $time; ?>_child{
	height:70px !important;
}	
</style>
<?php /* // waypoints comments
<div id="getRouteWayPoint_dialog<?php echo $time; ?>" style="display:none">
	<form id="frm_device_waypoint" method="post" onsubmit="return submitFormdevice_waypoint()">
	<p id="error_<?php echo $time; ?>"></p>
	<table width="100%">
			<tr><td style="height:10px;"></td></tr>
			<tr>
			<td width="35%" style="vertical-align:middle"><label>Waypoint Name</label>&nbsp;&nbsp;</td>
			<td width="75%" style="vertical-align:middle"><input type="text" id="waypoint_name<?php echo $time; ?>" name="waypoint_name" class="text ui-widget-content ui-corner-all" style="height: 22px; width: 100%;"/></td>
		   </tr>
		   <tr><td style="height:7px;"></td></tr>
		   <tr style="height:40px">
			<td width="35%" style="vertical-align:middle"><label>Landmark 1</label></td>
			<td width="75%" style="vertical-align:middle"><select id="LandmarkId1<?php echo $time; ?>" style="width:250px;height:80px"></select></td>
		   </tr>
		   <tr><td style="height:10px;"></td></tr>
		   <tr style="height:40px">
			<td width="35%" style="vertical-align:middle"><label>Landmark 2</label></td>
			<td width="75%" style="vertical-align:middle"><select id="LandmarkId2<?php echo $time; ?>" style="width:250px;height:80px"></select></td>
		   </tr>
		   <tr><td style="height:15px;"><input type="hidden" value="" name="waypoint_id" id="waypoint_id<?php echo $time; ?>"></td></tr>
		   <tr>
			<td colspan="2" align="center"><input type="submit" id="btn_submit_device" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>&nbsp;&nbsp;&nbsp;<input type="button" id="btn_close" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_close" onClick="close_waypnt();"/></td>
		   </tr>
		   <tr><td style="height:20px;"></td></tr>
	</table>
	</form>
</div>
 */ ?>
<div id="QTip_live_Model<?php echo $time; ?>"></div>
<div id="frm_live<?php echo $time; ?>" style="display:none">
<span><input type="checkbox" checked="checked" id="noTrack<?php echo $prefix; ?>" onClick="IsLines(this.checked);"/><?php echo $this->lang->line("show_track_lines"); ?></span><hr/>
<!--
<span><input type="checkbox" checked="checked" id="testCheck<?php echo $prefix; ?>" onClick="IsAnimate(this.checked);"/><?php echo $this->lang->line("Animate_Me"); ?>.!</span><hr/>
<span><input type="checkbox" checked="checked" id="Focus_Anim_chk<?php echo $prefix; ?>" onClick="Focus_Anim(this.checked);"/><?php echo $this->lang->line("Focus_Map_Center_Animate"); ?>.!<br/><span style='font-size:10px'>(<?php echo $this->lang->line("this_will_effect_when_Animate_is_Enable"); ?>)</span></span>
-->
<script type="text/javascript">
var route_id<?php echo $prefix?>;
var display_lines<?php echo $time; ?>=true;
var device_anim<?php echo $time; ?>=false;
var map_focus_center<?php echo $time; ?>=false;
function IsAnimate(val)
{
	device_anim<?php echo $time; ?>=val;
}
function IsLines(val)
{
	display_lines<?php echo $time; ?>=val;
	if(!val) {
	  if (polylinesmap) {
		for (i in polylinesmap) {
		  polylinesmap[i].setMap(null);
		}
	  }
	}
}
function Focus_Anim(val)
{
	map_focus_center<?php echo $time; ?>=val;
}
</script>
</div>
<div id="helpRightClick_top<?php echo $prefix; ?>" style="margin:-12px 0px 0px 0px;padding:0px;width:450px;height:25px;text-align:center;display:none">
<div id="helpRightClick<?php echo $prefix; ?>"><?php echo $this->lang->line("Right_Click_Map_for"); ?> <strong><?php echo $this->lang->line("Reload_Map"); ?></strong> <?php echo $this->lang->line("Or"); ?> <strong><?php echo $this->lang->line("Reload_Clear_Map"); ?></strong> <?php echo $this->lang->line("Menu"); ?></div>
</div>
<div id="devindia_note<?php echo $prefix; ?>" style="padding: 0px; text-align: center; color: darkred; height: 25px; font-family: Verdana; font-size: 10px; display: none; float: left; margin: -15px 0px -6px;">
This is only for conceptual viewing, actual route may vary when there is multiple road crossing.</div>
<div id="ignitionErr<?php echo $prefix; ?>" style="margin:-12px 0px 0px 0px;padding:0px;width:400px;height:25px;text-align:center;display:none;float:right;background:smoke;border:1px solid black;font-weight:bold;color:red">
Your Vehicle has Ignition Problem.
</div>
<div id="map<?php echo $prefix; ?>" style="width: 100%; height: 94%; position:relative;"></div>
<style>
.label {font-size:12px; text-align:center; color:#222; text-shadow:0 0 5px #fff; font-family:Helvetica, Arial, sans-serif;}
</style>
<script type="text/javascript">
if(auto_refresh_setting == 1){
	$("#timer_toggle<?php echo $time; ?>").attr("checked", true);
}else{
	$("#timer_toggle<?php echo $time; ?>").attr("checked", false);
}
var t<?php echo $prefix; ?>;
var angle_<?php echo $time; ?>;
$( "#pbar<?php echo $time; ?>" ).progressbar({value: 0});
var last_id<?php echo $prefix; ?> = <?php echo $last_id; ?>;
var last_datetime<?php echo $prefix; ?> = <?php echo $last_datetime; ?>;
var markersmap<?php echo $prefix; ?>  = [];

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];

var to_htmlsmap  = [];
var from_htmlsmap  = [];

var polylinesmap<?php echo $prefix; ?> = [];
var polylineCoordsmap<?php echo $prefix; ?> = [];
var mapmap<?php echo $prefix; ?> = null;
var mapOptionsmap<?php echo $prefix; ?>;

var lat<?php echo $prefix; ?> = new Array();
var lng<?php echo $prefix; ?> = new Array(); 
var html<?php echo $prefix; ?> = new Array();


var polyNameArr = [];
var polyArrV = [];
//var geocoder;
var live_poly_array<?php echo $prefix; ?> = [];
var live_poly_zone_array<?php echo $prefix; ?> = [];
var live_landmark_array<?php echo $prefix; ?> = [];
var live_trip_array<?php echo $prefix; ?> = [];
var circleArray<?php echo $prefix; ?> = [];

var directionsDisplay<?php echo $prefix; ?> = [];

var directionsDisplay1<?php echo $prefix; ?> = [];

var rendererOptions = { 
					preserveViewport: true,
					draggable: false,
					suppressMarkers: true,
					polylineOptions: {
					   map: mapmap<?php echo $prefix; ?>,
					   strokeColor:'green',
					   //strokeWidth: 3,
					   strokeOpacity: 0.7}
			};

///////
var loop<?php echo $prefix; ?>=0,j,x;
var lastPoint<?php echo $prefix; ?>;
var lastPoint_html<?php echo $prefix; ?>="";
var markers_lat<?php echo $prefix; ?>=[];
var reloadMap_bool=false;
var markers_lng<?php echo $prefix; ?>=[];
var ib<?php echo $prefix; ?>;
var last_ignition<?php echo $prefix; ?> = 0;	
var h_ways<?php echo $prefix; ?> = [];	
var h_id<?php echo $prefix; ?> = '';	
var h_start<?php echo $prefix; ?>;
var h_end<?php echo $prefix; ?>;
var routePolyArr<?php echo $prefix; ?>=[];
var dDisplay<?php echo $prefix; ?>=[];
$loadingDynamicObj<?php echo $prefix; ?>=null;
<?php if($this->session->userdata('usertype_id')==3){ ?>
	var dealerLat<?php echo $prefix; ?>=0;
	var dealerLng<?php echo $prefix; ?>=0;
	var strLatLng<?php echo $prefix; ?>="";
	var landmark_as_waypnt<?php echo $prefix; ?>=[];
	var landmark_as_waypnt<?php echo $prefix; ?>_1=[];
<?php } ?>
var gsmLandmark<?php echo $prefix; ?> = null;
var gsm_circle<?php echo $prefix; ?> = null;
var gsm_text<?php echo $prefix; ?> = null;
//new window button
function onLoadmap<?php echo $prefix; ?>(){
	
	directionsService = new google.maps.DirectionsService();
	
	var mapObjmap = document.getElementById("map<?php echo $prefix; ?>");
	
	if (mapObjmap != 'undefined' && mapObjmap != null) {

		mapOptionsmap<?php echo $prefix; ?> = {
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: true,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
		};
		
		mapmap<?php echo $prefix; ?> = new google.maps.Map(mapObjmap,mapOptionsmap<?php echo $prefix; ?>);
		mapmap<?php echo $prefix; ?>.enableKeyDragZoom();		
				
		<?php if($gsm_lat != "" && $gsm_lng != ""){ ?>
		var gsm_point = new google.maps.LatLng(<?php echo $gsm_lat; ?>,<?php echo $gsm_lng; ?>);
		
		gsmLandmark<?php echo $prefix; ?> = createGSMLandmark<?php echo $prefix; ?>(mapmap<?php echo $prefix; ?>, gsm_point, "GSM","GSM", 'image-red.png', '', "sidebar_map", '' );
		
		GSMCircle<?php echo $prefix; ?>(gsm_point, mapmap<?php echo $prefix; ?>);
		
		<?php } ?>
		
		var point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
		
		lastPoint<?php echo $prefix; ?> = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
		h_start<?php echo $prefix; ?> = point;
		lastPoint_html<?php echo $prefix; ?>="<?php echo $html_address; ?>";
		// address
		var myTextDiv_top_left = document.createElement('div');
		myTextDiv_top_left.innerHTML = '<span><?php echo $html_address; ?>&nbsp;</span>';
		myTextDiv_top_left.style.color = 'white';
		myTextDiv_top_left.style.fontWeight = 'bold';
		myTextDiv_top_left.style.border = '1px solid black';
		myTextDiv_top_left.style.borderRadius = '3px';
		myTextDiv_top_left.style.padding = '3px';
		myTextDiv_top_left.style.padding = '3px';
		myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
		mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv_top_left);
		
		var ReConnecting_txt = document.createElement('div');	
		ReConnecting_txt.id = "Reconn_div<?php echo $prefix; ?>";
		mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(ReConnecting_txt);
		// image<?php echo $prefix; ?>.rotate(<?php echo $angle; ?>);
		
		//options for InfoBox	
		
		var myOptions = {
				disableAutoPan: true
				,content: $("#imgR<?php echo $prefix; ?>").html()
				,isHidden: false
				,boxStyle: {
					   textAlign: "center"
					  ,fontSize: "8pt"
					  ,width: "18px"
                                             }									
                                ,pixelOffset: new google.maps.Size(-7, 0)
				,position: point
				,closeBoxURL: ""
				,pane: "mapPane"
				,enableEventPropagation: true
			};
		
		//this will hold car div and will use to move this div on map.
		
		ib<?php echo $prefix; ?> = new InfoBox(myOptions);                
		<?php if($lat != "" && $lat != 0 && $lng != "" && $lng != 0){ ?>
			ib<?php echo $prefix; ?>.open(mapmap<?php echo $prefix; ?>);
			
			lat<?php echo $prefix; ?>.push('<?php echo $lat; ?>');
			lng<?php echo $prefix; ?>.push('<?php echo $lng; ?>');
			html<?php echo $prefix; ?>.push('<?php echo $html; ?>');
			mapmap<?php echo $prefix; ?>.setCenter(point);
		<?php } ?>
		
		var bounds = new google.maps.LatLngBounds();
		<?php if($ignition!="" && $speed!="" && $gsm_lat == "" && $gsm_lng == ""){ ?>
			showIgnitionErr<?php echo $prefix; ?>(<?php echo $ignition; ?>,<?php echo $speed; ?>);		
		<?php } ?>
		
		<?php if($lat == "" && $lng == "" && $gsm_lat != "" && $gsm_lng != ""){ ?>	
			mapmap<?php echo $prefix; ?>.setCenter(gsm_point);
			$("#ignitionErr<?php echo $prefix; ?>").html('GSM Active.');
			$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
		<?php } ?>
		<?php if($lat != "" && $lng != "" && $gsm_lat != "" && $gsm_lng != ""){ ?>	
			$("#ignitionErr<?php echo $prefix; ?>").html('GPS and GSM Active.');
			$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
		<?php } ?>
	}
	<?php
	/*
	$i = 0;
	foreach($pplyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php 
		$pathArr = array();
		
		for($j=0; $j<count($pplyLat[$pIdv]); $j++){
			$pathArr[] = 'new google.maps.LatLng('.sprintf("%.6f", $pplyLat[$pIdv][$j]).', '.sprintf("%.6f", $pplyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArr);
		
		if(count($pplyDev[$pIdv]) > 0){
			$devices = implode("<br>", $pplyDev[$pIdv]);
		}
		?>
		var polygonCoords = [<?php echo $pathString; ?>];

		for (i = 0; i < polygonCoords.length; i++) {
		  bounds.extend(polygonCoords[i]);
		}
		//var devices = 'plyDev'
		label<?php echo $prefix; ?><?php echo $i; ?> = new ELabel({
		latlng: bounds.getCenter(), 
		label: "<div class='elable' id='elable_<?php echo $prefix; ?>_<?php echo $i; ?>' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'><?php echo $pplyName[$pIdv][0]; ?></div>", 
		classname: "label", 
		offset: 0, 
		opacity: 100, 
		overlap: true,
		clicktarget: false
		});
						
		var live_polyV<?php echo $prefix; ?><?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $pplyColor[$pIdv]; ?>'
		    });
		//map_polyV<?php echo $i; ?>.setMap(map<?php echo time(); ?>);
		live_poly_array<?php echo $prefix; ?>.push(live_polyV<?php echo $prefix; ?><?php echo $i; ?>);			
		google.maps.event.addListener(live_polyV<?php echo $prefix; ?><?php echo $i; ?>,"mouseover",function(event){
			label<?php echo $prefix; ?><?php echo $i; ?>.setMap(mapmap<?php echo $prefix; ?>);
			$("#elable_<?php echo $prefix; ?>_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(live_polyV<?php echo $prefix; ?><?php echo $i; ?>,"mouseout",function(event){
			label<?php echo $prefix; ?><?php echo $i; ?>.setMap(null);
		});
		google.maps.event.addListenerOnce(map<?php echo $prefix; ?>, 'idle', function() {
			google.maps.event.trigger(map<?php echo $prefix; ?>, 'resize');
			//map<?php echo time(); ?>.setCenter(point); // be sure to reset the map center as well
		});

		
	<?php $i++; } ?>
	
	<?php 
	$i = 0;
	$k=100;
	
	if(count($landmarks) > 0) {
		foreach ($landmarks as $landmark) {
			$distance_unit = $landmark->distance_unit;
			$text = "Name : ".$landmark->name."<br>";
			$text .= "Address : ".$landmark->address."<br>";
			$text .= "Assets : ".$landmark->assets.'<br>';
	?>
			setTimeout(function(){
				var point = new google.maps.LatLng(<?php echo floatval($landmark->lat); ?>,<?php echo floatval($landmark->lng); ?>);
				<?php if($this->session->userdata('usertype_id')==3){ ?>
					landmark_as_waypnt<?php echo $prefix; ?>["l_"+<?php echo $landmark->id; ?>]=
					{location:new google.maps.LatLng(parseFloat(<?php echo floatval($landmark->lat); ?>), parseFloat(<?php echo floatval($landmark->lng); ?>))};					
				<?php } ?>
				live_landmark_array<?php echo $prefix; ?>.push(createLandmarkMarker<?php echo time(); ?>(mapmap<?php echo $prefix; ?>, point, "<?php echo $landmark->name; ?>","<?php echo $text; ?>", '<?php echo $landmark->icon_path; ?>', '', "sidebar_map", '' ));
				
				<?php if($this->session->userdata('usertype_id') < 3){ ?>
				DrawCircle<?php echo $prefix; ?>(point, '<?php echo $landmark->radius; ?>', '<?php echo $distance_unit; ?>', mapmap<?php echo $prefix; ?>);
				<?php } ?>
				},<?php echo $k; ?>);
				
	<?php
		$k=$k+100;
		$i++;
		} // End For Loop
	}
	 
	?>
	//if(<?php echo $this->session->userdata('usertype_id'); ?> == 2){
	loadRoute<?php echo $prefix?>(<?php echo $route_id ?>,<?php echo $assets_id; ?>);
	route_id<?php echo $prefix?> = '<?php echo $route_id ?>';
	//}
	 <?php if($this->session->userdata("usertype_id")!=3){ ?>
	setTimeout(function(){
		live_clearLandmarkOverlays<?php echo $prefix; ?>();
		setTimeout(function(){
			live_clearLandmarkOverlays<?php echo $prefix; ?>();
		},500);
	},<?php echo $k+500; ?>);
	<?php } ?>
	//startLoading<?php echo $prefix; ?>();
	
	<?php if($this->session->userdata('usertype_id')==3){ ?>
		setTimeout(function(){
			if(dealerLat<?php echo $prefix; ?>!=0 && dealerLat<?php echo $prefix; ?>!=""){
				getDistanceFromMyLandmark(<?php echo $lat; ?>,<?php echo $lng; ?>);
			}
		},3000);
	<?php } */ ?>
	//addContextMenu();
}

</script>
<div style='text-align: center;clear:both' id="bottomLoadingBar_<?php echo $prefix; ?>">
	
		<div id="pbar<?php echo $time; ?>">
		<div style="display:none" id="pbar_refresh_buttons_<?php echo $prefix; ?>">
			<div style="float:left;width:96%;height:2em;padding-top:0.2em;position:absolute;text-align:center"><input type='checkbox' style="opacity:0;" onclick='stop_resume_toggle<?php echo $time; ?>()' id='timer_toggle<?php echo $time; ?>' checked> <?php //echo $this->lang->line('data_refresh_after'); ?> <input type='hidden' size='2' onblur='counter_change<?php echo $time; ?>()' value='30' id='time_in_seconds<?php echo $time; ?>'> <?php //echo $this->lang->line('seconds'); ?> <span style='display: inline-block;'><?php //echo $this->lang->line('refresh_after'); ?> <span style="visibility:hidden" id='seconds<?php echo $time; ?>'>30</span>&nbsp;&nbsp;<?php //echo $this->lang->line('second'); ?></span> &nbsp;&nbsp;<span onClick="directRefresh_live<?php echo $prefix; ?>()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php //echo $this->lang->line('refresh'); ?></span><span id="live_settings<?php echo $time; ?>" style="font-weight: bold; float: right; margin-right: 14px; text-decoration: underline;cursor:pointer"><?php echo $this->lang->line("Settings"); ?></span>
			
			
			<?php //if($this->session->userdata("history") == 1){ ?>
			<span id="live_history<?php echo $time; ?>" style="font-weight: bold; float: right; margin-right: 30px; text-decoration: underline;cursor:pointer">
			<a id="history_<?php echo $prefix; ?>" href="#" onclick="getHistory<?php echo $prefix; ?>(<?php echo $assets_id; ?>)"><?php echo $this->lang->line("history"); ?></a></span>
			<?php //} ?>
			</div>
		</div>
		<div style="float:left;width:96%;height:2em;padding-top:0.2em;position:absolute;text-align:center" id="pbar_Loading_<?php echo $prefix; ?>">
			<?php echo $this->lang->line("Loading_Please_Wait"); ?>...
		</div>
		</div>
</div>
<div id="timeTakenDiv<?php echo $prefix; ?>" style="display:none"><?php echo $time_taken; ?></div>
<?php 
	/*
	if($assets_category_id == 1 || $assets_category_id == "" || $assets_category_id == 0 || $assets_category_id == 13){
		$image_type = "truck.png";
	}else if($assets_category_id == 2){
		$image_type = "car.png";
	}
	else if($assets_category_id == 3){
		$image_type = "bus.png";
	}
	else if($assets_category_id == 4){
		$image_type = "mobile.png";
	}
	else if($assets_category_id == 5){
		$image_type = "bike.png";
	}
	else if($assets_category_id == 6){
		$image_type = "altenator.png";
	}
	else if($assets_category_id == 7 || $assets_category_id == 8){
		$image_type = "man.png";
	}
	else if($assets_category_id == 9){
		$image_type = "stacker.png";
	}
	else if($assets_category_id == 10){
		$image_type = "loader.png";
	}
	else if($assets_category_id == 11){
		$image_type = "locomotive.png";
	}
	else if($assets_category_id == 12){
		$image_type = "generator.png";
	}
	else if($assets_category_id == 113){
		$image_type = "maintenance.png";
	}
	else if($assets_category_id == 14){
		$image_type = "motor.png";
	}
	else if($assets_category_id == 15){
		$image_type = "bobcat.png";
	}
	else if($assets_category_id == 16){
		$image_type = "tractor.png";
	}
	else if($assets_category_id == 17){
		$image_type = "car1.png";
	}
	else if($assets_category_id == 18){
		$image_type = "satellite.png";
	}
	else if($assets_category_id == 21){
		$image_type = "stacker.png";
	}
	else{
		$image_type = "truck.png";
	}
	*/
?>
<div style="display:none">
<div id="imgR<?php echo $prefix; ?>"> 
<div id="car<?php echo $prefix; ?>" style="color: white;background-repeat:no-repeat;background-image:url(<?php echo base_url(); ?>assets/<?php echo $image_type; ?>); font-family: 'Lucida Grande', 'Arial', sans-serif;font-size: 10px;text-align: center; width: 35px; height:35px; white-space: nowrap;margin-top:-20px;">
</div>
</div>
</div>
<script type="text/javascript">
var disp_area<?php echo $time; ?>=true;
var disp_zone<?php echo $time; ?>=true;
var disp_route<?php echo $time; ?>=true;
var disp_landmark<?php echo $time; ?>=true;
function getAtt(attr,img){
	$("#"+attr).attr("src","<?php echo base_url(); ?>assets/images/"+img);
}
function showIgnitionErr<?php echo $prefix; ?>(ignition,speed){
	if(ignition==0 && speed>0){
		//$("#ignitionErr<?php echo $prefix; ?>").html('Your Vehicle has Ignition Problem.');
		//$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
	}else{
		$("#ignitionErr<?php echo $prefix; ?>").css('display','none');
	}
}
$(document).ready(function(){
	image<?php echo $prefix; ?>=$("#car<?php echo $prefix; ?>");
	//$("#pbar<?php echo $time; ?>" ).progressbar({value: 0});
	//stop_resume_toggle<?php echo $time; ?>();
//	alert();
	//,"<?php echo base_url(); ?>/assets/images/<?php echo $TabImage;?>");
	//alert('<?php echo $assets_id;?>_dot'+ " - "+"<?php echo base_url(); ?>/assets/images/<?php echo $TabImage;?>");
	setTimeout(function(){getAtt("<?php echo $assets_id; ?>_dot","<?php echo $TabImage; ?>");},500);
/*	*/
	$("#getRouteWayPoint_dialog<?php echo $time; ?>").dialog({
		autoOpen: false,
		modal: true,
		height: 'auto',
		width:'auto',
		resizable: true,
		title:"<?php echo $this->lang->line("Add Waypoint Between Route"); ?>"
	});
	$.post("<?php echo base_url(); ?>index.php/live/live_js",{prefix:'<?php echo $prefix; ?>',time:<?php echo $time; ?>,lat:<?php echo $lat; ?>,lng:<?php echo $lng; ?>},function(data){
		$("body").append(data);
		setTimeout(function(){
			$("#pbar_Loading_<?php echo $prefix; ?>").html("Loading Landmarks ...<span style='color:blue;cursor:pointer;' onClick='cancelLoading<?php echo $prefix; ?>();'>Cancel</span>");
			loadLandmarks<?php echo $prefix; ?>('<?php echo $prefix; ?>');
		},1000);
	});
	jQuery("input:button, input:submit, input:reset").button();
	onLoadmap<?php echo $prefix; ?>();	
});
function createGSMLandmark<?php echo $prefix; ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/assets/marker-images/blueblank.png"}
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
		  maxWidth : 325,
		  maxHeight : 125,
		  minWidth : 225,
		  minHeight : 80
		});

		google.maps.event.addListener(new_marker, 'click', function() {
			infoBubble.open(map, new_marker);
		});
	}
	var boxText = "<div style='color:white; line-height: 15px; font-size: 11px; font-weight: bold; color: blue; padding: 2px; margin-top: 8px;text-align:center;'>GSM LOCATION</div>";
	
	var myOptions1 = {
		 content: boxText
		,disableAutoPan: false
		,maxWidth: 0
		,position: point
		,pixelOffset: new google.maps.Size(-90,0)
		,zIndex: null
		,boxStyle: { 
		   opacity: 0.75
		  ,width: "180px"
                  
                 }
		,closeBoxMargin: "10px 2px 2px 2px"
		,closeBoxURL: ""
		//http://www.google.com/intl/en_us/mapfiles/close.gif
		,infoBoxClearance: new google.maps.Size(1, 1)
		,isHidden: false
		,pane: "floatPane"
		,enableEventPropagation: true
	};
	gsm_text<?php echo $prefix; ?> = new InfoBox(myOptions1);                
	gsm_text<?php echo $prefix; ?>.open(map);
	return new_marker;  
}
function GSMCircle<?php echo $prefix; ?>(center, map) {
	   
    gsm_circle<?php echo $prefix; ?> = new google.maps.Circle({
        center: center,
        radius: 500,
        strokeColor: "#FF0000",
        strokeOpacity: 0.3,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.25,
        map: map
    });
}
</script>
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