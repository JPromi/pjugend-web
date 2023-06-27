<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://'.$domain["intranet"]);
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Credentials: true');

//all
if(empty($_GET)) {
    $team = $con_public->query("SELECT id, name, description, focus, email FROM team WHERE `disabled` = '0'");
    $teamJSON = array();

    while ($entry = $team->fetch_assoc()) {
        array_push($teamJSON, json_encode($entry));
    }

    echo '['.implode(",", $teamJSON).']';
    exit();
}

//single user
if(isset($_GET["user"])) {
    $team = $con_public->query("SELECT id, name, description, focus, email FROM team WHERE `disabled` = '0' AND user_id = ".checkInput($_GET["user"]))->fetch_assoc();
    if(isset($team)) {
        echo json_encode($team);
    } else {
        echo '{"error":"entry could not be found"}';
    }
    exit();
}


//functions
function checkInput($input) {
    global $con;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}
?>