<script type='text/javascript'>
function live_clearPolyOverlays<?php echo $prefix; ?>() {
  if (live_poly_array<?php echo $prefix; ?>) {
    for (i in live_poly_array<?php echo $prefix; ?>) {
      live_poly_array<?php echo $prefix; ?>[i].setMap(null);
    }
  }
}
function live_showPolyOverlays<?php echo $prefix; ?>() {
 
  if (live_poly_array<?php echo $prefix; ?>) {
    for (i in live_poly_array<?php echo $prefix; ?>) {
	  live_poly_array<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
    }
  }
}
function live_clearLandmarkOverlays<?php echo $prefix; ?>() {
 
  if (live_landmark_array<?php echo $prefix; ?>) {
    for (i in live_landmark_array<?php echo $prefix; ?>) {
      live_landmark_array<?php echo $prefix; ?>[i].setMap(null);
    }
  }
  if (circleArray<?php echo $prefix; ?>) {
    for (i in circleArray<?php echo $prefix; ?>) {
      circleArray<?php echo $prefix; ?>[i].setMap(null);
    }
  }
}
function live_showLandmarkOverlays<?php echo $prefix; ?>() {
 
  if (live_landmark_array<?php echo $prefix; ?>) {
    for (i in live_landmark_array<?php echo $prefix; ?>) {
	  live_landmark_array<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
    }
  }
  if (circleArray<?php echo $prefix; ?>) {
    for (i in circleArray<?php echo $prefix; ?>) {
	  circleArray<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
    }
  }
}
function createLandmarkMarker<?php echo $time; ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/" + icon;}
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
	return new_marker;  
}
 
var plotDirection<?php echo $prefix; ?> = 0;
function drawRoute<?php echo $prefix; ?>(){
		if(plotDirection<?php echo $prefix; ?> < slArr<?php echo $prefix; ?>.length){
			i = plotDirection<?php echo $prefix; ?>;
			s1 = slArr<?php echo $prefix; ?>[i];
			e1 = elArr<?php echo $prefix; ?>[i];
			wp1 = wayPointsArr<?php echo $prefix; ?>[i];
			color = clrArr<?php echo $prefix; ?>[i];
			
			var polylineOptionsActual = new google.maps.Polyline({
				strokeColor: color,
				strokeOpacity: 1.0,
				strokeWeight: 4
				});

			dDisplay<?php echo $prefix; ?>[i] = new google.maps.DirectionsRenderer({polylineOptions: polylineOptionsActual});
			dDisplay<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
			var request1 = {
				origin:s1, 
				destination:e1,	
				waypoints: wp1,
				optimizeWaypoints: true,
				avoidHighways: true,
				avoidTolls: true,
				provideRouteAlternatives: false,
				unitSystem: google.maps.UnitSystem.METRIC,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			directionsService.route(request1, function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) 
				{
					var stepss = response.routes[0].legs;
					var path = response.routes[0].overview_path;
					for(var step = 0; step < stepss.length; step++)
					{
						stp = stepss[step].steps;
						for(var ss = 0; ss < stp.length; ss++)
						{
							polylineOptions = {
									map: mapmap<?php echo $prefix; ?>,
									strokeColor: color,
									strokeOpacity: 0.7,
									strokeWeight: 4,
									path: stp[ss].path
									//path: path,
							}
							routePolyArr<?php echo $prefix; ?>.push(new google.maps.Polyline(polylineOptions));				
						}
					}
				
					setTimeout("drawRoute<?php echo $prefix; ?>()", 200);
					plotDirection<?php echo $prefix; ?>++;
				}
				else {
					alert("An error occurred - " + status);
				}				  
			});	
			
		}else{
			plotDirection<?php echo $prefix; ?> = 0;
			<?php if($this->session->userdata('usertype_id') < 3){ ?>
			live_clearTripOverlays<?php echo $prefix; ?>();
			<?php } ?>
		}
  }
function live_clearTripOverlays<?php echo $prefix; ?>() {
   if (routePolyArr<?php echo $prefix; ?>) {
		for (i in routePolyArr<?php echo $prefix; ?>) {
			  routePolyArr<?php echo $prefix; ?>[i].setMap(null);
		}
	}
}
function live_showTripOverlays<?php echo $prefix; ?>() { 
	if (routePolyArr<?php echo $prefix; ?>) {
		for (i in routePolyArr<?php echo $prefix; ?>) {
			  routePolyArr<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
			}
	}  
}
function DrawCircle<?php echo $prefix; ?>(center, rad, dUnit, map) {
	if(dUnit == "KM")
		rad *= 1000; // convert to meters if in km
	if(dUnit == "Mile")
		rad *= (1000 * 1.609344); // convert to meters if in km
	if(dUnit == "Meter")
		rad = parseInt(rad); // convert to meters if in km
    /*if (draw_circle != null) {
        draw_circle.setMap(null);
    }*/
    draw_circle = new google.maps.Circle({
        center: center,
        radius: rad,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map
    });
	circleArray<?php echo $prefix; ?>.push(draw_circle);
}

function reloadMap(val){
	reload_Map<?php echo $prefix; ?>();
	if(Number(val)==1 || val=='1'){
		
		setTimeout(function(){
			markers_lat<?php echo $prefix; ?>.length=0;
			markers_lng<?php echo $prefix; ?>.length=0;
			 /*if (markersmap<?php echo $prefix; ?>) {
				for (i in markersmap<?php echo $prefix; ?>) {
				  markersmap<?php echo $prefix; ?>[i].setMap(null);
				}
			  }*/
			  var lastLat=0;
			  var lastLat1=0;
			  var lastlng = 0;
			  var lastlng1 = 0;
			  var lasthtm = 0;
			  var lasthtm1 = 0;
			  markersmap<?php echo $prefix; ?>.length=0;
			  if(lat<?php echo $prefix; ?>.length > 0){
			  lastLat=lat<?php echo $prefix; ?>[lat<?php echo $prefix; ?>.length-1];
			  lastlng=lng<?php echo $prefix; ?>[lng<?php echo $prefix; ?>.length-1];
			  lasthtm=html<?php echo $prefix; ?>[html<?php echo $prefix; ?>.length-1];
			  }
			  lat<?php echo $prefix; ?>.length = 0;
			  lng<?php echo $prefix; ?>.length = 0; 
			  html<?php echo $prefix; ?>.length = 0;
			  if(lastLat!=0){
				//  lat<?php echo $prefix; ?>.push(lastLat1);
				 // lng<?php echo $prefix; ?>.push(lastlng1);
				 // html<?php echo $prefix; ?>.push(lasthtm1);
				  lat<?php echo $prefix; ?>.push(lastLat);
				  lng<?php echo $prefix; ?>.push(lastlng);
				  html<?php echo $prefix; ?>.push(lasthtm);
			  }
			  
			  viewTrack<?php echo $prefix; ?>(lat<?php echo $prefix; ?>,lng<?php echo $prefix; ?>,html<?php echo $prefix; ?>, mapmap<?php echo $prefix; ?>, 0);
		},500);
		reloadMap_bool=true;
	}else{
		setTimeout(function(){
				if (directionsDisplay<?php echo $prefix; ?>) {
					for (i in directionsDisplay<?php echo $prefix; ?>) {
					  directionsDisplay<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
					}
				  }
				  if (markersmap<?php echo $prefix; ?>) {
					for (i in markersmap<?php echo $prefix; ?>) {
					  markersmap<?php echo $prefix; ?>[i].setMap(mapmap<?php echo $prefix; ?>);
					}
				  }
		},1000);
		reloadMap_bool=true;
	}
}
<?php /* // save as waypoint 
function saveAsWayPoint(Id){
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/live/getLandmarksList/id/"+Id,
		dataType: "json",
        success: function(data){
			if(data){
				//alert(data.to);
				$("#LandmarkId1<?php echo $time; ?>").html(data.opt);
				$("#LandmarkId2<?php echo $time; ?>").html(data.opt);
				$("#waypoint_id<?php echo $time; ?>").val(data.point);
				$("#LandmarkId1<?php echo $time; ?>").msDropDown();
				$("#LandmarkId2<?php echo $time; ?>").msDropDown();
				$("#error_<?php echo $time; ?>").removeClass("error");
				$("#error_<?php echo $time; ?>").html("");
				$("#error_<?php echo $time; ?>").hide();
				$("#waypoint_name<?php echo $time; ?>").val("");
				$("#getRouteWayPoint_dialog<?php echo $time; ?>").dialog("open");
			}
	    }
    });
	
}*/
?>
function saveInspection(trackId){
	$("#loading_top").css("display","block");
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/live/addToInspection/id/"+trackId,
        dataType: "json",
        success: function(data){
			if(data){
				$("#alert_dialog").html("<?php echo $this->lang->line("Record Stored Successfully"); ?>");
				$("#alert_dialog").dialog("open");
			}else{
				$("#alert_dialog").html("<?php echo $this->lang->line("Error Storing Record"); ?>");
				$("#alert_dialog").dialog("open");
			}
			$("#loading_top").css("display","none");
	    },
		error: function(request, status, err) {
           $("#loading_top").css("display","none");
		   $("#alert_dialog").html("<?php echo $this->lang->line("Error Storing Record"); ?>");
			$("#alert_dialog").dialog("open");
        }
	});
}
function getReloadDirections<?php echo $prefix; ?>(ji){
	var s1 = markersmap<?php echo $prefix; ?>[ji].position;
	var e1 = markersmap<?php echo $prefix; ?>[ji+1].position;
	// show route between the points
	
	directionsDisplay<?php echo $prefix; ?>[pointCounter] = new google.maps.DirectionsRenderer(rendererOptions);
	directionsDisplay<?php echo $prefix; ?>[pointCounter].setMap(mapmap<?php echo $prefix; ?>);
	var request = {
		origin:s1, 
		destination:e1,
		travelMode: google.maps.DirectionsTravelMode.DRIVING,
		avoidHighways: true,
		avoidTolls: true,
		provideRouteAlternatives: false
	};
	directionsService.route(request, function(response, status){
		if (status == google.maps.DirectionsStatus.OK) 
		{
			directionsDisplay<?php echo $prefix; ?>[pointCounter].setDirections(response);
		}
		if((markersmap<?php echo $prefix; ?>.length-2) > ji){
			getReloadDirections<?php echo $prefix; ?>(ji);
		}
	});				
}

