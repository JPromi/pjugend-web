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

<?php
    //check if gallery has password
    if(!$gallery["password"]) {
        echo '<meta http-equiv="refresh" content="0; url=view?id='.$hashID.'">';
        exit();
    }
?>

<?php
include '../../private/web/session/create.php';
?>
<?php
if(isset($_POST["submit"])) {
    if($gallery["password"] == $_POST["password"]) {
        $cookieHash = publicSession();
        $galleryID = $gallery["id"];
        //add permission to gallery_session
        $con_public->query("INSERT INTO gallery_session (gallery_id, cookie_hash) VALUES ('$galleryID', '$cookieHash')");
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie Verifizieren - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/verify.css">
    
    <?php
    include '../../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../../private/web/assets/nav.php';
?>

<body>
    <form class="content" method="POST">
        <h4>"<?php echo($gallery["title"]); ?>" ist Passwort geschützt</h4>
        <p>Bitte gib das Passwort für diese Gallerie ein</p>
        <input type="password" name="password" placeholder="Passwort" required>
        <input type="submit" name="submit" value="Senden">
    </form>
</body>

<?php
include '../../private/web/assets/footer.php';
?>

</html>
