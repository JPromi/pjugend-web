<?php
include '../private/config.php';
require "../private/database/int.php";
require "../private/database/firmung.php";
?>

<?php
//check current firmung
if(empty($_GET["year"])) {
    $today = date('Y-m-d');
    $currentFirmung = "SELECT * FROM firmung WHERE start_date < '$today' AND end_date >= '$today'";
} else {
    $getYEAR = mysqli_real_escape_string($con_firmung, $_GET["year"]);
    $currentFirmung = "SELECT * FROM firmung WHERE year = '$getYEAR'";
}

$currentFirmung = $con_firmung->query($currentFirmung);
$currentFirmung = $currentFirmung->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if(isset($currentFirmung)) {
        echo '
        <title>Firmung '.$currentFirmung["year"].' Login - '.$conf_title["intranet"].'</title>
        ';
    } else {
        echo '
        <title>Firmung nicht gefunden - '.$conf_title["intranet"].'</title>
        ';
    }
    ?>
    
    <link rel="stylesheet" href="css/firmung.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>
<?php
    include "../intranet/assets/html/favicon.html";


    //redirect logic
    if ($_GET["direct"]) {
        $redirect_url = '?direct=' . $_GET["direct"];

    }
    
    // When form submitted, check and create user session.

    //intranet login
    if (isset($_POST['username']) && isset($currentFirmung)) {
        $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($con_firmung, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con_firmung, $password);

        //log login
        $LOG_IP = $_SERVER['REMOTE_ADDR'];

        //failed attemps
        $failed = "SELECT * FROM `login_log` WHERE username = '$username'";
        $failed = mysqli_query($con_firmung, $failed);

        $failedCounterIP = 0;
        $failedCounter = 0;

        while ($fa = $failed->fetch_assoc()) {
            
            if(strtotime(date("Y-m-d h:i", strtotime("-3 minutes"))) <= strtotime($fa["timestamp"])) {
                if($fa["ip"] == $LOG_IP) {
                $failedCounterIP++;
                }
                $failedCounter++;
            }

            if($fa["status"] == "login") {
                $failedCounterIP = 0;
                $failedCounter = 0;
            }

        }

        if($failedCounter <= 10) {
            $firmungID = $currentFirmung["id"];
            // Check user is exist in the database
            // ARGON2ID
            $verify = "SELECT id, `password`, firstname, lastname, username FROM `firmling` WHERE username = '$username' AND firmung_id = '$firmungID'";
            $verify = mysqli_query($con_firmung, $verify);
            $verify = $verify->fetch_assoc();
        
            $passwordVerify = password_verify($password, $verify["password"]);

            if ($passwordVerify == 1) {
                $loginStatus = "login";
                $error = "";
                $coockie_hash = bin2hex(random_bytes(40));
                setcookie("SESSION_ID", $coockie_hash, time() + (86400 * 7), "", ".".$domain["default"]);
                $generateSession =    "INSERT INTO `session` 
                            (user_id, username, firstname, lastname, cookie_hash, used_for)
                            VALUES
                            ('".$verify['id']."', '".$verify['username']."', '".$verify['firstname']."', '".$verify['lastname']."', '$coockie_hash', 'web')";
                mysqli_query($con_firmung_new, $generateSession);

                // Set log var
                $userID = "'".$verify['id']."'";

                // Redirect to user home page
                header("Location: /redirect".$redirect_url);
            } else {
                $loginStatus = "error";
                $error = "loginerror";
                $userID = "NULL";
            }

            //log login
            mysqli_query($con_firmung_new, "INSERT INTO `login_log` (username, ip, `status`, user_id) VALUES ('$username', '$LOG_IP', '$loginStatus', $userID)");
        } else {
            $error = "attempts";
        }

        


    }
?>
<body>
    <?php
    if(isset($currentFirmung)) {
    ?>
    <section class="top">

        <?php
            //get logo
            $img_logo_root = $_SERVER["DOCUMENT_ROOT"]."/../cdn/firmung/logo/".$currentFirmung["year"].'.png';

            if(file_exists($img_logo_root)) {
                $img_logo_path = "https://".$domain["cdn"]."/firmung/logo/".$currentFirmung["year"].'.png';
            } else {
                $img_logo_path = "https://".$domain["cdn"]."/firmung/logo/default.svg";
            }
        ?>
   
        <!--login-->
        <section class="login <?php echo($error)?>">
            <div class="title">
                <img src="<?php echo($img_logo_path); ?>">
                <h1>Firmung <?php echo($currentFirmung["year"]); ?> Login</h1>
            </div>
            
            <form class="form" method="POST" name="login">

                <!--Username-->
                <p>Benutzername</p>
                <label>
                    <span class="material-symbols-outlined">
                    person
                    </span>
                    <input type="text" name="username" require/>
                </label>

                    <span class="spacetext"></span>
                
                <!--Password-->
                <p>Password</p>
                <label>
                    <span class="material-symbols-outlined">
                    lock
                    </span>
                    <input type="password" name="password" require/>
                </label>

                <input type="submit" value="Login" name="submit"require/>
            </form>

        </section>
    </section>


        <?php
        } else {
        ?>
        <section class="top">
    
            <!--login-->
            <section class="login <?php echo($error)?>">
                <div class="title">
                    <img src="https://<?php echo($domain["cdn"]);?>/firmung/logo/default.png">
                    <?php
                    if(empty($_GET["year"])) {
                        echo '
                            <h1>Zurzeit ist keine Firmung vorhanden</h1>
                        ';
                    } else {
                        echo '
                            <h1>Die Firmung '.$_GET["year"].' konnte nicht gefunden werden.</h1>
                        ';
                    }
                    ?>
                    <h1></h1>
                </div>

            </section>
        </section>
    <?php
    }
    ?>

</body>

<?php
    //include("../private/session/get_session.php");
    if (isset($dbSESSION)) {
        if (isset($_GET["direct_int"])) {
            header("Location: https://.".$domain["web"]."/firmung");
        }
        header("Location: https://.".$domain["web"]."/firmung");
        exit();
    }
?>

<?php

//include("assets/html/footer.php");
?>
</html>