function directRefresh_live<?php echo $prefix; ?>()
{
	//alert(landmark_as_waypnt<?php echo $prefix; ?>.toSource());
	$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
	DirectRefresh<?php echo $prefix; ?>();
}

function reload_Map<?php echo $prefix; ?>(){
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
		
		if(disp_landmark<?php echo $time; ?>==true){
			var trackNewWindowDiv = document.createElement('DIV');
			var trackNewWindowControl = new landmarkControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
			trackNewWindowDiv.index = 1;	
			mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
		}
		
		
	//Trip Button
		if(disp_route<?php echo $time; ?>==true){
			var trackNewWindowDiv = document.createElement('DIV');
			var trackNewWindowControl = new tripControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
			trackNewWindowDiv.index = 1;	
			mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
		}
		
		<?php if($this->session->userdata("usertype_id") < 3 ){ ?>
		//new window btn
		if(disp_area<?php echo $time; ?>==true){
			var trackNewWindowDiv = document.createElement('DIV');
			var trackNewWindowControl = new areaControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
			trackNewWindowDiv.index = 1;	
			mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
		}
		<?php } ?>
		//new window btn		
		
		
		var point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
		h_start<?php echo $prefix; ?> = point;
		var myTextDiv_top_left = document.createElement('div');
		myTextDiv_top_left.innerHTML = '<span>&nbsp;'+lastPoint_html<?php echo $prefix; ?>+'&nbsp;</span>';
		myTextDiv_top_left.style.color = 'white';
		myTextDiv_top_left.style.fontWeight = 'bold';
		myTextDiv_top_left.style.border = '1px solid black';
		myTextDiv_top_left.style.borderRadius = '3px';
		myTextDiv_top_left.style.padding = '3px';
		myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
		mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv_top_left);
		
		var ReConnecting_txt = document.createElement('div');	
		ReConnecting_txt.id = "Reconn_div<?php echo $prefix; ?>";
		mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(ReConnecting_txt);
		markers_lat<?php echo $prefix; ?>[markers_lat<?php echo $prefix; ?>.length-1];
		if(markers_lat<?php echo $prefix; ?>.length >= 2){
			lt1=markers_lat<?php echo $prefix; ?>[markers_lat<?php echo $prefix; ?>.length-1];
			lg1=markers_lng<?php echo $prefix; ?>[markers_lng<?php echo $prefix; ?>.length-1];
			lt2=markers_lat<?php echo $prefix; ?>[markers_lat<?php echo $prefix; ?>.length-2];
			lg2=markers_lng<?php echo $prefix; ?>[markers_lng<?php echo $prefix; ?>.length-2];
			///var pnt=new google.maps.LatLng(lt1,lg1);
			ang=(Math.atan2(lt1-lt2,lg1-lg2)*180)/Math.PI;
			image<?php echo $prefix; ?>.rotate(ang);
		}else{
			image<?php echo $prefix; ?>.rotate(<?php echo $angle; ?>);
		}
		
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
				,position: lastPoint<?php echo $prefix; ?>
				,closeBoxURL: ""
				,pane: "mapPane"
				,enableEventPropagation: true
			};
		
		//this will hold car div and will use to move this div on map.
		ib<?php echo $prefix; ?> = {}; 
		ib<?php echo $prefix; ?> = new InfoBox(myOptions);                
		ib<?php echo $prefix; ?>.open(mapmap<?php echo $prefix; ?>);

		var bounds = new google.maps.LatLngBounds();
		
		google.maps.event.addListener(mapmap<?php echo $prefix; ?>, "rightclick",function(event){
			showContextMenu(event.latLng);
		});
		google.maps.event.addListener(mapmap<?php echo $prefix; ?>, "click",function(event){
			$('.contextmenu').remove();
		});
  	}
}
function getHistory<?php echo $prefix; ?>(assets_id){
	$("#loading_top").css("display","block");
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/live/history/device/"+assets_id+"/id/"+h_id<?php echo $prefix; ?>,
        data: {  },
        dataType: "json",
        success: function(data) {
            // process data here
			if(data){
				hLatArr = data.lat;
				hLngArr = data.lng;
				hAddArr = data.html;
				h_id<?php echo $prefix; ?> = data.id;
				viewTrack<?php echo $prefix; ?>(hLatArr,hLngArr,hAddArr, mapmap<?php echo $prefix; ?>, 1);
				h_ways<?php echo $prefix; ?> = [];
				for(i=0; i<hLatArr.length; i++){
					
					if(i == (hLatArr.length) - 1)
						h_end<?php echo $prefix; ?> = new google.maps.LatLng(hLatArr[i],hLngArr[i]);
					else if(i%3 == 0){
					
						h_ways<?php echo $prefix; ?>.push({location:new google.maps.LatLng(parseFloat(hLatArr[i]), parseFloat(hLngArr[i]))});					
					}
				}
				var rendererOptionsH = {
					preserveViewport: true,
					draggable: false,
					suppressMarkers: true,
					polylineOptions: {
					   map: mapmap<?php echo $prefix; ?>,
					   strokeColor:'#FF0000',
					   //strokeWidth: 3,
					   strokeOpacity: 0.5}
					};
				directionsDisplay1<?php echo $prefix; ?>[h_id<?php echo $prefix; ?>] = new google.maps.DirectionsRenderer(rendererOptionsH);
				directionsDisplay1<?php echo $prefix; ?>[h_id<?php echo $prefix; ?>].setMap(mapmap<?php echo $prefix; ?>);
				var request<?php echo $prefix; ?> = {
					origin:h_end<?php echo $prefix; ?>, 
					destination:h_start<?php echo $prefix; ?>,
					travelMode: google.maps.DirectionsTravelMode.DRIVING,
					avoidHighways: true,
					avoidTolls: true,
					waypoints: h_ways<?php echo $prefix; ?>,
					provideRouteAlternatives: false
				};
				directionsService.route(request<?php echo $prefix; ?>, function(response, status) 
				{
					if (status == google.maps.DirectionsStatus.OK) 
					{
						directionsDisplay1<?php echo $prefix; ?>[h_id<?php echo $prefix; ?>].setDirections(response);
					}
				});
				mapmap<?php echo $prefix; ?>.setCenter(h_end<?php echo $prefix; ?>);
				h_start<?php echo $prefix; ?> = h_end<?php echo $prefix; ?>;
				map_focus_center<?php echo $time; ?> = false;
				$("#Focus_Anim_chk<?php echo $prefix; ?>").attr("checked",false);
				$("#loading_top").css("display","none");
				$("#history_<?php echo $prefix; ?>").html('History++')
			}
	    }
    });
	
}

function viewTrack<?php echo $prefix; ?>(lat, lng, html, mapmap, history){
	
	if(history != 1){
		//clearOverlays<?php echo time(); ?>(markersmap<?php echo $prefix; ?>, polylinesmap<?php echo $prefix; ?>);
	}

	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		var shadow = 'shadow50.png';
		var openInfo = false;
		var createMMarker = true;
		if(i == 0){	
			//var img = 'BLUE-START.png';
			createMMarker = true;
			var img = 'mini-RED-BLANK.png';
			
		}
		else if(i == (lat.length-1 && history != 1)){
			var img = 'BLUE-END.png';
			//openInfo = true;
			createMMarker = false;
		}
		else{
			var p1 = new google.maps.LatLng(lat[i-1], lng[i-1]);
			var p2 = new google.maps.LatLng(lat[i], lng[i]);
			var dir = bearing(p2, p1 );
			var dir = Math.round(dir/3) * 3;
			while (dir >= 120) {dir -= 120;}
			
			//var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+dir+".png";
			var img = 'mini-RED-BLANK.png';
			
		}
		if(createMMarker == true){
			markersmap<?php echo $prefix; ?>.push(createMarker_live(mapmap, point,"Marker Description",html[i], img, shadow, "sidebar_map", '',false));
		}
				
		if(i > 0){
			
			/*polylineCoordsmap<?php echo $prefix; ?>[i-1] = [
				new google.maps.LatLng(lat[i-1], lng[i-1])
			,
				new google.maps.La	tLng(lat[i], lng[i])
			];    	
			polylinesmap<?php echo $prefix; ?>[i-1] = new google.maps.Polyline({
			  path: polylineCoordsmap<?php echo $prefix; ?>[i-1]
			  , strokeColor: '#FF0000'
			  , strokeOpacity: 1.0
			  , strokeWeight: 2
			});
			polylinesmap<?php echo $prefix; ?>[i-1].setMap(mapmap);
			*/
		}
  	}
	//mapmap.setCenter(point);
}

