<script type="text/javascript">
	$(document).ready(function() {
	
	$(document).keypress(function(e) {
			if (e.keyCode == 27) {
			if($('#assets_category_form_div').css("display") != "none" &&  $('#assets_category_form_div').css("display") != undefined){			
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

<?php if($this->form_model->assets_cat_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Assets Category"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Assets Category"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="assets_category_error" class="error" style="display:none"><?php echo $this->lang->line("Assets category Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_assets_category" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormAssetsCategory('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Asset Category Name"); ?>*</td>
          <td width="50%">
            <input type="text" name="assets_cat_name" id="assets_cat_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->assets_cat_name; ?>" onblur="check_assets_category(<?php echo uri_assoc('id')?>)"/></td>
        </tr>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Asset Category Type"); ?>*<?php echo $this->form_model->assets_type_id; ?></td>
          <td width="50%">
            <select name="assets_type_id" id="assets_type_id" class="text ui-widget-content ui-corner-all">
            <?php if(!$this->form_model->assets_type_id){ ?>
            <option value="">Please Select</option> <?php }
			foreach($typecombo as $rs)
			{?>
				<option value="<?php echo $rs['id']; ?>" <?php echo ($rs['id']==$this->form_model->assets_type_id)?"selected=selected":""; ?> ><?php echo $rs['name'];?></option>
			<?php }
            ?>
            </select></td>
        </tr>
		<tr>
          <td width="20%">
          <?php echo $this->lang->line("Asset Category Image"); ?>*<?php echo $this->form_model->assets_cat; ?></td>
		<td width="50%">
            <select name="assets_cat_image" id="assets_cat_image" class="text ui-widget-content ui-corner-all">
            <?php 
				$query = $this->db->query("SELECT * from assests_category_images where del_date is null and status = 1", FALSE);
				$rows = $query->result();
				$images = '';
				$sel = '';
				if(count($rows) > 0) {
					foreach ($rows as $row) {
						if($row->id == $this->form_model->assets_cat_image)
							$sel = 'selected=selected';
						else
							$sel = '';
							
						$images .= '<option title="'.base_url().'assets/'.$row->image_path.'" value="'.$row->image_path.'" '.$sel.'></option>';
					}
				}
				else {
					$images .= '<option title="" value="">No Category Image</option>';
				}
				echo $images;
			?>
			</select>
			</td>
        </tr>
         <tr>
          <td width="20%">
          <?php echo $this->lang->line("Status"); ?></td>
          <td width="50%">
          <select name="assets_status" id="assets_status" class="text ui-widget-content ui-corner-all">
           <option value="1" <?php echo ($this->form_model->assets_status==1)?"selected=selected":""; ?>>Active</option>        
            <option value="0" <?php echo ($this->form_model->assets_status==0)?"selected=selected":""; ?>>Inactive</option>		 	 </select>
        </td> 
        </tr>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_assets_category_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_assets_category_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_assets_category()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#assets_cat_image").msDropDown();
//	$("#assets_type_loading").dialog("close");
		

});

$(".ddTitle").height(22);
$(".ddTitle img").height(22);
/*
var name = $("#name");
allFields = $([]).add(name);
tips = $("#error");*/
</script>