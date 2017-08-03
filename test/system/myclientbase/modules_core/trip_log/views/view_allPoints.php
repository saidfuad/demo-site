<?php $seprator	= time(); ?>
<html>    
<head> 	
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/simulator/geplugin-helpers.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/simulator/math3d.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/simulator/simulator.js"></script>
  
<style> 
	
#route-details {
	height:auto;
	overflow:auto;
	position:relative;
}
#route-details ol {
	margin:0;
	padding:0;
}
.dir-step {
	font-size:small;
	list-style-image:none;
	list-style-position:inside;
	list-style-type:decimal;
	padding-right:50px !important;
	position:relative;
}
.dir-step .note {
	color: darkorange;
    font-weight: bold;
	padding:6px 3px;
	position:absolute;
	right:0;
	top:0;
}
.dir-step, #dir-start, #dir-end {
	cursor:pointer;
	margin:0;
	padding:6px 3px;
}
.dir-step.sel {
	background-color:#0000FF;
	color:red;
}
.dir-step {
    border-bottom: 1px solid #888888;
}
</style> 
</head> 
<body>
<script type="text/javascript">

var DS_ge;
var DS_geHelpers;
var DS_map;

var latArr = new Array();
var lngArr = new Array();
var htmlArr = new Array();
loop = 0;

var lastPoint;


// index.js

var DS_directions = null;
var DS_steps = [];
var DS_path = []; 
var DS_simulator;
var DS_mapMarker = null; 
var DS_placemarks = {};

	var lat = [<?php for($i=0;$i<sizeof($lat);$i++){
		if($i!=sizeof($lat)-1)
			echo "'".$lat[$i]."',";
		else
			echo "'".$lat[$i]."'";
			} ?>];
	var lng = [<?php for($i=0;$i<sizeof($lng);$i++){
		if($i!=sizeof($lng)-1)
			echo "'".$lng[$i]."',";
		else
			echo "'".$lng[$i]."'";
			} ?>];
	var html = [<?php for($i=0;$i<sizeof($html);$i++){
		if($i!=sizeof($html)-1)
			echo "'".$html[$i]."',";
		else
			echo "'".$html[$i]."'";
			} ?>];
			
			
$(document).ready(function () {
			
	$("#dialog_landmark_detail").dialog({
		autoOpen: false,
		width:'50%',
		draggable: true,
		resizable: true,
		modal: false,
		title:'Login Info'
	});	
	
	jQuery("input:button, input:submit, input:reset").button();
	
	if(lat.length > 0){
		for(i=0; i<lat.length; i++){
			latArr.push(lat[i]);
			lngArr.push(lng[i]);
			htmlArr.push(html[i]);
		}
		
		lastPoint = new google.maps.LatLng(lat[lat.length-1], lng[lng.length-1]);
		
		var devText = "<?php echo $assets_name; ?>";
		var distance = <?php echo $distance; ?>;
		var txt = devText + ": Total Travel Distance : " + distance + " KM";
		
	}else{
		$("#dialog_landmark_detail").html("No Data Found");
		$("#dialog_landmark_detail").dialog('close');
	}
	DS_init();
});


function DS_init() {
  $('#directions-form input').attr('disabled', 'disabled');
  $('.simulator-earth').attr('disabled', 'disabled');
  google.earth.createInstance(
    'earth',
    function(ge) {
      DS_ge = ge;
      DS_ge.getWindow().setVisibility(true);
      DS_ge.getLayerRoot().enableLayerById(DS_ge.LAYER_BUILDINGS, true);
      DS_ge.getLayerRoot().enableLayerById(DS_ge.LAYER_BORDERS, true);
      DS_geHelpers = new GEHelpers(DS_ge);
      
      DS_ge.getNavigationControl().setVisibility(ge.VISIBILITY_AUTO);
      
      DS_map = new GMap2($('#map').get(0));
      DS_map.setCenter(new GLatLng(22, 70), 8);
      DS_map.addControl(new GSmallMapControl());
      DS_map.enableContinuousZoom();
      
      $('#directions-form input').removeAttr('disabled');
    },
    function() {});
}

