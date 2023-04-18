<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
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
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veranstaltung - PJugend</title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/view.css">
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
                    $img_path = '../../cdn/event/image/img-t_'. substr(md5($event["id"]), 5).'.jpg';
                    if(!file_exists($img_path)){
                        $img_path = 'https://'.$domain["cdn"].'/event/placeholder/image.png';
                    } else {
                        $img_path = 'https://'.$domain["cdn"].'/event/image/img-t_'. substr(md5($event["id"]), 5) .'.jpg';
                    };
                    ?>
                    <img src="<?php echo($img_path); ?>">
                    </div>

                    <div class="information">
                        <?php
                            if(!empty($event["date_from"])) {
                        ?>
                        <h6>Datum: </h6>
                        <p>
                            <?php
                            if(date("j.n.Y", strtotime($event["date_from"])) == date("j.n.Y", strtotime($event["date_to"]))) {
                                echo(date("j.n.Y", strtotime($event["date_from"])));
                            } else {
                                echo(date("j.n.Y", strtotime($event["date_from"])) . " - ". date("j.n.Y", strtotime($event["date_to"])));
                            }
                            ?>
                        </p>

                        <h6>Uhrzeit: </h6>
                        <p>
                            <?php
                                echo(date("H:i", strtotime($event["date_from"])) . " - ". date("H:i", strtotime($event["date_to"])));
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
                        <p>
                            <?php
                                $organizerString = str_replace(";", "','", $event["organizer"]);
                                $organizer = "SELECT firstname, lastname FROM `accounts` WHERE id IN ('$organizerString')";
                                $organizer = $con_new->query($organizer);

                                while ($person = $organizer->fetch_assoc()) {
                                    echo($person["firstname"] . " " . $person["lastname"] . "<br>");
                                }
                            ?>
                        </p>
                        
                    </div>
                </div>
                <div class="tools">
                    <?php
                    $organizerIDs = explode(";", $event["organizer"]);
                    if(in_array($dbSESSION["user_id"], $organizerIDs) || in_array("admin", $dbSESSION_perm)) {
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