function clearOverlays<?php echo time(); ?>(markersmap, polylinesmap) {  
  if (markersmap) {
    for (i in markersmap) {
      markersmap[i].setMap(null);
    }
  }
  if (polylinesmap) {
    for (i in polylinesmap) {
      polylinesmap[i].setMap(null);
    }
  }
}
var degreesPerRadian = 180.0 / Math.PI;
function bearing( from, to ) {
	// Convert to radians.
	var lat1 = from.lat();
	var lon1 = from.lng();
	var lat2 = to.lat();
	var lon2 = to.lng();
   
   var angle = - Math.atan2( Math.sin( lon1 - lon2 ) * Math.cos( lat2 ), Math.cos( lat1 ) * Math.sin( lat2 ) - Math.sin( lat1 ) * Math.cos( lat2 ) * Math.cos( lon1 - lon2 ) );
	if ( angle < 0.0 )
		angle  += Math.PI * 2.0;
	// And convert result to degrees.
	angle = angle * degreesPerRadian;
	angle = angle.toFixed(1);
	return angle;
}

var slArr<?php echo $prefix; ?> = [];
var elArr<?php echo $prefix; ?> = [];
var wayPointsArr<?php echo $prefix; ?> = [];
var clrArr<?php echo $prefix; ?> = [];
function loadRoute<?php echo $prefix; ?>(route_id,assets_id){
	if(route_id!=0){
	$.post(
	   "<?php echo site_url('home/loadRouteLive'); ?>",{'route_ids':route_id,'assets_id':assets_id},
	   function(data){
			points = [];
			
			for(i=0; i<data.coords.length; i++){
			
				var rId = data.coords[i].id;
				var rName = data.coords[i].routename;
				var rColor = data.coords[i].route_color;
				var rStart = data.coords[i].start_point.split(",");
				var rEnd = data.coords[i].end_point.split(",");
				var rWaypoints = data.coords[i].waypoints;
				var rPoints = data.coords[i].points;
				
				var start = new google.maps.LatLng(parseFloat(rStart[0]), parseFloat(rStart[1]));
				if(data.coords[i].round_trip == 1){
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
				
				wpts = [];
				for (var k=0; k<rWaypoints.length; k++) {
				  wpts = rWaypoints[k].split(",");
				  waypts.push({location:new google.maps.LatLng(parseFloat(wpts[0]), parseFloat(wpts[1]))});
				}
				
				slArr<?php echo $prefix; ?>[i] = start;
				elArr<?php echo $prefix; ?>[i] = end;
				wayPointsArr<?php echo $prefix; ?>[i] = waypts;
				clrArr<?php echo $prefix; ?>[i] = rColor;
			}
			drawRoute<?php echo $prefix; ?>();
			for(i=0; i<data.landmarks.length; i++){
				var text = data.landmarks[i].name+"<br>";
				text += data.landmarks[i].address+"<br>";
			
				var point = new google.maps.LatLng(data.landmarks[i].lat, data.landmarks[i].lng);		
				live_landmark_array<?php echo $prefix; ?>.push(createLandmarkMarker<?php echo $time; ?>(mapmap<?php echo $prefix; ?>, point, data.landmarks[i].name, text, data.landmarks[i].icon_path, '', "sidebar_map", '' ));
				<?php if($this->session->userdata('usertype_id') < 3){ ?>
				DrawCircle<?php echo $prefix; ?>(point, data.landmarks[i].radius, data.landmarks[i].distance_unit, mapmap<?php echo $prefix; ?>);				
				<?php } ?>
			}
		},'json');
	}
}

<?php if($this->session->userdata('usertype_id')==3){ ?>
function getDistanceFromMyLandmark(lat,lng){
	/*landmark_as_waypnt<?php echo $prefix; ?>_1.length=0;
	for(xp=0;xp<landmark_as_waypnt<?php echo $prefix; ?>.length;xp++){
		if(landmark_as_waypnt<?php echo $prefix; ?>.hasOwnProperty(xp)){
			if(landmark_as_waypnt<?php echo $prefix; ?>[xp]!=""){
				landmark_as_waypnt<?php echo $prefix; ?>_1.push(landmark_as_waypnt<?php echo $prefix; ?>[xp]);
			}
		}
	}*/
	var p1 = new google.maps.LatLng(lat,lng);
	var p2 = new google.maps.LatLng(dealerLat<?php echo $prefix; ?>,dealerLng<?php echo $prefix; ?>);
	var directS=new google.maps.DirectionsService();
	//var directS = new google.maps.DirectionsService();
	var req_direct={
		origin: p1,
		destination: p2,
		waypoints:landmark_as_waypnt<?php echo $prefix; ?>,
		optimizeWaypoints: true,
		avoidHighways: true,
		provideRouteAlternatives: false,
		avoidTolls: true,
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.METRIC,
		}
	directS.route(req_direct, function(response, status){
	 if (status != google.maps.DirectionsStatus.OK) {
		alert('Error was: ' + status);
	}else{
	//	alert(response.toSource());
		var dist=0;
		var duration=0;
		var duration1=0;
		var duration2=0;
		for(i=0;i<response.routes[0].legs.length;i++){
			dist+=response.routes[0].legs[0].distance.value;
			//duration+=response.routes[0].legs[0].duration.value;
		}
		dist=parseFloat(dist/1000).toFixed(2);
		var avg=40;
		(160/40)
		var duration1=parseInt(dist/avg);
		var duration2=parseFloat(parseFloat(dist/avg)-duration1).toFixed(2);
		duration=duration1+":"+parseInt((60*duration2));
		for(j=0; j< (mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.BOTTOM_LEFT].length); j++){
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.BOTTOM_LEFT].removeAt(j);
		}
		var myTextDiv_top_left = document.createElement('div');
		myTextDiv_top_left.innerHTML = '<span>&nbsp;Total Distance to Cover: '+dist+'&nbsp;KM , Remaining Time: '+duration+' Hours , Avg. Speed: '+avg+'&nbsp;KM&nbsp;</span>';
		myTextDiv_top_left.style.color = 'white';
		myTextDiv_top_left.style.fontWeight = 'bold';
		myTextDiv_top_left.style.border = '1px solid black';
		myTextDiv_top_left.style.borderRadius = '2px';
		myTextDiv_top_left.style.padding = '1px';
		myTextDiv_top_left.style.padding = '1px';
		myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
		mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(myTextDiv_top_left);		
		}
	});
}
<?php } ?>
function startLoading<?php echo $prefix; ?>(){
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/live/newPoint/device/<?php echo $prefix; ?>",
        data: {  id: last_id<?php echo $prefix; ?>, date_time: last_datetime<?php echo $prefix; ?>, route_id:route_id<?php echo $prefix?> },
        dataType: "json",
        timeout: 15000, // in milliseconds
        success: function(data) {
            // process data here
			
			if(data){
			
			if(data.view == "disable"){
				$("#pbar<?php echo $time; ?>").hide();
				clearTimeout(timer<?php echo $time; ?>);
				reloadDashboard_Assets_Timer();
				return false;
			}
			
			var lat = data.lat;
			var lng = data.lng;
			var gsm_lat = data.gsm_lat;
			var gsm_lng = data.gsm_lng;
			var ignition = data.ignition;
			var speed = data.speed;
			var html = data.html;
			var html_address = data.html_address;
		//	var driver_detail = data.driver_detail;
			var latLast, lngLast, htmlLast,htmlLast_addr;
			var completed=data.completed_landmarks_ids.split(",");
			<?php if($this->session->userdata('usertype_id')==3){ ?>
			for(ix=0;ix<completed.length;ix++){
				if(landmark_as_waypnt<?php echo $prefix; ?>.hasOwnProperty("l_"+completed[i])){
					landmark_as_waypnt<?php echo $prefix; ?>.splice("l_"+completed[i],1);
				}
			}
			<?php } ?>
			if(gsm_lat.length > 0){
				 
				for(i=0; i<gsm_lat.length; i++){
					if(gsm_lat[i] != "" && gsm_lng[i] != "" && gsm_lat[i] != null && gsm_lng[i] != null){
						if(gsm_text<?php echo $prefix; ?> != null)
							gsm_text<?php echo $prefix; ?>.setMap(null);
						if(gsm_circle<?php echo $prefix; ?> != null)
							gsm_circle<?php echo $prefix; ?>.setMap(null);
						if(gsmLandmark<?php echo $prefix; ?> != null)
							gsmLandmark<?php echo $prefix; ?>.setMap(null);
						
						var gsm_point = new google.maps.LatLng(gsm_lat[i], gsm_lng[i]);
						gsmLandmark<?php echo $prefix; ?> = createGSMLandmark<?php echo $prefix; ?>(mapmap<?php echo $prefix; ?>, gsm_point, "GSM","GSM", 'image-red.png', '', "sidebar_map", '' );
						GSMCircle<?php echo $prefix; ?>(gsm_point, mapmap<?php echo $prefix; ?>);
						
						if(lat[0] == null && lng[0] == null){
							$("#ignitionErr<?php echo $prefix; ?>").html('GSM Active.');
							$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
						}
					}
				}
			}
			if(lat.length > 0){
				 
				for(i=0; i<lat.length; i++){
					var point = new google.maps.LatLng(lat[i], lng[i]);
					//markersmap<?php echo $prefix; ?>.push(createMarker(mapmap<?php echo $prefix; ?>, point,"Marker Description",html[i], '', '', "sidebar_map", '' ));
					lat<?php echo $prefix; ?>.push(lat[i]);
					lng<?php echo $prefix; ?>.push(lng[i]);
					html<?php echo $prefix; ?>.push(html[i]);
					if(gsm_lat[0] == null && gsm_lng[0] == null){
						showIgnitionErr<?php echo $prefix; ?>(ignition[i],speed[i]);
					}else{
						$("#ignitionErr<?php echo $prefix; ?>").html('GSM and GPS Active.');
						$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
					}
					if(i==0){
						latLast = lat[i];
						lngLast = lng[i];
						htmlLast = html[i];
						htmlLast_addr = html_address[i];
					}
				}
				lastPoint<?php echo $prefix; ?> = new google.maps.LatLng(lat<?php echo $prefix; ?>[lat<?php echo $prefix; ?>.length-1], lng<?php echo $prefix; ?>[lng<?php echo $prefix; ?>.length-1]);
				<?php if($this->session->userdata('usertype_id')==3){ ?>
					setTimeout(function(){
						if(dealerLat<?php echo $prefix; ?>!=0 && dealerLat<?php echo $prefix; ?>!=""){
							getDistanceFromMyLandmark(lat<?php echo $prefix; ?>[lat<?php echo $prefix; ?>.length-1],lng<?php echo $prefix; ?>[lng<?php echo $prefix; ?>.length-1]);
						}
					},3000);
				<?php } ?>
				/*var myTextDiv = document.createElement('div');
				myTextDiv.innerHTML = '<h4>'+ htmlLast + '</h4>';
				myTextDiv.style.color = 'red';*/
				var myTextDiv_top_left = document.createElement('div');
				myTextDiv_top_left.innerHTML = '<span>'+htmlLast_addr+'&nbsp;</span>';
				lastPoint_html<?php echo $prefix; ?>=htmlLast_addr;
				myTextDiv_top_left.style.color = 'white';
				myTextDiv_top_left.style.fontWeight = 'bold';
				myTextDiv_top_left.style.border = '1px solid black';
				myTextDiv_top_left.style.borderRadius = '3px';
				myTextDiv_top_left.style.padding = '3px';
				myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
		
				for(j=0; j< (mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].length); j++){
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].removeAt(j);
				}
				mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv_top_left);
				
				//DRIVER DETAIL
			/*	if(driver_detail!=""){
					var driver_detail_top_left = document.createElement('div');
					driver_detail_top_left.innerHTML = '<span>&nbsp;'+driver_detail+'&nbsp;</span>';
					lastPoint_html<?php echo $prefix; ?>=driver_detail;
					driver_detail_top_left.style.color = 'black';
					driver_detail_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
					
					for(j=0; j< (mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.LEFT_TOP].length); j++){
						mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.LEFT_TOP].removeAt(j);
					}
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.LEFT_TOP].push(driver_detail_top_left);
				}*/
				lat1 = lat<?php echo $prefix; ?>[(lat<?php echo $prefix; ?>.length)-2]; 
				lat2 = lat<?php echo $prefix; ?>[(lat<?php echo $prefix; ?>.length)-1];
				lng1 = lng<?php echo $prefix; ?>[(lng<?php echo $prefix; ?>.length)-2]; 
				lng2 = lng<?php echo $prefix; ?>[(lng<?php echo $prefix; ?>.length)-1];
				var pointCounter = lat<?php echo $prefix; ?>.length;
				if(ignition[0] != 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}
				if(speed[0] > 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}
				if(last_ignition<?php echo $prefix; ?> == 1){
					viewTrack<?php echo $prefix; ?>(lat<?php echo $prefix; ?>,lng<?php echo $prefix; ?>,html<?php echo $prefix; ?>, mapmap<?php echo $prefix; ?>, 0);
				
					calcRoute<?php echo $prefix; ?>(lat1, lng1, lat2, lng2, pointCounter, html[0]);
				}
				if(speed[0] > 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}else{
					last_ignition<?php echo $prefix; ?> = ignition[0];
				}
				if(map_focus_center<?php echo $time; ?>==true){
					//mapmap<?php echo $prefix; ?>.setCenter(point);
				}
				last_id<?php echo $prefix; ?> = data.last_id;
				last_datetime<?php echo $prefix; ?> = data.last_datetime;
				setTimeout(function(){getAtt(data.assets_id+"_dot",data.TabImage);},500);
			}else{
				
				//update address part
				var myTextDiv_top_left = document.createElement('div');
				myTextDiv_top_left.innerHTML = '<span>'+html_address+'&nbsp;</span>';
				lastPoint_html<?php echo $prefix; ?>=html_address;
				myTextDiv_top_left.style.color = 'white';
				myTextDiv_top_left.style.fontWeight = 'bold';
				myTextDiv_top_left.style.border = '1px solid black';
				myTextDiv_top_left.style.borderRadius = '3px';
				myTextDiv_top_left.style.padding = '3px';
				myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
				for(j=0; j< (mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].length); j++){
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].removeAt(j);
				}
				mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv_top_left);
				setTimeout(function(){getAtt(data.assets_id+"_dot",data.TabImage);},500);
			}	
			
			if(timer_on<?php echo $time; ?>==1)
			{
				$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
				counter<?php echo $time; ?>();
			}
		}
	    },
        error: function(request, status, err) {
            if(status == "timeout") {
                //alert("timeOut");
            }
			reConnecting<?php echo $prefix; ?>(10);
        }
    });
}

