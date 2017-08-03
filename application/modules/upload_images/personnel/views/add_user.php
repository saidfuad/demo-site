<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css')?>" rel="stylesheet" />

<div class="container-fluid">
    <div class="row">
        <form id="add-user-form">
	        <div class="col-md-6">
	            <div class="panel panel-default">
	                <div class="panel-heading"> 
	                   User Details
	                </div>
	                <div class="panel-body">
	                    
	                    <div class="form-group">
	                        <label for="reservation">Access level<sup>*</sup>:</label>
	                         <select class="form-control" type="text" name="protocal" id="protocal" required="required">
	                        	<option value="0">--Select--</option>
	                        	<option value="7">Admin User</option>
	                        	<option value="5">Normal User</option>
	                        </select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">ID No <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="id_no" id="id_no" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Firstname <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="first_name" id="first_name" required="required"/>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Lastname <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="last_name" id="last_name" required="required"/>
	                    </div>
	                    <div class="form-group">
	                    	<label for="">Timezone <sup>*</sup>:</label>
	                        <select name="timezone" class="form-control" id="timezone">
								<option value="-12" >(GMT -12:00) Eniwetok, Kwajalein</option>
				                <option value="-11" >(GMT -11:00) Midway Island, Samoa</option>
				                <option value="-10" >(GMT -10:00) Hawaii</option>
				                <option value="-9"  >(GMT -9:00) Alaska</option>
				                <option value="-8"  >(GMT -8:00) Pacific Time (US &amp; Canada)</option>
				                <option value="-7"  >(GMT -7:00) Mountain Time (US &amp; Canada)</option>
				                <option value="-6"  >(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
				                <option value="-5"  >(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
				                <option value="-4.5">(GMT -4:30) Caracas</option>
				                <option value="-4"  >(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago</option>
				                <option value="-3.5">(GMT -3:30) Newfoundland</option>
				                <option value="-3"  >(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
				                <option value="-2"  >(GMT -2:00) Mid-Atlantic</option>
				                <option value="-1"  >(GMT -1:00 hour) Azores, Cape Verde Islands</option>
				                <option value="0">(GMT) Western Europe Time, London, Lisbon, Casablanca, Greenwich</option>
				                <option value="1"   >(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
				                <option value="2"   >(GMT +2:00) Kaliningrad, South Africa, Cairo</option>
				                <option value="3"   selected="selected">(GMT +3:00) Nairobi, Baghdad, Riyadh, Moscow, St. Petersburg</option>
				                <option value="3.5" >(GMT +3:30) Tehran</option>
				                <option value="4"   >(GMT +4:00) Abu Dhabi, Muscat, Yerevan, Baku, Tbilisi</option>
				                <option value="4.5" >(GMT +4:30) Kabul</option>
				                <option value="5"   >(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
				                <option value="5.5" >(GMT +5:30) Mumbai, Kolkata, Chennai, New Delhi</option>
				                <option value="5.75">(GMT +5:45) Kathmandu</option>
				                <option value="6"   >(GMT +6:00) Almaty, Dhaka, Colombo</option>
				                <option value="6.5" >(GMT +6:30) Yangon, Cocos Islands</option>
				                <option value="7"   >(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
				                <option value="8"   >(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
				                <option value="9"   >(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
				                <option value="9.5" >(GMT +9:30) Adelaide, Darwin</option>
				                <option value="10"  >(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
				                <option value="11"  >(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
				                <option value="12"  >(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
							</select>
	                    </div>
	                    <div class="form-group">
	                        <label for="reservation">Gender <sup>*</sup>:</label>
	                        <select class="form-control" type="text" name="gender" id="gender" required="required">
	                        	<option value="0">--Select--</option>
	                        	<option value="Male">Male</option>
								<option value="Female">Female</option>
	                        </select>
	                    </div>
	                    
	                    <div class="form-group">
	                        <label for="reservation">Address:</label>
	                        <textarea class="form-control" type="text" name="address" id="address" rows="3"></textarea>
	                    </div>
	                </div>
	                
	            </div>
	            
	           
			</div>
		
		<div class="col-md-6 col-lg-6">
			<div class="panel panel-default">
                <div class="panel-heading">
                   Contacts
                </div>
                <div class="panel-body">
                    <!-- <div class="form-group">
	                        <label for="reservation">Phone <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="phone_number" id="phone_number" required="required" placeholder="Format: +254701022477"/>
	                    </div> -->
	                    <div class="form-group">
	                        <label for="reservation">Phone <sup>*</sup>:</label>
	                        <!-- <input class="form-control" type="text"> -->
	                    </div>
	                    <div class="input-group" style="margin-bottom: 20px; margin-top: -15px;">
						  <span class="input-group-addon" id="basic-addon1">+254</span>
						  <input type="text" class="form-control" aria-describedby="basic-addon1" name="phone_number" id="phone_number" required="required">
						</div>
	                    <div class="form-group">
	                        <label for="reservation">Email <sup>*</sup>:</label>
	                        <input class="form-control" type="text" name="email_address" id="email_address" required="required"/>
	                    </div>
                </div>
            </div>

            <!--div class="panel panel-default">
                <div class="panel-heading">
                    Active dates
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                    	<label for="reservation">From <sup>*</sup>:</label>
                        <div class="form-group">
			                <div class='input-group date' id='datetimepicker1'>
			                    <input type="input" name="from_date" id="from_date"value="" class="form-control" required="required">
			                    <span class="input-group-addon">
			                        <span class="fa fa-calendar"></span>
			                    </span>
			                </div>
			            </div>
                   </div>
                   <div class="col-sm-12">
                    	<label for="reservation">To <sup>*</sup>:</label>
                        <div class="form-group">
			                <div class='input-group date' id='datetimepicker1'>
			                    <input type="input" name="to_date" id="to_date" value="" class="form-control" required="required">
			                    <span class="input-group-addon">
			                        <span class="fa fa-calendar"></span>
			                    </span>
			                </div>
			            </div>
                   </div>
                </div>
            </div-->

	
            <div class="panel panel-default">
                <div class="panel-heading">
                    Alerts
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <input type="checkbox" name="sms_alert" id="sms_alerts"value="1" class="alert-check"> SMS alerts
                   </div>
                   <div class="col-sm-12">
                        <input type="checkbox" name="email_alert" id="email_alerts" value="1" class="alert-check" > Email alerts
                   </div>
                </div>
            </div>

            <!--<div class="col-md-12 bg-crumb" align="center">
				<h2><i class="fa fa-users"></i> Personnel</h2>
				<br>
				<p>Manage drivers and other employees information. Assign roles to all personnel and permissions 
					to all users determining who accesses what and when </p>

				<a href="<?php echo site_url('personnel');?>" class="btn btn-success">View Personnel</a>	
			</div>-->
		</div>
		<div class="col-md-12 col-lg-12">
			<div class="panel-footer" align="right">
            	<button class="btn btn-primary btn-lg" type="submit" id="save-user">Save</button>
            </div>
        </div>

        </form>
    </div>
</div> 

 <script type="text/javascript">
    $(function () {
        //$('#datetimepicker4').datetimepicker();
    });
</script>

<script>
    $(function () {

        $('#add-user-form').on('submit' , function () {

        	var phone_number = $('#phone_number').val().trim();
        	var id_no = $('#id_no').val().trim();

			if ($('#protocal').val().trim()==0 || $('#id_no').val().trim().length==0 || 
					$('#first_name').val().trim().length ==0 || $('#last_name').val().trim().length==0 ||
						 $('#phone_number').val().trim().length==0 || $('#email_address').val().trim().length ==0 || 
						 	$('#timezone').val().trim()==0) {
				swal({   title: "Info",   text: "Fill in all required fields ( * )",   type: "info",   confirmButtonText: "ok" });
				return false;
			}

			if (isNaN(id_no) == true) {
				swal({   title: "Info",   text: "Enter a valid ID number",   type: "info",   confirmButtonText: "ok"});
			    return false;
			}
			
			// if(phone_number.indexOf('+') === -1 || phone_number.length < 11) {
			//    swal({   title: "Info",   text: "Enter a valid phone number",   type: "info",   confirmButtonText: "ok"});
			//    return false;
			// }
			var str1 = "+254";
			str2 = document.getElementById('phone_number').value;

			// alert(str2.charAt(0, 4));

				while( str2.charAt( 0 ) === '0' )
	    		str2 = str2.slice( 1 );

				var res = str1.concat(str2);

				var elem = document.getElementById("phone_number"); 
				var e = elem.value = res;


			$('#save-user').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-user').prop('disabled', true);

            if (e.length == 13){ 
            $.ajax({
                method: 'post',
                url: '<?= base_url('index.php/personnel/save_user') ?>',
                data: $(this).serialize(),
                success: function (response) {

                	if (response==0) {
                    	swal({   title: "Error",   text: "Failed, Try again later",   type: "error",   confirmButtonText: "ok" });
                    } else if (response=='id_exists') {
                    	swal({   title: "Info",   text: "ID No. already exists",   type: "error",   confirmButtonText: "ok" });
                    } else if (response=='phone_exists') {
                    	swal({   title: "Info",   text: "Phone number already exists",   type: "error",   confirmButtonText: "ok" });
                    } else if (response=='email_exists') {
                    	swal({   title: "Info",   text: "Email already exists",   type: "error",   confirmButtonText: "ok" });
                    } else if (parseInt(response) && parseInt(response) > 0) {
                    	$('#add-user-phone').find('input[type="text"]').val('');
                    	$('#add-user-phone').find('select').val(0);
                    	$('#add-user-phone').find('textarea').val('');
                    	swal({   title: "Info",   text: "Saved successfully",   type: "success",   confirmButtonText: "ok" });

                    	window.location.replace('<?= site_url('settings/edit_permissions');?>' + '/' + response);
                    }

                    $('#save-user').html('Save');
            		$('#save-user').prop('disabled', false);
                 }
            });
            }
            else{
            	$('#save-client').html('Save');
            	$('#save-client').prop('disabled', false);

            	document.getElementById('phone_number').value="";
            	swal({   title: "Info",   text: "Wrong Phone number",   type: "error",   confirmButtonText: "ok" });
            }

            
            return false;     
        });

    });
</script>     

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js')?>"></script>

