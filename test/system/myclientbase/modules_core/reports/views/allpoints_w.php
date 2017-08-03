<script type="text/javascript">
loadMarkerClusters();
loadInfoBubble();
$("#loading_top").css("display","block");
</script>
<?php

	 if(isset($_REQUEST["mode"]) && $_REQUEST["mode"]!='')
	 {
		$fmode=$_REQUEST["mode"];
		$end_date_context_menu= date("d.m.Y g:i A", time());
	 }
	 else
	 {
		$fmode='';
	 }
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
	 $ampm="";
	 $js_time_format=str_replace ("tt", "TT" ,$js_time_format);
	 if(strpos($js_time_format, 'TT'))
	 {
		$ampm="ampm:true,";
	 }
	 $prefix = time();

	$user = $this->session->userdata('user_id');	 
	$country = '';
	$sql = "SELECT mc.name as country FROM `tbl_users` tu left join mst_country mc ON mc.id = tu.country where tu.user_id = '$user'";
	$query = $this->db->query($sql);
	$row = $query->row();

	if(trim($row->country) != ''){
		$country = $row->country;
	} else {
		$SQL = "SELECT country_lati,country_longi FROM tbl_users where user_id = '$user'";
		$query = $this->db->query($SQL);
		$row = $query->row();
		if(count($row)){
			$lati =  $row->country_lati;
			$longi =  $row->country_longi;
		} else {
			$lati =  -5.794478;
		$longi =  -35.210953;
		

		}
		$lati =  -5.794478;
		$longi =  -35.210953;
		

	}

?>
<style>

#load_allpoints_grid<?php echo time(); ?>
{
	display:none !important;
}
#ui_tpicker_hour_label_all_points_sdate,#ui_tpicker_hour_label_all_points_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_minute_label_all_points_sdate,#ui_tpicker_minute_label_all_points_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#ui_tpicker_second_label_all_points_sdate,#ui_tpicker_second_label_all_points_edate
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

