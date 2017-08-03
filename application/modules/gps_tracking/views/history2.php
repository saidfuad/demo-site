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
    var polylines = [];
    var stepDisplay;
    var markerArray = [];
    var position;
    var marker = null;
    var polyline = null;
    var poly2 = null;
    var speed = 0.000005
        , wait = 1;
    var infowindow = null;
    var myPano;
    var panoClient;
    var nextPanoId;
    var timerHandle = null;
    var pathCoords;

    function createMarker(latlng, label, html) {
        var contentString = '<b>' + label + '</b><br>' + html;
        var marker = new google.maps.Marker({
            position: latlng
            , map: map
            , title: label
            , zIndex: Math.round(latlng.lat() * -100000) << 5
        });
        marker.myname = label;
        // gmarkers.push(marker);
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.setContent(contentString);
            infowindow.open(map, marker);
        });
        return marker;
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: <?= $map_lat; ?>
                , lng: <?= $map_long; ?>
            }
            , zoom: 16
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
    }

    function moveMarker(map, marker, latlng) {
        marker.setPosition(latlng);
        map.panTo(latlng);
    }

    function autoRefresh(map) {
        var i, route, marker;
        route = new google.maps.Polyline({
            path: []
            , geodesic: true
            , strokeColor: '#FF0000'
            , strokeOpacity: 1.0
            , strokeWeight: 2
            , editable: false
            , map: map
        });
        marker = new google.maps.Marker({
            map: map
            , icon: "http://maps.google.com/mapfiles/ms/micons/blue.png"
        });
        for (i = 0; i < pathCoords.length; i++) {
            console.log("Did i get here " + pathCoords[i].latitude);
            setTimeout(function (coords) {
                var latlng = new google.maps.LatLng(coords.latitude, coords.longitude);
                route.getPath().push(latlng);
                moveMarker(map, marker, latlng);
            }, 200 * i, pathCoords[i]);
        }
    }
    //google.maps.event.addDomListener(window, 'load', initialize);
    //  google.maps.event.addDomListener(window, 'load', initMap);
    $.ajax({
        url: "<?php echo base_url('index.php/gps_tracking/vehicle_history') ?>"
        , dataType: "JSON"
    }).done(function (data) {
        pathCoords = data.history;
        console.log("Did i get here " + pathCoords.length);
        autoRefresh(map);
    });
    /*$.ajax({
        url: "<?php echo base_url('index.php/gps_tracking/vehicle_history') ?>"
        , complete: function (response) {
            pathCoords = JSON.stringify(response.history);
            console.log("Did i get here " + pathCoords.length);
            autoRefresh(map);
        }
    });*/
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
        max-height: 300px !important;
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
    
    .no-vehicle-overlay {
        position: absolute;
        background: rgba(31, 35, 42, .8);
        width: 100%;
        height: 100%;
        z-index: 20000;
    }
    
    .no-vehicle-title {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -384.58px;
        margin-top: -159.5px;
        background: rgba(31, 35, 42, .5);
        border: none;
        color: #fff;
        padding: 16px;
        font-size: 14px;
        border-radius: 5px;
    }
    
    .btn.btn-success {
        color: rgba(31, 35, 42, 1);
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
                <br><a class="btn btn-success" href="<?php echo base_url(" index.php/vehicles/add_vehicle ") ?>"><span class="fa fa-car fa-fw"></span> &nbsp; Add Vehicles</a>
                <br>
                <br> </div>
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
                    var refresh = setInterval(function () {}, refresh_interval);
                    $('.radio-rt').on('click', function () {
                        $('#refresh-interval').val($(this).attr('data-time'));
                        refresh_interval = parseFloat($('#refresh-interval').val()) * 1000;
                        clearInterval(refresh);
                        refresh = null;
                        refresh = setInterval(function () {}, refresh_interval);
                        if (refresh_interval != 600000) {
                            display_alert('Refreshing in ' + refresh_interval / 1000 + ' Seconds');
                            hideAlert();
                        }
                    });
                    var count = 0;

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
                    $('.live-vehicle-list').on('click', function () {
                        var no_of_v = vehicle_markers.length;
                        var vehicle_id = $(this).attr('vehicle-id');
                        /* Empty for now */
                        var time_interval = "3600";
                        var startLat, startLng, endLat, endLng, stopLat, stopLng;
                        var stopover = [];
                        $.ajax({
                            type: "POST"
                            , cache: false
                            , data: {
                                vehicle_id: vehicle_id
                                , time_interval: time_interval
                            }
                            , url: "<?php echo base_url('index.php/gps_tracking/history_track_points') ?>"
                            , success: function (response) {
                                res = JSON.parse(response);
                                /* Start */
                                startLat = res[0].latitude;
                                startLng = res[0].longitude;
                                /* Stop */
                                endLat = res[res.length - 1].latitude;
                                endLng = res[res.length - 1].longitude;
                                /* Points */
                                for (var i = 1; i < res.length - 1; i++) {
                                    stopLat = res[i].latitude;
                                    stopLng = res[i].longitude;
                                    stopover.push({
                                        "lat": stopLat
                                        , "lng": stopLng
                                    });
                                }
                                calcRoute(startLat, startLng, stopover, endLat, endLng);
                            }
                        });
                    });
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
            <!-- Local 
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBn6m_W3hRMPg5nDlmqsRkaO3kE1LZ1HX4&libraries=places,drawing&callback=initMap">
</script> -->
            <!-- Server>-->
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhN58m494AvmP1ZApv51FghPbvtmvisjc&libraries=geometry&callback=initMap" async defer>
            </script>