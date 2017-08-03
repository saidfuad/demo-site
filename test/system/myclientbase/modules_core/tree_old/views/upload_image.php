
<style type="text/css">

	#Model_main{
	position: fixed; width: 100%; height: 100%; background-color: #eef0ee; z-index: 1; opacity: 0.7;z-index:1000010 

!important;display:none;
	}
	#model{
		background-color: #FFFFFF; width: 750px; height: 500px; top:12%; left:12%;
		position:fixed;z-index:1000011 !important;display:none;
		border-radius : 5px;
		box-shadow : 0 0 10px #aaa;
		-moz-border-radius : 5px;
		-o-border-radius : 5px;
		-webkit-border-radius : 5px;
		-ms-border-radius : 5px;
	}

	.drag_drop{
			width:695px;
			height:361px;
			position : absolute;
			left : 3%;
			top : 15%;
			border : 4px dashed #ccc;
			
	}
	.drag_text{
		font-weight : normal;
		text-align : center;
		margin-top : 136px;
		font-size : 50px;
		color : #ddd;
	
	}
	.user_image{
		position : absolute;
		width:150px;
		height:172px;
		margin : 178px 0 0 10px;
	}

#dropbox .message{
	font-size: 11px;
    text-align: center;
    padding-top:160px;
    display: block;
}

#dropbox .message i{
	color:#ccc;
	font-size:10px;
}

#dropbox:before{
	border-radius:3px 3px 0 0;
}
#dropbox .preview{
	width:245px;
	height: 215px;
	float:left;
	margin: -189px 0 0 235px;
	position: fixed;
	text-align: center;
}
#user_history{
	background-color: blue; margin: 0px 0px 0px 99px; color: white; width: 95px; text-align: center; height: 26px; cursor:pointer;
}
#user_history p{
	padding:5px;
}
.preview span img{
	width:100px;
	height: 100px;
}
#dropbox .preview img{
	max-width: 240px;
	max-height:180px;
	display: block;
	box-shadow:0 0 2px #000;
}

#dropbox .imageHolder{
	display: inline-block;
	position:relative;
}

#dropbox .uploaded{
	position: absolute;
	top:0;
	left:0;
	height:100%;
	width:100%;
	display: none;
}

#dropbox .preview.done .uploaded{
	display: block;
}
#uploadedfiledrag{
	display : none;
}

	</style>
	