</style>
<script type="text/javascript">
var latArr = new Array();
var lngArr = new Array();
var htmlArr = new Array();
var ignitionArr = new Array();
var cnt =0;
var device_jq;
jQuery().ready(function (){
	
	
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid({
		url:"<?php echo base_url(); ?>index.php/reports/allpoints/loadData",
		datatype: "local",
		colNames:["<?php $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Datetime"); ?>','<?php echo $this->lang->line("Asset_Name"); ?>','<?php echo $this->lang->line("Driver Name"); ?>', '<?php echo $this->lang->line("Address"); ?>', '<?php echo $this->lang->line("Speed"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"assets_name",editable:true, index:"assets_name", width:180, align:"center", formatter:formatName},
			{name:"driver_name",editable:true, index:"driver_name", width:180, align:"center",jsonmap:"driver_name"},
			//{name:"device_id",editable:true, index:"device_id", width:100, align:"center", jsonmap:"device_id"},
			{name:"address",editable:true, index:"address", width:250, align:"center", jsonmap:"address"},
			{name:"speed",editable:true, index:"speed", width:60, align:"center", jsonmap:"speed"},
			{name:"actions",editable:true, index:"id", width:60, align:"center", jsonmap:"actions"}
		],
		rowNum:100,
		height: 'auto',
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#allpoints_pager<?php echo time(); ?>"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
			$("#allpoints_grid<?php echo time(); ?>").setGridParam({datatype: 'json'}); 
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: false, 
		sortorder: "asc",
		caption:"<?php echo $this->lang->line("All_Point_List"); ?>",
		editurl:"users/deleteData",
		jsonReader: { repeatitems : false, id: "0" }
	});

	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid("navGrid", "#allpoints_pager<?php echo time(); ?>", {add:false, edit:false, del:false, search:true}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid("navButtonAdd","#allpoints_pager<?php echo time(); ?>",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#all_points_sdate').val();
			var edate = $('#all_points_edate').val();
			var device = $('#all_points_device').val();
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+device;
			document.location = "<?php echo base_url(); ?>index.php/reports/allpoints/loadData"+qrystr;
		}
	});
	$("#all_points_device").html(assets_combo_opt);
	//$(".date").datepicker('setDate', new Date());
	cancelloading();
	$( "#all_point_pBar" ).progressbar({value: 0});
	$("#all_point_pBar").css("display","none");
	//$(".date").datepicker({dateFormat:'dd.mm.yy',changeMonth: true,changeYear: true});
	//jQuery("input:button, input:submit, input:reset").button();	
	$("#alert-dialog-allpoint").dialog({
	  autoOpen: false,
	  modal: true
	});
	$("#all_points_sdate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});
	$("#all_points_edate").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',timeFormat: '<?php echo $js_time_format; ?>',<?php echo $ampm; ?>changeMonth: true,showSecond: true,changeYear: true});

	$("#all_points_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("12:00:00 am")); ?>'));
	$("#all_points_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s",strtotime("11:59:59 pm")); ?>'));
	
	//$("#all_points_sdate").datetimepicker('setDate', "21.01.2014 01:42:42 PM");
	//$("#all_points_edate").datetimepicker('setDate', "21.01.2014 01:50:42 PM");
}); 
function formatName(cellvalue, options, rowObject){
	return device_jq;
}
function searchallpoints(){
	$('.start_stop_btn').hide();
	$("#allpoints_grid_div<?php echo time(); ?>").show();
	$("#all_pont_map<?php echo time(); ?>").hide();
	var sdate = $('#all_points_sdate').val();
	var edate = $('#all_points_edate').val();
	var device = $('#all_points_device').val();
	
	if(device == ""){
		$("#alert-dialog-allpoint").html("<?php echo $this->lang->line("Please select device"); ?>");
		$("#alert-dialog-allpoint").dialog("open");
		return false;
	}
	clearMapRoutLoading();
	$("#all_point_pBar").css("display","none");
	$("#v_map_id").removeClass("ui-state-disabled");
	$("#v_map_id").removeAttr("disabled");
	device_jq=$('#all_points_device option:selected').html();
	//$("#allpoints_list").flexOptions({params: [{name:'sdate', value: sdate},{name:'edate',value:edate},{name:'device',value:device}]}).flexReload(); 
	$("#loading_top").css("display","block");
	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid('setGridParam',{postData:{sdate:sdate, edate:edate, device:device, page:1}}).trigger("reloadGrid");
	return false;	
}

