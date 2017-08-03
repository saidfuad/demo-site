<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<?php $device_id = $_REQUEST['device']; ?>
<?php 
		$user = $_SESSION['user_id'];
		 if(isset($_REQUEST['date']) && isset($_REQUEST['device'])){ 
		$query = "select am.assets_name,am.device_id	from assests_master am where am.id='".$_REQUEST['device']."' and find_in_set(am.id,(SELECT assets_ids FROM user_assets_map where user_id = $user))";
		 }else{		
		$query = "select am.assets_name,am.device_id	from assests_master am where am.device_id='".$_REQUEST['device']."' and find_in_set(am.id,(SELECT assets_ids FROM user_assets_map where user_id = $user))";
		 } 
		$res =mysql_query($query) or die($query.mysql_error());
		$names = "";
		if(mysql_num_rows($res)==1){
			$row =mysql_fetch_assoc($res);
			$names = " of ".$row['assets_name']." (".$row['device_id'].")";
		}
?>
<style>
.ui-body {
	padding:.4em 0px;
}
</style>
	<script type="text/javascript">
		$(document).live( "pagecreate", function() {
				var picker = $( "input[type='text']", this );
				$("#all_point_report_live_map").mobipick({
				locale: "en" //default is "en", english
			});
							$("#all_point_report_live_map1").mobipick({
				locale: "en" //default is "en", english
			});
			$("#all_point_report_live_map").bind( "change", function() {
				var date = $( this ).val();
				var dateObject = $( this ).mobipick( "option", "date" );
			});	
			$("#all_point_report_live_map1").bind( "change", function() {
				var date = $( this ).val();
				var dateObject = $( this ).mobipick( "option", "date" );
			});
			
	});
</script>
	
<script type="text/javascript">

function formatName(cellvalue, options, rowObject){
	return device_jq;
}
/*
function searchallpoints(){

	$("#allpoints_grid_div<?php echo time(); ?>").show();
	$("#all_pont_map<?php echo time(); ?>").hide();
	var sdate = $('#all_points_sdate').val();
	var edate = $('#all_points_edate').val();
	var device = $('#all_points_device').val();
	
	if(device == ""){
		alert("Please select device");
		return false;
	}
	clearMapRoutLoading();
	//$("#all_point_pBar").css("display","none");
	$("#v_map_id").removeClass("ui-state-disabled");
	$("#v_map_id").removeAttr("disabled");
	device_jq=$('#all_points_device option:selected').html();
	//$("#allpoints_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");
	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:device, page:1}}).trigger("reloadGrid");
	return false;	
}


*/
var latArr = new Array();
var lngArr = new Array();
var htmlArr = new Array();
var ignitionArr = new Array();
var cnt =0;
var device_jq;
var markersmapAllpoint  = [];

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];

var to_htmlsmap  = [];
var from_htmlsmap  = [];

var polylinesmapAllpoint = [];
var polylineCoordsmapAllpoint = [];
var mapmapAllpoint = null;
var mapOptionsmapAllpoint;
var totalDir=0;
var totalDir_count=0;
var Timer_counter=1000;
var Poin_Cntr=0;
var current_all_p;
var percentage_all_p;
var val_all_p;
var distance_all_p=0;
var distance_all_total=0;
var directionsDisplayAllpoint = [];
var rendererOptionsAllpoint = {
					preserveViewport: true,
					draggable: false,
					suppressMarkers: true,
					polylineOptions: {
					   map: mapmapAllpoint,
					   strokeColor:'#FF0000',
					   //strokeWidth: 3,
					   strokeOpacity: 0.7}
			};
var wayptsAllpoint = [];

var arrowMarkerAllpoint = [];
var mcOptionsAllpoint = {gridSize: 50, maxZoom: 15};
var markerClusterAllpoint;

var allpointBounds;

