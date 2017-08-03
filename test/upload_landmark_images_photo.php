<?php
include('image_resizer.php');
$uploaddir 	= 'assets/landmark_images/';

//$___debug->log($uploaddir, 'output');

if(! file_exists($uploaddir)) {
	mkdir($uploaddir);
}

$file_name = $uploaddir . basename($_FILES['uploadfile']['name']); 
$file= preg_replace('" "', '_', $file_name);


//$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
$size = $_FILES['uploadfile']['size'];

if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
echo "success"; 
} else { 
	echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
}

$image = new SimpleImage();
$image->load($file);
$image->save($file);

/*
call the function if the file is uploaded successfully.
param 1 : The original image path with filename
param 2 : The thumb image path with filenam
param 3 : The New Width For the Thumb Image
param 4 : The New Height For the Thumb Image.
*/
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}
?>