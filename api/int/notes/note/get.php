<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/functions/input.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
# header('Access-Control-Allow-Origin: https://'.$domain["intranet"]);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Credentials: true');

//check permissions
if(!isset($dbSESSION)) {
    http_response_code(403);
    exit();
}

if(isset($_REQUEST["id"])) {
    $tmp_note = checkInput($_REQUEST["id"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $note = $con->query("SELECT * FROM `notes` WHERE 
                        id = $tmp_note AND
                        (owner_id = $tmp_user_id OR
                        id IN (SELECT note_id FROM `notes_permission` WHERE note_id = $tmp_note AND user_id = $tmp_user_id))")->fetch_assoc();
    
    $owner = checkInput($note["owner_id"]);
    $owner = $con->query("SELECT id, firstname, lastname, username FROM accounts WHERE id = $owner")->fetch_assoc();

    if($dbSESSION["user_id"] == $note["owner_id"]) {
        $tmp_permission = 'owner';
    } else {
        $tmp_permission = $con->query("SELECT * FROM notes_permission WHERE note_id = $tmp_note AND user_id = $tmp_user_id")->fetch_assoc();
        $tmp_permission = $tmp_permission["permission"];
    }

    if(isset($note)) {
        echo json_encode(array(
            'id' => $note["id"],
            'title' => $note["title"],
            'owner' => $owner["username"],
            'permission' => $tmp_permission,
            'createdAt' => $note["created_at"],
            'lastChange' => $note["last_change"],
            'text' => $note["text"],
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