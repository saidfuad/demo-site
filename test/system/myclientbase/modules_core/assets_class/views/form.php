<script type="text/javascript">
	$(document).ready(function() {
	
	$(document).keypress(function(e) {
			if (e.keyCode == 27) {
			if($('#assets_class_form_div').css("display") != "none" &&  $('#assets_class_form_div').css("display") != undefined){			
				conf_dialog_assests_catagory_assets_dopost.dialog("open");
				}
		}
	}); 
		//$("#icon_id").msDropDown();
		
		$("#loading_top").css("display","none");
			
	});
	
	</script>
<script type="text/javascript">
loadDropdown()
</script>

<?php if($this->form_model->assets_class_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Assets Class"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Assets Class"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="assets_category_error" class="error" style="display:none"><?php echo $this->lang->line("Assets class Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_assets_class" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormAssetsClass('<?php echo uri_assoc('id')?>')">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Asset Class Name"); ?>*</td>
          <td width="50%">
            <input type="text" name="assets_class_name" id="assets_class_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->assets_class_name; ?>" onblur="check_assets_class(<?php echo uri_assoc('id')?>)"/></td>
        </tr>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_assets_category_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_assets_category_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_assets_class()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();
//	$("#assets_type_loading").dialog("close");
		

});
</script>