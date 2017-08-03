<?php 
	include("php/session.php");
	include("header.php"); 	
	$device_id 	= $_REQUEST['device'];
	$user 		= $_SESSION['user_id'];
	$show_dash_assets_combo 		= $_SESSION['show_dash_assets_combo'];
	$timezone 	= $_SESSION['timezone'];
	
	if($user != 1){
		$query ="select * from assests_master am where am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user)) AND am.status = 1 and am.device_id=".$_REQUEST['device'];
	}else{
		$query ="select * from assests_master am where am.status=1 AND am.del_date is null AND am.status = 1 and am.device_id=".$_REQUEST['device'];
	}
	
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)<1){
			echo "<div style='text-align:center;'><h1 style='font-size:60px'>Device Not Found<h1>";
			echo "<input type='button' value='Back' style='font-size:32px;display:inline;margin:0px;padding:4px;' onClick='window.location=\"./\"' /></div>";
			include("footer.php"); 
			die();
	}
	//$query = "SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$timezone."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.speed, lm.ignition, im.icon_path, am.assets_category_id from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where am.status = 1 AND lm.device_id = $device_id limit 1";
	
	$query = "SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$timezone."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.speed, lm.ignition, im.icon_path, am.assets_category_id, acm.assets_cat_image as image_type from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id left join assests_category_master acm on acm.id = am.assets_category_id LEFT JOIN icon_master im ON im.id = am.icon_id where am.status = 1 AND lm.device_id = '$device_id' limit 1";
	$last_id="";
	$res =mysql_query($query) or die($query.mysql_error());
	while($row =mysql_fetch_array($res))
	{
			$lat = floatval($row['lati']);
			$lng = floatval($row['longi']);
			$ignition = $row['ignition'];
			$last_id = $row['id'];
			$assets_category_id = $row['assets_category_id'];
			$image_type = $row['image_type'];
			$last_datetime = strtotime($row['add_date']);
		
			//$text .= '('.ago($row['add_date) . ' ago)<br>';
			
			$text_address .= $row['assets_name'].'('.$row['device_id'].')';
			$text_address .= ", ".ago($row['add_date'])." ago";
			$text_address .= ", ".date($_SESSION["date_format"]." ".$_SESSION["time_format"],strtotime($row['add_date']));
			$text_address .= ", Speed: ".$row['speed']." KM";
			if($row['address'] != ""){
				 $address = explode(",",$row['address']);
					if($address[0]>6){
						$address=$address[0];
					}else{
						$address=$address[0]." ".$address[1];
					}					
				$text_address .= ", ".$address;
			}
			//$#FFFFFFtext .= $row['sim_number'].'<br>';
			$html_address = $text_address;
			$icon_path = $row->icon_path;
	}
/*
	if($assets_category_id == 1 || $assets_category_id == "" || $assets_category_id == 0 || $assets_category_id == 13){
		$image_type = "truck.png";
	}else if($assets_category_id == 2){
		$image_type = "car.png";
	}
	else if($assets_category_id == 3){
		$image_type = "bus.png";
	}
	else if($assets_category_id == 4){
		$image_type = "mobile.png";
	}
	else if($assets_category_id == 5){
		$image_type = "bike.png";
	}
	else if($assets_category_id == 6){
		$image_type = "altenator.png";
	}
	else if($assets_category_id == 7 || $assets_category_id == 8){
		$image_type = "man.png";
	}
	else if($assets_category_id == 9){
		$image_type = "stacker.png";
	}
	else if($assets_category_id == 10){
		$image_type = "loader.png";
	}
	else if($assets_category_id == 11){
		$image_type = "locomotive.png";
	}
	else if($assets_category_id == 12){
		$image_type = "generator.png";
	}
	else if($assets_category_id == 13){
		$image_type = "maintenance.png";
	}
	else if($assets_category_id == 14){
		$image_type = "motor.png";
	}
	else if($assets_category_id == 15){
		$image_type = "bobcat.png";
	}
	else if($assets_category_id == 16){
		$image_type = "tractor.png";
	}
	else if($assets_category_id == 17){
		$image_type = "car1.png";
	}
	else if($assets_category_id == 18){
		$image_type = "satellite.png";
	}
	else{
		$image_type = "truck.png";
	}
*/
	if($image_type == '') {
		$image_type = "truck.png";
	}
	$prefix=$device_id;
?>

<script type='text/javascript'>
$(document).ready(function(){
	image<?php echo $prefix; ?>=$("#car<?php echo $prefix; ?>");
	var height=$(document).height();
	aprox_height=Number(height-140)+"px";
	$("#map_canvas").css("height",aprox_height);
	$("#map_canvas").css("margin-top","2px");
	$("#map_canvas").css("margin-bottom","0px");
});
</script>

