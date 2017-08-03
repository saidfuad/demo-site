<script src="<?php echo base_url('assets/angular_moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/app.js') ?>"></script>
<script type="text/javascript">
    var infowindow;
    var vehicle_markers = [];
    var landmark_markers = [];
    var pop_marker = [];
    var map_pop;
    var map;
    var iw_map;
    var zones = [];
    var routes = [];
    var directionsService;
    var directionsDisplay;
    var vehiclePath;
    var vehiclePaths = [];
    var apiKey = 'AIzaSyCfKP5H8r9ohlPjH_CbddIefMbeCirz7-U';
    //var apiKey = 'AIzaSyAhN58m494AvmP1ZApv51FghPbvtmvisjc';
    var polylines = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: <?= $map_lat; ?>
                , lng: <?= $map_long; ?>
            }
            , zoom: 15
            , mapTypeId: google.maps.MapTypeId.ROADMAP
            , heading: 90
            , tilt: 45
            , streetViewControl: true
            , streetViewControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
                , position: google.maps.ControlPosition.RIGHT_CENTER
            }
            , fullscreenControl: true
            , fullscreenControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
                , position: google.maps.ControlPosition.RIGHT_CENTER
            }
            , zoomControl: true
            , zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
                , position: google.maps.ControlPosition.RIGHT_CENTER
            }
        , });
        iw_map = new google.maps.InfoWindow({
            content: ''
        });
        infowindow = new google.maps.InfoWindow({
            content: ''
        });
        //load_landmarks();
        //load_zones();
        //load_routes();
    }
    /*function load_landmarks() {
        $.ajax({
            type: "POST"
            , url: "<?php echo base_url('index.php/settings/get_company_landmarks') ?>"
            , data: {
                company: 'this company'
            }
            , success: function (data) {
                landmarks = JSON.parse(data);
                //alert(landmarks.length);
                if (landmarks.length > 0) {
                    for (var row in landmarks) {
                        if (landmarks[row].gmap_icon == 0) {
                            $icon = "<?= base_url('" + landmarks[row].icon_path + "')?>";
                        }
                        else {
                            $icon = landmarks[row].icon_path;
                        }
                        $pos = {
                            lat: parseFloat(landmarks[row].latitude, 10)
                            , lng: parseFloat(landmarks[row].longitude)
                        };
                        //console.log(parseInt(landmarks[row].latitude, 10));
                        landmark_markers.push(new google.maps.Marker({
                            position: $pos
                            , map: map
                            , icon: $icon
                            , content: landmarks[row].landmark_name
                            , scaledSize: new google.maps.Size(20, 20)
                        , }));
                    }
                    var llength = landmark_markers.length;
                    for (var i = 0; i < llength; i++) {
                        landmark_markers[i].setIcon(({
                            url: landmark_markers[i].icon
                            , size: new google.maps.Size(71, 71)
                            , origin: new google.maps.Point(0, 0)
                            , anchor: new google.maps.Point(12, 24)
                            , scaledSize: new google.maps.Size(24, 24)
                        }));
                        google.maps.event.addListener(landmark_markers[i], "mouseout", function (event) {
                            iw_map.close();
                        });
                        google.maps.event.addListener(landmark_markers[i], "click", function (event) {
                            map.setCenter(this.getPosition());
                            map.setZoom(15);
                            map.setTilt(45);
                            console.log(event.latLng.lat() + ',' + event.latLng.lng());
                        });
                        google.maps.event.addListener(landmark_markers[i], "mouseover", function (event) {
                            iw_map.setContent(this.get("content"));
                            iw_map.open(map, this);
                        });
                    }
                }
            }
        });
    }*/
    /*function load_zones() {
        $.ajax({
            type: "POST"
            , url: "<?php echo base_url('index.php/gps_tracking/get_company_zones') ?>"
            , data: {
                company: 'this company'
            }
            , success: function (data) {
                data = JSON.parse(data);
                mapCords = [];
                size_zones = data.zones.length;
                size_vertices = data.vertices.length
                if (size_zones > 0) {
                    for (var zone in data.zones) {
                        var zona = [];
                        for (var vertex in data.vertices) {
                            if (data.vertices[vertex].zone_id == data.zones[zone].zone_id) {
                                //$zona.push({lat:data.vertices[vertex].latitude, lng:data.vertices[vertex].longitude});
                                zona.push(new google.maps.LatLng(parseFloat(data.vertices[vertex].latitude), parseFloat(data.vertices[vertex].longitude)));
                            }
                            mapCords.push(new google.maps.LatLng(parseFloat(data.vertices[vertex].latitude), parseFloat(data.vertices[vertex].longitude)));
                        }
                        zones.push(new google.maps.Polygon({
                            paths: zona
                            , strokeColor: data.zones[zone].zone_color
                            , strokeOpacity: 0.8
                            , strokeWeight: 2
                            , fillColor: data.zones[zone].zone_color
                            , fillOpacity: 0.35
                            , zone: data.zones[zone].zone_name
                            , address: data.zones[zone].address
                        }));
                        zones[zones.length - 1].setMap(map);
                        google.maps.event.addListener(zones[zones.length - 1], 'mouseover', function (event) {
                            var contentString = "<strong>Zone</strong>:" + this.zone + "<br><strong>Address</strong>: " + this.address;
                            //alert(contentString);
                            iw_map.setContent(contentString);
                            iw_map.setPosition(event.latLng);
                            iw_map.open(map);
                        });
                        google.maps.event.addListener(zones[zones.length - 1], 'mouseout', function (event) {
                            iw_map.close();
                        });
                    }
                }
            }
        });
    }*/
    /*function load_routes() {
        $.ajax({
            type: "POST"
            , url: "<?php echo base_url('index.php/gps_tracking/get_company_map_display_routes') ?>"
            , data: {
                company: 'this company'
            }
            , success: function (data) {
                response = JSON.parse(data);
                for (var row in response) {
                    //alert(JSON.parse(response[row].raw_route));
                    $route = response[row].raw_route;
                    if ($route.length != 0) {
                        directionsDisplay.setDirections(JSON.parse($route));
                    }
                }
                //directionsDisplay.setDirections(response);
            }
        });
        return false;
    }*/
    function rotate90() {
        var heading = map.getHeading() || 0;
        map.setHeading(heading + 90);
    }

    function autoRotate() {
        // Determine if we're showing aerial imagery.
        if (map.getTilt() !== 0) {
            window.setInterval(rotate90, 3000);
        }
    }
