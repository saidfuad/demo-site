<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');

	if($lat=="" || $lng=="")
	{
		echo "Location Not Found";
		Die();
	}
?>
<div id="map" style="width: 100%px; height: 100%;"></div>
<script type="text/javascript">
loadInfoBubble();
	var live_poly_array = [];
	var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
	map.enableKeyDragZoom();	

  //  var infowindow = new google.maps.InfoWindow();
    var marker, i;
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
        map: map
      });
	var infoBubble = new InfoBubble({
	  map: map,
	  content:"<?php echo $html; ?>",
	  shadowStyle: 1,
	  arrowSize: 10,
	  disableAutoPan: false,
	  arrowPosition: 30,
	  arrowStyle: 2,
	  minWidth : 250,
	  minHeight : 90
	});
	  google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
		  //infowindow.setContent("<?php echo $html; ?>");
		  //infowindow.open(map, marker);
		  infoBubble.open(map, marker);
        }
      })(marker, i));
	
	
$(document).ready(function () {
				
	$("#dialog_landmark_det").dialog({ 
		autoOpen: false,
		width:'50%',
		draggable: true,
		resizable: true,
		modal: false,
		title:'Route Out Log'
	});
});
</script>
<div id="dialog_landmark_det" style="display:none"> </div>

