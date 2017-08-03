function moveMarker(map, latlng) {
    marker.setPosition(latlng);
    map.panTo(latlng);
}

function addStartMarker(img_url, map, coord) {
    console.log("latitude " + coord.latitude + " longitude " + coord.longitude);
    new google.maps.Marker({
        position: {
            lat: parseFloat(coord.latitude)
            , lng: parseFloat(coord.longitude)
        }
        , map: map
        , icon: img_url
    });
}

function createMarker($vehicle, base_url) {
    $icon = base_url + "/assets/images/gps/marker-parked.png";
    $fill_color = "#ffff00";
    $speed_message = "Parked";
    console.log("vehicle " + $vehicle[0].speed);
    $vehicle_id = $vehicle[0].vehicle_id;
    if (parseInt($vehicle[0].ignition) == 1 && parseInt($vehicle[0].speed) > 0 && parseInt($vehicle[0].speed) < parseInt($vehicle[0].max_speed_limit)) {
        $icon = base_url + "/assets/images/gps/marker-moving.png";
        $fill_color = "#4CAF50";
        $speed_message = "Moving";
    }
    if (parseInt($vehicle[0].ignition) == 0 || parseInt($vehicle[0].speed) == 0) {
        $icon = base_url + "/assets/images/gps/marker-parked.png";
        $fill_color = "#ffff00";
        $speed_message = "Parked";
    }
    if (parseInt($vehicle[0].ignition) == 1 && parseInt($vehicle[0].speed) == 0) {
        $icon = base_url + "/assets/images/gps/marker-idle.png";
        $fill_color = "#0000ff";
        $speed_message = "Idle";
    }
    if ((parseInt($vehicle[0].speed) > parseInt($vehicle[0].max_speed_limit)) || (parseInt($vehicle[0].speed_alert) > 0) || (parseInt($vehicle[0].arm_alert) > 0) || (parseInt($vehicle[0].power_cut) > 0)) {
        $icon = base_url + "/assets/images/gps/marker-danger.png";
        $fill_color = "#ff0000";
        $speed_message = "Alert";
    }
    $direction = $vehicle[0].orientation;
    $vehicle_name = $vehicle[0].plate_no;
    $position = {
        lat: parseFloat($vehicle[0].latitude)
        , lng: parseFloat($vehicle[0].longitude)
    };
    $marker_type = 'vehicle';
    $fetch_vehicle_url = "<?php echo site_url('vehicles/fetch_vehicle'); ?>";
    $fetch_vehicle_url = $fetch_vehicle_url + "/" + $vehicle.vehicle_id;
    $vehicle_history_url = " <?php echo site_url('gps_history/view_history'); ?>";
    $vehicle_history_url = $vehicle_history_url + "/" + $vehicle.vehicle_id;
    //  $title = $vehicle.plate_no;
    $title = '<br><div style="text-align: left"><b>Plate No.:</b>' + '<div style="float: right">' + $vehicle[0].plate_no + '</div></div>' + '<div style="text-align: left"><b>Speed:</b>' + '<div style="float: right;">' + $vehicle[0].speed + 'Km/h</div></div>' + '<div style="text-align: left"><b>Address: &nbsp; &nbsp; </b>' + '<div style="float: right">' + $vehicle[0].address + '</div></div>' + '<div style="text-align: left"><b>Last seen:</b>' + '<div style="float: right">' + Date('d-m-Y H:i:s', $vehicle[0].last_seen) + '</div></div>' + '<br><div style="text-align: left"><a class="btn btn-xs btn-primary" href=' + $fetch_vehicle_url + '> View Details</a> <div style="float: right"> <a class="btn btn-xs btn-primary" href=' + $vehicle_history_url + '>View History</a> </button></div> </div>';
    $plate_no = $vehicle[0].plate_no + " - " + $vehicle[0].model;
    addMarker($marker_type, title, $position, $icon, $fill_color, $vehicle_id, $plate_no, $direction);
}

function addMarker(type, title, location, image, fill_col, vehicle_id, plate_no, direction) {
    if (parseFloat(direction) != false) {
        direction = parseFloat(direction);
    }
    else {
        direction = 0;
    }
    var infowindow = new google.maps.InfoWindow({
        content: title
    });
    if (parseFloat(direction) != false) {
        direction = parseFloat(direction);
    }
    else {
        direction = 0;
    }
    var sec_iw_map = new google.maps.InfoWindow({
        content: plate_no
    });
    var iw_map = new google.maps.InfoWindow({
        content: title
    });
    marker = new google.maps.Marker({
        position: location
        , map: map
        , content: title
        , title: plate_no
        , icon: image
        , vehicle_id: vehicle_id
        , vehicle_name: plate_no
        , direction: direction
    });
    google.maps.event.addListener(marker, "mouseover", function (event) {
        sec_iw_map.open(map, marker);
    });
    google.maps.event.addListener(marker, "mouseout", function (event) {
        sec_iw_map.close();
    });
    google.maps.event.addListener(marker, "click", function (event) {
        iw_map.open(map, this);
    });
    google.maps.event.addListener(iw_map, 'closeclick', function () {
        iw_map.close();
    });
}

