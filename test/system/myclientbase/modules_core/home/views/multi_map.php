<?php 
$tme=time();
$assets_ids = explode(",",$assets_ids);
$assets_1 = $assets_ids[0];
$assets_2 = '';
$assets_3 = '';
$assets_4 = '';
if(isset($assets_ids[1]))
	$assets_2 = $assets_ids[1];
if(isset($assets_ids[2]))
	$assets_3 = $assets_ids[2];
if(isset($assets_ids[3]))
	$assets_4 = $assets_ids[3];

	$user = $this->session->userdata('user_id');
	$SQL = "SELECT country_lati,country_longi FROM tbl_users where user_id = '$user'";
	$query = $this->db->query($SQL);
	$row = $query->row();
	if(count($row)){
		$lati =  $row->country_lati;
		$longi =  $row->country_longi;
	} else {
		$lati =  22.297744;
		$longi =  70.792444;
	}

?>

<script type="text/javascript" charset="utf-8">
loadInfoBubble();
var map1<?php echo time(); ?> = null;
var markersmap1<?php echo time(); ?>  = [];	
var markersmap2<?php echo time(); ?>  = [];	
var markersmap3<?php echo time(); ?>  = [];	
var markersmap4<?php echo time(); ?>  = [];		
var mbounds1<?php echo time(); ?>;
var mbounds2<?php echo time(); ?>;
var mbounds3<?php echo time(); ?>;
var mbounds4<?php echo time(); ?>;
var pointArr<?php echo time(); ?> = [];	
<?php $time=time(); ?>
$( "#pbar<?php echo $time; ?>" ).progressbar({value: 0});
var map_poly_array<?php echo time(); ?> = [];
var map_landmark_array<?php echo time(); ?> = [];
var circleArray<?php echo time(); ?> = [];

var map_trip_landmark_array<?php echo time(); ?> = [];
var trip_circleArray<?php echo time(); ?> = [];

var mcOptionsAllpoint = {gridSize: 50, maxZoom: 16};
var markerClusterMap<?php echo time(); ?>;

var routePolyArr<?php echo time(); ?>=[];
var dDisplay<?php echo time(); ?>=[];
var ibArr1<?php echo time(); ?> =[];
var ibArr2<?php echo time(); ?> =[];
var ibArr3<?php echo time(); ?> =[];
var ibArr4<?php echo time(); ?> =[];

var map1Assets<?php echo time(); ?> = '<?php echo $assets_1; ?>';
var map2Assets<?php echo time(); ?> = '<?php echo $assets_2; ?>';
var map3Assets<?php echo time(); ?> = '<?php echo $assets_3; ?>';
var map4Assets<?php echo time(); ?> = '<?php echo $assets_4; ?>';
function TrackControl<?php echo time(); ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
}
var selected_view<?php echo time(); ?> = 1;

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
function tripControl<?php echo time(); ?>(controlDiv, map){
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
  chkOne.id = "chkTripLive<?php echo time(); ?>";
  <?php if($this->session->userdata("usertype_id") > 2){ ?>
	chkOne.checked = true;
	map_showTripOverlays<?php echo time(); ?>();
  <?php }else{ ?>
	chkOne.checked = false;
  <?php } ?>
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkTripLive<?php echo time(); ?>";
  
  lblOne.appendChild(document.createTextNode('Trip'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkTripLive<?php echo time(); ?>").attr("checked") == "checked") {
		map_showTripOverlays<?php echo time(); ?>();
		map_trip_showLandmarkOverlays<?php echo time(); ?>();
	}else{
		map_clearTripOverlays<?php echo time(); ?>();
		map_trip_clearLandmarkOverlays<?php echo time(); ?>();
	}
  });
}

function zoomControl1<?php echo time(); ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Zoom In/Out Trip';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "zoomControl1<?php echo time(); ?>";
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "zoomControl1<?php echo time(); ?>";
  
  lblOne.appendChild(document.createTextNode('Full Screen'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#zoomControl1<?php echo time(); ?>").attr("checked") == "checked") {
		zoomIn<?php echo time(); ?>(1);
	}else{
		zoomOut<?php echo time(); ?>(1);
	}
  });
}
function addMap1<?php echo time(); ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = 'Add Assets';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '18px';
  areaText.style.paddingTop = '2px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = 'Select Assets';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addAssetsMap<?php echo time(); ?>();
	selected_view<?php echo time(); ?> = 1;
  });
}

