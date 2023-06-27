<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: * ');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Credentials: true');

if(isset($_GET["SESSION_ID"])) {
    $coockie_hash = stripslashes($_GET['SESSION_ID']);
    $coockie_hash = mysqli_real_escape_string($con, $coockie_hash);

    $session = $con->query("SELECT * FROM `session` WHERE `cookie_hash` = '$coockie_hash'")->fetch_assoc();
    
    if(isset($session)) {
        echo json_encode($session);
    } else {
        echo '{"error":"SESSION_ID not found"}';
    }
} else {
    echo '{"error":"no SESSION_ID set"}';
}
?>