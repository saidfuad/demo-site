<?php if($this->form_model->id == ""){ ?> 
<h3 class="title_black"><?php echo $this->lang->line("Add menu"); ?></h3>
<?php }else{ ?>  
<h3 class="title_black"><?php echo $this->lang->line("Update menu"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="menu_error" class="error" style="display:none"><?php echo $this->lang->line("Menu Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_user_menu" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormuser_group('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">*<?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        
		<tr>
			<td width="50%"><?php echo $this->lang->line("User Name"); ?></td>
			<td width="50%">
			<input type="text" name="usrNametxt" id="usrNametxt" class="text ui-widget-content ui-corner-all" readonly />
			</td>
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("menu List"); ?></td>
			<td width="50%"> 
			<input type='hidden' value='<?php echo uri_assoc('id')?>' name='user_id'>
			<?php 
				//$menu_id = array();
				$SQL ='select * from app_menu_master where del_date is null and id ='.uri_assoc('id');
				$query  = $this->db->query($SQL);
				foreach($query->result() as $row)
				{
					$menu_id = $row->menu_id ;
					$usr_idd = $row->user_id ;
				}
				
				//function print_menu_level($level,$val,$menu_id,$parent_id = "",$usr_idd)
				function print_menu_level($level,$val,$menu_id,$parent_id ,$usr_idd)
				{
					echo "<ul>";
					//$SQL ="select * from main_menu_master where del_date is null and status =1 and menu_level = $level and id =".$menu_id;
					$SQL ="select mmm.*, amm.status as disp, amm.where_to_show as wts from main_menu_master as mmm left join app_menu_master as amm on mmm.id=amm.menu_id where mmm.del_date is null and mmm.status =1 and user_id=".$usr_idd." and mmm.id =".$menu_id;
					if($parent_id != "")
					{
						$SQL .= " and parent_menu_id = $parent_id";
					}
					
					$query  = $val->query($SQL);
					
					foreach($query->result() as $row)
					{
						// for combo selection
						 $where_to_show = $row->where_to_show ;
							
						//die(print_r($row));
						echo "<li><input type='checkbox' name='menu_group' value='".$row->id."'";
						if ($row->id == $menu_id) {
							if($row->disp == 1)
								echo " checked = 'checked' ";
						}
						echo " />".$row->menu_name;
						$id =$row->id;
						//die(print_r($row));
						/*
						if($row->wts=="menu" || $row->wts=="sidebar")
						{
							echo "&nbsp;&nbsp;&nbsp;&nbsp; Where To show &nbsp; &nbsp;<select style='width:35%' name='where_to_show' id='where_to_show' >";
							if($row->wts=="menu")
								echo "<option value='menu' selected='selected'>Menu </option><option value='sidebar'>Sidebar </option></select>";
							else
								echo "<option value='menu'>Menu </option><option value='sidebar' selected='selected'>Sidebar </option></select>";
						}*/
						
						//<option value='menu'>Menu </option><option value='sidebar'>Sidebar </option></select>";
						print_menu_level($level+1,$val,$menu_id,$row->id,$usr_idd);
						//print_menu_level($level+1,$val,$menu_id,$row->id);
						echo "</li>";
					}
					echo "</ul>";  
				} 
				
				//print_menu_level(1,$this->db,$menu_id);
				print_menu_level(1,$this->db,$menu_id,'',$usr_idd);
				
			?>
			</td>
		</tr>
		<tr>
			<?php
				$SQL ='select * from app_menu_master where del_date is null and id ='.uri_assoc('id');
				$query  = $this->db->query($SQL);
				foreach($query->result() as $row)
				{
					$where_to_show = $row->where_to_show ;
				}
			?>
			<td width="50%"><?php echo $this->lang->line("Where To Show"); ?></td> 
			<td width="50%">
			<select name="where_to_show" id="where_to_show" class="text ui-widget-content ui-corner-all" onchange="sel_where_to_show(this.value)">
			<option value="" >Plese Select</option>    
			<option id="show_menu" value="menu" <?php if($where_to_show == "menu") echo "selected='selected'"; ?>>menu</option>        
			<option value="link" <?php if($where_to_show == "link") echo "selected='selected'"; ?>>link</option>        
            <option value="sidebar" <?php if($where_to_show == "sidebar") echo "selected='selected'"; ?>>sidebar</option>	
			</select></td> 
		</tr>
		<tr> 
			<?php
				$SQL ='select * from app_menu_master where del_date is null and id ='.uri_assoc('id');
				$query  = $this->db->query($SQL);
				foreach($query->result() as $row)
				{
					$priority = $row->priority ;
				}
			?>
			<td width="50%"><?php echo $this->lang->line("Priority"); ?></td>
			<td width="50%"><input type="text" name="priority" id="priority" class="text ui-widget-content ui-corner-all" value="<?php echo $priority; ?>"  /></td> 
		</tr>  
		 
		<tr> 
			<?php
				$SQL ='select * from app_menu_master where del_date is null and id ='.uri_assoc('id');
				$query  = $this->db->query($SQL);
				foreach($query->result() as $row)
				{
					$menu_id = $row->menu_id ;
				}
				$SQL ='select * from main_menu_master where del_date is null and id ='.$menu_id;
				$query  = $this->db->query($SQL);
				foreach($query->result() as $row)
				{
					$menu_level = $row->menu_level ;
					$parent_menu_id = $row->parent_menu_id ;
				}
				if($menu_level == '' or $parent_menu_id == ''){
					$SQL ='select * from main_menu_master where del_date is null and menu_level ='.$menu_level;
				}
				else{
					$SQL ='select * from main_menu_master where del_date is null and menu_level ='.$menu_level .' AND parent_menu_id ='.$parent_menu_id ;
				}
				
				$query  = $this->db->query($SQL);
				echo '<tr align="center"><th width="50%">Menu Name</th><th width="50%">Priority</th></tr>';
				foreach($query->result() as $row)
				{
					echo '<tr align="center">';
					echo '<td width="50%">'.$row->menu_name.'</td>';
					echo '<td width="50%">'.$row->priority.'</td>';
					echo '</tr>';
					
				}
			?>
		</tr> 
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_menu_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_menu_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_user_menu()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td> 
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

$(".ddTitle").height(22);
$(".ddTitle img").height(22);
/*
var name = $("#name");
allFields = $([]).add(name);  
tips = $("#error");*/
</script> 