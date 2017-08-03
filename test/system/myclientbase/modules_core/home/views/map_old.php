<?php 
$tme=time();
$date_format = $this->session->userdata('date_format');  
$time_format = $this->session->userdata('time_format');
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
loadMarkerClusters();
</script>
<script type="text/javascript">
//loadInfoBubble();
var map<?php echo $time; ?> = null;
var markersmap<?php echo $time; ?>  = [];		
var mbounds<?php echo $time; ?>;
var pointArr<?php echo $time; ?> = [];	
$( "#pbar<?php echo $time; ?>" ).progressbar({value: 0});
var map_poly_array<?php echo $time; ?> = [];
var arLabel<?php echo $time; ?> = [];
var map_poly_arrayzone<?php echo $time; ?> = [];
var map_landmark_array<?php echo $time; ?> = [];
var circleArray<?php echo $time; ?> = [];

var map_trip_landmark_array<?php echo $time; ?> = [];
var trip_circleArray<?php echo $time; ?> = [];

var mcOptionsAllpoint = {gridSize: 50, maxZoom: 16};
var markerClusterMap<?php echo $time; ?>;

var routePolyArr<?php echo $time; ?>=[];
var dDisplay<?php echo $time; ?>=[];
var ibArr<?php echo $time; ?> =[];

function TrackControl<?php echo $time; ?>(controlDiv, map) {

  controlDiv.style.padding = '5px';
	 /*
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to set the map to Home"); ?>';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingTop = '3px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = "Assets's Dashboard";
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+assetDeviceArray[selected_assets_ids], assetNameArray[selected_assets_ids]+" Details");
  });  */
}

//function dashboard(device_id,device_name)
function dashboard(device_id)
{
	//alert(device_id);
	var nameToCheck = assetNameArray[device_id]+" Details";
	var tabNameExists = false;
		
		$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		
			if (this.text == nameToCheck) {
				tabNameExists = true;
				$('#tabs').tabs('select', $(this).attr("href"));
				return false;
			}
		});
		if (!tabNameExists){
			if(assetDeviceArray[device_id] != undefined)
			{
				$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+device_id, assetNameArray[device_id]+" Details");
			}
			
		}
	/*$.post("<?php echo base_url(); ?>index.php/home/get_assets_nm/id/"+device_id,function(data){
		if(data!="")
		{
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+device_id, data+" Details");
		}
		else
		{
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+device_id, "User Assets Details");
		}
	});*/
	
}
function areaControl<?php echo $time; ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to Show/Hide Area"); ?>';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkArea<?php echo $time; ?>";
  chkOne.checked = false;
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkArea<?php echo $time; ?>";
  
  lblOne.appendChild(document.createTextNode('Area'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkArea<?php echo $time; ?>").attr("checked") == "checked") {
		map_showPolyOverlays<?php echo $time; ?>();
		
	}else{
		map_clearPolyOverlays<?php echo $time; ?>();
	}
  });
}
function zoneControl<?php echo $time; ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to Show/Hide Zone"); ?>';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkZone<?php echo $time; ?>";
  chkOne.checked = false;
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkZone<?php echo $time; ?>";
  
  lblOne.appendChild(document.createTextNode('Zone'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkZone<?php echo $time; ?>").attr("checked") == "checked") {
		map_showPolyOverlayszone<?php echo $time; ?>();
		
	}else{
		map_clearPolyOverlayszone<?php echo $time; ?>();
	}
  });
}
function landmarkControl<?php echo $time; ?>(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("Click to Show/Hide Landmark"); ?>';
  controlDiv.appendChild(controlUI);

  var chkOne = document.createElement( "input" );
  chkOne.type = "checkbox";
  chkOne.id = "chkLandmark<?php echo $time; ?>";
  <?php if($this->session->userdata("usertype_id") > 2){ ?>
	chkOne.checked = true;
	map_showLandmarkOverlays<?php echo $time; ?>();
  <?php }else{ ?>
	chkOne.checked = false;
  <?php } ?>
  var lblOne = document.createElement('label')
  lblOne.htmlFor = "chkLandmark<?php echo $time; ?>";
  lblOne.appendChild(document.createTextNode('Landmark'));
  lblOne.style.paddingRight = '4px';
  controlUI.appendChild(chkOne);
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if($("#chkLandmark<?php echo $time; ?>").attr("checked") == "checked") {
		map_showLandmarkOverlays<?php echo $time; ?>();
		
	}else{
		map_clearLandmarkOverlays<?php echo $time; ?>();
	}
  });
}

