<?php
		$option = "";
		$landmark_name = "";
		$groupnameid=uri_assoc('id');
		$user = $this->session->userdata('user_id');
			$SQL="select name from landmark where group_id = 0 AND add_uid = $user";
			$result=mysql_query($SQL);
			while($row=mysql_fetch_array($result))
			{
				$option .="<input type='checkbox' name='landmark_name[]'  value='".$row['name']."'>".$row['name']."<br>";
			}

		if(uri_assoc('id') == ""){
		}else{
		
		
		$SQL_landmarkname = "select name from landmark,landmark_group as lg where group_id = $groupnameid AND user_id = $user group by name";
		$resultlandmark=mysql_query($SQL_landmarkname);
		while($rowlandmark_name=mysql_fetch_array($resultlandmark))
		{
			$landmark_name .="<input type='checkbox' name='landmark_name[]'  value='".$rowlandmark_name['name']."' checked='checked'>".$rowlandmark_name['name']."<br>";
		}
		}
?>
<script type="text/javascript">
	$(document).ready(function() {
		//$("#icon_id").msDropDown();
		$(document).keypress(function(e) { 
		if($('#landmark_group_form_div').css("display") != "none" && $('#landmark_group_form_div').css("display") != undefined){
			if (e.keyCode == 27) { 
				conf_dialog_landmark_group_lokkup.dialog("open");
			}    
		}
	}); 
	$("#loading_top").css("display","none");
	});
	</script>
<?php if($this->landmark_form_model->landmark_group_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create_Landmark_Group"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update_Landmark_Group"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="landark_group_error" class="error" style="display:none"><?php echo $this->lang->line("Landmark_Group_Already_Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_landmark_group" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormassets_type('<?php echo uri_assoc('id')?>');" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
         <?php echo $this->lang->line("Landmark_Group_Name"); ?>*</td>
          <td width="50%">
            <input type="text" name="landmark_group_name" id="landmark_group_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->landmark_form_model->landmark_group_name; ?>" <?php /*onblur="check_landmark_name(<?php echo uri_assoc('id')?>)" */?> /></td>
        </tr>
	<?php	
	if($this->landmark_form_model->landmark_group_name == "")
	{ ?>
		<tr>
			<td><?php
				if(!empty($option))
				{
			?>
					<?php echo $this->lang->line("Landmark_Name"); ?>:
			<?php
				}
			?>	
				</td>
				<td>	
					<?php
						echo $option;
					?>
			</td>
		</tr>
	<?php
		}else{
			?>
				
				<tr>
				<td><?php
				if(!empty($landmark_name))
				{
			?>
					<?php echo $this->lang->line("Landmark_Name"); ?>:
			<?php
				}
			?>	
				</td>
				<td>
					<?php
						echo $landmark_name;
						echo $option;
					?>
				</td>
				</tr>
			<?php		
		}
	?>
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_landmark_group_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_landmark_group_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_landmark_group_cancle" onclick="cancel_landmark_name();" name="btn_landmark_group_cancle" value="<?php echo $this->lang->line("Back"); ?>" /></td>
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
$("#loading_dialog").dialog("close");
$(".ddTitle").height(22);
$(".ddTitle img").height(22);
/*
var name = $("#name");
allFields = $([]).add(name);
tips = $("#error");*/
</script>