<div class="container getHeightcss">
<?php 
	

?>
   <script type="text/javascript">
	var limit = 40;   
function AddressShowHide(controlDiv, map){
  controlDiv.style.padding = '5px';	 
  // Set CSS for the control border

  var controlUI = document.createElement('DIV');
  controlUI.style.background = 'none repeat scroll 0% 0% rgb(255, 255, 255)';
  controlUI.style.border = '1px solid rgb(113, 123, 135)';
  controlUI.style.cursor = 'pointer';
  controlUI.style.fontFamily = ' Arial,sans-serif';
  controlUI.style.boxShadow = ' 0px 2px 4px rgba(0, 0, 0, 0.4)';
  controlUI.style.fontWeight = 'bold';
  controlUI.style.minWidth = '28px';
  controlUI.style.textAlign = 'center';
  controlUI.style.fontSize = '13px';
  controlUI.style.padding = '1px 6px';
  controlUI.title = 'Click to Show/Hide Address';
  controlDiv.appendChild(controlUI);
  var lblOne = document.createElement('label');
  lblOne.htmlFor = "chkAreaLive<?php echo $prefix; ?>";
  lblOne.innerHTML= "<?php echo $lang['Address OFF']; ?>";
  controlUI.appendChild(lblOne);
  google.maps.event.addDomListener(controlUI, 'click', function() {
	if(lblOne.innerHTML== "<?php echo $lang['Address OFF']; ?>")
	{
		height=document.getElementById("htmlAddrs").offsetHeight+document.getElementById("map_canvas").offsetHeight; 
		document.getElementById("htmlAddrs").style.display="none";
		document.getElementById("map_canvas").style.height=height+"px";
		lblOne.innerHTML="<?php echo $lang['Address ON']; ?>";
	}
	else
	{
		document.getElementById("htmlAddrs").style.display="block";
		height=document.getElementById("map_canvas").offsetHeight-document.getElementById("htmlAddrs").offsetHeight; 
		document.getElementById("map_canvas").style.height=height+"px";
		lblOne.innerHTML="<?php echo $lang['Address OFF']; ?>";
	}
  });
}	   

		var sidebar_htmlmap  = '';
		var marker_htmlmap  = [];

		var to_htmlsmap  = [];
		var from_htmlsmap  = [];

		var polylinesmap = [];
		var polylineCoordsmap = [];
		var map = null;
		var mapOptionsmap;
		var markersmap<?php echo $prefix; ?>  = [];
		var polylinesmap<?php echo $prefix; ?> = [];
		var lat<?php echo $prefix ?> = new Array();
		var lng<?php echo $prefix; ?> = new Array(); 
		var html<?php echo $prefix; ?> = new Array();
		var ib<?php echo $prefix; ?>;
		var last_datetime<?php echo $prefix; ?> = <?php echo $last_datetime; ?>;
		var last_id = <?php echo $last_id; ?>;
		var loop<?php echo $prefix; ?>=0,j,x;
		
		var polyNameArr = [];
		var polyArrV = [];
		//var geocoder;
		var live_poly_array<?php echo $prefix; ?> = [];
		var live_landmark_array<?php echo $prefix; ?> = [];
		var circleArray<?php echo $prefix; ?> = [];

		var directionsDisplay<?php echo $prefix; ?> = [];
		var map;
		
 	<?php if($lat!="" && $lng!=""){ ?>
			lat<?php echo $prefix; ?>.push(<?php echo $lat; ?>);
			lng<?php echo $prefix; ?>.push(<?php echo $lng; ?>);
			html<?php echo $prefix; ?>.push('<?php echo $html_address; ?>');
		<?php } ?>
		
		
		
      function initialize() {
	  
	  directionsService = new google.maps.DirectionsService();
        var mapOptions = {
          zoom: 15,
          center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
		var point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
		var myOptions = {
			 content: $("#imgR").html()
			,boxStyle: {
			   textAlign: "center"
				  ,fontSize: "8pt"
				  ,width: "18px"
				 }
				,disableAutoPan: true
				,pixelOffset: new google.maps.Size(-7, 0)
				,enableEventPropagation: true
				,position: point
				,closeBoxURL: ""
				,isHidden: false
				,pane: "mapPane"
		};		
		ib<?php echo $prefix; ?> = new InfoBox(myOptions);                
		ib<?php echo $prefix; ?>.open(map);
		
		$("#htmlAddrs").html('<?php echo $html_address;?>');
		var trackNewWindowDiv = document.createElement('DIV');
		var trackNewWindowControl = new AddressShowHide(trackNewWindowDiv, map);
		trackNewWindowDiv.index = 1;
		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackNewWindowDiv);
      }
		var rendererOptions = {
							preserveViewport: true,
							draggable: false,
							suppressMarkers: true,
							polylineOptions: {
							   map: map,
							   strokeColor:'#FF0000',
							   //strokeWidth: 3,
							   strokeOpacity: 0.7}

					};
