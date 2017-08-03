<?php
	$user = $this->session->userdata('user_id');
	$SQL = "SELECT country_lati,country_longi FROM tbl_users where user_id = '$user'";
	$query = $this->db->query($SQL);
	$row = $query->row();
	if(count($row)){
		$lati =  $row->country_lati;
		$longi =  $row->country_longi;
	} else {
		$lati =  -37.588837;
		$longi =  145.12109;
	}
	$lati =  -37.588837;
	$longi =  145.12109;
?>
<style>
.ui-widget .ui-widget{
	overflow : auto !important;
}
.jqplot-yaxis-label{
	left : -20px !important;
}
.widgetcontent{
	overflow : auto !important;
}
.jqplot-title{
	top : -11px !important;
}
.jqplot-xaxis-label{
	top : 40px !important;
}
#columns .column
{
	width:48%;
}
.widget-content{
	min-height:200px;
}

.assets_det_tbl td{ 
    color: white;
    font-size: 18px;
	line-height: 1.2em;
	padding-left:10px;
}
</style>
<script>
var map<?php echo time(); ?> = null;
var markersmap<?php echo time(); ?>  = [];	
$(document).ready(function () {
	
	$('#speedo_meter<?php echo time(); ?>').speedometer();
	onLoadmap<?php echo time(); ?>();
	get_assets_det<?php echo time(); ?>();
	get_distance<?php echo time(); ?>();
	get_speed<?php echo time(); ?>();
	get_speed_graph<?php echo time(); ?>();
	get_distance_graph<?php echo time(); ?>();
	get_stop_report<?php echo time(); ?>();
	get_landmark_report<?php echo time(); ?>();
	get_distance_wise<?php echo time(); ?>();
	get_area_in_out<?php echo time(); ?>();
	$("#div_widget5<?php echo time(); ?>, #div_widget3<?php echo time(); ?>, #div_widget6<?php echo time(); ?>").css("background-image", "url( someimage.png )");
	$("#div_widget5<?php echo time(); ?>, #div_widget3<?php echo time(); ?>, #div_widget6<?php echo time(); ?>").css("background-color", "#FFFFFF");
	
	$("#div_widget6<?php echo time(); ?>, #div_widget3<?php echo time(); ?>, #div_widget4<?php echo time(); ?>").css("color", "#000");
	
});
	window[selected_assets_ids+"_t"] = window.setInterval('refreshDash<?php echo time(); ?>()',10000); 
	
	function refreshDash<?php echo time(); ?>(){
		get_distance<?php echo time(); ?>();
		get_location<?php echo time(); ?>();
		get_speed<?php echo time(); ?>();
		get_speed_graph<?php echo time(); ?>();
		get_distance_graph<?php echo time(); ?>();
		get_stop_report<?php echo time(); ?>();
		get_landmark_report<?php echo time(); ?>();
		get_distance_wise<?php echo time(); ?>();
		get_area_in_out<?php echo time(); ?>();
	}
	function get_assets_det<?php echo time(); ?>(){
		$.post(
			"<?php echo base_url(); ?>index.php/home/assets_det/id/<?php echo $id; ?>",
			function(data){
					$("#div_widget1<?php echo time(); ?>").html(data);
			}
		);
	}
	function get_distance<?php echo time(); ?>(){
		$.post(
			"<?php echo base_url(); ?>index.php/home/get_distance/id/<?php echo $id; ?>",
			function(data){
					$("#div_widget2<?php echo time(); ?>").html(data);
			}
		);
	}
	function get_stop_report<?php echo time(); ?>(){
		$.post("<?php echo base_url(); ?>index.php/home/get_stop_report/", { device: <?php echo $id; ?>},
		function(data) {
			
			$("#div_widget7<?php echo time(); ?>").html(data);
		});
	}
	function get_landmark_report<?php echo time(); ?>(){
		$.post("<?php echo base_url(); ?>index.php/home/get_landmark_report/", { device: <?php echo $id; ?>},
		function(data) {
			
			$("#div_widget8<?php echo time(); ?>").html(data);
		});
	}
	function get_distance_wise<?php echo time(); ?>(){
		$.post("<?php echo base_url(); ?>index.php/home/get_distance_wise/", { device: <?php echo $id; ?>},
		function(data) {
			
			$("#div_widget9<?php echo time(); ?>").html(data);
		});
	}
	function get_area_in_out<?php echo time(); ?>(){
		$.post("<?php echo base_url(); ?>index.php/home/get_area_in_out/", { device: <?php echo $id; ?>},
		function(data) {
			
			$("#div_widget10<?php echo time(); ?>").html(data);
		});
	}
	
	function get_location<?php echo time(); ?>(){
		
		$.post("<?php echo base_url(); ?>index.php/home/assets_location/id/<?php echo $id; ?>",
		 function(msg) {
			if(msg){
					clearOverlays<?php echo time(); ?>();
					var lat = msg.lat;
					var lng = msg.lng;
					var point = new google.maps.LatLng(lat, lng);
					
					var html = msg.speed+' KM/H,';
					if(msg.address != "")
						html += msg.address;
					html += '<br>'+msg.date+' ('+msg.before+' ago)';
					
					var myTextDiv = document.createElement('div');
					myTextDiv.id = 'my_text_div';
					myTextDiv.innerHTML = "<span style='font-size:12px;line-height:15px;'><b>"+html+"</b></span>";
					myTextDiv.style.color = 'white';
					
					for(i=0; i< (map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
							map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
						}
					map<?php echo time(); ?>.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv);
					
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
	
		
	function get_speed<?php echo time(); ?>(){
		$.post(
			"<?php echo base_url(); ?>index.php/home/get_speed/id/<?php echo $id; ?>",
			function(data){
					$('#speedo_meter<?php echo time(); ?>').speedometer({ percentage: data || 0 });
			}
		);
	}
	function onLoadmap<?php echo time(); ?>() {
		var mapObjmap = document.getElementById("map<?php echo time(); ?>");
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
		map<?php echo time(); ?> = new google.maps.Map(mapObjmap,mapOptionsmap);
		map<?php echo time(); ?>.enableKeyDragZoom();
		
		var html = '<?php echo $speed; ?> KM/H,';
		var address = '<?php echo $address; ?>';
		if(address != "")
			html += '<?php echo $address; ?>,';
		html += '<br><?php echo $date; ?> (<?php echo ago($date); ?> ago)';
		
		var myTextDiv = document.createElement('div');
		myTextDiv.id = 'my_text_div';
		myTextDiv.innerHTML = "<span style='font-size:12px;line-height:15px;'><b>"+html+"</b></span>";
		myTextDiv.style.color = 'red';
		
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
function get_speed_graph<?php echo time(); ?>(){
	$('#speed_chart<?php echo time(); ?>').html('');	
	$.post("<?php echo base_url(); ?>index.php/reports/speedgraph/loadData", { device: <?php echo $id; ?>, date: '<?php echo date('d.m.Y'); ?>', stime: '<?php echo date('H:i', strtotime('-3 hour')); ?>', etime: '<?php echo date('H:i'); ?>' },
	 function(response) {	
		  $('#speed_chart<?php echo time(); ?>').html('');
		  var dataLines<?php echo time(); ?> = [];  // initialize the array of lines.
		  var labels<?php echo time(); ?> = [];  // initialize array of line labels.
		  var current, i;  // some variables we'll need.
	
		if(response.XAxis.length > 0){
		  dataLines<?php echo time(); ?>.push([]);  // add an empty line.
		  labels<?php echo time(); ?>.push(response.Name);
		  for (i=0; i<response.XAxis.length; i++) {
			  dataLines<?php echo time(); ?>[0].push([response.XAxis[i], response.Speed[i]]);
		  }
	
		  var options = {
			  legend: { show: true },
			  title: '<?php echo $this->lang->line("Speed Vs Time"); ?>',
			  series: [{ label: labels<?php echo time(); ?>[0] }, { label: labels<?php echo time(); ?>[1]}],           
			  axesDefaults: {				  
			  	  pad: 1.2,
				  tickOptions: {
				  enableFontSupport: true,
				  fontSize: '9pt'               
				  }
			  },
			  seriesDefaults: { showMarker:true , trendline: { show: false }, lineWidth: 2 },
			  axes: {
				  yaxis: {min:0, max: 100, label: '<?php echo $this->lang->line("Speed"); ?>', labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
				  xaxis: {
				  	label: 'Time[30 min intervals]', 
					tickInterval:'30 minutes', 
				  	tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions:{formatString:'%I:%M %p', angle: -30}, 
					renderer: $.jqplot.DateAxisRenderer}
			  },
			  cursor:{
			  		show : true,
					zoom:true,
					tooltipOffset: 10,
					tooltipLocation: 'n'
			  },
			  highlighter: {
					sizeAdjust: 6
			  }


		  };	
		  var plot1 = $.jqplot('speed_chart<?php echo time(); ?>', dataLines<?php echo time(); ?>, options);
		  }else{
			$('#speed_chart<?php echo time(); ?>').html('<center><?php echo $this->lang->line("No Data Found"); ?></center>');
		  }
	 }, 'json');
	
	return false;	
}
function get_distance_graph<?php echo time(); ?>(){
		
	$.post("<?php echo base_url(); ?>index.php/reports/distancegraph/loadData", { device: <?php echo $id; ?>, sdate: '<?php echo date('d.m.Y', strtotime('-7 days')); ?>', edate: '<?php echo date('d.m.Y'); ?>' },
	 function(data) {
		 $("#distance_graph<?php echo time(); ?>").html('');
		 
		if(data.x_axis.length > 0){
			line = data.y_axis;
			ticks1 = data.x_axis;
			min_val = 0;
			max_val = data.x_max;
			
			plot1 = $.jqplot('distance_graph', [line], {
				title : '<?php echo $this->lang->line("Distance Travelled Vs. Date"); ?>',
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: { show: true },
					rendererOptions:{
						barWidth: '20'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						label: 'Date',
						ticks: ticks1,
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						  labelPosition:'middle',
							angle: -30
						}
					},
					yaxis: {
						label: '<?php echo $this->lang->line("Distance_Travelled"); ?>',
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
						tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						tickOptions: {
						  labelPosition:'middle',
							angle: -30
						},
						min:min_val,
						max:max_val
					}
				}
			});
		}else{
			$('#distance_graph<?php echo time(); ?>').html('<center><?php echo $this->lang->line("No_Data_Found"); ?></center>');
		}
	 }, 'json');
	
	return false;	
}

	
	function createMarker<?php echo time(); ?>(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
		var marker_options = {
			position: point,
			map: map,
			title: title};  
		if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>assets/marker-images/" + icon;}
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

  <div id="columns" style="height:50%;">
    <ul id="column1" class="column" style="white-space:0px;list-style:none">
		<li class="widget color-green" id="widget1<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Assets Details"); ?></b></h3>
			</div>
			<div id="div_widget1<?php echo time(); ?>" class="widget-content">
				<p></br><?php echo $this->lang->line("no_records_found"); ?>.</p>
			</div>
		</li>
		<li class="widget color-red" id="widget2<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Distance_today"); ?></h3>
			</div>
			<div id="div_widget2<?php echo time(); ?>" class="widget-content">
				<p></br><?php echo $this->lang->line("No Records found"); ?>.</p>
			</div>
		</li>
		<li class="widget color-blue" id="widget3<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Speed_Graph_Last_Hours"); ?></h3>
			</div>
			<div id="div_widget3<?php echo time(); ?>" class="widget-content">
				<div id="speed_chart<?php echo time(); ?>" style="margin-top:20px; margin-left:20px;"></div>
			</div>
		</li>
    </ul>
	<ul id="column2" class="column" style="white-space:0px;list-style:none">
      <li class="widget color-yellow" id="widget4<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Current Location"); ?></h3>
			</div>
			<div id="div_widget4<?php echo time(); ?>" class="widget-content">
				<div id="map<?php echo time(); ?>" style="width: 100%; height: 200px; position:relative;"></div>
			</div>
		</li>
		<li class="widget color-orange" id="widget5<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Current Speed"); ?></h3>
			</div>
			<div id="div_widget5<?php echo time(); ?>" class="widget-content">
				<div style='width:100%;text-align:center;padding-left:28%;padding-top:20px;' >
					<div id="speedo_meter<?php echo time(); ?>" style="left : 110px !important;"></div>
				</div>
			</div>
		</li>
		
		<li class="widget color-white" id="widget6<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Distance_Graph_LastDays"); ?></h3>
			</div>
			<div id="div_widget6<?php echo time(); ?>" class="widget-content">
				<div id="distance_graph<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			</div>
		</li>
		
		<li class="widget color-white" id="widget7<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Stop Report"); ?></h3>
			</div>
			<div id="div_widget7<?php echo time(); ?>" class="widget-content">
				<div id="stop_report<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			</div>
		</li>
		<li class="widget color-white" id="widget8<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Area In/Out Report"); ?></h3>
			</div>
			<div id="div_widget8<?php echo time(); ?>" class="widget-content">
				<div id="area_in_out_report<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			</div>
		</li>
		<li class="widget color-white" id="widget9<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Landmark Report"); ?></h3>
			</div>
			<div id="div_widget9<?php echo time(); ?>" class="widget-content">
				<div id="landmark_report<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			</div>
		</li>
		<li class="widget color-white" id="widget10<?php echo time(); ?>">
			<div class="widget-head">
				<h3><?php echo $this->lang->line("Distance Location"); ?></h3>
			</div>
			<div id="div_widget10<?php echo time(); ?>" class="widget-content">
				<div id="distance_wise<?php echo time(); ?>" style="margin-top:10px; margin-left:10px;"></div>
			</div>
		</li>
    </ul>
  </div>
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
<?php
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
?>