<?php
include '../private/config.php';
include '../private/database/int.php';
?>

<?php
require '../private/email/src/Exception.php';
require '../private/email/src/PHPMailer.php';
require '../private/email/src/SMTP.php';

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort Zurücksetzten - <?php echo($conf_title["intranet"]) ?></title>
    <link rel="stylesheet" href="css/password-reset.css">

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
?>
<body>
    <section class="top">
   
    <?php
    $resetIDget = mysqli_real_escape_string($con, $_GET["reset"]);
    $reset = "SELECT * FROM `password_reset` WHERE reset_id = '$resetIDget'";
    $reset = $con->query($reset);
    $reset = $reset->fetch_assoc();

    if(isset($reset)) {
    ?>
        <div class="login">
            <div class="title">
                <h1>Passwort zurücksetzten</h1>

                <form class="form" method="POST">

                    <!--Password-->
                    <p>Passwort: </p>
                    <label>
                        <span class="material-symbols-outlined">
                        lock
                        </span>
                        <input type="password" name="password[]" placeholser="Passwort" required>
                    </label>

                    <!--Password repeat-->
                    <p>Wiederholen: </p>
                    <label>
                        <span class="material-symbols-outlined">
                        lock
                        </span>
                        <input type="password" name="password[]" placeholser="Passwort" required>
                    </label>
                    
                    <input type="submit" value="Zurücksetzen" name="reset" require/>
                </form>
            </div>
    </div>
    <?php
    } else {

    ?>
        <!--login-->
        <section class="login <?php echo($error)?>">
            <div class="title">
                <h1>Passwort Zurücksetzen</h1>
            </div>
            <?php
            if (isset($_POST["username"])) {
            ?>

                <div class="message">
                    <p>E-Mail wurde versendet</p>
                    <a href="/">Zurück</a>
                </div>

            <?php
            } else {
            ?>
            <form class="form" method="POST">

                <!--Username-->
                <p>Benutzername</p>
                <label>
                    <span class="material-symbols-outlined">
                    person
                    </span>
                    <input type="text" name="username" require/>
                </label>
                
                <input type="submit" value="Zurücksetzen" name="resetpassword" require/>
            </form>
            <?php
            }
            ?>
        </section>
    </section>

    <?php
    }
    ?>
</body>

<?php

//include("assets/html/footer.php");
?>
</html>

<?php
if(isset($_POST["username"])) {
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $user = "SELECT id, username, email, firstname, lastname FROM accounts WHERE username = '$username'";
    $user = $con->query($user);
    $user = $user->fetch_assoc();
    
    if(!empty($user)) {

        $resetID = $user["id"].bin2hex(random_bytes(10)).$user["username"];;
        $user_id = $user["id"];

        $insertReset = "INSERT INTO `password_reset` (user_id, reset_id) VALUES ('$user_id', '$resetID')";
        $con->query($insertReset);

    
        $message = '
        Passwort Vergessen?

        Um dein Passort zurückzuseten gehe auf https://'.$domain["auth"].'/password-reset?reset='.$resetID.'


        Dies ist eine Automatisch generierte Nachricht
        ';

        $messageHTML = '
        <!DOCTYPE html>
        <html lang="de">

        <head>
            <meta charset="UTF-8">
        </head>

        <body>

            <style>
                body {
                    width: 100%;
                }
                .content {
                    width: 30rem;
                    display: flex;
                    flex-direction: column;
                    padding: 1rem;
                    margin: 0 auto;
                    background-color: #f5f5f520;
                    border-radius: 1.5rem;
                    box-shadow: .25rem .25rem 2rem #00000020;
                }

                .content .top {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                }

                .content .top .image {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                }

                .content .top img {
                    width: 9rem;
                    height: 9rem;
                    object-fit: contain;
                }

                .content .middle {
                    width: 100%;
                    padding-bottom: 1rem;
                    display: flex;
                    justify-content: center;
                }

                .content .middle a {
                    background-color: #4595a3;
                    border: 0;
                    border-radius: .75rem;
                    padding: 1rem 2rem;
                    color: #ffffff;
                    text-decoration: none;
                    margin: 0 auto;
                }

                .disclaimer {
                    margin-top: 2rem;
                    color: #00000075;
                }
            </style>

            <div class="content">
                
                <div class="top">
                    <div class="image">
                        <img src="https://'.$domain["cdn"].'/logo/pjugend/p_jugend-blue.svg" alt="PJugend">
                    </div>

                    <h2>Passwort vergessen?</h2>
                    <p>Drücke unten auf den Knopf um dein Passwort zurück zu setzen</p>
                
                </div>
                
                <div class="middle">
                    <a href="https://'.$domain["auth"].'/password-reset?reset='.$resetID.'">Zurücksetzen</a>            
                </div>

            </div>
            <p class="disclaimer">Dies ist eine Automatisch generierte Nachricht</p>

        </body>
        </html>
        ';
        
        //create new mail
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;     //debug                  
            $mail->isSMTP();
            $mail->Host       = $conf_mail["host"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $conf_mail["username"];
            $mail->Password   = $conf_mail["password"];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $conf_mail["port"];
            $mail->CharSet    = 'UTF-8';

            //Recipients
            $mail->setFrom($conf_mail["email"], $conf_title["intranet"]);
            $mail->addAddress($user["email"], $user["firstname"].' '.$user["lastname"]);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Passwort Vergessen';
            $mail->Body    = $messageHTML;
            $mail->AltBody = $message;

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<?php
if(isset($_POST["reset"])) {
    if($_POST["password"][0] == $_POST["password"][1]) {

        $password = stripslashes($_POST['password'][0]);
        $password = mysqli_real_escape_string($con, $password);
        $password = password_hash($password, PASSWORD_ARGON2ID);
        $userID = $reset["user_id"];

        $updatePassword = "UPDATE accounts SET `password` = '$password' WHERE id = '$userID'";
        $con->query($updatePassword);

        $deleteReset = "DELETE FROM password_reset WHERE reset_id = '$resetIDget'";
        $con->query($deleteReset);

        echo '<meta http-equiv="refresh" content="0; url=/">';
    }
}
?>