function viewOnMapAllpoint(){
	$('.view_reports').hide();
	$('.view_reports_map').show();
	var device = $('#all_points_device').val();
	var window_text=$('#all_points_device option:selected').text();
	Poin_Cntr=0;
	totalDir=0;
	totalDir_count=0;
	if(device == ""){
		alert("Please select device");
		return false;
	}
	
	$("#v_map_id").addClass("ui-state-disabled");
	$("#v_map_id").attr("disabled","disabled");
	onLoadmapAllpoint();
	
	var start_date = $('#all_point_report_live_map').val();
	var end_date = $('#all_point_report_live_map1').val();

	$.post("php/all_point_report_live_map.php?cmd=trackOnMap", { device: device, start_date: start_date, end_date: end_date },
	 function(data) {
		if(data){
			clearMapRoutLoading();
			var lat = data.lat;
			var lng = data.lng;
			var html = data.html;
			var ign = data.ignition_status;
			if(lat.length > 0){
				for(i=0; i<lat.length; i++){
					latArr.push(lat[i]);
					lngArr.push(lng[i]);
					htmlArr.push(html[i]);
					ignitionArr.push(ign[i]);
				}
				var distance = data.distance;
				distance=Math.round(distance*100)/100;
				var txt = "Map <?php echo $names; ?>  Distance : " + distance + " KM";
			
				//setTimeout(function(){
					viewTrackAllpoint(txt);
					//alert(latArr.length);
				//},1000);
				
			}else{
				alert("No_Data_Found");
				clearMapRoutLoading();
				//$("#all_point_pBar").css("display","none");
				$("#v_map_id").removeClass("ui-state-disabled");
				$("#v_map_id").removeAttr("disabled");
			}
			$("#loading_top").css("display","none");
		}
	 }, 'json'
	);
}

function onLoadmapAllpoint() {
	$("#allpoints_grid_div<?php echo time(); ?>").hide();
	$("#all_pont_map<?php echo time(); ?>").show();
	//$("#all_point_report_live_map").hide();
	//$("#all_point_report_live_map1").hide();
	directionsService = new google.maps.DirectionsService();
	var mapObjmap = document.getElementById("all_pont_map<?php echo time(); ?>");
	
	if (mapObjmap != 'undefined' && mapObjmap != null) {
	
	mapOptionsmapAllpoint = {
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		streetViewControl:true,
		overviewMapControl:true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR}
	};

	mapOptionsmapAllpoint.center = new google.maps.LatLng(
		25.000000,
		135.000000
	);
	
	mapmapAllpoint = new google.maps.Map(mapObjmap,mapOptionsmapAllpoint);
	mapmapAllpoint.enableKeyDragZoom();	
	allpointBounds = new google.maps.LatLngBounds();
  }
}
function clearMapRoutLoading(){
	totalDir=0;
	totalDir_count=0;
	arr_i=0;
	Timer_counter=1000;
	last_s1="";
	last_e1="";
	latArr = [];
	lngArr = [];
	htmlArr = [];
	ignitionArr = [];
	Poin_Cntr=0;
}