function DirectRefresh<?php echo $prefix; ?>(){
/*	$.post("<?php echo base_url(); ?>index.php/live/newPoint/device/<?php echo $prefix; ?>", { id: last_id<?php echo $prefix; ?>, date_time: last_datetime<?php echo $prefix; ?> },
	 function(data) {
		if(data){
		}
	 }, 'json'
	);*/
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/live/newPoint/device/<?php echo $prefix; ?>",
        data: { id: last_id<?php echo $prefix; ?>, date_time: last_datetime<?php echo $prefix; ?>, route_id:route_id<?php echo $prefix?>},
        dataType: "json",
        timeout: 15000, // in milliseconds
        success: function(data) {
            // process data here
			if(data){			
				if(data.view == "disable"){
					$("#pbar<?php echo $time; ?>").hide();
					clearTimeout(timer<?php echo $time; ?>);
					reloadDashboard_Assets_Timer();
					return false;
				}
			var lat = data.lat;
			var lng = data.lng;
			var gsm_lat = data.gsm_lat;
			var gsm_lng = data.gsm_lng;
			var ignition = data.ignition;
			var speed = data.speed;
			var html = data.html;
			var html_address = data.html_address;
			var driver_detail = data.driver_detail;
			var completed=data.completed_landmarks_ids.split(",");
			<?php if($this->session->userdata('usertype_id')==3){ ?>
			for(ix=0;ix<completed.length;ix++){
				if(landmark_as_waypnt<?php echo $prefix; ?>.hasOwnProperty("l_"+completed[i])){
					landmark_as_waypnt<?php echo $prefix; ?>.splice("l_"+completed[i],1);
				}
			}
			<?php } ?>
			var latLast, lngLast, htmlLast,htmlLast_addr;
			
			if(gsm_lat.length > 0){
				 
				for(i=0; i<gsm_lat.length; i++){
					if(gsm_lat[i] != "" && gsm_lng[i] != "" && gsm_lat[i] != null && gsm_lng[i] != null){
						
						if(gsm_text<?php echo $prefix; ?> != null)
							gsm_text<?php echo $prefix; ?>.setMap(null);
						if(gsm_circle<?php echo $prefix; ?> != null)
							gsm_circle<?php echo $prefix; ?>.setMap(null);
						if(gsmLandmark<?php echo $prefix; ?> != null)
							gsmLandmark<?php echo $prefix; ?>.setMap(null);
					
						var gsm_point = new google.maps.LatLng(gsm_lat[i], gsm_lng[i]);
						gsmLandmark<?php echo $prefix; ?> = createGSMLandmark<?php echo $prefix; ?>(mapmap<?php echo $prefix; ?>, gsm_point, "GSM","GSM", 'image-red.png', '', "sidebar_map", '' );
						GSMCircle<?php echo $prefix; ?>(gsm_point, mapmap<?php echo $prefix; ?>);
						
						if(lat[0] == null && lng[0] == null){
							$("#ignitionErr<?php echo $prefix; ?>").html('GSM Active.');
							$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
						}
					}
				}
			}
			if(lat.length > 0){
				 
				for(i=0; i<lat.length; i++){
					var point = new google.maps.LatLng(lat[i], lng[i]);
					lat<?php echo $prefix; ?>.push(lat[i]);
					lng<?php echo $prefix; ?>.push(lng[i]);
					html<?php echo $prefix; ?>.push(html[i]);
					if(gsm_lat[0] == null && gsm_lng[0] == null){
						showIgnitionErr<?php echo $prefix; ?>(ignition[i],speed[i]);
					}else{
						$("#ignitionErr<?php echo $prefix; ?>").html('GPS and GSM Active.');
						$("#ignitionErr<?php echo $prefix; ?>").css('display','block');
					}
					
					if(i==0){
						latLast = lat[i];
						lngLast = lng[i];
						htmlLast = html[i];
						htmlLast_addr = html_address[i];
					}
				}
				lastPoint<?php echo $prefix; ?> = new google.maps.LatLng(lat<?php echo $prefix; ?>[lat<?php echo $prefix; ?>.length-1], lng<?php echo $prefix; ?>[lng<?php echo $prefix; ?>.length-1]);
				<?php if($this->session->userdata('usertype_id')==3){ ?>
					setTimeout(function(){
						if(dealerLat<?php echo $prefix; ?>!=0 && dealerLat<?php echo $prefix; ?>!=""){
							getDistanceFromMyLandmark(lat<?php echo $prefix; ?>[lat<?php echo $prefix; ?>.length-1],lng<?php echo $prefix; ?>[lng<?php echo $prefix; ?>.length-1]);
						}
					},3000);
				<?php } ?>
				lastPoint_html<?php echo $prefix; ?>=htmlLast_addr;
				var myTextDiv_top_left = document.createElement('div');
				myTextDiv_top_left.innerHTML = '<span>'+htmlLast_addr+'&nbsp;</span>';
				myTextDiv_top_left.style.color = 'white';
				myTextDiv_top_left.style.fontWeight = 'bold';
				myTextDiv_top_left.style.border = '1px solid black';
				myTextDiv_top_left.style.borderRadius = '3px';
				myTextDiv_top_left.style.padding = '3px';
				myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
				for(j=0; j< (mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].length); j++){
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].removeAt(j);
				}
				mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv_top_left);
				
				
				lat1 = lat<?php echo $prefix; ?>[(lat<?php echo $prefix; ?>.length)-2]; 
				lat2 = lat<?php echo $prefix; ?>[(lat<?php echo $prefix; ?>.length)-1];
				lng1 = lng<?php echo $prefix; ?>[(lng<?php echo $prefix; ?>.length)-2]; 
				lng2 = lng<?php echo $prefix; ?>[(lng<?php echo $prefix; ?>.length)-1];
				var pointCounter = lat<?php echo $prefix; ?>.length;
				if(ignition[0] != 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}
				if(speed[0] > 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}
				if(last_ignition<?php echo $prefix; ?> == 1){
					viewTrack<?php echo $prefix; ?>(lat<?php echo $prefix; ?>,lng<?php echo $prefix; ?>,html<?php echo $prefix; ?>, mapmap<?php echo $prefix; ?>, 0);
				
					calcRoute<?php echo $prefix; ?>(lat1, lng1, lat2, lng2, pointCounter, html[0]);
				}
				if(speed[0] > 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}else{
					last_ignition<?php echo $prefix; ?> = ignition[0];
				}
				//mapmap<?php echo $prefix; ?>.setCenter(point);
				
				last_id<?php echo $prefix; ?> = data.last_id;
				last_datetime<?php echo $prefix; ?> = data.last_datetime;
				setTimeout(function(){getAtt(data.assets_id+"_dot",data.TabImage);},500);
			}else{				
				//update address part
				lastPoint_html<?php echo $prefix; ?>=html_address;
				var myTextDiv_top_left = document.createElement('div');
				myTextDiv_top_left.innerHTML = '<span>'+html_address+'&nbsp;</span>';
				myTextDiv_top_left.style.color = 'white';
				myTextDiv_top_left.style.fontWeight = 'bold';
				myTextDiv_top_left.style.border = '1px solid black';
				myTextDiv_top_left.style.borderRadius = '3px';
				myTextDiv_top_left.style.padding = '3px';
				myTextDiv_top_left.style.backgroundColor = 'rgba(0,0,0,0.4)';
				for(j=0; j< (mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].length); j++){
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].removeAt(j);
				}
				mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv_top_left);
				setTimeout(function(){getAtt(data.assets_id+"_dot",data.TabImage);},500);
			}
		}
	    },
        error: function(request, status, err) {
            if(status == "timeout") {
                //alert("timeOut");
            }
			reConnecting<?php echo $prefix; ?>(10);
        }
    });
}

