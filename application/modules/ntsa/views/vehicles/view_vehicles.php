<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">

                    <br>
                    <br>
                    <div class="col-md-12 col-lg-12">
                        <?php if (sizeof($vehicles)) {?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dataTables-example">
                                    <thead>
                                        <tr data-toggle='tooltip' data-original-title='The table shows the list of your vehicles. You can view more details, view history and replay history movement of your vehicle.'>
                                            <th>Number Plate</th>
                                            <th>Last Seen Address</th>
                                            <th>Last Seen Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vehicles as $key => $value) { ?>
                                            <tr class="gradeU">
                                                <!-- <td><?php echo $value->model; ?></td>-->
                                                <td>
                                                    <?php echo strtoupper($value->plate_no); ?>
                                                </td>
                                                <td>
                                                <?php if ($value->address != "") {
                                                          echo $value->address;
                                                      } else { echo "Not Available"; } ?>
                                                </td>
                                                  <td>
                                                    <?php echo $value->last_seen; ?>
                                                </td>
                                                <td>
                                                   <?php echo "<a data-toggle='tooltip' data-original-title='Track the status of your vehicle, view its location, commands history and more detailed information' href='".base_url('index.php/ntsa/fetch_vehicle/'.$value->vehicle_id)
                                          ."'class='btn btn-primary btn-xs'>View Details</span></a> &nbsp;
                                        <a data-toggle='tooltip' data-original-title='Track the movement history of your vehicle' href='".base_url('index.php/ntsa/view_history/'.$value->vehicle_id)
                                        ."'class='btn btn-primary btn-xs' style='color:#fff;'> History</span></a> &nbsp;";?></td>

                                            </tr>
                                            <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else{ ?>
                                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                    <h2><i class="fa fa-car"></i>No Vehicles</h2>
                                    <p>View and monitor sacco vehicles.</p></div>
                                <?php } ?>
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
    //$('a').tooltip();
</script>
