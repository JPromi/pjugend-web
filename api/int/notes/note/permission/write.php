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

if(isset($_REQUEST["id"]) && isset($_REQUEST["user"])) {
    $tmp_note = checkInput($_REQUEST["id"]);
    $tmp_user = checkInput($_REQUEST["user"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $note = $con->query("SELECT * FROM notes WHERE id = $tmp_note AND owner_id = $tmp_user_id")->fetch_assoc();
    
    if(isset($note)) {

        $getperm = $con->query("SELECT * FROM notes_permission WHERE note_id = $tmp_note AND user_id = $tmp_user")->fetch_assoc();

        if(isset($getperm)) {
            if($getperm["permission"] == "read") {
                $con->query("UPDATE notes_permission SET permission = 'write' WHERE note_id = $tmp_note AND user_id = $tmp_user");
                echo json_encode(array(
                    'status' => 'ok',
                    'action' => 'set',
                ));
            } else {
                $con->query("DELETE FROM notes_permission WHERE note_id = $tmp_note AND user_id = $tmp_user");
                echo json_encode(array(
                    'status' => 'ok',
                    'action' => 'removed',
                ));
            }
        } else {
            $con->query("INSERT INTO notes_permission (note_id, user_id, permission) VALUES ($tmp_note, $tmp_user, 'write')");
            echo json_encode(array(
                'status' => 'ok',
                'action' => 'set',
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