function calcRoute<?php echo $prefix; ?>(lat1, lng1, lat2, lng2, pointCounter, html1){
		reloadMap_bool=false;
		//$("#helpRightClick<?php echo $prefix; ?>").css("display","none");
		//$("#helpRightClick<?php echo $prefix; ?>").html("1");
		loop<?php echo $prefix; ?>=0,j,x;
		markers_lat<?php echo $prefix; ?>=[];
		markers_lng<?php echo $prefix; ?>=[];
		ib<?php echo $prefix; ?>;

		var s1 = new google.maps.LatLng(lat1, lng1);
		var e1 = new google.maps.LatLng(lat2, lng2);
		// show route between the points
		
		directionsDisplay<?php echo $prefix; ?>[pointCounter] = new google.maps.DirectionsRenderer(rendererOptions);
		directionsDisplay<?php echo $prefix; ?>[pointCounter].setMap(mapmap<?php echo $prefix; ?>);
		var request = {
			origin:s1, 
			destination:e1,
			travelMode: google.maps.DirectionsTravelMode.DRIVING,
			avoidHighways: true,
			avoidTolls: true,
			provideRouteAlternatives: false
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay<?php echo $prefix; ?>[pointCounter].setDirections(response);
				ren<?php echo $prefix; ?> = directionsDisplay<?php echo $prefix; ?>[pointCounter];
				y=0;
				for(i=0;i<ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps.length;i++)
				{
					for(j=0;j<ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path.length;j++)
					{
							if(markers_lat<?php echo $prefix; ?>[y-1]!=ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lat() && markers_lng<?php echo $prefix; ?>[y-1]!=ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lng())
							{
							markers_lat<?php echo $prefix; ?>.push(ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lat());
							markers_lng<?php echo $prefix; ?>.push(ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lng());
							}
					}
				}

				if(device_anim<?php echo $time; ?>==true)
				{				
					ib<?php echo $prefix; ?>.setPosition(ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[0].path[0]);
					 if($("#timer_toggle<?php echo $time; ?>").css("display") == "inline" || $("#timer_toggle<?php echo $time; ?>").css("display") == "inline-block")
					{
				
						//if(reloadMap_bool==false){
						//	alert("1. calling test" + reloadMap_bool);
							calltest<?php echo $prefix; ?>();
						//	alert("test Called");
						//}
					}
				}
				else{
					lt1=markers_lat<?php echo $prefix; ?>[markers_lat<?php echo $prefix; ?>.length-1];
					lg1=markers_lng<?php echo $prefix; ?>[markers_lng<?php echo $prefix; ?>.length-1];
					lt2=markers_lat<?php echo $prefix; ?>[markers_lat<?php echo $prefix; ?>.length-2];
					lg2=markers_lng<?php echo $prefix; ?>[markers_lng<?php echo $prefix; ?>.length-2];
					var pnt=new google.maps.LatLng(lt1,lg1);
					ang=(Math.atan2(lt1-lt2,lg1-lg2)*180)/Math.PI;
					image<?php echo $prefix; ?>.rotate(ang);
					ib<?php echo $prefix; ?>.close();
					ib<?php echo $prefix; ?>["content_"]=$("#imgR<?php echo $prefix; ?>").html();
					
					ib<?php echo $prefix; ?>.open(mapmap<?php echo $prefix; ?>);
					//alert("thakur badlay gyo animate na else ma .");
					ib<?php echo $prefix; ?>.setPosition(pnt);
				}	
				
			}else{
				//alert("Direction Not Found");		
				//alert('<?php echo $angle; ?>');
				image<?php echo $prefix; ?>.rotate(<?php echo $angle; ?>);
				ib<?php echo $prefix; ?>.close();
				ib<?php echo $prefix; ?>["content_"]=$("#imgR<?php echo $prefix; ?>").html();
				
				ib<?php echo $prefix; ?>.open(mapmap<?php echo $prefix; ?>);
				ib<?php echo $prefix; ?>.setPosition(e1);
				mapmap<?php echo $prefix; ?>.setCenter(e1);
			}
		});
}
function calltest<?php echo $prefix; ?>()
{
	//$("#helpRightClick<?php echo $prefix; ?>").append(".2");	
	//alert("2. Calltest Called " + reloadMap_bool);
	//alert(" CALL - "+ib<?php echo $prefix; ?>.getPosition());
	lt1<?php echo $prefix; ?>=markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>];
	ln1<?php echo $prefix; ?>=markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>];
	lt2<?php echo $prefix; ?>=markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>+1];
	ln2<?php echo $prefix; ?>=markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>+1];
	if((loop<?php echo $prefix; ?>+1)<markers_lat<?php echo $prefix; ?>.length)
	{
		if(loop<?php echo $prefix; ?>!=0 && markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>-1]==markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>] && markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>-1]==markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>])
		{
			loop<?php echo $prefix; ?>+=2;
		}
		else
		{
			loop<?php echo $prefix; ?>++;
		}
