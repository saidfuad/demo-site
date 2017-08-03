<link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css') ?>" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript">
    var infowindow;
    var zones = [];
    var pop_marker = [];
    var map_pop;
    var map;
    var iw_map;
    var new_zones_markers = [];
    var fillcolor = $("#full-popover").val();
    var countM = 0;
    var newLatLng = [];
    var marker;
    var infowindow;
    var place_icon = '';
    var markerContent = '';
    var newPolygon = '';
    var polygons = '';
    var vertices = '';
    var mapCords = [];
    var route = [];
    var raw_route = '';
    var route_data = {};
    var directionsDisplay=null;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: <?= $map_lat; ?>, lng: <?= $map_long; ?>},
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            heading: 90,
            tilt: 45,
            streetViewControl:true,
              streetViewControlOptions: {
                  style: google.maps.ZoomControlStyle.SMALL,
                  position: google.maps.ControlPosition.LEFT_CENTER
              },

              fullscreenControl: true,
              fullscreenControlOptions: {
                  style: google.maps.ZoomControlStyle.SMALL,
                  position: google.maps.ControlPosition.LEFT_CENTER
              },
              zoomControl: true,
              zoomControlOptions: {
                  style: google.maps.ZoomControlStyle.SMALL,
                  position: google.maps.ControlPosition.LEFT_CENTER
              },
        });

        var origin_place_id = null;
        var destination_place_id = null;
        var travel_mode = google.maps.TravelMode.DRIVING;

        var directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer;

        directionsDisplay.setMap(map);

        var origin_input = document.getElementById('origin-input');
        var destination_input = document.getElementById('destination-input');
        //var modes = document.getElementById('mode-selector');

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(origin_input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(destination_input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(modes);

        var origin_autocomplete = new google.maps.places.Autocomplete(origin_input);
        origin_autocomplete.bindTo('bounds', map);
        var destination_autocomplete =
                new google.maps.places.Autocomplete(destination_input);
        destination_autocomplete.bindTo('bounds', map);

        function expandViewportToFitPlace(map, place) {
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
        }

        origin_autocomplete.addListener('place_changed', function () {
            var place = origin_autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            expandViewportToFitPlace(map, place);

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            origin_place_id = place.place_id;
            generate_route(origin_place_id, destination_place_id, travel_mode,
                    directionsService, directionsDisplay);
        });

        destination_autocomplete.addListener('place_changed', function () {
            var place = destination_autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            expandViewportToFitPlace(map, place);

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            destination_place_id = place.place_id;

            generate_route(origin_place_id, destination_place_id, travel_mode,
                  directionsService, directionsDisplay);
          });

          function generate_route(origin_place_id, destination_place_id, travel_mode,
                         directionsService, directionsDisplay) {

            //alert();

            route = [];
            if (!origin_place_id || !destination_place_id) {
                return;
            }
            directionsService.route({

              origin: {'placeId': origin_place_id},
              destination: {'placeId': destination_place_id},
              travelMode: travel_mode
            }, function(response, status) {

               // alert();

                if (status === google.maps.DirectionsStatus.OK) {

                    directionsDisplay.setDirections(response);
                    raw_route = JSON.stringify(response);

                    var route_ = response.routes[0];
                    var legs = directionsDisplay.directions.routes[0].legs;
                    var legs_length = legs.length;
                    var steps = directionsDisplay.directions.routes[0].legs[0].steps;
                    var steps_length = steps.length;
                    var distance = directionsDisplay.directions.routes[0].legs[0].distance.text;
                    var distance_value = directionsDisplay.directions.routes[0].legs[0].distance.value;
                    var duration = directionsDisplay.directions.routes[0].legs[0].duration.text;
                    var duration_value = directionsDisplay.directions.routes[0].legs[0].duration.value;
                    var end_address = directionsDisplay.directions.routes[0].legs[0].end_address;
                    var end_latlng = directionsDisplay.directions.routes[0].legs[0].end_location;
                    var start_address = directionsDisplay.directions.routes[0].legs[0].start_address;
                    var start_latlng = directionsDisplay.directions.routes[0].legs[0].start_location;
                    var path = directionsDisplay.directions.routes[0].legs[0].steps[0].path;
                    var picked_points = [];
                    var route_path = [];

                    //neBounds = bounds.getNorthEast(),


                    for (var i = 0; i < steps_length; i++) {
                        picked_points.push(JSON.stringify(steps[i].start_location));

                        $path = steps[i].path;
                        $path_length = $path.length;

                        for (var p = 0; p < $path_length; p++) {
                            route_path.push(JSON.stringify($path[p]));
                        }
                        //console.log(steps[i].start_location.lat+','+steps[i].start_location.lng);
                    }

                    route_data = {
                                    start_address: start_address,
                                    start_latlng: JSON.stringify(start_latlng),
                                    end_address: end_address,
                                    end_latlng: JSON.stringify(end_latlng),
                                    distance:distance,
                                    distance_value:distance_value,
                                    duration:duration,
                                    duration_value:duration_value,
                                    picked_points:picked_points,
                                    route_path: route_path

                                }

                    //alert(JSON.stringify(route_data));

                } else {
                window.alert('Directions request failed due to ' + status);
              }


            });
        }



        infowindow = new google.maps.InfoWindow();


        //load_routes();

    }

    function load_routes() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/settings/get_company_routes') ?>",
            data: {company: 'this company'},
            success: function (data) {
                data = JSON.parse(data);

            }
        });
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




    function clearMarkers(new_landmark_markers) {
        var countMarkers = new_landmark_markers.length;
        if (countMarkers > 0) {
            for (var i = 0; i < countMarkers; i++) {
                new_landmark_markers[i].setMap(null);
            }
        }
    }


    function fit_bounds() {
        var bounds = new google.maps.LatLngBounds();
        var vsize = mapCords.length;

        for (i = 0; i < vsize; i++) {
            bounds.extend(mapCords[i]);
        }

        map.fitBounds(bounds);
    }




