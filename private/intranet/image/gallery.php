<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>


<?php
function createImage($imgOriginal, $imgType, $name, $gallery)
{
    //var
    $cdnPath = $_SERVER["DOCUMENT_ROOT"]."/../cdn/gallery/".$gallery."/";
    //create image from inputfile
    if($imgType == "image/jpeg") {
        $image = imagecreatefromjpeg($imgOriginal);
    } elseif ($imgType == "image/png") {
        $image = imagecreatefrompng($imgOriginal);
    }
    
    $imageratio = imagesy($image)/imagesx($image);

    $big = imagescale($image, 2000/$imageratio, 2000, IMG_NEAREST_NEIGHBOUR);
    $thumbnail = imagescale($image, 512/$imageratio, 512,  IMG_NEAREST_NEIGHBOUR);

    //move images
    //imagejpeg($image, $cdnPath.'original/'.$name.'.jpg');
    copy($imgOriginal, $cdnPath.'original/'.$name.'.jpg');
    imagejpeg($big, $cdnPath.'images/'.$name.'.jpg');
    imagejpeg($thumbnail, $cdnPath.'thumbnail/'.$name.'.jpg');

    //destroy images
    //imagedestroy($image);
    imagedestroy($big);
    imagedestroy($thumbnail);
}
?>