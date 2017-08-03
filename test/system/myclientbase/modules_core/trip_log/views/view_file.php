<html>    
<head> 
 <!-- <script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>-->
</head> 
<body>
<div id="dialog_landmark_detail" style="display:none"></div>
<div id="map" style="width: 100%px; height: 100%;"></div>
<script type="text/javascript">
	var html = "<?php echo "Device : $device_id <br/>Date & Time : $date_time <br/> Landmark Name : $landmark_name <br/> Distance : $distance <br/>"; ?>";
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
	map.enableKeyDragZoom();	
 
    var infowindow = new google.maps.InfoWindow();

    var marker, i;

     
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
			$("#dialog_landmark_detail").html(html);
			$("#dialog_landmark_detail").dialog('open');
         // infowindow.setContent(html,100);
		 // infowindow.open(map, marker);
        }
      })(marker, i));

$(document).ready(function () {
				
	$("#dialog_landmark_detail").dialog({
		autoOpen: false,
		width:'50%',
		draggable: true,
		resizable: true,
		modal: false,
		title:'Login Info'
	});	
});

</script>
</body>
</html>
