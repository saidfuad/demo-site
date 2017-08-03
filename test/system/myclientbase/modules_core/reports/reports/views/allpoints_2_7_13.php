<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Search","Export");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'51');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	//print_r($data);

?>
<script type="text/javascript">
loadMarkerClusters();
loadInfoBubble();
$("#loading_top").css("display","block");
</script>
<?php
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
		colNames:["<?php $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Datetime"); ?>','<?php echo $this->lang->line("Asset_Name"); ?>', '<?php echo $this->lang->line("Address"); ?>', '<?php echo $this->lang->line("Speed"); ?>', '<?php echo $this->lang->line("View_on_Map"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"add_date",editable:true, index:"add_date", width:150, align:"center", jsonmap:"add_date", formatter: 'date', formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"<?php echo $date_format; ?> <?php echo $time_format; ?>"}},
			{name:"assets_name",editable:true, index:"assets_name", width:180, align:"center", formatter:formatName},
			//{name:"device_id",editable:true, index:"device_id", width:100, align:"center", jsonmap:"device_id"},
			{name:"address",editable:true, index:"address", width:250, align:"center", jsonmap:"address"},
			{name:"speed",editable:true, index:"speed", width:60, align:"center", jsonmap:"speed"},
			{name:"actions",editable:true, index:"id", width:60, align:"center", jsonmap:"actions"}
		],
		rowNum:10,
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
<?php
	if(in_array('Search',$data))
		$Search = "true";
	else
		$Search = "false";	
	?>
	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid("navGrid", "#allpoints_pager<?php echo time(); ?>", {add:false, edit:false, del:false, search:<?php echo $Search; ?>}, {}, {}, {}, {multipleSearch:false});
	<?php
	if(in_array('Export',$data)){
	?>
	jQuery("#allpoints_grid<?php echo time(); ?>").jqGrid("navButtonAdd","#allpoints_pager<?php echo time(); ?>",{caption:"<?php echo $this->lang->line("Export"); ?>",
		onClickButton:function(){
			var sdate = $('#all_points_sdate').val();
			var edate = $('#all_points_edate').val();
			var device = $('#all_points_device').val();
			var qrystr ="/export?sdate="+sdate+"&edate="+edate+"&device="+device;
			document.location = "<?php echo base_url(); ?>index.php/reports/allpoints/loadData"+qrystr;
		}
	});
	<?php } ?>
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
	
	//var dt = new Date(year, month, day, hours, minutes, seconds, milliseconds);
	//var dt = new Date(<?php echo date("Y").",".date("m").",".date("d").",".date("H").",".date("i").",".date("s");?>);
	//alert('<?php echo date("Y").",".date("m").",".date("d").",".date("H").",".date("i").",".date("s");?>');
	//$("#all_points_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#all_points_sdate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	$("#all_points_edate").datetimepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	
	//$("#all_points_sdate").val('<?php echo date($date_format." ".$time_format);?>');
	//$("#all_points_edate").val('<?php echo date($date_format." ".$time_format);?>');
}); 
function formatName(cellvalue, options, rowObject){
	return device_jq;
}
function searchallpoints(){

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

function viewOnMapAllpoint(){
	var device = $('#all_points_device').val();
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
				var txt = devText + " Distance : " + distance + " KM";
				
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

	mapOptionsmapAllpoint = {
		zoom: 7,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmapAllpoint.center = new google.maps.LatLng(
		22.297744,
		70.792444
	);
	
	mapmapAllpoint = new google.maps.Map(mapObjmap,mapOptionsmapAllpoint);
	allpointBounds = new google.maps.LatLngBounds();
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
function saveInspection_allpoint(trackId){
	$("#loading_top").css("display","block");
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/live/addToInspection/id/"+trackId,
        dataType: "json",
        success: function(data){
			if(data){
				$("#alert_dialog").html("<?php echo $this->lang->line("Record Stored Successfully"); ?>");
				$("#alert_dialog").dialog("open");
			}else{
				$("#alert_dialog").html("<?php echo $this->lang->line("Error Storing Record"); ?>");
				$("#alert_dialog").dialog("open");
			}
			$("#loading_top").css("display","none");
	    },
		error: function(request, status, err) {
           $("#loading_top").css("display","none");
		   $("#alert_dialog").html("<?php echo $this->lang->line("Error Storing Record"); ?>");
			$("#alert_dialog").dialog("open");
        }
	});
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
	/*if(html!=''){
		html = '<div style="height:100px;">'+html+'</div>';
		var infowindow = new google.maps.InfoWindow({content: html, maxWidth:100});
		google.maps.event.addListener(new_marker, 'click', function() {
		  infowindow.open(map,new_marker);
		});
	}*/
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
	mapmapAllpoint.controls[google.maps.ControlPosition.TOP_LEFT].push(myTextDiv);
	//alert(latArr.length);
	totalDir=Math.floor(latArr.length/9);
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
			if(totalDir > totalDir_count)
			{
				for(xi=arr_i;xi<=arr_i+7;xi++)
				{
					if(ignitionArr[xi]==0){
						alert('zero');
						img = "http://vts.nkonnect.com/assets/marker-images/kml-RED-END.png";
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
			$("#all_point_pBar").progressbar("value" , percentage_all_p);
		}else if(totalDir == totalDir_count && latArr.length > arr_i && totalDir!=0)
		{
			//alert("5->"+arr_i);
			var total_ar=latArr.length-arr_i;
			if(total_ar>=2)
			{
			$("#all_point_pBar").progressbar("value" , 99.99);
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
			$("#all_point_pBar").css("display","none");
				setTimeout(function(){
					mapmapAllpoint.fitBounds(allpointBounds);
					markerClusterAllpoint = new MarkerClusterer(mapmapAllpoint, arrowMarkerAllpoint, mcOptionsAllpoint);
					var txt = device_jq + " Distance : " + distance_all_total.toFixed(2) + " KM";
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
			var txt = device_jq + " Distance : " + distance_all_total.toFixed(2) + " KM";
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
<?php
	$timestamp = strtotime("+2 day");
	$tomorrow=date($date_format." ".$time_format,$timestamp);
?>

<div id="allpoints_list_div">
<form onsubmit="return searchallpoints()">
<table border="5" width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="15%"><?php echo $this->lang->line("Start"); ?> : <input type="text" name="sdate" id="all_points_sdate" class="date text ui-widget-content ui-corner-all" style="width:180px" value="<?php echo date($date_format." ".$time_format); ?>" readonly="readonly"/></td>
		<td width="15%"><?php echo $this->lang->line("End"); ?> : <input type="text" name="edate" id="all_points_edate" class="date text ui-widget-content ui-corner-all" style="width:180px" value="<?php echo $tomorrow; ?>" readonly="readonly"/></td>
		<td width="3%"><?php echo $this->lang->line("Assets"); ?> :</td><td width="20%"><select name="device" id="all_points_device" class="select ui-widget-content ui-corner-all"></select></td>
	</tr>
	<tr>
		<td align="center" colspan="4"><input type="submit" value="<?php echo $this->lang->line("grid_view"); ?>"/>
		<input id="v_map_id" onclick="viewOnMapAllpoint()" type="button" value="<?php echo $this->lang->line("map_view"); ?>"/>
        </td>
        </tr></table>
</form> 
<div id="allpoints_grid_div<?php echo time(); ?>">
<table id="allpoints_grid<?php echo time(); ?>" class="jqgrid"></table>
<div id="allpoints_pager<?php echo time(); ?>"></div>
</div>
</div>
<div id="alert-dialog-allpoint" title=""></div>
<div id="all_point_pBar" title="" style="text-align:center;display:none"><span style="position:absolute"><?php echo $this->lang->line("Loading Points"); ?></span></div>
<div id="all_pont_map<?php echo time(); ?>" style="width: 100%; height: 90%; position:relative;"></div>
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