<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.dd.js"></script>
<link href="<?php echo base_url(); ?>assets/style/css/dd.css" rel="stylesheet" type="text/css" />
-->
<?php $tim=time(); ?>
<?php
	$uid = $this->session->userdata('user_id');
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
	  if($uid==1){
		$data = "Not Allow";
		
	}
	else
	{
		$data = "Not Allow";
		$va1l = $this->db;
		
		$va1l->where("user_id",$this->session->userdata('user_id'));
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("tbl_users");
		
		foreach($res_val ->result_Array() as $row)
		{
			
			$ans_cp=0;
			
			if(isset($row['device_id_not_editable'])){
				$ans_cp = $row['device_id_not_editable'];
			}
			
		}
		if($ans_cp=='1')
			$data = "Allow";
		else
			$data = "Not Allow";
	} 
?>
<script type="text/javascript">
jQuery.fn.DecimalOnly =
function()
{
    return this.each(function()
    {       
		$(this).keydown(function(e)
        {
			
            var key = e.charCode || e.keyCode || 0;
			
			if(this.value.indexOf('.') != -1){
				var val = this.value.split('.');
				val = val[1];
				if(val.length == 2){
					return(key == 8 || key == 9 || key == 46 || key == 18);
				}else{
					 return (
                key == 8 || 
                key == 9 ||
                key == 46 ||
				key == 18 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57 && e.shiftKey === false) ||
                (key >= 96 && key <= 105));
				}
			}else{
			// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			if(this.value.length == 0){
				return (
				key == 109 ||
				key == 173 ||
				key == 110 || 
				key == 190 || 
                key == 8 || 
                key == 9 ||
                key == 46 ||
				key == 18 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57 && e.shiftKey === false) ||
                (key >= 96 && key <= 105));
			}
			else return (
				key == 110 || 
				key == 190 || 
                key == 8 || 
                key == 9 ||
                key == 46 ||
				key == 18 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57 && e.shiftKey === false) ||
                (key >= 96 && key <= 105));
			}
        })
    })
};
</script>
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
		<?php if(isset($this->form_model->sensor_fuel) && $this->form_model->sensor_fuel == 1){ ?>
			$(".fuel_display").show();
		<?php } ?>
		<?php if(isset($this->form_model->fuel_in_out_sensor) && $this->form_model->fuel_in_out_sensor == 1){ ?>
			$(".fuel_in_out_sensor_display").show();
		<?php } ?>
		<?php if($this->form_model->tank_type == 'horizontal_rectangular'){ ?> 
			$(".tank_width").show();
		<?php }else{ ?>
			$(".tank_width").hide();
		<?php } ?>
		$("#duplicateDeviceId").hide();
		//$("#icon_id").html($("#assets_icon_tmp_id").html());
		//$("#icon_id").msDropDown();	
		$('#assetsupload-control').swfupload({
			upload_url: "<?php echo base_url(); ?>upload_assets_photo_grayscale.php",
			file_post_name: 'uploadfile',
			file_size_limit : "100 MB",
			file_types : "*.jpg;*.gif;*.png;*.bmp",
			file_types_description : "Web Image Files",
			file_upload_limit : 25,
			flash_url : "<?php echo base_url(); ?>/assets/swfupload/swfupload.swf",
			button_image_url : '<?php echo base_url(); ?>/assets/swfupload/wdp_buttons_upload_114x29.png',
			button_width : 114,
			button_height : 29,
			button_placeholder : $('#assets_button_uploader')[0],
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
				var pathtofile='<a href="<?php echo base_url(); ?>/assets/assets_photo/'+name+'" target="_blank" ><?php echo $this->lang->line('view'); ?> &raquo;</a>';
				item.addClass('success').find('span.status').html('  <?php echo $this->lang->line('Done'); ?>!!! | '+pathtofile);
				$('#assets_image_path').val(name);
			})  
		.bind('uploadComplete', function(event, file){
			// upload has completed, try the next one in the queue
			$(this).swfupload('startUpload');
			
		});
		$('#driverupload-control').swfupload({
			upload_url: "<?php echo base_url(); ?>simple_swf_upload.php",
			file_post_name: 'uploadfile',
			file_size_limit : "100 MB",
			file_types : "*.jpg;*.gif;*.png;*.bmp",
			file_types_description : "Web Image Files",
			file_upload_limit : 25,
			flash_url : "<?php echo base_url(); ?>/assets/swfupload/swfupload.swf",
			button_image_url : '<?php echo base_url(); ?>/assets/swfupload/wdp_buttons_upload_114x29.png',
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
				var pathtofile='<a href="<?php echo base_url(); ?>/assets/driver_photo/'+name+'" target="_blank" ><?php echo $this->lang->line("view &raquo"); ?>;</a>';
				item.addClass('success').find('span.status').html('  Done!!! | '+pathtofile);
				$('#driver_image').val(name);
			})  
		.bind('uploadComplete', function(event, file){
			// upload has completed, try the next one in the queue
			$(this).swfupload('startUpload');
		});
		$(document).keypress(function(e) { 
			if (e.keyCode == 27) { 
				if($("#assets_form_div").css("display") !="none" && $("#assets_form_div").css("display") != undefined){
					con_asse_dis.dialog("open");
				}
			}   
		}); 
		
		
	$("#loading_top").css("display","none");
	});
	var getAssetsCategory_i=0;
	function getAssetsCategory(id){
			
			$.post(	
			"<?php echo site_url('assets/assets_category_data/id');  ?>/"+id,function(data){
							$('#assets_category_id').html(data);
				<?php if($this->form_model->assets_category_id != "") {  ?>
				var id='<?php echo $this->form_model->assets_category_id; ?>';
				
				$('#assets_category_id').val(id);
			<?php } ?>
			});
		}
	/*
		function getAssetsGroup(){
			
			$.post(	
			"<?php echo site_url('assets/assets_group_data');  ?>",function(data){
							$('#assets_group_id').html(data);
				
			});
		}
	*/
		function selectedMarker(path,name,id)
		{
			$("#ic_path_id").attr("src","<?php echo base_url()."assets/marker-images/";?>"+path);
			$("#icon_id").val(id);
			$("#ic_path_id").attr("title",name);
			$("#Assets_icon_dialog<?php echo $tim; ?>").dialog("close");
		}
		function sensor_fuel_change(){
			if($("#sensor_fuel").attr("checked")=="checked"){
				$(".fuel_display").show();
			}else{
				$(".fuel_display").hide();
			}
		}
		function sensor_tempr_change(){
			if($("#sensor_tempr").attr("checked")=="checked"){
				$("#tempr_display").show();
			}else{
				$("#tempr_display").hide();
			}
		}
		function fuel_in_out_sensor_change(){
			if($("#fuel_in_out_sensor").attr("checked")=="checked"){
				$(".fuel_in_out_sensor_display").show();
			}else{
				$(".fuel_in_out_sensor_display").hide();
			}
		}
		function changeTankType(val){
			if(val == 'horizontal_rectangular'){
				$(".tank_width").show();
			}else{
				$(".tank_width").hide();
			}
		}
	</script>
