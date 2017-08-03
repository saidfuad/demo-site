<div class="modal fade" id="newDriver" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add New Driver</h4>
      </div>
      <form id="add-personnel-form">
          <div class="modal-body">
                <div class="form-group">
                    <input class="form-control" type="hidden" name="role_id" id="role_id" value="2">
                    <label for="reservation">ID No <sup>*</sup>:</label>
                    <input class="form-control" type="text" name="id_no" id="id_no"/>
                </div>
                <div class="form-group">
                    <label for="reservation">Firstname <sup>*</sup>:</label>
                    <input class="form-control" type="text" name="fname" id="fname"/>
                </div>
                <div class="form-group">
                    <label for="reservation">Lastname <sup>*</sup>:</label>
                    <input class="form-control" type="text" name="lname" id="lname"/>
                </div>
                <div class="form-group">
                    <label for="reservation">Gender <sup>*</sup>:</label>
                    <select class="form-control" type="text" name="gender" id="gender">
                        <option value="">--Select--</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reservation">Phone <sup>*</sup>:</label>
                </div>
                <div class="input-group" style="margin-bottom: 20px; margin-top: -15px;">
                    <span class="input-group-addon" id="basic-addon1">+254</span>
                    <input type="text" class="form-control" name="phone_no" id="p_phone_no">
                </div>
                <div class="form-group">
                    <label for="reservation">Email <sup>*</sup>:</label>
                    <input class="form-control" type="text" name="email" id="email"/>
                </div>
                <div>
                    <b>Note:</b> Remember to upload the Driver License Details when updating the user details.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>


        $('#add-personnel-form').on('submit', function () {

			if ($('#id_no').val().trim() == '' || $('#fname').val().trim() == '' || $('#lname').val().trim() == '' ||
				$('#gender').val().trim() == '' || $('#p_phone_no').val().trim()== ''){
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

            var $this = $(this);

			var str1 = "+254";
			str2 = document.getElementById('p_phone_no').value;

            while( str2.charAt( 0 ) === '0' )
            str2 = str2.slice( 1 );

            var res = str1.concat(str2);

            if (res.length == 13){
            	swal({
                title: "Confirm",
                text: "Add Driver?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
                }, function(){

	            $.ajax({

	                method: 'post',

	                url: '<?= base_url('index.php/personnel/save_personnel') ?>',

	                data: $this.serialize(),

	                success: function (response) {

	                    if (response==1) {

                            sessionStorage.setItem('vehname', $('#assets_friendly_nm').val());
                            sessionStorage.setItem('plate', $('#assets_name').val());
                            sessionStorage.setItem('vehtypeid', $('#assets_type_id').val());
                            sessionStorage.setItem('vehtype', $('#assets_type').val());
                            sessionStorage.setItem('vehcatid', $('#assets_category_id').val());
                            sessionStorage.setItem('vehcat', $('#assets_category').val());
                            sessionStorage.setItem('ownerid', $('#owner_id').val());
                            sessionStorage.setItem('owner', $('#owners').val());
                            sessionStorage.setItem('personid', $('#personnel_id').val());
                            sessionStorage.setItem('person', $('#personnel').val());

	                    	$('#add-personnel-form').find('input[type="text"]').val('');
	                    	$('#add-personnel-form').find('select').val(0);
	                    	$('#add-personnel-form').find('textarea').val('');

	                    	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });

                            location.reload();

	                    }
	                    else if (response==0) {

	                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
	                    }
	                    else if (response==77) {

	                    	swal({   title: "Info",   text: "ID No. already exists",   type: "error",   confirmButtonText: "ok" });
	                    }
	                    else if (response==78) {

	                    	swal({   title: "Info",   text: "Phone number already exists",   type: "error",   confirmButtonText: "ok" });
	                    }
	                    else if (response==79) {

	                    	swal({   title: "Info",   text: "Email already exists",   type: "error",   confirmButtonText: "ok" });
	                    }

	                    $('#save-personnel').html('Save');
	            		$('#save-personnel').prop('disabled', false);
	                 }
	            });
	        });

            }
            else{
            	$('#save-personnel').html('Save');
            	$('#save-personnel').prop('disabled', false);

            	swal({   title: "Info",   text: "Wrong Phone number",   type: "error",   confirmButtonText: "ok" });
            }

            return false;
        });

</script>
