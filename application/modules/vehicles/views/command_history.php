<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <br>
            <br>
            <div class="col-md-12 col-lg-12">

            <?php if (sizeof($command_history)) {?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Model</th>
                                <th>Command</th>
                                <th>Sent By</th>
                                <th>Sent At</th>
                                <th>Response</th>
                                <th>Trials</th>
                            </tr>

                        </thead>
                        <tbody>
                        <?php foreach ($command_history as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $value->plate_no; ?></td>
                                <td><?php echo $value->model; ?></td>
                                <td><?php echo $value->command; ?></td>
                                <td><?php echo $value->add_uid; ?></td>
                                <td><?php echo $value->add_date; ?></td>
                                <td><?php echo $value->response; ?></td>
                                <td><?php echo $value->count; ?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-terminal"></i> Command History</h2>
                    <p>No commands have been sent to this vehicle.</p>
                </div>
            <?php } ?>

            </div>
        </div>
    </div>
</div>    

<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>

<script>
// Initialize Loadie for Page Load
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });

    //$('a').tooltip();
</script>

