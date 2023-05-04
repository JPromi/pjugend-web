<?php
include '../../private/config.php';
include '../../private/database/public.php';
?>

<?php
$hashID = mysqli_real_escape_string($con_public, stripcslashes($_GET["id"]));
$gallery = "SELECT * FROM gallery WHERE hash_id = '$hashID'";
$gallery = $con_public->query($gallery);
$gallery = $gallery->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/view.css">
    
    <?php
    include '../../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../../private/web/assets/nav.php';
?>

<body>
    <div class="content">
        <?php
        //check if gallery has password
        if($gallery["password"]) {
            echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hashID.'">';
            exit();
        }
        ?>
    </div>
</body>

<?php
include '../../private/web/assets/footer.php';
?>

</html>