<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');

	if($lat=="" || $lng=="")
	{
		die("Location Not Found");
	}

	$time=time();
?>
<div id="map<?php echo $time; ?>" style="width: 100%px; height: 100%;"></div>
<script type="text/javascript"> 
    var map<?php echo $time; ?> = new google.maps.Map(document.getElementById('map<?php echo $time; ?>'), {
      zoom: 10,
      center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
    map<?php echo $time; ?>.enableKeyDragZoom();	

    var infowindow<?php echo $time; ?> = new google.maps.InfoWindow();
    var marker<?php echo $time; ?>, i<?php echo $time; ?>;
      marker<?php echo $time; ?> = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
        map: map<?php echo $time; ?>
      });

      google.maps.event.addListener(marker<?php echo $time; ?>, 'click', (function(marker<?php echo $time; ?>, i<?php echo $time; ?>) {
        return function() {
		  infowindow<?php echo $time; ?>.setContent("<?php echo $html; ?>");
		  infowindow<?php echo $time; ?>.open(map<?php echo $time; ?>, marker<?php echo $time; ?>);
        }
      })(marker<?php echo $time; ?>, i<?php echo $time; ?>));

$(document).ready(function () {
				
	$("#dialog_landmark_det<?php echo $time; ?>").dialog({ 
		autoOpen: false,
		width:'50%',
		draggable: true,
		resizable: true,
		modal: false,
		title:'Route Out Log'
	});
});
</script>
<div id="dialog_landmark_det<?php echo $time; ?>" style="display:none"> </div>
