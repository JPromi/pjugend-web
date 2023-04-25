<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
include '../../private/database/public.php';
?>

<?php
if(!(in_array("jugendteam_admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
$socialmedias = "SELECT * FROM socialmedia";
$socialmedias = $con_public->query($socialmedias);
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/social-media.css">
                
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <?php
        include '../../private/intranet/admin-settings/header.php';
        top("Social Media");
        ?>
        <div class="settings">
            <?php
            include '../../private/intranet/admin-settings/nav-social_media.php';
            ?>
            <div class="middle">
                <h2>Social Media</h2>

                <div class="linklist">
                    <a class="single" href="https://jpromi.com">
                        <p>Facebook</p>
                    </a>
                    <a class="single" href="https://jpromi.com">
                        <p>Twitter</p>
                    </a>
                    <a class="single" href="https://jpromi.com">
                        <p>YouTube</p>
                    </a>
                    <a class="single" href="https://jpromi.com">
                        <p>Instagram</p>
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>