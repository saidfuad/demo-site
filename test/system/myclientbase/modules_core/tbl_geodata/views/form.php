<script type="text/javascript">
	$(document).ready(function() {
				
		$(document).keypress(function(e) { 
		if($('#tbl_geodata_form_div').css("display") != "none" && $('#tbl_geodata_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_type_vtsloglog.dialog("open");
			}    
		}
	});
	});
	</script>
<?php if($this->form_model->id == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Geo Data"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Geo Data"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="tbl_geodata_error" class="error" style="display:none">Geofence Type Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_tbl_geodata" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormtbl_geodata('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
			<label><?php echo $this->lang->line("Cell"); ?></label>
			<input type="text" name="cell_id" id="cell_id" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->cell_id; ?>" /></td>
		  <td width="20%">
			<label><?php echo $this->lang->line("Lac"); ?></label>
			<input type="text" name="lac" id="lac" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->lac; ?>" /></td>
        </tr>
		<tr>
          <td width="20%">
			<label>Latitude *</label>
			<input type="text" name="latitude" id="latitude" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->latitude; ?>" /></td>
		  <td width="20%">
			<label>Longitude *</label>
			<input type="text" name="longitude" id="longitude" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->longitude; ?>" /></td>
        </tr>
		<tr>
          <td width="40%" colspan="2">
			<label><?php echo $this->lang->line("Address"); ?></label>
			<textarea name="address" id="address" class="textarea text ui-widget-content ui-corner-all" ><?php echo $this->form_model->address; ?></textarea></td>
		</tr>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_tbl_geodata_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_tbl_geodata_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_tbl_geodata()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
});

$("#loading_dialog").dialog("close");

</script>