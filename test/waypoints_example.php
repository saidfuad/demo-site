<div id="map_canvas"></div>
  
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
/*
*    Recursive function that sends batches of latlongs to a google.maps.DirectionsService and passes the resulting path to a userFunction
*    NB: overcomes the maximum 8 waypoints limit imposed by the service
*    
*    @param {google.maps.DirectionsService} service
*    @param {array} waypoints
*    @param {function} userFunction
*    @param {optional int} waypointIndex
*    @param {optional array} path - stores the latlongs of the travel route
*/
function gDirRequest(service, waypoints, userFunction, waypointIndex, path) {
    
    // set defaults
    waypointIndex = typeof waypointIndex !== 'undefined' ? waypointIndex : 0;
    path = typeof path !== 'undefined' ? path : [];

    // get next set of waypoints
    var s = gDirGetNextSet(waypoints, waypointIndex);

    // build request object
    var startl = s[0].shift()["location"];
    var endl = s[0].pop()["location"];
    var request = {
        origin: startl,
        destination: endl,
        waypoints: s[0],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        optimizeWaypoints: false,
        provideRouteAlternatives: false,
        avoidHighways: false,
        avoidTolls: false
    };
    console.log(request);

    service.route(request, function(response, status) {

        if (status == google.maps.DirectionsStatus.OK) {

            path = path.concat(response.routes[0].overview_path);

            if (s[1] != null) {
                gDirRequest(service, waypoints, userFunction, s[1], path)
            } else {
                userFunction(path);
            }

        } else {
            console.log(status);
        }

    });
}


/*
*    Return an array containing:
*    1) the next set of waypoints to send to Google, given the start index, and 
*    2) then next waypoint to start from after this set, or null if there is no more.
*
*    @param {google.maps.DirectionsService} service
*    @param {array} waypoints
*    @returns {array}
*/
function gDirGetNextSet (waypoints, startIndex) {
    var MAX_WAYPOINTS_PER_REQUEST = 8;

    var w = [];    // array of waypoints to return

    if (startIndex > waypoints.length - 1) { return [w, null]; } // no more waypoints to process

    var endIndex = startIndex + MAX_WAYPOINTS_PER_REQUEST;

    // adjust waypoints, because Google allows us to include the start and destination latlongs for free!
    endIndex += 2;

    if (endIndex > waypoints.length - 1) { endIndex = waypoints.length ; }

    // get the latlongs
    for (var i = startIndex; i < endIndex; i++) {
        w.push(waypoints[i]);
    }

    if (endIndex != waypoints.length) {
        return [w, endIndex -= 1];
    } else {
        return [w, null];
    }
}

function main() {

    // initalise directions service
    var directionsService = new google.maps.DirectionsService();

    var travelWaypoints = [
        {location: new google.maps.LatLng(-38.080528,144.363098) },
        {location: new google.maps.LatLng(-38.080528,144.363098) },
        {location: new google.maps.LatLng(-37.844495, 144.873962) },
        {location: new google.maps.LatLng(-37.785911, 144.846497) },
        {location: new google.maps.LatLng(-37.720763, 144.978333) },
        {location: new google.maps.LatLng(-37.777228, 144.95636) },
        {location: new google.maps.LatLng(-37.777228, 145.085449) },
        {location: new google.maps.LatLng(-37.881357, 145.022278) }, 
        {location: new google.maps.LatLng(-37.957192, 145.099182) },
        {location: new google.maps.LatLng(-38.006984, 145.200806) },
        {location: new google.maps.LatLng(-37.911701, 145.258484) },
        {location: new google.maps.LatLng(-37.848833, 145.220032) },
        {location: new google.maps.LatLng(-37.866181, 145.137634) },
        {location: new google.maps.LatLng(-37.731625, 145.126648) },
        {location: new google.maps.LatLng(-37.72728, 145.022278) },
        {location: new google.maps.LatLng(-37.728366, 144.95224) },
        {location: new google.maps.LatLng(-37.738141, 144.901428) },
        {location: new google.maps.LatLng(-37.732711, 144.84375) },
        {location: new google.maps.LatLng(-37.692514, 144.776459) },
        {location: new google.maps.LatLng(-37.692514, 144.736633) },
        {location: new google.maps.LatLng(-37.722935, 144.757233) },
        {location: new google.maps.LatLng(-37.722935, 144.795685) },
        {location: new google.maps.LatLng(-37.733797, 144.828644) },
        {location: new google.maps.LatLng(-37.746829, 144.797058) },
        {location: new google.maps.LatLng(-37.746829, 144.754486) },
        {location: new google.maps.LatLng(-37.730539, 144.724274) },
        {location: new google.maps.LatLng(-37.745743, 144.713287) },
        {location: new google.maps.LatLng(-37.767458, 144.698181) },
        {location: new google.maps.LatLng(-37.827141, 144.692688) },
        {location: new google.maps.LatLng(-37.670777, 144.437256) },
        {location: new google.maps.LatLng(-37.67594, 144.432106) },
        {location: new google.maps.LatLng(-37.667245, 144.429531) }
    ];

    // get directions and draw on map
    gDirRequest(directionsService, travelWaypoints, function drawGDirLine(path) {
        var line = new google.maps.Polyline({clickable:false,map:map,path:path});
    });
}
</script>


<script type="text/javascript">
  var map;
  function initialize() {
    var myOptions = {
      zoom: 8,
      center: new google.maps.LatLng(-37.84232584933157, 145.008544921875),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
    
    google.maps.event.addListenerOnce(map, 'idle', function(){
        main();
    });
  }

  google.maps.event.addDomListener(window, 'load', initialize);
</script>
?