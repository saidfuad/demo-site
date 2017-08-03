<script src="<?php echo base_url('assets/angular_moment.min.js') ?>"></script>
<script src="<?php echo base_url('assets/app.js') ?>"></script>
<script src="<?php echo base_url('assets/js/map_script.js') ?>"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?=$this->config->item('map_key')?>&libraries=places,drawing"></script>

<script type="text/javascript">
var apiKey = "<?=$this->config->item('map_key')?>";
var map;
var drawingManager;
var placeIdArray = [];
var polylines = [];
var snappedCoordinates = [];
    var lat, lon;

function initialize() {
  var pathCoords = <?php echo $coords; ?>;
        var mid = 0;
        if (pathCoords.length > 1) {
            mid = parseInt(pathCoords.length / 2 , 10);
            lat = pathCoords[mid].latitude;
            lon = pathCoords[mid].longitude;
        }
        else
        {
            lat = -4.0434771;
            lon = 39.6682065;
        }

       
       //get all points plus  tracking_time
        var points = [];
        for(var i = 0; i < pathCoords.length; i++)
        {
          points.push(pathCoords[i].latitude + "," + pathCoords[i].longitude + "," + pathCoords[i].tracking_time);
        }

        //get distinct points to be mapped
        var distinctMap = {};

        for (var i = 0; i < points.length; i++) {
            var value = points[i];
             distinctMap[value] = '';
        }   

        var unique_values = Object.keys(distinctMap);
        
        //remove tracking time from points to be used for mapping
        points = [];
        for(var i = 0; i  <unique_values.length; i++)
        {
            var point = unique_values[i].split(",");
            points.push(point[0]+"," + point[1]);
        }
    
    console.log("unique_values: " + points.length + " values: " + points.join('|'));


        //calculate step value if points are greater than 98
        var step = 1;
        
        if( points.length > 98)
        {
          step =  points.length /98 ;
        }

        //create aray to be mapped 
        var pathValues = [];
        for(var i = 0; i < points.length; i+= step)
        {
            pathValues.push(points[parseInt(i, 10)]);
        }

        console.log("plotted points: "+ pathValues.length + " values: "  + pathValues.join('|'));



 
    map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: parseFloat(lat)
                , lng:  parseFloat(lon)
            }
            , zoom: 17
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


// Snap a user-created polyline to roads and draw the snapped path
function runSnapToRoad() {
    
  $.get('https://roads.googleapis.com/v1/snapToRoads', {
    interpolate: true,
    key: apiKey,
    path: pathValues.join('|')
  }, function(data) {
    drawMarkers(data);
    processSnapToRoadResponse(data);
    drawSnappedPolyline();
  });
}

// Store snapped polyline returned by the snap-to-road service.
function processSnapToRoadResponse(data) {
  snappedCoordinates = [];
  placeIdArray = [];
  for (var i = 0; i < data.snappedPoints.length; i++) {
    var latlng = new google.maps.LatLng(
        data.snappedPoints[i].location.latitude,
        data.snappedPoints[i].location.longitude);
    snappedCoordinates.push(latlng);
    placeIdArray.push(data.snappedPoints[i].placeId);
  }
}

// Draws the snapped polyline (after processing snap-to-road response).
function drawSnappedPolyline() {
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: 'red',
    strokeWeight: 5
  });

  snappedPolyline.setMap(map);
  polylines.push(snappedPolyline);
}
    
    runSnapToRoad();

    function drawMarkers(data)

    {
         console.log("data[0].location" + data.snappedPoints[0].location.latitude);
                  
            $base_url = "<?php echo base_url() ?>";
            var img_url = $base_url + "/assets/images/gps/start.png";
            addStartMarker(img_url, map, data.snappedPoints[0].location);
           
            var img_url = $base_url + "/assets/images/gps/stop.png";
            addStartMarker(img_url, map, data.snappedPoints[data.snappedPoints.length - 1].location);
   
    }
}
$(window).load(initialize);
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
</style>
<div class="container-fluid fleet-view">
    <div class="row" style="margin-top: -180px">
        <div class="overlay_no_devices">
            <div class="col-md-12" style="margin-top: 44px; margin-left: 16px; padding: 0">
                <div class="row">
                    <div class="col-md-12" style="height: 100%; width: 100%; padding: 0 !important">
                        <div id="map_canvas" class="row" style="height: 100vh; width: 100%;"></div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo " map   key ".$this->config->item(" map_key ") ?>
    <!-- //. Content -->
   <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?=$this->config->item('map_key')?>&libraries=places,drawing&callback=initialize">
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?=$this->config->item('map_key')?>&libraries=places,drawing&callback=initMap">
</script>-->