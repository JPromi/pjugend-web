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
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Credentials: true');

//check permissions
if(!in_array('gallery', $dbSESSION_perm) && !in_array('jugendteam_admin', $dbSESSION_perm)) {
    http_response_code(403);
    exit();
}

if(isset($_REQUEST["id"])) {
    $tmp_gallery = checkInput($_REQUEST["id"]);
    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $gallery = $con_public->query("SELECT * FROM gallery WHERE hash_id = $tmp_gallery AND owner = $tmp_user_id")->fetch_assoc();

    if(isset($gallery)) {
            $tmp_gallery_id = checkInput($gallery["id"]);

            $folder = $_SERVER["DOCUMENT_ROOT"].'/../cdn/gallery/'.$_REQUEST["id"];
 
            //delete folder
            system("rm -rf ".escapeshellarg($folder));

            //delete from database
            $con_public->query("DELETE FROM gallery WHERE id = $tmp_gallery_id");
            $con_public->query("DELETE FROM gallery_permission WHERE gallery_id = $tmp_gallery_id");

            echo json_encode(array(
                'status' => 'ok',
            ));
        
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'gallery not found',
        ));
    }


} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'gallery ID or image not set',
    ));
}
?>