<?php if($this->form_model->assets_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Assets"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update_Assets"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div class="error" style="display:none" id="duplicateDeviceId"><?php echo $this->lang->line('Device Id is Already Exist'); ?></div>
<div class="content toggle">
  <form id="frm_assets" method="post" onSubmit="return submitFormAssets('<?php echo uri_assoc('id')?>')" action="<?php echo site_url($this->uri->uri_string()); ?>"  enctype="multipart/form-data">
  <input type="hidden" name="last_group" id="last_group" value="<?php echo $this->form_model->last_group; ?>" />
    <!--<p id="error" class="addTips">* Fields are mendatory</p>-->
    <table width="100%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="50%"><label><?php echo $this->lang->line('Asset_Name'); ?></label>
            <input type="text" name="assets_name" id="assets_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->assets_name; ?>" /></td>
          <td><label><?php echo $this->lang->line('Assets_Image'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><!--label><?php echo $this->lang->line('Icon'); ?></label-->
		  
		  <div id="assetsupload-control">
		<input type="button" id="assets_button_uploader" /> 
		<input type="hidden" id="assets_image_path" name="assets_image_path" />
		<!--<p id="queuestatus" ></p>-->
		<ol id="assetsupld" style='float: right; width: 321px;'>
		<?php if($this->form_model->assets_image_path != ""){ ?>
				<li style="color:red;" class='success'><?php echo $this->lang->line('File'); ?>: <em><?php echo $this->form_model->assets_image_path; ?></em>&nbsp;&nbsp;<span class="progressvalue" >100%</span><span class="status"> <?php echo $this->lang->line('Done'); ?>!!! | <a href="<?php echo base_url(); ?>/assets/assets_photo/<?php echo $this->form_model->assets_image_path; ?>" target="_blank" ><?php echo $this->lang->line('view'); ?> &raquo;</a></span>
				<?php } ?>
		</ol>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<!--img src="<?php echo "<?php echo base_url(); ?>/assets/marker-images/".$this->form_model->iconPath; ?>" id="ic_path_id" title="<?php echo $this->form_model->iconName; ?>" style='margin-right:5px;vertical-align: top;'/><input style="vertical-align: top;" type="button" id="getIconList" value="Choose Icon"/-->
        <input type="hidden" name="icon_id" id="icon_id" class="select ui-widget-content ui-corner-all" value="<?php echo $this->form_model->icon_id; ?>"/>
	</div>
	</td>
        </tr>
        <tr>
		<td width="50%"><label><?php echo $this->lang->line('Device'); ?>:</label>
             <!--<input type="text" name="device_id" id="device_id" class="text ui-widget-content ui-corner-all" value="<?php //echo $this->form_model->device_id; ?>"/>-->
			
                     <?php if($data=="Allow" && $this->form_model->device_id!=""){ ?>
            <input type="text" name="device_id" id="device_id" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->device_id; ?>" readonly/>
                     <?php }else{ ?>
            <input type="text" name="device_id" id="device_id" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->device_id; ?>"/>
               <?php } ?>
		</td>
		<td width="50%"><label><?php echo $this->lang->line('assets_friendly_nm'); ?>:</label>
            <input type="text" name="assets_friendly_nm" id="assets_friendly_nm" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->assets_friendly_nm; ?>"/>
			
		</td>
		</tr>
        <tr>
          <td><label><?php echo $this->lang->line('Device Description'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
		  <input type="text" name="device_desc" id="device_desc" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->device_desc; ?>"/></td>
          <td><label><?php echo $this->lang->line('Assets_Category'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <select name="assets_type_id" onchange="getAssetsCategory(this.value)" id="assets_type_id" class="select ui-widget-content ui-corner-all">
              <?php echo $this->form_model->assets_type_id; ?>
            </select></td>
        </tr>
        <tr>
			<td><label><?php echo $this->lang->line('assets_owner'); ?></label>
            <select name="assets_owner" id="assets_owner" class="select ui-widget-content ui-corner-all">
            <?php echo $this->form_model->ownersOpt; ?>
            </select></td>
			
			<td><label><?php echo $this->lang->line('Assets_Type'); ?></label>
            <select name="assets_category_id" id="assets_category_id" class="select ui-widget-content ui-corner-all">
            </select></td>
		</tr>
        <tr>
          <td><label><?php echo $this->lang->line('assets_division'); ?></label>
            <select name="assets_division" id="assets_division" class="select ui-widget-content ui-corner-all">
            <?php echo $this->form_model->divisionOpt; ?>
            </select></td>
		  <td><label><?php echo $this->lang->line('Assets_Group'); ?></label>
            <select name="assets_group_id" id="assets_group_id" class="select ui-widget-content ui-corner-all">
				<?php echo $this->form_model->assets_group_id; ?>
            </select></td>
		  
        </tr>
        <tr>
          <td><label><?php echo $this->lang->line('Driver_Name'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" name="driver_name" id="driver_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->driver_name; ?>"/></td>
          <td>
			<label><?php echo $this->lang->line('Driver_Image'); ?></label>
            <div id="driverupload-control">
				<input type="button" id="driver_button_uploader" /> 
				<input type="Hidden" id="driver_image" name="driver_image" value='<?php echo $this->form_model->driver_image; ?>' />
				<ol id="driverupld" style='float: right; width: 321px;'>
				<?php if($this->form_model->driver_image != ""){ ?>
				<li style="color:red;" class='success'><?php echo $this->lang->line('File'); ?>: <em><?php echo $this->form_model->driver_image; ?></em>&nbsp;&nbsp;<span class="progressvalue" >100%</span><span class="status"><?php echo $this->lang->line('Done'); ?>!!! | <a href="<?php echo base_url(); ?>/assets/driver_photo/<?php echo $this->form_model->driver_image; ?>" target="_blank" ><?php echo $this->lang->line('view'); ?> &raquo;</a></span>
				<?php } ?>
				</ol>
			</div>
			</td>

        </tr>
        <tr>
        <td><label><?php echo $this->lang->line('Driver_Mobile'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" name="driver_mobile" id="driver_mobile" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->driver_mobile; ?>"/></td>
          <td><label><?php echo $this->lang->line('Device_Status'); ?></label>
            <select name="device_status" id="device_status" class="text ui-widget-content ui-corner-all">
              <option value="1" <?php echo ($this->form_model->device_status==1 && $this->form_model->device_status!="")?"selected=selected":""; ?>>Active</option>
              <option value="0" <?php echo ($this->form_model->device_status==0 && $this->form_model->device_status!="")?"selected=selected":""; ?>>Inactive</option>
            </select></td>	
        </tr>		
       <tr>
          
		
		 <td><label><?php echo $this->lang->line('Max_speed_limit'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" name="max_speed_limit" id="max_speed_limit" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_speed_limit; ?>"/></td>
         
          <td><label><?php echo "Battery Size"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <select name="battery_size" id="battery_size" class="select ui-widget-content ui-corner-all">
              <?php echo $this->form_model->batteryOpt; ?>
            </select></td>        
        </tr>
		<tr>
          <td><label><?php echo "Telecom Provider"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <select name="telecom_provider" id="telecom_provider" class="select ui-widget-content ui-corner-all">
              <?php echo $this->form_model->tProvider; ?>
            </select></td>
			<td><label><?php echo $this->lang->line('Sim_Number'); ?></label>
                 <input type="text" name="sim_number" id="sim_number" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->sim_number; ?>"/>
			</td> 
			
        </tr>
        <tr>
			<td><label><?php echo "Engine Runtime"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" name="eng_runtime" id="eng_runtime" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->eng_runtime; ?>"/></td>
			<td><label><?php echo "KM Reading"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" name="km_reading" id="km_reading" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->km_reading; ?>"/></td>
        </tr>
		<tr>
          <td colspan="2"><label><?php echo $this->lang->line('Sensor Type'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
		  <input type="checkbox" style="width:2%" name="sensor_fuel" id="sensor_fuel" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sensor_fuel) && $this->form_model->sensor_fuel==1) || (!isset($this->form_model->sensor_fuel)))?"checked='checked'":""; ?> value=1 onClick="sensor_fuel_change()"/> <?php echo $this->lang->line('FUEL'); ?>
		  <input type="checkbox" style="width:2%" name="sensor_tempr" id="sensor_tempr" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->sensor_tempr) && $this->form_model->sensor_tempr==1) || (!isset($this->form_model->sensor_tempr)))?"checked='checked'":""; ?> value=1 onClick="sensor_tempr_change()"/><?php echo $this->lang->line('TEMPERATURE'); ?>
		   <input type="checkbox" style="width:2%" name="fuel_in_out_sensor" id="fuel_in_out_sensor" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->fuel_in_out_sensor) && $this->form_model->fuel_in_out_sensor==1) || (!isset($this->form_model->fuel_in_out_sensor)))?"checked='checked'":""; ?> value=1 onClick="fuel_in_out_sensor_change()"/><?php echo $this->lang->line('fuel_in_out_sensor'); ?>
		   <input type="checkbox" style="width:2%" name="xyz_sensor" id="xyz_sensor" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->xyz_sensor) && $this->form_model->xyz_sensor==1) || (!isset($this->form_model->xyz_sensor)))?"checked='checked'":""; ?> value=1 /><?php echo $this->lang->line('xyz_sensor'); ?>
		   <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <?php echo $this->lang->line('rollover_tilt'); ?><input type="checkbox" style="width:2%" name="rollover_tilt" id="rollover_tilt" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->rollover_tilt) && $this->form_model->rollover_tilt==1) || (!isset($this->form_model->rollover_tilt)))?"checked='checked'":""; ?> value=1 />
		   <?php echo $this->lang->line('panic'); ?><input type="checkbox" style="width:2%" name="panic" id="panic" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->panic) && $this->form_model->panic==1) || (!isset($this->form_model->panic)))?"checked='checked'":""; ?> value=1 />
		   <?php echo $this->lang->line('runtime'); ?><input type="checkbox" style="width:2%" name="runtime" id="runtime" class="text ui-widget-content ui-corner-all" <?php echo ((isset($this->form_model->runtime) && $this->form_model->runtime==1) || (!isset($this->form_model->runtime)))?"checked='checked'":""; ?> value=1 />
            </td>
        </tr>
		
		<!--tr id="fuel_display" style="display:none">
			<td><label><?php echo $this->lang->line('Max Fuel Dropout'); ?></label>
			<input type="text" name="max_fuel_dropout" id="max_fuel_dropout" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_fuel_dropout; ?>" style="width:80%"/>&nbsp;&nbsp;<strong>Ltr.</strong></td>
        </tr-->
		<tr class="fuel_display" style="display:none">
			<td colspan="2">
				<table width="100%">
					<tr>
						<td width="33%"><label>Tank Type</label>
						<select onchange="changeTankType(this.value)" name="tank_type" id="tank_type" class="select ui-widget-content ui-corner-all">
							<option title="<?php echo base_url(); ?>/assets/images/" value="horizontal_cylinder" <?php if($this->form_model->tank_type == 'horizontal_cylinder'){ echo "selected='selected'"; } ?>>Horizontal Cylindrical Tank</option>
							<option title="<?php echo base_url(); ?>/assets/images/" value="horizontal_rectangular"<?php if($this->form_model->tank_type == 'horizontal_rectangular'){ echo "selected='selected'"; } ?>>Horizontal Rectangular or Square Tank</option>
							<option title="<?php echo base_url(); ?>/assets/images/" value="vertical_cylinder" <?php if($this->form_model->tank_type == 'vertical_cylinder'){ echo "selected='selected'"; } ?>>Vertical Cylindrical Tank</option>
						</select>
						</td>
						<td width="33%"><label>Max Sensor On Full Tank&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="max_fuel_capacity" id="max_fuel_capacity" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_fuel_capacity; ?>"/></td>
						<td width="33%"><label><?php echo $this->lang->line('Max_fual_liters'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="max_fuel_liters" id="max_fuel_liters" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_fuel_liters; ?>"/></td>
					</tr>
				</table>
			</td>
        </tr>
		<tr class="fuel_display" style="display:none">
			<td colspan="2">
				<table width="100%">
					<tr>
						<td width="33%"><label>Tank Diameter/Height&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="tank_diameter" id="tank_diameter" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->tank_diameter; ?>"/></td>
						<td class="tank_width" style="display:none" width="33%"><label>Tank Width&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="tank_width" id="tank_width" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->tank_width; ?>"/></td>
						<td width="33%"><label>Tank Length&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="tank_length" id="tank_length" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->tank_length; ?>"/></td>
						
					</tr>
				</table>
			</td>
        </tr>
		<tr id="tempr_display" style="display:none">
          <td><label><?php echo $this->lang->line('Min Temprature Alert'); ?></label><input type="text" name="min_temprature" id="min_temprature" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->min_temprature; ?>"/>		  
           </td>
		   <td><label><?php echo $this->lang->line('Min Temprature Alert'); ?></label>
		  <input type="text" name="max_temprature" id="max_temprature" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->max_temprature; ?>"/>		  
           </td>		
        </tr>
		<tr class="fuel_in_out_sensor_display" style="display:none">
          <td><label><?php echo $this->lang->line('fuel_in_per_lit'); ?></label><input type="text" name="fuel_in_per_lit" id="fuel_in_per_lit" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fuel_in_per_lit; ?>"/>		  
           </td>
		   <td><label><?php echo $this->lang->line('fuel_in_company_name'); ?></label>
		  <input type="text" name="fuel_in_company_name" id="fuel_in_company_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fuel_in_company_name; ?>"/>		  
           </td>		
        </tr>
		<tr class="fuel_in_out_sensor_display" style="display:none">
          <td><label><?php echo $this->lang->line('fuel_in_product_code'); ?></label><input type="text" name="fuel_in_product_code" id="fuel_in_product_code" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fuel_in_product_code; ?>"/>		  
           </td>
		   <td>&nbsp;</td>
        </tr>
		<tr class='fuel_in_out_sensor_display' style='display:none'>
			<td><label><?php echo $this->lang->line('fuel_out_per_lit'); ?></label>
		  <input type="text" name="fuel_out_per_lit" id="fuel_out_per_lit" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fuel_out_per_lit; ?>"/>		  
           </td>
		    <td><label><?php echo $this->lang->line('fuel_out_company_name'); ?></label><input type="text" name="fuel_out_company_name" id="fuel_out_company_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fuel_out_company_name; ?>"/>		  
           </td>
		</tr>
		<tr class="fuel_in_out_sensor_display" style="display:none">
		   <td><label><?php echo $this->lang->line('fuel_out_product_code'); ?></label>
		  <input type="text" name="fuel_out_product_code" id="fuel_out_product_code" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->fuel_out_product_code; ?>"/>		  
           </td>	
<td>&nbsp;</td>		   
        </tr>
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit" />
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_assets()" name="btn_cancel" value="<?php echo $this->lang->line('Back'); ?>" /></td>
		</tr>
      </tbody>
    </table>
  </form>
</div>
<div id="Assets_icon_dialog<?php echo $tim; ?>" class="assestimage_oad"></div>
<script type="text/javascript">
$(document).ready(function(){
$("#loading_dialog").dialog("close");
	jQuery("input:button, input:submit, input:reset").button();	
	var typeval=$("#assets_type_id").val();
	getAssetsCategory(typeval);
	// getAssetsGroup();
	$("#Assets_icon_dialog<?php echo $tim; ?>").dialog({
		autoOpen: false,
		modal: true,
		height: 'auto',
		width:'70%',
		draggable: true,
		title:'Choose Marker Icon',
		resizable: true,
	});
	$("#driver_mobile").NumericOnly();
	$("#getIconList").click(function(){
		$("#Assets_icon_dialog<?php echo $tim; ?>").dialog("open");
		if($("#Assets_icon_dialog<?php echo $tim; ?>").html()=="")
		{
			$("#Assets_icon_dialog<?php echo $tim; ?>").html("<div style='text-align:center;verticle-align:middle' id='Imgloading'><img src='<?php echo base_url(); ?>/assets/images/loading.gif'> Loading...</div>");	
			$.post("<?php echo base_url(); ?>index.php/assets/getIco",function(data){
				if(data!=""){
					//$("#Assets_icon_dialog<?php echo $tim; ?>").append(data);
					$("#Assets_icon_dialog<?php echo $tim; ?>").html(data);
				}
				else
					$("#Assets_icon_dialog<?php echo $tim; ?>").html("");
			});
		}
	});	
	sensor_tempr_change();
	sensor_fuel_change();
});
$(".ddTitle").height(22);
$(".ddTitle img").height(22);
$("#min_temprature").DecimalOnly();
$("#max_temprature").DecimalOnly();
$("#device_id").NumericOnly();

</script>