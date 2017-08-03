<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?php if (sizeof($devices)) {?>
                <a style="float:right; margin-right: 16px" href="<?php echo " ".site_url('admin/devices/add_device')." "; ?>" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add Device </a>
                <?php } ?>
                    <br>
                    <br>
                    <div class="col-md-12 col-lg-12">
                        <?php if (sizeof($devices)) {?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="d-example">
                                    <thead>
                                        <tr>
                                            <th>Serial No.</th>
                                            <th>Phone No.</th>
                                            <th>Plate No.</th>
                                            <th>Device Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($devices as $key => $value) { ?>
                                            <tr class="gradeU">
                                                <td>
                                                    <?php echo $value->serial_no; ?>
                                                </td>
                                                <td>
                                                    <?php if(!empty($value->phone_no)){
                                    echo $value->phone_no; 
                                } else{
                                    echo "None";
                                }?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($value->account_id)) {

                                        $plate_no=$this->mdl_devices->get_plate($value->device_id);

                                        echo strtoupper($plate_no->plate_no);
                                    } else { ?> <b>Not Assigned</b>
                                                        <?php }?>
                                                </td>
                                                <td>
                                                    <?php if($value->status=="Assigned"){

                                    $vehicle_id=$this->mdl_devices->get_plate($value->device_id);

                                    echo "<a style='color:white;' data-original-title='Unassign' onclick='unassign($value->device_id,$vehicle_id->vehicle_id)'  href='' class='btn btn-primary btn-xs' style='color:#000'>Unassign</span></a>";
                                } else{
                                     echo "<b>$value->status</b>";
                                }?></td>
                                                <td>
                                                    <?php echo "<a data-original-title='View Assignment'  href='".base_url('index.php/admin/devices/fetch_device/'.$value->device_id)
                                                ."'class='btn btn-primary btn-xs'>View Details</span></a>"?>&nbsp;&nbsp;&nbsp;
                                                        <?php $chek = $this->mdl_devices->get_linked($value->device_id);
                                                if(!empty($chek)){?> <a class="btn btn-primary btn-xs" data-original-title="Add Work Time" href="#worktime<?php echo $value->device_id; ?>" data-toggle="modal">Add Work Time</a>
                                                            <?php }else{?> <a class="btn btn-primary btn-xs" data-original-title="Not Assigned" href="#">Not Assigned</a>
                                                                <?php }?>
                                                </td>
                                            </tr>
                                            <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else {?>
                                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                                    <h2><i class="fa fa-car"></i> devices</h2>
                                    <p>Add and manage your devices or motorcycles and begin monitoring their location.</p>
                                    <br> <a href="<?php echo site_url('devices/add_device');?>" class="btn btn-success">Add devices</a> </div>
                                <?php } ?>
                    </div>
        </div>
    </div>
</div>
<?php if(isset($devices)):?>
    <?php $i=1;
foreach ($devices as $new){
$wt = $this->mdl_devices->get_linked($new->device_id);
?>
        <!-- Modal -->
        <div class="modal fade" id="worktime<?php echo $new->device_id?>" tabindex="-1" role="dialog" aria-labelledby="worktimelabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ff">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="worktimelabel">Work Time Allocation</h4> </div>
                    <div class="modal-body">
                        <div id="err_worktime<?php echo $new->device_id?>">
                            <?php 
            if(validation_errors()){
        ?>
                                <div class="alert alert-danger alert-dismissable"> <i class="fa fa-ban"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-close"></i></button> <b><?php echo "alert"?>!</b>
                                    <?php echo validation_errors(); ?>
                                </div>
                                <?php  } ?>
                        </div>
                        <form method="post" action="<?php echo site_url('admin/devices/worktime/'.$new->device_id)?>" id="worktime" enctype="multipart/form-data">
                            <input type="hidden" name="device_id" value="<?php echo $new->device_id; ?>" />
                            <input type="hidden" name="vehicle_id" value="<?php echo $wt['vehicle_id']?>" class="form-control">
                            <input type="hidden" name="add_uid" value="<?php echo $add_uid?>" class="form-control">
                            <input type="hidden" name="account_id" value="<?php echo $wt['account_id']?>" class="form-control">
                            <input type="hidden" name="terminal_id" value="<?php echo $wt['terminal_id']?>" class="form-control">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="wt" style="clear:both;">Work Time</label>
                                        <input type="text" name="command" class="form-control" placeholder="Input Number Of Hours" required> </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary add_worktime" name="add_worktime">
                                    <?php echo "Add Work Time"?>
                                </button>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <?php echo "Close"?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <?php $i++;}?>
            <?php endif;?>
                <script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
                <script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
                <script>
                    // Initialize Loadie for Page Load
                    $(document).ready(function () {
                        $('#d-example').dataTable();
                    });

                    function unassign(devid, vehid) {
                        swal({
                            title: "Info",
                            text: "Unassign Device?",
                            type: "info",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            allowOutsideClick: false,
                            showLoaderOnConfirm: true
                        }, function () {
                            $.ajax({
                                method: 'post',
                                url: '<?= site_url('admin/devices/update_assign') ?>/' + devid + '/' + vehid,
                                data: {},
                                success: function (response) {
                                    if (response == 1) {
                                        swal({
                                            title: "Info",
                                            text: "Saved successfully",
                                            type: "success",
                                            confirmButtonText: "ok"
                                        }, function () {
                                            document.location.href = "<?php echo base_url('index.php/admin/devices') ?>";
                                        });
                                    }
                                    else if (response == 0) {
                                        swal({
                                            title: "Error",
                                            text: "Failed, Try again later",
                                            type: "error",
                                            confirmButtonText: "ok"
                                        });
                                    }
                                    else if (response == 77) {
                                        swal({
                                            title: "Info",
                                            text: "A device with that plate number already exists",
                                            type: "error",
                                            confirmButtonText: "ok"
                                        });
                                    }
                                }
                            });
                        });
                    }
                </script>