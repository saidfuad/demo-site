<?php echo $map['js']; ?>
<div class="container-fluid fleet-view">
    <div class="row">
        <!--<div class="col-md-12">
            <br>
            <div class="col-md-12 bg-crumb margin-top-10">
                <div class="form-inline" role="form">
                  <div class="form-group">
                     
                    <select class="form-control" name="select-owners" id="select-owners">
                        <option value="0">All Owners <i class="fa fa-home"></i></option>
                        <?php echo $ownerOpt;?>
                    </select>
                  </div>
                  <div class="form-group">
                     
                    <select class="form-control" name="select-types" id="select-types">
                        <option value="0">All Types</option>
                        <?php echo $typeOpt;?>
                    </select>
                  </div>
                  <div class="form-group">
                    
                    <select class="form-control" name="select-categories" id="select-categories">
                        <option value="0">All Categories</option>
                        <?php echo $categoryOpt;?>
                    </select>
                  </div>
                  <div class="form-group">
                     
                    <select class="form-control" name="select-areas" id="select-areas">
                        <option value="0">All Areas</option>
                        
                    </select>
                  </div>
                  <div class="form-group">
                     
                    <select class="form-control" name="select-routes" id="select-routes">
                        <option value="0">All Routes</option>
                        
                    </select>
                  </div>
                  <div class="form-group">
                    
                    <select class="form-control" name="select-landmarks" id="select-landmarks">
                        <option value="0">All Landmarks</option>
                        <?php echo $landmarkOpt;?>
                    </select>
                  </div>
                  <div class="form-group pull-right">
                    <label>Search: </label>
                    <input class="form-control" name="vehicle-search" id="vehicle-search" placeholder="vehicle name/no. plate"/>
                  </div>
                  
                </div>
            </div>    
           
        </div> -->    
         <div class="col-md-3" style="height:100%">
            <div class="col-md-12 bg-crumb" >
                <h4>Create Locations( <sup>*</sup> Required)</h4>
                <form id="form-create-landmark">
                    <div class="form-group">
                        <label>Location Name <sup>*</sup></label>
                        <input class="form-control input-sm clear-input" name="location_name" id="location_name" type="text" placeholder="Enter landmark name" required="required">
                    </div>
                    
                    <div class="form-group">
                        <label>Area Color <sup>*</sup></label>
                         <input type="text" class="form-control" id="full-popover" name="area_color" value="#337ab7" data-color-format="hex" required="required">
                        
                    </div>
                    <div class="form-group">
                        <label>Alert before Area (KM) <sup>*</sup></label>
                        <input class="form-control input-sm" min="1" value="1" id="alert_before_landmark" name="alert_before_landmark" type="number" placeholder="Specify the distance for alerts" required="required">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            
                            <input class="alert-checks" name="in_alert" type="checkbox" value="1" placeholder=""><br><label>In Alert</label>
                        </div>
                         <div class="col-sm-4">
                            <input class="alert-checks" name="out_alert" type="checkbox" value="1" placeholder=""><br><label>Out Alert</label>
                        </div>
                         <div class="col-sm-4">
                            <input class="alert-checks" name="sms_alert" type="checkbox" value="1" placeholder=""><br><label>SMS Alert</label>
                        </div>

                    </div>
                    <div class="form-group margin-top-10">
                        <div class="col-sm-6 margin-top-10">
                            <button class="btn btn-default btn-block" id="btn-clear">Clear</button>
                        </div>
                         <div class="col-sm-6 margin-top-10">
                           <button class="btn btn-success btn-block" id="btn-clear">Create</button>
                        </div>
                         
                    </div>
                </form>    
                
            </div>    
        </div>    
        <div class="col-md-9">
            <?php echo $map['html']; ?>
        </div>       
        <!-- //. Content -->
    </div>
    <!-- /.row -->
</div>        