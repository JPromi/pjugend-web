<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>


<?php
function createProfilePicture($imgOriginal, $imgType, $name)
{
    //var
    $cdnPath = $_SERVER["DOCUMENT_ROOT"]."/../cdn/profile/picture/";
    //create image from inputfile
    if($imgType == "image/jpeg") {
        $image = imagecreatefromjpeg($imgOriginal);
    } elseif ($imgType == "image/png") {
        $image = imagecreatefrompng($imgOriginal);
    }
    

    $size = min(imagesx($image), imagesy($image));

    $imageCrop = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
    $image1 = imagescale($imageCrop, 1000, 1000, IMG_NEAREST_NEIGHBOUR);
    $image2 = imagescale($imageCrop, 512, 512, IMG_NEAREST_NEIGHBOUR);
    $image3 = imagescale($imageCrop, 256, 256, IMG_NEAREST_NEIGHBOUR);
    $image4 = imagescale($imageCrop, 128, 128, IMG_NEAREST_NEIGHBOUR);

    //move images
    imagejpeg($image,  $cdnPath.$name.'-full.jpg');
    imagejpeg($image1, $cdnPath.$name.'-1000.jpg');
    imagejpeg($image2, $cdnPath.$name.'-512.jpg');
    imagejpeg($image3, $cdnPath.$name.'-256.jpg');
    imagejpeg($image4, $cdnPath.$name.'-128.jpg');

    //destroy images
    imagedestroy($image);
    imagedestroy($image1);
    imagedestroy($image2);
    imagedestroy($image3);
    imagedestroy($image4);
}
?>