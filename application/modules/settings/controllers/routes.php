<script type="text/javascript">
			//<![CDATA[
			
			var map; // Global declaration of the map
			var lat_longs_map = new Array();
			var markers_map = new Array();
            var iw_map;
			var directionsDisplay = new google.maps.DirectionsRenderer();
			var directionsService = new google.maps.DirectionsService();
			
			iw_map = new google.maps.InfoWindow();
				
			function initialize_map() {
				
				var myLatlng = new google.maps.LatLng(-4.043477, 39.668206);
				var myOptions = {
			  		zoom: 13,
					center: myLatlng,
			  		mapTypeId: google.maps.MapTypeId.ROADMAP}
				map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
				
				directionsDisplay.setMap(map);
				directionsDisplay.setPanel(document.getElementById("directionsDiv"));
			
				fitMapToBounds_map();
			
				calcRoute('Mombasa', 'Kampala');
				
			
			}
		
		
			function createMarker_map(markerOptions) {
				var marker = new google.maps.Marker(markerOptions);
				markers_map.push(marker);
				lat_longs_map.push(marker.getPosition());
				return marker;
			}
			function calcRoute(start, end) {
				
				var request = {
				    	origin:start,
				    	destination:end,
				    	travelMode: google.maps.TravelMode.DRIVING
				    	
				};
				  	directionsService.route(request, function(response, status) {
				    	if (status == google.maps.DirectionsStatus.OK) {
				      		directionsDisplay.setDirections(response);
				    	}else{
				    		switch (status) { 	
				    			case "NOT_FOUND": { alert("Either the start location or destination were not recognised"); break }
				    			case "ZERO_RESULTS": { alert("No route could be found between the start location and destination"); break }
				    			case "MAX_WAYPOINTS_EXCEEDED": { alert("Maximum waypoints exceeded. Maximum of 8 allowed"); break }
				    			case "INVALID_REQUEST": { alert("Invalid request made for obtaining directions"); break }
				    			case "OVER_QUERY_LIMIT": { alert("This webpage has sent too many requests recently. Please try again later"); break }
				    			case "REQUEST_DENIED": { alert("This webpage is not allowed to request directions"); break }
				    			case "UNKNOWN_ERROR": { alert("Unknown error with the server. Please try again later"); break }
				    		}
				    	}
				  	});
			}
			
			function fitMapToBounds_map() {
				var bounds = new google.maps.LatLngBounds();
				if (lat_longs_map.length>0) {
					for (var i=0; i<lat_longs_map.length; i++) {
						bounds.extend(lat_longs_map[i]);
					}
					map.fitBounds(bounds);
				}
			}
			
			google.maps.event.addDomListener(window, "load", initialize_map);
			
			//]]>
			</script>