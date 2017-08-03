<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
?>
<script type="text/javascript">
		$(document).ready(function() {
		jQuery("input:button, input:submit, input:reset").button();
		
		});
		</script>
		
			
		
		<?php if($this->form_model->profile_name == ""){ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Create_User_profile"); ?></h3>
		<?php }else{ ?>
		<h3 class="title_black"><?php echo $this->lang->line("Update_User_profiles"); ?></h3>
		<?php } ?>

		<?php $this->load->view('dashboard/system_messages'); ?>
		<div id="error_frm" class="error" style="display:none"></div>
		<div class="content toggle">
		<form id="frm_user_profile" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormUser_profile('<?php echo uri_assoc('id')?>')">
			<!--<p id="error" class="addTips">* Fields are mendatory</p>-->
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr>
						<input type='hidden' value='<?php echo uri_assoc('copy_id'); ?>' id='hidden_copy_id' />
						<td width="50%"><label><?php echo $this->lang->line("profile_name"); ?> *</label><input type="text" name="profile_name" id="profile_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->profile_name; ?>" /></td>
						
						<td width="50%"><label><?php echo $this->lang->line("Charges(Per Day)"); ?>*</label><input type="text" name="charges_per_day" id="charges_per_day" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->charges_per_day; ?>" /></td>
						
					</tr>
					<tr>
						<td>
							<label><?php echo $this->lang->line("Status"); ?> *</label><select name="status" id="status" style="width:98% !important"  class="select ui-widget-content ui-corner-all" >
							<option value='1' <?php if($this->form_model->status==1) echo "selected='selected'"; ?>><?php echo $this->lang->line("Active"); ?></option>
							<option value='0' <?php if($this->form_model->status==0) echo "selected='selected'"; ?>><?php echo $this->lang->line("Inactive"); ?></option>
							</select>
						</td>
						<td width="50%"><label><?php echo $this->lang->line("profile_desc"); ?> </label><textarea name="profile_desc" id="profile_desc" class="text ui-widget-content ui-corner-all"  style='resize:none;height:75px' ><?php echo $this->form_model->profile_desc; ?></textarea></td>
					</tr>
					<Tr>
						<?php  
							$values=array();
							if($this->form_model->profile_name != ""){ 
								$query ="select menu_id,setting_name from mst_user_profile_setting where del_date is null and status=1 and add_uid = ".$this->session->userdata('user_id')." and  profile_id=".$this->form_model->id;
								$res = $this->db->query($query);
								foreach($res->result_Array() as $row){
									if($row['setting_name']!='main')
										$value=$row['menu_id']."_".$row['setting_name'];
									else
										$value=$row['menu_id'];
									$values[]=$value;
									
								}
							} ?>

						<td colspan="2">
							<label><?php echo $this->lang->line("menu"); ?> *</label>
							<div style="width:98%;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
								<table width="100%"> 
							<?php
							if($this->session->userdata('usertype_id')=='1')
							{
								$res = $this->db->query("select id,menu_name,sub_settings from  main_menu_master where del_date is null and status=1 and type=0 and parent_menu_id is not null and id != 12 and id != 24 order by parent_menu_id,priority");
							}
							else
							{
								$pid = $this->session->userdata('profile_id');
								$pres = $this->db->query("select concat(menu_id) as menu_id from  mst_user_profile_setting where del_date is null and status=1 and setting_name='main' and profile_id=".$pid);
								$PIds="";
								foreach($pres->result_Array() as $row1){
									$PIds.=$row1['menu_id'].",";
								}
								$PIds=trim($PIds,',');					
								$res = $this->db->query("select id,menu_name,sub_settings from  main_menu_master where del_date is null and status=1 and type=0 and parent_menu_id is not null and id != 12 and id != 24 and id in ($PIds) order by parent_menu_id,priority");
							}
								foreach($res->result_Array() as $row){
									echo "<tr><Td style='padding-left:5px;width:24px;padding-top:0px'><input type='checkbox' name='menu_setting[]' value='".$row['id']."' ";
									if(in_array($row['id'],$values)){
										echo " checked='checked' ";
									}
									if(trim($row['sub_settings'])!=""){
										echo " onclick='if($(this).is(\":Checked\")==true){ $(\"#row_".$row['id']."\").show(); $(\"#row_".$row['id']." input:checkbox\").attr(\"checked\",\"checked\"); }else{ $(\"#row_".$row['id']."\").hide();   $(\"#row_".$row['id']." input:checkbox\").removeAttr(\"checked\");}' ";
									}
									
									echo "></td><td style='padding-top:0px'>";
									echo $this->lang->line($row['menu_name']);
									echo "</td></tr>";

									if(trim($row['sub_settings'])!=""){
										echo "<Tr style='background:lightsteelblue;";
										if(!in_array($row['id'],$values)){
											echo "display:none;";
										}
										echo " ' id='row_".$row['id']."' ><td colspan='2' style='padding-left:34px;padding-top:0px'><table width='90%'>";
										$sub = explode(",",trim($row['sub_settings']));
										for($i=0;$i<count($sub);$i=$i+4){
											echo "<Tr><Td style='padding-top:0px'>";
											if(isset($sub[$i])){
												echo "<input type='checkbox'  name='menu_setting[]' value='".$row['id']."_".$sub[$i]."'";
												if(in_array($row['id']."_".$sub[$i],$values)){
													echo " checked='checked' ";
												}
												echo " > ";
												echo $this->lang->line($sub[$i]);
														
											}
											
											echo "</td><td  style='padding-top:0px'>";
											if(isset($sub[$i+1])){
												echo "<input type='checkbox'  name='menu_setting[]' value='".$row['id']."_".$sub[$i+1]."' ";
												if(in_array($row['id']."_".$sub[$i+1],$values)){
													echo " checked='checked' ";
												}
												echo " > ";
												echo $this->lang->line($sub[$i+1]);
											}
											echo "</td><td  style='padding-top:0px'>";
											if(isset($sub[$i+2])){
												echo "<input type='checkbox'  name='menu_setting[]' value='".$row['id']."_".$sub[$i+2]."'";
												if(in_array($row['id']."_".$sub[$i+2],$values)){
													echo " checked='checked' ";
												}
												echo "> ";
												echo $this->lang->line($sub[$i+2]);

											}
											echo "</td><td  style='padding-top:0px'>";
											if(isset($sub[$i+3])){
												echo "<input type='checkbox'  name='menu_setting[]' value='".$row['id']."_".$sub[$i+3]."'";
												if(in_array($row['id']."_".$sub[$i+3],$values)){
													echo " checked='checked' ";
												}
												echo "> ";
												echo $this->lang->line($sub[$i+3]);

											}
											echo "</td></tr>";
										}
										echo "</table></td></tr>";
									}
								}
							
							?>
								</table>
							</div>
						</td>
					</tr>
						<Tr>
						<td colspan="2">
							<label><?php echo $this->lang->line("reports"); ?> *</label>
							<div style="width:98%;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
								<table width="100%"> 
							<?php
							
							if($this->session->userdata('usertype_id')=='1')
							{
								$res = $this->db->query("select id,menu_name,sub_settings from  main_menu_master where del_date is null and status=1 and type=1 and parent_menu_id is not null and id != 12 and id != 24 order by parent_menu_id,priority");
							}
							else
							{
								$pid = $this->session->userdata('profile_id');
								$pres = $this->db->query("select concat(menu_id) as menu_id from  mst_user_profile_setting where del_date is null and status=1 and setting_name='main' and profile_id=".$pid);
								$PIds="";
								foreach($pres->result_Array() as $row1){
									$PIds.=$row1['menu_id'].",";
								}
								$PIds=trim($PIds,',');
								$res = $this->db->query("select id,menu_name,sub_settings from  main_menu_master where del_date is null and status=1 and type=1 and parent_menu_id is not null and id in ($PIds) and id != 12 and id != 24 order by parent_menu_id,priority");
							}
							
								foreach($res->result_Array() as $row){
									echo "<tr><Td style='padding-left:5px;width:24px;padding-top:0px'><input type='checkbox' name='menu_setting[]' value='".$row['id']."' ";
									if(in_array($row['id'],$values)){
										echo " checked='checked' ";
									}
									if(trim($row['sub_settings'])!=""){
										echo " onclick='if($(this).is(\":Checked\")==true){ $(\"#row_".$row['id']."\").show(); $(\"#row_".$row['id']." input:checkbox\").attr(\"checked\",\"checked\"); }else{ $(\"#row_".$row['id']."\").hide();   $(\"#row_".$row['id']." input:checkbox\").removeAttr(\"checked\");}' ";
									}
									
									echo "  ></td><td style='padding-top:0px'>";
									echo $this->lang->line($row['menu_name']);
									echo "</td></tr>";
									if(trim($row['sub_settings'])!=""){
										echo "<Tr style='background:lightsteelblue;";
										if(!in_array($row['id'],$values)){
											echo "display:none;";
										}
										echo " ' id='row_".$row['id']."' ><td colspan='2' style='padding-left:34px;padding-top:0px'><table width='90%'>";
										$sub = explode(",",trim($row['sub_settings']));
										for($i=0;$i<count($sub);$i=$i+4){
											echo "<Tr><Td  style='padding-top:0px'>";
											if(isset($sub[$i])){
												echo "<input type='checkbox' name='menu_setting[]' value='".$row['id']."_".$sub[$i]."' ";
												if(in_array($row['id']."_".$sub[$i],$values)){
													echo " checked='checked' ";
												}
												echo " > ";
												echo $this->lang->line($sub[$i]);	
												
	
											}
											
											echo "</td><td  style='padding-top:0px'>";
											if(isset($sub[$i+1])){
												echo "<input type='checkbox' name='menu_setting[]' value='".$row['id']."_".$sub[$i+1]."' ";
												if(in_array($row['id']."_".$sub[$i+1],$values)){
													echo " checked='checked' ";
												}
												echo "> ";
												echo $this->lang->line($sub[$i+1]);
											}
											echo "</td><td  style='padding-top:0px'>";
											if(isset($sub[$i+2])){
												echo "<input type='checkbox' name='menu_setting[]' value='".$row['id']."_".$sub[$i+2]."' ";
												if(in_array($row['id']."_".$sub[$i+2],$values)){
													echo " checked='checked' ";
												}
												echo " > ";
												echo $this->lang->line($sub[$i+2]);

											}
											echo "</td><td  style='padding-top:0px'>";
											if(isset($sub[$i+3])){
												echo "<input type='checkbox' name='menu_setting[]' value='".$row['id']."_".$sub[$i+3]."' ";
												if(in_array($row['id']."_".$sub[$i+3],$values)){
													echo " checked='checked' ";
												}
												echo " > ";
											}
											echo "</td></tr>";
										}
										echo "</table></td></tr>";
										echo $this->lang->line($sub[$i+3]);

									}
								}
							?>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onclick="cancel_user_profile()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
					</tr>					
				</tbody>
			</table>
			</form>
		</div>
		
