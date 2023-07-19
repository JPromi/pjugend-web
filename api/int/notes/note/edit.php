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

$tmp_date = date("Y-m-d H:i:s");

if(isset($_REQUEST["id"])) {
    $tmp_note = checkInput($_REQUEST["id"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);


    $note = $con->query("SELECT * FROM `notes` WHERE 
        id = $tmp_note AND
        (owner_id = $tmp_user_id OR
        id IN 
            (SELECT note_id FROM `notes_permission` WHERE
            note_id = $tmp_note AND 
            user_id = $tmp_user_id AND 
            permission = 'write'))
        ")->fetch_assoc();

    if(isset($_REQUEST["title"])) {
        $tmp_title = checkInput($_REQUEST["title"]);
    } else {
        $tmp_title = checkInput($note["title"]);
    }

    if(isset($_REQUEST["text"])) {
        $tmp_text = checkTextInput($_REQUEST["text"]);
    } else {
        $tmp_text = checkTextInput($note["text"]);
    }

    if(isset($note)) {
        $con->query("UPDATE notes SET `title` = $tmp_title, `text` = $tmp_text, last_change = '$tmp_date' WHERE id = $tmp_note");
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
    $tmp_user_id = checkInput($dbSESSION["user_id"]);
    $tmp_text = checkTextInput($_REQUEST["text"]);
    $tmp_title = checkInput($_REQUEST["title"]);

    $con->query("INSERT INTO notes (title, text, owner_id, last_change, created_at) VALUES ($tmp_title, $tmp_text, $tmp_user_id, '$tmp_date', '$tmp_date')");
    $tmp_note_id = $con->insert_id;
    if($tmp_note_id == 0) {
        echo json_encode(array(
            'status' => 'error',
        ));
    } else {
        echo json_encode(array(
            'status' => 'ok',
            'noteId' => $tmp_note_id,
        ));
    }
    
}
?>