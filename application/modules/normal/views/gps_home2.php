<script src="<?php echo base_url('assets/angular_moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/app.js') ?>"></script>
<script type="text/javascript">
    var infowindow;
    var vehicle_markers = [];
    var array_inf_win = [];
    var array_inf_win2 = [];
    var landmark_markers = [];
    var pop_marker = [];
    var map_pop;
    var map;
    var zones = [];
    var routes = [];
    var directionsService;
    var directionsDisplay;
    var vehiclePath;
    var vehiclePaths = [];
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

    }


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
        padding: 0 !important;
    }

    .controls {
        position: fixed;
        top: 204px;
        left: 172px;
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
        display: none;
        width: 100%;
        max-height: 300px;
        overflow-y: scroll;
        background: rgba(193, 215, 46,.8);
        transition: .4s ease all;
    }

    .live-vehicle-list li {
        height: 36px;
        list-style-type: none;
        width: 100%;
        overflow-y: hidden;
        vertical-align: middle;
        text-align: center;
        padding: 4px;
        color: rgba(19, 27, 38, .58);
        cursor: pointer;
        padding-top: 9px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .live-vehicle-list li span {
        margin-right: 16px;
        font-size: 12px;
        color: rgba(19, 27, 38, .4);
    }

    .live-vehicle-list li:hover {
        color: #fff;
        background: rgba(19, 27, 38, 1);
        transition: .4s ease all;
    }

    .live-vehicle-list li:hover > span {
        color: #fff;
        transition: .4s ease all;
    }

    .live-vehicle-list li a {
        text-decoration: none;
        color: #fff;
    }

    .form-search {
        display: none;
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

    #show-all {
        display: none;
        background: #c1d72e;
        height: 36px;
        list-style-type: none;
        width: 100%;
        overflow-y: hidden;
        vertical-align: middle;
        text-align: center;
        padding: 4px;
        color: rgba(19, 27, 38, .58);
        cursor: pointer;
        padding-top: 9px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        transition: .4s ease all;
    }

    .blinking-toggle {
        background: #c1d72e !important;
    }

    .more {
        text-align: center;
        //background-color: #c1d72e;
        color: #131b26;
        width: 300px;
        text-transform: uppercase;
        margin-top: 20px;
        margin-bottom: 0px;
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
        height: 30px;
    }

    #legend-markers {
        line-height: 36px;
        color: #131b26;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    #legend-markers a {
        text-align: center;
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
        background: rgba(31, 35, 42,.8);
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
        background: rgba(31, 35, 42,.5);
        border: none;
        color: #fff;
        padding: 16px;
        font-size: 14px;
        border-radius: 5px;
    }

    .btn.btn-success {
        color: rgba(31, 35, 42,1);
    }

    .radio-rt {
        font-size: 13px;
    }

    .badge {
        color: #fff;
        position: absolute;
        width: 20px;
        height: 20px;
        font-size: 10px;
        text-align: center;
        padding: 0;
        line-height: 18px;
        top: -10px;
        right: 78px;
        border-radius: 50%;
    }

    #badge1 span {
        background: #00796B;
    }

    #badge2 span {
        background: #FFA000;
    }

    #badge3 span {
        background: #0288D1;
    }

    #badge4 span {
        background: #D32F2F;
    }

    #badge5 span {
        background: #616161;
    }

    .notify-click-map {
        position: absolute;
        z-index: 2000;
        bottom: 96px;
        right: 56px;
        height: 32px;
        font-size: 12px;
        width: auto;
        padding: 6px;
        color: #fff;
        border-radius: 5px;
        background: rgba(19, 27, 38,.7);
        outline: none;
        z-index: 1000;
    }

    .vehicle-details {
        position: absolute;
        z-index: 2000;
        bottom: 140px;
        right: 56px;
        height: auto;
        font-size: 12px;
        width: auto;
        padding: 8px;
        color: #000;
        border-radius: 5px;
        border: .05em solid rgba(19, 27, 38,1);
        background: rgba(255,255,255,.9);
        //background: rgba(19, 27, 38,.7);
        //background: rgba(193, 215, 46,.5);
        outline: none;
        z-index: 2000;
        display: none;
        transition: .4s ease all;
    }

    #button-details {
        text-align: center;
        margin: 16px 0 8px 0;
    }

    #space {
        width: 100%;
        height: 1px;
        background: rgba(19, 27, 38,.6);
    }

    #hawk-image {
        text-align: center;
    }

    #plate-no {
        margin: 6px;
    }

    #plate-no span {
        font-weight: 600;
        float: right;
    }

    #model {
        margin: 6px;
    }

    #model span {
        font-weight: 600;
        float: right;
    }

    #status {
        margin: 6px;
    }

    #status span {
        font-weight: 600;
        float: right;
    }

    #speed {
        margin: 6px;
    }

    #speed span {
        font-weight: 600;
        float: right;
    }

    #last-location {
        margin: 6px;
    }

    #last-location span {
        font-weight: 600;
        float: right;
    }

    #close {
        font-size: 14px;
        color: rgba(19, 27, 38,1);
        cursor: pointer;
    }
