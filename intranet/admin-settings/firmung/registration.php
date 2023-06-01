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

$firmung = "SELECT * FROM firmung WHERE `year` = $year";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

$firmungID = $firmung["id"];

if(!isset($firmung)) {
    header("Location: /admin-settings/firmung");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmung <?php echo($firmung["year"]) ?> Registrirungen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/firmung/css/registration.css">
                
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

                <div class="title">
                    <h1>Registrirungen</h1>
                    <p>Hier findest du alle Firmlinge die sich Registriert haben.</p>
                </div>

                <div class="registration">
                    <div class="search">
                        <input type="text" placeholder="Suche" id="filterTable" onkeyup="search('regData')">

                        <span class="material-symbols-outlined" id="searchRemove" onclick="clearInput('filterTable')">
                        close
                        </span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <div class="ct">
                                        ID
                                        <div class="arrow"></div>
                                    </div>
                                </th>
                                <th>
                                    <div class="ct">
                                        Vorname
                                        <div class="arrow"></div>
                                    </div>
                                </th>
                                <th>
                                    <div class="ct">
                                        Nachname
                                        <div class="arrow"></div>
                                    </div>
                                </th>
                                <th>
                                    <div class="ct">
                                        Datum
                                        <div class="arrow"></div>
                                    </div>
                                </th>
                                <th>
                                    <div class="ct">
                                        Termin
                                        <div class="arrow"></div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="regData">
                            <tr><!--order--></tr>
                            <?php
                            $registrations = "SELECT * FROM firmung_registration WHERE firmung_id = $firmungID";
                            $registrations = $con_firmung->query($registrations);

                            while ($registration = $registrations->fetch_assoc()) {
                                echo '
                                <tr class="entry" onclick="window.location.href=`/admin-settings/firmung/registration/view?year='.$firmung["year"].'&regID='.$registration["id"].'`">
                                    <td>'.$registration["id"].'</td>
                                    <td class="name">'.$registration["firstname"].'</td>
                                    <td class="name">'.$registration["lastname"].'</td>
                                    <td>'.date("d.m.Y", strtotime($registration["timestamp"])).'</td>
                                    <td>'.date("H:i d.m.Y", strtotime($registration["timestamp"])).'</td>
                                </tr>
                                ';
                            }
                            ?>
                        </tbody>
                    </table>
                    <script src="/admin-settings/firmung/registration/js/order.js"></script>
                    <script src="/admin-settings/firmung/registration/js/search.js"></script>
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