function DS_goDirections() {
  $('#route-details').empty();
  $('#route-details').html(
      '<span class="loading">Loading directions...</span>');
  
  if (DS_directions)
    DS_directions.clear();

  DS_directions = new google.maps.Directions(DS_map, null);
  
  google.maps.Event.addListener(DS_directions, 'load', DS_directionsLoaded);
  
  google.maps.Event.addListener(DS_directions, 'error', function() {
    $('#route-details').empty();
    $('#route-details').html(
        '<span class="error">No directions found.</span>');
  });
  
  //DS_directions.load('from: 22.856087,069.724731 to: 23.196069,070.217914',
  DS_directions.load('from: '+new google.maps.LatLng(latArr[0],lngArr[0])+' to: '+new google.maps.LatLng(latArr[latArr.length -1],lngArr[lngArr.length -1]),
      {getSteps: true, getPolyline: true});
}


function DS_directionsLoaded() {
  $('#route-details').empty();
  var route = DS_directions.getRoute(0);
  var start = route.getStartGeocode();
  var end = route.getEndGeocode();
  
  DS_buildPathStepArrays();
  
  DS_geHelpers.clearFeatures();
  DS_placemarks = {};
  
  DS_placemarks['start'] = DS_geHelpers.createPointPlacemark(
      new google.maps.LatLng(start.Point.coordinates[1],
                             start.Point.coordinates[0]),
      {description: start.address, standardIcon: 'grn-diamond'});
  
  for (var i = 0; i < DS_steps.length; i++) {
    var step = DS_steps[i];
    
    var placemark = DS_geHelpers.createPointPlacemark(
        step.loc, {description: step.desc, standardIcon: 'red-circle'});
    
    DS_placemarks['step-' + i] = placemark; 
    
    google.earth.addEventListener(placemark, 'click', function(event) {
      // match up the placemark to its id in the dictionary to find out
      // which step number it is
      var id = '';
      for (k in DS_placemarks)
        if (DS_placemarks[k].equals(event.getTarget()))
          id = k;
      
      var stepNum = parseInt(id.match(/step-(\d+)/)[1]);
      
      DS_flyToStep(stepNum);
    });
  }
  
  DS_placemarks['end'] = DS_geHelpers.createPointPlacemark(
      new google.maps.LatLng(end.Point.coordinates[1],
                             end.Point.coordinates[0]),
      {description: end.address, standardIcon: 'grn-diamond'});
  var lineStringKml = '<LineString><coordinates>\n';
  
  for (var i = 0; i < DS_path.length; i++)
    lineStringKml +=
        DS_path[i].loc.lng().toString() + ',' +
        DS_path[i].loc.lat().toString() +
        ',10\n';
  
  lineStringKml += '</coordinates></LineString>';
  
  var routeLineString = DS_ge.parseKml(lineStringKml);
  routeLineString.setTessellate(true);
  
  var routePlacemark = DS_ge.createPlacemark('');
  routePlacemark.setGeometry(routeLineString);
  DS_placemarks['route'] = routePlacemark;
  
  routePlacemark.setStyleSelector(
      DS_geHelpers.createLineStyle({width: 10, color: '88ff0000'}));
  
  DS_ge.getFeatures().appendChild(routePlacemark);

  DS_buildDirectionsList();
  
  DS_flyToLatLng(new google.maps.LatLng(
                 start.Point.coordinates[1], start.Point.coordinates[0]));
  
  $('.simulator-earth').removeAttr('disabled');
  
  if (DS_simulator) {
    DS_simulator.destroy();
    DS_simulator = null;
  }
}

function DS_buildPathStepArrays() {
  DS_steps = [];
  DS_path = [];
  
  var polyline = DS_directions.getPolyline();
  var route = DS_directions.getRoute(0);
  var numPolylineVertices = polyline.getVertexCount();
  var numSteps = route.getNumSteps();
  for (var i = 0; i < numSteps; i++) {
    var step = route.getStep(i);
    
    var firstPolylineIndex = step.getPolylineIndex();
    
    var lastPolylineIndex = -1;
    if (i == numSteps - 1)
      lastPolylineIndex = numPolylineVertices - 1;
    else {
      lastPolylineIndex = route.getStep(i + 1).getPolylineIndex() - 2;
    }
    
    DS_steps.push({
      loc: step.getLatLng(),
      desc: step.getDescriptionHtml(),
      distanceHtml: step.getDistance().html,
      pathIndex: DS_path.length
    });
    
    var stepDistance = step.getDistance().meters;
    for (var j = firstPolylineIndex; j <= lastPolylineIndex; j++) {
      var loc = polyline.getVertex(j);
      var distance = (j == numPolylineVertices - 1) ?
                     0 : DS_geHelpers.distance(loc, polyline.getVertex(j + 1));
      
      DS_path.push({
        loc: loc,
        step: i,
        distance: distance,
        
        duration: step.getDuration().seconds * distance / stepDistance
      });
    }
  }
}

