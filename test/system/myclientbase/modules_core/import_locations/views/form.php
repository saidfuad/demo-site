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
	
});
</script>
<?php if($this->form_model->id == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Add Locations"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Locations"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="error_frm_lati" class="error" style="display:none"></div>
<div id="error_frm_longi" class="error" style="display:none"></div>
<div id="error_frm_address" class="error" style="display:none"></div>
<div id="error_frm_duplicate" class="error" style="display:none"></div>
<div class="content toggle" align="center">
  <form id="frm_import_locations" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormimport_locations('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="100%" class="formtable" style="padding: 0;margin-left: -40px;margin-top: -16px;">
      <tbody>
        <tr>
		<td width="50%">
			<label><?php echo $this->lang->line("Address"); ?></label>
            <textarea name="address" id="address_add" class="text ui-widget-content ui-corner-all" style="height:72px"><?php echo $this->form_model->address; ?></textarea></td>
		<td width="50%">
          <label><?php echo $this->lang->line("latitude"); ?>*</label>
            <input type="text" name="latitude" id="latitude" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->latitude; ?>"/>
          <label><?php echo $this->lang->line("longitude"); ?>*</label>
            <input type="text" name="longitude" id="longitude" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->longitude; ?>"/></td>
		</tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_address_book_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_address_book_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_import_locations()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	jQuery("input:button, input:submit, input:reset").button();
});
$("#loading_dialog").dialog("close");
$("#latitude").LatLongValidate();
$("#longitude").LatLongValidate();
</script>