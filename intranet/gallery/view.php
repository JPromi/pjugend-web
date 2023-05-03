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
$hash_id = mysqli_real_escape_string($con_public, stripcslashes($_GET["id"]));
$selectedGallery = "SELECT * FROM gallery WHERE hash_id = '$hash_id'";
$selectedGallery = $con_public->query($selectedGallery);
$selectedGallery = $selectedGallery->fetch_assoc();

if(!isset($selectedGallery)) {
    header("Location: ../gallery");
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
    <link rel="stylesheet" href="css/view.css">
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
                <h1><?php echo($selectedGallery["title"]); ?></h1>
                <p><?php echo str_replace("\n", '<br>', $selectedGallery["description"]); ?></p>
                <div class="btn">
                    <a href="">edit</a>
                    <a href="">download</a>
                </div>

                <div class="images">
                    <?php
                    //select images
                    $files = scandir('../../cdn/gallery/'.$selectedGallery["hash_id"].'/images/');
                    $files = array_slice($files, 2);;
                    foreach ($files as $image) {
                        $imagePath = 'https://'.$domain["cdn"].'/gallery/'.$selectedGallery["hash_id"].'/thumbnail/'.$image;
                        echo '
                        <img src="'.$imagePath.'" onclick="window.location.href=`image?g='.$selectedGallery["hash_id"].'&i='.$image.'`">
                        ';
                    }
                    ?>
                </div>
            </div>
            
        </div>

    </div>
    
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>