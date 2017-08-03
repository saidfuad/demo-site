<script type="text/javascript">

var markersmap  = [];
var sidebar_htmlmap  = '';
var marker_htmlmap  = [];
var to_htmlsmap  = [];
var from_htmlsmap  = [];
var polylinesmap = [];
var polylineCoordsmap = [];
var mapmap = null;
var mapOptionsmap;
var dashboardMap = null;
var dMap = null;
var dashboardMarkers = [];
var dLabelArr = [];
var dashboardBounds;
var dashboardChecked = 0;

var polyVarr = [];
var labelArr = [];
var mbounds;

var dArr = [];
var directionsDisplay;
var selectedGroup = '';
var loadGrid = 0;

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
function clearSelection(){
	if (markers) {
		for (i in markers) {
		  markers[i].setMap(null);
		  path.removeAt(i);
		  markers.splice(i, 1);
		}
	  }
}

function calcRoute(s1, e1, mapmap){		
		directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		directionsDisplay.setMap(mapmap);
		var request = {
			origin:s1, 
			destination:e1,
			avoidHighways: true,
			avoidTolls: true,
			provideRouteAlternatives: false,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay.setDirections(response);
				distance = (response.routes[0].legs[0].distance.value)/1000;
				distance = distance.toFixed(2);
				var myTextDiv = document.createElement('div');
				//myTextDiv.id = 'my_text_div';
				myTextDiv.innerHTML = '<h2 style="color:black;background-color:rgba(255,255,255,0.7);padding:3px">'+distance + ' KM</h2>';
				myTextDiv.style.color = 'white';
				mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv);
			}
		});	
  }

