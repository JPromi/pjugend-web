<?php
include '../private/config.php';
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
</head>
<?php
    include "../intranet/assets/html/favicon.html";
    include "../private/config.php";
    require("../private/database/int.php");


    //redirect logic
?>
<body>
    <section class="top">
   
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
                
                <input type="submit" value="Zurücksetzen" name="submit" require/>
            </form>
            <?php
            }
            ?>
        </section>
    </section>
</body>

<?php

//include("assets/html/footer.php");
?>
</html>

<?php
if(isset($_POST["username"])) {

}
?>


