<style>

    .buttons-excel {
        background: #172230 !important;
        border: 0px !important;
        color: #fff !important;
    }

    .buttons-pdf {
        background: #172230 !important;
        border: 0px !important;
        color: #fff !important;
    }

    .dataTables_filter, .dataTables_info {
        display: none;
    }

    #controls {
        position: relative;
    }

</style>
<div class="container-fluid">
    <div class="row">

        <a data-toggle='tooltip' data-original-title="Click to view pplayback"
           style="float:right; margin-right: 16px; margin-bottom: 16px"
           href="<?php echo " " . site_url('gps_history/view_playback/' . $vehicle_id) . " "; ?>"
           class="btn btn-primary btn-sm"> View Playback </a>

        <a data-toggle='tooltip' data-original-title="Click to view vehicle details"
           style="float:right; margin-right: 16px; margin-bottom: 16px"
           href="<?php echo " " . site_url('vehicles/fetch_vehicle/' . $vehicle_id) . " "; ?>"
           class="btn btn-primary btn-sm"> Vehicle Details </a>

        <div class="col-md-12 col-lg-12">

            <div class="table-responsive">

                <div class="row" id="controls">

                    <div class="col-xs-3">
                        <h6><b>Filter By Date</b></h6>
                        <div style="margin-bottom:20px;">
                            <form class='form-horizontal'>
                                <fieldset>
                                    <div class='control-group'>
                                        <div class='controls'>
                                            <div class='input-prepend input-group'>
                                                        <span class='add-on input-group-addon'>
                                                            <i class='glyphicon glyphicon-calendar fa fa-calendar'></i>
                                                        </span>
                                                <input type="text" style="width: 200px" id="reportrangeH"
                                                       class="form-control"/>
                                                <input type="hidden" id="vehicle-id" value="<?= $vehicle_id ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div style="margin-top:40px; margin-left: -100px">
                        <button class="btn btn-xs btn-primary" id="generateH">Generate History Report</button>
                    </div>
                </div>

                <table class="table table-striped table-hover" id="history-tbl">
                    <thead>
                    <tr>
                        <th>Plate No.</th>
                        <th>Start Time</th>
                        <th>Start Address</th>
                        <th>Stop Time</th>
                        <th>Stop Address</th>
                        <th>Distance Covered (KM)</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($history as $key => $value) {
                        if(!empty($value->stop_time)){?>
                        <tr>
                        <td>
                            <?php echo strtoupper($value->plate_no); ?>
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
                            <?php echo $value->distance/1000; ?>
                        </td>
                        <td>
                            <?php echo "<a data-toggle='tooltip' data-original-title='Track the status of your vehicle, view its location, commands history and more detailed information' href='".base_url('index.php/gps_history/history/'.$value->vehicle_id.'/'.$value->start_time.'/'.$value->stop_time)
                                          ."'class='btn btn-primary btn-xs'>View On Map</span></a>"; ?>
                        </td>
                        </tr>
                   <?php }}?>
                    
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo base_url('assets/dataTables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/dataTables.bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/dataTables.buttons.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/buttons.bootstrap.min.js') ?>"></script>

<script src="<?php echo base_url('assets/dataTables/buttons.flash.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/buttons.html5.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/buttons.print.min.js') ?>"></script>

<script src="<?php echo base_url('assets/dataTables/jszip.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/jszip.min.js') ?>"></script>

<script src="<?php echo base_url('assets/dataTables/pdfmake.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/vfs_fonts.js') ?>"></script>

<script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js') ?>"></script>

<script src="<?php echo base_url('assets/js/reports/script_history.js') ?>"></script>
<script type="text/javascript">
    
    $(document).ready(function () {
        $('#history-tbl').dataTable();
    });
</script>