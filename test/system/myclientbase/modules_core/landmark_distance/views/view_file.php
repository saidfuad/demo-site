<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
?>
<html>    
<head> 
 <!-- <script src="http://maps.google.com/maps/api/js?sensor=false"  
          type="text/javascript"></script>-->
</head>
<body>
<div id="map" style="width: 100%px; height: 100%;"></div>
<script type="text/javascript">
	var html = "<table><tr><td>Date Time : </td><td><?php echo date("$date_format $time_format", strtotime($date_time)); ?></td></tr><tr><td>Landmark Name : &nbsp;&nbsp;&nbsp;</td><td><?php echo $landmark_name;  ?></td></tr><tr><td>Device Name : &nbsp;&nbsp;&nbsp;</td><td><?php echo $device_name; ?></td></tr><tr><td>Distance : &nbsp;&nbsp;&nbsp;</td><td><?php echo $distance; ?></td></tr></table>";
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
			$("#dialog_landmark_det").html(html);
			$("#dialog_landmark_det").dialog('open');
         // infowindow.setContent(html,100);
		 // infowindow.open(map, marker);
        }
      })(marker, i));

$(document).ready(function () {
				
	$("#dialog_landmark_det").dialog({
		autoOpen: false,
		width:'50%',
		draggable: true,
		resizable: true,
		modal: false,
		title:'<?php echo $this->lang->line("Landmark")." ".$this->lang->line("Report"); ?>'
	});
	
});
</script>
<div id="dialog_landmark_det" style="display:none"> 
</body>
</html>
