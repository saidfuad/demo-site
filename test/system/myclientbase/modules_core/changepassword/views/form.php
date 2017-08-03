<script type="text/javascript">
$("#alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#alert_dialog").dialog("close")',5000);
		}
	});
function submitFormUsers_password_change(){
	$("#users_oldPass").val($.trim($("#users_oldPass").val()));
	$("#users_newPass").val($.trim($("#users_newPass").val()));
	$("#users_confPass").val($.trim($("#users_confPass").val()));
	$("#passLength").css({"display":"none"});
	var rtnBool=true;
	if($.trim($("#users_oldPass").val()) == "")
	{
		$("#old_pass_require").css({"display":"block"});
		rtnBool=false;
	}else{
		$("#old_pass_require").css({"display":"none"});
	}
	if($.trim($("#users_newPass").val()) == "")
	{
		$("#new_pass_require").css({"display":"block"});
		rtnBool=false;
	}else{
		$("#new_pass_require").css({"display":"none"});
	}
	if($.trim($("#users_confPass").val()) == "")
	{
		$("#confirm_pass_require").css({"display":"block"});
		rtnBool=false;
	}else{
		$("#confirm_pass_require").css({"display":"none"});
	}
	if($.trim($("#users_newPass").val()) != $.trim($("#users_confPass").val()))
	{
		$("#checkpass_new_match").css({"display":"block"});
		rtnBool=false;
	}else{
		$("#checkpass_new_match").css({"display":"none"});
	}	
	if(rtnBool==true){
		$("#old_pass_require").css("display","none");
		$("#new_pass_require").css("display","none");
		$("#confirm_pass_require").css("display","none");
		$("#checkpass_dupli").css("display","none");
		$("#checkpass_new_match").css("display","none");
		if($.trim($("#users_newPass").val()).length <= 5 || $.trim($("#users_confPass").val()).length <= 5){
			$("#passLength").css({"display":"block"});
			return false;
		}else{
			$("#passLength").css({"display":"none"});
			$("#loading_top").css("display","block");
			$.post("<?php echo base_url() ?>index.php/changepassword/change_password_submit",$("#change_password_user").serialize(),function(result){
				if(result.status == "0")
				{
					$("#checkpass_dupli").css({"display":"block"});
				}else if(result.status == "-1"){
					$("#checkpass_new_match").css("display","block");
				}else{
					$("#users_oldPass").val("");
					$("#users_newPass").val("");
					$("#users_confPass").val("");
					$("#alert_dialog").html("<?php echo $this->lang->line('Password_Change_Successfully'); ?>");
					$("#alert_dialog").dialog("open");
				}
				$("#loading_top").css("display","none");
			},'json');
		}
	}
	return false;
}

function resetFrm(){
	$("#users_oldPass").val("");
	$("#users_newPass").val("");
	$("#users_confPass").val("");
	$("#old_pass_require").css({"display":"none"});
	$("#new_pass_require").css({"display":"none"});
	$("#confirm_pass_require").css({"display":"none"});
	$("#checkpass_new_match").css({"display":"none"});
	$("#checkpass_dupli").css({"display":"none"});
	$("#passLength").css({"display":"none"});
}
</script>
<h3 class="title_black"><?php echo $this->lang->line('Changepassword'); ?></h3>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="old_pass_require" class="error" style="display:none"><?php echo $this->lang->line("Old Password Required"); ?></div>
<div id="alert_dialog" style="display:none;"></div>
<div id="new_pass_require" class="error" style="display:none"><?php echo $this->lang->line("New Password Required"); ?></div>
<div id="confirm_pass_require" class="error" style="display:none"><?php echo $this->lang->line("Confirm Password Required"); ?></div>
<div id="checkpass_dupli" class="error" style="display:none"><?php echo $this->lang->line("Old Password Does Not Match"); ?></div>
<div id="checkpass_new_match" class="error" style="display:none"><?php echo $this->lang->line("New & Confirm Password Does Not Match"); ?></div>
<div id="passLength" class="error" style="display:none"><?php echo $this->lang->line("Password Length"); ?></div>
<div class="content toggle" align="center">
  <form id="change_password_user" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormUsers_password_change()">
    <p id="error" class="addTips" color="#CC0000">* <?php echo $this->lang->line("Fields are mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>        
		<tr>
			<td width="35%" align='right'><?php echo $this->lang->line("Old Password"); ?> <font color="#CC0000">*</font>&nbsp;&nbsp;</td>
			<td width="65%"><input type="password" name="users_oldPass" id="users_oldPass" class="text ui-widget-content ui-corner-all"/></td>
		</tr>
		<tr>
			<td width="35%" align='right'><?php echo $this->lang->line("new_password"); ?><font color="#CC0000">*</font>&nbsp;&nbsp;</td> 
			<td width="65%"><input type="password" name="users_newPass" id="users_newPass" class="text ui-widget-content ui-corner-all"/></td>
		</tr>
		<tr>
			<td width="35%" align='right'><?php echo $this->lang->line("Confirm Password"); ?> <font color="#CC0000">*</font>&nbsp;&nbsp;</td> 
			<td width="65%"><input type="password" name="users_confPass" id="users_confPass" class="text ui-widget-content ui-corner-all"/></td>
		</tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_menu_submit" value="<?php echo $this->lang->line("submit"); ?>" name="btn_menu_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line("Reset"); ?>" onClick="resetFrm();"/></td>
        </tr>
		</tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#loading_top").css("display","none");
	jQuery("input:button, input:submit, input:reset").button();	
});
</script>
<script type="text/javascript">
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>