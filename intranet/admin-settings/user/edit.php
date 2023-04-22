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
                                if(in_array($perm["id"], explode(";", $account["permission"]))) {
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
                            if(in_array($group["id"], explode(";", $account["permission_group"]))) {
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
    
    //permissions
    if(isset($_POST["permission"])) {
        $permission = "'".implode(";", $_POST["permission"])."'";
    } else {
        $permission = "NULL";
    }

    //groups
    if(isset($_POST["group"])) {
        $group = "'".implode(";", $_POST["group"])."'";
    } else {
        $group = "NULL";
    }

    $userID = $account["id"];
    $editUser = "UPDATE `accounts` SET 
                                    `username` = $username, 
                                    `firstname` = $firstname, 
                                    `lastname` = $lastname, 
                                    `email` = $email, 
                                    `birthdate` = $birthdate, 
                                    `password` = $password, 
                                    `permission` = $permission, 
                                    `permission_group` = $group 
                                    WHERE `id` = '$userID'";
    $con->query($editUser);

    if(!(empty($_FILES["profile_picture"]["tmp_name"]))) {
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], '../../../cdn/profile/picture/im_p-'.substr(md5($account["id"]), 0, 10).$account["id"].'.jpg');
    }

    if(isset($_POST["profile_picture_delete"])) {
        unlink('../../../cdn/profile/picture/im_p-'.substr(md5($account["id"]), 0, 10).$account["id"].'.jpg');
    }

    //echo '<meta http-equiv="refresh" content="0; url=">';
}
?>

<?php

//function
function checkInput($input) {
    global $con;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}

function checkInputPassword($input) {
    global $con;
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    return $input;
}
?>