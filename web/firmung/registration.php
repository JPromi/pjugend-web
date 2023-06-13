<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';
?>

<?php
//$year = mysqli_real_escape_string($con_firmung, $_GET["year"]);
//$firmung = "SELECT * FROM firmung WHERE `year` = $year";

$todayY = date('Y');
$today = date('Y-m-d H:i:s');
$todaySt = strtotime($today);
$firmungID = "0";

//get registration
$registrationsFirmung = "SELECT * FROM firmung_registration_settings";
$registrationsFirmung = $con_firmung->query($registrationsFirmung);

while ($regFirmung = $registrationsFirmung->fetch_assoc()) {
    if(strtotime($regFirmung["end"]) > $todaySt && $todaySt > strtotime($regFirmung["start"])) {
        $firmungID = $regFirmung["firmung_id"];
    }
}

//get firmung
$firmung = "SELECT * FROM firmung WHERE `id` = '$firmungID'";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

$firmungID = $firmung["id"];

?>

<?php
$weekdays = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/registration.css">
    
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>
    
</head>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/nav.php';
?>

<body>
    <div class="content">
        <div class="information">
            <?php
                $img_logo_root = $_SERVER["DOCUMENT_ROOT"]."/../cdn/firmung/logo/".$firmung["year"].'.png';

                if(file_exists($img_logo_root)) {
                    $img_logo_path = "https://".$domain["cdn"]."/firmung/logo/".$firmung["year"].'.png';
                } else {
                    $img_logo_path = "https://".$domain["cdn"]."/firmung/logo/default.svg";
                }
            ?>
            <img class="logo" src="a<?php echo($img_logo_path); ?>">
            <h1>Firmung <?php echo($firmung["year"]); ?> Anmeldung</h1>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="block">
                <h2>Firmling</h2>
                <table>

                    <tr>
                        <td>Vorname:</td>
                        <td>
                            <input type="text" name="firstname" placeholder="Vorname">
                        </td>
                    </tr>

                    <tr>
                        <td>Mittelname:</td>
                        <td>
                            <input type="text" name="middlename"  placeholder="Mittelname">
                        </td>
                    </tr>

                    <tr>
                        <td>Nachname:</td>
                        <td>
                            <input type="text" name="lastname" placeholder="Nachname">
                        </td>
                    </tr>

                    <tr>
                        <td>Geschlecht:</td>
                        <td>
                        <select name="gender">
                            <option value=""></option>
                            <option value="Männlich">Männlich</option>
                            <option value="Männlich">Weiblich</option>
                            <option value="Divers">Divers</option>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Geburtsdatum:</td>
                        <td>
                            <input type="date" name="birthdate">
                        </td>
                    </tr>

                    <tr>
                        <td>Adresse</td>
                        <td class="address">
                            <div>
                                <input type="text" name="address_city" placeholder="Ort">
                                <input type="text" name="address_plz" placeholder="PLZ">
                            </div>
                            <div>
                                <input type="text" name="address_street" placeholder="Straße">
                                <input type="text" name="address_housenumber" placeholder="Nr.">
                                <input type="text" name="address_stair" placeholder="Stiege">
                                <input type="text" name="address_door" placeholder="Tür">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>Telefonnummer:</td>
                        <td>
                            <input type="tel" name="phone_number" placeholder="Telefonnummer">
                        </td>
                    </tr>

                    <tr>
                        <td>E-Mail:</td>
                        <td>
                            <input type="email" name="email" placeholder="E-Mail">
                        </td>
                    </tr>

                    <tr>
                        <td>Anmeldetag:</td>
                        <td>
                        <select name="meeting_date">
                            <option value=""></option>
                        </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Wunschfimstundentermin:</td>
                        <td>
                            <select name="firmstunde_date">
                                <option value="0">egal</option>
                                <?php
                                $firmstunden = "SELECT * FROM firmung_group_block WHERE firmung_id = '$firmungID'";
                                $firmstunden = $con_firmung->query($firmstunden);

                                while ($firmstunde = $firmstunden->fetch_assoc()) {
                                    echo '
                                    <option value="'.$firmstunde["id"].'">'.$weekdays[$firmstunde["weekday"]].' '.$firmstunde["name"].' ('.date("H:i", strtotime($firmstunde["from_date"])).' - '.date("H:i", strtotime($firmstunde["to_date"])).' Uhr)</option>
                                    ';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="block">
                <h2>Erziehungsberechtigter</h2>
                <table>
                    <tr>
                        <td>Beziehung:</td>
                        <td>
                            <select name="lg_type">
                                <option value=""></option>
                                <option value="Mutter">Mutter</option>
                                <option value="Vater">Vater</option>
                                <option value="Betreuer">Betreuer</option>
                                <option value="Sonnstiges">Sonnstiges</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Geschlecht:</td>
                        <td>
                            <select name="lg_gender">
                                <option value=""></option>
                                <option value="Männlich">Männlich</option>
                                <option value="Männlich">Weiblich</option>
                                <option value="Divers">Divers</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Vorname:</td>
                        <td>
                            <input type="text" name="lg_firstname" placeholder="Vorname">
                        </td>
                    </tr>

                    <tr>
                        <td>Nachname:</td>
                        <td>
                            <input type="text" name="lg_lastname"  placeholder="Nachname">
                        </td>
                    </tr>

                    <tr>
                        <td>Telefonnummer:</td>
                        <td>
                            <input type="tel" name="lg_phone_number"  placeholder="Telefonnummer">
                        </td>
                    </tr>

                    <tr>
                        <td>E-Mail:</td>
                        <td>
                            <input type="email" name="lg_email"  placeholder="E-Mail">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="block">
                <h2>Hinweise</h2>

                <p>Damit deine Voranmeldung verbindlich wird, ist es notwendig, an dem von dir ausgewählten Anmeldetag in den Pfarrhof (Marktplatz 14, 2380 Perchtoldsdorf) zu kommen.</p><br>
            
                <p>Falls du zu dem von dir ausgewählten Anmeldetag aus wichtigen Gründen doch nicht kommen kannst, kontaktiere bitte vorher (!) Britta Jacobi per Mail (britta.jacobi@pfarre-perchtoldsdorf.at) oder telefonisch (01 / 869 02 26 / 14).</p><br>
            
                <p>Der Firmling und der Erziehungsberechtigter erhalltet eine E-Mail mit weitern informationen.</p><br>

                <p>Wenn diese Mail nicht eingetroffen ist, kontorolliere deinen Spam-Ordern. Wenn du auch dort keine E-Mail bekommen hast, bitte kontaktiere <a href="mailto:jonas.prominzer@jpromi.com">jonas.prominzer@jpromi.com</a> per mail.</p>

            </div>

            <div class="block">
                <h2>Hinweise zum Datenschutz</h2>
                <div>
                    <input type="checkbox" name="dsgvo[]" value="1" required>
                    <p>Hiermit stimme ich zu, dass folgende personenbezogenen Daten des Firmlings und des Erziehungsberechtigter für die Firmvorbereitung vom Firmteam der Pfarre Perchtoldsdorf verarbeitet werden dürfen und wir zu diesem Zweck kontaktiert werden dürfen: Name, Telefonnummer, Mail, Geschlecht, Geburtsdatum (nur Firmling), Adresse (nur Firmling). Wir können diese Zustimmung jederzeit schriftlich per Mail an 'firmteam@pfarre-perchtoldsdorf.at' widerrufen.</p>
                </div>

                <div>
                    <input type="checkbox" name="dsgvo[]" value="2" required>
                    <p>Ich habe die Informationen zum Datenschutz gelesen, die <a href="https://www.bischofskonferenz.at/datenschutz/">hier</a> verfügbar sind.</p>
                </div>
                
            </div>

            <div class="block">
                <input type="submit" name="subtmi" value="Anmelden">
            </div>
        </form>
    </div>
</body>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/footer.php';
?>

</html>