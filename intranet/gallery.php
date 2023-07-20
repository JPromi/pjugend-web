<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");

include($_SERVER["DOCUMENT_ROOT"]."/../private/database/public.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>

<?php
if(in_array("gallery", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm)) {
    
} else {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/gallery.css">
    <link rel="stylesheet" href="/gallery/css/style.css">
        
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

<div class="content">

        <?php
        include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/gallery/header.php';
        top("Galerie");
        ?>

        <div class="gallery">
            <?php
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/gallery/nav.php';
            ?>
            <div class="middle">
                <?php
                $galleries = $con_public->query("SELECT * FROM gallery WHERE owner = '$user_id' OR id IN (SELECT gallery_id FROM gallery_permission WHERE user_id = '$user_id')");
                while($gallery = $galleries->fetch_assoc()) {

                    //get thumbnail
                    $pathGallery = $_SERVER["DOCUMENT_ROOT"].'/../cdn/gallery/'.$gallery["hash_id"].'/thumbnail/';
                    $galleryFolder = scandir ($pathGallery);
    
                    if($galleryFolder[2] != "") {
                        $thumbnail = 'https://'.$domain["cdn"].'/gallery/'.$gallery["hash_id"].'/thumbnail/'.$galleryFolder[2];
                    } else {
                        $thumbnail = 'https://'.$domain["cdn"].'/gallery/placeholder/gallery.jpg';
                    }
                    echo '
                    <a class="single" href="/gallery/view?id='.$gallery["hash_id"].'">
                        <img src="'.$thumbnail.'">
                        <h4>'.$gallery["title"].'</h4>
                    </a>
                    ';
                }
                ?>
            </div>
        </div>

    </div>
    
</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>