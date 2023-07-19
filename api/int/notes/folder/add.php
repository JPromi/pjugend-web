<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/functions/input.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
# header('Access-Control-Allow-Origin: https://'.$domain["intranet"]);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');

//check permissions
if(!isset($dbSESSION)) {
    http_response_code(403);
    exit();
}

if(isset($_REQUEST["name"])) {
    $tmp_name = checkInput($_REQUEST["name"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);
    $con->query("INSERT INTO `notes_group` (owner_id, name) VALUES ($tmp_user_id, $tmp_name)");

    $tmp_folder_id = $con->insert_id;
    if($tmp_folder_id !== 0) {
        echo json_encode(array(
            'status' => 'ok',
            'folderId' => $tmp_folder_id,
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'insert error',
        ));
    }
} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'name not set',
    ));
}
?>