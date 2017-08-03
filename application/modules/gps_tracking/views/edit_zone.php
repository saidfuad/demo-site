<link href="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.css') ?>" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript">

    var infowindow;
    var zones = [];
    var pop_marker = [];
    var map_pop;
    var map;
    var iw_map;
    var new_zones_markers = [];
    var fillcolor =$("#full-popover").val();
    var countM = 0;
    var newLatLng = [];
    var marker;
    var infowindow;
    var place_icon = '';
    var markerContent ='';
    var newPolygon = '';
    var polygons = '';
    var vertices = '';
    var mapCords = [];


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


        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            clear_polygons();
            get_place(place);
        });

        function get_place (place) {
          infowindow.close();
          marker.setVisible(false);

          if (!place.geometry) {
            //window.alert("Returned place contains no geometry");
            swal({title: "Place not found",text:'Returned place contains no geometry', type:'info'});
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }

          marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
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
              place_icon = place.icon;
              //$("#icon_path").val(place.icon);
          } else {
              place_icon = "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png";
              //$('#gmap_icon').val(0);
              //$("#icon_path").val('');
          }


           $("#zone_name").val(lnd_mark);
          $("#address").val(addrs);


          $(".page-alert").children("p").html("You have <strong>selected</strong>  "+ place.name);

          markerContent = '<div><strong>' + place_name + '</strong><br>' + address;
          infowindow.setContent('<div><strong>' + place_name + '</strong><br>' + address);
          infowindow.open(map, marker);

        }



        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);


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

                //addMarker(newLatLng, landmark_image, landmark_name);
                //addCircle(newLatLng, landmark_circle_color);
              }


       iw_map = new google.maps.InfoWindow({
            content: ''
          });


       var drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: false,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.LEFT_CENTER,
            drawingModes: [
              google.maps.drawing.OverlayType.POLYGON
            ]
          },
          polygonOptions: {
            strokeColor: fillcolor,
            strokeOpacity: 0.7,
            strokeWeight: 2,
            fillColor: fillcolor,
            fillOpacity: 0.35,
            draggable: true,
            geodesic: true
          }
        });

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
            vertices = (polygon.getPath().getArray());
            newPolygon = polygon;
            polygons.push(newPolygon);
            drawingManager.setDrawingMode(null);

            // = $('#full-popover').val();

            google.maps.event.addListener(polygon, 'dragend', function() {
              vertices = (polygon.getPath().getArray());
              //alert(vertices);

              confirm ();
            });


            confirm ();



        });

        function confirm () {
          swal({
                title: "Info",
                text: "Save this zone?",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Edit Zone!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                closeOnConfirm: false,
                closeOnCancel: true

                }, function(isConfirm){
                    if (isConfirm === true) {
                      swal({title: "Info",text:'Fill In the form on the side then click CREATE', type:'info', timer: 6000});
                    }

                });
        }

        google.maps.event.addListener(drawingManager, "drawingmode_changed", function() {
            infowindow.close();
            //alert(polygon);
            if (drawingManager.getDrawingMode() == google.maps.drawing.OverlayType.POLYGON) {
                  clear_polygons();
            }

        });



       load_zones();

    }

    function clear_polygons () {
      for (var i=0; i < polygons.length; i++) {
                    polygons[i].setMap(null);
                  }
                  polygons = [];
                  vertices ='';


    }


    function load_zones () {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/settings/get_company_zones') ?>",
            data : {company:'this company'},
            success: function(data) {
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
                            zona.push(new google.maps.LatLng(
                                        parseFloat(data.vertices[vertex].latitude),
                                        parseFloat(data.vertices[vertex].longitude)
                                ));
                       }

                        mapCords.push(new google.maps.LatLng(
                            parseFloat(data.vertices[vertex].latitude),
                            parseFloat(data.vertices[vertex].longitude)
                        ));
                    }

                    zones.push(new google.maps.Polygon({
                                  paths: zona,
                                  strokeColor: data.zones[zone].zone_color,
                                  strokeOpacity: 0.8,
                                  strokeWeight: 2,
                                  fillColor: data.zones[zone].zone_color,
                                  fillOpacity: 0.35,
                                  zone:data.zones[zone].zone_name,
                                  address: data.zones[zone].address
                                }));

                     zones[zones.length-1].setMap(map);

                     google.maps.event.addListener(zones[zones.length-1], 'mouseover', function(event) {
                        var contentString = "<strong>Zone</strong>:" + this.zone + "<br><strong>Address</strong>: " + this.address;
                        //alert(contentString);
                        iw_map.setContent(contentString);
                        iw_map.setPosition(event.latLng);
                        iw_map.open(map);
                      });

                      google.maps.event.addListener(zones[zones.length-1], 'mouseout', function(event) {
                        iw_map.close();
                      });

                  }

                  fit_bounds();

                }
            }
        });
    }

    function rotate90 () {
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
        if (countMarkers>0) {
            for (var i = 0; i < countMarkers; i++) {
                new_landmark_markers[i].setMap(null);
            }
        }
    }


    function fit_bounds () {
        var bounds = new google.maps.LatLngBounds();
        var vsize = mapCords.length;

        for(i=0; i<vsize; i++) {
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


<div>
    <div class="row">
        <div class="col-md-12" >
            <div class="" style="height:400px;position: absolute; right:30px;margin-top:40px;width:330px;z-index:5000;display:;">

                <div class="col-md-12 bg-crumb" style="box-shadow:3px 3px 3px #333;" >
                <h4>Edit Zones( <sup>*</sup> Required)</h4>
                <form id="form-create-zone">
                    <div class="form-group">
                        <label>Zone Name <sup>*</sup></label>
                        <input type="hidden" name="zone_id" value="<?php echo $fetch_zone['zone_id']; ?>" />
                        <input class="form-control input-sm clear-input" name="zone_name" id="zone_name" type="text" placeholder="Type zone name" required="required" value="<?php echo $fetch_zone['zone_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input class="form-control input-sm clear-input" id="address" name="address" type="text" placeholder="Enter address" value="<?php echo $fetch_zone['address']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Zone_color <sup>*</sup></label>
                         <input type="text" class="form-control" id="full-popover" name="zone_color" value="#337ab7" data-color-format="hex" required="required" value="<?php echo $fetch_zone['zone_color']; ?>">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <input name="in_alert" id="in" type="hidden" value="0" />
                                <input class="alert-checks" id="in_alert" type="checkbox" <?php if($fetch_zone['in_alert'] == 1) echo "checked" ?> /><br><label>In Alert</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="out_alert" id="out" type="hidden" value="0" />
                                <input class="alert-checks" id="out_alert" type="checkbox" <?php if($fetch_zone['out_alert'] == 1) echo "checked"; ?>><br><label>Out Alert</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="sms_alert" id="sms" type="hidden" value="0" />
                                <input class="alert-checks" id="sms_alert" type="checkbox" <?php if($fetch_zone['sms_alert'] == 1) echo "checked"; ?>><br><label>SMS Alert</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="email_alert" id="email" type="hidden" value="0" />
                                <input class="alert-checks" id="email_alert" type="checkbox" <?php if($fetch_zone['email_alert'] == 1) echo "checked"; ?>><br><label>Email Alert</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group margin-top-10" style="border-top:1px solid #ccc;padding-top: 5px">
                        <div class="col-sm-6 margin-top-10">
                           <button class="btn btn-success btn-block" id="btn-create">Update</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

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
            <div class="col-md-12" id='map_canvas' style="height:610px"></div>
            <div class="control-bar">
              <div class="row" align="center">
                  <div class="col-md-4 col-md-offset-4">
                      <button class="btn btn-default btn-block" id="clear-zone">Clear Zone</button>
                  </div>
              </div>
          </div>


        </div>
        <!-- //. Content -->
    </div>
    <!-- /.row -->
</div>

<script type="text/javascript">
    $(function() {

        function clear_zones () {
            for (var i=0; i < zones.length; i++) {
                    zones[i].setMap(null);
                  }
                  zones = [];
        }

        $('#clear-zone').on('click', function () {
            if (polygons.length) {
              clear_polygons();
              swal({ title: "Info", type: "info", text: "Selected zone cleared"});
            }


                return false;
        });

        $('.page-alert').html('<p align="center">Click/Search on the map to select a zone point</p>');
        $('.page-alert').animate({right:'10px'}, 2000);
        setTimeout(function(){
          var showPageAlert = setInterval(function(){ $('.page-alert').toggleClass('hide-me'); }, 1500);
        }, 4000);


        $("input#full-popover").ColorPickerSliders({
          placement: 'right',
          hsvpanel: true,
          previewformat: 'hex'
        });


        $('#btn-clear').on('click', function () {
            clearMarkers(new_zones_markers)
            $(document).find('.clear-input').val('');
            $(document).find('.alert-checks').prop('checked', false);
            $('.page-alert').html('<p align="center">Click on the map to select a zone</p>');
        });


        $('#form-create-zone').on('submit', function () {
            var zone_name = $('#zone_name').val().trim();
            var zone_color= $('#full-popover').val().trim();
            var address = $('#address').val().trim();

            if($('#in_alert').is(':checked')){
                $("#in").val(1);
            }
            if($('#out_alert').is(':checked')){
                $("#out").val(1);
            }
            if($('#sms_alert').is(':checked')){
                $("#sms").val(1);
            }
            if($('#email_alert').is(':checked')){
                $("#email").val(1);
            }

            var $this = $(this);
            //var data = $this.serialize() +'&vertices='+ vertices;

            /*if (vertices.length==0) {
                swal({ title: "Info", type: "info", text: "Please select a zone on the map first"});
                return false;
            }*/


            if (zone_name.length==0 || zone_color.length==0 || address.length==0) {
                swal({ title: "Info", type: "info", text: "Please fill in all required fields"});
                return false;
            }

            swal({
                title: "Info",
                text: "Edit Zone?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true,

                }, function(){
                    $.ajax({
                        type    : "POST",
                        cache   : false,
                        data: $this.serialize(),
                        url     : "<?php echo base_url('index.php/settings/update_zone') ?>",
                        success: function(response) {

                            if (response==1) {
                                swal({title: "Info",text:'Zone Update successfully', type:'success'});

                            } else {
                                swal({title: "Info",text:'Zone creation failed, Try again.', type:'error'});

                            }

                        }

                    });

                });

            return false;

        });
    });
</script>
<script src="<?php echo base_url('assets/bootstrap-color-picker/src/bootstrap.colorpickersliders.js')?>"></script>

<!--
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBn6m_W3hRMPg5nDlmqsRkaO3kE1LZ1HX4&libraries=places,drawing&callback=initMap"></script>
-->

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfKP5H8r9ohlPjH_CbddIefMbeCirz7-U&libraries=places,drawing&callback=initMap"></script>