//		i++;
		if(map_focus_center<?php echo $time; ?>==true){
				//mapmap<?php echo $prefix; ?>.setCenter(latlngs[index]);
				//	mapmap<?php echo $prefix; ?>.setCenter(new google.maps.LatLng(markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>], markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>]));
			}
	
		ang=(Math.atan2(ln2<?php echo $prefix; ?>-ln1<?php echo $prefix; ?>,lt2<?php echo $prefix; ?>-lt1<?php echo $prefix; ?>)*180)/Math.PI;
		image<?php echo $prefix; ?>.rotate(ang);	
	 
		ib<?php echo $prefix; ?>["content_"]=$("#imgR<?php echo $prefix; ?>").html();
		ib<?php echo $prefix; ?>.open(mapmap<?php echo $prefix; ?>);
		if($("#timer_toggle<?php echo $time; ?>").css("display") == "inline" || $("#timer_toggle<?php echo $time; ?>").css("display") == "inline-block")
		{
			//alert("3. after Calltest Called calling Test()" + reloadMap_bool);
		//	if(reloadMap_bool==false){
				test<?php echo $prefix; ?>(lt1<?php echo $prefix; ?>,ln1<?php echo $prefix; ?>,lt2<?php echo $prefix; ?>,ln2<?php echo $prefix; ?>);
		
			//}
		}
	}
	/*else
	{
		alert("Destination Arrived.!!");
	}
	*/
	
}
//this will get two points (lat1,lng1) and (lat2,lng2) and animate from first point to second
function test<?php echo $prefix; ?>(lat1,lng1,lat2,lng2)
{		 
		//$("#helpRightClick<?php echo $prefix; ?>").append(".3");
		fromLat = lat1;
          fromLng = lng1;
          toLat = lat2;
          toLng = lng2;
		  //alert("TEST - >"+ib<?php echo $prefix; ?>.getPosition());
          // store a LatLng for each step of the animation
          frames<?php echo $prefix; ?> = [];
          for (var percent = 0; percent < 1; percent += 0.02) {
            curLat = fromLat + percent * (toLat - fromLat);
            curLng = fromLng + percent * (toLng - fromLng);
            frames<?php echo $prefix; ?>.push(new google.maps.LatLng(curLat, curLng));
          }

          move<?php echo $prefix; ?> = function(ib<?php echo $prefix; ?>, latlngs, index, wait, newDestination) {
			//if(reloadMap_bool==false){
			//	alert("5 if");
				ib<?php echo $prefix; ?>.setPosition(latlngs[index]);
				if(map_focus_center<?php echo $time; ?>==true){
					mapmap<?php echo $prefix; ?>.setCenter(latlngs[index]);
					//alert("5 if true");
				}
				if(index != latlngs.length-1) {
				  // call the next "frame" of the animation
			//	  alert("6 if true");
					if(reloadMap_bool==false){
					  setTimeout(function() {
						move<?php echo $prefix; ?>(ib<?php echo $prefix; ?>, latlngs, index+1, wait, newDestination);
					  }, wait);
					}else{
						setTimeout(function(){
							mapmap<?php echo $prefix; ?>.setCenter(lastPoint<?php echo $prefix; ?>);
						},1000);
					}
				  
				}
				else {
			//	alert("6 if false");
				  ib<?php echo $prefix; ?>.position = ib<?php echo $prefix; ?>.destination;
				  ib<?php echo $prefix; ?>.destination = newDestination;
				  //this will call calltest when first point to second point animation done.
				  if($("#timer_toggle<?php echo $time; ?>").css("display") == "inline" || $("#timer_toggle<?php echo $time; ?>").css("display") == "inline-block")
					{
					calltest<?php echo $prefix; ?>();
					}
				}
			/*}else{
				//alert(markers_lat<?php echo $prefix; ?>.length);
				
				setTimeout(function(){
					reloadMap_bool=false;
				},500);				
			}*/
          }

          // begin animation, send back to origin after completion
		  
	if(reloadMap_bool==false){
		move<?php echo $prefix; ?>(ib<?php echo $prefix; ?>, frames<?php echo $prefix; ?>, 0, 20, ib<?php echo $prefix; ?>.position);
		//$("#helpRightClick<?php echo $prefix; ?>").append(".4");
		}else{
			//$("#helpRightClick<?php echo $prefix; ?>").append(".-4-");
			//reloadMap_bool=false;
		}
}

function submitFormdevice_waypoint(){
	$("#loading_top").css("display","block");
	var landmark1=$("#LandmarkId1<?php echo $time; ?>").val();
	var landmark2=$("#LandmarkId2<?php echo $time; ?>").val();
	var waypoint=$("#waypoint_id<?php echo $time; ?>").val();
	var waypoint_name=$.trim($("#waypoint_name<?php echo $time; ?>").val());
	$("#error_<?php echo $time; ?>").removeClass("error");
	$("#error_<?php echo $time; ?>").html("");
	$("#error_<?php echo $time; ?>").hide();
	if(landmark1!=landmark2 && waypoint!="" && waypoint_name!=""){
	$.post("<?php echo base_url(); ?>index.php/live/insertWaypoint",{"landmark1":landmark1,"landmark2":landmark2,"waypoint":waypoint,"waypoint_name":waypoint_name},function(data){
		$("#loading_top").css("display","none");
		if(data){
			$("#alert_dialog").html("<?php echo $this->lang->line("Record Stored Successfully"); ?>");
			$("#alert_dialog").dialog("open");
			$("#getRouteWayPoint_dialog<?php echo $time; ?>").dialog("close");
		}else{
			$("#alert_dialog").html("<?php echo $this->lang->line("Waypoint Already Exist"); ?>");
			$("#alert_dialog").dialog("open");
		}
	});
	}else if(waypoint_name==""){
		$("#error_<?php echo $time; ?>").html("<?php echo $this->lang->line('Waypoint Name is Requireds'); ?>");
		$("#error_<?php echo $time; ?>").addClass("error");
		$("#error_<?php echo $time; ?>").show();
	}else if(landmark1==landmark2){
		$("#error_<?php echo $time; ?>").html("<?php echo $this->lang->line('Both Landmark Should be Different'); ?>");
		$("#error_<?php echo $time; ?>").addClass("error");
		$("#error_<?php echo $time; ?>").show();
	}else{
		$("#error_<?php echo $time; ?>").html("<?php echo $this->lang->line('Point Not Found, Please try Letter'); ?>");
		$("#error_<?php echo $time; ?>").addClass("error");
		$("#error_<?php echo $time; ?>").show();
	}
	return false;
}
function close_waypnt(){
	$("#getRouteWayPoint_dialog<?php echo $time; ?>").dialog("close");
}
function reConnecting<?php echo $prefix; ?>(i){
	if($("#timer_toggle<?php echo $time; ?>").css("display") == "inline" || $("#timer_toggle<?php echo $time; ?>").css("display") == "inline-block")
	{
		if(timer_on<?php echo $time; ?>==1)
			{
		if(i!=0){
			
				$("#Reconn_div<?php echo $prefix; ?>").html("Reconnecting in "+i+" Seconds");
				i--;
				setTimeout(function(){
					reConnecting<?php echo $prefix; ?>(i);
				},1000);
		}
		if(i==0)
		{
			/*clearTimeout(timer<?php echo $time; ?>);
			timer_on<?php echo $time; ?>=0;
			$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
			*/
			$("#Reconn_div<?php echo $prefix; ?>").html("");
			//$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
			if(timer_on<?php echo $time; ?>==1){
				//counter<?php echo $time; ?>();
				clearTimeout(timer<?php echo $time; ?>);
				$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
				counter<?php echo $time; ?>();
			}
		}
		}else{
			$("#Reconn_div<?php echo $prefix; ?>").html("");
		}
	}
}
function addContextMenu(){
	google.maps.event.addListener(mapmap<?php echo $prefix; ?>, "rightclick",function(event){
			showContextMenu(event.latLng);
		});
	google.maps.event.addListener(mapmap<?php echo $prefix; ?>, "click",function(event){
		$('.contextmenu').remove();
	});
}

function tripControlLive<?php echo $prefix; ?>(controlDiv, map){
  controlDiv.style.padding = '5px';
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Show/Hide Trip';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkTripLive<?php echo $prefix; ?>";
  <?php if($this->session->userdata("usertype_id") > 2){ ?>
	chkOne.checked = true;
	live_showTripOverlays<?php echo $prefix; ?>();
  <?php }else{ ?>
	chkOne.checked = false;
  <?php } ?>
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkTripLive<?php echo $prefix; ?>";
  
  lblOne.appendChild(document.createTextNode('Trip'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkTripLive<?php echo $prefix; ?>").attr("checked") == "checked") {
		live_showTripOverlays<?php echo $prefix; ?>();
		
	}else{
		live_clearTripOverlays<?php echo $prefix; ?>();
	}
  });
}
function landmarkControlLive<?php echo $prefix; ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Show/Hide Landmark';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkLandmarkLive<?php echo $prefix; ?>";
  <?php if($this->session->userdata("usertype_id") > 2){ ?>
	chkOne.checked = true;
	live_showLandmarkOverlays<?php echo $prefix; ?>();
  <?php }else{ ?>
	chkOne.checked = false;
  <?php } ?>
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkLandmarkLive<?php echo $prefix; ?>";
  
  lblOne.appendChild(document.createTextNode('Landmark'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkLandmarkLive<?php echo $prefix; ?>").attr("checked") == "checked") {
		live_showLandmarkOverlays<?php echo $prefix; ?>();		
	}else{
		live_clearLandmarkOverlays<?php echo $prefix; ?>();
	}
  });
}
var timer_on<?php echo $time; ?>=0;
var timer<?php echo $time; ?>;
var time_in_s<?php echo $time; ?>;
var current<?php echo $time; ?> ;
var percentage<?php echo $time; ?>;
function stop_resume_toggle<?php echo $time; ?>()
{
	time_in_s<?php echo $time; ?>=Number($("#time_in_seconds<?php echo $time; ?>").val());
	if(timer_on<?php echo $time; ?>==1)
	{
		clearTimeout(timer<?php echo $time; ?>);
		timer_on<?php echo $time; ?>=0;
		$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
	}	
	else
	{
		counter<?php echo $time; ?>();
		timer_on<?php echo $time; ?>=1;
	}
}
function counter<?php echo $time; ?>()
{
	if($("#seconds<?php echo $time; ?>").html() == 0){
		clearTimeout(timer<?php echo $time; ?>);
		startLoading<?php echo $prefix; ?>();
	}
	else{
		/*
		current<?php echo $time; ?>=Number($("#seconds<?php echo $time; ?>").html());
		percentage<?php echo $time; ?> = Number(current<?php echo $time; ?>/(time_in_s<?php echo $time; ?>)*100)-Number(0.99/(time_in_s<?php echo $time; ?>)*100);
		val<?php echo $time; ?>=100-percentage<?php echo $time; ?>;
	
		$("#pbar<?php echo $time; ?>").progressbar("value" , val<?php echo $time; ?>);
		*/
		$("#seconds<?php echo $time; ?>").html(Number($("#seconds<?php echo $time; ?>").html())-1);
		
		if($("#timer_toggle<?php echo $time; ?>").css("display") == "inline" || $("#timer_toggle<?php echo $time; ?>").css("display") == "inline-block")
		{
			timer<?php echo $time;?> = setTimeout('counter<?php echo $time; ?>()',1000);
		}
		else
		{
			clearTimeout(timer<?php echo $time; ?>);
		}
	}
}
function counter_change<?php echo $time; ?>()
{
	if(Number($("#time_in_seconds<?php echo $time; ?>").val())<1)
		$("#time_in_seconds<?php echo $time; ?>").val(60);
	$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
	time_in_s<?php echo $time; ?> = $("#time_in_seconds<?php echo $time; ?>").val();
}
function getCanvasXY(caurrentLatLng){
	var scale = Math.pow(2, mapmap<?php echo $prefix; ?>.getZoom());
	var nw = new google.maps.LatLng(
	 mapmap<?php echo $prefix; ?>.getBounds().getNorthEast().lat(),
	 mapmap<?php echo $prefix; ?>.getBounds().getSouthWest().lng()
	);
	var worldCoordinateNW = mapmap<?php echo $prefix; ?>.getProjection().fromLatLngToPoint(nw);
	var worldCoordinate = mapmap<?php echo $prefix; ?>.getProjection().fromLatLngToPoint(caurrentLatLng);
	var caurrentLatLngOffset = new google.maps.Point(
	 Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
	 Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
	);
	return caurrentLatLngOffset;
}
function setMenuXY(caurrentLatLng){
	var mapWidth = $('#map<?php echo $prefix; ?>').width();
	var mapHeight = $('#map<?php echo $prefix; ?>').height();
	var menuWidth = $('.contextmenu').width();
	var menuHeight = $('.contextmenu').height();
	var clickedPosition = getCanvasXY(caurrentLatLng);
	var x = clickedPosition.x ;
	var y = clickedPosition.y ;

	 if((mapWidth - x ) < menuWidth)
		 x = x - menuWidth;
	if((mapHeight - y ) < menuHeight)
		y = y - menuHeight;

	$('.contextmenu').css('left',x);
	$('.contextmenu').css('top',y );
};
function showContextMenu(caurrentLatLng) {
	var projection;   
	projection = mapmap<?php echo $prefix; ?>.getProjection() ;
	$('.contextmenu').remove();
	  contextmenuDir = document.createElement("div");
	  contextmenuDir.className  = 'contextmenu';
	  contextmenuDir.innerHTML = "<a id`='reloadMap_live' onClick='reloadMap(0);'><div class=context>Reload Map<\/div><\/a><a id`='reloadMap_live_clear' onClick='reloadMap(1);'><div class=context>Reload & Clear Map<\/div><\/a>";
	$(mapmap<?php echo $prefix; ?>.getDiv()).append(contextmenuDir);
	setMenuXY(caurrentLatLng);
	contextmenuDir.style.visibility = "visible";
}

