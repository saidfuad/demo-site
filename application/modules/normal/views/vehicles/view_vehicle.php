<style type="text/css">
    #map-pop {
        display: none;
        display: none;
        position: fixed;
        right: 50px;
        top: 55px;
        left: 300px;
        width: 55%;
        height: 500px;
        border: 1px solid #303641;
        /*border:2px solid #eee;*/
        z-index: 3000;
        border-radius: 0px;
        background: #fff;
        border-radius: 5px;
    }

    .live-vehicle-list {
        width: 100%;
        background: ;
        padding: 0px;
    }

    .live-vehicle-list li:first-child {
        border-top: 1px solid #ddd;
    }

    .live-vehicle-list li {
        height: 30px;
        border-bottom: 1px solid #ddd;
        list-style-type: none;
        width: 100%;
        overflow-y: hidden;
        vertical-align: middle;
        padding: 5px 5px 5px 10px;
        color: #333;
        cursor: pointer;
        font-size: 13px;
    }

    .live-vehicle-list li:hover {
        background: #f5f5f5;
        color: #333;
    }

    .live-vehicle-list li a {
        text-decoration: none;
        color: #fff;
    }

    .more {
        background-color: #18bc9c;
        color: #fff;
        border: solid 2px #eee;
        width: 260px;
        border-radius: 5px;
        text-transform: uppercase;
        text-underline-position: left;
        margin-top: 7px;
        padding: 5px;
        /*padding-bottom: 5px;*/
    }

    .fleet-issues {
        border: 2px solid #c1d72e;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .no-vehicle-overlay {
        position: absolute;
        background: rgba(31, 35, 42, .5);
        margin-left: 14px;
        width: 95%;
        height: 368px;
        z-index: 20000;
    }

    .no-vehicle-title {
        width: 100%;
        height: 368px;
        background-color: rgba(31, 35, 42, .75);
        border: none;
        color: #fff;
        padding-top: 72px;
        padding-left: 100px;
        padding-right: 100px;
        font-size: 16px;
        border-radius: 0;
        z-index: 20000;
    }

    .order.btn.btn-success {
        background: rgba(31, 35, 42, 1);
        color: #fff;
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
</style>
<?php foreach ($vehicle as $value) { ?>
    <div class="container-fluid fleet-view">
        <div class="row" ng-app>
            <div class="row">
                <div class="col-md-5">
                    <!-- Details -->
                    <div data-toggle='tooltip' data-original-title='Displays the status of your vehicle and last location and time updated.' class="panel panel-blockquote panel-border-success left" id="fleet-car-details">
                        <div class="panel-heading" style="background-color:#1f232a;">
                            <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-car fa-fw"></i> &nbsp; <?php echo $value->plate_no ?></h3>
                            <?php if($this->session->userdata('hawk_user_type_id') != '7'){ ?>
                            <a data-toggle='tooltip' data-placement='right' data-original-title='Edit details of your vehicle' style="color:#1f232a" class="btn btn-success btn-xs pull-right" href="<?php echo base_url('index.php/vehicles/edit_vehicle/' . $value->vehicle_id); ?>">Edit Details</a>
                            <?php } ?>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover" style="margin-bottom:0">
                                <tbody>
                                    <input type="hidden" id="device_id" value="<?= $value->device_id; ?>" />
                                    <tr>
                                        <td class="text-muted text-left col-md-3">Model</td>
                                        <td>
                                            <?php echo $value->model; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Plate No.</td>
                                        <td>
                                            <?php echo $value->plate_no; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Address</td>
                                        <td>
                                            <?php
                                        if ($value->address == null) {
                                            echo "Not Available";
                                        } else {
                                            echo $value->address;
                                        }
                                        ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Max Speed</td>
                                        <td>
                                            <?php echo number_format($value->max_speed_limit) . " km/h"; ?>
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted text-left">Last Seen</td>
                                        <td>
                                            <?php echo date('d-m-Y H:i:s', strtotime($value->last_seen)); ?>
                                        </td>
                                        <td></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
                <div class="col-md-7">
                    <!-- Stats -->
                    <div class="row">
                        <!-- Service Reminders -->
                        <div class="col-md-12">
                            <div class="panel panel-square">
                                <div data-toggle='tooltip' data-original-title='Shows the current location and status of your vehicle.' class="panel-heading panel-info clearfix" style="background-color:#1f232a;">
                                    <h3 class="panel-title">Current Location</h3>
                                    <?php
                                    if ($value->device_id != 0 && ($this->session->userdata('hawk_user_type_id') != '7')) {
                                        echo " &nbsp; <a data-toggle='tooltip' data-original-title='Click to view the geofences that you have assigned for your vehicle.' data-placement='bottom' href='" . site_url('gps_tracking/geo_data?vehicle_id=') . $value->vehicle_id
                                        . "'class='btn btn-success btn-xs pull-right' style='color:#000'>Assigned Geofences</span></a>";
                                    }
                                    ?>
                                        <?php
                                    if ($value->device_id != 0) {
                                        echo " &nbsp; <a data-toggle='tooltip' data-original-title='Click to view history movement of your vehicle.' data-placement='bottom' href='" . base_url('index.php/ntsa/view_history/' . $value->vehicle_id)
                                        . "'class='btn btn-success btn-xs pull-right' style='margin-right:16px; color:#000'>History</span></a>";
                                    }
                                    ?> </div>
                                <div class="panel-body fleet-issues" style="padding: 0">
                                    <div class="row">
                                        <?php if ($value->device_id == "" || $value->device_id == null || $value->device_id == 0) { ?>
                                        <div class="no-vehicle-overlay">
                                            <div class="col-sm-6 col-md-6 bg-crumb no-vehicle-title" align="center">
                                                <h2>
                                                    <i class="fa fa-map-marker"></i> &nbsp; Vehicle Not Tracked
                                                </h2>
                                                <p>Currently this vehicle has no tracking device installed on it. Click on 'Order Devices' button to order a device or contact your nearest installer.</p>
                                                <br>
                                                <a class="btn btn-success order" href="<?php echo base_url(" index.php/orders/products ") ?>">
                                                    <span class="fa fa-shopping-cart fa-fw"></span> &nbsp; Order Devices
                                                </a>
                                                <br>
                                                <br>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div id="map" style="height:368px; margin:0px 12px">
                                            <script>

                                                var map;
                                                function initMap() {
                                                    map = new google.maps.Map(document.getElementById('map'), {
                                                        center: {
                                                            lat: <?php echo $value->latitude; ?>
                                                            , lng: <?php echo $value->longitude; ?>
                                                        }
                                                        , zoom: 15
                                                    });
                                                    console.log("add marker");
                                                    //icon = "<?= base_url('assets/images/gps/marker-idle.png') ?>";
                                                    fill_color = "#0000ff";
                                                    speed_message = "Idle";
                                                    vehicle_id = <?php echo $value->vehicle_id; ?>;
                                                    if (parseInt(<?php echo $value->ignition; ?>) == 1 && parseInt(<?php echo $value->speed; ?>) > 0 && parseInt(<?php echo $value->speed; ?>) < parseInt(<?php echo $value->max_speed_limit; ?>)) {
                                                        icon = "<?= base_url('assets/images/gps/marker-moving.png') ?>";
                                                        fill_color = "#4CAF50";
                                                        speed_message = "Moving";
                                                    }
                                                    if (parseInt(<?php echo $value->ignition; ?>) == 1 && parseInt(<?php echo $value->speed; ?>) == 0) {
                                                        icon = "<?= base_url('assets/images/gps/marker-idle.png') ?>";
                                                        fill_color = "#0000ff";
                                                        speed_message = "Idle";
                                                        // message.push($speed_message);
                                                    }
                                                    if (parseInt(<?php echo $value->ignition; ?>) == 0 && parseInt(<?php echo $value->speed; ?>) == 0) {
                                                        icon = "<?= base_url('assets/images/gps/marker-parked.png') ?>";
                                                        fill_color = "#ffff00";
                                                        speed_message = "Parked";
                                                        //   message.push($speed_message);
                                                    }
                                                    if ((parseInt(<?php echo $value->speed; ?>) > parseInt(<?php echo $value->max_speed_limit; ?>)) || (parseInt(<?php echo $value->speed_alert; ?>) > 0) || (parseInt(<?php echo $value->arm_alert; ?>) > 0) || (parseInt(<?php echo $value->power_cut; ?>) > 0)) {
                                                        icon = "<?= base_url('assets/images/gps/marker-danger.png') ?>";
                                                        fill_color = "#ff0000";
                                                        speed_message = "Alert";
                                                        //  message.push($speed_message);
                                                    }
                                                    if (parseInt(<?php echo $value->ignition; ?>) == 1) {
                                                        ignition = '<button class="btn btn-success btn-xs">On</button>';
                                                    }
                                                    else {
                                                        ignition = '<button class="btn btn-default btn-xs">Off</button>';
                                                    }
                                                    //$direction = $value->orientation;
                                                    vehicle_name = "<?php echo $value->plate_no; ?>";
                                                    marker_type = 'vehicle';
                                                    title = "<?php echo $value->model; ?>";
                                                    plate_no = "<?php echo $value->plate_no; ?>" + " - " + "<?php echo $value->model; ?>";
                                                    var marker = new google.maps.Marker({
                                                        position: {
                                                            lat: <?php echo $value->latitude; ?>
                                                            , lng: <?php echo $value->longitude; ?>
                                                        }
                                                        , map: map
                                                        , icon: icon
                                                        , content: plate_no
                                                        , vehicle_id: vehicle_id
                                                        , vehicle_name: vehicle_name
                                                    });
                                                }

                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="height:100%; width: 100%; padding: 0 !important">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <!-- /.row -->
    </div>
    <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?= $this->config->item('map_key') ?>&libraries=places,drawing&callback=initMap">
    </script>
