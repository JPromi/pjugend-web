<?php
//include auth_session.php file on all user panel pages
//include("../../private/session/auth_session.php");

include("../../private/database/public.php");
include("../../private/config.php");
?>

<?php
$hash_id = mysqli_real_escape_string($con_public, stripcslashes($_GET["g"]));
$gallery = "SELECT * FROM gallery WHERE hash_id = '$hash_id'";
$gallery = $con_public->query($gallery);
$gallery = $gallery->fetch_assoc();

if(!isset($gallery) || !isset($_GET["i"])) {
    header("Location: ../gallery");
    exit();
}

?>

<?php
        //check if gallery has password
        if($gallery["password"]) {

            //check if cookie isset
            if($_COOKIE["PUBLIC_SESSION_ID"]) {
                $sessionPublic = $_COOKIE["PUBLIC_SESSION_ID"];
                $galleryID = $gallery["id"];
                
                $gallerySession = $con_public->query("SELECT * FROM gallery_session WHERE cookie_hash = '$sessionPublic' AND gallery_id = '$galleryID'");
                $gallerySession = $gallerySession->fetch_assoc();

                //check if cookie is expired
                if(empty($gallerySession)) {
                    echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hashID.'">';
                    exit();
                }
            } else {
                echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hashID.'">';
                exit();
            }
        }
?>


<?php

$imageName = str_replace("/", "", $_GET["i"]);

$galleryFolder = scandir('../../cdn/gallery/'.$gallery["hash_id"].'/images');

//iamge position in folder
$imagePosition = array_search($imageName, $galleryFolder);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/image.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    
    <?php
    include '../../private/favicon/main.php';
    ?>
    
</head>

<body>
<?php
        //check if gallery has password
        if($gallery["password"]) {

            //check if cookie isset
            if($_COOKIE["PUBLIC_SESSION_ID"]) {
                $sessionPublic = $_COOKIE["PUBLIC_SESSION_ID"];
                $galleryID = $gallery["id"];
                
                $gallerySession = $con_public->query("SELECT * FROM gallery_session WHERE cookie_hash = '$sessionPublic' AND gallery_id = '$galleryID'");
                $gallerySession = $gallerySession->fetch_assoc();

                //check if cookie is expired
                if(empty($gallerySession)) {
                    echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hash_id.'">';
                    exit();
                }
            } else {
                echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hash_id.'">';
                exit();
            }            
        }
        ?>
    <div class="content" id="pre">
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
                <a href="?g='.$_GET["g"].'&i='.$galleryFolder[intval($imagePosition) - 1].'#pre">
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
                <a href="?g='.$_GET["g"].'&i='.$galleryFolder[intval($imagePosition) + 1].'#pre">
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

</html>