<?php /* $this->load->view('reports/header'); */ ?>
<?php
	 //$this->load->view('reports/sidebar', array('side_block'=>array('users/sidebar', 'settings/sidebar'),'hide_quicklinks'=>TRUE)); 
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
<script type="text/javascript">
  var script = '<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/markerclusterer';
  if (document.location.search.indexOf('compiled') !== -1) {
	script += '_compiled';
  }
  script += '.js"><' + '/script>';
  document.write(script);
</script>
<script type="text/javascript" charset="utf-8">

var markersmap  = [];

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];

var to_htmlsmap  = [];
var from_htmlsmap  = [];

var polylinesmap = [];
var polylineCoordsmap = [];
var mapmap = null;
var mapOptionsmap;

var directionsDisplay = [];
var rendererOptions = {
					preserveViewport: true,
					draggable: false,
					suppressMarkers: true,
					polylineOptions: {
					   map: mapmap,
					   strokeColor:'#FF0000',
					   //strokeWidth: 3,
					   strokeOpacity: 0.7}

			};
var waypts = [];

var arrowMarker = [];
var mcOptions = {gridSize: 50, maxZoom: 15};
var markerCluster;
		
function TrackControl(controlDiv, mapmap) {

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';

  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to start or stop Tracking';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.height = '16px';
  controlText.style.paddingTop = '3px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = 'Start';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($(this).children().html() == "Start") {
		alert("<?php echo $this->lang->line("Click Stop To Stop Tracking"); ?>");
		$(this).children().html("<?php echo $this->lang->line("Stop"); ?>");
	}else {
		alert("<?php echo $this->lang->line("Click Start To Start Tracking"); ?>");
		$(this).children().html("<?php echo $this->lang->line("Start"); ?>");
	}
	
//    map.setCenter(chicago)
  });
}


function onLoadmap() {
	directionsService = new google.maps.DirectionsService();
	var mapObjmap = document.getElementById("map");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmap = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmap.center = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	
	mapmap = new google.maps.Map(mapObjmap,mapOptionsmap);
	mapmap.enableKeyDragZoom();	
		
	// Create the DIV to hold the control and call the TrackControl() constructor
  	// passing in this DIV.
	var trackControlDiv = document.createElement('DIV');
    var trackControl = new TrackControl(trackControlDiv, mapmap);
	
	trackControlDiv.index = 1;
	//mapmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
		
	//AddButtons(mapmap);
	/*var bdsmap = new google.maps.LatLngBounds(new google.maps.LatLng(22.283045088, 70.782244), new google.maps.LatLng(22.312442712, 70.802644));
	mapmap.fitBounds(bdsmap);*/
	
	//var point = new google.maps.LatLng(22.297744,70.792444);
	var point = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	
	markersmap.push(createMarker(mapmap, point,"DevIndia Infoway",'Date : 17.05.2012 12:59: pm<br />Speed : 0<br />Device : GJ12Z 2156 (2244)<br />Mobile : 8238003756', '', '', "sidebar_map", '' ));
	mapmap.setCenter(point);
	
   	polylineCoordsmap[1] = [
		new google.maps.LatLng(22.29209, 70.79221),
		new google.maps.LatLng(22.3021545, 70.782444),
		new google.maps.LatLng(22.3121545, 70.802444)
	];    	
	polylinesmap[1] = new google.maps.Polyline({
	  path: polylineCoordsmap[1]
	  , strokeColor: '#FF0000'
	  , strokeOpacity: 1.0
	  , strokeWeight: 2
	});
	//polylinesmap[1].setMap(mapmap);
  }
}

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
		
		/*
		html = '<div style="height:auto;overflow:hidden;">'+html+'</div>';
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(html);
		*/
		var infoBubble = new InfoBubble({
          map: map,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 0,
		  minWidth : 200
        });
		
		google.maps.event.addListener(new_marker, 'click', function() {
		  if (!infoBubble.isOpen()) {
			infoBubble.setContent(html);
			infoBubble.open(map, new_marker);
		  }
		});
		
		if(openers != ''&&!isEmpty(openers)){
		   for(var i in openers){
			 var opener = document.getElementById(openers[i]);
			 opener.onclick = function(){infoBubble.open(map, new_marker); return false};
		   }
		}
		
		if(sidebar_id != ''){
			var sidebar = document.getElementById(sidebar_id);
			if(sidebar!=null && sidebar!=undefined && title!=null && title!=''){
				var newlink = document.createElement('a');
				
				newlink.onclick=function(){infoBubble.open(map, new_marker); return false};
				
				newlink.innerHTML = title;
				sidebar.appendChild(newlink);
			}
		}
	}
	return new_marker;  
}

