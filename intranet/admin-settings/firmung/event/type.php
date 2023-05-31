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
    <title>Firmung <?php echo($firmung["year"]) ?> Aktion Art - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/firmung/event/css/type.css">
                
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
                <h1>Aktions Arten</h1>
                <p>Verschiedenen Varianten der Aktionen</p>
                <div class="btn top">
                    <?php
                    if(!isset($_GET["edit"])) {
                    ?>
                        <a href="type?year=<?php echo($firmung["year"]) ?>&edit" title="Bearbeiten">
                            <span class="material-symbols-outlined">
                            edit
                            </span>
                        </a>
                    <?php
                    }
                    ?>
                </div>

                <?php
                if(isset($_GET["edit"])) {
                // edit mode
                ?>
                <div class="types edit">
                    <div id="types">
                        <?php
                        $allTypes = "SELECT * FROM firmung_event_type WHERE firmung_id = $firmungID";
                        $allTypes = $con_firmung->query($allTypes);

                        $typeCounter = 0;

                        while ($type = $allTypes->fetch_assoc()) {
                            $typeCounter++;
                            echo '
                            <div class="single" id="'.$typeCounter.'">
                                <input type="hidden" name="typeID[]" value="'.$type["id"].'">
                                <input type="text" placeholder="Titel" name="type[]" value="'.$type["name"].'">
                                <a href="javascript:void(0)" onclick="removeType('.$typeCounter.')">
                                    <span class="material-symbols-outlined">
                                    delete
                                    </span>
                                </a>
                            </div>
                            ';
                        }
                        ?>
                    </div>
                    
                    <div class="single add" onclick="newType()">
                        <p>
                            <span class="material-symbols-outlined">
                            add
                            </span>
                            Neue Aktionsart Hinzufügen
                        </p>
                    </div>
                </div>

                <div class="btn form">
                    <input type="submit" name="quit" value="Abbrechen">
                    <input type="submit" name="submit" value="Speichern">
                </div>
                <script src="js/type-edit.js"></script>
                <?php
                } else {
                // view mode
                ?>
                <div class="types">
                    <?php
                    $allTypes = "SELECT * FROM firmung_event_type WHERE firmung_id = $firmungID";
                    $allTypes = $con_firmung->query($allTypes);

                    while ($type = $allTypes->fetch_assoc()) {
                        echo '
                        <h3 class="single">'.$type["name"].'</h3>
                        ';
                    }
                    ?>
                </div>
                <?php
                }
                ?>

                

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
if(isset($_POST["quit"])) {
    echo '<meta http-equiv="refresh" content="0; url=/admin-settings/firmung/event?year='.$firmung["year"].'">';
}

if(isset($_POST["submit"])) {

    //delete
    $eventTypes = "SELECT * FROM firmung_event_type WHERE firmung_id = $firmungID";
    $eventTypes = $con_firmung->query($eventTypes);

    while ($eventType = $eventTypes->fetch_assoc()) {
        if(!(in_array($eventType["id"], $_POST["typeID"]))) {
            $typeID = checkInput($eventType["id"]);
            $con_firmung->query("DELETE FROM firmung_event_type WHERE firmung_id = $firmungID AND id = $typeID");
        }
    }

    //update / insert
    for ($i=0; $i < count($_POST["typeID"]); $i++) {
        $typeID = checkInput($_POST["typeID"][$i]);
        $type = checkInput($_POST["type"][$i]);
        if(!empty($_POST["typeID"][$i])) {
            //update
            $con_firmung->query("UPDATE firmung_event_type SET `name` = $type WHERE firmung_id = $firmungID AND id = $typeID");
        } else {
            //insert
            $con_firmung->query("INSERT INTO firmung_event_type (firmung_id, name) VALUES ($firmungID, $type)");
        }
    }

    echo '<meta http-equiv="refresh" content="0; url=type?year='.$firmung["year"].'">';
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