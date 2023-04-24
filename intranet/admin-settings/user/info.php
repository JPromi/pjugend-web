<?php
//include auth_session.php file on all user panel pages
include("../../../private/session/auth_session.php");
include("../../../private/database/int.php");
?>

<?php
if(!(in_array("admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
//get account
$accountID = $_GET["id"];
$account = "SELECT * FROM `accounts` WHERE id = '$accountID'";
$account = $con_new->query($account);
$account = $account->fetch_assoc();
?>

<?php
if(empty($account)) {
    header("Location: ../user");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo($account["username"]); ?> Info - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="css/info.css">
                
    <?php
    include '../../../private/favicon/main.php';
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
        top("Benutzer");
        ?>
        <div class="settings">
            <?php
            include '../../../private/intranet/admin-settings/nav-user.php';
            ?>
            <div class="middle">
                <div class="title">
                    <h2>Informationen</h2>
                </div>
                <div class="info">

                    <div class="block">
                        <h3>Allgemein</h3>
                        <p>Benutzername: <?php echo($account["username"]); ?></p>
                        <p>Vorname: <?php echo($account["firstname"]); ?></p>
                        <p>Nachname: <?php echo($account["lastname"]); ?></p>
                        <p>E-Mail: <?php echo($account["email"]); ?></p>
                        <p>Geburtstag: <?php echo(date("d.m.Y", strtotime($account["birthdate"]))); ?></p>
                    </div>

                    <div class="block">
                        <h3>Profilbild</h3>
                        <?php
                        //grt profile picture
                        $img_profile_root = "../../../cdn/profile/picture/im_p-".substr(md5($account["id"]), 0, 10).$account["id"].'.jpg';

                        if(file_exists($img_profile_root)) {
                            $img_profile_path = "https://".$domain["cdn"]."/profile/picture/im_p-".substr(md5($account["id"]), 0, 10).$account["id"].".jpg";
                        } else {
                            $img_profile_path = "https://".$domain["cdn"]."/profile/placeholder/picture.jpg";
                        }
                        ?>
                        <img src="<?php echo($img_profile_path); ?>">
                    </div>

                    <div class="block">
                        <h3>Berechtigungen</h3>
                        <div class="list">
                            <?php
                            foreach (explode(";", $account["permission"]) as $permID) {
                                $permission = "SELECT * FROM `permissions` WHERE id = '$permID'";
                                $permission = $con_new->query($permission);
                                $permission = $permission->fetch_assoc();

                                echo '
                                    <p>'.$permission["perm"].'</p>
                                ';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="block">
                        <h3>Gruppen</h3>
                        <div class="list">
                            <?php
                            foreach (explode(";", $account["permission_group"]) as $groupID) {
                                $group = "SELECT * FROM `permissions_group` WHERE id = '$groupID'";
                                $group = $con_new->query($group);
                                $group = $group->fetch_assoc();

                                echo '
                                    <p>'.$group["perm"].'</p>
                                ';
                            }
                            ?>
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include("../../../private/intranet/assets/scripts-bottom.php")
?>

</html>