function arrowMarkerFunction(map, point, title, html, img){
	//create marker
	var new_marker = new google.maps.Marker({
		position: point,
		icon: new google.maps.MarkerImage(img,
									new google.maps.Size(24,24),
									new google.maps.Point(0,0),
									new google.maps.Point(12,12)
								   ),
		map: map,
		//title: Math.round((dir>360)?dir-360:dir)+'°'
		title : title
	});
	if(html!=''){
		html = '<div style="height:100px;">'+html+'</div>';
		var infowindow = new google.maps.InfoWindow({content: html, maxWidth:100});
		google.maps.event.addListener(new_marker, 'click', function() {
		  infowindow.open(map,new_marker);
		});
	}
	return new_marker;  
}

onLoadmap();

function viewLocation(lat, lng, html){
	clearOverlays();
	var point = new google.maps.LatLng(lat, lng);
	var text = "<div style='font-size:12px;line-height: 14px;'> " + html + "</div>";
	markersmap.push(createMarker(mapmap, point,"Marker Description",text, '', '', "sidebar_map", '' ));	
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);

}

function viewTrack(lat, lng, html, devText){
	
	clearOverlays();	
	
	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		var image = '';
		var shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
		if(i == 0){	
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-START.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmap.push(createMarker(mapmap, point,"Marker Description",html[i], img, shadow, "sidebar_map", '' ));
		}
		else if(i == (lat.length-1)){
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-END.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmap.push(createMarker(mapmap, point,"Marker Description",html[i], img, shadow, "sidebar_map", '' ));
		}
		else{
			var p1 = new google.maps.LatLng(lat[i-1], lng[i-1]);
			var p2 = new google.maps.LatLng(lat[i], lng[i]);
			
			var dir = bearing(p1, p2);
			var dir = Math.round(dir/3) * 3;
			while (dir >= 120) {dir -= 120;}
			
			a=p1,
            z=p2,
			  
			dir=((Math.atan2(z.lng()-a.lng(),z.lat()-a.lat())*180)/Math.PI)+360,
            ico=((dir-(dir%3))%120);
			
			var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+ico+".png";
			//var img = '<?php echo base_url(); ?>assets/marker-images/mini-RED-BLANK.png';
			/*
			var mkr = new google.maps.Marker({
                position: a,
                icon: new google.maps.MarkerImage(img,
                                            new google.maps.Size(24,24),
                                            new google.maps.Point(0,0),
                                            new google.maps.Point(12,12)
                                           ),
				map: mapmap,
				//title: Math.round((dir>360)?dir-360:dir)+'°'
				title : html[i]
			});
			var html = '<div style="height:100px;">'+html[i]+'</div>';
			var infowindow = new google.maps.InfoWindow({content: html, maxWidth:100});
			google.maps.event.addListener(mkr, 'click', function() {
			  infowindow.open(mapmap,mkr);
			});
			*/
			var mkr = arrowMarkerFunction(mapmap, point, "Marker Description", html[i], img)
			markersmap.push(mkr);
			arrowMarker.push(mkr);
		}
				
		if(i > 0){
			/*
			polylineCoordsmap[i-1] = [
				new google.maps.LatLng(lat[i-1], lng[i-1])
			,
				new google.maps.LatLng(lat[i], lng[i])
			];    	
			polylinesmap[i-1] = new google.maps.Polyline({
			  path: polylineCoordsmap[i-1]
			  , strokeColor: 'blue'
			  , strokeOpacity: 0.4
			  , strokeWeight: 3
			});
			polylinesmap[i-1].setMap(mapmap);
			*/
			
		}
  	}
	
	markerCluster = new MarkerClusterer(mapmap, arrowMarker, mcOptions);
	
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);
	
	var j = 0;
	if(lat.length > 9){
		for(i=0; i<lat.length; i++){
			if(i == (lat.length) - 1){
				endP = new google.maps.LatLng(lat[i], lng[i]);	
				calcRoute(startP, endP, i);
			}else{
				if(j == 10){
					j = 0;
					
				}
				if(j == 0){
					startP = new google.maps.LatLng(lat[i], lng[i]);
				}
				else if(j == 9){
					endP = new google.maps.LatLng(lat[i], lng[i]);			
					calcRoute(startP, endP, i);
					i = i-1;
					
				}else{
					waypts.push({
						location:new google.maps.LatLng(lat[i], lng[i]),
						stopover:true});		
				}
			}
			j++;
		}
	}else{
		for(i=0; i<lat.length; i++){			
			if(i == 0){
				startP = new google.maps.LatLng(lat[i], lng[i]);
			}
			else if(i == (lat.length - 1)){
				endP = new google.maps.LatLng(lat[i], lng[i]);			
				calcRoute(startP, endP, i);
			}else{
				waypts.push({
					location:new google.maps.LatLng(lat[i], lng[i]),
					stopover:true});		
			}
		}
	}
	var myTextDiv = document.createElement('div');
	myTextDiv.id = 'my_text_div';
	myTextDiv.innerHTML = '<h2>'+devText+'</h2>';
	myTextDiv.style.color = 'white';
	mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv);
	
}
function calcRoute(s1, e1, pointCounter){
		
		directionsDisplay[pointCounter] = new google.maps.DirectionsRenderer(rendererOptions);
		directionsDisplay[pointCounter].setMap(mapmap);
		var request = {
			origin:s1, 
			destination:e1,
			waypoints: waypts,
			optimizeWaypoints: true,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay[pointCounter].setDirections(response);

			}
		});	
		waypts = [];
  }
