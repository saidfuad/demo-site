
<link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css')?>" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript">
    var infowindow;
    var vehicle_markers = [];
    var landmark_markers = [];
    var pop_marker = [];
    var map_pop;
    var map;
    var iw_map;
    var new_landmark_markers = [];
    var new_landmark_circles = [];
    var newLatLng = '';
    var fillcolor = $("#full-popover").val();
    var range = parseFloat($("#landmark-radius").val());
    var countM = 0;
    var newLatLng = [];
    var landmark_circle_color = $('#full-popover').val();
    var marker;
    var infowindow;
    var infobox;
    var pick_icon;
    var dest_icon;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: <?= $map_lat; ?>, lng: <?= $map_long; ?>},
            zoom: 7,
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


        var geocoder = new google.maps.Geocoder;

        var input = /** @type {!HTMLInputElement} */(
                document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);



        infowindow = new google.maps.InfoWindow();


        marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, 0)
        });

        
        /*map.addListener('click', function(e) {
            newLatLng = e.latLng;
            show_marker(true);
        });
        */

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            get_place(place);
        });

        function get_place(place) {
            infowindow.close();
            //marker.setVisible(false);

            if (!place.geometry) {
                //window.alert("Returned place contains no geometry");
                swal({title: "Place not found", text: 'Returned place contains no geometry', type: 'info'});
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }

            landmark_circle_color = $('#full-popover').val();
            newLatLng = place.geometry.location;

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            if (place.name) {
                var place_name = place.name;
            } else {
                var place_name = place.address_components[0].short_name;
            }

            var sel_lat = place.geometry.location.lat;
            var sel_lng = place.geometry.location.lng;
            var lnd_mark = place_name;
            var addrs = address;

            var place_icon = "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png";

            if (place.icon) {
                place_icon = place.icon;
            }   

            if ($('#pickup-selected').val() == 0) {
                //alert(latitude);
                $("#pick-lat").val(sel_lat);
                $("#pick-lng").val(sel_lng);
                $("#pickup_address").val(place_name);
                $("#pickup-selected").val(1);
                $('.page-alert').html('<p align="center">Select destination</p>');
                $location = "Pickup location : ";
                $("#pick-icon").val(place_icon);
            } else if ($('#destination-selected').val() == 0) {
                $("#end-lat").val(sel_lat);
                $("#end-lng").val(sel_lng);
                $("#destination_address").val(place_name);
                $('#destination-selected').val(1);
                $('.page-alert').html('<p align="center">Enter trip details</p>');
                $location = "Destination : ";
                $("#dest-icon").val(place_icon);
            } else if ($('#pickup-selected').val() == 1 && $('#destination-selected').val() == 1) {
                $('.page-alert').html('<p align="center">To <b>RESET</b> locations clear the selected addresses</p>');
                return false;
            } 


            addMarker(newLatLng , place_icon, $location + place_name);


            //console.log(place.icon);
           
        }

        
        map.addListener('click', function (event) {

            //marker.setVisible(false);
            //clearMarkers(new_landmark_markers);
            //clearCircles(new_landmark_circles);

            newLatLng = event.latLng;
            icon = "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png";
            pick_icon = icon;
            dest_icon = icon;

            var latitude = event.latLng.lat();
            var longitude = event.latLng.lng();
            //var landmark_name = $('#landmark_name').val();
            landmark_circle_color = $('#full-popover').val();
            //var landmark_image = $('#icon_path').val();
            var place_name = '';
            var address = '';

            if ($('#pickup-selected').val() == 0) {
                //alert(latitude);
                $("#pick-lat").val(latitude);
                $("#pick-lng").val(longitude);

            } else if ($('#destination-selected').val() == 0) {
                $("#end-lat").val(latitude);
                $("#end-lng").val(longitude);

            } else if ($('#pickup-selected').val() == 1 && $('#destination-selected').val() == 1) {
                $('.page-alert').html('<p align="center">To <b>RESET</b> locations clear the selected addresses</p>');
                return false;
            } 

            


            var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {

                        //alert('results');
                        place_name = results[0].address_components[1].long_name + " " + results[0].address_components[0].long_name;
                        //address = results[0].formatted_address;

                        if ($('#pickup-selected').val() == 0) {
                            //alert('pickup');
                            $("#pickup_address").val(place_name);
                            $("#pickup-selected").val(1);
                            $('.page-alert').html('<p align="center">Select destination</p>');
                            $("#pick-icon").val(pick_icon);
                            $location = "Pickup location : ";
                        } else if ($('#destination-selected').val() == 0) {
                           $("#destination_address").val(place_name);
                           $('#destination-selected').val(1);
                           $("#dest-icon").val(icon);
                           $('.page-alert').html('<p align="center">Enter trip details</p>');
                           $location = "Destination : ";
                        }

                        addMarker(newLatLng , icon, $location + place_name);
                    } else {
                        place_name = '';
                        address = '';

                        if ($('#pickup-selected').val() == 0) {
                            swal({title: "Info", text: 'Input pickup address: Could not get the Selected Place address', type: 'info'});
                            $('.page-alert').html('<p align="center">Select destination</p>');
                            $location = "Pickup location : Address Undefined ";
                            $("#pick-icon").val(pick_icon);
                        } else if ($('#destination-selected').val() == 0) {
                            swal({title: "Info", text: 'Input destination address: Could not get the Selected Place address', type: 'info'});
                            $('.page-alert').html('<p align="center">Enter trip details</p>');
                            $location = "Destination : Address Undefined";
                            $("#dest-icon").val(icon);
                        }
                        addMarker(newLatLng , icon, $location);
                    }
                } else {
                    swal({title: "Info", text: 'Type address: Could not get the Selected Place address', type: 'success'});
                    place_name = '';
                    address = '';
                     if ($('#pickup-selected').val() == 0) {
                        swal({title: "Info", text: 'Input pickup address: Could not get the Selected Place address', type: 'info'});
                        $('.page-alert').html('<p align="center">Select destination</p>');
                        $location = "Pickup location : Address Undefined ";
                        $("#pick-icon").val(pick_icon);
                    } else if ($('#destination-selected').val() == 0) {
                        swal({title: "Info", text: 'Input destination address: Could not get the Selected Place address', type: 'info'});
                        $('.page-alert').html('<p align="center">Enter trip details</p>');
                        $location = "Destination : Address Undefined";
                        $("#dest-icon").val(icon);
                    }

                   addMarker(newLatLng , icon, $location);
                }
            });

            //alert(place_name);

            ///place_name = "(" + place_name +")";

            
        });

        function show_marker(show) {
            marker.setPosition(newLatLng);
            marker.setVisible(true);
            if (show) {

                //https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png
                marker.setIcon({
                    url: "https://maps.gstatic.com/mapfiles/place_api/icons/shopping-71.png",
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                });
            }

            range = parseFloat($("#landmark-radius").val());

            //addMarker(newLatLng, landmark_image, landmark_name);
            addCircle(newLatLng, landmark_circle_color);
        }

        iw_map = new google.maps.InfoWindow({
            content: ''
        });

        load_landmarks();

    }

    function load_landmarks() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/settings/get_company_landmarks') ?>",
            data: {company: 'this company'},
            success: function (data) {
                landmarks = JSON.parse(data);

                //alert(landmarks.length);
                if (landmarks.length > 0) {
                    for (var row in landmarks) {
                        if (landmarks[row].gmap_icon == 0) {
                            $icon = "<?= base_url('" + landmarks[row].icon_path+ "') ?>";
                        } else {
                            $icon = landmarks[row].icon_path;
                        }

                        $pos = {lat: parseFloat(landmarks[row].latitude, 10), lng: parseFloat(landmarks[row].longitude)};
                        //console.log(parseInt(landmarks[row].latitude, 10));
                        landmark_markers.push(new google.maps.Marker({
                            position: $pos,
                            map: map,
                            icon: $icon,
                            content: landmarks[row].landmark_name,
                        }));
                    }

                    var llength = landmark_markers.length;

                    for (var i = 0; i < llength; i++) {

                        $icn = landmark_markers[i].icon;

                        landmark_markers[i].setIcon(/** @type {google.maps.Icon} */({
                            url: $icn,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(12, 24),
                            scaledSize: new google.maps.Size(24, 24)
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

                    if (llength) {
                        //fit_bounds();
                    }


                }

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


    function addMarker(location, icon, landmark_name) {

        //icon = "<?= base_url('" + icon + "') ?>";
        new_landmark_markers.push(new google.maps.Marker({
            position: location,
            map: map,
            icon: icon,
            content: landmark_name,
            scaledSize: new google.maps.Size(20, 20)

        }));

        map.setCenter(location);
        map.setZoom(17);  // Why 17? Because it looks good.


        assign_event();

    }

    function assign_event () {

        var countMarkers = new_landmark_markers.length;
        if (countMarkers > 0) {
            for (var i = 0; i < countMarkers; i++) {
                 new_landmark_markers[i].setIcon(({
                            url:new_landmark_markers[i].icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(0, 0),
                            scaledSize: new google.maps.Size(24, 24)
                          }));

                google.maps.event.addListener(new_landmark_markers[i], "mouseout", function (event) {
                    iw_map.close();
                });

                google.maps.event.addListener(new_landmark_markers[i], "mouseover", function (event) {
                    iw_map.setContent(this.get("content"));
                    iw_map.open(map, this);
                });
            }
        }
        
    }


    function addCircle(location, color) {
        new_landmark_circles.push(new google.maps.Circle({
            strokeColor: "0.8",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: fillcolor,
            fillOpacity: 0.4,
            map: map,
            center: location,
            radius: range * 1000
        }));


    }

    function clearMarkers(new_landmark_markers) {
        var countMarkers = new_landmark_markers.length;
        if (countMarkers > 0) {
            for (var i = 0; i < countMarkers; i++) {
                new_landmark_markers[i].setMap(null);
            }
        }
    }

    function clearCircles(new_landmark_circles) {
        var countCircles = new_landmark_circles.length;
        if (countCircles > 0) {
            for (var i = 0; i < countCircles; i++) {
                new_landmark_circles[i].setMap(null);
            }
        }
    }


    function fit_bounds() {
        var bounds = new google.maps.LatLngBounds();
        var vsize = landmark_markers.length;

        //console.log('Size:' + vehicle_markers.length);

        for (i = 0; i < vsize; i++) {
            bounds.extend(landmark_markers[i].getPosition());
        }

        map.fitBounds(bounds);
    }




</script>

<script type="text/javascript">
    $(function () {


    });
</script>

<style>

    .controls {
        position:absolute;
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        /*margin-left:300px;*/
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
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }

    
</style>


<div class="">
    <div class="row">

        <input type="hidden" value="0" id="pickup-selected" />
        <input type="hidden" value="0" id="destination-selected" />
        <div class="col-md-12" >
            <div class="" style="height:400px;position: absolute; right:30px;margin-top:40px;width:330px;z-index:5000;display:;">
                
                <div class="col-md-12 bg-crumb" style="box-shadow:3px 3px 3px #333;" >
                    <!--<h4>Create Trips( <sup>*</sup> Required)</h4>-->
                    <form id="form-create-trip">
                        <div class="form-group">
                            <label for="reservation">Trip Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control clear-input" type="text" name="trip_name" id="trip_name" />
                        </div>
                        <script>
                            $('#trip_name').on('focus', function(){
                                $('.assets_holder').hide();
                                $('.client_holder').hide();
                                $('.route_holder').hide();
                            });
                        </script>
                        <div class="form-group">
                                <input id="client_id" type="hidden" class="form-control clear-input"/>
                                <label class="control-lable">Client<sup title="Required field">*</sup>:</label>
                                <input id="client" name="client" class="form-control clear-input" />

                                <div class="client_holder">
                                    <ul id="client-list">
                                    <?php
                                    if ( $all_clients == null) 
                                        {   
                                            echo "<li style='list-style:none;padding-top:4px;margin-left:-20px;border-bottom:1px solid #eee;'>No Clients Found</li>";
                                            
                                        // echo $value->driver_name;
                                    } else{
                                    foreach($all_clients as $key => $row) {
                                        echo "<li style='list-style:none;padding-top:4px;margin-left:-20px;border-bottom:1px solid #eee;' onclick=\"clientClicked(this.id,this.title)\" class='types' id='" . $row['client_id'] . "'
                                        title='".$row['client_name'] . "' >" . $row['client_name'] . "</li>";
                                        }
                                    }
                                    ?>
                                    </ul>
                                </div>
                                <script>

                                $('#client').on('focus', function(){
                                    $('.assets_holder').hide();
                                    $('.client_holder').show();
                                    $('.route_holder').hide();                                   
                                });

                                $('#client').on('keyup focusin', function () {
                                    var value = $(this).val().trim();
                                    $('#client-list').show();
                                    $("#client-list >li").each(function () {
                                        if($(this).text().toLowerCase().search(value) > -1) {
                                            $(this).show();
                                        }
                                        else {
                                            $(this).hide();
                                        }
                                    });
                                });

                                function clientClicked(client,value) {
                                    // alert(value);
                                    $("#client").val(value);
                                    $("#client_id").val(client);
                                    $("#client").focus();
                                    $('#client-list').hide(1000);
                                }
                                </script>
                            </div>

                            <div class="form-group">
                                <input id="asset_id" type="hidden" class="form-control clear-input"/>
                                <label class="control-lable">Vehicle</label>
                                <input id="asset" name="asset" class="form-control clear-input" />

                                <div class="assets_holder">
                                    <ul id="asset-list">
                                    <?php
                                    if ( $all_assets == null) 
                                        {   
                                            echo "<li style='list-style:none;padding-top:4px;margin-left:-20px;border-bottom:1px solid #eee;'>No Vehicle Found</li>";
                                            
                                        // echo $value->driver_name;
                                    } else{
                                    foreach($all_assets as $key => $row) {
                                        echo "<li style='list-style:none;padding-top:4px;margin-left:-20px;border-bottom:1px solid #eee;' onclick=\"assetClicked(this.id,this.title)\" class='types' id='" . $row['asset_id'] . "'
                                        title='".$row['asset_name'] . "' >" . $row['asset_name'] . "</li>";
                                        }
                                    }
                                    ?>
                                    </ul>
                                </div>
                                <script>

                                $('#asset').on('focus', function(){
                                    $('.assets_holder').show();
                                    $('.client_holder').hide();
                                    $('.route_holder').hide();                                   
                                });
                                
                                $('#asset').on('keyup focusin', function () {
                                    var value = $(this).val().trim();
                                    $('#asset-list').show();
                                    $("#asset-list >li").each(function () {
                                        if($(this).text().toLowerCase().search(value) > -1) {
                                            $(this).show();
                                        }
                                        else {
                                            $(this).hide();
                                        }
                                    });
                                });

                                function assetClicked(asset,value) {
                                    // alert(asset);
                                    $("#asset").val(value);
                                    $("#asset_id").val(asset);
                                    $("#asset").focus();
                                    $('#asset-list').hide();
                                }
                                </script>
                            </div>

                            <div class="form-group">
                                <input id="route_id" type="hidden" class="form-control clear-input"/>
                                <label class="control-lable">Route</label>
                                <input id="route" name="route" class="form-control clear-input" />

                                <div class="route_holder" style="border: 1px solid #eee;border-radius: 5px;">
                                    <ul id="right-list">
                                    <?php
                                    if ( $all_routes == null) 
                                        {   
                                            echo "<li style='list-style:none;padding-top:4px;margin-left:-20px;border-bottom:1px solid #eee;'>No Routes Found</li>";
                                            
                                        // echo $value->driver_name;
                                    } else{
                                    foreach($all_routes as $key => $row) {
                                        echo "<li style='list-style:none;padding-top:4px;margin-left:-20px;border-bottom:1px solid #eee;' onclick=\"routeClicked(this.id,this.title)\" class='types' id='" . $row['route_id'] . "'
                                        title='".$row['route_name'] . "' >" . $row['route_name'] . "</li>";
                                        }
                                    }
                                    ?>
                                    </ul>
                                </div>
                                <script>

                                $('#route').on('focus', function(){
                                    $('.assets_holder').hide();
                                    $('.client_holder').hide();
                                    $('.route_holder').show();                                   
                                });

                                $('#route').on('keyup focusin', function () {
                                    var value = $(this).val().trim();
                                    $('#right-list').show();
                                    $("#right-list >li").each(function () {
                                        if($(this).text().toLowerCase().search(value) > -1) {
                                            $(this).show();
                                        }
                                        else {
                                            $(this).hide();
                                        }
                                    });
                                });

                                function routeClicked(route,value) {
                                    // alert(value);
                                    $("#route").val(value);
                                    $("#route_id").val(route);
                                    $("#route").focus();
                                    $('#right-list').hide();
                                }
                                </script>
                            </div>
                        
                        <div class="form-group">
                            <label>Consignment<sup title="Required field">*</sup>:</label>
                            <textarea class="form-control input-sm clear-input" id="consignment" name="consignment" placeholder="Enter cargo details" ></textarea>
                        </div>
                        <script>
                            $('#consignment').on('focus', function(){
                                $('.assets_holder').hide();
                                $('.client_holder').hide();
                                $('.route_holder').hide();
                            });
                        </script>
                        <div class="form-group margin-top-10" style="border-top:1px solid #ccc;padding-top: 5px">

                            <div class="col-sm-6 margin-top-10">
                                <button class="btn btn-default btn-block" id="btn-clear">Clear</button>
                            </div>
                            <div class="col-sm-6 margin-top-10">
                                <input class="btn btn-success btn-block" type="submit" id="btn-create" value="Create" />
                            </div>

                        </div>
                    </form>    
                </div>   
            </div> 
            <input id="pac-input" class="controls" type="text"
                   placeholder="Enter a location">
            <div class="col-md-12" id='map_canvas' style="height:610px"></div>
            <div class="control-bar">
                <div class="row" align="center">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-inline" role="form">
                            <div class="form-group">
                                <label class="control-label">Pickup Location<sup title="Required field">*</sup>:</label>
                                <input class="form-control input-sm clear-input" id="pickup_address" name="pickup_address" type="text">
                            </div>
                            <div class="form-group">
                                <!--<label>Destinatin</label>-->
                                <input type="hidden" name="pick_lat clear-input" id="pick-lat" />
                                <input type="hidden" name="pick_lng clear-input" id="pick-lng" />
                                <input type="hidden" name="pick_icon clear-input" id="pick-icon" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" id="btn-clear-pickup">Clear pickup location</button>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Destination Location<sup title="Required field">*</sup>:</label>
                                <input class="form-control input-sm clear-input" id="destination_address" name="destination_address" type="text">
                            </div>
                            <div class="form-group">
                                <!--<label>Destinatin</label>-->
                                <input type="hidden" name="end_lat clear-input" id="end-lat" />
                                <input type="hidden" name="end_lng clear-input" id="end-lng" />
                                <input type="hidden" name="dest_icon clear-input" id="dest-icon" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" id="btn-clear-destination">Clear Destination location</button>
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

       /* $('#asset, #client, #route ').on('mouseout', function () {
            $('#client-list').hide();
            $('#right-list').hide();
            $('#asset-list').hide();
            $(this).blur();
        });*/

        $('#btn-clear-pickup').on('click', function() {

            swal({   
                title: "Info",   
                text: "Clear pickup location?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                //showLoaderOnConfirm: true, 
                //confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false, 

                }, function(){
                    $("#pick-lat").val('');
                    $("#pick-lng").val('');
                    $("#pickup_address").val('');
                    $("#pickup-selected").val(0);
                    $('.page-alert').html('<p align="center">Select Pickup location</p>');
                    
                    swal({title: "Info", text:'Pickup location cleared', type:'info'});
                });
        });

         $('#btn-clear-destination').on('click', function() {
            swal({   
                title: "Info",   
                text: "Clear destination location?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                //showLoaderOnConfirm: true, 
                //confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false, 

                }, function(){
                    $("#end-lat").val('');
                    $("#end-lng").val('');
                    $("#destination_address").val('');
                    $("#destination-selected").val(0);
                    
                    if ($('#pickup-selected').val() == 1) {
                        $('.page-alert').html('<p align="center">Select Destination location</p>');
                    } else if ($('#pickup-selected').val() == 0) {
                        $('.page-alert').html('<p align="center">Select Pickup and destion locations</p>');
                    } 
                   
                    swal({title: "Info",text:'Destination location cleared', type:'info'});
                });
        });

        $('.page-alert').html('<p align="center">Click/Search on the map to set pickup location</p>');
        $('.page-alert').animate({right: '10px'}, 2000);
        setTimeout(function () {
            var showPageAlert = setInterval(function () {
                $('.page-alert').toggleClass('hide-me');
            }, 1500);
        }, 4000);

        $('#form-create-trip').on('submit', function () {

            
            var trip_name = $('#trip_name').val().trim();
            var asset_id = $('#asset_id').val().trim();
            var route_id = $('#route_id').val().trim();
            var pickup_address = $('#pickup_address').val().trim();
            var pick_lat = $("#pick-lat").val();
            var pick_lng = $("#pick-lng").val();
            var pick_icon = $("#pick-icon").val();
            var destination_address = $('#destination_address').val().trim();
            var end_lat = $("#end-lat").val();
            var end_lng = $("#end-lng").val();
            var dest_icon = $("#dest-icon").val();            
            var client_id = $('#client_id').val().trim();
            var client_name = $('#client').val().trim();
            var consignment = $('#consignment').val().trim();
            //var input = $("#pac-input").val().trim();
            
            var $this = $(this);

            if (pick_lat.length == 0 || end_lat.length == 0) {
                swal({title: "Info", type: "info", text: "Please select pickup and destination locations"});
                return false;
            }

            if (trip_name.length == 0 || asset_id.length == 0 || route_id.length == 0 || destination_address.length == 0 || client_id.length == 0 || consignment.length == 0 || pickup_address.length == 0 || pick_lat.length == 0 || end_lat.length == 0) {
                swal({title: "Info", type: "info", text: "Please fill in all required fields"});
                return false;
            }

            /*if (input.length == 0) {
                swal({title: "Info", type: "info", text: "Please select trip destination"});
                return false;
            }*/

            //return false;
            
            swal({   
                title: "Info",   
                text: "Create trip?",   
                type: "info",   
                showCancelButton: true,   
                closeOnConfirm: false, 
                allowOutsideClick: false,  
                showLoaderOnConfirm: true, 
                //confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, create it!",
                closeOnConfirm: false, 

                }, function(){
                    $.ajax({
                        type : "POST",
                        cache : false,
                        data : {trip_name:trip_name, asset_id:asset_id, route_id:route_id, client_id:client_id, client_name:client_name, pickup_address:pickup_address, pick_lat:pick_lat, pick_lng:pick_lng, pick_icon:pick_icon, destination_address:destination_address, end_lat:end_lat, end_lng:end_lng, dest_icon:dest_icon, consignment:consignment},
                        url : "<?php echo base_url('index.php/settings/save_trip') ?>",
                        success: function(response) {
                            
                            if (response==1) {
                                $('.clear-input').val('');
                                $('#destination-input').val('');
                                
                                swal({title: "Info",text:'Trip created successfully', type:'success'});
                               
                            } else {
                                swal({title: "Info",text:'Sorry, Failed to save', type:'error'});
                                
                            }     
                        }
                        
                    });
                    
                });

           // return false;
        });
    });
</script>

<script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js')?>"></script>
<!--<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBn6m_W3hRMPg5nDlmqsRkaO3kE1LZ1HX4&libraries=places,drawing&callback=initMap"></script>
-->
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCfKP5H8r9ohlPjH_CbddIefMbeCirz7-U&libraries=places&callback=initMap">
  </script>