function createMarker(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo, mid){
	
	var marker_options = {
		position: point,
		map: map,
		optimized: false,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var i;
	var new_marker = new google.maps.Marker(marker_options);
	var infowindow = new google.maps.InfoWindow();
		google.maps.event.addListener(new_marker, 'click', (function(new_marker, i) {
			return function() {
			  infowindow.setContent(html);
			  infowindow.open(map, new_marker);
			}
		})(new_marker, i));
	return new_marker;  
}

/*
// Commented by kunal
function createMarker(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo, mid){
	
	var marker_options = {
		position: point,
		map: map,
		optimized: false,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var i;
	var new_marker = new google.maps.Marker(marker_options);
	
	new_marker.ast_id = 'mini_' + mid;
	
	var infoBubble = new InfoBubble({
          map: map,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 2,
		  minWidth : 200
        });

	var div1 = document.createElement('DIV');
	div1.innerHTML = html;
	
	var div2 = document.createElement('DIV');
	div2.setAttribute("id", new_marker.ast_id);
	div2.setAttribute("class", 'minimap');
	
	infoBubble.addTab('Tab 1', div2);
	infoBubble.addTab('Tab 2', div1);
	
	google.maps.event.addListener(new_marker, 'click', function() {
		
	  if (!infoBubble.isOpen()) {
		infoBubble.open(map, new_marker);
		setTimeout(function () { 
			var mapOptions = {
				zoom: 18,
				center:new google.maps.LatLng(point.lat().toFixed(6),point.lng().toFixed(6)),
				disableDefaultUI: true
			  }
			new_marker.detailMap = new google.maps.Map(document.getElementById(new_marker.ast_id),mapOptions);
			//new_marker.dcreated = true;								
			var marker = new google.maps.Marker({
				  position: new google.maps.LatLng(point.lat().toFixed(6),point.lng().toFixed(6)),
				  map: new_marker.detailMap,
				  title: 'Hello World!'
			  });
			
		  }, 500);
		 }
	});
	
	return new_marker;  
}
*/

function createMarkerMapAll(map, point, html, content){
	var i;
	var new_marker = new RichMarker({
          map: map,
          position: point,
          content: content
        });
	var infowindow = new google.maps.InfoWindow();
		google.maps.event.addListener(new_marker, 'click', (function(new_marker, i) {
			return function() {
			  infowindow.setContent(html);
			  infowindow.open(map, new_marker);
			}
		})(new_marker, i));
	return new_marker;  
}
function createMarker_old(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		optimized: false,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		
		<?php /*
		// Commented By Kunal.
		
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(html);
		*/ ?>
		
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
		<?php /*	
		// Commented By Kunal
		  update_timeout = setTimeout(function(){
				infowindow.open(map,new_marker);
			}, 200); 
		*/ ?>			
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
function viewLocation(lat, lng, html){
	clearOverlaysMain();
	var point = new google.maps.LatLng(lat, lng);
	var text = "<div style='font-size:12px;height:130px;width:200px;'> " + html[i] + "</div>";
	markersmap.push(createMarker(mapmap, point,"Marker Description",text, '', '', "sidebar_map", '' ));
	
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);

}
function viewTrack(lat, lng, html){
	clearOverlaysMain();
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
function clearDirection(){
	for(i=0; i< (mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
		mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
	}
	directionsDisplay.setMap(null);
}
function clearOverlaysMain() {
  if(directionsDisplay != undefined){
	clearDirection();
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
function profile_div_close(){
	$("#Model_main").hide();
	$("#model").hide();	
}

function profile_div(){
	$("#Model_main").show();
	$("#model").show();
	$('#profile').hide();	
}	
function user_change(val,id){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	if(val!="")
	{
		$.post("<?php echo base_url(); ?>/index.php/tree/get_assets_selecteds/usr_id/"+val+"/assets_ids/"+selected_assets_ids,function(result)
		{
			//$("#loading_dialog").dialog("close");	
			$("#loading_top").css("display","none");
			if(result!=""){
				$("#cmb_assets").html(result);
			}
		});
	}

}
function setLanguage(lang){
	$.post(
		"<?php echo base_url(); ?>/index.php/tree/setLanguage",{'lang':lang},
		function(result)
		{
			window.location.reload();
		}
	);
}
function changeGroupCombo(val){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	if(val!="")
	{
		$.post("<?php echo base_url(); ?>/index.php/tree/get_assets_selecteds_grp/grp_id/"+val+"/assets_ids/"+selected_assets_ids,function(result)
		{
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
			if(result!=""){
				$("#cmb_assets_grp").html(result);
				//$("#add_to_user").html(result.user_combo);
			}
		});
	}
}
function subMenu(id){
	<?php /*$(".submenu").hide();
	$("#"+id).show();
	*/ ?>
	$(".ui-layout-west").html($('#'+id).html());
	if(myLayout.state.west.isClosed) myLayout.open( "west" );
	
	$("#layout1").trigger("click");
}
function print_window()
{
	window.print();
} 
function maximize(obj){
	if(obj.alt=="max"){
		$.each('north,west,south'.split(','), function(){myLayout.close(this);});
		$(obj).attr("alt", 'min');
		$(obj).attr("title", '<?php echo $this->lang->line("Minimize"); ?>');
		$(obj).attr("src", '<?php echo base_url(); ?>assets/style/img/icons/window_no_full_screen.png');
	}
	else{
		$.each('north,south'.split(','), function(){myLayout.open(this);});
		$(obj).attr("alt", 'max');
		$(obj).attr("title", '<?php echo $this->lang->line("Maximize"); ?>');
		$(obj).attr("src", '<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png');
	}
}
function show_more_div(val) {
	$('#more_div').css('left',$(val).position().left).slideDown();
}
function closeDist()
{
	$('#distanceBtn_div').fadeOut();
}

function user_dialog_opn()
{
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$('#user_dialog').dialog('open');
	$(".user_add_table").hide();
	$("#create_u_div").show();
	$("#usr_combo").show();
	$("#usr_asset_combo").show();
	fillUsrs();
}
function fillUsrs()
{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/tree/get_usrs/assets_ids/"+selected_assets_ids,function(result)
	{
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		if(result.result == "done"){
			if(result.user_combo!="")
			{
				$("#add_to_user").html(result.user_combo);
				$("#add_to_user").trigger("change");
			}
			else
			{
				//$('#user_dialog').dialog('open');
				$("#add_to_user").html('');
				$("#user_error").html('There is No Users Exist');
				$("#user_error").addClass('ui-state-highlight');
				setTimeout(function() {
					tips.removeClass('ui-state-highlight', 1500);
				}, 500);
				$(".user_add_table").show();
				$("#create_u_div").hide();
				$("#usr_combo").hide();
				$("#usr_asset_combo").hide();
			}
		}
	},'json');	
}

function submitFormUsers_dash(){
		//$("#loading_dialog").dialog("open");
		$("#loading_top").css("display","block");
		var selectAssets_cmb =  $("#cmb_assets option:selected").map(
	function () {return this.value;}).get().join(",");
		var bValid = true;
		var str="/cmd/";
		$("#assets_ids").val(selectAssets_cmb);
		AllFieldsUser.removeClass("ui-state-error");
		if(add_to_user.children("option").length!=0)
		{
		bValid = bValid && checkNull(add_to_user,"<?php echo $this->lang->line("Select User"); ?>");
		}
		if($("#create_u_div").css("display") =="none")
		{
			bValid = bValid && checkNull(username,"<?php echo $this->lang->line("username"); ?>");
			bValid = bValid && checkNull(first_name,"<?php echo $this->lang->line("First_Name"); ?>");
			bValid = bValid && checkNull(last_name,"<?php echo $this->lang->line("Last_Name"); ?>");
			bValid = bValid && checkNumber(mobile_number,"<?php echo $this->lang->line("mobile_number"); ?>");
			
			
			//bValid = bValid && parse_date(from_date,"Date");
			if($("#u_id").val()=="")
				bValid = bValid && checkNull(password,"<?php echo $this->lang->line("password"); ?>");
			str+="add";
		}
		else
		{
			str+="update";
		}
		if (bValid) {
			$.post("<?php echo base_url(); ?>index.php/tree/save_user"+str, $("#frm_users").serialize(),
			 function(data) {
			 //$("#loading_dialog").dialog("close");
			 $("#loading_top").css("display","none");
				if(data.result == "true"){
					$("#u_id").val("");
					$("#pass_u_id").css("display","inline");
					if($("#create_u_div").css("display") =="none"){
						$('#user_dialog').dialog('close');
							//$('#user_dialog').dialog('open');
						user_dialog_opn();
					}
					else
					{
						$('#user_dialog').dialog('close');
					}					
					if(data.dash_cmd!="")
					{ $("#optdetail").html(data.dash_cmb); }
					
					$("#alert_dialog").html(data.msg);
					$("#alert_dialog").dialog("open");	
					$("user_dialog").dialog({ modal: true });
				}
				else
				{
					updateTips(data.msg);
					AllFieldsUser.removeClass('ui-state-error');
				}
			}, "json");
		}
	return false
}
function selectedAssets_Combo() {         
	
	selected_assets_ids =  $("#cmb_assets option:selected").map(
	function () {return this.value;}).get().join(",");
	getSelectChkd();
	//$("#assets_ids").val(selected_assets_ids);
	//$("#float-icons a[title='Add To Group']").css("opacity0.2");
	//var totalSelected = selected_assets_ids.split(",");

	if(selected_assets_ids.length>0)
	{
		//$("#float-icons a").removeClass("float-icon-disabled");
		//$("#float-icons a").addClass("float-icon-enabled");
		$("#float-icons").show();	
	}
	else
	{
		//$("#float-icons a").removeClass("float-icon-enabled");
		//$("#float-icons a").addClass("float-icon-disabled");
		$("#float-icons").hide();
	}
	
	<?php /*if(totalSelected.length == 2){
		var top =$('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().top + 15 + $("#tabs").scrollTop();
		var left= $('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().left + 15 + $("#tabs").scrollLeft();

		$("#distanceBtn_div").css({top: top ,left: left, position: 'absolute', height:"50px", border:"1px solid lightblue",borderRadius:"4px"});
		$('#distanceBtn_div').fadeIn();
	}else{
		$('#distanceBtn_div').fadeOut();
	}*/ ?>
  }
 function selectedAssets_Combo_group() {         
	
	selected_assets_ids =  $("#cmb_assets_grp option:selected").map(
	function () {return this.value;}).get().join(",");
	getSelectChkd();
	//$("#assets_ids").val(selected_assets_ids);
	//$("#float-icons a[title='Add To Group']").css("opacity0.2");
	//var totalSelected = selected_assets_ids.split(",");

	if(selected_assets_ids.length>0)
	{
		//$("#float-icons a").removeClass("float-icon-disabled");
		//$("#float-icons a").addClass("float-icon-enabled");
		$("#float-icons").show();
	}
	else
	{
		//$("#float-icons a").removeClass("float-icon-enabled");
		//$("#float-icons a").addClass("float-icon-disabled");
		$("#float-icons").hide();
	}
	
	<?php /*if(totalSelected.length == 2){
		var top =$('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().top + 15 + $("#tabs").scrollTop();
		var left= $('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().left + 15 + $("#tabs").scrollLeft();

		$("#distanceBtn_div").css({top: top ,left: left, position: 'absolute', height:"50px", border:"1px solid lightblue",borderRadius:"4px"});
		$('#distanceBtn_div').fadeIn();
	}else{
		$('#distanceBtn_div').fadeOut();
	}*/ ?>
  }

function getSelectChkd()
{
	//alert(selected_assets_ids.toSource());
	$(".alist input:checkbox").attr("checked",false);

	if(selected_assets_ids!="" || selected_assets_ids!= undefined)
	{
		var assetss=selected_assets_ids.split(",");
		for(i=0;i<assetss.length;i++)
		{
			$(".alist input:checkbox[value='"+assetss[i]+"']").attr("checked",true);
		}
	}
	if(timer_on==1)
	{
		$("#checkboxToggle").attr("checked", true);
	}
}

function getSelectJGridChkd()
{
	//alert(selected_assets_ids.toSource());
	$("#lastpoint_grid tr td input:checkbox").attr("checked",false);
	
	if(loadGrid == 1) {
		$("#lastpoint_grid tr td input:checkbox").attr("checked",true);
	}
	else if(selected_assets_ids!="" || selected_assets_ids!= undefined)
	{
		var assetss=selected_assets_ids.split(",");
		for(i=0;i<assetss.length;i++)
		{
			$("#lastpoint_grid tr td input:checkbox[value='"+assetss[i]+"']").attr("checked",true);
		}
		
		<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
			if(assetss.length==2)
			{
				//$("#distanceBtn_grid").show();
			}
			else
			{
				//$("#distanceBtn_grid").hide();
			}
		<?php } ?>
	}
	if(timer_on==1)
	{
		$("#checkboxToggle").attr("checked", true);
	}
}
function open_group_dialog()
{
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$("#new_group").val("");
	$("#edit_group_id").val("");
	$("#tips_grp").text("");
	$("#new_group").removeClass('ui-state-error');
	$("#create_g_div").show();
	$("#new_group_tr").hide();
	$("#group_list_combo").show();
	$("#assets_combo_grp").show();
	$('#group_dialog').dialog('open');
	$("#tips_grp").removeClass('ui-state-highlight');
	$("#tips_grp").hide();
	fillGroups();
}

function addToGroup(){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	str="/cmd/";
	gp="";
	
		var selectAssets_cmb_grp =  $("#cmb_assets_grp option:selected").map(
	function () {return this.value;}).get().join(",");
	
		if(selectAssets_cmb_grp=="")
			selectAssets_cmb_grp=0;
		if(($("#group_list_combo").css("display") =="none") && ($("#edit_group_id").val()=="" )){
			tips = $("#user_error");
			var newgp = $("#new_group").val();
			new_group = $("#new_group");
			
			if(newgp == ""){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
				//$("#alert_dialog").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				
				$("#tips_grp").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				$("#tips_grp").show();
				$("#tips_grp").addClass('ui-state-highlight');
				setTimeout(function() {
					$("#tips_grp").removeClass('ui-state-highlight', 1500);
				}, 500);
	
			return false;
			}
			str+="new";
			gp="new";
		}else if(($("#group_list_combo").css("display") =="none") && ($("#edit_group_id").val()!="")){
			var newgp = $("#new_group").val();
			
			if(newgp == ""){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
				//$("#alert_dialog").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				$("#tips_grp").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				$("#tips_grp").show();
				$("#tips_grp").addClass('ui-state-highlight');
				setTimeout(function() {
					$("#tips_grp").removeClass('ui-state-highlight', 1500);
				}, 500);
				return false;
			}
			gp=$("#group_combo").val();
			str+="edit";
		}
		else
		{
			if(selectAssets_cmb_grp == '' || selectAssets_cmb_grp == 0){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			return false;
			}
			gp=$("#group_combo").val();
			str+="update";
		}
		$.post("<?php echo base_url(); ?>index.php/tree/add_to_group/assets/"+selectAssets_cmb_grp+str+"/group/"+gp+"/newgp/"+newgp,
			 function(data) {	
			 //$("#loading_dialog").dialog("close");
			 $("#loading_top").css("display","none");
				if(data.result == "true"){
					$("#new_group").val("");
					$("#edit_group_id").val("");
					if($("#group_list_combo").css("display") =="none"){
						
						$('#group_dialog').dialog('close');
						//$('#group_dialog').dialog('open');
						open_group_dialog();
					}
					else
					{
						$('#group_dialog').dialog('close');
					}
					$("#alert_dialog").html(data.msg);
					$("#alert_dialog").dialog("open");	
					$("#tips_grp").text("");
					$("#new_group").removeClass('ui-state-error');
					$("group_dialog" ).dialog({ modal: true });
					$.post("<?php echo base_url(); ?>index.php/users/getDashCombo",function(dt_data){
					if($.trim(dt_data)!="")
					{
						$("#optdetail").html(dt_data);
					}
					});
				}	
				else
				{	
					updateTipsErr(data.msg,$("#tips_grp"));
					//AllFieldsUser.removeClass('ui-state-error');
					$("#new_group").addClass('ui-state-error');
					//$("#alert_dialog").html(data.msg);
					//$("#alert_dialog").dialog("open");	
				}
				
			}, "json");
	
}
function fillGroups()
{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/tree/get_grps/assets_ids/"+selected_assets_ids,function(result)
	{
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		if(result.result == "done"){
			if(result.user_combo!="")
			{
				$("#group_combo").html(result.user_combo);
				$("#group_combo").trigger("change");
			}
			else
			{
				$("#group_combo").html('');
				$("#create_g_div").hide();
				$("#new_group_tr").show();
				$("#group_list_combo").hide();
				$("#assets_combo_grp").hide();
			}
		}
	},'json');	
}
function group_dialog_opn(val)
{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	if(selected_assets_ids.length>0 && val==0)
	{
		user_dialog_opn();
	}
	else if(selected_assets_ids.length>0 && val==1)
	{
		open_group_dialog();
	}
	else if(selected_assets_ids.length>0 && val==2)
	{
		newTab();
	}
	else if(selected_assets_ids.length>0 && val==4)
	{
		multiScreen();
	}
	else if(selected_assets_ids.length>0 && val==3)
	{
		loadAssetsDash();
	}
	
	
}
function create_usr_func()
{
	$("#user_dialog").dialog('option', 'title', '<?php echo $this->lang->line('Create_Users'); ?>');
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$("#user_error").html("");
	$("#u_id").val("");
	$("#pass_u_id").css("display","inline");
	$("#username").val("");
	$("#password").val("");
	//$("#from_date").val("<?php echo date('d.m.Y H:i'); ?>");
//	$("#to_date").val("<?php echo date('d.m.Y H:i',strtotime(date('d.m.Y H:i').'+2 days')); ?>");
	var newDt=new Date();
	newDt.setFullYear(newDt.getFullYear() + 1);
	$("#from_date").datetimepicker('setDate', new Date());
	$("#to_date").datetimepicker('setDate', newDt);
	$("#first_name").val("");
	$("#last_name").val("");
	$("#mobile_number").val("");
	$("#email_address").val("");
	$("#email_alert").attr("checked","checked");
	$("#sms_alert").attr("checked","checked");
	$(".user_add_table").show();
	$("#create_u_div").hide();
	$("#usr_combo").hide();
	$("#usr_asset_combo").hide();
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
}
function edit_usr_func()
{
	$("#user_dialog").dialog('option', 'title', '<?php echo $this->lang->line('Update_Userss'); ?>');
	var val=$("#add_to_user").val();
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/tree/get_usrs_details/uid/"+val,function(data){
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		$("#u_id").val(data.row.user_id);
		$("#pass_u_id").css("display","none");
		$("#username").val(data.row.username);
		$("#from_date").val(data.row.from_date);
		$("#to_date").val(data.row.to_date);
		$("#first_name").val(data.row.first_name);
		$("#last_name").val(data.row.last_name);
		$("#mobile_number").val(data.row.mobile_number);
		$("#email_address").val(data.row.email_address);
		if(data.row.email_alert==1){$("#email_alert").attr("checked","checked");}else{$("#email_alert").removeAttr("checked");}
		if(data.row.sms_alert==1){$("#sms_alert").attr("checked","checked");}else{$("#email_alert").removeAttr("checked");}
	},'json');
	$(".user_add_table").show();
	$("#create_u_div").hide();
	$("#usr_combo").hide();
	$("#usr_asset_combo").hide();
	
}
function cancel_ds_usr()
{
	
	$("#user_error").stop();
	$("#user_dialog").dialog('option', 'title', '<?php echo $this->lang->line('Create_Users'); ?>');
	$("#u_id").val("");
	$("#pass_u_id").css("display","inline");
	fillUsrs();
	if($("#usr_combo").css("display") =="none" && $("#add_to_user option").length>0){
		$(".user_add_table").hide();
		$("#create_u_div").show();
		$("#usr_combo").show();
		$("#usr_asset_combo").show();
	}
	else
	{
		$("#user_dialog").dialog("close");
	}
}
function delete_usr_func()
{
	$("#confirm_alert_dialog").dialog('option', 'buttons',{
		'Yes' : function(){
			$(this).dialog("close");
			var val=$("#add_to_user").val();
			val=val.replace("u-","");
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$.post("<?php echo base_url(); ?>/index.php/users/deleteData",{id:val},function(data){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
				$("user_dialog").dialog({ modal: true });
				if(data=="1")
				{
					fillUsrs();
					//$("#user_dialog").dialog("close");
					$("#alert_dialog").html("User Deleted Successfully");
					$("#alert_dialog").dialog("open");
				}
			});
		},
		'No' : function(){
			$(this).dialog("close");
			$("user_dialog").dialog({ modal: true });
		}
	});
	$("confirm_alert_dialog").dialog({ modal: true });
	$("#confirm_alert_dialog").dialog("open");
}
function cancel_ds_grp()
{
	$("#tips_grp").stop();
	$("#tips_grp").hide();
	$("#group_dialog").dialog("option","title","<?php echo $this->lang->line('Create_Group'); ?>");
	$("#new_group").val("");
	$("#edit_group_id").val("");
	<?php /*fillGroups();*/ ?>
	if($("#group_list_combo").css("display") =="none" && $("#group_combo option").length > 0){
		$("#create_g_div").show();
		$("#new_group_tr").hide();
		$("#group_list_combo").show();
		$("#assets_combo_grp").show();	
	}
	else if($("#group_list_combo").css("display") =="block" && $("#group_combo option").length<1){
		$('#group_dialog').dialog('close');
	}else
	{
		$('#group_dialog').dialog('close');
	}
}
function create_group_func()
{
	$("#group_dialog").dialog("option","title","<?php echo $this->lang->line('Create_Group'); ?>");
	$("#edit_group_id").val("");
	$("#create_g_div").hide();
	$("#new_group_tr").show();
	$("#new_group").val("");
	$("#group_list_combo").hide();
	$("#assets_combo_grp").hide();	
}
function edit_group_func()
{
	$("#group_dialog").dialog("option","title","<?php echo $this->lang->line('Update_Group'); ?>");
	var val=$("#group_combo").val();
	$.post("<?php echo base_url(); ?>/index.php/tree/get_group_detail/uid/"+val,function(data){
		$("#edit_group_id").val(data.row.id);
		$("#new_group").val(data.row.group_name);
	},'json');
	$("#create_g_div").hide();
	$("#new_group_tr").show();
	$("#group_list_combo").hide();
	$("#assets_combo_grp").hide();	
}