function createMarker(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "../assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "../assets/marker-images/" + icon_shadow;}
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
function startLoading<?php echo $prefix; ?>(){
	$.ajax({
	  url: "php/device_live.php",
	  dataType :"json",
	  data:{ device: <?php echo $device_id; ?>, id: last_id, date_time: last_datetime<?php echo $prefix; ?> },
	  success:function(data){	
		 	var lat = data.lat;
			var lng = data.lng;
			var ignition = data.ignition;
			var speed = data.speed;
			var html = data.html;
			var html_address = data.html_address;
			var latLast, lngLast, htmlLast,htmlLast_addr;
			if(lat.length > 0){
				
				for(i=0; i<lat.length; i++){
					var point = new google.maps.LatLng(lat[i], lng[i]);
					//markersmap<?php echo $prefix; ?>.push(createMarker(map, point,"Marker Description",html[i], '', '', "sidebar_map", '' ));
					lat<?php echo $prefix; ?>.push(lat[i]);
					lng<?php echo $prefix; ?>.push(lng[i]);
					html<?php echo $prefix; ?>.push(html[i]);
					if(i==0){
						latLast = lat[i];
						lngLast = lng[i];
						htmlLast = html[i];
						htmlLast_addr = html_address[i];
					}
				}
				$("#htmlAddrs").html(""+html_address);
			
				lat1 = lat<?php echo $prefix; ?>[(lat<?php echo $prefix; ?>.length)-2]; 
				lat2 = lat<?php echo $prefix; ?>[(lat<?php echo $prefix; ?>.length)-1];
				lng1 = lng<?php echo $prefix; ?>[(lng<?php echo $prefix; ?>.length)-2]; 
				lng2 = lng<?php echo $prefix; ?>[(lng<?php echo $prefix; ?>.length)-1];
				var pointCounter = lat<?php echo $prefix; ?>.length;
				if(ignition[0] != 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}
				if(speed[0] > 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}
				if(last_ignition<?php echo $prefix; ?> == 1){
					viewTrack<?php echo $prefix; ?>(lat<?php echo $prefix; ?>,lng<?php echo $prefix; ?>,html<?php echo $prefix; ?>, map);
				
					calcRoute<?php echo $prefix; ?>(lat1, lng1, lat2, lng2, pointCounter, html[0]);
				}
				if(speed[0] > 0){
					last_ignition<?php echo $prefix; ?> = 1;
				}else{
					last_ignition<?php echo $prefix; ?> = ignition[0];
				}
				//map.setCenter(point);
				
				last_id<?php echo $prefix; ?> = data.last_id;
				last_datetime<?php echo $prefix; ?> = data.last_datetime;	
			}else{
				
				//update address part
				$("#htmlAddrs").html("'"+html_address+"'");
			}
			/*if(latLast == ""){
				latLast = <?php echo $lat; ?>;
				lngLast = <?php echo $lng; ?>;
			}
			
			for(i=0; i<polyArrV.length; i++){
				var coordinate = new google.maps.LatLng(latLast,lngLast);
				var isWithinPolygon = polyArrV[i].containsLatLng(coordinate);
				if(isWithinPolygon == true){
					//alert('In Area :' + polyNameArr[i]);
				}else{
					//alert('Out Of Area :' + polyNameArr[i]);
				}
			}
			*/
			if(timer_on==1)
			{
				document.getElementById('seconds').value=document.getElementById('time_in_seconds').value;
				counter();
			}
	  },
	  error:function(jqXHR, textStatus, errorThrown) {
		startLoading<?php echo $prefix; ?>();
	  }
	});
}
google.maps.event.addDomListener(window, 'load', initialize);
	
function calcRoute<?php echo $prefix; ?>(lat1, lng1, lat2, lng2, pointCounter, html1){

		loop<?php echo $prefix; ?>=0,j,x;
		markers_lat<?php echo $prefix; ?>=[];
		markers_lng<?php echo $prefix; ?>=[];
		ib<?php echo $prefix; ?>;

		var s1 = new google.maps.LatLng(lat1, lng1);
		var e1 = new google.maps.LatLng(lat2, lng2);
		// show route between the points
		
		directionsDisplay<?php echo $prefix; ?>[pointCounter] = new google.maps.DirectionsRenderer(rendererOptions);
		directionsDisplay<?php echo $prefix; ?>[pointCounter].setMap(map);
		var request = {
			origin:s1, 
			destination:e1,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay<?php echo $prefix; ?>[pointCounter].setDirections(response);
				ren<?php echo $prefix; ?> = directionsDisplay<?php echo $prefix; ?>[pointCounter];
				y=0;
				for(i=0;i<ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps.length;i++)
				{
					for(j=0;j<ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path.length;j++)
					{
							if(markers_lat<?php echo $prefix; ?>[y-1]!=ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lat() && markers_lng<?php echo $prefix; ?>[y-1]!=ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lng())
							{
							markers_lat<?php echo $prefix; ?>.push(ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lat());
							markers_lng<?php echo $prefix; ?>.push(ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[i].path[j].lng());
							}
					}
				}
				
				ib<?php echo $prefix; ?>.setPosition(ren<?php echo $prefix; ?>.directions.routes[0].legs[0].steps[0].path[0]);
				calltest<?php echo $prefix; ?>();
				
			}
		});
		
  }