</style>

<?php $_SESSION['refresh_time'] = 30; ?>
<input type="hidden" id="refresh-session" class="" value="0" />
<input type="hidden" id="refresh-interval" class="" value="30" />

    <div class="notify-click-map">Click the hawk icon on the map for vehicle details</div>   

<div class="container-fluid fleet-view">
    <div class="row" style="margin-top: -64px">
        <div class="overlay_no_devices"></div>
        <div class="col-md-12" style="margin-top: 44px; margin-left: 16px; padding: 0">
            <div class="row">
                <div data-toggle='tooltip' data-original-title='Click to show a list of all of your vehicles. Toggle to hide.' data-placement='right' class="controls" id="map-vehicle-zone">
                    <ul>
                        <span id="toggle-vehicles" class="fa fa-m fa-bars"></span>
                        <li id="type-selector">&nbsp; &nbsp; Click to select vehicle</li>
                        <div class="form-search">
                            <input class="form-control" name="vehicle-search" id="vehicle-search" placeholder="Search Plate No." />
                        </div>
                        <li id="show-all">Show All</li>
                        <ul class="live-vehicle-list" style="max-height: 100%; padding: 0px !important; overflow-y: auto;">
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
                                display: 'block'
                            });
                            $(".form-search").css({
                                display: 'block'
                            });
                            $("#show-all").css({
                                display: 'block'
                            });
                        } else {
                            $("#toggle-vehicles").removeClass("fa-times");
                            $("#toggle-vehicles").addClass("fa-bars");
                            var selector = document.getElementById("type-selector").innerHTML = " &nbsp; &nbsp; Click to select vehicle";
                            $(".live-vehicle-list").css({
                                display: 'none'
                            });
                            $(".form-search").css({
                                display: 'none'
                            });
                            $("#show-all").css({
                                display: 'none'
                            });
                        }
                    });
                    $("#toggle-vehicles").on('click', function () {
                        if ($("#toggle-vehicles").hasClass("fa-bars")) {
                            $("#toggle-vehicles").removeClass("fa-bars");
                            $("#toggle-vehicles").addClass("fa-times");
                            var selector = document.getElementById("type-selector").innerHTML = "Hide all vehicles";
                            $(".live-vehicle-list").css({
                                display: 'block'
                            });
                            $(".form-search").css({
                                display: 'block'
                            });
                            $("#show-all").css({
                                display: 'block'
                            });
                        } else {
                            $("#toggle-vehicles").removeClass("fa-times");
                            $("#toggle-vehicles").addClass("fa-bars");
                            var selector = document.getElementById("type-selector").innerHTML = " &nbsp; &nbsp; Click to select vehicle";
                            $(".live-vehicle-list").css({
                                display: 'none'
                            });
                            $(".form-search").css({
                                display: 'none'
                            });
                            $("#show-all").css({
                                display: 'none'
                            });
                        }
                    });
                </script>
                <div class="col-md-12" style="height: 100%; width: 100%; padding: 0 !important">
                    <div id="map_canvas" class="row" style="height: 100vh; width: 100%;"></div>
                    <div class="control-bar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="" style="">
                                    <div class="col-md-4">
                                        <div class="form-inline" role="form" style="color: #131b26">
                                            <label style="color: #131b26">Refresh after: &nbsp; </label>
                                            <div class="form-group">
                                                <input type="radio" name="refresh-secs" data-time="15" <?php if ($_SESSION['refresh_time'] == 16) { ?>checked
                                                       <?php } ?>>
                                                15 secs
                                            </div>
                                            &nbsp;
                                            &nbsp;
                                            <div class="form-group">
                                                <input type="radio" name="refresh-secs" data-time="30" <?php if ($_SESSION['refresh_time'] == 30) { ?>checked
                                                       <?php } ?>>
                                                30 secs
                                            </div>
                                            &nbsp;
                                            &nbsp;
                                            <div class="form-group">
                                                <input type="radio" name="refresh-secs" data-time="45" <?php if ($_SESSION['refresh_time'] == 45) { ?>checked
                                                       <?php } ?>>
                                                45 secs
                                            </div>
                                            &nbsp;
                                            &nbsp;
                                            <div class="form-group">
                                                <input type="radio" name="refresh-secs" data-time="3600" <?php if ($_SESSION['refresh_time'] == 3600) { ?>checked
                                                       <?php } ?>>
                                                Disable
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-inline" role="form">
                                            <div class="form-group col-sm-2" id="legend-markers">
                                                <img src="<?php echo base_url('assets/images/gps/marker-moving.png'); ?>" class="legend-marker" />
                                                <a id="badge1" href="#"><span class="badge"><?php echo $moving_vehicles; ?></span></a>
                                                Moving
                                            </div>
                                            <div class="form-group  col-sm-2" id="legend-markers">
                                                <img src="<?php echo base_url('assets/images/gps/marker-idle.png'); ?>" class="legend-marker" />
                                                <a id="badge2" href="#"><span class="badge"><?php echo $idle_vehicles; ?></span></a>
                                                Idle
                                            </div>
                                            <div class="form-group  col-sm-2" id="legend-markers">
                                                <img src="<?php echo base_url('assets/images/gps/marker-parked.png'); ?>" class="legend-marker" />
                                                <a id="badge3" href="#"><span class="badge"><?php echo $parked_vehicles; ?></span></a>
                                                Parked
                                            </div>
                                            <div class="form-group  col-sm-2" id="legend-markers">
                                                <img src="<?php echo base_url('assets/images/gps/marker-danger.png'); ?>" class="legend-marker" />
                                                <a id="badge4" href="#"><span class="badge"><?php echo $alert_vehicles; ?></span></a>
                                                Alert
                                            </div>
                                            <div class="form-group  col-sm-2" id="legend-markers">
                                                <img src="<?php echo base_url('assets/images/gps/marker-disabled.png'); ?>" class="legend-marker" />
                                                <a id="badge5" href="#"><span class="badge"><?php echo $disabled_vehicles; ?></span></a>
                                                Inactive
                                            </div>
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
        var res;
        var check = true;
        var refresh_interval = parseFloat($('#refresh-interval').val()) * 1000;
        var refresh = setInterval(function () {
            refresh_vehicle_locations(2);
        }, refresh_interval);
        refresh_vehicle_locations(1);
        $('.radio-rt').on('click', function () {
            $('#refresh-interval').val($(this).attr('data-time'));
            refresh_interval = parseFloat($('#refresh-interval').val()) * 1000;
            clearInterval(refresh);
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
                    } else {
                        console.log(results[1]);
                        return "";
                    }
                } else {
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
                , url: "<?php echo base_url('index.php/normal/refresh_grid') ?>"
                , success: function (response) {
                    res = JSON.parse(response);
                    displayMarkers(res);
                }
            });
        }

        function displayMarkers(res)
        {
            console.log("res length " + res.length);
            var message = [];
            var LatLngList = [];
            for (var i = 0; i < res.length; i++) {
                $vehicle_id = res[i].vehicle_id;
                $device_id = res[i].device_id;
                if (parseInt(res[i].ignition) == 1 && parseInt(res[i].speed) > 0 && parseInt(res[i].speed) < parseInt(res[i].max_speed_limit)) {
                    $icon = "<?= base_url('assets/images/gps/marker-moving.png') ?>";
                    $fill_color = "#4CAF50";
                    $speed_message = "Moving";
                }
                if (parseInt(res[i].ignition) == 0 && parseInt(res[i].speed) == 0) {
                    $icon = "<?= base_url('assets/images/gps/marker-parked.png') ?>";
                    $fill_color = "#ffff00";
                    $speed_message = "Parked";
                    message.push($speed_message);
                }
                if (parseInt(res[i].ignition) == 1 && parseInt(res[i].speed) == 0) {
                    $icon = "<?= base_url('assets/images/gps/marker-idle.png') ?>";
                    $fill_color = "#0000ff";
                    $speed_message = "Idle";
                    message.push($speed_message);
                }
                if ((parseInt(res[i].speed) > parseInt(res[i].max_speed_limit)) || (parseInt(res[i].speed_alert) > 0) || (parseInt(res[i].arm_alert) > 0) || (parseInt(res[i].power_cut) > 0)) {
                    $icon = "<?= base_url('assets/images/gps/marker-danger.png') ?>";
                    $fill_color = "#ff0000";
                    $speed_message = "Alert";
                    message.push($speed_message);
                }
                if (parseInt(res[i].ignition) == 1) {
                    $ignition = 'On';
                } else {
                    $ignition = 'Off';
                }
                $direction = res[i].orientation;
                $vehicle_name = res[i].plate_no;
                $position = {
                    lat: parseFloat(res[i].latitude)
                    , lng: parseFloat(res[i].longitude)
                };
                $marker_type = 'vehicle';
                $fetch_vehicle_url = "<?php echo site_url('vehicles/fetch_vehicle'); ?>";
                $fetch_vehicle_url = $fetch_vehicle_url + "/" + res[i].vehicle_id;
                $vehicle_history_url = " <?php echo site_url('gps_history/view_history'); ?>";
                $vehicle_history_url = $vehicle_history_url + "/" + res[i].vehicle_id;
                $vehicle_playback_url = " <?php echo site_url('gps_history/view_playback'); ?>";
                $vehicle_playback_url = $vehicle_playback_url + "/" + res[i].vehicle_id;
                //  $title = res[i].plate_no;
                $title = '<br><div style="text-align: left"><b>Plate No.:</b>' + '<div style="float: right">' + res[i].plate_no + '</div></div>' +
                        '<div style="text-align: left"><b>Ignition:</b>' + '<div style="float: right">' + $ignition + '</div></div>' +
                        '<div style="text-align: left"><b>Speed:</b>' + '<div style="float: right;">' + res[i].speed + 'Km/h</div></div>' +
                        '<div style="text-align: left"><b>Address: &nbsp; &nbsp; </b>' + '<div style="float: right">' + res[i].address + '</div></div>' +
                        '<div style="text-align: left"><b>Last seen:</b>' + '<div style="float: right">' + formatDate(res[i].last_seen) + '</div></div>' +
                        '<br> <div> <a  style="float:left" class="btn btn-xs btn-primary" href=' + $fetch_vehicle_url + '>Details</a><a  style="float:center; margin-left:10px" class="btn btn-xs btn-primary" href=' + $vehicle_history_url + '>History</a> <a  style="float:right" class="btn btn-xs btn-primary" href=' + $vehicle_playback_url + '>Playback</a></div>';
                $plate_no = res[i].plate_no + " - " + res[i].model;
                addMarker($marker_type, $title, $position, $icon, $fill_color, $vehicle_id, $device_id, $plate_no, $direction);
            }

            if (res.length) {
                fit_bounds();
            }
            /* Refresh Map Legend */
            window.base_url = <?php echo json_encode(base_url()); ?>;
            $("#badge1").load(base_url + "index.php/normal #badge1");
            $("#badge2").load(base_url + "index.php/normal #badge2");
            $("#badge3").load(base_url + "index.php/normal #badge3");
            $("#badge4").load(base_url + "index.php/normal #badge4");
            $("#badge5").load(base_url + "index.php/normal #badge5");
        }

        function formatDate(date_in)
        {

            var data = date_in.split(" ");
            var date_segment = data[0].split('-');
            return date_segment[2] + "/" + date_segment[1] + "/" + date_segment[0] + " " + data[1];
        }

        function addMarker(type, title, location, image, fill_col, vehicle_id, device_id, plate_number, direction) {
            if (parseFloat(direction) != false) {
                direction = parseFloat(direction);
            } else {
                direction = 0;
            }

            var sec_iw_map = new google.maps.InfoWindow({
                content: plate_number
            });

            var iw_map = new google.maps.InfoWindow({
                content: title
            });

            var marker = new google.maps.Marker({
                position: location,
                map: map,
                content: title,
                title: plate_number,
                icon: image,
                vehicle_id: vehicle_id,
                device_id: device_id,
                vehicle_name: plate_number,
                direction: direction
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

            vehicle_markers.push(marker);
            // hideAlert();

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
                    } else {
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
                    } else {
                        //$(vehicle_lis[i]).hide();
                        $(vehicle_lis[i]).removeClass('blinking');
                        $(vehicle_lis[i]).removeClass('blinking-toggle');
                    }
                }
            }
        });
        $('.live-vehicle-list').on('click', 'li', function () {

            /* Hide Vehicles List */
            $("#toggle-vehicles").removeClass("fa-times");
            $("#toggle-vehicles").addClass("fa-bars");

            var selector = document.getElementById("type-selector").innerHTML = " &nbsp; &nbsp; Click to select vehicle";

            $(".live-vehicle-list").css({
                display: 'none'
            });
            $(".form-search").css({
                display: 'none'
            });
            $("#show-all").css({
                display: 'none'
            });

            var no_of_v = vehicle_markers.length;
            var vehicle_id = $(this).attr('vehicle-id');

            for (var i = 0; i < no_of_v; i++) {

                var vid = vehicle_markers[i].vehicle_id;

                if (vid == vehicle_id) {

                    vehicle_markers[i].setVisible(true);
                    var posl = vehicle_markers[i].getPosition();

                    window.setTimeout(function () {
                        map.panTo(posl);
                        map.setZoom(18);
                    }, 1000);
                } else {
                    console.log('No asset with that id on the map');
                    vehicle_markers[i].setVisible(false);
                    //fit_bounds();
                }
            }
        });

        $('#close').on('click', function () {
            $('.vehicle-details').css({'opacity': 0});
            refresh_vehicle_locations(1);
        });

        $('#show-all').on('click', function () {

            /* Hide Vehicles List */
            $("#toggle-vehicles").removeClass("fa-times");
            $("#toggle-vehicles").addClass("fa-bars");

            var selector = document.getElementById("type-selector").innerHTML = " &nbsp; &nbsp; Click to select vehicle";

            $(".live-vehicle-list").css({
                display: 'none'
            });
            $(".form-search").css({
                display: 'none'
            });
            $("#show-all").css({
                display: 'none'
            });

            $('.vehicle-details').css({'opacity': 0});
            refresh_vehicle_locations(1);
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
                , url: "<?php echo base_url('index.php/normal/filter_grid') ?>"
                , success: function (response) {
                    res = JSON.parse(response);
                    displayMarkers(res);
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
            } else if (temperature < 60 || pressure < 60 || temperature > 70 || pressure > 70) {
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
        //$('#view_vehicle').on('click', function () {
        //    //var checked = $(this).attr('checked');
        //    if ($(this).is(':checked')) {
        //        $('#map-vehicle-zone').fadeIn(1000);
        //    }
        //    else {
        //        $('#map-vehicle-zone').fadeOut(1000);
        //    }
        //});
        $('#close-map-pop').click(function () {
            $('#map-pop').fadeOut(1000);
            $('.overshadow').fadeOut(1000);
        });
    });
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?= $this->config->item("map_key") ?>&libraries=places,drawing&callback=initMap">
</script>
