<script type="text/javascript">
	$(document).ready(function() {
		//$("#icon_id").msDropDown();
		
		$(document).keypress(function(e) { 
		if($('#address_book_group_form_div').css("display") != "none" && $('#address_book_group_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_assest_type_vtsloglog.dialog("open");
			}    
		}
	});
	});
	</script>
<?php if($this->form_model->group_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Address Group"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Address Group"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="address_book_group_error" class="error" style="display:none"><?php echo $this->lang->line("Address Group Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_address_book_group" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormaddress_book_group('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
          <label><?php echo $this->lang->line("Address Group Name"); ?>*</label>
         
            <input type="text" name="group_name" id="group_name_add" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->group_name; ?>" onblur="check_address_book_group(<?php echo uri_assoc('id')?>)"/></td>
        </tr>
         <tr>
          <td width="20%">
         <label> <?php echo $this->lang->line("Comments"); ?></label>
          
           <textarea name="comments" class="text ui-widget-content ui-corner-all"><?php echo $this->form_model->comments; ?></textarea>
		</td>
        </tr>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_address_book_group_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_address_book_group_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_address_book_group()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
//	$("#address_book_group_loading").dialog("close");
});

$("#loading_dialog").dialog("close");
$(".ddTitle").height(22);
$(".ddTitle img").height(22);
/*
var name = $("#name");
allFields = $([]).add(name);
tips = $("#error");*/
</script>