<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/settings.css">
    <link rel="stylesheet" href="/settings/css/style.css">

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
        include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/settings/header.php';
        top("Einstellungen");
        ?>

        <div class="settings">
            <?php
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/settings/nav.php';
            ?>
        </div>

    </div>

</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>