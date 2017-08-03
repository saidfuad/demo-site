<div id="container-fluid" >
    <div class="row">
    <br>
        <div class="col-md-12 col-lg-12">
            
                <div class="col-md-3 col-lg-3" align="center">
                <div class="bg-crumb">
                    <i class="fa fa-user fa-4x"></i>
                    <h3><?php echo $user['first_name'] . ' ' . $user['last_name'];?></h3>
                    <p><?php echo $user['phone_number'];?></p>
                    <p><?php echo $user['email_address'];?></p>
                    <p><?php echo $user['address'];?></p>
                    <input type="hidden" id="user-id-holder" value="<?php echo $user['user_id'];?>" />
                </div>

                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h4 class="panel-title">Menu Permissions</h4>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row" id="menu-holder">
                                <div class="col-sm-12">
                                    <input type="checkbox" name="" value=""  <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> class="menu-check-all"> Select all/Unselect all
                                </div>
                                <br>
                                <br>
                                <?php 
                                $menu_permissions = explode(',', $user['menu_permissions']);


                                foreach ($menus as $k=>$menu) {?>
                                    

                                <div class="col-sm-12 chk-holder">
                                    <input type="checkbox" <?php if(in_array($menu->menu_id, $menu_permissions)) {?> checked="checked" <?php }?> name="menu_id" value="<?php echo $menu->menu_id; ?>" class="menu-check" <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?>> <?php echo $menu->menu_name; ?>
                                </div>
                                <?php } ?>
                            </div>
                            
                        </div>
                        <div class="panel-footer" >
                            <button class="btn btn-success  btn-block btn-sm" <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> id="save-menus"> Save</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h4 class="panel-title">Alerts Permissions</h4>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="checkbox" name="" value=""  class="alerts-check-all"> Select all/Unselect all
                                </div>
                               <br>
                               <br>
                               <div class="col-sm-12">
                                    <input type="checkbox" name="sms_alerts" id="sms_alerts" <?php if($user['sms_alert'] ==1) {?> checked="checked" <?php }?> value="<?php echo $user['sms_alert']?>" class="alert-check"> SMS alerts
                               </div>
                               <div class="col-sm-12">
                                    <input type="checkbox" name="email_alerts" id="email_alerts"  <?php if($user['email_alert'] ==1) {?> checked="checked" <?php }?> value="<?php echo $user['email_alert']?>" class="alert-check" > Email alerts
                               </div>
                                
                            </div>
                            
                        </div>
                        <div class="panel-footer" >
                            <button class="btn btn-success  btn-block btn-sm" id="save-alerts"> Save</button>
                        </div>
                    </div>
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h4 class="panel-title">Assigned Groups</h4>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row" id="groups-holder">
                                <div class="col-sm-12">
                                    <input type="checkbox" name="" value=""  <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> class="groups-check-all"> Select all/Unselect all
                                </div>
                               <br>
                                <br>
                                <?php 
                                $assigned_groups = explode(',', $user['assigned_groups']);

                                if (sizeof($groups)) {

                                    foreach ($groups as $k=>$group) {?>
                                    <div class="col-sm-12 chk-holder">
                                        <input type="checkbox"  <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> <?php if(in_array($group->assets_group_id, $assigned_groups)) {?> checked="checked" <?php }?> name="assets_group_id" value="<?php echo $group->assets_group_id; ?>" class="group-check"> <?php echo $group->assets_group_nm; ?>
                                    </div>
                                    <?php } 
                                } else { ?>
                                <div class="alert alert-info">No Vehicle Groups</div>

                                <?php }?>
                            </div>
                        </div>
                        <div class="panel-footer" >
                            <button class="btn btn-success  btn-block btn-sm" id="save-groups"> Save</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="panel panel-square">
                        <div class="panel-heading panel-info clearfix">
                            <h4 class="panel-title">Reports Permissions</h4>
                        </div>
                        <div class="panel-body fleet-issues">
                            <div class="row" id="reports-holder">
                                <div class="col-sm-12">
                                    <input type="checkbox" name="" value=""  <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> class="reports-check-all"> Select all/Unselect all
                                </div>
                               <br>
                                <br>
                                <?php 
                                $report_permissions = explode(',', $user['report_permissions']);


                                foreach ($reports as $k=>$report) {?>
                                <div class="col-sm-12 chk-holder">
                                    <input type="checkbox" <?php if(in_array($report->report_id, $report_permissions)) {?> checked="checked" <?php }?> name="report_id" value="<?php echo $report->report_id; ?>" class="report-check"  <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> > <?php echo $report->report_name; ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="panel-footer" >
                            <button class="btn btn-success  btn-block btn-sm" <?php if ($user['protocal'] == 7) {?> disabled='disabled'<?php } ?> id="save-reports"> Save</button>
                        </div>
                    </div>
                </div>      
           
        </div>
    </div>
</div>    


<script type="text/javascript">
    $(function () {
        $('.menu-check-all').on('click', function () {
           // alert($(this).attr('checked'));
            if ($(this).prop('checked')) {
                $('.menu-check').prop('checked', true);
            } else {
                $('.menu-check').prop('checked', false);
            }
            
        });

        $('.alerts-check-all').on('click', function () {
           // alert($(this).attr('checked'));
            if ($(this).prop('checked')) {
                $('.alert-check').prop('checked', true);
            } else {
                $('.alert-check').prop('checked', false);
            }
            
        });

        $('.groups-check-all').on('click', function () {
           // alert($(this).attr('checked'));
            if ($(this).prop('checked')) {
                $('.group-check').prop('checked', true);
            } else {
                $('.group-check').prop('checked', false);
            }
            
        });


        $('.reports-check-all').on('click', function () {
           // alert($(this).attr('checked'));
            if ($(this).prop('checked')) {
                $('.report-check').prop('checked', true);
            } else {
                $('.report-check').prop('checked', false);
            }
            
        });


        $('#save-menus').on('click', function () {

            var user_id = $("#user-id-holder").val().trim();
            var menu_ids = $('#menu-holder').children('.chk-holder').children("input:checkbox:checked").map(function(){
                return $(this).val();
            })
            .get()
            .join();


            swal({
                  title: 'Are you sure?',
                  text: "Click finish to complete action!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Continue!',
                  closeOnConfirm: false
                },
                function() {

                $.ajax({
                    type    : "POST",
                    cache   : false,
                    data : {user_id:user_id, menu_ids:menu_ids},
                    url     : "<?php echo base_url('index.php/settings/set_menu_permissions') ?>",
                    success: function(response) {

                        //alert(response);
                        if (response == 1) {
                           swal({title:'Success',   text: 'Saved Successfully',   type:'success',   confirmButtonText: "Ok" });
                        } else {
                             swal({title:'Error',   text: 'Failed to save',   type:'error',   confirmButtonText: "Ok" });
                        }                 

                    } 

                });
            });

                return false;
        });

        $('#save-alerts').on('click', function () {

            var user_id = $("#user-id-holder").val().trim();
            var sms_alert =  0;
            var email_alert =  0;

            if ($('#sms_alerts').prop('checked')) {
                sms_alert = 1;
            }

            if ($('#email_alerts').prop('checked')) {
                email_alert = 1;
            }

            //alert(sms_alert);

            swal({
                  title: 'Are you sure?',
                  text: "Click finish to complete action!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Continue!',
                  closeOnConfirm: false
                },
                function() {

                    $.ajax({
                        type    : "POST",
                        cache   : false,
                        data : {user_id:user_id, sms_alert:sms_alert, email_alert:email_alert},
                        url     : "<?php echo base_url('index.php/settings/set_alert_permissions') ?>",
                        success: function(response) {

                            //alert(response);
                            if (response == 1) {
                               swal({title:'Success',   text: 'Saved Successfully',   type:'success',   confirmButtonText: "Ok" });
                            } else {
                                 swal({title:'Error',   text: 'Failed to save',   type:'error',   confirmButtonText: "Ok" });
                            }                 

                        } 

                    });
                });

                return false;
        });


        $('#save-groups').on('click', function () {

            var user_id = $("#user-id-holder").val().trim();
            var group_ids = $('#groups-holder').children('.chk-holder').children("input:checkbox:checked").map(function(){
                return $(this).val();
            })
            .get()
            .join();


            swal({
                  title: 'Are you sure?',
                  text: "Click finish to complete action!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Continue!',
                  closeOnConfirm: false
                },
                function() {

                $.ajax({
                    type    : "POST",
                    cache   : false,
                    data : {user_id:user_id, group_ids:group_ids},
                    url     : "<?php echo base_url('index.php/settings/set_group_permissions') ?>",
                    success: function(response) {

                        //alert(response);
                        if (response == 1) {
                           swal({title:'Success',   text: 'Saved Successfully',   type:'success',   confirmButtonText: "Ok" });
                        } else {
                             swal({title:'Error',   text: 'Failed to save',   type:'error',   confirmButtonText: "Ok" });
                        }                 

                    } 

                });
            });

                return false;
        });

        $('#save-reports').on('click', function () {

            var user_id = $("#user-id-holder").val().trim();
            var report_ids = $('#reports-holder').children('.chk-holder').children("input:checkbox:checked").map(function(){
                return $(this).val();
            })
            .get()
            .join();


            swal({
                  title: 'Are you sure?',
                  text: "Click finish to complete action!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Continue!',
                  closeOnConfirm: false
                },
                function() {

                $.ajax({
                    type    : "POST",
                    cache   : false,
                    data : {user_id:user_id, report_ids:report_ids},
                    url     : "<?php echo base_url('index.php/settings/set_report_permissions') ?>",
                    success: function(response) {

                        //alert(response);
                        if (response == 1) {
                           swal({title:'Success',   text: 'Saved Successfully',   type:'success',   confirmButtonText: "Ok" });
                        } else {
                             swal({title:'Error',   text: 'Failed to save',   type:'error',   confirmButtonText: "Ok" });
                        }                 

                    } 

                });
            });

                return false;
        });

    })
</script>