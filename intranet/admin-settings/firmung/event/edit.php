<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
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
    <link rel="stylesheet" href="/admin-settings/firmung/event/css/edit.css">
                
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
            <form class="middle" method="POST">
                <h1>Aktion - <?php echo($event["title"]); ?> Bearbeiten</h1>

                <div class="informations">
                    <div class="single">
                        <h3>Allgemein</h3>
                        <span class="line-title"></span>
                        <p><b>Titel:</b> <input type="text" name="title" value="<?php echo($event["title"]); ?>"></p>
                        <p class="txtar"><b>Beschreibung:</b> <textarea name="description"><?php echo($event["description"]); ?></textarea></p>
                    </div>

                    <div class="single">
                        <?php
                        $eventType = $event["type"];
                        $eventType = "SELECT * FROM firmung_event_type WHERE firmung_id = '$firmungID'";
                        $eventType = $con_firmung->query($eventType);
                        ?>
                        <h3>Informationen</h3>
                        <span class="line-title"></span>
                        <p><b>Start:</b> <input type="datetime-local" name="start" value="<?php echo($event["start"]); ?>"></p>
                        <p><b>Ende:</b> <input type="datetime-local" name="end" value="<?php echo($event["end"]); ?>"></p>
                        <p><b>Ort:</b> <input type="text" name="location" value="<?php echo($event["location"]); ?>"></p>
                        <p><b>Platz:</b> <input type="number" name="max_space" value="<?php echo($event["max_space"]); ?>"> Personen</p>
                        <p><b>Art:</b> 
                        <select name="type">
                            <option value=""></option>
                            <?php
                            while ($type = $eventType->fetch_assoc()) {
                                $selected = "";
                                if($type["id"] == $event["type"]) {
                                    $selected = "selected";
                                }
                                echo '
                                <option value="'.$type["id"].'" '.$selected.'>'.$type["name"].'</option>
                                ';
                            }
                            ?>
                        </select>
                        </p>
                    </div>

                    <div class="single">
                        <h3>Firmbegleiter</h3>
                        <span class="line-title"></span>
                        <table>
                            <thead>
                                <tr>
                                    <th>Aktion</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $allFirmbegleiter = "SELECT * FROM firmung_team WHERE firmung_id = '$firmungID'";
                                $allFirmbegleiter = $con_firmung->query($allFirmbegleiter);

                                $allFirmbegleiterID = array();

                                while ($firmbegleiter = $allFirmbegleiter->fetch_assoc()) {
                                    array_push($allFirmbegleiterID, $firmbegleiter["user_id"]);
                                }
                                $allFirmbegleiterIDstr = implode("', '", $allFirmbegleiterID);
                                
                                $firmbegleiterAcc = "SELECT * FROM `accounts` WHERE id IN ('$allFirmbegleiterIDstr') ORDER BY lastname ASC";
                                $firmbegleiterAcc = $con->query($firmbegleiterAcc);


                                $firmbegleiterEvent = "SELECT * FROM firmung_event_firmbegleiter WHERE firmung_id = '$firmungID' AND event_id = '$eventID'";
                                $firmbegleiterEvent = $con_firmung->query($firmbegleiterEvent);

                                $firmbegleiterEventID = array();

                                while ($firmbegleiter = $firmbegleiterEvent->fetch_assoc()) {
                                    array_push($firmbegleiterEventID, $firmbegleiter["user_id"]);
                                }


                                while ($firmbegleiter = $firmbegleiterAcc->fetch_assoc()) {
                                    if(in_array($firmbegleiter["id"], $firmbegleiterEventID)) {
                                        $checked = "checked";
                                    } else {
                                        $checked = "";
                                    }

                                    echo '
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="firmbegleiter[]" value="'.$firmbegleiter["id"].'" '.$checked.'>
                                        </td>
                                        <td>
                                            <p>'.$firmbegleiter["lastname"].' '.$firmbegleiter["firstname"].'</p>
                                        </td>
                                    </tr>
                                    ';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="single">
                        <h3>Firmlinge</h3>
                        <span class="line-title"></span>
                        <table id="firmlinge">
                            <thead>
                                <tr>
                                    <th>Angemeldet</th>
                                    <th>Anwesend</th>
                                    <th>Name</th>
                                    <th>Notiz</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $firmlinge = "SELECT * FROM firmling WHERE firmung_id = '$firmungID'";
                                $firmlinge = $con_firmung->query($firmlinge);

                                $firmlingeEvent = "SELECT * FROM firmung_event_firmling WHERE firmung_id = '$firmungID' AND event_id = '$eventID'";
                                $firmlingeEvent = $con_firmung->query($firmlingeEvent);

                                while ($firmling = $firmlinge->fetch_assoc()) {
                                    $firmlingID = $firmling["id"];
                                    $firmlingEvent = "SELECT * FROM firmung_event_firmling WHERE firmung_id = '$firmungID' AND event_id = '$eventID' AND firmling_id = '$firmlingID'";
                                    $firmlingEvent = $con_firmung->query($firmlingEvent);
                                    $firmlingEvent = $firmlingEvent->fetch_assoc();

                                    if(isset($firmlingEvent)) {
                                        $checkedReg = "checked";
                                        $disabledReg = "";
                                    } else {
                                        $checkedReg = "";
                                        $disabledReg = "disabled";
                                    }

                                    if($firmlingEvent["finished"] == 1) {
                                        $checkedPre = "checked";
                                    } else {
                                        $checkedPre = "";
                                    }

                                    echo '
                                    <tr>
                                        <input type="hidden" name="firmlingeID[]" value="'.$firmling["id"].'">
                                        <td class="input">
                                            <input type="checkbox" name="firmlinge[]" value="'.$firmling["id"].'" '.$checkedReg.' onclick="checkRegistration(this)">
                                        </td>
                                        <td class="input">
                                            <input type="checkbox" name="present[]" value="'.$firmling["id"].'" '.$checkedPre.' '.$disabledReg.'>
                                        </td>
                                        <td>
                                            <p>'.$firmling["lastname"].' '.$firmling["firstname"].'</p>
                                        </td>
                                        <td>
                                            <textarea name="note[]" '.$disabledReg.'>'.$firmlingEvent["note"].'</textarea>
                                        </td>
                                    </tr>
                                    ';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="btn">
                    <input type="submit" name="quit" value="Abbrechen">
                    <input type="submit" name="delete" value="Löschen">
                    <input type="submit" name="submit" value="Speichern">
                </div>

            </form>
        </div>
        <script src="js/event-edit.js"></script>
    </div>

</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php
if(isset($_POST["quit"])) {
    echo '<meta http-equiv="refresh" content="0; url=view?year='.$firmung["year"].'&id='.$eventID.'">';
}

if(isset($_POST["submit"])) {
    $p_title = checkInput($_POST["title"]);
    $p_description = checkInput($_POST["description"]);
    $p_start = InputCheckDate($_POST["start"]);
    $p_end = InputCheckDate($_POST["end"]);
    $p_location = checkInput($_POST["location"]);
    $p_max_space = checkInput($_POST["max_space"]);
    $p_type = checkInput($_POST["type"]);

    //update event informations
    $con_firmung->query("UPDATE firmung_event SET `title` = $p_title, `description` = $p_description, `start` = $p_start, `end` = $p_end, `location` = $p_location, `type` = $p_type, `max_space` = $p_max_space WHERE id = $eventID");
    
    //update firmbegleiter
        # delete
        $eventFirmbegleiter = "SELECT * FROM firmung_event_firmbegleiter WHERE firmung_id = $firmungID AND event_id = $eventID";
        $eventFirmbegleiter = $con_firmung->query($eventFirmbegleiter);

        $firmbegleiterexists = array();
        $firmbegleiterdel = array();

        if(isset($_POST["firmbegleiter"])) {
            $firmbegleiterArray = $_POST["firmbegleiter"];
        } else {
            $firmbegleiterArray = array("");
        }
        
        while ($firmbegleiter = $eventFirmbegleiter->fetch_assoc()) {
            $firmbegleiterID = checkInput($firmbegleiter["user_id"]);
            array_push($firmbegleiterexists, $firmbegleiterID);
            if(!(in_array($firmbegleiter["id"], $firmbegleiterArray))) {
                array_push($firmbegleiterdel, $firmbegleiterID);
            }
        }

        $firmbegleiterdel = implode(", ", $firmbegleiterdel);
        $con_firmung->query("DELETE FROM firmung_event_firmbegleiter WHERE firmung_id = $firmungID AND event_id = $eventID AND user_id IN ($firmbegleiterdel)");


        # add
        foreach ($_POST["firmbegleiter"] as $firmbegleiter) {
            if(!in_array($firmbegleiter, $firmbegleiterexists)) {
                $firmbegleiterID = checkInput($firmbegleiter);
                $con_firmung->query("INSERT INTO firmung_event_firmbegleiter (firmung_id, event_id, user_id) VALUES ($firmungID, $eventID, $firmbegleiterID)");
            }
        }

    //update firmlinge
        # delete
        $eventFirmlinge = "SELECT * FROM firmung_event_firmling WHERE firmung_id = $firmungID AND event_id = $eventID";
        $eventFirmlinge = $con_firmung->query($eventFirmlinge);

        $firmlingexists = array();
        $firmlingdel = array();

        if(isset($_POST["firmlinge"])) {
            $firmlingArray = $_POST["firmlinge"];
        } else {
            $firmlingArray = array("");
        }
        
        while ($firmling = $eventFirmlinge->fetch_assoc()) {
            $firmlingID = checkInput($firmling["firmling_id"]);
            array_push($firmlingexists, $firmlingID);
            if(!(in_array($firmling["id"], $firmlingArray))) {
                array_push($firmlingdel, $firmlingID);
            }
        }

        $firmlingdel = implode(", ", $firmlingdel);
        $con_firmung->query("DELETE FROM firmung_event_firmling WHERE firmung_id = $firmungID AND event_id = $eventID AND firmling_id IN ($firmlingdel)");

        #add
        for ($i=0; $i < count($_POST["firmlinge"]); $i++) {
            //$_POST["firmlinge"][$i]
            $p_firmlingID = checkInput($_POST["firmlinge"][$i]);

            //check present
            $precentArray = array("");
            if(isset($_POST["present"])) {
                $precentArray = $_POST["present"];
            } else {
                $precentArray = array("");
            }

            if(in_array($_POST["firmlinge"][$i], $precentArray)) {
                $p_firmlingPresent = 1;
            } else {
                $p_firmlingPresent = 0;
            }

            //note
            $firmlingNotePlaceID = array_search($_POST["firmlinge"][$i], $_POST["firmlingeID"]);
            $p_firmlingNote = checkInput($_POST["note"][$firmlingNotePlaceID]);

            //firmling update / insert
            if(in_array($_POST["firmlinge"][$i], $firmlingexists)) {
                $con_firmung->query("UPDATE firmung_event_firmling SET finished = $p_firmlingPresent, note = $p_firmlingNote WHERE firmung_id = $firmungID AND event_id = $eventID AND firmling_id = $p_firmlingID");
            } else {
                $con_firmung->query("INSERT INTO firmung_event_firmling (firmung_id, event_id, firmling_id, finished, note) VALUES ($firmungID, $eventID, $p_firmlingID, $p_firmlingPresent, $p_firmlingNote)");
                echo $con_firmung->error;
            }
        }

    
    echo '<meta http-equiv="refresh" content="0; url=view?year='.$firmung["year"].'&id='.$eventID.'">';
    
}

if(isset($_POST["delete"])) {
    $con_firmung->query("DELETE FROM firmung_event WHERE id = $eventID AND firmung_id = $firmungID");
    $con_firmung->query("DELETE FROM firmung_event_firmbegleiter WHERE event_id = $eventID AND firmung_id = $firmungID");
    $con_firmung->query("DELETE FROM firmung_event_firmling WHERE event_id = $eventID AND firmung_id = $firmungID");

    echo '<meta http-equiv="refresh" content="0; url=/admin-settings/firmung/event?year='.$firmung["year"].'">';
}
?>

<?php
//function
function checkInput($input) {
    global $con_firmung;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con_firmung, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}

function InputCheckDate($input)
{
    if($input == "") {
        $input = "NULL";
    } else {
        $input = "'". date("Y-m-d H:i", strtotime($input)) ."'";
    }
    return $input;
}
?>