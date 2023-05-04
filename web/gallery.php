<?php
include '../private/config.php';
include '../private/database/public.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/gallery.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <div class="content">
        <h1>Bilder</h1>
        <div class="gallery">
            <?php
                //get galleries
                $galleries = "SELECT * FROM gallery WHERE public_view = '1'";
                $galleries = $con_public->query($galleries);

                while ($gallery = $galleries->fetch_assoc()) {
                    //get thumbnail
                    $pathGallery = '../cdn/gallery/'.$gallery["hash_id"].'/thumbnail/';
                    $galleryFolder = scandir ($pathGallery);

                    if($galleryFolder[2] != "") {
                        $thumbnail = 'https://'.$domain["cdn"].'/gallery/'.$gallery["hash_id"].'/thumbnail/'.$galleryFolder[2];
                    } else {
                        $thumbnail = 'https://'.$domain["cdn"].'/gallery/placeholder/gallery.jpg';
                    }

                    echo '
                    <div class="single" onclick="window.location.href=`gallery/view?id='.$gallery["hash_id"].'`">
                        <img src="'.$thumbnail.'">
                        <h3>'.$gallery["title"].'</h3>
                    </div>
                    ';
                }
            ?>
        </div>
    </div>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>