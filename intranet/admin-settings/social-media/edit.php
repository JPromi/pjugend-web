<?php
//include auth_session.php file on all user panel pages
include("../../../private/session/auth_session.php");
include '../../../private/database/public.php';
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/edit.css">
                    
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <?php
        include '../../../private/intranet/admin-settings/header.php';
        top("Social Media");
        ?>
        <div class="settings">
            <?php
            include '../../../private/intranet/admin-settings/nav-social_media.php';
            ?>
            <form class="middle" method="POST">
                <h2>Social Media</h2>

                <div class="linklist">
                    <div class="links" id="links">
                        <a class="single" data-id="1">
                            <div class="btn">
                                <span class="material-symbols-outlined" onclick="deleteLink('1')">
                                delete
                                </span>
                            </div>

                            <div class="edit" id="edit1">
                                <input type="hidden" name="id[]" id="1">
                                <input type="text" name="name[]" placeholder="Titel" class="name" value="">
                                <input type="text" name="link[]" placeholder="Link" value="">
                            </div>
                        </a>
                    </div>
                    
                    <a class="single add" onclick="addLink()">
                        <p class="ele">
                            <span class="material-symbols-outlined">
                            add
                            </span>
                            Neues Element
                        </p>
                    </a>
                </div>

            </form>
            <script src="js/edit.js"></script>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include("../../../private/intranet/assets/scripts-bottom.php")
?>

</html>