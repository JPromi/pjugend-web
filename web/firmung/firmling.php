<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/firmung/auth_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';
?>

<?php
$firmling = $dbSESSION_firmling["firmling_id"];
$firmling = "SELECT * FROM firmling WHERE id = '$firmling'";
$firmling = $con_firmung->query($firmling);
$firmling = $firmling->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmling - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/firmung/firmling.css">
    
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>
    
</head>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/nav.php';
?>

<body>
    <div class="content">

        <div class="top">
            <h1>Herzlich Willkommen, <?php echo $firmling["firstname"].' '.$firmling["lastname"] ?>!</h1>
            <p>Hier findest du alle Informationen zu deiner Firmvorbereitung unf Firmung.</p>
            <div class="btn">
                <a href="/firmung/firmling/settings">Einstellungen</a>
            </div>
        </div>
    </div>
</body>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/footer.php';
?>

</html>