function map_clearPolyOverlays<?php echo $time; ?>() {
 
  if (map_poly_array<?php echo $time; ?>) {
    for (i in map_poly_array<?php echo $time; ?>) {
      map_poly_array<?php echo $time; ?>[i].setMap(null);
      arLabel<?php echo $time; ?>[<?php echo $i; ?>].setMap(null);
    }
  }
}
function map_showPolyOverlays<?php echo $time; ?>() {
 
  if (map_poly_array<?php echo $time; ?>) {
    for (i in map_poly_array<?php echo $time; ?>) {
	  map_poly_array<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
	  arLabel<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
	  $("#elable_"+i).parent().parent().css('z-index','99999');
    }
  }
}
function map_clearPolyOverlayszone<?php echo $time; ?>() {
 
  if (map_poly_arrayzone<?php echo $time; ?>) {
    for (i in map_poly_arrayzone<?php echo $time; ?>) {
      map_poly_arrayzone<?php echo $time; ?>[i].setMap(null);
    }
  }
}
function map_showPolyOverlayszone<?php echo $time; ?>() {
 
  if (map_poly_arrayzone<?php echo $time; ?>) {
    for (i in map_poly_arrayzone<?php echo $time; ?>) {
	  map_poly_arrayzone<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
    }
  }
}
function map_clearLandmarkOverlays<?php echo $time; ?>() {
 
  if (map_landmark_array<?php echo $time; ?>) {
    for (i in map_landmark_array<?php echo $time; ?>) {
      map_landmark_array<?php echo $time; ?>[i].setMap(null);
    }
  }
  if (circleArray<?php echo $time; ?>) {
    for (i in circleArray<?php echo $time; ?>) {
      circleArray<?php echo $time; ?>[i].setMap(null);
    }
  }
}
function map_showLandmarkOverlays<?php echo $time; ?>() {
 
  if (map_landmark_array<?php echo $time; ?>) {
    for (i in map_landmark_array<?php echo $time; ?>) {
	  map_landmark_array<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
    }
  }
  if (circleArray<?php echo $time; ?>) {
    for (i in circleArray<?php echo $time; ?>) {
	  circleArray<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
    }
  }
}
function map_trip_clearLandmarkOverlays<?php echo $time; ?>() {
 
  if (map_trip_landmark_array<?php echo $time; ?>) {
    for (i in map_trip_landmark_array<?php echo $time; ?>) {
      map_trip_landmark_array<?php echo $time; ?>[i].setMap(null);
    }
  }
  if (trip_circleArray<?php echo $time; ?>) {
    for (i in trip_circleArray<?php echo $time; ?>) {
      trip_circleArray<?php echo $time; ?>[i].setMap(null);
    }
  }
}
function map_trip_showLandmarkOverlays<?php echo $time; ?>() {
 
  if (map_trip_landmark_array<?php echo $time; ?>) {
    for (i in map_trip_landmark_array<?php echo $time; ?>) {
	  map_trip_landmark_array<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
    }
  }
  if (trip_circleArray<?php echo $time; ?>) {
    for (i in trip_circleArray<?php echo $time; ?>) {
	  trip_circleArray<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
    }
  }
}
function map_clearTripOverlays<?php echo $time; ?>() {
   if (routePolyArr<?php echo $time; ?>) {
		for (i in routePolyArr<?php echo $time; ?>) {
			  routePolyArr<?php echo $time; ?>[i].setMap(null);
		}
	}
}
function map_showTripOverlays<?php echo $time; ?>() { 
	if (routePolyArr<?php echo $time; ?>) {
		for (i in routePolyArr<?php echo $time; ?>) {
			  routePolyArr<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
			}
	}  
}
function DrawCircle<?php echo $time; ?>(center, rad, dUnit, map) {
	if(dUnit == "KM")
		rad *= 1000; // convert to meters if in km
	if(dUnit == "Mile")
		rad *= (1000 * 1.609344); // convert to meters if in km
	if(dUnit == "Meter")
		rad = parseInt(rad);
    /*if (draw_circle != null) {
        draw_circle.setMap(null);
    }*/
    draw_circle = new google.maps.Circle({
        center: center,
        radius: rad,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map
    });
	circleArray<?php echo $time; ?>.push(draw_circle);
}
function tripDrawCircle<?php echo $time; ?>(center, rad, dUnit, map) {
	if(dUnit == "KM")
		rad *= 1000; // convert to meters if in km
	if(dUnit == "Mile")
		rad *= (1000 * 1.609344); // convert to meters if in km
	if(dUnit == "Meter")
		rad = parseInt(rad);
    /*if (draw_circle != null) {
        draw_circle.setMap(null);
    }*/
    draw_circle = new google.maps.Circle({
        center: center,
        radius: rad,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map
    });
	trip_circleArray<?php echo $time; ?>.push(draw_circle);
}

