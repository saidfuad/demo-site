<?php 

	
	$device_id 	= "2166";
	$user 		= $_SESSION['user_id'];
	$show_dash_assets_combo 		= $_SESSION['show_dash_assets_combo'];
	$timezone 	= $_SESSION['timezone'];
	
	$query ="select * from assests_master am where am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user)) "; 
	$res = mysql_query($query) or die($query. mysql_error());
	$device_Array = array();
	while($row =mysql_fetch_array($res)){
		
		$device_id = $row['device_id'];
		$device_Array['device'][]=$device_id;
		$last_id="";
		$query = "SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$timezone."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.angle_dir, lm.speed, lm.ignition, im.icon_path, am.assets_category_id from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where lm.device_id = $device_id limit 1";
		
		$results =mysql_query($query) or die($query.mysql_error());
		while($rowest =mysql_fetch_array($results)){

			$device_Array['lat'][$device_id] = floatval($rowest['lati']);
			$device_Array['lng'][$device_id] = floatval($rowest['longi']);
			$device_Array['ignition'][$device_id] = $rowest['ignition'];
			$device_Array['angle'][$device_id] = floatval($rowest['angle_dir']);
			$device_Array['last_id'][$device_id] = $rowest['id'];
			$device_Array['assets_category_id'][$device_id] = $rowest['assets_category_id'];
			$device_Array['last_datetime'][$device_id] = strtotime($rowest['add_date']);
			//$text .= '('.ago($rowest['add_date) . ' ago)<br>';
			
			$text_address .= ago($rowest['add_date'])." ago";
			$text_address .= ", ".date($_SESSION['date_format']." ".$_SESSION['time_format'],strtotime($rowest['add_date']));
			$text_address .= ", Speed: ".$rowest['speed']." KM";
			if($rowest['address'] != ""){
				$text_address .= ", ".$rowest['address'];
			}
			//$#FFFFFFtext .= $rowest['sim_number'].'<br>';
			$device_Array['html_address'][$device_id] = $text_address;
			$device_Array['icon_path'][$device_id] = $rowest['icon_path'];
			if($device_Array['assets_category_id'][$device_id] == 1 || $device_Array['assets_category_id'][$device_id] == "" || $device_Array['assets_category_id'][$device_id] == 0){
				$device_Array['image_type'][$device_id] = "truck.png";
			}else if($device_Array['assets_category_id'][$device_id] == 2){
				$device_Array['image_type'][$device_id] = "car.png";
			}else if($device_Array['assets_category_id'][$device_id] == 3){
				$device_Array['image_type'][$device_id] = "bus.png";
			}else{
				$device_Array['image_type'][$device_id] = "truck.png";
			}
		}
	}

?>


<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">


<script type='text/javascript'>
$(document).ready(function(){
	image=$("#car");
	var height=$(document).height();
	aprox_height=Number(height-140)+"px";
	$("#map_canvas").css("height",aprox_height);
	$("#map_canvas").css("margin-top","2px");
	$("#map_canvas").css("margin-bottom","0px");
});
</script>

<div class="container getHeightcss">
   <script type="text/javascript">
	
		var map = null;
	
		
		
    function initialize() {
	  
		directionsService = new google.maps.DirectionsService();
        var mapOptions = {
          zoom: 2,
		   center: new google.maps.LatLng(0, 0),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
		<?php foreach($device_Array['device'] as $vals){ 
		if($device_Array['lat'][$vals] !="" &&  $device_Array['lng'][$vals] != ""){		?>
		point<?php echo $vals; ?> = new google.maps.LatLng(<?php echo $device_Array['lat'][$vals]; ?>,<?php echo  $device_Array['lng'][$vals]; ?>);
		myOptions<?php echo $vals; ?> = {
			 content: $("#imgR<?php echo $vals; ?>").html()
			,boxStyle: {
			   textAlign: "center"
				  ,fontSize: "8pt"
				  ,width: "18px"
				 }
				,disableAutoPan: true
				,pixelOffset: new google.maps.Size(-7, 0)
				,enableEventPropagation: true
				,position: point<?php echo $vals; ?>
				,closeBoxURL: ""
				,isHidden: false
				,pane: "mapPane"
		};		
		ib<?php echo $vals; ?> = new InfoBox(myOptions<?php echo $vals; ?>);                
		ib<?php echo $vals; ?>.open(map);
		<?php } 
		if($device_Array['angle'][$vals] !="" ){	
		?>
			image.rotate(<?php echo $device_Array['angle'][$vals]; ?>);
		<?php }  }?>
		
		
		
		var trackNewWindowDiv = document.createElement('DIV');
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
		   strokeOpacity: 0.7
		}
	};
	google.maps.event.addDomListener(window, 'load', initialize);
	


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
	
    <div id="map_canvas"></div>
