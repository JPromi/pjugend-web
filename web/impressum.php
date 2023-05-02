<?php
include '../private/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressum - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/impressum.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
   <section class="content">
        <h2>Impressum</h2>

        <div class="article">
            <p>Röm.-kath. Pfarre Perchtoldsdorf</p>
            <p>Marktplatz 14</p>
            <p>2380 Perchtoldsdorf</p>
            <p>Österreich</p>
            <p>DVR: 0029874(005)</p>
        </div>

        <div class="article">
            <h4>Technische Umsetzung</h4>
            <p>Jonas Prominzer</p>
        </div>

        <div class="article">
            <h4>Bildmaterial</h4>
            <p>Jonas Prominzer</p>
            <p>Röm.-kath. Pfarre Perchtoldsdorf</p>
        </div>
   </section>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>