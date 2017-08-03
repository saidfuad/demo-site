<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
           
            <div class="col-md-12 col-lg-12">

            <?php if (sizeof($mpesa)) {?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Order No.</th>
                                <th>Confirmation Code</th>
                                <th>Amount To Pay</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                        <?php foreach ($mpesa as $key => $value) { ?>
                           <tr class="gradeU">
                                <td><?php echo $value->order_id; ?></td>
                                <td><?php echo $value->confirmation_code; ?></td>
                                <td><?php echo $value->amount_to_pay; ?></td>
                                <td><?php if($value->status==0){

                                    echo "Not Approved";

                                } else{
                                     echo "Approved";
                                }?></td>
                                <td><?php if($value->status==0){ echo "<a data-original-title='Approve'  href='".base_url('index.php/admin/devices/approve/'.$value->entry_id)
                                                ."'class='btn btn-primary btn-xs'>Approve</span></a>";
                                            }else{
                                                echo "<a data-original-title='Disapprove'  href='".base_url('index.php/admin/devices/disapprove/'.$value->entry_id)
                                                ."'class='btn btn-primary btn-xs'>Disapprove</span></a>";
                                            }?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php } else {?>
                <div class="col-sm-8 col-md-8 col-md-offset-2 bg-crumb" align="center">
                    <h2><i class="fa fa-money"></i>Mpesa</h2>
                    <p>No Mpesa Transactions Done To Approve</p>
                    <br> 
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
                    url: '<?= base_url('index.php/admin/devices/update_assign') ?>/'+devid+'/'+vehid,
                    data: {},
                    success: function (response) {
                        if (response == 1) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/admin/devices') ?>";
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

