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
		//$("#icon_id").msDropDown();
		
		$(document).keypress(function(e) { 
		if($('#driver_master_form_div').css("display") != "none" && $('#driver_master_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_type_vtsloglog.dialog("open");
			}    
		}
	});
	});
	</script>
<?php if($this->form_model->driver_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Driver"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Driver"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="error_frm_address" class="error" style="display:none"></div>
<div class="content toggle" align="center">
  <form id="frm_driver_master" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormdriver_master('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="100%" class="formtable" style="padding: 0;margin-left: -40px;margin-top: -16px;">
      <tbody>
        <tr>
		<td width="50%">
          <label><?php echo $this->lang->line("Driver Name"); ?>*</label>
            <input type="text" name="driver_name" id="name_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->driver_name; ?>"/></td>
		<td width="50%">
          <label><?php echo $this->lang->line("Driver Code"); ?>*</label>
            <input type="text" name="driver_code" id="driver_code" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->driver_code; ?>"/></td>
		 </tr>
			<tr>
          <td width="50%">
			<label><?php echo $this->lang->line("Address"); ?></label>
            <textarea name="address" id="address_add" class="text ui-widget-content ui-corner-all" style="height:72px"><?php echo $this->form_model->address; ?></textarea></td>
			
          <td width="50%">
          <label><?php echo $this->lang->line("mobile_number"); ?></label>
            <input type="text" name="mobile_no" id="mobile_number_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->mobile_no; ?>"/><br/></td>
			</tr>
			<tr>
			<td>
          <label><?php echo $this->lang->line("email"); ?></label>
            <input type="text" name="email" id="email_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->email; ?>"/></td>
        </tr>
		
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_driver_master_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_driver_master_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_driver_master()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();
	$("#mobile_number_add").Mobile_Comma_Only();
	$("#driver_code").Mobile_Comma_Only();
});
$("#loading_dialog").dialog("close");

</script>