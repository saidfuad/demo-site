<?php
//include('upload_smarter.php');
$uploaddir 	= 'assets/assets_photo/';

//$___debug->log($uploaddir, 'output');

if(! file_exists($uploaddir)) {
	mkdir($uploaddir);
}
/*
$file_name = $uploaddir . basename($_FILES['uploadfile']['name']); 
$gray_file = $uploaddir ."gray_". basename($_FILES['uploadfile']['name']); 
$file= preg_replace('" "', '_', $file_name);
$gray_file= preg_replace('" "', '_', $gray_file);

//$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
$size = $_FILES['uploadfile']['size'];
smart_resize_image($_FILES['uploadfile'],100,100);
*/
<?php
// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$fileName = $_FILES["uploadfile"]["name"]; // The file name
$fileTmpLoc = $_FILES["uploadfile"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["uploadfile"]["type"]; // The type of file it is
$fileSize = $_FILES["uploadfile"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["uploadfile"]["error"]; // 0 for false... and 1 for true
$kaboom = explode(".", $fileName); // Split file name into an array using the dot
$fileExt = end($kaboom); // Now target the last array element to get the file extension
// START PHP Image Upload Error Handling --------------------------------------------------
if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
} else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
    echo "ERROR: Your file was larger than 5 Megabytes in size.";
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    exit();
} else if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
     // This condition is only if you wish to allow uploading of specific file types    
     echo "ERROR: Your image was not .gif, .jpg, or .png.";
     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
     exit();
} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
    echo "ERROR: An error occured while processing the file. Try again.";
    exit();
}
// END PHP Image Upload Error Handling ----------------------------------------------------
// Place it into your "uploads" folder mow using the move_uploadfile() function
$moveResult = move_uploadfile($fileTmpLoc, "assets/assets_photo/$fileName");
// Check to make sure the move result is true before continuing
if ($moveResult != true) {
    echo "ERROR: File not uploaded. Try again.";
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    exit();
}
unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
// ---------- Include Adams Universal Image Resizing Function --------
include_once("ak_php_img_lib_1.0.php");
$target_file = "assets/assets_photo/$fileName";
$resized_file = "assets/assets_photo/resized_$fileName";
$wmax = 200;
$hmax = 150;
ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
// ----------- End Adams Universal Image Resizing Function -----------
// Display things to the page so you can see what is happening for testing purposes
echo "The file named <strong>$fileName</strong> uploaded successfuly.<br /><br />";
echo "It is <strong>$fileSize</strong> bytes in size.<br /><br />";
echo "It is an <strong>$fileType</strong> type of file.<br /><br />";
echo "The file extension is <strong>$fileExt</strong><br /><br />";
echo "The Error Message output for this upload is: $fileErrorMsg";
?>

<!-- -------------------------------------------- -->
<!-- "ak_php_img_lib_1.0.php" -->
<!-- -------------------------------------------- -->

<?php
// Adam Khoury PHP Image Function Library 1.0
// Function for resizing any jpg, gif, or png image files
function ak_img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 80);
}
?> 
/*
if (move_uploadfile($_FILES['uploadfile']['tmp_name'], $file)) { 
echo "success"; 
} else {
	echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
}*
/*
$image = new SimpleImage();
$image->load($file);
$image->resizeToWidth(120);
$image->save($file);
$image->grayscale();
$image->save($gray_file);

/*
call the function if the file is uploaded successfully.
param 1 : The original image path with filename
param 2 : The thumb image path with filenam
param 3 : The New Width For the Thumb Image
param 4 : The New Height For the Thumb Image.
*/
/*
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}*/
?>