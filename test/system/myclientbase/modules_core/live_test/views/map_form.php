<?php
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
<script type="text/javascript">
function test(){
var lat = new Array();
var lng = new Array(); 
var html = new Array();
lat[0] = 22.29209;
lat[1] = 22.3021545;
lat[2] = 22.3121545;
lng[0] = 70.79221;
lng[1] = 70.782444;
lng[2] = 70.802444;
html[0] = 'aaa';
html[1] = 'bbb';
html[2] = 'ccc';
viewTrack(lat, lng, html);
}

</script>
<!--<div><a href="#" onclick="viewLocation(22.292090,70.792210, 'rajkot')">Device 1</a></div>
<div><a href="#" onclick="viewLocation(23.255490,69.659240, 'bhuj')">Device 2</a></div>
<div><a href="#" onclick="viewLocation(23.016320,72.613590, 'ahmedabad')">Device 3</a></div>
<div><a href="#" onclick="test()">Track</a></div>-->
<script type="text/javascript" charset="utf-8">

var markersmap  = [];

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];

var to_htmlsmap  = [];
var from_htmlsmap  = [];

var polylinesmap = [];
var polylineCoordsmap = [];
var mapmap = null;
var mapOptionsmap;
function onLoadmapNew() {
	var mapObjmap = document.getElementById("map_new");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmap = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmap.center = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	
	mapmap = new google.maps.Map(mapObjmap,mapOptionsmap);
	mapmap.enableKeyDragZoom();	
	var bdsmap = new google.maps.LatLngBounds(new google.maps.LatLng(22.283045088, 70.782244), new google.maps.LatLng(22.312442712, 70.802644));
	mapmap.fitBounds(bdsmap);
	var point = new google.maps.LatLng(22.2833333,70.8);
	markersmap.push(createMarker(mapmap, point,"Marker Description","Marker Description", '', '', "sidebar_map", '' ));

  }
}
onLoadmapNew();


</script>
<div id="map_new" style="width: 100%; height: 90%; position:relative;"></div>

