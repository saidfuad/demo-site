<?php
include('image_resizer.php');
$uploaddir 	= 'assets/';
$uploaddir1 	= 'assets/marker-images/';

//$___debug->log($uploaddir, 'output');

if(! file_exists($uploaddir)) {
	mkdir($uploaddir);
}
if(! file_exists($uploaddir1)) {
	mkdir($uploaddir1);
}

$file_name = $uploaddir . basename($_FILES['uploadfile']['name']); 
$file_name1 = $uploaddir1 . basename($_FILES['uploadfile']['name']); 
$gray_file = $uploaddir ."gray_". basename($_FILES['uploadfile']['name']); 
$gray_file1 = $uploaddir1 ."gray_". basename($_FILES['uploadfile']['name']); 
$file= preg_replace('" "', '_', $file_name);
$file1= preg_replace('" "', '_', $file_name1);
$gray_file= preg_replace('" "', '_', $gray_file);

//$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
$size = $_FILES['uploadfile']['size'];

if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file) && move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file1)) { 
echo "success"; 
} else {
	echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
        echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file1."($size)";
}

$image = new SimpleImage();
$image->load($file);
$image->load($file1);
//$image->resizeToWidth(120);
$image->save($file);
$image->save($file1);
//$image->grayscale();
//$image->save($gray_file);

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