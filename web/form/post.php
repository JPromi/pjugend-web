<?php
include '../../private/config.php';
include '../../private/database/int.php';
include '../../private/database/public.php';
include '../../private/database/form.php';
?>

<?php
if(empty($_GET["form_id"]) || empty($_GET["post_id"])) {
    header("Location: ../");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/post.css">
    
    <?php
    include '../../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../../private/web/assets/nav.php';
?>

<body>
    <?php
    $postID = $_GET["post_id"];
    $formID = $_GET["form_id"];
    $post = "SELECT id FROM `form_$formID` WHERE id = '$postID'";
    $post = $con_form_new->query($post);
    $post = $post->fetch_assoc();
    ?>

    <div class="box">
        <?php
        if(isset($post)) {
            echo '
            <h4>Erfolgreich Gesendet</h4>
            <p>Deine Ergebnisse wurden erfolgreich gespeichert</p>
            <a href="../">Zurück</a>
            ';
        } else {
            echo '
            <h4>Etwas ist schiefgelaufen</h4>
            <p>Deine Ergebnisse konnte nicht gespeichert werden</p>
            <a href="../">Zurück</a>
            ';
        }
        ?>
    </div>
</body>

<?php
include '../../private/web/assets/footer.php';
?>

</html>