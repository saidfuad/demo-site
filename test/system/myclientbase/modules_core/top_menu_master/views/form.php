<?php if($this->form_model->menu_nm == ""){ ?> 
<h3 class="title_black"><?php echo $this->lang->line("Update User Top Menu"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="menu_error" class="error" style="display:none"><?php echo $this->lang->line("Menu Already Exist"); ?>.!</div>
<div class="content toggle" align="center"> 
	<form id="frm_top_menu_master" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormuser_top_menu_group('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
		<tr>
			<td><?php echo $this->lang->line("User Name"); ?></td>
			<td>
				<input type="text" name="usrNametxt" id="usrNametxt" class="text ui-widget-content ui-corner-all" readonly />
			</td>
		</tr>
		<tr>
				<?php
					$SQL = "select Text from top_main_menu_master where id =". $this->form_model->menu_id;
			
					$query = $this->db->query($SQL);
					foreach($query->result() as $row1)
					{
					
					//$row->{$row1->menu_name} = $row1->menu_name;
						$row->Text  = $row1->Text;
					}
				?>
				<td>
					<input type="hidden" value="<?php echo  $row->Text; ?>" name="menu_name" id="menu_name">
				</td>
				<td>
					<input type="hidden" value="<?php echo  $this->form_model->id ; ?>" name="menu_id" id="menu_id">
				</td>
		</tr> 
		<tr>
			<?php
				if($this->form_model->status == 1)
				{ ?>
				<td><?php echo $this->lang->line("Disable Menu"); ?> : </td>
				<td>
					<input type="checkbox" name="menu_status" id="menu_status" checked="yes" style="margin-left:50px"> <?php echo  $row->Text; ?> 
				</td>
				<?php  }else{ ?>
				<td><?php echo $this->lang->line("Enable Menu"); ?> : </td>
				<td>
					<input type="checkbox" name="menu_status" id="menu_status" style="margin-left:50px"> <?php echo  $row->Text; ?>
				</td>
				<?php } ?>
		</tr>
		<tr> 
			<td> 
				<input type="submit" id="btn_menu_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_menu_submit"/>
			</td> 
			<td>
				<input type="button" id="btn_cancel" onclick="cancel_top_menu_master()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" />
			</td> 
		</tr> 
      </tbody>
    </table> 
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
//	$("#loading_dialog").dialog("close");
$("#loading_dialog").dialog("close");
	$("#usrNametxt").val($("#user_name option:selected").text());
});

</script>