function onLoadmap<?php echo $time; ?>() {
	
	directionsService = new google.maps.DirectionsService();
	var mapObjmap = document.getElementById("map<?php echo $time; ?>");
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
	
	map<?php echo $time; ?> = new google.maps.Map(mapObjmap,mapOptionsmap);
	map<?php echo $time; ?>.enableKeyDragZoom();
	
	// Create the DIV to hold the control and call the TrackControl() constructor
  	// passing in this DIV.
	var trackControlDiv = document.createElement('DIV');
	var trackControl = new TrackControl<?php echo $time; ?>(trackControlDiv, map<?php echo $time; ?>);	
	trackControlDiv.index = 1;
	map<?php echo $time; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	
	
	//new window btn		
	<?php if($this->session->userdata('show_map_area_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new areaControl<?php echo $time; ?>(trackNewWindowDiv, map<?php echo $time; ?>);
	trackNewWindowDiv.index = 1;	
	map<?php echo $time; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);

	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new zoneControl<?php echo $time; ?>(trackNewWindowDiv, map<?php echo $time; ?>);
	trackNewWindowDiv.index = 1;	
	map<?php echo $time; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>
	//new window btn
	<?php if($this->session->userdata('show_map_landmark_button')==1){ ?>
	var trackNewWindowDiv = document.createElement('DIV');
	var trackNewWindowControl = new landmarkControl<?php echo $time; ?>(trackNewWindowDiv, map<?php echo $time; ?>);
	trackNewWindowDiv.index = 1;	
	map<?php echo $time; ?>.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
	<?php } ?>
	
	mbounds<?php echo $time; ?> = new google.maps.LatLngBounds();
	
	<?php 
	if(count($coords) > 0) {
			
			foreach ($coords as $coord) {
				if($coord->assets_category_id == 1 || $coord->assets_category_id == "" || $coord->assets_category_id == 0  || $coord->assets_category_id == 13){
					$image_type = "truck.png";
				}else if($coord->assets_category_id == 2){
					$image_type = "car.png";
				}
				else if($coord->assets_category_id == 3){
					$image_type = "bus.png";
				}
				else if($coord->assets_category_id == 4){
					$image_type = "mobile.png";
				}
				else if($coord->assets_category_id == 5){
					$image_type = "bike.png";
				}
				else if($coord->assets_category_id == 6){
					$image_type = "altenator.png";
				}
				else if($coord->assets_category_id == 7 || $coord->assets_category_id == 8){
					$image_type = "man.png";
				}
				else if($coord->assets_category_id == 9){
					$image_type = "stacker.png";
				}
				else if($coord->assets_category_id == 10){
					$image_type = "loader.png";
				}
				else if($coord->assets_category_id == 11){
					$image_type = "locomotive.png";
				}
				else if($coord->assets_category_id == 12){
					$image_type = "generator.png";
				}
				else if($coord->assets_category_id == 113){
					$image_type = "maintenance.png";
				}
				else if($coord->assets_category_id == 14){
					$image_type = "motor.png";
				}
				else if($coord->assets_category_id == 15){
					$image_type = "bobcat.png";
				}
				else if($coord->assets_category_id == 16){
					$image_type = "tractor.png";
				}
				else if($coord->assets_category_id == 17){
					$image_type = "car1.png";
				}
				else if($coord->assets_category_id == 18){
					$image_type = "satellite.png";
				}
				else if($coord->assets_category_id == 21){
					$image_type = "stacker.png";
				}
				else{
					$image_type = "truck.png";
				}
				$direction = $coord->angle_dir;
				$minutes_before = $coord->beforeTime;
				$text = "<b>".$coord->assets_name;
				if($coord->assets_friendly_nm!="" || $coord->assets_friendly_nm!=null)
					$text.=" (".$coord->assets_friendly_nm.") ";
					
				if($this->session->userdata('usertype_id')!=3){
					$text.=" (".$coord->device_id.")";
				}
				$text .= "</b><br>";
				$text .= $coord->received_time.", ".date($date_format." ".$time_format,strtotime($coord->add_date))."<br>";
				if($this->session->userdata('show_map_driver_detail_window')==1){
					
					if($coord->assets_image_path!= NULL || $coord->assets_image_path!="")
					{
						$text .= "<img src='".base_url()."assets/assets_photo/".$coord->assets_image_path."' /><br>";
					}
					
					if($coord->driver_image!= NULL || $coord->driver_image!="")
					{
						$text .= "<img src='".base_url()."assets/driver_photo/".$coord->driver_image."' /><br>";
					}
				}			
				if($coord->ignition == 0)
					$ignition = "OFF";
				else 
					$ignition = "ON";
				$text .="Ignition: ".$ignition." , Speed: ".$coord->speed." KM<br>";
				
				$tag = '';
				if($coord->driver_name != ""){
					//$tag = $row->driver_name.", ";
				}
				$tag .= $coord->assets_name;
				//$tag .= $coord->speed." KM";
				
				if($coord->address != "")
					$text .= " ".$coord->address."<br>";
				
				if($this->session->userdata('show_dash_legends')==1){
					$text .="Status: ";
					if($minutes_before < $this->session->userdata('network_timeout') && $coord->speed > 10 && $minutes_before != ""){
							$status ="Running";
							$status_img = "green_dot.png";
							$color = "green";
					}else if($minutes_before < $this->session->userdata('network_timeout')  && $coord->speed <= 10 && $coord->ignition == 0 && $minutes_before != ""){
							$status ="Parked";
							$status_img = "blue_dot.png";
							$color = "#06F";
					}else if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed <= 10 && $coord->ignition == 1 && $seconds_before != ""){
						$status ="Idle";
						$status_img = "green_dot.png";
						$color = "green";
					}else if($minutes_before >= $this->session->userdata('network_timeout') && $minutes_before <= ($this->session->userdata('network_timeout')+36000) && $minutes_before != ""){
							$status ="Out of network";
							$status_img = "RedDot.png";
							$color = "orange";
					}else if($minutes_before > ($this->session->userdata('network_timeout')+36000) or $minutes_before ==""){
							$status ="Out of network";
							$status_img = "RedDot.png";
							$color = "orange";
					}
				}
				$text .= $status."<br>";
								
				if($status == "Parked")
					$text .="Parked From : ".$coord->stop_from."<br>";
/*				
				if($coord->routename != ""){
					$text .="Route : ".$coord->routename."<br>";
					if($coord->landmark_n != ""){						
						$text .="Next Landmark : ".$coord->landmark_n."<br>";
					}
				}
*/				
					
				if($this->session->userdata('show_map_driver_detail_window')==1){
				
					if($coord->driver_name!="" || $coord->driver_name!=null) 
					$text .="Driver Name: ".$coord->driver_name."<br>"; 
				
					if($coord->driver_mobile!="" || $coord->driver_mobile!=null) 
					$text .="Driver Mob.:".$coord->driver_mobile."<br>"; 
				}
				if($this->session->userdata('show_dash_dashboard_button')==1){
					//$text .="<a onClick='dashboard(".$coord->assets_id.");' style='color: blue; text-decoration: underline; cursor: pointer;'>View Dashboard</a>";
				}
				if($coord->icon_path=="" || $coord->icon_path==null)
				{
					if(($minutes_before/60) <= 10){
						if($coord->speed > 0)					
							$coord->icon_path = 'marker-GREEN-START.png';
						else
							$coord->icon_path = 'marker-GREEN-END.png';
					}
					else
						$coord->icon_path = 'kml-ORANGE-END.png';
				}
				$assets_name = explode("-", $coord->assets_name);
				$assets_name = end($assets_name);
				$prefix = $coord->assets_id;
	?>	
		
		var point = new google.maps.LatLng(<?php echo floatval($coord->lati); ?>, <?php echo floatval($coord->longi); ?>);
		if(<?php echo $location_with_tag; ?> == 1){
			/*var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; font-style: italic; color: white; border: 1px solid black; background: none repeat scroll 0% 0% #8467D7; padding: 2px; margin-top: 8px;text-align:center;-moz-border-radius: 8px; border-radius: 8px;'><img src='<?php echo base_url(); ?>assets/images/direction.jpg' title='Direction' style='transform: rotate(<?php echo $direction; ?>deg);-ms-transform:rotate(<?php echo $direction; ?>deg);-webkit-transform:rotate(<?php echo $direction; ?>deg);'>&nbsp;<img src='<?php echo base_url(); ?>assets/images/<?php echo $status_img; ?>' title='<?php echo $status; ?>'>&nbsp;<?php echo $tag; ?></div>";

			var boxText = "<center><div style='width:90px; line-height: 10px; font-size: 12px; font-weight: bold; color: <?php echo $color; ?>; border: 1px solid black; background: none repeat scroll 0% 0% white; padding: 2px; margin-top: 0px;text-align:center;-moz-border-radius: 2px; border-radius: 2px;'>&nbsp;<?php echo $tag; ?></div></center>";
			*/

			var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; color: #2E6E9E; border: 1px solid black; background: none repeat scroll 0% 0% #DFEFFC; padding: 2px; margin-top: 4px; text-align:center; -moz-border-radius: 8px; border-radius: 8px; white-space: nowrap;'><img src='<?php echo base_url(); ?>assets/images/<?php echo $status_img; ?>' title='<?php echo $status; ?>'><img src='<?php echo base_url(); ?>assets/images/direction.jpg'>&nbsp;<?php echo addslashes($coord->assets_name .", ". date($date_format." ".$time_format,strtotime($coord->add_date)))?></div>";
			
			var myOptions1 = {
				 content: boxText
				,disableAutoPan: true
				,maxWidth: 500
				,position: point
				,pixelOffset: new google.maps.Size(-90,0)
				,zIndex: null
				,boxStyle: { 
				  //background: "url('tipbox.gif') no-repeat"
				  opacity: 0.75
				  ,width: "auto"
				 }
				,closeBoxMargin: "10px 2px 2px 2px"
				,closeBoxURL: ""
				//http://www.google.com/intl/en_us/mapfiles/close.gif
				,infoBoxClearance: new google.maps.Size(1, 1)
				,isHidden: false
				,pane: "floatPane"
				,enableEventPropagation: true
			};
			ib1<?php echo $time; ?> = new InfoBox(myOptions1);                
			ib1<?php echo $time; ?>.open(map<?php echo $time; ?>);
			ibArr<?php echo $time; ?>.push(ib1<?php echo $time; ?>);
		}
		pointArr<?php echo $time; ?>.push(point);
		<?php if($dist=="dist"){
			echo "$('#refreshbar_id".$tme."').hide();";
		} ?>
		var content = "<img src='<?php echo base_url(); ?>assets/<?php echo $image_type; ?>' title='<?php echo addslashes($coord->assets_name)?>'>";
		
		<?php
		if($coord->lat_n != "" && $coord->lng_n != ""){	?>					
			
			var p1 = new google.maps.LatLng(<?php echo floatval($coord->lati); ?>,<?php echo floatval($coord->longi); ?>);
			var p2 = new google.maps.LatLng(<?php echo $coord->lat_n; ?>,<?php echo $coord->lng_n; ?>);
			
			createMarkerWithDistnace<?php echo $time; ?>(p1, p2, "<?php echo $text; ?>", content);
			/*
			var directS<?php echo $prefix; ?>=new google.maps.DirectionsService();
			var landmark_as_waypnt<?php echo $prefix; ?> = [];
			var req_direct={
				origin: p1,
				destination: p2,
				waypoints:landmark_as_waypnt<?php echo $prefix; ?>,
				optimizeWaypoints: true,
				avoidHighways: true,
				avoidTolls: true,
				travelMode: google.maps.TravelMode.DRIVING,
				unitSystem: google.maps.UnitSystem.METRIC,
			}
			directS<?php echo $prefix; ?>.route(req_direct, function(response, status){
				if (status != google.maps.DirectionsStatus.OK) {
					markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, p1, "<?php echo $text; ?>", content ));
				}else{
					
					var dist = 0;
					var duration1, duration2 = 0;
					for(j=0;j<response.routes[0].legs.length;j++){
						dist+=response.routes[0].legs[0].distance.value;
					}
					dist=parseFloat(dist/1000).toFixed(2);
					var avg=40;
					var duration1=parseInt(dist/avg);
					var duration2=parseFloat(parseFloat(dist/avg)-duration1).toFixed(2);
					duration=duration1+":"+parseInt((60*duration2))+"(Speed : 40Km/H)";
					markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, p1, "<?php echo $text; ?>Distance : "+dist+" KM, Time : "+duration, content ));
				}
			});
			*/
		<?php }else{ ?>
			//markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, point, '<?php echo $coord->assets_name; ?>', "<?php echo $text; ?>", content, "<?php echo $coord->assets_id; ?>"));
			
			markersmap<?php echo $time; ?>.push(createMarker(map<?php echo $time; ?>, point, '<?php echo $coord->assets_name; ?>', "<div style='text-align:left;'><?php echo $text; ?></div>", '<?php echo $image_type; ?>', '', 'sidebar_map', '', '', "map<?php echo $coord->assets_id; ?>"));
		<?php } ?>
		
		mbounds<?php echo $time; ?>.extend(point);
		
	<?php			
			} // End For Loop
			?>
		<?php } else {
	?>
		var point = new google.maps.LatLng(
			<?php echo $lati;?>,
			<?php echo $longi;?>
		);
		
		markersmap<?php echo $time; ?>.push(createMarker(map<?php echo $time; ?>, point,"DevIndia Infoway","DevIndia Infoway", '', '', "sidebar_map", '' ));
		
		map<?php echo $time; ?>.setCenter(point);
	<?php } ?>
	<?php if($find_distance == 1){ ?>
		calcRoute(pointArr<?php echo $time; ?>[0], pointArr<?php echo $time; ?>[1], map<?php echo $time; ?>);
	<?php } ?>
	
	map<?php echo $time; ?>.setCenter(point);
	map<?php echo $time; ?>.fitBounds(mbounds<?php echo $time; ?>);
  }

  <?php
	$i = 0;
  
	foreach($plyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php
		$pathArr = array();
		
		for($j=0; $j<count($plyLat[$pIdv]); $j++){
			$pathArr[] = 'new google.maps.LatLng('.sprintf("%.6f", $plyLat[$pIdv][$j]).', '.sprintf("%.6f", $plyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArr);
		
		if(count($plyDev[$pIdv]) > 0){
			$devices = implode("<br>", $plyDev[$pIdv]);
		}
		?>
		var polygonCoords = [<?php echo $pathString; ?>];

		for (i = 0; i < polygonCoords.length; i++) {
		  bounds.extend(polygonCoords[i]);
		}
		//var devices = 'plyDev'
		arLabel<?php echo $time; ?>[<?php echo $i; ?>] = new ELabel({
		latlng: bounds.getCenter(), 
		label: "<div class='elable' id='elable_<?php echo $i; ?>' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'><?php echo $plyName[$pIdv][0]; ?></div>", 
		classname: "label", 
		offset: 0, 
		opacity: 100, 
		overlap: true,
		clicktarget: false
		});
		
		var map_polyV<?php echo $time; ?><?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $plyColor[$pIdv]; ?>'
		    });
		//map_polyV<?php echo $time; ?><?php echo $i; ?>.setMap(map<?php echo $time; ?>);
		map_poly_array<?php echo $time; ?>.push(map_polyV<?php echo $time; ?><?php echo $i; ?>)			
		google.maps.event.addListener(map_polyV<?php echo $time; ?><?php echo $i; ?>,"mouseover",function(event){
			arLabel<?php echo $time; ?>[<?php echo $i; ?>].setMap(map<?php echo $time; ?>);
			$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(map_polyV<?php echo $time; ?><?php echo $i; ?>,"mouseout",function(event){
			arLabel<?php echo $time; ?>[<?php echo $i; ?>].setMap(null);
		});
		google.maps.event.addListenerOnce(map<?php echo $time; ?>, 'idle', function() {
			google.maps.event.trigger(map<?php echo $time; ?>, 'resize');
			//map<?php echo $time; ?>.setCenter(point); // be sure to reset the map center as well
		});
				
	<?php $i++; } ?>
	<?php
	$i = 0;
	foreach($znId as $znIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php
		$pathArr = array();
		
		for($j=0; $j<count($znLat[$znIdv]); $j++){
			$pathArr[] = 'new google.maps.LatLng('.sprintf("%.6f", $znLat[$znIdv][$j]).', '.sprintf("%.6f", $znLng[$znIdv][$j]).')';
		}
		$pathString = implode(",", $pathArr);
		
		if(count($znDev[$znIdv]) > 0){
			$devices = implode("<br>", $znDev[$znIdv]);
		}
		?>
		var zngonCoords = [<?php echo $pathString; ?>];

		for (i = 0; i < zngonCoords.length; i++) {
		  bounds.extend(zngonCoords[i]);
		}
		//var devices = 'plyDev'
		label<?php echo $time; ?><?php echo $i; ?> = new ELabel({
		latlng: bounds.getCenter(), 
		label: "<div class='elable' id='elable_<?php echo $i; ?>' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'><?php echo $znName[$znIdv][0]; ?></div>", 
		classname: "label", 
		offset: 0, 
		opacity: 100, 
		overlap: true,
		clicktarget: false
		});
		
		var map_znV<?php echo $time; ?><?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $znColor[$znIdv]; ?>'
		    });
		//map_polyV<?php echo $time; ?><?php echo $i; ?>.setMap(map<?php echo $time; ?>);
		map_poly_arrayzone<?php echo $time; ?>.push(map_znV<?php echo $time; ?><?php echo $i; ?>)			
		//map_poly_arrayzone<?php echo $time; ?>.push(map_polyV<?php echo $time; ?><?php echo $i; ?>)			
		google.maps.event.addListener(map_znV<?php echo $time; ?><?php echo $i; ?>,"mouseover",function(event){
			label<?php echo $time; ?><?php echo $i; ?>.setMap(map<?php echo $time; ?>);
			$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(map_znV<?php echo $time; ?><?php echo $i; ?>,"mouseout",function(event){
			label<?php echo $time; ?><?php echo $i; ?>.setMap(null);
		});
		google.maps.event.addListenerOnce(map<?php echo $time; ?>, 'idle', function() {
			google.maps.event.trigger(map<?php echo $time; ?>, 'resize');
			//map<?php echo $time; ?>.setCenter(point); // be sure to reset the map center as well
		});
				
	<?php $i++; } ?>
	<?php
	$i = 0;
	if(count($landmarks) > 0) {
		foreach ($landmarks as $landmark) {
			$distance_unit = $landmark->distance_unit;
			$text = "Name : ".$landmark->name."<br>";
			$text .= "Address : ".$landmark->address."<br>";
			$text .= "Assets : ".$landmark->assets.'<br>';
		//	$text .= "<img src='".$landmark->assets.'<br>';
	?>				
			var point = new google.maps.LatLng(<?php echo floatval($landmark->lat); ?>, <?php echo floatval($landmark->lng); ?>);		
			
			map_landmark_array<?php echo $time; ?>.push(createLandmarkMarker<?php echo $time; ?>(map<?php echo $time; ?>, point, "<?php echo $landmark->name; ?>","<?php echo $text; ?>", '<?php echo $landmark->icon_path; ?>', '', "sidebar_map", '' ));
			DrawCircle<?php echo $time; ?>(point, '<?php echo $landmark->radius; ?>', '<?php echo $distance_unit; ?>', map<?php echo $time; ?>);
	<?php
		$i++;
		} // End For Loop
	}
	
	?>
	
	loadRoute<?php echo $time; ?>('<?php echo $ids; ?>');
	
	map_clearLandmarkOverlays<?php echo $time; ?>();
	/*if(markersmap<?php echo $time; ?>.length > 2){
		markerClusterMap<?php echo $time; ?> = new MarkerClusterer(map<?php echo $time; ?>, markersmap<?php echo $time; ?>,
		mcOptionsAllpoint);
	}*/
}
function setDirection<?php echo $time; ?>(){
	
	for(j=0; j<directionId<?php echo $time; ?>.length; j++){
		alert(directionId<?php echo $time; ?>[j]+":"+directionVal<?php echo $time; ?>[j])
		$(directionId<?php echo $time; ?>[j]).rotate(directionVal<?php echo $time; ?>[j]);
	}
}
function createMarkerWithNumber(map, number, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/marker_image.php?text="+number+"&icon="+icon;}
	
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		
		/*
		// Commented By Kunal.
		
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(html);
		*/
		
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
		//infoBubble.open(map, new_marker);
		google.maps.event.addListener(new_marker, 'click', function() {
			update_timeout = setTimeout(function(){
				if (!infoBubble.isOpen()) {
					//infoBubble.setContent(html);
					infoBubble.open(map, new_marker);
				}
			}, 200);
		/*	
		// Commented By Kunal
		  update_timeout = setTimeout(function(){
				infowindow.open(map,new_marker);
			}, 200); 
		*/			
		});
		google.maps.event.addListener(new_marker, 'dblclick', function() {
			dArr.push(point);
			  if(dArr.length == 2){
					calcRoute(dArr[0], dArr[1], map);
					dArr = [];
					
			  }
			  if(dArr.length == 1 && directionsDisplay != undefined){
				clearDirection();
				}
			 clearTimeout(update_timeout);
		});
		
		if(openInfo == true) {
			//setTimeout(function(){
				
				//infoBubble.setContent(html);
				setTimeout(function(){
				infoBubble.open(map, new_marker);
				},1000);
			//}, 500);
		}
		
		if(openers != ''&&!isEmpty(openers)){
		   for(var i in openers){
			 var opener = document.getElementById(openers[i]);
			 opener.onclick = function(){infoBubble.open(map,new_marker); return false};
		   }
		}
		
		
		if(sidebar_id != ''){
			var sidebar = document.getElementById(sidebar_id);
			if(sidebar!=null && sidebar!=undefined && title!=null && title!=''){
				var newlink = document.createElement('a');
				
				newlink.onclick=function(){infoBubble.open(map,new_marker); return false};
				
				newlink.innerHTML = title;
				sidebar.appendChild(newlink);
			}
		}
	}
	return new_marker;  
}
var slArr<?php echo $time; ?> = [];
var elArr<?php echo $time; ?> = [];
var wayPointsArr<?php echo $time; ?> = [];
var clrArr<?php echo $time; ?> = [];
function loadRoute<?php echo $time; ?>(assets_ids){
	if(assets_ids!=0){
	$.post(
	   "<?php echo site_url('home/loadRouteMap'); ?>",{'assets_ids':assets_ids},
	   function(data){
			for(s=0; s<data.coords.length; s++){						
				for(i=0; i<data.coords[s].length; i++){
				
					var rId = data.coords[s][i].id;
					var rName = data.coords[s][i].routename;
					var rColor = data.coords[s][i].route_color;
					var rStart = data.coords[s][i].start_point.split(",");
					var rEnd = data.coords[s][i].end_point.split(",");
					var rWaypoints = data.coords[s][i].waypoints;
					var rPoints = data.coords[s][i].points;
					
					var start = new google.maps.LatLng(parseFloat(rStart[0]), parseFloat(rStart[1]));
					if(data.coords[s][i].round_trip == 1){
						var end = start;
					}else{
						var end = new google.maps.LatLng(parseFloat(rEnd[0]), parseFloat(rEnd[1]));
					}
					var waypts = [];
					if(rWaypoints != "" && rWaypoints != null){
						rWaypoints = rWaypoints.split(":");
					}else{
						rWaypoints = [];
					}
					
					wpts = [];
					for (var k=0; k<rWaypoints.length; k++) {
					  wpts = rWaypoints[k].split(",");
					  waypts.push({location:new google.maps.LatLng(parseFloat(wpts[0]), parseFloat(wpts[1]))});
					}
					
					slArr<?php echo $time; ?>[i] = start;
					elArr<?php echo $time; ?>[i] = end;
					wayPointsArr<?php echo $time; ?>[i] = waypts;
					clrArr<?php echo $time; ?>[i] = rColor;
				}
			}
			
			for(i=0; i<data.landmarks.length; i++){
				var text = data.landmarks[i].name+"<br>";
				text += data.landmarks[i].address+"<br>";
			
				var point = new google.maps.LatLng(data.landmarks[i].lat, data.landmarks[i].lng);		
				map_trip_landmark_array<?php echo $time; ?>.push(createLandmarkMarker<?php echo $time; ?>(map<?php echo $time; ?>, point, data.landmarks[i].name, text, data.landmarks[i].icon_path, '', "sidebar_map", '' ));
				<?php if($this->session->userdata('usertype_id') < 3){ ?>
				tripDrawCircle<?php echo $time; ?>(point, data.landmarks[i].radius, data.landmarks[i].distance_unit, map<?php echo $time; ?>);				
				<?php } ?>
			}
			<?php if($this->session->userdata('usertype_id') < 3){ ?>
				map_trip_clearLandmarkOverlays<?php echo $time; ?>();
			<?php } ?>
			drawRoute<?php echo $time; ?>();
		},'json');
	}
}
var plotDirection<?php echo $time; ?> = 0;
 function drawRoute<?php echo $time; ?>(){		
		
		if(plotDirection<?php echo $time; ?> < slArr<?php echo $time; ?>.length){
			i = plotDirection<?php echo $time; ?>;
			s1 = slArr<?php echo $time; ?>[i];
			e1 = elArr<?php echo $time; ?>[i];
			wp1 = wayPointsArr<?php echo $time; ?>[i];
			color = clrArr<?php echo $time; ?>[i];
			
			var polylineOptionsActual = new google.maps.Polyline({
				strokeColor: color,
				strokeOpacity: 1.0,
				strokeWeight: 4
				});

			dDisplay<?php echo $time; ?>[i] = new google.maps.DirectionsRenderer({polylineOptions: polylineOptionsActual});
			dDisplay<?php echo $time; ?>[i].setMap(map<?php echo $time; ?>);
			var request1 = {
				origin:s1, 
				destination:e1,
				waypoints: wp1,
				optimizeWaypoints: true,
				avoidHighways: true,
				avoidTolls: true,
				provideRouteAlternatives: false,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			directionsService.route(request1, function(response, status) 
			{
				if (status == google.maps.DirectionsStatus.OK) 
				{
					var stepss = response.routes[0].legs;
					var path = response.routes[0].overview_path;
					for(var step = 0; step < stepss.length; step++)
					{
						stp = stepss[step].steps;
						for(var ss = 0; ss < stp.length; ss++)
						{	
							polylineOptions = {
									map: map<?php echo $time; ?>,
									strokeColor: color,
									strokeOpacity: 0.7,
									strokeWeight: 4,
									path: stp[ss].path
									//path: path,
							}
							routePolyArr<?php echo $time; ?>.push(new google.maps.Polyline(polylineOptions));
						}
					}
					setTimeout("drawRoute<?php echo $time; ?>()", 200);
					plotDirection<?php echo $time; ?>++;
				}
				else {
					alert("An error occurred - " + status);
				}				  
			});	
			
		}else{
			plotDirection<?php echo $time; ?> = 0;
			<?php if($this->session->userdata('usertype_id') < 3){ ?>
			map_clearTripOverlays<?php echo $time; ?>();
			<?php } ?>
		}
  }
function createLandmarkMarker<?php echo $time; ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		optimized: false,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>/" + icon;}
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
			infoBubble.open(map, new_marker);
		});
		
		
	}
	return new_marker;  
}
//t<?php echo $time; ?>=window.setTimeout('startLoading<?php echo $time; ?>()',60000); 
//setTimeout('startLoading<?php echo $time; ?>()',60000)

