<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
include("../../private/config.php");
?>

<?php
if(!(in_array("gallery", $dbSESSION_perm)) || !(in_array("jugendteam_admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php

$hash_id = mysqli_real_escape_string($con_public, stripcslashes($_GET["g"]));
$user_id = $dbSESSION["user_id"];


if(in_array("jugendteam_admin", $dbSESSION_perm)) {
    $selectedGallery = "SELECT * FROM gallery WHERE hash_id = '$hash_id'";
    $selectedGallery = $con_public->query($selectedGallery);
    $selectedGallery = $selectedGallery->fetch_assoc();
    $hash_id = $selectedGallery["hash_id"];
}else if(in_array("gallery", $dbSESSION_perm)) {
    $selectedGallery = "SELECT * FROM gallery WHERE hash_id = '$hash_id' AND (owner = '$user_id' OR id IN (SELECT gallery_id FROM gallery_permission WHERE user_id = '$user_id'))";
    $selectedGallery = $con_public->query($selectedGallery);
    $selectedGallery = $selectedGallery->fetch_assoc();
    $hash_id = $selectedGallery["hash_id"];
} else {
    header("Location: ../gallery");
    exit();
}

if(!isset($selectedGallery) || !isset($_GET["i"])) {
    header("Location: ../gallery");
    exit();
}

?>

<?php

$imageName = str_replace("/", "", $_GET["i"]);

$galleryFolder = scandir('../../cdn/gallery/'.$selectedGallery["hash_id"].'/images');

//iamge position in folder
$imagePosition = array_search($imageName, $galleryFolder);
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/image.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
        
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<body class="main" id="main">
    <div class="content">
    <div class="header">
            <div class="back">
                <a href="/gallery/view?id=<?php echo($_GET["g"]); ?>" titl="Zurück">
                    <span class="material-symbols-outlined">
                    arrow_back
                    </span>
                </a>
            </div>
            <h3><?php echo $_GET["i"]; ?></h3>
        </div>
        

        <div class="maincontent" id="image">
            <?php
            if($imagePosition >= 3) {
                echo '
                <a href="?g='.$_GET["g"].'&i='.$galleryFolder[intval($imagePosition) - 1].'">
                    <span class="material-symbols-outlined">
                    arrow_back_ios
                    </span>
                </a>
                ';
            } else {
                echo '
                <a class="hiddenOpacity">
                    <span class="material-symbols-outlined">
                    arrow_back_ios
                    </span>
                </a>
                ';
            }
            ?>
                
            <div class="preview">
                <?php
                $imageUrl = 'https://'.$domain["cdn"].'/gallery/'.$hash_id.'/images/'.$imageName;
                $imageUrlOriginal = 'https://'.$domain["cdn"].'/gallery/'.$hash_id.'/original/'.$imageName;
                ?>
                <img src="<?php echo($imageUrl); ?>">
            </div>
            <?php
            if($imagePosition < count($galleryFolder)-1) {
                echo '
                <a href="?g='.$_GET["g"].'&i='.$galleryFolder[intval($imagePosition) + 1].'">
                    <span class="material-symbols-outlined">
                    arrow_forward_ios
                    </span>
                </a>
                ';
            } else {
                echo '
                <a class="hiddenOpacity">
                    <span class="material-symbols-outlined">
                    arrow_forward_ios
                    </span>
                </a>
                ';
            }
            ?>
            </div>
            <div class="btn">
                <a href="<?php echo($imageUrlOriginal); ?>" download title="Bild Herunterladen">
                    <span class="material-symbols-outlined">
                    download
                    </span>
                </a>
            </div>
    </div>
    
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>