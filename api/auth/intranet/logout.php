<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: * ');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Credentials: true');

if(isset($_REQUEST["SESSION_ID"])) {
    $coockie_hash = stripslashes($_REQUEST['SESSION_ID']);
    $coockie_hash = mysqli_real_escape_string($con, $coockie_hash);

    $con->query("DELETE FROM `session` WHERE coockie_hash = '$coockie_hash'");

    echo '{"status":"session removed"}';
} else {
    echo '{"error":"no SESSION_ID set"}';
}
?>