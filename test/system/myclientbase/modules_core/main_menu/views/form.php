<style>
/* CSS for assetsupld block */ 
		
		#assetsupload-control p{ margin:10px 5px; font-size:0.9em; }
		#digital { margin:0; padding:0; width:auto;}
		#assetsupld li { list-style-position:inside; margin:2px; border:1px solid #ccc; padding:3px; font-size:12px; 
			font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;margin-right:6px;}
		#assetsupld li .progressbar{ border:1px solid #333; height:5px; background:#fff; }
		#assetsupld li .progress{ background:#999; width:0%; height:5px; }
		#assetsupld li p{ margin:0; line-height:18px; }
		#assetsupld li.success{ border:1px solid #339933; background:#ccf9b9; }
		#assetsupld li span.cancel{ position:absolute; top:5px; right:5px; width:20px; height:20px; 
			background:url('<?php echo base_url(); ?>assets/swfupload/cancel.png') no-repeat; cursor:pointer; }
			

/* CSS for driverupld block */ 
		
		#driverupload-control p{ margin:10px 5px; font-size:0.9em; }
		#digital { margin:0; padding:0; width:auto;}
		#driverupld li { list-style-position:inside; margin:2px; border:1px solid #ccc; padding:3px; font-size:12px; 
			font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;margin-right:6px;}
		#driverupld li .progressbar{ border:1px solid #333; height:5px; background:#fff; }
		#driverupld li .progress{ background:#999; width:0%; height:5px; }
		#driverupld li p{ margin:0; line-height:18px; }
		#driverupld li.success{ border:1px solid #339933; background:#ccf9b9; }
		#driverupld li span.cancel{ position:absolute; top:5px; right:5px; width:20px; height:20px; 
			background:url('<?php echo base_url(); ?>assets/swfupload/cancel.png') no-repeat; cursor:pointer; }

</style>

<script type="text/javascript">
	$(document).ready(function() {
	$("#loading_top").css("display","none");
		 
		$('#assetsupload-control').swfupload({
			upload_url: "<?php echo base_url(); ?>upload_menu_photo_grayscale.php",
			file_post_name: 'uploadfile',
			file_size_limit : "100 MB",
			file_types : "*.mp3;*.wav", 
			file_types_description : "Web sound Files",
			file_upload_limit : 25,
			flash_url : "<?php echo base_url(); ?>assets/swfupload/swfupload.swf",
			button_image_url : '<?php echo base_url(); ?>assets/swfupload/wdp_buttons_upload_114x29.png',
			button_width : 114,
			button_height : 29,
			button_placeholder : $('#assets_button_uploader')[0],
			debug: false 
			})
			.bind('fileQueued', function(event, file){		
			
			// start the upload since it's queued
			$(this).swfupload('startUpload');
		})
		.bind('fileQueued', function(event, file){
			var name = file.name;
			name = name.replace(/ /gi,'_');
			var listitem='<li style="color:red;" id="'+file.id+'" >'+
				'File: <em>'+name+'</em>&nbsp;&nbsp;('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span><span class="status"></span>'
			$('#assetsupld').html(listitem);		
			$('li#'+file.id+' .cancel').bind('click', function(){ //Remove from queue on cancel click  
			//swfu.cancelUpload(file.id);   
			//alert($("#assetsupld li#"+file.id).html());
			$("#assetsupld li#"+file.id).remove();
			$('li#'+file.id).slideUp('fast');
			 
			});
			// start the upload since it's queued  
			$(this).swfupload('startUpload');
		})
		.bind('uploadProgress', function(event, file, bytesLoaded){
				//Show Progress
				var percentage=Math.round((bytesLoaded/file.size)*100);
				$('#assetsupld li#'+file.id).find('div.progress').css('width', percentage+'%');
				$('#assetsupld li#'+file.id).find('span.progressvalue').text(percentage+'%');
			})
		.bind('uploadSuccess', function(event, file, serverData){
				var name = file.name;
				name = name.replace(/ /gi,'_');
				var item=$('#assetsupld li#'+file.id);
				item.find('div.progress').css('width', '100%');
				item.find('span.progressvalue').text('100%');
				var pathtofile='<a href="<?php echo base_url(); ?>assets/menu_sound/'+name+'" target="_blank" ><?php echo $this->lang->line("view"); ?> &raquo;</a>';
				item.addClass('success').find('span.status').html('  Done!!! | '+pathtofile);
				$('#menu_sound').val(name);
			})  
		.bind('uploadComplete', function(event, file){
			// upload has completed, try the next one in the queue
			$(this).swfupload('startUpload');
			
		}); 
		$('#driverupload-control').swfupload({
			upload_url: "<?php echo base_url(); ?>simple_menu_swf_upload.php",
			file_post_name: 'uploadfile',
			file_size_limit : "100 MB",
			file_types : "*.jpg;*.gif;*.png;*.bmp", 
			file_types_description : "Web Image Files",
			file_upload_limit : 25,
			flash_url : "<?php echo base_url(); ?>assets/swfupload/swfupload.swf",
			button_image_url : '<?php echo base_url(); ?>assets/swfupload/wdp_buttons_upload_114x29.png',
			button_width : 114,
			button_height : 29,
			button_placeholder : $('#driver_button_uploader')[0],
			debug: false
			})
			.bind('fileQueued', function(event, file){		
			
			// start the upload since it's queued
			$(this).swfupload('startUpload');
		})
		.bind('fileQueued', function(event, file){
			var name = file.name;
			name = name.replace(/ /gi,'_');
			var listitem='<li style="color:red;" id="'+file.id+'" >'+
				'File: <em>'+name+'</em>&nbsp;&nbsp;('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span><span class="status"></span>'
			$('#driverupld').html(listitem);		
			$('li#'+file.id+' .cancel').bind('click', function(){ //Remove from queue on cancel click  
			//swfu.cancelUpload(file.id);   
			//alert($("#driverupld li#"+file.id).html());
			$("#driverupld li#"+file.id).remove();
			$('li#'+file.id).slideUp('fast');
			 
			});
			// start the upload since it's queued  
			$(this).swfupload('startUpload'); 
		})
		.bind('uploadProgress', function(event, file, bytesLoaded){
				//Show Progress
				var percentage=Math.round((bytesLoaded/file.size)*100);
				$('#driverupld li#'+file.id).find('div.progress').css('width', percentage+'%');
				$('#driverupld li#'+file.id).find('span.progressvalue').text(percentage+'%');
			})
		.bind('uploadSuccess', function(event, file, serverData){
				var name = file.name;
				name = name.replace(/ /gi,'_');
				var item=$('#driverupld li#'+file.id);
				item.find('div.progress').css('width', '100%');
				item.find('span.progressvalue').text('100%');
				var pathtofile='<a href="<?php echo base_url(); ?>assets/menu_image/'+name+'" target="_blank" ><?php echo $this->lang->line("view"); ?> &raquo;</a>';
				item.addClass('success').find('span.status').html('  Done!!! | '+pathtofile);
				$('#menu_image').val(name);
			})  
		.bind('uploadComplete', function(event, file){
			// upload has completed, try the next one in the queue
			$(this).swfupload('startUpload');
			
		});
	});
	
	function sel_where_to_show(val)
	{
		if(val == "link"){
			$("#menu_td").show();
			$("#menu_link").show();
		}
		else{
			$("#menu_td").hide();
			$("#menu_link").hide();
		}
	}
	function select_parent_menu(id){ 
		if(id != "1" && id != ""){
		$.post("<?php echo site_url('main_menu/sel_menu/id'); ?>/"+id, 
				function(data){
					//alert(data);
					if(data){
						$('#parent_menu_td').show();
						$('#parent_menu_id').show();
						$('#parent_menu_id').html(data);
					}
				}  
			);
		}
		else
		{
			$('#parent_menu_td').hide();
			$('#parent_menu_id').hide();
		}
		return false;	  
	}
	</script>
