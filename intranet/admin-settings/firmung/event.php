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

$firmung = "SELECT * FROM firmung WHERE `year` = $year";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

if(!isset($firmung)) {
    header("Location: /admin-settings/firmung");
    exit();
}

$firmungID = $firmung["id"];
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmung <?php echo($firmung["year"]) ?> Bearbeiten - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/firmung/css/event.css">
                
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
                <h1>Aktionen</h1>
                <p>Hier kannst du alle Aktionen verwalten</p>

                <div class="btn">
                    <button onclick="window.location.href=`/admin-settings/firmung/event/add?year=<?php echo($year); ?>`" title="Neue Aktion Hinzufügen">
                        <span class="material-symbols-outlined">
                        add
                        </span>
                    </button>
                </div>

                <div class="events">
                    <?php
                        $events = "SELECT * FROM firmung_event WHERE firmung_id = '$firmungID'";
                        $events = $con_firmung->query($events);

                        while ($event = $events->fetch_assoc()) {
                            $curentType = "SELECT * FROM firmung_event_type WHERE id = '".$event["type"]."'";
                            $curentType = $con_firmung->query($curentType);
                            $curentType = $curentType->fetch_assoc();
                            echo '
                            <div class="single" onclick="window.location.href=`/admin-settings/firmung/event/view?year='.$_GET["year"].'&id='.$event["id"].'`">
                                <h2>'.$event["title"].'</h2>
                                <p><i>'.$curentType["name"].'</i></p>
                                <p>'.date("H:i d.m.Y", strtotime($event["start"])).'</p>
                            </div>
                            ';
                        }
                    ?>
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