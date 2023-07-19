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

if(isset($_REQUEST["id"]) && isset($_REQUEST["note"])) {
    $tmp_folder = checkInput($_REQUEST["id"]);
    $tmp_note = checkInput($_REQUEST["note"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $folder = $con->query("SELECT * FROM notes_group WHERE id = $tmp_folder AND owner_id = $tmp_user_id")->fetch_assoc();
    if(isset($folder)) {
        $getnote = $con->query("SELECT * FROM notes_group_note WHERE group_id = $tmp_folder AND note_id = $tmp_note")->fetch_assoc();
        if(isset($getnote)) {
            $con->query("DELETE FROM notes_group_note WHERE group_id = $tmp_folder AND note_id = $tmp_note");
            echo json_encode(array(
                'status' => 'ok',
                'action' => 'remove',
            ));
        } else {
            $con->query("INSERT INTO notes_group_note (group_id, note_id) VALUES ($tmp_folder, $tmp_note)");
            echo json_encode(array(
                'status' => 'ok',
                'action' => 'add',
            ));
        }
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'no permission',
        ));
    }

} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'folder ID or note ID not set',
    ));
}
?>