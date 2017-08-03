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
		  minWidth : 350,
		  minHeight : 125
        });
	  google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
		  //infowindow.setContent("<?php echo $html; ?>");
		  //infowindow.open(map, marker);
		  infoBubble.open(map, marker);
        }
      })(marker, i));
	
	<?php
	$i = 0;
	foreach($pplyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php 
		$pathArr = array();
		
		for($j=0; $j<count($pplyLat[$pIdv]); $j++){
			$pathArr[] = 'new google.maps.LatLng('.sprintf("%.6f", $pplyLat[$pIdv][$j]).', '.sprintf("%.6f", $pplyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArr);
		
		if(count($pplyDev[$pIdv]) > 0){
			$devices = implode("<br>", $pplyDev[$pIdv]);
		}
		?>
		var polygonCoords = [<?php echo $pathString; ?>];

		for (i = 0; i < polygonCoords.length; i++) {
		  bounds.extend(polygonCoords[i]);
		}
		//var devices = 'plyDev'
		label<?php echo $i; ?> = new ELabel({
		latlng: bounds.getCenter(), 
		label: "<div class='elable' id='elable__<?php echo $i; ?>' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'><?php echo $pplyName[$pIdv][0]; ?></div>", 
		classname: "label", 
		offset: 0, 
		opacity: 100, 
		overlap: true,
		clicktarget: false
		});
						
		var live_polyV<?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $pplyColor[$pIdv]; ?>'
		    });
		//map_polyV<?php echo $i; ?>.setMap(map<?php echo time(); ?>);
		live_poly_array.push(live_polyV<?php echo $i; ?>)			
		google.maps.event.addListener(live_polyV<?php echo $i; ?>,"mouseover",function(event){
			label<?php echo $i; ?>.setMap(map);
			$("#elable__<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(live_polyV<?php echo $i; ?>,"mouseout",function(event){
			label<?php echo $i; ?>.setMap(null);
		});/*
		google.maps.event.addListenerOnce(map, 'idle', function() {
			google.maps.event.trigger(map, 'resize');
			//map<?php echo time(); ?>.setCenter(point); // be sure to reset the map center as well
		});*/
		/*google.maps.event.addDomListener(ib<?php  echo $prefix; ?> ,'click',function(){ 
			alert('clicked!');
		});//doesn't work*/
	<?php $i++; } ?>
	if (live_poly_array) {
		for (i in live_poly_array) {
		  live_poly_array[i].setMap(map);
		}
	  }
$(document).ready(function () {
				
	$("#dialog_landmark_det").dialog({ 
		autoOpen: false,
		width:'50%',
		draggable: true,
		resizable: true,
		modal: false,
		title:'<?php echo $this->lang->line("Route Out Log"); ?>'
	});
});
</script>
<div id="dialog_landmark_det" style="display:none"> </div>

