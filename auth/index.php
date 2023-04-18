<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PJugend</title>
    <link rel="stylesheet" href="css/index.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
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
    if (isset($_POST['username'])) {
        $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($con, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        // Check user is exist in the database
        $query    = "SELECT * FROM `accounts` WHERE username='$username' AND
                     password='" . md5($password) . "'";
        $result = mysqli_query($con, $query);
        $rows = mysqli_num_rows($result);

        //get other informations of user
        $resultsOT = $con_new->query($query);
                $rowsOT = $resultsOT->fetch_assoc();

        if ($rows == 1) {
            $error = "";
            $coockie_hash = bin2hex(random_bytes(40));
            setcookie("SESSION_ID", $coockie_hash, time() + (86400 * 7), "", ".".$domain["default"]);
            $generateSession =    "INSERT INTO `session` 
                        (user_id, username, firstname, lastname, cookie_hash, used_for)
                        VALUES
                        ('".$rowsOT['id']."', '".$rowsOT['username']."', '".$rowsOT['firstname']."', '".$rowsOT['lastname']."', '$coockie_hash', 'web')";
            mysqli_query($con_new, $generateSession);

            // Set log var
            $LOG_IP = $_SERVER['REMOTE_ADDR'];
            $LOG_USERID = $rowsOT['id'];

            //log login
            $loginlog_query =    "INSERT INTO `login_log` 
                        (user_id, 
                        ip)
                        VALUES 
                        ('$LOG_USERID',
                        '$LOG_IP')";
            mysqli_query($con_new, $loginlog_query);
            
            // Redirect to user home page
            header("Location: /redirect".$redirect_url);
        } else {
            $error = "loginerror";
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


