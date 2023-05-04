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
$selectedGallery = "SELECT * FROM gallery WHERE hash_id = '$hash_id'";
$selectedGallery = $con_public->query($selectedGallery);
$selectedGallery = $selectedGallery->fetch_assoc();

if(!isset($selectedGallery) || !isset($_GET["i"])) {
    header("Location: ../gallery");
    exit();
}

?>

<?php
if($selectedGallery["owner"] == $dbSESSION["user_id"] || in_array("jugendteam_admin", $dbSESSION_perm) || in_array($dbSESSION["user_id"], explode(";", $selectedGallery["user_edit"]))) {

} else {
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
    <link rel="stylesheet" href="/gallery/css/style.css">
        
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">

        <?php
        include '../../private/intranet/gallery/header.php';
        top("Galerie");
        ?>

        <div class="gallery">
            <?php
            include '../../private/intranet/gallery/nav.php';
            ?>
            <div class="middle">
                <div class="maincontent">
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
                <!--<div class="btn">
                    <a href="<?php echo($imageUrlOriginal); ?>" download>
                        <span class="material-symbols-outlined">
                        download
                        </span>
                    </a>
                </div>-->
                
            </div>
            
        </div>

    </div>
    
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>