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
    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: <?= $map_lat; ?>, lng: <?= $map_long; ?>},
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            heading: 90,
            tilt: 45,
            streetViewControl: false,
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
            anchorPoint: new google.maps.Point(0, -29)
        });
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            get_place(place);
        });
        function get_place(place) {
            infowindow.close();
            marker.setVisible(false);
            if (!place.geometry) {
                //window.alert("Returned place contains no geometry");
                swal({title: "Place not found", text: 'Returned place contains no geometry', type: 'error'});
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(15); // Why 17? Because it looks good.
            }

            marker.setIcon(/** @type {google.maps.Icon} */({
                url: "<div class='form - group'>< label > Tel No < /label>" +
                        "< input type = 'text' class = 'form-control' id = 'tel_no' name = 'tel_no' >" +
                        "< /div>",
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            landmark_circle_color = $('#full-popover').val();
            newLatLng = place.geometry.location;
            show_marker(false);
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
            if (place.icon) {
                var place_icon = place.icon;
                $('#gmap_icon').val(1);
                $("#icon_path").val(place.icon);
            } else {
                $('#gmap_icon').val(0);
                $("#icon_path").val('');
            }


            $("#input-latitude").val(sel_lat);
            $("#input-longitude").val(sel_lng);
            $("#address").val(lnd_mark);
            $("#address").val(addrs);
            //$(".page-alert").children("p").html("You have <strong>selected</strong>  " + place.name);


            infowindow.setContent('<div><strong>' + place_name + '</strong><br>' + address);
            infowindow.open(map, marker);
        }

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
            var radioButton = document.getElementById(id);
            radioButton.addEventListener('click', function () {
                autocomplete.setTypes(types);
            });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
        map.addListener('click', function (event) {

            marker.setVisible(false);
            clearMarkers(new_landmark_markers);
            clearCircles(new_landmark_circles);
            newLatLng = event.latLng;
            var latitude = event.latLng.lat();
            var longitude = event.latLng.lng();
            var address = $('#address').val();
            landmark_circle_color = $('#full-popover').val();
            var landmark_image = $('#icon_path').val();
            var place_name = '';
            var address = '';
            $("#input-latitude").val(event.latLng.lat());
            $("#input-longitude").val(event.latLng.lng());
            var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        place_name = results[0].address_components[1].long_name + " " + results[0].address_components[0].long_name;
                        address = results[0].formatted_address;
                        $("#address").val(place_name);
                        $("#address").val(address);
                        //get_place(place);
                        //alert(JSON.stringify(results[0]));
                        show_marker(true);
                    } else {
                        swal({title: "Info", text: 'Could not get the Selected Place address', type: 'error'});
                        place_name = '';
                        address = '';
                        show_marker(true);
                    }
                } else {
                    swal({title: "Info", text: 'Could not get the Selected Place address', type: 'error'});
                    place_name = '';
                    address = '';
                    show_marker(true);
                }
            });
            //alert(place_name);

            ///place_name = "(" + place_name +")";

            //$(".page-alert").children("p").html("You have <strong>selected</strong> the position on the <strong>marker</strong> ");
        });
        function show_marker(show) {
            marker.setPosition(newLatLng);
            marker.setVisible(true);
            if (show) {
                marker.setIcon({
                    url: "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png",
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                });
            }

            range = parseFloat($("#landmark-radius").val());
            //addMarker(newLatLng, landmark_image, address);
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
                            content: landmarks[row].address,
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
                        fit_bounds();
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


    function addMarker(location, icon, address) {

        icon = "<?= base_url('" + icon + "') ?>";
        new_landmark_markers.push(new google.maps.Marker({
            position: location,
            map: map,
            //icon: icon,
            content: address,
            scaledSize: new google.maps.Size(20, 20)

        }));
        google.maps.event.addListener(new_landmark_markers[0], "mouseout", function (event) {
            iw_map.close();
        });
        google.maps.event.addListener(new_landmark_markers[0], "click", function (event) {
            swal({title: "Info", text: 'Landmark Created successfully', type: 'success'});
        });
        google.maps.event.addListener(new_landmark_markers[0], "mouseover", function (event) {
            iw_map.setContent(this.get("content"));
            iw_map.open(map, this);
        });
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


    });</script>

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
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

        #type-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }
</style>

<div class="container-fluid fleet-view">
    <div class="row">
        <div class="col-md-4 panel-default">
            <div class="panel panel-default" style="">
                <div class="panel-heading" style="background-color: #081833;">
                    <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-info-circle fa-fw"></i>Landmark Details</h3>
                </div>
                <div class="panel-body" style="" >
                    <div class="col-md-12 bg-crumb" style="" >
                        <h4>(<sup>*</sup> required fields)</h4>

                        <form id="form-create-landmark">
                            <div class="form-group">
                                <label>Landmark Name<sup>*</sup></label>
                                <input class="form-control input-sm clear-input" name="landmark_name" id="landmark_name" type="text" placeholder="Landmark Name" required="required">
                            </div>
                            <div class="form-group">
                                <label>Address<sup>*</sup></label>
                                <input class="form-control input-sm clear-input" readonly name="address" id="address" type="text" placeholder="Address" required="required">
                            </div>
                            <div class="form-group">
                                <label>Landmark circle background <sup>*</sup></label>
                                <input type="text" class="form-control" id="full-popover" name="landmark_circle_color" value="#337ab7" data-color-format="hex" required="required">
                            </div>
                            <div class="form-group">
                                <label>Radius (In KM) <sup>*</sup></label>
                                <input class="form-control input-sm clear-input" min="0.05" step="0.01" name="radius" id="landmark-radius" value="0.05" type="number" placeholder="Enter the landmark radius" required="required">
                            </div>
                            <!-- <div class="form-group">
                                 <label>Alert before Landmark(KM) <sup>*</sup></label>
                                 <input class="form-control input-sm" min="1" value="1" id="alert_before_landmark" name="alert_before_landmark" type="number" placeholder="Specify the distance for alerts" required="required">
                             </div>-->
                            <!--                        <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-sm-3">

                                                                <input class="alert-checks" name="in_alert" type="checkbox" value="1" placeholder=""><br><label>In Alert</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input class="alert-checks" name="out_alert" type="checkbox" value="1" placeholder=""><br><label>Out Alert</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input class="alert-checks" name="sms_alert" type="checkbox" value="1" placeholder=""><br><label>SMS Alert</label>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <input class="alert-checks" name="email_alert" type="checkbox" value="1" placeholder=""><br><label>Email Alert</label>
                                                            </div>
                                                        </div>
                                                    </div>-->
                            <div class="form-group">
                                <input class="form-control input-sm  clear-input" id="input-latitude" value="" name="latitude" type="hidden">
                                <input class="form-control input-sm  clear-input" id="input-longitude" value="" name="longitude" type="hidden">
                            </div>

                            <div class="form-group margin-top-10" style="border-top: 1px solid #ccc; padding-top: 5px">

                                <div class="col-sm-6 margin-top-10">
                                    <button class="btn btn-default btn-block" id="btn-clear">Clear</button>
                                </div>
                                <div class="col-sm-6 margin-top-10">
                                    <button class="btn btn-success btn-block" type="submit" id="btn-create">Create</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <input id="pac-input" class="controls" type="text"
                placeholder="Enter a location">
            <div id="type-selector" class="controls">
                <input type="radio" name="type" id="changetype-all" checked="checked">
                <label for="changetype-all">All</label>

                <input type="radio" name="type" id="changetype-establishment">
                <label for="changetype-establishment">Establishments</label>

                <input type="radio" name="type" id="changetype-address">
                <label for="changetype-address">Addresses</label>

                <input type="radio" name="type" id="changetype-geocode">
                <label for="changetype-geocode">Geocodes</label>
            </div>
            <div class="col-md-12" id='map_canvas' style="height: 70vh;"></div>
            <!--            <div class="control-bar">
                            <div class="row" align="center">
                                <div class="col-md-4 col-md-offset-4">
                                    <button class="btn btn-default btn-block" id="clear-route">Clear Map</button>
                                </div>
                            </div>
                        </div>-->
        </div>

        <script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js') ?>"></script>

        <script type="text/javascript">

            $(function () {
                $("input#full-popover").ColorPickerSliders({
                    color: "rgb(36,170,242)",
                    sliders: true,
                    placement: 'right',
                    hsvpanel: true,
                    previewformat: 'hex'
                });
                $('.page-alert').html('<p align="center">Click or Search on the map to Set a Landmark</p>');
                $('.page-alert').animate({right: '10px'}, 2000);
                setTimeout(function () {
                    var showPageAlert = setInterval(function () {
                        $('.page-alert').toggleClass('hide-me');
                    }, 1500);
                }, 4000);
                $('#landmark-radius').on('keyup change', function () {

                    var radius = parseFloat($(this).val().trim());
                    //alert(radius);

                    clearCircles(new_landmark_circles);
                    if (!$.isNumeric(radius) || radius == 0) {
                        clearCircles(new_landmark_circles);
                    } else {
                        range = radius;
                        addCircle(newLatLng);
                    }



                });
                $('#btn-clear').on('click', function () {
                    clearCircles(new_landmark_circles);
                    clearMarkers(new_landmark_markers)
                    $(document).find('.clear-input').val('');
                    $(document).find('.alert-checks').prop('checked', false);
                    $('.page-alert').html('<p align="center">Click on the map to set landmark position</p>');
                });
                $('#form-create-landmark').on('submit', function () {
                    var latitude = $('#input-latitude').val().trim();
                    var longitude = $('#input-longitude').val().trim();
                    var landmark_name = $('#landmark_name').val().trim();
                    var address = $('#address').val().trim();
                    var landmark_circle_color = $('#full-popover').val().trim();
                    var landmark_image = "custom_icon";
                    var radius = $('#landmark-radius').val().trim();
                    var $this = $(this);


                    if (latitude.length == 0 || longitude.length == 0) {
                        swal({title: "Info", type: "info", text: "Please select outlet location/point on the map"});
                        return false;
                    }

                    if (landmark_name.length == 0 || landmark_circle_color.length == 0 || radius == 0 || address.length == 0) {
                        swal({title: "Info", type: "info", text: "Please fill in all required fields"});
                        return false;
                    }

                    var $this = $(this);
                    swal({
                        title: "Info",
                        text: "Create Landmark?",
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    }, function () {
                        $.ajax({
                            type: "POST",
                            cache: false,
                            data: $this.serialize(),
                            url: "<?php echo base_url('index.php/settings/save_landmark') ?>",
                    success: function (response) {
                        if (response == 1) {
                            $('.clear-input').val('');
                            $("input[type=checkbox]").prop('checked', false);
                            clearMarkers(landmark_markers);
                            marker.setVisible(false);
                            load_landmarks();
                            swal({title: "Info", text: 'Landmark Created successfully', type: 'success'},function(){
                                document.location.href = "<?php echo site_url('gps_tracking/geo_data') ?>";
                            });
                        } else {
                            swal({title: "Info", text: 'Sorry, Failed to save', type: 'error'});
                        }

                    }

                });
            });
            return false;
        });
        $('.div-landmarks-images').on('click', 'li', function () {
            var selected_path = $(this).attr('value');
            $('#icon_path').val(selected_path);
            $('.div-landmarks-images').find('li').removeClass('highlighted');
            $(this).addClass('highlighted');
        });
    });</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?=$this->config->item("map_key")?>&libraries=places,drawing&callback=initMap">
</script>