function calltest<?php echo $prefix; ?>()
{
	lt1<?php echo $prefix; ?>=markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>];
	ln1<?php echo $prefix; ?>=markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>];
	lt2<?php echo $prefix; ?>=markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>+1];
	ln2<?php echo $prefix; ?>=markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>+1];
	if((loop<?php echo $prefix; ?>+1)<markers_lat<?php echo $prefix; ?>.length)
	{
		if(loop<?php echo $prefix; ?>!=0 && markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>-1]==markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>] && markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>-1]==markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>])
		{
			loop<?php echo $prefix; ?>+=2;
		}
		else
		{
			loop<?php echo $prefix; ?>++;
		}
//		i++;
		map.setCenter(new google.maps.LatLng(markers_lat<?php echo $prefix; ?>[loop<?php echo $prefix; ?>], markers_lng<?php echo $prefix; ?>[loop<?php echo $prefix; ?>]));
		ib<?php echo $prefix; ?>["content_"]=$("#imgR").html();
		ib<?php echo $prefix; ?>.open(map);
		test<?php echo $prefix; ?>(lt1<?php echo $prefix; ?>,ln1<?php echo $prefix; ?>,lt2<?php echo $prefix; ?>,ln2<?php echo $prefix; ?>);
	}

	
}
//this will get two points (lat1,lng1) and (lat2,lng2) and animate from first point to second
function test<?php echo $prefix; ?>(lat1,lng1,lat2,lng2)
{		
		fromLat = lat1;
        fromLng = lng1;
        toLat = lat2;
        toLng = lng2;
		  
          // store a LatLng for each step of the animation
          frames<?php echo $prefix; ?> = [];
          for (var percent = 0; percent < 1; percent += 0.05) {
            curLat = fromLat + percent * (toLat - fromLat);
            curLng = fromLng + percent * (toLng - fromLng);
            frames<?php echo $prefix; ?>.push(new google.maps.LatLng(curLat, curLng));
          }

          move<?php echo $prefix; ?> = function(ib<?php echo $prefix; ?>, latlngs, index, wait, newDestination) {
            ib<?php echo $prefix; ?>.setPosition(latlngs[index]);
			map.setCenter(latlngs[index]);
            if(index != latlngs.length-1) {
              // call the next "frame" of the animation
			  
              setTimeout(function() {
                move<?php echo $prefix; ?>(ib<?php echo $prefix; ?>, latlngs, index+1, wait, newDestination);
              }, wait);
            }
            else {
              ib<?php echo $prefix; ?>.position = ib<?php echo $prefix; ?>.destination;
              ib<?php echo $prefix; ?>.destination = newDestination;
			  //this will call calltest when first point to second point animation done.
			  calltest<?php echo $prefix; ?>();
			 
            }
          }

          // begin animation, send back to origin after completion
          move<?php echo $prefix; ?>(ib<?php echo $prefix; ?>, frames<?php echo $prefix; ?>, 0, 20, ib<?php echo $prefix; ?>.position);
}
function viewTrack<?php echo $prefix; ?>(lat, lng, html, mapmap){
	
	clearOverlays<?php echo time(); ?>(markersmap<?php echo $prefix; ?>, polylinesmap<?php echo $prefix; ?>);
	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		var shadow = 'shadow50.png';
		var openInfo = false;
		var createMMarker = true;
		if(i == 0){	
			//var img = 'BLUE-START.png';
			createMMarker = true;
			var img = 'mini-RED-BLANK.png';
			
		}
		else if(i == (lat.length-1)){
			var img = 'BLUE-END.png';
			//openInfo = true;
			createMMarker = false;
		}
		else{
			var p1 = new google.maps.LatLng(lat[i-1], lng[i-1]);
			var p2 = new google.maps.LatLng(lat[i], lng[i]);
			var dir = bearing(p2, p1 );
			var dir = Math.round(dir/3) * 3;
			while (dir >= 120) {dir -= 120;}
			
			//var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+dir+".png";
			var img = 'mini-RED-BLANK.png';
			
		}
		if(createMMarker == true){
			markersmap<?php echo $prefix; ?>.push(createMarker(mapmap, point,"Marker Description",html[i], img, shadow, "sidebar_map", '',false));
		}
				
		if(i > 0){
			
			
		}
  	}
	//mapmap.setCenter(point);
}

