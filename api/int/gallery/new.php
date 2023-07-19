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

if(isset($_REQUEST["name"])) {
    $tmp_name = checkInput($_REQUEST["name"]);
    $tmp_description = checkInput($_REQUEST["description"]);
    $tmp_public = checkInput($_REQUEST["public"]);
    $tmp_public_view = checkInput($_REQUEST["public_view"]);
    $tmp_password = checkInput($_REQUEST["password"]);
    $hashID = "g-".bin2hex(random_bytes(5)).$dbSESSION["user_id"].substr(md5(date("Y-m-d h:m:i")) , 0, 5); 

    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $con_public->query("INSERT INTO gallery (title, description, hash_id, owner, public, public_view, password) VALUES ($tmp_name, $tmp_description, '$hashID', $tmp_user_id, $tmp_public, $tmp_public_view, $tmp_password)");
    if(!$con_public->error) {
        mkdir($_SERVER["DOCUMENT_ROOT"]."/../cdn/gallery/".$hashID);
        mkdir($_SERVER["DOCUMENT_ROOT"]."/../cdn/gallery/".$hashID.'/thumbnail');
        mkdir($_SERVER["DOCUMENT_ROOT"]."/../cdn/gallery/".$hashID.'/images');
        mkdir($_SERVER["DOCUMENT_ROOT"]."/../cdn/gallery/".$hashID.'/original');

        echo json_encode(array(
            'status' => 'ok',
            'id' => $hashID,
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'something went wrong',
            'sql' => $con_public->error,
        ));
    }

} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'gallery ID or image not set',
    ));
}
?>