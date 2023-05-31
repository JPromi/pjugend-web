<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/image/firmung.php';

?>

<?php
if(!(in_array("firmung_admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
$year = mysqli_real_escape_string($con_firmung, $_GET["year"]);
$eventID = mysqli_real_escape_string($con_firmung, $_GET["id"]);

$firmung = "SELECT * FROM firmung WHERE `year` = $year";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

$firmungID = $firmung["id"];

$event = "SELECT * FROM firmung_event WHERE firmung_id = '$firmungID' AND id = '$eventID'";
$event = $con_firmung->query($event);
$event = $event->fetch_assoc();

if(!isset($firmung)) {
    header("Location: /admin-settings/firmung");
    exit();
}

if(!isset($event)) {
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
    <title>Firmung <?php echo($firmung["year"]) ?> Aktion - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/firmung/event/css/view.css">
                
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
                <h1>Aktion - <?php echo($event["title"]); ?></h1>
                <p><?php echo($event["description"]); ?></p>

                <div class="btn">
                    <button onclick="window.location.href=`/admin-settings/firmung/event/edit?year=<?php echo($year); ?>&id=<?php echo($eventID); ?>`" title="Aktion Bearbeiten">
                        <span class="material-symbols-outlined">
                        edit
                        </span>
                    </button>
                </div>

                <div class="informations">
                    <div class="single">
                        <?php
                        $eventType = $event["type"];
                        $eventType = "SELECT * FROM firmung_event_type WHERE id = '$eventType'";
                        $eventType = $con_firmung->query($eventType);
                        $eventType = $eventType->fetch_assoc();
                        ?>
                        <h3>Informationen</h3>
                        <span class="line-title"></span>
                        <p><b>Start:</b> <?php echo date("H:i d.m.Y", strtotime($event["start"])); ?></p>
                        <p><b>Ende:</b> <?php echo date("H:i d.m.Y", strtotime($event["end"])); ?></p>
                        <p><b>Ort:</b> <?php echo($event["location"]); ?></p>
                        <p><b>Platz:</b> <?php echo($event["max_space"]); ?> Personen</p>
                        <p><b>Art:</b> <?php echo($eventType["name"]); ?></p>
                    </div>

                    <div class="single">
                        <?php
                        $firmbegleiter = "SELECT * FROM firmung_event_firmbegleiter WHERE event_id = '$eventID'";
                        $firmbegleiter = $con_firmung->query($firmbegleiter);
                        ?>
                        <h3>Firmbegleiter</h3>
                        <span class="line-title"></span>
                        <?php
                            $eventFirmbegleiter = array();
                            while ($firmbegleiterEvent = $firmbegleiter->fetch_assoc()) {
                                $firmbegleiter1 = "SELECT firstname, lastname FROM accounts WHERE id = '".$firmbegleiterEvent["user_id"]."'";
                                $firmbegleiter1 = $con->query($firmbegleiter1);
                                $firmbegleiter1 = $firmbegleiter1->fetch_assoc();
                                array_push($eventFirmbegleiter, $firmbegleiter1["lastname"]." ".$firmbegleiter1["firstname"]);
                            }

                            sort($eventFirmbegleiter);

                            foreach ($eventFirmbegleiter as $firmbegleiter) {
                                echo '
                                <p>'.$firmbegleiter.'</p>
                                ';
                            }
                        ?>
                    </div>

                    <div class="single">
                        <?php
                        $firmlinge = "SELECT * FROM firmung_event_firmling WHERE event_id = '$eventID'";
                        $firmlinge = $con_firmung->query($firmlinge);
                        ?>
                        <h3>Angemeldete Firmlinge</h3>
                        <span class="line-title"></span>
                        <?php
                            $eventFirmlinge = array();
                            while ($firmlingEvent = $firmlinge->fetch_assoc()) {
                                $firmling = "SELECT * FROM firmling WHERE id = '".$firmlingEvent["firmling_id"]."'";
                                $firmling = $con_firmung->query($firmling);
                                $firmling = $firmling->fetch_assoc();
                                array_push($eventFirmlinge, $firmling["lastname"]." ".$firmling["firstname"]);
                            }

                            sort($eventFirmlinge);

                            foreach ($eventFirmlinge as $firmling) {
                                echo '
                                <p>'.$firmling.'</p>
                                ';
                            }
                        ?>
                    </div>

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