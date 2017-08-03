<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="edit-device-form">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Serial No.:</label>
                            
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="serial_no" id="serial_no" value="<?php echo $device->serial_no ?>" readonly/>
                            </div>
                            </div>
                        </div><br><br>

                        <?php if(!empty($device->phone_no)){?>
                        <div class="form-group">
                              <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Phone No.:</label>
                            
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="phone_no" id="phone_no" value="<?php echo $device->phone_no ?>" readonly/>
                            </div>
                            </div>
                           
                        </div><br><br>
                        <?php }?>

                       <?php if($device->status=="Assigned"){
                       $plate=$this->mdl_devices->get_plate($device->device_id);
                       ?>


                        <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Plate No.:</label>
                            
                            </div>
                            <div class="col-md-8">
                               <input class="form-control" type="text" name="plate_no" id="plate_no" value="<?php echo $plate->plate_no ?>" readonly/>
                            </div>
                            </div>
                            </div><br><br>

                       
                        <?php }?>

                        <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Status:</label>
                            
                            </div>
                            <div class="col-md-8">
                               <input class="form-control" type="text" name="plate_no" id="plate_no" value="<?php echo $device->status ?>" readonly/>
                            </div>
                            </div>
                            </div><br><br>
                        </div>                       
                        
                    </div>
                </div>

                
            </div>

            <?php if($device->status=="Assigned"){?>
             <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="col-md-12">

                          <?php if($device->status=="Assigned"){
                       $account_id=$this->mdl_devices->get_account($device->account_id);
                       ?>

                        <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Account Name:</label>
                            
                            </div>
                            <div class="col-md-8">
                               <input class="form-control" type="text" name="account_id" id="account_id" value="<?php echo $account_id->account_name ?>" readonly/>
                            </div>
                            </div>
                            </div><br><br>

                        <?php }?>

                          <?php if(!empty($device->account_id)){
                       $installer=$this->mdl_devices->get_installer($device->installer_id);
                       ?>

                          <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Installer:</label>
                            
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="installer" id="installer" value="<?php echo $installer->first_name.' ' ?><?php echo $installer->last_name ?>" readonly/>
                            </div>
                            </div>
                            </div><br><br>

                            <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Installation Date:</label>
                            
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="installation_date" id="installation_date" value="<?php echo $device->installation_date ?>" readonly/>
                            </div>
                            </div>
                            </div><br><br>


                        <?php }?>

                         <div class="form-group">
                            <div class="col-md-12">
                            <div class="col-md-4">
                            <label for="reservation">Credits:</label>
                            
                            </div>
                            <div class="col-md-8">
                                 <input class="form-control" type="text" name="credits" id="credits" value="<?php echo $device->credits ?>" readonly/>
                            </div>
                            </div>
                            </div><br><br>

                    </div>
                       
                        
                    </div>
                </div>
                
            </div>
            <?php }?>
        </form>
        <div class="col-md-6 col-lg-6" style="display:none;">
            <div class="panel panel-default" style="display:none;">
                <div class="panel-heading">
                    Upload Vehicle Image
                </div>
                <div class="panel-body">
                    <div id="dropzone">
                        <form action="<?php echo base_url('index.php/upload_images/upload_vehicle_image') ?>" class="dropzone" id="dropzone-container">
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--<div class="col-md-12 bg-crumb" align="center">
                <h2><i class="fa fa-car"></i> Vehicles</h2>
                <br>
                <p>Manage Vehicles and begin monitoring your assets Location, Fuel usage driver efficiency and schedule preventative maintenance</p>

                <a href="<1?php echo site_url('vehicles/groups');?>" class="btn btn-success">View Groups</a>    
            </div>-->

        </div>

    </div>
</div> 

<script src="<?php echo  base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>  


