<?php
/**
* @author Joe Okatch, +254720089059, joemackay@gmail.com
* This code can upload all the four image mimetypes and rename the file with a random string name
* Its a modified version of kerala,kollam, Phone No:9037191298, email:sibiraj2007@gmail.com
*/
DEFINE('WIDTH_16', 16);
DEFINE('HEIGHT_16', 16);
DEFINE('WIDTH_32', 32);
DEFINE('HEIGHT_32', 32);
DEFINE('WIDTH_64', 64);
DEFINE('HEIGHT_64', 64);
DEFINE('WIDTH_128', 128);
DEFINE('HEIGHT_128', 128);
DEFINE('WIDTH_256', 256);
DEFINE('HEIGHT_256', 256);
DEFINE('WIDTH_512', 512);
DEFINE('HEIGHT_512', 512);

class Mdl_upload_images extends CI_Model{    
                                         
            // *** Class variables
            private $image;
            private $width;
            private $height;
            private $imageResized;
            public $imageMimeType;
            private $mimeTypes = array("image/gif"=>".gif", "image/jpeg"=>".jpg", "image/png"=>".png", "image/bmp"=>".bmp", 'application/zip'=>'.zip', 'application/x-zip-compressed'=>'.zip', 'multipart/x-zip'=>'.zip', 'application/s-compressed'=>'.zip', );

            //error output
            var $msg;
            
            //image info
            var $imageId;
            var $imageName;
            var $imageSize;
            
            var $NewImageName;
            
            //directory
            var $save_dir;
            
            
            var $dbObj;

    /**
    * Construct
    * 
    * @param mixed $table optional if u want to save info into the db
    * @param mixed $attr also optional if u want to save info into the db
    * @return image
    */
            function __construct() {
                return 0; 
            }
    
            ## --------------------------------------------------------
            
            /**
            * The letter l (lowercase L) and the number 1
            * have been removed, as they can be mistaken
            * for each other.
            */
            function createRandomName() {
                $chars = "abcdefghij_kmnopqrst_uvwxyz_0123456789_ABCDEFG_HIJKLMNPQRS_TUVWXYZ0123_4567890000000";
                srand((double)microtime()*1000000);
                $i = 0;
                $pass = '' ;

                while ($i <= 30) {
                    $num = rand() % 33;
                    $tmp = substr($chars, $num, 1);
                    $pass = $pass . $tmp;
                    $i++;
                }
                return $pass;
            }
            
            ## --------------------------------------------------------

            public function openImage($dir, $formElement) {
                // *** Get extension
                $fileName=$_FILES[$formElement]["name"];
                $filetype=$_FILES[$formElement]["type"];
                $fileTmp=$_FILES[$formElement]["tmp_name"];
                $extension = strtolower(strrchr($fileName, '.'));
                $this->save_dir = $dir;
                
                if(array_key_exists($filetype, $this->mimeTypes)) {
                    switch($extension) {
                        case '.jpg':
                        case '.jpeg':
                            $img = imagecreatefromjpeg($fileTmp);
                            break;
                        case '.gif':
                            $img = imagecreatefromgif($fileTmp);
                            break;
                        case '.png':
                            $img = imagecreatefrompng($fileTmp);
                            break;
                        default:
                            $img = false;
                            break;
                    }
                    //return $img;
                    $this->imageSize = $_FILES[$formElement]["size"];
                    $this->imageMimeType = strtolower($extension);
                    
                    // *** Get width and height
                    $this->width  = imagesx($img);
                    $this->height = imagesy($img);
                    
                    $this->NewImageName = $NewImageName = $this->createRandomName().$extension;
                    
                    $this->resizeImage($img, WIDTH_32, HEIGHT_32);
                    $this->saveImage($NewImageName, $this->save_dir."32/", 100); 
                    
                    $this->resizeImage($img, WIDTH_64, HEIGHT_64);
                    $this->saveImage($NewImageName, $this->save_dir."64/", 100);

                    $this->resizeImage($img, WIDTH_128, HEIGHT_128);
                    $this->saveImage($NewImageName, $this->save_dir."128/", 100); 
                    
					$this->resizeImage($img, WIDTH_256, HEIGHT_256);
                    $this->saveImage($NewImageName, $this->save_dir."256/", 100); 
					
					                  
                } 
                return true;
            }

            /**
            * retain the transparency of the image...
            * 
            * @param mixed $new_image image resource identifier such as returned by imagecreatetruecolor(). must be passed by reference 
            * @param mixed $image_source image resource identifier returned by imagecreatefromjpeg, imagecreatefromgif and imagecreatefrompng. must be passed by reference 
            */
            function setTransparency($new_image, $image_source) {

                $transparencyIndex = imagecolortransparent($image_source);
                $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

                if ($transparencyIndex >= 0) {
                    $transparencyColor    = imagecolorsforindex($image_source, $transparencyIndex);   
                }

                $transparencyIndex    = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
                imagefill($new_image, 0, 0, $transparencyIndex);
                imagecolortransparent($new_image, $transparencyIndex);

            }
 
            
            ## --------------------------------------------------------

