<style type="text/css">
table{
	width: 598px; 
	height: 10px;
	margin-left: -40px;
	margin-top: -16px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$(document).keypress(function(e) { 
		if($('#address_book_form_div').css("display") != "none" && $('#address_book_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_type_vtsloglog.dialog("open");
			}
		}
	});
});
</script>
<?php if($this->form_model->group_id == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Address Book"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Address Book"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="address_book_error" class="error" style="display:none"><?php echo $this->lang->line("Address Already Exist"); ?>.!</div>
<div id="error_frm_address" class="error" style="display:none"></div>
<div class="content toggle" align="center">
  <form id="frm_address_book" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormaddress_book('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="100%" class="formtable" style="padding: 0;margin-left: -40px;margin-top: -16px;">
      <tbody>
        <tr>
		<td width="50%">
          <label><?php echo $this->lang->line("Name"); ?>*</label>
            <input type="text" name="name" id="name_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->name; ?>"/></td>
			 
			 <td width="50%">
          <label><?php echo $this->lang->line("group_name"); ?></label>
			<select name="group_id" id="group_id" class="select ui-widget-content ui-corner-all" >
			<?php echo $this->form_model->group_id; ?>
			</select></td>
		 </tr>
  		<tr>
          <td width="50%">
			<label><?php echo $this->lang->line("Address"); ?></label>
            <textarea name="address" id="address_add" class="text ui-widget-content ui-corner-all" style="height:72px"><?php echo $this->form_model->address; ?></textarea></td>
          <td width="50%">
          <label><?php echo $this->lang->line("mobile_number"); ?>*</label>
            <input type="text" name="mobile_no" id="mobile_number_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->mobile_no; ?>"/><br/>
          <label><?php echo $this->lang->line("email"); ?></label>
            <input type="text" name="email" id="email_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->email; ?>"/></td>
        </tr>
		<tr>
			<td width="50%"><br/><input type="checkbox" style="width:11%" name="send_sms" id="send_sms" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->send_sms) && $this->form_model->send_sms==1) || (!isset($this->form_model->send_sms)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Mobile_Alerts"); ?></label></td>
			<td width="50%"><br/><input type="checkbox" style="width:11%" name="send_email" id="send_email" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->send_email) && $this->form_model->send_email==1) || (!isset($this->form_model->send_email)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Email_Alerts"); ?></label></td>         
		</tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_address_book_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_address_book_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_address_book()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
	$("#mobile_number_add").Mobile_Comma_Only();
});

$("#loading_dialog").dialog("close");
$(".ddTitle").height(22);
$(".ddTitle img").height(22);

</script>