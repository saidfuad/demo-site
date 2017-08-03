<?php 
include('image_resizer.php');
include('db.php');
$userid=$_REQUEST['id'];
if(!empty($_FILES['pic'])){
$pic = $_FILES['pic'];
$msg="";
$path="./assets/upload_image/Images_upload/";

$file=$pic['name'];
$timestamp = time();
$filetype=$pic['type'];
$pos = strrpos($file,".");
$user_image_up="";
	$imgnamedrag = substr($file,0,$pos)."_$timestamp".substr($file,$pos);
	$ext = substr($file ,strrpos($file,".") + 1);
	$extent = strtolower($ext);
	if(($extent == 'jpg') || ($extent == 'png') || ($extent == 'jpeg') || ($extent == 'gif') || ($extent == 'bmp'))
	{
			move_uploaded_file($pic['tmp_name'], $path.$imgnamedrag);
			/*$image = new SimpleImage();
			$image->load($path.$imgnamedrag);
			$image->resize(104,104);
			$image->save($path.$imgnamedrag);*/
			$query="update tbl_users set photo='".$imgnamedrag."' where user_id=$userid";
			$result=mysql_query($query);
			$data['msg'] = "File Uploaded"; 
			$data['file'] = $imgnamedrag;
				die(json_encode($data));
			return json_encode($data);
			
	}else{
		 $data['msg'] = "not valid img"; 
			return json_encode($data);
		 
	}
}else{
$imgname=$_REQUEST['d'];
$msg="";
$target_path="./assets/upload_image/Images_upload/";
$timestamp = time();
$file = $_FILES['file']['name'];
$file_type  = $_FILES['file']['type'];
$pos = strrpos($file,".");
$Excel_file = "";
//?heck that we have a file
if((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0)) {
	//Check if the file is excel
	$exts = substr($file, strrpos($file, '.') + 1);
	//if (($ext == "xls") && ($_FILES["file"]["type"] == "application/vnd.ms-excel")){
		$ext = strtolower($exts);
	if (($ext == "jpg") || ($ext == "jpeg") || ($ext == "png") || ($ext == "bmp") || ($ext == "gif")){
		$Excel_file = substr($file,0,$pos)."_$timestamp".substr($file,$pos);
		$target_path = $target_path . $imgname;
		if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			/*$image = new SimpleImage();
				$image->load($target_path);
				$image->resize(104,104);
				$image->save($target_path);*/
			$msg = "File&nbsp;Uploaded"; // $Excel_file . " uploaded";
?>
<script type="text/javascript" language="javascript">
parent.$('#uploadedfile').html('<?php echo $Excel_file;?>');
</script>
<?php
		}else{
			$msg =  "Error: Uploading File";
		}
	}else{
		$msg =  "Error: Only .xls or .xlsx files";
	}
}else{
	$msg = "Error: No file uploaded";
}
?>
<script type="text/javascript" language="javascript">
parent.$('#uploadmsg').html('<font color="red"><?php echo $msg;?></font>');
</script>
<?php
if($Excel_file==""){
?>
<script type="text/javascript" language="javascript">
parent.$('#uploadedfile').html('');
</script>
<?php
}
}
?>