<?php if($this->form_model->menu_name == ""){ ?> 
<h3 class="title_black"><?php echo $this->lang->line("Add menu"); ?></h3>
<?php 

	$SQL = "SELECT max(priority) as priority FROM `main_menu_master` where del_date is null and status=1"; 		
	$query = $this->db->query($SQL);
	$row = $query->result(); 
	$priority =$row[0]->priority;
	$priority = ($priority + 1);
	
?>
<?php }else{ ?>  
<h3 class="title_black"><?php echo $this->lang->line("Update menu"); ?></h3>
<?php 
	if(uri_assoc('id') != ''){
		$SQL = "SELECT priority FROM `main_menu_master` where del_date is null and status=1 and id =".uri_assoc('id'); 		
		$query = $this->db->query($SQL);
		$row = $query->result(); 
		$priority =$row[0]->priority;
	}
?>
<?php } ?>

<?php $this->load->view('dashboard/system_messages'); ?>
<div id="menu_error" class="error" style="display:none"><?php echo $this->lang->line("Menu Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_menu" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitForm_Main_menu('<?php echo uri_assoc('id')?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="80%" align="center" class="formtable">
      <tbody>
        
		<tr>
			<td width="50%"><?php echo $this->lang->line("Menu Name"); ?></td>
			<td width="50%"><input type="text" name="menu_name" id="menu_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->menu_name; ?>" onblur="check_menu(<?php echo uri_assoc('id')?>)"/></td>
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("Where To Show"); ?></td>
			<td width="50%">
			<select name="where_to_show" id="where_to_show" class="text ui-widget-content ui-corner-all" onchange="sel_where_to_show(this.value)">
			<option id="show_menu" value="menu" <?php if($this->form_model->where_to_show == "show_menu") echo "selected='selected'"; ?>><?php echo $this->lang->line("Menu"); ?></option>        
			<option value="link" <?php if($this->form_model->where_to_show == "link") echo "selected='selected'"; ?>><?php echo $this->lang->line("Link"); ?></option>        
            <option value="sidebar" <?php if($this->form_model->where_to_show == "sidebar") echo "selected='selected'"; ?>><?php echo $this->lang->line("sidebar"); ?></option>		
			</select></td> 
		</tr> 
		<tr> 
			<td width="50%" id="menu_td" style="display:none"><?php echo $this->lang->line("Menu Link"); ?></td>
			<td width="50%"><input type="text" name="menu_link" id="menu_link" style="display:none" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->menu_link; ?>" /></td>
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("Menu Sound"); ?></td>
			<td width="50%">
			<!--<input type="text" name="menu_sound" id="menu_sound" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->menu_sound; ?>" />-->
			 <div id="assetsupload-control">
				<input type="button" id="assets_button_uploader" /> 
				<input type="hidden" id="menu_sound" name="menu_sound" />
				<!--<p id="queuestatus" ></p>-->
				<ol id="assetsupld" style='float: right; width: 321px;'>
					<?php if($this->form_model->menu_sound != ""){ ?>
					<li style="color:red;" class='success'><?php echo $this->lang->line("File"); ?>: <em><?php echo $this->form_model->menu_sound; ?></em>&nbsp;&nbsp;<span class="progressvalue" >100%</span><span class="status"> <?php echo $this->lang->line("Done"); ?>!!! | <a href="<?php echo base_url(); ?>assets/menu_sound/<?php echo $this->form_model->menu_sound; ?>" target="_blank" ><?php echo $this->lang->line("view"); ?> &raquo;</a></span>
					<?php } ?>
				</ol>
			</div>
			</td>
		</tr>
		<tr> 
			<td width="50%"><?php echo $this->lang->line("Tab Title"); ?></td>
			<td width="50%"><input type="text" name="tab_title" id="tab_title" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->tab_title; ?>" /></td> 
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("Menu Level"); ?></td> 
			<td width="50%"> 
			<!--<input type="text" name="menu_level" id="menu_level" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->menu_level; ?>" />-->
			<select name="menu_level" id="menu_level" class="text ui-widget-content ui-corner-all" onchange="select_parent_menu(this.value)"> 
			<?php
				$SQL = "SELECT max(menu_level) as menu_level FROM `main_menu_master` where del_date is null and status=1"; 		
				$query = $this->db->query($SQL);
				$row = $query->result(); 
				$level_no =$row[0]->menu_level;
				echo "<option value=''>".$this->lang->line("Select Level")."</option>"; 
				for($i=1;$i<=$level_no;$i++){
					if($this->form_model->menu_level == $i)					
						echo "<option value='$i' selected='selected'>Level $i</option>";
					else					
						echo "<option value='$i'>Level $i</option>"; 
				}
			?> 
			</select></td>
			
		</tr>
		<tr> 
			<td width="50%" id="parent_menu_td" style="display:none;"><?php echo $this->lang->line("Parent Menu"); ?></td>
			<td width="50%">
			<!--<input type="text" name="parent_menu_id" id="parent_menu_id" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->parent_menu_id; ?>" />-->
			<select name="parent_menu_id" style="display:none;" id="parent_menu_id" class="text ui-widget-content ui-corner-all">
			<option value=''>Please select</option> 
			</select>
			</td>
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("Menu Image"); ?></td>
			<td width="50%">
			<!--<input type="text" name="menu_image" id="menu_image" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->menu_image; ?>" />-->
			<div id="driverupload-control"> 
				<input type="button" id="driver_button_uploader" /> 
				<input type="Hidden" id="menu_image" name="menu_image" value='<?php echo $this->form_model->menu_image; ?>' />
				<!--<p id="queuestatus" ></p>-->
				<ol id="driverupld" style='float: right; width: 321px;'>
				<?php if($this->form_model->menu_image != ""){ ?>
				<li style="color:red;" class='success'><?php echo $this->lang->line("File"); ?>: <em><?php echo $this->form_model->menu_image; ?></em>&nbsp;&nbsp;<span class="progressvalue" >100%</span><span class="status"> <?php echo $this->long->line("Done"); ?>!!! | <a href="<?php echo base_url(); ?>assets/menu_image/<?php echo $this->form_model->menu_image; ?>" target="_blank" ><?php echo $this->lang->line("view"); ?> &raquo;</a></span>
				<?php } ?>
				</ol>
			</div>
			</td>
		</tr> 
		<tr> 
			<td width="50%"><?php echo $this->lang->line("Priority"); ?></td>
			<td width="50%"><input type="text" name="priority" id="priority" class="text ui-widget-content ui-corner-all" value="<?php echo $priority; ?>" readonly /></td> 
		</tr>
		<tr> 
			<td width="50%"><?php echo $this->lang->line("Is Admin"); ?></td> 
			<td width="50%">
			<input type="checkbox" name="is_admin" id="is_admin" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" <?php if($this->form_model->is_admin == 1) echo "checked='checked'"; ?> />
			</td> 
		</tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_menu_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_menu_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_menu()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td> 
        </tr>
		</tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
//	$("#loading_dialog").dialog("close");
$("#loading_top").css("display","none");
});
$(".ddTitle").height(22);
$(".ddTitle img").height(22);
/*
var name = $("#name");
allFields = $([]).add(name);
tips = $("#error");*/
</script>