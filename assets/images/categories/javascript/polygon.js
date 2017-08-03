	var PolyBnds;
	var http;
	var htmlTable="";
	var addTable="";
	var newpoly=null;
	var directions;
	var curPoly=0;
    var isEditing = false;
    var polygon = Array();
	var upPoly = null;
	var polynm=Array();
	var polyid=Array();
	var arrDevID=Array();
    var map = null;
    var isCompatible = GBrowserIsCompatible();
	//var marker=null;
	var geocoder=null;
	var distance = Array(); 
	var isMove = false;
    var iconIncluded = 'http://maps.google.com/mapfiles/dd-start.png';
    //var iconExcluded = 'http://maps.google.com/mapfiles/dd-end.png';
	var iconExcluded = 'images/anicon.gif';
	var elable = Array();
	var htmltext;
	var btnCreate;
	
function getPoly()
	{
		for(i=0;i<polygon.length;i++)
		{
			map.removeOverlay(polygon[i]);
		}
		polygon = [];
		
		http = XMLObject();
		var sUrl = "php/getpoly.php?uid="+uid;
		http.open('GET', sUrl, true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Connection", "close");
		http.send(null);
		http.onreadystatechange=handleGetPoly;
	}
	function XMLObject() {
		var http = false;
		if(navigator.appName == "Microsoft Internet Explorer") 
		{
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		else 
		{
			http = new XMLHttpRequest();
		}
		return http;
	}
	function handleGetPoly()
	{
		
		if(http.readyState==4) {
			try{
			if(http.responseXML == null) {
				alert("Error Retrieving the Data");
			}
			var xmlDoc = http.responseXML.documentElement;
			var tmppoly = xmlDoc.getElementsByTagName("polygon");
			for(i=0;i<tmppoly.length;i++){
				polynm.push(tmppoly[i].getAttribute("name"));
				polyid.push(tmppoly[i].getAttribute("id"));
				arrDevID.push(tmppoly[i].getAttribute("devid"));
				var points = tmppoly[i].getElementsByTagName("point");
				var latlng = Array();
				for(j=0;j<points.length;j++){
					lat = points[j].getAttribute("lat");
					lng = points[j].getAttribute("lng");
					latlng.push(new GLatLng(lat,lng));
				}
				polygon.push(new GPolygon(latlng,"#000000", 1, 1, "#336699", 0.3));
			}
			
			for(i=0;i<polygon.length;i++){
				map.addOverlay(polygon[i]);
			center = polygon[i].getBounds().getCenter();
				// add lable
				var label = new ELabel(center,"<b><font color=000000 class=style2>"+polynm[i]+"</font></b>", null, new GSize(-10,12));
      			map.addOverlay(label); 
				
			}
			setTimeout("updatePoints()",1000);
				
			}catch(e){
				alert(e);
			}
		}
		
	}
	function deletePoly(cid){
		cid = '['+cid+']';
		var action = confirm("Do want to delete this area?");
		if(action == true){
			polyid = document.getElementById('hidPoly'+cid).value;
			http = XMLObject();
			var sUrl = "php/delpoly.php?id="+polyid;
			
			http.open('GET', sUrl, true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http.setRequestHeader("Connection", "close");
			http.send(null);
			http.onreadystatechange=function(){
				if(http.readyState==4)
				{
					alert(http.responseText);
					//window.location = url;
					styledPopupClose();
					RefreshData(map);
					//getPoly();
				}
			}
		}
	}
	function editClick(cid) {
		try{
			isEditing = !isEditing;
			
			if(isEditing && polygon != null) {
				document.getElementById("btnEdit["+cid+"]").value = 'Stop Editing';
				document.getElementById("btnUpdate").disabled = 'disabled';
				document.getElementById("btnDelete").disabled = 'disabled';
				//document.getElementById('btnCreate').disabled = 'disabled';
				polygon[curPoly].enableDrawing();
				upPoly = polygon[curPoly];
				
			} else {
				document.getElementById("btnEdit["+cid+"]").value = 'Edit Selection';
				document.getElementById("btnUpdate").disabled = '';
				document.getElementById("btnEdit["+cid+"]").disabled = 'disabled';
				polygon[curPoly].disableEditing();
				
			}
		}
		catch(e){
			alert(e);
		}
	}
	function updatePoly(cid)
	{
		cid = '['+cid+']';
		pid = document.getElementById('hidPoly'+cid).value;
		pnm = document.getElementById('txtPoly'+cid).value;
		deviceId = document.getElementById('cmbDevice'+cid).value;
		var x=document.getElementById("cmbDevice"+cid);
		txt=""
		var first = false;
		for (i=0;i<x.options.length;i++)
		  {
			if(x.options[i].selected == true){
				if(first == true){
					txt += ",";
				}
				first = true;
				txt += x.options[i].value;
				
			}
		  }
		devId = txt;
		
		var polyLat = Array();
		var polyLng = Array();
		
		if(pnm == "" || pnm == null){
			alert("Please enter area name");
			document.getElementById('txtPoly').focus();
			return false;
		}
		
		if(upPoly == "" || upPoly == null){
			alert("Please select area on map");
			return false;
		}
		
		for(i=0;i<upPoly.getVertexCount();i++){
			polyLat.push(upPoly.getVertex(i).lat());
			polyLng.push(upPoly.getVertex(i).lng());
		}
		
		http = XMLObject();
		var sUrl = "php/updatepoly.php?device=" + devId +"&id="+pid+"&name="+pnm+"&latUpd="+polyLat+"&lngUpd="+polyLng;
		http.open('GET', sUrl, true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Connection", "close");
		http.send(null);
		http.onreadystatechange=function(){
			if(http.readyState==4)
			{
				alert(http.responseText);
				//window.location = url;
				styledPopupClose();
				RefreshData(map);
			}
		}
	}
	function addPolyNew()
	{
		htmltext = '<table>';
		htmltext +=	'<tr>';
		htmltext +=	'<td>Area Name</td>';
		htmltext +=	'<td><input type="text" id="txtPoly1" name="txtPoly1" onmouseover="this.focus();"></td>';
		htmltext +=	'</tr>';
		htmltext +=	'<tr>';
		htmltext +=	'<td>Device Name</td>';
		htmltext +=	'<td>';
		deviceCombo = deviceCombo.replace(/ selected/g,'');
		htmltext += deviceCombo;
		
		htmltext += '</td>';
		htmltext +=	'</tr>';
		htmltext +=	'<tr>';
		htmltext +=	'<td><input type="button" id="btnCreate" value="Create" onClick="createPoly()"></td>';
		htmltext +=	'<td>';
		htmltext +=	'<input type="button" id="btnCancel" value="Cancel" onClick="clearSelection()">';
		htmltext +=	'</td>';
		htmltext +=	'</tr>';
		htmltext +=	'</table>';
		htmltext +=	'<input type="hidden" id="hidPoly1" name="hidPoly1">';
								
//		var context = new draggablePopup(htmltext);
		
		document.getElementById("divPopup").innerHTML = htmltext;
		showpopup();
		//context.show();		
	}
	function createPoly(){
		try {
			btnCreate = document.getElementById('btnCreate');
			//document.getElementById('btnEdit').disabled = 'disabled';
			//document.getElementById('btnUpdate').disabled = 'disabled';
			//document.getElementById('btnDelete').disabled = 'disabled';
			if(btnCreate.value == "Create"){
				btnCreate.value="Insert";
				newpoly = new GPolygon([], "#000000", 1, 1, "#336699", 0.3);
				map.addOverlay(newpoly);
				newpoly.enableDrawing();
				GEvent.addListener(newpoly, "endline", function() {
					alert('Enter Area Name');
					document.getElementById('txtPoly1').value="";
					document.getElementById('txtPoly1').focus();
					
				});
				isMove = false;
			}
			else{
				addPoly();
			}
		}
		catch(e) {
			alert(e.description);
		}
	}
	function addPoly()
	{
		polyname = document.getElementById('txtPoly1').value;
		deviceId = document.getElementById('cmbDevice').value;
		 var x=document.getElementById("cmbDevice");
		txt=""
		var first = false;
		for (i=0;i<x.length;i++)
		  {
			if(x.options[i].selected == true){
				if(first == true){
					txt += ",";
				}
				first = true;
				txt += x.options[i].value;
				
			}
		  }
		devId = txt;
		
		var latAdd = Array();
		var lngAdd = Array();
		for(i=0;i<newpoly.getVertexCount();i++){
			latAdd.push(newpoly.getVertex(i).lat());
			lngAdd.push(newpoly.getVertex(i).lng());
		}
		
		if(polyname == "" || polyname == null){
			alert("Please enter area name");
			document.getElementById('txtPoly1').focus();
			return false;
		}
		
		if(newpoly == "" || newpoly == null){
			alert("Please select area on map");
			return false;
		}
		
		http = XMLObject();
		var sUrl = "php/addpoly.php?device=" + devId +"&name=" + polyname + "&latAdd=" + latAdd + "&lngAdd=" + lngAdd;
		
		http.open('GET', sUrl, true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Connection", "close");
		http.send(null);
		http.onreadystatechange=handleAddPoly;
	}
	
	function handleAddPoly()
	{
		if(http.readyState==4) {
			try{
			if(http.responseText == null) {
				alert("Error Retrieving the Data");
			}
			alert(http.responseText);
			//window.location = url;
			styledPopupClose();
			RefreshData(map);
			}catch(e){
				alert(e);
			}
		}
		
	}
	function updatePoints() {
		//document.getElementById('distance').innerHTML = "";
		var inPoly = Array()
		distance = Array();
		var tmpPoly = Array();
		var tmpDevID = Array();
		var htmlTable = '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		htmlTable += '<tr><td height="23" align="left" valign="middle" bgcolor="#F2F2F2"><B>Area</B></td><td align="left" valign="middle" bgcolor="#f2f2f2"><B>Distance</B></td></tr>';
		//alert(marker);
		if(marker != null && deviceId != "viewall")
		{
			
			var point = marker.getLatLng();
			for(i=0;i<polygon.length;i++){
				if(!polygon[i].containsLatLng(point)){
					inPoly.push(polygon[i].containsLatLng(point));
					distance.push(roundNumber(bdccGeoDistanceToPolyMtrs(polygon[i],point)/1000,2));
					tmpPoly.push(polynm[i]);
					tmpDevID.push(arrDevID[i]);
				}
			}
			for(i=0;i<polygon.length;i++){
				if(polygon[i].containsLatLng(point)){
					inPoly.push(polygon[i].containsLatLng(point));
					distance.push(roundNumber(bdccGeoDistanceToPolyMtrs(polygon[i],point)/1000,2));
					tmpPoly.push(polynm[i]);
					tmpDevID.push(arrDevID[i]);
				}
			}
			
			for(i=0;i<inPoly.length;i++){
				var arr = Array();
					//var arr= new Array(8,9,10);
				arr = tmpDevID[i].split(",");
				arr.push(arr);
				
					if(inPoly[i]){
						
						//marker.setImage(iconIncluded);
						if(distance[i] < 0.2)
						{
							if(arr.inArray(deviceId)){
							htmlTable += '<tr><td height="20" align="left" valign="middle">&nbsp;</td><td width="30%" align="left" valign="middle" height="20">' + tmpPoly[i] + '</td><td width="70%" align="left" valign="middle">' + distance[i] + ' M</td></tr>';
							//document.getElementById('distance').innerHTML += "<br>Your are Near the Boundry of Area : <b>" + tmpPoly[i] + "</b>, Distance : " + distance[i] + "M";
							}
						}
						else
						{
							if(arr.inArray(deviceId)){
							htmlTable += '<tr><td width="30%" align="left" valign="middle" height="20">' + tmpPoly[i] + '</td><td width="70%" align="left" valign="middle">In the Area</td></tr>';
							//document.getElementById('distance').innerHTML += "<br>Your are in the Boundry of Area : <b>" + tmpPoly[i] + "</b>";
							}
						}
						/*if(sendsms == true){
							$.post( 
								"php/area_inout.php?cmd=area_inout&device="+deviceId, 
								function(data){
									if(data == "1"){
										//sendsms()
										alert(data)
										$.post("php/area_inout.php?cmd=update&val=0&device="+deviceId, function(data){});
									}
								}
							);
						}*/
					}
					else{
						if(arr.inArray(deviceId)){
							//alert("Outside the boundry of " + tmpPoly[i] + ", Distance : " + distance[i]);
						   /* if(sendsms == true){
								$.post( 
									"php/area_inout.php?cmd=area_inout&device="+deviceId, 
									function(data){
										if(data == "0"){
											//sendsms()
											alert(data)
											$.post("php/area_inout.php?cmd=update&val=1&device="+deviceId, function(data){});
										}
									}
								);
							}*/
							marker.setImage(iconExcluded);
						//if(isMove == true && (isEditing==false && distance[i] != 999999999))
						//{
							htmlTable += '<tr><td width="30%" align="left" valign="middle" height="20">' + tmpPoly[i] + '</td><td width="70%" align="left" valign="middle" style="color:#FF0000; font-weight:bold">' + distance[i] + ' KM</td></tr>';
							//document.getElementById('distance').innerHTML += "<br>Your are out of the Boundry of Area : <b>" + tmpPoly[i] + "</b>, Distance : " + distance[i] + "M";
						//}
						}
					}
			}
//			htmlTable += '<tr><td height="10" align="left" valign="middle" bgcolor="#F2F2F2">&nbsp;</td><td align="left" valign="middle" bgcolor="#f2f2f2">&nbsp;</td></tr>';
			htmlTable += '</table>';
			document.getElementById('distance').innerHTML = htmlTable;
				if(bookmark_status == ""){
					bookmark_status = document.getElementById("placemark").checked;
				}
				//alert(bookmark_status);
				if(bookmark_status == true)
				{
					setTimeout("displayBookmark(true)",1000);
				}
		}
	}
	Array.prototype.inArray = function (value)
	{
	// Returns true if the passed value is found in the
	// array. Returns false if it is not.
	var i;
	for (i=0; i < this.length; i++) 
	{
		if (this[i] == value) 
		{
		return true;
		}
	}
	return false;
	};
	function clearSelection()
	{
		
		//alert(btnCreate.value);
		isEditing=false;
		btnCreate.value = "Create"
		if(newpoly!=null){
			newpoly.disableEditing();
			map.removeOverlay(newpoly);
		}
	}
	