function createMarkerAllpoint(map, point, title, html, icon, icon_shadow, sidebar_id, openers){
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){

		var infoBubble = new InfoBubble({
          map: map,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 0,
		  minWidth : 230,
		  minHeight : 90
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

function arrowMarkerAllpointFunction(map, point, title, html, img){
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
	if(html!=''){

		var infoBubble = new InfoBubble({
          map: map,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 0,
		  minWidth : 200,
		  minHeight : 90
        });
		
		google.maps.event.addListener(new_marker, 'click', function() {
		  if (!infoBubble.isOpen()) {
			infoBubble.setContent(html);
			infoBubble.open(map, new_marker);
		  }
		});
		/*
		if(openers != ''&&!isEmpty(openers)){
		   for(var i in openers){
			 var opener = document.getElementById(openers[i]);
			 opener.onclick = function(){infoBubble.open(map, new_marker); return false};
		   }
		}*/
		/*
		if(sidebar_id != ''){
			var sidebar = document.getElementById(sidebar_id);
			if(sidebar!=null && sidebar!=undefined && title!=null && title!=''){
				var newlink = document.createElement('a');
				
				newlink.onclick=function(){infoBubble.open(map, new_marker); return false};
				
				newlink.innerHTML = title;
				sidebar.appendChild(newlink);
			}
		}*/
	}
	return new_marker;
}

function viewLocationAllpoint(lat, lng, html){
	onLoadmapAllpoint();
	clearOverlaysAllpoint();
	var point = new google.maps.LatLng(lat, lng);
	var text = "<div style='font-size:12px;line-height: 14px;'> " + html + "</div>";
	markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",text, '', '', "sidebar_map", '' ));	
	mapmapAllpoint.setCenter(point);
	
}

function viewTrackAllpoint(devText){
	clearOverlaysAllpoint();
	var myTextDiv = document.createElement('div');
	myTextDiv.id = 'my_text_div';
	myTextDiv.innerHTML = '<Span id="distance_txt_all_p" style="color:black;background-color:rgba(255,255,255,0.7);display:none">'+devText+'</span>';
	myTextDiv.style.color = 'white';
	mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(myTextDiv);
	totalDir=Math.floor(latArr.length/9);
	call_start_new_line(0);
}

function call_start_new_line(arr_i)
{
		//alert("Total Directions->"+totalDir+", Count->"+totalDir_count+", Arr_i->"+arr_i);
		/*if($("#all_point_pBar").css("display") != "block" && $("#all_point_pBar").css("display") != "inline-block" )
		{
			clearMapRoutLoading();
			return false;
		}*/
		var point = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
		allpointBounds.extend(point);
		var image = '';
		var shadow = new google.maps.MarkerImage("../assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
		if(arr_i == 0){
			mapmapAllpoint.setCenter(point);
			//alert("0->"+arr_i);
			var img = '../assets/marker-images/BLUE-START.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",htmlArr[arr_i], img, shadow, "sidebar_map", '' ));			
		}
		else if(arr_i == (latArr.length-1)){
			//alert("1-="+arr_i);
			var img = '../assets/marker-images/BLUE-END.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",htmlArr[arr_i], img, shadow, "sidebar_map", '' ));
		}else{
			//alert("2->"+arr_i);
			var p1 = new google.maps.LatLng(latArr[arr_i-1], lngArr[arr_i-1]);
			var p2 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			
			var dir = bearing(p1, p2);
			var dir = Math.round(dir/3) * 3;
			while (dir >= 120) {dir -= 120;}
			
			a=p1,
            z=p2,
			  
			dir=((Math.atan2(z.lng()-a.lng(),z.lat()-a.lat())*180)/Math.PI)+360,
            ico=((dir-(dir%3))%120);
			var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+ico+".png";	
			if(totalDir > totalDir_count)
			{
				for(xi=arr_i;xi<=arr_i+7;xi++)
				{
					if(ignitionArr[xi]==0){
						alert('zero');
						img = "http://gatti.nkonnect.com/assets/marker-images/kml-RED-END.png";
					}	
				}
			}
					
		
			var mkr = arrowMarkerAllpointFunction(mapmapAllpoint, point, "Marker Description", htmlArr[arr_i], img);
			markersmapAllpoint.push(mkr);
			arrowMarkerAllpoint.push(mkr);
			
		}

	//alert("3->"+arr_i);
	
		if(totalDir > totalDir_count)
		{
			//alert("4->"+arr_i);
			var point1 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			wayptsAllpoint=[];
			Poin_Cntr++;
			for(i=0;i<=7;i++)
			{
			arr_i++;
			wayptsAllpoint.push({
				location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
				stopover:true
				});
			}
			
			arr_i++;
			var point2 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			calcRouteAllpoint(point1,point2,Poin_Cntr);
			if(latArr.length != arr_i)
				setTimeout(function(){call_start_new_line(arr_i)},Timer_counter);
			totalDir_count++;
			
			current_all_p=Number(totalDir_count);
			percentage_all_p = Number(current_all_p/(totalDir)*100)-Number(0.99/(totalDir)*100);
			val_all_p=100-percentage_all_p;
//			$("#all_point_pBar").progressbar("value" , percentage_all_p);
		}else if(totalDir == totalDir_count && latArr.length > arr_i && totalDir!=0)
		{
			//alert("5->"+arr_i);
			var total_ar=latArr.length-arr_i;
			if(total_ar>=2)
			{
//			$("#all_point_pBar").progressbar("value" , 99.99);
			var point1 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			wayptsAllpoint=[];
			Poin_Cntr++;
			if(total_ar>2)
			{
				//alert("6->"+arr_i);
				for(i=0;i<=total_ar-2;i++)
				{
				arr_i++;
				wayptsAllpoint.push({
								location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
								stopover:true});
				}
			}
			arr_i++;
			var point2 = new google.maps.LatLng(latArr[latArr.length-1], lngArr[latArr.length-1]);
			calcRouteAllpoint(point1,point2,Poin_Cntr);
			if(latArr.length != arr_i)
				setTimeout(function(){call_start_new_line(arr_i)},Timer_counter);
			//totalDir_count++;
			//$("#all_point_pBar").css("display","none");
				setTimeout(function(){
					mapmapAllpoint.fitBounds(allpointBounds);
					markerClusterAllpoint = new MarkerClusterer(mapmapAllpoint, arrowMarkerAllpoint, mcOptionsAllpoint);
					var txt = "Map <?php echo $names; ?> Distance : " + distance_all_total.toFixed(2) + " KM";
					$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
					$("#distance_txt_all_p").css("display","block");
				},1000);
			}
			
			$("#v_map_id").removeClass("ui-state-disabled");
			$("#v_map_id").removeAttr("disabled");
		}
		else
		{
			$("#distance_txt_all_p").css("display","block");
			$("#v_map_id").removeClass("ui-state-disabled");
			$("#v_map_id").removeAttr("disabled");
			//$("#all_point_pBar").css("display","none");
			var txt = "Map <?php echo $names; ?> Distance : " + distance_all_total.toFixed(2) + " KM";
			$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
			markerClusterAllpoint = new MarkerClusterer(mapmapAllpoint, arrowMarkerAllpoint, mcOptionsAllpoint);
		//	alert(arr_i+"of -> total"+latArr.length+", Total"+totalDir+", CountTotal"+totalDir_count);
		}
}

var last_s1="";
var last_e1="";
function calcRouteAllpoint(s1, e1, pointCounter){
		directionsDisplayAllpoint[pointCounter] = new google.maps.DirectionsRenderer(rendererOptionsAllpoint);
		directionsDisplayAllpoint[pointCounter].setMap(mapmapAllpoint);
		var request = {
			origin:s1, 
			destination:e1,
			waypoints: wayptsAllpoint,
			optimizeWaypoints: true,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplayAllpoint[pointCounter].setDirections(response);
				distance_all_p = Number((response.routes[0].legs[0].distance.value)/1000);
				distance_all_total += Math.round(distance_all_p*100)/100;
				//var txt = "<?php echo $names; ?> Distance : " + distance_all_total.toFixed(2) + " KM";
				//$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
				if(last_s1=="")
					Timer_counter=Timer_counter-8;
				return true;
			}
			else
			{
				if(last_s1!=s1 && last_e1!=e1)
				{
					Timer_counter=Timer_counter+30;
					last_s1=s1;
					last_e1=e1;
					calcRouteAllpoint(s1, e1, pointCounter);
				}
				else
				{	
					Timer_counter=Timer_counter+100;
				}
			}
		});	
		wayptsAllpoint = [];
  }
  
function clearOverlaysAllpoint() {
	if (directionsDisplayAllpoint) {
		for (i in directionsDisplayAllpoint) {
		  directionsDisplayAllpoint[i].setMap(null);
		}
	 }
	directionsDisplayAllpoint = [];	
	if(arrowMarkerAllpoint.length > 0){
		arrowMarkerAllpoint = [];
		markerClusterAllpoint.clearMarkers();
	}
	for(i=0; i< (mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_LEFT].length); i++){
		mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_LEFT].removeAt(i);
	}
	if (markersmapAllpoint) {
		for (i in markersmapAllpoint) {
			markersmapAllpoint[i].setMap(null);
		}
	}
	if (polylinesmapAllpoint) {
		for (i in polylinesmapAllpoint) {
			polylinesmapAllpoint[i].setMap(null);
		}
	}
	markersmapAllpoint = [];
	polylinesmapAllpoint = [];
	wayptsAllpoint = [];
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
	<center><h3><?php echo $lang['All Point Report'].' Map'.$names; ?></h3></center>
	<?php /*if(isset($_REQUEST['date']) && isset($_REQUEST['device'])){ ?>
	<div data-role="content">
	
		<div class="ui-body ui-body-d">
		<!--	<table width='100%' id='table' border="1" style='font-size:12px !important'>
				<Tr style='font-weight:bold'><th>Assets Name</th><th>Stop Time</th><th>Start Time</th><th>Location Name</th><th>Duration </th></tr>-->
				<?php
					$user = $_SESSION['user_id'];
					if(isset($_REQUEST['page']))
						$page = $_REQUEST['page'];
					else
						$page = 1;
					$sdate=date('Y-m-d 00:00:00',strtotime($_REQUEST['date']));
					$edate=date('Y-m-d 23:59:59',strtotime($_REQUEST['date1']));
					$device=$_REQUEST['device'];
					
					$limit = 5;
					$start = ($page-1)*$limit;
					$end = $page*$limit;
					
					$query = "SELECT count(*) as count from tbl_track dm left join assests_master am on am.id=dm.assets_id WHERE  find_in_set(dm.assets_id,(SELECT assets_ids FROM user_assets_map where user_id = $user)) and CONVERT_TZ(dm.add_date,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "'  AND dm.assets_id = '".$device."'";
					
					$res =mysql_query($query) or die($query.mysql_error());
					$row =mysql_fetch_array($res);
					$total =$row['count'];
					if($end >$total)
						$end =$total;
					
					
					$query = "SELECT dm.*,CONCAT(am.assets_name,'(',am.device_id,')') as assets_id, CONVERT_TZ(dm.add_date,'+00:00','".$_SESSION['timezone']."') as add_date from tbl_track dm left join assests_master am on am.id=dm.assets_id  WHERE find_in_set(dm.assets_id,(SELECT assets_ids FROM user_assets_map where user_id = $user)) and CONVERT_TZ(dm.add_date,'+00:00','".$_SESSION['timezone']."') BETWEEN '" . $sdate . "' AND '" . $edate . "' AND dm.assets_id = '".$device."'";
						
					$query .= " order by dm.id desc limit $start,$limit";
					$res =mysql_query($query) or die($query.mysql_error());
					if(mysql_num_rows($res)<1)
					{
						echo "<center><b>".$lang['No Data Found'] ."</b></center>";
					}
					while($row=mysql_fetch_array($res))
					{
						echo "<div data-role='collapsible-set' data-theme='b' data-content-theme=''><div data-role='collapsible' data-collapsed=''><h3>".$row['assets_id']."</h3><div data-role='fieldcontain'><b>".$lang['Date'] ." : </b>".date($_SESSION['date_format'],strtotime($row['add_date']))."<br><b>".$lang['Address'] ." : </b>".$row['address']."<br><b>".$lang['speed'] ." : </b>".$row['speed']." </div></div></div>";
					}
			?>
	<!--	</table>-->
	</div>
	<div class="ui-body ui-body-d" style='text-align:center'>
	<?php if($total>1) { ?>
		<b><?php echo $lang['View']." "; echo $start+1; ?>-<?php echo $end." "; echo $lang['from']." "; echo $total; ?></b>

		<?php if($page >1) { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?date1=<?php echo $_REQUEST['date1']; ?>&date=<?php echo $_REQUEST['date']; ?>&device=<?php echo $_REQUEST['device']; ?>&page=<?php echo $page-1; ?>"  data-role='button' data-theme='e'  data-inline='false' ><?php echo $lang['Previous']; ?></a>
		<?php }else {?>
			&nbsp;
		<?php } ?>
		<?php if($end <$total) { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>?date1=<?php echo $_REQUEST['date1']; ?>&date=<?php echo $_REQUEST['date']; ?>&device=<?php echo $_REQUEST['device']; ?>&page=<?php echo $page+1; ?>"  data-role='button' data-theme='e'  data-inline='false' ><?php echo $lang['Next']; ?></a>
		<?php } } ?>
		<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
	</div>
</div>
		
	<?php } else { */ ?>
<div data-role="content">
		<div class="ui-body ui-body-d">
                	<div data-role="fieldcontain">
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
				<table style='width:100%;'>
					<tr  align='center' class='view_reports'>
						<td  align='right'><?php echo $lang['From Date']; ?>:</td>
  	                	<td><input type="text" name="date" id="all_point_report_live_map"   /></td>
					</tr>
					<tr  align='center' class='view_reports'>
						<td  align='right'><?php echo $lang['To Date']; ?>:</td>
  	                	<td><input type="text" name="date1" id="all_point_report_live_map1"   /></td>
					</tr>
					<?php 
						$user = $_SESSION['user_id'];
						$query = "select * from assests_master where status=1 AND del_date is null AND device_id='".$device_id."'";
						
						$res =mysql_query($query) or die($query.mysql_error());
						while($row =mysql_fetch_array($res))
						{
							$div_id = $row['id'];
						}
					?>
					<tr align='center' class='view_reports'>
						<td> <input type="hidden" name="all_points_device" id="all_points_device" value="<?php echo $div_id; ?>"/>
							
						</td>
					</tr>
					<tr align='center' class='view_reports'>
						<td colspan='2'><input data-theme="e" value=<?php echo $lang['search']; ?> onclick="viewOnMapAllpoint()" type="button" ></td>
					</tr>
					<tr align='center' class='view_reports_map' style='display:none'>
						<td colspan='2'><div id="all_pont_map<?php echo time(); ?>" style="display:none;width: 100%; height: 400px; position:relative;"></div></td>
					</tr>
					<tr class='view_reports'>
						<td colspan='2' ><a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a></td>
					</tr>
					<tr class='view_reports_map' style='display:none'>
						<td colspan='2' ><a data-icon="back" onclick='$(".view_reports_map").hide();$(".view_reports").show();'  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a></td>
					</tr>
				</table>
			</form>
        </div>
	</div>
</div>
<div id="all_point_pBar" title="" style="text-align:center;display:inline"><span style="position:absolute"><?php echo $lang['Loading Points']; ?></span></div>


<?php /*}*/ include("footer.php"); ?>