function callLandmarksLoop(landmarks,k){
	var distanceUnit=landmarks.distance_unit;
			var landmarkHtml="";
			landmarkHtml+="Name : "+landmarks.name;
			landmarkHtml+="<br/>Address : "+landmarks.address;
			landmarkHtml+="<br/>Assets : "+landmarks.assets;
	setTimeout(function(){
		var point = new google.maps.LatLng(parseFloat(landmarks.lat),parseFloat(landmarks.lng));
		<?php if($this->session->userdata('usertype_id')==3){ ?>
			landmark_as_waypnt<?php echo $prefix; ?>["l_"+landmarks.id]=
			{location:new google.maps.LatLng(parseFloat(landmarks.lat),parseFloat(landmarks.lng))};					
		<?php } ?>
		live_landmark_array<?php echo $prefix; ?>.push(createLandmarkMarker<?php echo $time; ?>(mapmap<?php echo $prefix; ?>, point, landmarks.name,landmarkHtml, landmarks.icon_path, '', "sidebar_map", '' ));
		
		<?php if($this->session->userdata('usertype_id') < 3){ ?>
			DrawCircle<?php echo $prefix; ?>(point, landmarks.radius, distanceUnit, mapmap<?php echo $prefix; ?>);
		<?php } ?>
	},k);
	
}
function areaControlLive<?php echo $prefix; ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Show/Hide Area';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkAreaLive<?php echo $prefix; ?>";
  chkOne.checked = false;
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkAreaLive<?php echo $prefix; ?>";
  
  lblOne.appendChild(document.createTextNode('Area'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkAreaLive<?php echo $prefix; ?>").attr("checked") == "checked") {
		live_showPolyOverlays<?php echo $prefix; ?>();
		
	}else{
		live_clearPolyOverlays<?php echo $prefix; ?>();
	}
  });
}
function loadAreas<?php echo $prefix; ?>(id){
	$("#pbar_Loading_<?php echo $prefix; ?>").html("Loading Area... <span style='color:blue;cursor:pointer;' onClick='cancelLoading<?php echo $prefix; ?>();'>Cancel</span>");
	var label<?php echo $prefix; ?> = new Array();
	var live_polyV<?php echo $prefix; ?> = new Array();
	$loadingDynamicObj<?php echo $prefix; ?> = $.ajax({
        type: "GET",
        url: "<?php echo base_url(); ?>index.php/live/loadArea/id/"+id,
        dataType: "json",
        success: function(data) {
			for(i=0;i<data.pplyId.length;i++){
				var pId=data.pplyId[i];
				var polygonCoords = new Array();
				var bounds = new google.maps.LatLngBounds();
				for(j=0;j<data.pplyLat[pId].length;j++){
					data.pplyLat[pId].lat,data.pplyLng[pId][j]
					polygonCoords.push(new google.maps.LatLng(parseFloat(data.pplyLat[pId][j]), parseFloat(data.pplyLng[pId][j])));
				}
				for (x = 0; x < polygonCoords.length; x++) {
				  bounds.extend(polygonCoords[x]);
				}
				//alert(i+"-> elable_<?php echo $prefix; ?>_"+i+","+ pId);
				label<?php echo $prefix; ?>[i] = new ELabel({
					latlng: bounds.getCenter(),
					label: "<div class='elable' id='elable_<?php echo $prefix; ?>_"+i+"' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'>"+data.pplyName[pId]+"</div>", 
					classname: "label", 
					offset: 0, 
					opacity: 100, 
					overlap: true,
					clicktarget: false
				});
				
				live_polyV<?php echo $prefix; ?>[i] = new google.maps.Polygon({
				  paths: polygonCoords,
				  strokeWeight: 2,
				  strokeOpacity : 0.6,
				  fillColor: data.pplyColor[pId]
				});
				
				live_poly_array<?php echo $prefix; ?>.push(live_polyV<?php echo $prefix; ?>[i]);
				with ({ foo: i }){	
					google.maps.event.addListener(live_polyV<?php echo $prefix; ?>[foo],"mouseover",function(event){
						label<?php echo $prefix; ?>[foo].setMap(mapmap<?php echo $prefix; ?>);
						
						$("#elable_<?php echo $prefix; ?>_"+foo).parent().parent().css('z-index','99999');
					});
					google.maps.event.addListener(live_polyV<?php echo $prefix; ?>[foo],"mouseout",function(event){
						label<?php echo $prefix; ?>[foo].setMap(null);
					});
				}
				google.maps.event.addListenerOnce(mapmap<?php echo $prefix; ?>, 'idle', function() {
					google.maps.event.trigger(mapmap<?php echo $prefix; ?>, 'resize');
					//map<?php echo time(); ?>.setCenter(point); // be sure to reset the map center as well
				});
			}
			<?php if($this->session->userdata('show_map_area_button')==1){ ?>
			//new window btn		
			var trackNewWindowDiv = document.createElement('DIV');
			var trackNewWindowControl = new areaControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
			trackNewWindowDiv.index = 1;	
			mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
			<?php } ?>
			$("#pbar_Loading_<?php echo $prefix; ?>").html("Area Loading Complete ...");
			contentLoadComplete();
		},
        error: function(request, status, err){
			$("#pbar_Loading_<?php echo $prefix; ?>").html("Area Loading Canceled.");
			disp_area<?php echo $time; ?>=false;
			contentLoadComplete();
        }
    });
}