function startLoading<?php echo $time; ?>(){
	
	$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/<?php echo $ids; ?>",
	 function(data) {
		if(data){
			clearOverlays<?php echo $time; ?>();
			
			var lat_n = data.lat_n;
			var lng_n = data.lng_n;
			
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			var tag = data.tag;
			var direction = data.direction;
			var status = data.status;
			var location_with_tag = data.location_with_tag;
			var speed = data.speed;
			var title = data.title;
			var icon_path=data.icon_path;
			var beforeTime = data.beforeTime;
			var assets_ids = data.assets_ids;
			if(lat.length > 0){
				for(i=0; i<lat.length; i++){
						
					if(status[i] == 'Running')	{
						var status_img = 'green_dot.png';
						var color = "green";
					}else if(status[i] == 'Idle'){
						var status_img = 'green_dot.png';
						var color = "green";
					}else if(status[i] == 'Parked'){
						var status_img = 'blue_dot.png';
						var color = "#06F";
					}else if(status[i] == 'Out of network'){
						var status_img = 'RedDot.png';
						var color = "orange";
					}else if(status[i] == 'Out of network'){
						var status_img = 'RedDot.png';
						var color = "orange";
					}
					var point = new google.maps.LatLng(lat[i], lng[i]);
					if(location_with_tag == 1){
						
						/*var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; font-style: italic; color: white; border: 1px solid black; background: none repeat scroll 0% 0% #8467D7; padding: 2px; margin-top: 8px;text-align:center;-moz-border-radius: 8px; border-radius: 8px;'><img src='<?php echo base_url(); ?>assets/images/direction.jpg'>&nbsp;<img src='<?php echo base_url(); ?>assets/images/"+status_img+"' title='"+status[i]+"'>&nbsp;"+tag[i]+"</div>";
						*/
						var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; color: #2E6E9E; border: 1px solid black; background: none repeat scroll 0% 0% #DFEFFC; padding: 2px; margin-top: 4px; text-align:center; -moz-border-radius: 8px; border-radius: 8px; white-space: nowrap;'><img src='<?php echo base_url(); ?>assets/images/"+status_img+"' title='<?php echo $status; ?>'><img src='<?php echo base_url(); ?>assets/images/direction.jpg'>&nbsp;"+tag[i]+"</div>";

						var myOptions1 = {
							 content: boxText
							,disableAutoPan: true
							,maxWidth: 500
							,position: point
							,pixelOffset: new google.maps.Size(-90,0)
							,zIndex: null
							,boxStyle: { 
							  //background: "url('tipbox.gif') no-repeat"
							  opacity: 0.75
							  ,width: "auto"
							 }
							,closeBoxMargin: "10px 2px 2px 2px"
							,closeBoxURL: ""
							//http://www.google.com/intl/en_us/mapfiles/close.gif
							,infoBoxClearance: new google.maps.Size(1, 1)
							,isHidden: false
							,pane: "floatPane"
							,enableEventPropagation: true
						};
						ib1<?php echo $time; ?> = new InfoBox(myOptions1);                
						ib1<?php echo $time; ?>.open(map<?php echo $time; ?>);
						ibArr<?php echo $time; ?>.push(ib1<?php echo $time; ?>);
					}	
					content = "<img src='<?php echo base_url(); ?>assets/"+icon_path[i]+"' title='Click Me'>";
					
					if(lat_n[i] != "" && lat_n[i] != null && lng_n[i] != "" && lng_n[i] != null){
						var p1 = new google.maps.LatLng(lat[i],lng[i]);
						var p2 = new google.maps.LatLng(lat_n[i],lng_n[i]);
						createMarkerWithDistnace<?php echo $time; ?>(p1, p2, html[i], content);
					}else{
						//markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, point, html[i], content ));
						markersmap<?php echo $time; ?>.push(createMarker(map<?php echo $time; ?>, point, title[i], "<div style='text-align:left;'>"+html[i]+"</div>", icon_path[i], '', 'sidebar_map', '', '', 'map'+assets_ids[i]));
					}
					
				}				
				/*if(markersmap<?php echo $time; ?>.length > 2){
					//markerClusterMap<?php echo $time; ?>.clearMarkers();
					//markerClusterMap<?php echo $time; ?> = new MarkerClusterer(map<?php echo $time; ?>, //markersmap<?php echo $time; ?>, mcOptionsAllpoint);
				}else{
					map<?php echo $time; ?>.setCenter(point);
				}*/
			}
			if(timer_on<?php echo $time; ?>==1)
			{
				$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
				counter<?php echo $time; ?>();
			}
		}
	 }, 'json'
	);
	//t<?php echo $time; ?>=window.setTimeout('startLoading<?php echo $time; ?>()',60000); 
}