<script type="text/javascript">
$(document).ready(function() {

$("#loading_top").css("display","none");
	
	jQuery("input:button, input:submit, input:reset").button();
	
	$("#callJson").click(function(){
		$.post("<?php echo site_url('user_profile/get_json_data');  ?>",function(data){
			alert(data.toSource());
		});
	});
	$("#selectAlldays").change(function(){
		if($(this).attr("checked")=="checked"){
		$.each($("input[name='display_day[]']"), function() {
				$(this).attr("checked","checked");
			});
		}else
		{
		$.each($("input[name='display_day[]']"), function() {
				$(this).removeAttr("checked");
			});
		}
	});
	$("input[name='display_day[]']").change(function(){
		var bool=true;
		$.each($("input[name='display_day[]']"), function() {
			if($(this).attr("checked")!="checked"){
				bool=false;
			}
		});
		if(bool==true){
			$("#selectAlldays").attr("checked","checked");
		}else{
			$("#selectAlldays").removeAttr("checked");
		}
	});
	<?php 
	if(isset($this->form_model->country) && $this->form_model->country!=""){ 
		if($this->form_model->state!=""){
		?>
		usr_state(<?php echo $this->form_model->country.",".$this->form_model->state; ?>);
	<?php } else { ?>
		usr_state(<?php echo $this->form_model->country.",0"; ?>);
	<?php }
	
	if($this->form_model->state!="" && $this->form_model->city!="")
	{ ?>	
		usr_city(<?php echo $this->form_model->state.",".$this->form_model->city; ?>);
	<?php
	}
	else if($this->form_model->state=="" && $this->form_model->city!="")
	{ ?>
		usr_city(<?php echo $this->form_model->city.",0"; ?>);
	<?php }
	}
	?>	
	
	$("#zip").NumericOnly();
	$("#fax_number").NumericOnly();
	$("#phone_number").NumericOnly();
	$("#username").UserName();
	//$("#password").Password();
	$("#mobile_number").Mobile_Comma_Only();
	vfields['username']="NoBlank";
	vfields['mobile_number'] = "NoBlank";
	//vfields['password']="NoBlank";
	vfields['email_address']="NoBlank";
	vfields['email_address']="Email";
	
	<?php if(isset($weekdays) && isset($this->form_model->username) && $this->form_model->username!=""){
		for($i=0;$i<count($weekdays);$i++){ ?>
			 $("input[value='<?php echo $weekdays[$i] ?>']").attr("checked","checked");
			 
			 <?php
			 if($i==count($weekdays)-1){?>
			 $("input[value='<?php echo $weekdays[$i] ?>']").trigger("change");
			 <?php }
		}
	}else{?>
		$("#selectAlldays").trigger("change");
	<?php }
	?>
	
		
	
		setTimeout(function(){
			
		if($("#hidden_copy_id").val() != "")
		{
			$("#profile_name").val("");
		$("#profile_desc").val("");
		$("#status option[value='1']").attr("selected","selected");
		
		}
		},100);
});

</script>