<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
?>		
		<?php if($this->form_model->user_id == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create Payment"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update Payment"); ?></h3>
		<?php } ?>
		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_payment_frm" class="error" style="display:none"></div>
		<div class="content toggle">
			<form id="frm_payment" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormPayment('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("User"); ?> : </label><select name="user_id" id="user_id" class="select ui-widget-content ui-corner-all"><?php echo $this->form_model->users; ?></select>
						</td>
						<td width="50%"><label><?php echo $this->lang->line("Payment Type"); ?> : </label><select name="payment_type" id="payment_type" class="select ui-widget-content ui-corner-all"><option value="Cash" <?php if($this->form_model->payment_type == "Cash") echo "selected='selected'"; ?>>Cash</option><option value="Cheque" <?php if($this->form_model->payment_type == "Cheque") echo "selected='selected'"; ?>>Cheque</option></select>
						</td>
					</tr>
					<tr>
						<td width="50%"><label><?php echo $this->lang->line("Payment For"); ?> : </label><select name="payment_for" id="payment_for" class="select ui-widget-content ui-corner-all"><option value="Server charges" <?php if($this->form_model->payment_for == "Server charges") echo "selected='selected'"; ?>>Server charges</option><option value="Sms Balance" <?php if($this->form_model->payment_for == "Sms Balance") echo "selected='selected'"; ?>>Sms Balance</option><option value="Installation" <?php if($this->form_model->payment_for == "Installation") echo "selected='selected'"; ?>>Installation</option></select></td>
						<td width="50%"><label>Amount</label><input type="text" name="amount" id="amount" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->amount; ?>" /></td>
					</tr>
					<tr class="cheque_fields">
						
						<td width="50%"><label><?php echo $this->lang->line("Cheque Number"); ?></label><input type="text" name="cheque_number" id="cheque_number" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->cheque_number; ?>" /></td>
						<td width="50%"><label><?php echo $this->lang->line("Cheque Date"); ?></label><input type="text" name="cheque_date" id="cheque_date" class="date text ui-widget-content ui-corner-all" value="<?php if($this->form_model->cheque_date != "") echo date($date_format, strtotime($this->form_model->cheque_date)); ?>" /></td>
					</tr>
					<tr>
						
						<td width="50%" class="cheque_fields"><label><?php echo $this->lang->line("Cheque Bank Name"); ?></label><input type="text" name="cheque_bank_name" id="cheque_bank_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->cheque_bank_name; ?>" /></td>
						<td width="50%"><label><?php echo $this->lang->line("Comments"); ?></label><textarea name="comments" id="comments" class="date textarea ui-widget-content ui-corner-all"><?php echo $this->form_model->comments; ?></textarea></td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_payment()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>
					
				</tbody>
			</table>
			</form>

		</div>

<script type="text/javascript">
$(document).ready(function() {
		
		$(document).keypress(function(e) { 
		if($('#payment_form_div').css("display") != "none" && $('#payment_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_payment_var_bitbot.dialog("open");
			}    
		}
	}); 
	jQuery(".date").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	$("#amount").DecimalOnly();
	jQuery("input:button, input:submit, input:reset").button();	
	$("#loading_top").css("display","none");
	
	$("#payment_type").change(function(){
		if($(this).val() == "Cheque"){
			$(".cheque_fields").show();
		}else{
			$(".cheque_fields").hide();
		}
	});
	if('<?php echo $this->form_model->payment_type; ?>' == 'Cheque'){
		$(".cheque_fields").show();
	}else{
		$(".cheque_fields").hide();
	}
});
	


</script>