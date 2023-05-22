<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>


<?php
function createLogo($imgOriginal, $imgType, $name)
{
    //var
    $cdnPath = $_SERVER["DOCUMENT_ROOT"]."/../cdn/firmung/logo/";
    //create image from inputfile
    if($imgType == "image/jpeg") {
        $image = imagecreatefromjpeg($imgOriginal);
    } elseif ($imgType == "image/png") {
        $image = imagecreatefrompng($imgOriginal);
    } elseif ($imgType == "image/gif") {
        $image = imagecreatefromgif($imgOriginal);
    }
    
    $imageratio = imagesy($image)/imagesx($image);

    $big = imagescale($image, 2000/$imageratio, 2000, IMG_NEAREST_NEIGHBOUR);
    $thumbnail = imagescale($image, 512/$imageratio, 512,  IMG_NEAREST_NEIGHBOUR);

    //move images
    imagepng($image,  $cdnPath.$name.'.png');

    //destroy images
    imagedestroy($image);
}
?>