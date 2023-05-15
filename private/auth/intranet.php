<?php
include '../private/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo($conf_title["intranet"]) ?></title>
    <link rel="stylesheet" href="css/intranet.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>
<?php
    include "../intranet/assets/html/favicon.html";
    include "../private/config.php";
    require("../private/database/int.php");


    //redirect logic
    if ($_GET["direct"]) {
        $redirect_url = '?direct=' . $_GET["direct"];

    }
    
    // When form submitted, check and create user session.

    //intranet login
    if (isset($_POST['username'])) {
        $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($con, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);

        //log login
        $LOG_IP = $_SERVER['REMOTE_ADDR'];

        //failed attemps
        $failed = "SELECT * FROM `login_log` WHERE username = '$username'";
        $failed = mysqli_query($con, $failed);

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
            // Check user is exist in the database
            // ARGON2ID
            $verify = "SELECT * FROM `accounts` WHERE username = '$username'";
            $verify = mysqli_query($con, $verify);
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
                mysqli_query($con_new, $generateSession);

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
            mysqli_query($con_new, "INSERT INTO `login_log` (username, ip, `status`, user_id) VALUES ('$username', '$LOG_IP', '$loginStatus', $userID)");
        } else {
            $error = "attempts";
        }

        


    }
?>
<body>
    <section class="top">
   
        <!--login-->
        <section class="login <?php echo($error)?>">
            <div class="title">
                <img src="https://<?php echo($domain["cdn"]);?>/logo/pjugend/p_jugend-blue.svg">
                <h1>Intranet Login</h1>
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

                <a href="password-reset">Forgot password?</a>

                <input type="submit" value="Login" name="submit"require/>
            </form>

        </section>
    </section>
    <?php
        if ($error == "loginerror") {
            echo "<p class='error'>Flasches Passwort oder Benutzername</p>";
        } else if ($error == "attempts") {
            echo "<p class='error'>Zu viele Versuche, versuche es in 3 min wieder</p>";
        }
    ?>

</body>

<?php
    include("../private/session/get_session.php");
    if (isset($dbSESSION)) {
        if (isset($_GET["direct_int"])) {
            header("Location: /redirect?direct_int=".$_GET["direct_int"]);
        }
        header("Location: /redirect");
        exit();
    }
?>

<?php

//include("assets/html/footer.php");
?>
</html>