function delete_group_func()
{
	$("#confirm_alert_dialog").dialog('option', 'buttons',{
		'Yes' : function(){
			$(this).dialog("close");
			var val=$("#group_combo").val();
			val=val.replace("g-","");
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$.post("<?php echo base_url(); ?>/index.php/group/deleteData",{id:val},function(data){
				////$("#loading_dialog").dialog("close");
				$("group_dialog").dialog({ modal: true });	
				if(data=="1")
				{
					$('#group_dialog').dialog('close');
						//$('#group_dialog').dialog('open');
					open_group_dialog();
					$("#alert_dialog").html("Group Deleted Successfully");
					$("#alert_dialog").dialog("open");
				}
			});
		},
		'No' : function(){
			//$('#group_dialog').dialog('close');
						//$('#group_dialog').dialog('open');
			//		open_group_dialog();
			$(this).dialog("close");
			$("group_dialog").dialog({ modal: true });
		}
	});
	$("confirm_alert_dialog").dialog({ modal: true });
	$("#confirm_alert_dialog").dialog("open");
}
function cancelLoading(){
	//if(typeof  $xhr==='object') {$xhr.abort()};
	$("#loading_top").css("display","none");
}


//////////////////////////

function click_refresh()
{
	$('.ui-icon-refresh, .ui-jqgrid-sortable').click(function(){$("#loading_top").css("display","block");});
	$('.ui-pg-selbox').change(function(){$("#loading_top").css("display","block");});
	$('.ui-pg-button.ui-corner-all').click(function(){
		if(!$(this).hasClass('ui-state-disabled'))
		{
			$("#loading_top").css("display","block");
		}
	});
}
function hideQTip()
{
	$(".deviceMain").qtip('hide');
}
function detail_list_a(val)
{
	
	$("#optdetail option").removeAttr('selected');
	$("#optdetail option[value='"+val+"']").attr('selected','selected');
	$("#optdetail").trigger("change");
	
}
var first_time_load = 1;
function loadAssets(){
	
	if(tree_elements.length > 0) {
		$("#loading_top").css("display","block");
		$('#distanceBtn_div').hide();
	
		$req_obj_xhr = $.post(url, { assets:tree_elements }, function(data) {
			$("#main").html(data);
			$("#loading_top").css("display","none");
		});
		
		selected_assets_ids = tree_elements.join(',');
	}
}

