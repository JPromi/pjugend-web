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

            </div>
        </div>

    </div>
    
</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>