function viewOnMapAllpointnew(){
	var device = $('#fmode').val();
	//var window_text=$('#all_points_device option:selected').text();
	Poin_Cntr=0;
	totalDir=0;
	totalDir_count=0;
	if(device == ""){
		$("#alert-dialog-allpoint").html("<?php echo $this->lang->line("Please select device"); ?>");
		$("#alert-dialog-allpoint").dialog("open");
		return false;
	}
	$("#all_point_pBar").css("display","block");
	$("#v_map_id").addClass("ui-state-disabled");
	$("#v_map_id").attr("disabled","disabled");
	onLoadmapAllpoint();
	
	var start_date = $('#all_points_sdate').val();
	var end_date = $('#all_points_edate').val();

	$("#loading_top").css("display","block");
	
	$.post("<?php echo base_url(); ?>index.php/reports/allpoints/trackOnMap", { device: device, start_date: start_date, end_date: end_date },
	 function(data) {
		if(data){
			image = data.image;
			//$("#car<?php echo $prefix; ?>").css("background-image", "url(<?php echo base_url(); ?>assets/"+image+")");
			var point = new google.maps.LatLng(
				latArr[0],
				lngArr[0]
			);
			var myOptions = {
						disableAutoPan: true
						,content: $("#imgR<?php echo $prefix; ?>").html()
						,isHidden: false
						,boxStyle: {
							   textAlign: "center"
							  ,fontSize: "8pt"
							  ,width: "18px"
							 }
						,pixelOffset: new google.maps.Size(-7, 0)
						,position: point
						,closeBoxURL: ""
						,pane: "mapPane"
						,enableEventPropagation: true
					};
				
				//this will hold car div and will use to move this div on map.
				
				ib<?php echo $prefix; ?> = new InfoBox(myOptions);                
				
				ib<?php echo $prefix; ?>.open(mapmapAllpoint);
				
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
				var devText = $('#device option:selected').text();
				var distance = data.distance;
				distance=Math.round(distance*100)/100;
				var txt = "Distance : " + distance + " KM";
				
				//setTimeout(function(){
					viewTrackAllpoint(txt);
					//alert(latArr.length);
				//},1000);
				
			}else{
		
				$("#alert-dialog-allpoint").html("<?php echo $this->lang->line("No_Data_Found"); ?>");
				$("#alert-dialog-allpoint").dialog('open');
				clearMapRoutLoading();
				$("#all_point_pBar").css("display","none");
				$("#v_map_id").removeClass("ui-state-disabled");
				$("#v_map_id").removeAttr("disabled");
			}
			$("#loading_top").css("display","none");
		}
	 }, 'json'
	);
}
var navigation_type = '';
function viewOnMapAllpoint(){
	var device = $('#all_points_device').val();
	 navigation_type = $('#navigation_type').val();
	//alert(navigation_type);
	var window_text=$('#all_points_device option:selected').text();
	Poin_Cntr=0;
	totalDir=0;
	totalDir_count=0;
	if(device == ""){
		$("#alert-dialog-allpoint").html("<?php echo $this->lang->line("Please select device"); ?>");
		$("#alert-dialog-allpoint").dialog("open");
		return false;
	}
	$("#all_point_pBar").css("display","block");
	$("#v_map_id").addClass("ui-state-disabled");
	$("#v_map_id").attr("disabled","disabled");
	onLoadmapAllpoint();
	
	var start_date = $('#all_points_sdate').val();
	var end_date = $('#all_points_edate').val();

	$("#loading_top").css("display","block");
	
	$.post("<?php echo base_url(); ?>index.php/reports/allpoints/trackOnMap", { device: device, start_date: start_date, end_date: end_date },
	 function(data) {
		if(data){
			image = data.image;
			//$("#car<?php echo $prefix; ?>").css("background-image", "url(<?php echo base_url(); ?>assets/"+image+")");
			var point = new google.maps.LatLng(
				latArr[0],
				lngArr[0]
			);
			var myOptions = {
						disableAutoPan: true
						,content: $("#imgR<?php echo $prefix; ?>").html()
						,isHidden: false
						,boxStyle: {
							   textAlign: "center"
							  ,fontSize: "8pt"
							  ,width: "18px"
							 }
						,pixelOffset: new google.maps.Size(-7, 0)
						,position: point
						,closeBoxURL: ""
						,pane: "mapPane"
						,enableEventPropagation: true
					};
				
				//this will hold car div and will use to move this div on map.
				
				ib<?php echo $prefix; ?> = new InfoBox(myOptions);                
				
				ib<?php echo $prefix; ?>.open(mapmapAllpoint);
				
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
				var devText = $('#device option:selected').text();
				var distance = data.distance;
				distance=Math.round(distance*100)/100;
				var txt = "Distance : " + distance + " KM";
				
				//setTimeout(function(){
					viewTrackAllpoint(txt);
					//alert(latArr.length);
				//},1000);
				
			}else{
				$("#alert-dialog-allpoint").html("<?php echo $this->lang->line("No_Data_Found"); ?>");
				$("#alert-dialog-allpoint").dialog('open');
				clearMapRoutLoading();
				$("#all_point_pBar").css("display","none");
				$("#v_map_id").removeClass("ui-state-disabled");
				$("#v_map_id").removeAttr("disabled");
			}
			$("#loading_top").css("display","none");
		}
	 }, 'json'
	);
}
</script>
<script type="text/javascript" charset="utf-8">

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

