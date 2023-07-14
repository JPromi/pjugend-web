<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/functions/input.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
# header('Access-Control-Allow-Origin: https://'.$domain["intranet"]);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Credentials: true');

//check permissions
if(!isset($dbSESSION)) {
    http_response_code(403);
    exit();
}

if(isset($_REQUEST["id"])) {
    $tmp_folder = checkInput($_REQUEST["id"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);
    $getfolder = $con->query("SELECT * FROM notes_group WHERE id = $tmp_folder AND owner_id = $tmp_user_id")->fetch_assoc();
    if(isset($getfolder)) {
        $con->query("DELETE FROM notes_group WHERE id = $tmp_folder");
        $con->query("DELETE FROM notes_group_note WHERE group_id = $tmp_folder");
        echo json_encode(array(
            'status' => 'ok',
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'folder not found',
        ));
    }
} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'name not set',
    ));
}
?>