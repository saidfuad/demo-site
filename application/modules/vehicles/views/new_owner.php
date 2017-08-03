<div class="modal fade" id="newOwner" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create New Owner</h4>
            </div>
            <form id="new-owner-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reservation">Owner Name:</label>
                        <input class="form-control" type="text" name="owner_name" id="owner_name" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="reservation">Phone
                            <sup>*</sup>:
                        </label>
                        <!-- <input class="form-control" type="text"> -->
                    </div>
                    <div class="input-group" style="margin-bottom: 20px; margin-top: -15px;">
                        <span class="input-group-addon" id="basic-addon1">+254</span>
                        <input type="text" class="form-control" aria-describedby="basic-addon1" name="phone_no" id="phone_no" required="required">
                    </div>
                    <div class="form-group">
                        <label for="reservation">Email:</label>
                        <input class="form-control" type="text" name="email" id="email" required="required"/>
                    </div>
                    <div class="form-group">
                        <label for="reservation">Address:</label>
                        <textarea class="form-control" type="text" name="address" id="address" required="required" row="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save_owner">Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

    $("#new-owner-form").on('submit', function(){

            var owner_name = $('#owner_name').val().trim();
        	// var phone_no = $('#phone_no').val().trim();
        	var email = $('#email').val().trim();
        	var address = $('#address').val().trim();
        	var $this = $(this);

			if ($('#owner_name').val().trim().length==0 || $('#phone_no').val().trim().length==0 ||
						$('#email').val().trim().length ==0 || $('#address').val().trim().length ==0) {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

			var str1 = "+254";
            str2 = document.getElementById('phone_no').value;

            while( str2.charAt( 0 ) === '0' )
            str2 = str2.slice( 1 );

            var res = str1.concat(str2);

			$('#save-owner').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-owner').prop('disabled', true);

            var $this = $(this);
            if (res.length == 13){

            swal({
                title: "Info",
                text: "Add Owner?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
                }, function(){

		            $.ajax({
		                method: 'post',
		                url: '<?= base_url('index.php/owners/save_owner') ?>',
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

		                    	$('#new-owner-form').find('input[type="text"]').val('');
		                    	$('#new-owner-form').find('select').val(0);
		                    	$('#new-owner-form').find('textarea').val('');
		                    	$('div.dz-success').remove();
		                    	$('div.dz-message').show();

		                    	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });

                                location.reload();

		                    } else if (response==0) {
		                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
		                    } else if (response==77) {
		                    	swal({   title: "Info",   text: "Phone number already exists",   type: "error",   confirmButtonText: "ok" });
		                    } else if (response==78) {
		                    	swal({   title: "Info",   text: "Email already exists",   type: "error",   confirmButtonText: "ok" });
		                    }

		                    $('#save-owner').html('Save');
		            		$('#save-owner').prop('disabled', false);
		                 }
		            });
		        });
            	$('#save-owner').html('Save');
		        $('#save-owner').prop('disabled', false);
		            }
            else{
            	swal({   title: "Info",   text: "Wrong Phone number",   type: "error",   confirmButtonText: "ok" });
                $('#save-client').html('Save');
            	$('#save-client').prop('disabled', false);
            }


            return false;

        });

</script>