function onLoadmapAllpoint() {
	$("#allpoints_grid_div<?php echo time(); ?>").hide();
	$("#all_pont_map<?php echo time(); ?>").show();
	directionsService = new google.maps.DirectionsService();
	var mapObjmap = document.getElementById("all_pont_map<?php echo time(); ?>");
	if (mapObjmap != 'undefined' && mapObjmap != null) {
	if(navigation_type == '1'){
	mapOptionsmapAllpoint = {
		zoom: 5,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};
	}
	else{
	mapOptionsmapAllpoint = {
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		streetViewControl:true,
		overviewMapControl:true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR}
	};

	
	}

	<?php if($country == '') { ?>

	mapOptionsmapAllpoint.center = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);

	<?php } ?>

	mapmapAllpoint = new google.maps.Map(mapObjmap,mapOptionsmapAllpoint);
	allpointBounds = new google.maps.LatLngBounds();
		
		<?php if($country != '') { ?>
		var c_geocoder = new google.maps.Geocoder();
		c_geocoder.geocode( {address:'<?php echo $country; ?>'}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				mapmapAllpoint.setCenter(results[0].geometry.location);
				mapmapAllpoint.fitBounds(results[0].geometry.viewport);
			}
		});
		<?php } ?>

	}
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
		  minWidth : 250,
		  minHeight : 75
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
		html = '<div style="">'+html+'</div>';
		var infowindow = new google.maps.InfoWindow({content: html, minWidth:100, minHeight:75});
		google.maps.event.addListener(new_marker, 'click', function() {
		  infowindow.open(map,new_marker);
		});
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
	/*
	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		allpointBounds.extend(point);
		var image = '';
		var shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
		if(i == 0){	
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-START.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",html[i], img, shadow, "sidebar_map", '' ));
		}
		else if(i == (lat.length-1)){
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-END.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",html[i], img, shadow, "sidebar_map", '' ));
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
		
			var mkr = arrowMarkerAllpointFunction(mapmapAllpoint, point, "Marker Description", html[i], img)
			markersmapAllpoint.push(mkr);
			arrowMarkerAllpoint.push(mkr);
		}
				
		if(i > 0){
		
			
		}
  	}*/
	/*
	markerClusterAllpoint = new MarkerClusterer(mapmapAllpoint, arrowMarkerAllpoint, mcOptionsAllpoint);
	if(lat.length > 0){
		mapmapAllpoint.fitBounds(allpointBounds);
	}
	var j = 0;
	if(lat.length > 9){
		for(i=0; i<lat.length; i++){
			if(i == (lat.length) - 1){
				endP = new google.maps.LatLng(lat[i], lng[i]);	
				calcRouteAllpoint(startP, endP, i);
			}else{
				if(j == 10){
					j = 0;
					
				}
				if(j == 0){
					startP = new google.maps.LatLng(lat[i], lng[i]);
				}
				else if(j == 9){
					endP = new google.maps.LatLng(lat[i], lng[i]);			
					calcRouteAllpoint(startP, endP, i);
					i = i-1;
					
				}else{
					wayptsAllpoint.push({
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
				calcRouteAllpoint(startP, endP, i);
			}else{
				wayptsAllpoint.push({
					location:new google.maps.LatLng(lat[i], lng[i]),
					stopover:true});		
			}
		}
	}*/
	if(navigation_type == '1'){
	var myTextDiv = document.createElement('div');
	myTextDiv.id = 'my_text_div';
	myTextDiv.innerHTML = '<Span id="distance_txt_all_p" style="color:black;background-color:rgba(255,255,255,0.7);display:none">'+devText+'</span>';
	myTextDiv.style.color = 'white';
	mapmapAllpoint.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv);
	}
	//alert(latArr.length);
	if(navigation_type == '1'){
	totalDir=Math.floor(latArr.length/1);
	}else{
	totalDir=Math.floor(latArr.length/9);
	}
	call_start_new_line(0);
	
	
}
function call_start_new_line(arr_i)
{	
		//alert("Total Directions->"+totalDir+", Count->"+totalDir_count+", Arr_i->"+arr_i);
		if($("#all_point_pBar").css("display") != "block" && $("#all_point_pBar").css("display") != "inline-block" )
		{
			//alert($("#all_point_pBar").css("display"));
			clearMapRoutLoading();
			return false;
		}
		var point = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
		allpointBounds.extend(point);
		var image = '';
		var shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
		if(arr_i == 0){
			mapmapAllpoint.setCenter(point);
			//alert("0->"+arr_i);
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-START.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
			markersmapAllpoint.push(createMarkerAllpoint(mapmapAllpoint, point,"Marker Description",htmlArr[arr_i], img, shadow, "sidebar_map", '' ));
		}
		else if(arr_i == (latArr.length-1)){
			//alert("1-="+arr_i);
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-END.png';
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
		
			var mkr = arrowMarkerAllpointFunction(mapmapAllpoint, point, "Marker Description", htmlArr[arr_i], img)
			markersmapAllpoint.push(mkr);
			arrowMarkerAllpoint.push(mkr);
		}
		//console.log(totalDir);
		//console.log(totalDir_count);
		if((totalDir - 1) > totalDir_count)
		{
			//alert("4->"+arr_i);
			
			var point1 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			wayptsAllpoint=[];
			Poin_Cntr++;
			if(navigation_type == '1'){
			/*for(i=0;i<=7;i++)
			{
			arr_i++;
			wayptsAllpoint.push({
				location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
				stopover:true
				});
			}*/
			}else{
			for(i=0;i<=7;i++)
			{
			arr_i++;
			wayptsAllpoint.push({
				location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
				stopover:true
				});
			}
			
			
			}
			arr_i++;
			var point2 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			if(navigation_type == '1'){
			//calcRouteAllpoint(point1,point2,Poin_Cntr);
			}
			else{
			calcRouteAllpoint(point1,point2,Poin_Cntr);
			}
			if(latArr.length != arr_i)
				setTimeout(function(){call_start_new_line(arr_i)},0);
			totalDir_count++;
			
			current_all_p=Number(totalDir_count);
			percentage_all_p = Number(current_all_p/(totalDir)*100)-Number(0.99/(totalDir)*100);
			val_all_p=100-percentage_all_p;
			$("#all_point_pBar").progressbar("value" , percentage_all_p);
		}else if(totalDir == totalDir_count)
		{
			
			//alert("5->"+arr_i);
			var total_ar=latArr.length-arr_i;
			if(total_ar>=2)
			{
			$("#all_point_pBar").progressbar("value" , 99.99);
			var point1 = new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]);
			wayptsAllpoint=[];
			Poin_Cntr++;
			/*if(total_ar>2)
			{
				//alert("6->"+arr_i);
				for(i=0;i<=total_ar-2;i++)
				{
				arr_i++;
				wayptsAllpoint.push({
								location:new google.maps.LatLng(latArr[arr_i], lngArr[arr_i]),
								stopover:true});
				}
			}*/
			arr_i++;
			var point2 = new google.maps.LatLng(latArr[latArr.length-1], lngArr[latArr.length-1]);
			calcRouteAllpoint(point1,point2,Poin_Cntr);
			if(latArr.length != arr_i)
				setTimeout(function(){call_start_new_line(arr_i)},Timer_counter);
			//totalDir_count++;
			$("#all_point_pBar").css("display","none");
				setTimeout(function(){
					mapmapAllpoint.fitBounds(allpointBounds);
					markerClusterAllpoint = new MarkerClusterer(mapmapAllpoint, arrowMarkerAllpoint, mcOptionsAllpoint);
					var txt = "Distance : " + distance_all_total.toFixed(2) + " KM";
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
			$("#all_point_pBar").css("display","none");
			var txt = "Distance : " + distance_all_total.toFixed(2) + " KM";
			$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
			markerClusterAllpoint = new MarkerClusterer(mapmapAllpoint, arrowMarkerAllpoint, mcOptionsAllpoint);
		//	alert(arr_i+"of -> total"+latArr.length+", Total"+totalDir+", CountTotal"+totalDir_count);
			loop<?php echo $prefix; ?>=0,j,x;
			rrr = 0;
			if(navigation_type == '1')
			calcRouteAllpointNew();
			
			setTimeout(function(){
					$("#tour_start_btn").show();
					//calltest<?php echo $prefix; ?>();
				},1000);
			
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
				if(navigation_type == '1'){
				//directionsDisplayAllpoint[pointCounter].setDirections(response);
				}
				else{
				directionsDisplayAllpoint[pointCounter].setDirections(response);
				}
				distance_all_p = Number((response.routes[0].legs[0].distance.value)/1000);
				distance_all_total += Math.round(distance_all_p*100)/100;
				//var txt = device_jq + " Distance : " + distance_all_total.toFixed(2) + " KM";
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
var rrr = 0; 
function calcRouteAllpointNew(){
		lt1<?php echo $prefix; ?>=latArr[rrr];
		ln1<?php echo $prefix; ?>=lngArr[rrr];
		lt2<?php echo $prefix; ?>=latArr[rrr+1];
		ln2<?php echo $prefix; ?>=lngArr[rrr+1];
		
		var sl = new google.maps.LatLng(lt1<?php echo $prefix; ?>, ln1<?php echo $prefix; ?>);
		var el = new google.maps.LatLng(lt2<?php echo $prefix; ?>, ln2<?php echo $prefix; ?>);
		
		var color1 = "#FF0000";
		var polyline = new google.maps.Polyline({
						path: [sl,el],
						strokeColor: color1,
						strokeOpacity: 0.7
					});
		polyline.setMap(mapmapAllpoint);
		distance_all_total += distancecalc(lt1<?php echo $prefix; ?>,ln1<?php echo $prefix; ?>, lt2<?php echo $prefix; ?>, ln2<?php echo $prefix; ?> );
		//console.log(rrr);
		//console.log(latArr.length);
		//console.log(distance_all_total.toFixed(2));
		var txt = "Distance : " + distance_all_total.toFixed(2) + " KM";
		$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");
		if(rrr < (latArr.length-2)){
			rrr++;
			setTimeout(function(){
			calcRouteAllpointNew();
			}, 0);
		}
		
		/*directionsDisplayAllpoint[rrr] = new google.maps.DirectionsRenderer(rendererOptionsAllpoint);
		directionsDisplayAllpoint[rrr].setMap(mapmapAllpoint);
		var request = {
			origin:sl, 
			destination:el,
			waypoints: wayptsAllpoint,
			optimizeWaypoints: true,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplayAllpoint[rrr].setDirections(response);
				distance_all_p = Number((response.routes[0].legs[0].distance.value)/1000);
				distance_all_total += Math.round(distance_all_p*100)/100;
				var txt = "Distance : " + distance_all_total.toFixed(2) + " KM";
				$("#distance_txt_all_p").html("&nbsp;&nbsp;"+txt+"&nbsp;&nbsp;");

			}
			if(rrr < (latArr.length-1)){
				rrr++;
				setTimeout(function(){
				calcRouteAllpointNew();
				}, 0);
			}
		});	
		wayptsAllpoint = [];*/
  }
  
  function distancecalc(lat1, lon1, lat2, lon2) {
	var R = 6371;
	var dLat = (lat2 - lat1) * Math.PI / 180;
	var dLon = (lon2 - lon1) * Math.PI / 180;
	var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
			Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
			Math.sin(dLon / 2) * Math.sin(dLon / 2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
	var d = R * c;
	return d;
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
	for(i=0; i< (mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
		mapmapAllpoint.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
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
function clearMapRoutLoading()
{
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
distance_all_total = 0;

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

var loop<?php echo $prefix; ?>=0,j,x;
var ib<?php echo $prefix; ?>;
var map_focus_center<?php echo $time; ?> = true;
var reloadMap_bool=false;
$(document).ready(function(){
	image<?php echo $prefix; ?>=$("#car<?php echo $prefix; ?>");
});
var lll = 0;
function calltest<?php echo $prefix; ?>()
{
	//$("#helpRightClick<?php echo $prefix; ?>").append(".2");	
	//alert("2. Calltest Called " + reloadMap_bool);
	//alert(" CALL - "+ib<?php echo $prefix; ?>.getPosition());
	lt1<?php echo $prefix; ?>=latArr[loop<?php echo $prefix; ?>];
	ln1<?php echo $prefix; ?>=lngArr[loop<?php echo $prefix; ?>];
	lt2<?php echo $prefix; ?>=latArr[loop<?php echo $prefix; ?>+1];
	ln2<?php echo $prefix; ?>=lngArr[loop<?php echo $prefix; ?>+1];
	if((loop<?php echo $prefix; ?>+1)<latArr.length)
	{
		if(loop<?php echo $prefix; ?>!=0 && latArr[loop<?php echo $prefix; ?>-1]==latArr[loop<?php echo $prefix; ?>] && lngArr[loop<?php echo $prefix; ?>-1]==lngArr[loop<?php echo $prefix; ?>])
		{
			loop<?php echo $prefix; ?>+=2;
		}
		else
		{
			loop<?php echo $prefix; ?>++;
		}
//		i++;
		if(map_focus_center<?php echo $time; ?>==true){
				//mapmap<?php echo $prefix; ?>.setCenter(latlngs[index]);
				//	mapmap<?php echo $prefix; ?>.setCenter(new google.maps.LatLng(latArr[loop<?php echo $prefix; ?>], lngArr[loop<?php echo $prefix; ?>]));
			}
	
		ang=(Math.atan2(ln2<?php echo $prefix; ?>-ln1<?php echo $prefix; ?>,lt2<?php echo $prefix; ?>-lt1<?php echo $prefix; ?>)*180)/Math.PI;
		image<?php echo $prefix; ?>.rotate(ang);	
	 
		ib<?php echo $prefix; ?>["content_"]=$("#imgR<?php echo $prefix; ?>").html();
		ib<?php echo $prefix; ?>.open(mapmapAllpoint);
		
		test<?php echo $prefix; ?>(lt1<?php echo $prefix; ?>,ln1<?php echo $prefix; ?>,lt2<?php echo $prefix; ?>,ln2<?php echo $prefix; ?>);
		
	}
	/*else
	{
		alert("Destination Arrived.!!");
	}
	*/
	
}
function test<?php echo $prefix; ?>(lat1,lng1,lat2,lng2)
{		 
		if(t_stop == true){
			return false;
		}
		//$("#helpRightClick<?php echo $prefix; ?>").append(".3");
		fromLat = parseFloat(lat1);
          fromLng = parseFloat(lng1);
          toLat = parseFloat(lat2);
          toLng = parseFloat(lng2);
		  //alert("TEST - >"+ib<?php echo $prefix; ?>.getPosition());
          // store a LatLng for each step of the animation
          frames<?php echo $prefix; ?> = [];
          for (var percent = 0; percent < 1; percent += 0.02) {
            curLat = fromLat + percent * (toLat - fromLat);
            curLng = fromLng + percent * (toLng - fromLng);
            frames<?php echo $prefix; ?>.push(new google.maps.LatLng(curLat, curLng));
          }

          move<?php echo $prefix; ?> = function(ib<?php echo $prefix; ?>, latlngs, index, wait, newDestination) {
				
			//if(reloadMap_bool==false){
			//	alert("5 if");
				
				ib<?php echo $prefix; ?>.setPosition(latlngs[index]);
				if(map_focus_center<?php echo $time; ?>==true){
					mapmapAllpoint.setCenter(latlngs[index]);
					//alert("5 if true");
				}
				if(index != latlngs.length-1) {
				  // call the next "frame" of the animation
			//	  alert("6 if true");
					if(reloadMap_bool==false){
					  setTimeout(function() {
						move<?php echo $prefix; ?>(ib<?php echo $prefix; ?>, latlngs, index+1, wait, newDestination);
					  }, wait);
					}else{
						setTimeout(function(){
							mapmapAllpoint.setCenter(lastPoint<?php echo $prefix; ?>);
						},1000);
					}
				  
				}
				else {
			//	alert("6 if false");
				  ib<?php echo $prefix; ?>.position = ib<?php echo $prefix; ?>.destination;
				  ib<?php echo $prefix; ?>.destination = newDestination;
				  //this will call calltest when first point to second point animation done.
				 
					calltest<?php echo $prefix; ?>();
				
				}
				
				if(loop<?php echo $prefix; ?> == (latArr.length-1)){
					loop<?php echo $prefix; ?>=0,j,x;
					tour_stop();
				}
			/*}else{
				//alert(latArr.length);
				
				setTimeout(function(){
					reloadMap_bool=false;
				},500);				
			}*/
          }

          // begin animation, send back to origin after completion
		  
	
		move<?php echo $prefix; ?>(ib<?php echo $prefix; ?>, frames<?php echo $prefix; ?>, 0, 20, ib<?php echo $prefix; ?>.position);
		
}
var t_stop = false;
function tour_stop(){
	$("#tour_stop_btn").hide();
	$("#tour_start_btn").show();
	t_stop = true;
}
function tour_start(){
	t_stop = false;
	$("#tour_stop_btn").show();
	$("#tour_start_btn").hide();
	
	calltest<?php echo $prefix; ?>();
}
</script>
<script>
jQuery().ready(function (){
	if($('#fmode').val()!='')
	{
	var enddate= document.getElementById("end_date_context_menu").value;
	document.getElementById("all_points_edate").value = enddate;
	var deviceid = $('#fmode').val();
	$('#all_points_device').val(deviceid);
	viewOnMapAllpointnew();
	}
	else
	{
	}
});
</script>
<?php
	$timestamp = strtotime("+2 day");
	$tomorrow=date($date_format." ".$time_format,$timestamp);
?>

<?php
//$image_type = "car.png";
?>
<div style="display:none">
<div id="imgR<?php echo $prefix; ?>">
<div id="car<?php echo $prefix; ?>" style="height:32px;width:17px;color: white; background-image:url(<?php echo base_url(); ?>assets/<?php echo $image_type; ?>); font-family: 'Lucida Grande', 'Arial', sans-serif;font-size: 10px;text-align: center; white-space: nowrap;margin-top:-20px;">
</div>
</div>
</div>
<div id="allpoints_list_div">
<form onsubmit="return searchallpoints();">
<table border="5" width="100%" class="formtable" style="margin-bottom: 5px;">
	<input type="hidden" id="fmode" name="fmode" value="<?php echo $fmode;?>">
	<input type="hidden" id="end_date_context_menu" name="end_date_context_menu" value="<?php echo $end_date_context_menu;?>">
	<tr>
		<td width="10%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="all_points_sdate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="" readonly="readonly"/></td>
		<td width="10%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="all_points_edate" class="date text ui-widget-content ui-corner-all" style="width:150px" value="" readonly="readonly"/></td>
		<td width="6%">Navigation Type :</td><td width="10%"><select name="navigation_type" id="navigation_type" class="select ui-widget-content ui-corner-all"><option value="1">Point To Point</option</select><option value="2">Google Road</option></td>
		<td width="5%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="10%"><select name="device" id="all_points_device" class="select ui-widget-content ui-corner-all"></select></td>
	</tr>
	<tr>
		<td align="center" colspan="6"><input type="submit" value="<?php echo $this->lang->line("grid_view"); ?>"/>
		<input id="v_map_id" onclick="viewOnMapAllpoint()" type="button" value="<?php echo $this->lang->line("map_view"); ?>"/>
		<!-- input id="tour_stop_btn" class="start_stop_btn" style="display:none;" onclick="tour_stop()" type="button" value="Stop"/>
		<input id="tour_start_btn" class="start_stop_btn" style="display:none;" onclick="tour_start()" type="button" value="Start"/ -->
        </td>
        </tr></table>
</form> 
<div id="allpoints_grid_div<?php echo time(); ?>">
<table id="allpoints_grid<?php echo time(); ?>" class="jqgrid"></table>
<div id="allpoints_pager<?php echo time(); ?>"></div>
</div>
</div>
<div id="alert-dialog-allpoint" title=""></div>
<div id="all_point_pBar" title="" style="text-align:center;display:none"><span style="position:absolute">Loading Points</span></div>
<div id="all_pont_map<?php echo time(); ?>" style="width: 100%; height: 90%; position:relative;"></div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34256255-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>