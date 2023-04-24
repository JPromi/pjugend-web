<?php
//include auth_session.php file on all user panel pages
include("../private/session/auth_session.php");
?>

<?php
if(!(in_array("admin", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm))) {
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
    <link rel="stylesheet" href="css/admin-settings.css">
            
    <?php
    include '../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <div class="header">
            <h4>Admin Einstellungen</h4>
        </div>
    </div>
    <?php
    //admin settings
    if(in_array("admin", $dbSESSION_perm)) {
    ?>
        <a href="admin-settings/user">Benutzer</a>
    <?php
    }
    ?>

    <?php
    //jt admin settings
    if(in_array("jugendteam_admin", $dbSESSION_perm)) {
    ?>
        <a href="admin-settings/social-media">Social Media</a>
    <?php
    }
    ?>
</body>

<?php
//include scripts for bottom
include("../private/intranet/assets/scripts-bottom.php")
?>

</html>