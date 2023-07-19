<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
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
    $tmp_gallery = checkInput($_REQUEST["id"]);
    $tmp_user = checkInput($_REQUEST["user"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $gallery = $con_public->query("SELECT * FROM gallery WHERE hash_id = $tmp_gallery AND owner = $tmp_user_id")->fetch_assoc();
    
    if(isset($gallery)) {

        $tmp_gallery_id = $gallery["id"];

        $getperm = $con_public->query("SELECT * FROM gallery_permission WHERE gallery_id = $tmp_gallery_id AND user_id = $tmp_user")->fetch_assoc();

        if(isset($getperm)) {
            if($getperm["permission"] != "view") {
                $con_public->query("UPDATE gallery_permission SET permission = 'view' WHERE gallery_id = $tmp_gallery_id AND user_id = $tmp_user");
                echo json_encode(array(
                    'status' => 'ok',
                    'action' => 'set',
                ));
            } else {
                $con_public->query("DELETE FROM gallery_permission WHERE gallery_id = $tmp_gallery_id AND user_id = $tmp_user");
                echo json_encode(array(
                    'status' => 'ok',
                    'action' => 'removed',
                ));
            }
        } else {
            $con_public->query("INSERT INTO gallery_permission (gallery_id, user_id, permission) VALUES ($tmp_gallery_id, $tmp_user, 'view')");
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
        'error' => 'gallery not set',
    ));
}
?>