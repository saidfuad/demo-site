<?php if($this->form_model->id == ""){ ?> 
<h3 class="title_black">Add Assests Division </h3>
<?php }else{ ?>  
<h3 class="title_black">Update Assests Division </h3>
<?php } ?>

<?php $this->load->view("dashboard/system_messages"); ?>
<div class="content toggle" align="center">
  <form id="frm_assests_division_master" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormassests_division_mastermaster('<?php echo uri_assoc("id")?>')" enctype="multipart/form-data">
    <p id="error" class="addTips" color="#CC0000">* Fields are mandatory</p>
    <table width="80%" align="center" class="formtable">
      <tbody>
       	<tr>
			<td width="50%"><?php echo $this->lang->line('Assets Division'); ?><font color="#CC0000">*</font> </td>
			<td width="50%"><input type="text" name="division" id="division" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->division; ?>"  /></td>
		</tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_menu_submit" value="Submit" name="btn_menu_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_assests_division_master()" name="btn_cancel" value="Back" /></td> 
        </tr>
		</tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
});
</script>