function close_pop_up(val)
{
	total_pop_up=0;
	$("#open_pop_up_"+val).remove();
}
var total_pop_up =0;
function open_pop_up(header,data,link,type)
{
	$.post('<?php echo base_url(); ?>index.php/tree/popup_request', { header: header, data:data, link:link, type:type },
		function(data) {});
	var thissound=document.getElementById("sound_start");
	thissound.play();
	var html ='';
	if(total_pop_up==0){
		$("#popup").append("<div class='shadow popup_window ' style='height:24px' ><table width='100%'><tr><td style='text-align: center;'><a style='color:white;' href='Javascript:void(0);' onclick='$(\".popup_window\").remove();total_pop_up=0;' >Close All</a></td></tr></div>");
	}
	html = "<div class='shadow popup_window ' id='open_pop_up_"+total_pop_up+"'><table width='100%'><tr style='border-bottom: 1px dotted;'><td>"+header+"<img src='<?php echo base_url(); ?>assets/upload_image/close.png' alt='close' style='height: 12px;cursor:pointer;float:right;padding:4px' onclick='close_pop_up("+total_pop_up+")'/></td></tr><tR><td><img src='<?php echo base_url(); ?>/assets/";
	
	if(type=='error')
		html = html+"Error-8.png"; 
	else
		html = html + "alert-8.png";
	
	html = html+ "'style='float: left;padding-right:4px' >"+data+" <span style='float:right'>";
	if(link!=null && link != "")
		html = html+ "<a  onclick='topMenuToTab(\""+link+"\",\"From Alert\",\"from_login\")' href='JavaScript:void(0); ' style='color:#ffffff'>&nbsp;<?php echo $this->lang->line("more"); ?></a>";
//	topMenuToTab(url, name, id);
	html = html+ "</span></td></tr></table></div>";
	$("#popup").prepend(html);

	total_pop_up = total_pop_up+1
	return "open_pop_up_"+(total_pop_up-1);
}
function loadAssetsList(){
	//$("#loading_dialog").dialog("open");
	$("#select_assest_list_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/listview.png');
	$("#select_assest_list_view").attr('onClick','loadAssetsGrid()');
	
	if(typeof $req_obj_xhr==='object'){$req_obj_xhr.abort()};

	url = "<?php echo base_url(); ?>index.php/tree/tree_list";
		
	if(tree_elements.length > 0) {
		$("#loading_top").css("display","block");
	
		$req_obj_xhr = $.post(url, { assets:tree_elements }, function(data) {
			$("#main").html(data);
			$("#loading_top").css("display","none");
		});
		
		selected_assets_ids = tree_elements.join(',');
	}
	//loadAssets();
}

function loadAssetsThumb(){
$("#loading_top").css("display","block");
if(typeof $req_obj_xhr==='object'){$req_obj_xhr.abort()};
$("#select_assest_list_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/gridview.png');
$("#select_assest_thumb_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/selectthumbnail_view.png');
	$("#select_assest_grid_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/listview.png');
	limit = 8;
	url = "<?php echo base_url(); ?>index.php/tree/assets";
	
	//loadAssets();
	var txt = $('#srcTxt').val();
	if(txt == "Search Assets..."){
		txt = "";
	}
	
	var report = Array();
	loadGrid = 0;
	
	$(".optdetail").each(function(index, ele) {
	    report[index] = $(this).val();
	});	
	
	//limit = 40;
//	url = "<?php echo base_url(); ?>index.php/tree/assets_list";
	
		//$("#main").html('<div id="load" style="padding-top:30%;height: 50%;" align="center"><img src="<?php echo base_url(); ?>assets/style/css/images/ajax.gif" alt="Loading"></div>'+$("#main").html());
		$req_obj_xhr = $.post(url, { txt: txt, report:report, limit:limit, page:1 },
			function(data) {
			$("#main").html(data);
			getSelectChkd();
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
		});
}

function loadAssetsGrid(){

	$("#select_assest_list_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/gridview.png');
	$("#select_assest_list_view").attr('onClick','loadAssetsList()');
		
	loadJGrid();

	url = "<?php echo base_url(); ?>index.php/tree/grid_view";

	if(tree_elements.length > 0) {
		$("#loading_top").css("display","block");
	
		$req_obj_xhr = $.post(url, { assets:tree_elements }, function(data) {
			$("#main").html(data);
			$("#loading_top").css("display","none");
		});
		
		selected_assets_ids = tree_elements.join(',');
	}
	
}
function changeLimit(lmt){
	page = 1;
	limit = lmt;
	loadAssets();
}

function triggerChange(cmb, val) {
	$("#"+cmb).val(val);
	$("#"+cmb).trigger('change');
}

function changeReport(){
	page = 1;
	var values = Array();
	$("#assets_running").css("text-decoration","none");
	$("#assets_out").css("text-decoration","none");
	$("#assets_fault").css("text-decoration","none");
	$("#assets_total").css("text-decoration","none");
	$("#assets_parked").css("text-decoration","none");
	
//	$("#main input:checked").removeAttr("checked"); // Remove all the checked boxes.
	loadGrid = 1;
	
	$(".optdetail").each(function(index, ele) {
	    values[index] = $(this).val();
	});

	loadAssets();
	
	<?php /* if($this->session->userdata('usertype_id') == 1){ ?>
		
		$.post("<?php echo base_url(); ?>/index.php/tree/changeAssetsCombo/user_id/"+values,
			function(data) {
				assets_combo_opt = data;
				assets_combo_opt_report = "<option value=''>Select All</option>"+data;
			});
	
	<?php } */ ?>
}

function setcombo(ele_name, value) {
	if(ele_name == 'opt_users')	sel_users = value;
	if(ele_name == 'opt_groups') sel_groups = value;
	if(ele_name == 'opt_areas')	sel_areas = value;
	if(ele_name == 'opt_landmarks') sel_landmarks = value;
	if(ele_name == 'opt_owners') sel_owners = value;
	if(ele_name == 'opt_divisions')	sel_divisions = value;
}

function searchAssets(){
	page = 1;
	loadAssets();
	return false;
}
function changePage(pg){
	page = pg;
	loadAssets();
}
function changePage_img(pg,img_assets_id,time){
	$("#loading_top").css("display","block");
	var limit=$("#numImage"+time).val();
	$.post("<?php echo base_url(); ?>/index.php/tree/navigationImages/page/"+pg+"/id/"+img_assets_id+"/limit/"+limit+"/time/"+time,
	function(data) {
		$("#ImageContainer_"+img_assets_id).html(data);
		$("#loading_top").css("display","none");
	});
}
function ImageLimitChange(img_assets_id,time){
	$("#loading_top").css("display","block");
	var limit=$("#numImage"+time).val();
	$.post("<?php echo base_url(); ?>/index.php/tree/navigationImages/page/"+pg+"/id/"+img_assets_id+"/limit/"+limit+"/time/"+time,
	function(data) {
		$("#ImageContainer_"+img_assets_id).html(data);
		$("#loading_top").css("display","none");
	});
}
function reloadAssets(){
	//$("#loading_dialog").dialog("open");
	if(tree_elements.length > 0) {
		$("#loading_top").css("display","block");
	
		$.post(url, { assets:tree_elements }, function(data) {
			$("#loading_top").css("display","none");
			$("#main").html(data);
		});	
	}
	if(timer_on==1) {
		$("#seconds").html($("#time_in_seconds").val());
		counter();
	}
	resizeDMap();
}

function reloadAssets_DirectRefreshlink(){
	if(tree_elements.length > 0) {
		$("#loading_top").css("display","block");
	
		$.post(url, { assets:tree_elements }, function(data) {
			$("#loading_top").css("display","none");
			$("#main").html(data);
		});	
	}
}
function validateMob()
{
if($("#mobile_number").val() != ""){
	var mobN=$("#mobile_number").val();
	var MO_Num=emails.split(/[;,]+/);
	for(i=0;i<MO_Num.length;i++)
	{
		if(MO_Num[i].length == 10)
		{
			$("#error_frm").hide();
		}else{
			$("#error_frm").show();
			$("#error_frm").html("Mobile Number Formate is Not Valid");
			return false;
		}
	}
	}		
}
function validateEmails()
{
if($("#mobile_number").val() != ""){
	var mobN=$("#mobile_number").val();
	var MO_Num=emails.split(/[;,]+/);
	for(i=0;i<MO_Num.length;i++)
	{
		if(MO_Num[i].length == 10)
		{
			$("#error_frm").hide();
		}else{
			$("#error_frm").show();
			$("#error_frm").html("Mobile Number Formate is Not Valid");
			return false;
		}
	}
	}		
}

function toggleSelection() {
	
	if(dashboardChecked == 0) {
		$('.asset_checkbox').each(function(index, object) {
			$(this).attr('checked', true);
		    if (dashboardMarkers[$(this).val()] != undefined) {
				dashboardMarkers[$(this).val()].setMap(dMap);
				dLabelArr[$(this).val()].setMap(dMap);
			}
			dashboardChecked = 1;
			selectedAssets();
		});
	} else {
		$('.asset_checkbox').each(function(index, object) {
			$(this).attr('checked', false);
		    if (dashboardMarkers[$(this).val()] != undefined) {
				dashboardMarkers[$(this).val()].setMap(null);
				dLabelArr[$(this).val()].setMap(null);
			}
			dashboardChecked = 0;
			selectedAssets();
		});
	}
}

function selectedAssets() {
	
	selected_assets_ids =  $("#main input:checked").map(
	function () {return this.value;}).get().join(",");
	
	//$("#assets_ids").val(selected_assets_ids);
	//$("#float-icons a[title='Add To Group']").css("opacity0.2");
	var totalSelected = selected_assets_ids.split(",");
	if (dashboardMarkers) {
		for (i in dashboardMarkers) {
			dashboardMarkers[i].setMap(null);
			dLabelArr[i].setMap(null);
		}
	}
	
	if(selected_assets_ids.length>0)
	{

		$.each(totalSelected, function( index, value ) {
			if(dashboardMarkers[value]) {
				dashboardMarkers[value].setMap(dMap);
				dLabelArr[value].setMap(dMap);
			}
		});

		//$("#float-icons a").removeClass("float-icon-disabled");
		//$("#float-icons a").addClass("float-icon-enabled");
		
		$("#float-icons").show();
		
	}
	else
	{
		//$("#float-icons a").removeClass("float-icon-enabled");
		//$("#float-icons a").addClass("float-icon-disabled");
		$("#float-icons").hide();
	}
	
	if(totalSelected.length > 1){
		if($("#main #lastpoint_list_div").html())
		{
			<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
				//$("#distanceBtn_grid").show();
			<?php } ?>
		}
		else
		{
			var top =$('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().top + 15 + $("#tabs").scrollTop();
			var left= $('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().left + 15 + $("#tabs").scrollLeft();

			<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
				$("#distanceBtn_div").css({top: top ,left: left, position: 'absolute', height:"50px", border:"1px solid lightblue",borderRadius:"4px"});
			<?php } ?>
			$('#distanceBtn_div').fadeIn();
		}
	}else{
		$('#distanceBtn_div').fadeOut();
		<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
			//$("#distanceBtn_grid").hide();
		<?php } ?>
	}
	
}
function directTab(id, ast_id){

	$(".deviceMain").qtip('hide');
	var nameToCheck = assetNameArray[ast_id];
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/live/device/window/current/id/"+id, "<img src='' style='margin-top:-4px' id='"+ast_id+"_dot'/>"+assetNameArray[ast_id]);	
	}
<?php /*
	var inarray = false;
	var selectTab;
	for(i=0; i<tabArrKey.length; i++){		
		  if(tabArrKey[i] == assetDeviceArray[ast_id]){
			inarray = true;
			selectTab = tabArrValue[i];
		  }
	}
	tab_id = assetDeviceArray[ast_id];
	if(inarray == true){
		$('#tabs').tabs('select', '#' + selectTab);
	}else{
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/live/device/window/current/id/"+id, assetNameArray[ast_id]);		
		return false;
	}
	return false;
	*/ ?>
}
function historyTab(url, name, device_id){
	
	<?php /*var inarray = false;
	var selectTab;
	for(i=0; i<tabArrKey.length; i++){		
		  if(tabArrKey[i] == device_id){
			inarray = true;
			selectTab = tabArrValue[i];
		  }
	}
	tab_id = device_id;
	alert(tab_id)
	if(inarray == true){
		$('#tabs').tabs('select', '#' + selectTab);
	}else{
		$('#tabs').tabs('add', url, name);		
		return false;
	}
	return false;
	*/ ?>
	var nameToCheck = name;
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		
		if (this.text == nameToCheck) {
			
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	
	if (!tabNameExists){
		$('#tabs').tabs('add', url, name);
	} 
}
function newTab(){
<?php /*	var gsr = jQuery("#lastpoint_grid").jqGrid("getGridParam","selarrrow");
	if(gsr != null && gsr != "" && gsr != undefined)
	{
		var allpoint = "";
		for(i=0;i<gsr.length;i++)
		{
				var gsrval = jQuery("#lastpoint_grid").jqGrid('getCell', gsr[i], 'assets_id');		
				if(i == gsr.length-1)
				{
					allpoint +=gsrval;
				}else{
					allpoint +=gsrval+",";
				}
		}
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/map/id/"+allpoint, 'Map');
	} else*/ ?>
	if(selected_assets_ids != undefined && selected_assets_ids != '') 
	{
		
		var selectedAst = selected_assets_ids.split(",");
		selected_assets_ids = selected_assets_ids.replace("on,", "");
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/map/id/"+selected_assets_ids, 'Map');
		
	}   
	
	return false; 
}
function multiScreen(){

	if(selected_assets_ids != undefined && selected_assets_ids != '') 
	{
		var selectedAst = selected_assets_ids.split(",");
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/multi_map/id/"+selected_assets_ids, 'Multi Screen');
		
	}   
	
	return false; 
}
function getAssetsDistance(){
	//$('#distanceBtn_div').hide();
//	$("#distance_box_dialog").html("Distance Will be displayed Soon");
	<?php /*$("#distance_box_dialog").dialog('option', 'buttons', {
			"View On Map" : function() {
			var selectedAst = selected_assets_ids.split(",");
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/map/id/"+selected_assets_ids+"/d/1/cmd/dist", 'Map');
			$(this).dialog("close");
				},
			"Cancel" : function() {
			$(this).dialog("close");
				}
			});
	*/ ?>
	$("#distance_box_dialog").dialog("open");
	return false;
}
function getAssetsDistance_New(from,to){
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/map/id/"+from+","+to+"/d/1/cmd/dist", 'Distance');
}

function loadAssetsDash_tt(id,name){
	$(".deviceMain").qtip('hide');
	var nameToCheck = name+" Details";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/assets_dash/id/"+id, name+" Details");
	}
}
function loadImagesTab(id,name){
	$(".deviceMain").qtip('hide');
	var nameToCheck = name+" Details";
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/image_open/id/"+id+"/limit/8", name+" Details");
	}
}
function loadAssetsDash(){
	if(selected_assets_ids != undefined)
	{	
	var selectedAst = selected_assets_ids.split(",");
	
	if(selectedAst.length > 1){
		$("#alert_dialog").html("<?php echo $this->lang->line("Please_Select_Only_One_Asset"); ?>");
		$("#alert_dialog").dialog("open");
		return false;
	}else{	
		var nameToCheck = assetNameArray[selected_assets_ids]+" Details";
		
		var tabNameExists = false;
		
		$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		
			if (this.text == nameToCheck) {
				tabNameExists = true;
				$('#tabs').tabs('select', $(this).attr("href"));
				return false;
			}
		});
		if (!tabNameExists){
			if(assetDeviceArray[selected_assets_ids] != undefined)
			{
				$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/assets_dash/id/"+selectedAst[0], assetNameArray[selected_assets_ids]+" Details");
			}
			
		} 
		<?php /*insertedTabCheck = true;
		var inarray = false;
		var selectTab;
		for(i=0; i<tabArrKey.length; i++){		
			  if(tabArrKey[i] == selected_assets_ids){
				inarray = true;
				selectTab = tabArrValue[i];
			  }
		}
		tab_id = selected_assets_ids;
		if(inarray == true){
			$('#tabs').tabs('select', '#' + selectTab);
		}else{
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/assets_dash/id/"+assetDeviceArray[selected_assets_ids], assetNameArray[selected_assets_ids]);
			//$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/tree/dash", assetNameArray[selected_assets_ids], 1);
			
			return false;
		}
		*/ ?>
		}
	}
	else
	{
		$("#alert_dialog").html("<?php echo $this->lang->line("No_Assets_Selected"); ?>");
		$("#alert_dialog").dialog("open");
	}
}
function hideSubMenu_all(){
	$('.potato-menu-item').each(function(index){
		$(this).css('padding-bottom', '0px');
	});
	$('.potato-menu-item ul').css('display', 'none');
	if($("#float-icons").css('display') != 'none'){
		$("#float-icons1").hide();
		$("#float-icons").hide();
	}
	myLayout.resizeAll();
}
//menu function
function hideSubMenu(url, name, id){
	$('.potato-menu-item').each(function(index){
		$(this).css('padding-bottom', '0px');
	});
	$('.potato-menu-item ul').css('display', 'none');
	topMenuToTab(url, name, id);
}
function topMenuToTab(url, name, id){
	if(id == "Home"){

		//$(".ui-layout-west").html(vt_menu);
		$('#west_sub_menu').html('<div id="jqxTree"></div>');
		
		initializeTree();
		
/*		
		$( "#accordion" ).accordion({
			collapsible: true, 
			active : false,
			
			change: function(event, ui){
				$url = $(ui.newHeader[0]).children('a').attr('href');
				$.get($url, function (data) {
					$(ui.newHeader[0]).next().html(data);
				});
			}
		});		
*/		
		/*
		subMenu("home_sidebar");
		
		$('.accordion').dcAccordion({
			eventType: 'click',
			autoClose: false,
			saveState: true,
			disableLink: true,
			speed: 'fast',
			classActive: 'test',
			showCount: false
		});
		*/
		// $("#float-icons").show();
		if(selected_assets_ids.length>0)
		{
			$("#float-icons").fadeIn(500);
		}
		
		var $tabs = $('#tabs').tabs(); 
		for (var i = $tabs.tabs('length') - 1; i >= 1; i--) { 
			$tabs.tabs('remove', i); 
		} 
		
		$('#tabs').tabs('select', '#tabs-1');
		//myLayout.close( "west" )
		$("#opt_users").val(sel_users);
		$("#opt_groups").val(sel_groups);
		$("#opt_areas").val(sel_areas);
		$("#opt_landmarks").val(sel_landmarks);
		$("#opt_owners").val(sel_owners);
		$("#opt_divisions").val(sel_divisions);
		
		return false;
	}
	else{
		lastUrl=url;
		lastUrlName=name;
	}

	var nameToCheck = name;
	var tabNameExists = false;
	if (tabNameExists == true){
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
	} 
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
	
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	
	if (!tabNameExists){
		//$("#loading_dialog").dialog("open");
		$("#loading_top").css("display","block");
		$('#tabs').tabs('add', url, name);
	}

}
<?php /*
/////////////////
/-*
function hideShowFixedIcon(){
	
	if($("#float-icons").css('display') == 'none'){
		$("#float-icons").fadeIn(500);
		$("#float-icons1 span").attr('class','float-open');
	}else{
		$("#float-icons").fadeOut(500);
		$("#float-icons1 span").attr('class','float-close');
	}
	
}*-/

//////////// */ ?>
	if(auto_refresh_setting == 1)
		var timer_on=0;
	else
		var timer_on=1;
	var timer;
	var current ;
	var percentage;
	var time_in_s;
	function stop_resume_toggle()
	{
		time_in_s=Number($("#time_in_seconds").val());
		if(timer_on==1)
		{
			clearTimeout(timer);
			timer_on=0;
			$("#seconds").html($("#time_in_seconds").val());
		}	
		else
		{
			counter();
			timer_on=1;
		}
		
	}
	
	function counter(){
		if(Number($("#seconds").html()) == Number($("#time_in_seconds").val()))
		{
			getSelectChkd();
		}
		if($("#seconds").html() == 0){
			//$("#pbar").progressbar("value" , 0);
			clearTimeout(timer);
			reloadAssets();
		}
		else{
			/*
			current=Number($("#seconds").html());		
			percentage = Number(current/(time_in_s)*100)-Number(0.99/(time_in_s)*100);
			val=100-percentage;
			
			$("#pbar").progressbar("value" , val);	
			*/
			$("#seconds").html(Number($("#seconds").html())-1);
			timer = setTimeout('counter()',1000);
		}
	}
	
	function counter_change(){
		if(Number($("#time_in_seconds").val())<1)
			$("#time_in_seconds").val(15);
			
		$("#seconds").html($("#time_in_seconds").val());
		time_in_s=Number($("#time_in_seconds").val());
	}
	
	function loadJQPLOT()
	{
		if($.jqplot == undefined)
		{
		var scriptss="<script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jquery.jqplot.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.canvasTextRenderer.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.barRenderer.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.categoryAxisRenderer.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.dateAxisRenderer.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.pointLabels.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.cursor.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.highlighter.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.canvasAxisLabelRenderer.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.canvasAxisTickRenderer.min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.canvasOverlay.min.js'></scr"+"ipt><link href='<?php echo base_url(); ?>assets/jqplot/jquery.jqplot.min.css' rel='stylesheet' type='text/css'>";
		$("head").append(scriptss);
		}
	}
	
	function loadColorSelection()
	{
		if(!$.isFunction($(".color-picker").miniColors))
		{
			var scrpt="<link href='<?php echo base_url(); ?>assets/style/css/jquery.miniColors.css' rel='stylesheet' type='text/css'><script src='<?php echo base_url(); ?>assets/jquery/jquery.miniColors_min.js' type='text/javascript'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jquery/jquery.dd.js'></scr"+"ipt><link href='<?php echo base_url(); ?>assets/style/css/dd.css' rel='stylesheet' type='text/css' />";
			$("head").append(scrpt);
		}
	}
	
	function loadDropdown()
	{
		if(!$.isFunction($(".color-picker").msDropDown))
		{
			var scrpt="<script type='text/javascript' src='<?php echo base_url(); ?>assets/jquery/jquery.dd.js'></scr"+"ipt><link href='<?php echo base_url(); ?>assets/style/css/dd.css' rel='stylesheet' type='text/css' />";
			$("head").append(scrpt);
		}
	}
	
	function loadSWFupload()
	{
		if(!$.isFunction($(".x").swfupload))
		{
			var swfScript="<script type='text/javascript' src='<?php echo base_url(); ?>/assets/swfupload/swfupload_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>/assets/swfupload/jquery.swfupload.js'></scr"+"ipt>";
			$("head").append(swfScript);
		}
	}
	
	function loadFancyBox(){
		if(!$.isFunction($(".x").fancybox))
		{
			var fancybx="<script type='text/javascript' src='<?php echo base_url(); ?>assets/fancybox/jquery.mousewheel-3.0.6.pack.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>/assets/fancybox/jquery.fancybox.js?v=2.1.3'></scr"+"ipt><link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/fancybox/jquery.fancybox.css?v=2.1.2' media='screen' /><link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/fancybox/jquery.fancybox-buttons.css?v=1.0.5'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/fancybox/jquery.fancybox-buttons.js?v=1.0.5'></scr"+"ipt><link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/fancybox/jquery.fancybox-thumbs.css?v=1.0.7' /><script type='text/javascript' src='<?php echo base_url(); ?>assets/fancybox/jquery.fancybox-thumbs.js?v=1.0.7'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/fancybox/jquery.fancybox-media.js?v=1.0.5'></scr"+"ipt>";
			$("head").append(fancybx);
		}
	}

	function loadInfobox()
	{
		if(!$.isFunction($(".x").rotate))
		{
			var varinfoBox="<script src='<?php echo base_url(); ?>assets/javascript/infobox_min.js' type='text/javascript'></scr"+"ipt><script src='<?php echo base_url(); ?>assets/javascript/jQueryRotate.2.2_min.js' type='text/javascript'></sc"+"ript>";
			$("head").append(varinfoBox);
		}
	}

	function loadInfoBubble()
	{
		if(!window.InfoBubble)
		{
			var varInfob="<script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/infobubble-compiled.js'></sc"+"ript><script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/elabel_min.js'></sc"+"ript>";
			$("head").append(varInfob);
		}
	}
