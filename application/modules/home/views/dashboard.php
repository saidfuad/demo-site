<?php if (in_array(1, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>

<script type="text/javascript">
  var infowindow;
  var vehicle_markers = [];
  var landmark_markers = [];
  var pop_marker = [];
  var map_pop;
  var map;
  var iw_map;
  var zones = [];

 

    function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {lat: -4.0434771, lng:39.6682065},
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            heading: 90,
            tilt: 45
        });


        iw_map = new google.maps.InfoWindow({
            content: ''
        });

        load_landmarks();
        load_zones ();
    }

    function load_landmarks () {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/settings/get_company_landmarks') ?>",
            data : {company:'this company'},
            success: function(data) {
                landmarks = JSON.parse(data);

                //alert(landmarks.length);
                if(landmarks.length > 0) { 
                  for (var row in landmarks) {
                        if(landmarks[row].gmap_icon == 0) {
                            $icon = "<?= base_url('" + landmarks[row].icon_path+ "')?>";
                        } else {
                            $icon = landmarks[row].icon_path;
                        }
                    $pos = {lat:parseFloat(landmarks[row].latitude, 10), lng: parseFloat(landmarks[row].longitude)};
                    //console.log(parseInt(landmarks[row].latitude, 10));
                      landmark_markers.push(new google.maps.Marker({
                            position: $pos,
                            map: map,
                            icon: $icon, 
                            content:landmarks[row].landmark_name,
                            scaledSize: new google.maps.Size(20, 20),
                      }));
                  }

                  var llength =landmark_markers.length;

                  for (var i=0; i<llength; i++) { 
                          landmark_markers[i].setIcon(({
                            url:landmark_markers[i].icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(12, 24),
                            scaledSize: new google.maps.Size(24, 24)
                          }));

                      google.maps.event.addListener(landmark_markers[i], "mouseout", function(event) {
                        iw_map.close();
                      });

                      google.maps.event.addListener(landmark_markers[i], "click", function(event) {
                        map.setCenter(this.getPosition());
                        map.setZoom(15);
                        map.setTilt(45);

                        console.log(event.latLng.lat() +',' + event.latLng.lng());
                        
                      });
                      
                      google.maps.event.addListener(landmark_markers[i], "mouseover", function(event) {
                          iw_map.setContent(this.get("content"));
                          iw_map.open(map, this);
                      });

                  }
              } 

            }
        });
  }

    function load_zones () {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/home/get_company_zones') ?>",
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

                    zones.push( new google.maps.Polygon({
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

    </script>

<?php } ?>
<div class="container-fluid">
    <div class="row fleet-content">

      <div class="col-lg-4">
            <div class="row">
                <!-- Service Reminders -->
                <div class="col-md-12">
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h4 class="panel-title">Statistics</h4>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row">
                                <div class="col-sm-3 text-center">
                                    <h1 class="warning"><a href="
                                        <?php if ($this->session->userdata('protocal') > 5) {
                                            echo base_url('index.php/settings/devices');
                                        } else {
                                            echo '#';
                                        }
                                        ?>"> <?php echo $count_devices; ?></a></h1>
                                    <span class="caption">Devices</span>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <h1 class="warning"><a href="<?php echo base_url('index.php/vehicles/groups') ?>"> <?php echo $count_groups; ?></a></h1>
                                    <span class="caption">Groups</span>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <h1 class="success"><a href="<?php echo base_url('index.php/vehicles') ?>"> <?php echo $a_v_num; ?></a></h1>
                                    <span class="caption">Vehicles</span>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <h1 class="warning"><a href="<?php echo base_url('index.php/home/total_drivers') ?>"> <?php echo $a_d_num; ?></a></h1>
                                    <span class="caption">Drivers</span>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Various Data
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="graphs-pie-chart"></div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- <div class='col-lg-12'>
                    
                    <div class="user-card-mini row">
                        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                            <h1><?php echo $count_untracked; ?></h1>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <span>Untracked</span>
                            <span>Vehicles</span>
                        </div>
                    </div> 
                    <div class="user-card-mini row">
                        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                            <h1><?php echo $count_available_devices; ?></h1>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <span>Available</span>
                            <span>Devices</span>
                        </div>
                    </div>
                    <div class="user-card-mini row">
                        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                            <h1><?php echo $count_unassigned_groups; ?></h1>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <span>Unassigned</span>
                            <span>Groups</span>
                        </div>
                    </div>
                    <div class="user-card-mini row">
                        <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                            <h1><?php echo $count_unassigned_users; ?></h1>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <span>Unassigned</span>
                            <span>Users</span>
                        </div>
                    </div>
                </div> -->

                <div class="col-lg-12 col-md-12">
                    <!-- Timeline Activity Widget -->
                    <div class="panel panel-success">
                        <div class="panel-heading clearfix">
                            <i class="fa fa-clock-o fa-fw"></i> Updates
                           
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body has-nice-scroll" tabindex="5000" style="height: 500px;">
                            <ul class="timeline">
                                <?php  if (sizeof($alerts)) {  $count = 1;?>
                                    <?php
                                    foreach ($alerts as $value) {
                                        if (strtolower($value->alert_header) == 'overspeeding') {
                                            $icon = "<i class='fa fa-dashboard fa-spin fa-2x'></i>";
                                        } else if (strtolower($value->alert_header) == 'near landmark alert') {
                                            $icon = "<i class='fa fa-location-arrow fa-2x'></i>";
                                        } else if (strtolower($value->alert_header) == 'tyre pressure') {
                                            $icon = "<i class='fa fa-circle-o-notch fa-2x'></i>";
                                        } else if (strtolower($value->alert_header) == 'destination reached') {
                                            $icon = "<i class='fa fa-thumbs-up fa-2x'></i>";
                                        } else if (strtolower($value->alert_header) == 'route infringement') {
                                            $icon = "<i class='fa fa-road fa-2x'></i>";
                                        }

                                        if (fmod($count, 2) == 0) {
                                            $liclass = "";
                                        } else {
                                            $liclass="timeline-inverted";
                                        }


                                        ?>
                                        <li class="<?php echo $liclass; ?>">
                                            <div class="timeline-badge"><?php echo $icon; ?>
                                            </div>
                                            <div class="timeline-panel">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title remove margin bottom"><?php echo $value->alert_header; ?></h4>
                                                    <p>
                                                        <small class="text-muted"><i class="fa fa-time"></i> <?php echo $value->add_date; ?></small>
                                                    </p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p><?php echo $value->alert_msg; ?></p>
                                                </div>
                                            </div> 
                                        </li>
                                    <?php $count++;} ?>
                                    <?php } else { ?>
                                        <li class=''>
                                            <div class="timeline-badge full"><i class="fa fa-flag"></i></div>
                                            <div class="timeline-panel full">
                                                <div class="timeline-body was-at">
                                                    <div class="well">
                                                        <div class="row">
                                                            <div class="col-md-2 text-center">
                                                                <i class="fa fa-flag fa-5x"></i>
                                                            </div>
                                                            <div class="col-md-10">
                                                                
                                                                <p>No Alerts available now</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <!-- End of Timeline Activity Widget -->
                    
                </div>
                
            </div>

           
            <!--<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Service Costs</h4>
                        </div>
                        <div class="panel-body">
                            <div id="service-costs"></div>
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Fuel Costs</h4>
                        </div>
                        <div class="panel-body">
                            <div id="fuel-costs"></div>
                        </div>
                    </div>
                    
                </div>
                
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Latest Meter Readings</h4>
                        </div>
                        <div class="panel-body">
                            <div id="latest-meter-readings"></div>
                        </div>
                    </div>
                    
                </div>
               
            </div>-->
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Vehicle status chart
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="status-pie-chart"></div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                
                
                <!--<div class="col-lg-4">

                    <div class="panel panel-default">
                        <div class="panel-heading padding padding-all">
                            <h4 class="panel-title">Vehicles</h4>
                        </div>
                       
                        <div class="panel-body" style="height: 330px; overflow-y: scroll;">
                            <?php if (sizeof($vehicles)) { ?>
                                <table class="table table-hover table-striped">
                                    <tbody>
                                                <?php foreach ($vehicles as $value) { ?>

                                            <tr>
                                                <td width="10%"><img src="<?php echo base_url('assets/images/categories') . '/' . $value->assets_cat_image; ?>" class="img-circle img-vehicle" /></td>
                                                <td>
                                                    <?php echo $value->assets_name; ?><br />
                                                    <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="View stats of this vehicle" title="View stats of this vehicle"><i class="fa fa-area-chart"></i>
                                                    </a>&nbsp;|&nbsp;
                                                    <i class="fa fa-dashboard"></i>
                                                    <a href="#" data-toggle="tooltip" data-container="body" data-placement="top" title="Edit odometer" title="Edit odometer">153,745</a> km
                                                </td>
                                                <td class="text-right">

                                                <?php echo "<a href='" . base_url('index.php/vehicles/fetch_vehicle/' . $value->asset_id)
                                                . "'class='btn btn-info btn-xs'><span class='fa fa-eye'></span></a>"
                                                ?>
                                                </td>

                                            </tr><?php } ?>
                                    </tbody>
                                </table>

                            <?php } else { ?>
                                <div class="alert alert-info"> You have 0 Vehicles  <a href="<?php echo site_url('vehicles/add_vehicle'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Vehicles</a><br></div>
                            <?php } ?>
                        </div>                        
                        
                    </div>
                    
                </div>-->
                    <div class="col-lg-8">
                    <?php if (in_array(1, explode(',', $this->session->userdata('itms_company_subscriptions')))) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading padding padding-all">
                                    <h4 class="panel-title">GPS Surveillance <span class="btn btn-success btn-xs pull-right ">Refresh after 30seconds</span></h4>
                                </div>
                                <!-- /.panel-heading -->

                                <div class="panel-body" style="height: 430px;;" id="map_canvas">

                                </div>                        
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Trips status Charts
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div id="trips-status-chart"></div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <div class="col-lg-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Trips Analytics
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div id="trips-chart"></div>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                         
                    </div>

                </div>
            </div>
            
        </div>
    </div>
    <!-- /.row --> 
</div>



<!-- Page-Level Plugin Scripts - Dashboard
    <script src="<?php echo base_url('assets/js/plugins/morris/raphael-2.1.0.min.js') ?>"></script>
    
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.tooltip.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.resize.js') ?>"></script>
    
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.tooltip.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.time.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.resize.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/demo/fleet_dashboard.js') ?>"></script>

-->

<script type="text/javascript">

  $(function () {


      var refresh_interval = 30 * 1000;
      var refresh = setInterval(function(){ refresh_vehicle_locations(2); }, refresh_interval);
      
      //console.log(markers_map.length);
      refresh_vehicle_locations(1);

           
      var count = 0;
     
      function refresh_vehicle_locations (times) {
          
          var refresh_session = parseInt($('#refresh-session').val(), 10);
          display_alert('Refreshing...');
          
          if (times > 1) {
            clearVehicleMarkers();
          }
          
          
          $('#refresh-session').val(refresh_session + 1);


          //return false;

          $.ajax({
                  type    : "POST",
                  cache   : false,
                  data : {none:'none'},
                  url     : "<?php echo base_url('index.php/gps_tracking/refresh_grid') ?>",
                  success: function(response) {
                      res = JSON.parse(response);
                      var message = [];  
                      var LatLngList = []; 

                      //alert(res.vehicles.length);
                      
                      for(var vehicle in res.vehicles) {

                          $asset_id = res.vehicles[vehicle].asset_id;
                          $device_id = res.vehicles[vehicle].device_id;

                          if(parseInt(res.vehicles[vehicle].ignition) == 1 && parseInt(res.vehicles[vehicle].speed) > 0) {
                              $icon = "<?= base_url('assets/images/gps/marker-normal.png') ?>";
                              $speed_message = "Vehicle Moving Normally";
                              //message.push($speed_message);
                          } else if (parseInt(res.vehicles[vehicle].ignition) ==1 && parseInt(res.vehicles[vehicle].speed) == 0) {
                              $icon = "<?= base_url('assets/images/gps/marker-idle.png') ?>";
                              $speed_message = "Vehicle is idle";
                              message.push($speed_message);
                          } else if (parseInt(res.vehicles[vehicle].speed) > parseInt(res.vehicles[vehicle].max_speed_limit)) {
                              $icon = "<?= base_url('assets/images/gps/marker-danger.png') ?>";
                              $speed_message = "Overspeeding";
                              message.push($speed_message);
                          } else if (parseInt(res.vehicles[vehicle].ignition) == 0 ) {
                              $icon = "<?= base_url('assets/images/gps/marker-warning.png') ?>";
                              $speed_message = "Vehicle has stopped";
                              message.push($speed_message);
                          }

                          $time = res.vehicles[vehicle].tm; 

                          $date = res.vehicles[vehicle].dt;
                          $date = $date.split(" ");
                          $date = $date[0];
                          $date = new Date($date +" "+$time);

                          if ( res.vehicles[vehicle].ignition == 1) {
                            $ignition = '<button class="btn btn-success btn-xs">On</button>';
                          } else {
                            $ignition = '<button class="btn btn-default btn-xs">Off</button>';
                          }

                          $vehicle_name = res.vehicles[vehicle].assets_name;
                          $position = {lat: parseFloat(res.vehicles[vehicle].latitude), lng: parseFloat(res.vehicles[vehicle].longitude)};
                          //$icon = "<?= base_url('assets/images/gps/normals.png') ?>";
                          $marker_type = 'vehicle';

                          $title = 'Vehicle Name: ' + res.vehicles[vehicle].assets_friendly_nm + ' <br>Plate No.:' + res.vehicles[vehicle].assets_name +' <br>Ignition: ' + $ignition +' <br>Speed: ' + res.vehicles[vehicle].speed + "Kmh <br>Near address: " + res.vehicles[vehicle].address + "<br>Date: " + $date + "<br> <p class='more'><b> Click Vehicle Icon For More Details"+"</b></p>";
                          $asset_name = res.vehicles[vehicle].assets_name + " - " + res.vehicles[vehicle].assets_friendly_nm;

                          addMarker($marker_type, $title, $position, $icon, $asset_id, $device_id, $asset_name);
                          //  Make an array of the LatLng's of the markers you want to show
                          
        
                      } 


                      for (var i=0; i<vehicle_markers.length; i++) {
                          google.maps.event.addListener(vehicle_markers[i], "mouseout", function(event) {
                            iw_map.close();
                          });

                          google.maps.event.addListener(vehicle_markers[i], "click", function(event) {
                            var posl = this.getPosition();
                            console.log(event.latLng.lat() +',' + event.latLng.lng());
                            map.setCenter(posl);
                            map.setZoom(15);
                            map.setTilt(45);

                            $('#map-pop').fadeIn(1000);
                            $('.overshadow').fadeIn(1000);

                            get_vehicle_details (this.asset_id, this.device_id, this.content, this.icon, posl);
                            
                          });
                          
                          google.maps.event.addListener(vehicle_markers[i], "mouseover", function(event) {
                              iw_map.setContent(this.get("content"));
                              iw_map.open(map, this);
                          });

                          google.maps.event.addListener(vehicle_markers[i], "doubleclick", function(event) {
                              alert();
                          });
                      }                     

                      hideAlert();

                      if (res.vehicles.length) {
                        fit_bounds();
                      }
                      

                  }
                  
              });


            ///hideAlert();


        }



        function addMarker(type, title, location, image, asset_id, device_id) {

            vehicle_markers.push(new google.maps.Marker({
                position: location,
                map: map,
                icon: image,
                content: title,
                animation: google.maps.Animation.DROP,
                asset_id: asset_id,
                device_id: device_id
            }));

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
            $('.page-alert').animate({right: '10px'}, 2000);
        }

        function hideAlert() {
            $('.page-alert').animate({right: '-600px'}, 2000);
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


});
  
</script>    

<?php if (in_array(1, explode(',',$this->session->userdata('itms_company_subscriptions')))) {?>
<!--
<script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBn6m_W3hRMPg5nDlmqsRkaO3kE1LZ1HX4&callback=initMap"></script>
    -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCfKP5H8r9ohlPjH_CbddIefMbeCirz7-U&callback=initMap">
    </script>

<?php } ?>

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="<?php echo base_url('assets/js/plugins/morris/raphael-2.1.0.min.js')?>"></script>


    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.tooltip.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.resize.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.time.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.pie.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/morris/morris.js')?>"></script>
    
    <script type="text/javascript">
        //Flot Pie Chartalert("this");
  
  $(function() {
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "<?php echo base_url('index.php/home/graphs') ?>",
                             
                dataType: "html",   //expect html to be returned                
                success: function(response){  
                     var graphsData = JSON.parse(response);
                     // alert (response);
                      var plotGraph = $.plot($("#graphs-pie-chart"), graphsData, {
                            series: {
                                pie: {
                                    show: true
                                }
                            },
                            grid: {
                                hoverable: true
                            },
                            tooltip: true,
                            tooltipOpts: {
                                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                                shifts: {
                                    x: 20,
                                    y: 0
                                },
                                defaultTheme: true
                            }
                        });
                    
                }

            });

        });
    $(function() {
        
        $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "<?php echo base_url('index.php/home/trips') ?>",
                     
        dataType: "html",   //expect html to be returned                
        success: function(response){  
             var data = JSON.parse(response);
            if ($("#trips-status-chart").length) {
                Morris.Donut({
                    element: 'trips-status-chart',
                    data: data,
                    resize: true
                });
            }
        }

    });
    
            
        });

         $(function() {

            if ($("#trips-chart").length) {

                var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                Morris.Area({
                    element: 'trips-chart',
                    data: [
                    { m: '2016-01', a: 8},
                    { m: '2016-02', a: 55},
                    { m: '2016-03', a: 50},
                    { m: '2016-04', a: 75},
                    { m: '2016-05', a: 0}
                    ],
                    xkey: 'm',
                    ykeys: ['a'],
                    labels: ['Trips'],
                      xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
                        var month = months[x.getMonth()];
                        return month;
                      },
                      dateFormat: function(x) {
                        var month = months[new Date(x).getMonth()];
                        return month;
                      },
                    pointSize: 5,
                    hideHover: 'auto',
                    resize: true,
                    lineColors: ['#73d175'],
                    fillOpacity: 0.5,
                    pointFillColors: ['#B3EDA4']
                });
            }

        });
    </script>
    