function getDistinctRoute(pathCoords) {
    distPathCoords = [];
    var count = 0;
    if (pathCoords.length > 1) {
        distPathCoords.push(pathCoords[0]);
        latitude = pathCoords[0].latitude;
        longitude = pathCoords[0].longitude;
        for (i = 1; i < pathCoords.length; i++) {
            if (latitude != pathCoords[i].latitude && longitude != pathCoords[i].longitude) {
                distPathCoords.push(pathCoords[i]);
                latitude = pathCoords[i].latitude;
                longitude = pathCoords[i].longitude;
                count = 0;
                console.log("stoppedPoints " + count);
            }
            else {
                count = count + 1;
                console.log("stoppedPoints " + count);
                if (count > 60) {
                    console.log("stoppedPoints " + count);
                }
            }
        }
    }
    console.log("stoppedPoints " + count);
    return distPathCoords;
}

function drawRoute(map, pathCoords, routes_array) {
    path = [];
    routes_array = clear(routes_array);
    if (pathCoords.length > 1) {
        for (i = 0; i < pathCoords.length; i++) {
            path.push(new google.maps.LatLng(pathCoords[i].latitude, pathCoords[i].longitude));
        }
        routes_array = load_route(path);
        show_routes(routes_array);
    }
    return routes_array;
}

function load_route(path) {
    var lineSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
    };
    routes_array.push(new google.maps.Polyline({
        path: path
        , icons: [{
            icon: lineSymbol
            , offset: '0'
            , repeat: '100px'
          }]
        , geodesic: true
        , strokeColor: '#FF0000'
        , strokeOpacity: 1.0
        , strokeWeight: 2
    }));
    return routes_array;
}

function show_routes(routes_array) {
    var len = routes_array.length;
    for (var i = 0; i < len; i++) {
        routes_array[i].setMap(map);
    }
}

function clear(routes_array) {
    var len = routes_array.length;
    console.log("clear " + len);
    for (var i = 0; i < len; i++) {
        console.log("i " + i);
        routes_array[i].setMap(null);
    }
    routes_array = [];
    return show_routes;
}
var v = 200;

function pause() {
    isPaused = true;
}

function play() {
    isPaused = false;
}
var stop;
var isPaused = false;

function animateRoute(map, pathCoords) {
    var i = 0;
    setTimeout(function () {
        stop = setInterval(function () {
            if (!isPaused) {
                if (i == pathCoords.length - 1) stop_interval();
                var latlng = new google.maps.LatLng(pathCoords[i].latitude, pathCoords[i].longitude);
                moveMarker(map, latlng);
                console.log("latitude: " + pathCoords[i].latitude + " longitude: " + pathCoords[i].longitude);
                latitude = pathCoords[i].latitude
                longitude = pathCoords[i].longitude
                i = 1 + i;
            }
        }, 500)
    }, 2000);
}

function stop_interval() {
    clearInterval(stop);
}
//Get the distance of the calculated points
function getDistance(pathCoords, coords = null) {
    distance = 0;
    if (coords == null) {
        if (pathCoords.length > 1) {
            for (i = 0; i < pathCoords.length; i++) {
                if (i > 0) {
                    distance = distance + getDistanceFromLatLonInKm(pathCoords[i - 1], pathCoords[i])
                }
            }
        }
    }
    else {
        if (coords.length > 1) {
            for (i = 0; i < pathCoords.length; i++) {
                if (i > 0) {
                    distance = distance + getDistanceFromLatLonInKm(pathCoords[i - 1], pathCoords[i])
                }
            }
        }
    }
    return distance;
}

function getDistanceFromLatLonInKm(point_1, point_2) {
    var R = 6371000; // Radius of the earth in km
    var dLat = deg2rad(point_2.latitude - point_1.latitude); // deg2rad below
    var dLon = deg2rad(point_2.longitude - point_1.longitude);
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(deg2rad(point_1.latitude)) * Math.cos(deg2rad(point_2.latitude)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c; // Distance in km
    return d;
}

function deg2rad(deg) {
    return deg * (Math.PI / 180)
}

function getCoordinates(result) {
    var currentRouteArray = result.routes[0]; //Returns a complex object containing the results of the current route
    var currentRoute = currentRouteArray.overview_path; //Returns a simplified version of all the coordinates on the path
    obj_newPolyline = new google.maps.Polyline({
        map: map
    }); //a polyline just to verify my code is fetching the coordinates
    var path = obj_newPolyline.getPath();
    for (var x = 0; x < currentRoute.length; x++) {
        var pos = new google.maps.LatLng(currentRoute[x].kb, currentRoute[x].lb)
        latArray[x] = currentRoute[x].kb; //Returns the latitude
        lngArray[x] = currentRoute[x].lb; //Returns the longitude
        path.push(pos);
    }
}

function getDateDifference(startDate, endDate) {
    var seconds = (new Date(endDate).getTime() - new Date(startDate).getTime()) / 1000;
    // console.log(" seconds  " + seconds);
    if (seconds > (60 * 60 * 3)) return false;
    else return true;
}