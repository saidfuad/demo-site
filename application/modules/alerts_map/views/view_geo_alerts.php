<title>
    <?php echo $title ?>
</title>
<link href="<?php echo base_url('assets/css/styles/default.css') ?>" type="text/css" rel="stylesheet" id="style_color" />
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/system/hawk_logo.png">

<style>
    #side_menu {
        position: absolute; z-index: 2000;
        background-color: #FFF;
        height: 100%;
        box-shadow: 0px 0px 20px 2px #C0C1C2;
        left: 0;
        width: 256px;
        transition: .3s ease-in all;
    }

    #slide{
        position: absolute; z-index: 2000;
        top: 100px;
        right: -55px;
        box-shadow: 0px 0px 20px 2px #C0C1C2;
    }
</style>
<div class="col-md-12" style="">
    <div class="row">       
        <div class="col-md-2" id="side_menu">
            <button id="slide" class="btn btn-sm btn-primary"><span class="fa fa-chevron-left">Close</span></button>
            <img class="img-responsive" src="<?= base_url('assets/images/system/hawk.gif'); ?>" alt="Chania">
            <div class="col-md-12" id="">
                <div class="row">
                    <div id="title"></div>
                    <div id="plate_no" align=""></div>
                    <b id="distance_title">Distance: </b><div id="distance"></div>
                    <b>Date: </b><div id="start_date"></div>
                    <b>Address: </b><div id="start_address"></div>
                    <!--                    <b>Stop Date: </b><div id="stop_date"></div>
                                        <b>Stop Address: </b><div id="stop_address"></div>-->
                </div>
            </div>
        </div>

        <div class="col-md-12" style="">
            <div id="map_canvas" class="row" style="height: 100%; width: 100%;"></div>
        </div>
    </div>
</div>

<link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js') ?>"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?= $this->config->item('map_key') ?>&libraries=places,drawing&callback=initMap">
</script>

<script type="text/javascript">

    $(function () {
        var open = true;
        $("#slide").click(function () {
            if (open) {
                $("#side_menu").css({
                    'margin-left': '-256px'
                });
                $("#slide span").text('Open');
                open = false;
            } else {
                $("#side_menu").css({
                    'margin-left': '0px'
                });
                $("#slide span").text('Close');
                open = true;
            }
        });
    });

    var iw_map;
    var marker = null;
    var polyline = null;
    var infowindow = null;
    istPathCoords = [], snapPaths;
    var distance = 0;
    var latitude, longitude;
    var placeIdArray = [];
    var polylines = [], routes_array = [];
    var snappedCoordinates = [];
    var map;
    var title;
    var startAddress, startDate;
    var fillColor;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: <?= $vehicle_details['start_lat']; ?>
                , lng: <?= $vehicle_details['start_lng']; ?>
            }
            , zoom: 17
            , mapTypeId: google.maps.MapTypeId.ROADMAP
            , heading: 90
            , tilt: 45
            , mapTypeControl: true
            , mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.TOP_CENTER
            }
            , streetViewControl: false
            , streetViewControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
                , position: google.maps.ControlPosition.RIGHT_CENTER
            }
            , fullscreenControl: false
            , fullscreenControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
                , position: google.maps.ControlPosition.RIGHT_CENTER
            }
            , zoomControl: true
            , zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
                , position: google.maps.ControlPosition.RIGHT_CENTER
            }});

        var vehicleDetails = <?= json_encode($vehicle_details) ?>;

        $icon = "<?= base_url('assets/images/gps/marker-danger.png') ?>";
        $position = {
            lat: parseFloat(vehicleDetails.start_lat)
            , lng: parseFloat(vehicleDetails.start_lng)
        };

        startAddress = vehicleDetails.start_address;
        startDate = vehicleDetails.start_date;
        distance = vehicleDetails.distance;
        fillColor = vehicleDetails.fill_color;

        if (distance !== null) {
            $('#distance').html((distance >= 1000) ? (distance / 1000) + " km" : distance + " m");
        }else{
            $('#distance_title').hide();
            $('#distance').hide();
        }

        $('#plate_no').html("<h4>" + vehicleDetails.plate_no + "</h4>");
        $('#start_address').html(startAddress);
        $('#start_date').html(startDate);

        addMarker(vehicleDetails.plate_no, $position, $icon, vehicleDetails.plate_no);

        var geoData = <?= json_encode($geo_data) ?>;

        loadGeoData(map, vehicleDetails.type, vehicleDetails.name, geoData);
    }

    function addMarker(title, location, icon, plate_no) {

        var infowindow = new google.maps.InfoWindow({
            content: "<b>PLATE: </b> " + plate_no + "</br><b>START ADDRESS: </b> " + startAddress + "</br><b>START TIME: </b> " + startDate
        });

        var sec_iw_map = new google.maps.InfoWindow({
            content: plate_no
        });
        var iw_map = new google.maps.InfoWindow({
            content: title
        });
        marker = new google.maps.Marker({
            position: location
            , map: map
            , content: plate_no
            , title: title
            , icon: icon
        });
        google.maps.event.addListener(marker, "mouseover", function (event) {
            infowindow.open(map, marker);
        });
        google.maps.event.addListener(marker, "mouseout", function (event) {
            infowindow.close();
        });
        google.maps.event.addListener(marker, "click", function (event) {
            infowindow.open(map, this);
        });
        google.maps.event.addListener(iw_map, 'closeclick', function () {
            infowindow.close();
        });
    }

    function loadGeoData(map, type, name, geoData, distance = null) {
        var title = $("#title");
        switch (type) {
            case 'geofence':
                title.html("<h5>GEOFENCE INFRINGEMENT</h5>");
                loadGeofence(map, type, name, geoData);
                break;
            case 'route':

                title.html("ROUTE INFRINGEMENT");
                loadRoute(map, type, name, geoData);
                break;
            case 'landmark':
                //info.html(plateNo + "IS IN " + name + " landmark.");
                //loadLandmark(map, plateNo, type, name, geoData);
                break;
    }
    }

    function loadGeofence(map, type, name, geoData) {
        var path = [];
        if (geoData.length > 1) {
            for (i = 0; i < geoData.length; i++) {
                console.log("LAT: " + geoData[i].latitude + " LNG: " + geoData[i].longitude);
                path.push(new google.maps.LatLng(geoData[i].latitude, geoData[i].longitude));
            }
        }

        var geofence = new google.maps.Polygon({
            path: path,
            strokeColor: '#BBD8E9',
            strokeOpacity: 1,
            strokeWeight: 3,
            fillColor: fillColor
        });

        geofence.setMap(map);
    }

    function loadRoute(map, type, name, geoData) {
        var path = [];
        if (geoData.length > 1) {
            for (i = 0; i < geoData.length; i++) {
                console.log("LAT: " + geoData[i].latitude + " LNG: " + geoData[i].longitude);
                path.push(new google.maps.LatLng(geoData[i].latitude, geoData[i].longitude));
            }
        }

        var route = new google.maps.Polyline({
            path: path,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        route.setMap(map);
    }


</script>
