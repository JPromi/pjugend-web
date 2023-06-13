<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/firmung/auth_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';
?>

<?php
$firmling = $dbSESSION_firmling["firmling_id"];
$firmling = "SELECT * FROM firmling WHERE id = '$firmling'";
$firmling = $con_firmung->query($firmling);
$firmling = $firmling->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmling - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/settings.css">
    
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>
    
</head>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/nav.php';
?>

<body>
    <div class="content">

        <div class="top">
            <h1>Einstellungen</h1>
            <p>Hier kannst du dein Profil bearbeiten</p>
        </div>

        <form method="POST">

            <!--profil-->
            <div class="single">
                <h3>Profil</h3>
                <table>

                    <tr>
                        <td>Benutzername:</td>
                        <td><input type="text" name="username" value="<?php echo $firmling["username"] ?>" required></td>
                    </tr>

                    <tr>
                        <td>Passwort:</td>
                        <td><input type="text" name="password[]"></td>
                    </tr>

                    <tr>
                        <td>Passwort Wiederholen:</td>
                        <td><input type="text" name="password[]"></td>
                    </tr>

                </table>
            </div>

            <!--Persönliche Daten-->
            <div class="single">
                <h3>Persönliche Daten</h3>
                <table>

                    <tr>
                        <td>Vorname:</td>
                        <td><input type="text" name="firstname" value="<?php echo $firmling["firstname"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Mittelname:</td>
                        <td><input type="text" name="middlename" value="<?php echo $firmling["middlename"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Nachname:</td>
                        <td><input type="text" name="lastname" value="<?php echo $firmling["lastname"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Geburtsdatum:</td>
                        <td><input type="date" name="birthdate" value="<?php echo $firmling["birthdate"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>E-Mail:</td>
                        <td><input type="email" name="email" value="<?php echo $firmling["email"] ?>" required></td>
                    </tr>

                    <tr>
                        <td>Telefonnummer:</td>
                        <td><input type="tel" name="phone_number" value="<?php echo $firmling["phone_number"] ?>" required></td>
                    </tr>

                    <tr>
                        <td>SV-Nummer:</td>
                        <td><input type="number" name="sv_number" value="<?php echo $firmling["sv_number"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>PLZ:</td>
                        <td><input type="text" name="address_zip" value="<?php echo $firmling["address_zip"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Ort:</td>
                        <td><input type="text" name="address_city" value="<?php echo $firmling["address_city"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Straße:</td>
                        <td><input type="text" name="address_street" value="<?php echo $firmling["address_street"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Hausnummer:</td>
                        <td><input type="text" name="address_housenumber" value="<?php echo $firmling["address_housenumber"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Stiege:</td>
                        <td><input type="text" name="address_stair" value="<?php echo $firmling["address_stair"] ?>" disabled></td>
                    </tr>

                    <tr>
                        <td>Türe:</td>
                        <td><input type="text" name="address_door" value="<?php echo $firmling["address_door"] ?>" disabled></td>
                    </tr>
                    
                </table>
            </div>
        </form>
        <div class="informations">
            <p>Wenn sich bei den Grau hinterlegten Feldern etwas geändert haben sollte, bitte melde dich bei deinem Firmbegleiter</p>
        </div>
    </div>
</body>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/footer.php';
?>

</html>