<div style="display:none">
<?php foreach($device_Array['device'] as $vals){ ?>
<div id="imgR<?php echo $vals; ?>">
<div id="car<?php echo $vals; ?>" style="color: white; background-image:url(../assets/<?php echo $device_Array['image_type'][$vals]; ?>); background-repeat:no-repeat; font-family: 'Lucida Grande', 'Arial', sans-serif;font-size: 10px;text-align: center; width: 100px; height:33px; white-space: nowrap;margin-top:-20px;">
</div>
</div>
<?php } ?>
</div>
<style>
.ui-slider{
height:26px !important;
}
</style>	


<script>
	$(document).ready(function(){
		$("#opt_users").change(function() { show_hide_function();	});
		$("#opt_groups").change(function() { show_hide_function();	});
		$("#opt_areas").change(function() { show_hide_function();	});
		$("#opt_landmarks").change(function() { show_hide_function();	});
		$("#opt_owners").change(function() { show_hide_function();	});
		$("#opt_divisions").change(function() { show_hide_function();	});
	});
	function show_hide_function(){
		var opt_users = $("#opt_users").val();
		var opt_groups = $("#opt_groups").val();
		var opt_areas = $("#opt_areas").val();
		var opt_landmarks = $("#opt_landmarks").val();
		var opt_owners = $("#opt_owners").val();
		var opt_divisions = $("#opt_divisions").val();
		<?php foreach($device_Array['device'] as $vals){ ?>
		ib<?php echo $vals; ?>.setMap(null)
		<?php } ?>
		if(opt_users=="" && opt_groups=="" && opt_areas=="" && opt_landmarks=="" && opt_divisions=="" && opt_owners==""){
			<?php foreach($device_Array['device'] as $vals){ ?>
			ib<?php echo $vals; ?> = new InfoBox(myOptions<?php echo $vals; ?>); ib<?php echo $vals; ?>.open(map);
			<?php } ?>
		}else{
			var all_parts  = new Array();
			if(opt_users!=""){
				all_parts = opt_users.split(",");
				
			}
			if(opt_groups!=""){
				if(all_parts.length==0){
					all_parts = opt_groups.split(",");
				}else{	
					var parts = opt_groups.split(",");
					all_parts = compare_array(all_parts,parts);
				}
			}
			if(opt_areas!=""){
				if(all_parts.length==0){
					all_parts = opt_areas.split(",");
				}else{	
					var parts = opt_areas.split(",");
					all_parts = compare_array(all_parts,parts);
				}
				
			}
			if(opt_owners!=""){
				if(all_parts.length==0){
					all_parts = opt_owners.split(",");
				}else{	
					var parts = opt_owners.split(",");
					all_parts = compare_array(all_parts,parts);
				}
				
			}
			if(opt_landmarks!=""){
				if(all_parts.length==0){
					all_parts = opt_landmarks.split(",");
				}else{	
					var parts = opt_landmarks.split(",");
					all_parts = compare_array(all_parts,parts);
				}
			}
			if(opt_divisions!=""){
				if(all_parts.length==0){
					all_parts = opt_divisions.split(",");
				}else{	
					var parts = opt_divisions.split(",");
					all_parts = compare_array(all_parts,parts);
				}
			}
			jQuery.each(all_parts,function( index ,value) {
				 window['ib'+value] = new InfoBox(window['myOptions'+value]); window['ib'+value].open(map);
			});
		}
	}
	function compare_array(array1,array2){
		var arras = new Array();
		jQuery.each(array1,function( index ,value) {
			if(jQuery.inArray( value, array2 )!="-1"){
				arras.push(value);
			}
		});
		return arras;
	}
</script>