function clearOverlays<?php echo time(); ?>(markersmap, polylinesmap) {
  
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
//onLoadmap<?php echo $prefix; ?>();

window.addEventListener("resize", function() {
	$("#map_canvas").css("height","0");
	var height=$(document).height();
	aprox_height=Number(height-140)+"px";
	$("#map_canvas").css("height",aprox_height);
	$("#map_canvas").css("margin-top","2px");
	$("#map_canvas").css("margin-bottom","0px");
}, false);
</script>



	<div>
		
	</div>
	
	<div id="htmlAddrs" align="center" ></div>
    <div id="map_canvas"></div>
	
	<div>
			<div style='float:left;height: 23px;vertical-align: middle;padding-top: 9px;padding-right: 6px;'><?php echo $lang['Refresh After']; ?></div>
			<div style='float:left'><input type="text" size='2' id='seconds' style="width: 35px; display: inline-block;" data-mini="true" readonly='readonly' value="15"></div>
			<div style='float:left;height: 23px;vertical-align: middle;padding-top: 9px;padding-right: 6px;padding-left: 6px;'>/</div>
			<div style='float:left'><input data-mini="true" type='text' size='2' onblur='counter_change()' value='15' id='time_in_seconds' style="width: 35px; display: inline-block;"></div>
			<div style='float:left;padding-top: 2px;'>
				<select data-mini="true" name="toggleswitch1" id="toggleswitch1" data-theme="" data-role="slider" onChange="stop_resume_toggle();">
					<option value="off"><?php echo $lang['Off']; ?></option>
					<option value="on" selected='selected'><?php echo $lang['On']; ?></option>
				</select>				
			</div>
			<div style='clear:both'></div>
			<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
	 </div>
	</center>
<div style="display:none">
<div id="imgR">
<div id="car<?php echo $prefix; ?>" style="color: white; background-image:url(../assets/<?php echo $image_type; ?>); background-repeat:no-repeat; font-family: 'Lucida Grande', 'Arial', sans-serif;font-size: 10px;text-align: center; width: 100px; height:33px; white-space: nowrap;margin-top:-20px;">
</div>
</div>
</div>
<style>
.ui-slider{
height:26px !important;
}
</style>	
        
<script type="text/javascript">
var sel_users = '';
var sel_groups= '';
var sel_areas = '';
var sel_landmarks = '';
var sel_owners = '';
var sel_divisions = '';
var timer_on=0;
var timer;
var time_in_s;
var current ;
var percentage;
var TSeconds=document.getElementById('time_in_seconds');
var seconds=document.getElementById('seconds');
function stop_resume_toggle()
{
	time_in_s=Number(TSeconds.value);
	if(timer_on==1)
	{
		clearTimeout(timer);
		timer_on=0;
		seconds.value=TSeconds.value;
	}	
	else
	{
		counter();
		timer_on=1;
	}
}
stop_resume_toggle();
function counter()
{
	if(seconds.value == 0){
		clearTimeout(timer);
		startLoading<?php echo $prefix; ?>();
	}
	else{
		current=Number(seconds.value);
		percentage = Number(current/(time_in_s)*100)-Number(0.99/(time_in_s)*100);
		val=Number(100-percentage)+"%";
	
		seconds.value=Number(seconds.value)-1;
		timer<?php echo $time;?> = setTimeout('counter()',1000);
	}
}
function counter_change()
{
	if(Number(TSeconds.value)<1)
		TSeconds.value = 15;
	seconds.value=TSeconds.value;
	time_in_s = TSeconds.value;
}
function directRefresh_live()
{
	$("#seconds").html($("#time_in_seconds").val());
	DirectRefresh();
}
</script>
<?php include("footer.php"); ?>