</script>
<style>
    html {
        overflow: hidden !important;
        padding: 0 !important
    }
    
    .controls {
        position: fixed;
        top: 128px;
        left: 168px;
        height: 32px;
        width: 232px;
        outline: none;
        z-index: 1000;
    }
    
    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
    }
    
    #pac-input:focus {
        border-color: #4d90fe;
    }
    
    .pac-container {
        font-family: Roboto;
    }
    
    #type-selector {
        color: #fff;
        background-color: #131b26;
        text-transform: uppercase;
        line-height: 24px;
        font-size: 10px;
        margin-bottom: 6px;
        list-style: none;
        box-shadow: 0 0 0 0;
        padding: 6px;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
    }
    
    #toggle-vehicles {
        color: #fff;
        cursor: pointer;
        position: absolute;
        top: 10px;
        margin-left: 10px;
        transition: .3s ease-in all;
    }
    
    #toggle-vehicles:hover {
        color: #c1d72e;
        transition: .2s ease-in all;
    }
    
    #map-pop {
        display: none;
        position: absolute;
        top: 40px;
        left: 5%;
        width: 95%;
        border: 1px solid #303641;
        /*border:2px solid #eee;*/
        z-index: 3000;
        border-radius: 0px;
        background: #fff;
        border-radius: 5px;
    }
    
    .live-vehicle-list {
        opacity: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: scroll;
        background: #c1d72e;
        transition: .4s ease all;
    }
    
    .live-vehicle-list li {
        height: 32px;
        list-style-type: none;
        width: 100%;
        overflow-y: hidden;
        vertical-align: middle;
        text-align: center;
        padding: 4px;
        color: rgba(19, 27, 38, .58);
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .live-vehicle-list li span {
        margin-right: 8px;
        font-size: 12px;
        color: rgba(19, 27, 38, .4);
    }
    
    .live-vehicle-list li:hover {
        color: rgba(19, 27, 38, 1);
    }
    
    .live-vehicle-list li a {
        text-decoration: none;
        color: #fff;
    }
    
    .form-search {
        opacity: 0;
        padding: 8px;
        background: #c1d72e;
        transition: .4s ease all;
    }
    
    #vehicle-search {
        background: rgba(19, 27, 38, .2);
        border: none;
        height: 28px;
        color: #fff;
        font-size: 12px;
        text-align: center;
    }
    
    .blinking-toggle {
        background: #c1d72e !important;
    }
    
    .more {
        background-color: #c1d72e;
        color: #131b26;
        width: 260px;
        border-radius: 10px;
        text-transform: uppercase;
        margin-top: 7px;
        padding: 5px;
    }
    
    .control-bar {
        position: fixed;
        background: #fff;
        left: 0;
        right: 0;
        bottom: 0;
        margin-left: 200px;
        height: 64px;
        color: #fff;
    }
    
    #vehicle-search::-webkit-input-placeholder {
        color: #fff;
        letter-spacing: 1px;
    }
    
    #vehicle-search::-moz-placeholder {
        /* Firefox 18- */
        color: #fff;
        letter-spacing: 1px;
    }
    
    #vehicle-search::-moz-placeholder {
        /* Firefox 19+ */
        color: #fff;
        letter-spacing: 1px;
    }
    
    #vehicle-search::-ms-input-placeholder {
        color: #fff;
        letter-spacing: 1px;
    }
    
    .legend-marker {
        width: 40px;
        height: 36px;
    }
    
    #legend-markers {
        color: #131b26;
    }
    
    textarea:focus,
    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="datetime"]:focus,
    input[type="datetime-local"]:focus,
    input[type="date"]:focus,
    input[type="month"]:focus,
    input[type="time"]:focus,
    input[type="week"]:focus,
    input[type="number"]:focus,
    input[type="email"]:focus,
    input[type="url"]:focus,
    input[type="search"]:focus,
    input[type="tel"]:focus,
    input[type="color"]:focus,
    .uneditable-input:focus {
        border-color: rgba(126, 239, 104, 0.8);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.0) inset, 0 0 8px rgba(126, 239, 104, 0);
        outline: 0 none;
    }

    .no-vehicle-overlay{
        position: absolute;
        background: rgba(31, 35, 42,.8);
        width: 100%;
        height: 100%;
        z-index: 20000;
    }

    .no-vehicle-title{
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -384.58px;
        margin-top: -159.5px;
        background: rgba(31, 35, 42,.5);
        border: none;
        color: #fff;
        padding: 16px;
        font-size: 14px;
        border-radius: 5px;
    }

    .btn.btn-success{
        color: rgba(31, 35, 42,1);
    }

