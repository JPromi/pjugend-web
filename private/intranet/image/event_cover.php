<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>


<?php
function createEventCover($imgOriginal, $imgType, $name)
{
    //var
    $cdnPath = $_SERVER["DOCUMENT_ROOT"]."/../cdn/event/image/";
    //create image from inputfile
    if($imgType == "image/jpeg") {
        $image = imagecreatefromjpeg($imgOriginal);
    } elseif ($imgType == "image/png") {
        $image = imagecreatefrompng($imgOriginal);
    }
    
    $imageratio = imagesy($image)/imagesx($image);

    //$imageCrop = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
    $image1 = imagescale($image, 1000/$imageratio, 1000, IMG_NEAREST_NEIGHBOUR);
    $image2 = imagescale($image, 512/$imageratio, 512,  IMG_NEAREST_NEIGHBOUR);
    $image3 = imagescale($image, 256/$imageratio, 256,  IMG_NEAREST_NEIGHBOUR);

    //move images
    imagejpeg($image,  $cdnPath.$name.'-full.jpg');
    imagejpeg($image1, $cdnPath.$name.'-1000.jpg');
    imagejpeg($image2, $cdnPath.$name.'-512.jpg');
    imagejpeg($image3, $cdnPath.$name.'-256.jpg');

    //destroy images
    imagedestroy($image);
    imagedestroy($image1);
    imagedestroy($image2);
    imagedestroy($image3);
    imagedestroy($image4);
}
?>