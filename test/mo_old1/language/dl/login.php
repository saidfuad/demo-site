<?php include("header.php"); ?>
		<div data-role="content">
			<form  action ="" method='post' id="loginform">				
				<div class="ui-body ui-body-d">

					<div data-role="fieldcontain">
						<label for="Username">User name</label>
						<input type="text" name="name" id="Username" data-inline="false" value="" /><br><br>
						<label for="Password">Password</label>
						<input type="password" name="name" id="Password" value=""  />
					</div>
					<div data-role="fieldcontain">
						<a data-role="button"  data-theme="b" data-inline="false" onclick="return checklogin()">Login</a>
					</div>
					<div data-role="fieldcontain">
						<a data-role="button"  data-theme="b" data-inline="false" onclick="$('#Username').val('demo');$('#Password').val('devindia');checklogin();">Demo</a>
					</div>
					
					
				</div><!-- /body-d -->
				
			</form>
		</div>
<script>
function checklogin()
{
	if($("#Username").val()=="" || $("#Password").val()=="")
	{
		alert("Username or Password Blank Not Allowed");
		return false;
	}
		var Username = $("#Username").val();
		var Password = $("#Password").val();
		$.post("php/check_login.php",{Username:Username,Password:Password},function(data){
		if(data.result == "true"){
			window.location = data.msg;
			}
			else{
			alert(data.error);
				
			}	
	},'json');
	return false;
}
</script>
<?php include("footer.php"); ?>
