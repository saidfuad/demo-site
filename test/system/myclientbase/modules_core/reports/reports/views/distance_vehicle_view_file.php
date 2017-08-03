<?php 
$tme=time();
?>
<script type="text/javascript">
loadMarkerClusters();
</script>
<script type="text/javascript" charset="utf-8">
loadInfoBubble();
var map<?php echo time(); ?> = null;
var markersmap<?php echo time(); ?>  = [];		
var mbounds<?php echo time(); ?>;
var pointArr<?php echo time(); ?> = [];	
<?php $time=time(); ?>
$( "#pbar<?php echo $time; ?>" ).progressbar({value: 0.1});
var map_poly_array<?php echo time(); ?> = [];
var map_landmark_array<?php echo time(); ?> = [];
var circleArray<?php echo time(); ?> = [];

var map_trip_landmark_array<?php echo time(); ?> = [];
var trip_circleArray<?php echo time(); ?> = [];

var mcOptionsAllpoint = {gridSize: 50, maxZoom: 16};
var markerClusterMap<?php echo time(); ?>;

var dDisplay<?php echo time(); ?>=[];
function TrackControl<?php echo time(); ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
	 /*
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to set the map to Home"); ?>';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingTop = '3px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = "Assets's Dashboard";
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+assetDeviceArray[selected_assets_ids], assetNameArray[selected_assets_ids]+" Details");
  });  */
}

//function dashboard(device_id,device_name)
function dashboard(device_id)
{
	//alert(device_id);
	var nameToCheck = assetNameArray[device_id]+" Details";
	var tabNameExists = false;
		
		$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		
			if (this.text == nameToCheck) {
				tabNameExists = true;
				$('#tabs').tabs('select', $(this).attr("href"));
				return false;
			}
		});
		if (!tabNameExists){
			if(assetDeviceArray[device_id] != undefined)
			{
				$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+device_id, assetNameArray[device_id]+" Details");
			}
			
		}
	/*$.post("<?php echo base_url(); ?>index.php/home/get_assets_nm/id/"+device_id,function(data){
		if(data!="")
		{
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+device_id, data+" Details");
		}
		else
		{
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+device_id, "User Assets Details");
		}
	});*/
	
}
function areaControl<?php echo time(); ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to Show/Hide Area"); ?>';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkArea<?php echo time(); ?>";
  chkOne.checked = false;
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkArea<?php echo time(); ?>";
  
  lblOne.appendChild(document.createTextNode('Area'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkArea<?php echo time(); ?>").attr("checked") == "checked") {
		map_showPolyOverlays<?php echo time(); ?>();
		
	}else{
		map_clearPolyOverlays<?php echo time(); ?>();
	}
  });
}
function landmarkControl<?php echo time(); ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to Show/Hide Landmark"); ?>';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkLandmark<?php echo time(); ?>";
  <?php if($this->session->userdata("usertype_id") > 2){ ?>
	chkOne.checked = true;
	map_showLandmarkOverlays<?php echo time(); ?>();
  <?php }else{ ?>
	chkOne.checked = false;
  <?php } ?>
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkLandmark<?php echo time(); ?>";
  lblOne.appendChild(document.createTextNode('Landmark'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkLandmark<?php echo time(); ?>").attr("checked") == "checked") {
		map_showLandmarkOverlays<?php echo time(); ?>();
		
	}else{
		map_clearLandmarkOverlays<?php echo time(); ?>();
	}
  });
}
function map_clearPolyOverlays<?php echo time(); ?>() {
 
  if (map_poly_array<?php echo time(); ?>) {
    for (i in map_poly_array<?php echo time(); ?>) {
      map_poly_array<?php echo time(); ?>[i].setMap(null);
    }
  }
}
function map_showPolyOverlays<?php echo time(); ?>() {
 
  if (map_poly_array<?php echo time(); ?>) {
    for (i in map_poly_array<?php echo time(); ?>) {
	  map_poly_array<?php echo time(); ?>[i].setMap(map<?php echo time(); ?>);
    }
  }
}
function map_clearLandmarkOverlays<?php echo time(); ?>() {
 
  if (map_landmark_array<?php echo time(); ?>) {
    for (i in map_landmark_array<?php echo time(); ?>) {
      map_landmark_array<?php echo time(); ?>[i].setMap(null);
    }
  }
  if (circleArray<?php echo time(); ?>) {
    for (i in circleArray<?php echo time(); ?>) {
      circleArray<?php echo time(); ?>[i].setMap(null);
    }
  }
}
function map_showLandmarkOverlays<?php echo time(); ?>() {
 
  if (map_landmark_array<?php echo time(); ?>) {
    for (i in map_landmark_array<?php echo time(); ?>) {
	  map_landmark_array<?php echo time(); ?>[i].setMap(map<?php echo time(); ?>);
    }
  }
  if (circleArray<?php echo time(); ?>) {
    for (i in circleArray<?php echo time(); ?>) {
	  circleArray<?php echo time(); ?>[i].setMap(map<?php echo time(); ?>);
    }
  }
}
function map_trip_clearLandmarkOverlays<?php echo time(); ?>() {
 
  if (map_trip_landmark_array<?php echo time(); ?>){
    for (i in map_trip_landmark_array<?php echo time(); ?>){
      map_trip_landmark_array<?php echo time(); ?>[i].setMap(null);
    }
  }
  if (trip_circleArray<?php echo time(); ?>) {
    for (i in trip_circleArray<?php echo time(); ?>) {
      trip_circleArray<?php echo time(); ?>[i].setMap(null);
    }
  }
}
function map_trip_showLandmarkOverlays<?php echo time(); ?>() {
 
  if (map_trip_landmark_array<?php echo time(); ?>) {
    for (i in map_trip_landmark_array<?php echo time(); ?>) {
	  map_trip_landmark_array<?php echo time(); ?>[i].setMap(map<?php echo time(); ?>);
    }
  }
  if (trip_circleArray<?php echo time(); ?>) {
    for (i in trip_circleArray<?php echo time(); ?>) {
	  trip_circleArray<?php echo time(); ?>[i].setMap(map<?php echo time(); ?>);
    }
  }
}

