<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="col-md-6">
   <div class="panel panel-blockquote panel-border-success left" id="fleet-car-details" data-toggle='tooltip' data-original-title='Details about <?php echo $user['first_name'].' '.$user['last_name'];?>'>
                        <div class="panel-heading" style="background-color:#1f232a;">
                            <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-car fa-fw"></i> &nbsp; <?php echo "User Details" ?></h3><a style="color:#1f232a;margin-left:15px;" class="btn btn-success btn-xs pull-right" href="<?php echo base_url('index.php/users'); ?>">Back</a><a style="color:#1f232a" class="btn btn-success btn-xs pull-right" href="<?php echo base_url('index.php/users/edit_user/'.$user['user_id']); ?>">Edit Details</a>

                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover" style="margin-bottom:0">
                                <tbody>
                                    <input class="form-control" type="hidden" name="user_id" id="user_id" value="<?php echo $user['user_id']; ?>" />

                                    <tr>
                                        <td class="text-muted text-left col-md-3">First Name</td>
                                        <td>
                                            <?php echo $user['first_name'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Last Name</td>
                                        <td>
                                            <?php echo $user['last_name'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Email</td>
                                        <td>
                                            <?php echo $user['email'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">Phone Number</td>
                                        <td>
                                            <?php echo $user['phone_no'] ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted text-left">User Type</td>
                                        <td>
                                            <?php if($user['user_type_id']==2){echo 'Admin';}else if($user['user_type_id']==3){echo 'Normal';}else if($user['user_type_id']==4){echo 'Installer';}?>
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr style="display:none;">
                                        <td class="text-muted text-left">Date Added</td>
                                        <td>
                                            <input class="form-control" type="text" name="account_id" id="account_id" value="<?php echo $accountid; ?>" readonly/>
                                            <?php echo $value->add_date; ?>
                                             <input class="form-control" type="text" name="add_uid" id="add_uid" value="<?php echo $uid; ?>" readonly/>
                                        </td>
                                        <td></td>
                                    </tr>

                                     <?php if($user['user_type_id']!=2){if(empty($assigned)){?>
                                      <tr>
                                        <td class="text-muted text-left">Assigned Vehicles</td>
                                        <td>
                                           <?php echo "None"; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php }}?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

<div class="col-md-6" data-toggle='tooltip' data-original-title='List of vehicles assigned to <?php echo $user['first_name'].' '.$user['last_name'];?>'>
    <?php if(!empty($assigned)){?>
 <div class="panel panel-blockquote panel-border-success right" id="fleet-car-details">
                        <div class="panel-heading" style="background-color:#1f232a;">
                            <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-car fa-fw"></i> &nbsp; <?php echo "Assigned Vehicles" ?></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover" style="margin-bottom:0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted text-left"><b>Model</b></td>
                                        <td>
                                           <b> Plate No.</b>
                                        </td>
                                        <td>Action</td>
                                    </tr>
                                    <?php $i=1; foreach ($assigned as $new) {?>
                                    <tr>
                                        <td class="text-muted text-left"><?php echo $i.". ".$new->model;?></td>
                                        <td>
                                            <?php echo $new->plate_no; ?>
                                        </td>
                                        <td><a class='btn btn-success btn-xs' style="color:black;" href="<?php echo base_url('index.php/users/unassign/'.$user['user_id'].'/'.$new->assign_id); ?>">Unassign</a></td>
                                    </tr>
                                    <?php ++$i; }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }else{if($user['user_type_id']!=2){?>
                        <div class="col-md-12 bg-crumb" align="center"><br><br>
                            <br>
                            <h2><i class="fa fa-car"></i> Users</h2>
                            <br>
                            <p>Manage Users and begin monitoring your assets through them</p>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url('users'); ?>" class="btn btn-primary">View Users</a>  
                            <br><br><br> <br>
                        </div>
                    <?php }}
                    if($user['user_type_id']==2){?>
                         <div class="panel panel-blockquote panel-border-success right" id="fleet-car-details">
                        <div class="panel-heading" style="background-color:#1f232a;">
                            <h3 class="panel-title" style="color: #fff; margin-top: 3px"><i class="fa fa-car fa-fw"></i> &nbsp; <?php echo "Assigned Vehicles" ?></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover" style="margin-bottom:0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted text-left"><b>Model</b></td>
                                        <td>
                                           <b> Plate No.</b>
                                        </td>
                                    </tr>
                                    <?php $i=1; foreach ($viewall as $new) {?>
                                    <tr>
                                        <td class="text-muted text-left"><?php echo $i.". ".$new->model;?></td>
                                        <td>
                                            <?php echo $new->plate_no; ?>
                                        </td>
                                         </tr>
                                    <?php ++$i; }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }?>
                </div>
</div> 


<script src="<?php echo  base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>  


