<script>
jQuery().ready(function (){
$("#loading_top").css("display","none");
	jQuery("input:button, input:submit, input:reset").button();
	$("#frm_menu_setting_all").hide();
	$("#submit_usr").hide();
});
function getUsersMenu(uid){
	if(uid!=""){
		$("#loading_top").css("display","block");
		$.post("<?php echo site_url('user_display_setting/loadUser'); ?>/id/"+uid,function(data){
			if(data){
				$("#display_menu").html(data);
				$("#submit_usr").show();
				$("#user_id").val(uid);
			}
			$("#loading_top").css("display","none");
		});
	}else{
		$("#display_menu").html("");
		$("#submit_usr").hide();
		$("#user_id").val("");
	}
}
function submitFormUserMenuSettings(){	
	$.post("<?php echo site_url('user_display_setting/submitUser'); ?>",$("#frm_user_setting_all").serialize(),function(data){
		if(data){
			$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
			$("#select_display_menu option:first").attr('selected','selected');
			getUsersMenu("");
		}else{
			$("#alert_dialog").html('<?php echo $this->lang->line("Error Updating Record"); ?>');
		}
		$("#alert_dialog").dialog('open');
	});
	return false;
}
function checkAll_settings(rel){
	if(rel=='0'){
		if($("#users_check_all_"+rel).attr("checked")){
			$("input:checkbox").attr("checked","checked");
		}else{
			$("input:checkbox").removeAttr( "checked");
		}
	}
	if($("#users_check_all_"+rel).attr("checked")){
		$("#tbl_"+rel+" input[rel="+ rel+"]").attr("checked","checked");

	}else{
		$("#tbl_"+rel+" input[rel="+ rel+"]").removeAttr( "checked");
	}
}
function checkAll_with_rel(rel){
	"show_dash_combo"
	"show_dash_combo;141"
	"show_dash_combo;142"
	if($("#tbl_0 input[rel="+rel+"]").attr("checked")){
		$("input:checkbox[placeholder='"+rel+"']").attr("checked","checked");
	}else{
		$("input:checkbox[placeholder='"+rel+"']").removeAttr( "checked");
	}

}
</script>
<div class="content toggle">
	<form id="frm_user_setting_all" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormUserMenuSettings()">
	<table border=1 width='100%' id='one_user_settings'>
		<tbody>
		<tr>
		<td><?php echo $this->lang->line("Select User"); ?></td>
		</tr>
		<tr><td>
		<select id="select_display_menu" class="select ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 94%;" onChange="getUsersMenu(this.value)">
			<option value=''><?php echo $this->lang->line("Please Select"); ?></option><?php echo $Users_combo; ?>
		</select><input type='hidden' value="" id="user_id" name="user_id"/>
		</td></tr>
		<tr><td id="display_menu"></td></tr>
		<tr><td id="submit_usr" align="center"><input type="submit" value="<?php echo $this->lang->line("update"); ?>" style="margin-top:15px"></td></tr>
		</tbody>
	</table>
	</form>	
</div>
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