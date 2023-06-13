<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmung - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/firmung.css">
    
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>
    
</head>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/nav.php';
?>

<body>
    <div class="content">
        <a href="/firmung/registration">Registrieren</a>
    </div>
</body>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/footer.php';
?>

</html>