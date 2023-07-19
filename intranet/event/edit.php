<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");

include("../../private/functions/input.php");
include("../../private/intranet/image/event_cover.php");
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

if(!(in_array($dbSESSION["user_id"], explode(";", $event["organizer"]))) && !(in_array("jugendteam_admin", $dbSESSION_perm))) {
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
    <title>Veranstaltung Bearbeiten -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/edit.css">
                
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
    top("Veranstaltung Bearbeiten");
    ?>
    
        <form class="content" method="post" enctype="multipart/form-data">

            <div class="alert disabled" id="alert">

                <!--date-->
                <div class="alertbox date disabled" id="date">
                    <h1>Termine</h1>
                    
                    <a onclick="addDate()">
                        <span class="material-symbols-outlined">
                        add
                        </span>
                    </a>

                    <table class="list">
                        <tbody id="datelist">
                            <?php
                            $event_dates = $con_public->query("SELECT * FROM event_calendar WHERE event_id = '$eventID' ORDER BY `start`");
                            $event_dates_counter = 1;

                            $all_event_datesID_old = array();
                            while ($date = $event_dates->fetch_assoc()) {
                                echo '
                                    <tr id="date-'.$event_dates_counter.'">
                                        <th>'.$event_dates_counter.'</th>
                                        <td>
                                            <input type="hidden" name="date_id[]" value="'.$date["id"].'">
                                            <input type="datetime-local" name="date_start[]" value="'.$date["start"].'">
                                            -
                                            <input type="datetime-local" name="date_end[]" value="'.$date["end"].'">

                                            <label>
                                                <span class="material-symbols-outlined">
                                                close
                                                </span>
                                                <input type="button" onclick="removedate('."'".$event_dates_counter."'".')">
                                            </label>
                                        </td>
                                    </tr>
                                ';
                                array_push($all_event_datesID_old, $date["id"]);
                                $event_dates_counter++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!--organizer-->
                <div class="alertbox organizer disabled" id="organizer">
                    <h1>Veranstalter Bearbeiten</h1>
                    <div class="list">
                        <?php
                        $all_organizer = $con_public->query("SELECT * FROM `event_organizer` WHERE event_id = '$eventID'");
                        $organizerIds = array();

                        while ($organizer = $all_organizer->fetch_assoc()) {
                            array_push($organizerIds, $organizer["user_id"]);
                        }

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
                    <?php
                    $img_path = '../../cdn/event/image/img-t_'. substr(md5($event["id"]), 5).'-512.jpg';
                    if(!file_exists($img_path)){
                        $img_path = 'https://'.$domain["cdn"].'/event/placeholder/image.png';
                    } else {
                        $img_path = 'https://'.$domain["cdn"].'/event/image/img-t_'. substr(md5($event["id"]), 5) .'-512.jpg';
                    };
                    ?>
                    <img src="<?php echo($img_path); ?>" id="cover" data-original-file="<?php echo($img_path); ?>">

                    <div class="btn">
                        <label>
                            <input type="file" name="cover" dragable onchange="CoverimagePreview()" accept="image/jpeg, image/png">
                            <span class="material-symbols-outlined">
                            upload
                            </span>
                        </label>
                        <label>
                        <?php
                        echo '
                        <input id="coverDel" type="checkbox" name="coverDel" onclick="coverDelete(`'.$domain["cdn"].'`)">
                        ';
                        ?>
                            
                            <span class="material-symbols-outlined">
                            delete
                            </span>
                        </label>
                    </div>
                </div>

                <div class="information">
                    <h6>Termine: </h6>
                    <div class="single">
                        <!--<input type="datetime-local" name="date_from" value="<?php echo($event["date_from"]); ?>">
                        <p> - </p>
                        <input type="datetime-local" name="date_to" value="<?php echo($event["date_to"]); ?>">-->

                        <a onclick="alertadd('date')">
                            <span class="material-symbols-outlined">
                            edit
                            </span>
                        </a>

                    </div>


                        <h6>Ort: </h6>
                        <input type="text" name="location" value="<?php echo($event["location"]); ?>">



                        <h6>Alter: </h6>
                        <div class="single">
                            <input type="number" name="age_from" value="<?php echo($event["age_from"]); ?>">
                            <p> - </p>
                            <input type="number" name="age_to" value="<?php echo($event["age_to"]); ?>">
                        </div>

                        <h6>Kosten: </h6>
                        <div class="single">
                            <input type="number" name="costs" value="<?php echo($event["price"]); ?>">
                            <p>€</p>
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
                    <input type="submit" value="Löschen" name="delete">
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
    $Ptitle = valueCheck(htmlspecialchars($_POST["title"]));
    $Pdescription = valueCheck(htmlspecialchars($_POST["description"]));

    # $Pdate_from = valueCheckDate($_POST["date_from"]);
    # $Pdate_to = valueCheckDate($_POST["date_to"]);
    
    $Page_from = valueCheck($_POST["age_from"]);
    $Page_to =  valueCheck($_POST["age_to"]);
    $Plocation = valueCheck($_POST["location"]);

    $Pcosts = valueCheck($_POST["costs"]);

    $updateEvent = "UPDATE `event` SET 
                                        `title` = $Ptitle,
                                        `description` = $Pdescription,
                                        `age_from` = $Page_from,
                                        `age_to` = $Page_to,
                                        `location` = $Plocation,
                                        `price` = $Pcosts
                                        WHERE `id`='$eventID'";
    mysqli_query($con_public, $updateEvent);

    //organizer
    $o_organizer = $con_public->query("SELECT user_id FROM event_organizer WHERE event_id = '$eventID'");
    $o1_organizer = array();
    while ($orga = $o_organizer->fetch_assoc()) {
        array_push($o1_organizer, $orga["user_id"]);
        $tmp_usr = checkInput($orga["user_id"]);

        if(!in_array($orga["user_id"], $_POST["organizer"])) {
            $con_public->query("DELETE FROM event_organizer WHERE event_id = '$eventID' AND user_id = $tmp_usr");
        }
    }
    
    for ($i=0; $i < count($_POST["organizer"]); $i++) { 
        if(in_array($_POST["organizer"][$i], $o1_organizer)) {
        } else {
            $tmp_usr = checkInput($_POST["organizer"][$i]);
            $con_public->query("INSERT INTO event_organizer (event_id, user_id) VALUES ('$eventID', $tmp_usr)");
        }
    }

    //update calendar
    for ($i=0; $i < count($_POST["date_id"]); $i++) { 
        if(in_array($_POST["date_id"][$i], $all_event_datesID_old)) {
            $P_date_start = valueCheckDate($_POST["date_start"][$i]);
            $P_date_end = valueCheckDate($_POST["date_end"][$i]);
            $P_date_id = valueCheck($_POST["date_id"][$i]);

            $con_public->query("UPDATE event_calendar SET `start` = $P_date_start, `end` = $P_date_end WHERE id = $P_date_id AND event_id = '$eventID'");
        } elseif ($_POST["date_id"][$i] == "new") {
            $P_date_start = valueCheckDate($_POST["date_start"][$i]);
            $P_date_end = valueCheckDate($_POST["date_end"][$i]);

            $con_public->query("INSERT INTO `event_calendar` (event_id, `start`, `end`) VALUES ('$eventID', $P_date_start, $P_date_end)");
        }
    }

    foreach ($all_event_datesID_old as $oldID) {
        if(!in_array($oldID, $_POST["date_id"])) {
            $con_public->query("DELETE FROM event_calendar WHERE id = '$oldID' AND event_id = '$eventID'");
        }
    }

    //links
    $removeLinks = "DELETE FROM `event_link` WHERE `event_id`='$eventID'";
    mysqli_query($con_public, $removeLinks);
    if (!empty($_POST["link"])) {
        for ($i=0; $i < count($_POST["link"]); $i++) { 
            $addLink = "INSERT INTO `event_link` (event_id, title, link) VALUES ('$eventID', ".valueCheck($_POST["linkTitle"][$i]).", ".valueCheck($_POST["link"][$i]).")";
            mysqli_query($con_public, $addLink);
        }
    }
    //cover
    if(!(empty($_FILES["cover"]["tmp_name"]))) {
        createEventCover($_FILES["cover"]["tmp_name"], $_FILES["cover"]["type"], 'img-t_'. substr(md5($eventID), 5));
    }

    if(!(empty($_POST["coverDel"]))) {
        $mask = '../../cdn/event/image/img-t_'. substr(md5($eventID), 5) ."*.*";
        array_map('unlink', glob($mask));
    }

    echo '<meta http-equiv="refresh" content="0; url=view?id='.$eventID.'">';
    
} else if (!empty($_POST["delete"])) {

    //remove cover
    if(file_exists('../../cdn/event/image/img-t_'. substr(md5($eventID), 5) .'.jpg')) {
        unlink('../../cdn/event/image/img-t_'. substr(md5($eventID), 5) .'.jpg');
    }

    //remove links
    $con_public->query("DELETE FROM `event_link` WHERE `event_id`='$eventID'");

    //remove event
    $con_public->query("DELETE FROM `event` WHERE `id`='$eventID'");
    $con_public->query("DELETE FROM `event_organizer` WHERE `event_id`='$eventID'");
    $con_public->query("DELETE FROM `event_calendar` WHERE `event_id`='$eventID'");

    echo '<meta http-equiv="refresh" content="0; url=../event">';


} else if (!empty($_POST["cancle"])) {
    echo '<meta http-equiv="refresh" content="0; url=view?id='.$eventID.'">';
}

// function value checker for null
function valueCheck($input)
{
    if($input == "") {
        $input = "NULL";
    } else {
        $input = "'". $input ."'";
    }
    return $input;
}

function valueCheckDate($input)
{
    if($input == "") {
        $input = "NULL";
    } else {
        $input = "'". date("Y-m-d H:i", strtotime($input)) ."'";
    }
    return $input;
}
?>