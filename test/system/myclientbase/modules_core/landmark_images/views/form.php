<?php $tim=time(); ?>
<script type="text/javascript">
loadSWFupload();
</script>
<style>
/* CSS for landmark_imagesupld block */ 
		
		#landmark_imagesupload-control p{ margin:10px 5px; font-size:0.9em; }
		#digital { margin:0; padding:0; width:auto;}
		#landmark_imagesupld li { list-style-position:inside; margin:2px; border:1px solid #ccc; padding:3px; font-size:12px; 
			font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;margin-right:6px;}
		#landmark_imagesupld li .progressbar{ border:1px solid #333; height:5px; background:#fff; }
		#landmark_imagesupld li .progress{ background:#999; width:0%; height:5px; }
		#landmark_imagesupld li p{ margin:0; line-height:18px; }
		#landmark_imagesupld li.success{ border:1px solid #339933; background:#ccf9b9; }
		#landmark_imagesupld li span.cancel{ position:absolute; top:5px; right:5px; width:20px; height:20px; 
			background:url('<?php echo base_url(); ?>/assets/swfupload/cancel.png') no-repeat; cursor:pointer; }
			

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
			background:url('<?php echo base_url(); ?>/assets/swfupload/cancel.png') no-repeat; cursor:pointer; }

</style>
<script type="text/javascript">
	$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
		$('#landmark_imagesupload-control').swfupload({
			upload_url: "<?php echo base_url(); ?>upload_landmark_images_photo.php",
			file_post_name: 'uploadfile',
			file_size_limit : "100 MB",
			file_types : "*.jpg;*.gif;*.png;*.bmp",
			file_types_description : "Web Image Files",
			file_upload_limit : 25,
			flash_url : "<?php echo base_url(); ?>/assets/swfupload/swfupload.swf",
			button_image_url : '<?php echo base_url(); ?>/assets/swfupload/wdp_buttons_upload_114x29.png',
			button_width : 114,
			button_height : 29,
			button_placeholder : $('#landmark_images_button_uploader')[0],
			debug: true
			})
			.bind('fileQueued', function(event, file){		
			
			// start the upload since it's queued
			$(this).swfupload('startUpload');
		})
		.bind('fileQueued', function(event, file){
			var name = file.name;
			name = name.replace(/ /gi,'_');
			var listitem='<li style="color:red;" id="'+file.id+'" >'+
				'<?php echo $this->lang->line('File'); ?>: <em>'+name+'</em>&nbsp;&nbsp;('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span><span class="status"></span>'
			$('#landmark_imagesupld').html(listitem);		
			$('li#'+file.id+' .cancel').bind('click', function(){ //Remove from queue on $("#landmark_imagesupld li#"+file.id).remove();
			$('li#'+file.id).slideUp('fast');
			 
			});
			// start the upload since it's queued  
			$(this).swfupload('startUpload');
		})
		.bind('uploadProgress', function(event, file, bytesLoaded){
				//Show Progress
				var percentage=Math.round((bytesLoaded/file.size)*100);
				$('#landmark_imagesupld li#'+file.id).find('div.progress').css('width', percentage+'%');
				$('#landmark_imagesupld li#'+file.id).find('span.progressvalue').text(percentage+'%');
			})
		.bind('uploadSuccess', function(event, file, serverData){
				var name = file.name;
				name = name.replace(/ /gi,'_');
				var item=$('#landmark_imagesupld li#'+file.id);
				item.find('div.progress').css('width', '100%');
				item.find('span.progressvalue').text('100%');
				var pathtofile='<a href="<?php echo base_url(); ?>assets/landmark_images/'+name+'" target="_blank" ><?php echo $this->lang->line('view'); ?> &raquo;</a>';
				item.addClass('success').find('span.status').html('  <?php echo $this->lang->line('Done'); ?>!!! | '+pathtofile);
				$('#landmark_images_image_path').val(name);
				$('#image_path').val("assets/landmark_images/"+name);				
			})  
		.bind('uploadComplete', function(event, file){
			// upload has completed, try the next one in the queue
			$(this).swfupload('startUpload');
			
		});
		$(document).keypress(function(e) { 
			if (e.keyCode == 27) { 
				if($("#landmark_images_form_div").css("display") !="none" && $("#landmark_images_form_div").css("display") != undefined){
					con_asse_dis.dialog("open");
				}
			}   
		}); 
	$("#loading_top").css("display","none");
	});
	</script>
<?php if($this->form_model->id == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create_landmark_images"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update_landmark_images"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div class="content toggle">
  <form id="frm_landmark_images" method="post" onSubmit="return submitFormlandmark_images('<?php echo uri_assoc('id')?>')" action="<?php echo site_url($this->uri->uri_string()); ?>"  enctype="multipart/form-data">
    <!--<p id="error" class="addTips">* Fields are mendatory</p>-->
    <table width="100%" align="center" class="formtable">
      <tbody>
        <tr>
          <td><label><?php echo $this->lang->line('landmark_images_Image'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		  
		  <div id="landmark_imagesupload-control">
		<input type="button" id="landmark_images_button_uploader" /> 
		<input type="hidden" id="image_path" name="image_path" />
		<!--<p id="queuestatus" ></p>-->
		<ol id="landmark_imagesupld" style='float: right; width: 321px;'>
		<?php if($this->form_model->landmark_images_image_path != ""){ ?>
				<li style="color:red;" class='success'><?php echo $this->lang->line('File'); ?>: <em><?php echo $this->form_model->landmark_images_image_path; ?></em>&nbsp;&nbsp;<span class="progressvalue" >100%</span><span class="status"> <?php echo $this->lang->line('Done'); ?>!!! | <a href="<?php echo base_url(); ?>/assets/landmark_images/<?php echo $this->form_model->landmark_images_image_path; ?>" target="_blank" ><?php echo $this->lang->line('view'); ?> &raquo;</a></span>
				<?php } ?>
		</ol>
	</div></td>
        </tr>
		 <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_landmark_images()" name="btn_cancel" value="<?php echo $this->lang->line('Back'); ?>" /></td>
		</tr>       
      </tbody>
    </table>
  </form>
</div>
<div id="landmark_images_icon_dialog<?php echo $tim; ?>" class="assestimage_oad"></div>