function DirectRefresh<?php echo $time; ?>(){
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>index.php/home/device_map_refresh/id/<?php echo $ids; ?>",
	 function(data) {
		if(data){
			$("#loading_top").css("display","none");
			clearOverlays<?php echo $time; ?>();
			
			var lat_n = data.lat_n;
			var lng_n = data.lng_n;
			
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			var tag = data.tag;
			var direction = data.direction;
			var status = data.status;
			var location_with_tag = data.location_with_tag;
			var speed = data.speed;
			var title = data.title;
			var icon_path=data.icon_path;
			var beforeTime = data.beforeTime;
			var assets_ids = data.assets_ids;
			if(lat.length > 0){
				for(i=0; i<lat.length; i++){
					//getDistanceFromMyLandmark<?php echo $time; ?>(lat[i],lng[i],'22.298569','70.794301');
					if(status[i] == 'Running')	{
						var status_img = 'green_dot.png';
						var color = "green";
					}else if(status[i] == 'Idle'){
						var status_img = 'green_dot.png';
						var color = "green";
					}else if(status[i] == 'Parked'){
						var status_img = 'green_dot.png';
						var color = "#06F";
					}else if(status[i] == 'Out of network'){
						var status_img = 'RedDot.png';
						var color = "orange";
					}else if(status[i] == 'Out of network'){
						var status_img = 'RedDot.png';
						var color = "orange";
					}
					var point = new google.maps.LatLng(lat[i], lng[i]);
					if(location_with_tag == 1){
												
						/*var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; font-style: italic; color: white; border: 1px solid black; background: none repeat scroll 0% 0% #8467D7; padding: 2px; margin-top: 8px;text-align:center;-moz-border-radius: 8px; border-radius: 8px;'><img src='<?php echo base_url(); ?>assets/images/direction.jpg'>&nbsp;<img src='<?php echo base_url(); ?>assets/images/"+status_img+"' title='"+status[i]+"'>&nbsp;"+tag[i]+"</div>";
						*/
						var boxText = "<div style='line-height: 15px; font-size: 11px; font-weight: bold; color: #2E6E9E; border: 1px solid black; background: none repeat scroll 0% 0% #DFEFFC; padding: 2px; margin-top: 4px; text-align:center; -moz-border-radius: 8px; border-radius: 8px; white-space: nowrap;'><img src='<?php echo base_url(); ?>assets/images/"+status_img+"' title='<?php echo $status; ?>'><img src='<?php echo base_url(); ?>assets/images/direction.jpg'>&nbsp;"+tag[i]+"</div>";

						var myOptions1 = {
							 content: boxText
							,disableAutoPan: true
							,maxWidth: 500
							,position: point
							,pixelOffset: new google.maps.Size(-90,0)
							,zIndex: null
							,boxStyle: { 
							  //background: "url('tipbox.gif') no-repeat"
							  opacity: 0.75
							  ,width: "auto"
							 }
							,closeBoxMargin: "10px 2px 2px 2px"
							,closeBoxURL: ""
							//http://www.google.com/intl/en_us/mapfiles/close.gif
							,infoBoxClearance: new google.maps.Size(1, 1)
							,isHidden: false
							,pane: "floatPane"
							,enableEventPropagation: true
						};
						ib1<?php echo $time; ?> = new InfoBox(myOptions1);                
						ib1<?php echo $time; ?>.open(map<?php echo $time; ?>);
						ibArr<?php echo $time; ?>.push(ib1<?php echo $time; ?>);
					}
					content = "<img src='<?php echo base_url(); ?>assets/"+icon_path[i]+"' title='Click Me'>";
										
					if(lat_n[i] != "" && lat_n[i] != null && lng_n[i] != "" && lng_n[i] != null){
						
						var p1 = new google.maps.LatLng(lat[i],lng[i]);
						var p2 = new google.maps.LatLng(lat_n[i],lng_n[i]);
						createMarkerWithDistnace<?php echo $time; ?>(p1, p2, html[i], content);
						
					}else{
						//markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, point, html[i], content ));
						markersmap<?php echo $time; ?>.push(createMarker(map<?php echo $time; ?>, point, title[i], "<div style='text-align:left;'>"+html[i]+"</div>", icon_path[i], '', 'sidebar_map', '', '', 'map'+assets_ids[i]));
					}
				}
				/*if(markersmap<?php echo $time; ?>.length > 2){
					//markerClusterMap<?php echo $time; ?>.clearMarkers();
					//markerClusterMap<?php echo $time; ?> = new MarkerClusterer(map<?php echo $time; ?>, //markersmap<?php echo $time; ?>, mcOptionsAllpoint);
				}else{
					//map<?php echo $time; ?>.setCenter(point);
				}*/
			}
		}
	 }, 'json'
	);
}
function createMarkerWithDistnace<?php echo $time; ?>(p1, p2, txt, content){
	var directS=new google.maps.DirectionsService();
	var landmark_as_waypnt = [];
	var req_direct={
		origin: p1,
		destination: p2,
		waypoints:landmark_as_waypnt,
		optimizeWaypoints: true,
		avoidHighways: true,
		avoidTolls: true,
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.METRIC,
	}
	directS.route(req_direct, function(response, status){
		if (status != google.maps.DirectionsStatus.OK) {
			markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, p1, txt, content ));
		}else{
			
			var dist = 0;
			var duration1, duration2 = 0;
			for(j=0;j<response.routes[0].legs.length;j++){
				dist+=response.routes[0].legs[0].distance.value;
			}
			dist=parseFloat(dist/1000).toFixed(2);
			var avg=40;
			var duration1=parseInt(dist/avg);
			var duration2=parseFloat(parseFloat(dist/avg)-duration1).toFixed(2);
			duration=duration1+":"+parseInt((60*duration2))+"(Speed : 40Km/H)";
			txt = txt + "Distance : "+dist+" KM, Time : "+duration
			markersmap<?php echo $time; ?>.push(createMarkerMapAll(map<?php echo $time; ?>, p1, txt, content ));
		}
		
	});
}
function clearOverlays<?php echo $time; ?>() {
  
  if (markersmap<?php echo $time; ?>) {
    for (i in markersmap<?php echo $time; ?>) {
      markersmap<?php echo $time; ?>[i].setMap(null);
    }
  }
  if (ibArr<?php echo $time; ?>) {
    for (i in ibArr<?php echo $time; ?>) {
      ibArr<?php echo $time; ?>[i].setMap(null);
    }
  }
  ibArr<?php echo $time; ?> = [];
  markersmap<?php echo $time; ?> = [];
}
onLoadmap<?php echo $time; ?>();
</script>

