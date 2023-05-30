<?php
include '../private/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/index.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <div class="content">
        <div class="ph">
            <h2>Hey,... eh,... schön das du den Weg hier her gefunden hast.</h2>
            <p>Zurzeit gibt es hier noch nicht wirklich was zu sehen, schau ein anderes mal wieder vorbei</p>
        </div>
    </div>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>