</script>


<style>

    .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #origin-input,
    #destination-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 200px;
    }

    #origin-input:focus,
    #destination-input:focus {
        border-color: #4d90fe;
    }

    #mode-selector {
        color: #fff;
        background-color: #4d90fe;
        margin-left: 12px;
        padding: 5px 11px 0px 11px;
    }

    #mode-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }

</style>


<div>
    <div class="row">
        <div class="col-md-12">
        <div class="" style="height:400px;position: absolute; right:30px;margin-top:40px;width:330px;z-index:5000;display:;">

            <div class="col-md-12 bg-crumb" style="box-shadow:3px 3px 3px #333;" >
                <h4>Edit Route( <sup>*</sup> Required)</h4>
                <form id="form-update-route">
                    <div class="form-group">
                        <label>Route Name <sup>*</sup></label>
                        <input type="hidden" name="route_id" value="<?php echo $routes['route_id'] ?>" />
                        <input class="form-control input-sm clear-input" name="route_name" id="route_name" type="text" placeholder="Type route name" required="required" value="<?php echo $routes['route_name'] ?>">
                    </div>

                    <div class="form-group">
                        <label>Route color <sup>*</sup></label>
                        <input type="text" class="form-control" id="full-popover" name="route_color" value="<?php echo $routes['route_color'] ?>" data-color-format="hex" required="required">
                    </div>
                    <div class="form-group">
                        <label>Allowed diversion distance (in KM)<sup>*</sup></label>
                        <input type="number" class="form-control" id="allowed_diversion_distance" name="allowed_diversion_distance" min="0.2" max="5" step="0.1" value="<?php echo $routes['allowed_diversion_distance'] ?>" required="required">
                    </div>
                    <div class="form-group">
                        <div class="row">

                        </div>
                    </div>
                    <div class="form-group margin-top-10" style="border-top:1px solid #ccc;padding-top: 5px">
                         <div class="col-sm-6 margin-top-10">
                           <button class="btn btn-success btn-block" id="btn-update" type="submit">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <input id="origin-input" class="controls" type="text" placeholder="Enter an origin location">
        <input id="destination-input" class="controls" type="text" placeholder="Enter a destination location">
        <div class="col-md-12" id='map_canvas' style="height:610px"></div>


        <div class="control-bar">
            <div class="row" align="center">
                <div class="col-md-4 col-md-offset-4">
                    <button class="btn btn-default btn-block" id="clear-route">Clear Map</button>
                </div>
            </div>
        </div>

    </div>
    <!-- /.row -->
</div>
</div>

<script type="text/javascript">
    $(function () {

        //swal.enableLoading();

        function clear_routes(routes) {
            for (var i = 0; i < routes.length; i++) {
                routes[i].setMap(null);
            }
            routes = [];
        }

        $('#clear-route').on('click', function () {
            directionsDisplay.setMap(null);
            directionsDisplay = new google.maps.DirectionsRenderer;
            directionsDisplay.setMap(map);
            route_data = {};
            raw_route = '';
            $('#origin-input').val('');
            $('#destination-input').val('');

            //swal({title: "Info", type: "info", text: "Route cleared"});

            return false;
        });

        $('.page-alert').html('<p align="center">Use the search boxes on the map to select start and destination</p>');
        $('.page-alert').animate({right: '10px'}, 2000);
        setTimeout(function () {
            var showPageAlert = setInterval(function () {
                $('.page-alert').toggleClass('hide-me');
            }, 1500);
        }, 4000);


        $("input#full-popover").ColorPickerSliders({
            placement: 'right',
            hsvpanel: true,
            previewformat: 'hex'
        });


        $('#btn-clear').on('click', function () {
            //clearMarkers(new_zones_markers)
            $(document).find('.clear-input').val('');
            $(document).find('.alert-checks').prop('checked', false);
            $('.page-alert').html('<p align="center">Click on the map to select a zone</p>');
        });


        $('#form-update-route').on('submit', function () {

            var route_name = $('#route_name').val().trim();
            var route_color= $('#full-popover').val().trim();
            //var address = $('#address').val().trim();
            var $this = $(this);
            //var data = JSON.stringify(route_data);

            //alert(raw_route.length);

            /*if (Object.keys(route_data).length==0 || raw_route.length == 0) {
                swal({ title: "Info", type: "info", text: "Please select a route on the map first"});
                return false;
            }*/

            if (route_name.length == 0 || route_color.length == 0) {
                swal({title: "Info", type: "info", text: "Please fill in all required fields"});
                return false;
            }

            swal({
                title: "Confirm",
                text: "Update route?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
                }, function(){
                    $.ajax({
                        type    : "POST",
                        cache   : false,
                        data: $this.serialize(),
                        //{data:data, route_name:route_name, route_color:route_color, raw_route:raw_route},
                        url     : "<?php echo base_url('index.php/settings/update_route') ?>",
                        success: function(response) {

                            if (response==1) {
                                swal({title: "Info",text:'Route updated successfully', type:'success'});

                            } else {
                                swal({title: "Info",text:'Sorry, Failed to save', type:'error'});

                            }

                        }

                    });

                });

           // return false;
        }
        );
    });
</script>
<script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js') ?>"></script>

<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBn6m_W3hRMPg5nDlmqsRkaO3kE1LZ1HX4&libraries=places,drawing&callback=initMap">
</script>-->

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfKP5H8r9ohlPjH_CbddIefMbeCirz7-U&libraries=places,drawing&callback=initMap">
</script>
