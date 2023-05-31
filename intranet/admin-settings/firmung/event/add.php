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
                <h1>Neue Aktion Erstellen</h1>

                <div class="informations">
                    <div class="single">
                        <h3>Allgemein</h3>
                        <span class="line-title"></span>
                        <p><b>Titel:</b> <input type="text" name="title"></p>
                        <p class="txtar"><b>Beschreibung:</b> <textarea name="description"></textarea></p>
                    </div>

                    <div class="single">
                        <?php
                        $eventType = $event["type"];
                        $eventType = "SELECT * FROM firmung_event_type WHERE firmung_id = '$firmungID'";
                        $eventType = $con_firmung->query($eventType);
                        ?>
                        <h3>Informationen</h3>
                        <span class="line-title"></span>
                        <p><b>Start:</b> <input type="datetime-local" name="start"></p>
                        <p><b>Ende:</b> <input type="datetime-local" name="end"></p>
                        <p><b>Ort:</b> <input type="text" name="location"></p>
                        <p><b>Platz:</b> <input type="number" name="max_space"> Personen</p>
                        <p><b>Art:</b> 
                        <select name="type">
                            <option value=""></option>
                            <?php
                            while ($type = $eventType->fetch_assoc()) {
                                echo '
                                <option value="'.$type["id"].'">'.$type["name"].'</option>
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
                                while ($firmbegleiter = $firmbegleiterAcc->fetch_assoc()) {
                                    

                                    echo '
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="firmbegleiter[]" value="'.$firmbegleiter["id"].'">
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

                </div>

                <div class="btn">
                    <input type="submit" name="submit" value="Erstellen">
                </div>

            </form>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php
if(isset($_POST["submit"])) {
    //debug
    echo json_encode($_POST);

    //convert post
    $p_title = checkInput($_POST["title"]);
    $p_description = checkInput($_POST["description"]);
    $p_start = InputCheckDate($_POST["start"]);
    $p_end = InputCheckDate($_POST["end"]);
    $p_location = checkInput($_POST["location"]);
    $p_max_space = checkInput($_POST["max_space"]);
    $p_type = checkInput($_POST["type"]);

    //insert in table
    $con_firmung->query("INSERT INTO firmung_event (firmung_id, `title`, `description`, `start`, `end`, `location`, `type`, `max_space`) VALUES ('$firmungID', $p_title, $p_description, $p_start, $p_end, $p_location, $p_type, $p_max_space)");

    $eventID = $con_firmung->insert_id;

    //insert firmbegleiter
    foreach ($_POST["firmbegleiter"] as $firmbegleiter) {
        $firmbegleiterID = checkInput($firmbegleiter);
        $con_firmung->query("INSERT INTO firmung_event_firmbegleiter (firmung_id, event_id, user_id) VALUES ($firmbegleiterID, $eventID, $firmbegleiterID)");
        echo $con_firmung->error;
    }
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