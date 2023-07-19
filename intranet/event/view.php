<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
include '../../private/web/assets/team.php';
include '../../private/config.php';
?>

<?php
$eventID = $_GET["id"];
$event = "SELECT * FROM `event` WHERE id = '$eventID'";
$event = $con_public_new->query($event);
$event = $event->fetch_assoc();
$eventID = $event["id"];
if(empty($event)) {
    header("Location: ../event");
    exit();
};

$eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$eventID' AND `start` >= NOW()")->fetch_assoc();
if(!isset($eventCalendar)) {
    $eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$eventID' ORDER BY `start` DESC")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veranstaltung -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/view.css">
                
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <?php
    include("../../private/intranet/event/header.php");
    top("Veranstaltung");
    ?>

        <div class="content">
            <div class="left">
                <div class="main">
                    <div class="image">
                    <?php
                    $img_path = '../../cdn/event/image/img-t_'. substr(md5($event["id"]), 5).'-512.jpg';
                    if(!file_exists($img_path)){
                        $img_path = 'https://'.$domain["cdn"].'/event/placeholder/image.png';
                    } else {
                        $img_path = 'https://'.$domain["cdn"].'/event/image/img-t_'. substr(md5($event["id"]), 5) .'-512.jpg';
                    };
                    ?>
                    <img src="<?php echo($img_path); ?>">
                    </div>

                    <div class="information">
                        <?php
                            if(!empty($eventCalendar["start"])) {
                        ?>
                        <h6>Datum: </h6>
                        <p>
                            <?php
                            if(date("j.n.Y", strtotime($eventCalendar["start"])) == date("j.n.Y", strtotime($eventCalendar["edn"]))) {
                                echo(date("j.n.Y", strtotime($eventCalendar["start"])));
                            } else {
                                echo(date("j.n.Y", strtotime($eventCalendar["start"])) . " - ". date("j.n.Y", strtotime($eventCalendar["end"])));
                            }
                            ?>
                        </p>

                        <h6>Uhrzeit: </h6>
                        <p>
                            <?php
                                echo(date("H:i", strtotime($eventCalendar["start"])) . " - ". date("H:i", strtotime($eventCalendar["end"])));
                            ?>
                        </p>

                        <?php
                        }
                        ?>

                        <?php
                        if(isset($event["location"])) {
                            echo '
                            <h6>Ort: </h6>
                            <p>'.$event["location"].'</p>';
                        }
                        ?>

                        <?php
                        if(isset($event["age_from"])) {
                            echo '
                            <h6>Alter: </h6>
                            <p>'.$event["age_from"].' - '.$event["age_to"].'</p>';
                        }
                        ?>

                        <h6>Veranstalter: </h6>
                        <p class="col">
                        <?php
                            $organizerIDs = array();
                            $all_organizer = $con_public->query("SELECT * FROM `event_organizer` WHERE event_id = '$eventID'");
                            while ($organizer = $all_organizer->fetch_assoc()) {
                                array_push($organizerIDs, $organizer["user_id"]);
                                echo '<span>';
                                echo teamEntry($organizer["user_id"], "name");
                                
                                if(!empty(teamEntry($organizer["user_id"], "email"))) {
                                    echo '
                                        <a href="mailto:'.teamEntry($organizer["user_id"], "email").'">
                                            <span class="material-symbols-outlined">
                                            mail
                                            </span>
                                        </a>';
                                }
                                echo '</span>';
                            }
                        ?>
                        </p>
                    </div>
                </div>
                <div class="tools">
                    <?php
                    if(in_array($dbSESSION["user_id"], $organizerIDs) || in_array("jugendteam_admin", $dbSESSION_perm)) {
                        ?>
                        <button onclick="window.location.href=`/event/edit?id=<?php echo($eventID); ?>`">
                            <span class="material-symbols-outlined">
                            edit
                            </span>
                        </button>
                        
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="middle">
                <h4><?php echo($event["title"]); ?></h4>
                <p class="description">
                    <?php
                    echo(str_replace("\n", "<br>", $event["description"]));
                    ?>
                </p>
                <div class="links">
                    
                    <?php
                    $links = "SELECT * FROM `event_link` WHERE event_id = '$eventID'";
                    $links = $con_public_new->query($links);

                    if(!($links->num_rows === 0)) {
                        echo '
                        <h3>Links</h3>
                        ';
                    }
                    
                    while ($link = $links->fetch_assoc()) {
                        echo '
                            <a href="'.$link["link"].'">'.$link["title"].'</a>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>