<?php
//include auth_session.php file on all user panel pages
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/auth_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/image/profile_picture.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/functions/input.php';
?>

<?php
if(!(in_array("admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
//get account
$accountID = checkTextInput($_GET["id"], "string");
$account = $con->query("SELECT * FROM `accounts` WHERE id = '$accountID'")->fetch_assoc();
$accountPermission = $con->query("SELECT permission_id FROM `accounts_permission` WHERE user_id = '$accountID'")->fetch_all();
$accountPermission = array_column($accountPermission, 0);
$accountPermissionGroup = $con->query("SELECT group_id FROM `accounts_permission_group` WHERE user_id = '$accountID'")->fetch_all();
$accountPermissionGroup = array_column($accountPermissionGroup, 0);
?>

<?php
if(empty($account)) {
    header("Location: ../user");
    exit();
}
?>

<?php
//get permissions
$permissions = "SELECT * FROM `permissions` ORDER BY perm";
$permissions = $con_new->query($permissions);

//get groups
$groups = "SELECT * FROM `permissions_group` ORDER BY perm";
$groups = $con_new->query($groups);
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo($account["username"]); ?> Bearbeiten - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="css/add.css">
                    
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
            <form class="middle" method="post" enctype="multipart/form-data">
                <div class="title">
                    <h2>Bearbeiten</h2>
                </div>
                <div class="set">
                    <div class="block">
                        <h3>Allgemein</h3>
                        <label>Benutzername:
                            <input type="text"
                            name="username"
                            value="<?php echo($account["username"]) ?>"
                            placeholder="<?php echo($account["username"]) ?>">
                        </label>
                        <label>Vorname: 
                            <input type="text" 
                            name="firstname"
                            value="<?php echo($account["firstname"]) ?>"
                            placeholder="<?php echo($account["firstname"]) ?>">
                        </label>
                        <label>Nachname: 
                            <input type="text" 
                            name="lastname"
                            value="<?php echo($account["lastname"]) ?>"
                            placeholder="<?php echo($account["lastname"]) ?>">
                        </label>
                        <label>E-Mail: 
                            <input 
                            type="email" 
                            name="email"
                            value="<?php echo($account["email"]) ?>"
                            placeholder="<?php echo($account["email"]) ?>">
                            </label>
                        <label>Geburtstag: 
                            <input 
                            type="date" 
                            name="birthdate"
                            value="<?php echo($account["birthdate"]) ?>">
                        </label>
                    </div>

                    <div class="block">
                        <h3>Passwort</h3>
                        <label>Passwort: <input type="password" name="password"></label>
                    </div>

                    <div class="block">
                        <h3>Profilbild</h3>
                        <img src="">
                        <label>Hochladen: <input type="file" name="profile_picture" accept="image/jpeg, image/png"></label>
                        <label><input type="checkbox" name="profile_picture_delete"> Profilbild Löschen</label>
                    </div>

                    <div class="block">
                        <h3>Berechtigungen</h3>
                        <div class="list">
                            <?php
                            while ($perm = $permissions->fetch_assoc()) {
                                if(in_array($perm["id"], $accountPermission)) {
                                    $hasPermission = "checked";
                                } else {
                                    $hasPermission = "";
                                }
                                echo '
                                <label><input type="checkbox" name="permission[]" value="'.$perm["id"].'" '.$hasPermission.'>'.$perm["perm"].'</label>
                                ';
                            }
                            ?>
                        </div>
                        
                    </div>

                    <div class="block">
                        <h3>Gruppen</h3>
                        <div class="list">
                        <?php
                        while ($group = $groups->fetch_assoc()) {
                            if(in_array($group["id"], $accountPermissionGroup)) {
                                $hasGroup = "checked";
                            } else {
                                $hasGroup = "";
                            }
                            echo '
                            <label><input type="checkbox" name="group[]" value="'.$group["id"].'" '.$hasGroup.'> '.$group["perm"].'</label>
                            ';
                        }
                        ?>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Speichern" name="submit">
            </form>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include("../../../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php
if(isset($_POST["submit"])) {
    $username = checkInput($_POST["username"]);
    $firstname = checkInput($_POST["firstname"]);
    $lastname = checkInput($_POST["lastname"]);
    $email = checkInput($_POST["email"]);
    $birthdate = checkInput($_POST["birthdate"]);
    $timestamp = date("Y-m-d H:i:s");

    if(!(empty($_POST["password"]))) {
        $password = "'".password_hash(checkInputPassword($_POST["password"]), PASSWORD_ARGON2ID)."'";
    } else {
        $password = "'".$account["password"]."'";
    }

    $userID = $account["id"];
    $editUser = "UPDATE `accounts` SET 
                                    `username` = $username, 
                                    `firstname` = $firstname, 
                                    `lastname` = $lastname, 
                                    `email` = $email, 
                                    `birthdate` = $birthdate, 
                                    `password` = $password
                                    WHERE `id` = '$userID'";
    $con->query($editUser);

    //permissions

    // delete
    if(empty($_POST["permission"])) {
        $p_permission_del = $accountPermission;
    } else {
        $p_permission_del = array_diff($accountPermission, $_POST["permission"]);
    }
    $con->query("DELETE FROM accounts_permission WHERE user_id = '$accountID' AND permission_id IN ('".implode("', '", $p_permission_del)."')");
    
    //insert
    if(!empty($_POST["permission"])) {
        $p_permission_insert = array_diff($_POST["permission"], $accountPermission);

        $con->query("INSERT INTO accounts_permission (user_id, permission_id) VALUES ".insertPermArray($p_permission_insert));
    }

    //group

    // delete
    if(empty($_POST["group"])) {
        $p_permissionGroup_del = $accountPermissionGroup;
    } else {
        $p_permissionGroup_del = array_diff($accountPermissionGroup, $_POST["group"]);
    }
    $con->query("DELETE FROM accounts_permission_group WHERE user_id = '$accountID' AND group_id IN ('".implode("', '", $p_permissionGroup_del)."')");
    
    //insert
    if(!empty($_POST["group"])) {
        $p_permissionGroup_insert = array_diff($_POST["group"], $accountPermissionGroup);

        $con->query("INSERT INTO accounts_permission_group (user_id, group_id) VALUES ".insertPermArray($p_permissionGroup_insert));
    }

    // profilepicture
    if(!(empty($_FILES["profile_picture"]["tmp_name"]))) {
        createProfilePicture($_FILES["profile_picture"]["tmp_name"], $_FILES["profile_picture"]["type"], 'im_p-'.substr(md5($account["id"]), 0, 10).$account["id"]);
    }

    if(isset($_POST["profile_picture_delete"])) {
        $mask = '../../../cdn/profile/picture/im_p-'.substr(md5($account["id"]), 0, 10).$account["id"]."*.*";
        array_map('unlink', glob($mask));
    }

    echo '<meta http-equiv="refresh" content="0; url=">';
}
?>

<?php
//functions
function insertPermArray($array) {
    global $accountID;
    $retrun = array();
    for ($i=0; $i < count($array); $i++) { 
        array_push($retrun, "('".$accountID."', ".checkInput($array[$i]).")");
    }

    return implode(", ", $retrun);
}
?>