<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/gmap.js"></script>
<?php 		
	echo $headerjs;
?>
<script type="text/javascript" charset="utf-8">
var t;
var last_id = <?php echo $last_id; ?>;
var markersmap  = [];

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];

var to_htmlsmap  = [];
var from_htmlsmap  = [];

var polylinesmap = [];
var polylineCoordsmap = [];
var mapmap = null;
var mapOptionsmap;

function TrackControl(controlDiv, mapmap) {

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';
	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to set the map to Home';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.height = '16px';
  controlText.style.paddingTop = '3px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = 'Start';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($(this).children().html() == "Start") {
		
		startLoading();
		
		$(this).children().html("Stop");
		
	}else if($(this).children().html() == "Stop") {
		
		clearTimeout(t);
		
		$(this).children().html("Start");
	}
	
//    map.setCenter(chicago)
  });
}

function onLoadmap() {
	
	var mapObjmap = document.getElementById("map");
	
	if (mapObjmap != 'undefined' && mapObjmap != null) {

		mapOptionsmap = {
			zoom: 13,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: true,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
		};
		
		mapmap = new google.maps.Map(mapObjmap,mapOptionsmap);
		
		var trackControlDiv = document.createElement('DIV');
		var trackControl = new TrackControl(trackControlDiv, mapmap);
		
		trackControlDiv.index = 1;
		
		mapmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
		
		var point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
		markersmap.push(createMarker(mapmap, point,"Marker Description","Marker Description", '', '', "sidebar_map", '' ));
		mapmap.setCenter(point);
				
  	}
}
function startLoading(){
	
	$.post("<?php echo base_url(); ?>index.php/live/newPoint/id/<?php echo $prefix; ?>", { id: last_id },
	 function(data) {
		if(data){
			
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			for(i=0; i<lat.length; i++){
				var point = new google.maps.LatLng(lat[i], lng[i]);
	   			markersmap.push(createMarker(mapmap, point,"Marker Description",html[i], '', '', "sidebar_map", '' ));
				
			}
			mapmap.setCenter(point);
			if(lat.length > 0){
				last_id = data.last_id;
			}
		}
	 }, 'json'
	);
	t=window.setTimeout('startLoading()',50000); 
}
</script>

<body onLoad="onLoadmap()">
<div id="map" style="width: 100%; height: 90%; position:relative;"></div>
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