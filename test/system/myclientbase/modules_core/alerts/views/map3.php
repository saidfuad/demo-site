
<script type="text/javascript" charset="utf-8">
var prefix = "<?php echo $prefix; ?>";
var <?php echo $prefix; ?>markersmap  = [];

var <?php echo $prefix; ?>sidebar_htmlmap  = '';
var <?php echo $prefix; ?>marker_htmlmap  = [];

var <?php echo $prefix; ?>to_htmlsmap  = [];
var <?php echo $prefix; ?>from_htmlsmap  = [];

var <?php echo $prefix; ?>polylinesmap = [];
var <?php echo $prefix; ?>polylineCoordsmap = [];
var <?php echo $prefix; ?>mapmap = null;
var <?php echo $prefix; ?>mapOptionsmap;
function <?php echo $prefix; ?>onLoadmapNew() {
	var mapObjmap = $("#<?php echo $prefix; ?>map3");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	<?php echo $prefix; ?>mapOptionsmap = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	<?php echo $prefix; ?>mapOptionsmap.center = new google.maps.LatLng(
		<?php echo $c_lat; ?> , <?php echo $c_lng; ?>
	);
	
	<?php echo $prefix; ?>mapmap = new google.maps.Map(mapObjmap,<?php echo $prefix; ?>mapOptionsmap);
	<?php echo $prefix; ?>mapmap.enableKeyDragZoom();
	
	var bdsmap = new google.maps.LatLngBounds(new google.maps.LatLng(22.283045088, 70.782244), new google.maps.LatLng(22.312442712, 70.802644));
	<?php echo $prefix; ?>mapmap.fitBounds(bdsmap);
	var point = new google.maps.LatLng(<?php echo $c_lat; ?> , <?php echo $c_lng; ?>);
	<?php echo $prefix; ?>markersmap.push(createMarker(<?php echo $prefix; ?>mapmap, point,"DevIndia Infoway","DevIndia Infoway", '', '', "sidebar_map", '' ));

  }
}
<?php echo $prefix; ?>onLoadmapNew();
</script>
<div id="<?php echo $prefix; ?>map3" style="width: 100%; height: 90%; position:relative;"></div>
