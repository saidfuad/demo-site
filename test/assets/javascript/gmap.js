function createMarker(map, point, title, html, icon, icon_shadow, sidebar_id, openers){
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		var infowindow = new google.maps.InfoWindow({content: html, maxWidth:100});
		google.maps.event.addListener(new_marker, 'click', function() {
		  infowindow.open(map,new_marker);
		});
		if(openers != ''&&!isEmpty(openers)){
		   for(var i in openers){
			 var opener = document.getElementById(openers[i]);
			 opener.onclick = function(){infowindow.open(map,new_marker); return false};
		   }
		}
		
		if(sidebar_id != ''){
			var sidebar = document.getElementById(sidebar_id);
			if(sidebar!=null && sidebar!=undefined && title!=null && title!=''){
				var newlink = document.createElement('a');
				
				newlink.onclick=function(){infowindow.open(map,new_marker); return false};
				
				newlink.innerHTML = title;
				sidebar.appendChild(newlink);
			}
		}
	}
	return new_marker;  
}

function viewLocation(map, lat, lng, html){
	clearOverlays();
	var point = new google.maps.LatLng(lat, lng);
	markersmap.push(createMarker(map, point,"Marker Description",html[i], '', '', "sidebar_map", '' ));
	
	map.setCenter(point);
}
function viewTrack(map, lat, lng, html){
	clearOverlays(map);
	
	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		
		markersmap.push(createMarker(map, point,"Marker Description",html[i], '', '', "sidebar_map", '' ));
				
		if(i > 0){
			polylineCoordsmap[i-1] = [
				new google.maps.LatLng(lat[i-1], lng[i-1])
			,
				new google.maps.LatLng(lat[i], lng[i])
			];    	
			polylinesmap[i-1] = new google.maps.Polyline({
			  path: polylineCoordsmap[i-1]
			  , strokeColor: '#cc0000'
			  , strokeOpacity: 50
			  , strokeWeight: 3
			});			
			polylinesmap[i-1].setMap(map);
		}
  	}
	map.setCenter(point);

}