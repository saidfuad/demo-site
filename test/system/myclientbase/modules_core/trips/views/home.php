<?php $this->load->view('live/header'); ?>
<?php $this->load->view('assets/sidebar', array('side_block'=>array('assets/sidebar', 'settings/sidebar'),'hide_quicklinks'=>TRUE)); 

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
  controlUI.title = '<?php echo $this->lang->line("Click_to_start_or_stop_Tracking"); ?>';
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
	var mapObjmap = document.getElementById("map");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmap = {
		zoom: 13,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
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
	mapmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
		
	//AddButtons(mapmap);
	/*var bdsmap = new google.maps.LatLngBounds(new google.maps.LatLng(22.283045088, 70.782244), new google.maps.LatLng(22.312442712, 70.802644));
	mapmap.fitBounds(bdsmap);*/
	
	var point = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	markersmap.push(createMarker(mapmap, point,"DevIndia Infoway","DevIndia Infoway", '', '', "sidebar_map", '' ));
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
	polylinesmap[1].setMap(mapmap);
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

function viewLocation(lat, lng, html){
	clearOverlays();
	var point = new google.maps.LatLng(lat, lng);
	var text = "<div style='font-size:12px;height:130px;width:200px;'> " + html[i] + "</div>";
	markersmap.push(createMarker(mapmap, point,"Marker Description",text, '', '', "sidebar_map", '' ));
	
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);

}
function viewTrack(lat, lng, html){
	clearOverlays();
	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		var image = '';
		var shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
		if(i == 0){	
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-START.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
		}
		else if(i == (lat.length-1)){
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-END.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
		}
		else{
			var p1 = new google.maps.LatLng(lat[i-1], lng[i-1]);
			var p2 = new google.maps.LatLng(lat[i], lng[i]);
			var dir = bearing(p2, p1 );
			var dir = Math.round(dir/3) * 3;
			while (dir >= 120) {dir -= 120;}
			
			var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+dir+".png";
			
			shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(1, 1));
			
			image = new google.maps.MarkerImage(img, new google.maps.Size(24,24), new google.maps.Point(0,0), new google.maps.Point(10,10));
		}
		markersmap.push(createMarker(mapmap, point,"Marker Description",html[i], img, shadow, "sidebar_map", '' ));
				
		if(i > 0){
			polylineCoordsmap[i-1] = [
				new google.maps.LatLng(lat[i-1], lng[i-1])
			,
				new google.maps.LatLng(lat[i], lng[i])
			];    	
			polylinesmap[i-1] = new google.maps.Polyline({
			  path: polylineCoordsmap[i-1]
			  , strokeColor: '#FF0000'
			  , strokeOpacity: 1.0
			  , strokeWeight: 2
			});
			polylinesmap[i-1].setMap(mapmap);
		}
  	}
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);

}
function clearOverlays() {
  
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
}
var degreesPerRadian = 180.0 / Math.PI;
function bearing( from, to ) {
	// Convert to radians.
	var lat1 = from.lat();
	var lon1 = from.lng();
	var lat2 = to.lat();
	var lon2 = to.lng();
   
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
		<li><a href="#tabs-1"><?php echo $this->lang->line("Info"); ?></a></li>
		
		<div style="float:right;" align="right"> 
    		<img id="imgmaxmin" src="<?php echo base_url(); ?>assets/style/img/max_button.png" height="20px" width="20px" style="margin-top:5px; cursor:pointer;" alt="max" title="Maximize" onclick="maximize(this)" />
		</div>
	
	</ul>
	<div id="tabs-1">
		<?php echo $this->lang->line("Click on Device to view on map"); ?>
	</div>
	
</div>
<?php $this->load->view('dashboard/footer'); ?>