function DS_buildDirectionsList() {
  var start = DS_directions.getRoute(0).getStartGeocode();
  var end = DS_directions.getRoute(0).getEndGeocode();
  
  $('#route-details').append($(
      '<div id="dir-start" class="ui-state-default">' + start.address + '</div>'));
  
  $('#route-details').append('<ol>');
  for (var i = 0; i < DS_steps.length; i++) {
    $('#route-details ol').append($(
        '<li class="dir-step" id="dir-step-' + i + '">' +
        DS_steps[i].desc +
        '<div class="note">' + DS_steps[i].distanceHtml + '</div>' + 
        '</li>'));
  }
  
  $('#route-details').append($(
      '<div id="dir-end" class="ui-state-default">' + end.address + '</div>'));
  
  // handle events on the directions list
  $('#dir-start').click(function() {
    DS_flyToLatLng(new google.maps.LatLng(
                   start.Point.coordinates[1], start.Point.coordinates[0]));
  });
  
  $('#dir-end').click(function() {
    DS_flyToLatLng(new google.maps.LatLng(
                   end.Point.coordinates[1], end.Point.coordinates[0]));
  });
  
  $('#route-details li').click(function() {
    var id = $(this).attr('id');
    if (id == 'dir-start' || id == 'dir-end')
      return;
    
    var stepNum = parseInt(id.match(/dir-step-(\d+)/)[1]);
    DS_flyToStep(stepNum);
  });
}

function DS_flyToStep(stepNum) {
  var step = DS_steps[stepNum];
  
  var la = DS_ge.createLookAt('');
  la.set(step.loc.lat(), step.loc.lng(),
      0, // altitude
      DS_ge.ALTITUDE_RELATIVE_TO_GROUND,
      DS_geHelpers.getHeading(step.loc, DS_path[step.pathIndex + 1].loc),
      60, // tilt
      50 // range (inverse of zoom)
      );
  DS_ge.getView().setAbstractView(la);

  // show the description balloon.
  var balloon = DS_ge.createFeatureBalloon('');
  balloon.setFeature(DS_placemarks['step-' + stepNum]);
  DS_ge.setBalloon(balloon); 

  DS_highlightStep(stepNum);
}

function DS_highlightStep(stepNum) {
  $('#route-details li').removeClass('ui-state-highlight');
  $('#route-details #dir-step-' + stepNum).addClass('ui-state-highlight');
}


function DS_flyToLatLng(loc) {
  var la = DS_ge.createLookAt('');
  la.set(loc.lat(), loc.lng(),
      10, // altitude
      DS_ge.ALTITUDE_RELATIVE_TO_GROUND,
      90, // heading
      0, // tilt
      200 // range (inverse of zoom)
      );
  DS_ge.getView().setAbstractView(la);
  
  $('#route-details li').removeClass('ui-state-highlight');
}


function DS_formatTime(s) {
  var m = Math.floor(s / 60);
  s %= 60;
  var h = Math.floor(m / 60);
  m %= 60;
  s = Math.round(s);
  return ((h < 10) ? ('0' + h) : h) + ':' + ((m < 10) ? ('0' + m) : m);
}

