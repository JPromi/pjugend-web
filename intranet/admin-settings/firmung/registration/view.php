<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';
?>

<?php
if(!(in_array("firmung_admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
$year = mysqli_real_escape_string($con_firmung, $_GET["year"]);
$regID = mysqli_real_escape_string($con_firmung, $_GET["regID"]);

$firmung = "SELECT * FROM firmung WHERE `year` = $year";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

$firmungID = $firmung["id"];

if(!isset($firmung)) {
    header("Location: /admin-settings/firmung");
    exit();
}

$registration_firmling = "SELECT * FROM firmung_registration WHERE `firmung_id` = $firmungID AND id = $regID";
$registration_firmling = $con_firmung->query($registration_firmling);
$registration_firmling = $registration_firmling->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmung <?php echo($firmung["year"]) ?> Registrierung - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="css/view.css">
                
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <?php
        include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/header.php';
        top("Firmung");
        ?>
        <div class="settings">
            <?php
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/nav-firmung_single.php';
            ?>
            <div class="middle">
                <h1>Anmeldung von <?php echo $registration_firmling["firstname"].' '. $registration_firmling["lastname"] ?></h1>
                <div class="bot">
                    <div class="single">
                        <h3>Allgemein</h3>
                        <span class="line-title"></span>
                        <p><b>Vorname:</b> <?php echo $registration_firmling["firstname"] ?></p>
                        <p><b>Mittelname:</b> <?php echo $registration_firmling["middlename"] ?></p>
                        <p><b>Nachname:</b> <?php echo $registration_firmling["lastname"] ?></p>
                        <p><b>Geburtsdatum:</b> <?php echo date("d.m.Y", strtotime($registration_firmling["birthdate"])) ?></p>
                    </div>

                    <div class="single">
                        <?php
                        $housenumber = $registration_firmling["address_street"] . " " . $registration_firmling["address_housenumber"];

                        if(!empty($registration_firmling["address_stair"])) {
                            $housenumber = $housenumber . ", " . $registration_firmling["address_stair"];
                        }

                        if(!empty($registration_firmling["address_door"])) {
                            $housenumber = $housenumber . " / " . $registration_firmling["address_door"];
                        }
                        ?>
                        <h3>Daten</h3>
                        <span class="line-title"></span>
                        <p><b>Telefonnummer:</b> <?php echo $registration_firmling["phone_number"] ?></p>
                        <p><b>E-Mail:</b> <?php echo $registration_firmling["email"]; ?></p>
                        <p><b>Staatsbürgerschaft:</b> <?php echo $registration_firmling["nationality"] ?></p>
                        <p><b>Ort:</b> <?php echo $registration_firmling["address_zip"] ?> <?php echo $registration_firmling["address_city"] ?></p>
                        <p><b>Hausnummer:</b> <?php echo $housenumber ?></p>
                        <p><b>SV-Nummer:</b> <?php echo $registration_firmling["sv_number"] ?></p>
                    </div>

                    <div class="single">
                        <?php

                        if($registration_firmling["is_christen"] == "1") {
                            $is_christen = "Ja";
                        } else {
                            $is_christen = "Nein";
                        }

                        if(!empty($registration_firmling["christen_date"])) {
                            $christen_date = date("d.m.Y", strtotime($registration_firmling["christen_date"]));
                        } else {
                            $christen_date = "";
                        }
                        ?>

                        <h3>Kirche</h3>
                        <span class="line-title"></span>
                        <p><b>Getauft:</b> <?php echo $is_christen ?></p>
                        <p><b>Taufdatum:</b> <?php echo $christen_date ?></p>
                    </div>

                    <div class="single">
                        <h3>Bild</h3>
                        <span class="line-title"></span>
                        <div class="image">
                            <img src="https://<?php echo($domain["cdn"]); ?>/firmung/firmlinge/registration?year=<?php echo($firmung["year"])?>&regID=<?php echo($registration_firmling["id"]) ?>">
                        </div>
                    </div>

                    <div class="single">
                        <h3>Anmelde Informationen</h3>
                        <span class="line-title"></span>
                        <p><b>Anmelde Termin:</b> Noch nicht in der DB</p>
                        <p><b>Formular geschickt:</b> <?php echo date("H:i d.m.Y", strtotime($registration_firmling["timestamp"])) ?></p>
                        <p><b>IP:</b> <?php echo $registration_firmling["ip"]; ?></p>
                    </div>

                </div>

                <div class="btn">
                    <a href="/admin-settings/firmung/firmlinge/add?year=<?php echo $year ?>&regID=<?php echo $regID ?>">Firmling Anlegen</a>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>