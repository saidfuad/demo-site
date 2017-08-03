<?php
	$lat = $_REQUEST['lat'];
	$lng = $_REQUEST['lng'];
	$title = $_REQUEST['title'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nkonnect.com - Map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script src="assets/javascript/infobox.js"></script>
	
    <script>
var map;
function initialize() {
	var mapOptions = {
		zoom: 13,
		center: new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
	  
	var point = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
		
	createMarker(map, point, "Click Me", "<?php echo $title; ?>", 'red.png', '');
	
	var boxText = "<div style='line-height:15px;font-size:12px;font-weight:bold;font-size:15px; text-align:center; color:white; border: 1px solid black; margin-top: 8px; background: red; padding: 2px;'><?php echo $title; ?></div>";
	var myOptions1 = {
		 content: boxText
		,disableAutoPan: false
		,maxWidth: 0
		,position: point
		,pixelOffset: new google.maps.Size(-90,0)
		,zIndex: null
		,boxStyle: { 
		  //background: "url('tipbox.gif') no-repeat"
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
	ib = new InfoBox(myOptions1);                
	ib.open(map);
}
function createMarker(map, point, title, html, icon, icon_shadow){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "assets/marker-images/shadow50.png";}
	
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(html);
		google.maps.event.addListener(new_marker, 'click', function() {
			infowindow.open(map, new_marker);
		});
	}
	return new_marker;  
}
google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
<body>
	<div id="map-canvas"></div>
</body>
</html>