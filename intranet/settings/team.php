<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
include("../../private/database/public.php");
?>

<?php
$userID = $dbSESSION["user_id"];
$teamProfile = "SELECT * FROM team WHERE user_id = '$userID'";
$teamProfile = $con_public_new->query($teamProfile);
$teamProfile = $teamProfile->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/team.css">
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
                    <h2>Team</h2>
                </div>
                <div class="set">

                    <div class="block">
                        <h3>Allgemein</h3>
                        <?php
                        if($teamProfile["name"] == "") {
                            $teamProfileName = $dbSESSION["firstname"];
                        } else {
                            $teamProfileName = $teamProfile["name"];
                        }
                        ?>
                        <label>Anzeige Namen:
                            <input type="text"
                                name="name" 
                                value="<?php echo($teamProfileName); ?>" 
                                placeholder="<?php echo($teamProfileName); ?>" required>
                        </label>
                        
                        <div class="textarea">
                            <p>Kurzbeschreibung:</p>
                            <textarea name="description" placeholder="<?php echo($teamProfile["description"]); ?>"><?php echo($teamProfile["description"]); ?></textarea>
                        </div>

                        <label>Zuständigkeit:
                            <input type="text"
                                name="focus" 
                                value="<?php echo($teamProfile["focus"]); ?>" 
                                placeholder="<?php echo($teamProfile["focus"]); ?>">
                        </label>

                        <label>E-Mail:
                            <input type="email"
                                name="email" 
                                value="<?php echo($teamProfile["email"]); ?>" 
                                placeholder="<?php echo($teamProfile["email"]); ?>">
                        </label>

                        <label>
                            <?php
                            if($teamProfile["show_age"] == "1") {
                                $showAgeCheckbox = "checked";
                            }
                            ?>
                            <input type="checkbox" name="show_age" <?php echo($showAgeCheckbox); ?>>
                             Alter Anzeigen
                        </label>
                        
                    </div>

                    <div class="block">
                        <h3>Profilbild</h3>
                        <?php
                        if($teamProfile["disabled"] == "1") {
                            $profileDisabled = "checked";
                        }
                        ?>
                        <label><input type="file" accept="image/jpeg, image/png" name="profile_picture"></label>
                        <label><input type="checkbox" name="profile_picture_delete"> Löschen</label> 
                    </div>

                    <div class="block">
                        <h3>Sichtbarkeit</h3>
                        <?php
                        if($teamProfile["disabled"] == "1") {
                            $profileDisabled = "checked";
                        }
                        ?>
                        <label><input type="checkbox" name="disable" <?php echo($profileDisabled); ?>> Deaktivieren</label>
                        <label><input type="checkbox" name="delete"> Löschen</label> 
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
if(isset($_POST["submit"])) {
    $name           = checkInput($_POST["name"]);
    $description    = checkInput($_POST["description"]);
    $focus          = checkInput($_POST["focus"]);
    $email          = checkInput($_POST["email"]);

    $show_age       = checkBoolean($_POST["show_age"]);
    $disabled       = checkBoolean($_POST["disabled"]);
    $user_id        = $dbSESSION["user_id"];


    //entry
    if(!empty($teamProfile)) {
        $profileUpdate = "UPDATE team SET `name` = $name, `description` = $description, `show_age` = $show_age, `focus` = $focus, `email` = $email, `user_id` = $user_id, `disabled` = $disabled WHERE id = '$user_id'";
        $con_public->query($profileUpdate);
    } else {
        $profileInsert = "INSERT INTO team (`name`, `description`, `show_age`, `focus`, `email`, `user_id`, `disabled`) VALUE ($name, $description, '$show_age', $focus, $email, '$user_id', '$disabled')";
        $con_public->query($profileInsert);
    }

    //profile picture
    if(!(empty($_FILES["profile_picture"]["tmp_name"]))) {
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], '../../cdn/profile/team/picture/im_p-'.substr(md5($user_id), 0, 10).$user_id.'.jpg');
    }

    if(isset($_POST["profile_picture_delete"])) {
        unlink('../../cdn/profile/team/picture/im_p-'.substr(md5($user_id), 0, 10).$user_id.'.jpg');
    }

    //delte entry
    if(isset($_POST["delete"])) {
        $con_public->query("DELETE FROM team WHERE user_id = '$user_id'");
        unlink('../../cdn/profile/team/picture/im_p-'.substr(md5($user_id), 0, 10).$user_id.'.jpg');
    }

    echo '<meta http-equiv="refresh" content="0; url=team">';
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

function checkBoolean($input) {
    if($input == "on") {
        $input = "1";
    } else {
        $input = "0";
    }

    return $input;
}
?>