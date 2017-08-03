<script>
var map<?php echo time(); ?> = null;
var markersmap<?php echo time(); ?>  = [];	
$(document).ready(function () {
	onLoadmap<?php echo time(); ?>();
});
	//window[selected_assets_ids+"_t"] = window.setInterval('refreshDash<?php echo time(); ?>()',10000); 
	
	function refreshDash<?php echo time(); ?>(){
		get_distance<?php echo time(); ?>();
		get_location<?php echo time(); ?>();
		get_speed<?php echo time(); ?>();
		get_speed_graph<?php echo time(); ?>();
		get_distance_graph<?php echo time(); ?>();
	}
	
	function get_location<?php echo time(); ?>(){
		
		$.post("<?php echo base_url(); ?>index.php/home/assets_location/id/<?php echo $id; ?>",
		 function(msg) {
		// alert(msg.toSource());
			if(msg){
				
					clearOverlays<?php echo time(); ?>();
					var lat = msg.lat;
					var lng = msg.lng;
					var point = new google.maps.LatLng(lat, lng);
					var html = msg.speed+' KM/H,';
					if(msg.address != "")
						html += msg.address;
					html += '<br>'+msg.date+'('+msg.before+')';
					
					var myTextDiv = document.createElement('div');
					myTextDiv.id = 'my_text_div';
					myTextDiv.innerHTML = "<span style='font-size: 12px; line-height: 15px; color: black; background-color: rgba(255, 255, 255, 0.52);'><b>"+html+"</b></span>";
					myTextDiv.style.color = 'white';
					
					for(i=0; i< (map<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_CENTER].length); i++){
							map<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_CENTER].removeAt(i);
						}
					map<?php echo time(); ?>.controls[google.maps.ControlPosition.TOP_CENTER].push(myTextDiv);
					
					if(msg.speed > 0)					
						var icon_path = 'marker-GREEN-START.png';
					else
						var icon_path = 'kml-ORANGE-END.png';
					markersmap<?php echo time(); ?>.push(createMarker<?php echo time(); ?>(map<?php echo time(); ?>, point, msg.html,msg.html, icon_path, '', "sidebar_map", '' ));
					map<?php echo time(); ?>.setCenter(point);		
			}
		  }, 'json'
		);	
	}
	
	function onLoadmap<?php echo time(); ?>() {
		var mapObjmap = document.getElementById("map<?php echo time(); ?>");
		if (mapObjmap != 'undefined' && mapObjmap != null) {

		mapOptionsmap = {
			zoom: 13,
			mapTypeId: google.maps.MapTypeId.TERRAIN,
			mapTypeControl: true,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
		};

		mapOptionsmap.center = new google.maps.LatLng(
			22.297744,
			70.792444
		);
		map<?php echo time(); ?> = new google.maps.Map(mapObjmap,mapOptionsmap);
		map<?php echo time(); ?>.enableKeyDragZoom();
		/*
		var html = '<?php echo $speed; ?> KM/H,';
		var address = '<?php echo $address; ?>';
		if(address != "")
			html += '<?php echo $address; ?>,';
		html += '<br><?php echo $date; ?> (<?php echo ago($date); ?> ago)';
		
		var myTextDiv = document.createElement('div');
		myTextDiv.id = 'my_text_div';
		myTextDiv.innerHTML = "<span style='font-size:12px;line-height:15px;'><b>"+html+"</b></span>";
		myTextDiv.style.color = 'white';
		
		for(i=0; i< (map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
				map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
			}
		map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv);
		
		
		var point = new google.maps.LatLng(<?php echo floatval($lat); ?>, <?php echo floatval($lng); ?>);
		if(<?php echo $speed; ?> > 0)					
			var icon_path = 'marker-GREEN-START.png';
		else
			var icon_path = 'kml-ORANGE-END.png';
		markersmap<?php echo time(); ?>.push(createMarker(map<?php echo time(); ?>, point, '<?php echo $html; ?>','<?php echo $html; ?>', icon_path, '', "sidebar_map", '' ));
		
		map<?php echo time(); ?>.setCenter(point);
		*/
		get_location<?php echo time(); ?>();
	  }
	}
	function clearOverlays<?php echo time(); ?>() {
	  
	  if (markersmap<?php echo time(); ?>) {
		for (i in markersmap<?php echo time(); ?>) {
		  markersmap<?php echo time(); ?>[i].setMap(null);
		}
	  }
	  markersmap<?php echo time(); ?> = [];
	}

	function createMarker<?php echo time(); ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
		var marker_options = {
			position: point,
			map: map,
			title: title};  
		if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/assets/marker-images/" + icon;}
		if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
		//create marker
		var new_marker = new google.maps.Marker(marker_options);
		if(html!=''){
						
			var infoBubble = new InfoBubble({
			  map: map,
			  content:html,
			  shadowStyle: 1,
			  arrowSize: 10,
			  disableAutoPan: true,
			  arrowPosition: 30,
			  arrowStyle: 2,
			  minWidth : 200
			});

			google.maps.event.addListener(new_marker, 'click', function() {
				update_timeout = setTimeout(function(){
					if (!infoBubble.isOpen()) {
						//infoBubble.setContent(html);
						infoBubble.open(map, new_marker);
					}
				}, 200);
			});
			google.maps.event.addListener(new_marker, 'dblclick', function() {
				dArr.push(point);
				  if(dArr.length == 2){
						calcRoute(dArr[0], dArr[1]);
						dArr = [];
				  }
				  if(dArr.length == 1 && directionsDisplay != undefined){
					clearDirection();
					}
				 clearTimeout(update_timeout);
			});
			
			if(openInfo == true) {
				infoBubble.open(map,new_marker);
			}
		}
		return new_marker;  
	}
</script>

<div id="map<?php echo time(); ?>" style="width: 100%; height: 200px; position:relative;"></div>

<?php
/*
function ago($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();
	   $time = strtotime($time);
       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j]";
}
*/
?>