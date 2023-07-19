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
    $tmp_note = checkInput($_REQUEST["id"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);
    $note = $con->query("SELECT * FROM notes WHERE owner_id = $tmp_user_id AND id = $tmp_note")->fetch_assoc();

    if(isset($note)) {
        $con->query("DELETE FROM notes WHERE owner_id = $tmp_user_id AND id = $tmp_note");
        $con->query("DELETE FROM notes_permission WHERE note_id = $tmp_note");
        $con->query("DELETE FROM notes_group_note WHERE note_id = $tmp_note");

        echo json_encode(array(
            'status' => 'ok',
        ));   
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'note not found',
        ));    
    }


} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'note ID not set',
    ));    
}
?>