function clearOverlays() {
	if (directionsDisplay) {
		for (i in directionsDisplay) {
		  directionsDisplay[i].setMap(null);
		}
	 }
	directionsDisplay = [];	
	if(arrowMarker.length > 0){
		arrowMarker = [];
		markerCluster.clearMarkers();
	}
	for(i=0; i< (mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
		mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
	}
	if (markersmap) {
		for (i in markersmap) {
			markersmap[i].setMap(null);
		}
	}
	if (polylinesmap) {
		for (i in polylinesmap) {
			polylinesmap[i].setMap(null);
		}
	}
	markersmap = [];
	polylinesmap = [];
	waypts = [];
}
var degreesPerRadian = 180.0 / Math.PI;
function bearing( from, to ) {
	// Convert to radians.
	var lat1 = ((eval(from.lat()))*(Math.PI/180));

	var lon1 = ((eval(from.lng()))*(Math.PI/180));
	var lat2 = ((eval(to.lat()))*(Math.PI/180));
	var lon2 = ((eval(to.lng()))*(Math.PI/180));
   
   var angle = - Math.atan2( Math.sin( lon1 - lon2 ) * Math.cos( lat2 ), Math.cos( lat1 ) * Math.sin( lat2 ) - Math.sin( lat1 ) * Math.cos( lat2 ) * Math.cos( lon1 - lon2 ) );
	if ( angle < 0.0 )
		angle  += Math.PI * 2.0;
	// And convert result to degrees.
	angle = angle * degreesPerRadian;
	angle = angle.toFixed(1);
	return angle;
}
</script>

<div class="ui-layout-center" id="tabs" style="overflow: hidden;">
	<ul>
		<li><a href="#tabs-1"><?php echo $this->lang->line("map"); ?> 1</a></li>
		
		<div class="addons">
    		<img id="dashboard" src="<?php echo base_url(); ?>assets/style/img/icons/home1.png" style="cursor:pointer;" alt="Dashboard" title="Send to Dashboard" />
            
    		<img id="imgmaxmin" src="<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png" style="cursor:pointer;" alt="max" title="Maximize" onclick="maximize(this)" />
            
        	<?php echo anchor(site_url($this->uri->uri_string()), "<img src=\"".base_url()."assets/style/img/icons/new_window.png\" title=\"".$this->lang->line('new_window')."\" />", 'rel="external"'); ?>
            
        	<?php echo anchor(site_url($this->uri->uri_string()), "<img src=\"".base_url()."assets/style/img/icons/printer.png  \" title=\"".$this->lang->line('print')."\" />", 'rel="print"'); ?>
		</div>
	
	</ul>
	<div id="tabs-1">
		
		<div id="map" style="width: 100%; height: 90%; position:relative;"></div>
		<?php //echo $map; ?>
        <?php echo $onload; ?>
	</div>
	
</div>
<div id="myTextDiv" style="color: white; position: absolute;">
    <h1>Hello World</h1>
</div>
<?php $this->load->view('dashboard/footer'); ?>
<script type="text/javascript">
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>