</style>

<?php $_SESSION['refresh_time'] = 10000; ?>
<input type="hidden" id="refresh-session" class="" value="0" />
<input type="hidden" id="refresh-interval" class="" value="600" />

<?php if(sizeof($vehicleList) == 0){ ?>

<div class="no-vehicle-overlay">

    <div class="col-sm-5 col-md-5 bg-crumb no-vehicle-title" align="center">
        <h2><i class="fa fa-car"></i> No Vehicles</h2>
        <p>You currently have no vehicles tracked. Click on the 'Add Vehicles' button to add a new vehicle and start tracking it from here.</p>
        <br><a class="btn btn-success" href="<?php echo base_url("index.php/vehicles/add_vehicle") ?>"><span class="fa fa-car fa-fw"></span> &nbsp; Add Vehicles</a><br><br>
    </div>

</div>

<?php } ?>

<div class="container-fluid fleet-view">
    <div class="row" style="margin-top: -64px">
        <div class="overlay_no_devices"></div>
        <div class="col-md-12" style="margin-top: 44px;  margin-left: -8px;">
            <div class="row">
                <div class="controls" id="map-vehicle-zone">
                    <ul> <span id="toggle-vehicles" class="fa fa-m fa-bars"></span>
                        <li id="type-selector"> &nbsp; &nbsp; Click to select vehicle</li>
                        <div class="form-search">
                            <input class="form-control" name="vehicle-search" id="vehicle-search" placeholder="Search Plate No." /> </div>
                        <ul class="live-vehicle-list" style="max-height:100%;padding:0px !important;overflow-y:auto;">
                            <?php echo $vehicleList; ?>
                        </ul>
                    </ul>
                </div>
                <script>
                    $("#type-selector").on('click', function () {
                        if ($("#toggle-vehicles").hasClass("fa-bars")) {
                            $("#toggle-vehicles").removeClass("fa-bars");
                            $("#toggle-vehicles").addClass("fa-times");
                            var selector = document.getElementById("type-selector").innerHTML = "Hide all vehicles";
                            $(".live-vehicle-list").css({
                                opacity: 1
                            });
                            $(".form-search").css({
                                opacity: 1
                            });
                        }
                        else {
                            $("#toggle-vehicles").removeClass("fa-times");
                            $("#toggle-vehicles").addClass("fa-bars");
                            var selector = document.getElementById("type-selector").innerHTML = " &nbsp; &nbsp; Click to select vehicle";
                            $(".live-vehicle-list").css({
                                opacity: 0
                            });
                            $(".form-search").css({
                                opacity: 0
                            });
                        }
                    });
                    $("#toggle-vehicles").on('click', function () {
                        if ($("#toggle-vehicles").hasClass("fa-bars")) {
                            $("#toggle-vehicles").removeClass("fa-bars");
                            $("#toggle-vehicles").addClass("fa-times");
                            var selector = document.getElementById("type-selector").innerHTML = "Hide all vehicles";
                            $(".live-vehicle-list").css({
                                opacity: 1
                            });
                            $(".form-search").css({
                                opacity: 1
                            });
                        }
                        else {
                            $("#toggle-vehicles").removeClass("fa-times");
                            $("#toggle-vehicles").addClass("fa-bars");
                            var selector = document.getElementById("type-selector").innerHTML = " &nbsp; &nbsp; Click to select vehicle";
                            $(".live-vehicle-list").css({
                                opacity: 0
                            });
                            $(".form-search").css({
                                opacity: 0
                            });
                        }
                    });
                </script>
                <div class="col-md-12" style="height:100%;">
                    <div id="map_canvas" class="row" style="height:100vh; width: 100vw;"></div>
                    <div class="control-bar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="" style="">
                                    <div class="col-md-4">
                                        <div class="form-inline" role="form" style="color: #131b26">
                                            <div class="form-group">
                                                <label style="color: #131b26">Refresh after:</label>
                                                <input class="form-control radio-rt" type="radio" name="refresh-secs" data-time="15" <?php if ($_SESSION[ 'refresh_time']==16) {?>checked
                                                <?php } ?>> 15 secs </div>
                                            <div class="form-group">
                                                <input class="form-control radio-rt" type="radio" name="refresh-secs" data-time="30" <?php if ($_SESSION[ 'refresh_time']==30) {?>checked
                                                <?php } ?>> 30 secs </div>
                                            <div class="form-group">
                                                <input class="form-control radio-rt" type="radio" name="refresh-secs" data-time="45" <?php if ($_SESSION[ 'refresh_time']==45) {?>checked
                                                <?php } ?>> 45 secs </div>
                                            <div class="form-group">
                                                <input class="form-control radio-rt" type="radio" name="refresh-secs" data-time="600" checked>disabled </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-inline" role="form">
                                            <div class="form-group col-sm-3" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-moving.png'); ?>" class="legend-marker" /> Moving </div>
                                            <div class="form-group  col-sm-3" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-idle.png'); ?>" class="legend-marker" /> Idle </div>
                                            <div class="form-group  col-sm-3" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-parked.png'); ?>" class="legend-marker" /> parked </div>
                                            <div class="form-group  col-sm-3" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-danger.png'); ?>" class="legend-marker" /> Alert </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- //. Content -->
    </div>
    <!-- /.row -->
