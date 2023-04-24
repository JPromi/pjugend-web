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
    <title>Benutzer Erstellen - <?php echo($conf_title["intranet"]); ?></title>

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
            include '../../../private/intranet/admin-settings/nav-user_all.php';
            ?>
            <form class="middle" method="post" enctype="multipart/form-data">
                <div class="title">
                    <h2>Konto</h2>
                </div>
                <div class="set">
                    <div class="block">
                        <h3>Allgemein</h3>
                        <label>Benutzername:
                            <input type="text"
                                name="username">
                        </label>
                        <label>Vorname: 
                            <input type="text" 
                            name="firstname">
                        </label>
                        <label>Nachname: 
                            <input type="text" 
                            name="lastname">
                        </label>
                        <label>E-Mail: 
                            <input 
                            type="email" 
                            name="email">
                            </label>
                        <label>Geburtstag: 
                            <input 
                            type="date" 
                            name="birthdate">
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
                    </div>

                    <div class="block">
                        <h3>Berechtigungen</h3>
                        <div class="list">
                            <?php
                            while ($perm = $permissions->fetch_assoc()) {
                                echo '
                                <label><input type="checkbox" name="permission[]" value="'.$perm["id"].'"> '.$perm["perm"].'</label>
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
                            echo '
                            <label><input type="checkbox" name="group[]" value="'.$group["id"].'"> '.$group["perm"].'</label>
                            ';
                        }
                        ?>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Erstellen" name="submit">
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
    $password = "'".password_hash(checkInputPassword($_POST["password"]), PASSWORD_ARGON2ID)."'";
    $timestamp = date("Y-m-d H:i:s");
    
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

    $addUser = "INSERT INTO `accounts`  (username, firstname, lastname, email, birthdate, `password`, permission, permission_group, create_datetime) VALUES
                                        ($username, $firstname, $lastname, $email, $birthdate, $password, $permission, $group, '$timestamp')";
    $addUser = $con->query($addUser);
    $userID = $con->insert_id;
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