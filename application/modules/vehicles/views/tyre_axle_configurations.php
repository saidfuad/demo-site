<link href="<?php echo base_url('assets/js/plugins/ion.rangeSlider-1.9.1/css/ion.rangeSlider.css')?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/js/plugins/ion.rangeSlider-1.9.1/css/ion.rangeSlider.skinFlat.css')?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/js/plugins/ion.rangeSlider-1.9.1/css/normalize.min.css')?>" rel="stylesheet" />

<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<style type="text/css">
	#bg-vehicle-area img{
		margin-top: 30px;
		width:65px;
	}

	#bg-vehicle-area {
		
	}
</style>
<div class="container-fluid">
    <div class="row">
        <form>
	        <div class="col-md-5">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                   TPMS Devices Integration
	                </div>
	                <div class="panel-body">
	               
	                    <!-- <div class="form-group">
	                        <label for="reservation">Select Vehicle:</label>
	                        <select class="form-control" type="text" name="asset_id" id="asset_id" required="required">
	                        	<option value="0">-- Select Vehicle --</option>
	                        	<?php foreach ($vehicles as $key => $vehicle) { ?>
	                        		<option value="<?php echo $vehicle->asset_id; ?>"><?php echo $vehicle->assets_name .'-'.$vehicle->assets_friendly_nm ; ?></option>
	                        	<?php }?>
	                        </select>
	                    </div> -->
	                    <div class="form-group">
                                <input id="asset_id" type="hidden" class="form-control clear-input"/>
                                <label class="control-lable">Vehicle</label>
                                <input id="asset" name="asset" class="form-control clear-input" />
                                <a style="float: right; margin-top: -28px; margin-right: 5px" href="<?php echo site_url('vehicles/add_vehicle');?>" class='btn btn-success btn-xs'>New Vehicle
                            		<span class='fa fa-plus'></span>
                            	</a>
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
                                        title='".$row['assets_name'] . "' >" . $row['assets_friendly_nm'] ." - " . $row['assets_name'] . "</li>";
                                        }
                                    }
                                    ?>
                                    </ul>
                                </div>
                                <script>

                                $('#asset').on('focus', function(){
									$('#asset-list').show();
									$('.assets_holder').show();
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
	                        <label for="reservation">No of Axles:</label>
	                        <input class="form-control" type="number" min="0" max="7" name="axle_no" id="axle_no" required="required"/>
	                        
	                    </div>
	                    <script>
	                    	$('#axle_no').on('focus', function(){
								$('.assets_holder').hide();
							});
	                    </script>
	                    <div class="form-group" id="axles-configs">
	                        <label for="reservation">Axle Tyre and min/max pressure configurations: (from the front axle)</label>
		                    
						</div>	
                    </div>
	                    						                    
                </div>
	                <div class="panel-footer" align="right">
	                	<button class="btn btn-default" type="" id="btn-preview">Preview</button>
	                	<button class="btn btn-primary" type="submit" id="btn-save-config">Save Configurations</button>
	                </div>
	            </div>
			</form>

			<div class="col-md-7 col-lg-7">
				<div class="bg-crumb panel panel-default">
					<div class="panel-heading">
						Preview
					</div>
					<div class="row" id="bg-vehicle-area" style="min-height:200px">
			           
                    </div>
				</div>	
			</div>
	           
			</div>
		
		

    </div>
</div> 


<script src="<?php echo base_url('assets/js/plugins/ion.rangeSlider-1.9.1/js/ion-rangeSlider/ion.rangeSlider.js')?>"></script>

<!--<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>-->
<script type="text/javascript">
	$(function () {
		/*$('#asset_id').on('change', function () {
			var asset_id = $(this).val();
			//alert();
			if (asset_id!=0) {
				$.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/vehicles/get_vehicle_by_id') ?>',
                    data: {asset_id:asset_id},
                    success: function (response) {
                        vehicle = JSON.parse(response);

                        //alert(vehicle.asset_id);
                     }
                });
			}



			return false;

		});

		*/

		$('#axle_no').on('keyup', function () {
			var axle_no = $(this).val();
			
			if (axle_no !="" && axle_no > 0) {
				set_axles(axle_no);
			}
 			
		});

		$('#axle_no').on('change', function () {
			

			var asset_id = $("#asset_id").val();
			var axle_no = parseInt($("#axle_no").val().trim());
 			
 			if (asset_id == 0) {
 				swal({   title: "Info",   text: "Select vehicle",   type: "error",   confirmButtonText: "ok" });
                return false;
                $("#axle_no").val(0)
			}

			var axle_no = $(this).val();
 			
 			if (axle_no !="" && axle_no > 0) {
				set_axles(axle_no);
			}
			
			var config = $.map($("#axles-configs").find(".axle_config"), function(element) {
							      return $(element).val()
							  })
							  .join("-");

			preview_axle_config(axle_no, config);

			return false;
		});

		function set_axles (axle_no) {
			$selects = '';
			$('#axles-configs').html('');
			
			$count = 1;
			for(i=0; i<axle_no; i++) { 
				
				$('#axles-configs').append('<div class="col-md-12 col-lg-12" style = "margin-bottom:20px;border-bottom:1px solid #eee;padding-bottom: 10px;">'	
												+'<div class="col-md-3 col-lg-3">'
													+'<h4>Axle '+ $count +'</h4>'
												+'</div>'
												+'<div class="col-md-4 col-lg-4">'
													+'<select class="form-control axle_config" type="text" name="axle'+$count+'config" id="axle1config" required="required">'
														+'<option value="2">Single tyre Axle</option>'
														/*+'<option value="SR">Single tyre retractable Axle</option>'*/
														+'<option value="4">Twin Tyre Axle</option>'
														/*+'<option value="TR">Twin Tyre retractable Axle</option>'*/
							                		+'</select>'
												+'</div>'
												+'<div class="col-md-5 col-lg-5">'
												/*+'<div class="">'
										        	+'<div class="panel panel-default" style="">'
											          +'<div class="panel-heading" style="font-size:9px">'
											            +'Max & Min Pressure'
								 			          +'</div>'
											          +'<div class="panel-body">'

											              +'<!-- Slider -->'
											              +'<input id="example_'+$count+'" class="example" type="text" name="example_'+$count+'" value="0;100" style="display: none;">'

											              +'<!-- Slider -->'*/
											              +'<input id="example_'+$count+'" class="example" type="text" name="" value="0;200" style="display: none;">'

											              <!-- // Slider END -->
											          /*+'</div>'
											        +'</div>'
											    +'</div>'*/
												+'</div>'
											+'</div>');
				// var x = example_+$count+;

				$(".example").ionRangeSlider({
				    min: 0,
				    max: 200,
				    step:10,
				    type: 'double',
				    prefix: "",
				    maxPostfix: "",
				    prettify: false,
				    hasGrid: true
				});

				$count++;				
			}
		}
		

		$('#btn-save-config').on('click', function () {
			//alert();
			var asset_id = $("#asset_id").val();
			var axle_no = parseInt($("#axle_no").val().trim());
 			
 			if (asset_id == 0) {
 				swal({   title: "Info",   text: "Select vehicle",   type: "error",   confirmButtonText: "ok" });
                return false;
			}

			if (axle_no == 0 || axle_no == "") {
 				swal({   title: "Info",   text: "Set tyre axle configuration",   type: "error",   confirmButtonText: "ok" });
                return false;
			}

			var config = $.map($("#axles-configs").find(".axle_config"), function(element) {
							      return $(element).val()
							  })
							  .join("-");

			var confs = config.split('-');
			var tyre_no = 0;

			for (var i = 0; i<confs.length; i++) {
				if (confs[i] == '2' || confs[i] == '2') {
					tyre_no += 2;
				} else if (confs[i]=='4' || confs[i]=='4') {
					tyre_no += 4;
				}
			}


			var minmax = $.map($("#axles-configs").find(".example"), function(element) {
							      return $(element).val()
							  })
							  .join(",");

			//alert(minmax);
			//return false;

			swal({
                  title: 'Are you sure?',
                  text: "All previous settings will be overwritten!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Continue!',
                  closeOnConfirm: false
                },
                function() {

				    $.ajax({
	                    type    : "POST",
	                    cache   : false,
	                    data : {asset_id:asset_id, no_of_axles:axle_no, no_of_tyres:tyre_no, axle_tyre_config:config, minmax:minmax},
	                    url     : "<?php echo base_url()?>index.php/vehicles/save_tyre_axle_config",
	                    success: function(response) {
	                    	if (response == 1) {
	                    		$('#axles-configs').html('');
	                    		$("#asset_id").val('');
	                    		$("#asset").val('');
	                    		$("#axle_no").val('');
	                    		swal({   title: "Info",   text: "Successfully configured",   type: "success",   confirmButtonText: "ok" });
	                    	} else {
	                    		swal({   title: "Info",   text: "Failed",   type: "error",   confirmButtonText: "ok" });
	                    	}
	                    }
	                });

            });
			return false;
		});

		$('#btn-preview').on('click', function () {
			var asset_id = $("#asset_id").val();
			var axle_no = parseInt($("#axle_no").val().trim());
 			
 			if (asset_id == 0) {
 				swal({   title: "Info",   text: "Select vehicle",   type: "error",   confirmButtonText: "ok" });
                return false;
			}

			if (axle_no == 0 || axle_no == "") {
 				swal({   title: "Info",   text: "Set tyre axle configuration",   type: "error",   confirmButtonText: "ok" });
                return false;
			}
			
			var config = $.map($("#axles-configs").find(".axle_config"), function(element) {
							      return $(element).val()
							  })
							  .join("-");

			preview_axle_config(axle_no, config);

			return false;
		});

		function preview_axle_config(axles, axleConfig) {
			//alert(axleConfig);
			var total_axles = axles;
            

            /*put the axles and tyres numbers and pattern in arrays. Single tyre S, twin tyre T, 
            single retractable SR and twin retractable TR*/
            var patternA = axleConfig.split('-').filter(Boolean);
            
            /*Verify if axles and tyres configurations have been set so as to render the diagnostics image,
            with the correct number of tyres and axles*/
            if (total_axles == 0) {
                swal({   title: "Info",   text: "Set the vehicle axles and tyres configurations",   type: "error",   confirmButtonText: "ok" });
                return false;
            }

            var completePattern = patternA;

            //alert(completePattern.length);
            //Display the panel if above conditions have been met.
            $('#bg-vehicle-area').html('');
            //$('#panel-diagnostics').fadeOut(500);
            //$('#panel-diagnostics').fadeIn(1000);
            $('#bg-vehicle-area').html('');

            var vehicle_axles = total_axles;

            //Divs to render axles and tyres 2
            var axle_divs_two = ['<div class="col-sm-7 col-md-7 axle-div"></div>', '<div class="col-sm-5 col-md-5 axle-div"></div>'];
            var axle_divs_three = ['<div class="col-sm-3 col-md-3 axle-div"></div>', '<div class="col-sm-4 col-md-4 axle-div"></div>', '<div class="col-sm-5 col-md-5 axle-div"></div>'];
            
            //axle and tyres images.
            var styre_axle = "<img src='<?php echo base_url()?>/assets/images/itms/normal-axle.png' alt='axle' class='v-axle'/>";
            var dtyre_axle = "<img src='<?php echo base_url()?>/assets/images/itms/double-tyre-axle.png' alt='axle' class='v-axle'/>";
            var no_of_axle_divs = 2;
            var divs_to_append = axle_divs_two;
            
            //Check the number axles to determine which vehicle image & how many divs to append to hold the axles
            if (vehicle_axles > 5) {
                no_of_axle_divs = 3;
                divs_to_append = axle_divs_three;
                $('#bg-vehicle-area').css({'background': 'url("<?php echo base_url()?>/assets/images/itms/trailer.png") no-repeat'});
            } if (vehicle_axles > 3) {
                no_of_axle_divs = 3;
                divs_to_append = axle_divs_three;
                $('#bg-vehicle-area').css({'background': 'url("<?php echo base_url()?>/assets/images/itms/lorry6.png") no-repeat'});
            } else {
                $('#bg-vehicle-area').css({'background': 'url("<?php echo base_url()?>/assets/images/itms/lorry2.png") no-repeat'});
            }
            
            //appending divs first on top of the vehicle image
            for (var i = 0; i < no_of_axle_divs; i++) {
                $('#bg-vehicle-area').append(divs_to_append[i]) 
            }
            
            //append axles on the divs depending on the set and pattern of the tyres on each axle.
            //NOTE:The completePattern Variable 
            count = 1;
            lap = 1;

            if (vehicle_axles == 2) {
                $container = [1,2];
            } else if (vehicle_axles == 3) {
                $container = [1,2,2];
            } else if (vehicle_axles == 4) {
                $container = [1,2,3,3];
            } else if (vehicle_axles == 5) {
                $container = [1,2,2,3,3];
            } else if (vehicle_axles == 6) {
                $container = [1,2,2,3,3,3];
            } else if (vehicle_axles == 7) {
                $container = [1,2,2,2,3,3,3];
            } 
            
            for (var i = 0; i < vehicle_axles; i++) {

                if (completePattern[i] == 'S' || completePattern[i] == 'SR') {
                    $axle_image = styre_axle;
                } else if (completePattern[i] == 'T' || completePattern[i] == 'TR') {
                    $axle_image = dtyre_axle;
                }

                var count = $container[i];
                                
                $('#bg-vehicle-area').find('.axle-div:nth-child('+count+')').append($axle_image);   
            }
            
            //give the axles ids 
            var appended_axles = $('#bg-vehicle-area').find('.v-axle');
            var size_appended_axles = appended_axles.length;
            
            for (var i = 0; i < size_appended_axles; i++) {
                var numb = i+1;
                $(appended_axles[i]).attr("id","X"+numb);
            }
			return false;
		}

	});
</script>
