<script type="text/javascript">
	$(document).ready(function() {
	
		$("#loading_top").css("display","none");
			
	});
	
</script>

<?php if($this->form_model->command == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Assets Command"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Assets Command"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="assets_category_error" class="error" style="display:none"><?php echo $this->lang->line("Assets command Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_assets_command" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormAssetsCommand('<?php echo uri_assoc('id')?>')">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Assets Command"); ?>*</td>
          <td width="50%">
            <input type="text" name="command" id="command" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->command; ?>" onblur="check_assets_command(<?php echo uri_assoc('id')?>)"/></td>
        </tr>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Assets Class"); ?>*<?php echo $this->form_model->assets_class_id; ?></td>
          <td width="50%">
            <select name="assets_class_id" id="assets_class_id" class="select ui-widget-content ui-corner-all">
            <?php if(!$this->form_model->assets_class_id){ ?>
            <option value=""><?php echo $this->lang->line("Please Select");?></option>
            <?php }
			foreach($classcombo as $rs) { ?>
				<option value="<?php echo $rs['id']; ?>" <?php echo ($rs['id']==$this->form_model->assets_class_id)?"selected=selected":""; ?> ><?php echo $rs['name'];?></option>
			<?php } ?>
            </select></td>
        </tr>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_assets_command_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_assets_command_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_assets_command()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
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