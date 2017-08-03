<!--<script type="text/javascript" src="<?php echo base_url(); ?>rfid/jquery/jquery.dd.js"></script>
<link href="<?php echo base_url(); ?>rfid/style/css/dd.css" rel="stylesheet" type="text/css" />
-->
<?php $tim=time(); ?>
<script type="text/javascript">
	$(document).ready(function() {
	
		$(document).keypress(function(e) { 
			if (e.keyCode == 27) { 
				if($("#rfid_form_div").css("display") !="none" && $("#rfid_form_div").css("display") != undefined){
					con_asse_dis.dialog("open");
				}
			}   
		}); 
	$("#loading_top").css("display","none");
	jQuery("input:button, input:submit, input:reset").button();
	$("#inform_mobile").Mobile_Comma_Only();
	});
	
	</script>
<?php if($this->form_model->rfid == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create_RF_ID"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update_RF_ID"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div class="error" id="error_frm_M_rfid" style="display:none"></div>
<div class="error" id="error_frm_E_rfid" style="display:none"></div>
<div class="content toggle">

  <form id="frm_rfid" method="post" onSubmit="return submitFormrfid('<?php echo uri_assoc('id')?>')" action="<?php echo site_url($this->uri->uri_string()); ?>"  enctype="multipart/form-data">
    <!--<p id="error" class="addTips">* Fields are mendatory</p>-->
    <table width="100%" align="center" class="formtable">
      <tbody>
        <tr>
		  <td width="50%"><label><?php echo $this->lang->line('RF_ID'); ?>:*</label>
            <input type="text" name="rfid" id="rfid" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->rfid; ?>" /></td>
			<td width="50%"><label><?php echo $this->lang->line('Person'); ?>:*</label>
            <input type="text" name="person" id="person" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->person; ?>"/></td>
        </tr>
        <tr>
          <td><label><?php echo $this->lang->line('Assets'); ?>:*</label>
            <select name="asset_id" id="asset_id" class="select ui-widget-content ui-corner-all">
            
            <option value=""><?php echo $this->lang->line("Please Select"); ?></option>
		<?php echo $this->form_model->asset_id; ?>
            </select></td>
          <td><label><?php echo $this->lang->line('Mobile'); ?>:</label>
           <input type="text" name="inform_mobile" id="inform_mobile" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->inform_mobile; ?>"/>
        	</td>
		   
        </tr>
       <tr>
		<td><label><?php echo $this->lang->line('Email_Address'); ?>:</label>
            <input type="text" name="inform_email" id="inform_email" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->inform_email; ?>"/>
			</td>
			<td><label><?php echo $this->lang->line('Comments'); ?>:</label>
            <textarea name="comments" id="comments" class="text ui-widget-content ui-corner-all" ><?php echo $this->form_model->comments; ?></textarea>
			</td>
	   </tr>
	   <tr>
          <td><label><?php echo $this->lang->line("Landmark"); ?>:</label>
            <select name="landmark_id" id="landmark_id" class="select ui-widget-content ui-corner-all">
              
            <option value=""><?php echo $this->lang->line("Please Select"); ?></option> 
<?php echo $this->form_model->landmark_id; ?>
            </select></td>
          <td></td>
		   
        </tr>
	   <tr>
			<td width="50%"><br/><input type="checkbox" style="width:11%" name="send_sms" id="send_sms" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->send_sms) && $this->form_model->send_sms==1) || (!isset($this->form_model->send_sms)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Mobile_Alerts"); ?></label></td>
			<td width="50%"><br/><input type="checkbox" style="width:11%" name="send_email" id="send_email" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->send_email) && $this->form_model->send_email==1) || (!isset($this->form_model->send_email)))?"checked='checked'":""; ?> value=1 /><label><?php echo $this->lang->line("Send_Email_Alerts"); ?></label></td>
		</tr> 
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_rfid()" name="btn_cancel" value="<?php echo $this->lang->line('Back'); ?>" /></td>
		</tr>
      </tbody>
    </table>
  </form>
</div>
<div id="rfid_icon_dialog<?php echo $tim; ?>" class="assestimage_oad"></div>