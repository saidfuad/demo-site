<script type="text/javascript" src="assets/jquery/jquery.js"></script>
<?php
$msg="";
$target_path = "Excel/files/";
$timestamp = time();
$filename = $_FILES['filename']['name'];
$file_type  = $_FILES['filename']['type'];
$pos = strrpos($filename,".");
$Excel_file = "";
//?heck that we have a file
if((!empty($_FILES["filename"])) && ($_FILES['filename']['error'] == 0)) {
	//Check if the file is excel
	$ext = substr($filename, strrpos($filename, '.') + 1);
	//if (($ext == "xls") && ($_FILES["filename"]["type"] == "application/vnd.ms-excel")){
	if (($ext == "xls") || ($ext == "XLS") || ($ext == "xlsx") ){
		$Excel_file = substr($filename,0,$pos)."_$timestamp".substr($filename,$pos);
		$target_path = $target_path . $Excel_file;
		if(move_uploaded_file($_FILES['filename']['tmp_name'], $target_path)) {
			$msg = $Excel_file . " uploaded";
?>
<script type="text/javascript" language="javascript">
parent.$('#uploadedfile_import_locations').html('<?php echo $Excel_file;?>');
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
parent.$('#uploadmsg_import_locations').html('<font color="red"><?php echo $msg;?></font>');
</script>
<?php
if($Excel_file==""){
?>
<script type="text/javascript" language="javascript">
parent.$('#uploadedfile_import_locations').html('');
</script>
<?php
}
?>
