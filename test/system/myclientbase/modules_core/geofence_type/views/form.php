<script type="text/javascript">
	$(document).ready(function() {
				
		$(document).keypress(function(e) { 
		if($('#geofence_type_form_div').css("display") != "none" && $('#geofence_type_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_type_vtsloglog.dialog("open");
			}    
		}
	});
	});
	</script>
<?php if($this->form_model->type == ""){ ?>
<h3 class="title_black">Create Geofence Type</h3>
<?php }else{ ?>
<h3 class="title_black">Update Geofence Type</h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="geofence_type_error" class="error" style="display:none">Geofence Type Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_geofence_type" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormgeofence_type('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
          <label>Geofence Type*</label>
         
            <input type="text" name="type" id="type" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->type; ?>" onblur="check_geofence_type(<?php echo uri_assoc('id')?>)"/></td>
        </tr>
         <tr>
          <td width="20%">
         <label> <?php echo $this->lang->line("Comments"); ?></label>
          
           <textarea name="comments" class="text ui-widget-content ui-corner-all"><?php echo $this->form_model->comments; ?></textarea>
		</td>
        </tr>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_geofence_type_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_geofence_type_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_geofence_type()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
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