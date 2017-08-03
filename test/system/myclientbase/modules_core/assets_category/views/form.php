<?php  ini_set( 'memory_limit', '1024M' );
ini_set('gd.jpeg_ignore_warning', 1);
?>
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
loadDropdown();
</script>

<?php if($this->form_model->assets_cat_name == ""){ ?>
<h3 class="title_black"><?php echo $this->lang->line("Create Assets Category"); ?></h3>
<?php }else{ ?>
<h3 class="title_black"><?php echo $this->lang->line("Update Assets Category"); ?></h3>
<?php } ?>
<?php $this->load->view('dashboard/system_messages'); ?>
<div id="assets_category_error" class="error" style="display:none"><?php echo $this->lang->line("Assets category Already Exist"); ?>.!</div>
<div class="content toggle" align="center">
  <form id="frm_assets_category" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormAssetsCategory('<?php echo uri_assoc('id');?>')" enctype="multipart/form-data">
    <p id="error" class="addTips">* <?php echo $this->lang->line("Fields_are_mendatory"); ?></p>
    <table width="70%" align="center" class="formtable">
      <tbody>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Asset Category Name"); ?>*</td>
          <td width="50%">
            <input type="text" name="assets_cat_name" id="assets_cat_name" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->assets_cat_name; ?>" onblur="check_assets_category(<?php echo uri_assoc('id');?>)"/></td>
        </tr>
        <tr>
          <td width="20%">
          <?php echo $this->lang->line("Asset Category Type"); ?>*</td>
          <td width="50%">
            <select name="assets_type_id" id="assets_type_id" class="text ui-widget-content ui-corner-all">
            <?php if(!$this->form_model->assets_type_id){ ?>
            <option value=""><?php echo $this->lang->line("Please Select"); ?></option> <?php }
			foreach($typecombo as $rs)
			{?>
				<option value="<?php echo $rs['id']; ?>" <?php echo ($rs['id']==$this->form_model->assets_type_id)?"selected=selected":""; ?> ><?php echo $rs['name'];?></option>
			<?php }
            ?>
            </select></td>
        </tr>
		<tr>
		<td width="20%"><?php echo $this->lang->line("Asset Category Image"); ?></td>
		<td width="50%"><div style="width:50%;display:inline-block;"><input type='radio' id="asset" name='assets_cat_image' value='any' onClick="alert_toggle(0)"  <?php echo set_radio('assets_cat_image', '1', TRUE); ?>></div></td>
              </tr>
	      
	     <tr>
	     <td width="20%"></td>
	      <td width="50%">
	      
	       <div id="asset_cat">   
            <select name="assets_cat_image" id="assets_cat_image" class="text ui-widget-content ui-corner-all">
            <?php 
				$query = $this->db->query("SELECT * from assests_category_images where del_date is null and status = 1", FALSE);
				$rows = $query->result();
				$images = '';
				$sel = '';
				if(count($rows) > 0) {
					foreach ($rows as $row) {
						if($row->image_path == $this->form_model->assets_cat_image)
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
			</div>
			</td>
			</tr>
			
			<tr>
			<td width="20%"><?php echo $this->lang->line("Upload Asset Category Image"); ?></td>
			<td width="50%"><div style="width:50%;display:inline-block;"><input type='radio' id="asset1" name='assets_cat_image1' value='given' onClick="alert_toggle(1)" <?php if ($this->form_model->assets_cat_image1 == 'given') echo "checked='checked'"; ?>></div></td>
			</tr>
			<tr>
			<td width="20%"></td>
			<td width="50%">
          
		  <div id="driverupload-control">
                            <input type="button" id="driver_button_uploader" /> 
                            <input type="Hidden" id="assets_cat_image1" name="assets_cat_image1" value='<?php echo $this->form_model->assets_cat_image1; ?>' />
                            <ol id="driverupld" style='float: right; width: 321px;'>
                           <?php if ($this->form_model->assets_cat_image1 != "") { ?>
                                    <li style="color:red;" class='success'><?php echo $this->lang->line('File'); ?>: <em><?php echo $this->form_model->assets_cat_image1; ?></em>&nbsp;&nbsp;<span class="progressvalue" >100%</span><span class="status"><?php echo $this->lang->line('Done'); ?>!!! | <a href="<?php echo base_url(); ?>/assets/<?php $this->form_model->assets_cat_image1; ?>" target="_blank" ><?php echo $this->lang->line('view'); ?> &raquo;</a></span>
                             <?php } ?>
                            </ol>
                         <span><?php echo $this->lang->line("maximum size of image is 35 * 35 pixel");?></span>
                        </div>
			
            </td>
	 
	 
	   
        </tr>
        <!-- <tr>
          <td width="20%">
          <?php echo $this->lang->line("Status"); ?></td>
          <td width="50%">
          <select name="assets_status" id="assets_status" class="text ui-widget-content ui-corner-all">
           <option value="1" <?php //echo ($this->form_model->assets_status==1)?"selected=selected":""; ?>>Active</option>        
            <option value="0" <?php //echo ($this->form_model->assets_status==0)?"selected=selected":""; ?>>Inactive</option>		 	 </select>
        </td> 
        </tr>-->
        <tr>
          <td align="center" colspan="2"><input type="submit" id="btn_assets_category_submit" value="<?php echo $this->lang->line('submit'); ?>" name="btn_assets_category_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_assets_category()" name="btn_cancel" value="<?php echo $this->lang->line("Back"); ?>" /></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<style>
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
	jQuery("#assets_cat_image").msDropDown();
//	$("#assets_type_loading").dialog("close");
		

});