function loadLandmarks<?php echo $prefix; ?>(id){
	$loadingDynamicObj<?php echo $prefix; ?> = $.ajax({
        type: "GET",
        url: "<?php echo base_url(); ?>index.php/live/loadLandmarks/id/"+id,
        dataType: "json",
        success: function(data) {
			var k=100;
			for(i=0;i<data.landmarks.length;i++){
				callLandmarksLoop(data.landmarks[i],k);
				k=k+100;
			}
			setTimeout(function(){
				<?php if($this->session->userdata('show_map_landmark_button')==1){ ?>
				var trackNewWindowDiv = document.createElement('DIV');
				var trackNewWindowControl = new landmarkControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
				trackNewWindowDiv.index = 1;	
				mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
				<?php } ?>
				$("#pbar_Loading_<?php echo $prefix; ?>").html("Landmarks Loading Complete ...");
				setTimeout(function(){
					loadRoutes<?php echo $prefix; ?>(<?php echo $prefix; ?>);
					//live_clearLandmarkOverlays<?php echo $prefix; ?>();
				},1000);
				
			},k+500);
			<?php if($this->session->userdata('usertype_id')==3){ ?>
			strLatLng<?php echo $prefix; ?>='<?php echo $dealerLandmark_latlng; ?>';
			var arr=strLatLng<?php echo $prefix; ?>.split(";");
			if(arr.length>1){
				alert("found array");
			}else{
				var arrk=strLatLng<?php echo $prefix; ?>.split(":");
				dealerLat<?php echo $prefix; ?>=arrk[0];
				dealerLng<?php echo $prefix; ?>=arrk[1];
			}
			<?php } ?>
		},
        error: function(request, status, err){
			$("#pbar_Loading_<?php echo $prefix; ?>").html("Landmarks Loading Canceled.");
			disp_landmark<?php echo $time; ?>=false;
			setTimeout(function(){
				loadRoutes<?php echo $prefix; ?>(<?php echo $prefix; ?>);
			},1000);
        }
    });
}
function loadRoutes<?php echo $prefix; ?>(id){
	live_clearLandmarkOverlays<?php echo $prefix; ?>();
	$("#pbar_Loading_<?php echo $prefix; ?>").html("Loading Routes... <span style='color:blue;cursor:pointer;' onClick='cancelLoading<?php echo $prefix; ?>();'>Cancel</span>");
	$loadingDynamicObj<?php echo $prefix; ?> = $.ajax({
        type: "GET",
        url: "<?php echo base_url(); ?>index.php/live/loadRoutes/id/"+id,
        dataType: "json",
        success: function(data){
			var k=100;
			if(data.route_id!=0){
			$.post(
			   "<?php echo site_url('home/loadRouteLive'); ?>",{'route_ids':data.route_id,'assets_id':data.assets_id},
			   function(data){
					points = [];					
					for(i=0; i<data.coords.length; i++){
					
						var rId = data.coords[i].id;
						var rName = data.coords[i].routename;
						var rColor = data.coords[i].route_color;
						var rStart = data.coords[i].start_point.split(",");
						var rEnd = data.coords[i].end_point.split(",");
						var rWaypoints = data.coords[i].waypoints;
						var rPoints = data.coords[i].points;
						
						var start = new google.maps.LatLng(parseFloat(rStart[0]), parseFloat(rStart[1]));
						if(data.coords[i].round_trip == 1){
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
						
						wpts = [];
						for (var k=0; k<rWaypoints.length; k++) {
						  wpts = rWaypoints[k].split(",");
						  waypts.push({location:new google.maps.LatLng(parseFloat(wpts[0]), parseFloat(wpts[1]))});
						}
						
						slArr<?php echo $prefix; ?>[i] = start;
						elArr<?php echo $prefix; ?>[i] = end;
						wayPointsArr<?php echo $prefix; ?>[i] = waypts;
						clrArr<?php echo $prefix; ?>[i] = rColor;
					}
					drawRoute<?php echo $prefix; ?>();
					for(i=0; i<data.landmarks.length; i++){
						var text = data.landmarks[i].name+"<br>";
						text += data.landmarks[i].address+"<br>";
						var point = new google.maps.LatLng(data.landmarks[i].lat, data.landmarks[i].lng);		
						live_landmark_array<?php echo $prefix; ?>.push(createLandmarkMarker<?php echo $time; ?>(mapmap<?php echo $prefix; ?>, point, data.landmarks[i].name, text, data.landmarks[i].icon_path, '', "sidebar_map", '' ));
						<?php if($this->session->userdata('usertype_id') < 3){ ?>
						DrawCircle<?php echo $prefix; ?>(point, data.landmarks[i].radius, data.landmarks[i].distance_unit, mapmap<?php echo $prefix; ?>);				
						<?php } ?>
					}
					<?php if($this->session->userdata('show_map_trip_button')==1){ ?>
					//Trip Button
					var trackNewWindowDiv = document.createElement('DIV');
					var trackNewWindowControl = new tripControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
					trackNewWindowDiv.index = 1;	
					mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
					<?php }  ?>
					live_clearLandmarkOverlays<?php echo $prefix; ?>();
					setTimeout(function(){
						$("#pbar_Loading_<?php echo $prefix; ?>").html("Routes Loading Complete ...");
						setTimeout(function(){
							loadAreas<?php echo $prefix; ?>(<?php echo $prefix; ?>);
						},500);						
					},500);
				},'json');
			}else{
				<?php if($this->session->userdata('show_map_trip_button')==1){ ?>
				//Trip Button
				var trackNewWindowDiv = document.createElement('DIV');
				var trackNewWindowControl = new tripControlLive<?php echo $prefix; ?>(trackNewWindowDiv, mapmap<?php echo $prefix; ?>);
				trackNewWindowDiv.index = 1;	
				mapmap<?php echo $prefix; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
				<?php }  ?>
				live_clearLandmarkOverlays<?php echo $prefix; ?>();
				setTimeout(function(){
					$("#pbar_Loading_<?php echo $prefix; ?>").html("Routes Loading Complete ...");
					setTimeout(function(){
						loadAreas<?php echo $prefix; ?>(<?php echo $prefix; ?>);
					},500);						
				},500);
			}
		},
        error: function(request, status, err){
			$("#pbar_Loading_<?php echo $prefix; ?>").html("Routes Loading Canceled.");
			disp_route<?php echo $time; ?>=false;
			setTimeout(function(){
				loadAreas<?php echo $prefix; ?>(<?php echo $prefix; ?>);
			},1000);
	    }
    });
}
function cancelLoading<?php echo $prefix; ?>(){
	if(typeof  $loadingDynamicObj<?php echo $prefix; ?>==='object'){
		$loadingDynamicObj<?php echo $prefix; ?>.abort()
	};	
}
function contentLoadComplete(){
	$("#pbar_refresh_buttons_<?php echo $prefix; ?>").css("display","block");
	$("#pbar_Loading_<?php echo $prefix; ?>").css("display","none");
	stop_resume_toggle<?php echo $time; ?>();
	$("#live_settings<?php echo $time; ?>").qtip(
      {
         content: {
			prerender: true,
            text: $("#frm_live<?php echo $time; ?>").html(),
            title: {
               text: 'Live Map Settings', // Give the tooltip a title using each elements text
               button: 'Close' // Show a close link in the title
            }
         },
         position: {
			corner: {
                tooltip: "bottomRight", 
                target: "topLeft"
               }
         },
         show: { 
            when: 'click', 
            solo: true // Only show one tooltip at a time
         },
		 hide: false,
         style: {
            tip: true, // Apply a speech bubble tip to the tooltip at the designated tooltip corner
            border: {
               width: 0,
               radius: 4
            },
            name: 'light', // Use the default light style
            width: 300 // Set the tooltip width
         },
		api: {
	     beforeShow: function()
         {
            // Fade in the modal "blanket" using the defined show speed
            $('#QTip_live_Model<?php echo $time; ?>').fadeIn(this.options.show.effect.length);
         },
         beforeHide: function()
         {
            // Fade out the modal "blanket" using the defined hide speed
            $('#QTip_live_Model<?php echo $time; ?>').fadeOut(this.options.hide.effect.length);
         }
      }
      });
   $('#QTip_live_Model<?php echo $time; ?>')
      .css({
         position: 'absolute',
         top: $(document).scrollTop(), // Use document scrollTop so it's on-screen even if the window is scrolled
         left: 0,
         height: $(document).height(), // Span the full document height...
         width: '100%', // ...and full width

         opacity: 0.7, // Make it slightly transparent
         backgroundColor: 'black',
         zIndex: 5000  // Make sure the zIndex is below 6000 to keep it below tooltips!
      })
      .appendTo(document.body) // Append to the document body
      .hide(); // Hide it initially	 
	  $("#helpRightClick_top<?php echo $prefix; ?>").css("display","block");
	  $("#helpRightClick<?php echo $prefix; ?>").addClass('ui-state-highlight');
	  
	
	setTimeout(function() {
		$("#helpRightClick<?php echo $prefix; ?>").removeClass('ui-state-highlight', 1000).after(function(){setTimeout(function(){
		$("#helpRightClick<?php echo $prefix; ?>").addClass('ui-state-highlight');
			setTimeout(function() {
				$("#helpRightClick<?php echo $prefix; ?>").removeClass('ui-state-highlight', 1000).after(function(){setTimeout(function(){
					$("#helpRightClick_top<?php echo $prefix; ?>").hide('fast');
					$("#devindia_note<?php echo $prefix; ?>").show('fast');
				},2000)});
			}, 500);
		},1200)});
	}, 500);	
}

function createMarker_live(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		
		/*
		// Commented By Kunal.
		
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(html);
		*/
		
		var infoBubble = new InfoBubble({
          map: map,
		  content:html,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 2,
		  minWidth : 225,
		  minHeight : 80
        });
		//infoBubble.open(map, new_marker);
		google.maps.event.addListener(new_marker, 'click', function() {
			update_timeout = setTimeout(function(){
				if (!infoBubble.isOpen()) {
					//infoBubble.setContent(html);
					infoBubble.open(map, new_marker);
				}
			}, 200);
		/*	
		// Commented By Kunal
		  update_timeout = setTimeout(function(){
				infowindow.open(map,new_marker);
			}, 200); 
		*/			
		});
		google.maps.event.addListener(new_marker, 'dblclick', function() {
			dArr.push(point);
			  if(dArr.length == 2){
					calcRoute(dArr[0], dArr[1], map);
					dArr = [];
					
			  }
			  if(dArr.length == 1 && directionsDisplay != undefined){
				clearDirection();
				}
			 clearTimeout(update_timeout);
		});
		
		if(openInfo == true) {
			//setTimeout(function(){
				
				//infoBubble.setContent(html);
				setTimeout(function(){
				infoBubble.open(map, new_marker);
				},1000);
			//}, 500);
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

$(document).ready(function(){
	$("#pbar_Loading_<?php echo $prefix; ?>").html("Loading Menu...");
	addContextMenu();
	$("#pbar_Loading_<?php echo $prefix; ?>").html("Menu Loading Complete ...");
});
</script>