function DrawCircle<?php echo time(); ?>(center, rad, dUnit, map) {
	if(dUnit == "KM")
		rad *= 1000; // convert to meters if in km
	if(dUnit == "Mile")
		rad *= (1000 * 1.609344); // convert to meters if in km
	if(dUnit == "Meter")
		rad = parseInt(rad);
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
	circleArray<?php echo time(); ?>.push(draw_circle);
}
function onLoadmap<?php echo time(); ?>() {
	var rendererOptionsH = {
		preserveViewport: true,
		draggable: false,
		suppressMarkers: true,
		polylineOptions: {
		   map: map<?php echo time(); ?>,
		   strokeColor:'#FF0000',
		   //strokeWidth: 3,
		   strokeOpacity: 0.5}
		};
					
	directionsService = new google.maps.DirectionsService();
	directionsDisplay = new google.maps.DirectionsRenderer(rendererOptionsH);
	//directionsDisplay = new google.maps.DirectionsRenderer();

	var mapObjmap = document.getElementById("map<?php echo time(); ?>");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmap = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmap.center = new google.maps.LatLng(
		22.297744,
		70.792444
	);
	
	map<?php echo time(); ?> = new google.maps.Map(mapObjmap,mapOptionsmap);
	
	// Create the DIV to hold the control and call the TrackControl() constructor
  	// passing in this DIV.
	var trackControlDiv = document.createElement('DIV');
    var trackControl = new TrackControl<?php echo time(); ?>(trackControlDiv, map<?php echo time(); ?>);	
	trackControlDiv.index = 1;
	map<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	
	
	//new window btn		
	<?php if($this->session->userdata('show_map_area_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new areaControl<?php echo time(); ?>(trackNewWindowDiv, map<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>
	//new window btn
	<?php if($this->session->userdata('show_map_landmark_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new landmarkControl<?php echo time(); ?>(trackNewWindowDiv, map<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>	

	
	<?php 
	if(count($coords) > 0) {
				
				$p1 = explode(",", $coords['asset1_lat_lng']);
				$p2 = explode(",", $coords['asset2_lat_lng']);
				$pLt1 = $p1[0];
				$pLg1 = $p1[1];
				$pLt2 = $p2[0];
				$pLg2 = $p2[1];
	?>	
	var myTextDiv_top_left = document.createElement('div');
	myTextDiv_top_left.innerHTML = '<h2 style="color:black;background-color:rgba(255,255,255,0.7);padding:3px">&nbsp;<?php echo $coords['distance']." KM"; ?>&nbsp;</h2>';
	map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv_top_left);
	mbounds<?php echo time(); ?> = new google.maps.LatLngBounds();
	
		var point = new google.maps.LatLng(<?php echo floatval($pLt1); ?>,<?php echo floatval($pLg1); ?>);
		var point1 = new google.maps.LatLng(<?php echo floatval($pLt2); ?>,<?php echo floatval($pLg2); ?>);
		var request1 = {
				origin:point,
				destination:point1,
				avoidHighways: true,
				avoidTolls: true,
				provideRouteAlternatives: false,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			directionsService.route(request1, function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) 
				{
					directionsDisplay.setDirections(response);
					directionsDisplay.setMap(map<?php echo time(); ?>);
				}			  
			});	
		pointArr<?php echo time(); ?>.push(point);
		pointArr<?php echo time(); ?>.push(point1);	
		<?php if($coords['asset_id1']==$coords['html_assets_id1']){ ?>
		markersmap<?php echo time(); ?>.push(createMarkerWithNumber(map<?php echo time(); ?>, "", point, "<?php echo $coords['asset_id1']; ?>","<?php echo $coords['html1']; ?>", "<?php echo $coords['iconPath1']; ?>", '', "sidebar_map", '', false ));

		markersmap<?php echo time(); ?>.push(createMarkerWithNumber(map<?php echo time(); ?>, "", point1, "<?php echo $coords['asset_id2']; ?>","<?php echo $coords['html2']; ?>", "<?php echo $coords['iconPath2']; ?>", '', "sidebar_map", '', false ));

		<?php } else { ?>
		markersmap<?php echo time(); ?>.push(createMarkerWithNumber(map<?php echo time(); ?>, "", point, "<?php echo $coords['asset_id1']; ?>","<?php echo $coords['html2']; ?>", "<?php echo $coords['iconPath1']; ?>", '', "sidebar_map", '', false ));
		
		markersmap<?php echo time(); ?>.push(createMarkerWithNumber(map<?php echo time(); ?>, "", point1, "<?php echo $coords['asset_id2']; ?>","<?php echo $coords['html1']; ?>", "<?php echo $coords['iconPath2']; ?>", '', "sidebar_map", '', false ));
		<?php } ?>
		mbounds<?php echo time(); ?>.extend(point);
		mbounds<?php echo time(); ?>.extend(point1);
		
	<?php			
			//} // End For Loop
		} else {
	?>
		var point = new google.maps.LatLng(22.297744,70.792444);
		<?php if($this->session->userdata('user_id') == 6 || $this->session->userdata('admin_id') == 6){ ?>
			markersmap<?php echo time(); ?>.push(createMarkerWithNumber(map<?php echo time(); ?>, point,"DevIndia Infoway","DevIndia Infoway", '', '', "sidebar_map", '' ));
		<?php }else{ ?>
			markersmap<?php echo time(); ?>.push(createMarker(map<?php echo time(); ?>, point,"DevIndia Infoway","DevIndia Infoway", '', '', "sidebar_map", '' ));
		<?php } ?>
		map<?php echo time(); ?>.setCenter(point);
	<?php } ?>
	map<?php echo time(); ?>.fitBounds(mbounds<?php echo time(); ?>);
  }
  <?php
	$i = 0;
	foreach($plyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php
		$pathArr = array();
		
		for($j=0; $j<count($plyLat[$pIdv]); $j++){
			$pathArr[] = 'new google.maps.LatLng('.sprintf("%.6f", $plyLat[$pIdv][$j]).', '.sprintf("%.6f", $plyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArr);
		
		if(count($plyDev[$pIdv]) > 0){
			$devices = implode("<br>", $plyDev[$pIdv]);
		}
		?>
		var polygonCoords = [<?php echo $pathString; ?>];

		for (i = 0; i < polygonCoords.length; i++) {
		  bounds.extend(polygonCoords[i]);
		}
		//var devices = 'plyDev'
		label<?php echo time(); ?><?php echo $i; ?> = new ELabel({
		latlng: bounds.getCenter(), 
		label: "<div class='elable' id='elable_<?php echo $i; ?>' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'><?php echo $plyName[$pIdv][0]; ?></div>", 
		classname: "label", 
		offset: 0, 
		opacity: 100, 
		overlap: true,
		clicktarget: false
		});
						
		var map_polyV<?php echo time(); ?><?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $plyColor[$pIdv]; ?>'
		    });
		//map_polyV<?php echo time(); ?><?php echo $i; ?>.setMap(map<?php echo time(); ?>);
		map_poly_array<?php echo time(); ?>.push(map_polyV<?php echo time(); ?><?php echo $i; ?>)			
		google.maps.event.addListener(map_polyV<?php echo time(); ?><?php echo $i; ?>,"mouseover",function(event){
			label<?php echo time(); ?><?php echo $i; ?>.setMap(map<?php echo time(); ?>);
			$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(map_polyV<?php echo time(); ?><?php echo $i; ?>,"mouseout",function(event){
			label<?php echo time(); ?><?php echo $i; ?>.setMap(null);
		});
		google.maps.event.addListenerOnce(map<?php echo time(); ?>, 'idle', function() {
			google.maps.event.trigger(map<?php echo time(); ?>, 'resize');
			//map<?php echo time(); ?>.setCenter(point); // be sure to reset the map center as well
		});
				
	<?php $i++; } ?>
	<?php
	$i = 0;
	if(count($landmarks) > 0) {
		foreach ($landmarks as $landmark) {
			$distance_unit = $landmark->distance_unit;
			$text = "Name : ".$landmark->name."<br>";
			$text .= "Address : ".$landmark->address."<br>";
			$text .= "Assets : ".$landmark->assets.'<br>';
		//	$text .= "<img src='".$landmark->assets.'<br>';
	?>				
			var point = new google.maps.LatLng(<?php echo floatval($landmark->lat); ?>, <?php echo floatval($landmark->lng); ?>);		
			
			map_landmark_array<?php echo time(); ?>.push(createLandmarkMarker<?php echo time(); ?>(map<?php echo time(); ?>, point, "<?php echo $landmark->name; ?>","<?php echo $text; ?>", '<?php echo $landmark->icon_path; ?>', '', "sidebar_map", '' ));
			DrawCircle<?php echo time(); ?>(point, '<?php echo $landmark->radius; ?>', '<?php echo $distance_unit; ?>', map<?php echo time(); ?>);
	<?php
		$i++;
		} // End For Loop
	}	
	?>
	setTimeout(function(){
		map_clearLandmarkOverlays<?php echo time(); ?>();
	},200*<?php if($i>0) echo $i; ?>);

}
function createMarkerWithNumber(map, number, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "http://gatti.nkonnect.com/marker_image.php?text="+number+"&icon="+icon;}
	
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>/assets/marker-images/" + icon_shadow;}
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
		  minWidth : 300,
		  minHeight : 200
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
var slArr<?php echo time(); ?> = [];
var elArr<?php echo time(); ?> = [];
var wayPointsArr<?php echo time(); ?> = [];
var clrArr<?php echo time(); ?> = [];
function createLandmarkMarker<?php echo time(); ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>/assets/marker-images/" + icon_shadow;}
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
			infoBubble.open(map, new_marker);
		});
		
		
	}
	return new_marker;  
}
//t<?php echo time(); ?>=window.setTimeout('startLoading<?php echo time(); ?>()',60000); 
//setTimeout('startLoading<?php echo time(); ?>()',60000)
function clearOverlays<?php echo time(); ?>() {
  
  if (markersmap<?php echo time(); ?>) {
    for (i in markersmap<?php echo time(); ?>) {
      markersmap<?php echo time(); ?>[i].setMap(null);
    }
  }
  markersmap<?php echo time(); ?> = [];
}
onLoadmap<?php echo time(); ?>();
</script>
<div id="map<?php echo time(); ?>" style="width: 100%; height: 100%; position:relative;"></div>
<div style="height:10px"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#loading_top").css("display","none");
	
});
</script>