function loadMarkerClusters()
{
	if(!window.MarkerClusterer)
	{
		var varMCluster="<script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/markerclusterer_min.js'></sc"+"ript>";
		$("head").append(varMCluster);
	}
}
function loadSpeedoMeter()
{
	if(!$.isFunction($(".x").speedometer))
	{
		var varSMetter="<script src='<?php echo base_url(); ?>assets/speedo-meter/jquery.speedometer.js'></sc"+"ript><script src='<?php echo base_url(); ?>assets/speedo-meter/jquery.jqcanvas-modified.js'></scr"+"ipt><script src='<?php echo base_url(); ?>assets/speedo-meter/excanvas-modified.js'></sc"+"ript>";
		$("head").append(varSMetter);
	}
}
function loadInfo_Rotate()
{
	var varSMetter="<script src='<?php echo base_url(); ?>assets/javascript/infobox_min.js' type='text/javascript'></scr"+"ipt><script src='<?php echo base_url(); ?>assets/javascript/jQueryRotate.2.2_min.js' type='text/javascript'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/infobubble-compiled.js'></sc"+"ript><script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/elabel_min.js'></scr"+"ipt>";
	$("head").append(varSMetter);
}
function load_dropdown_div()
{
	if(!$.isFunction($(".x").dropdown))
	{
		var vardropdown_div="<link rel='stylesheet' href='<?php echo base_url(); ?>assets/style/css/dropdown.css' type='text/css' media='all' /><script src='<?php echo base_url(); ?>assets/jquery/jquery.dropdown.js' type='text/javascript'></scri"+"pt>";
		$("head").append(vardropdown_div);
	}
}
function loadMultiSelectDropDown(){
	if(!$.isFunction($(".x").dropdownchecklist))
	{	
		var vardropdown_checklist="<script type='text/javascript' src='<?php echo base_url(); ?>/assets/dropdonwchecklist/ui.dropdownchecklist.js'></scr"+"ipt>";
		$("head").append(vardropdown_checklist);
	}
}
function reloadDashboard_Assets_Timer()
{
	//clearTimeout(timer);
	//reloadAssets();
	$("#seconds").html($("#time_in_seconds").val());
	reloadAssets_DirectRefreshlink();
	
}
function cancelloading()
{
	$("#loading_top").css("display","none");
}
//loadJGrid();
//loadInfobox();
//loadInfoBubble();

