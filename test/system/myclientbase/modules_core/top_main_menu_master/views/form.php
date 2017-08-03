<script>
	
</script>
<?php if($this->form_model->Text == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Add Top Main Menu Master"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Top Main Menu Master"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="text_type_error" class="error" style="display:none"><?php echo $this->lang->line("Text Type Already Exist"); ?>.!</div>
<div id="link_type_error" class="error" style="display:none"><?php echo $this->lang->line("Link Already Exist"); ?>.!</div>
<div class="content toggle">
  <form id="frm_top_main_menu_master" method="post" onSubmit="return submitFormtop_main_menu_master('<?php echo uri_assoc('id')?>')" action="<?php echo site_url($this->uri->uri_string()); ?>"  enctype="multipart/form-data">
    <!--<p id="error" class="addTips">* Fields are mendatory</p>-->
    <table width="100%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="50%"><label><?php echo $this->lang->line("Text"); ?> *</label>
            <input type="text" name="Text" id="Text" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->Text; ?>" /></td>
          <td width="50%"><label><?php echo $this->lang->line("Link"); ?> *</label>
            <input type="text" name="link" id="link" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->link; ?>" /></td>
        </tr>
      
           <tr>

         
  <td><label><?php echo $this->lang->line("Comments"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" name="comments" id="comments" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->comments; ?>"/></td>
        </tr>   
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_top_main_menu_master()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
	<tr>
			<td>
			
			</td>
		</tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
$("#loading_dialog").dialog("close");
	jQuery("input:button, input:submit, input:reset").button();	

});

$(".ddTitle").height(22);
$(".ddTitle img").height(22);


/*
var name = $("#name");
allFields = $([]).add(name);
tips = $("#error");*/
</script>