function zoomControl2<?php echo time(); ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Zoom In/Out Trip';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "zoomControl2<?php echo time(); ?>";
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "zoomControl2<?php echo time(); ?>";
  
  lblOne.appendChild(document.createTextNode('Full Screen'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#zoomControl2<?php echo time(); ?>").attr("checked") == "checked") {
		zoomIn<?php echo time(); ?>(2);
	}else{
		zoomOut<?php echo time(); ?>(2);
	}
  });
}
function addMap2<?php echo time(); ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = 'Add Assets';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '18px';
  areaText.style.paddingTop = '2px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = 'Select Assets';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addAssetsMap<?php echo time(); ?>();
	selected_view<?php echo time(); ?> = 2;
  });
}
function zoomControl3<?php echo time(); ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Zoom In/Out Trip';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "zoomControl3<?php echo time(); ?>";
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "zoomControl3<?php echo time(); ?>";
  
  lblOne.appendChild(document.createTextNode('Full Screen'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#zoomControl3<?php echo time(); ?>").attr("checked") == "checked") {
		zoomIn<?php echo time(); ?>(3);
	}else{
		zoomOut<?php echo time(); ?>(3);
	}
  });
}
function addMap3<?php echo time(); ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = 'Add Assets';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '18px';
  areaText.style.paddingTop = '2px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = 'Select Assets';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addAssetsMap<?php echo time(); ?>();
	selected_view<?php echo time(); ?> = 3;
  });
}
function zoomControl4<?php echo time(); ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to Zoom In/Out Trip';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "zoomControl4<?php echo time(); ?>";
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "zoomControl4<?php echo time(); ?>";
  
  lblOne.appendChild(document.createTextNode('Full Screen'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#zoomControl4<?php echo time(); ?>").attr("checked") == "checked") {
		zoomIn<?php echo time(); ?>(4);
	}else{
		zoomOut<?php echo time(); ?>(4);
	}
  });
}
function addMap4<?php echo time(); ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = 'Add Assets';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '18px';
  areaText.style.paddingTop = '2px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = 'Select Assets';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addAssetsMap<?php echo time(); ?>();
	selected_view<?php echo time(); ?> = 4;
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
	  map_poly_array<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
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
	  map_landmark_array<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
    }
  }
  if (circleArray<?php echo time(); ?>) {
    for (i in circleArray<?php echo time(); ?>) {
	  circleArray<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
    }
  }
}
function map_trip_clearLandmarkOverlays<?php echo time(); ?>() {
 
  if (map_trip_landmark_array<?php echo time(); ?>) {
    for (i in map_trip_landmark_array<?php echo time(); ?>) {
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
	  map_trip_landmark_array<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
    }
  }
  if (trip_circleArray<?php echo time(); ?>) {
    for (i in trip_circleArray<?php echo time(); ?>) {
	  trip_circleArray<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
    }
  }
}
function map_clearTripOverlays<?php echo time(); ?>() {
   if (routePolyArr<?php echo time(); ?>) {
		for (i in routePolyArr<?php echo time(); ?>) {
			  routePolyArr<?php echo time(); ?>[i].setMap(null);
		}
	}
}
function map_showTripOverlays<?php echo time(); ?>() { 
	if (routePolyArr<?php echo time(); ?>) {
		for (i in routePolyArr<?php echo time(); ?>) {
			  routePolyArr<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
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
function tripDrawCircle<?php echo time(); ?>(center, rad, dUnit, map) {
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
	trip_circleArray<?php echo time(); ?>.push(draw_circle);
}
function onLoadmap1<?php echo time(); ?>() {
	directionsService = new google.maps.DirectionsService();
	var mapObjmap1 = document.getElementById("map1<?php echo time(); ?>");
	var mapObjmap2 = document.getElementById("map2<?php echo time(); ?>");
	var mapObjmap3 = document.getElementById("map3<?php echo time(); ?>");
	var mapObjmap4 = document.getElementById("map4<?php echo time(); ?>");
	
	mapOptionsmap = {
		zoom: 3,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmap.center = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	
	map1<?php echo time(); ?> = new google.maps.Map(mapObjmap1,mapOptionsmap);
	map2<?php echo time(); ?> = new google.maps.Map(mapObjmap2,mapOptionsmap);
	map3<?php echo time(); ?> = new google.maps.Map(mapObjmap3,mapOptionsmap);
	map4<?php echo time(); ?> = new google.maps.Map(mapObjmap4,mapOptionsmap);
	
	map1<?php echo time(); ?>.enableKeyDragZoom();
	map2<?php echo time(); ?>.enableKeyDragZoom();
	map3<?php echo time(); ?>.enableKeyDragZoom();
	map4<?php echo time(); ?>.enableKeyDragZoom();
	
	
	/*
	// Create the DIV to hold the control and call the TrackControl() constructor
  	// passing in this DIV.
	var trackControlDiv = document.createElement('DIV');
    var trackControl = new TrackControl<?php echo time(); ?>(trackControlDiv, map1<?php echo time(); ?>);	
	trackControlDiv.index = 1;
	map1<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	
	
	//new window btn		
	<?php if($this->session->userdata('show_map_area_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new areaControl<?php echo time(); ?>(trackNewWindowDiv, map1<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map1<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>
	//new window btn
	<?php if($this->session->userdata('show_map_landmark_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new landmarkControl<?php echo time(); ?>(trackNewWindowDiv, map1<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map1<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>
	//Trip Button
	
	<?php if($this->session->userdata('show_map_trip_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new tripControl<?php echo time(); ?>(trackNewWindowDiv, map1<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map1<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>
	*/
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new zoomControl1<?php echo time(); ?>(trackNewWindowDiv, map1<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map1<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new zoomControl2<?php echo time(); ?>(trackNewWindowDiv, map2<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map2<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new zoomControl3<?php echo time(); ?>(trackNewWindowDiv, map3<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map3<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new zoomControl4<?php echo time(); ?>(trackNewWindowDiv, map4<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map4<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	/*
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new addMap1<?php echo time(); ?>(trackNewWindowDiv, map1<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map1<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new addMap2<?php echo time(); ?>(trackNewWindowDiv, map2<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map2<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new addMap3<?php echo time(); ?>(trackNewWindowDiv, map3<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;	
	map3<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new addMap4<?php echo time(); ?>(trackNewWindowDiv, map4<?php echo time(); ?>);
	trackNewWindowDiv.index = 1;
	map4<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	*/
	
	mbounds1<?php echo time(); ?> = new google.maps.LatLngBounds();
	mbounds2<?php echo time(); ?> = new google.maps.LatLngBounds();
	mbounds3<?php echo time(); ?> = new google.maps.LatLngBounds();
	mbounds4<?php echo time(); ?> = new google.maps.LatLngBounds();
	
	DirectRefresh<?php echo time(); ?>();
	/*
	<?php 
	if(count($coords) > 0) {
			foreach ($coords as $coord) {
				$minutes_before = ($coord->beforeTime)/60;
				$text ="<div style='margin:3px;'>";
				$text .="<div style='background-color: lightgreen; text-align: center; border-radius: 7px 7px 7px 7px;'>".$coord->received_time.", ".date("d.m.Y h:i a",strtotime($coord->add_date))."</div><span style='display: block ! important; width: 100%; height: 7px;'></span>";
				if($this->session->userdata('show_map_driver_detail_window')==1){
				$text .="<div align='center' style='float:left;verticle-align:middle'><img src='".base_url()."assets/assets_photo/";
				if($coord->assets_image_path!= NULL || $coord->assets_image_path!="")
				{
					$text .= $coord->assets_image_path."' />";
				}
				else
				{
					$text .= "truck.png' />";
				}
				$text.="<span style='display: block; height: 13px;'></span><img src='".base_url()."assets/driver_photo/";
				if($coord->driver_image!= NULL || $coord->driver_image!="")
				{
					$text .= $coord->driver_image."' />";
				}
				else
				{
					$text .= "not_available.jpg' />";
				}
				$text.="</div>";
				}
				$text .="<div style='height:120px;margin:3px;width:200px;float:left'>";
				$text .="<div style='height: 63px ! important; margin-top: -2px;'><span style='display: block;'> ".$coord->assets_name;
				$tag = $coord->assets_name;
				if($coord->assets_friendly_nm!="" || $coord->assets_friendly_nm!=null)
					$text.=" (".$coord->assets_friendly_nm.") ";
					if($coord->assets_name != $coord->assets_friendly_nm)
						$tag .= "<br>".$coord->assets_friendly_nm;
				if($this->session->userdata('usertype_id')!=3){
					$text.=" (".$coord->device_id.") </span>";
				}
				if($coord->ignition == 0)
					$ignition = "OFF";
				else 
					$ignition = "ON";
				$text .="<span style='display: block;'> Ignition: ".$ignition." , Speed: ";
				$text .=" ".$coord->speed." KM </span>";
				$tag .= "<br>".$coord->speed." KM";
				$text .="<span style='display: block;'>";
				
				if($coord->address != "")
					$text .= " ".$coord->address;
				$text .="</span>";
				if($this->session->userdata('show_dash_legends')==1){
					$text .="<span style='display: block;'> Status: ";
					if($minutes_before < $this->session->userdata('network_timeout') && $coord->speed > 0 && $minutes_before != ""){
							$status ="Running";
					}else if($minutes_before < $this->session->userdata('network_timeout') && $coord->speed == 0 && $coord->ignition == 0 && $minutes_before != ""){
							$status ="Parked";
					}else if($minutes_before < $this->session->userdata('network_timeout') && $coord->speed == 0 && $coord->ignition == 1 && $minutes_before != ""){
							$status ="Idle";
					}else if($minutes_before >= $this->session->userdata('network_timeout') && $minutes_before <= ($this->session->userdata('network_timeout')+36000) && $minutes_before != ""){
							$status ="Out of network";
					}else if($minutes_before > ($this->session->userdata('network_timeout')+36000) or $minutes_before ==""){
							$status ="Out of network";
					}
				}
				$text .= $status;
				$tag .= "<br>".$status;
				
				$text .="</span>";
				if($this->session->userdata('show_map_driver_detail_window')==1){
				$text .="<span style='display: block;'>Driver Name: ";
				if($coord->driver_name!="" || $coord->driver_name!=null) 
				$text .= $coord->driver_name; 
				else 
				$text .="N/A";  
				$text .=" </span>";
				$text .="<span style='display: block;'>Driver Mob.:";
				if($coord->driver_mobile!="" || $coord->driver_mobile!=null) 
				$text .= $coord->driver_mobile; 
				else 
				$text .="N/A";  
				$text .=" </span>";
				}
				if($this->session->userdata('show_dash_dashboard_button')==1){
				$text .="<a onClick='dashboard(".$coord->assets_id.");' style='left: 213px; top: 176px; position: absolute; color: blue; text-decoration: underline; cursor: pointer;'>View Dashboard</a>";
				}
				$text .="</div></div></div>";
				if($coord->icon_path=="" || $coord->icon_path==null)
				{
				if($minutes_before <= 10){
					if($coord->speed > 0)					
						$coord->icon_path = 'marker-GREEN-START.png';
					else
						$coord->icon_path = 'marker-GREEN-END.png';
				}
				else
					$coord->icon_path = 'kml-ORANGE-END.png';
				}
				$assets_name = explode("-", $coord->assets_name);
				$assets_name = end($assets_name);
	?>				
		var point = new google.maps.LatLng(<?php echo floatval($coord->lati); ?>, <?php echo floatval($coord->longi); ?>);
		if(<?php echo $location_with_tag; ?> == 1){
			var boxText = "<div style='line-height:15px;font-size:11px; font-style:italic; color:white; border: 1px solid black; margin-top: 8px; background: #666; padding: 2px;'><?php echo $tag; ?></div>";
	
			var myOptions1 = {
				 content: boxText
				,disableAutoPan: false
				,maxWidth: 0
				,position: point
				,pixelOffset: new google.maps.Size(-60,0)
				,zIndex: null
				,boxStyle: { 
				  //background: "url('tipbox.gif') no-repeat"
				  opacity: 0.75
				  ,width: "150px"
				 }
				,closeBoxMargin: "10px 2px 2px 2px"
				,closeBoxURL: ""
				//http://www.google.com/intl/en_us/mapfiles/close.gif
				,infoBoxClearance: new google.maps.Size(1, 1)
				,isHidden: false
				,pane: "floatPane"
				,enableEventPropagation: true
			};
			ib1<?php echo time(); ?> = new InfoBox(myOptions1);                
			ib1<?php echo time(); ?>.open(map1<?php echo time(); ?>);
			ibArr1<?php echo time(); ?>.push(ib1<?php echo time(); ?>);
		}
		pointArr<?php echo time(); ?>.push(point);
		<?php if($dist=="dist"){
			echo "$('#refreshbar_id".$tme."').hide();";
		} ?>
		
		markersmap1<?php echo time(); ?>.push(createMarker(map1<?php echo time(); ?>, point, "<?php echo $coord->assets_name; ?>","<?php echo $text; ?>", '<?php echo $coord->icon_path; ?>', '', "sidebar_map", '', <?php if($dist=="dist"){echo "true";}else{echo "false";} ?> ));
		
		mbounds<?php echo time(); ?>.extend(point);
		
		/*	   var myOptions = {
									 content: $("#imgR").html()
									,boxStyle: {
									   textAlign: "center"
									  ,fontSize: "8pt"
									  ,width: "18px"
									 }
									,disableAutoPan: true
									,pixelOffset: new google.maps.Size(0, 0)                      
									,position: point
									,closeBoxURL: ""
									,isHidden: false
									,pane: "mapPane"
							};

			var ib = new InfoBox(myOptions);                
			ib.open(map1<?php echo time(); ?>);
		
	<?php			
			} // End For Loop
		} else {
	?>
		var point = new google.maps.LatLng(22.297744,70.792444);
		
		markersmap1<?php echo time(); ?>.push(createMarker(map1<?php echo time(); ?>, point,"DevIndia Infoway","DevIndia Infoway", '', '', "sidebar_map", '' ));
		
		map1<?php echo time(); ?>.setCenter(point);
	<?php } ?>
	<?php if($find_distance == 1){ ?>
		//calcRoute(pointArr<?php echo time(); ?>[0], pointArr<?php echo time(); ?>[1], map1<?php echo time(); ?>);
	<?php } ?>
	
	map1<?php echo time(); ?>.setCenter(point);
	map1<?php echo time(); ?>.fitBounds(mbounds<?php echo time(); ?>);
	
  
  /*
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
		//map_polyV<?php echo time(); ?><?php echo $i; ?>.setMap(map1<?php echo time(); ?>);
		map_poly_array<?php echo time(); ?>.push(map_polyV<?php echo time(); ?><?php echo $i; ?>)			
		google.maps.event.addListener(map_polyV<?php echo time(); ?><?php echo $i; ?>,"mouseover",function(event){
			label<?php echo time(); ?><?php echo $i; ?>.setMap(map1<?php echo time(); ?>);
			$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(map_polyV<?php echo time(); ?><?php echo $i; ?>,"mouseout",function(event){
			label<?php echo time(); ?><?php echo $i; ?>.setMap(null);
		});
		google.maps.event.addListenerOnce(map1<?php echo time(); ?>, 'idle', function() {
			google.maps.event.trigger(map1<?php echo time(); ?>, 'resize');
			//map1<?php echo time(); ?>.setCenter(point); // be sure to reset the map center as well
		});
				
	<?php $i++; } ?>
	<?php
	$i = 0;
	if(count($landmarks) > 0) {
		foreach ($landmarks as $landmark) {
			$distance_unit = $landmark->distance_unit;
			$text = "Name : ".$landmark->name."<br>";
			//$text .= "Address : ".$landmark->address."<br>";
			//$text .= "Assets : ".$landmark->assets.'<br>';
		//	$text .= "<img src='".$landmark->assets.'<br>';
	?>				
			var point = new google.maps.LatLng(<?php echo floatval($landmark->lat); ?>, <?php echo floatval($landmark->lng); ?>);		
			
			map_landmark_array<?php echo time(); ?>.push(createLandmarkMarker<?php echo time(); ?>(map1<?php echo time(); ?>, point, "<?php echo $landmark->name; ?>","<?php echo $text; ?>", '<?php echo $landmark->icon_path; ?>', '', "sidebar_map", '' ));
			DrawCircle<?php echo time(); ?>(point, '<?php echo $landmark->radius; ?>', '<?php echo $distance_unit; ?>', map1<?php echo time(); ?>);
	<?php
		$i++;
		} // End For Loop
	}
	
	?>
	
	//loadRoute<?php echo time(); ?>('<?php echo $ids; ?>');
	
	map_clearLandmarkOverlays<?php echo time(); ?>();
	if(markersmap1<?php echo time(); ?>.length > 2){
		markerClusterMap<?php echo time(); ?> = new MarkerClusterer(map1<?php echo time(); ?>, markersmap1<?php echo time(); ?>, mcOptionsAllpoint);
	}
	*/
}
function createMarkerWithNumber(map, number, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/marker_image.php?text="+number+"&icon="+icon;}
	
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
		  minWidth : 200
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
function loadRoute<?php echo time(); ?>(assets_ids){
	if(assets_ids!=0){
	$.post(
	   "<?php echo site_url('home/loadRouteMap'); ?>",{'assets_ids':assets_ids},
	   function(data){
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
					
					wpts = [];
					for (var k=0; k<rWaypoints.length; k++) {
					  wpts = rWaypoints[k].split(",");
					  waypts.push({location:new google.maps.LatLng(parseFloat(wpts[0]), parseFloat(wpts[1]))});
					}
					
					slArr<?php echo time(); ?>[i] = start;
					elArr<?php echo time(); ?>[i] = end;
					wayPointsArr<?php echo time(); ?>[i] = waypts;
					clrArr<?php echo time(); ?>[i] = rColor;
				}
			}
			
			for(i=0; i<data.landmarks.length; i++){
				var text = data.landmarks[i].name+"<br>";
				text += data.landmarks[i].address+"<br>";
			
				var point = new google.maps.LatLng(data.landmarks[i].lat, data.landmarks[i].lng);		
				map_trip_landmark_array<?php echo time(); ?>.push(createLandmarkMarker<?php echo time(); ?>(map1<?php echo time(); ?>, point, data.landmarks[i].name, text, data.landmarks[i].icon_path, '', "sidebar_map", '' ));
				<?php if($this->session->userdata('usertype_id') < 3){ ?>
				tripDrawCircle<?php echo time(); ?>(point, data.landmarks[i].radius, data.landmarks[i].distance_unit, map1<?php echo time(); ?>);				
				<?php } ?>
			}
			<?php if($this->session->userdata('usertype_id') < 3){ ?>
				map_trip_clearLandmarkOverlays<?php echo time(); ?>();
			<?php } ?>
			drawRoute<?php echo time(); ?>();
		},'json');
	}
}
var plotDirection<?php echo time(); ?> = 0;
 function drawRoute<?php echo time(); ?>(){		
		
		if(plotDirection<?php echo time(); ?> < slArr<?php echo time(); ?>.length){
			i = plotDirection<?php echo time(); ?>;
			s1 = slArr<?php echo time(); ?>[i];
			e1 = elArr<?php echo time(); ?>[i];
			wp1 = wayPointsArr<?php echo time(); ?>[i];
			color = clrArr<?php echo time(); ?>[i];
			
			var polylineOptionsActual = new google.maps.Polyline({
				strokeColor: color,
				strokeOpacity: 1.0,
				strokeWeight: 4
				});

			dDisplay<?php echo time(); ?>[i] = new google.maps.DirectionsRenderer({polylineOptions: polylineOptionsActual});
			dDisplay<?php echo time(); ?>[i].setMap(map1<?php echo time(); ?>);
			var request1 = {
				origin:s1, 
				destination:e1,
				waypoints: wp1,
				optimizeWaypoints: true,
				avoidHighways: true,
				avoidTolls: true,
				provideRouteAlternatives: false,
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
									map: map1<?php echo time(); ?>,
									strokeColor: color,
									strokeOpacity: 0.7,
									strokeWeight: 4,
									path: stp[ss].path
									//path: path,
							}
							routePolyArr<?php echo time(); ?>.push(new google.maps.Polyline(polylineOptions));
						}
					}
					setTimeout("drawRoute<?php echo time(); ?>()", 200);
					plotDirection<?php echo time(); ?>++;
				}
				else {
					alert("An error occurred - " + status);
				}				  
			});	
			
		}else{
			plotDirection<?php echo time(); ?> = 0;
			<?php if($this->session->userdata('usertype_id') < 3){ ?>
			map_clearTripOverlays<?php echo time(); ?>();
			<?php } ?>
		}
  }
function createLandmarkMarker<?php echo time(); ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
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
function startLoading<?php echo time(); ?>(){
	DirectRefresh<?php echo time(); ?>();
	if(timer_on<?php echo $time; ?>==1)
	{
		$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
		counter<?php echo $time; ?>();
	}	
}
function DirectRefresh<?php echo time(); ?>(){
	$("#loading_top").css("display","block");
	if(map1Assets<?php echo time(); ?> != ""){
		$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/"+map1Assets<?php echo time(); ?>,
		 function(data) {
			if(data){
				DirectRefreshReply<?php echo time(); ?>(1, data);				
			}
		 }, 'json'
		);
	}
	if(map2Assets<?php echo time(); ?> != ""){
		$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/"+map2Assets<?php echo time(); ?>,
		 function(data) {
			if(data){
				DirectRefreshReply<?php echo time(); ?>(2, data);				
			}
		 }, 'json'
		);
	}
	if(map3Assets<?php echo time(); ?> != ""){
		$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/"+map3Assets<?php echo time(); ?>,
		 function(data) {
			if(data){
				DirectRefreshReply<?php echo time(); ?>(3, data);				
			}
		 }, 'json'
		);
	}
	if(map4Assets<?php echo time(); ?> != ""){
		$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/"+map4Assets<?php echo time(); ?>,
		 function(data) {
			if(data){
				DirectRefreshReply<?php echo time(); ?>(4, data);				
			}
		 }, 'json'
		);
	}
}
var zoom_set_1<?php echo time(); ?> = true;
var zoom_set_2<?php echo time(); ?> = true;
var zoom_set_3<?php echo time(); ?> = true;
var zoom_set_4<?php echo time(); ?> = true;
function DirectRefreshReply<?php echo time(); ?>(mview, data){
	$("#loading_top").css("display","none");
	if(mview == 1){
		clearOverlays1<?php echo time(); ?>();
		current_map = map1<?php echo time(); ?>;
		current_bound = mbounds1<?php echo time(); ?>;
		if(zoom_set_1<?php echo time(); ?>){
			current_map.setZoom(13);
			zoom_set_1<?php echo time(); ?> = false;
		}
	}
	if(mview == 2){
		clearOverlays2<?php echo time(); ?>();
		current_map = map2<?php echo time(); ?>;
		current_bound = mbounds2<?php echo time(); ?>;
		if(zoom_set_2<?php echo time(); ?>){
			current_map.setZoom(13);
			zoom_set_2<?php echo time(); ?> = false;
		}
	}
	if(mview == 3){
		clearOverlays3<?php echo time(); ?>();
		current_map = map3<?php echo time(); ?>;
		current_bound = mbounds3<?php echo time(); ?>;
		if(zoom_set_3<?php echo time(); ?>){
			current_map.setZoom(13);
			zoom_set_3<?php echo time(); ?> = false;
		}
	}
	if(mview == 4){
		clearOverlays4<?php echo time(); ?>();
		current_map = map4<?php echo time(); ?>;
		current_bound = mbounds4<?php echo time(); ?>;
		if(zoom_set_4<?php echo time(); ?>){
			current_map.setZoom(13);
			zoom_set_4<?php echo time(); ?> = false;
		}
	}
	var lat = data.lat;
	var lng = data.lng;
	var html = data.html;
	var tag = data.tag;
	var direction = data.direction;
	var status = data.status;
	var location_with_tag = data.location_with_tag;
	var speed = data.speed;
	var title = data.title;
	var icon_path=data.icon_path;
	var beforeTime = data.beforeTime;
	if(lat.length > 0){
		for(i=0; i<lat.length; i++){
			if(icon_path[i]=="" || icon_path[i]==null)
			{
			if(beforeTime[i] <= 10){
				if(data.speed[i] > 0)					
					icon_path[i] = 'marker-GREEN-START.png';
				else
					icon_path[i] = 'marker-GREEN-END.png';
			}
			else
					icon_path[i] = 'kml-ORANGE-END.png';
			}
			if(status[i] == 'Running')	{
				var status_img = 'green_dot.png';
			}else if(status[i] == 'Parked'){
				var status_img = 'blue_dot.png';
			}else if(status[i] == 'Idle'){
				var status_img = 'green_dot.png';
			}else if(status[i] == 'Out of network'){
				var status_img = 'RedDot.png';
			}else if(status[i] == 'Out of network'){
				var status_img = 'RedDot.png';
			}
			var point = new google.maps.LatLng(lat[i], lng[i]);
			current_bound.extend(point);
			if(location_with_tag == 1){

			    var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; color: #2E6E9E; border: 1px solid black; background: none repeat scroll 0% 0% #DFEFFC; padding: 2px; margin-top: 4px; text-align:center; -moz-border-radius: 8px; border-radius: 8px; white-space: nowrap;'><img src='<?php echo base_url(); ?>assets/images/"+status_img+"' title='"+status[i]+"'><img src='<?php echo base_url(); ?>assets/images/direction.jpg' style='transform: rotate("+direction[i]+"deg);-ms-transform:rotate("+direction[i]+"deg);-webkit-transform:rotate("+direction[i]+"deg);' title='Direction'>&nbsp;"+tag[i]+"</div>";
			    
/*
			    var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; font-style: italic; color: white; border: 1px solid black; background: none repeat scroll 0% 0% #8467D7; padding: 2px; margin-top: 8px;text-align:center;-moz-border-radius: 8px; border-radius: 8px;'><img src='<?php echo base_url(); ?>assets/images/direction.jpg' style='transform: rotate("+direction[i]+"deg);-ms-transform:rotate("+direction[i]+"deg);-webkit-transform:rotate("+direction[i]+"deg);' title='Direction'>&nbsp;<img src='<?php echo base_url(); ?>assets/images/"+status_img+"' title='"+status[i]+"'>&nbsp;"+tag[i]+"</div>";
*/				
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
					  ,width: "auto"
					 }
					,closeBoxMargin: "10px 2px 2px 2px"
					,closeBoxURL: ""
					//http://www.google.com/intl/en_us/mapfiles/close.gif
					,infoBoxClearance: new google.maps.Size(1, 1)
					,isHidden: false
					,pane: "floatPane"
					,enableEventPropagation: true
				};
				ib1<?php echo time(); ?> = new InfoBox(myOptions1);                
				ib1<?php echo time(); ?>.open(current_map);
				ibArr1<?php echo time(); ?>.push(ib1<?php echo time(); ?>);
			}
			
			markersmap1<?php echo time(); ?>.push(createMarker(current_map, point, title[i], html[i], icon_path[i], '', "sidebar_map", '' ));
			
		}
		current_map.setCenter(point);
		//current_map.fitBounds(current_bound);
		//if(lat.length > 1){
			//markerClusterMap<?php echo time(); ?>.clearMarkers();
			//markerClusterMap<?php echo time(); ?> = new MarkerClusterer(current_map, markersmap1<?php echo time(); ?>, mcOptionsAllpoint);
		//}
	}
}
function clearOverlays1<?php echo time(); ?>() {
  
  if (markersmap1<?php echo time(); ?>) {
    for (i in markersmap1<?php echo time(); ?>) {
      markersmap1<?php echo time(); ?>[i].setMap(null);
    }
  }
  if (ibArr1<?php echo time(); ?>) {
    for (i in ibArr1<?php echo time(); ?>) {
      ibArr1<?php echo time(); ?>[i].setMap(null);
    }
  }
  ibArr1<?php echo time(); ?> = [];
  markersmap1<?php echo time(); ?> = [];
}
function clearOverlays2<?php echo time(); ?>() {
  
  if (markersmap2<?php echo time(); ?>) {
    for (i in markersmap2<?php echo time(); ?>) {
      markersmap2<?php echo time(); ?>[i].setMap(null);
    }
  }
  if (ibArr2<?php echo time(); ?>) {
    for (i in ibArr2<?php echo time(); ?>) {
      ibArr2<?php echo time(); ?>[i].setMap(null);
    }
  }
  ibArr2<?php echo time(); ?> = [];
  markersmap2<?php echo time(); ?> = [];
}
function clearOverlays3<?php echo time(); ?>() {
  
  if (markersmap3<?php echo time(); ?>) {
    for (i in markersmap3<?php echo time(); ?>) {
      markersmap3<?php echo time(); ?>[i].setMap(null);
    }
  }
  if (ibArr3<?php echo time(); ?>) {
    for (i in ibArr3<?php echo time(); ?>) {
      ibArr3<?php echo time(); ?>[i].setMap(null);
    }
  }
  ibArr3<?php echo time(); ?> = [];
  markersmap3<?php echo time(); ?> = [];
}
function clearOverlays4<?php echo time(); ?>() {
  
  if (markersmap4<?php echo time(); ?>) {
    for (i in markersmap4<?php echo time(); ?>) {
      markersmap4<?php echo time(); ?>[i].setMap(null);
    }
  }
  if (ibArr4<?php echo time(); ?>) {
    for (i in ibArr4<?php echo time(); ?>) {
      ibArr4<?php echo time(); ?>[i].setMap(null);
    }
  }
  ibArr4<?php echo time(); ?> = [];
  markersmap4<?php echo time(); ?> = [];
}
onLoadmap1<?php echo time(); ?>();
function changeViewMap(val){
	if(val == 1){
		$("#map2<?php echo time(); ?>").hide();
		$("#map3<?php echo time(); ?>").hide();
		$("#map4<?php echo time(); ?>").hide();
		$("#map1<?php echo time(); ?>").css("min-height", "98%");
		$("#map1<?php echo time(); ?>").css("width", "100%");
	}else if(val == 2){
		$("#map2<?php echo time(); ?>").show();
		$("#map3<?php echo time(); ?>").hide();
		$("#map4<?php echo time(); ?>").hide();
		$("#map1<?php echo time(); ?>").css("min-height", "98%");
		$("#map2<?php echo time(); ?>").css("min-height", "98%");
		$("#map1<?php echo time(); ?>").css("width", "50%");
		$("#map2<?php echo time(); ?>").css("width", "49%");
	}
	else if(val == 4){
		$("#map2<?php echo time(); ?>").show();
		$("#map3<?php echo time(); ?>").show();
		$("#map4<?php echo time(); ?>").show();
		$("#map1<?php echo time(); ?>").css("min-height", "49%");
		$("#map2<?php echo time(); ?>").css("min-height", "49%");
		$("#map1<?php echo time(); ?>").css("width", "50%");
		$("#map2<?php echo time(); ?>").css("width", "49%");
	}
}
function zoomIn<?php echo time(); ?>(val){
	if(val == 1){
		$("#map2<?php echo time(); ?>").hide();
		$("#map3<?php echo time(); ?>").hide();
		$("#map4<?php echo time(); ?>").hide();
		$("#map1<?php echo time(); ?>").css("min-height", "98%");
		$("#map1<?php echo time(); ?>").css("width", "100%");
	}else if(val == 2){
		$("#map1<?php echo time(); ?>").hide();
		$("#map3<?php echo time(); ?>").hide();
		$("#map4<?php echo time(); ?>").hide();
		$("#map2<?php echo time(); ?>").css("min-height", "98%");
		$("#map2<?php echo time(); ?>").css("width", "100%");
	}
	else if(val == 3){
		$("#map2<?php echo time(); ?>").hide();
		$("#map1<?php echo time(); ?>").hide();
		$("#map4<?php echo time(); ?>").hide();
		$("#map3<?php echo time(); ?>").css("min-height", "98%");
		$("#map3<?php echo time(); ?>").css("width", "100%");
	}
	else if(val == 4){
		$("#map2<?php echo time(); ?>").hide();
		$("#map3<?php echo time(); ?>").hide();
		$("#map1<?php echo time(); ?>").hide();
		$("#map4<?php echo time(); ?>").css("min-height", "98%");
		$("#map4<?php echo time(); ?>").css("width", "100%");
	}
}
function zoomOut<?php echo time(); ?>(val){
	$("#map1<?php echo time(); ?>").show();
	$("#map2<?php echo time(); ?>").show();
	$("#map3<?php echo time(); ?>").show();
	$("#map4<?php echo time(); ?>").show();
	$("#map1<?php echo time(); ?>").css("min-height", "49%");
	$("#map2<?php echo time(); ?>").css("min-height", "49%");
	$("#map1<?php echo time(); ?>").css("width", "50%");
	$("#map2<?php echo time(); ?>").css("width", "49%");
	
	$("#map3<?php echo time(); ?>").css("min-height", "49%");
	$("#map4<?php echo time(); ?>").css("min-height", "49%");
	$("#map3<?php echo time(); ?>").css("width", "50%");
	$("#map4<?php echo time(); ?>").css("width", "49%");
}
function addAssetsMap<?php echo time(); ?>(){
	$("#alert_dialog<?php echo $time; ?>").html('<select name="device[]" id="device_area<?php echo $time; ?>" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple="multiple">'+assets_combo_opt_report+'</select>&nbsp;<input type="button" value="Done" onclick="doneSelectingAssets()">');
	
	$("#device_area<?php echo $time; ?>").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options){
		var selectedOptions = options.filter(":selected");
		var countOfSelected = selectedOptions.size();
		switch(countOfSelected) {
			case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
			case 1: return selectedOptions.text();
			case options.size(): return "<b><?php echo $this->lang->line("all_assets"); ?></b>";
			default: return countOfSelected + " Assets";
		}
	}, icon: {}, width: 190});
	$("#ddcl-device_area<?php echo $time; ?>").css('vertical-align','middle');
	$("#ddcl-device_area<?php echo $time; ?>-ddw").css('overflow-x','hidden');
	$("#ddcl-device_area<?php echo $time; ?>-ddw").css('overflow-y','auto');
	$("#ddcl-device_area<?php echo $time; ?>-ddw").css('height','300px');
	$("#ddcl-device_area<?php echo $time; ?>-ddw").css('width','190px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	$("#alert_dialog<?php echo $time; ?>").dialog('open');
}
function doneSelectingAssets(){
	var dev = '';
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-device_area<?php echo $time; ?>-i"+i).is(':checked')){
			dev+=$("#ddcl-device_area<?php echo $time; ?>-i"+i).val()+",";
		}
	}
	dev = dev.slice(0, -1);
	
	$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/"+dev,
	 function(data) {
		if(data){
			$("#alert_dialog<?php echo $time; ?>").dialog('close');
			if(selected_view<?php echo time(); ?> == 1){
				map1Assets<?php echo time(); ?> = dev;
			}
			else if(selected_view<?php echo time(); ?> == 2){
				map2Assets<?php echo time(); ?> = dev;
			}
			else if(selected_view<?php echo time(); ?> == 3){
				map3Assets<?php echo time(); ?> = dev;
			}
			else if(selected_view<?php echo time(); ?> == 4){
				map4Assets<?php echo time(); ?> = dev;
			}
			/*if(selected_view<?php echo time(); ?> == 1){
				current_map = map1<?php echo time(); ?>;
				clearOverlays1<?php echo time(); ?>();
				map1Assets<?php echo time(); ?> = dev;
				current_bound = mbounds1<?php echo time(); ?>;
			}
			else if(selected_view<?php echo time(); ?> == 2){
				current_map = map2<?php echo time(); ?>;
				clearOverlays2<?php echo time(); ?>();
				map2Assets<?php echo time(); ?> = dev;
				current_bound = mbounds2<?php echo time(); ?>;
			}
			else if(selected_view<?php echo time(); ?> == 3){
				current_map = map3<?php echo time(); ?>;
				clearOverlays3<?php echo time(); ?>();
				map3Assets<?php echo time(); ?> = dev;
				current_bound = mbounds3<?php echo time(); ?>;
			}
			else if(selected_view<?php echo time(); ?> == 4){
				current_map = map4<?php echo time(); ?>;
				clearOverlays4<?php echo time(); ?>();
				map4Assets<?php echo time(); ?> = dev;
				current_bound = mbounds4<?php echo time(); ?>;
			}
			
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			var tag = data.tag;
			var location_with_tag = data.location_with_tag;
			var speed = data.speed;
			var title = data.title;
			var icon_path=data.icon_path;
			var beforeTime = data.beforeTime;
			if(lat.length > 0){
				for(i=0; i<lat.length; i++){
					if(icon_path[i]=="" || icon_path[i]==null)
					{
					if(beforeTime[i] <= 10){
						if(data.speed[i] > 0)					
							icon_path[i] = 'marker-GREEN-START.png';
						else
							icon_path[i] = 'marker-GREEN-END.png';
					}
					else
							icon_path[i] = 'kml-ORANGE-END.png';
					}
					var point = new google.maps.LatLng(lat[i], lng[i]);
					current_bound.extend(point);
					if(location_with_tag == 1){
						var boxText = "<div style='line-height:15px;font-size:11px; font-weight:bold; font-style:italic; color:white; border: 1px solid black; margin-top: 8px; background: red; padding: 2px;'>"+tag[i]+"</div>";
						
						var myOptions1 = {
							 content: boxText
							,disableAutoPan: false
							,maxWidth: 0
							,position: point
							,pixelOffset: new google.maps.Size(-60,0)
							,zIndex: null
							,boxStyle: { 
							  //background: "url('tipbox.gif') no-repeat"
							  opacity: 0.75
							  ,width: "150px"
							 }
							,closeBoxMargin: "10px 2px 2px 2px"
							,closeBoxURL: ""
							//http://www.google.com/intl/en_us/mapfiles/close.gif
							,infoBoxClearance: new google.maps.Size(1, 1)
							,isHidden: false
							,pane: "floatPane"
							,enableEventPropagation: true
						};
						ib1<?php echo time(); ?> = new InfoBox(myOptions1);                
						ib1<?php echo time(); ?>.open(current_map);
						if(selected_view<?php echo time(); ?> == 1){
							ibArr1<?php echo time(); ?>.push(ib1<?php echo time(); ?>);
						}
						else if(selected_view<?php echo time(); ?> == 2){
							ibArr2<?php echo time(); ?>.push(ib1<?php echo time(); ?>);
						}
						else if(selected_view<?php echo time(); ?> == 3){
							ibArr3<?php echo time(); ?>.push(ib1<?php echo time(); ?>);
						}
						else if(selected_view<?php echo time(); ?> == 4){
							ibArr4<?php echo time(); ?>.push(ib1<?php echo time(); ?>);
						}
					}
					
					if(selected_view<?php echo time(); ?> == 1){
						markersmap1<?php echo time(); ?>.push(createMarker(current_map, point, title[i], html[i], icon_path[i], '', "sidebar_map", '' ));
					}
					else if(selected_view<?php echo time(); ?> == 2){
						markersmap2<?php echo time(); ?>.push(createMarker(current_map, point, title[i], html[i], icon_path[i], '', "sidebar_map", '' ));
					}
					else if(selected_view<?php echo time(); ?> == 3){
						markersmap3<?php echo time(); ?>.push(createMarker(current_map, point, title[i], html[i], icon_path[i], '', "sidebar_map", '' ));
					}
					else if(selected_view<?php echo time(); ?> == 4){
						markersmap4<?php echo time(); ?>.push(createMarker(current_map, point, title[i], html[i], icon_path[i], '', "sidebar_map", '' ));
					}
				}
				current_map.fitBounds(current_bound);
				//markerClusterMap<?php echo time(); ?>.clearMarkers();
				//markerClusterMap<?php echo time(); ?> = new MarkerClusterer(map1<?php echo time(); ?>, markersmap1<?php echo time(); ?>, mcOptionsAllpoint);
			}*/
			DirectRefreshReply<?php echo time(); ?>(selected_view<?php echo time(); ?>, data);
		}
	 }, 'json'
	);
}
</script>
<div id="alert_dialog<?php echo $time; ?>" style="display:none"></div>
<!--div>
<input type="button" value="Single View" onclick="changeViewMap(1)">
<input type="button" value="Two Map" onclick="changeViewMap(2)">
<input type="button" value="Four Map" onclick="changeViewMap(4)">
</div-->

