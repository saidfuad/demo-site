<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <?php if (sizeof($devices)) {?>
            <a style="float:right; margin-right: 16px" href="<?php echo "".site_url('devices/add_device').""; ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>
                Add Device
            </a>
            <?php } ?>
            <br>
            <br>
            <div class="col-md-12 col-lg-12">

            <?php if (sizeof($devices)) {?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
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
                                <td><?php echo $value->serial_no; ?></td>
                                <td><?php if(!empty($value->phone_no)){
                                    echo $value->phone_no; 
                                } else{
                                    echo "None";
                                }?></td>
                                <td>
                                    <?php if (!empty($value->account_id)) {

                                        $plate_no=$this->mdl_devices->get_plate($value->device_id);

                                        echo strtoupper($plate_no->plate_no);
                                    } else { ?>
                                    <b>Not Assigned</b>
                                    <?php }?></td>
                                <td><?php if($value->status=="Assigned"){

                                    $vehicle_id=$this->mdl_devices->get_plate($value->device_id);

                                    echo "<a style='color:white;' data-original-title='Unassign' onclick='unassign($value->device_id,$vehicle_id->vehicle_id)'  href='' class='btn btn-primary btn-xs' style='color:#000'>Unassign</span></a>";
                                } else{
                                     echo "<b>$value->status</b>";
                                }?></td>
                                <td><?php echo "<a data-original-title='View Assignment'  href='".base_url('index.php/devices/fetch_device/'.$value->device_id)
                                                ."'class='btn btn-primary btn-xs'>View Details</span></a>"?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-car"></i> devices</h2>
                    <p>Add and manage your devices or motorcycles and begin monitoring their location.</p>
                    <br>
                    <a href="<?php echo site_url('devices/add_device');?>" class="btn btn-success">Add devices</a> 
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

    function unassign(devid,vehid){
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
                    url: '<?= base_url('index.php/devices/update_assign') ?>/'+devid+'/'+vehid,
                    data: {},
                    success: function (response) {
                        if (response == 1) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/devices') ?>";
                                }
                            );
                            
                        } else if (response == 0) {
                            swal({title: "Error", text: "Failed, Try again later", type: "error", confirmButtonText: "ok"});
                        } else if (response == 77) {
                            swal({title: "Info", text: "A device with that plate number already exists", type: "error", confirmButtonText: "ok"});
                        }

                    }
                });
            });
    }

    //$('a').tooltip();
</script>

