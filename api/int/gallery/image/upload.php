<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/image/gallery.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: * https://'.$domain["intranet"]);
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Credentials: true');

//check permissions
if(!in_array('gallery', $dbSESSION_perm) && !in_array('jugendteam_admin', $dbSESSION_perm)) {
    http_response_code(403);
    exit();
}


//check if get is defined
if(!isset($_GET["g"])) {
    echo '{ "error": "gallery has not been defined", "status": "error" }';
    exit();
}

//get gallery
$gallery = mysqli_real_escape_string($con_public, $_GET["g"]);
$gallery = "SELECT * FROM gallery WHERE hash_id = '$gallery'";
$gallery = $con_public->query($gallery);
$gallery = $gallery->fetch_assoc();

//check if gallery exists
if(!isset($gallery)) {
    echo '{ "error": "gallery could not be found", "status": "error" }';
    exit();
}

for ($i=0; $i < count($_FILES["image"]["name"]); $i++) {
    $filename = date("Y-m-d_H-i-s", $_POST["image_lastmodify"][$i]).'_'.pathinfo($_FILES['image']['name'][$i])['filename']."-".$i.substr(md5(date("Y-m-d h:m:i")) , 0, 5);
    createImage($_FILES['image']['tmp_name'][$i],
                $_FILES['image']['type'][$i],
                $filename,
                $gallery["hash_id"]);

    echo '{ "status": "ok", "message": "'.$_FILES['image']['name'][$i].' has been successfully uploaded.", "new_name": "'.$filename.'" }';
}
?>