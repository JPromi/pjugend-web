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
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
   <section class="content">
        <h2>Impressum</h2>

        <div class="article">
            <p>Adresse</p>
            <p>PLZ Ort</p>
            <p>Tel.: <a href="tel:+43123465789">+123456789</a></p>
            <p>E-Mail.: <a href="mailto:ex@mp.le">ex@mp.le</a></p>
        </div>

        <div class="article">
            <h4>Technische Umsetzung</h4>
            <p>Jonas Prominzer</p>
        </div>

        <div class="article">
            <h4>Bildmaterial</h4>
            <p>Jonas Prominzer</p>
            <p>Pixabay</p>
        </div>
   </section>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>