<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");

include("../../private/intranet/image/event_cover.php");
?>

<?php
if(!(in_array("event", $dbSESSION_perm))) {
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
    <title>Veranstaltung Erstellen -  <?php echo($conf_title["intranet"]); ?></title>

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


                <div class="alertbox organizer disabled" id="organizer">
                    <h1>Veranstalter Erstellen</h1>
                    <div class="list">
                        <?php
                        $user = "SELECT id, firstname, lastname FROM `accounts` ORDER BY firstname";
                        $user = $con_new->query($user);
                        while ($suser = $user->fetch_assoc()) {
                            $isOrganizer = "";
                            if($suser["id"] == $dbSESSION["user_id"]) {
                                $isOrganizer = "checked";
                            }
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

                    <img src="https://<?php echo($domain["cdn"]); ?>/event/placeholder/image.png" id="cover" data-original-file="https://<?php echo($domain["cdn"]); ?>/event/placeholder/image.png">

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
                    <h6>Zeit: </h6>
                    <div class="single">
                        <!--<input type="datetime-local" name="date_from">
                        <p> - </p>
                        <input type="datetime-local" name="date_to">-->

                        <a onclick="alertadd('date')">
                            <span class="material-symbols-outlined">
                            edit
                            </span>
                        </a>

                    </div>


                        <h6>Ort: </h6>
                        <input type="text" name="location">



                        <h6>Alter: </h6>
                        <div class="single">
                            <input type="number" name="age_from">
                            <p> - </p>
                            <input type="number" name="age_to">
                        </div>

                        <h6>Kosten: </h6>
                        <div class="single">
                            <input type="number" name="costs">
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
                <input type="text" name="title" class="title">
                <textarea class="description" name="description"><?php
                    echo($event["description"]);
                ?></textarea>
                <div class="links" id="links">
                    <h3>Links</h3>
                    <?php
                    for ($i=1; $i < 4; $i++) { 
                        echo '
                            <div class="single" id="link'.$i.'">
                                <a onclick="removeLink('.$i.')" class="removeLink">
                                    <span class="material-symbols-outlined">
                                    remove
                                    </span>
                                </a>
                                <input type="text" placeholder="Titel" name="linkTitle[]">
                                <input type="text" placeholder="Link" name="link[]" class="link">
                            </div>
                        ';
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
    $Ptitle = valueCheck(htmlspecialchars($_POST["title"]));
    $Pdescription = valueCheck(htmlspecialchars($_POST["description"]));

    $Pdate_from = valueCheckDate($_POST["date_from"]);
    $Pdate_to = valueCheckDate($_POST["date_to"]);
    
    $Page_from = valueCheck($_POST["age_from"]);
    $Page_to =  valueCheck($_POST["age_to"]);
    $Plocation = valueCheck($_POST["location"]);
    $Pcosts = valueCheck($_POST["costs"]);
    //$Pprice = $_POST["price"];
    //$Pspec_group = $_POST["only_specific_group"];
    $Porganizer = implode(";", $_POST["organizer"]);

    $addEvent = "INSERT INTO `event`    (title, description, age_from, age_to, location, organizer, price) VALUES
                                        ($Ptitle, $Pdescription, $Page_from, $Page_to, $Plocation, $Porganizer, $Pcosts)";
    $con_public->query($addEvent);

    $eventID = $con_public->insert_id;

    for ($i=0; $i < count($_POST["date_id"]); $i++) { 
            $P_date_start = valueCheckDate($_POST["date_start"][$i]);
            $P_date_end = valueCheckDate($_POST["date_end"][$i]);

            $con_public->query("INSERT INTO `event_calendar` (event_id, `start`, `end`) VALUES ('$eventID', $P_date_start, $P_date_end)");
    }

    //links
    for ($i=0; $i < count($_POST["link"]); $i++) {
        if(!(empty($_POST["linkTitle"][$i]) || empty($_POST["link"][$i]))) {
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