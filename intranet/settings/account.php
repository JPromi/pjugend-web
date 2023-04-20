<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
include("../../private/database/int.php");
?>

<?php
$accountID = $dbSESSION["user_id"];
$account = "SELECT * FROM accounts WHERE id = '$accountID'";
$account = $con_new->query($account);
$account = $account->fetch_assoc();
?>

<?php
if(isset($_POST["submit"])) {
    
    //set variables
    $username = checkInput($_POST["username"]);
    $firstname = checkInput($_POST["firstname"]);
    $lastname = checkInput($_POST["lastname"]);
    $email = checkInput($_POST["email"]);
    $birthdate = checkInput($_POST["birthdate"]);

    $accountUpdate = "UPDATE accounts SET username = $username, firstname = $firstname, lastname = $lastname, email = $email, birthdate = $birthdate WHERE id = '$accountID'";
    $accountUpdate = $con->query($accountUpdate);

    //profile picture
    if(!(empty($_FILES["profile_picture"]["tmp_name"]))) {
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], '../../cdn/profile/picture/im_p-'.substr(md5($account["id"]), 0, 10).$account["id"].'.jpg');
    }

    if($_POST["profile_picture_delete"] == "1") {
        unlink('../../cdn/profile/picture/im_p-'.substr(md5($account["id"]), 0, 10).$account["id"].'.jpg');
    }


    //check password
    if(!empty($_POST["password_old"]) && !empty($_POST["password_new"]) && !empty($_POST["password_new_repeat"])) {
        //check if old password is safe
        if(password_verify(checkInputPassword($_POST["password_old"]), $account["password"])) {
            if($_POST["password_new"] == $_POST["password_new_repeat"]) {
                $password = checkInputPassword($_POST["password_new"]);
                $password = password_hash($password, PASSWORD_ARGON2ID);
                $passwordUpdate = "UPDATE accounts SET `password` = '$password' WHERE id = '$accountID'";
                $passwordUpdate = $con->query($passwordUpdate);
            } else {
                $passwordError = true;
                $passwordErrorMessage = "die Passwörter stimmen nicht überein";
            }
        } else {
            $passwordError = true;
            $passwordErrorMessage = "das alter Passwort ist falsch";
        }
        
    } elseif (!empty($_POST["password_old"]) || !empty($_POST["password_new"]) || !empty($_POST["password_new_repeat"])) {
        $passwordError = true;
        $passwordErrorMessage = "nicht alle Felder befüllt";
    }

    //echo '<meta http-equiv="refresh" content="0; url=account">';
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/account.css">
    <link rel="stylesheet" href="/settings/css/style.css">
</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

    <div class="content">

        <?php
        include '../../private/intranet/settings/header.php';
        top("Einstellungen");
        ?>

        <div class="settings">
            <?php
            include '../../private/intranet/settings/nav.php';
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
                                name="username" 
                                value="<?php echo($account["username"]); ?>" 
                                placeholder="<?php echo($account["username"]); ?>">
                        </label>
                        <label>Vorname: 
                            <input type="text" 
                            name="firstname" 
                            value="<?php echo($account["firstname"]); ?>" 
                            placeholder="<?php echo($account["firstname"]); ?>">
                        </label>
                        <label>Nachname: 
                            <input type="text" 
                            name="lastname" 
                            value="<?php echo($account["lastname"]); ?>" 
                            placeholder="<?php echo($account["lastname"]); ?>">
                        </label>
                        <label>E-Mail: 
                            <input 
                            type="email" 
                            name="email"
                            value="<?php echo($account["email"]); ?>" 
                            placeholder="<?php echo($account["email"]); ?>">
                            </label>
                        <label>Geburtstag: 
                            <input 
                            type="date" 
                            name="birthdate" 
                            value="<?php echo($account["birthdate"]); ?>" 
                            placeholder="<?php echo($account["birthdate"]); ?>">
                        </label>
                    </div>

                    <div class="block">
                        <h3>Passwort</h3>
                        <label>Altes Passwort: <input type="password" name="password_old"></label>
                        <label>Neues Passwort: <input type="password" name="password_new"></label>
                        <label>Passwort Wiederholen: <input type="password" name="password_new_repeat"></label>
                        <?php
                        if($passwordError == true) {
                            echo '<p class="error">'.$passwordErrorMessage.'</p>';
                        }
                        ?>
                    </div>

                    <div class="block">
                        <h3>Profilbild</h3>
                        <img src="" data-original-image="">
                        <label>Hochladen: <input type="file" name="profile_picture" accept="image/jpeg, image/png"></label>
                        <label><input type="checkbox" name="profile_picture_delete"> Profilbild Löschen</label>
                    </div>
                </div>
                <input type="submit" value="Speichern" name="submit">
            </form>
            
        </div>

    </div>

</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>
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