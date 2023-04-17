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

if(!(in_array($dbSESSION["user_id"], explode(";", $event["organizer"]))) && !(in_array("admin", $dbSESSION_perm))) {
    header("Location: ../event");
    exit();
}

?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veranstaltung Bearbeiten - PJugend</title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/edit.css">
</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <?php
    include("../../private/intranet/event/header.php");
    top("Veranstaltung Bearbeiten");
    ?>
    
        <form class="content" method="post">

            <div class="alert disabled" id="alert">
                <div class="alertbox organizer disabled" id="organizer">
                    <h1>Veranstalter Bearbeiten</h1>
                    <div class="list">
                        <?php
                        $organizerIds = explode(";", $event["organizer"]);
                        $user = "SELECT id, firstname, lastname FROM `accounts` ORDER BY firstname";
                        $user = $con_new->query($user);
                        while ($suser = $user->fetch_assoc()) {
                            $isOrganizer = "";
                            if(in_array($suser["id"], $organizerIds)) {
                                $isOrganizer = "checked";
                            };
                            echo '
                                <label>
                                    <input type="checkbox" name="organizer[]" value="'.$suser["id"].'" '.$isOrganizer.'>
                                    <p>'.$suser["firstname"].' '.$suser["lastname"].'</p>
                                </label>
                            ';
                        }
                        ?>
                    </div>
                </div>

                <div class="back disabled" id="back" onclick="removealert()">

                </div>
            </div>

            <div class="left">
                <div class="image">
                    <img src="https://cdn.pjugend.jpromi.com/event/placeholder/image.png">
                </div>

                <div class="information">
                    <h6>Zeit: </h6>
                    <div class="single">
                        <input type="datetime-local" name="date_from" value="<?php echo($event["date_from"]); ?>">
                        <p> - </p>
                        <input type="datetime-local" name="date_to" value="<?php echo($event["date_to"]); ?>">

                    </div>


                        <h6>Ort: </h6>
                        <input type="text" name="location" value="<?php echo($event["location"]); ?>">



                        <h6>Alter: </h6>
                        <div class="single">
                            <input type="number" name="age_from" value="<?php echo($event["age_from"]); ?>">
                            <p> - </p>
                            <input type="number" name="age_to" value="<?php echo($event["age_to"]); ?>">
                        </div>

                    <h6>Veranstalter: </h6>
                    <a onclick="alertadd('organizer')">
                        <span class="material-symbols-outlined">
                        edit
                        </span>
                    </a>
                    
                </div>
            </div>
            <div class="middle">
                <input type="text" name="title" class="title" value="<?php echo($event["title"]); ?>">
                <textarea class="description" name="description"><?php
                    echo($event["description"]);
                ?></textarea>
                <div class="links" id="links">
                    <h3>Links</h3>
                    <?php
                    $links = "SELECT * FROM `event_link` WHERE event_id = '$eventID'";
                    $links = $con_public_new->query($links);
                    $linkCounter = 1;
                    while ($link = $links->fetch_assoc()) {
                        echo '
                            <div class="single" id="link'.$linkCounter.'">
                                <a onclick="removeLink('.$linkCounter.')" class="removeLink">
                                    <span class="material-symbols-outlined">
                                    remove
                                    </span>
                                </a>
                                <input type="text" placeholder="Titel" name="linkTitle[]" value="'.$link["title"].'">
                                <input type="text" placeholder="Link" name="link[]" class="link" value="'.$link["link"].'">
                            </div>
                        ';
                        $linkCounter++;
                    }
                    ?>
                    
                </div>
                <div class="single" id="con">
                        <a onclick="addLink()">
                            <span class="material-symbols-outlined">
                            add
                            </span>
                        </a>
                </div>

                <div class="btn">
                    <input type="submit" value="Speichern" name="submit">
                    <input type="submit" value="Abbrechen" name="cancle">
                </div>
            </div>
        </form>
        <script src="js/edit.js"></script>
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php
if(!empty($_POST["submit"])) {
    $Ptitle = htmlspecialchars($_POST["title"]);
    $Pdescription = htmlspecialchars($_POST["description"]);

    if(!(empty($_POST["date_from"]))) {
        $Pdate_from = date("Y-m-d H:i", strtotime($_POST["date_from"]));
    } else {
        $Pdate_from = "";
    };

    if(!(empty($_POST["date_to"]))) {
        $Pdate_to = date("Y-m-d H:i", strtotime($_POST["date_to"]));
    } else {
        $Pdate_to = "";
    };
    
    $Page_from = $_POST["age_from"];
    $Page_to = $_POST["age_to"];
    $Plocation = htmlspecialchars($_POST["location"]);
    $eventID = $eventID;
    //$Pprice = $_POST["price"];
    //$Pspec_group = $_POST["only_specific_group"];
    $Porganizer = implode(";", $_POST["organizer"]);
    echo($Porganizer);
    $updateEvent = "UPDATE `event` SET 
                                        `title` = '$Ptitle',
                                        `description` = '$Pdescription',
                                        `date_from` = '$Pdate_from',
                                        `date_to` = '$Pdate_to',
                                        `age_from` = '$Page_from',
                                        `age_to` = '$Page_to',
                                        `location` = '$Plocation',
                                        `organizer` = '$Porganizer'
                                        WHERE `id`='$eventID'";
    mysqli_query($con_public, $updateEvent);

    //links
    $removeLinks = "DELETE FROM `event_link` WHERE `event_id`='$eventID'";
    mysqli_query($con_public, $removeLinks);

    for ($i=0; $i < count($_POST["link"]); $i++) { 
        $addLink = "INSERT INTO `event_link` (event_id, title, link) VALUES ('$eventID', '".$_POST["linkTitle"][$i]."', '".$_POST["link"][$i]."')";
        mysqli_query($con_public, $addLink);
    }

    echo '<meta http-equiv="refresh" content="0; url=view?id='.$eventID.'">';
    
} else if (!empty($_POST["cancle"])) {
    echo '<meta http-equiv="refresh" content="0; url=view?id='.$eventID.'">';
}
?>