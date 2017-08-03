<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
?>

<style>
#ui_tpicker_hour_label_from_date,#ui_tpicker_hour_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_minute_label_from_date,#ui_tpicker_minute_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_second_label_from_date,#ui_tpicker_second_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

.dropdown-panel {
	padding:10px 20px;
}
.dropdown-panel li {
	margin:10px 0;
}
.dropdown-panel a {
	cursor:pointer;
	background:lightblue;
	border-radius:4px;
	padding:2px 4px;
	margin:4px 0;
}
</style>
<script type="text/javascript">
		$(document).ready(function() {
			jQuery(".date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
			jQuery("input:button, input:submit, input:reset").button();
			$("#loading_top").css("display","none");
			$("#btn_submit").click(function(){
				if($("#mobile_number").val() != ""){
					var emails=$("#mobile_number").val();
					var em=emails.split(/[;,]+/);
					for(i=0;i<em.length;i++)
					{
						if(em[i].length == 10)
						{
							$("#error_frm").hide();
						}else{
							$("#error_frm").show();
							$("#error_frm").html("<?php echo $this->lang->line("Mobile_Number_Formate_is_Not_Valid"); ?>");
							return false;
						}
					}
				}
			});
		});

		$("#conf_dia").dialog({
			   autoOpen: false,
			   modal: true,
			   buttons : {
					"Confirm" : function() {
						$(this).dialog("close");
						submitFormCopyUsers();
					},
					"Cancel" : function() {
					  $(this).dialog("close");
					}
				  }
				});
		function confirm_copy(){
			$("#conf_dia").dialog("open");
		}
		
		function exceptme(val){
			if(val != 0)
			{
				$("#loading_top").css("display","block");
				$.post(	
					"<?php echo site_url('users/usersExceptMe/id');  ?>/"+val,
					function(data){
						$('#secondary_user').html(data);
						$("#loading_top").css("display","none");
					});
			}else{
				$('#secondary_user').html('');
			}
		}
		
		function submitFormCopyUsers(id){
			$("#loading_top").css("display","block");
			if($("#secondary_user option:selected").text() == "" || $("#secondary_user option:selected").text() == "null"){
				$("#error_frm").show();
				$("#error_frm").html("<?php echo "Please Select Secondary User"; ?>");
				return false;
			}
				
			$.post("<?php echo site_url('users/AddCopyUsers'); ?>", $("#frm_Copyusers").serialize(), 
				function(data){
					if($.trim(data)){
						$('#users_form_div').html(data);
					}else{
						$("#error_frm").hide();
						$("#error_frm").html("");
						$('#secondary_user').html('');
						$('#frm_Copyusers')[0].reset();
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
						$("#alert_dialog").dialog('open');
					}
					$("#loading_top").css("display","none");
				});
		}
		
		function cancel_users(id){
			$("#error_frm").hide();
			$("#error_frm").html("");
			$('#secondary_user').html('');
			$('#frm_Copyusers')[0].reset();
		}
		</script>

		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_Copyusers" method="post" action="" onsubmit="return true')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"> <label><?php echo $this->lang->line("username"); ?>&nbsp;&nbsp;</label>
						<select name="primary_user" id="primary_user" class="select ui-widget-content ui-corner-all" onchange="exceptme(this.value);">
						<option value="">Please Select</option>
							<?php echo $users; ?>
						</select>
						<td width="50%"><label><?php echo $this->lang->line("username"); ?></label>
						<select name="secondary_user[]" id="secondary_user" multiple="multiple" class="select ui-widget-content ui-corner-all">
							
						</select>
						</td>
					</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="button" id="btn_cancel" onclick="confirm_copy()" name="btn_cancel" value="Submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel1" onclick="cancel_users()" name="btn_cancel1" value="<?php echo $this->lang->line("Reset"); ?>" /></td>
					</tr>					
				</tbody>
			</table>
			</form>
		</div>
		<div id="conf_dia" title="Confirmation Required" style="display:none">
  Are You Sure Want To Copy User Data?
</div>
