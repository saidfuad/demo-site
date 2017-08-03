<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-group-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    Group Details
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group">
	                        <label for="reservation">Group Name <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="group_name" id="group_name" required="required" autofocus/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Group Description:</label>
	                        <textarea class="form-control" type="text" name="group_description" id="group_description" rows="5"></textarea>
	                    </div>
	                </div>
	                
	            </div>
	            <div class="panel-footer" align="right">
                	<button class="btn btn-primary" type="submit" id="save-group">Save</button>
                </div>

	           
			</div>
		</form>
		<div class="col-md-6 col-lg-6">
			<div class="col-md-12 bg-crumb" align="center">
				<h2><i class="fa fa-sitemap"></i> Groups</h2>
				<br>
				<p>a collection of vehicles that has a collective goal and is linked to a particular route/area/locaton.</p>

				<a href="<?php echo site_url('vehicles/groups');?>" class="btn btn-success">View Groups</a>	
			</div>
		</div>

    </div>
</div> 

<!--<script src="<?php echo  base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>   -->
   
<script type="text/javascript">
        $(function () {

            $('#add-group-form').on('submit' , function () {

              var group_name = $('#group_name').val().trim();
              var group_description = $('#group_description').val().trim();
      				if ($('#group_name').val().trim()==0) {
      					swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
      					return false;
      				}

				$('#save-group').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#save-group').prop('disabled', true);

                var $this = $(this);

        				if ($('#group_name').val().trim()==0) {
        					swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
        					return false;
        				}

        				$('#save-group').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#save-group').prop('disabled', true);
               
                swal({
                  title: 'Are you sure?',
                  text: "Click continue to add new Group!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Continue!',
                  closeOnConfirm: false
                },
                function() {

                  $.ajax({

                      method: 'POST',
                      cache   : false,
                      data: {group_name:group_name, group_description:group_description},
                      url: '<?= base_url('index.php/vehicles/save_vehicle_group') ?>',
                      
                      success: function (response) {
                          if (response==1) {
                          	$('#add-group-form').find('input[type="text"]').val('');
                          	$('#add-group-form').find('textarea').val('');
                          	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });
                          } else if (response==0) {
                          	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                          } else if (response==77) {
                          	swal({   title: "Info",   text: "A group with that name already exists. Enter a different group name",   type: "error",   confirmButtonText: "ok" });
                          }

                          $('#save-group').html('Save');
                  		    $('#save-group').prop('disabled', false);
                       }
                  });
              });  
                $('#save-group').html('Save');
                $('#save-group').prop('disabled', false);
                
                return false;     
            });

        });
    </script>     