function DS_controlSimulator(command, opt_cb) {
  switch (command) {
    case 'reset':
      if (DS_simulator)
        DS_simulator.destroy();
    
		DS_simulator = new DDSimulator(DS_ge, DS_path, {
     
		on_tick: function() {
          DS_map.panTo(DS_simulator.currentLoc);
          DS_mapMarker.setLatLng(DS_simulator.currentLoc);
          
          if (DS_simulator) {
            $('#status').html(
               '<strong>Time:</strong> <font color="red">' +
                  DS_formatTime(DS_simulator.totalTime) + '&nbsp;&nbsp;&nbsp;' +
                '</font><strong>Distance:</strong> <font color="red">' +
                  (Math.round(
                      DS_simulator.totalDistance / 1609.344 * 10) / 10) +
                  ' mi' + '&nbsp;&nbsp;&nbsp;' +
                '</font><strong>Current Speed:</strong> <font color="red">' +
                  Math.round(DS_simulator.currentSpeed / 0.44704) + 'mph') +
                  '</font><br/>';
          }
        },
        
   
	on_changeStep: function(stepNum) {
          DS_highlightStep(stepNum);
        }
      });
      
      if (!DS_mapMarker) {
        // create vehicle location indicator on map
        var icon = new google.maps.Icon();
        icon.iconSize = new google.maps.Size(42, 42);
        icon.iconAnchor = new google.maps.Point(21, 21);
        icon.image = '<?php echo base_url(); ?>assets/simulator/smart_marker.png';
        DS_mapMarker = new google.maps.Marker(
                       DS_simulator.currentLoc, {icon: icon});
        DS_map.addOverlay(DS_mapMarker);
      }
      
      DS_map.setZoom(13);
      DS_mapMarker.setLatLng(DS_simulator.currentLoc);
      
      DS_updateSpeedIndicator();
      DS_simulator.initUI(opt_cb);
      break;
    
    case 'start':
      if (!DS_simulator)
        DS_controlSimulator('reset', function() {
          DS_simulator.start();
          if (opt_cb) opt_cb();
        });
      else {
        DS_simulator.start();
        if (opt_cb) opt_cb();
      }
      break;
    
    case 'pause':
      if (DS_simulator)
        DS_simulator.stop();
      
      if (opt_cb) opt_cb();
      break;
    
    case 'resume':
      if (DS_simulator)
        DS_simulator.start();
      
      if (opt_cb) opt_cb();
      break;
    
    case 'slower':
      if (DS_simulator && DS_simulator.options.speed > 0.125) {
        DS_simulator.options.speed /= 2.0;
        DS_updateSpeedIndicator();
      }
      break;
    
    case 'faster':
      if (DS_simulator && DS_simulator.options.speed < 64.0) {
        DS_simulator.options.speed *= 2.0;
        DS_updateSpeedIndicator();
      }
      break;
  }
}


function DS_updateSpeedIndicator() {
  if (DS_simulator.options.speed < 1)
    $('#speed-indicator').text('1/' +
        Math.floor(1 / DS_simulator.options.speed) + 'x');
  else
    $('#speed-indicator').text(Math.floor(DS_simulator.options.speed) + 'x');
}

</script>
	
<div id="dialog_landmark_detail"></div>
<div id="map_container" style="width: 100%px; height: 50%;">
	<table style="width: 100%; height: 400px;">
	<tr>  
		<td colspan="2">
			<b><font color='red'>Note : Google earth map is used only for concept purpose.</font></b>
		</td>
	</tr>  
	<tr>  
	  <td style="width: 66%" valign="top">
		<div id="earth" style="border: 1px solid #000; height: 400px;"></div>
		<div id="status"></div>
	  </td>
	  <td style="width: 33%" valign="top">
		<div id="map" style="border: 1px solid #000; height: 400px;"></div>
	  </td>
	</tr>
	<tr>
		<td>
			<br>
			<form onsubmit="return false;" action="get">
				<input type="submit" onclick="DS_goDirections();" id="go" value="Go!"/>
			  
			  <input type="button" onclick="DS_controlSimulator('reset');" value="Reset"/>
			  <input type="button" class='simulator-earth' onclick="DS_controlSimulator('start');" value="Start"/>
			  <input type="button" class='simulator-earth' onclick="DS_controlSimulator('pause');" value="Pause"/>
			  Speed: <strong><span id="speed-indicator">1x</span></strong>
			  <input type="button" class='simulator-earth' onclick="DS_controlSimulator('slower');" value="-"/>
			  <input type="button" class='simulator-earth' onclick="DS_controlSimulator('faster');" value="+"/>
			</form>
		</td>
	</tr>
	</table>
	<br>
	<div id="route-details"></div>

</div>


</body>

</html>