            public function resizeImage($image, $newWidth, $newHeight, $option="auto") {
                // *** Get optimal width and height - based on $option
                $optionArray = $this->getDimensions($newWidth, $newHeight, $option);

                $optimalWidth  = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];

                // *** Resample - create image canvas of x, y size
                $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
                //var_dump($this->image); 
                $this->setTransparency($this->imageResized, $image);
                imagecopyresampled($this->imageResized, $image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);

                // *** if option is 'crop', then crop too
                if ($option == 'crop') {
                    $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
                } 
            }

            ## --------------------------------------------------------
            
            public function getDimensions($newWidth, $newHeight, $option) {

               switch ($option) {
                    case 'exact':
                        $optimalWidth = $newWidth;
                        $optimalHeight= $newHeight;
                        break;
                    case 'portrait':
                        $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                        $optimalHeight= $newHeight;
                        break;
                    case 'landscape':
                        $optimalWidth = $newWidth;
                        $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                        break;
                    case 'auto':
                        $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                        $optimalWidth = $optionArray['optimalWidth'];
                        $optimalHeight = $optionArray['optimalHeight'];
                        break;
                    case 'crop':
                        $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                        $optimalWidth = $optionArray['optimalWidth'];
                        $optimalHeight = $optionArray['optimalHeight'];
                        break;
                }
                return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
            }

            ## --------------------------------------------------------

            public function getSizeByFixedHeight($newHeight) {
                $ratio = $this->width / $this->height;
                $newWidth = $newHeight * $ratio;
                return $newWidth;
            }

            public function getSizeByFixedWidth($newWidth) {
                $ratio = $this->height / $this->width;
                $newHeight = $newWidth * $ratio;
                return $newHeight;
            }

            public function getSizeByAuto($newWidth, $newHeight) {
                if ($this->height < $this->width) { // *** Image to be resized is wider (landscape)
                    $optimalWidth = $newWidth;
                    $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                } elseif ($this->height > $this->width) { // *** Image to be resized is taller (portrait)
                    $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    $optimalHeight= $newHeight;
                } else { // *** Image to be resizerd is a square
                    if ($newHeight < $newWidth) {
                        $optimalWidth = $newWidth;
                        $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                    } else if ($newHeight > $newWidth) {
                        $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                        $optimalHeight= $newHeight;
                    } else {
                        // *** Sqaure being resized to a square
                        $optimalWidth = $newWidth;
                        $optimalHeight= $newHeight;
                    }
                }

                return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
            }

            ## --------------------------------------------------------

            public function getOptimalCrop($newWidth, $newHeight) {

                $heightRatio = $this->height / $newHeight;
                $widthRatio  = $this->width /  $newWidth;

                if ($heightRatio < $widthRatio) {
                    $optimalRatio = $heightRatio;
                } else {
                    $optimalRatio = $widthRatio;
                }

                $optimalHeight = $this->height / $optimalRatio;
                $optimalWidth  = $this->width  / $optimalRatio;

                return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
            }

            ## --------------------------------------------------------

            public function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight) {
                // *** Find center - this will be used for the crop
                $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
                $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

                $crop = $this->imageResized;
                //imagedestroy($this->imageResized);

                // *** Now crop from center to exact requested size
                $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
                imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
            }

            ## --------------------------------------------------------

            public function saveImage($NewImageName, $savePath, $imageQuality="100") {
                $extension = $this->imageMimeType;
                $savePath = $savePath.$NewImageName;
                // *** Get extension
                
                //$extension = strrchr($savePath, '.');
                //$extension = strtolower($extension);

                switch($extension) {
                    case '.jpg':
                    case '.jpeg':
                        if (imagetypes() & IMG_JPG) {
                            imagejpeg($this->imageResized, $savePath, $imageQuality);
                        }
                        break;

                    case '.gif':
                        if (imagetypes() & IMG_GIF) {
                            imagegif($this->imageResized, $savePath);
                        }
                        break;

                    case '.png':
                        // *** Scale quality from 0-100 to 0-9
                        $scaleQuality = round(($imageQuality/100) * 9);

                        // *** Invert quality setting as 0 is best, not 9
                        $invertScaleQuality = 9 - $scaleQuality;

                        if (imagetypes() & IMG_PNG) {
                             imagepng($this->imageResized, $savePath, $invertScaleQuality);
                        }
                        break;

                    // ... etc

                    default:
                        // *** No extension - No save.
                        break;
                }

                imagedestroy($this->imageResized);
                
            }


            ## --------------------------------------------------------

}