<div id="map1<?php echo time(); ?>" style="width: 50%; float:left; min-height:49%; position:relative;border:1px solid;margin-bottom:5px;"></div>
<div id="map2<?php echo time(); ?>" style="width: 49%; float:right; min-height:49%; position:relative;border:1px solid;margin-bottom:5px;"></div>
<div id="map3<?php echo time(); ?>" style="width: 50%; float:left; clear:both; min-height:49%; position:relative;border:1px solid;"></div>
<div id="map4<?php echo time(); ?>" style="width: 49%; float:right; min-height:49%; position:relative;border:1px solid;"></div>

<div style='text-align: center;clear:both' id="refreshbar_id<?php echo $tme;?>">
<!--<div style="float:left;width:100%;height:2em;padding-top:0.2em"><input type='checkbox' onclick='stop_resume_toggle<?php echo $time; ?>()' id='timer_toggle<?php echo $time; ?>'> <?php echo $this->lang->line('data_refresh_after'); ?> <input type='text' size='2' onblur='counter_change<?php echo $time; ?>()' value='15' id='time_in_seconds<?php echo $time; ?>'> <?php echo $this->lang->line('seconds'); ?> <span style='display: inline-block;'>(<?php echo $this->lang->line('refresh_after'); ?> <span id='seconds<?php echo $time; ?>'>15</span><?php echo $this->lang->line('second'); ?>)</span> &nbsp;&nbsp;<span onClick="directRefreshMap()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php echo $this->lang->line('refresh'); ?></span></div>-->
<div style="float:left;width:100%;height:2em;padding-top:0.2em"><input type='checkbox' onclick='stop_resume_toggle<?php echo $time; ?>()' id='timer_toggle<?php echo $time; ?>' style="opacity:0;"> <?php //echo $this->lang->line('data_refresh_after'); ?> <input type='hidden' size='2' onblur='counter_change<?php echo $time; ?>()' value='30' id='time_in_seconds<?php echo $time; ?>'> <?php //echo $this->lang->line('seconds'); ?> <span style='display: inline-block;'><?php //echo $this->lang->line('refresh_after'); ?> <span id='seconds<?php echo $time; ?>' style="visibility:hidden;">30</span><?php //echo $this->lang->line('second'); ?></span> &nbsp;&nbsp;<span onClick="directRefreshMap()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php //echo $this->lang->line('refresh'); ?></span></div>
	
	<div id="pbar<?php echo $time; ?>"></div> 
	
	<!-- <a href='JavaScript:void(0);' onclick='stop_resume_toggle()' style='font-weight:bold' id='Timer_Event'>Stop Refresh</a>-->
</div>
<div style="height:10px"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$( "#pbar<?php echo $time; ?>" ).progressbar({value: 0});
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		$("#alert_dialog<?php echo $time; ?>").dialog({
			autoOpen: false,
			minHeight: '400',
			minWidth: '200',
			draggable: false,
			resizable: false,
			modal: true
		});
		loadMultiSelectDropDown();
	});
	var timer_on<?php echo $time; ?>=0;
	
	if(auto_refresh_setting == 1){
		$("#timer_toggle<?php echo $time; ?>").attr("checked", true);
		timer_on<?php echo $time; ?>=0;
	}else{
		$("#timer_toggle<?php echo $time; ?>").attr("checked", false);
		timer_on<?php echo $time; ?>=1;
	}
	
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
			startLoading<?php echo time(); ?>();
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
			$("#time_in_seconds<?php echo $time; ?>").val(15);
		$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
		time_in_s<?php echo $time; ?> = $("#time_in_seconds<?php echo $time; ?>").val();
	}
	function directRefreshMap()
	{
		$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
		DirectRefresh<?php echo $time; ?>();
	}
	stop_resume_toggle<?php echo $time; ?>();
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