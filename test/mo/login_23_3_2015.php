<?php include("header.php"); ?>
		<div data-role="content">
			<form  action ="" method='post' >				
				<div class="ui-body ui-body-d">

					<div data-role="fieldcontain">
						<label id="Username_label" for="Username">Username</label>
						<input type="text" name="name" id="Username" data-inline="false" value="" /><br>
						<label id="Password_label" for="Password">Password</label>
						<input type="password" name="name" id="Password" value=""  /><br>
						<label id="language_label" for="language">Language</label>
						<select id="language" type="text" name="language"><option value='Portuguese'>Português</option><option value='English'>English</option></select>
					</div>
					<div data-role="fieldcontain">
						<a data-role="button"  data-theme="b" data-inline="false" onclick="return checklogin()">Login</a>
					</div>
				</div><!-- /body-d -->
				
			</form>
		</div>
<script>
$("#language").change(function () {
 // console.log("hello");
   // var $this = $(this),
        val   = $this.val();
	//	alert('onChange = ' + val);
	//	if(val == "English"){
		//$("#language_label").val("hpk");
		
		//}
		//console.log($("#language_label").val());
    //alert('onChange = ' + val);
});



function checklogin()
{
	if($("#Username").val()=="" || $("#Password").val()=="")
	{
		alert("Username or Password Blank Not Allowed");
		return false;
	}
		var Username = $("#Username").val();
		var Password = $("#Password").val();
		var language = $("#language").val();
		$.post("php/check_login.php",{Username:Username,Password:Password,language:language},function(data){
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