<div id="map<?php echo $time; ?>" style="width: 100%; height: 100%; position:relative;"></div>
<div style='text-align: center;clear:both' id="refreshbar_id<?php echo $tme;?>">
	<div style="float:left;width:100%;height:2em;padding-top:0.2em"><input type='checkbox' onclick='stop_resume_toggle<?php echo $time;?>()' id='timer_toggle<?php echo $time; ?>'> <?php echo $this->lang->line('data_refresh_after'); ?> <input type='text' size='2' onblur='counter_change<?php echo $time; ?>()' value='15' id='time_in_seconds<?php echo $time; ?>'> <?php echo $this->lang->line('seconds'); ?> <span style='display: inline-block;'>(<?php echo $this->lang->line('refresh_after'); ?> <span id='seconds<?php echo $time; ?>'>15</span> &nbsp;<?php echo $this->lang->line('second'); ?>)</span> &nbsp;<span onClick="directRefreshMap()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php echo $this->lang->line('refresh'); ?></span></div>
	<div id="pbar<?php echo $time; ?>"></div> 
	
	<!-- <a href='JavaScript:void(0);' onclick='stop_resume_toggle()' style='font-weight:bold' id='Timer_Event'>Stop Refresh</a>-->
</div>
<div style="height:10px"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$( "#pbar<?php echo $time; ?>" ).progressbar({value: 0});
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		
	});

	var timer_on<?php echo $time; ?>=0;
	
	if(auto_refresh_setting == 1){
		$("#timer_toggle<?php echo $time; ?>").attr("checked", true);
		timer_on<?php echo $time; ?>=0;
	}else{
		$("#timer_toggle<?php echo $time; ?>").attr("checked", false);
		timer_on<?php echo $time; ?>=1;
	}
	
	var timer<?php echo $time; ?>;
	var time_in_s<?php echo $time; ?>;
	var current<?php echo $time; ?> ;
	var percentage<?php echo $time; ?>;
	function stop_resume_toggle<?php echo $time; ?>()
	{
		time_in_s<?php echo $time; ?>=Number($("#time_in_seconds<?php echo $time; ?>").val());
		if(timer_on<?php echo $time; ?>==1)
		{
			clearTimeout(timer<?php echo $time; ?>);
			timer_on<?php echo $time; ?>=0;
			$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
		}	
		else
		{
			counter<?php echo $time; ?>();
			timer_on<?php echo $time; ?>=1;
		}
		
	}
	function counter<?php echo $time; ?>()
	{
		if($("#seconds<?php echo $time; ?>").html() == 0){
			clearTimeout(timer<?php echo $time; ?>);
			startLoading<?php echo $time; ?>();
		}
		else{
			/*
			current<?php echo $time; ?>=Number($("#seconds<?php echo $time; ?>").html());
			percentage<?php echo $time; ?> = Number(current<?php echo $time; ?>/(time_in_s<?php echo $time; ?>)*100)-Number(0.99/(time_in_s<?php echo $time; ?>)*100);
			val<?php echo $time; ?>=100-percentage<?php echo $time; ?>;
			$("#pbar<?php echo $time; ?>").progressbar("value" , val<?php echo $time; ?>);
			*/
			$("#seconds<?php echo $time; ?>").html(Number($("#seconds<?php echo $time; ?>").html())-1);
			
			if($("#timer_toggle<?php echo $time; ?>").css("display") == "inline" || $("#timer_toggle<?php echo $time; ?>").css("display") == "inline-block")
			{
				timer<?php echo $time;?> = setTimeout('counter<?php echo $time; ?>()',1000);
			}
			else
			{
				clearTimeout(timer<?php echo $time; ?>);
			}
		}
	}
	function counter_change<?php echo $time; ?>()
	{
		if(Number($("#time_in_seconds<?php echo $time; ?>").val())<1)
			$("#time_in_seconds<?php echo $time; ?>").val(15);
		$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
		time_in_s<?php echo $time; ?> = $("#time_in_seconds<?php echo $time; ?>").val();
	}
	function directRefreshMap()
	{
		$("#seconds<?php echo $time; ?>").html($("#time_in_seconds<?php echo $time; ?>").val());
		DirectRefresh<?php echo $time; ?>();
	}
	stop_resume_toggle<?php echo $time; ?>();
</script>
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
<?php /*
<!--div id="imgR"> 
<div id="car" style=" color: white; background-image:url(<?php echo base_url(); ?>/assets/caricon1.png); font-family: 'Lucida Grande', 'Arial', sans-serif;font-size: 10px;text-align: center; width: 35px;height:18;white-space: nowrap;margin-top:-20px">truck
</div>
</div--> */ ?>