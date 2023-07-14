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
    $tmp_folder = checkInput($_REQUEST["id"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);
    $all_notes = array();

    $folder = $con->query("SELECT * FROM `notes_group` WHERE id = $tmp_folder AND owner_id = $tmp_user_id")->fetch_assoc();
    $notes = $con->query("SELECT * FROM `notes` WHERE 
                        id IN (SELECT note_id FROM `notes_group_note` WHERE group_id = $tmp_folder) AND
                        (id IN (SELECT user_id FROM `notes_permission` WHERE note_id = $tmp_folder) OR
                        owner_id = $tmp_user_id)
                        ORDER BY `last_change`")->fetch_all();

    foreach ($notes as $note) {
        array_push($all_notes, array(
            'noteId' => $note[0],
            'title' => $note[1],
            'shortText' => substr(str_replace(array("\r", "\n", "  "), '', strip_tags($note[2])), 0, 50),
        ));
    }

    if(isset($folder)) {
        echo json_encode(array(
            'id' => $folder["id"],
            'name' => $folder["name"],
            'notes' => $all_notes,
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
        'error' => 'folder ID not set',
    ));
}
?>