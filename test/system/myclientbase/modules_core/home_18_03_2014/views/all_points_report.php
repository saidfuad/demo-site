<script>
$(document).ready(function () {
	get_all_points_report<?php echo time(); ?>();
	$("#loading_top").css("display","none");
});

function get_all_points_report<?php echo time(); ?>(){
	$.post("<?php echo base_url(); ?>index.php/home/get_all_points_report/", { device: <?php echo $id; ?>},
	 function(data) {
		$("#stop_report<?php echo time(); ?>").html('');
		$("#stop_report<?php echo time(); ?>").html(data);
	 });
	
}
function viewAllpointonmap(lat, lng, html){
	onLoadmapAllpoint_dash();
	clearOverlaysAllpoint_dash();
	var point = new google.maps.LatLng(lat, lng);
	var text = "<div style='font-size:12px;line-height: 14px;'> " + html + "</div>";
	markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",text, '', '', "sidebar_map", '' ));	
	mapmapAllpoint.setCenter(point);
	
}
function onLoadmapAllpoint_dash() {
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
	$("#allpoints_grid_div<?php echo time(); ?>").hide();
	$("#all_pont_map<?php echo time(); ?>").show();
	directionsService = new google.maps.DirectionsService();
	var mapObjmap = document.getElementById("all_pont_map<?php echo time(); ?>");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmapAllpoint = {
		zoom: 7,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmapAllpoint.center = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	
	mapmapAllpoint = new google.maps.Map(mapObjmap,mapOptionsmapAllpoint);
	mapmapAllpoint.enableKeyDragZoom();	
	allpointBounds = new google.maps.LatLngBounds();
  }
}
function clearOverlaysAllpoint_dash() {
	if (directionsDisplayAllpoint) {
		for (i in directionsDisplayAllpoint) {
		  directionsDisplayAllpoint[i].setMap(null);
		}
	 }
	directionsDisplayAllpoint = [];	
	if(arrowMarkerAllpoint.length > 0){
		arrowMarkerAllpoint = [];
		//markerClusterAllpoint.clearMarkers();
	}
	for(i=0; i< (mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
		mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
	}
	if (markersmapAllpoint) {
		for (i in markersmapAllpoint) {
			markersmapAllpoint[i].setMap(null);
		}
	}
	if (polylinesmapAllpoint) {
		for (i in polylinesmapAllpoint) {
			polylinesmapAllpoint[i].setMap(null);
		}
	}
	markersmapAllpoint = [];
	polylinesmapAllpoint = [];
	wayptsAllpoint = [];
}
</script>
<style type="text/css">
.ui-widget .ui-widget{
	overflow : auto !important;
}
.jqplot-yaxis-label{
	left : -20px !important;
}
.widgetcontent{
	overflow : auto !important;
}
.jqplot-title{
	top : -11px !important;
}
.jqplot-xaxis-label{
	top : 40px !important;
}
</style>
<div id="stop_report<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
