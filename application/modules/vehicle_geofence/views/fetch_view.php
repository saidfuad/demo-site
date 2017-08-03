<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="col-md-6">
   <div class="panel panel-blockquote panel-border-success left" id="fleet-car-details">
                        <div class="panel-heading" style="background-color:#1f232a;">
                            <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-car fa-fw"></i> &nbsp; <?php if($vga['type']=="landmark"){
                                echo "Landmark Assignment";
                            }else{
                                echo "Geofence Assignment";
                            } ?></h3>

                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover" style="margin-bottom:0">
                                <tbody>

                                    <tr>
                                        <td class="text-muted text-left col-md-3">Plate No.</td>
                                        <td>
                                            <?php echo $vga['plate_no'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Geofence/Landmark Name</td>
                                        <td>
                                            <?php echo $vga['name'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Type</td>
                                        <td>
                                            <?php echo $vga['type'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">General Status</td>
                                        <td>
                                            <?php echo ($vga['status'] == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Assigned By</td>
                                        <td>
                                            <?php echo $vga['assign_uid']; ?>
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted text-left">Assign Date</td>
                                        <td>
                                            <?php echo $vga['assign_date']; ?>
                                        </td>
                                        <td></td>
                                    </tr>

                                 

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

<div class="col-md-6">
  <div class="panel panel-blockquote panel-border-success left" id="fleet-car-details">
                        <div class="panel-heading" style="background-color:#1f232a;">
                            <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-car fa-fw"></i> &nbsp; <?php echo "Alerts Settings" ?></h3><a style="color:#1f232a" class="btn btn-success btn-xs pull-right" href="<?php echo base_url('index.php/vehicle_geofence/edit_view/'.$vga['id']); ?>">Edit Settings</a>

                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover" style="margin-bottom:0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted text-left col-md-3">In Alert</td>
                                        <td>
                                            <?php echo ($vga['in_alert'] == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Out Alert</td>
                                        <td>
                                            <?php echo ($vga['out_alert'] == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Sms Alert</td>
                                        <td>
                                            <?php echo ($vga['sms_alert'] == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Email Alert</td>
                                        <td>
                                            <?php echo ($vga['email_alert'] == 1) ? "Active" : "Inactive"; ?>
                                        </td>
                                        <td></td>
                                    </tr>

                                       <tr>
                                        <td class="text-muted text-left">Updated By</td>
                                        <td>
                                            <?php echo $vga['update_uid']; ?>
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted text-left">Update Date</td>
                                        <td>
                                            <?php echo $vga['update_date']; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr><td></td><td></td></tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
</div> 


<script src="<?php echo  base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>  