</div>
<script type="text/javascript">
    $(function () {
        var refresh_interval = parseFloat($('#refresh-interval').val()) * 1000;
        var refresh = setInterval(function () {
            refresh_vehicle_locations(2);
        }, refresh_interval);
        refresh_vehicle_locations(1);
        $('.radio-rt').on('click', function () {
            $('#refresh-interval').val($(this).attr('data-time'));
            refresh_interval = parseFloat($('#refresh-interval').val()) * 1000;
            clearInterval(refresh);
            refresh = null;
            refresh = setInterval(function () {
                refresh_vehicle_locations(2);
            }, refresh_interval);
            if (refresh_interval != 600000) {
                display_alert('Refreshing in ' + refresh_interval / 1000 + ' Seconds');
                hideAlert();
            }
        });
        var count = 0;

        function geocodeLatLng(position) {
            var geocoder = new google.maps.Geocoder;
            //  var latlng = {lat: lat, lng: lng};
            geocoder.geocode({
                'location': position
            }, function (results, status) {
                if (status === 'OK') {
                    if (results[1]) {
                        console.log("response found " + JSON.stringify(results[1].formatted_address));
                        return JSON.stringify(results[1].formatted_address);
                    }
                    else {
                        console.log(results[1]);
                        return "";
                    }
                }
                else {
                    console.log("error");
                    return "";
                }
            });
        }

        function refresh_vehicle_locations(times) {
            var query = $('#vehicle-search').val().trim();
            var refresh_session = parseInt($('#refresh-session').val(), 10);
            display_alert('Refreshing Map');
            if (times > 1) {
                clearVehicleMarkers();
            }
            $('#refresh-session').val(refresh_session + 1);
            $.ajax({
                type: "POST"
                , data: {
                    query: query
                }
                , url: "<?php echo base_url('index.php/admin/refresh_grid') ?>"
                , success: function (response) {
                    res = JSON.parse(response);
                    console.log(res);
                    var message = [];
                    var LatLngList = [];
                    for (var i = 0; i < res.length; i++) {
                        $vehicle_id = res[i].vehicle_id;
                        $device_id = res[i].device_id;
                        if (parseInt(res[i].ignition) == 1 && parseInt(res[i].speed) > 0 && parseInt(res[i].speed) < parseInt(res[i].max_speed_limit)) {
                            $icon = "<?= base_url('assets/images/gps/icons/marker-moving.png') ?>";
                            $fill_color = "#4CAF50";
                            $speed_message = "Moving";
                        }
                        if (parseInt(res[i].ignition) == 0 && parseInt(res[i].speed) == 0) {
                            $icon = "<?= base_url('assets/images/gps/icons/marker-parked.png') ?>";
                            $fill_color = "#ffff00";
                            $speed_message = "Parked";
                            message.push($speed_message);
                        }
                        if (parseInt(res[i].ignition) == 1 && parseInt(res[i].speed) == 0) {
                            $icon = "<?= base_url('assets/images/gps/icons/marker-idle.png') ?>";
                            $fill_color = "#0000ff";
                            $speed_message = "Idle";
                            message.push($speed_message);
                        }
                        if ((parseInt(res[i].speed) > parseInt(res[i].max_speed_limit)) || (parseInt(res[i].speed_alert) > 0) || (parseInt(res[i].arm_alert) > 0) || (parseInt(res[i].power_cut) > 0)) {
                            $icon = "<?= base_url('assets/images/gps/icons/marker-danger.png') ?>";
                            $fill_color = "#ff0000";
                            $speed_message = "Alert";
                            message.push($speed_message);
                        }
                        if (parseInt(res[i].ignition) == 1) {
                            $ignition = '<button class="btn btn-success btn-xs">On</button>';
                        }
                        else {
                            $ignition = '<button class="btn btn-default btn-xs">Off</button>';
                        }
                        $direction = res[i].orientation;
                        $vehicle_name = res[i].plate_no;
                        $position = {
                            lat: parseFloat(res[i].latitude)
                            , lng: parseFloat(res[i].longitude)
                        };
                        $marker_type = 'vehicle';
                        $title = '<br><div style="text-align: left"><b>Vehicle Name:</b>' + '<div style="float: right">' + res[i].model + '</div></div>' + '<div style="text-align: left"><b>Plate No.:</b>' + '<div style="float: right">' + res[i].plate_no + '</div></div>' + '<br><div style="text-align: left"><b>Ignition:</b>' + '<div style="float: right">' + $ignition + '</div></div>' + '</div><div style="text-align: left"><b>Speed:</b>' + '<div style="float: right">' + res[i].speed + 'Km/h</div></div>' + '</div><div style="text-align: left"><b>Address:</b>' + '<div style="float: right">' + res[i].address + '</div></div>' + '</div><center><p class="more"><b> Click Icon For More Details' + '</b></p></center>';
                        $plate_no = res[i].plate_no + " - " + res[i].model;
                        addMarker($marker_type, $title, $position, $icon, $fill_color, $vehicle_id, $device_id, $plate_no, $direction);
                    }
                    for (var i = 0; i < vehicle_markers.length; i++) {
                        google.maps.event.addListener(vehicle_markers[i], "mouseout", function (event) {
                            iw_map.close();
                        });
                        google.maps.event.addListener(vehicle_markers[i], "click", function (event) {
                            var posl = this.getPosition();
                            console.log(event.latLng.lat() + ',' + event.latLng.lng());
                            map.setCenter(posl);
                            map.setZoom(15);
                            map.setTilt(45);
                            $('#map-pop').fadeIn(1000);
                            $('.overshadow').fadeIn(1000);
                            //get_vehicle_details(this.vehicle_id, this.device_id, this.content, this.icon, this.fill, this.direction, posl);
                        });
                        google.maps.event.addListener(vehicle_markers[i], "mouseover", function (event) {
                            iw_map.setContent(this.get("content"));
                            iw_map.open(map, this);
                        });
                        google.maps.event.addListener(vehicle_markers[i], "doubleclick", function (event) {
                            alert();
                        });
                    }
                    hideAlert();
                    if (res.length) {
                        console.log
                        fit_bounds();
                    }
                }
            });
        }

        function addMarker(type, title, location, image, fill_col, vehicle_id, device_id, asset_name, direction) {
            if (parseFloat(direction) != false) {
                direction = parseFloat(direction);
            }
            else {
                direction = 0;
            }
            vehicle_markers.push(new google.maps.Marker({
                position: location
                , map: map
                , icon: image
                , /*  icon:  {
url: image,
size: new google.maps.Size(60, 70),
origin: new google.maps.Point(0, 0),
anchor: new google.maps.Point(17, 34),
scaledSize: new google.maps.Size(60, 70)
}
            ,*/
                content: title, //animation: google.maps.Animation.DROP,
                vehicle_id: vehicle_id
                , device_id: device_id
                , vehicle_name: asset_name
                , direction: direction
            }));
            /* vehicle_markers.push(new google.maps.Marker({
                position: location
                , map: map
                , icon: {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                    , strokeColor: '#101010'
                    , strokeWeight: 1
                    , fillColor: fill_col
                    , fillOpacity: 1
                    , rotation: direction
                    , anchor: new google.maps.Point(0, 0)
                    , scale: 5
                }
                , content: title
                , animation: google.maps.Animation.DROP
                , vehicle_id: vehicle_id
                , device_id: device_id
                , fill: fill_col
                , vehicle_name: asset_name
                , direction: direction
            }));*/
        }

        function fit_bounds() {
            var bounds = new google.maps.LatLngBounds();
            var vsize = vehicle_markers.length;
            //console.log('Size:' + vehicle_markers.length);
            for (i = 0; i < vsize; i++) {
                bounds.extend(vehicle_markers[i].getPosition());
            }
            map.fitBounds(bounds);
        }

        function fit_bounds_pop() {
            var bounds = new google.maps.LatLngBounds();
            for (i = 0; i < pop_marker.length; i++) {
                bounds.extend(pop_marker[i].getPosition());
            }
            map_pop.fitBounds(bounds);
        }

        function display_alert(Message) {
            $('.page-alert').html('<p align="center">' + Message + '</p>');
            $('.page-alert').animate({
                right: '10px'
            }, 2000);
        }

        function hideAlert() {
            $('.page-alert').animate({
                right: '-600px'
            }, 2000);
        }

        function clearVehicleMarkers() {
            var vMarkers = vehicle_markers.length;
            console.log('to be cleared' + vMarkers);
            if (vMarkers > 0) {
                for (var i = 0; i < vMarkers; i++) {
                    vehicle_markers[i].setMap(null);
                    console.log('clearing');
                }
            }
            console.log('final size: ' + vehicle_markers.length);
        }

        function clearAllMarkers() {
            var countMarkers = markers_map.length;
            if (countMarkers > 0) {
                for (var i = 0; i < countMarkers; i++) {
                    markers_map[i].setMap(null);
                }
            }
        }
        var togle = setInterval(function () {
            toggle_blink();
        }, 1000);

        function toggle_blink() {
            if ($('.live-vehicle-list').find('.blinking')) {
                $('.blinking').toggleClass('blinking-toggle');
            }
        }
        $('#vehicle-search').keyup(function () {
            var no_of_v = vehicle_markers.length;
            var query = $(this).val().trim();
            var myValRe = new RegExp(query, 'i');
            var vehicle_lis = $('.live-vehicle-list').find('li');
            var vli_length = vehicle_lis.length;
            $('.live-vehicle-list').find('li').removeClass('blinking');
            //console.log(no_of_v);
            if (query.length) {
                for (var i = 0; i < no_of_v; i++) {
                    var lookup_text = vehicle_markers[i].vehicle_name;
                    //console.log(lookup_text + '        ' +query);
                    if (lookup_text.match(myValRe)) {
                        vehicle_markers[i].setVisible(true);
                        fit_bounds();
                    }
                    else {
                        vehicle_markers[i].setVisible(false);
                        fit_bounds();
                    }
                }
                for (var i = 0; i < vli_length; i++) {
                    var lookup_tex = $(vehicle_lis[i]).html();
                    console.log(lookup_tex + '        ' + query);
                    if (lookup_tex.match(myValRe)) {
                        //$(vehicle_lis[i]).show();
                        $(vehicle_lis[i]).addClass('blinking');
                    }
                    else {
                        //$(vehicle_lis[i]).hide();
                        $(vehicle_lis[i]).removeClass('blinking');
                        $(vehicle_lis[i]).removeClass('blinking-toggle');
                    }
                }
            }
        });
        $('.live-vehicle-list').on('click', 'li', function () {
            var no_of_v = vehicle_markers.length;
            var vehicle_id = $(this).attr('vehicle-id');
            for (var i = 0; i < no_of_v; i++) {
                var vid = vehicle_markers[i].vehicle_id;
                if (vid == vehicle_id) {
                    vehicle_markers[i].setVisible(true);
                    var posl = vehicle_markers[i].getPosition();
                    window.setTimeout(function () {
                        map.panTo(posl);
                        map.setZoom(50);
                    }, 1000);
                }
                else {
                    console.log('No asset with that id on the map');
                    vehicle_markers[i].setVisible(false);
                    //fit_bounds();
                }
            }
        });
        $('.filter-map').change(function () {
            $('#vehicle-search').val('');
            var filter = $(this).val();
            var owner = $('#select-owners').val();
            var type = $('#select-types').val();
            var cat = $('#select-categories').val();
            var group = $("#select-groups").val();
            filter_grid(owner, type, cat, group);
        });

        function filter_grid(owner, type, cat, group) {
            display_alert('Searching Map');
            clearVehicleMarkers();
            $.ajax({
                type: "POST"
                , cache: false
                , data: {
                    owner: owner
                    , type: type
                    , cat: cat
                    , group: group
                }
                , url: "<?php echo base_url('index.php/admin/filter_grid') ?>"
                , success: function (response) {
                    res = JSON.parse(response);
                    var message = [];
                    var LatLngList = [];
                    //alert(res.vehicles.length);
                    for (var vehicle in res.vehicles) {
                        $vehicle_id = res[i].vehicle_id;
                        $device_id = res[i].device_id;
                        if (parseInt(res[i].ignition) == 1 && parseInt(res[i].speed) > 0 && parseInt(res[i].speed) < parseInt(res[i].max_speed_limit)) {
                            $icon = "<?= base_url('assets/images/gps/marker-moving.png') ?>";
                            $fill_color = "#00ff00";
                            $speed_message = "Vehicle Moving";
                            //message.push($speed_message);
                        }
                        else if (parseInt(res[i].ignition) == 1 && parseInt(res[i].speed) == 0) {
                            $icon = "<?= base_url('assets/images/gps/marker-idle.png') ?>";
                            $fill_color = "#0000ff";
                            $speed_message = "Vehicle is idle";
                            message.push($speed_message);
                        }
                        else if (parseInt(res[i].speed) > parseInt(res[i].max_speed_limit)) {
                            $icon = "<?= base_url('assets/images/gps/marker-danger.png') ?>";
                            $fill_color = "#ff0000";
                            $speed_message = "Alert";
                            message.push($speed_message);
                        }
                        else if (parseInt(res[i].ignition) == 0) {
                            $icon = "<?= base_url('assets/images/gps/marker-parked.png') ?>";
                            $fill_color = "#00ff00";
                            $speed_message = "Vehicle has stopped";
                            message.push($speed_message);
                        }
                        $time = res[i].tm;
                        $date = res[i].dt;
                        $date = $date.split(" ");
                        $date = $date[0];
                        $date = new Date($date + " " + $time);
                        if (res[i].ignition == 1) {
                            $ignition = '<button class="btn btn-success btn-xs">On</button>';
                        }
                        else {
                            $ignition = '<button class="btn btn-default btn-xs">Off</button>';
                        }
                        $vehicle_name = res[i].plate_no;
                        $position = {
                            lat: parseFloat(res[i].latitude)
                            , lng: parseFloat(res[i].longitude)
                        };
                        $marker_type = 'vehicle';
                        $title = 'Model: ' + res[i].model + ' <br>Plate No.:' + res[i].plate_no + ' <br>Ignition: ' + $ignition + ' <br>Speed: ' + res[i].speed + "Kmh <br>Last Update: " + $date;
                        addMarker($marker_type, $title, $position, $icon, $fill_color, $vehicle_id, $device_id);
                        //  Make an array of the LatLng's of the markers you want to show
                    }
                    for (var i = 0; i < vehicle_markers.length; i++) {
                        google.maps.event.addListener(vehicle_markers[i], "mouseout", function (event) {
                            iw_map.close();
                        });
                        google.maps.event.addListener(vehicle_markers[i], "click", function (event) {
                            var posl = this.getPosition();
                            console.log(event.latLng.lat() + ',' + event.latLng.lng());
                            map.setCenter(posl);
                            map.setZoom(15);
                            map.setTilt(45);
                            $('#map-pop').fadeIn(1000);
                            //get_vehicle_details(this.vehicle_id, this.device_id, this.content, this.icon, posl);
                        });
                        google.maps.event.addListener(vehicle_markers[i], "mouseover", function (event) {
                            iw_map.setContent(this.get("content"));
                            iw_map.open(map, this);
                        });
                        google.maps.event.addListener(vehicle_markers[i], "doubleclick", function (event) {
                            alert();
                        });
                    }
                    hideAlert();
                    if (res.vehicles.length) {
                        fit_bounds();
                    }
                }
            });
            ///hideAlert();
        }
        $(document).on('mouseenter', '.lbl-info', function (e) {
            $('#info-div').find('.lbl-info').removeClass('info-highlighted');
            $(this).addClass('info-highlighted');
            var id_ = $(this).attr('tyr');
            $('#bg-vehicle-area').find('.info-tyre').removeClass('blink_');
            $('#bg-vehicle-area').find('#' + id_).addClass('blink_');
        });
        $(document).on('mouseleave', '.lbl-info', function () {
            $('#info-div').find('.lbl-info').removeClass('info-highlighted');
            $('#bg-vehicle-area').find('.info-tyre').removeClass('blink_');
        });
        $(document).on('mouseenter', '.info-tyre', function (e) {
            $('#tyre-info-div').stop().hide();
            var pressure = $(this).attr('pressure');
            var temperature = $(this).attr('temperature');
            var axle = $(this).attr('axle');
            var tyre = $(this).attr('tyre');
            var status = 'Normal';
            var btn_type = 'btn-primary';
            if (temperature < 50 || pressure < 50 || temperature > 78 || pressure > 78) {
                status = 'Critical';
                btn_type = 'btn-danger';
            }
            else if (temperature < 60 || pressure < 60 || temperature > 70 || pressure > 70) {
                status = 'Warning';
                btn_type = 'btn-warning';
            }
            var left = parseInt(e.pageX) + 10;
            var top = parseInt(e.pageY) + 10;
            //alert();
            $('#tyre-info-div').find('#pressure-info').html(pressure + '<sup>PSI</sup>');
            $('#tyre-info-div').find('#temperature-info').html(temperature + '<sup>0C</sup>');
            $('#tyre-info-div').find('#status-info').html(status).removeClass('btn-danger').removeClass('btn-warning').addClass(btn_type);
            $('#tyre-info-div').find('#axle-info').html('Axle : ' + axle);
            $('#tyre-info-div').find('#tyre-info').html('Tyre : ' + tyre);
            $('#tyre-info-div').stop().show().css({
                'left': left + 'px'
                , 'top': top + 'px'
            });
            $('#info-div').find('.lbl-info').removeClass('info-highlighted');
            $('#info-div').find('.' + tyre).parent().addClass('info-highlighted');
        });
        $(document).on('mouseleave', '.info-tyre', function () {
            $('#tyre-info-div').stop().hide();
            $('#info-div').find('.lbl-info').removeClass('info-highlighted');
        });
        $(".page-header").hide();
        $('#view_vehicle').on('click', function () {
            //var checked = $(this).attr('checked');
            if ($(this).is(':checked')) {
                $('#map-vehicle-zone').fadeIn(1000);
            }
            else {
                $('#map-vehicle-zone').fadeOut(1000);
            }
        });
        $('#close-map-pop').click(function () {
            $('#map-pop').fadeOut(1000);
            $('.overshadow').fadeOut(1000);
        });
    });
</script>
<!-- Local -->
 <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBn6m_W3hRMPg5nDlmqsRkaO3kE1LZ1HX4&libraries=places,drawing&callback=initMap">
</script>
<!-- Server -->
<!--<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyAhN58m494AvmP1ZApv51FghPbvtmvisjc&libraries=places,drawing&callback=initMap">
</script>-->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhN58m494AvmP1ZApv51FghPbvtmvisjc&callback=initMap" async defer></script>
 -->