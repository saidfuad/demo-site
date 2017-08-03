<script type="text/javascript">
    var landmarkCircles = [];
    var newLatLng = '';
    var fillcolor = $("#full-popover").val();
    var range = parseFloat($("#landmark-radius").val());
    var countM = 0;

    function addMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });

        markers_map.push(marker);
    }

    function addCircle(location) {
        var landmarkCircle = new google.maps.Circle({
            strokeColor: "0.8",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: fillcolor,
            fillOpacity: 0.4,
            map: map,
            center: location,
            radius: range * 1000
        });

        landmarkCircles.push(landmarkCircle);
    }

    function clearMarkers() {
        var countMarkers = markers_map.length;
        if (countMarkers > 0) {
            for (var i = 0; i < countMarkers; i++) {
                markers_map[i].setMap(null);
            }
        }
    }

    function clearCircles() {
        var countCircles = landmarkCircles.length;
        if (countCircles > 0) {
            for (var i = 0; i < countCircles; i++) {
                landmarkCircles[i].setMap(null);
            }
        }
    }
</script>

<?php echo $map['js']; ?>
<div class="container-fluid fleet-view">
    <div class="row">
        <!--<div class="col-md-12">
            <br>
            <div class="col-md-12 bg-crumb margin-top-10">
                <div class="form-inline" role="form">
                  <div class="form-group">
                     
                    <select class="form-control" name="select-owners" id="select-owners">
                        <option value="0">All Owners <i class="fa fa-home"></i></option>
        <?php echo $ownerOpt; ?>
                    </select>
                  </div>
                  <div class="form-group">
                     
                    <select class="form-control" name="select-types" id="select-types">
                        <option value="0">All Types</option>
        <?php echo $typeOpt; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    
                    <select class="form-control" name="select-categories" id="select-categories">
                        <option value="0">All Categories</option>
        <?php echo $categoryOpt; ?>
                    </select>
                  </div>
                  <div class="form-group">
                     
                    <select class="form-control" name="select-areas" id="select-areas">
                        <option value="0">All Areas</option>
                        
                    </select>
                  </div>
                  <div class="form-group">
                     
                    <select class="form-control" name="select-routes" id="select-routes">
                        <option value="0">All Routes</option>
                        
                    </select>
                  </div>
                  <div class="form-group">
                    
                    <select class="form-control" name="select-landmarks" id="select-landmarks">
                        <option value="0">All Landmarks</option>
        <?php echo $fetch_landmarkOpt; ?>
                    </select>
                  </div>
                  <div class="form-group pull-right">
                    <label>Search: </label>
                    <input class="form-control" name="vehicle-search" id="vehicle-search" placeholder="vehicle name/no. plate"/>
                  </div>
                  
                </div>
            </div>    
           
        </div> -->    
        <div class="col-md-3" style="height:100%">
            <div class="col-md-12 bg-crumb" >
                <h4>Edit Landmark( <sup>*</sup> Required)</h4>
                <form id="form-create-landmark">
                    <div class="form-group">
                        <label>Landmark Name <sup>*</sup></label>
                        <input name="landmark_id" type="hidden" value="<?php echo $fetch_landmark['landmark_id']; ?>" />
                        <input class="form-control input-sm clear-input" name="landmark_name" id="landmark_name" type="text" placeholder="Enter landmark name" value="<?php echo $fetch_landmark['landmark_name']; ?>" required="required">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input class="form-control input-sm clear-input" id="address" name="address" type="text" placeholder="Enter address" value="<?php echo $fetch_landmark['address']; ?>" >
                    </div>
                    <div class="form-group">
                        <label>Landmark Icon <sup>*</sup></label>
                        <div class="div-landmarks-images">    
                            <ul>
                                <?php echo $landmark_images; ?>
                            </ul>
                        </div>    
                        <input class="form-control input-sm" name="icon_path" id="icon_path" type="hidden" value="<?php echo $fetch_landmark['icon_path']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Landmark circle background <sup>*</sup></label>
                        <input type="text" class="form-control" id="full-popover" name="landmark_circle_color" value="#337ab7" data-color-format="hex" required="required" value="<?php echo $fetch_landmark['landmark_circle_color']; ?>">

                    </div>
                    <div class="form-group">
                        <label>Radius (In KM) <sup>*</sup></label>
                        <input class="form-control input-sm" min="0" step="0.1" name="radius" id="radius" value="0.1" type="number" placeholder="Enter the landmark radius" required="required" value="<?php echo $fetch_landmark['radius']; ?>">
                    </div>
                    <!-- <div class="form-group">
                         <label>Alert before Landmark(KM) <sup>*</sup></label>
                         <input class="form-control input-sm" min="1" value="1" id="alert_before_landmark" name="alert_before_landmark" type="number" placeholder="Specify the distance for alerts" required="required">
                     </div>-->
                    <div class="form-group">
                        <div class="col-sm-3">
                            <input name="in_alert" id="in" type="hidden" value="0" />
                            <input class="alert-checks" id="in_alert" type="checkbox" <?php if($fetch_landmark['in_alert'] == 1) echo "checked" ?> /><br><label>In Alert</label>
                        </div>
                        <div class="col-sm-3">
                            <input name="out_alert" id="out" type="hidden" value="0" />
                            <input class="alert-checks" id="out_alert" type="checkbox" <?php if($fetch_landmark['out_alert'] == 1) echo "checked"; ?>><br><label>Out Alert</label>
                        </div>
                        <div class="col-sm-3">
                            <input name="sms_alert" id="sms" type="hidden" value="0" />
                            <input class="alert-checks" id="sms_alert" type="checkbox" <?php if($fetch_landmark['sms_alert'] == 1) echo "checked"; ?>><br><label>SMS Alert</label>
                        </div>
                        <div class="col-sm-3">
                            <input name="email_alert" id="email" type="hidden" value="0" />
                            <input class="alert-checks" id="email_alert" type="checkbox" <?php if($fetch_landmark['email_alert'] == 1) echo "checked"; ?>><br><label>Email Alert</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input class="form-control input-sm  clear-input" id="input-latitude" value="<?php echo $fetch_landmark['latitude']; ?>" name="latitude" type="hidden">
                        <input class="form-control input-sm  clear-input" id="input-longitude" value="<?php echo $fetch_landmark['longitude']; ?>" name="longitude" type="hidden">
                    </div>

                    <div class="form-group margin-top-10">
                        <div class="col-sm-6 margin-top-10">
                            <button class="btn btn-success btn-block" type="submit" id="btn-clear">Update</button>
                        </div>

                    </div>
                </form>    

            </div>    
        </div>  
        <div class="col-md-9">
            <?php echo $map['html']; ?>
        </div>       
        <!-- //. Content -->
    </div>
    <!-- /.row -->
</div>        

<script type="text/javascript">
    $(function () {
        $('.page-alert').html('<p align="center">Click on the map to set landmark position</p>');
        $('.page-alert').animate({right: '10px'}, 2000);
        setTimeout(function () {
            var showPageAlert = setInterval(function () {
                $('.page-alert').toggleClass('hide-me');
            }, 1500);
        }, 4000);

        $('#landmark-radius').on('keyup change', function () {

            var radius = parseFloat($(this).val().trim());
            if (countM > 0) {
                clearCircles();
                if (!$.isNumeric(radius) || radius == 0) {
                    clearCircles();
                } else {
                    range = radius;
                    addCircle(newLatLng);
                }
            }
        });

        $('#form-create-landmark').on('submit', function () {

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

            swal({
                title: "Confirm",
                text: "Update Landmark?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: "POST",
                    cache: false,
                    data: $this.serialize(),
                    url: "<?php echo base_url('index.php/settings/update_landmark') ?>",
                    success: function (response) {
                        if (response == 1) {
                            swal({title: "Info", text: 'Landmark Updated successfully', type: 'success'});

                        } else {
                            swal({title: "Info", text: 'Update Failed, Try Again.', type: 'error'});
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
    });
</script>