$(".ddTitle").height(22);
$(".ddTitle img").height(22);
/*

var name = $("#name");
allFields = $([]).add(name);
tips = $("#error");*/
<?php if ($this->form_model->assets_cat_image == 'any' || $this->form_model->assets_cat_image == ' ' || $this->form_model->assets_cat_image == null) { ?>
                    $("#asset_cat").show();
		    $("#driverupload-control").hide();
		     //$("#assets_cat_image1").val(" "); 
<?php } ?>

<?php if($this->form_model->assets_cat_image1 == 'given') { ?>
                    $("#driverupload-control").hide();
		     $("#asset_cat").hide();
		     //$("#assets_cat_image").val(" ");
<?php } ?>

function alert_toggle(val) {
//alert(val);
                if (val == 0) {
		    $("#asset_cat").show();
		    $("#driverupload-control").hide();
		    document.getElementById('asset1').checked = false;
		    
		    } 
		else
		{
		$("#driverupload-control").show();
		$("#asset_cat").hide();
		 document.getElementById('asset').checked = false;
			
		}  
            }

 $('#driverupload-control').swfupload({
            upload_url: "<?php echo base_url(); ?>simple_swf_upload1.php",
            file_post_name: 'uploadfile',
            file_size_limit: "1 MB",
            file_types: "*.jpg;*.gif;*.png;*.bmp",
            file_types_description: "Web Image Files",
            file_upload_limit: 25,
            flash_url: "<?php echo base_url(); ?>/assets/swfupload/swfupload.swf",
            button_image_url: '<?php echo base_url(); ?>/assets/swfupload/wdp_buttons_upload_114x29.png',
            button_width: 114,
            button_height: 29,
            button_placeholder: $('#driver_button_uploader')[0],
            debug: false
        })
                .bind('fileQueued', function (event, file) {

                    // start the upload since it's queued
                    $(this).swfupload('startUpload');
                })
                .bind('fileQueued', function (event, file) {
                    var name = file.name;
                    name = name.replace(/ /gi, '_');
                    var listitem = '<li style="color:red;" id="' + file.id + '" >' +
                            'File: <em>' + name + '</em>&nbsp;&nbsp;(' + Math.round(file.size) + ' KB) <span class="progressvalue" ></span><span class="status"></span>'
                    $('#driverupld').html(listitem);
                    $('li#' + file.id + ' .cancel').bind('click', function () { //Remove from queue on cancel click  
                        //swfu.cancelUpload(file.id);   
                        //alert($("#driverupld li#"+file.id).html());
                        $("#driverupld li#" + file.id).remove();
                        $('li#' + file.id).slideUp('fast');

                    });
                    // start the upload since it's queued  
                    $(this).swfupload('startUpload');
                })
                .bind('uploadProgress', function (event, file, bytesLoaded) {
                    //Show Progress
                    var percentage = Math.round((bytesLoaded / file.size) * 100);
                    $('#driverupld li#' + file.id).find('div.progress').css('width', percentage + '%');
                    $('#driverupld li#' + file.id).find('span.progressvalue').text(percentage + '%');
                })
                .bind('uploadSuccess', function (event, file, serverData) {
                    var name = file.name;
                    name = name.replace(/ /gi, '_');
                    var item = $('#driverupld li#' + file.id);
                    item.find('div.progress').css('width', '100%');
                    item.find('span.progressvalue').text('100%');
                    var pathtofile = '<a href="<?php echo base_url(); ?>/assets/' + name + '" target="_blank" ><?php echo $this->lang->line("view &raquo"); ?>;</a>';
                    item.addClass('success').find('span.status').html('  Done!!! | ' + pathtofile);
                    $('#assets_cat_image1').val(name);
                })
                .bind('uploadComplete', function (event, file) {
                    // upload has completed, try the next one in the queue
                    $(this).swfupload('startUpload');
                });
    $("#loading_top").css("display", "none");
</script>