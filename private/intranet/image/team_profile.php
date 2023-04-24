<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>


<?php
function createTeamPicture($imgOriginal, $imgType, $name)
{
    //var
    $cdnPath = $_SERVER["DOCUMENT_ROOT"]."/../cdn/profile/team/picture/";
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

    //move images
    imagejpeg($image,  $cdnPath.$name.'-full.jpg');
    imagejpeg($image1, $cdnPath.$name.'-1000.jpg');
    imagejpeg($image2, $cdnPath.$name.'-512.jpg');

    //destroy images
    imagedestroy($image);
    imagedestroy($image1);
    imagedestroy($image2);
}
?>