<script>
$(function(){
	var dropbox = $('#dropbox'),
		message = $('.message', dropbox);
		var use_id=$("#uploadedfiledrag").html();
		
	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',
		
		maxfiles: 1,
    	maxfilesize: 2,
		url: '<?php echo base_url(); ?>upload.php?id='+use_id,
		
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			
			$.post(
		"<?php echo base_url(); ?>/index.php/home/get_photo",function(result)
		{
			if(result == ""){
				$(".imageHolder").html("<img src='<?php echo base_url(); ?>assets/driver-photo/not_available.jpg' class='user_img_set' alt='image'></img>");
			}else{
				$(".imageHolder").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"' class='user_img_set' alt='image'></img>");
				$("#img_upload_setid").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"");
				$("#chage_profile_photo").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"");
				$("#user_img_form a").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"' class='user_img_set' alt='image' width='148'></img>");
			}	
		}
	);
			// response is the JSON object that post_file.php returns
		},
		
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('<?php echo $this->lang->line("Your browser does not support HTML5 file uploads!"); ?>');
					break;
				case 'TooManyFiles':
					alert('<?php echo $this->lang->line("Too many files! Please select 1 at most! (configurable)"); ?>');
					break;
				case 'FileTooLarge':
					alert(file.name+' <?php echo $this->lang->line("is too large! Please upload files up to 2mb (configurable)"); ?>.');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alert('<?php echo $this->lang->line("only image jpeg,png,bmp,gif  allow"); ?>');
				
				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
			createImage(file);
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
    	 
	});
	
	var template = 	'<div class="preview">'+
						'<span class="imageHolder">'+
							'<img />'+
							'<span class="uploaded"></span>'+
						'</span>'+
						'<div class="progressHolder">'+
							'<div class="progress"></div>'+
						'</div>'+
						'</div>'; 
	
	
	function createImage(file){

		var preview = $(template), 
			image = $('img', preview);
			
		var reader = new FileReader();
		
		image.width = 100;
		image.height = 100;
		
		reader.onload = function(e){
			
			// e.target.result holds the DataURL which
			// can be used as a source of the image:
			
			image.attr('src',e.target.result);
		};
		
		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);
		
		message.hide();
		preview.appendTo(dropbox);
		
		// Associating a preview container
		// with the file, using jQuery's $.data():
		
		$.data(file,preview);
	}
	function showMessage(msg){
		message.html(msg);
	}

});
</script>
<script type="text/javascript">
function uload_img(value){
	var Imgname= value.split("\\");
	var img_name_split="";
	if(Imgname.length  == 3)
	{
		img_name_split = Imgname[2];
	}else{
		img_name_split = Imgname[0];
	}

	var da=new Date();
	var img_name=da.format("Hms")+Math.floor((Math.random()*999999)+1)+img_name_split;

	$("#user_img").attr("action","<?php echo base_url(); ?>upload.php?d="+img_name);
	$("#user_img").submit();
	iTimeOut = setTimeout('getStatus();',500);
	$.post(
		"<?php echo base_url(); ?>index.php/home/put_photo",{data : img_name},function(resulrt){
			if(resulrt == null || resulrt == ""){
			
			}else{
				alert('<?php echo $this->lang->line("only image jpeg,png,bmp,gif  allow"); ?>');
			}
		}
	);
	$.post(
		"<?php echo base_url(); ?>/index.php/home/get_photo",function(result)
		{
			if(result == ""){
				$(".imageHolder").html("<img src='<?php echo base_url(); ?>assets/driver-photo/not_available.jpg' class='user_img_set' alt='image'></img>");
			}else{
				$(".imageHolder").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"' class='user_img_set' alt='image'></img>");
				$("#img_upload_setid").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"");
				$("#chage_profile_photo").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"");
				$("#user_img_form a").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/"+result+"' class='user_img_set' alt='image' width='148'></img>");
			}	
		}
	);		
 }
 function getStatus(){
 		if($("#uploadedfile").html()==""){
 			iTimeOut = setTimeout('getStatus();',500);
 		}else{
 			clearTimeout(iTimeOut);
 			fillDetails();
 		}
 }
 function fillDetails()
 {
	var file = $('#uploadedfile').html();
 }
</script>
<div id="Model_main">
		</div>
		<div id="model">
		
			<img src="<?php echo base_url(); ?>assets/upload_image/close.png" alt="close" style='height: 10px;cursor:pointer;float : right; padding:10px;' id="upload_close" onclick="profile_div_close();"/>
				<h1 style="text-align : center;  font-weight : normal; font-size : 2.25em !important; margin-top : 10px;"><?php echo $this->lang->line("Select profile Image"); ?></h1>
				<hr/>
							<div id="uploadedfiledrag">
								<?php
									echo $user_id_photo = $this->session->userdata('user_id');
								?>
							</div>
						<div class="drag_drop" id="dropbox">
							<h1 class="drag_text"><?php echo $this->lang->line("Drag a profile photo here"); ?></h1>
							<div id="file" style="position : fixed; width: 255px; height: 25px; margin: 0px 0px 0px 250px; position: fixed;">
								<form id="user_img" target="hiddenframe" enctype="multipart/form-data" action="<?php echo base_url(); ?>index.php/ajax/upload" method="POST" onSubmit="return true">
								
								<input type="hidden" id="uploadedfile" style='cursor:pointer'>
								<div id="browsebtn" onclick="$('#uploadbox').trigger('click');" style="text-align : center ; position : fixed; width: 221px; margin: 21px 0px 0px 0px; background-color: blue; border-radius : 5px; color :white ; height: 23px; cursor:pointer"><?php echo $this->lang->line("Select Profile Photo"); ?></div>

								<input type="file" class="file" name="file" id="uploadbox" onChange="uload_img(this.value)" style="position: fixed; display:none; cursor: pointer; opacity: 1; margin: 21px 0px 0px 0px;" onchange="uload_img(this.value)" name="file"/>
								<iframe name="hiddenframe" style="display:none"></iframe>
								</form>
							</div>
						<div class="preview">
						<span class="imageHolder">
						</span>
						</div>
				</div>
		</div>
		
