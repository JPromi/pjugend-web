<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
?>

<?php
if(!(in_array("admin", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm) || in_array("firmung_admin", $dbSESSION_perm))) {
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
    <title>Admin Einstellungen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/css/admin-settings.css">
            
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
        include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/header.php';
        top("Admin Einstellungen");
        ?>
        <div class="settings">
            <?php
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/nav.php';
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