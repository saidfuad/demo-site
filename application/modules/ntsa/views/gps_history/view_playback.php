<script src="<?php echo base_url('assets/angular_moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/app.js') ?>"></script>
<script src="<?php echo base_url('assets/js/map_script.js') ?>"></script>
<script type="text/javascript">
    var iw_map;
    var marker = null;
    var polyline = null;
    var infowindow = null;
    var pathCoords, distPathCoords = []
        , snapPaths;
    var distance = 0;
    var latitude, longitude;
    var placeIdArray = [];
    var polylines = []
        , routes_array = [];
    var snappedCoordinates = [];
    var stoppedPoints = []
    var map;
    var title;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: <?= $map_lat; ?>
                , lng: <?= $map_long; ?>
            }
            , zoom: 14
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
        $vehicle = <?php echo $vehicle; ?>;
        $base_url = "<?php echo base_url() ?>";
        console.log("base url " + $base_url);
        createMarker($vehicle, $base_url);
    }

    function getDateDifference(startDate, endDate) {
        var seconds = (new Date(endDate).getTime() - new Date(startDate).getTime()) / 1000;
        // console.log(" seconds  " + seconds);
        if (seconds > (60 * 60 * 3)) return false;
        else return true;
    }
    $(function () {
        $('#daterange').daterangepicker({
            startDate: moment().subtract(1, 'hours')
            , endDate: moment()
            , minDate: moment().subtract(14, 'days')
            , maxDate: moment()
            , timePicker: true
            , timePickerIncrement: 5
            , format: 'DD/MM/YYYY h:mm A', //locale: {
            //    format: 'DD/MM/YYYY h:mm A'
            //}
        });
        $('#daterange').on('apply.daterangepicker', function (ev, picker) {
            start_date = picker.startDate.format('YYYY-MM-DD H:mm');
            end_date = picker.endDate.format('YYYY-MM-DD H:mm');
            check = getDateDifference(start_date, end_date);
            if (check) {
                vehicle_id = <?php echo $vehicle_id; ?>;
                $.ajax({
                    type: 'POST'
                    , url: "<?php echo base_url('index.php/gps_history/vehicle_playback') ?>"
                    , data: {
                        vehicle_id: vehicle_id
                        , start_date: start_date
                        , end_date: end_date
                    }
                    , dataType: "JSON"
                    , success: function (resultData) {
                        console.log("I got here this time");
                        stop_interval();
                        play();
                        pathCoords = resultData.history;
                        distance = getDistance(pathCoords);
                        console.log("Distance1: " + distance + " pathCoords.length1 " + pathCoords.length);
                        pathCoords = getDistinctRoute(pathCoords);
                        if (pathCoords.length > 1) {
                            latitude = pathCoords[0].latitude;
                            longitude = pathCoords[0].longitude;
                        }
                        $base_url = "<?php echo base_url() ?>";
                        var img_url = $base_url + "/assets/images/gps/start.png";
                        addStartMarker(img_url, map, pathCoords[0]);
                        distance = getDistance(pathCoords);
                        console.log("Distance2: " + distance + " pathCoords.length1 " + pathCoords.length);
                        routes_array = drawRoute(map, pathCoords, routes_array);
                        var img_url = $base_url + +"/assets/images/gps/stop.png";
                        addStartMarker(img_url, map, pathCoords[pathCoords.length - 1]);
                        animateRoute(map, pathCoords);
                        console.log("pathCoords.length1:  " + pathCoords.length);
                    }
                });
            }
            else {
                alert("Playback time has to be 3 hours or less");
            }
        });
    });
</script>
<style>
    html {
        overflow: hidden !important;
        padding: 0 !important;
    }

    .controls {
        position: fixed;
        top: 194px;
        left: 300px;
        height: 32px;
        width: 300px;
        outline: none;
        z-index: 1000;
    }

    #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        width: 300px;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto', 'sans-serif';
        line-height: 30px;
        padding-left: 10px;
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
        background: rgba(193, 215, 46, .8);
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

    #show-all {
        opacity: 0;
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
        font-size: 13px;
        text-transform: capitalize;
    }

    .btn.btn-success {
        color: rgba(31, 35, 42, 1);
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
        right: 50px;
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
        background: rgba(19, 27, 38, .7);
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
        border: .05em solid rgba(19, 27, 38, 1);
        background: rgba(255, 255, 255, .9);
        //background: rgba(19, 27, 38,.7);
        //background: rgba(193, 215, 46,.5);
        outline: none;
        z-index: 2000;
        opacity: 0;
        transition: .4s ease all;
    }

    #button-details {
        text-align: center;
        margin: 16px 0 8px 0;
    }

    #space {
        width: 100%;
        height: 1px;
        background: rgba(19, 27, 38, .6);
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
        color: rgba(19, 27, 38, 1);
        cursor: pointer;
    }

    #datepick {
        position: absolute;
        top: 140px;
        left: 204px;
        z-index: 2000;
    }
</style>
<div class="container-fluid fleet-view">
    <div class="row" style="margin-top: -180px">
        <div class='input-prepend input-group' id="datepick"> <span class='add-on input-group-addon'>
                <i class='glyphicon glyphicon-calendar fa fa-calendar'></i>
            </span>
            <input type="text" style="width: 300px" name="daterange" id="daterange" class="form-control" value="" /> &nbsp;
            <button class="btn btn-xm btn-success" onclick="pause()">pause</button> &nbsp;
            <button class="btn btn-xm btn-success" onclick="play()">continue</button>
        </div>
        <div class="overlay_no_devices">
            <div class="col-md-12" style="margin-top: 44px; margin-left: 16px; padding: 0">
                <div class="row">
                    <div class="col-md-12" style="height: 100%; width: 100%; padding: 0 !important">
                        <div id="map_canvas" class="row" style="height: 100vh; width: 100%;"></div>
                        <!--                        <div class="control-bar">
                            <div class="row">

                                <input type="text" id="daterange" style="background: #0288D1; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" name="daterange" value="" />
                            </div>-->
                        <script>
                            console.log(moment().subtract(1, 'hours').format('YYYY-MM-DD h:mm A') + " - " + moment().format('YYYY-MM-DD h:mm A'));
                            $('#daterange').val(moment().subtract(1, 'hours').format('DD/MM/YYYY h:mm A') + " - " + moment().format('DD/MM/YYYY h:mm A'));
                        </script>
                    </div>
                    <div class="col-md-12">
                        <div class="" style="">
                            <div class="col-md-12">
                                <div class="form-inline" role="form">
                                    <div class="form-group col-sm-2" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-moving.png'); ?>" class="legend-marker" /> Moving </div>
                                    <div class="form-group  col-sm-2" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-idle.png'); ?>" class="legend-marker" /> Idle </div>
                                    <div class="form-group  col-sm-2" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-parked.png'); ?>" class="legend-marker" /> Parked </div>
                                    <div class="form-group  col-sm-2" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-danger.png'); ?>" class="legend-marker" /> Alert </div>
                                    <div class="form-group  col-sm-2" id="legend-markers"> <img src="<?php echo base_url('assets/images/gps/marker-disabled.png'); ?>" class="legend-marker" /> Inactive </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?=$this->config->item('map_key')?>&libraries=places,drawing&callback=initMap">
</script>
