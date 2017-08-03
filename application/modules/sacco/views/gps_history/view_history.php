<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-md-12 col-lg-12">
                <?php if (sizeof($history)) {?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Plate No.</th>
                                    <th>Start Time</th>
                                    <th>Start Address</th>
                                    <th>Stop Time</th>
                                    <th>Stop Address</th>
                                    <th>Distance Covered(KM)</th>
                                  <!--  <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                          foreach ($history as $key => $value) { ?>
                                    <tr class="gradeU">
                                        <td>
                                            <?php echo $value->plate_no; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->start_time; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->start_address; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->stop_time; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->stop_address; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->distance / 1000; ?>
                                        </td>
                                   <!--     <td>
                                            <?php // echo "<a data-original-title='View Map' href='".base_url("/index.php/gps_history/history/".$value->vehicle_id."/".$value->start_time."/".$value->stop_time)."' class='btn btn-primary btn-xs'>View Map</a>"?></td> -->
                                    </tr> 
                                    <?php

                          }?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else {?>
                        <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                            <h2><i class="fa fa-history"></i>History</h2>
                            <br>
                            <p>No Tracking History of Vehicle to Display</p>
                        </div>
                        <?php }?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
<script>
    // Initialize Loadie for Page Load
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>