/*chat start*/

var windowFocus = true;
var chat_username;
var chat_name;
var chatHeartbeatCount = 0;
var minChatHeartbeat = 1000;
var maxChatHeartbeat = 33000;
var chatHeartbeatTime = minChatHeartbeat;
var originalTitle;
var blinkOrder = 0;

var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var chatBoxes = new Array();

$(document).ready(function(){
	originalTitle = document.title;
	startChatSession();

	$([window, document]).blur(function(){
		windowFocus = false;
	}).focus(function(){
		windowFocus = true;
		document.title = originalTitle;
	});
});

function restructureChatBoxes() {
	align = 1;
	
	for (x in chatBoxes) {
		chatboxtitle = chatBoxes[x];

		if ($("#chatbox_"+chatboxtitle).css('display') != 'none') {
			if (align == 0) {
				$("#chatbox_"+chatboxtitle).css('right', '20px');
			} else {
				width = (align)*(225+7)+20;
				$("#chatbox_"+chatboxtitle).css('right', width+'px');
			}
			align++;
		}
	}
}

function chatWith(chatuser,chatid) {
	createChatBox(chatuser,chatid);
	$("#chatbox_"+chatuser+" .chatboxtextarea").focus();
}

function createChatBox(chatboxtitle,chatid,minimizeChatBox) {
	if ($("#chatbox_"+chatboxtitle).length > 0) {
		if ($("#chatbox_"+chatboxtitle).css('display') == 'none') {
			$("#chatbox_"+chatboxtitle).css('display','block');
			restructureChatBoxes();
		}
		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
		return;
	}

	$(" <div />" ).attr("id","chatbox_"+chatboxtitle)
	.addClass("chatbox")
	.html('<div class="chatboxhead"><div class="chatboxtitle">'+chatid+'</div><div class="chatboxoptions"><a href="javascript:void(0)" onclick="javascript:toggleChatBoxGrowth(\''+chatboxtitle+'\')">-</a> <a href="javascript:void(0)" onclick="javascript:closeChatBox(\''+chatboxtitle+'\')">X</a></div><br clear="all"/></div><div class="chatboxcontent"></div><div class="chatboxinput"><textarea class="chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxtitle+'\');"></textarea></div>')
	.appendTo($( "body" ));
			   
	$("#chatbox_"+chatboxtitle).css('bottom', '0px');
	
	chatBoxeslength = 1;

	for (x in chatBoxes) {
		if ($("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
			chatBoxeslength++;
		}
	}

	if (chatBoxeslength == 0) {
		$("#chatbox_"+chatboxtitle).css('right', '20px');
	} else {
		width = (chatBoxeslength)*(225+7)+20;
		$("#chatbox_"+chatboxtitle).css('right', width+'px');
	}
	
	chatBoxes.push(chatboxtitle);

	if (minimizeChatBox == 1) {
		minimizedChatBoxes = new Array();

		if ($.cookie('chatbox_minimized')) {
			minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
		}
		minimize = 0;
		for (j=0;j<minimizedChatBoxes.length;j++) {
			if (minimizedChatBoxes[j] == chatboxtitle) {
				minimize = 1;
			}
		}

		if (minimize == 1) {
			$('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','none');
			$('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','none');
		}
	}

	chatboxFocus[chatboxtitle] = false;

	$("#chatbox_"+chatboxtitle+" .chatboxtextarea").blur(function(){
		chatboxFocus[chatboxtitle] = false;
		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").removeClass('chatboxtextareaselected');
	}).focus(function(){
		chatboxFocus[chatboxtitle] = true;
		newMessages[chatboxtitle] = false;
		$('#chatbox_'+chatboxtitle+' .chatboxhead').removeClass('chatboxblink');
		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").addClass('chatboxtextareaselected');
	});

	$("#chatbox_"+chatboxtitle).click(function() {
		if ($('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') != 'none') {
			$("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
		}
	});

	$("#chatbox_"+chatboxtitle).show();
}


function chatHeartbeat(){

	var itemsfound = 0;
	
	if (windowFocus == false) {
 
		var blinkNumber = 0;
		var titleChanged = 0;
		for (x in newMessagesWin) {
			if (newMessagesWin[x] == true) {
				++blinkNumber;
				if (blinkNumber >= blinkOrder) {
					document.title = x+' says...';
					titleChanged = 1;
					break;	
				}
			}
		}
		
		if (titleChanged == 0) {
			document.title = originalTitle;
			blinkOrder = 0;
		} else {
			++blinkOrder;
		}

	} else {
		for (x in newMessagesWin) {
			newMessagesWin[x] = false;
		}
	}

	for (x in newMessages) {
		if (newMessages[x] == true) {
			if (chatboxFocus[x] == false) {
				//FIXME: add toggle all or none policy, otherwise it looks funny
				$('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
			}
		}
	}
	
	$.ajax({
	  url: "<?php echo site_url("tree/chat"); ?>?action=chatheartbeat",
	  cache: false,
	  dataType: "json",
	  success: function(data) {
		$.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug
				chatboxtitle = item.f;

				if ($("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle, item.name);
				}
				if ($("#chatbox_"+chatboxtitle).css('display') == 'none') {
					$("#chatbox_"+chatboxtitle).css('display','block');
					restructureChatBoxes();
				}
				
				if (item.name == chat_name) {
					item.name = 'me';
				}

				if (item.s == 2) {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
				} else {
					newMessages[chatboxtitle] = true;
					newMessagesWin[chatboxtitle] = true;
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.name+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
				}

				$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
				itemsfound += 1;
			}
		});$('#chatbox_main .chatboxcontent').html('');
		$.each(data.user, function(i,users){
			if (users)	{ // fix strange ie bug
				if(users.cs==1)
					users.cs = "<img src='<?php echo base_url(); ?>assets/images/green_dot.png' />";
				else
					users.cs = "<img src='<?php echo base_url(); ?>assets/images/RedDot.png' />";
				$('#chatbox_main .chatboxcontent').append("<a href='javascript:void(0)' onclick='javascript:chatWith(\""+users.data+"\",\""+users.data1+"\")'><div>"+users.cs+users.data1+"</div></a>");
				//if(users.data)
			}
		});
		chatHeartbeatCount++;

		if (itemsfound > 0) {
			chatHeartbeatTime = minChatHeartbeat;
			chatHeartbeatCount = 1;
		} else if (chatHeartbeatCount >= 10) {
			chatHeartbeatTime *= 2;
			chatHeartbeatCount = 1;
			if (chatHeartbeatTime > maxChatHeartbeat) {
				chatHeartbeatTime = maxChatHeartbeat;
			}
		}
		
		//setTimeout('chatHeartbeat();',chatHeartbeatTime); //chat close
	}});
}

function closeChatBox(chatboxtitle) {
	$('#chatbox_'+chatboxtitle).css('display','none');
	restructureChatBoxes();

	$.post("<?php echo site_url("tree/chat"); ?>", { action:'closechat',chatbox: chatboxtitle} , function(data){	
	});

}

function toggleChatBoxGrowth(chatboxtitle) {
	if ($('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') == 'none') {  
		
		var minimizedChatBoxes = new Array();
		
		if ($.cookie('chatbox_minimized')) {
			minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
		}

		var newCookie = '';

		for (i=0;i<minimizedChatBoxes.length;i++) {
			if (minimizedChatBoxes[i] != chatboxtitle) {
				newCookie += chatboxtitle+'|';
			}
		}

		newCookie = newCookie.slice(0, -1)


		$.cookie('chatbox_minimized', newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','block');
		$('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','block');
		$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
	} else {
		
		var newCookie = chatboxtitle;

		if ($.cookie('chatbox_minimized')) {
			newCookie += '|'+$.cookie('chatbox_minimized');
		}


		$.cookie('chatbox_minimized',newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','none');
		$('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','none');
	}
	
}

function checkChatBoxInputKey(event,chatboxtextarea,chatboxtitle) {
	 
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		message = $(chatboxtextarea).val();
		message = message.replace(/^\s+|\s+$/g,"");

		$(chatboxtextarea).val('');
		$(chatboxtextarea).focus();
		$(chatboxtextarea).css('height','44px');
		if (message != '') {
			message = message.replace(/</g,"&lt;").replace('/>/g',"&gt;").replace(/\"/g,"&quot;");
			$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">Me:&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+message+'</span></div>');
			$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
			$.post("<?php echo site_url("tree/chat"); ?>", {action:'sendChat',to: chatboxtitle, message: message} , function(data){
			});
		}
		chatHeartbeatTime = minChatHeartbeat;
		chatHeartbeatCount = 1;

		return false;
	}

	var adjustedHeight = chatboxtextarea.clientHeight;
	var maxHeight = 94;

	if (maxHeight > adjustedHeight) {
		adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
		if (maxHeight)
			adjustedHeight = Math.min(maxHeight, adjustedHeight);
		if (adjustedHeight > chatboxtextarea.clientHeight)
			$(chatboxtextarea).css('height',adjustedHeight+8 +'px');
	} else {
		$(chatboxtextarea).css('overflow','auto');
	}
	 
}

function startChatSession(){  
	$.ajax({
	  url: "<?php echo site_url("tree/chat"); ?>?action=startchatsession",
	  cache: false,
	  dataType: "json",
	  success: function(data) {
 
		chat_username = data.username;
		chat_name = data.display_name;

		$.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.f;

				if ($("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle,1);
				}
				
				if (item.s == 1) {
					item.f = username;
				}

				if (item.s == 2) {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxinfo">'+item.m+'</span></div>');
				} else {
					$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<div class="chatboxmessage"><span class="chatboxmessagefrom">'+item.f+':&nbsp;&nbsp;</span><span class="chatboxmessagecontent">'+item.m+'</span></div>');
				}
			}
		});
		
		for (i=0;i<chatBoxes.length;i++) {
			chatboxtitle = chatBoxes[i];
			$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
			setTimeout('$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);', 100); // yet another strange ie bug
		}
	
	//setTimeout('chatHeartbeat();',chatHeartbeatTime); // chat close
		
	}});
}

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
/*chat end*/

function select_all_ast(){
	$("#lastpoint_grid input").removeAttr("checked");
	if($("#all_ast").attr("checked")=="checked"){
		$("input[name=assets_check\\[\\]]").each(function()    
		{    
			$(this).attr("checked","checked");
		});
	}
	else{
		$("input[name=assets_check\\[\\]]").each(function()    
		{    
			$(this).removeAttr("checked");
		});
	}
	selected_assets_ids =  $("#lastpoint_grid input:checked").map(
	function () {return this.value;}).get().join(",");
	selectedAssets();
	// uncheckOthers("#fault_ast","#parked_ast","#out_ast","#running_ast");
}
function select_running_ast(){
			$("#lastpoint_grid input").removeAttr("checked");
			if($("#running_ast").attr("checked")=="checked"){
				$("input.running_asts").each(function()    
				{    
					$(this).attr("checked","checked");
				});
			}
			else{
				$("input.running_asts").each(function()
				{    
					$(this).removeAttr("checked");
				});
			}
			selected_assets_ids =  $("#lastpoint_grid input:checked").map(
			function () {return this.value;}).get().join(",");
			selectedAssets();
			// uncheckOthers("#fault_ast","#all_ast","#parked_ast","#out_ast");
}
function select_parked_ast(){
$("#lastpoint_grid input").removeAttr("checked");
	if($("#parked_ast").attr("checked")=="checked"){
		$("input.parked_asts").each(function()
		{    
			$(this).attr("checked","checked");
		});
	}
	else{
		$("input.parked_asts").each(function()
		{    
			$(this).removeAttr("checked");
		});
	}
	selected_assets_ids =  $("#lastpoint_grid input:checked").map(
	function () {return this.value;}).get().join(",");
	selectedAssets();
	// uncheckOthers("#fault_ast","#all_ast","#out_ast","#running_ast");
}
function select_out_ast(){
$("#lastpoint_grid input").removeAttr("checked");
	if($("#out_ast").attr("checked")=="checked"){
		$("input.out_of_network_asts").each(function()
		{    
			$(this).attr("checked","checked");
		});
	}
	else{
		$("input.out_of_network_asts").each(function()  
		{    
			$(this).removeAttr("checked");
		});
	}
	selected_assets_ids =  $("#lastpoint_grid input:checked").map(
	function () {return this.value;}).get().join(",");
	selectedAssets();
	// uncheckOthers("#fault_ast","#all_ast","#parked_ast","#running_ast");
}
function select_fault_ast(){

	$("#lastpoint_grid input").removeAttr("checked");
	if($("#fault_ast").attr("checked")=="checked"){
		$("input.device_fault_asts").each(function()
		{
			$(this).attr("checked","checked");
		});
	}
	else{
		$("input.device_fault_asts").each(function()  
		{
			$(this).removeAttr("checked");
		});
	}
	selected_assets_ids =  $("#lastpoint_grid input:checked").map(
	function () {return this.value;}).get().join(",");
	selectedAssets();
	// uncheckOthers("#all_ast","#parked_ast","#out_ast","#running_ast");
}
function uncheckOthers(a,b,c,d){
	$(a).removeAttr("checked");
	$(b).removeAttr("checked");
	$(c).removeAttr("checked");
	$(d).removeAttr("checked");
}
function filterAssetsCombo(val, cid){
	$.post("<?php echo base_url(); ?>index.php/tree/filter_assets", { grp: val},
	 function(data) {
		$("#"+cid).dropdownchecklist('destroy');
		$("#"+cid).html("<option value=''>Select All</option>"+data);
		$("#"+cid).dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options){
			var selectedOptions = options.filter(":selected");
			var countOfSelected = selectedOptions.size();
			switch(countOfSelected) {
				case 0: return "<i><?php echo $this->lang->line("Please Select"); ?><i>";
				case 1: return selectedOptions.text();
				case options.size(): return "<b><?php echo $this->lang->line("all_assets"); ?></b>";
				default: return countOfSelected + " Assets";
			}
		}, icon: {}, width: 150});
		$("#ddcl-"+cid).css('vertical-align','middle');
		$("#ddcl-"+cid+"-ddw").css('overflow-x','hidden');
		$("#ddcl-"+cid+"-ddw").css('overflow-y','auto');
		$("#ddcl-"+cid+"-ddw").css('height','200px');
		$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	 });
}
</script>